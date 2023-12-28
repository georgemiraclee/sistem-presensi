<?php defined('BASEPATH') or exit('No direct script access allowed');

class data_absensi extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->library(array('Ceksession', 'global_lib'));
    $this->load->model('Db_datatable');
    $this->ceksession->login();
    $this->global_lib = new global_lib;
  }

  public function index()
  {
    $user = $this->session->userdata('user');
    if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama" && $user['role_access'] != '1') {
      redirect(base_url());
      exit();
    }
    $data['skpd'] = $this->Db_select->query_all('SELECT id_unit, nama_unit FROM tb_unit WHERE id_channel = ? AND is_deleted = ? ORDER BY nama_unit', [$user['id_channel'], 0]);
    $data['jabatan'] = $this->Db_select->query_all('SELECT id_jabatan, nama_jabatan FROM tb_jabatan WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ? ORDER BY nama_jabatan ASC', [$user['id_channel'], 1, 0]);
    $data['karyawan'] = $this->Db_select->query_all('SELECT a.user_id, a.nama_user, a.nip FROM tb_user a JOIN tb_unit b ON a.id_unit = b.id_unit WHERE b.id_channel = ? AND a.is_admin = ? and a.is_superadmin = ? and a.is_aktif = ?', [$user['id_channel'], 0, 0, 1]);
    $data['lokasi'] = $this->Db_select->select_all_where('tb_lokasi', ['id_channel' => $user['id_channel'], 'is_deleted' => 0], ['id_lokasi', 'nama_lokasi']);

    $menu['main'] = 'kehadiran';
    $menu['child'] = 'kehadiran_absensi';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/data_absensi');
    $this->load->view('Administrator/footer');
  }

  public function batalkan()
  {
    $insert['id_absensi'] = $this->input->post('id_absensi');
    $selectUser = $this->Db_select->select_where('tb_absensi', $insert);
    $selectNewUser = $this->Db_select->select_where('tb_user', 'user_id = "' . $selectUser->user_id . '"');
    if (count($insert) > 0) {
      $insertData = $this->Db_dml->normal_insert('tb_pembatalan_absensi', $insert);
      if ($insertData) {
        $message = "Absen Anda Telah Dibatalkan";
        $this->global_lib->send_notification_user($selectUser->user_id, 'pembatalan_absensi', $message, $this->input->post('id_absensi'));
        // FCM
        $this->global_lib->sendFCM('Pembatalan Absensi', $message, $selectUser->user_id);
        $result['status'] = true;
        $result['message'] = "Absen Berhasil Dibatalkan";
        $result['data'] = null;
      } else {
        $result['status'] = false;
        $result['message'] = "Absen Gagal Dibatalkan";
        $result['data'] = null;
      }
    } else {
      $result['status'] = false;
      $result['message'] = "Absen Gagal Disimpan";
      $result['data'] = null;
    }
    echo json_encode($result);
  }

  public function potonganBatalAbsen($user_id, $saldo)
  {
    $selectPotongan = $this->Db_select->select_where('tb_potongan_batal_absensi', 'id_potongan_batal_absensi = 1');
    $pengurangan = $selectPotongan->besar_potongan * $saldo / 100;
    $insert['id_potongan_batal_absensi'] = 1;
    $insert['user_id'] = $user_id;
    $insert['total_potongan'] = $pengurangan;
    $this->Db_dml->normal_insert('tb_hstry_potongan_batal_absensi', $insert);
  }

  function get_data_user($value = null)
  {
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];

    $where['id_channel'] = $id_channel;
    $where['is_default'] = 1;
    $getPola = $this->Db_select->select_where('tb_pola_kerja', $where, ['file_pola_kerja']);
    $file = 'appconfig/new/' . $getPola->file_pola_kerja;
    $jadwal = json_decode(file_get_contents($file))->jam_kerja;

    $filter = array();
    $data['skpd'] = $this->Db_select->query_all('SELECT id_unit, nama_unit FROM tb_unit WHERE id_channel = ? AND is_deleted = ? ORDER BY nama_unit', [$id_channel, 0]);
    $data['jabatan'] = $this->Db_select->query_all('SELECT id_jabatan, nama_jabatan FROM tb_jabatan WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ? ORDER BY nama_jabatan ASC', [$id_channel, 1, 0]);

    //filter data
    $query = " ";
    if ($this->input->get('skpd', true)) {
      $filter['skpd'] = $this->input->get('skpd', true);
      $query .= " AND tb_user.id_unit IN(" . $this->input->get('skpd', true) . ")";
    }
    if ($this->input->get('jabatan', true)) {
      $filter['jabatan'] = $this->input->get('jabatan', true);
      $filter['jabatan'] = explode(',', $filter['jabatan']);
      $jabatan = "";
      foreach ($filter['jabatan'] as $key => $value) {
        $jabatan .= ",'$value'";
      }
      $jabatan = substr($jabatan, 1);
      $query .= " AND tb_user.jabatan IN(" . $jabatan . ")";
    }
    if ($this->input->get('jenkel', true)) {
      $filter['jenkel'] = $this->input->get('jenkel', true);
      $filter['jenkel'] = explode(',', $filter['jenkel']);
      $jenkel = "";
      foreach ($filter['jenkel'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " AND tb_user.jenis_kelamin IN(" . $jenkel . ")";
    }
    if ($this->input->get('status', true)) {
      $filter['status'] = $this->input->get('status', true);
      $filter['status'] = explode(',', $filter['status']);
      $jenkel = "";
      foreach ($filter['status'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " AND status_absensi IN(" . $jenkel . ")";
    }
    if ($this->input->get('dari', true)) {
      $filter['dari'] = $this->input->get('dari', true);
      $query .= " AND DATE(created_absensi)  >= '" . $this->input->get('dari', true) . "'";
    }
    if ($this->input->get('sampai', true)) {
      $filter['sampai'] = $this->input->get('sampai', true);
      $query .= " AND DATE(created_absensi) <= '" . $this->input->get('sampai', true) . "' ";
    }
    if ($this->input->get('status_user', true) != null || $this->input->get('status_user', true) != "") {
      $filter['status_user'] = $this->input->get('status_user', true);
      $filter['status_user'] = explode(',', $filter['status_user']);
      $jenkel = "";
      foreach ($filter['status_user'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " AND tb_user.is_aktif IN(" . $jenkel . ")";
    }
    $st = 'id_channel=' . $id_channel . ' AND is_attendance = 1' . $query;
    $list = $this->Db_datatable->get_datatables($st, ['tb_absensi.waktu_pulang', 'tb_absensi.waktu_datang', 'tb_absensi.waktu_istirahat', 'tb_absensi.waktu_kembali', 'tb_absensi.waktu_keterlambatan', 'tb_absensi.status_absensi', 'tb_absensi.created_absensi', 'tb_absensi.user_id', 'tb_absensi.day_off', 'tb_absensi.day_off_desc', 'tb_absensi.id_absensi', 'tb_user.nip', 'tb_user.nama_user']);
    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {
      $no++;
      if ($field->waktu_pulang == null || $field->waktu_pulang == '') {
        $pulang = '-';
      } else {
        $pulang = date('H:i', strtotime($field->waktu_pulang));
      }

      //keterangan
      if ($field->waktu_keterlambatan == 0) {
        $day = strtolower(date("l", strtotime($field->waktu_datang)));
        $jadwalNew = date_create($jadwal->$day->from);
        $jam_skrg = date_create(date("H:i", strtotime($field->waktu_datang)));
        $diff  = date_diff($jam_skrg, $jadwalNew);
        $keteranganTerlambat = "";
        if ($field->status_absensi == "Terlambat") {
          if ($diff->h != 0) {
            $keteranganTerlambat .= $diff->h . " Jam ";
          }
          if ($diff->i != 0) {
            $keteranganTerlambat .= $diff->i . " Menit ";
          }
        }
      } else {
        $keteranganTerlambat = $this->global_lib->konversi_detik($field->waktu_keterlambatan);
      }

      if ($field->status_absensi == 'Tidak Hadir') {
        $pulang = "-";

        $created_absensi = date("Y-m-d", strtotime($field->created_absensi));
        $ss = $this->Db_select->query('SELECT status_pengajuan FROM tb_pengajuan WHERE user_id = ? AND tanggal_awal_pengajuan BETWEEN ? AND ? AND status_approval = ?', [$field->user_id, $created_absensi . ' 00:00:00', $created_absensi . '23:59:59', 1]);

        if ($ss == "" || $ss == null) {
          $field->status_absensi = "Tidak Hadir";
        } else {
          $tt = $this->Db_select->select_where('tb_status_pengajuan', 'id_status_pengajuan = ' . $ss->status_pengajuan);
          $field->status_absensi = $tt->nama_status_pengajuan;
        }
      }

      $waktu_datang = $field->waktu_datang ? date("H:i", strtotime($field->waktu_datang)) : null;
      $waktu_istirahat = $field->waktu_istirahat ? date("H:i", strtotime($field->waktu_istirahat)) : null;
      $waktu_kembali = $field->waktu_kembali ? date("H:i", strtotime($field->waktu_kembali)) : null;
      $waktu_pulang = $field->waktu_pulang ? date("H:i", strtotime($field->waktu_pulang)) : null;

      $total_jam = null;
      if ($waktu_datang && $waktu_istirahat && $waktu_kembali && $waktu_pulang) {
        $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
        $minutesKerja = $total_kerja->days * 24 * 60;
        $minutesKerja += $total_kerja->h * 60;
        $minutesKerja += $total_kerja->i;

        $total_istirahat = date_diff(date_create($waktu_istirahat), date_create($waktu_kembali));
        $minutesIstirahat = $total_istirahat->days * 24 * 60;
        $minutesIstirahat += $total_istirahat->h * 60;
        $minutesIstirahat += $total_istirahat->i;

        $total_jam = $this->convertToHoursMins($minutesKerja - $minutesIstirahat);
      } elseif ($waktu_datang && $waktu_pulang) {
        $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
        $minutesKerja = $total_kerja->days * 24 * 60;
        $minutesKerja += $total_kerja->h * 60;
        $minutesKerja += $total_kerja->i;

        $total_jam = $minutesKerja ? $this->convertToHoursMins($minutesKerja) : '0 Jam';
      }

      $statusBadge = "";

      if ($field->status_absensi == "Tidak Hadir") {
        $labelStatus = "";
        if ($field->day_off) {
          $labelStatus = "(" . ucwords($field->day_off_desc) . ")";
        }

        $statusBadge = "<span class='badge badge-danger'>Tidak hadir " . $labelStatus . "</span>";
      } else if ($field->status_absensi == "Terlambat") {
        $statusBadge = "<span class='badge badge-warning text-white'>Terlambat " . $keteranganTerlambat . "</span>";
      } else if ($field->status_absensi == "Tepat Waktu") {
        $statusBadge = "<span class='badge badge-success'>" . $field->status_absensi . "</span>";
      } else {
        $statusBadge = "<span class='badge badge-warning text-white'>" . $field->status_absensi . "</span>";
      }

      //keterangan
      $row = array();
      $row[] = $field->nip;
      $row[] = $field->nama_user;
      $row[] = date('d-m-Y', strtotime($field->created_absensi));
      $row[] = $waktu_datang ? $waktu_datang : '-';
      $row[] = $pulang;
      $row[] = $total_jam ? $total_jam : '-';
      $row[] = $statusBadge;
      $row[] = '
            <a href="' . base_url() . 'Administrator/data_absensi/detail/' . $field->id_absensi . '" class="btn btn-info btn-sm"><span class="fa fa-search"></span></a>
            <button onclick="changeAbsensi(' . $field->id_absensi . ')" class="btn btn-secondary btn-sm"><span class="fa fa-pencil-alt"></span></button>
          ';
      $data[] = $row;
    }

    if ($this->input->post('order')[0]['column'] == 5) {
      for ($i = 0; $i < count($data); $i++) {
        for ($k = $i + 1; $k < count($data); $k++) {
          if ($this->input->post('order')[0]['dir'] == 'asc') {
            if (($data[$i][5] !== '-') && ($data[$k][5] !== '-')) {
              $jam_pulang_prev = explode(' ', $data[$i][5]);
              $jam_pulang_next = explode(' ', $data[$k][5]);
              $minute_prev = (intval($jam_pulang_prev[0]) * 60) + intval(isset($jam_pulang_prev[2]));
              $minute_next = (intval($jam_pulang_next[0]) * 60) + intval(isset($jam_pulang_next[2]));
              if ($minute_next < $minute_prev) {
                $tmp = $data[$i][5];
                $data[$i][5] = $data[$k][5];
                $data[$k][5] = $tmp;
              }
            }
            
            if (($data[$i][5] !== '-') && ($data[$k][5] == '-')) {
              $tmp = $data[$i][5];
              $data[$i][5] = $data[$k][5];
              $data[$k][5] = $tmp;
            }
          }
          if ($this->input->post('order')[0]['dir'] == 'desc') {
            if (($data[$i][5] !== '-') && ($data[$k][5] !== '-')) {
              $jam_pulang_prev = explode(' ', $data[$i][5]);
              $jam_pulang_next = explode(' ', $data[$k][5]);
              $minute_prev = (intval($jam_pulang_prev[0]) * 60) + intval(isset($jam_pulang_prev[2]));
              $minute_next = (intval($jam_pulang_next[0]) * 60) + intval(isset($jam_pulang_next[2]));
              if ($minute_next > $minute_prev) {
                $tmp = $data[$i][5];
                $data[$i][5] = $data[$k][5];
                $data[$k][5] = $tmp;
              }
            }

            if (($data[$i][5] == '-') && ($data[$k][5] !== '-')) {
              $tmp = $data[$k][5];
              $data[$k][5] = $data[$i][5];
              $data[$i][5] = $tmp;
            }
          }
        }
      }
    }
    
    if ($this->input->post('order')[0]['column'] == 4) {
      for ($i = 0; $i < count($data); $i++) {
        for ($k = $i + 1; $k < count($data); $k++) {
          if ($this->input->post('order')[0]['dir'] == 'asc') {
            if (($data[$i][4] !== '-') && ($data[$k][4] !== '-')) {
              $jam_pulang_prev = explode(':', $data[$i][4]);
              $jam_pulang_next = explode(':', $data[$k][4]);
              $minute_prev = (intval($jam_pulang_prev[0]) * 60) + intval(isset($jam_pulang_prev[2]));
              $minute_next = (intval($jam_pulang_next[0]) * 60) + intval(isset($jam_pulang_next[2]));
              if ($minute_next < $minute_prev) {
                $tmp = $data[$i][4];
                $data[$i][4] = $data[$k][4];
                $data[$k][4] = $tmp;
              }
            }
          }
          if ($this->input->post('order')[0]['dir'] == 'desc') {
            if (($data[$i][4] !== '-') && ($data[$k][4] !== '-')) {
              $jam_pulang_prev = explode(':', $data[$i][4]);
              $jam_pulang_next = explode(':', $data[$k][4]);
              $minute_prev = (intval($jam_pulang_prev[0]) * 60) + intval(isset($jam_pulang_prev[2]));
              $minute_next = (intval($jam_pulang_next[0]) * 60) + intval(isset($jam_pulang_next[2]));
              if ($minute_next > $minute_prev) {
                $tmp = $data[$i][4];
                $data[$i][4] = $data[$k][4];
                $data[$k][4] = $tmp;
              }
            }
          }
        }
      }
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Db_datatable->count_all($st),
      "recordsFiltered" => $this->Db_datatable->count_filtered($st, ['COUNT(*) total'])->total,
      "data" => $data,
    );

    //output dalam format JSON
    echo json_encode($output);
  }

  public function detail($id_absensi)
  {
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];

    $query = $this->Db_select->query('select f.user_id, f.nama_user, a.tagging, a.location_tagging, a.manual_absen, a.created_absensi, a.waktu_datang, a.waktu_istirahat, a.waktu_kembali, a.waktu_pulang, a.status_absensi, a.url_file_absensi, b.lat, b.lng, e.nama_lokasi, c.ssid_jaringan, c.mac_address_jaringan, a.waktu_keterlambatan from tb_absensi a left join tb_history_absensi b on a.id_absensi = b.id_absensi left join tb_jaringan c on b.id_jaringan = c.id_jaringan left join tb_lokasi e on b.id_lokasi =e.id_lokasi left join tb_user f on a.user_id = f.user_id  where a.id_absensi = "' . $id_absensi . '" ');

    if ($query->url_file_absensi == "" || $query->url_file_absensi == null) {
      $query->url_file_absensi = base_url() . "assets/images/absensi/default_photo.jpg";
    } else {
      $query->url_file_absensi = base_url() . 'assets/images/absensi/' . $query->url_file_absensi;
    }

    $status_absensi = $query->status_absensi;
    if ($query->status_absensi == "Terlambat") {
      $keteranganTerlambat = "";
      if ($query->waktu_keterlambatan == 0) {
        $where['id_channel'] = $id_channel;
        $where['is_default'] = 1;
        $getPola = $this->Db_select->select_where('tb_pola_kerja', $where);
        $file = 'appconfig/new/' . $getPola->file_pola_kerja;
        $jadwal = json_decode(file_get_contents($file))->jam_kerja;

        $day = strtolower(date("l", strtotime($query->waktu_datang)));
        $jadwalNew = date_create($jadwal->$day->from);
        $jam_skrg = date_create(date("H:i", strtotime($query->waktu_datang)));
        $diff  = date_diff($jam_skrg, $jadwalNew);
        if ($diff->h != 0) {
          $keteranganTerlambat .= $diff->h . " Jam ";
        }
        if ($diff->i != 0) {
          $keteranganTerlambat .= $diff->i . " Menit ";
        }
      } else {
        $keteranganTerlambat = $this->global_lib->konversi_detik($query->waktu_keterlambatan);
      }

      $query->status_absensi = '<span class="badge badge-warning text-white">Terlambat (' . $keteranganTerlambat . ')</span>';
    } elseif ($query->status_absensi == "Tepat Waktu") {
      $query->status_absensi = '<span class="badge badge-success">Tepat Waktu</span>';
    } elseif ($query->status_absensi == "Tidak Hadir") {
      $query->status_absensi = '<span class="badge badge-danger">Tidak Hadir</span>';
    } else {
      $query->status_absensi = '<span class="badge badge-warning text-white">' . $query->status_absensi . '</span>';
    }

    $waktu_datang = $query->waktu_datang ? date("H:i", strtotime($query->waktu_datang)) : null;
    $waktu_istirahat = $query->waktu_istirahat ? date("H:i", strtotime($query->waktu_istirahat)) : null;
    $waktu_kembali = $query->waktu_kembali ? date("H:i", strtotime($query->waktu_kembali)) : null;
    $waktu_pulang = $query->waktu_pulang ? date("H:i", strtotime($query->waktu_pulang)) : null;

    $total_jam = null;
    if ($waktu_datang && $waktu_istirahat && $waktu_kembali && $waktu_pulang) {
      $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
      $minutesKerja = $total_kerja->days * 24 * 60;
      $minutesKerja += $total_kerja->h * 60;
      $minutesKerja += $total_kerja->i;

      $total_istirahat = date_diff(date_create($waktu_istirahat), date_create($waktu_kembali));
      $minutesIstirahat = $total_istirahat->days * 24 * 60;
      $minutesIstirahat += $total_istirahat->h * 60;
      $minutesIstirahat += $total_istirahat->i;

      $total_jam = $this->convertToHoursMins($minutesKerja - $minutesIstirahat);
    } elseif ($waktu_datang && $waktu_pulang) {
      $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
      $minutesKerja = $total_kerja->days * 24 * 60;
      $minutesKerja += $total_kerja->h * 60;
      $minutesKerja += $total_kerja->i;

      $total_jam = $minutesKerja ? $this->convertToHoursMins($minutesKerja) : '0 Jam';
    }

    $data['user_id'] = $query->user_id;
    $data['nama'] = $query->nama_user;
    $data['tanggal'] = date("Y-m-d", strtotime($query->created_absensi));
    $data['datang'] = $query->waktu_datang ? date("H:i", strtotime($query->waktu_datang)) : '-';
    $data['istirahat'] = $query->waktu_istirahat ? date("H:i", strtotime($query->waktu_istirahat)) : '-';
    $data['kembali'] = $query->waktu_kembali ? date("H:i", strtotime($query->waktu_kembali)) : '-';
    $data['pulang'] = $query->waktu_pulang ? date("H:i", strtotime($query->waktu_pulang)) : '-';
    $data['total_jam'] = $total_jam;
    $data['status'] = $query->status_absensi;
    $data['strStatus'] = $status_absensi;
    $data['foto'] = $query->url_file_absensi;
    $data['lat'] = $query->lat;
    $data['lng'] = $query->lng;
    $data['lokasi'] = $query->nama_lokasi;
    $data['ssid'] = $query->ssid_jaringan;
    $data['mac'] = $query->mac_address_jaringan;
    $data['tagging'] = $query->tagging;
    $data['tagging'] = $query->tagging;
    $data['location_tagging'] = $query->location_tagging;
    $data['manual_absen'] = $query->manual_absen;

    $menu['main'] = 'kehadiran';
    $menu['child'] = 'kehadiran_absensi';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/detail_absensi');
    $this->load->view('Administrator/footer');
  }

  function convertToHoursMins($time, $format = '%02d:%02d')
  {
    if ($time < 1) {
      return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);

    return $hours . ' jam ' . $minutes . ' menit';
  }

  public function getDetail()
  {
    $id_absensi = $_GET['id'];

    $getAbsensi = $this->Db_select->select_where('tb_absensi', ['id_absensi' => $id_absensi]);

    if ($getAbsensi) {
      $getAbsensi->waktu_datang = $getAbsensi->waktu_datang ? date('H:i', strtotime($getAbsensi->waktu_datang)) : null;
      $getAbsensi->waktu_istirahat = $getAbsensi->waktu_istirahat ? date('H:i', strtotime($getAbsensi->waktu_istirahat)) : null;
      $getAbsensi->waktu_kembali = $getAbsensi->waktu_kembali ? date('H:i', strtotime($getAbsensi->waktu_istirahat)) : null;
      $getAbsensi->waktu_pulang = $getAbsensi->waktu_pulang ? date('H:i', strtotime($getAbsensi->waktu_istirahat)) : null;
      $result['status'] = true;
      $result['message'] = "success";
      $result['data'] = $getAbsensi;
    } else {
      $result['status'] = false;
      $result['message'] = "Failed";
    }

    echo json_encode($result);
  }

  public function updateDataAbsensi()
  {
    $getAbsensi = $this->Db_select->select_where('tb_absensi', ['id_absensi' => $this->input->post('id_absensi')]);

    if ($getAbsensi) {
      $date = date('Y-m-d', strtotime($getAbsensi->created_absensi));
      $update['waktu_datang'] = $this->input->post('waktu_datang') ? $date . " " . $this->input->post('waktu_datang') : null;
      $update['waktu_istirahat'] = $this->input->post('waktu_istirahat') ? $date . " " . $this->input->post('waktu_istirahat') : null;
      $update['waktu_kembali'] = $this->input->post('waktu_kembali') ? $date . " " . $this->input->post('waktu_kembali') : null;
      $update['waktu_pulang'] = $this->input->post('waktu_pulang') ? $date . " " . $this->input->post('waktu_pulang') : null;
      $update['status_absensi'] = $this->input->post('status_absensi');
      $update['tagging'] = $this->input->post('tagging');
      $update['location_tagging'] = $this->input->post('location_tagging');
      $where['id_absensi'] = $getAbsensi->id_absensi;

      if (!$update['waktu_pulang']) {
        $update['is_pulang'] = 0;
      }

      $update = $this->Db_dml->update('tb_absensi', $update, $where);

      if ($update == 0 || $update == 1) {
        $result['status'] = true;
        $result['message'] = "Data absensi berhasil diubah";
      } else {
        $result['status'] = false;
        $result['message'] = "Data absensi gagal diubah";
      }
    } else {
      $result['status'] = false;
      $result['message'] = "Data absensi tidak ditemukan";
    }

    echo json_encode($result);
  }

  public function absenManual()
  {
    $getData = $this->Db_select->query('select *from tb_absensi where user_id = ' . $this->input->post('user_id', true) . ' and date_format(created_absensi, "%Y-%m-%d") = "' . $this->input->post('created_absensi', true) . '"');

    if (!$getData) {
      $file = $this->global_lib->getPolaUser($this->input->post('user_id', true));

      $insert['user_id'] = $this->input->post('user_id', true);
      $insert['waktu_datang'] = $this->input->post('waktu_datang', true) ? $this->input->post('created_absensi', true) . " " . $this->input->post('waktu_datang', true) : null;
      $insert['waktu_istirahat'] = $this->input->post('waktu_istirahat', true) ? $this->input->post('created_absensi', true) . " " . $this->input->post('waktu_istirahat', true) : null;
      $insert['waktu_kembali'] = $this->input->post('waktu_kembali', true) ? $this->input->post('created_absensi', true) . " " . $this->input->post('waktu_kembali', true) : null;
      $insert['waktu_pulang'] = $this->input->post('waktu_pulang', true) ? $this->input->post('created_absensi', true) . " " . $this->input->post('waktu_pulang', true) : null;
      // $insert['status_absensi'] = $this->input->post('status_absensi', true);
      $insert['created_absensi'] = $this->input->post('created_absensi', true);
      $insert['tagging'] = $this->input->post('tagging', true);
      $insert['location_tagging'] = $this->input->post('location_tagging', true);
      $insert['note_absensi_manual'] = $this->input->post('note_absensi_manual', true) ? $this->input->post('note_absensi_manual', true) : null;
      $insert['manual_absen'] = 1;

      $cekDispensasi = $this->global_lib->getDispensasi($insert['user_id']);

      if ($cekDispensasi) {
        if (date('H:i', strtotime($insert['waktu_datang'])) > $cekDispensasi->jam_akhir_dispensasi) {
          $insert['status_absensi'] = 'Terlambat';
        } else {
          $insert['status_absensi'] = 'Tepat Waktu';
        }
      } else {
        $jadwal = json_decode(file_get_contents($file['file']))->jam_kerja;

        /* proses pemecahan jadwal kerja hari ini */
        $getStatusAbsensi = $this->global_lib->getStatusAbsensi($file['getPola'], $jadwal);
        $day = strtolower(date("l", strtotime(strtotime($insert['waktu_datang']))));
        $jadwalNew = date_create($jadwal->$day->from);
        $jam_skrg = date_create(date("H:i", strtotime($insert['waktu_datang'])));
        $diff  = date_diff($jam_skrg, $jadwalNew);

        /* set keterlambatan */
        $insert['status_absensi'] = $getStatusAbsensi;
        $keteranganTerlambat = 0;
        if ($getStatusAbsensi == "Terlambat") {
          if ($diff->h != 0) {
            $keteranganTerlambat += $diff->h * 3600;
          }
          if ($diff->i != 0) {
            $keteranganTerlambat += $diff->i * 60;
          }
        }
        $insert['waktu_keterlambatan'] = $keteranganTerlambat;
      }

      $absensi = $this->Db_dml->insert('tb_absensi', $insert);

      if ($absensi) {
        $result['status'] = true;
        $result['message'] = "Data absensi berhasil disimpan!";
      } else {
        $result['status'] = false;
        $result['message'] = "Data absensi gagal disimpan!";
      }
    } else {
      $result['status'] = false;
      $result['message'] = "Data absensi sudah tersedia untuk tanggal yang dituju !";
    }

    echo json_encode($result);
  }

  public function rekap()
  {
    $user = $this->session->userdata('user');

    if (empty($this->input->get(null, true))) {
      $menu['main'] = 'kehadiran';
      $menu['child'] = 'kehadiran_rekap';
      $data['menu'] = $menu;
      $data['data_jabatan'] = $this->Db_select->select_all_where(
        'tb_jabatan',
        [
          'id_channel' => $user['id_channel'],
          'is_deleted' => 0,
        ],
        'id_jabatan,nama_jabatan'
      );
      $data['data_unit'] = $this->Db_select->select_all_where(
        'tb_unit',
        [
          'id_channel' => $user['id_channel'],
          'is_deleted' => 0,
          'is_aktif' => 1,
        ],
        'id_unit,nama_unit'
      );
      $data['data_status_pegawai'] = $this->Db_select->select_all_where(
        'tb_status_user',
        [
          'id_channel' => $user['id_channel'],
          'is_deleted' => 0,
          'is_aktif' => 1,
        ],
        'id_status_user,nama_status_user'
      );
      $data['data_lokasi'] = $this->Db_select->select_all_where(
        'tb_lokasi',
        [
          'id_channel' => $user['id_channel'],
          'is_deleted' => 0,
        ],
        'id_lokasi,nama_lokasi'
      );
      $this->load->view('Administrator/header', $data);
      $this->load->view('Administrator/Absensi/rekap_absensi');
      $this->load->view('Administrator/footer');
    } else {
      $filters = $this->input->get(null, true);

      switch ($filters['type']) {
        case '1':
          // Harian
          $list_absensi_harian = $this->getRekapAbsensiHarian($user['id_channel'], $filters);
          $pola_kerja = $this->Db_select->select_where('tb_pola_kerja', [
            'id_channel' => $user['id_channel'],
            'is_default' => 1
          ], ['file_pola_kerja']);
          $file = 'appconfig/new/' . $pola_kerja->file_pola_kerja;
          $jadwal = json_decode(file_get_contents($file))->jam_kerja;
          $day = strtolower(date("l"));
          $jadwal_new = date_create($jadwal->$day->from);

          foreach ($list_absensi_harian as $key => $absensi_harian) {
            $date_now = date('Y-m-d', strtotime($absensi_harian->created_absensi));
            $data_absensi_lembur = $this->Db_select->query("
              SELECT id_absensi FROM tb_absensi WHERE date_format(created_absensi, '%Y-%m-%d') = '$date_now' AND user_id = ? AND is_attendance = ? AND status_absensi = ?
            ", [$absensi_harian->user_id, 0, 'Lembur']);

            $absensi_harian->overtime = null;
            if ($data_absensi_lembur) {
              $overtime = $this->Db_select->query("
                SELECT lama_lembur, durasi_lembur FROM tb_lembur WHERE user_id = '$absensi_harian->user_id' AND tanggal_lembur = '$date_now' AND status = 1
              ");
              if ($overtime) {
                $absensi_harian->overtime = $overtime->lama_lembur ? ($overtime->lama_lembur * 60) . " Menit" : ($overtime->durasi_lembur) . " Menit";
              }
            }

            if ($absensi_harian->status_absensi == "Terlambat") {
              $absensi_harian->keterlambatan = "";
              if ($absensi_harian->waktu_keterlambatan == 0) {
                $jam_skrg = date_create(date("H:i", strtotime($absensi_harian->waktu_datang)));
                $diff  = date_diff($jam_skrg, $jadwal_new);
                if ($diff->h != 0) {
                  $absensi_harian->keterlambatan .= $diff->h . " Jam ";
                }
                if ($diff->i != 0) {
                  $absensi_harian->keterlambatan .= $diff->i . " Menit ";
                }
              } else {
                $absensi_harian->keterlambatan = $this->global_lib->konversi_detik($absensi_harian->waktu_keterlambatan);
              }
            }

            if ($absensi_harian->status_absensi == "Lembur") {
              unset($list_absensi_harian[$key]);
            }
          }

          $data['list'] = $list_absensi_harian;
          $data['start_date'] = $filters['start_date'];
          $data['end_date'] = $filters['end_date'];

          $menu['main'] = 'kehadiran';
          $menu['child'] = 'kehadiran_rekap';
          $data['menu'] = $menu;

          $this->load->view('Administrator/header', $data);
          $this->load->view('Administrator/Absensi/rekap_absensi_harian');
          $this->load->view('Administrator/footer');
          break;
        case '2':
          // Akumulatif
          $filters = $this->input->get(null, true);
          $start_date = strtotime($filters['start_date']);
          $end_date = strtotime($filters['end_date']);
          $date_diff = round(($end_date - $start_date) / (60 * 60 * 24));

          $rekap_absensi_akumulatif = $this->getRekapAbsensiAkumulatif($user['id_channel'], $filters);

          $data['start_date'] = $filters['start_date'];
          $data['end_date'] = $filters['end_date'];
          $menu['main'] = 'kehadiran';
          $menu['child'] = 'kehadiran_rekap';
          $data['menu'] = $menu;
          $data['difDate'] = $date_diff;

          foreach ($rekap_absensi_akumulatif as $rekap) {
            $total_hari_tidak_masuk = $rekap->total_absen ? ($date_diff + 1) - $rekap->total_absen : ($date_diff + 1);
            $rata_rata_absensi = $rekap->total_absen / ($rekap->total_absen + $total_hari_tidak_masuk);
            $rata_rata_absensi = number_format($rata_rata_absensi, 2);
            $persentase_absensi = $rata_rata_absensi * 100;

            $rekap->rata_rata_absensi = $rata_rata_absensi;
            $rekap->persentase_absensi = $persentase_absensi;
          }

          $data['list'] = $rekap_absensi_akumulatif;
          $this->load->view('Administrator/header', $data);
          $this->load->view('Administrator/Absensi/rekap_absensi_akumulatif');
          $this->load->view('Administrator/footer');
          break;
        case '3':
          // Other
          $list_absensi_per_tanggal = [];
          $start_date = $this->input->get('start_date') . " 00:00:00";
          $end_date = $this->input->get('end_date') . " 23:59:59";
          $rekap_absensi_per_tanggal = $this->getRekapAbsensiPerTanggal($user['id_channel'], $start_date, $end_date);
          $rekap_absensi_hadir = $rekap_absensi_per_tanggal[0];
          $rekap_absensi_tidak_hadir = $rekap_absensi_per_tanggal[1];

          $unix_start_date = strtotime($start_date);
          $unix_end_date = strtotime($end_date);
          while ($unix_start_date <= $unix_end_date) {
            $total_hadir = 0;
            $total_tidak_hadir = 0;

            foreach ($rekap_absensi_hadir as $index => $absensi_hadir) {
              if ($absensi_hadir->created_absensi == date('Y-m-d', $unix_start_date)) {
                $total_hadir = $absensi_hadir->total_absen;
                unset($rekap_absensi_hadir[$index]);
                break;
              }
            }

            foreach ($rekap_absensi_tidak_hadir as $index => $absensi_tidak_hadir) {
              if ($absensi_tidak_hadir->created_absensi == date('Y-m-d', $unix_start_date)) {
                $total_tidak_hadir = $absensi_tidak_hadir->total_absen;
                unset($rekap_absensi_tidak_hadir[$index]);
                break;
              }
            }

            $list_absensi_per_tanggal[] =
              (object) [
                'hari' => date('l', $unix_start_date),
                'tanggal' => date('d-F-Y', $unix_start_date),
                'total_hadir' => $total_hadir,
                'total_tidak_hadir' => $total_tidak_hadir,
              ];
            $unix_start_date = strtotime(date('Y-m-d', $unix_start_date) . '+1 day');
          }

          $menu['main'] = 'kehadiran';
          $menu['child'] = 'kehadiran_rekap';
          $data['menu'] = $menu;
          $data['start_date'] = $this->input->get('start_date');
          $data['end_date'] = $this->input->get('end_date');
          $data['list_absensi_per_tanggal'] = $list_absensi_per_tanggal;
          $this->load->view('Administrator/header', $data);
          $this->load->view('Administrator/Absensi/rekap_absensi_per_tanggal');
          $this->load->view('Administrator/footer');
          break;
        default:
          redirect(base_url() . '/Administrator/data_absensi/rekap');
          break;
      }
    }
  }

  public function getRekapAbsensiHarian($id_channel, $filters)
  {
    $start_date = date('Y-m-d', strtotime($filters['start_date']));
    $end_date = date('Y-m-d', strtotime($filters['end_date'] . ' +1 day'));
    unset($filters['start_date']);
    unset($filters['end_date']);
    unset($filters['type']);
    $where_filter = count($filters) !== 0 ? " AND " : " ";

    $index = 1;
    foreach ($filters as $key => $value) {
      switch ($key) {
        case 'jabatan':
          $where_filter .= "tb_user.jabatan IN ($value)";
          break;
        case 'unit':
          $where_filter .= "tb_user.id_unit IN ($value)";
          break;
        case 'status_pegawai':
          $where_filter .= "tb_user.status_user IN ($value)";
          break;
        case 'kategori_absensi':
          $value = explode(',', $value);
          $tagging1 = isset($value[0]) ? strtoupper($value[0]) : '';
          $where_filter .= "tb_absensi.tagging IN ('$tagging1'";
          if (isset($value[1])) {
            $tagging2 = strtoupper($value[1]);
            $where_filter .= ",'$tagging2')";
          } else {
            $where_filter .= ")";
          }
          break;
        case 'lokasi':
          $where_filter .= "tb_history_absensi.id_lokasi IN ($value)";
          break;
        case 'status_absensi':
          $status_absensi = explode(',', $value);
          $counter = 1;
          $where_filter .= "tb_absensi.status_absensi IN (";
          foreach ($status_absensi as $status) {
            $status = ucwords(str_replace("_", " ", $status));
            $where_filter .= "'$status'";
            if ($counter < count($status_absensi)) $where_filter .= ",";
            $counter++;
          }
          $where_filter .= ")";
          break;
      }
      if ($index < count($filters)) $where_filter .= " AND ";
      $index++;
    }

    $getData = $this->Db_select->query_all("
      SELECT tb_absensi.id_absensi, tb_absensi.user_id, tb_absensi.day_off, tb_absensi.day_off_desc, tb_user.nip, tb_user.nama_user, tb_absensi.created_absensi, tb_absensi.waktu_datang, tb_absensi.waktu_istirahat, tb_absensi.waktu_kembali, tb_absensi.waktu_pulang, tb_absensi.url_file_absensi, tb_absensi.status_absensi, tb_absensi.tagging, tb_absensi.location_tagging, tb_history_absensi.lat, tb_history_absensi.lng, tb_history_absensi.address, tb_unit.nama_unit, tb_jabatan.nama_jabatan, tb_absensi.waktu_keterlambatan 
      FROM tb_absensi 
      JOIN tb_user ON tb_absensi.user_id = tb_user.user_id 
      JOIN tb_unit ON tb_user.id_unit = tb_unit.id_unit 
      LEFT JOIN tb_history_absensi ON tb_absensi.id_absensi = tb_history_absensi.id_absensi 
      JOIN tb_jabatan ON tb_user.jabatan = tb_jabatan.id_jabatan 
      WHERE tb_absensi.created_absensi >= '$start_date' AND 
      tb_absensi.created_absensi <= '$end_date' AND 
      tb_unit.id_channel = '$id_channel' AND
      tb_jabatan.id_channel = '$id_channel'
      $where_filter
      GROUP BY tb_absensi.id_absensi
    ");

    return $getData;
  }

  public function getRekapAbsensiHarianByUserId($user_id, $start_date, $end_date)
  {
    $getData = $this->Db_select->query_all("select b.nip, b.nama_user, a.created_absensi, a.waktu_datang, a.waktu_istirahat, a.waktu_kembali, a.waktu_pulang, a.url_file_absensi, a.status_absensi, a.tagging, a.location_tagging, d.lat, d.lng, d.address, c.nama_unit, e.nama_jabatan, a.waktu_keterlambatan from tb_absensi a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit left join tb_history_absensi d on a.id_absensi = d.id_absensi join tb_jabatan e on b.jabatan = e.id_jabatan where a.created_absensi >= '" . $start_date . "' and a.created_absensi <= '" . $end_date . "' and a.user_id = '" . $user_id . "'");

    return $getData;
  }

  public function getRekapAbsensiAkumulatif($id_channel, $filters)
  {
    $start_date = date('Y-m-d', strtotime($filters['start_date']));
    $end_date = date('Y-m-d', strtotime($filters['end_date'] . ' +1 day'));
    unset($filters['start_date']);
    unset($filters['end_date']);
    unset($filters['type']);

    $filters_sub_query = [];
    foreach ($filters as $key => $filter) {
      if ($key == "kategori_absensi" || $key == "lokasi") {
        $filters_sub_query[$key] = $filter;
        unset($filters[$key]);
      }
    }

    $where_filter = count($filters) !== 0 ? " AND " : " ";
    $counter = 1;
    foreach ($filters as $key => $value) {
      switch ($key) {
        case 'jabatan':
          $where_filter .= "tb_user.jabatan IN ($value)";
          break;
        case 'unit':
          $where_filter .= "tb_user.id_unit IN ($value)";
          break;
        case 'status_pegawai':
          $where_filter .= "tb_user.status_user IN ($value)";
          break;
      }
      if ($counter < count($filters)) $where_filter .= " AND ";
      $counter++;
    }

    $where_filter_sub_query = count($filters_sub_query) !== 0 ? " AND " : " ";
    $counter = 1;
    foreach ($filters_sub_query as $key => $value) {
      switch ($key) {
        case 'kategori_absensi':
          $value = explode(',', $value);
          $tagging1 = isset($value[0]) ? strtoupper($value[0]) : '';
          $where_filter_sub_query .= "tb_absensi.tagging IN ('$tagging1'";
          if (isset($value[1])) {
            $tagging2 = strtoupper($value[1]);
            $where_filter_sub_query .= ",'$tagging2')";
          } else {
            $where_filter_sub_query .= ")";
          }
          break;
        case 'lokasi':
          $where_filter_sub_query .= "tb_history_absensi.id_lokasi IN ($value)";
          break;
      }
      if ($counter < count($filters_sub_query)) {
        $where_filter_sub_query .= " AND ";
      }
      unset($filters_sub_query[$key]);
      $counter++;
    }
    $where_filter_sub_query .= " AND tb_absensi.status_absensi in ('Tepat Waktu', 'Terlambat')";

    $getData = $this->Db_select->query_all("
      SELECT tb_user.user_id, tb_user.nip AS nik, tb_user.nama_user, tb_jabatan.nama_jabatan, COALESCE(ta.total, 0) AS total_absen, ta.total_jam, tb_unit.nama_unit 
      FROM tb_user
      JOIN tb_jabatan ON tb_user.jabatan = tb_jabatan.id_jabatan
      JOIN tb_unit ON tb_user.id_unit = tb_unit.id_unit 
      LEFT JOIN (
          SELECT tb_absensi.user_id, count(tb_absensi.user_id) total, SUM(TIMESTAMPDIFF(SECOND, tb_absensi.waktu_datang, COALESCE(tb_absensi.waktu_pulang, tb_absensi.waktu_datang))
        ) total_jam 
        FROM tb_absensi 
        WHERE tb_absensi.created_absensi >= '$start_date' AND 
        tb_absensi.created_absensi <= '$end_date'
        $where_filter_sub_query
        GROUP BY tb_absensi.user_id
      ) AS ta ON tb_user.user_id = ta.user_id 
        WHERE tb_unit.id_channel = $id_channel
        $where_filter
        ORDER BY ta.total DESC
    ");

    return $getData;
  }

  private function getRekapAbsensiPerTanggal($id_channel, $start_date, $end_date)
  {
    $dataAbsensiHadir = $this->Db_select->query_all("
      SELECT COUNT(tb_absensi.user_id) AS total_absen, DATE_FORMAT(tb_absensi.created_absensi, '%Y-%m-%d') AS created_absensi
      FROM tb_absensi 
      JOIN tb_user ON tb_user.user_id = tb_absensi.user_id
      JOIN tb_unit ON tb_unit.id_unit = tb_user.id_unit
      WHERE tb_unit.id_channel = $id_channel AND
      tb_absensi.created_absensi >= '$start_date' AND 
      tb_absensi.created_absensi <= '$end_date' AND
      tb_absensi.status_absensi != 'Tidak Hadir'
      GROUP BY DATE_FORMAT(tb_absensi.created_absensi, '%Y-%m-%d') 
      ORDER BY tb_absensi.created_absensi ASC
    ");

    $dataAbsensiTidakHadir = $this->Db_select->query_all("
      SELECT COUNT(tb_absensi.user_id) AS total_absen, DATE_FORMAT(tb_absensi.created_absensi, '%Y-%m-%d') AS created_absensi
      FROM tb_absensi 
      JOIN tb_user ON tb_user.user_id = tb_absensi.user_id
      JOIN tb_unit ON tb_unit.id_unit = tb_user.id_unit
      WHERE tb_unit.id_channel = $id_channel AND
      tb_absensi.created_absensi >= '$start_date' AND 
      tb_absensi.created_absensi <= '$end_date' AND
      tb_absensi.status_absensi = 'Tidak Hadir'
      GROUP BY DATE_FORMAT(tb_absensi.created_absensi, '%Y-%m-%d') 
      ORDER BY tb_absensi.created_absensi ASC
    ");

    return [$dataAbsensiHadir, $dataAbsensiTidakHadir];
  }

  public function downloadAbsensiAkumulatif()
  {
    $user = $this->session->userdata('user');
    $filters = $this->input->get(null, true);
    $channel = $this->Db_select->select_where('tb_channel', ['id_channel' => $user['id_channel']]);
    $start_date = strtotime($filters['start_date']); // or your date as well
    $end_date = strtotime($filters['end_date']); // or your date as well
    $date_diff = round(($end_date - $start_date) / (60 * 60 * 24));

    $rekapAbsensiAkumulatif = $this->getRekapAbsensiAkumulatif($user['id_channel'], $filters);

    $this->load->library('Excel');
    $excel = new PHPExcel();

    $excel->getProperties()->setCreator('Pressensi')
      ->setLastModifiedBy('Folkatech')
      ->setTitle("Rekap Laporan Absensi Akumulatif")
      ->setSubject($channel->nama_channel)
      ->setDescription("Rekap Laporan Absensi Akumulatif");

    $excel->setActiveSheetIndex(0)
      ->setCellValue('A1', "NO")
      ->setCellValue('B1', "NIK")
      ->setCellValue('C1', "NAMA")
      ->setCellValue('D1', "JABATAN")
      ->setCellValue('E1', "UNIT KERJA")
      ->setCellValue('F1', "JUMLAH HADIR")
      ->setCellValue('G1', "RATA-RATA HADIR")
      ->setCellValue('H1', "PERSENTASE HADIR")
      ->setCellValue('I1', "JUMLAH TIDAK HADIR")
      ->setCellValue('J1', "TOTAL JAM");

    $i = 2;
    foreach ($rekapAbsensiAkumulatif as $key => $rekap) {
      $init = $rekap->total_jam ? $rekap->total_jam : 0;
      $hours = floor($init / 3600);
      $minutes = floor(($init / 60) % 60);
      $seconds = $init % 60;

      $total_hari_tidak_masuk = $rekap->total_absen ? ($date_diff + 1) - $rekap->total_absen : ($date_diff + 1);

      $rata_rata_absensi = $rekap->total_absen / ($rekap->total_absen + $total_hari_tidak_masuk);
      $rata_rata_absensi = number_format($rata_rata_absensi, 2);
      $persentase_absensi = $rata_rata_absensi * 100;

      $excel->setActiveSheetIndex(0)
        ->setCellValue('A' . $i, $key + 1)
        ->setCellValue('B' . $i, $rekap->nik)
        ->setCellValue('C' . $i, $rekap->nama_user)
        ->setCellValue('D' . $i, $rekap->nama_jabatan)
        ->setCellValue('E' . $i, $rekap->nama_unit)
        ->setCellValue('F' . $i, $rekap->total_absen ? $rekap->total_absen : '0')
        ->setCellValue('G' . $i, $rata_rata_absensi) // Rata-rata kehadiran
        ->setCellValue('H' . $i, "$persentase_absensi%") // Persentase kehadiran
        ->setCellValue('I' . $i, $total_hari_tidak_masuk)
        ->setCellValue('J' . $i, $hours . ':' . $minutes . ':' . $seconds);
      $i++;
    }

    $excel->getActiveSheet()->setTitle('Sheet1');
    $excel->setActiveSheetIndex(0);

    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Rekap Jumlah Absensi ' . $this->input->get('start_date') . ' - ' . $this->input->get('end_date') . '.xlsx"');
    header('Cache-Control: max-age=0');
    $write->save('php://output');
  }

  public function downloadAbsensiHarian()
  {
    $user = $this->session->userdata('user');
    $filters = $this->input->get(null, true);
    $getPola = $this->Db_select->select_where('tb_pola_kerja', [
      'id_channel' => $user['id_channel'],
      'is_default' => 1
    ]);
    $file = 'appconfig/new/' . $getPola->file_pola_kerja;
    $jadwal = json_decode(file_get_contents($file))->jam_kerja;
    $day = strtolower(date("l"));
    $jadwal_new = date_create($jadwal->$day->from);

    $channel = $this->Db_select->select_where('tb_channel', ['id_channel' => $user['id_channel']]);
    $list_absensi_harian = $this->getRekapAbsensiHarian($user['id_channel'], $filters);

    $this->load->library('Excel');
    $excel = new PHPExcel();
    $excel->getProperties()->setCreator('Pressensi')
      ->setLastModifiedBy('Folkatech')
      ->setTitle("Rekap Laporan Absensi Harian")
      ->setSubject($channel->nama_channel)
      ->setDescription("Rekap Laporan Absensi Harian");

    if ($user['id_channel'] == 18) {
      $excel->setActiveSheetIndex(0)
        ->setCellValue('A1', "NO")
        ->setCellValue('B1', "NIK")
        ->setCellValue('C1', "NAMA")
        ->setCellValue('D1', "JABATAN")
        ->setCellValue('E1', "UNIT KERJA")
        ->setCellValue('F1', "TANGGAL")
        ->setCellValue('G1', "JAM MASUK")
        ->setCellValue('H1', "JAM PULANG")
        ->setCellValue('I1', "JUMLAH JAM KERJA")
        ->setCellValue('J1', "OVERTIME")
        ->setCellValue('K1', "STATUS KEHADIRAN")
        ->setCellValue('L1', "WAKTU KETERLAMBATAN")
        ->setCellValue('M1', "TAGGING")
        ->setCellValue('N1', "Alamat Absensi")
        ->setCellValue('O1', "FOTO ABSENSI");

      $i = 2;
      foreach ($list_absensi_harian as $key => $absensi_harian) {
        $total_jam = null;
        $waktu_datang = $absensi_harian->waktu_datang;
        $waktu_istirahat = $absensi_harian->waktu_istirahat;
        $waktu_kembali = $absensi_harian->waktu_kembali;
        $waktu_pulang = $absensi_harian->waktu_pulang;

        if ($waktu_datang && $waktu_istirahat && $waktu_kembali && $waktu_pulang) {
          $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
          $minutesKerja = $total_kerja->days * 24 * 60;
          $minutesKerja += $total_kerja->h * 60;
          $minutesKerja += $total_kerja->i;

          $total_istirahat = date_diff(date_create($waktu_istirahat), date_create($waktu_kembali));
          $minutesIstirahat = $total_istirahat->days * 24 * 60;
          $minutesIstirahat += $total_istirahat->h * 60;
          $minutesIstirahat += $total_istirahat->i;

          $total_jam = $this->convertToHoursMins($minutesKerja - $minutesIstirahat);
        } elseif ($waktu_datang && $waktu_pulang) {
          $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
          $minutesKerja = $total_kerja->days * 24 * 60;
          $minutesKerja += $total_kerja->h * 60;
          $minutesKerja += $total_kerja->i;

          $total_jam = $minutesKerja ? $this->convertToHoursMins($minutesKerja) : '0 Jam';
        }

        if ($absensi_harian->url_file_absensi == "" || $absensi_harian->url_file_absensi == null) {
          $absensi_harian->url_file_absensi = "-";
        } else {
          $absensi_harian->url_file_absensi = base_url() . 'assets/images/absensi/' . $absensi_harian->url_file_absensi;
        }

        $statusAbsensi = $absensi_harian->status_absensi;
        if ($absensi_harian->day_off) {
          $statusAbsensi = $absensi_harian->status_absensi . " (" . $absensi_harian->day_off_desc . ")";
        }
        $keteranganTerlambat = "-";
        if ($absensi_harian->status_absensi == "Terlambat") {
          if ($absensi_harian->waktu_keterlambatan == 0) {
            $jam_skrg = date_create(date("H:i", strtotime($absensi_harian->waktu_datang)));
            $diff  = date_diff($jam_skrg, $jadwal_new);
            if ($diff->h != 0) {
              $keteranganTerlambat .= $diff->h . " Jam ";
            }
            if ($diff->i != 0) {
              $keteranganTerlambat .= $diff->i . " Menit ";
            }
          } else {
            $keteranganTerlambat = gmdate('H:i:s', $absensi_harian->waktu_keterlambatan);
          }
        }

        if ($absensi_harian->location_tagging) {
          $absensi_harian->location_tagging = "(" . $absensi_harian->location_tagging . ")";
        }

        $dateNow = date('Y-m-d', strtotime($absensi_harian->created_absensi));
        $getDataAbsenHariItu = $this->Db_select->query('select *from tb_absensi where date_format(created_absensi, "%Y-%m-%d") = "' . $dateNow . '" and user_id = "' . $absensi_harian->user_id . '" and is_attendance = 0 and status_absensi = "Lembur"');

        $absensi_harian->overtime = null;
        if ($getDataAbsenHariItu) {
          $overtime = $this->Db_select->query('select *from tb_lembur where user_id = "' . $absensi_harian->user_id . '" and tanggal_lembur = "' . $dateNow . '" and status = 1');
          if ($overtime) {
            $absensi_harian->overtime = $overtime->lama_lembur ? ($overtime->lama_lembur * 60) . " Menit" : ($overtime->durasi_lembur) . " Menit";
          }
        }

        if ($absensi_harian->status_absensi != "Lembur") {
          $excel->setActiveSheetIndex(0)
            ->setCellValue('A' . $i, $key + 1)
            ->setCellValue('B' . $i, $absensi_harian->nip)
            ->setCellValue('C' . $i, $absensi_harian->nama_user)
            ->setCellValue('D' . $i, $absensi_harian->nama_jabatan)
            ->setCellValue('E' . $i, $absensi_harian->nama_unit)
            ->setCellValue('F' . $i, date('Y-m-d', strtotime($absensi_harian->created_absensi)))
            ->setCellValue('G' . $i, $absensi_harian->waktu_datang ? date('H:i', strtotime($absensi_harian->waktu_datang)) : '-')
            ->setCellValue('H' . $i, $absensi_harian->waktu_pulang ? date('H:i', strtotime($absensi_harian->waktu_pulang)) : '-')
            ->setCellValue('I' . $i, $total_jam ? $total_jam : '-')
            ->setCellValue('J' . $i, $absensi_harian->overtime ? $absensi_harian->overtime : 0)
            ->setCellValue('K' . $i, $statusAbsensi)
            ->setCellValue('L' . $i, $keteranganTerlambat)
            ->setCellValue('M' . $i, $absensi_harian->status_absensi == 'Tidak Hadir' ? '-' : $absensi_harian->tagging . ' ' . $absensi_harian->location_tagging)
            ->setCellValue('N' . $i, $absensi_harian->address ? $absensi_harian->address : '-')
            ->setCellValue('O' . $i, $absensi_harian->url_file_absensi);
          $i++;
        }
      }
    } else {
      $excel->setActiveSheetIndex(0)
        ->setCellValue('A1', "NO")
        ->setCellValue('B1', "NIK")
        ->setCellValue('C1', "NAMA")
        ->setCellValue('D1', "JABATAN")
        ->setCellValue('E1', "UNIT KERJA")
        ->setCellValue('F1', "TANGGAL")
        ->setCellValue('G1', "JAM MASUK")
        ->setCellValue('H1', "JAM ISTIRAHAT")
        ->setCellValue('I1', "JAM KEMBALI")
        ->setCellValue('J1', "JAM PULANG")
        ->setCellValue('K1', "JUMLAH JAM KERJA")
        ->setCellValue('L1', "OVERTIME")
        ->setCellValue('M1', "STATUS KEHADIRAN")
        ->setCellValue('N1', "WAKTU KETERLAMBATAN")
        ->setCellValue('O1', "TAGGING")
        ->setCellValue('P1', "Alamat Absensi")
        ->setCellValue('Q1', "LAT")
        ->setCellValue('R1', "LNG")
        ->setCellValue('S1', "FOTO ABSENSI");

      $i = 2;
      foreach ($list_absensi_harian as $key => $absensi_harian) {
        $total_jam = null;
        $waktu_datang = $absensi_harian->waktu_datang;
        $waktu_istirahat = $absensi_harian->waktu_istirahat;
        $waktu_kembali = $absensi_harian->waktu_kembali;
        $waktu_pulang = $absensi_harian->waktu_pulang;

        if ($waktu_datang && $waktu_istirahat && $waktu_kembali && $waktu_pulang) {
          $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
          $minutesKerja = $total_kerja->days * 24 * 60;
          $minutesKerja += $total_kerja->h * 60;
          $minutesKerja += $total_kerja->i;

          $total_istirahat = date_diff(date_create($waktu_istirahat), date_create($waktu_kembali));
          $minutesIstirahat = $total_istirahat->days * 24 * 60;
          $minutesIstirahat += $total_istirahat->h * 60;
          $minutesIstirahat += $total_istirahat->i;

          $total_jam = $this->convertToHoursMins($minutesKerja - $minutesIstirahat);
        } elseif ($waktu_datang && $waktu_pulang) {
          $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
          $minutesKerja = $total_kerja->days * 24 * 60;
          $minutesKerja += $total_kerja->h * 60;
          $minutesKerja += $total_kerja->i;

          $total_jam = $minutesKerja ? $this->convertToHoursMins($minutesKerja) : '0 Jam';
        }


        if ($absensi_harian->url_file_absensi == "" || $absensi_harian->url_file_absensi == null) {
          $absensi_harian->url_file_absensi = "-";
        } else {
          $absensi_harian->url_file_absensi = base_url() . 'assets/images/absensi/' . $absensi_harian->url_file_absensi;
        }

        $keteranganTerlambat = "-";
        $statusAbsensi = $absensi_harian->status_absensi;
        if ($absensi_harian->day_off) {
          $statusAbsensi = $absensi_harian->status_absensi . " (" . $absensi_harian->day_off_desc . ")";
        }
        if ($absensi_harian->status_absensi == "Terlambat") {
          if ($absensi_harian->waktu_keterlambatan == 0) {
            $jam_skrg = date_create(date("H:i", strtotime($absensi_harian->waktu_datang)));
            $diff  = date_diff($jam_skrg, $jadwal_new);
            if ($diff->h != 0) {
              $keteranganTerlambat .= $diff->h . " Jam ";
            }
            if ($diff->i != 0) {
              $keteranganTerlambat .= $diff->i . " Menit ";
            }
          } else {
            $keteranganTerlambat = $this->global_lib->konversi_detik($absensi_harian->waktu_keterlambatan);
          }
        }

        if ($absensi_harian->location_tagging) {
          $absensi_harian->location_tagging = "(" . $absensi_harian->location_tagging . ")";
        }

        $dateNow = date('Y-m-d', strtotime($absensi_harian->created_absensi));
        $getDataAbsenHariItu = $this->Db_select->query('select *from tb_absensi where date_format(created_absensi, "%Y-%m-%d") = "' . $dateNow . '" and user_id = "' . $absensi_harian->user_id . '" and is_attendance = 0 and status_absensi = "Lembur"');

        $absensi_harian->overtime = null;
        if ($getDataAbsenHariItu) {
          $overtime = $this->Db_select->query('select *from tb_lembur where user_id = "' . $absensi_harian->user_id . '" and tanggal_lembur = "' . $dateNow . '" and status = 1');
          if ($overtime) {
            $absensi_harian->overtime = $overtime->lama_lembur ? ($overtime->lama_lembur * 60) . " Menit" : ($overtime->durasi_lembur) . " Menit";
          }
        }

        if ($absensi_harian->status_absensi != "Lembur") {
          $excel->setActiveSheetIndex(0)
            ->setCellValue('A' . $i, $key + 1)
            ->setCellValue('B' . $i, $absensi_harian->nip)
            ->setCellValue('C' . $i, $absensi_harian->nama_user)
            ->setCellValue('D' . $i, $absensi_harian->nama_jabatan)
            ->setCellValue('E' . $i, $absensi_harian->nama_unit)
            ->setCellValue('F' . $i, date('Y-m-d', strtotime($absensi_harian->created_absensi)))
            ->setCellValue('G' . $i, $absensi_harian->waktu_datang ? date('H:i', strtotime($absensi_harian->waktu_datang)) : '-')
            ->setCellValue('H' . $i, $absensi_harian->waktu_istirahat ? date('H:i', strtotime($absensi_harian->waktu_istirahat)) : '-')
            ->setCellValue('I' . $i, $absensi_harian->waktu_kembali ? date('H:i', strtotime($absensi_harian->waktu_kembali)) : '-')
            ->setCellValue('J' . $i, $absensi_harian->waktu_pulang ? date('H:i', strtotime($absensi_harian->waktu_pulang)) : '-')
            ->setCellValue('K' . $i, $total_jam ? $total_jam : '-')
            ->setCellValue('L' . $i, $absensi_harian->overtime ? $absensi_harian->overtime : 0)
            ->setCellValue('M' . $i, $statusAbsensi)
            ->setCellValue('N' . $i, $keteranganTerlambat)
            ->setCellValue('O' . $i, $absensi_harian->status_absensi == 'Tidak Hadir' ? '-' : $absensi_harian->tagging . ' ' . $absensi_harian->location_tagging)
            ->setCellValue('P' . $i, $absensi_harian->address ? $absensi_harian->address : '-')
            ->setCellValue('Q' . $i, $absensi_harian->lat ? $absensi_harian->lat : '-')
            ->setCellValue('R' . $i, $absensi_harian->lng ? $absensi_harian->lng : '-')
            ->setCellValue('S' . $i, $absensi_harian->url_file_absensi);
          $i++;
        }
      }
    }

    $excel->getActiveSheet()->setTitle('Sheet1');
    $excel->setActiveSheetIndex(0);
    foreach ($excel->getWorksheetIterator() as $worksheet) {
      $excel->setActiveSheetIndex($excel->getIndex($worksheet));

      $sheet = $excel->getActiveSheet();
      $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(true);
      /** @var PHPExcel_Cell $cell */
      foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
      }
    }

    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Rekap Absensi Harian ' . $this->input->get('start_date') . ' - ' . $this->input->get('end_date') . '.xlsx"');
    header('Cache-Control: max-age=0');
    $write->save('php://output');
  }

  public function downloadAbsensiPerTanggal()
  {
    $user = $this->session->userdata('user');
    $start_date = $this->input->get('start_date') . " 00:00:00";
    $end_date = $this->input->get('end_date') . " 23:59:59";
    $rekap_absensi_per_tanggal = $this->getRekapAbsensiPerTanggal($user['id_channel'], $start_date, $end_date);
    $channel = $this->Db_select->select_where('tb_channel', ['id_channel' => $user['id_channel']]);
    $rekap_absensi_hadir = $rekap_absensi_per_tanggal[0];
    $rekap_absensi_tidak_hadir = $rekap_absensi_per_tanggal[1];

    $this->load->library('Excel');
    $excel = new PHPExcel();

    $excel->getProperties()->setCreator('Pressensi')
      ->setLastModifiedBy('Folkatech')
      ->setTitle("Rekap Laporan Absensi Per Tanggal")
      ->setSubject($channel->nama_channel)
      ->setDescription("Rekap Laporan Absensi Per Tanggal");

    $excel->setActiveSheetIndex(0)
      ->setCellValue('A1', "NO")
      ->setCellValue('B1', "HARI")
      ->setCellValue('C1', "TANGGAL")
      ->setCellValue('D1', "TOTAL HADIR")
      ->setCellValue('E1', "TOTAL TIDAK HADIR");

    $numbering = 1;
    $row = 2;
    $unix_start_date = strtotime($start_date);
    $unix_end_date = strtotime($end_date);
    while ($unix_start_date <= $unix_end_date) {
      $total_hadir = 0;
      $total_tidak_hadir = 0;

      foreach ($rekap_absensi_hadir as $index => $absensi_hadir) {
        if ($absensi_hadir->created_absensi == date('Y-m-d', $unix_start_date)) {
          $total_hadir = $absensi_hadir->total_absen;
          unset($rekap_absensi_hadir[$index]);
          break;
        }
      }

      foreach ($rekap_absensi_tidak_hadir as $index => $absensi_tidak_hadir) {
        if ($absensi_tidak_hadir->created_absensi == date('Y-m-d', $unix_start_date)) {
          $total_tidak_hadir = $absensi_tidak_hadir->total_absen;
          unset($rekap_absensi_tidak_hadir[$index]);
          break;
        }
      }

      $excel->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, $numbering)
        ->setCellValue('B' . $row, date('l', $unix_start_date))
        ->setCellValue('C' . $row, date('d-F-Y', $unix_start_date))
        ->setCellValue('D' . $row, $total_hadir)
        ->setCellValue('E' . $row, $total_tidak_hadir);

      $row++;
      $numbering++;
      $unix_start_date = strtotime(date('Y-m-d', $unix_start_date) . '+1 day');
    }

    $excel->getActiveSheet()->setTitle('Sheet1');
    $excel->setActiveSheetIndex(0);

    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Rekap Jumlah Absensi ' . $this->input->get('start_date') . ' - ' . $this->input->get('end_date') . '.xlsx"');
    header('Cache-Control: max-age=0');
    $write->save('php://output');
  }

  public function rekap_detail($user_id)
  {
    $start_date = date('Y-m-d', strtotime($this->input->get('start_date')));
    $end_date = date('Y-m-d', strtotime($this->input->get('end_date') . ' +1 day'));

    $getData = $this->Db_select->query_all("select *from tb_absensi a left join tb_history_absensi b on a.id_absensi = b.id_absensi 
    where a.user_id = " . $user_id . " and date_format(a.created_absensi, '%Y-%m-%d') >= '" . $start_date . "' and date_format(a.created_absensi, '%Y-%m-%d') <= '" . $end_date . "' group by a.id_absensi");

    if ($getData) {
      $tmpPayload = [];
      foreach ($getData as $key => $value) {
        if ($value->url_file_absensi == "" || $value->url_file_absensi == null) {
          $value->url_file_absensi = base_url() . "assets/images/absensi/default_photo.jpg";
        } else {
          $value->url_file_absensi = base_url() . 'assets/images/absensi/' . $value->url_file_absensi;
        }

        $status_absensi = $value->status_absensi;
        if ($value->status_absensi == "Terlambat") {
          $value->status_absensi = '<span class="badge badge-warning text-white">Terlambat</span>';
        } elseif ($value->status_absensi == "Tepat Waktu") {
          $value->status_absensi = '<span class="badge badge-success">Tepat Waktu</span>';
        } else {
          $value->status_absensi = '<span class="badge badge-danger">Tidak Hadir</span>';
        }

        $waktu_datang = $value->waktu_datang ? date("H:i", strtotime($value->waktu_datang)) : null;
        $waktu_istirahat = $value->waktu_istirahat ? date("H:i", strtotime($value->waktu_istirahat)) : null;
        $waktu_kembali = $value->waktu_kembali ? date("H:i", strtotime($value->waktu_kembali)) : null;
        $waktu_pulang = $value->waktu_pulang ? date("H:i", strtotime($value->waktu_pulang)) : null;

        $total_jam = null;
        if ($waktu_datang && $waktu_istirahat && $waktu_kembali && $waktu_pulang) {
          $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
          $minutesKerja = $total_kerja->days * 24 * 60;
          $minutesKerja += $total_kerja->h * 60;
          $minutesKerja += $total_kerja->i;

          $total_istirahat = date_diff(date_create($waktu_istirahat), date_create($waktu_kembali));
          $minutesIstirahat = $total_istirahat->days * 24 * 60;
          $minutesIstirahat += $total_istirahat->h * 60;
          $minutesIstirahat += $total_istirahat->i;

          $total_jam = $this->convertToHoursMins($minutesKerja - $minutesIstirahat);
        } elseif ($waktu_datang && $waktu_pulang) {
          $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
          $minutesKerja = $total_kerja->days * 24 * 60;
          $minutesKerja += $total_kerja->h * 60;
          $minutesKerja += $total_kerja->i;

          $total_jam = $minutesKerja ? $this->convertToHoursMins($minutesKerja) : '0 Jam';
        }

        $data['user_id'] = $value->user_id;
        $data['nama'] = $value->nama_user;
        $data['tanggal'] = date("Y-m-d", strtotime($value->created_absensi));
        $data['datang'] = $value->waktu_datang ? date("H:i", strtotime($value->waktu_datang)) : '-';
        $data['istirahat'] = $value->waktu_istirahat ? date("H:i", strtotime($value->waktu_istirahat)) : '-';
        $data['kembali'] = $value->waktu_kembali ? date("H:i", strtotime($value->waktu_kembali)) : '-';
        $data['pulang'] = $value->waktu_pulang ? date("H:i", strtotime($value->waktu_pulang)) : '-';
        $data['total_jam'] = $total_jam;
        $data['status'] = $value->status_absensi;
        $data['strStatus'] = $status_absensi;
        $data['foto'] = $value->url_file_absensi;
        $data['lat'] = $value->lat;
        $data['lng'] = $value->lng;
        $data['lokasi'] = $value->nama_lokasi;
        $data['ssid'] = $value->ssid_jaringan;
        $data['mac'] = $value->mac_address_jaringan;
        $data['tagging'] = $value->tagging;
        $data['location_tagging'] = $value->location_tagging;

        array_push($tmpPayload, $data);
      }

      $payload['list'] = $tmpPayload;
      $payload['start_date'] = $this->input->get('start_date');
      $payload['end_date'] = $this->input->get('end_date');
      $payload['user_id'] = $user_id;
      $menu['main'] = 'kehadiran';
      $menu['child'] = 'kehadiran_absensi';
      $payload['menu'] = $menu;
      $this->load->view('Administrator/header', $payload);
      $this->load->view('Administrator/Absensi/rekap_absensi_detail');
      $this->load->view('Administrator/footer');
    } else {
      redirect('Administrator/data_absensi');
    }
  }

  public function export_rekap_detail($user_id)
  {
    $sess = $this->session->userdata('user');
    $start_date = $this->input->get('start_date');
    $end_date = $this->input->get('end_date');
    $id_channel = $sess['id_channel'];
    $getChannel = $this->Db_select->select_where('tb_channel', ['id_channel' => $id_channel]);
    $res = $this->getRekapAbsensiHarianByUserId($user_id, $start_date, $end_date);

    $this->load->library('Excel');
    $excel = new PHPExcel();

    $excel->getProperties()->setCreator('Pressensi')
      ->setLastModifiedBy('Folkatech')
      ->setTitle("Rekap Laporan Absensi Harian")
      ->setSubject($getChannel->nama_channel)
      ->setDescription("Rekap Laporan Absensi Harian");

    $excel->setActiveSheetIndex(0)
      ->setCellValue('A1', "NO")
      ->setCellValue('B1', "NIK")
      ->setCellValue('C1', "NAMA")
      ->setCellValue('D1', "JABATAN")
      ->setCellValue('E1', "UNIT KERJA")
      ->setCellValue('F1', "TANGGAL")
      ->setCellValue('G1', "JAM MASUK")
      ->setCellValue('H1', "JAM ISTIRAHAT")
      ->setCellValue('I1', "JAM KEMBALI")
      ->setCellValue('J1', "JAM PULANG")
      ->setCellValue('K1', "JUMLAH JAM KERJA")
      ->setCellValue('L1', "STATUS KEHADIRAN")
      ->setCellValue('M1', "TAGGING")
      ->setCellValue('N1', "Alamat Absensi")
      ->setCellValue('O1', "LAT")
      ->setCellValue('P1', "LNG")
      ->setCellValue('Q1', "FOTO ABSENSI");

    $i = 2;
    foreach ($res as $key => $value) {
      $total_jam = null;
      $waktu_datang = $value->waktu_datang;
      $waktu_istirahat = $value->waktu_istirahat;
      $waktu_kembali = $value->waktu_kembali;
      $waktu_pulang = $value->waktu_pulang;

      if ($waktu_datang && $waktu_istirahat && $waktu_kembali && $waktu_pulang) {
        $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
        $minutesKerja = $total_kerja->days * 24 * 60;
        $minutesKerja += $total_kerja->h * 60;
        $minutesKerja += $total_kerja->i;

        $total_istirahat = date_diff(date_create($waktu_istirahat), date_create($waktu_kembali));
        $minutesIstirahat = $total_istirahat->days * 24 * 60;
        $minutesIstirahat += $total_istirahat->h * 60;
        $minutesIstirahat += $total_istirahat->i;

        $total_jam = $this->convertToHoursMins($minutesKerja - $minutesIstirahat);
      } elseif ($waktu_datang && $waktu_pulang) {
        $total_kerja = date_diff(date_create($waktu_datang), date_create($waktu_pulang));
        $minutesKerja = $total_kerja->days * 24 * 60;
        $minutesKerja += $total_kerja->h * 60;
        $minutesKerja += $total_kerja->i;

        $total_jam = $minutesKerja ? $this->convertToHoursMins($minutesKerja) : '0 Jam';
      }


      if ($value->url_file_absensi == "" || $value->url_file_absensi == null) {
        $value->url_file_absensi = base_url() . "assets/images/absensi/default_photo.jpg";
      } else {
        $value->url_file_absensi = base_url() . 'assets/images/absensi/' . $value->url_file_absensi;
      }

      $excel->setActiveSheetIndex(0)
        ->setCellValue('A' . $i, $key + 1)
        ->setCellValue('B' . $i, $value->nip)
        ->setCellValue('C' . $i, $value->nama_user)
        ->setCellValue('D' . $i, $value->nama_jabatan)
        ->setCellValue('E' . $i, $value->nama_unit)
        ->setCellValue('F' . $i, date('Y-m-d', strtotime($value->created_absensi)))
        ->setCellValue('G' . $i, $value->waktu_datang ? date('H:i', strtotime($value->waktu_datang)) : '-')
        ->setCellValue('H' . $i, $value->waktu_istirahat ? date('H:i', strtotime($value->waktu_istirahat)) : '-')
        ->setCellValue('I' . $i, $value->waktu_kembali ? date('H:i', strtotime($value->waktu_kembali)) : '-')
        ->setCellValue('J' . $i, $value->waktu_pulang ? date('H:i', strtotime($value->waktu_pulang)) : '-')
        ->setCellValue('K' . $i, $total_jam ? $total_jam : '-')
        ->setCellValue('L' . $i, $value->status_absensi)
        ->setCellValue('M' . $i, $value->status_absensi == 'Tidak Hadir' ? '-' : $value->tagging . ' (' . $value->location_tagging . ')')
        ->setCellValue('N' . $i, $value->address ? $value->address : '-')
        ->setCellValue('O' . $i, $value->lat ? $value->lat : '-')
        ->setCellValue('P' . $i, $value->lng ? $value->lng : '-')
        ->setCellValue('Q' . $i, $value->url_file_absensi);
      $i++;
    }

    $excel->getActiveSheet()->setTitle('Sheet1');
    $excel->setActiveSheetIndex(0);

    $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Rekap Absensi Harian ' . $start_date . ' - ' . $end_date . '.xlsx"');
    header('Cache-Control: max-age=0');
    $write->save('php://output');
  }
}
