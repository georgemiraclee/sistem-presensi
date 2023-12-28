<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit', '128M');
/**
* 
*/
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
    $sess = $this->session->userdata('user');
    $getChannel = $this->Db_select->select_where('tb_unit', 'id_unit = "'.$sess['id_unit'].'"');
    $id_channel = $getChannel->id_channel;
    if (!$sess['akses']) {
      redirect(base_url());exit();
    }
    $filter = array();
    $sess = $this->session->userdata('user');
    $data['skpd'] = $this->Db_select->query_all('select *from tb_unit where id_channel='.$id_channel.' order by nama_unit');
    $data['jabatan'] = $this->Db_select->query_all('select *from tb_jabatan where is_aktif = 1 and id_channel='.$id_channel.' order by nama_jabatan asc');

    $query = " ";
    if ($this->input->get('skpd')) {
      $filter['skpd'] = $this->input->get('skpd');
      $query .= " and tb_user.id_unit in(".$this->input->get('skpd').")";
    }

    if ($this->input->get('jabatan')) {
      $filter['jabatan'] = $this->input->get('jabatan');
      $filter['jabatan'] = explode(',', $filter['jabatan']);
      $jabatan = "";
      foreach ($filter['jabatan'] as $key => $value) {
        $jabatan .= ",'$value'";
      }
      $jabatan = substr($jabatan, 1);
      $query .= " and tb_user.jabatan in(".$jabatan.")";
    }

    if ($this->input->get('jenkel')) {
      $filter['jenkel'] = $this->input->get('jenkel');
      $filter['jenkel'] = explode(',', $filter['jenkel']);
      $jenkel = "";
      foreach ($filter['jenkel'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " and tb_user.jenis_kelamin in(".$jenkel.")";
    }

    if ($this->input->get('status')) {
      $filter['status'] = $this->input->get('status');
      $filter['status'] = explode(',', $filter['status']);
      $jenkel = "";
      foreach ($filter['status'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " and status_absensi in(".$jenkel.")";
    }

    if ($this->input->get('dari')) {
      $filter['dari'] = $this->input->get('dari');
      $query .= " and date(created_absensi)  >= '".$this->input->get('dari')."'";
    }

    if ($this->input->get('sampai')) {
      $filter['sampai'] = $this->input->get('sampai');
      $query .= " and date(created_absensi) <= '".$this->input->get('sampai')."' ";
    }

    if ($this->input->get('status_user')) {
      $filter['status_user'] = $this->input->get('status_user');
      $filter['status_user'] = explode(',', $filter['status_user']);
      $jenkel = "";
      foreach ($filter['status_user'] as $key => $value) {
        $jenkel .= ",'$value'";
      }
      $jenkel = substr($jenkel, 1);
      $query .= " and tb_user.is_aktif in(".$jenkel.")";
    }
    
    $this->load->view('SEKDA/header', $data);
    $this->load->view('SEKDA/data_absensi', $data);
    $this->load->view('SEKDA/footer');
  }

  public function batalkan()
  {
      $insert['id_absensi'] = $this->input->post('id_absensi');
      // echo json_encode($insert);exit();
      $selectUser = $this->Db_select->select_where('tb_absensi', $insert);
      $selectNewUser = $this->Db_select->select_where('tb_user', 'user_id = "'.$selectUser->user_id.'"');
      $sess = $this->session->userdata('user');
      if (count($insert) > 0) {
          $insertData = $this->Db_dml->normal_insert('tb_pembatalan_absensi', $insert);
          if ($insertData) {
              $this->potonganBatalAbsen($selectNewUser->user_id, $selectNewUser->saldo);
              $this->global_lib->send_notification_user($selectUser->user_id, 'pembatalan_absensi');
              // FCM
              $message = "Absen Anda Telah Dibatalkan";
              $this->global_lib->sendFCM('Pembatalan Absensi', $message, $selectUser->user_id);
              $pesan = "<div class='alert alert-success alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Absen Berhasil Dibatalkan</div>";
              $this->session->set_flashdata('pesan', $pesan);
                redirect(base_url()."Leader/data_absensi");exit();
          }else{
              $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Absen Gagal Dibatalkan</div>";
              $this->session->set_flashdata('pesan', $pesan);
                redirect(base_url()."Leader/data_absensi");exit();
          }
      }else{
          $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Gagal Disimpan</div>";
              $this->session->set_flashdata('pesan', $pesan);
                redirect(base_url()."Leader/data_absensi");exit();
      }
      echo json_encode($result);
  }

  public function potonganBatalAbsen($user_id, $saldo)
  {
      $selectPotongan = $this->Db_select->select_where('tb_potongan_batal_absensi', 'id_potongan_batal_absensi = 1');
      $pengurangan = $selectPotongan->besar_potongan*$saldo/100;
      $insert['id_potongan_batal_absensi'] = 1;
      $insert['user_id'] = $user_id;
      $insert['total_potongan'] = $pengurangan;
      $this->Db_dml->normal_insert('tb_hstry_potongan_batal_absensi', $insert);
  }

  function get_data_user($value = null)
  {
      $sess = $this->session->userdata('user');
      $getChannel = $this->Db_select->select_where('tb_unit', 'id_unit = "'.$sess['id_unit'].'"');
      $id_channel = $getChannel->id_channel;
      $namaFile = 'jadwal_default_'.$id_channel.'.txt';
      $file = 'appconfig/new/'.$namaFile;
      $filter = array();
      $sess = $this->session->userdata('user');
      $data['skpd'] = $this->Db_select->query_all('select *from tb_unit where id_channel='.$id_channel.' order by nama_unit');
      $data['jabatan'] = $this->Db_select->query_all('select *from tb_jabatan where is_aktif = 1 and id_channel='.$id_channel.' order by nama_jabatan asc');

      //filter data
      $query = " ";
      if ($this->input->get('skpd')) {
          $filter['skpd'] = $this->input->get('skpd');
          $query .= " and tb_user.id_unit in(".$this->input->get('skpd').")";
      }
      if ($this->input->get('jabatan')) {
          $filter['jabatan'] = $this->input->get('jabatan');
          $filter['jabatan'] = explode(',', $filter['jabatan']);
          $jabatan = "";
          foreach ($filter['jabatan'] as $key => $value) {
              $jabatan .= ",'$value'";
          }
          $jabatan = substr($jabatan, 1);
          $query .= " and tb_user.jabatan in(".$jabatan.")";
      }
      if ($this->input->get('jenkel')) {
          $filter['jenkel'] = $this->input->get('jenkel');
          $filter['jenkel'] = explode(',', $filter['jenkel']);
          $jenkel = "";
          foreach ($filter['jenkel'] as $key => $value) {
              $jenkel .= ",'$value'";
          }
          $jenkel = substr($jenkel, 1);
          $query .= " and tb_user.jenis_kelamin in(".$jenkel.")";
      }
      if ($this->input->get('status')) {
          $filter['status'] = $this->input->get('status');
          $filter['status'] = explode(',', $filter['status']);
          $jenkel = "";
          foreach ($filter['status'] as $key => $value) {
              $jenkel .= ",'$value'";
          }
          $jenkel = substr($jenkel, 1);
          $query .= " and status_absensi in(".$jenkel.")";
      }
      if ($this->input->get('dari')) {
          $filter['dari'] = $this->input->get('dari');
          $query .= " and date(created_absensi)  >= '".$this->input->get('dari')."'";
      }else{
      }
      if ($this->input->get('sampai')) {
          $filter['sampai'] = $this->input->get('sampai');
          $query .= " and date(created_absensi) <= '".$this->input->get('sampai')."' ";
      }
        if ($this->input->get('status_user') != null || $this->input->get('status_user') != "") {
          $filter['status_user'] = $this->input->get('status_user');
          $filter['status_user'] = explode(',', $filter['status_user']);
          $jenkel = "";
          foreach ($filter['status_user'] as $key => $value) {
              $jenkel .= ",'$value'";
          }
          $jenkel = substr($jenkel, 1);
          $query .= " and tb_user.is_aktif in(".$jenkel.")";
      }
      $st='id_parent = '.$sess['id_user'].' and id_channel='.$id_channel.''.$query;

      $list = $this->Db_datatable->get_datatables($st);
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $field) {
          $no++;
          //time  
          if ($field->waktu_istirahat==null||$field->waktu_istirahat=='') {
              $istirahat='-';
          }else{
              $istirahat= date('h:i', strtotime($field->waktu_istirahat));
          }
          if ($field->waktu_kembali==null||$field->waktu_kembali=='') {
              $kembali='-';
          }else{
              $kembali= date('h:i', strtotime($field->waktu_kembali));
          }
          if ($field->waktu_pulang==null||$field->waktu_pulang=='') {
              $pulang='-';
          }else{
              $pulang= date('h:i', strtotime($field->waktu_pulang));
          }
          //time
          //potongan
          $potongan_apel=$this->Db_select->query('select total_potongan from tb_hstry_potongan_apel where user_id = '.$field->user_id.' and date_format(created_hstry_potongan_apel, "%d-%m-%Y") = "'.date('d-m-Y', strtotime($field->created_absensi)).'"');
          if ($potongan_apel!= null) {
              $apel_potongan = $potongan_apel->total_potongan;
          }else{
              $apel_potongan="0";
          }
          $potongan_batal=$this->Db_select->query('select total_potongan from tb_hstry_potongan_batal_absensi where user_id = '.$field->user_id.' and date_format(created_hstry_potongan_batal_absensi, "%d-%m-%Y") = "'.date('d-m-Y', strtotime($field->created_absensi)).'"');
          if ($potongan_batal!= null) {
              $batal_potongan = $potongan_batal->total_potongan;
          }else{
              $batal_potongan="0";
          }
          $potongan_mabal=$this->Db_select->query('select total_potongan from tb_hstry_potongan_keluar_jamkerja where user_id = '.$field->user_id.' and date_format(created_hstry_meninggalkan_kantor, "%d-%m-%Y") = "'.date('d-m-Y', strtotime($field->created_absensi)).'"');
          if ($potongan_mabal!= null) {
              $mabal_potongan = $potongan_mabal->total_potongan;
          }else{
              $mabal_potongan="0";
          }
          $potongan_telat=$this->Db_select->query('select total_potongan from tb_hstry_potongan_keterlambatan where user_id = '.$field->user_id.' and date_format(created_hstry_keterlambatan, "%d-%m-%Y") = "'.date('d-m-Y', strtotime($field->created_absensi)).'"');
          if ($potongan_telat!= null) {
              $telat_potongan = $potongan_telat->total_potongan;
          }else{
              $telat_potongan="0";
          }
          $potongan_alpa=$this->Db_select->query('select total_potongan from tb_hstry_potongan_mangkir where user_id = '.$field->user_id.' and date_format(created_hstry_mangkir, "%d-%m-%Y") = "'.date('d-m-Y', strtotime($field->created_absensi)).'"');
          if ($potongan_alpa!= null) {
              $alpa_potongan = $potongan_alpa->total_potongan;
          }else{
              $alpa_potongan="0";
          }
          $all_potongan = $apel_potongan + $batal_potongan + $mabal_potongan + $telat_potongan + $alpa_potongan;
          //potongan   
          //batal
          $selectBatal = $this->Db_select->select_where('tb_pembatalan_absensi','id_absensi = '.$field->id_absensi.'');
          if ($selectBatal) {
              $batal2 = "Dibatalkan";
          }else{
              if ($field->status_absensi != "Tidak Hadir") {
                  $batal2 = '<a href="#"  id="hapus'.$no.'" data-type="ajax-loader" onclick="hapus('.$field->id_absensi.')" ><button class="btn btn-warning btn-sm text-white" type="button">Batalkan</button></a>';
              }else{
                  $batal2 = "-";
              }
          }
          //batal    
          
          //keterangan
          $jadwal = json_decode(file_get_contents($file))->jam_kerja;
          $day = strtolower(date("l"));
          $jadwalNew = date_create($jadwal->$day->from);
          $jam_skrg = date_create(date("H:i", strtotime($field->waktu_datang)));
          $diff  = date_diff($jam_skrg, $jadwalNew);
          $keteranganTerlambat = "";
          if ($field->status_absensi == "Terlambat") {
              if ($diff->h != 0) {
                  $keteranganTerlambat .= $diff->h." Jam ";
              }
              if ($diff->i != 0) {
                  $keteranganTerlambat .= $diff->i." Menit ";
              }
          }

          //keterangan
          $row = array();
          $row[] = $no;
          $row[] = $field->nip;
          $row[] = $field->nama_user;
          $row[] = date('d-m-Y', strtotime($field->created_absensi));
          $row[] = date('h:i', strtotime($field->waktu_datang));
          $row[] = $istirahat;
          $row[] = $kembali;
          $row[] = $pulang;
          $row[] = $field->status_absensi." ". $keteranganTerlambat;
          $row[] = $batal2;
          $row[] = '<a href="'.base_url().'Leader/data_absensi/detail/'.$field->id_absensi.'" class="btn btn-secondary btn-sm"><span class="fa fa-search"></span></a>';
          $data[] = $row;
      }
      $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->Db_datatable->count_all($st),
          "recordsFiltered" => $this->Db_datatable->count_filtered( $st, ['COUNT(*) total'])->total,
          "data" => $data,
      );
      //output dalam format JSON
      echo json_encode($output);
  }
  
  public function detail($id_absensi){
    $query= $this->Db_select->query('select f.nama_user, a.created_absensi, a.waktu_datang, a.waktu_istirahat, a.waktu_kembali, a.waktu_pulang, a.status_absensi, a.url_file_absensi, b.lat, b.lng, e.nama_lokasi, c.ssid_jaringan, c.mac_address_jaringan from tb_absensi a left join tb_history_absensi b on a.id_absensi = b.id_absensi left join tb_jaringan c on b.id_jaringan = c.id_jaringan left join tb_lokasi e on b.id_lokasi =e.id_lokasi left join tb_user f on a.user_id = f.user_id  where a.id_absensi = "'.$id_absensi.'" ');

    if ($query->waktu_istirahat = null || $query->waktu_istirahat == '') {
        $query->waktu_istirahat= "-";
    }else{
        $query->waktu_istirahat = date("H:i", strtotime($query->waktu_istirahat));
    }
    if ($query->waktu_kembali = null || $query->waktu_kembali == '') {
        $query->waktu_kembali= "-";
    } else {
        $query->waktu_kembali = date("H:i", strtotime($query->waktu_kembali));
    }
    if ($query->waktu_pulang = null || $query->waktu_pulang == '') {
        $query->waktu_pulang= "-";
    } else {
        $query->waktu_pulang = date("H:i", strtotime($query->waktu_pulang));
    }

    if ($query->url_file_absensi == "" || $query->url_file_absensi == null) {
        $query->url_file_absensi = base_url()."assets/images/absensi/default_photo.jpg";
    }else{
        $path = realpath('../assets/images/absensi/' . $query->url_file_absensi);

        if (file_exists($path)) {
            $query->url_file_absensi = base_url().'assets/images/absensi/'.$query->url_file_absensi;
        }else{
            $query->url_file_absensi = base_url() . 'assets/images/absensi/default_photo.jpg';
        }
    }

    if ($query->status_absensi == "Terlambat") {
        $query->status_absensi = '<span class="badge badge-warning text-white">Terlambat</span>';
    } elseif ($query->status_absensi == "Tepat Waktu") {
        $query->status_absensi = '<span class="badge badge-success">Tepat Waktu</span>';
    } else{
        $query->status_absensi = '<span class="badge badge-danger">Tidak Hadir</span>';
    }

    $data['nama']= $query->nama_user;
    $data['tanggal']= date("Y-m-d", strtotime($query->created_absensi));
    $data['datang']= date("H:i", strtotime($query->waktu_datang));
    $data['istirahat']= $query->waktu_istirahat;
    $data['kembali']= $query->waktu_kembali;
    $data['pulang']= $query->waktu_pulang;
    $data['status']= $query->status_absensi;
    $data['foto']= $query->url_file_absensi;
    $data['lat']= $query->lat;
    $data['lng']= $query->lng;
    $data['lokasi']= $query->nama_lokasi;
    $data['ssid']= $query->ssid_jaringan;
    $data['mac']= $query->mac_address_jaringan;

    $this->load->view('SEKDA/header');
    $this->load->view('SEKDA/detail_absensi' , $data);
    $this->load->view('SEKDA/footer');
  }
}