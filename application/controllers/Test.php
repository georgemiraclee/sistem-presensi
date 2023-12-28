<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
  public function __construct()
	{
		parent::__construct();
  }

  public function importPegawai()
  {
    include APPPATH.'third_party/PHPExcel.php';
    $excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($_FILES['userfile']['tmp_name']);
    $loadexcel->setActiveSheetIndex(0);
		$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
    
    $total = 0;
    foreach($sheet as $row => $value){
      if ($row >= 2) {
        /* Cek Data */
        $cek = $this->Db_select->select_where('tb_user', ['nip' => $value['A']]);
        if ($cek) {
          $where['user_id'] = $cek->user_id;
          $data['nip'] = $value['B'];
          $this->Db_dml->update('tb_user', $data, $where);
          $total++;
          // if (strtolower($value['D']) == 'aktif') {
          //   $where['user_id'] = $cek->user_id;
          //   $data['sudah_ada'] = 1;
            
          //   $this->Db_dml->update('tb_user', $data, $where);
          // }
          // /* insert jabatan */
          // $jabatan = $this->Db_select->select_where('tb_jabatan', ['nama_jabatan' => $value['D'], 'id_channel' => 14]);
          // if (!$jabatan) {
          //   $insertJabatan['nama_jabatan'] = $value['D'];
          //   $insertJabatan['id_channel'] = 14;
          //   $id_jabatan = $this->Db_dml->insert('tb_jabatan', $insertJabatan);
          //   $jabatan = $this->Db_select->select_where('tb_jabatan', ['id_jabatan' => $id_jabatan]);
          // }
          // /* unit */
          // $unit = $this->Db_select->select_where('tb_unit', ['nama_unit' => $value['E'], 'id_channel' => 14]);
          // if (!$unit) {
          //   $insertUnit['nama_unit'] = $value['E'];
          //   $insertUnit['id_channel'] = 14;
          //   $id_unit = $this->Db_dml->insert('tb_unit', $insertUnit);
          //   $unit = $this->Db_select->select_where('tb_unit', ['id_unit' => $id_unit]);
          // }
          // /* status Pegawai */
          // $statusPegawai = $this->Db_select->select_where('tb_status_user', ['nama_status_user' => $value['F'], 'id_channel' => 14]);
          // if (!$statusPegawai) {
          //   $insertStatusPegawai['nama_status_user'] = $value['F'];
          //   $insertStatusPegawai['id_channel'] = 14;
          //   $id_status_user = $this->Db_dml->insert('tb_status_user', $insertStatusPegawai);
          //   $statusPegawai = $this->Db_select->select_where('tb_status_user', ['id_status_user' => $id_status_user]);
          // }
          
          // $nama_user = $value['B'];
          
          // $data['nip'] = $value['A'];
          // $data['password_user'] = md5('12345');
          // $data['nama_user'] = $nama_user;
          // $data['id_unit'] = $unit->id_unit;
          // $data['jabatan'] = $jabatan ? $jabatan->id_jabatan : 292;
          // $data['status_user'] = $statusPegawai->id_status_user;
          // $data['tempat_lahir'] = $value['G'];
          // $data['tanggal_lahir'] = $value['H'];
          // $data['tanggal_lahir'] = $value['H'];
          // $data['jenis_kelamin'] = 'p';
          // $data['telp_user'] = $value['J'];
          // $data['email_user'] = $value['K'];
          // $data['nomor_identitas'] = $value['L'];
          // $data['jenis_nomor_identitas'] = 'ktp';
          // $data['id_struktur'] = 2;
          // $data['id_parent'] = 5891;
          // $data['cuti'] = 14;
          // $data['gaji_pokok'] = 0;

          // $db = $this->Db_dml->insert('tb_user', $data);
          // if (!$db) {
          //   echo json_encode('haha'); exit();
          // }
        }
      }
    }

    echo $total;
  }

  public function importPegawaiMx()
  {
    include APPPATH.'third_party/PHPExcel.php';
    $excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($_FILES['userfile']['tmp_name']);
    $loadexcel->setActiveSheetIndex(1);
		$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
    
    foreach($sheet as $row => $value){
      /* CHECK USER */
      $user = $this->Db_select->select_where('tb_user', ['nip' => $value['A']]);

      if ($user) {
        /* CHECK STATUS PEKERJAAN */
        $statusPekerjaan = $this->Db_select->select_where('tb_status_user', ['id_channel' => 15, 'nama_status_user' => $value['C']]);
        if (!$statusPekerjaan) {
          /* INSERT DATA STATUS PEKERJAAN */
          $insertPekerjaan['nama_status_user'] = $value['C'];
          $insertPekerjaan['id_channel'] = 15;
          $insertPekerjaan['is_aktif'] = 1;
  
          $idPekerjaan = $this->Db_dml->insert('tb_status_user', $insertPekerjaan);
          $statusPekerjaan = $this->Db_select->select_where('tb_status_user', ['id_status_user' => $idPekerjaan]);
        }
  
        /* CHECK JABATAN */
        $statusJabatan = $this->Db_select->select_where('tb_jabatan', ['id_channel' => 15, 'nama_jabatan' => $value['D']]);
        if (!$statusJabatan) {
          /* INSERT DATA STATUS PEKERJAAN */
          $insertJabatan['nama_jabatan'] = $value['D'];
          $insertJabatan['id_channel'] = 15;
          $insertJabatan['is_aktif'] = 1;
  
          $idJabatan = $this->Db_dml->insert('tb_jabatan', $insertJabatan);
          $statusJabatan = $this->Db_select->select_where('tb_jabatan', ['id_jabatan' => $idJabatan]);
        }

        $where['user_id'] = $user->user_id;
        $update['nama_user'] = $value['B'];
        $update['status_user'] = $statusPekerjaan->id_status_user;
        $update['jabatan'] = $statusJabatan->id_jabatan;
        $update['role'] = $value['F'] == 'HR' ? 1 : 0;
        $update['jenis_kelamin'] = $value['G'] == 'Laki-Laki' ? 'l' : 'p';
        $update['tempat_lahir'] = $value['H'];
        $update['status_pernikahan'] = $value['J'];
        $update['agama'] = $value['K'];
        $update['alamat_user'] = $value['L'];
        $update['email'] = $value['M'];
        $update['telp_user'] = $value['N'];
        $this->Db_dml->update('tb_user', $update, $where);
      }
    }

    echo json_encode('success'); exit();
  }

  public function stuktur()
  {
    include APPPATH.'third_party/PHPExcel.php';
    $excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($_FILES['userfile']['tmp_name']);
    $loadexcel->setActiveSheetIndex(1);
		$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

    foreach($sheet as $row => $value){
      /* Cek Data */
      $cek = $this->Db_select->select_where('tb_user', ['nip' => $value['A']]);
      if ($cek) {
        if ($value['E']) {
          $atasan = $this->Db_select->select_where('tb_user', ['nip' => $value['E']]);

          $data['id_parent'] = $atasan->user_id;
          $where['user_id'] = $cek->user_id;
          $this->Db_dml->update('tb_user', $data, $where);
        }
      }
    }
  }

  public function umb()
  {
    $getData = $this->Db_select->query_all('select a.* from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = 14');

    foreach ($getData as $value) {
      $update['is_aktif'] = 1;
      $where['user_id'] = $value->user_id;

      $this->Db_dml->update('tb_user', $update, $where);
    }

  }
}