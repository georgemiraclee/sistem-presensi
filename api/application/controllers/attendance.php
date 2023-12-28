<?php
/**
 * 
 */
class attendance extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('global_lib', 'loghistory'));
		$this->loghistory = new loghistory;
	}

	public function index()
	{
		$this->result = array(
      'status' => false,
      'message' => 'Akses ditolak.',
      'data' => null
    );

    $this->loghistory->createLog($this->result);
    echo json_encode($this->result);
	}

	public function cekIn()
	{
        $require = array('nip');
        $this->global_lib->input($require);
        if (is_numeric($this->input->post('nip'))) {
            $getUser = $this->db_select->query('select a.*, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$this->input->post('nip').'"');
            $file = '../appconfig/'.$getUser->id_channel.'_auto_respon.txt';
            $cek = $this->db_select->select_where('tb_absensi', 'user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');

            if (!$cek) {
                $insert['user_id'] = $getUser->user_id;
                $insert['waktu_datang'] = mdate("%Y-%m-%d %H:%i:%s", time());

                $jadwal = json_decode(file_get_contents($file))->jam_kerja;
                $dispensasiKeterlambatan = json_decode(file_get_contents($file))->dispensasi;
                if ($dispensasiKeterlambatan == null || $dispensasiKeterlambatan == "") {
                    $dispensasiKeterlambatan = 0;
                }
                $day = strtolower(date("l"));
                $jadwal = $jadwal->$day;
                $newJamMasuk = $jadwal->from;
                $time = strtotime($newJamMasuk);
                $endTime = date("H:i", strtotime('+'.$dispensasiKeterlambatan.' minutes', $time));
                if (date('H:i', strtotime($insert['waktu_datang'])) > $endTime) {
                    $insert['status_absensi'] = 'Terlambat';
                    $ket = 'Terlambat';
                }else{
                    $insert['status_absensi'] = 'Tepat Waktu';
                    $ket = 'Tepat Waktu';
                }
                
                $insert_absen = $this->db_dml->insert('tb_absensi', $insert);
                if ($insert_absen) {
                    $input_history = array(
                        'id_absensi' => $insert_absen
                    );
                    $this->db_dml->normal_insert('tb_history_absensi', $input_history);
                    $this->potonganSaldo($getUser->user_id, $insert['waktu_datang']);
                    $sess = array(
                        'id_absensi' => $insert_absen,
                        'keterangan' => $insert['waktu_datang'].' ('.$ket.')',
                        );
                    $this->result = array(
                        'status' => true,
                        'message' => 'Proses absen berhasil',
                        'data' => $sess
                    );
                }else{
                    $this->result = array(
                        'status' => false,
                        'message' => 'Proses absen gagal',
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
        }else{
            $this->result = array(
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            );
        }
        $this->loghistory->createLog($this->result);
        echo json_encode($this->result);
    }

    public function cekOut()
    {
        $require = array('nip');
        $this->global_lib->input($require);

        if (is_numeric($this->input->post('nip'))) {
            $getUser = $this->db_select->query('select a.*, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$this->input->post('nip').'"');
            $file = '../appconfig/'.$getUser->id_channel.'_auto_respon.txt';
            $cek = $this->db_select->select_where('tb_absensi', 'user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
            if ($cek) {
                if ($cek->waktu_pulang == null || $cek->waktu_pulang == '') {
                    $update['waktu_pulang'] = mdate("%Y-%m-%d %H:%i:%s", time());
                    $update_absen = $this->db_dml->update('tb_absensi', $update, 'user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
                    if ($update_absen == 1) {
                        $input_history = array(
                            'id_absensi' => $cek->id_absensi
                        );
                        $this->db_dml->normal_insert('tb_history_absensi', $input_history);
                        $sess = array(
                            'id_absensi' => $cek->id_absensi
                            );
                        $this->result = array(
                            'status' => true,
                            'message' => 'Proses absen berhasil',
                            'data' => $sess
                        );
                    }else{
                        $this->result = array(
                            'status' => false,
                            'message' => 'Proses absen gagal',
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
            }else{
                $this->result = array(
                    'status' => false,
                    'message' => 'Silahkan lakukan absensi masuk terlebih dahulu',
                    'data' => null
                );
            }
        }else{
            $this->result = array(
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            );
        }
        $this->loghistory->createLog($this->result);
        echo json_encode($this->result);
    }

    public function potonganSaldo($id, $waktu_datang)
    {
        $cekUser = $this->db_select->query('select a.*, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.user_id = "'.$id.'"');
        $file = '../appconfig/'.$cekUser->id_channel.'_auto_respon.txt';
        // cek keterlambatan
        $jadwal = json_decode(file_get_contents($file))->jam_kerja;
        $dispensasiKeterlambatan = json_decode(file_get_contents($file))->dispensasi;
        $day = strtolower(date("l"));
        $newJadwal = date('H:i:s', strtotime($jadwal->$day->from));
        $time = strtotime($newJadwal);
        $startTime = date("H:i:s", strtotime('+'.$dispensasiKeterlambatan.' minutes', $time));
        // absen masuk
        $awal = date_create(date('H:i:s', strtotime($waktu_datang)));
        // jam masuk
        $akhir = date_create($startTime);
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