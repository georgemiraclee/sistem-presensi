<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends CI_Controller {
  private $file = '../appconfig/auto_respon.txt';
  public function __construct()
  {
    parent::__construct();
    $this->load->library(array('global_lib', 'loghistory','polygon'));
    $this->loghistory = new loghistory();
    $this->polygon = new polygon;
    $this->result = array(
      'status' => false,
      'message' => 'Data tidak ditemukan',
      'data' => null
    );
  }

  public function login() {
      $require = array('nip', 'password');
      $this->global_lib->input($require);

      $user = $this->db_select->query('select *from tb_user where nip like "'.$this->input->post('nip').'" or email_user like "'.$this->input->post('username').'"');
      $password = $this->input->post('password');
      if ($user) {
          if ($user->password_user == md5($password)) {
              if ($user->is_aktif == 1) {
                  $getChannel = $this->db_select->query('select *from tb_unit a join tb_channel b on a.id_channel = b.id_channel where a.id_unit = "'.$user->id_unit.'"');

                  $data['token_user'] = md5($this->random_code());
                  $data['last_login'] = mdate("%Y-%m-%d %H:%i:%s", time());
                  $data['versi'] = $this->input->post('version');
                  $data['reg_id'] = $this->input->post('reg_id');
                  $where['user_id'] = $user->user_id;
                  $update = $this->db_dml->update('tb_user', $data, $where);
                  if ($update == 1) {
                      /* get level user */
                      $level = $this->global_lib->getLevel($user->user_id);

                      if ($user->is_admin == 1) {
                        $akses = 'admin';
                      }else{
                        if ($level) {
                          $akses = 'manager';
                        } else {
                          $akses = 'user';
                        }
                      }

                      if ($user->foto_user != "" || $user->foto_user != null) {
                        $path = realpath('../assets/images/member-photos/' . $user->foto_user);

                        if (file_exists($path)) {
                            $user->foto_user = image_url() . 'images/member-photos/' . $user->foto_user;
                        }else{
                            $user->foto_user = image_url() . 'images/member-photos/default_photo.jpg';
                        }
                      }else{
                        $user->foto_user = image_url() . 'images/member-photos/default_photo.jpg';
                      }

                      $sess['nip'] = $user->nip;
                      $sess['email'] = $user->email_user;
                      $sess['foto'] = $user->foto_user;
                      $sess['token'] = $data['token_user'];
                      $sess['akses'] = $akses;
                      $sess['trial'] = $user->is_trial;
                      $sess['alamat'] = $user->alamat_user;

                      $this->result = array(
                        'status' => true,
                        'message' => 'Login Berhasil',
                        'data' => $sess
                      );

                      /* update reg id yang sama */
                      $this->db->query('update tb_user set reg_id = 1 where user_id != "'.$user->user_id.'" and reg_id = "'.$this->input->post('reg_id').'"');             
                  }else{
                      $this->result = array(
                          'status' => false,
                          'message' => 'Login gagal, silahkan coba lagi.',
                          'data' => null
                      );
                  }
              }else{
                  $this->result = array(
                      'status' => false,
                      'message' => 'Akun Anda telah di non aktifkan karena suatu alasan',
                      'data' => null
                  );
              }
          }else{
              $this->result = array(
                  'status' => false,
                  'message' => 'NIK dan password tidak cocok.',
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

  public function forgot_password()
  {
    $nip = $this->input->post('nip');
    if ($nip) {
      $getData = $this->db_select->select_where('tb_user', ['nip' => $nip]);
      if ($getData) {
        $sendMail = $this->global_lib->sendMail($getData);
        if ($sendMail) {
          $result['status'] = true;
          $result['message'] = 'Silahkan periksa kotak masuk atau spam di email Anda.';
        } else {
          $result['status'] = false;
          $result['message'] = 'Permintaan reset password gagal dilakukan.';
        }
      } else {
        $result['status'] = false;
        $result['message'] = 'Data pegawai tidak ditemukan';
      }
      
      echo json_encode($result);
    }
  }

  public function registration()
  {
      $require = array('nip', 'password', 'ulangi_password', 'nama', 'email', 'telp', 'id_unit');
      $this->global_lib->input($require);
      $where['nip'] = $this->input->post('nip');
      $user = $this->db_select->select_where('tb_user', $where);
      if (!$user) {
          if (is_numeric($where['nip'])) {
              $password = $this->input->post('password');
              $ulangi_password = $this->input->post('ulangi_password');
              if (strlen($password) > 5) {
                  if ($password == $ulangi_password) {
                      $insert['nip'] = $where['nip'];
                      $insert['password_user'] = md5($this->input->post('password'));
                      $insert['nama_user'] = $this->input->post('nama');
                      $insert['email_user'] = $this->input->post('email');
                      $insert['telp_user'] = $this->input->post('telp');
                      $insert['id_unit'] = $this->input->post('id_unit');
                      $insert['is_aktif'] = 0;
                      $registration = $this->db_dml->normal_insert('tb_user', $insert);
                      if ($registration == 1) {
                          $this->result = array(
                              'status' => true,
                              'message' => 'Registrasi berhasil, kami akan verifikasi akun anda.',
                              'data' => null
                          );
                      }else{
                          $this->result = array(
                              'status' => false,
                              'message' => 'Registrasi gagal, silahkan coba lagi',
                              'data' => null
                          );
                      }
                  }else{
                      $this->result = array(
                          'status' => false,
                          'message' => 'Password dan ulangi password tidak cocok.',
                          'data' => null
                      );
                  }
              }else{
                  $this->result = array(
                      'status' => false,
                      'message' => 'Panjang password minimal 6 karakter.',
                      'data' => null
                  );
              }
          }else{
              $this->result = array(
                  'status' => false,
                  'message' => 'NIP hanya berisi angka.',
                  'data' => null
              );
          }
      }else{
          $this->result = array(
              'status' => false,
              'message' => 'NIP telah digunakan',
              'data' => null
          );
      }
      $this->loghistory->createLog($this->result);
      echo json_encode($this->result);
  }

  public function login_by()
  {
    $require = array('email', 'version', 'reg_id');
    $this->global_lib->input($require);

    /* check email registered */
    $email = $this->input->post('email');
    $cekEmail = $this->db_select->select_where('tb_user', 'email_user = "'.$email.'"');

    if ($cekEmail) {
        if ($cekEmail->is_aktif == 1) {
            $data['token_user'] = md5($this->random_code());
            $data['last_login'] = mdate("%Y-%m-%d %H:%i:%s", time());
            $data['versi'] = $this->input->post('version');
            $data['reg_id'] = $this->input->post('reg_id');
            $where['user_id'] = $cekEmail->user_id;
            $update = $this->db_dml->update('tb_user', $data, $where);
            if ($update == 1) {
                $sess['nip'] = $cekEmail->nip;
                $sess['foto'] = (!is_null($cekEmail->foto_user)) ? image_url() . 'images/member-photos/' . $cekEmail->foto_user : image_url() . 'images/member-photos/default_photo.jpg';
                $sess['banner'] = (!is_null($select->banner)) ? image_url() . 'images/banner/' . $select->banner : image_url() . 'images/banner/default.png';
                $sess['token'] = $data['token_user'];
                $sess['akses'] = ($cekEmail->is_admin == 1) ? 'admin' : 'user';
                $sess['trial'] = $cekEmail->is_trial;
                $this->result = array(
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'data' => $sess
                );
                
                /* update reg id yang sama */
                $this->db->query('update tb_user set reg_id = 1 where user_id != "'.$cekEmail->user_id.'" and reg_id = "'.$this->input->post('reg_id').'"');
            }else{
                $this->result = array(
                    'status' => false,
                    'message' => 'Login gagal, silahkan coba lagi.',
                    'data' => null
                );
            }
        }else{
            $this->result = array(
                'status' => false,
                'message' => 'Akun Anda telah di non aktifkan karena suatu alasan',
                'data' => null
            );
        }
    }else{
        $this->result = array(
            'status' => false,
            'message' => 'Akun tidak ditemukan.',
            'data' => null
        );
    }

    $this->loghistory->createLog($this->result);
    echo json_encode($this->result);
  }

  public function random_code() {
      $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $charactersLength = strlen($characters);
      $randomString = "";
      for ($i = 0; $i < 6; $i++) {
              $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }

  public function tidakHadir()
  {
      $pegawai = $this->db_select->query_all('select a.*, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_admin = 0 and a.is_aktif = 1');
      if ($pegawai) {
          foreach ($pegawai as $key => $value) {
              $file = '../appconfig/'.$value->id_channel.'_auto_respon.txt';
              $this->tidakApel($value);
              $absensi = $this->db_select->query('select id_absensi from tb_absensi where user_id = '.$value->user_id.' and date_format(created_absensi,"%Y-%m-%d") = "'.date('Y-m-d').'"');
              if (!$absensi) {
                  $newwhere['user_id'] = $value->user_id;
                  $cek_cuti = $this->db_select->query('select *from tb_pengajuan where user_id = "'.$value->user_id.'" and date_format(now(), "%Y-%m-%d") >= date_format(tanggal_awal_pengajuan, "%Y-%m-%d") and date_format(now(), "%Y-%m-%d") <= date_format(tanggal_akhir_pengajuan, "%Y-%m-%d")');
                  
                  //Cek Libur
                  $tanggalSekarang = date('l');
                  $tanggalSekarang2 = date('Y-m-d');
                  $tanggalEvent = $this->db_select->select_where('tb_event', 'tanggal_event = "'.$tanggalSekarang2.'"');
                  if (!$tanggalEvent) {
                      if ($tanggalSekarang != "Saturday" && $tanggalSekarang != "Sunday") {
                          if ($cek_cuti) {
                              if ($cek_cuti->status_approval == 0 || $cek_cuti->status_approval == 2) {
                                  $jadwal = json_decode(file_get_contents($file))->jam_kerja;
                                  $day = strtolower(date("l"));
                                  $newJadwal = $jadwal->$day;
                                  $now = date('H:i');
                                  $to = strtotime(date($newJadwal->to));
                                  $time = strtotime($now);
                                  if ($newJadwal) {
                                      if($time >= $to){
                                          $insert['user_id'] = $value->user_id;
                                          $insert['status_absensi'] = 'Tidak Hadir';
                                          $insertdb = $this->db_dml->normal_insert('tb_absensi', $insert);
                                          if ($insertdb) {
                                              $getPotongan = $this->db_select->select_where('tb_potongan_mangkir', 'id_channel = "'.$value->id_channel.'" and nama_tipe = "Tanpa Keterangan"');
                                              $this->potonganMangkir($getPotongan->id_mangkir, $value->user_id, $value->saldo);
                                          }
                                      }
                                  }
                              }elseif ($cek_cuti->status_approval == 1) {
                                  $jadwal = json_decode(file_get_contents($file))->jam_kerja;
                                  $day = strtolower(date("l"));
                                  $newJadwal = $jadwal->$day;
                                  $now = date('H:i');
                                  $to = strtotime(date($newJadwal->to));
                                  $time = strtotime($now);
                                  if ($newJadwal) {
                                      if($time >= $to){
                                          $status_cuti = $this->db_select->select_where('tb_status_pengajuan','id_status_pengajuan = '.$cek_cuti->status_pengajuan);
                                          $insert['user_id'] = $value->user_id;
                                          $insert['status_absensi'] = $status_cuti->nama_status_pengajuan;
                                          $insertdb = $this->db_dml->normal_insert('tb_absensi', $insert);
                                          if ($insertdb) {
                                              if ($cek_cuti->status_pengajuan == 2) {
                                                  $getPotongan = $this->db_select->select_where('tb_potongan_mangkir', 'id_channel = "'.$value->id_channel.'" and nama_tipe = "Keperluan Pribadi"');
                                                  $this->potonganMangkir($getPotongan->id_mangkir, $value->user_id, $value->saldo);
                                              }
                                          }
                                      }
                                  }
                              }
                          }else{
                              $where['nip'] = $value->nip;
                              $where['created_absensi'] = date('Y-m-d');
                              $Newabsensi = $this->db_select->query('select id_absensi from tb_absensi where user_id = '.$value->user_id.' and date_format(created_absensi,"%Y-%m-%d") = "'.date('Y-m-d').'"');
                              if (!$Newabsensi) {
                                  $jadwal = json_decode(file_get_contents($file))->jam_kerja;
                                  $day = strtolower(date("l"));
                                  $newJadwal = $jadwal->$day;
                                  $now = date('H:i');
                                  $to = strtotime(date($newJadwal->to));
                                  $time = strtotime($now);
                                  if ($newJadwal) {
                                      if($time >= $to){
                                          $insert['user_id'] = $value->user_id;
                                          $insert['status_absensi'] = 'Tidak Hadir';
                                          $insertdb = $this->db_dml->normal_insert('tb_absensi', $insert);
                                          if ($insertdb) {
                                              $getPotongan = $this->db_select->select_where('tb_potongan_mangkir', 'id_channel = "'.$value->id_channel.'" and nama_tipe = "Tanpa Keterangan"');
                                              $this->potonganMangkir($getPotongan->id_mangkir, $value->user_id, $value->saldo);
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
  }

  public function convertTanggal($dari, $sampai)
  {
      $tgl1 = $dari;  // 1 Oktober 2009
      $tgl2 = $sampai;  // 10 Oktober 2009
      // memecah tanggal untuk mendapatkan bagian tanggal, bulan dan tahun
      // dari tanggal pertama
      $pecah1 = explode("-", $tgl1);
      $date1 = $pecah1[2];
      $month1 = $pecah1[1];
      $year1 = $pecah1[0];
      // memecah tanggal untuk mendapatkan bagian tanggal, bulan dan tahun
      // dari tanggal kedua
      $pecah2 = explode("-", $tgl2);
      $date2 = $pecah2[2];
      $month2 = $pecah2[1];
      $year2 =  $pecah2[0];
      // menghitung JDN dari masing-masing tanggal
      $jd1 = GregorianToJD($month1, $date1, $year1);
      $jd2 = GregorianToJD($month2, $date2, $year2);
      // hitung selisih hari kedua tanggal
      $selisih = $jd2 - $jd1;
      return abs($selisih);
  }

  public function tidakApel($data)
  {
      $file = '../appconfig/'.$data->id_channel.'_auto_respon.txt';
      $cekApel = $this->db_select->query('select id_apel from tb_apel where date_format(tanggal_apel, "%Y-%m-%d") = "'.date('Y-m-d').'"');
      if ($cekApel) {
          $jadwal = json_decode(file_get_contents($file))->jam_kerja;
          $day = strtolower(date("l"));
          $newJadwal = $jadwal->$day;
          $now = date('H:i');
          $to = strtotime(date($newJadwal->to));
          $time = strtotime($now);
          if ($newJadwal) {
              if($time >= $to){
                  $cekAbsenApel = $this->db_select->query('select id_absensi_apel from tb_absensi_apel where user_id = "'.$data->user_id.'" and date_format(created_absensi_apel, "%Y-%m-%d") = "'.date('Y-m-d').'" and is_absen = 1');
                  if (!$cekAbsenApel) {
                      $newwhere['user_id'] = $data->user_id;
                      $cek_cuti = $this->db_select->query('select status_approval from tb_pengajuan where user_id = "'.$value->user_id.'" and date_format(now(), "%Y-%m-%d") >= date_format(tanggal_awal_pengajuan, "%Y-%m-%d") and date_format(now(), "%Y-%m-%d") <= date_format(tanggal_akhir_pengajuan, "%Y-%m-%d")');
                      if ($cek_cuti) {
                          if ($cek_cuti->status_approval == 0 || $cek_cuti->status_approval == 2) {
                              $insert['user_id'] = $data->user_id;
                              $insert['is_absen'] = 0;
                              $insert = $this->db_dml->normal_insert('tb_absensi_apel', $insert);
                              if ($insert) {
                                  $getPotongan = $this->db_select->select_where('tb_potongan_apel', 'id_channel = "'.$data->id_channel.'" and nama_tipe = "Tanpa Keterangan"');
                                  $this->potonganApel($getPotongan->id_potongan_apel, $data->user_id, $data->saldo);
                              }
                          }
                      }else{
                          $cekIzinApel = $this->db_select->select_where('tb_pengajuan_apel', 'user_id = "'.$data->user_id.'" and id_apel = "'.$cekApel->id_apel.'"');
                          if ($cekIzinApel) {
                              if ($cekIzinApel->status_approval == 0) {
                                  $insert['user_id'] = $data->user_id;
                                  $insert['is_absen'] = 0;
                                  $insert = $this->db_dml->normal_insert('tb_absensi_apel', $insert);
                                  if ($insert) {
                                      $getPotongan = $this->db_select->select_where('tb_potongan_apel', 'id_channel = "'.$data->id_channel.'" and nama_tipe = "Tanpa Keterangan"');
                                      $this->potonganApel($getPotongan->id_potongan_apel, $data->user_id, $data->saldo);
                                  }
                              }elseif ($cekIzinApel->status_approval == 1) {
                                  $insert['user_id'] = $data->user_id;
                                  $insert['is_absen'] = 0;
                                  $insert = $this->db_dml->normal_insert('tb_absensi_apel', $insert);
                                  if ($insert) {
                                      if ($cekIzinApel->status_pengajuan_apel == 2) {
                                          $getPotongan = $this->db_select->select_where('tb_potongan_apel', 'id_channel = "'.$data->id_channel.'" and nama_tipe = "Sakit (Tanpa Surat Dokter)"');
                                          $this->potonganApel($getPotongan->id_potongan_apel, $data->user_id, $data->saldo);
                                      }
                                  }
                              }
                          }else{
                              $insert['user_id'] = $data->user_id;
                              $insert['is_absen'] = 0;
                              $insert = $this->db_dml->normal_insert('tb_absensi_apel', $insert);
                              if ($insert) {
                                  $getPotongan = $this->db_select->select_where('tb_potongan_apel', 'id_channel = "'.$data->id_channel.'" and nama_tipe = "Tanpa Keterangan"');
                                  $this->potonganApel($getPotongan->id_potongan_apel, $data->user_id, $data->saldo);
                              }
                          }
                      }
                  }
              }
          }
      }
  }

  public function potonganApel($id, $user_id, $saldo)
  {
    $tanggalSekarang = date('Y-m-d');
    $cekPotongan = $this->db_select->query('select * from tb_hstry_potongan_apel where user_id = "'.$user_id.'" and date_format(created_hstry_potongan_apel, "%Y-%m-%d") = "'.$tanggalSekarang.'"');
    if (!$cekPotongan) {
      $selectPotongan = $this->db_select->select_where('tb_potongan_apel', 'id_potongan_apel = "'.$id.'"');
      $pengurangan = $selectPotongan->besar_potongan*$saldo/100;
      $insert['id_potongan_apel'] = $id;
      $insert['user_id'] = $user_id;
      $insert['total_potongan'] = $pengurangan;
      $this->db_dml->normal_insert('tb_hstry_potongan_apel', $insert);
    }
  }

  public function potonganMangkir($id, $user_id, $saldo)
  {
    $tanggalSekarang = date('Y-m-d');
    $cekPotongan = $this->db_select->query('select * from tb_hstry_potongan_mangkir where user_id = "'.$user_id.'" and date_format(created_hstry_mangkir, "%Y-%m-%d") = "'.$tanggalSekarang.'"');
    if (!$cekPotongan) {
      $selectPotongan = $this->db_select->select_where('tb_potongan_mangkir', 'id_mangkir = "'.$id.'"');
      if ($selectPotongan) {
        if ($selectPotongan->besar_potongan == "") {
          $besar_potongan = 0;
        }else{
          $besar_potongan = $selectPotongan->besar_potongan;
        }
      }else{
        $besar_potongan = 0;
      }
      $pengurangan = $besar_potongan*$saldo/100;
      $insert['id_mangkir'] = $id;
      $insert['user_id'] = $user_id;
      $insert['total_potongan'] = $pengurangan;
      $this->db_dml->normal_insert('tb_hstry_potongan_mangkir', $insert);
    }
  }
  
  public function fcm()
  {
    // FCM ULTAH
    $getUser = $this->db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_admin = 0 and a.is_aktif = 1');
    $dateNow = date('m-d');
    foreach ($getUser as $key => $value) {
      if ($value->user_id != 5042) {
        if ($dateNow == date('m-d', strtotime($value->tanggal_lahir))) {
          $message = $value->nama_user." Berulang Tahun Hari Ini.";
          $this->global_lib->send_notification2('ulangtahun', $value->id_channel, $message, null);
          $this->global_lib->sendFCMAll2('Ucapkan Selamat Ulang Tahun', $message, $value->id_channel);
        }
      }
    }
  }
  
  public function absenKeluar()
  {
    $pegawai = $this->db_select->query_all('select a.*, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_admin = 0 and a.is_aktif = 1');
    if (count($pegawai) >= 0) {
        foreach ($pegawai as $key => $value) {
            /*file jam kerja*/
            $wherea['id_channel'] = $value->id_channel;
            $wherea['is_default'] = 1;
            $getPola = $this->db_select->select_where('tb_pola_kerja', $wherea);
            $file = '../appconfig/new/'.$getPola->file_pola_kerja;

            $absensi = $this->db_select->query('select *from tb_absensi where user_id = '.$value->user_id.' and date_format(created_absensi,"%Y-%m-%d") = "'.date('Y-m-d').'"');
            if ($absensi) {
                if ($absensi->waktu_pulang == null || $absensi->waktu_pulang == "") {
                    $jadwal = json_decode(file_get_contents($file))->jam_kerja;
                    $day = strtolower(date("l"));
                    $newJadwal = $jadwal->$day;
                    $newJadwal = strtotime(date($newJadwal->to));
                    /*update data*/
                    $whereUpdate['id_absensi'] = $absensi->id_absensi;
                    $update['waktu_pulang'] = date('Y-m-d H:i:s', $newJadwal);
                    $this->db_dml->update('tb_absensi',$update,$whereUpdate);
                }
            }
        }
    }
  }

  public function resetCuti()
  {
      $where['is_aktif'] = 1;
      $where['is_admin'] = 0;
      $where['is_superadmin'] = 0;
      $getUser = $this->db_select->select_all_where('tb_user', $where);

      foreach ($getUser as $key => $value) {
          $tglNow = date('Y-m-d');
          if ($value->tanggal_kerja == "" || $value->tanggal_kerja == null) {
              $tglKerja = date('Y-m-d', strtotime(date("Y-m-d", strtotime($value->created_user)) . " + 1 year"));
          }else{
              $tglKerja = date('Y-m-d', strtotime(date("Y-m-d", strtotime($value->tanggal_kerja)) . " + 1 year"));
          }

          if ($tglNow == $tglKerja) {
              $whereUpdate['user_id'] = $value->user_id;
              $update['cuti'] = 0;

              $this->db_dml->update('tb_user', $update, $whereUpdate);
          }
      }
  }

  public function tesFCM()
  {
      $timestamp = date("Y-m-d H:i:s");
      $fcmMsg = array(
          "image" => "",
          "timestamp" => $timestamp,
          "title" => "Notification title",
          "message" => "Notification message",
          "is_background" => true
      );
      $fcmFields = array(
          'to' => 'c0exkiYMVV4:APA91bFq05IxhNDiwiwiknLfEn1uFGpGGZxYlVM_PM2T22mHEjsYr-8EDIeOQf3SdwN5Qe88qAG5ReAtmpVP3FWkU4p-3I0hQ4eIMcsKFmbwQAxruYIJg2g3ff4phFHyMBuEp56iWy34',
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
  }

  public function checkVersion()
  {
    $get = $this->db_select->query('select nama_versi version from tb_versi where id_versi = 1');

    $result['status'] = true;
    $result['message'] = 'Data not found';
    $result['data'] = $get;

    echo json_encode($result);
  }

  public function checkExpired()
  {
    $require = array('nip');
    $this->global_lib->input($require);

    $nip = $this->input->post('nip');

    if (is_numeric($nip)) {
        /* get data user */
        $where['nip'] = $nip;
        $getUser = $this->db_select->select_where('tb_user', $where);

        if ($getUser->is_actived == 0) {
            /* user masih aktif */
            $this->result = array(
                'status' => true,
                'message' => 'Actived',
                'data' => null
            );
        }else{
            /* user sudah tidak aktif */
            $this->result = array(
                'status' => false,
                'message' => 'Masa aktif user sudah habis',
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
}