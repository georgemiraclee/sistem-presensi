<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi extends CI_Controller {
		public function __construct(){
		parent::__construct();
		$this->load->model('DB_super_admin');
		$this->load->helper(array('form', 'url','download'));
	}


	public function index(){
		$select_user=$this->DB_super_admin->select_user_login();
		$data['lokasi']="";
			foreach ($select_user as $key => $value) {
				$id_absen=$value->id_absensi;
				$nama=$value->nama_user;
				$select_lokasi =$this->DB_super_admin->select_lokasi_terakhir($id_absen);
					foreach ($select_lokasi as $row) {
						$lat=$row->lat;
						$lng=$row->lng;
					}	
				$data['lokasi'].= "['".$nama."',".$lat.",".$lng."],";
			}	
		// echo json_encode($data['lokasi']);exit();

				//echo json_encode($select_user);exit();
		$this->load->view('lokasi',$data);
	}
	
	
}
