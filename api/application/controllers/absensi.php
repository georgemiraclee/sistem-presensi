<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class absensi extends CI_Controller
{
  /* default file jam kerja */
  function __construct()
  {
    parent::__construct();
    $this->load->library(array('global_lib', 'polygon','loghistory','absensi_lib'));
    // /* library untuk pengecekan user : parameter (nip dan token yang di ambil) */
    $this->global_lib->authentication();
    // /* library untuk pengecekan lokasi absensi */
    $this->polygon = new polygon;
    // /* library untuk mencatat aktivitas API */
    $this->loghistory = new loghistory;
    $this->absensi_lib = new absensi_lib;
    
    $this->result = array(
      'status' => false,
      'message' => 'Data tidak ditemukan',
      'data' => null
    );
  }

  public function absenMasuk()
  {
    $require = array('nip');
    $this->global_lib->input($require);

    if (is_numeric($this->input->post('nip'))) {
      /* get data user */
      $getUser = $this->db_select->query('select a.*, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$this->input->post('nip').'"');
      
      if ($getUser && $getUser->is_aktif == 1) {
        /* start pengecekan user trial */
        if ($getUser->is_trial) {
          $lokasi['id_jaringan'] = null;
          $lokasi['id_lokasi'] = null;
        }else{
          /* start cek lokasi absensi */
          $lokasi = $this->cekLokasi();
          /* end cek lokasi absensi */
        }

        $tagLocation = $this->tagLocation();
        /* end pengecekan user trial */

        /* get data pola kerja pada hari ini */
        $file = $this->absensi_lib->getPolaUser($getUser->user_id);

        /* pengecekan file pola kerja tersedia atau tidak */
        if (!file_exists($file['file'])) {
          /* jika file tidak di temukan, diberikan informasi */
          $this->result = array(
            'status' => false,
            'message' => 'Pola kerja tidak ditemukan, harap hubungi administrator.',
            'data' => null
          );

          $this->loghistory->createLog($this->result);
          echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
        }
          
        /* get data absensi user pada hari ini */
        $cek = $this->db_select->select_where('tb_absensi', 'user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'" and is_attendance = 1');

        if ($cek) {
          if ($cek->status_absensi == 'Tidak Hadir') {
            $this->result = array(
              'status' => false,
              'message' => 'Anda sudah tidak dapat melakukan absensi.',
              'data' => null
            );
            $this->loghistory->createLog($this->result);
            echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
          } else {
            if (isset($cek->waktu_datang) && isset($cek->waktu_istirahat) && isset($cek->waktu_kembali) && isset($cek->waktu_pulang)){
                $this->result = array(
                    'status' => false,
                    'message' => 'Anda sudah melakukan absensi1.',
                    'data' => null
                );
            }else{
                if ($cek->waktu_istirahat != null || $cek->waktu_istirahat != '') {
                    if ($_FILES) {
                        if ($_FILES['userfile']['name'] != '') {
                            $path = realpath('../assets/images/absensi');
                            $time = date(now())*1000;
                            $config = array('allowed_types' => 'jpg|jpeg|gif|png',
                                            'upload_path' => $path,
                                            'encrypt_name' => false,
                                            'file_name' => $getUser->user_id."_".$time
                                        );
                            $this->upload->initialize($config);
                            if ($this->upload->do_upload()) {
                                $img_data = $this->upload->data();
                                $new_imgname = $getUser->user_id."_".$time.$img_data['file_ext'];
                                $new_imgpath = $img_data['file_path'].$new_imgname;
                                rename($img_data['full_path'], $new_imgpath);

                                $update['url_file_absensi'] = $new_imgname;
                                $update['waktu_kembali'] = mdate("%Y-%m-%d %H:%i:%s", time());
                                $update_absen = $this->db_dml->update('tb_absensi', $update, 'user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
                                if ($update_absen == 1) {
                                    $input_history = array(
                                        'id_absensi' => $cek->id_absensi,
                                        'lat' => $this->input->post('lat'),
                                        'lng' => $this->input->post('lng'),
                                        'id_jaringan' => $lokasi['id_jaringan'],
                                        'id_lokasi' => $lokasi['id_lokasi']
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
                            }else {
                                $this->result = array(
                                    'status' => false,
                                    'message' => $this->upload->display_errors(),
                                    'data' => null
                                );
                                $this->loghistory->createLog($this->result);
                                echo json_encode($this->result, JSON_NUMERIC_CHECK);exit();
                            }
                        }else{
                            $this->result = array(
                                'status' => false,
                                'message' => 'Harap isikan foto',
                                'data' => null
                            );
                        }
                    }else{
                        $this->result = array(
                            'status' => false,
                            'message' => 'Harap isikan foto',
                            'data' => null
                        );
                    } 
                }else{
                    $this->result = array(
                        'status' => false,
                        'message' => 'Anda sudah melakukan absensi2!',
                        'data' => null
                    );
                }
            }
          }
        }else{
          /* create data absensi pada hari ini */
          $insert['user_id'] = $getUser->user_id;
          $insert['waktu_datang'] = mdate("%Y-%m-%d %H:%i:%s", time());
          if ($tagLocation['status']) {
            $insert['tagging'] = 'WFO';
            $insert['location_tagging'] = $tagLocation['location'];
          } else {
            $insert['tagging'] = 'WFH';
            $insert['location_tagging'] = null;
          }

          /* GET DISPENSASI KETERLAMBATAN YANG DI SET LANGSUNG DARI DASHBOARD ADMIN */
          $cekDispensasi = $this->absensi_lib->getDispensasi($getUser->user_id);
            
          if ($cekDispensasi) {
              if (date('H:i', strtotime($insert['waktu_datang'])) > $cekDispensasi->jam_akhir_dispensasi) {
                  $insert['status_absensi'] = 'Terlambat';
                  $ket = 'Terlambat';
              }else{
                  $insert['status_absensi'] = 'Tepat Waktu';
                  $ket = 'Tepat Waktu';
              }
          }else{
            $jadwal = json_decode(file_get_contents($file['file']))->jam_kerja;

            /* proses pemecahan jadwal kerja hari ini */
            $getStatusAbsensi = $this->absensi_lib->getStatusAbsensi($file['getPola'], $jadwal);
            $day = strtolower(date("l"));
            $jadwalNew = date_create($jadwal->$day->from);
            $jam_skrg = date_create(date("H:i"));
            $diff  = date_diff($jam_skrg, $jadwalNew);

            /* set keterlambatan */
            $insert['status_absensi'] = $getStatusAbsensi;
            $keteranganTerlambat = 0;
            if ($getStatusAbsensi == "Terlambat") {
                if ($diff->h != 0) {
                    $keteranganTerlambat += $diff->h*3600;
                }
                if ($diff->i != 0) {
                    $keteranganTerlambat += $diff->i*60;
                }
            }
            $insert['waktu_keterlambatan'] = $keteranganTerlambat;
            $ket = $getStatusAbsensi;
          }

            
          if ($_FILES) {
              /* aksi menyimpan foto absensi */
              if ($_FILES['userfile']['name'] != '') {
                  $path = realpath('../assets/images/absensi');
                  $time = date(now())*1000;
                  $config = array('allowed_types' => 'jpg|jpeg|gif|png',
                                  'upload_path' => $path,
                                  'encrypt_name' => false,
                                  'file_name' => $getUser->user_id."_".$time
                              );
                  $this->upload->initialize($config);

                  if ($this->upload->do_upload()) {
                      $img_data = $this->upload->data();
                      $new_imgname = $getUser->user_id."_".$time.$img_data['file_ext'];
                      $new_imgpath = $img_data['file_path'].$new_imgname;
                      rename($img_data['full_path'], $new_imgpath);

                      /* aksi pengecekan face recognition disimpan di sini nantinya */
                      /* -------------- */
                      /* end */

                      $insert['url_file_absensi'] = $new_imgname;
                  }else{
                      /* respon ketika proses upload foto gagal */
                      $this->result = array(
                          'status' => false,
                          'message' => $this->upload->display_errors(),
                          'data' => null
                      );
                      $this->loghistory->createLog($this->result);
                      echo json_encode($this->result, JSON_NUMERIC_CHECK);exit();
                  }
              }else{
                  /* aksi ketika user tidak mengirimkan foto */
                  $this->result = array(
                      'status' => false,
                      'message' => 'Harap isikan foto',
                      'data' => null
                  );
                  $this->loghistory->createLog($this->result);
                  echo json_encode($this->result, JSON_NUMERIC_CHECK);exit();
              }
          }else{
              /* aksi ketika user tidak mengirimkan foto */
              $this->result = array(
                  'status' => false,
                  'message' => 'Harap isikan foto',
                  'data' => null
              );
              $this->loghistory->createLog($this->result);
              echo json_encode($this->result, JSON_NUMERIC_CHECK);exit();
          }

          /* proses menyimpan data absensi */
          $insert_absen = $this->db_dml->insert('tb_absensi', $insert);
          if ($insert_absen) {
            /* set data history absensi */
            $input_history = array(
              'id_absensi' => $insert_absen,
              'lat' => $this->input->post('lat'),
              'lng' => $this->input->post('lng'),
              'address' => $this->input->post('address'),
              'id_jaringan' => $lokasi['id_jaringan'],
              'id_lokasi' => $lokasi['id_lokasi']
            );

            $this->db_dml->normal_insert('tb_history_absensi', $input_history);
            // $this->potonganSaldo($getUser->user_id, $insert['waktu_datang']);
              
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
        }
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Akun anda telah expired.',
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
    echo json_encode($this->result, JSON_NUMERIC_CHECK);
  }

  public function absenKeluar()
  {
    $require = array('nip', 'akses');
    $this->global_lib->input($require);

    if (is_numeric($this->input->post('nip'))) {
      $getUser = $this->db_select->query('select a.*, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$this->input->post('nip').'"');

      if ($getUser->is_aktif == 1) {
        if ($getUser->is_trial) {
          $lokasi['id_jaringan'] = null;
          $lokasi['id_lokasi'] = null;
        }else{
          /* start cek lokasi absensi */
          $lokasi = $this->cekLokasi();
          /* end cek lokasi absensi */
        }

        $cek = $this->db_select->select_where('tb_absensi', 'user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
        if ($cek) {
          /* akses 1 = Absen keluar saat istirahat */
          /* akses 2 = Absen keluar saat pulang kerja */

          if ($this->input->post('akses') == 1) {
            if ($cek->waktu_istirahat == null || $cek->waktu_istirahat == '') {
              $update['waktu_istirahat'] = mdate("%Y-%m-%d %H:%i:%s", time());
              $update_absen = $this->db_dml->update('tb_absensi', $update, 'user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
              if ($update_absen == 1) {
                $input_history = array(
                  'id_absensi' => $cek->id_absensi,
                  'lat' => $this->input->post('lat'),
                  'lng' => $this->input->post('lng'),
                  'address' => $this->input->post('address'),
                  'id_jaringan' => $lokasi['id_jaringan'],
                  'id_lokasi' => $lokasi['id_lokasi']
                );
                $this->db_dml->normal_insert('tb_history_absensi', $input_history);
                $sess = array(
                  'id_absensi' => $cek->id_absensi,
                  'status' => 'istirahat'
                );
                $this->result = array(
                  'status' => true,
                  'message' => 'Proses absen berhasil',
                  'is_pulang' => true,
                  'data' => $sess
                );
              }else{
                $this->result = array(
                  'status' => false,
                  'message' => 'Proses absen gagal',
                  'is_pulang' => true,
                  'data' => null
                );
              }
            }else{
              $this->result = array(
                'status' => false,
                'message' => 'Anda sudah melakukan absensi',
                'is_pulang' => true,
                'data' => null
              );
            }
          }elseif ($this->input->post('akses') == 2) {
            if ($cek->waktu_pulang == null || $cek->waktu_pulang == '') {
              $update['waktu_pulang'] = mdate("%Y-%m-%d %H:%i:%s", time());
              $update['is_pulang'] = 1;
              $update_absen = $this->db_dml->update('tb_absensi', $update, 'user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
              if ($update_absen == 1) {
                $input_history = array(
                  'id_absensi' => $cek->id_absensi,
                  'lat' => $this->input->post('lat'),
                  'lng' => $this->input->post('lng'),
                  'address' => $this->input->post('address'),
                  'id_jaringan' => $lokasi['id_jaringan'],
                  'id_lokasi' => $lokasi['id_lokasi']
                );
                $this->db_dml->normal_insert('tb_history_absensi', $input_history);
                
                $sess = array(
                  'id_absensi' => $cek->id_absensi,
                  'status' => 'keluar'
                );
                
                $this->result = array(
                  'status' => true,
                  'message' => 'Proses absen berhasil',
                  'is_pulang' => true,
                  'data' => $sess
                );
              }else{
                $this->result = array(
                  'status' => false,
                  'message' => 'Proses absen gagala',
                  'is_pulang' => true,
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
              'message' => 'Format akses ditolak',
              'is_pulang' => true,
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
      } else {
        $this->result = array(
          'status' => false,
          'message' => 'Akun anda telah expired.',
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
    echo json_encode($this->result, JSON_NUMERIC_CHECK);
  }

  public function cekFace($user_id, $image)
  {
      $url = "http://dev.folkatech.id:1112/image-auth?id=".$user_id."&filename=".$image;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $url);
      $result = curl_exec($ch);
      $result = json_decode($result);
      curl_close($ch);
      if ($result->status != 1) {
          $this->result = array(
              'status' => false,
              'message' => 'Wajah anda tidak dikenal, cobalagi',
              'data' => null
          );
          $this->loghistory->createLog($this->result);
          echo json_encode($this->result, JSON_NUMERIC_CHECK);exit();
      }
  }

  public function cekLokasi()
  {
    $pass = true;

    $cekUser = $this->db_select->select_where('tb_user','nip = "'.$this->input->post('nip').'"');
    $ssid_jaringan = $this->input->post('ssid');
    $cekAksesAbsen = $this->db_select->select_where('tb_akses_absensi','user_id = "'.$cekUser->user_id.'" and date_format(tanggal_akhir, "%Y-%m-%d") >= date_format(now(),"%Y-%m-%d")');
    if ($cekAksesAbsen) {
      $dataLokasi = $this->db_select->select_where('tb_lokasi','id_lokasi = "'.$cekUser->id_unit.'"');
      $check = $this->polygon->get_position($this->input->post('lat'), $this->input->post('lng'), '', $cekAksesAbsen->url_file_lokasi);
      
      if ($check) {
        $data['id_lokasi'] = $dataLokasi->id_lokasi;
      }else{
        $data['id_lokasi'] = null;
      }
    }else{
      $dataUnit = $this->db_select->select_where('tb_unit','id_unit = "'.$cekUser->id_unit.'"');
      // Cek Jaringan
      if ($dataUnit->data_jaringan != null || $dataUnit->data_jaringan != "") {
        $dataJaringan = json_decode($dataUnit->data_jaringan);
        if ($dataJaringan) {
          $pass = false;
          for ($y=0; $y < count($dataJaringan) ; $y++) {
            $dataJaringanNew = $this->db_select->select_where('tb_jaringan','id_jaringan = "'.$dataJaringan[$y].'"');
            if ($ssid_jaringan == $dataJaringanNew->ssid_jaringan) {
              $data['id_jaringan'] = $dataJaringanNew->id_jaringan;
              break;
            }else{
              $data['id_jaringan'] = null;
            }
          }
        }
      }
      // Cek Lokasi
      if ($dataUnit->data_lokasi != null || $dataUnit->data_lokasi != "") {
        $dataLokasiA = json_decode($dataUnit->data_lokasi);
        $data['id_lokasi'] = null;
        if ($dataLokasiA) {
          $pass = false;
          $kk = count($dataLokasiA);
          for ($a=0; $a < $kk ; $a++) {
              $dataLokasi = $this->db_select->select_where('tb_lokasi','id_lokasi = "'.$dataLokasiA[$a].'"');
              if ($dataLokasi) {
                  $check = $this->polygon->get_position($this->input->post('lat'), $this->input->post('lng'), '', $dataLokasi->url_file_lokasi);
                  if ($check) {
                      $data['id_lokasi'] = $dataLokasi->id_lokasi;
                      break;
                  }else{
                      $data['id_lokasi'] = null;
                  }
              }else{
                  $data['id_lokasi'] = null;
              }
          }
        }
      }
    }

    if ($this->input->post('status_auth') == false) {
      $data['id_jaringan'] = null;
    }
    
    if ($data['id_jaringan'] == null && $data['id_lokasi'] == null && !$pass) {
      $this->result = array(
        'status' => false,
        'message' => 'Anda berada di luar lokasi atau jaringan yang telah ditentukan',
        'data' => null
      );
      $this->loghistory->createLog($this->result);
      echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
    }else{
      return $data;
    }
  }
  
  public function tagLocation()
  {
    $cekUser = $this->db_select->select_where('tb_user','nip = "'.$this->input->post('nip').'"');
    $ssid_jaringan = $this->input->post('ssid');
    $dataUnit = $this->db_select->select_where('tb_unit','id_unit = "'.$cekUser->id_unit.'"');
    $getAllLocation = $this->db_select->select_all_where('tb_lokasi', ['id_channel' => $dataUnit->id_channel]);

    $status = false;
    $location = null;
    foreach ($getAllLocation as $value) {
      $check = $this->polygon->get_position($this->input->post('lat'), $this->input->post('lng'), '', $value->url_file_lokasi);

      if ($check) {
        $status = true;
        $location = $value->nama_lokasi;
      }
    }

    $res['status'] = $status;
    $res['location'] = $location;
    return $res;
  }

  public function uploadFoto()
  {
      $require = array('nip', 'id_absensi');
      $this->global_lib->input($require);
      if (is_numeric($this->input->post('nip')) && is_numeric($this->input->post('id_absensi'))) {
          if ($_FILES['userfile']['name'] != '') {
              $path = realpath('../assets/images/absensi');
              $time = $this->input->post('nip').'_'.strtotime('now');
              $config = array('allowed_types' => 'jpg|jpeg|gif|png',
                              'upload_path' => $path,
                              'encrypt_name' => false,
                              'file_name' => $time
                              );
              $this->upload->initialize($config);
              if ($this->upload->do_upload()) {
                  $img_data = $this->upload->data();
                  $new_imgname = $time.$img_data['file_ext'];
                  $new_imgpath = $img_data['file_path'].$new_imgname;
                  rename($img_data['full_path'], $new_imgpath);
                  $insert['id_absensi'] = $this->input->post('id_absensi');
                  $insert['url_file_absensi'] = $new_imgname;
                  $upload = $this->db_dml->normal_insert('tb_file_absensi', $insert);
                  if ($upload == 1) {
                      $this->result = array(
                          'status' => true,
                          'message' => 'Data berhasil disimpan',
                          'data' => $insert
                      );
                  }else{
                      $this->result = array(
                          'status' => false,
                          'message' => 'Data gagal disimpan',
                          'data' => null
                      );
                  }
              } else {
                  $this->result = array(
                      'status' => false,
                      'message' => $this->upload->display_errors(),
                      'data' => null
                  );
                  $this->loghistory->createLog($this->result);
                  echo json_encode($this->result, JSON_NUMERIC_CHECK);exit();
              }
          }else{
              $this->result = array(
                  'status' => false,
                  'message' => 'Harap isikan foto',
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
      echo json_encode($this->result, JSON_NUMERIC_CHECK);
  }

  public function getFotoAbsensi()
  {
      $require = array('id_absensi');
      $this->global_lib->input($require);
      if (is_numeric($this->input->post('id_absensi'))) {
          $getUser = $this->db_select->select_where('tb_user', 'nip = "'.$this->input->post('nip').'"');
          $cek = $this->db_select->query('select url_file_absensi from tb_absensi where id_absensi = '.$this->input->post('id_absensi').' and user_id = '.$getUser->user_id.'');
          if (count($cek) > 0) {
              if ($cek->url_file_absensi != null || $cek->url_file_absensi != "") {
                  $path = realpath('../assets/images/absensi/' . $cek->url_file_absensi);

                  if (file_exists($path)) {
                      $cek->url_file_absensi = image_url().'images/absensi/'.$cek->url_file_absensi;
                  }else{
                      $cek->url_file_absensi = image_url() . 'images/absensi/default_photo.jpg';
                  }
              }else{
                  $cek->url_file_absensi = image_url() . 'images/absensi/default_photo.jpg';
              }

              $this->result = array(
                  'status' => true,
                  'message' => 'Data ditemukan.',
                  'data' => $cek
              );
          }else{
              $this->result = array(
                  'status' => false,
                  'message' => 'Anda belum melakukan foto absensi.',
                  'data' => null
              );
          }
      }
      $this->loghistory->createLog($this->result);
      echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
  }

  public function cekAbsensi()
  {
    $require = array('nip');
    $this->global_lib->input($require);
    if (is_numeric($this->input->post('nip'))) {
      $getUser = $this->db_select->select_where('tb_user', 'nip = "'.$this->input->post('nip').'"');
      $tanggal = date("Y/m/d");
      $cek = $this->db_select->query('select *from tb_absensi where user_id = '.$getUser->user_id.' and date_format(created_absensi,"%Y/%m/%d") = "'.$tanggal.'"');
      $cekApel = $this->db_select->select_where('tb_apel', 'date_format(tanggal_apel, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")');
      if ($cekApel) {
        $cekSudahApel = $this->db_select->select_where('tb_absensi_apel', 'user_id = "'.$getUser->user_id.'" and id_apel = "'.$cekApel->id_apel.'"');
        if ($cekSudahApel) {
          $is_apel = false;
          $id_apel = $cekApel->id_apel;
        }else{
          $is_apel = true;
          $id_apel = $cekApel->id_apel;
        }
      }else{
        $is_apel = false;
        $id_apel = null;
      }

      if ($cek) {
          $this->result = array(
              'status' => true,
              'message' => 'Anda sudah melakukan absensi.',
              'is_apel' => $is_apel,
              'id_apel' => $id_apel,
              'data' => null
          );
      }else{
          $cek = $this->db_select->query('select *from tb_absensi where user_id = '.$getUser->user_id.' order by created_absensi desc limit 1');
          if (count($cek) > 0) {
              $this->result = array(
                  'status' => false,
                  'message' => 'Terakhir Anda Login '.date('d-m-Y', strtotime($cek->created_absensi)),
                  'is_apel' => $is_apel,
                  'id_apel' => $id_apel,
                  'data' => null
              );
          }else{
              $this->result = array(
                  'status' => false,
                  'message' => 'Anda belum melakukan absensi',
                  'is_apel' => $is_apel,
                  'id_apel' => $id_apel,
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
    echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
  }

  public function cekStatusAbsensi()
  {
      $require = array('nip');
      $this->global_lib->input($require);
      if (is_numeric($this->input->post('nip'))) {
          $getUser = $this->db_select->query('select user_id from tb_user where nip = "'.$this->input->post('nip').'"');
          $tanggal = date("Y/m/d");
          $cek = $this->db_select->query('select *from tb_absensi where user_id = '.$getUser->user_id.' and date_format(created_absensi,"%Y/%m/%d") = "'.$tanggal.'"');
          if ($cek) {
              if ($cek->url_file_absensi != null || $cek->url_file_absensi != "") {
                  $path = realpath('../assets/images/absensi/' . $cek->url_file_absensi);

                  if (file_exists($path)) {
                      $fotoAbsensi = image_url().'images/absensi/'.$cek->url_file_absensi;
                  }else{
                      $fotoAbsensi = image_url() . 'images/absensi/default_photo.jpg';
                  }
              }else{
                  $fotoAbsensi = image_url() . 'images/absensi/default_photo.jpg';
              }
              
              if ($cek->waktu_datang == null || $cek->waktu_datang == "") {
                  $cek->waktu_datang = "";
              }else{
                  $cek->waktu_datang = date('H:i', strtotime($cek->waktu_datang));
              }

              if ($cek->waktu_pulang == null || $cek->waktu_pulang == "") {
                  $cek->waktu_pulang = "";
              }else{
                  $cek->waktu_pulang = date('H:i', strtotime($cek->waktu_pulang));
              }

              if ($cek->waktu_datang != null && $cek->waktu_istirahat == null && $cek->waktu_kembali == null && $cek->waktu_pulang == null) {
                  $this->result = array(
                      'status' => true,
                      'message' => 'Data ditemukan',
                      'data' => array(
                          'id_absensi' => $cek->id_absensi,
                          'status' => 'masuk',
                          'foto_absensi' => $fotoAbsensi,
                          'jam_masuk' => $cek->waktu_datang,
                          'jam_keluar' => $cek->waktu_pulang
                      )
                  );
              } elseif ($cek->waktu_datang != null && $cek->waktu_istirahat != null && $cek->waktu_kembali != null && $cek->waktu_pulang == null) {
                  $this->result = array(
                      'status' => true,
                      'message' => 'Data ditemukan',
                      'data' => array(
                          'id_absensi' => $cek->id_absensi,
                          'status' => 'masuk_istirahat',
                          'foto_absensi' => $fotoAbsensi,
                          'jam_masuk' => $cek->waktu_datang,
                          'jam_keluar' => $cek->waktu_pulang
                      ),
                  );
              } else {
                  $this->result = array(
                      'status' => false,
                      'message' => 'Data tidak ditemukan',
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
      }else{
          $this->result = array(
              'status' => false,
              'message' => 'Data tidak ditemukan',
              'data' => null
          );
      }
      $this->loghistory->createLog($this->result);
      echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
  }

  public function addPengajuan()
  {
    $require = array('nip');
    $this->global_lib->input($require);

    if (is_numeric($this->input->post('nip'))) {
      $dataUser = $this->db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = '.$this->input->post("nip").'');
      $getCuti1 = $this->db_select->select_where('tb_pengaturan_cuti','id_channel = '.$dataUser->id_channel.'');

      if (!$getCuti1) {
        $insertJatah['jumlah_cuti_tahunan'] = 14;
        $insertJatah['jatah_cuti_bulanan'] = 3;
        $insertJatah['batasan_cuti'] = 0;
        $insertJatah['id_channel'] = $dataUser->id_channel;
        $this->db_dml->insert('tb_pengaturan_cuti', $insertJatah);
      }

      $data['status_pengajuan'] = $this->input->post('tipe_izin');
      $data['user_id'] = $dataUser->user_id;
      $data['tanggal_awal_pengajuan'] = $this->input->post('tanggal_awal');
      $data['tanggal_akhir_pengajuan'] = $this->input->post('tanggal_akhir');
      $data['keterangan_pengajuan'] = $this->input->post('keterangan');

      /* cek status pengajuan */
      $cekStatus = $this->db_select->select_where('tb_status_pengajuan', 'id_status_pengajuan = "'.$data['status_pengajuan'].'"');

      if ($cekStatus->nama_status_pengajuan == "Sakit") {
        if ($_FILES['userfile']['name'] != '') {
          $path = realpath('../assets/images/pengajuan_sakit');
          $time = $this->input->post('nip').'_'.strtotime('now');
          $config = array('allowed_types' => 'jpg|jpeg|gif|png',
            'upload_path' => $path,
            'encrypt_name' => false,
            'file_name' => $time
          );
          $this->upload->initialize($config);
          if ($this->upload->do_upload()) {
            $img_data = $this->upload->data();
            $new_imgname = $time.$img_data['file_ext'];
            $new_imgpath = $img_data['file_path'].$new_imgname;
            rename($img_data['full_path'], $new_imgpath);
            $data['url_file_pengajuan'] = $new_imgname;
          } else {
            $this->result = array(
              'status' => false,
              'message' => $this->upload->display_errors(),
              'data' => null
            );
            $this->loghistory->createLog($this->result);
            echo json_encode($this->result, JSON_NUMERIC_CHECK);exit();
          }
        }else{
          $this->result = array(
            'status' => false,
            'message' => 'Harap masukan foto surat keterangan',
            'data' => null
          );
          $this->loghistory->createLog($this->result);
          echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
        }
      } elseif ($cekStatus == "Cuti" || $cekStatus == "Cuti Tahunan") {
        $getCuti = $this->db_select->select_where('tb_pengaturan_cuti','id_channel = '.$dataUser->id_channel.'');
        
        if ($getCuti) {
          if ($dataUser->cuti >= $getCuti->jumlah_cuti_tahunan) {
            $this->result = array(
              'status' => false,
              'message' => 'Cuti tahunan anda sudah habis',
              'data' => null
            );
          }else{
            if ($getCuti->batasan_cuti == 1) {
              $getHistory = $this->db_select->query('select count(*) total from tb_pengajuan a join tb_status_pengajuan b on a.status_pengajuan = b.id_status_pengajuan where user_id = "'.$dataUser->user_id.'" and nama_status_pengajuan in ("Cuti", "Cuti Tahunan") and status_approval = 1 and date_format(now(), "%Y-%m") = date_format(tanggal_awal_pengajuan, "%Y-%m")');

              if ($getHistory->total > $getCuti->jatah_cuti_bulanan) {
                $this->result = array(
                  'status' => false,
                  'message' => 'Cuti bulanan anda sudah habis',
                  'data' => null
                );

                $this->loghistory->createLog($this->result);
                echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
              }
            }
          }
        }else{
          $this->result = array(
            'status' => false,
            'message' => 'Batasan Cuti Tidak Tersedia',
            'data' => null
          );
        }
      }
      
      $insert = $this->db_dml->normal_insert('tb_pengajuan', $data);
      if ($insert == 1) {
        if ($dataUser->id_parent) {
          /* push notif ke atasan */
          $this->global_lib->sendFCMByUser('Pengajuan Baru', 'Terdapat pengajuan baru yang perlu di tindaklanjuti di Portal Web.', $dataUser->id_parent);
          $this->sendEmail($dataUser->id_parent);
        }
        $this->result = array(
          'status' => true,
          'message' => 'Data berhasil disimpan.',
          'data' => null
        );
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Data gagal disimpan',
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
    echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
  }

  public function sendEmail($user_id)
  {
    $user = $this->db_select->select_where('tb_user', ['user_id' => $user_id]);
    $body = $this->load->view('template_pengajuan', $user, TRUE);

    $this->email->from('hello@pressensi.com', 'Info Pressensi');
    $this->email->to($user->email_user);
    $this->email->subject('Pressensi App Pengajuan Baru');
    $this->email->message($body);

    if ($this->email->send()) {
      return true;
    }else{
      return false;
    }
  }

  public function addPengajuanLembur()
  {
    $require = array('nip', 'tanggal', 'lama_jam', 'jam_mulai', 'keterangan');
    $this->global_lib->input($require);

    $nip = $this->input->post('nip');
    $tanggal = $this->input->post('tanggal');
    $lama_jam = $this->input->post('lama_jam');
    $jam_mulai = $this->input->post('jam_mulai');
    $keterangan = $this->input->post('keterangan');

    if (is_numeric($nip)) {
      /* get data user */
      $getUser = $this->db_select->query('select * from tb_user where nip = '.$this->input->post('nip'));
      
      /* insert data lembur */
      $insert['user_id'] = $getUser->user_id;
      $insert['tanggal_lembur'] = $tanggal;
      $insert['lama_lembur'] = $lama_jam;
      $insert['jam_mulai'] = date('H:i:s', strtotime($jam_mulai));
      $insert['keterangan'] = $keterangan;

      if ($_FILES['userfile']['name'] != '') {
        $path = realpath('../assets/images/lembur');
        $time = $this->input->post('nip').'_'.strtotime('now');
        $config = array(
          'allowed_types' => 'jpg|jpeg|gif|png',
          'upload_path' => $path,
          'max_size' => 0,
          'encrypt_name' => false,
          'file_name' => $time
        );
        $this->upload->initialize($config);
        if ($this->upload->do_upload()) {
          $img_data = $this->upload->data();
          $new_imgname = $time.$img_data['file_ext'];
          $new_imgpath = $img_data['file_path'].$new_imgname;
          rename($img_data['full_path'], $new_imgpath);
          $insert['userfile'] = $new_imgname;
        } else {
          $this->result = array(
            'status' => false,
            'message' => $this->upload->display_errors(),
            'data' => null
          );
          $this->loghistory->createLog($this->result);
          echo json_encode($this->result, JSON_NUMERIC_CHECK);exit();
        }
      }

      $insertData = $this->db_dml->insert('tb_lembur', $insert);
      if ($insertData) {
        $this->result = array(
          'status' => true,
          'message' => 'Success',
          'data' => null
        );
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Data pengajuan gagal',
          'data' => null
        );
      }
    }else{
      $this->result = array(
        'status' => false,
        'message' => 'Format nip tidak sesuai',
        'data' => null
      );
    }

    $this->loghistory->createLog($this->result);
    echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
  }
  
  public function addPengajuanLemburv2()
  {
    $require = array('nip', 'tanggal', 'jam_mulai', 'jam_selesai', 'keterangan');
    $this->global_lib->input($require);

    $startDate = strtotime($this->input->post('jam_mulai'));
    $endDate = strtotime($this->input->post('jam_selesai'));
    $diff_minutes = round(abs($startDate - $endDate) / 60,2);

    $nip = $this->input->post('nip');
    $tanggal = $this->input->post('tanggal');
    $durasi_lembur = $diff_minutes;
    $jam_mulai = $this->input->post('jam_mulai');
    $keterangan = $this->input->post('keterangan');

    if (is_numeric($nip)) {
      /* get data user */
      $getUser = $this->db_select->query('select * from tb_user where nip = '.$this->input->post('nip'));
      
      /* insert data lembur */
      $insert['user_id'] = $getUser->user_id;
      $insert['tanggal_lembur'] = $tanggal;
      $insert['durasi_lembur'] = $durasi_lembur;
      $insert['jam_mulai'] = date('H:i:s', strtotime($jam_mulai));
      $insert['keterangan'] = $keterangan;

      if ($_FILES['userfile']['name'] != '') {
        $path = realpath('../assets/images/lembur');
        $time = $this->input->post('nip').'_'.strtotime('now');
        $config = array(
          'allowed_types' => 'jpg|jpeg|gif|png',
          'upload_path' => $path,
          'max_size' => 0,
          'encrypt_name' => false,
          'file_name' => $time
        );
        $this->upload->initialize($config);
        if ($this->upload->do_upload()) {
          $img_data = $this->upload->data();
          $new_imgname = $time.$img_data['file_ext'];
          $new_imgpath = $img_data['file_path'].$new_imgname;
          rename($img_data['full_path'], $new_imgpath);
          $insert['userfile'] = $new_imgname;
        } else {
          $this->result = array(
            'status' => false,
            'message' => $this->upload->display_errors(),
            'data' => null
          );
          $this->loghistory->createLog($this->result);
          echo json_encode($this->result, JSON_NUMERIC_CHECK);exit();
        }
      }

      $insertData = $this->db_dml->insert('tb_lembur', $insert);
      if ($insertData) {
        $this->result = array(
          'status' => true,
          'message' => 'Success',
          'data' => null
        );
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Data pengajuan gagal',
          'data' => null
        );
      }
    }else{
      $this->result = array(
        'status' => false,
        'message' => 'Format nip tidak sesuai',
        'data' => null
      );
    }

    $this->loghistory->createLog($this->result);
    echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
  }
  
  public function addPengajuanCuti()
  {
      $require = array('nip');
      $this->global_lib->input($require);
      if (is_numeric($this->input->post('nip'))) {
          $dataUser = $this->db_select->select_where('tb_user', 'nip = '.$this->input->post("nip").'');
          $data['user_id'] = $dataUser->user_id;
          $data['id_apel'] = $this->input->post('id_apel');
          $data['status_pengajuan_apel'] = $this->input->post('tipe_izin');
          $data['keterangan_pengajuan_apel'] = $this->input->post('keterangan');
          $insert = $this->db_dml->normal_insert('tb_pengajuan_apel', $data);
          if ($insert == 1) {
              $this->result = array(
                  'status' => true,
                  'message' => 'Data berhasil disimpan.',
                  'data' => null
              );
          }else{
              $this->result = array(
                  'status' => false,
                  'message' => 'Data gagal disimpan',
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
      echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
  }
  
  public function listStatusPengajuan()
  {
      $getUser = $this->db_select->query('select b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$this->input->post('nip').'"');
      /* cek apakah sudah ada status atau belum untuk masing-masing channel */
      $cek = $this->db_select->select_all_where('tb_status_pengajuan', 'id_channel = "'.$getUser->id_channel.'"');
      
      if (!$cek) {
          if ($getUser->id_channel != 1) {
              /* buatkan status default */
              $nama_status_pengajuan = array('Cuti','Izin','Sakit','Lembur');
              for ($i=0; $i < count($nama_status_pengajuan) ; $i++) { 
                  $input['nama_status_pengajuan'] = $nama_status_pengajuan[$i];
                  $input['id_channel'] = $getUser->id_channel;
                  $input['is_default'] = 1;
                  $this->db_dml->insert('tb_status_pengajuan', $input);
              }
          }
      }

      $data = $this->db_select->select_all_where('tb_status_pengajuan','id_channel = "'.$getUser->id_channel.'"');
      if (count($data) > 0) {
          $this->result = array(
              'status' => true,
              'message' => 'Data ditemukan',
              'data' => $data
          );
      } else {
          $this->result = array(
              'status' => false,
              'message' => 'Data tidak ditemukan',
              'data' => null
          );
      }
      $this->loghistory->createLog($this->result);
      echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
  }

  public function absensiApel()
  {
      $require = array('nip', 'id_apel');
      $this->global_lib->input($require);
      if (is_numeric($this->input->post('nip'))) {
          $getUser = $this->db_select->select_where('tb_user', 'nip = "'.$this->input->post('nip').'"');
          $cek_lokasi = $this->cekLokasiApel();
          $getDataApel = $this->db_select->select_where('tb_apel', 'id_apel = "'.$this->input->post('id_apel').'"');
          $jam_awal = $getDataApel->jam_mulai;
          $time = $getDataApel->jam_mulai;
          $time2 = $getDataApel->durasi_absen;
          $secs = strtotime($time2)-strtotime("00:00:00");
          $jam_akhir = date("H:i:s",strtotime($time)+$secs);
          $jamSkrg = date("H:i:s");
          if (strtotime($jamSkrg) < strtotime($jam_awal)) {
              $this->result = array(
                  'status' => false,
                  'message' => 'Anda tidak diperbolehkan absen pada waktu sekarang.',
                  'data' => array()
              );
              $this->loghistory->createLog($this->result);
              echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
          }elseif (strtotime($jamSkrg) > strtotime($jam_akhir)) {
              $this->result = array(
                  'status' => false,
                  'message' => 'Anda sudah melewati batas absensi yang telah ditentukan.',
                  'data' => array()
              );
              $this->loghistory->createLog($this->result);
              echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
          }
          $insert['user_id'] = $getUser->user_id;
          $insert['id_apel'] = $this->input->post('id_apel');
          $insert['waktu_absen_apel'] = date("H:i");
          $insert['is_absen'] = 1;
          $insertData = $this->db_dml->normal_insert('tb_absensi_apel', $insert);
          if ($insertData) {
              $this->result = array(
                  'status' => true,
                  'message' => 'Proses absen berhasil',
                  'data' => array()
              );
          }else{
              $this->result = array(
                  'status' => false,
                  'message' => 'Proses absen gagal',
                  'data' => array()
              );
          }
      }else{
          $this->result = array(
              'status' => false,
              'message' => 'Proses absen gagal',
              'data' => array()
          );
      }
      $this->loghistory->createLog($this->result);
      echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
  }

  public function cekLokasiApel()
  {
      $cekUser = $this->db_select->select_where('tb_user','nip = "'.$this->input->post('nip').'"');
      $getApel = $this->db_select->select_where('tb_apel', 'id_apel = '.$this->input->post('id_apel').'');
      $dataLokasi = $this->db_select->select_where('tb_lokasi','id_lokasi = "'.$getApel->id_lokasi.'"');
      $check = $this->polygon->get_position($this->input->post('lat'), $this->input->post('lng'), '', $dataLokasi->url_file_lokasi);
      if ($check) {
          $data['id_lokasi'] = $dataLokasi->id_lokasi;
      }else{
          $data['id_lokasi'] = null;
      }
      if ($data['id_lokasi'] == null) {
          $this->result = array(
              'status' => false,
              'message' => 'Anda berada di luar lokasi atau jaringan yang telah ditentukan',
              'data' => null
          );
          $this->loghistory->createLog($this->result);
          echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
      }else{
          return $data;
      }
  }

  public function potonganSaldo($id, $waktu_datang)
  {
      $cekUser = $this->db_select->query('select a.*, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.user_id = "'.$id.'"');
      
      /* cek pola kerja */
      $tglNow = date('Y-m-d');
      $cekPolaUser = $this->db_select->query('select *from tb_pola_user where user_id = "'.$id.'" and "'.$tglNow.'" between start_pola_kerja and end_pola_kerja');
      if ($cekPolaUser) {
          $getPola = $this->db_select->select_where('tb_pola_kerja','id_pola_kerja = "'.$cekPolaUser->id_pola_kerja.'"');
          $file = '../appconfig/new/'.$getPola->file_pola_kerja;
      }else{
          $wherea['id_channel'] = $cekUser->id_channel;
          $wherea['is_default'] = 1;
          $getPola = $this->db_select->select_where('tb_pola_kerja', $wherea);
          $file = '../appconfig/new/'.$getPola->file_pola_kerja;
      }

      // cek keterlambatan
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
      $newJadwal = date('H:i:s', strtotime($jadwal->$day->from));
      $time = strtotime($newJadwal);
      $startTime = date("H:i:s", strtotime('+'.$dispensasiKeterlambatan.' minutes', $time));
      
      // absen masuk
      $awal = date_create(date('H:i:s', strtotime($waktu_datang)));
      
      // jam masuk
      $akhir = date_create($startTime);
      if ($awal >= $akhir) {
          if ($cekUser->id_channel == 7) {
              $diff = date_diff( $awal, $akhir );
              $newDate = strtotime($diff->h.":".$diff->i.":".$diff->s);
              $newDate = date('H:i:s', $newDate);
              $cekKeterlambatan = $this->db_select->query('select * from tb_potongan_keterlambatan where "'.$newDate.'" < durasi_keterlambatan and id_channel = "'.$cekUser->id_channel.'" limit 1');
              $saldoUser = $cekUser->gaji_pokok;
              if ($cekKeterlambatan) {
                  $pengurangan = $cekKeterlambatan->potongan_keterlambatan*$saldoUser/100;
                  $id_keterlambatan = $cekKeterlambatan->id_keterlambatan;
              }else{
                  $keterlambatanMax = $this->db_select->query('select * from tb_potongan_keterlambatan where id_channel = "'.$cekUser->id_channel.'" order by durasi_keterlambatan desc limit 1');
                  $pengurangan = $keterlambatanMax->potongan_keterlambatan*$saldoUser/100;
                  $id_keterlambatan = $keterlambatanMax->id_keterlambatan;
              }
          }else{
              $diff = date_diff( $awal, $akhir );
              $newDate = strtotime($diff->h.":".$diff->i.":".$diff->s);
              $newDate = date('H:i:s', $newDate);
              $cekKeterlambatan = $this->db_select->query('select * from tb_potongan_keterlambatan where "'.$newDate.'" < durasi_keterlambatan and id_channel = "'.$cekUser->id_channel.'" limit 1');
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

  public function izinPulang()
  {
      $require = array('nip');
      $this->global_lib->input($require);
      $cekUser = $this->db_select->select_where('tb_user', 'nip = "'.$this->input->post('nip').'"');
      $cek = $this->db_select->select_where('tb_absensi', 'user_id = '.$cekUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
      if ($cek) {
            if ($cekUser) {
                  if ($cek->waktu_pulang == null || $cek->waktu_pulang == '') {
                      $insert['user_id'] = $cekUser->user_id;
                      $insert['keterangan_izin_pulang'] = $this->input->post('keterangan_izin_pulang');
                      $insert['status_pengajuan'] = $this->input->post('status_pengajuan');
                      $insertData = $this->db_dml->normal_insert('tb_izin_pulang', $insert);
                      if ($insertData) {
                          $lokasi = $this->cekLokasi();
                          $update['waktu_pulang'] = mdate("%Y-%m-%d %H:%i:%s", time());
                          $update_absen = $this->db_dml->update('tb_absensi', $update, 'user_id = '.$cekUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
                          if ($update_absen) {
                              $input_history = array(
                                  'id_absensi' => $cek->id_absensi,
                                  'lat' => $this->input->post('lat'),
                                  'lng' => $this->input->post('lng'),
                                  'address' => $this->input->post('address'),
                                  'id_jaringan' => $lokasi['id_jaringan'],
                                  'id_lokasi' => $lokasi['id_lokasi']
                                  );
                              $this->db_dml->normal_insert('tb_history_absensi', $input_history);
                          }
                          if ($insert['status_pengajuan'] == 2) {
                              $this->potonganIzinPulang(2, $cekUser->user_id, $cekUser->saldo);
                          }
                          $this->result = array(
                              'status' => true,
                              'message' => 'Data Berhasil Disimpan.',
                              'data' => null
                          );
                      }else{
                          $this->result = array(
                              'status' => false,
                              'message' => 'Data Gagal Disimpan.',
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
                      'message' => 'Maaf anda tidak dapat absen pada hari libur.',
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
      $this->loghistory->createLog($this->result);
      echo json_encode($this->result, JSON_NUMERIC_CHECK);
  }

  public function potonganIzinPulang($id, $user_id, $saldo)
  {
      $selectPotongan = $this->db_select->select_where('tb_potongan_keluar_jamkerja', 'id_meninggalkan_kantor = "'.$id.'"');
      $pengurangan = $selectPotongan->besar_potongan*$saldo/100;
      $insert['id_meninggalkan_kantor'] = $id;
      $insert['user_id'] = $user_id;
      $insert['total_potongan'] = $pengurangan;
      $this->db_dml->normal_insert('tb_hstry_potongan_keluar_jamkerja', $insert);
  }

  public function detailApel()
  {
      $require = array('nip','id_apel');
      $this->global_lib->input($require);
      if (is_numeric($this->input->post('nip')) && is_numeric($this->input->post('id_apel'))) {
          $getApel = $this->db_select->query('select a.nama_apel, a.tanggal_apel, a.jam_mulai, a.deskripsi_apel, b.nama_lokasi from tb_apel a join tb_lokasi b on a.id_lokasi = b.id_lokasi where a.id_apel = "'.$this->input->post('id_apel').'"');
          if ($getApel) {
              $data['jenis_kegiatan'] = $getApel->nama_apel;
              $data['tanggal_kegiatan'] = date('Y-m-d' ,strtotime($getApel->tanggal_apel));
              $data['waktu_kegiatan'] = $getApel->jam_mulai;
              $data['tempat_kegiatan'] = $getApel->nama_lokasi;
              $data['isi_kegiatan'] = $getApel->deskripsi_apel;
              $this->result = array(
                  'status' => true,
                  'message' => 'Data ditemukan',
                  'data' => $data
              );
          }else{
              $this->result = array(
                  'status' => false,
                  'message' => 'Data tidak ditemukan',
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
      echo json_encode($this->result, JSON_NUMERIC_CHECK);
  }

  public function addTracking()
  {
      // data yang wajib dikirimkan
      $require = array('nip','latitude','longtitude');
      $this->global_lib->input($require);
      if (is_numeric($this->input->post('nip'))) {
          // start mengambil data user
          $getUser = $this->db_select->select_where('tb_user','nip = "'.$this->input->post('nip').'"');
          // end mengambil data user
          if ($getUser) {
              // start mengambil data history perjalanan setiap user di hari ini
              $cekTracking = $this->db_select->select_where('tb_perjalanan', 'user_id = "'.$getUser->user_id.'" and date_format(created_perjalanan, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")');
              // end mengambil data history perjalanan setiap user di hari ini
              // jika $cekTracking memiliki data maka update data perjalanan dinas
              if ($cekTracking) {
                  // menambahkan data history perjalanan dinas dengan data terbaru.
                  $dataTracking = json_decode($cekTracking->text_perjalanan);
                  $newText_perjalanan = array(
                      'lat' => $this->input->post('latitude'),
                      'lng' => $this->input->post('longtitude'),
                      'waktu' => date('Y-m-d h:i:s')
                  );
                  array_push($dataTracking, $newText_perjalanan);
                  $update['text_perjalanan'] = json_encode($dataTracking);
                  // update data perjalanan dinas dengan data perjalanan yg baru.
                  $updateData = $this->db_dml->update('tb_perjalanan', $update, 'id_perjalanan = "'.$cekTracking->id_perjalanan.'"');
                  if ($updateData) {
                      $this->result = array(
                          'status' => true,
                          'message' => 'Data berhasil disimpan',
                          'data' => null
                      );
                  }else{
                      $this->result = array(
                          'status' => false,
                          'message' => 'Data gagal disimpan',
                          'data' => null
                      );
                  }
              // jika tidak memiliki data maka insert data perjalanan dinas baru
              }else{
                  $insert['user_id'] = $getUser->user_id;
                  $text_perjalanan[] = array(
                      'lat' => $this->input->post('latitude'),
                      'lng' => $this->input->post('longtitude'),
                      'waktu' => date('Y-m-d h:i:s')
                  );
                  $insert['text_perjalanan'] = json_encode($text_perjalanan);
                  // insert data perjalanan dinas baru.
                  $insertData = $this->db_dml->normal_insert('tb_perjalanan', $insert);
                  if ($insertData) {
                      $this->result = array(
                          'status' => true,
                          'message' => 'Data berhasil disimpan',
                          'data' => null
                      );
                  }else{
                      $this->result = array(
                          'status' => false,
                          'message' => 'Data gagal disimpan',
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
      }
      $this->loghistory->createLog($this->result);
      echo json_encode($this->result, JSON_NUMERIC_CHECK);
  }

  public function stopTracking()
  {
      $require = array('nip');
      $this->global_lib->input($require);
      if (is_numeric($this->input->post('nip'))) {
          // start mengambil data user
          $getUser = $this->db_select->select_where('tb_user','nip = "'.$this->input->post('nip').'"');
          // end mengambil data user
          if ($getUser) {
              $cekTracking = $this->db_select->select_where('tb_perjalanan', 'user_id = "'.$getUser->user_id.'" and date_format(created_perjalanan, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")');
              if ($cekTracking) {
                  $update['end_trip'] = 1;
                  $updateData = $this->db_dml->update('tb_perjalanan', $update, 'id_perjalanan = "'.$cekTracking->id_perjalanan.'"');
                  if ($updateData) {
                      $this->result = array(
                          'status' => true,
                          'message' => 'Data berhasil disimpan',
                          'data' => null
                      );
                  }else{
                      $this->result = array(
                          'status' => false,
                          'message' => 'Data gagal disimpan',
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
          }else{
              $this->result = array(
                  'status' => false,
                  'message' => 'Data tidak ditemukan',
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
      echo json_encode($this->result, JSON_NUMERIC_CHECK);
  }
  
  public function statusAbsensi()
  {
      $require = array('nip');
      $this->global_lib->input($require);
      if (is_numeric($this->input->post('nip'))) {
          $nip = $this->input->post('nip');
          $getUser = $this->db_select->select_where('tb_user', 'nip = "'.$nip.'"');
          if ($getUser) {
              $getData = $this->db_select->query('select *from tb_absensi where user_id = "'.$getUser->user_id.'" and date_format(created_absensi, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")');

              $data['jam_masuk'] = date('H:i', strtotime($getData->waktu_datang));
              $data['jam_keluar'] = date('H:i', strtotime($getData->waktu_pulang));

              if ($getData->waktu_datang == "" || $getData->waktu_datang == null) {
                  $data['jam_masuk'] = "";
              }

              if ($getData->waktu_pulang == "" || $getData->waktu_pulang == null) {
                  $data['jam_keluar'] = "";
              }

              $this->result = array(
                  'status' => true,
                  'message' => 'Data ditemukan',
                  'data' => $data
              );
          }else{
              $this->result = array(
                  'status' => false,
                  'message' => 'Data tidak ditemukan',
                  'data' => null
              );
          }

          $this->loghistory->createLog($this->result);
          echo json_encode($this->result, JSON_NUMERIC_CHECK); exit();
      }
  }

  public function getLastAttendance()
  {
    $getUser = $this->db_select->select_where('tb_user', ['nip' => $this->input->post('nip')]);

    if ($getUser) {
      $getAbsensi = $this->db_select->query('select *from tb_absensi where user_id = '.$getUser->user_id.' order by id_absensi desc');
      $lat = '-6.902491841062216';
      $lng = '107.61882072876503';
      if ($getAbsensi) {
        $getHistoryAbsensi = $this->db_select->query('select *from tb_history_absensi where id_absensi = '.$getAbsensi->id_absensi.' order by id_history_absensi desc');
        if ($getHistoryAbsensi) {
          $lat = $getHistoryAbsensi->lat;
          $lng = $getHistoryAbsensi->lng;
          $result['status'] = true;
          $result['message'] = 'Success';
          $result['data'] = array(
            'lat' => $lat,
            'lng' => $lng
          );
        } else {
          $result['status'] = true;
          $result['message'] = 'Success';
          $result['data'] = array(
            'lat' => $lat,
            'lng' => $lng
          );
        }
      } else {
        $result['status'] = true;
        $result['message'] = 'Success';
        $result['data'] = array(
          'lat' => $lat,
          'lng' => $lng
        );
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data user tidak ditemukan';
      $result['data'] = null;
    }
    
    echo json_encode($result);
  }
}
