<?php defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class pegawai extends CI_Controller
{
  private $file = 'appconfig/auto_respon.txt';
  private $jumlahTotal = array();

  function __construct()
  {
    parent::__construct();
    $this->load->library('ceksession');
    $this->ceksession->login();
  }

  public function index()
  {
    $user = $this->session->userdata('user');
    $id_channel = $user['id_channel'];
    if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama" && $user['role_access'] != '1') {
      redirect(base_url());
      exit();
    }
    /* cek user limit */
    $channel = $this->Db_select->select_where('tb_channel', ['id_channel' => $id_channel], ['limit_user']);
    $getTotalUser = $this->Db_select->query("SELECT COUNT(*) total FROM tb_user a JOIN tb_unit b ON a.id_unit = b.id_unit WHERE b.id_channel = ? ", [$id_channel])->total;
    if ($getTotalUser >= $channel->limit_user) {
      $data['tombol'] = '<button class="btn btn-warning text-white" onclick="limit()"><span class="fa fa-plus"></span> Tambah Staff</button>';
    } else {
      $data['tombol'] = '<a href="' . base_url() . 'Administrator/pegawai/add" class="btn btn-warning text-white"><span class="fa fa-plus"></span> Tambah Staff</a>';
    }
    $data['jabatan'] = $this->Db_select->query_all("SELECT id_jabatan, nama_jabatan FROM tb_jabatan WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ? ORDER BY nama_jabatan ASC", [$id_channel, 1, 0]);

    $data['departemen'] = $this->Db_select->query_all("SELECT id_unit, nama_unit FROM tb_unit WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ?", [$id_channel, 1, 0]);
    $data['tipe'] = $this->Db_select->query_all("SELECT id_status_user, nama_status_user FROM tb_status_user WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ?", [$id_channel, 1, 0]);
    /* menu active */
    $menu['main'] = 'personalia';
    $menu['child'] = 'personalia_staff';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/pegawai');
    $this->load->view('Administrator/footer');
  }

  public function import()
  {
    $this->load->view('Administrator/header');
    $this->load->view('Administrator/import_pegawai');
    $this->load->view('Administrator/footer');
  }

  public function import_data()
  {
    // Load plugin PHPExcel nya
    include APPPATH . 'third_party/PHPExcel.php';
    $sess = $this->session->userdata('user');
    $excelreader = new PHPExcel_Reader_Excel2007();
    $loadexcel = $excelreader->load($_FILES['userfile']['tmp_name']); // Load file yang telah diupload ke folder excel
    $loadexcel->setActiveSheetIndex(0);
    $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);
    // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database

    $numrow = 1;
    foreach ($sheet as $row) {
      // Cek $numrow apakah lebih dari 1
      // Artinya karena baris pertama adalah nama-nama kolom
      // Jadi dilewat saja, tidak usah diimport
      if ($numrow > 1) {
        /* create data user */
        $whereUser['nip'] = $row['B'];
        $checkUser = $this->Db_select->select_where('tb_user', $whereUser);
        if (!$checkUser) {
          /* create data departemen */
          $whereDepartemen['id_channel'] = $sess['id_channel'];
          $whereDepartemen['nama_unit'] = $row['N'];
          $checkDepartemen = $this->Db_select->select_where('tb_unit', $whereDepartemen);
          if ($checkDepartemen) {
            $idDepartemen = $checkDepartemen->id_unit;
          } else {
            $departemen['nama_unit'] = $row['N'];
            $departemen['is_aktif'] = 1;
            $departemen['id_channel'] = $sess['id_channel'];

            $idDepartemen = $this->Db_dml->insert('tb_unit', $departemen);
          }
          /* create data jabatan */
          $whereJabatan['id_channel'] = $sess['id_channel'];
          $whereJabatan['nama_jabatan'] = $row['O'];
          $checkJabatan = $this->Db_select->select_where('tb_jabatan', $whereJabatan);
          if ($checkJabatan) {
            $idJabatan = $checkJabatan->id_jabatan;
          } else {
            $jabatan['nama_jabatan'] = $row['O'];
            $jabatan['id_channel'] = $sess['id_channel'];

            $idJabatan = $this->Db_dml->insert('tb_jabatan', $jabatan);
          }
          /* create status user */
          $whereStatus['id_channel'] = $sess['id_channel'];
          $whereStatus['nama_status_user'] = $row['M'];
          $checkStatus = $this->Db_select->select_where('tb_status_user', $whereStatus);
          if ($checkStatus) {
            $idStatusUser = $checkStatus->id_status_user;
          } else {
            $statusUser['nama_status_user'] = $row['M'];
            $statusUser['id_channel'] = $sess['id_channel'];

            $idStatusUser = $this->Db_dml->insert('tb_status_user', $statusUser);
          }
          /* check jenis kelamin */
          $str = strtolower($row['D']);
          if (strpos($str, 'laki') !== false) {
            $jk = "l";
          } elseif (strpos($str, 'pria') !== false) {
            $jk = "l";
          } elseif (strpos($str, 'wanita') !== false) {
            $jk = "p";
          } elseif (strpos($str, 'perempuan') !== false) {
            $jk = "p";
          }
          if (is_numeric($row['P'])) {
            $gaji = $row['P'];
          } else {
            $gaji = 0;
          }
          $user['nip'] = $row['B'];
          $user['password'] = md5('12345');
          $user['nama_user'] = $row['C'];
          $user['jenis_kelamin'] = $jk;
          $user['tempat_lahir'] = $row['E'];
          $user['tanggal_lahir'] = date('Y-m-d H:i:s', strtotime($row['F']));
          $user['agama'] = $row['G'];
          $user['status_pernikahan'] = $row['H'];
          $user['tanggal_kerja'] = date('Y-m-d H:i:s', strtotime($row['I']));
          $user['telp_user'] = $row['J'];
          $user['email_user'] = $row['K'];
          $user['alamat_user'] = $row['L'];
          $user['status_user'] = $idStatusUser;
          $user['id_unit'] = $idDepartemen;
          $user['jabatan'] = $idJabatan;
          $user['gaji_pokok'] = $gaji;
          $this->Db_dml->insert('tb_user', $user);
        }
      } else {
        if ($row['A'] != 'No') {
          echo 'salah';
        } elseif ($row['B'] != 'NIP') {
          echo 'salah';
        } elseif ($row['C'] != 'Nama Lengkap') {
          echo 'salah';
        } elseif ($row['D'] != 'Jenis Kelamin') {
          echo 'salah';
        } elseif ($row['E'] != 'Tempat Lahir') {
          echo 'salah';
        } elseif ($row['F'] != 'Tanggal Lahir') {
          echo 'salah';
        } elseif ($row['G'] != 'Agama') {
          echo 'salah';
        } elseif ($row['H'] != 'Status Pernikahan') {
          echo 'salah';
        } elseif ($row['I'] != 'Bekerja Sejak') {
          echo 'salah';
        } elseif ($row['J'] != 'Nomor Telepon') {
          echo 'salah';
        } elseif ($row['K'] != 'Email') {
          echo 'salah';
        } elseif ($row['L'] != 'Alamat') {
          echo 'salah';
        } elseif ($row['M'] != 'Status Pekerjaan') {
          echo 'salah';
        } elseif ($row['N'] != 'Departemen') {
          echo 'salah';
        } elseif ($row['O'] != 'Jabatan') {
          echo 'salah';
        } elseif ($row['P'] != 'Gaji Pokok') {
          echo 'salah';
        }
      }

      $numrow++; // Tambah 1 setiap kali looping
    }
    $result['status'] = true;
    $result['message'] = "Success";
    $result['data'] = array();
    echo json_encode($result);
    exit();
  }

  public function utf8_converter($array)
  {
    array_walk_recursive($array, function (&$item, $key) {
      if (!mb_detect_encoding($item, 'utf-8', true)) {
        $item = utf8_encode($item);
      }
    });

    return $array;
  }

  public function add()
  {
    $user = $this->session->userdata('user');
    $id_channel = $user['id_channel'];
    $data_staff = $this->Db_select->query_all("SELECT user_id FROM tb_user a JOIN tb_unit b ON a.id_unit = b.id_unit");
    $data['departemen'] = $this->Db_select->query_all("SELECT id_unit, nama_unit FROM tb_unit WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ? ORDER BY nama_unit ASC", [$id_channel, 1, 0]);
    $data['jabatan'] = $this->Db_select->query_all("SELECT id_jabatan, nama_jabatan FROM tb_jabatan WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ? ORDER BY nama_jabatan ASC", [$id_channel, 1, 0]);
    $data['status_staff'] = $this->Db_select->select_all_where('tb_status_user', [
      'id_channel' => $id_channel,
      'is_aktif' => 1,
      'is_deleted' => 0
    ], ['id_status_user', 'nama_status_user']);
    $struktur_organisasi = $this->Db_select->select_where('tb_struktur_organisasi', ['id_channel' => $id_channel], ['struktur_data']);
    $data['posisi'] = json_decode($struktur_organisasi->struktur_data);

    foreach ($data_staff as $key => $value) {
      $selectUser = $this->Db_select->select_where('tb_absensi', ['user_id' => $value->user_id], ['id_absensi']);
      if ($selectUser) {
        $value->delete = false;
      } else {
        $value->delete = true;
      }
    }

    /* get komponen pendapatan */
    $getKomponen = $this->Db_select->query_all("SELECT id_komponen_pendapatan, nama_komponen_pendapatan FROM tb_komponen_pendapatan WHERE id_channel = ? ", [$id_channel]);
    $data['getKomponen'] = "";

    foreach ($getKomponen as $key => $value) {
      $isi = 0;
      $data['getKomponen'] .= '
			  <div class="col-md-12" >	
				  <div class="form-group">
					  <label class="form-label">' . $value->nama_komponen_pendapatan . ' <span style="color: red;">*</span></label>
					  <div class="form-line">
						  <input type="number" onKeyPress="return goodchars(event,"0123456789",this)" name="komponen_pendapatan' . $value->id_komponen_pendapatan . '" class="form-control" placeholder="' . $value->nama_komponen_pendapatan . '" required="" value="' . $isi . '">
					  </div>
				  </div>
			  </div>';
    }

    $data['list_is_aktif'] = '<option value="1" selected>Aktif</option>
			<option value="0">Nonaktif</option>';

    $menu['main'] = 'personalia';
    $menu['child'] = 'personalia_staff';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/tambah_pegawai');
    $this->load->view('Administrator/footer');
  }

  public function insert()
  {
    $user = $this->session->userdata('user');
    $cekEmail = $this->Db_select->query("SELECT user_id FROM tb_user a JOIN tb_unit b ON a.id_unit = b.id_unit WHERE a.is_deleted = 0 and id_channel = ? AND a.is_aktif = ? AND a.email_user = ?", [$user['id_channel'], 1, $this->input->post('email_user', true)]);

    if (!$cekEmail) {
      $cekNip = $this->Db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_aktif = 1 and a.is_deleted = 0 and b.id_channel = "'.$user['id_channel'].'" and a.nip = "'.$this->input->post('nip').'"');
      if ($cekNip) {
        $result['status'] = false;
        $result['message'] = 'NIP telah digunakan.';
        $result['data'] = array();
        echo json_encode($result);
        exit();
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'E-email telah digunakan.';
      $result['data'] = array();
      echo json_encode($result);
      exit();
    }

    $data['nama_user'] = $this->input->post('nama_user', true);
    $data['nip'] = $this->input->post('nip', true);
    $data['jenis_kelamin'] = $this->input->post('jenis_kelamin', true);
    $data['tempat_lahir'] = $this->input->post('tempat_lahir', true);
    $data['agama'] = $this->input->post('agama', true);
    $data['status_pernikahan'] = $this->input->post('status_pernikahan', true);
    $data['telp_user'] = $this->input->post('telp_user', true);
    $data['email_user'] = $this->input->post('email_user', true);
    $data['alamat_user'] = $this->input->post('alamat_user', true);
    $data['id_unit'] = $this->input->post('id_unit', true);
    $data['id_divisi'] = $this->input->post('id_divisi', true);
    $data['jabatan'] = $this->input->post('jabatan', true);
    $data['status_user'] = $this->input->post('status_user', true);
    $data['tanggal_lahir'] = date("Y-m-d", strtotime($this->input->post('tanggal_lahir', true)));
    $data['password_user'] = md5($this->input->post('password', true));
    $data['id_struktur'] = $this->input->post('id_struktur', true);
    $data['id_parent'] = $this->input->post('id_parent', true) ? $this->input->post('id_parent', true) : null;
    $data['nama_bidang'] = $this->input->post('nama_bidang', true);
    $data['saldo'] = $this->input->post('saldo', true);
    $data['is_aktif'] = $this->input->post('is_aktif', true);
    $data['gaji_pokok'] = $this->input->post('gaji_pokok', true);
    $data['cuti'] = $this->input->post('cuti', true);
    $data['nomor_identitas'] = $this->input->post('no_identitas', true);
    $data['role'] = $this->input->post('role', true);

    $data['jenis_nomor_identitas'] = $this->input->post('jenis_nomor_identitas', true);
    $data['nama_kontak_darurat'] = $this->input->post('nama_kontak_darurat', true);
    $data['telepon_kontak_darurat'] = $this->input->post('nomor_kontak_darurat', true);
    $data['tanggal_kerja'] = date("Y-m-d", strtotime($this->input->post('tanggal_kerja', true)));
    $data['tanggal_akhir'] = date("Y-m-d", strtotime($this->input->post('tanggal_akhir', true)));

    if ($_FILES['userfile']['name']) {
      $nmfile = "file_" . time(); //nama file saya beri nama langsung dan diikuti fungsi time
      $config['upload_path'] = './assets/images/member-photos'; //path folder
      $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
      $config['max_size'] = '2048'; //maksimum besar file 2M
      $config['file_name'] = $nmfile; //nama yang terupload nantinya

      $this->load->library('upload', $config);
      $this->upload->initialize($config);

      if ($this->upload->do_upload('userfile')) {
        $gbr = $this->upload->data();
        $name3 = $gbr['file_name'];
        $data['foto_user'] = $name3;
      } else {
        $result['status'] = false;
        $result['message'] = $this->upload->display_errors();
        $result['data'] = array();

        echo json_encode($result);
        exit();
      }
    }
    $insert = $this->Db_dml->insert('tb_user', $data);

    if ($insert) {
      // $instansi  = $this->input->post('nama_instansi_pendidikan', true);
      // $pekerjaan = $this->input->post('perusahaan', true);
      // foreach ($instansi as $value) {
      //   $data_instansi['user_id'] = $insert;
      //   if (isset($value['jenis_pendidikan'])) {
      //     $data_instansi['jenis_pendidikan'] = $value['jenis_pendidikan'];
      //   } else {
      //     $data_instansi['jenis_pendidikan'] = null;
      //   }
      //   if (isset($value['nama'])) {
      //     $data_instansi['nama_instansi_pendidikan'] = $value['nama'];
      //   } else {
      //     $data_instansi['nama_instansi_pendidikan'] = null;
      //   }
      //   if (isset($value['jurusan'])) {
      //     $data_instansi['jurusan'] = $value['jurusan'];
      //   } else {
      //     $data_instansi['jurusan'] = null;
      //   }
      //   if (isset($value['tahun_masuk'])) {
      //     $data_instansi['tahun_masuk'] = $value['tahun_masuk'];
      //   } else {
      //     $data_instansi['tahun_masuk'] = null;
      //   }
      //   if (isset($value['tahun_lulus'])) {
      //     $data_instansi['tahun_lulus'] = $value['tahun_lulus'];
      //   } else {
      //     $data_instansi['tahun_lulus'] = null;
      //   }

      //   $this->Db_dml->insert('tb_riwayat_pendidikan', $data_instansi);
      // }

      // foreach ($pekerjaan as $value) {
      //   $data_pekerjaan['user_id'] = $insert;
      //   if (isset($value['id_form'])) {
      //     $data_pekerjaan['id_form'] = $value['id_form'];
      //   } else {
      //     $data_pekerjaan['id_form'] = null;
      //   }
      //   if (isset($value['nama_perusahaan'])) {
      //     $data_pekerjaan['nama_perusahaan'] = $value['nama_perusahaan'];
      //   } else {
      //     $data_pekerjaan['nama_perusahaan'] = null;
      //   }
      //   if (isset($value['posisi'])) {
      //     $data_pekerjaan['posisi'] = $value['posisi'];
      //   } else {
      //     $data_pekerjaan['posisi'] = null;
      //   }
      //   if (isset($value['deskripsi'])) {
      //     $data_pekerjaan['deskripsi'] = $value['deskripsi'];
      //   } else {
      //     $data_pekerjaan['deskripsi'] = null;
      //   }
      //   if (isset($value['lama_bekerja'])) {
      //     $data_pekerjaan['lama_bekerja'] = $value['lama_bekerja'];
      //   } else {
      //     $data_pekerjaan['lama_bekerja'] = null;
      //   }
      //   if (isset($value['alasan_berhenti'])) {
      //     $data_pekerjaan['alasan_berhenti'] = $value['alasan_berhenti'];
      //   } else {
      //     $data_pekerjaan['alasan_berhenti'] = null;
      //   }
      //   $insert_perusahaan = $this->Db_dml->insert('tb_riwayat_pekerjaan', $data_pekerjaan);
      // }
      $getKomponen = $this->Db_select->select_all_where('tb_komponen_pendapatan', [
        'id_channel' => $user['id_channel']
      ], ['id_komponen_pendapatan']);
      foreach ($getKomponen as $key => $value) {
        $namaParam = "komponen_pendapatan" . $value->id_komponen_pendapatan;
        // insert
        $insertPendapatan['user_id'] = $insert;
        $insertPendapatan['id_komponen_pendapatan'] = $value->id_komponen_pendapatan;
        $insertPendapatan['nominal'] = $this->input->post($namaParam, true);
        $insertData = $this->Db_dml->insert('tb_pendapatan', $insertPendapatan);
      }

      $result['status'] = true;
      $result['message'] = 'Data berhasil disimpan.';
      $result['data'] = array();
    } else {
      $result['status'] = false;
      $result['message'] = 'Data gagal disimpan.';
      $result['data'] = array();
    }

    echo json_encode($result);
  }

  public function update()
  {
    $sess = $this->session->userdata('user');

    /* set default value */
    $update = false;
    $updateData = false;
    $insertData = false;
    $insert_perusahaan = false;
    $insert_instansi = false;

    $getUser = $this->Db_select->select_where('tb_user', 'user_id = ' . $this->input->post('user_id'));
    $email = $this->input->post('email_user');
    $nip = $this->input->post('nip');
    $telp_user = $this->input->post('telp_user');

    if ($email != $getUser->email_user) {
      $whereEmail['email_user'] = $this->input->post('email_user');
      $cekEmail = $this->Db_select->select_where('tb_user', $whereEmail);

      if ($cekEmail) {
        $result['status'] = false;
        $result['message'] = 'E-email telah digunakan.';
        $result['data'] = array();

        echo json_encode($result);
        exit();
      }
    }

    if ($nip != $getUser->nip) {
      $whereNip['nip'] = $this->input->post('nip');
      $cekNip = $this->Db_select->select_where('tb_user', $whereNip);
      if ($cekNip) {
        $result['status'] = false;
        $result['message'] = 'NIP telah digunakan.';
        $result['data'] = array();
        echo json_encode($result);
        exit();
      }
    }

    if ($telp_user != $getUser->telp_user) {
      $whereTlp['telp_user'] = $this->input->post('telp_user');
      $cekTelp = $this->Db_select->select_where('tb_user', $whereTlp);

      if ($cekTelp) {
        $result['status'] = false;
        $result['message'] = 'Nomor telepon telah digunakan.';
        $result['data'] = array();
        echo json_encode($result);
        exit();
      }
    }

    $where['user_id'] = $this->input->post('user_id');
    $data['nama_user'] = $this->input->post('nama_user');
    $data['nip'] = $this->input->post('nip');
    $data['jenis_kelamin'] = $this->input->post('jenis_kelamin');
    $data['tempat_lahir'] = $this->input->post('tempat_lahir');
    $data['agama'] = $this->input->post('agama');
    $data['status_pernikahan'] = $this->input->post('status_pernikahan');
    $data['telp_user'] = $this->input->post('telp_user');
    $data['email_user'] = $this->input->post('email_user');
    $data['alamat_user'] = $this->input->post('alamat_user');
    $data['id_unit'] = $this->input->post('id_unit');
    $data['id_divisi'] = $this->input->post('id_divisi');
    $data['jabatan'] = $this->input->post('jabatan');
    $data['status_user'] = $this->input->post('status_user');
    $data['tanggal_lahir'] = date("Y-m-d", strtotime($this->input->post('tanggal_lahir')));
    $data['id_struktur'] = $this->input->post('id_struktur');
    $data['id_parent'] = $this->input->post('id_parent') ? $this->input->post('id_parent') : null;
    $data['nama_bidang'] = $this->input->post('nama_bidang');
    $data['saldo'] = $this->input->post('saldo');
    $data['is_aktif'] = $this->input->post('is_aktif');
    $data['gaji_pokok'] = $this->input->post('gaji_pokok');
    $data['cuti'] = $this->input->post('cuti');
    $data['role'] = $this->input->post('role');

    $data['nomor_identitas'] = $this->input->post('no_identitas');

    $data['jenis_nomor_identitas'] = $this->input->post('jenis_nomor_identitas');
    $data['nama_kontak_darurat'] = $this->input->post('nama_kontak_darurat');
    $data['telepon_kontak_darurat'] = $this->input->post('nomor_kontak_darurat');
    $data['tanggal_kerja'] = date("Y-m-d", strtotime($this->input->post('tanggal_kerja')));
    $data['tanggal_akhir'] = date("Y-m-d", strtotime($this->input->post('tanggal_akhir')));

    if ($this->input->post('password') != null || $this->input->post('password') != "") {
      $data['password_user'] = md5($this->input->post('password'));
    }

    if ($_FILES['userfile']['name']) {
      $nmfile = "file_" . time(); //nama file saya beri nama langsung dan diikuti fungsi time
      $config['upload_path'] = './assets/images/member-photos'; //path folder
      $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
      $config['max_size'] = '2048'; //maksimum besar file 2M
      $config['file_name'] = $nmfile; //nama yang terupload nantinya
      $this->load->library('upload', $config);
      $this->upload->initialize($config);
      if ($this->upload->do_upload('userfile')) {
        $gbr = $this->upload->data();
        $name3 = $gbr['file_name'];
        $data['foto_user'] = $name3;
      } else {
        $result['status'] = false;
        $result['message'] = $this->upload->display_errors();
        $result['data'] = array();
        echo json_encode($result);
        exit();
      }
    }

    $update = $this->Db_dml->update('tb_user', $data, $where);
    /* get data komponen pendapatan */
    $getKomponen = $this->Db_select->select_all_where('tb_komponen_pendapatan', 'id_channel = "' . $sess['id_channel'] . '"');
    foreach ($getKomponen as $value) {
      $namaParam = "komponen_pendapatan" . $value->id_komponen_pendapatan;
      /* cek komponen pendapatan */
      $wherePendapatan['user_id'] = $this->input->post('user_id');
      $wherePendapatan['id_komponen_pendapatan'] = $value->id_komponen_pendapatan;
      $getPendapatan = $this->Db_select->select_where('tb_pendapatan', $wherePendapatan);

      if ($getPendapatan) {
        // update
        $wherePendapatanLagi['user_id'] = $this->input->post('user_id');
        $wherePendapatanLagi['id_komponen_pendapatan'] = $value->id_komponen_pendapatan;
        $updatePendapatan['nominal'] = $this->input->post($namaParam);
        $updateData = $this->Db_dml->update('tb_pendapatan', $updatePendapatan, $wherePendapatanLagi);
      } else {
        // insert
        $insertPendapatan['user_id'] = $this->input->post('user_id');;
        $insertPendapatan['id_komponen_pendapatan'] = $value->id_komponen_pendapatan;
        $insertPendapatan['nominal'] = $this->input->post($namaParam);
        $insertData = $this->Db_dml->insert('tb_pendapatan', $insertPendapatan);
      }
    }

    // $instansi  = $this->input->post('nama_instansi_pendidikan');
    // $pekerjaan = $this->input->post('perusahaan');
    // $cekPendidikan = $this->Db_select->select_where('tb_riwayat_pendidikan', 'user_id =' . $this->input->post('user_id'));
    // $cekPekerjaan = $this->Db_select->select_where('tb_riwayat_pekerjaan', 'user_id =' . $this->input->post('user_id'));

    // if ($cekPendidikan == null || $cekPendidikan == "") {
    //   foreach ($instansi as $value) {
    //     $data_instansi['user_id'] = $this->input->post('user_id');
    //     if (isset($value['jenis_pendidikan'])) {
    //       $data_instansi['jenis_pendidikan'] = $value['jenis_pendidikan'];
    //     } else {
    //       $data_instansi['jenis_pendidikan'] = null;
    //     }
    //     if (isset($value['nama'])) {
    //       $data_instansi['nama_instansi_pendidikan'] = $value['nama'];
    //     } else {
    //       $data_instansi['nama_instansi_pendidikan'] = null;
    //     }
    //     if (isset($value['jurusan'])) {
    //       $data_instansi['jurusan'] = $value['jurusan'];
    //     } else {
    //       $data_instansi['jurusan'] = null;
    //     }
    //     if (isset($value['tahun_masuk'])) {
    //       $data_instansi['tahun_masuk'] = $value['tahun_masuk'];
    //     } else {
    //       $data_instansi['tahun_masuk'] = null;
    //     }
    //     if (isset($value['tahun_lulus'])) {
    //       $data_instansi['tahun_lulus'] = $value['tahun_lulus'];
    //     } else {
    //       $data_instansi['tahun_lulus'] = null;
    //     }
    //     $insert_instansi = $this->Db_dml->insert('tb_riwayat_pendidikan', $data_instansi);
    //   }
    // } else {
    //   foreach ($instansi as $key => $value) {
    //     $where2['user_id'] = $this->input->post('user_id');
    //     $where2['jenis_pendidikan'] = $value['jenis_pendidikan'];
    //     if (isset($value['nama'])) {
    //       $data_instansi['nama_instansi_pendidikan'] = $value['nama'];
    //     } else {
    //       $data_instansi['nama_instansi_pendidikan'] = "-";
    //     }
    //     if (isset($value['jurusan'])) {
    //       $data_instansi['jurusan'] = $value['jurusan'];
    //     } else {
    //       $data_instansi['jurusan'] = "-";
    //     }
    //     if (isset($value['tahun_masuk'])) {
    //       $data_instansi['tahun_masuk'] = $value['tahun_masuk'];
    //     } else {
    //       $data_instansi['tahun_masuk'] = "-";
    //     }
    //     if (isset($value['tahun_lulus'])) {
    //       $data_instansi['tahun_lulus'] = $value['tahun_lulus'];
    //     } else {
    //       $data_instansi['tahun_lulus'] = "-";
    //     }

    //     $insert_instansi = $this->Db_dml->update('tb_riwayat_pendidikan', $data_instansi, $where2);
    //   }
    // }

    // if ($cekPekerjaan == null || $cekPekerjaan == "") {
    //   if ($pekerjaan) {
    //     foreach ($pekerjaan as $value) {
    //       $data_pekerjaan['user_id'] = $this->input->post('user_id');
    //       if (isset($value['id_form'])) {
    //         $data_pekerjaan['id_form'] = $value['id_form'];
    //       } else {
    //         $data_pekerjaan['id_form'] = null;
    //       }
    //       if (isset($value['nama_perusahaan'])) {
    //         $data_pekerjaan['nama_perusahaan'] = $value['nama_perusahaan'];
    //       } else {
    //         $data_pekerjaan['nama_perusahaan'] = null;
    //       }
    //       if (isset($value['posisi'])) {
    //         $data_pekerjaan['posisi'] = $value['posisi'];
    //       } else {
    //         $data_pekerjaan['posisi'] = null;
    //       }
    //       if (isset($value['deskripsi'])) {
    //         $data_pekerjaan['deskripsi'] = $value['deskripsi'];
    //       } else {
    //         $data_pekerjaan['deskripsi'] = null;
    //       }
    //       if (isset($value['lama_bekerja'])) {
    //         $data_pekerjaan['lama_bekerja'] = $value['lama_bekerja'];
    //       } else {
    //         $data_pekerjaan['lama_bekerja'] = null;
    //       }
    //       if (isset($value['alasan_berhenti'])) {
    //         $data_pekerjaan['alasan_berhenti'] = $value['alasan_berhenti'];
    //       } else {
    //         $data_pekerjaan['alasan_berhenti'] = null;
    //       }
    //       $insert_perusahaan = $this->Db_dml->insert('tb_riwayat_pekerjaan', $data_pekerjaan);
    //     }
    //   }
    // } else {
    //   foreach ($pekerjaan as $value) {
    //     if ($value['id']) {
    //       $where3['user_id'] = $this->input->post('user_id');
    //       $where3['id'] = $value['id'];
    //       if (isset($value['nama_perusahaan']) && !empty($value['nama_perusahaan'])) {
    //         $data_pekerjaan['nama_perusahaan'] = $value['nama_perusahaan'];
    //       } else {
    //         $data_pekerjaan['nama_perusahaan'] = "-";
    //       }
    //       if (isset($value['posisi']) && !empty($value['posisi'])) {
    //         $data_pekerjaan['posisi'] = $value['posisi'];
    //       } else {
    //         $data_pekerjaan['posisi'] = "-";
    //       }
    //       if (isset($value['deskripsi']) && !empty($value['deskripsi'])) {
    //         $data_pekerjaan['deskripsi'] = $value['deskripsi'];
    //       } else {
    //         $data_pekerjaan['deskripsi'] = "-";
    //       }
    //       if (isset($value['lama_bekerja']) && !empty($value['lama_bekerja'])) {
    //         $data_pekerjaan['lama_bekerja'] = $value['lama_bekerja'];
    //       } else {
    //         $data_pekerjaan['lama_bekerja'] = "-";
    //       }
    //       if (isset($value['alasan_berhenti']) && !empty($value['alasan_berhenti'])) {
    //         $data_pekerjaan['alasan_berhenti'] = $value['alasan_berhenti'];
    //       } else {
    //         $data_pekerjaan['alasan_berhenti'] = "-";
    //       }
    //       $insert_perusahaan = $this->Db_dml->update('tb_riwayat_pekerjaan', $data_pekerjaan, $where3);
    //     }
    //   }
    // }

    if ($update || $updateData || $insertData || $insert_perusahaan || $insert_instansi) {
      $res['status'] = true;
      $res['message'] = 'Data berhasil disimpan.';
      $res['data'] = array();
      echo json_encode($res);
      exit();
    } else {
      $res['status'] = true;
      $res['message'] = 'Data berhasil disimpan.';
      $res['data'] = array();
      echo json_encode($res);
      exit();
    }
  }

  public function update2()
  {
    $where['user_id'] = $this->input->post('user_id');

    $data['is_aktif'] = $this->input->post('is_aktif');
    $data['tanggal_kerja'] = date("Y-m-d", strtotime($this->input->post('tanggal_kerja')));
    $data['gaji_pokok'] = $this->input->post('gaji_pokok');
    $data['tunjangan_makan'] = $this->input->post('tunjangan_makan');
    $data['tunjangan_jabatan'] = $this->input->post('tunjangan_jabatan');
    $data['tunjangan_transportasi'] = $this->input->post('tunjangan_transportasi');
    if ($_FILES['userfile']['name']) {
      $nmfile = "file_" . time(); //nama file saya beri nama langsung dan diikuti fungsi time
      $config['upload_path'] = './assets/images/member-photos'; //path folder
      $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
      $config['max_size'] = '2048'; //maksimum besar file 2M
      $config['file_name'] = $nmfile; //nama yang terupload nantinya

      $this->load->library('upload', $config);
      $this->upload->initialize($config);
      if ($this->upload->do_upload('userfile')) {
        $gbr = $this->upload->data();
        $name3 = $gbr['file_name'];
        $data['foto_user'] = $name3;
      } else {
        $result['status'] = false;
        $result['message'] = $this->upload->display_errors();
        $result['data'] = array();
        echo json_encode($result);
        exit();
      }
    }

    $update = $this->Db_dml->update('tb_user', $data, $where);
    if ($update) {
      $result['status'] = true;
      $result['message'] = 'Data berhasil disimpan.';
      $result['data'] = array();
    } else {
      $result['status'] = true;
      $result['message'] = 'Data berhasil disimpan.';
      $result['data'] = array();
    }
    echo json_encode($result);
  }

  public function delete()
  {
    $where['user_id'] = $this->input->post('nip');
    $update['is_deleted'] = 1;
    $updateData = $this->Db_dml->update('tb_user', $update, $where);

    if ($updateData == 1) {
      $result['status'] = true;
      $result['message'] = 'Data telah di hapus';
      $result['data'] = null;
    } else {
      $result['status'] = false;
      $result['message'] = 'Data gagal di hapus';
      $result['data'] = null;
    }
    echo json_encode($result);
  }

  public function edit($id)
  {
    $sess = $this->session->userdata('user');

    $user_id = $id;
    $agama = ['islam', 'protestan', 'katolik', 'hindu', 'budha', 'khonghucu'];
    $jni = ['ktp', 'sim', 'paspor'];
    $jenkel = ['l', 'p'];
    $status_pernikahan = ['lajang', 'menikah', 'janda', 'duda'];
    $data['data_staff'] = $this->Db_select->query('select *, date_format(a.tanggal_lahir,"%Y-%m-%d") tanggal_lahir, a.is_aktif is_aktiff from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.user_id = ' . $id . ' and b.id_channel = "' . $sess['id_channel'] . '"');
    $struktur_organisasi = $this->Db_select->select_where('tb_struktur_organisasi', 'id_channel = "' . $sess['id_channel'] . '"');
    $posisi = json_decode($struktur_organisasi->struktur_data);
    $posisi = $this->getPosisi($posisi);
    $data['atasan'] = $this->Db_select->query_all('select a.* from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = ' . $sess['id_channel'] . ' order by a.nama_user asc');

    /* mengubah array berdasarkan id terkecil */
    function compare_lastname($a, $b)
    {
      return strnatcmp($a['id'], $b['id']);
    }

    // sort number by id
    usort($posisi, 'compare_lastname');
    $data['posisi'] = $posisi;
    $skpd = $this->Db_select->query_all('select *from tb_unit where is_aktif = 1 and id_channel = ' . $sess['id_channel'] . ' and is_deleted = 0 order by nama_unit asc');
    $jabatan = $this->Db_select->query_all('select *from tb_jabatan where is_aktif = 1 and id_channel = ' . $sess['id_channel'] . ' and is_deleted = 0 order by nama_jabatan asc');
    /* where status staff */
    $whereStatus['is_aktif'] = 1;
    $whereStatus['id_channel'] = $sess['id_channel'];
    $whereStatus['is_deleted'] = 0;
    $status_staff = $this->Db_select->select_all_where('tb_status_user', $whereStatus);

    $data['list_agama'] = "";
    for ($i = 0; $i < count($agama); $i++) {
      $data['list_agama'] .= '<option value="' . $agama[$i] . '"' . $this->selected($agama[$i], $data['data_staff']->agama) . '>' . ucwords($agama[$i]) . '</option>';
    }

    $data['list_jenkel'] = "";
    for ($i = 0; $i < count($jenkel); $i++) {
      if ($jenkel[$i] == 'l') {
        $nama_jenkel = 'Laki-Laki';
      } elseif ($jenkel[$i] == 'p') {
        $nama_jenkel = 'Perempuan';
      }
      $data['list_jenkel'] .= '<option value="' . $jenkel[$i] . '"' . $this->selected($jenkel[$i], $data['data_staff']->jenis_kelamin) . '>' . ucwords($nama_jenkel) . '</option>';
    }

    $data['list_jni'] = "";
    for ($i = 0; $i < count($jni); $i++) {
      if ($jni[$i] == 'ktp') {
        $nama_jni = 'ktp';
      } elseif ($jni[$i] == 'sim') {
        $nama_jni = 'sim';
      } elseif ($jni[$i] == 'paspor') {
        $nama_jni = 'paspor';
      }
      $data['list_jni'] .= '<option value="' . $jni[$i] . '"' . $this->selected($jni[$i], $data['data_staff']->jenis_nomor_identitas) . '>' . ucwords($nama_jni) . '</option>';
    }

    $data['list_status_pernikahan'] = "";
    for ($i = 0; $i < count($status_pernikahan); $i++) {
      $data['list_status_pernikahan'] .= '<option value="' . $status_pernikahan[$i] . '"' . $this->selected($status_pernikahan[$i], $data['data_staff']->status_pernikahan) . '>' . ucwords($status_pernikahan[$i]) . '</option>';
    }

    $data['list_data_unit'] = "";
    foreach ($skpd as $key => $value) {
      $data['list_data_unit'] .= '<option value="' . $value->id_unit . '"' . $this->selected($value->id_unit, $data['data_staff']->id_unit) . '>' . $value->nama_unit . '</option>';
    }

    $data['list_jabatan'] = "";
    foreach ($jabatan as $key => $value) {
      $data['list_jabatan'] .= '<option value="' . $value->id_jabatan . '"' . $this->selected($value->id_jabatan, $data['data_staff']->jabatan) . '>' . $value->nama_jabatan . '</option>';
    }

    $data['list_status_pekerjaan'] = "";
    foreach ($status_staff as $key => $value) {
      $data['list_status_pekerjaan'] .= '<option value="' . $value->id_status_user . '"' . $this->selected($value->id_status_user, $data['data_staff']->status_user) . '>' . $value->nama_status_user . '</option>';
    }
    $data['list_is_aktif'] = '
		  <option value="1"' . $this->selected($data['data_staff']->is_aktiff, 1) . '>Aktif</option>
		  <option value="0"' . $this->selected($data['data_staff']->is_aktiff, 0) . '>Nonaktif</option>';

    if ($data['data_staff']->tanggal_kerja == null || $data['data_staff']->tanggal_kerja == "") {
      $data['data_staff']->tanggal_kerja = date('Y-m-d', strtotime($data['data_staff']->created_user));
    } else {
      $data['data_staff']->tanggal_kerja = date('Y-m-d', strtotime($data['data_staff']->tanggal_kerja));
    }

    $departemen = $this->Db_select->query_all('select *from tb_unit where is_aktif = 1 and id_channel = ' . $sess['id_channel'] . ' and is_deleted = 0 order by nama_unit asc');
    $data['departemen'] = "";

    foreach ($departemen as $key => $value) {
      $data['departemen'] .= '<option value="' . $value->id_unit . '"' . $this->selected($value->id_unit, $data['data_staff']->id_unit) . '>' . $value->nama_unit . '</option>';
    }

    if ($data['data_staff']->id_divisi != null || $data['data_staff']->id_divisi != "") {
      $where['id_unit'] = $data['data_staff']->id_unit;
      $divisi = $this->Db_select->select_all_where('tb_divisi', $where);
      $data['divisi'] = "";
      foreach ($divisi as $key => $value) {
        $data['divisi'] .= '<option value="' . $value->id_divisi . '"' . $this->selected($value->id_divisi, $data['data_staff']->id_divisi) . '>' . $value->nama_divisi . '</option>';
      }
    }

    /* get komponen pendapatan */
    $getKomponen = $this->Db_select->query_all('select *from tb_komponen_pendapatan where id_channel = "' . $sess['id_channel'] . '"');
    $data['getKomponen'] = "";
    foreach ($getKomponen as $key => $value) {
      $isi = 0;
      /* get komponen user */
      $wherez['user_id'] = $data['data_staff']->user_id;
      $wherez['id_komponen_pendapatan'] = $value->id_komponen_pendapatan;
      $getPendapatan = $this->Db_select->select_where('tb_pendapatan', $wherez);
      if ($getPendapatan) {
        $isi = $getPendapatan->nominal;
      }
      $data['getKomponen'] .= '
        <div class="col-md-12">
          <label class="form-label">' . $value->nama_komponen_pendapatan . ' <span style="color: red;">*</span></label>
          <div class="form-group">
            <div class="form-line">
              <input type="number" onKeyPress="return goodchars(event,"0123456789",this)" name="komponen_pendapatan' . $value->id_komponen_pendapatan . '" class="form-control" placeholder="Gaji Pokok" required="" value="' . $isi . '">
            </div>
          </div>
        </div>';
    }

    $getRiwayatPendidikan = $this->Db_select->select_all_where('tb_riwayat_pendidikan', 'user_id=' . $user_id);
    $data['riwayat_pendidikan'] = '';
    if ($getRiwayatPendidikan) {
      foreach ($getRiwayatPendidikan as $key => $value) {
        $no = $key + 1;
        if ($value->jenis_pendidikan) {
          $jenis_pendidikan = $value->jenis_pendidikan;
          $instansi = 'Nama Universitas';
          if ($value->jenis_pendidikan == 'sma/smk') {
            $jenis_pendidikan = "Sekolah Menegah Atas/Kejuruan (SMA/SMK)";
            $instansi = 'Nama Sekolah';
          } else if ($value->jenis_pendidikan == 'smp') {
            $instansi = 'Nama Sekolah';
            $jenis_pendidikan = "Sekolah Menegah Pertama (SMP)";
          } else if ($value->jenis_pendidikan == 'sd') {
            $instansi = 'Nama Sekolah';
            $jenis_pendidikan = "Sekolah Dasar SD";
          }

          if ($value->jenis_pendidikan == 'sd' || $value->jenis_pendidikan == 'smp') {
            $data['riwayat_pendidikan'] .= '<h2 class="card-inside-title">' . $jenis_pendidikan . '</h2>
              <div class="row">
                <div class="col-md-12">
                  <label for="nama_universitas' . $no . '">' . $instansi . '</label>
                  <div class="form-group">
                      <div class="form-line">
                          <input type="text" class="form-control" name="nama_instansi_pendidikan[' . $no . '][nama]" value="' . $value->nama_instansi_pendidikan . '" id="nama_universitas' . $no . '" placeholder="' . $instansi . '">
                      </div>
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-float">
                        <div class="form-line">
                            <label for="tahun_masuk' . $no . '">Tahun Masuk</label>
                            <input type="number" class="form-control" id="tahun_masuk' . $no . '" name="nama_instansi_pendidikan[' . $no . '][tahun_masuk]" value="' . $value->tahun_masuk . '" placeholder="Tahun Masuk">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-float">
                        <div class="form-line">
                            <label for="tahun_lulus' . $no . '">Tahun Lulus</label>
                            <input type="number" class="form-control" id="tahun_lulus' . $no . '" name="nama_instansi_pendidikan[' . $no . '][tahun_lulus]" value="' . $value->tahun_lulus . '" placeholder="Tahun Lulus">
                        </div>
                    </div>
                </div>
              </div>
              <input type="hidden" name="nama_instansi_pendidikan[' . $no . '][jurusan]" value="">
              <input type="hidden" name="nama_instansi_pendidikan[' . $no . '][jenis_pendidikan]" value="' . $value->jenis_pendidikan . '">
            ';
          } else {
            $data['riwayat_pendidikan'] .= '<h2 class="card-inside-title">' . $jenis_pendidikan . '</h2>
              <div class="row">
                <div class="col-md-6">
                  <label for="nama_universitas' . $no . '">' . $instansi . '</label>
                  <div class="form-group">
                      <div class="form-line">
                          <input type="text" class="form-control" name="nama_instansi_pendidikan[' . $no . '][nama]" value="' . $value->nama_instansi_pendidikan . '" id="nama_universitas' . $no . '" placeholder="' . $instansi . '">
                      </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="jurusan' . $no . '">Jurusan</label>
                  <div class="form-group">
                      <div class="form-line">
                          <input type="text" class="form-control" id="jurusan' . $no . '" name="nama_instansi_pendidikan[' . $no . '][jurusan]" value="' . $value->jurusan . '" placeholder="Jurusan">
                      </div>
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-float">
                        <div class="form-line">
                            <label for="tahun_masuk' . $no . '">Tahun Masuk</label>
                            <input type="number" class="form-control" name="nama_instansi_pendidikan[' . $no . '][tahun_masuk]" id="tahun_masuk' . $no . '" value="' . $value->tahun_masuk . '" placeholder="Tahun Masuk">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-float">
                        <div class="form-line">
                            <label for="tahun_lulus' . $no . '">Tahun Lulus</label>
                            <input type="number" class="form-control" id="tahun_lulus' . $no . '" name="nama_instansi_pendidikan[' . $no . '][tahun_lulus]" value="' . $value->tahun_lulus . '" placeholder="Tahun Lulus">
                        </div>
                    </div>
                </div>
              </div>
              <input type="hidden" name="nama_instansi_pendidikan[' . $no . '][jenis_pendidikan]" value="' . $value->jenis_pendidikan . '">
            ';
          }
        }
      }
    }

    $getRiwayatPekerjaan =  $this->Db_select->select_all_where('tb_riwayat_pekerjaan', 'user_id=' . $user_id);
    $data['riwayat_pekerjaan'] = '';
    foreach ($getRiwayatPekerjaan as $key => $value) {
      $no = $key + 1;
      $data['riwayat_pekerjaan'] .= '
			  <h2 class="card-inside-title">Riwayat Pekerjaan ' . $no . '</h2>
        <div class="row">
          <div class="col-md-6">
              <div class="form-group form-float">
                  <div class="form-line">
                    <label for="nama_perusahaan' . $no . '">Nama Perusahaan</label>
                    <input type="text" class="form-control" id="nama_perusahaan' . $no . '" placeholder="Nama Perusahaan" name="perusahaan[' . $no . '][nama_perusahaan]" value="' . $value->nama_perusahaan . '"  >
                    <input type="hidden" value="' . $value->id . '" name="perusahaan[' . $no . '][id]" > 
                  </div>
              </div>
          </div>
          <div class="col-md-6">
              <div class="form-group form-float">
                  <div class="form-line">
                    <label for="posisi' . $no . '">Posisi</label>
                    <input type="text" class="form-control" id="posisi' . $no . '" name="perusahaan[' . $no . '][posisi]" value="' . $value->posisi . '" placeholder="Posisi">
                  </div>
              </div>
          </div>
          <div class="col-md-12">
              <div class="form-group form-float">
                  <div class="form-line">
                    <label for="deskripsi' . $no . '">Deskripsi Pekerjaan</label>
                    <input type="text" class="form-control" id="deskripsi' . $no . '" name="perusahaan[' . $no . '][deskripsi]" value="' . $value->deskripsi . '" placeholder="Deskripsi Pekerjaan">
                  </div>
              </div>
          </div>
          <div class="col-md-6">
              <div class="form-group form-float">
                  <div class="form-line">
                    <label for="lama' . $no . '">Lama Bekerja</label>
                    <input type="number" class="form-control" id="lama' . $no . '" name="perusahaan[' . $no . '][lama_bekerja]" value="' . $value->lama_bekerja . '" placeholder="Lama Bekerja">
                  </div>
              </div>
          </div>
          <div class="col-md-6">
              <div class="form-group form-float">
                  <div class="form-line">
                    <label for="alasan' . $no . '">Alasan Berhenti</label>
                    <input type="text" class="form-control" id="alasan' . $no . '" name="perusahaan[' . $no . '][alasan_berhenti]" value="' . $value->alasan_berhenti . '" placeholder="Alasan Berhenti">
                  </div>
              </div>
          </div>
        </div>';
    }

    $menu['main'] = 'personalia';
    $menu['child'] = 'personalia_staff';
    $data['menu'] = $menu;


    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/edit_pegawai');
    $this->load->view('Administrator/footer');
  }

  public function getPosisi($data, $id = 0)
  {
    foreach ($data as $key => $value) {
      $push['id'] = $value->id;
      $push['name'] = $value->name;
      $push['parent'] = $value->parent;
      if (isset($value->children)) {
        if (count($value->children)) {
          $this->getPosisi($value->children, $value->id);
        }
      }
      array_push($this->jumlahTotal, $push);
    }
    return $this->jumlahTotal;
  }

  public function selected($value, $nama)
  {
    if ($value == $nama) {
      return " selected";
    } else {
      return "";
    }
  }

  public function get_level($id = null, $nip = null)
  {
    if ($nip) {
      $this->Newget_level($id, $nip);
    }
    $sess = $this->session->userdata('user');
    $selectUser = $this->Db_select->query_all('select a.* from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = "' . $sess['id_channel'] . '" and a.id_unit = "' . $id . '" order by a.nama_user asc');
    $data = '';
    if ($selectUser) {
      $option = '<option value="">-- Pilih atasan --</option>';
      foreach ($selectUser as $value) {
        $option .= "<option value='" . $value->user_id . "'>" . $value->nama_user . "</option>";
      }
      $data = '<label class="form-label" for="id_parent">Atasan</label>
        <div class="form-group form-float">
          <div class="form-line">
            <select class="form-control show-tick" name="id_parent" id="id_parent">
              ' . $option . '
            </select>
          </div>
      </div>';
    }

    echo $data;
  }

  public function Newget_level($id = null, $nip = null)
  {
    $dataUser = $this->Db_select->select_where('tb_user', ['nip' => $nip]);
    $data = "";

    if ($dataUser) {
      $selectUser = $this->Db_select->query_all('select *from tb_user where id_unit = ' . $id . ' order by nama_user asc');
      $option = "<option value=''>-- Pilih Atasan --</option>";
      foreach ($selectUser as $value) {
        $option .= "<option value='" . $value->user_id . "'" . $this->selected($dataUser->id_parent, $value->user_id) . ">" . $value->nama_user . "</option>";
      }

      $data .= '<label class="form-label" for="id_parent">Atasan</label>
        <div class="form-group form-float">
          <div class="form-line">
            <select class="form-control show-tick" name="id_parent" id="id_parent">
              ' . $option . '
            </select>
          </div>
        </div>';
    }

    echo $data;
    exit();
  }

  public function getdown($id = null, $newId = null, $newId2 = null)
  {
    $selectUser = $this->Db_select->query_all('select *from tb_user where id_unit = "' . $id . '" and id_struktur = "' . $newId . '"');
    $data = "<option></option>";
    foreach ($selectUser as $key => $value) {
      $data .= "<option value='" . $value->user_id . "'" . $this->selected($newId2, $value->user_id) . ">" . $value->nama_user . "</option>";
    }
    echo $data;
  }

  public function list_jabatan($id = null)
  {
    $sess = $this->session->userdata('user');
    $data = "";
    $jabatan = $this->Db_select->select_all_where('tb_jabatan', 'is_aktif = 1 and id_channel = "' . $sess['id_channel'] . '"');
    foreach ($jabatan as $key => $value) {
      $data .= "<option value='" . $value->id_jabatan . "'" . $this->selected($id, $value->id_jabatan) . ">" . $value->nama_jabatan . "</option>";
    }
    return $data;
  }

  public function list_eselon($id = null, $newId = null)
  {
    $data = "";
    $eselon = $this->Db_select->select_all_where('tb_eselon', 'id_struktur = "' . $id . '"');
    foreach ($eselon as $key => $value) {
      $data .= "<option value='" . $value->id_eselon . "'" . $this->selected($newId, $value->id_eselon) . ">" . $value->nama_eselon . "</option>";
    }
    return $data;
  }

  public function list_atasan($id = null, $newId = null)
  {
    $data = "";
    $atasan = $this->Db_select->query_all('select *from tb_user where id_struktur = "' . $id . '"');
    foreach ($atasan as $key => $value) {
      $data .= "<option value='" . $value->user_id . "'" . $this->selected($newId, $value->user_id) . ">" . $value->nama_user . "</option>";
    }
    return $data;
  }

  public function list_OPD($id = null)
  {
    $sess = $this->session->userdata('user');
    $data = "";
    $OPD = $this->Db_select->select_all_where('tb_unit', 'is_aktif = 1 and id_channel = "' . $sess['id_channel'] . '"');
    foreach ($OPD as $key => $value) {
      $data .= "<option value='" . $value->id_unit . "'" . $this->selected($id, $value->id_unit) . ">" . $value->nama_unit . "</option>";
    }
    return $data;
  }

  public function detail($id)
  {
    $item = $this->Db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_admin = 0 AND user_id = ' . $id);
    $selectJabatan = $this->Db_select->select_where('tb_jabatan', 'id_jabatan = ' . $item->jabatan);
    if ($selectJabatan) {
      $item->jabatan = $selectJabatan->nama_jabatan;
    } else {
      $item->jabatan = "-";
    }
    $selectTipe = $this->Db_select->select_where('tb_status_user', 'id_status_user = ' . $item->status_user);
    if ($selectTipe) {
      $item->status_user = $selectTipe->nama_status_user;
    } else {
      $item->status_user = "-";
    }
    if ($item->foto_user == "" || $item->foto_user == null) {
      $item->foto_user = base_url() . "assets/images/member-photos/ava.png";
    } else {
      $filename = './assets/images/member-photos/' . $item->foto_user;
      if (!file_exists($filename)) {
        $item->foto_user = base_url() . "assets/images/member-photos/ava.png";
      } else {
        $item->foto_user = base_url() . "assets/images/member-photos/" . $item->foto_user;
      }
    }

    if ($item->status_pernikahan == "" || $item->status_pernikahan == null) {
      $item->status_pernikahan = "-";
    }
    if ($item->pendidikan_terakhir == "" || $item->pendidikan_terakhir == null) {
      $item->pendidikan_terakhir = "-";
    }
    $data['item'] = $item;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/detail_pegawai');
    $this->load->view('Administrator/footer');
  }

  public function list_ttp($id = null)
  {
    $cekStatus = $this->Db_select->select_where('tb_status_user', 'id_status_user = "' . $id . '"');
    $list = "";
    if ($cekStatus->pemotongan_tpp == 1) {
      $list .= '<div class="form-group form-float">
        <label class="form-label"><i class="col-red">*</i> 30% dari Tunjangan Kinerja</label>
        <div class="form-line">
          <input type="text" name="saldo" class="form-control" required>
        </div>
      </div>';
    }
    echo $list;
  }

  public function list_divisi($id = null, $newId = null)
  {
    if ($newId != null || $newId != '') {
      $this->Newlist_divisi($id, $newId);
    }

    $cekStatus = $this->Db_select->select_all_where('tb_divisi', 'id_unit = "' . $id . '"');
    if ($cekStatus == "" || $cekStatus == null || $cekStatus == []) {
      $list = "";
    } else {
      $list = '
				<div class="col-md-12" >
					<div class="form-group form-float">
            <label class="form-label">Divisi <span class="col-red">*</span></label>
            <div class="form-line">
              <select class="form-control show-tick" id="divisi" name="id_divisi" required>';
      $list .= "<option value=''>-- Divisi --</option>";
      foreach ($cekStatus as $key => $value) {
        $list .= ' <option value="' . $value->id_divisi . '">' . $value->nama_divisi . '</option>';
      }
      $list .= '</select>
            </div>
          </div>
        </div>';
    }

    echo $list;
  }

  public function Newlist_divisi($id = null, $newId = null)
  {
    $getUser = $this->Db_select->select_where('tb_user', 'user_id = "' . $newId . '"');
    $cekStatus = $this->Db_select->select_all_where('tb_divisi', 'id_unit = "' . $id . '"');
    if ($cekStatus == "" || $cekStatus == null || $cekStatus == []) {
      $list = "";
    } else {
      $list = '<div class="col-md-12" >
        <div class="form-group form-float">
          <label class="form-label">Divisi <span class="col-red">*</span></label>
          <div class="form-line">
            <select class="form-control show-tick" id="divisi" name="id_divisi" required>';
      $list .= "<option value=''>-- Divisi --</option>";
      foreach ($cekStatus as $key => $value) {
        $list .= ' <option value="' . $value->id_divisi . '"' . $this->selected($getUser->id_divisi, $value->id_divisi) . '>' . $value->nama_divisi . '</option>';
      }
      $list .= '</select>
        </div>
      </div>
      </div>';
    }

    echo $list;
    exit();
  }

  public function list_ttpNew($id = null, $newId = null)
  {
    $getUser = $this->Db_select->select_where('tb_user', 'user_id = "' . $newId . '"');
    $cekStatus = $this->Db_select->select_where('tb_status_user', 'id_status_user = "' . $id . '"');
    $list = "";
    if ($cekStatus->pemotongan_tpp == 1) {
      $list .= '<div class="form-group form-float">
        <label class="form-label"><i class="col-red">*</i> 30% dari Tunjangan Kinerja</label>
        <div class="form-line">
          <input type="text" name="saldo" class="form-control" value="' . $getUser->saldo . '" required>
        </div>
      </div>';
    }
    echo $list;
  }

  public function simulasi($id = null)
  {
    $list = '';
    if ($id != null) {
      if ($id == 1) {
        $list .= '
          <h2 class="card-inside-title">Jam Masuk</h2>
            <div class="row clearfix">
              <div class="col-sm-12">
                <input type="text" name="monday_from" id="jam_masuk" class="form-control time24" placeholder="07:30" size="1" required>
              </div>
            </div>
            <script type="text/javascript">
              $("#jam_masuk").change(function() {
                var e = $("#jam_masuk").val();
                $.ajax({
                  method  : "GET",
                url     : path+"Administrator/pegawai/pemotonganKeterlambatan/"+e,
                async   : true,
                  success: function(data, status, xhr){
                    $("#besar_potongan").val(xhr.responseText);
                  }
                });
              });
            </script>
        ';

        $list .= '
          <h2 class="card-inside-title">Besar Potongan</h2>
          <div class="row clearfix">
            <div class="col-sm-12">
                <input type="text" id="besar_potongan" name="monday_from" class="form-control" required>
            </div>
          </div>
        ';
      } else {
        //TidakMasuk
        if ($id == 2) {
          $besaPotongan = $this->pemotongantidakMasuk(1);
        } elseif ($id == 3) {
          $besaPotongan = $this->pemotongantidakMasuk(2);
        } elseif ($id == 4) {
          $besaPotongan = $this->pemotongantidakMasuk(3);
        } elseif ($id == 5) {
          $besaPotongan = $this->pemotonganPulangCepat(1);
        } elseif ($id == 6) {
          $besaPotongan = $this->pemotonganPulangCepat(2);
        } elseif ($id == 7) {
          $besaPotongan = $this->pemotonganPulangCepat(3);
        } elseif ($id == 8) {
          $besaPotongan = $this->pemotonganTidakApel(1);
        } elseif ($id == 9) {
          $besaPotongan = $this->pemotonganTidakApel(2);
        } elseif ($id == 10) {
          $besaPotongan = $this->pemotonganTidakApel(3);
        } else {
          $besaPotongan = 0;
        }
        $list .= '
          <h2 class="card-inside-title">Besar Potongan</h2>
          <div class="row clearfix">
              <div class="col-sm-12">
                  <input type="text" id="besar_potongan" value="' . $besaPotongan . '" name="monday_from" class="form-control" required>
              </div>
          </div>
        ';
      }
    }
    echo $list;
  }

  public function pemotonganKeterlambatan($id = null)
  {
    $sess = $this->session->userdata('user');
    $cekUser = $this->Db_select->select_where('tb_user', 'user_id = "' . $sess['id_user'] . '"');

    // cek keterlambatan
    $jadwal = json_decode(file_get_contents($this->file))->jam_kerja;
    $day = strtolower(date("l"));
    $newJadwal = date('H:i:s', strtotime($jadwal->$day->from));
    // absen masuk
    $awal = date_create(date('H:i:s', strtotime($id)));
    // jam masuk
    $akhir = date_create($newJadwal);
    $pengurangan = 0;

    if ($awal > $akhir) {
      $diff = date_diff($awal, $akhir);
      $newDate = strtotime($diff->h . ":" . $diff->i . ":" . $diff->s);
      $newDate = date('H:i:s', $newDate);
      $cekKeterlambatan = $this->Db_select->query('select * from tb_potongan_keterlambatan where "' . $newDate . '" < durasi_keterlambatan limit 1');
      $saldoUser = $cekUser->saldo;
      if ($cekKeterlambatan) {
        $pengurangan = $cekKeterlambatan->potongan_keterlambatan * $saldoUser / 100;
      } else {
        $keterlambatanMax = $this->Db_select->query('select * from tb_potongan_keterlambatan order by durasi_keterlambatan desc limit 1');
        if ($keterlambatanMax) {
          $pengurangan = $keterlambatanMax->potongan_keterlambatan * $saldoUser / 100;
        }
      }
    }

    echo json_encode($pengurangan);
    exit();
  }

  public function pemotongantidakMasuk($id = null)
  {
    $sess = $this->session->userdata('user');
    if ($id != null) {
      $getUser = $this->Db_select->select_where('tb_user', 'user_id = "' . $sess['id_user'] . '"');
      $selectPotongan = $this->Db_select->select_where('tb_potongan_mangkir', 'id_mangkir = "' . $id . '"');
      if ($selectPotongan) {
        if ($selectPotongan->besar_potongan == null || $selectPotongan->besar_potongan == "") {
          $selectPotongan->besar_potongan = 0;
        }
        if ($getUser->saldo == null || $getUser->saldo == "") {
          $getUser->saldo = 0;
        }
        $pengurangan = $selectPotongan->besar_potongan * $getUser->saldo / 100;
      } else {
        $pengurangan = 0;
      }
      return $pengurangan;
    }
  }

  public function pemotonganPulangCepat($id = null)
  {
    $sess = $this->session->userdata('user');
    if ($id != null) {
      $getUser = $this->Db_select->select_where('tb_user', 'user_id = "' . $sess['id_user'] . '"');
      $selectPotongan = $this->Db_select->select_where('tb_potongan_keluar_jamkerja', 'id_meninggalkan_kantor = "' . $id . '"');
      $pengurangan = $selectPotongan->besar_potongan * $getUser->saldo / 100;
      return $pengurangan;
    }
  }

  public function pemotonganTidakApel($id = null)
  {
    $sess = $this->session->userdata('user');
    if ($id != null) {
      $getUser = $this->Db_select->select_where('tb_user', 'user_id = "' . $sess['id_user'] . '"');
      $selectPotongan = $this->Db_select->select_where('tb_potongan_apel', 'id_potongan_apel = "' . $id . '"');
      $pengurangan = $selectPotongan->besar_potongan * $getUser->saldo / 100;
      return $pengurangan;
    }
  }

  public function search()
  {
    $sess = $this->session->userdata('user');
    if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
      redirect(base_url());
      exit();
    }

    $this->load->view('Administrator/header');
    $this->load->view('Administrator/pegawai_search');
    $this->load->view('Administrator/footer');
  }

  public function getData()
  {
    $sess = $this->session->userdata('user');
    $where = 'b.id_channel = "' . $sess['id_channel'] . '"';
    $columns = array(
      0 =>  'nip',
      1 =>  'nama_user',
      2 =>  'jabatan',
      3 =>  'b.nama_unit',
      4 =>  'd.nama_status_user',
      5 =>  'a.role',
      6 =>  'a.is_aktif',
      7 =>  'aksi'
    );
    //filter data
    $filter = array();
    $query = " ";
    if ($this->input->get('departemen')) {
      $filter['departemen'] = $this->input->get('departemen');
      $filter['departemen'] = explode(',', $filter['departemen']);
      $departemen = "";
      foreach ($filter['departemen'] as $key => $value) {
        $departemen .= ",'$value'";
      }
      $departemen = substr($departemen, 1);
      $query .= " and a.id_unit in(" . $departemen . ")";
    }
    if ($this->input->get('jabatan')) {
      $filter['jabatan'] = $this->input->get('jabatan');
      $filter['jabatan'] = explode(',', $filter['jabatan']);
      $jabatan = "";
      foreach ($filter['jabatan'] as $key => $value) {
        $jabatan .= ",'$value'";
      }
      $jabatan = substr($jabatan, 1);
      $query .= " and a.jabatan in(" . $jabatan . ")";
    }
    if ($this->input->get('jenkel')) {
      $filter['jenkel'] = $this->input->get('jenkel');
      $filter['jenkel'] = explode(',', $filter['jenkel']);
      $jenkel = "";
      foreach ($filter['jenkel'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " and a.jenis_kelamin in(" . $jenkel . ")";
    }
    if ($this->input->get('tipe')) {
      $filter['tipe'] = $this->input->get('tipe');
      $filter['tipe'] = explode(',', $filter['tipe']);
      $tipe = "";
      foreach ($filter['tipe'] as $key => $value) {
        $tipe .= ",'$value'";
      }
      $tipe = substr($tipe, 1);
      $query .= " and status_user in(" . $tipe . ")";
    }

    $limit  = $this->input->post('length');
    $start  = $this->input->post('start');
    $order  = $columns[$this->input->post('order')[0]['column']];
    $dir    = $this->input->post('order')[0]['dir'];
    $totalData = $this->Db_global->allposts_count_all("select *, a.is_aktif as as_aktif from tb_user a join tb_unit b on a.id_unit = b.id_unit left outer join tb_jabatan c on a.jabatan = c.id_jabatan left outer join tb_status_user d on a.status_user = d.id_status_user where a.is_deleted = 0 and a.is_aktif = 1 and " . $where . " " . $query . "");
    $totalFiltered = $totalData;
    if (empty($this->input->post('search')['value'])) {
      $posts = $this->Db_global->allposts_all("select *, a.is_aktif as as_aktif from tb_user a join tb_unit b on a.id_unit = b.id_unit left outer join tb_jabatan c on a.jabatan = c.id_jabatan left outer join tb_status_user d on a.status_user = d.id_status_user where a.is_deleted = 0 and a.is_aktif = 1 and " . $where . " " . $query . " order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
    } else {
      $search = $this->input->post('search')['value'];
      $posts = $this->Db_global->posts_search_all("select *, a.is_aktif as as_aktif from tb_user a join tb_unit b on a.id_unit = b.id_unit left outer join tb_jabatan c on a.jabatan = c.id_jabatan left outer join tb_status_user d on a.status_user = d.id_status_user where a.is_deleted = 0 and a.is_aktif = 1 and " . $where . " " . $query . " and (a.nip like '%" . $search . "%' or a.nama_user like '%" . $search . "%' or a.jabatan like '%" . $search . "%' or b.nama_unit like '%" . $search . "%') order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");

      $totalFiltered = $this->Db_global->posts_search_count_all("select *, a.is_aktif as as_aktif from tb_user a join tb_unit b on a.id_unit = b.id_unit left outer join tb_jabatan c on a.jabatan = c.id_jabatan left outer join tb_status_user d on a.status_user = d.id_status_user where a.is_deleted = 0 and a.is_aktif = 1 and " . $where . " " . $query . " and (a.nip like '%" . $search . "%' or a.nama_user like '%" . $search . "%' or a.jabatan like '%" . $search . "%' or b.nama_unit like '%" . $search . "%')");
    }
    $ct = $this->Db_select->select_where('tb_pengaturan_cuti', 'id_channel = ' . $sess['id_channel']);
    if (!$ct) {
      $jumlah_cuti_tahunan = 0;
    } else {
      $jumlah_cuti_tahunan = $ct->jumlah_cuti_tahunan;
    }
    $data = array();
    if (!empty($posts)) {
      foreach ($posts as $key => $post) {
        if ($jumlah_cuti_tahunan == 0) {
          $sisa_cuti = $post->cuti;
        } else {
          $sisa_cuti = $post->cuti;
        }
        if ($post->as_aktif == 1) {
          $post->as_aktif = "<span class='badge badge-success'>Aktif</span>";
        } else {
          $post->as_aktif = "<span class='badge badge-danger'>Nonaktif</span>";
        }
        if ($post->gaji_pokok == null || $post->gaji_pokok == "") {
          $post->gaji_pokok = 0;
        }
        $nestedData['nip']  = $post->nip;
        $nestedData['nama_user']  = $post->nama_user;
        $nestedData['jabatan']  = $post->nama_jabatan;
        $nestedData['departemen']  = $post->nama_unit;
        $nestedData['tipe']  = $post->nama_status_user;
        $nestedData['role']  = !$post->role ? "<span class='badge badge-warning text-white'>Standar</span>" : "<span class='badge badge-info'>SDM / HR</span>";
        $nestedData['status']  = $post->as_aktif;
        $nestedData['aksi']  = '
        <a href="' . base_url() . 'Administrator/pegawai/edit/' . $post->user_id . '" class="btn btn-info btn-sm"><span class="fa fa-pencil-alt"></span></a>
        <a href="javascript:void(0)" onclick="hapus(' . $post->user_id . ')" class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></a>
        <a href="' . base_url() . 'Administrator/pegawai/changePassword/' . $post->user_id . '" class="btn btn-warning text-white btn-sm">Ubah Password</a>
        ';
        $data[] = $nestedData;
      }
    }
    $json_data = array(
      "draw"            => intval($this->input->post('draw')),
      "recordsTotal"    => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data"            => $data
    );

    echo json_encode($json_data);
  }

  public function changePassword($user_id)
  {
    $getUser = $this->Db_select->select_where('tb_user', ['user_id' => $user_id]);

    if ($getUser) {
      $data['user'] = $getUser;
      $menu['main'] = 'personalia';
      $menu['child'] = 'personalia_staff';
      $data['menu'] = $menu;

      $this->load->view('Administrator/header', $data);
      $this->load->view('Administrator/pegawai/change_password');
      $this->load->view('Administrator/footer');
    } else {
      show_404();
    }
  }

  public function actionChangePassword()
  {
    $getUser = $this->Db_select->select_where('tb_user', ['user_id' => $this->input->post('user_id')]);
    if ($getUser) {
      $update['password_user'] = md5($this->input->post('password'));

      if ($this->Db_dml->update('tb_user', $update, ['user_id' => $getUser->user_id])) {
        $result['status'] = true;
        $result['message'] = 'Password berhasil diubah.';
      } else {
        $result['status'] = true;
        $result['message'] = 'Password berhasil diubah.';
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data user tidak ditemukan.';
    }

    echo json_encode($result);
  }
}
