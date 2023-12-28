<?php defined('BASEPATH') or exit('No direct script access allowed');

class data_jaringan extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->library(array('ceksession', 'global_lib'));
    $this->load->model('Db_datatable');
  }

  public function wifi()
  {
    $user = $this->session->userdata('user');
    $id_channel = $user['id_channel'];
    if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama") {
      redirect(base_url());
      exit();
    }

    $data['list'] = $this->Db_select->select_all_where('tb_jaringan', [
      'id_channel' => $id_channel,
      'is_deleted' => 0
    ]);
    $menu['main'] = 'pengaturan';
    $menu['child'] = 'pengaturan_wifi';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/wifi');
    $this->load->view('Administrator/footer');
  }

  public function detailWifi($id)
  {
    $getData = $this->Db_select->select_where('tb_jaringan', ['id_jaringan' => $id], ['id_jaringan', 'ssid_jaringan', 'mac_address_jaringan', 'lokasi_jaringan']);

    if ($getData) {
      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $getData;
    } else {
      $result['status'] = false;
      $result['message'] = 'Data wifi tidak ditemukan';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function insert_wifi()
  {
    $user = $this->session->userdata('user');
    $id_channel = $user['id_channel'];
    $insert = array();
    if ($this->input->post('ssid_jaringan', true)) {
      $insert['ssid_jaringan'] = $this->input->post('ssid_jaringan', true);
      $insert['lokasi_jaringan'] = $this->input->post('lokasi_jaringan', true);
      $insert['mac_address_jaringan'] = $this->input->post('mac_address_jaringan', true);
      $insert['id_channel'] = $id_channel;
    }

    if (count($insert) > 0) {
      $insertData = $this->Db_dml->normal_insert('tb_jaringan', $insert);
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

  public function delete_wifi()
  {
    $where['id_jaringan'] = $this->input->post('id_jaringan', true);
    $delete = $this->Db_dml->update('tb_jaringan', ['is_deleted' => 1], $where);
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

  public function update_wifi()
  {
    $where['id_jaringan'] = $this->input->post('id_jaringan', true);
    $update = array();
    $update['ssid_jaringan'] = $this->input->post('ssid_jaringan', true);
    $update['lokasi_jaringan'] = $this->input->post('lokasi_jaringan', true);
    $update['mac_address_jaringan'] = $this->input->post('mac_address_jaringan', true);

    $updateData = $this->Db_dml->update('tb_jaringan', $update, $where);
    if ($updateData == 1 || $updateData == 0) {
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

  public function update_status_wifi()
  {
    $where['id_jaringan'] = $this->input->post('id_jaringan', true);
    $update['is_aktif'] = $this->input->post('is_aktif', true);
    $updateData = $this->Db_dml->update('tb_jabatan', $update, $where);
    if ($updateData) {
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

  public function area()
  {
    $user = $this->session->userdata('user');
    $id_channel = $user['id_channel'];
    $data['data_area'] = $this->Db_select->select_all_where('tb_lokasi', [
      'id_channel' => $id_channel,
      'is_deleted' => 0
    ], ['id_lokasi']);

    foreach ($data['data_area'] as $key => $value) {
      $selectUser = $this->Db_select->select_where('tb_history_absensi', ['id_lokasi' => $value->id_lokasi], ['id_history_absensi']);
      if ($selectUser) {
        $value->delete = false;
      } else {
        $value->delete = true;
      }
    }

    $menu['main'] = 'pengaturan';
    $menu['child'] = 'pengaturan_lokasi';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/area');
    $this->load->view('Administrator/footer');
  }

  public function addNew()
  {
    $menu['main'] = 'pengaturan';
    $menu['child'] = 'pengaturan_lokasi';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/addNew');
    $this->load->view('Administrator/footer');
  }

  public function insert_area()
  {
    $user = $this->session->userdata('user');
    $id_channel = $user['id_channel'];
    $input['nama_lokasi'] = $this->input->post('nama_lokasi', true);
    $input['id_channel'] = $id_channel;
    $is_find = $this->Db_select->select_where('tb_lokasi', $input, 'id_lokasi');
    if (!$is_find) {
      if ($_FILES['userfile']['name']) {
        $path = realpath(APPPATH . '../assets/polygon');
        $namafile = mdate("%Y%m%d%H%i%s", time());
        $config = array(
          'allowed_types' => 'csv',
          'upload_path' => $path,
          'max_size' => 2000,
          'encrypt_name' => false,
          'file_name' => $namafile
        );
        $this->upload->initialize($config);

        if ($this->upload->do_upload()) {
          $img_data = $this->upload->data();
          $new_imgname = $namafile . $img_data['file_ext'];
          $new_imgpath = $img_data['file_path'] . $new_imgname;
          rename($img_data['full_path'], $new_imgpath);
          $input['url_file_lokasi'] = $new_imgname;
          $polygon_insert = $this->Db_dml->normal_insert('tb_lokasi', $input);
          if ($polygon_insert) {
            $result['status'] = true;
            $result['message'] = 'Data berhasil disimpan';
            $result['data'] = array();
          } else {
            $result['status'] = true;
            $result['message'] = 'Data gagal disimpan';
            $result['data'] = array();
          }
        } else {
          $text = $this->upload->display_errors();
          $text = str_ireplace('<p>', '', $text);
          $text = str_ireplace('</p>', '', $text);

          $result['status'] = false;
          $result['message'] = $text;
          $result['data'] = array();
          echo json_encode($result);
          exit();
        }
      } else {
        $result['status'] = false;
        $result['message'] = 'File wilayah wajib di isi';
        $result['data'] = array();
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data sudah dibuat.';
      $result['data'] = array();
    }

    echo json_encode($result);
  }

  public function delete_area()
  {
    $where['id_lokasi'] = $this->input->post('id_lokasi', true);
    $delete = $this->Db_dml->update('tb_lokasi', ['is_deleted' => 1], $where);
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

  public function update_area()
  {
    $where['id_lokasi'] = $this->input->post('id_lokasi', true);
    $update = array();
    $is_find = $this->Db_select->select_where('tb_lokasi', $where, ['nama_lokasi']);
    if ($is_find->nama_lokasi != $this->input->post('nama_lokasi', true)) {
      $whereFind['nama_lokasi'] = $this->input->post('nama_lokasi', true);
      $is_find = $this->Db_select->select_where('tb_lokasi', $whereFind, ['id_lokasi']);
      if ($is_find) {
        $result['status'] = false;
        $result['message'] = 'Nama lokasi sudah terdaftar.';
        $result['data'] = array();

        echo json_encode($result);
        exit();
      }
      $update['nama_lokasi'] = $this->input->post('nama_lokasi', true);
    }
    if ($_FILES['userfile']['name']) {
      $path = realpath(APPPATH . '../assets/polygon');
      $namafile = mdate("%Y%m%d%H%i%s", time());
      $config = array(
        'allowed_types' => 'csv',
        'upload_path' => $path,
        'max_size' => 2000,
        'encrypt_name' => false,
        'file_name' => $namafile
      );
      $this->upload->initialize($config);
      if ($this->upload->do_upload()) {
        $img_data = $this->upload->data();
        $new_imgname = $namafile . $img_data['file_ext'];
        $new_imgpath = $img_data['file_path'] . $new_imgname;
        rename($img_data['full_path'], $new_imgpath);
        $update['url_file_lokasi'] = $new_imgname;
      } else {
        $text = $this->upload->display_errors();
        $text = str_ireplace('<p>', '', $text);
        $text = str_ireplace('</p>', '', $text);

        $result['status'] = false;
        $result['message'] = $text;
        $result['data'] = array();
        echo json_encode($result);
        exit();
      }
    }

    if (count($update) > 0) {
      $updateData = $this->Db_dml->update('tb_lokasi', $update, $where);
      if ($updateData == 1) {
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
    exit();
  }

  public function update_status_area()
  {
    $where['id_jaringan'] = $this->input->post('id_jaringan', true);
    $update['is_aktif'] = $this->input->post('is_aktif', true);
    $updateData = $this->Db_dml->update('tb_lokasi', $update, $where);
    if ($updateData) {
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

  public function get_data_user($value = null)
  {
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];
    if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama") {
      redirect(base_url());
      exit();
    }
    $tb = 'tb_jaringan';
    $wr = 'id_channel=' . $id_channel;
    $fld =  array(null, 'ssid_jaringan', 'mac_address_jaringan', 'lokasi_jaringan', null);
    $src = array('ssid_jaringan');
    $ordr = array('created_jaringan' => 'asc');;
    $list = $this->Db_datatable->get_datatables2($tb, $wr, $fld, $src, $ordr);

    $data = array();
    $no = $_POST['start'];

    foreach ($list as $field) {
      $selectUser = $this->Db_select->select_where('tb_history_absensi', 'id_jaringan = ' . $field->id_jaringan);

      if ($selectUser) {
        $delete = false;
      } else {
        $delete = true;
      }

      if ($delete == true) {
        $del = ' <a href="#" style="color: grey" data-type="ajax-loader" onclick="hapus(' . $field->id_jaringan . ')" class="badge bg-red"><span class="material-icons" style="font-size: 15px;">delete</span></a>';
      } else {
        $del = '';
      }

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $field->ssid_jaringan;
      $row[] = $field->mac_address_jaringan;
      $row[] = $field->lokasi_jaringan;
      $row[] = '<a href="#" style="color: grey;" data-toggle="modal" data-target="#updateModal' . $no . '" class="badge bg-orange"><span class="material-icons" style="font-size: 15px;">mode_edit</span></a>' . $del;
      $data[] = $row;
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Db_datatable->count_all2($tb, $wr, $fld, $src, $ordr),
      "recordsFiltered" => $this->Db_datatable->count_filtered2($tb, $wr, $fld, $src, $ordr),
      "data" => $data,
    );

    echo json_encode($output);
  }

  public function get_data_area($value = null)
  {
    $user = $this->session->userdata('user');
    $id_channel = $user['id_channel'];

    if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama") {
      redirect(base_url());
      exit();
    }

    $tb = 'tb_lokasi';
    $wr = 'id_channel = "' . $id_channel . '" and is_deleted = 0';
    $fld =  array(null, 'nama_lokasi', 'url_file_lokasi', null);
    $src = array('nama_lokasi');
    $ordr = array('created_lokasi' => 'asc');;
    $list = $this->Db_datatable->get_datatables2($tb, $wr, $fld, $src, $ordr);

    $data = array();
    $no = $_POST['start'];
    foreach ($list as $field) {
      $del = ' <a href="#" data-type="ajax-loader" onclick="hapus(' . $field->id_lokasi . ')" class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></a>';

      $no++;
      $row = array();
      $row[] = $no;
      $row[] = $field->nama_lokasi;
      $row[] = $field->url_file_lokasi;
      $row[] = '<a href="' . base_url('Administrator/data_jaringan/edit_lokasi/' . $field->id_lokasi) . '" class="btn btn-info btn-sm text-white"><span class="fa fa-pencil-alt"></span></a>' . $del;
      $data[] = $row;
    }
    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->Db_datatable->count_all2($tb, $wr, $fld, $src, $ordr),
      "recordsFiltered" => $this->Db_datatable->count_filtered2($tb, $wr, $fld, $src, $ordr),
      "data" => $data,
    );

    echo json_encode($output);
  }

  public function insertNew()
  {
    $coordinates = $this->input->post('kordinat');

    $coordinates = [$coordinates['lng'], $coordinates['lat']]; // [lon, lat]
    $radius = $this->input->post('radius'); // in meters
    $numberOfEdges = 32; // optional that defaults to 32

    $resultConvert = $this->global_lib->convert($coordinates, $radius, $numberOfEdges);

    $data['kordinat'] = $resultConvert['coordinates'][0];

    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];

    // Load plugin PHPExcel nya
    include APPPATH . 'third_party/PHPExcel.php';

    // Panggil class PHPExcel nya
    $excel = new PHPExcel();

    // Settingan awal fil excel
    $excel->getProperties()->setCreator('Pressensi')
      ->setLastModifiedBy('Pressensi')
      ->setTitle("koordinat lokasi")
      ->setSubject("koordinat")
      ->setDescription("koordinat lokasi")
      ->setKeywords("koordinat");

    // Buat header tabel nya pada baris ke 3
    $excel->setActiveSheetIndex(0)->setCellValue('A1', "Lat"); // Set kolom A3 dengan tulisan "NO"
    $excel->setActiveSheetIndex(0)->setCellValue('B1', "Lng"); // Set kolom B3 dengan tulisan "NIS"

    /* isi data dari data koordinat */
    $no = 2;
    foreach ($data['kordinat'] as $key => $value) {
      if ($value[1] != "" || $value[1] != null) {
        $excel->setActiveSheetIndex(0)->setCellValue('A' . $no, $value[1]);
        $excel->setActiveSheetIndex(0)->setCellValue('B' . $no, $value[0]);
        $no++;
      }
    }

    $excel->setActiveSheetIndex(0)->setCellValue('A' . $no, $data['kordinat'][0]['lat']);
    $excel->setActiveSheetIndex(0)->setCellValue('B' . $no, $data['kordinat'][0]['lng']);

    // Set orientasi kertas jadi LANDSCAPE
    $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

    // Set judul file excel nya
    $excel->getActiveSheet(0)->setTitle("Koordinat Lokasi");
    $excel->setActiveSheetIndex(0);

    // Proses file excel
    $namafile = mdate("%Y%m%d%H%i%s", time());
    $file_name = $id_channel . '_' . $namafile . ".csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $file_name . '"'); // Set nama file excel nya

    $write = PHPExcel_IOFactory::createWriter($excel, 'CSV');
    ob_end_clean();
    $write->save("assets/polygon/" . $file_name);

    $insert['nama_lokasi'] = $this->input->post('nama_lokasi');
    $insert['url_file_lokasi'] = $file_name;
    $insert['id_channel'] = $id_channel;
    $insert['lat'] = $this->input->post('kordinat')['lat'];
    $insert['lng'] = $this->input->post('kordinat')['lng'];
    $insert['radius'] = $radius;
    $polygon_insert = $this->Db_dml->normal_insert('tb_lokasi', $insert);
    if ($polygon_insert == 1) {
      $result['status'] = true;
      $result['message'] = 'Data berhasil disimpan';
      $result['data'] = array();
    } else {
      $result['status'] = false;
      $result['message'] = 'Data gagal disimpan';
      $result['data'] = array();
    }

    echo json_encode($result);
  }

  public function edit_lokasi($id)
  {
    $data['data'] = $this->Db_select->select_where('tb_lokasi', 'id_lokasi = "' . $id . '"');
    $menu['main'] = 'pengaturan';
    $menu['child'] = 'pengaturan_lokasi';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/edit_lokasi');
    $this->load->view('Administrator/footer');
  }

  public function updateLokasi()
  {
    $where['id_lokasi'] = $this->input->post('id_lokasi');

    $getData = $this->Db_select->select_where('tb_lokasi', $where);

    if ($getData) {
      $coordinates = $this->input->post('kordinat');
      $coordinates = [$coordinates['lng'], $coordinates['lat']]; // [lon, lat]
      $radius = $this->input->post('radius'); // in meters
      $numberOfEdges = 32; // optional that defaults to 32

      $result = $this->global_lib->convert($coordinates, $radius, $numberOfEdges);

      $data['kordinat'] = $result['coordinates'][0];

      $sess = $this->session->userdata('user');
      $id_channel = $sess['id_channel'];

      // Load plugin PHPExcel nya
      include APPPATH . 'third_party/PHPExcel.php';

      // Panggil class PHPExcel nya
      $excel = new PHPExcel();

      // Settingan awal fil excel
      $excel->getProperties()->setCreator('Pressensi')
        ->setLastModifiedBy('Pressensi')
        ->setTitle("koordinat lokasi")
        ->setSubject("koordinat")
        ->setDescription("koordinat lokasi")
        ->setKeywords("koordinat");

      // Buat header tabel nya pada baris ke 3
      $excel->setActiveSheetIndex(0)->setCellValue('A1', "Lat"); // Set kolom A3 dengan tulisan "NO"
      $excel->setActiveSheetIndex(0)->setCellValue('B1', "Lng"); // Set kolom B3 dengan tulisan "NIS"

      /* isi data dari data koordinat */
      $no = 2;
      foreach ($data['kordinat'] as $key => $value) {
        if ($value[1] != "" || $value[1] != null) {
          $excel->setActiveSheetIndex(0)->setCellValue('A' . $no, $value[1]);
          $excel->setActiveSheetIndex(0)->setCellValue('B' . $no, $value[0]);
          $no++;
        }
      }

      $excel->setActiveSheetIndex(0)->setCellValue('A' . $no, $data['kordinat'][0]['lat']);
      $excel->setActiveSheetIndex(0)->setCellValue('B' . $no, $data['kordinat'][0]['lng']);


      // Set orientasi kertas jadi LANDSCAPE
      $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

      // Set judul file excel nya
      $excel->getActiveSheet(0)->setTitle("Koordinat Lokasi");
      $excel->setActiveSheetIndex(0);

      // Proses file excel
      $file_name = $getData->url_file_lokasi;
      header('Content-Type: text/csv; charset=utf-8');
      header('Content-Disposition: attachment; filename="' . $file_name . '"'); // Set nama file excel nya

      $write = PHPExcel_IOFactory::createWriter($excel, 'CSV');
      ob_end_clean();
      $write->save("assets/polygon/" . $file_name);

      $update['nama_lokasi'] = $this->input->post('nama_lokasi');
      $update['url_file_lokasi'] = $file_name;
      $update['id_channel'] = $id_channel;
      $update['lat'] = $this->input->post('kordinat')['lat'];
      $update['lng'] = $this->input->post('kordinat')['lng'];
      $update['radius'] = $this->input->post('radius');

      $this->Db_dml->update('tb_lokasi', $update, $where);

      $result['status'] = true;
      $result['message'] = 'Data berhasil diubah';
      $result['data'] = array();
    } else {
      $result['status'] = false;
      $result['message'] = 'Data tidak ditemukan';
      $result['data'] = array();
    }
    echo json_encode($result);
  }
}
