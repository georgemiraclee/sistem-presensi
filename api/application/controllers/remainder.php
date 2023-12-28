<?php

/**
 * 
 */
class remainder extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$getUser = $this->db_select->query_all('select a.user_id, a.reg_id, b.id_channel, a.is_notif from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.reg_id is not null and a.is_admin = 0 and a.is_superadmin = 0 and a.is_notif < 5 limit 10');
    
		foreach ($getUser as $value) {
      /* cek apakah sudah absen */
      $cekAbsensi = $this->db_select->query('select *from tb_absensi where user_id = "'.$value->user_id.'" and date_format(created_absensi, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")');

      if (!$cekAbsensi) {
        /* aksi user yang belum melakukan absen */
        
        /* cek jam kerja */
        $tanggal_hari_ini = mdate("%Y-%m-%d", time());
        $cek_libur = $this->db_select->query('select *from tb_event where date_format(tanggal_event, "%Y-%m-%d") = "'.$tanggal_hari_ini.'" and id_channel = "'.$value->id_channel.'"');
        if (!$cek_libur) {
          /* cek Izin */
          $dateNow = mdate("%Y-%m-%d", time());
          $cekIzin = $this->db_select->query('select *from tb_pengajuan where ("'.$dateNow.'" between date_format(tanggal_awal_pengajuan, "%Y-%m-%d") and date_format(tanggal_akhir_pengajuan, "%Y-%m-%d")) and user_id = "'.$value->user_id.'" and status_approval = 1');
          
          if (!$cekIzin) {
            $where['id_channel'] = $value->id_channel;
            $where['is_default'] = 1;
            $getPola = $this->db_select->select_where('tb_pola_kerja', $where);
            if ($getPola) {
              $file = '../appconfig/new/'.$getPola->file_pola_kerja;

              $tglNow = date('Y-m-d');
              $cekPolaUser = $this->db_select->query('select *from tb_pola_user where user_id = "'.$value->user_id.'" and "'.$tglNow.'" between start_pola_kerja and end_pola_kerja');
              if ($cekPolaUser) {
                $getPola = $this->db_select->select_where('tb_pola_kerja','id_pola_kerja = "'.$cekPolaUser->id_pola_kerja.'"');
                $file = '../appconfig/new/'.$getPola->file_pola_kerja;
              }

              
              $jadwal = json_decode(file_get_contents($file))->jam_kerja;
              if ($getPola->toleransi_keterlambatan == 1) {
                $dispensasiKeterlambatan = $getPola->waktu_toleransi_keterlambatan;
                if ($dispensasiKeterlambatan == null || $dispensasiKeterlambatan == "") {
                  $dispensasiKeterlambatan = 0;
                }
              }else{
                  $dispensasiKeterlambatan = 0;
              }
              $day = strtolower(date("l"));
              $jadwal = $jadwal->$day;
              if ($jadwal->libur == 0) {
                $newJamMasuk = $jadwal->from;
                $time = strtotime($newJamMasuk);
                $endTime = date("H:i", strtotime('+'.$dispensasiKeterlambatan.' minutes', $time));
                
                if (date('H:i', strtotime(mdate("%Y-%m-%d %H:%i:%s", time()))) > $endTime) {
                  $fcm['message'] = 'Anda Belum Melakukan Absensi di Hari ini, Jangan Lupa yaaaa..';
                  if ($value->is_notif == 4) {
                    $fcm['message'] = 'Haii, anda belum melakukan absen di hari ini.';
                  }
                  $fcm['reg_id'] = $value->reg_id;
                  $sendFCM = json_decode($this->fcm($fcm));

                  if ($sendFCM->success == 1) {
                    $whereUpdate['user_id'] = $value->user_id;
                    $update['is_notif'] = $value->is_notif+1;

                    $this->db_dml->update('tb_user', $update, $whereUpdate);
                  }
                }
              }
            }
          }
        }
      }
		}
	}

	public function jamPulang()
	{
		$getUser = $this->db_select->query_all('select a.user_id, a.reg_id, b.id_channel, a.is_notif_pulang from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.reg_id is not null and a.is_admin = 0 and a.is_superadmin = 0 and a.is_notif_pulang < 5');

		if ($getUser) {
			foreach ($getUser as $key => $value) {
				if ($value->id_channel == 1) {
					if ($value->is_notif_pulang < 5) {
						/* cek apakah sudah absen */
						$cekAbsensi = $this->db_select->query('select *from tb_absensi where user_id = "'.$value->user_id.'" and date_format(created_absensi, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")');
						if ($cekAbsensi) {
							/* aksi user yang belum melakukan absen pulang */
							if ($cekAbsensi->waktu_pulang == null || $cekAbsensi->waktu_pulang == "") {
								$where['id_channel'] = $value->id_channel;
                $where['is_default'] = 1;
                $getPola = $this->db_select->select_where('tb_pola_kerja', $where);
                if ($getPola) {
                  $file = '../appconfig/new/'.$getPola->file_pola_kerja;

									$tglNow = date('Y-m-d');
									$cekPolaUser = $this->db_select->query('select *from tb_pola_user where user_id = "'.$value->user_id.'" and "'.$tglNow.'" between start_pola_kerja and end_pola_kerja');
									if ($cekPolaUser) {
										$getPola = $this->db_select->select_where('tb_pola_kerja','id_pola_kerja = "'.$cekPolaUser->id_pola_kerja.'"');
				                		$file = '../appconfig/new/'.$getPola->file_pola_kerja;
									}

									$jadwal = json_decode(file_get_contents($file))->jam_kerja;
									$day = strtolower(date("l"));
                  $jadwal = $jadwal->$day;

                  /* mendapatkan lama jam kerja */
                  $date1 = new DateTime($jadwal->from);
									$date2 = new DateTime($jadwal->to);
									$interval = $date1->diff($date2);
									$hour = $interval->h;
									$minute = $interval->i;

									$newJamMasuk = date('h:i', strtotime($cekAbsensi->waktu_datang));
                  $time = strtotime($newJamMasuk);
									if ($hour != 0) {
										/* menambahkan jam */
                    $endTime = date("H:i", strtotime('+'.$hour.' hours', $time));
									}

									if ($minute != 0) {
										/* menambahkan menit */
                    $endTime = date("H:i", strtotime('+'.$minute.' minutes', strtotime($endTime)));
									}

                  if (date('H:i', strtotime(mdate("%Y-%m-%d %H:%i:%s", time()))) > $endTime) {
                    $fcm['message'] = 'Anda Belum Melakukan Absensi Pulang Sore Ini, ayo pulang..';
                    if ($value->is_notif_pulang == 4) {
                      $fcm['message'] = 'Kamu ga akan pulang? ayo pulang...';
                    }
                    $fcm['reg_id'] = $value->reg_id;
                    $sendFCM = json_decode($this->fcm($fcm));

                    if ($sendFCM->success == 1) {
                      $whereUpdate['user_id'] = $value->user_id;
                      $update['is_notif_pulang'] = $value->is_notif_pulang+1;

                      $this->db_dml->update('tb_user', $update, $whereUpdate);
                    }
                  }
								}
							}
						}
					}
				}
			}
		}
	}

	public function fcm($data)
	{
		$fcmMsg = array(
	        'body' => $data['message'],
	        'title' => 'Pressensi',
	        'sound' => "default",
	        'color' => "#203E78" 
	    );
	    $fcmFields = array(
	        'to' => $data['reg_id'],
	        'priority' => 'high',
	        'notification' => $fcmMsg
	    );
	    $headers = array(
	        'Authorization: key=' . 'AAAAyFOofvM:APA91bHchv2OBeGftIN0_8ZRpqvyJp37gEQPbpMi2XHLdpGIwiEqDQuvEhu1UkgQDYvcyRFikY6oR5iJ9v4VNXymRnZRq7Im0OPh2rvsdGahACNZrUp3QAkUabbI6XfROPeoHoLZ0PR9',
	        'Content-Type: application/json'
	    );
	    $ch = curl_init();
	    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
	    curl_setopt( $ch,CURLOPT_POST, true );
	    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
	    $result = curl_exec($ch );
	    curl_close( $ch );

	    return $result;
	}

	public function resetNotif()
	{
		$data['is_notif'] = 0;
		$data['is_notif_pulang'] = 0;
		$this->db->update('tb_user', $data);
	}

	public function setTanggalMerah()
	{
		/* get channel */
		$dataChannel = $this->db_select->select_all_where('tb_channel', 'status = 1');

		foreach ($dataChannel as $valuez) {
			$tanggal = $this->tglMerah();
			foreach ($tanggal as $value) {
				$create['tanggal_event'] = "2019-".date('m-d', strtotime($value->date));
				$create['nama_event'] = $value->event;
				$create['id_channel'] = $valuez->id_channel;

				$this->db_dml->insert('tb_event', $create);
			}
		}
	}

	public function tglMerah()
	{
		return json_decode('[{
					"date": "1 January",
					"event": "Tahun Baru Masehi"
				}, {
					"date": "10 February",
					"event": "Tahun Baru Imlek"
				}, {
					"date": "12 March",
					"event": "Hari Raya Nyepi"
				}, {
					"date": "9 May",
					"event": "Kenaikan Isa Almasih"
				}, {
					"date": "25 May",
					"event": "Hari Raya Waisak"
				}, {
					"date": "17 August",
					"event": "Hari Kemerdekaan"
				}, {
					"date": "25 December",
					"event": "Hari Natal"
				}]');
	}
}