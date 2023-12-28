<?php defined('BASEPATH') or exit('No direct script access allowed');

class lembur extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->library(array('ceksession', 'global_lib'));
    $this->load->model('Db_datatable');

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
    $data['departemen'] = $this->Db_select->query_all('SELECT id_unit, nama_unit FROM tb_unit WHERE id_channel = ? AND is_deleted = ? ORDER BY nama_unit ASC', [$id_channel, 0]);
    $data['jabatan'] = $this->Db_select->query_all('SELECT id_jabatan, nama_jabatan FROM tb_jabatan WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ? ORDER BY nama_jabatan ASC', [$id_channel, 1, 0]);
    $data['tipe'] = $this->Db_select->query_all('SELECT id_status_user, nama_status_user FROM tb_status_user WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ?', [$id_channel, 1, 0]);

    $menu['main'] = 'pengajuan';
    $menu['child'] = 'pengajuan_lembur';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/lembur');
    $this->load->view('Administrator/footer');
  }

  public function select()
  {

    $where['id_lembur'] = $this->input->post('id', true);
    $data = $this->Db_select->query('SELECT * FROM tb_user a JOIN tb_lembur b ON a.user_id = b.user_id WHERE b.id_lembur = ?', [$where['id_lembur']]);
    if ($data) {
      if ($data->userfile) {
        $data->userfile = base_url('assets/images/lembur/' . $data->userfile);
      }
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

  public function update()
  {
    $where['id_lembur'] = $this->input->post('id_lembur', true);
    $data['status'] = $this->input->post('status', true);

    $updateData = $this->Db_dml->update('tb_lembur', $data, $where);
    if ($updateData == 1) {
      /* get data user */
      $getUser = $this->Db_select->query('SELECT b.user_id FROM tb_lembur a JOIN tb_user b ON a.user_id = b.user_id WHERE a.id_lembur = ?', [$this->input->post('id_lembur', true)]);

      // SEND NOTIFIKASI MELALUI FCM
      if ($data['status'] == 1) {
        $dataLembur = $this->Db_select->select_where('tb_lembur', $where, ['tanggal_lembur']);
        /* absensi */
        $insertAbsensi['user_id'] = $getUser->user_id;
        $insertAbsensi['status_absensi'] = "Lembur";
        $insertAbsensi['id_status_pengajuan'] = 80;
        $insertAbsensi['is_attendance'] = 0;
        $insertAbsensi['created_absensi'] = date("Y-m-d", strtotime($dataLembur->tanggal_lembur));
        $this->Db_dml->insert('tb_absensi', $insertAbsensi);
        $message = "Pengajuan Lembur Anda Telah Disetujui";
      } else {
        $message = "Pengajuan Lembur Anda Ditolak";
      }
      $this->global_lib->send_notification_user($getUser->user_id, 'lainnya', $message, $this->input->post('id_lembur'));
      $this->global_lib->NEWsendFCM('Approval Pengajuan Lembur', $message, $getUser->user_id, '', 'pengajuan', 'lembur');

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

  function get_data_user($value = null)
  {
    $sess = $this->session->userdata('user');
    if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
      redirect(base_url());
      exit();
    }

    $columns = array(
      0 =>  'no',
      1 =>  'b.nip',
      2 =>  'b.nama_user',
      3 =>  'a.tanggal_lembur',
      4 =>  'a.jam_mulai',
      5 =>  'a.lama_lembur',
      6 =>  'a.status',
      7 =>  'aksi'
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

    if ($this->input->get('dari', true)) {
      $filter['dari'] = $this->input->get('dari', true);
      $query .= " AND date(tanggal_lembur)  >= '" . $this->input->get('dari', true) . "'";
    }

    if ($this->input->get('sampai', true)) {
      $filter['sampai'] = $this->input->get('sampai', true);
      $query .= " AND date(tanggal_lembur) <= '" . $this->input->get('sampai', true) . "' ";
    }

    $where = 'c.id_channel = "' . $sess['id_channel'] . '" AND b.is_aktif = 1' . $query;

    $limit  = $this->input->post('length', true);
    $start  = $this->input->post('start', true);
    $order  = $columns[$this->input->post('order', true)[0]['column']];
    $dir    = $this->input->post('order', true)[0]['dir'];
    $totalData = $this->Db_global->allposts_count_all("select * from tb_lembur a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where " . $where);
    $totalFiltered = $totalData;
    if (empty($this->input->post('search', true)['value'])) {
      $posts = $this->Db_global->allposts_all("select * from tb_lembur a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where " . $where . " order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
    } else {
      $search = $this->input->post('search', true)['value'];
      $posts = $this->Db_global->allposts_all("select * from tb_lembur a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where " . $where . " and (b.nama_user like '%" . $search . "%' or a.tanggal_lembur like '%" . $search . "%' or a.jam_mulai like '%" . $search . "% or a.lama_lembur like '%" . $search . "% or a.status like '%" . $search . "%') order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
      $totalFiltered = $this->Db_global->posts_search_count_all("select * from tb_lembur a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where " . $where . " and (b.nama_user like '%" . $search . "%' or a.tanggal_lembur like '%" . $search . "%' or a.jam_mulai like '%" . $search . "% or a.lama_lembur like '%" . $search . "% or a.status like '%" . $search . "%')");
    }

    $data = array();
    if (!empty($posts)) {
      foreach ($posts as $key => $post) {
        if ($post->status == 0) {
          $post->status = "<p class='text-warning'>PENDING</p>";
        } elseif ($post->status == 1) {
          $post->status = "<p class='text-success'>ACC</p>";
        } else {
          $post->status = "<p class='text-danger'>DITOLAK</p>";
        }


        $nestedData['no'] = $key + 1;
        $nestedData['nik'] = $post->nip;
        $nestedData['nama_user'] = $post->nama_user;
        $nestedData['tanggal_lembur'] = date('d F Y', strtotime($post->tanggal_lembur));
        $nestedData['jam_mulai'] = date('H:i', strtotime($post->jam_mulai));
        $nestedData['lama_lembur'] = $post->lama_lembur ? $post->lama_lembur . " Jam" : $post->durasi_lembur . " Menit";
        $nestedData['status'] = $post->status;
        $nestedData['aksi'] = '<a href="#" onclick="selectDetail(' . $post->id_lembur . ')" data-toggle="modal" data-target="#updateModal" class="btn btn-info btn-sm"><span class="fa fa-pencil-alt"></span></a>';

        $data[] = $nestedData;
      }
    }
    $json_data = array(
      "draw" => intval($this->input->post('draw', true)),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data" => $data
    );

    echo json_encode($json_data);
    exit();
  }

  public function payrollLembur($idLembur)
  {
    if (is_numeric($idLembur)) {
      /* get data lembur */
      $getLembur = $this->Db_select->query('select a.id_lembur, a.lama_lembur, a.tanggal_lembur, a.user_id, c.id_channel from tb_lembur a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where a.id_lembur = "' . $idLembur . '"');

      if ($getLembur) {
        /* check tanggal libur atau bukan */
        $tanggalLembur = mdate("%Y-%m-%d", strtotime($getLembur->tanggal_lembur));

        /* get hari kerja */
        $jadwal = $this->getPolaKerja($getLembur->user_id, $tanggalLembur, $getLembur->id_channel)['pola'];

        /* get data pengaturan lembur */
        $lemburSetting = $this->Db_select->select_where('tb_komponen_lembur', 'id_channel = "' . $getLembur->id_channel . '"');

        if ($lemburSetting) {
          if ($lemburSetting->is_custom) {
            /* ambil perhitungan lembur custom */
            $upahLembur = $this->perhitunganCustom($getLembur->lama_lembur, $getLembur->user_id, $lemburSetting->nominal);
          } else {
            /* check tanggal merah */
            if ($this->tanggalMerah($tanggalLembur, $getLembur->id_channel, $getLembur->user_id)) {
              /* ambil perhitungan lembur di hari libur */
              $upahLembur = $this->perhitunganLibur($jadwal->lama_hari_libur, $getLembur->lama_lembur, $getLembur->user_id);
            } else {
              /* ambil perhitungan lembur di hari kerja */
              $upahLembur = $this->perhitungan($getLembur->lama_lembur, $getLembur->user_id);
            }
          }

          return $upahLembur;
        }
      }
    }
  }

  public function perhitunganCustom($lama_lembur, $user_id, $nominal)
  {
    $totalJumlah = $lama_lembur * $nominal;

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
      for ($i = 0; $i < $lamaLembur; $i++) {
        if ($countLama <= 8) {
          /* 2x */
          $hitung = floor(2 * $upahPerJam);
          $totalJumlah = $totalJumlah + $hitung;
        } elseif ($countLama == 9) {
          /* 3x */
          $hitung = floor(3 * $upahPerJam);
          $totalJumlah = $totalJumlah + $hitung;
        } elseif ($countLama >= 10) {
          /* 4x */
          $hitung = floor(3 * $upahPerJam);
          $totalJumlah = $totalJumlah + $hitung;
        }
        $countLama++;
      }
    } elseif ($hariKerja > 5) {
      /* Untuk perusahaan dengan 6 hari kerja, rate adalah 2x upah sejam untuk 7 jam pertama, 3x upah sejam untuk jam ke-8, dan 4x upah sejam untuk jam ke-9 dan ke-10 */
      $countLama = 1;
      for ($i = 0; $i < $lamaLembur; $i++) {
        if ($countLama <= 7) {
          /* 2x */
          $hitung = floor(2 * $upahPerJam);
          $totalJumlah = $totalJumlah + $hitung;
        } elseif ($countLama == 8) {
          /* 3x */
          $hitung = floor(3 * $upahPerJam);
          $totalJumlah = $totalJumlah + $hitung;
        } elseif ($countLama >= 9) {
          /* 4x */
          $hitung = floor(4 * $upahPerJam);
          $totalJumlah = $totalJumlah + $hitung;
        }
        $countLama++;
      }
    }

    return $totalJumlah;
  }

  public function perhitungan($lamaLembur, $userId)
  {
    /* hitung upah lembur perjam */
    $upahPerJam = $this->hitungUpah($userId);
    $totalJumlah = 0;
    /* start perhitungan lembur di hari kerja */
    $countLama = 1;
    for ($i = 0; $i < $lamaLembur; $i++) {
      /* Untuk lembur pada hari kerja, rate upah lembur adalah 1,5x upah sejam pada jam pertama lembur dan 2x upah sejam pada jam seterusnya */
      if ($countLama == 1) {
        /* 1.5x */
        $hitung = floor(1.5 * $upahPerJam);
        $totalJumlah = $totalJumlah + $hitung;
      } elseif ($countLama >= 2) {
        /* 2x */
        $hitung = floor(2 * $upahPerJam);
        $totalJumlah = $totalJumlah + $hitung;
      }
      $countLama++;
    }

    return $totalJumlah;
  }

  public function hitungUpah($userId)
  {
    $getUser = $this->Db_select->select_where('tb_user', 'user_id = "' . $userId . '"');

    if ($getUser) {
      /* hitung upah sejam gapok x 1/173 */
      $upahLembur = floor($getUser->gaji_pokok * 1 / 173);

      return $upahLembur;
    } else {
      return 0;
    }
  }

  public function tanggalMerah($tanggal, $idChannel, $userId)
  {
    $cek_libur = $this->Db_select->query('select *from tb_event where date_format(tanggal_event, "%Y-%m-%d") = "' . $tanggal . '" and id_channel = "' . $idChannel . '"');
    if ($cek_libur) {
      $isLibur = 1;
    } else {
      $isLibur = 0;
    }

    $jadwal = $this->getPolaKerja($userId, $tanggal, $idChannel)['jadwal'];
    $day = strtolower(date("l", strtotime($tanggal)));
    $jadwal = $jadwal->$day;

    if ($jadwal->libur) {
      $isLibur = 1;
    } else {
      $isLibur = 0;
    }

    if ($isLibur) {
      return true;
    } else {
      return false;
    }
  }

  public function getPolaKerja($userId, $tanggal, $idChannel)
  {
    $cekPolaUser = $this->Db_select->query('select *from tb_pola_user where user_id = "' . $userId . '" and "' . $tanggal . '" between start_pola_kerja and end_pola_kerja');

    if ($cekPolaUser) {
      $getPola = $this->Db_select->select_where('tb_pola_kerja', 'id_pola_kerja = "' . $cekPolaUser->id_pola_kerja . '"');
      $file = 'appconfig/new/' . $getPola->file_pola_kerja;
    } else {
      $where['id_channel'] = $idChannel;
      $where['is_default'] = 1;
      $getPola = $this->Db_select->select_where('tb_pola_kerja', $where);
      $file = 'appconfig/new/' . $getPola->file_pola_kerja;
    }

    if (!file_exists($file)) {
      $file = 'appconfig/new/jadwal_default_1.txt';
    }

    $jadwal['jadwal'] = json_decode(file_get_contents($file))->jam_kerja;
    $jadwal['pola'] = $getPola;

    return $jadwal;
  }
}
