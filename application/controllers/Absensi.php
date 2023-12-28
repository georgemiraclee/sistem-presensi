<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Absensi extends CI_Controller {
		public function __construct(){
			
		parent::__construct();
		$this->load->model('DB_super_admin');
		$this->load->helper(array('form', 'url','download'));
	}
	public function index(){
		$datar['user'] = $this->session->userdata('user');
		$tanggal = date("Y/m/d");
		$data['select_absen']=$this->DB_super_admin->select_absensi_todayNew($tanggal);
		$jml = $this->DB_super_admin->count_absen($tanggal);
		$data['jumlah_hadir']=$jml->total;
		$pgw = $this->DB_super_admin->count_pegawai();
		$data['tidak_hadir']=$pgw->total - $jml->total;
		$this->load->view('landingpage',$data);
	}
	public function image()
	{
		$this->load->library('ftp');
		$config['hostname'] = 'dev.folkatech.id';
		$config['username'] = 'pressensi';
		$config['password'] = 'folkatech';
		$config['debug']        = TRUE;
		$this->ftp->connect($config);
		$list = $this->ftp->list_files('/');
		// $this->ftp->mkdir('1234', 0777);
		// echo json_encode($list); exit();
		$source = './assets/images/absensi/123456_1508727643.jpg';
		$cek = $this->ftp->upload($source, '1234/123456_1508727641.jpg', 'ascii', 0777);
		var_dump($cek);
		/*cek image face recog number terakhir itu nip*/
		// $url = 'http://dev.folkatech.id/check/1';
		// file_get_contents($url);
	}
	public function images()
	{	
		if ($_FILES['userfile']['name'] != '') {
			$this->load->library('ftp');
			$config['hostname'] = 'dev.pressensi.id';
			$config['username'] = 'pressensi';
			$config['password'] = 'folkatech';
			$config['debug']        = TRUE;
			$path = $_FILES['userfile']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$nama_v1 = date(now())*1000;
			$name = $nama_v1.".".$ext;
			$this->ftp->connect($config);
			$nip = '12';
			$cekLokasi = $this->ftp->list_files($nip);
			echo json_encode($cekLokasi); exit();
			if ($cekLokasi == [] || $cekLokasi == false) {
				$this->ftp->mkdir($nip, 0777);
			}
			$cek = $this->ftp->upload($_FILES['userfile']['tmp_name'], $nip.'/'.$name, 'ascii', 0777);
			var_dump($cek);
        }
	}
	
	public function select()
	{
		$pg=$this->DB_super_admin->select_absensi_pegawai();
		// echo json_encode($pg);exit();
		$count=1;
		
		$pegawai ='';
			foreach ($pg as $key => $value) {	
				$sekarang=date(now());
			$timestamp = strtotime($value->tanggal_lahir);
			$timestamp2 = strtotime($sekarang);
			$tgl_lhr= date("d", $timestamp);
			$tgl_skr= date("d", $sekarang);
			$bln_lhr=date("m", $timestamp);
			$bln_skr=date("m", $sekarang);
			if ($tgl_lhr==$tgl_skr && $bln_lhr==$bln_skr ) {
					$ultah='<img src="https://image.flaticon.com/icons/png/512/233/233881.png" style="width:50px; position:fixed">';
				}else{
					$ultah='';
				}	
				if ($count%4 == 1)
				    {  
				    	if ($count == 1) {
				    		$tes = "active";
				    	}else{
				    		$tes = "";
				    	}
				         $pegawai .='<div class="item '.$tes.'">
                                        <div class="row"> ';
				    }
				   
				    
				if ($value->status_absensi==null||$value->status_absensi==''||$value->status_absensi=='Tidak Hadir') {
					$datang = "-";
					$class="danger";
					$ket="BELUM DATANG";
				}
				if ($value->status_absensi=='Tepat Waktu') {
					$datang = $value->waktu_datang;
					$class="success";
					$ket="TEPAT WAKTU";
				}
				if ($value->status_absensi=='Terlambat') {
					$datang = $value->waktu_datang;
					$class="warning";
					$ket="TERLAMBAT";
				}
			
				if ($value->foto_user==null||$value->foto_user=='') {
					$value->foto_user= "default_photo.jpg";
				}
				if ($value->url_file_absensi == null || $value->url_file_absensi == '') {
					$value->url_file_absensi= "default_photo.jpg";
				}
				$nama=$value->nama_user;
				if (strlen($nama) > 25)
   				$nama = substr($nama, 0, 23) . '...';
                $pegawai .='<div class="col-sm-3">
                                                    <div class="col-item">
                                                    	'.$ultah.'
                                                        <div class="photo">
                                                            <img src="'.base_url().'assets/images/absensi/'.$value->url_file_absensi.'"  class="img-responsive"  style="width:200px; height: 200px; border-radius: 50%;"  border-radius: 50%" alt="a" />
                                                        </div>
                                                        <div class="info">
                                                            <div class="row">
                                                                <div class="price col-md-12">
                                                                    <h5 align="center">
                                                                        '.$nama.'</h5>
                                                                        
                                                                </div>
                                                            </div>
                                                            <div class="separator clear-left" >
                                                                <p class="btn-add">
                                                                    <i class="fa fa-clock-o"></i><a href="http://www.jquery2dotnet.com" class="hidden-sm">Jam Masuk</a></p>
                                                                <p class="btn-details">
                                                                    <a href="" class="hidden-sm">'.$datang.'</a></p>
                                                            </div>
                                                            <br>
                                                            <br>
                                                            <button type="button" class="btn btn-block btn-lg btn-'.$class.'">'.$ket.'</button>
                                                        </div>
                                                    </div>
                                                </div>';
		            if ($count%4 == 0)
				    {
				        $pegawai .='</div>
                                        </div>';
				    }
				    $count++;
			}
			if ($count%4 != 1) {
				$pegawai .='</div>
                                        </div>';
			}
			// <div class="col-md-12">
                                                            //    <a href="'.base_url().'Log/data/'.$value->nip.'"> <button class="btn btn-'.$class.' btn-block" style="background-color:'.$bek.';" >Log Absensi</button></a>
                                                            // </div> 
		
		$tanggal = date("Y/m/d");
		$select_absen=$this->DB_super_admin->select_absensi_today($tanggal);
		$jml = $this->DB_super_admin->count_absen($tanggal);
		$jumlah_hadir=$jml->total;
		$jml_telat = $this->DB_super_admin->count_absen_telat($tanggal);
		$jumlah_telat=$jml_telat->total;
		$pgw = $this->DB_super_admin->count_pegawai();
		$tidak_hadir=$pgw->total - $jml->total;
		$result['status'] = true;
        $result['message'] = 'Data ditemukan.';
        $result['data'] = $pegawai;
        $result['jumlah_hadir'] = $jumlah_hadir;
        $result['jumlah_telat'] = $jumlah_telat;
        $result['tidak_hadir'] = $tidak_hadir;
		echo json_encode($result); exit();
		// echo json_encode($select_absen); exit();
		if ($select_absen) {
			$result['status'] = true;
	        $result['message'] = 'Data ditemukan.';
	        $result['data'] = $select_absen;
	        $result['jumlah_hadir'] = $jumlah_hadir;
	        $result['tidak_hadir'] = $tidak_hadir;
		}else{
			$result['status'] = false;
	        $result['message'] = 'Data tidak ditemukan.';
	        $result['data'] = array();
	        $result['jumlah_hadir'] = $jumlah_hadir;
	        $result['tidak_hadir'] = $tidak_hadir;
		}
		echo json_encode($result);
	}
}
