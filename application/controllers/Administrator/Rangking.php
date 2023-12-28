<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class rangking extends CI_Controller
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
    $id_channel = $sess['id_channel'];
    if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama" && $sess['role_access'] != '1') {
        redirect(base_url());exit();
    }
    $filter = array();
    $sess = $this->session->userdata('user');
    $data['skpd'] = $this->Db_select->query_all('select *from tb_unit where  id_channel = '.$id_channel.' order by nama_unit');
    $data['jabatan'] = $this->Db_select->query_all('select *from tb_jabatan where is_aktif = 1 and id_channel = '.$id_channel.' order by nama_jabatan asc');
    $queryNew = "select a.nama_user, b.nama_unit, b.id_channel, COALESCE(c.total, 0) Terlambat, COALESCE(d.total, 0) Tepat_waktu, COALESCE(e.total, 0) Tidak_hadir from tb_user a join tb_unit b on a.id_unit = b.id_unit left outer join (select user_id, count(*) total from tb_absensi where status_absensi = 'Terlambat' group by user_id) as c on a.user_id = c.user_id left outer join (select user_id, count(*) total from tb_absensi where status_absensi = 'Tepat Waktu' group by user_id) as d on a.user_id = d.user_id left outer join (select user_id, count(*) total from tb_absensi where status_absensi = 'Tidak Hadir' group by user_id) as e on a.user_id = e.user_id where b.id_channel = ".$id_channel." ";
    if ($this->input->get('skpd')) {
      $queryNew .= "and b.id_unit = ".$this->input->get('skpd')." ";
    }

    if ($this->input->get('jabatan')) {
      $filter['jabatan'] = $this->input->get('jabatan');
      $filter['jabatan'] = explode(',', $filter['jabatan']);
      $jabatan = "";
      foreach ($filter['jabatan'] as $key => $value) {
        $jabatan .= ",'$value'";
      }
      $jabatan = substr($jabatan, 1);
      $queryNew .= "and a.jabatan = ".$jabatan." ";
    }

    if ($this->input->get('jenkel')) {
      $filter['jenkel'] = $this->input->get('jenkel');
      $filter['jenkel'] = explode(',', $filter['jenkel']);
      $jenkel = "";
      foreach ($filter['jenkel'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $queryNew .= "and a.jenis_kelamin = ".$jenkel." ";
    }
    
    if ($this->input->get('status')) {
      $filter['type'] = $this->input->get('status');
      $filter['type'] = explode(',', $filter['type']);
      $type = "";
      foreach ($filter['type'] as $key => $value) {
        $type .= ",'$value'";
      }
      $type = substr($type, 1);
      $queryNew .= "and a.status_user = ".$type." ";
    }

    $dari= $this->input->get('dari');
    $sampai= $this->input->get('sampai');
    if ($dari!= null and $dari != $sampai) {
      $filter['dari'] = $dari;
      $queryNew .= 'and date(created_absensi)  >= "'.$dari.'" ';    
    }

    if ($sampai != null and $sampai != $dari) {
      $filter['sampai'] = $this->input->get('sampai');
      $queryNew .= 'and date(created_absensi) <= "'.$sampai.'" '; 
    }

    $queryNew .= ' order by COALESCE(d.total, 0) desc, COALESCE(c.total, 0) asc';
    $select_pegawai= $this->Db_select->query_all($queryNew);
    $data['rank']='';

    if ($select_pegawai) {
      foreach ($select_pegawai as $key => $value) {
        $nama=$value->nama_user;
        if (strlen($nama) > 15){
          $nama = substr($nama, 0, 12) . '...';
        }

        if ($value->Tepat_waktu == "" || $value->Tepat_waktu == null) {
          $value->Tepat_waktu = 0;
        }

        if ($value->Terlambat == "" || $value->Terlambat == null) {
          $value->Terlambat = 0;
        }

        if ($value->Tidak_hadir == "" || $value->Tidak_hadir == null) {
          $value->Tidak_hadir = 0;
        }

        if ($id_channel == 14) {
          $data['rank'] .='<div class="col-sm-6 col-md-3">
            <div class="card">
              <img src="'.base_url().'assets/images/member-photos/ava.png" class="card-img-top">
              <div class="card-body">
                <div class="caption">
                  <h4>
                    <span class="badge badge-primary">'.($key+1).'</span> <b>'.$nama.'</b>
                  </h4>
                  <p>'.$value->nama_unit.'</p>
                  <hr>
                  <p>
                    Jumlah Hadir: '.($value->Tepat_waktu+$value->Terlambat).'
                    <br>
                    Jumlah Tidak Hadir: '.$value->Tidak_hadir.'
                    <br> 
                  </p>
                </div>
              </div>
            </div>
          </div>';
        } else {
          $data['rank'] .='<div class="col-sm-6 col-md-3">
            <div class="card">
              <img src="'.base_url().'assets/images/member-photos/ava.png" class="card-img-top">
              <div class="card-body">
                <div class="caption">
                  <h4><span class="badge badge-primary">'.($key+1).'</span> <b>'.$nama.'</b></h4>
                  <hr>
                  <p>
                    Jumlah Tepat Waktu: '.$value->Tepat_waktu.'
                    <br>
                    Jumlah Terlambat: '.$value->Terlambat.'
                    <br>
                    Jumlah Tidak Hadir: '.$value->Tidak_hadir.'
                    <br> 
                  </p>
                </div>
              </div>
            </div>
          </div>';
        }
        

      }
    }else{
      $data['rank'] = "<center><h3>Belum Ada Data</h3></center>";
    }

    $data['tipe'] = $this->Db_select->query_all('select *from tb_status_user where is_aktif = 1 and id_channel='.$id_channel);
    // echo json_encode($data['rank']); exit();

    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/rangking', $data);
    $this->load->view('Administrator/footer');
  }

  public function department()
  {
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];
    $select_skpd= $this->Db_select->query_all('select b.nama_unit, b.icon_unit, COALESCE(tepat.tepat_waktu, 0) tepat_waktu, COALESCE(terlambat.terlambat, 0) terlambat, COALESCE(tidak.tidak_hadir, 0) tidak_hadir from tb_user a right join tb_unit b on a.id_unit = b.id_unit left outer join (select tb_unit.id_unit, count(*) tepat_waktu from tb_user join tb_unit on tb_user.id_unit = tb_unit.id_unit join tb_absensi on tb_user.user_id = tb_absensi.user_id where tb_absensi.status_absensi = "Tepat Waktu" group by tb_unit.id_unit) as tepat on b.id_unit = tepat.id_unit left outer join (select tb_unit.id_unit, count(*) terlambat from tb_user join tb_unit on tb_user.id_unit = tb_unit.id_unit join tb_absensi on tb_user.user_id = tb_absensi.user_id where tb_absensi.status_absensi = "Terlambat" group by tb_unit.id_unit) as terlambat on b.id_unit = terlambat.id_unit left outer join (select tb_unit.id_unit, count(*) tidak_hadir from tb_user join tb_unit on tb_user.id_unit = tb_unit.id_unit join tb_absensi on tb_user.user_id = tb_absensi.user_id where tb_absensi.status_absensi = "Tidak Hadir" group by tb_unit.id_unit) as tidak on b.id_unit = tidak.id_unit where id_channel = '.$id_channel.' group by b.id_unit order by tepat.tepat_waktu desc ');
    $data['rank']='';
    $i=0;
    foreach ($select_skpd as $key => $value) {
      if ($value->icon_unit==null||$value->icon_unit=="") {
        $ikon = ''.base_url().'assets/images/unit/avatar-03.png';
      }else{
        $ikon =''.base_url().'assets/images/unit/'.$value->icon_unit.' ';
      }
      $nama=$value->nama_unit;
      if (strlen($nama) > 15)
        $nama = substr($nama, 0, 12) . '...';

      $i++;
      if ($id_channel == 14) {
        $data['rank'].='<div class="col-sm-6 col-md-3">
          <div class="card">
            <img src="'.$ikon.'" class="card-img-top">
            <div class="card-body">
              <div class="caption">
                <h4><span class="badge bg-blue" >'.$i.'</span>   <b>'.$nama.'</b></h4>
                <hr>
                <p>
                  Jumlah Hadir: '.($value->tepat_waktu+$value->terlambat).'
                  <br>
                  Jumlah Tidak Hadir: '.$value->tidak_hadir.'
                </p>
              </div>
            </div>
          </div>
        </div>';
      } else {
        $data['rank'].='<div class="col-sm-6 col-md-3">
          <div class="card">
            <img src="'.$ikon.'" class="card-img-top">
            <div class="card-body">
              <div class="caption">
                <h4><span class="badge bg-blue" >'.$i.'</span>   <b>'.$nama.'</b></h4>
                <hr>
                <p>
                  Jumlah Tepat Waktu: '.$value->tepat_waktu.'
                  <br>
                  Jumlah Terlambat: '.$value->terlambat.'
                  <br>
                  Jumlah Tidak Hadir: '.$value->tidak_hadir.'
                </p>
              </div>
            </div>
          </div>
        </div>';
      }
      
    }
    $this->load->view('Administrator/header', $data);
    $this->load->view('Administrator/rangking_skpd', $data);
    $this->load->view('Administrator/footer');
  }
}