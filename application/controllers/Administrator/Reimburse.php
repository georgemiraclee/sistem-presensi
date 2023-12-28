<?php defined('BASEPATH') or exit('No direct script access allowed');

class reimburse extends CI_Controller
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
    $menu['child'] = 'pengajuan_reimburse';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/reimburse');
    $this->load->view('Administrator/footer');
  }

  public function detail($id)
  {
    $sess = $this->session->userdata('user');
    if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
      redirect(base_url());
      exit();
    }

    $data['cari'] = 'false';
    $query = $this->Db_select->query('select nama_user, a.foto_user, created_at from tb_user a join tb_reimburse b on a.user_id = b.user_id where id_reimburse = ' . $id);

    if ($query->foto_user == "" || $query->foto_user == null) {
      $query->foto_user = "" . base_url() . "assets/images/member-photos/ava.png ";
    } else {
      $filename = './assets/images/member-photos/' . $query->foto_user;
      if (!file_exists($filename)) {
        $query->foto_user = "http://placehold.it/500x400";
      } else {
        $query->foto_user = " " . base_url() . "assets/images/member-photos/" . $query->foto_user;
      }
    }

    $data['foto'] = $query->foto_user;
    $data['nama'] = $query->nama_user;
    $data['tanggal'] = date('d-M-Y', strtotime($query->created_at));
    $data['modal'] = "";
    $data['list'] = "";
    $query2 = $this->Db_select->query_all('SELECT a.id_history_reimburse, a.file_reimburse, a.tanggal_reimburse, a.keterangan_reimburse, a.nominal, c.nama_tipe_reimburse FROM tb_history_reimburse a JOIN tb_reimburse b ON a.id_reimburse = b.id_reimburse JOIN tb_tipe_reimburse c ON a.id_tipe_reimburse = c.id_tipe_reimburse WHERE a.id_reimburse = ?', [$id]);

    foreach ($query2 as $value) {
      $query3 = $this->Db_select->select_where('tb_aksi_reimburse', ['id_history_reimburse' => $value->id_history_reimburse], ['status_reimburse']);
      $data['modal'] .= '
        <div id="myModalb' . $value->id_history_reimburse . '" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-body">
                <form id="add-form2" action="javascript:void(0);" method="post">
                  <div class="form-group">
                    <label for="keterangan_aksi_reimburse">Keterangan Penolakan:</label>
                    <input type="text" name="keterangan_aksi_reimburse" id="keterangan_aksi_reimburse" class="form-control" placeholder="Tulis alasan penolakan secara terperinci" required="">
                  </div>
                  <input type="hidden" name="id_history_reimburse" value="' . $value->id_history_reimburse . '">
                  <input type="hidden" name="status_reimburse" value="2">
                  <input type="hidden" name="dibayarkan" value="0">
              </div>
              <div class="modal-footer">
                <button class="btn btn-warning text-white btn-block" type="submit">Decline</button>
                  </form>
              </div>
            </div>
          </div>
        </div>
        <div id="myModala' . $value->id_history_reimburse . '" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-body">
                <form id="add-form" action="javascript:void(0);" method="post">
                  <div class="form-group">
                    <label for="email_address_2">Nominal yang dibayarkan :</label>
                    <input type="text" name="maximum_amount" id="rupiah" class="form-control" placeholder="Nominal yang disetujui" required>
                  </div>
                  <div class="form-group">
                    <label for="email_address_2">Keterangan reimburse :</label>
                    <input type="text" name="keterangan_aksi_reimburse" class="form-control" placeholder="Keterangan tambahan" required="">
                  </div>
                  <input type="hidden" name="status_reimburse" value="1">
                  <input type="hidden" name="id_history_reimburse" value="' . $value->id_history_reimburse . '">
                  </div>
                  <div class="modal-footer">
                  <button class="btn btn-primary btn-block" type="submit">Accept</button>
                  </form>
              </div>
            </div>
          </div>
        </div>
        <div id="myModalc' . $value->id_history_reimburse . '" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-body">
                <img src="' . base_url() . 'assets/images/reimburse/' . $value->file_reimburse . '" style="width:100%;">
                
              </div>
            </div>
          </div>
        </div>
        <script> </script>
      ';
      if ($query3 == "" || $query3 == null) {
        $status = '<span class="badge badge-warning text-white">Menunggu Persetujuan</span>';
        $aksi = '
          <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModala' . $value->id_history_reimburse . '" >Accept</button>
          <button class="btn btn-warning btn-sm text-white" data-toggle="modal" data-target="#myModalb' . $value->id_history_reimburse . '" >Decline</button>
          <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalc' . $value->id_history_reimburse . '" >Bukti Transaksi</button>
        ';
      } else {
        if ($query3->status_reimburse == 1) {
          $status = '<span class="badge badge-success">Disetujui</span>';
          $aksi = '<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalc' . $value->id_history_reimburse . '" >Bukti Transaksi</button>';
        } else {
          $status = '<span class="badge badge-danger">Ditolak</span>';
          $aksi = '<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalc' . $value->id_history_reimburse . '" >Bukti Transaksi</button>';
        }
      }
      $data['list'] .= '
        <tr>
          <th scope="row">' . date('d-M-Y', strtotime($value->tanggal_reimburse)) . '</th>
          <td>' . $value->nama_tipe_reimburse . '</td>
          <td>' . $value->keterangan_reimburse . '</td>
          <td>Rp ' . $value->nominal. '</td>
          <td>' . $status . ' </td>
          <td>' . $aksi . ' </td>
        </tr>
      ';
    }


    $menu['main'] = 'pengajuan';
    $menu['child'] = 'pengajuan_reimburse';
    $data['menu'] = $menu;
    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/detail_reimburse');
    $this->load->view('Administrator/footer');
  }

  public function getData()
  {
    $sess = $this->session->userdata('user');
    $columns = array(
      0 =>  'b.id_reimburse',
      1 =>  'a.nip',
      2 =>  'a.nama_user',
      3 =>  'jumlah_acc',
      4 =>  'b.created_at',
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
      $query .= " AND a.id_unit IN(" . $departemen . ")";
    }
    if ($this->input->get('jabatan', true)) {
      $filterJabatan = $this->input->get('jabatan', true);
      $filterJabatan = explode(',', $filterJabatan);
      $jabatan = "";
      foreach ($filterJabatan as $key => $value) {
        $jabatan .= ",'$value'";
      }
      $jabatan = substr($jabatan, 1);
      $query .= " AND a.jabatan IN(" . $jabatan . ")";
    }
    if ($this->input->get('jenkel', true)) {
      $filterJenkel = $this->input->get('jenkel', true);
      $filterJenkel = explode(',', $filterJenkel);
      $jenkel = "";
      foreach ($filterJenkel as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " AND a.jenis_kelamin IN(" . $jenkel . ")";
    }
    if ($this->input->get('tipe', true)) {
      $filterType = $this->input->get('tipe', true);
      $filterType = explode(',', $filterType);
      $tipe = "";
      foreach ($filterType as $key => $value) {
        $tipe .= ",'$value'";
      }
      $tipe = substr($tipe, 1);
      $query .= " AND status_user IN(" . $tipe . ")";
    }
    $where = 'id_channel = ' . $sess['id_channel'] . $query;

    $limit  = $this->input->post('length', true);
    $start  = $this->input->post('start', true);
    $order  = $columns[$this->input->post('order', true)[0]['column']];
    $dir    = $this->input->post('order', true)[0]['dir'];
    $totalData = $this->Db_global->allposts_count_all("select * from tb_user a join tb_reimburse b on a.user_id = b.user_id join tb_unit c on a.id_unit = c.id_unit where " . $where);
    $totalFiltered = $totalData;
    
    if (empty($this->input->post('search', true)['value'])) {
      $posts = $this->Db_global->allposts_all("select * from tb_user a join tb_reimburse b on a.user_id = b.user_id join tb_unit c on a.id_unit = c.id_unit where " . $where . " order by " . $order . " " . $dir . " limit " . $start . "," . $limit);
    } else {
      $search = $this->input->post('search', true)['value'];
      $posts = $this->Db_global->posts_search_all("select * from tb_user a join tb_reimburse b on a.user_id = b.user_id join tb_unit c on a.id_unit = c.id_unit where " . $where . " and (a.nip like '%" . $search . "%' or a.nama_user like '%" . $search . "%') order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
      $totalFiltered = $this->Db_global->posts_search_count_all("select * from tb_user a join tb_reimburse b on a.user_id = b.user_id join tb_unit c on a.id_unit = c.id_unit where " . $where . " and (a.nip like '%" . $search . "%' or a.nama_user like '%" . $search . "%')");
    }

    $data = array();
    if (!empty($posts)) {
      foreach ($posts as $keys => $post) {
        $getTotalReimburs = $this->Db_select->query('SELECT COUNT(*) total FROM `tb_reimburse` a JOIN tb_history_reimburse b ON a.id_reimburse = b.id_reimburse WHERE a.id_reimburse = ?', [$post->id_reimburse]);
        $totalData = $getTotalReimburs->total;
        $getDataList =  $this->Db_select->query_all('SELECT id_history_reimburse FROM tb_reimburse a JOIN tb_history_reimburse b ON a.id_reimburse = b.id_reimburse WhERE a.id_reimburse = ?', [$post->id_reimburse]);
        $accData = 0;
        foreach ($getDataList as $key => $value) {
          $getAcc  = $this->Db_select->select_where('tb_aksi_reimburse', [
            'status_reimburse' => 1,
            'id_history_reimburse' => $value->id_history_reimburse
          ], ['id']);
          if ($getAcc != null || $getAcc != "") {
            $accData++;
          }
        }

        $status = "<span class='badge badge-warning text-white'>Menunggu Persetujuan</span>";
        if ($accData == $totalData) {
          $status = "<span class='badge badge-success'>Disetujui</span>";
        } else if ($accData < $totalData && $accData != 0) {
          $status = "<span class='badge badge-info text-white'>Disetujui Sebagian</span>";
        } else if ($accData == 0) {
          $status = "<span class='badge badge-warning text-white'>Menunggu Persetujuan</span>";
        }
        $nestedData['id_reimburse']  = $post->id_reimburse;
        $nestedData['nip']  = $post->nip;
        $nestedData['nama_user']  = $post->nama_user;
        $nestedData['jumlah_acc']  = $accData . "/" . $totalData;
        $nestedData['status']  = $status;
        $nestedData['created_at']  = $post->created_at;
        $nestedData['aksi']  = '<a href="' . base_url() . 'Administrator/reimburse/detail/' . $post->id_reimburse . '" class="btn btn-info btn-sm">
                <span class="fa fa-eye"></span>
              </a>';

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

  public function insert()
  {
    $insert = array();
    if ($this->input->post('status_reimburse', true)) {
      $insert['status_reimburse'] = $this->input->post('status_reimburse', true);
      $insert['id_history_reimburse'] = $this->input->post('id_history_reimburse', true);
      $insert['dibayarkan'] = $this->input->post('dibayarkan', true);;
      $insert['keterangan_aksi_reimburse'] = $this->input->post('keterangan_aksi_reimburse', true);
    }
    if (count($insert) > 0) {
      $insertData = $this->Db_dml->normal_insert('tb_aksi_reimburse', $insert);
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
}
