<?php defined('BASEPATH') or exit('No direct script access allowed');

class History_pengunduran_diri extends CI_Controller
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

    $getUser = $this->Db_select->query_all("SELECT user_id, nama_user FROM tb_user a JOIN tb_unit b ON a.id_unit = b.id_unit WHERE id_channel = ? AND a.is_aktif = ? AND a.is_deleted = ?", [$id_channel, 1, 0]);
    $data['option'] = '';
    foreach ($getUser as $value) {
      $getData = $this->Db_select->select_where('tb_history_pengunduran_diri', ['user_id' => $value->user_id], ['id_pengunduran_diri']);
      if (!$getData) {
        $data['option'] .= '<option value="' . $value->user_id . '">' . $value->nama_user . '</option>';
      }
    }

    $data['departemen'] = $this->Db_select->query_all("SELECT id_unit, nama_unit FROM tb_unit WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ?", [$id_channel, 1, 0]);
    $data['jabatan'] = $this->Db_select->query_all("SELECT * FROM tb_jabatan WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ? ORDER BY nama_jabatan ASC", [$id_channel, 1, 0]);
    $data['tipe'] = $this->Db_select->query_all("SELECT * FROM tb_status_user WHERE id_channel = ? AND is_aktif = ? AND is_deleted = ?", [$id_channel, 1, 0]);
    $menu['main'] = 'personalia';
    $menu['child'] = 'personalia_pengunduran';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/pengunduran_diri');
    $this->load->view('Administrator/footer');
  }

  public function insert()
  {
    $userId = $this->input->post('user_id', true);
    $tglPengunduranDiri = $this->input->post('tanggal_pengunduran_diri', true);
    $dataHistoryPengunduranDiri = [
      'user_id' => $userId,
      'tanggal_pengunduran_diri' => $tglPengunduranDiri,
      'alasan_pengunduran' => $this->input->post('alasan_pengunduran', true),
      'is_cron' => 1
    ];

    $historyPengunduranDiriId = $this->Db_dml->insert('tb_history_pengunduran_diri', $dataHistoryPengunduranDiri);
    if ($historyPengunduranDiriId) {
      /* update data user */
      $isUpdated = $this->Db_dml->update('tb_user', ['is_aktif' => 0], ['user_id' => $userId]);
      if (!$isUpdated) {
        $result['status'] = false;
        $result['message'] = 'Gagal menonaktifkan status user.';
        $result['data'] = array();
        echo json_encode($result);
        exit();
      }

      $result['status'] = true;
      $result['message'] = 'Data berhasil disimpan.';
      $result['data'] = array();
    } else {
      $result['status'] = false;
      $result['message'] = 'Data histori pengunduran gagal disimpan.';
      $result['data'] = array();
    }

    echo json_encode($result);
    exit();
  }

  public function rollback()
  {
    $user_id = $this->input->post('user_id');

    /* update data tb_user */
    $updareData["is_aktif"] = 1;
    $whereData["user_id"] = $user_id;
    $update = $this->Db_dml->update('tb_user', $updareData, $whereData);
    if ($update) {
      /* delete data history pengunduran diri */
      /* cek dulu data user nya */
      $cekData = $this->Db_select->select_where('tb_history_pengunduran_diri', 'user_id = "' . $user_id . '"');
      if ($cekData) {
        $this->Db_dml->delete('tb_history_pengunduran_diri', 'user_id = "' . $user_id . '"');
      }

      $result['status'] = true;
      $result['message'] = "Data berhasil dikembalikan";
    } else {
      $result['status'] = false;
      $result['message'] = "Data gagal dikembalikan";
    }

    echo json_encode($result);
  }

  public function getData()
  {
    $sess = $this->session->userdata('user');
    $columns = array(
      0 =>  'a.user_id',
      1 =>  'a.nip',
      2 =>  'a.nama_user',
      3 =>  'b.tanggal_pengunduran_diri',
      4 =>  'b.alasan_pengunduran',
      5 =>  'aksi'
    );

    $query = " ";
    if ($this->input->get('departemen', true)) {
      $filterDepartemen = $this->input->get('departemen', true);
      $filterDepartemen = explode(',', $filterDepartemen);
      $departemen = "";
      foreach ($filterDepartemen as $key => $value) {
        $departemen .= ",'$value'";
      }
      $departemen = substr($departemen, 1);
      $query .= " and a.id_unit in(" . $departemen . ")";
    }
    if ($this->input->get('jabatan', true)) {
      $filterJabatan = $this->input->get('jabatan', true);
      $filterJabatan = explode(',', $filterJabatan);
      $jabatan = "";
      foreach ($filterJabatan as $key => $value) {
        $jabatan .= ",'$value'";
      }
      $jabatan = substr($jabatan, 1);
      $query .= " and a.jabatan in(" . $jabatan . ")";
    }
    if ($this->input->get('jenkel', true)) {
      $filterJenkel = $this->input->get('jenkel', true);
      $filterJenkel = explode(',', $filterJenkel);
      $jenkel = "";
      foreach ($filterJenkel as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " and a.jenis_kelamin in(" . $jenkel . ")";
    }
    if ($this->input->get('tipe', true)) {
      $filterType = $this->input->get('tipe', true);
      $filterType = explode(',', $filterType);
      $tipe = "";
      foreach ($filterType as $key => $value) {
        $tipe .= ",'$value'";
      }
      $tipe = substr($tipe, 1);
      $query .= " and status_user in(" . $tipe . ")";
    }

    $limit  = $this->input->post('length');
    $start  = $this->input->post('start');
    $order  = $columns[$this->input->post('order')[0]['column']];
    $dir    = $this->input->post('order')[0]['dir'];
    $search = $this->input->post('search')['value'];
    $totalData = $this->Db_global->allposts_count_all("select a.nama_user, b.tanggal_pengunduran_diri, b.alasan_pengunduran from tb_user a join tb_unit c on a.id_unit = c.id_unit left join tb_history_pengunduran_diri b on a.user_id = b.user_id where a.is_aktif = 0 and c.id_channel = " . $sess['id_channel'] . " " . $query . " order by " . $order . " " . $dir);
    $totalFiltered = $totalData;

    if (empty($this->input->post('search')['value'])) {
      $posts = $this->Db_global->allposts_all("select a.user_id, a.nip, a.nama_user, b.tanggal_pengunduran_diri, b.alasan_pengunduran from tb_user a join tb_unit c on a.id_unit = c.id_unit left join tb_history_pengunduran_diri b on a.user_id = b.user_id where a.is_aktif = 0 and c.id_channel = " . $sess['id_channel'] . " " . $query . " order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
    } else {
      $search = $this->input->post('search')['value'];
      $posts = $this->Db_global->posts_search_all("select a.user_id, a.nip, a.nama_user, b.tanggal_pengunduran_diri, b.alasan_pengunduran from tb_user a join tb_unit c on a.id_unit = c.id_unit left join tb_history_pengunduran_diri b on a.user_id = b.user_id where a.is_aktif = 0 and c.id_channel = " . $sess['id_channel'] . " " . $query . " and (a.nip like '%" . $search . "%' or a.nama_user like '%" . $search . "%' or b.alasan_pengunduran like '%" . $search . "%') order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");

      $totalFiltered = $this->Db_global->posts_search_count_all("select a.user_id, a.nip, a.nama_user, b.tanggal_pengunduran_diri, b.alasan_pengunduran from tb_user a join tb_unit c on a.id_unit = c.id_unit left join tb_history_pengunduran_diri b on a.user_id = b.user_id where a.is_aktif = 0 and c.id_channel = " . $sess['id_channel'] . " " . $query . " and (a.nip like '%" . $search . "%' or a.nama_user like '%" . $search . "%' or b.alasan_pengunduran like '%" . $search . "%')");
    }

    $data = array();
    if (!empty($posts)) {
      foreach ($posts as $key => $post) {
        if ($post->tanggal_pengunduran_diri == null || $post->tanggal_pengunduran_diri == "") {
          $post->tanggal_pengunduran_diri = "-";
        }

        if ($post->alasan_pengunduran == null || $post->alasan_pengunduran == "") {
          $post->alasan_pengunduran = "-";
        }

        $nestedData['no']  = $key + 1;
        $nestedData['nip']  = $post->nip;
        $nestedData['nama']  = $post->nama_user;
        $nestedData['tanggal']  = $post->tanggal_pengunduran_diri;
        $nestedData['alasan']  = $post->alasan_pengunduran;
        $nestedData['aksi']  = '<a href="#" class="btn btn-success" onclick="hapus(' . $post->user_id . ')">Cancel</a>';
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
}
