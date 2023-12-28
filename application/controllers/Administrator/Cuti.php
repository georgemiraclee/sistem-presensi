<?php defined('BASEPATH') or exit('No direct script access allowed');

class cuti extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->library(array('ceksession', 'global_lib'));
    $this->ceksession->login();
    $this->global_lib = new global_lib;
  }

  public function index()
  {
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];
    if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
      redirect(base_url());
      exit();
    }
    $data['cari'] = 'false';
    $data['departemen'] = $this->Db_select->query_all('SELECT id_unit, nama_unit FROM tb_unit WHERE id_channel = ? AND is_deleted = ? ORDER BY nama_unit ASC', [$id_channel, 0]);
    $data['jabatan'] = $this->Db_select->query_all('SELECT id_jabatan, nama_jabatan FROM tb_jabatan WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ? ORDER BY nama_jabatan ASC', [$id_channel, 1, 0]);
    $data['tipe'] = $this->Db_select->query_all('SELECT id_status_user, nama_status_user FROM tb_status_user WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ?', [$id_channel, 1, 0]);

    $menu['main'] = 'pengajuan';
    $menu['child'] = 'pengajuan_cuti';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/cuti');
    $this->load->view('Administrator/footer');
  }

  public function insert()
  {
    $sess = $this->session->userdata('user');
    $insert = array();
    $insert['id_channel'] = $sess['id_channel'];
    $insert['nama_unit'] = $this->input->post('nama_unit', true);
    $insert['alamat_unit'] = $this->input->post('alamat_unit', true);
    if ($_FILES['userfile']['name']) {
      $this->load->library('upload');
      $nmfile = "file_" . time(); //nama file saya beri nama langsung dan diikuti fungsi time
      $config['upload_path'] = './assets/images/unit'; //path folder
      $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
      $config['max_size'] = '2048'; //maksimum besar file 2M
      $config['file_name'] = $nmfile; //nama yang terupload nantinya
      $this->upload->initialize($config);
      if ($this->upload->do_upload('userfile')) {
        $gbr = $this->upload->data();
        $name3 = $gbr['file_name'];
        $insert['icon_unit'] = $name3;
      }
    }
    if (count($insert) > 0) {
      $insertData = $this->Db_dml->normal_insert('tb_unit', $insert);
      if ($insertData) {
        $result['status'] = true;
        $result['message'] = 'Data berhasil disimpan.';
        $result['data'] = array();
      } else {
        $result['status'] = false;
        $result['message'] = 'Data gagal disimpan.';
        $result['data'] = array();
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data gagal disimpan.';
      $result['data'] = array();
    }
    echo json_encode($result);
  }

  public function delete()
  {
    $where['id_unit'] = $this->input->post('id_unit', true);
    $delete = $this->Db_dml->delete('tb_unit', $where);
    if ($delete == 1) {
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
    $where['id_unit'] = $this->input->post('id_unit', true);
    $update = array();
    if ($this->input->post('nama_unit', true)) {
      $update['nama_unit'] = $this->input->post('nama_unit', true);
    }
    if ($this->input->post('alamat_unit', true)) {
      $update['alamat_unit'] = $this->input->post('alamat_unit', true);
    }
    if ($_FILES['userfile']['name']) {
      $this->load->library('upload');
      $nmfile = "file_" . time(); //nama file saya beri nama langsung dan diikuti fungsi time
      $config['upload_path'] = './assets/images/unit'; //path folder
      $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
      $config['max_size'] = '2048'; //maksimum besar file 2M
      $config['file_name'] = $nmfile; //nama yang terupload nantinya
      $this->upload->initialize($config);
      if ($this->upload->do_upload('userfile')) {
        $gbr = $this->upload->data();
        $name3 = $gbr['file_name'];
        $update['icon_unit'] = $name3;
      }
    }
    if (count($update) > 0) {
      $updateData = $this->Db_dml->update('tb_unit', $update, $where);
      if ($updateData) {
        $result['status'] = true;
        $result['message'] = 'Data berhasil disimpan.';
        $result['data'] = array();
      } else {
        $result['status'] = false;
        $result['message'] = 'Data gagal disimpan.';
        $result['data'] = array();
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data gagal disimpan.';
      $result['data'] = array();
    }
    echo json_encode($result);
  }

  public function approve_pengajuan()
  {
    $sess = $this->session->userdata('user');
    $update['status_approval'] = 1;
    $where['id_pengajuan'] = $this->input->post('id', true);
    $getUser = $this->Db_select->query('SELECT id_pengajuan, user_id, nama_status_pengajuan, tanggal_awal_pengajuan,tanggal_akhir_pengajuan FROM tb_pengajuan a JOIN tb_status_pengajuan b ON a.status_pengajuan = b.id_status_pengajuan WHERE a.id_pengajuan = ?', [$this->input->post('id', true)]);
    $dataUser = $this->Db_select->select_where('tb_user', ['user_id' => $getUser->user_id], ['user_id', 'cuti']);

    if ($dataUser) {
      /* cek type pengajuan */
      if ($getUser->nama_status_pengajuan == "cuti" || $getUser->nama_status_pengajuan == "Cuti" || $getUser->nama_status_pengajuan == "Cuti Tahunan") {
        $getCuti = $this->Db_select->select_where('tb_pengaturan_cuti', ['id_channel' => $sess['id_channel']], ['id_pengaturan_cuti']);

        if (!$getCuti) {
          $input['jumlah_cuti_tahunan'] = 14;
          $input['jatah_cuti_bulanan'] = 3;
          $input['batasan_cuti'] = 0;
          $input['id_channel'] = $sess['id_channel'];

          $this->Db_dml->insert('tb_pengaturan_cuti', $input);
        }

        $getCuti = $this->Db_select->select_where('tb_pengaturan_cuti', ['id_channel' => $sess['id_channel']], ['id_pengaturan_cuti', 'batasan_cuti', 'jatah_cuti_bulanan']);

        $beginday = date("Y-m-d", strtotime($getUser->tanggal_awal_pengajuan));
        $lastday = date("Y-m-d", strtotime($getUser->tanggal_akhir_pengajuan));

        $nr_work_days = $this->getWorkingDays($beginday, $lastday);

        $dataCuti = $dataUser->cuti;

        if ($dataCuti < $nr_work_days) {
          $result['status'] = false;
          $result['message'] = 'Cuti tahunan user telah habis.';
          $result['data'] = array();
        } else {
          if ($getCuti->batasan_cuti == 1) {
            $getHistory = $this->Db_select->query('SELECT COUNT(*) total FROM tb_pengajuan a JOIN tb_status_pengajuan b ON a.status_pengajuan = b.id_status_pengajuan WHERE user_id = ? AND nama_status_pengajuan IN ? AND status_approval = ? AND date_format(now(), "%Y-%m") = date_format(tanggal_awal_pengajuan, "%Y-%m")', [$getUser->user_id, ["Cuti", "Cuti Tahunan"], 1]);

            if ($getHistory->total > $getCuti->jatah_cuti_bulanan) {
              $this->result = array(
                'status' => false,
                'message' => 'Cuti bulanan anda sudah habis',
                'data' => null
              );

              $this->loghistory->createLog($this->result);
              echo json_encode($this->result, JSON_NUMERIC_CHECK);
              exit();
            }
          }

          $updateData = $this->Db_dml->update('tb_pengajuan', $update, $where);
          if ($updateData) {
            /* history Approval */
            $historyApproval['id_pengajuan'] = $this->input->post('id');
            $historyApproval['user_id'] = $sess['id_user'];
            $historyApproval['status'] = 1;
            $this->Db_dml->insert('tb_history_approval_pengajuan', $historyApproval);

            $startDate = date_create(date('Y-m-d', strtotime($getUser->tanggal_awal_pengajuan)));
            $endDate = date_create(date('Y-m-d', strtotime($getUser->tanggal_akhir_pengajuan)));
            $interval = date_diff($startDate, $endDate);

            for ($i = 0; $i < ($interval->days + 1); $i++) {
              $nowDate = date('Y-m-d', strtotime($getUser->tanggal_awal_pengajuan . ' +' . $i . ' day'));
              $insertAbsensi['user_id'] = $getUser->user_id;
              $insertAbsensi['status_absensi'] = $getUser->nama_status_pengajuan;
              $insertAbsensi['id_status_pengajuan'] = $getUser->id_pengajuan;
              $insertAbsensi['is_attendance'] = 0;
              $insertAbsensi['created_absensi'] = date("Y-m-d", strtotime($nowDate));
              $this->Db_dml->insert('tb_absensi', $insertAbsensi);
            }

            /*update sisa cuti*/
            $whereCuti['user_id'] = $getUser->user_id;
            $updateCuti['cuti'] = $dataCuti;
            $this->Db_dml->update('tb_user', $updateCuti, $whereCuti);

            $getData = $this->Db_select->query('SELECT b.nama_status_pengajuan FROM tb_pengajuan a JOIN tb_status_pengajuan b IN a.status_pengajuan = b.id_status_pengajuan WHERE a.id_pengajuan = ?', [$this->input->post('id', true)]);
            $result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();

            $message = "Pengajuan  " . $getData->nama_status_pengajuan . " Anda Telah Disetujui";
            $this->global_lib->send_notification_user($getUser->user_id, 'acc_pengajuan', $message, $this->input->post('id', true));
            // FCM
            $this->global_lib->NEWsendFCM('Approval Pengajuan ' . $getData->nama_status_pengajuan, $message, $getUser->user_id, '', 'pengajuan', $getData->nama_status_pengajuan);
          } else {
            $result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
          }
        }
      } else {
        $updateData = $this->Db_dml->update('tb_pengajuan', $update, $where);
        if ($updateData) {
          /* history Approval */
          $historyApproval['id_pengajuan'] = $this->input->post('id');
          $historyApproval['user_id'] = $sess['id_user'];
          $historyApproval['status'] = 1;
          $this->Db_dml->insert('tb_history_approval_pengajuan', $historyApproval);

          $startDate = date_create(date('Y-m-d', strtotime($getUser->tanggal_awal_pengajuan)));
          $endDate = date_create(date('Y-m-d', strtotime($getUser->tanggal_akhir_pengajuan)));
          $interval = date_diff($startDate, $endDate);

          for ($i = 0; $i < ($interval->days + 1); $i++) {
            $nowDate = date('Y-m-d', strtotime($getUser->tanggal_awal_pengajuan . ' +' . $i . ' day'));
            $insertAbsensi['user_id'] = $getUser->user_id;
            $insertAbsensi['status_absensi'] = $getUser->nama_status_pengajuan;
            $insertAbsensi['id_status_pengajuan'] = $getUser->id_pengajuan;
            $insertAbsensi['is_attendance'] = 0;
            $insertAbsensi['created_absensi'] = date("Y-m-d", strtotime($nowDate));
            $this->Db_dml->insert('tb_absensi', $insertAbsensi);
          }

          $getData = $this->Db_select->query('SELECT b.nama_status_pengajuan FROM tb_pengajuan a JOIN tb_status_pengajuan b ON a.status_pengajuan = b.id_status_pengajuan WHERE a.id_pengajuan = ?', [$this->input->post('id', true)]);

          $result['status'] = true;
          $result['message'] = 'Data berhasil disimpan.';
          $result['data'] = array();

          $message = "Pengajuan  " . $getData->nama_status_pengajuan . " Anda Telah Disetujui";
          $this->global_lib->send_notification_user($getUser->user_id, 'acc_pengajuan', $message, $this->input->post('id', true));
          // FCM
          $this->global_lib->NEWsendFCM('Approval Pengajuan ' . $getData->nama_status_pengajuan, $message, $getUser->user_id, '', 'pengajuan', $getData->nama_status_pengajuan);
        } else {
          $result['status'] = false;
          $result['message'] = 'Data gagal disimpan.';
          $result['data'] = array();
        }
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data tidak ditemukan.';
      $result['data'] = array();
    }
    // if ($cekRoleAccess) {
    // } else {
    //   $result['status'] = false;
    //   $result['message'] = 'Pengajuan belum dapat persetujuan dari atasan terkait.';
    //   $result['data'] = null;
    // }


    echo json_encode($result);
  }

  public function getRoleAccess($id_pengajuan, $data)
  {
    if ($data->id_parent) {
      $sess = $this->session->userdata('user');
      if ($sess['id_user'] == $data->id_parent) {
        return true;
      }

      $cekApproval = $this->Db_select->select_where('tb_history_approval_pengajuan', ['id_pengajuan' => $id_pengajuan], ['id']);
      if ($cekApproval) {
        return true;
      }

      return false;
    }

    return true;
  }

  public function reject_pengajuan()
  {
    $sess = $this->session->userdata('user');
    $where['id_pengajuan'] = $this->input->post('id', true);
    $checkData = $this->Db_select->select_where('tb_pengajuan', $where, ['user_id']);
    if ($checkData) {
      $dataUser = $this->Db_select->select_where('tb_user', ['user_id' => $checkData->user_id], ['id_parent']);

      $cekRoleAccess = $this->getRoleAccess($this->input->post('id', true), $dataUser);

      if ($cekRoleAccess) {
        $update['status_approval'] = 2;
        $updateData = $this->Db_dml->update('tb_pengajuan', $update, $where);
        $getData = $this->Db_select->query('SELECT b.nama_status_pengajuan FROM tb_pengajuan a JOIN tb_status_pengajuan b ON a.status_pengajuan = b.id_status_pengajuan WHERE a.id_pengajuan = ?', [$this->input->post('id', true)]);
        if ($updateData) {
          /* history Approval */
          $historyApproval['id_pengajuan'] = $this->input->post('id', true);
          $historyApproval['user_id'] = $sess['id_user'];
          $historyApproval['status'] = 2;
          $historyApproval['note'] = $this->input->post('note');
          $this->Db_dml->insert('tb_history_approval_pengajuan', $historyApproval);

          $result['status'] = true;
          $result['message'] = 'Data berhasil disimpan.';
          $result['data'] = array();

          $getUser = $this->Db_select->select_where('tb_pengajuan', $where)->user_id;
          $message = "Pengajuan  " . $getData->nama_status_pengajuan . " Anda Tidak Disetujui";
          $this->global_lib->send_notification_user($getUser, 'reject_pengajuan', $message, $this->input->post('id'));
          // FCM
          $this->global_lib->NEWsendFCM('Approval Pengajuan ' . $getData->nama_status_pengajuan, $message, $getUser, '', 'pengajuan', $getData->nama_status_pengajuan);
        } else {
          $result['status'] = false;
          $result['message'] = 'Data gagal disimpan.';
          $result['data'] = array();
        }
      } else {
        $result['status'] = false;
        $result['message'] = 'Pengajuan belum dapat persetujuan dari atasan terkait.';
        $result['data'] = null;
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data pengajuan tidak ditemukan.';
      $result['data'] = array();
    }

    echo json_encode($result);
  }

  public function detail_cuti($id)
  {
    $getData = $this->Db_select->select_where('tb_pengajuan', ['id_pengajuan' => $id], ['user_id']);

    if ($getData) {
      $user = $this->Db_select->query('SELECT a.*, b.nama_jabatan, c.nama_status_user FROM tb_user a JOIN tb_jabatan b ON a.jabatan = b.id_jabatan JOIN tb_status_user c ON a.status_user = c.id_status_user WHERE a.user_id = ?', [$getData->user_id]);
      $data_cuti = $this->Db_select->query('SELECT a.id_pengajuan, a.url_file_pengajuan, a.status_approval, a.tanggal_awal_pengajuan, a.tanggal_akhir_pengajuan, a.keterangan_pengajuan, b.foto_user, d.nama_status_pengajuan FROM tb_pengajuan a JOIN tb_user b ON a.user_id = b.user_id JOIN tb_unit c ON b.id_unit = c.id_unit JOIN tb_status_pengajuan d ON a.status_pengajuan = d.id_status_pengajuan WHERE id_pengajuan = ?', [$id]);
      $getHistory = $this->Db_select->query('SELECT COUNT(*) total FROM tb_pengajuan a JOIN tb_status_pengajuan b ON a.status_pengajuan = b.id_status_pengajuan WHERE user_id = 
      ? AND nama_status_pengajuan IN ? AND status_approval = ?', [$getData->user_id, ["Cuti", "Cuti Tahunan"], 1]);
      

      $user->sisa_cuti = $user->cuti - $getHistory->total;

      if ($data_cuti->url_file_pengajuan != null) {
        $data['image'] = '
          <div class = "row">
            <div class="col-md-12">
              <div class="thumbnail">
                <img src="' . base_url() . 'assets/images/pengajuan_sakit/' . $data_cuti->url_file_pengajuan . '">
              </div>
            </div>
          </div>';
      } else {
        $data['image'] = '';
      }

      if ($data_cuti->status_approval == 1) {
        $data_cuti->status_approval2 = '<span class="badge bg-green">Approval</span>';
        $data['tombol'] = '';
      } else if ($data_cuti->status_approval == 2) {
        $data_cuti->status_approval2 = '<span class="badge badge-danger">Rejected</span>';
        $data['tombol'] = '';
      } else {
        $data_cuti->status_approval2 = '<span class="badge badge-warning text-white">Butuh Konfirmasi</span>';
        $data['tombol'] = '
          <button id="acc' . $data_cuti->id_pengajuan . '" onclick="acc(' . $data_cuti->id_pengajuan . ')" class="btn btn-primary btn-block btn-sm">ACC</button>
          <button id="reject' . $data_cuti->id_pengajuan . '" onclick="reject(' . $data_cuti->id_pengajuan . ')" class="btn btn-warning btn-block btn-sm text-white">Reject</button>';
      }

      if ($data_cuti->foto_user == "" || $data_cuti->foto_user == null) {
        $data['foto'] = base_url() . "assets/images/member-photos/ava.png ";
      } else {
        $filename = './assets/images/member-photos/' . $data_cuti->foto_user;
        if (!file_exists($filename)) {
          $data['foto'] = "http://placehold.it/500x400";
        } else {
          $data['foto'] = base_url() . "assets/images/member-photos/" . $data_cuti->foto_user;
        }
      }

      $data['history'] = $this->Db_select->query_all('select *from tb_history_approval_pengajuan a join tb_user b on a.user_id = b.user_id where a.id_pengajuan = ' . $id);
      // echo json_encode($data['history'] ); exit();
      $data['item'] = $data_cuti;
      $data['user'] = $user;

      $menu['main'] = 'pengajuan';
      $menu['child'] = 'pengajuan_cuti';
      $data['menu'] = $menu;

      $this->load->view('Administrator/header', $data);
      $this->load->view('Administrator/detail_cuti');
      $this->load->view('Administrator/footer');
    } else {
      $this->output->set_status_header('404');
      return $this->load->view('errors/html/error_404');
    }
  }

  public function search()
  {
    $sess = $this->session->userdata('user');
    if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
      redirect(base_url());
      exit();
    }
    $data['cari'] = 'true';
    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/cuti');
    $this->load->view('Administrator/footer');
  }

  /* setting Cuti & Izin */
  public function setting()
  {
    $sess = $this->session->userdata('user');
    if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
      redirect(base_url());
      exit();
    }
    $data['cari'] = 'false';
    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/setting_cuti');
    $this->load->view('Administrator/footer');
  }

  public function add_setting()
  {
    $sess = $this->session->userdata('user');

    $this->load->view('Administrator/header');
    $this->load->view('Administrator/tambah_setting_cuti');
    $this->load->view('Administrator/footer');
  }

  public function getData()
  {
    $sess = $this->session->userdata('user');

    $columns = array(
      0 =>  'a.id_pengajuan',
      1 =>  'b.nip',
      2 =>  'b.nama_user',
      3 =>  'a.tanggal_awal_pengajuan',
      4 =>  'a.tanggal_akhir_pengajuan',
      5 =>  'a.keterangan_pengajuan',
      6 =>  'd.nama_status_pengajuan',
      7 =>  'a.status_approval',
      8 =>  'aksi'
    );

    $query = "";

    if ($this->input->get('departemen', true)) {
      $filterDepartemen = $this->input->get('departemen', true);
      $filterDepartemen = explode(',', $filterDepartemen);
      $departemen = "";
      foreach ($filterDepartemen as $key => $value) {
        $departemen .= ",'$value'";
      }
      $departemen = substr($departemen, 1);
      $query .= " AND b.id_unit IN(" . $departemen . ")";
    }
    if ($this->input->get('jabatan', true)) {
      $filterJabatan = $this->input->get('jabatan', true);
      $filterJabatan = explode(',', $filterJabatan);
      $jabatan = "";
      foreach ($filterJabatan as $key => $value) {
        $jabatan .= ",'$value'";
      }
      $jabatan = substr($jabatan, 1);
      $query .= " AND b.jabatan IN(" . $jabatan . ")";
    }
    if ($this->input->get('jenkel', true)) {
      $filterJenkel = $this->input->get('jenkel', true);
      $filterJenkel = explode(',', $filterJenkel);
      $jenkel = "";
      foreach ($filterJenkel as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " AND b.jenis_kelamin IN(" . $jenkel . ")";
    }
    if ($this->input->get('tipe', true)) {
      $filterType = $this->input->get('tipe', true);
      $filterType = explode(',', $filterType);
      $tipe = "";
      foreach ($filterType as $key => $value) {
        $tipe .= ",'$value'";
      }
      $tipe = substr($tipe, 1);
      $query .= " AND b.status_user IN(" . $tipe . ")";
    }

    $where = 'c.id_channel = "' . $sess['id_channel'] . '" AND b.is_aktif = 1' . $query;

    $limit  = $this->input->post('length', true);
    $start  = $this->input->post('start', true);
    $order  = $columns[$this->input->post('order', true)[0]['column']];
    $dir    = $this->input->post('order', true)[0]['dir'];
    $totalData = $this->Db_global->allposts_count_all("select * from tb_pengajuan a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where " . $where . "");
    $totalFiltered = $totalData;

    if (empty($this->input->post('search', true)['value'])) {
      $posts = $this->Db_global->allposts_all("select *from tb_pengajuan a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where " . $where . " order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
    } else {
      $search = $this->input->post('search', true)['value'];
      $posts = $this->Db_global->allposts_all("select *from tb_pengajuan a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where " . $where . " and (b.nip like '%" . $search . "%' or b.nama_user like '%" . $search . "%' or a.keterangan_pengajuan like '%" . $search . "%' or a.status_approval like '%" . $search . "%') order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
      $totalFiltered = $this->Db_global->posts_search_count_all("select *from tb_pengajuan a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where " . $where . " and (b.nip like '%" . $search . "%' or b.nama_user like '%" . $search . "%' or a.keterangan_pengajuan like '%" . $search . "%' or a.status_approval like '%" . $search . "%')");
    }

    $data = array();
    if (!empty($posts)) {
      foreach ($posts as $key => $post) {
        if ($post->status_approval == 0) {
          $post->status_approval = "<p style='color: orange;'>PENDING</p>";
        } elseif ($post->status_approval == 1) {
          $post->status_approval = "<p style='color: green;'>ACC</p>";
        } else {
          $post->status_approval = "<p style='color: red;'>DITOLAK</p>";
        }

        $nestedData['no'] = $key + 1;
        $nestedData['nip'] = $post->nip;
        $nestedData['nama_user'] = $post->nama_user;
        $nestedData['tanggal_awal_pengajuan'] = date_format(date_create($post->tanggal_awal_pengajuan), 'Y-m-d');
        $nestedData['tanggal_akhir_pengajuan'] = date_format(date_create($post->tanggal_akhir_pengajuan), 'Y-m-d');
        $nestedData['keterangan_pengajuan'] = $post->keterangan_pengajuan;
        $nestedData['kategori'] = $post->nama_status_pengajuan;
        $nestedData['status_approval'] = $post->status_approval;
        $nestedData['aksi'] = '<a href="' . base_url() . 'Administrator/cuti/detail_cuti/' . $post->id_pengajuan . '" class="btn btn-primary btn-sm"><span class="fa fa-eye"></span></a>';

        $data[] = $nestedData;
      }
    }
    $json_data = array(
      "draw" => intval($this->input->post('draw')),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data" => $data
    );

    echo json_encode($json_data);
  }

  function getWorkingDays($startDate, $endDate)
  {
    $begin = strtotime($startDate);
    $end = strtotime($endDate);

    return ceil(abs($end - $begin) / 86400);
  }
}
