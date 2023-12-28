<?php defined('BASEPATH') OR exit('No direct script access allowed');
class maps extends CI_Controller
{


	function __construct()
	{
		parent::__construct();
		$this->load->library('ceksession');
		$this->ceksession->login();
	}
	public function index()
	{
		$sess = $this->session->userdata('user');
    if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama" && $sess['role_access'] != '1') {
      redirect(base_url());exit();
    }
        
    $sess = $this->session->userdata('user');
    $id_channel = $sess['id_channel'];

    $dataMaps = $this->Db_select->query_all('select a.user_id, a.is_aktif, a.nama_user, a.foto_user, a.jenis_kelamin, b.waktu_datang,b.waktu_pulang,b.status_absensi, d.lat, d.lng, d.created_history_absensi, a.nip from tb_user a join tb_absensi b on a.user_id = b.user_id join tb_history_absensi d on b.id_absensi = d.id_absensi join tb_unit e on a.id_unit = e.id_unit where a.is_aktif = 1 and b.id_absensi = (select max(c.id_absensi) from tb_absensi c where c.user_id = a.user_id) and d.id_history_absensi = (select max(e.id_history_absensi) from tb_history_absensi e where b.id_absensi = e.id_absensi) and id_channel = '.$id_channel.' order by b.created_absensi desc');
        
    $data['map']="[";
    foreach ($dataMaps as $key => $value) {
      if ($value->status_absensi=="Terlambat") {
        $id_kategori = 2;
      }
      if ($value->status_absensi=="Tepat Waktu") {
        $id_kategori = 1;
      }
      if ($value->foto_user == "" || $value->foto_user == null) {
        $value->foto_user = "default_photo.jpg";
      }else{
        $value->foto_user = $value->foto_user;
      }
      $path = realpath(APPPATH . '..assets/images/member-photos/'.$value->foto_user);
      if (!file_exists($path)) {
        $value->foto_user = "default_photo.jpg";
      }
      
      $data['map'].= "{id:".$value->user_id.", kategori:".$id_kategori.", foto_user:'".$value->foto_user."', nama_user:'".$value->nama_user."', jenis_kelamin:'".$value->jenis_kelamin."',waktu_datang:'".$value->waktu_datang."',waktu_pulang:'".$value->waktu_pulang."',lat:".$value->lat.", lng:".$value->lng."},";
    }
    $data['map'] .= "]";

    $menu['main'] = 'map';
    $menu['child'] = '';
    $data['menu'] = $menu;

    $this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/maps');
		$this->load->view('Administrator/footer');
  }
    
  public function info_window($user_id)
  {
    /* get data */
    $getData = $this->Db_select->query('select a.user_id, a.is_aktif, a.nama_user, a.foto_user, a.jenis_kelamin, b.waktu_datang,b.waktu_pulang,b.status_absensi, d.lat, d.lng, d.created_history_absensi, a.nip from tb_user a join tb_absensi b on a.user_id = b.user_id join tb_history_absensi d on b.id_absensi = d.id_absensi join tb_unit e on a.id_unit = e.id_unit where a.is_aktif = 1 and b.id_absensi = (select max(c.id_absensi) from tb_absensi c where c.user_id = a.user_id) and d.id_history_absensi = (select max(e.id_history_absensi) from tb_history_absensi e where b.id_absensi = e.id_absensi) and a.user_id = "'.$user_id.'" order by b.created_absensi desc');

    if ($getData) {
      if ($getData->waktu_datang == "" || $getData->waktu_datang == null) {
        $getData->waktu_datang = "-";
        $hari = "-";
      } else {
        $getData->waktu_datang = date("H:i", strtotime($getData->waktu_datang));
        $hari = date("d/m/Y", strtotime($getData->waktu_datang));
      }

      if ($getData->waktu_pulang == "" || $getData->waktu_pulang == null) {
        $getData->waktu_pulang = "-";
      } else {
        $getData->waktu_pulang = date("H:i", strtotime($getData->waktu_pulang));
      }

      echo "<div style='margin:20px 0px 0px 20px;'>
        <div style='margin-top:-10px; margin-bottom:10px;'>
          <i class='glyphicon glyphicon-user'></i> ".$getData->nama_user."<br>
          <p>".$hari."</p>
          <small class='text-muted'>Jam Masuk : ".$getData->waktu_datang."</small><br>
          <small class='text-muted'>Jam Keluar : ".$getData->waktu_pulang."</small>
        </div>
      </div>";
    } else {
      echo "<div style='margin:20px 0px 0px 20px;'>
        <div style='margin-top:-10px; margin-bottom:10px;'>
          hgaha<br><small class='text-muted'>
          <small class='text-muted'><i class='glyphicon glyphicon-user'></i> Ragnar</small>
          <small class='text-muted'>Media</small>
        </div>
      </div>";
    }
  }
}