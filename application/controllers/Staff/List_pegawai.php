<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit', '128M');
/**
* 
*/
class List_pegawai extends CI_Controller
{ 
  function __construct()
  {
    parent::__construct();
    $this->load->library('Ceksession');
    $this->ceksession->login();
  }

  public function index()
  {   
    $sess = $this->session->userdata('user');
    if ($sess['akses'] !="staff") {
      redirect(base_url());exit();
    }

    $user=$sess['id_user'];
    $data_user=$this->Db_select->query('select * from tb_user where user_id = '.$user.'');
    $unit = $data_user->id_unit;
      
    $pg=$this->DB_super_admin->select_absensi_pegawai3($unit);

    $data['pegawai']='';

          foreach ($pg as $key => $value) {
                if ($value->foto_user==null||$value->foto_user=='') {
                  $value->foto_user= "default_photo.jpg";
              }

              if ($value->waktu_datang==null||$value->waktu_datang=='') {
                $datang = "-";
                $ket="<span class='badge badge-danger'>Belum Datang</span>";
              }else{
                $datang = $value->waktu_datang;
                if ($datang > "08:30") {
                  $ket="<span class='badge badge-warning text-white'>Kesiangan</span>";
                }else {
                  $ket="<span class='badge badge-success'>Tepat Waktu</span>";
                }                    
              }
              $data_unit=$this->Db_select->query('select * from tb_unit where id_unit = '.$value->id_unit.'');
              $unit = $data_unit->nama_unit;
              $data_jabatan=$this->Db_select->query('select * from tb_jabatan where id_jabatan = '.$value->jabatan.'');
              $jabatan = $data_jabatan->nama_jabatan;
              $data['pegawai'].=' 
                <tr>
                <td>'.$value->nip.'</td>
                <td>'.ucwords($value->nama_user).'</td>
                <td>'.$jabatan.'</td>
                <td>'.$unit.'</td>
                <td>'.$datang.'</td>
                <td>'.$ket.'</td>
                </tr>';

          }
        
    $this->load->view('Staff/header', $data);
    $this->load->view('Staff/list_pegawai', $data);
    $this->load->view('Staff/footer');
  }
}