<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class lembur extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('ceksession','global_lib'));
		$this->ceksession->login();

		$this->global_lib = new global_lib;
	}

	public function index()
	{
		$sess = $this->session->userdata('user');
        if (!$sess['akses']) {
        	redirect(base_url());exit();
        }
		$parrent = $sess['id_user'];

		$data['data_cuti'] = $this->Db_select->query_all('select *from tb_user a join tb_lembur b on a.user_id = b.user_id  where id_parent = '.$parrent.'');

		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/lembur');
		$this->load->view('SEKDA/footer');
	}

	public function select()
	{
		$where['id_lembur'] = $this->input->post('id');

        $data = $this->Db_select->query('select *from tb_user a join tb_lembur b on a.user_id = b.user_id where b.id_lembur = '.$where['id_lembur'].'');

        if ($data) {
            $result['status'] = true;
            $result['message'] = 'Data ditemukan.';
            $result['data'] = $data;
        } else {
            $result['status'] = false;
            $result['message'] = 'Data tidak ditemukan.';
            $result['data'] = array();
        }

        echo json_encode($result);
	}

	public function insert()
	{
		$data['user_id'] = $this->input->post('user_id');
		$data['tanggal_lembur'] = date("Y-m-d", strtotime($this->input->post('tanggal_lembur')));
		$data['lama_lembur'] = $this->input->post('lama_lembur');

		$data_pengajuan=$this->Db_select->query('select *from tb_pengajuan where status_approval = 1 and user_id = "'.$data['user_id'].'" and "'.$data['tanggal_lembur'].'" >= tanggal_awal_pengajuan and "'.$data['tanggal_lembur'].'" <= tanggal_akhir_pengajuan ');
		if (!$data_pengajuan) {
			$insert = $this->Db_dml->normal_insert('tb_lembur', $data);
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan, pegawai sedang dalam masa data_cuti.';
            $result['data'] = array();
			echo json_encode($result); exit();
		}

		if ($insert == 1) {
			$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}
		echo json_encode($result);
	}

	public function update()
	{
		$where['id_lembur'] = $this->input->post('id_lembur');
		$data['status'] = $this->input->post('status');
		
		if ($data['status'] == 1) {
			$upahLembur = $this->payrollLembur($this->input->post('id_lembur'));
			$data['upah_lembur'] = $upahLembur;
		}

		$updateData = $this->Db_dml->update('tb_lembur', $data, $where);
		if ($updateData == 1) {
			/* get data user */
			$getUser = $this->Db_select->query('select b.* from tb_lembur a join tb_user b on a.user_id = b.user_id where a.id_lembur = "'.$this->input->post('id_lembur').'"');

			// SEND NOTIFIKASI MELALUI FCM
			if ($data['status'] == 1) {
				$message = "Pengajuan Lembur Anda Telah Disetujui";
			}else{
				$message = "Pengajuan Lembur Anda Ditolak";
			}
			$this->global_lib->NEWsendFCM('Approval Pengajuan Lembur', $message, $getUser->user_id,'','pengajuan','lembur');

			$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = $data;
        } else {
            $result['status'] = false;
            $result['message'] = 'Data gagal diubah.';
            $result['data'] = array();
        }
        echo json_encode($result);
	}

	public function payrollLembur($idLembur)
    {
        if (is_numeric($idLembur)) {
            /* get data lembur */
			$getLembur = $this->Db_select->query('select a.id_lembur, a.lama_lembur, a.tanggal_lembur, a.user_id, c.id_channel from tb_lembur a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where a.id_lembur = "'.$idLembur.'"');
            
            if ($getLembur) {
				/* check tanggal libur atau bukan */
                $tanggalLembur = mdate("%Y-%m-%d", strtotime($getLembur->tanggal_lembur));
                
                /* get hari kerja */
                $jadwal = $this->getPolaKerja($getLembur->user_id, $tanggalLembur, $getLembur->id_channel)['pola'];
				
                /* get data pengaturan lembur */
                $lemburSetting = $this->Db_select->select_where('tb_komponen_lembur', 'id_channel = "'.$getLembur->id_channel.'"');
				
                if ($lemburSetting) {
                    if ($lemburSetting->is_custom) {
                        /* ambil perhitungan lembur custom */
                        $upahLembur = $this->perhitunganCustom($getLembur->lama_lembur, $getLembur->user_id, $lemburSetting->nominal);
                    }else{
						/* check tanggal merah */
                        if($this->tanggalMerah($tanggalLembur, $getLembur->id_channel, $getLembur->user_id)){
							/* ambil perhitungan lembur di hari libur */
                            $upahLembur = $this->perhitunganLibur($jadwal->lama_hari_libur, $getLembur->lama_lembur, $getLembur->user_id);
                        }else{
							/* ambil perhitungan lembur di hari kerja */
                            $upahLembur = $this->perhitungan($getLembur->lama_lembur, $getLembur->user_id);
                        }
					}
					
					return $upahLembur;
                }
            }
        }
	}

	public function getPolaKerja($userId, $tanggal, $idChannel)
    {
		$cekPolaUser = $this->Db_select->query('select *from tb_pola_user where user_id = "'.$userId.'" and "'.$tanggal.'" between start_pola_kerja and end_pola_kerja');

        if ($cekPolaUser) {
            $getPola = $this->Db_select->select_where('tb_pola_kerja','id_pola_kerja = "'.$cekPolaUser->id_pola_kerja.'"');
            $file = './appconfig/new/'.$getPola->file_pola_kerja;
        }else{
            $where['id_channel'] = $idChannel;
            $where['is_default'] = 1;
            $getPola = $this->Db_select->select_where('tb_pola_kerja', $where);
            $file = './appconfig/new/'.$getPola->file_pola_kerja;
        }

        $jadwal['jadwal'] = json_decode(file_get_contents($file))->jam_kerja;
		$jadwal['pola'] = $getPola;

        return $jadwal;
	}
	
	public function tanggalMerah($tanggal, $idChannel, $userId)
    {
        $cek_libur = $this->Db_select->query('select *from tb_event where date_format(tanggal_event, "%Y-%m-%d") = "'.$tanggal.'" and id_channel = "'.$idChannel.'"');
        if ($cek_libur) {
            $isLibur = 1;
        }else{
            $isLibur = 0;
        }

        $jadwal = $this->getPolaKerja($userId, $tanggal, $idChannel)['jadwal'];
        $day = strtolower(date("l", strtotime($tanggal)));
        $jadwal = $jadwal->$day;

        if ($jadwal->libur) {
            $isLibur = 1;
        }else{
            $isLibur = 0;
        }

        if ($isLibur) {
            return true;
        }else{
            return false;
        }
	}
	
	public function perhitunganCustom($lama_lembur, $user_id, $nominal)
	{
		$totalJumlah = $lama_lembur*$nominal;
		
		return $totalJumlah;
	}

    public function perhitunganLibur($hariKerja, $lamaLembur, $userId)
    {
        /* hitung upah lembur perjam */
        $upahPerJam = $this->hitungUpah($userId);
        $totalJumlah = 0;
        /* start perhitungan lembur di hari libur */
        if ($hariKerja == 5) {
            /* Untuk perusahaan dengan 5 hari kerja, rate adalah 2x upah sejam untuk 8 jam pertama, 3x upah sejam untuk jam ke-9, dan 4x upah sejam untuk jam ke-10 dan ke-11 */
            $countLama = 1;
            for ($i=0; $i < $lamaLembur ; $i++) { 
                if ($countLama <= 8) {
                    /* 2x */
                    $hitung = floor(2*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }elseif($countLama == 9){
                    /* 3x */
                    $hitung = floor(3*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }elseif ($countLama >= 10) {
                    /* 4x */
                    $hitung = floor(3*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }
                $countLama++;
            }
        }elseif ($hariKerja > 5) {
            /* Untuk perusahaan dengan 6 hari kerja, rate adalah 2x upah sejam untuk 7 jam pertama, 3x upah sejam untuk jam ke-8, dan 4x upah sejam untuk jam ke-9 dan ke-10 */
            $countLama = 1;
            for ($i=0; $i < $lamaLembur ; $i++) { 
                if ($countLama <= 7) {
                    /* 2x */
                    $hitung = floor(2*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }elseif($countLama == 8){
                    /* 3x */
                    $hitung = floor(3*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }elseif ($countLama >= 9) {
                    /* 4x */
                    $hitung = floor(4*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }
                $countLama++;
            }
        }

        return $totalJumlah;
	}
	
	public function hitungUpah($userId)
    {
		$getUser = $this->Db_select->select_where('tb_user', 'user_id = "'.$userId.'"');
		
        if ($getUser) {
			/* hitung upah sejam gapok x 1/173 */
            $upahLembur = floor($getUser->gaji_pokok*1/173);

            return $upahLembur;
        }else{
            return 0;
        }
    }

	public function delete()
	{
		$where['user_id'] = $this->input->post('user_id');

		$delete = $this->Db_dml->delete('tb_lembur', $where);

		if ($delete == 1) {
			$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}
		echo json_encode($result);
	}
}