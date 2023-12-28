<?php

class status_pengajuan extends CI_Controller
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
    $user = $this->session->userdata('user');

    if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama") {
      redirect(base_url());
      exit();
    }
    $data['status_pengajuan'] = $this->Db_select->select_all_where('tb_status_pengajuan', ['is_deleted' => 0, 'id_channel' => $user['id_channel']]);

    /* cek apakah sudah ada status atau belum untuk masing-masing channel */
    $cek = $this->Db_select->select_all_where('tb_status_pengajuan', ['id_channel' => $user['id_channel']], ['id_status_pengajuan']);

    if (!$cek) {
      if ($user['id_channel'] != 1) {
        /* buatkan status default */
        $nama_status_pengajuan = array('Cuti', 'Izin', 'Sakit');
        for ($i = 0; $i < count($nama_status_pengajuan); $i++) {
          $input['nama_status_pengajuan'] = $nama_status_pengajuan[$i];
          $input['id_channel'] = $user['id_channel'];
          $input['is_default'] = 1;
          $this->Db_dml->insert('tb_status_pengajuan', $input);
        }
      }
    }
    $menu['main'] = 'pengaturan';
    $menu['child'] = 'pengaturan_status_pengajuan';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/status_pengajuan');
    $this->load->view('Administrator/footer');
  }

  public function insert()
  {
    $user = $this->session->userdata('user');
    $data['nama_status_pengajuan'] = $this->input->post('nama_status_pengajuan', true);
    $data['id_channel'] = $user['id_channel'];
    $data['is_default'] = 0;

    $insert = $this->Db_dml->insert('tb_status_pengajuan', $data);

    if ($insert) {
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

  public function delete()
  {
    $where['id_status_pengajuan'] = $this->input->post('id_status_pengajuan', true);

    $delete = $this->Db_dml->update('tb_status_pengajuan', ['is_deleted' => 1], $where);

    if ($delete) {
      $result['status'] = true;
      $result['message'] = 'Data berhasil dihapus.';
      $result['data'] = array();
    } else {
      $result['status'] = true;
      $result['message'] = 'Data gagal dihapus.';
      $result['data'] = array();
    }
    echo json_encode($result);
  }

  public function update()
  {
    $where['id_status_pengajuan'] = $this->input->post('id_status_pengajuan', true);
    $data['nama_status_pengajuan'] = $this->input->post('nama_status_pengajuan', true);

    $update = $this->Db_dml->update('tb_status_pengajuan', $data, $where);

    if ($update) {
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

  public function getData()
  {
    $user = $this->session->userdata('user');
    $columns = array(
      0 =>  'no',
      1 =>  'nama_status_pengajuan',
      2 =>  'is_aktif',
      3 =>  'is_default',
      4 => 'aksi'
    );

    $limit  = $this->input->post('length');
    $start  = $this->input->post('start');
    $order  = $columns[$this->input->post('order')[0]['column']];
    $dir    = $this->input->post('order')[0]['dir'];
    $query = 'SELECT id_status_pengajuan, nama_status_pengajuan, is_default FROM tb_status_pengajuan WHERE id_channel = ' . $user['id_channel'] . ' AND is_deleted = 0';
    echo $query; exit();
    $totalData = $this->Db_global->allposts_count_all($query);
    $totalFiltered = $totalData;
    if (empty($this->input->post('search')['value'])) {
      $posts = $this->Db_global->allposts_all($query . " order by " . $order . " " . $dir . " limit " . $start . "," . $limit);
    } else {
      $search = $this->input->post('search', true)['value'];
      $posts = $this->Db_global->posts_search_all($query . " AND nama_status_pengajuan like '%" . $search . "%' or is_default like '%" . $search . "%' order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
      $totalFiltered = $this->Db_global->posts_search_count_all($query . " AND nama_status_pengajuan like '%" . $search . "%' or is_default like '%" . $search . "%'");
    }

    $data = array();
    if (!empty($posts)) {
      foreach ($posts as $post) {
        if ($post->is_default == 1) {
          $aksi = "";
        } else {
          $cekUser = $this->Db_select->select_where('tb_pengajuan', ['status_pengajuan' => $post->id_status_pengajuan], ['id_pengajuan']);
          $aksi = '<a href="#" data-toggle="modal" onclick="updateData(' . $post->id_status_pengajuan . ')" class="btn btn-info btn-sm text-white"><span class="fa fa-pencil-alt"></span></a>';
          if (!$cekUser) {
            $aksi .= '&nbsp<a href="#" data-type="ajax-loader" onclick="hapus(' . $post->id_status_pengajuan . ')" class="btn btn-danger btn-sm text-white"><span class="fa fa-trash"></span></a>';
          }
        }

        $is_default = "";

        if ($post->is_default == 1) {
          $is_default = "<span class='badge badge-warning text-white'>Default</span>";
        }

        $is_aktif = "";
        if ($post->is_aktif == 1) {
          $is_aktif = 'checked';
        }

        $nestedData['nama_status_pengajuan'] = ucwords($post->nama_status_pengajuan);
        $nestedData['status'] = '<input type="checkbox" name="my-checkbox" onchange="is_aktif(' . $post->id_status_pengajuan . ')" ' . $is_aktif . ' id="is_aktif' . $post->id_status_pengajuan . '" data-bootstrap-switch>';
        $nestedData['type'] = $is_default;
        $nestedData['aksi'] = $aksi;
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

  public function toggleActiveStatusPengajuan()
  {
    $id_status_pengajuan = $this->input->post('id', true);
    $is_aktif = $this->input->post('is_aktif', true);
    $user = $this->session->userdata('user');
    $status_pengajuan = $this->Db_select->select_where('tb_status_pengajuan', [
      'id_status_pengajuan' => $id_status_pengajuan,
      'id_channel' => $user['id_channel']
    ]);

    if (is_null($status_pengajuan)) {
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(404)
        ->set_output(json_encode([
          'status' => false,
          'message' => 'Data status pengajuan tidak ditemukan',
          'data' => []
        ], JSON_PRETTY_PRINT))
        ->_display();
      exit();
    }

    $updateData = $this->Db_dml->update(
      'tb_status_pengajuan',
      ['is_aktif' => intval($is_aktif)],
      ['id_status_pengajuan' => $id_status_pengajuan]
    );

    if ($updateData == 1) {
      $result['status'] = true;
      $result['message'] = 'Berhasil mengubah aktif status pengajuan.';
      $result['data'] = [];
    } else {
      $result['status'] = false;
      $result['message'] = 'Gagal mengubah aktif status pengajuan.';
      $result['data'] = [];
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(500)
        ->set_output(json_encode($result, JSON_PRETTY_PRINT))
        ->_display();
      exit();
    }

    $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($result, JSON_PRETTY_PRINT));
  }

  public function update_status()
  {
    # code...
  }

  public function detail($id)
  {
    $check = $this->Db_select->select_where('tb_status_pengajuan', ['id_status_pengajuan' => $id], ['id_status_pengajuan', 'nama_status_pengajuan']);
    if ($check) {
      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $check;
    } else {
      $result['status'] = false;
      $result['message'] = 'Data tidak ditemukan';
      $result['data'] = null;
    }

    echo json_encode($result);
  }
}