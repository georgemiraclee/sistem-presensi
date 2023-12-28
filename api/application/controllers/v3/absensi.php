<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class absensi extends CI_Controller
{
	private $token = 'acb00107158d8d08c1492c67b1676c15';
	private $file = '../appconfig/6_auto_respon.txt';

	function __construct()
	{
		parent::__construct();
        $this->load->library(array('global_lib','loghistory'));

        $this->loghistory = new loghistory;

        $this->result = array(
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null
        );

        if ($this->input->post('token') != $this->token) {
        	$this->result = array(
	            'status' => false,
	            'message' => 'Akses ditolak.',
	            'data' => null
	        );

	        echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
        }
	}

	public function getData()
	{
		$getAbsensi = $this->db_select->query_all('select b.nama_user, b.nip, a.waktu_datang as jam_masuk, a.waktu_istirahat as jam_istirahat, a.waktu_kembali as jam_kembali, a.waktu_pulang as jam_pulang, a.status_absensi from tb_absensi a join tb_user b on a.user_id = b.user_id where date_format(created_absensi, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")');

		if ($getAbsensi) {
            foreach ($getAbsensi as $key => $value) {
                $value->nip = (string) $value->nip;
            }
            var_dump($getAbsensi);
			$this->result = array(
	            'status' => true,
	            'message' => 'Data ditemukan',
	            'data' => $getAbsensi
	        );
		}else{
			$this->result = array(
	            'status' => false,
	            'message' => 'Data tidak ditemukan',
	            'data' => null
	        );
		}
		echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}

	public function fingerPrint()
	{
		$require = array('nip');
        $this->global_lib->input($require);

        if (is_numeric($this->input->post('nip'))) {
        	$tanggal_hari_ini = mdate("%Y-%m-%d", time());
        	$cek_libur = $this->db_select->query('select *from tb_event where date_format(tanggal_event, "%Y-%m-%d") = "'.$tanggal_hari_ini.'"');
            if ($cek_libur) {
                $this->result = array(
                    'status' => false,
                    'message' => 'Maaf anda tidak dapat absen pada hari libur.',
                    'data' => null
                );

                $this->loghistory->createLog($this->result);
                echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
            }

            $getUser = $this->db_select->select_where('tb_user', 'nip = "'.$this->input->post('nip').'"');
            $cek = $this->db_select->select_where('tb_absensi', 'user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
            if ($cek) {
            	if (isset($cek->waktu_datang) && isset($cek->waktu_pulang)) {
            		$this->result = array(
                        'status' => false,
                        'message' => 'Anda sudah melakukan absensi',
                        'data' => null
                    );
            	}else{
            		if ($cek->waktu_datang != null || $cek->waktu_datang != '') {
            			$update['waktu_pulang'] = mdate("%Y-%m-%d %H:%i:%s", time());

            			$update_absen = $this->db_dml->update('tb_absensi', $update, 'user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
            			if ($update_absen == 1) {
                            $sess = array(
                                'id_absensi' => $cek->id_absensi
                                );

                            $this->result = array(
                                'status' => true,
                                'message' => 'Proses absen keluar berhasil',
                                'data' => $sess
                            );
                        }else{
                            $this->result = array(
                                'status' => false,
                                'message' => 'Proses absen keluar gagal',
                                'data' => null
                            );
                        }
            		}else{
            			$this->result = array(
                            'status' => false,
                            'message' => 'Anda sudah melakukan absensi',
                            'data' => null
                        );
            		}
            	}
            }else{
            	$insert['user_id'] = $getUser->user_id;
                $insert['waktu_datang'] = mdate("%Y-%m-%d %H:%i:%s", time());
                $insert['media_absensi'] = 'fingerprint';

                $tanggalSkrg = mdate("%Y-%m-%d", time());
                $cekDispensasi = $this->db_select->select_where('tb_dispensasi', 'tanggal_dispensasi = "'.$tanggalSkrg.'" and user_id = "'.$getUser->user_id.'"');

                if ($cekDispensasi) {
                    if (date('H:i', strtotime($insert['waktu_datang'])) > $cekDispensasi->jam_akhir_dispensasi) {
                        $insert['status_absensi'] = 'Terlambat';
                        $ket = 'Terlambat';
                    }else{
                        $insert['status_absensi'] = 'Tepat Waktu';
                        $ket = 'Tepat Waktu';
                    }
                }else{
                    $jadwal = json_decode(file_get_contents($this->file))->jam_kerja;
                    $day = strtolower(date("l"));
                    $jadwal = $jadwal->$day;
                    if (date('H:i', strtotime($insert['waktu_datang'])) > $jadwal->from) {
                        $insert['status_absensi'] = 'Terlambat';
                        $ket = 'Terlambat';
                    }else{
                        $insert['status_absensi'] = 'Tepat Waktu';
                        $ket = 'Tepat Waktu';
                    }
                }

                $insert_absen = $this->db_dml->insert('tb_absensi', $insert);
                if ($insert_absen) {
                    $this->potonganSaldo($getUser->user_id, $insert['waktu_datang']);

                    $sess = array(
                        'id_absensi' => $insert_absen,
                    );
                    $this->result = array(
                        'status' => true,
                        'message' => 'Proses absen masuk berhasil',
                        'data' => $sess
                    );
                }else{
                    $this->result = array(
                        'status' => false,
                        'message' => 'Proses absen masuk gagal',
                        'data' => null
                    );
                }
            }
        }else{
        	$this->result = array(
	            'status' => false,
	            'message' => 'Data tidak ditemukan',
	            'data' => null
	        );
        }

        $this->loghistory->createLog($this->result);
        echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}

	public function potonganSaldo($id, $waktu_datang)
    {
        $cekUser = $this->db_select->select_where('tb_user','user_id = "'.$id.'"');

        // cek keterlambatan
        $jadwal = json_decode(file_get_contents($this->file))->jam_kerja;
        $day = strtolower(date("l"));
        $newJadwal = date('H:i:s', strtotime($jadwal->$day->from));

        // absen masuk
        $awal = date_create(date('H:i:s', strtotime($waktu_datang)));
        // jam masuk
        $akhir = date_create($newJadwal);
        if ($awal >= $akhir) {
            $diff = date_diff( $awal, $akhir );
            $newDate = strtotime($diff->h.":".$diff->i.":".$diff->s);
            $newDate = date('H:i:s', $newDate);

            $cekKeterlambatan = $this->db_select->query('select * from tb_potongan_keterlambatan where "'.$newDate.'" < durasi_keterlambatan limit 1');
            $saldoUser = $cekUser->saldo;

            if ($cekKeterlambatan) {
                $pengurangan = $cekKeterlambatan->potongan_keterlambatan*$saldoUser/100;
                $id_keterlambatan = $cekKeterlambatan->id_keterlambatan;
            }else{
                $keterlambatanMax = $this->db_select->query('select * from tb_potongan_keterlambatan order by durasi_keterlambatan desc limit 1');
                $pengurangan = $keterlambatanMax->potongan_keterlambatan*$saldoUser/100;
                $id_keterlambatan = $keterlambatanMax->id_keterlambatan;
            }
        }

        if ($pengurangan != 0) {
            $insert['id_keterlambatan'] = $id_keterlambatan;
            $insert['user_id'] = $id;
            $insert['total_potongan'] = $pengurangan;
            $this->db_dml->normal_insert('tb_hstry_potongan_keterlambatan', $insert);
        }
    }
	
}