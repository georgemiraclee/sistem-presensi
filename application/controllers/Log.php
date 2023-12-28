<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends CI_Controller {
		public function __construct(){
		parent::__construct();
		$this->load->model('DB_super_admin');
		$this->load->helper(array('form', 'url','download'));
	}


	public function data($id){
		$selectUser = $this->Db_select->select_where('tb_user', 'nip = "'.$id.'"');
		$select=$this->DB_super_admin->select_detail_pegawai($selectUser->user_id);
		foreach ($select as $key => $value) {
			if ($value->foto_user == null || $value->foto_user == '') {
				$value->foto_user = 'default_photo.jpg';
			}
			
			$ut=$value->id_unit;
			$select2=$this->DB_super_admin->select_detail_unit($ut);
				foreach ($select2 as $key => $values) {
					$unt=$values->nama_unit;
				}

			$data['nip']=$value->nip;
			$data['nama']=$value->nama_user;
			$data['alamat']=$value->alamat_user;
			$data['email']=$value->email_user;
			$data['telepon']=$value->telp_user;
			$data['jabatan']=$value->jabatan;
			$data['unit']=$unt;
			$data['jenis_kelamin']=$value->jenis_kelamin;
			$data['foto']=$value->foto_user;
			$nip=$value->nip;
			
		}
		$data['dt']=$this->DB_super_admin->select_detail_absen($selectUser->user_id);

		$dt=$this->DB_super_admin->select_detail_absen($selectUser->user_id);
			foreach ($dt as $key => $value) {
				$id_ab =$value->id_absensi;
			}
		$select_lokasi =$this->DB_super_admin->select_lokasi($id_ab);
			$data['lokasi']="";
			foreach ($select_lokasi as $key => $value) {
				
				$data['lokasi'].= "['".$value->created_history_absensi."',".$value->lat.",".$value->lng."],";
			}

			$count_kehadiran = $this->DB_super_admin->count_kehadiran($nip);
			$data['kehadiran']=$count_kehadiran->total;
			$count_kesiangan = $this->DB_super_admin->count_kesiangan($nip);
			$data['kesiangan']=$count_kesiangan->total;
			//echo json_encode($count_kesiangan->total);exit();


		$data2['user'] = $this->session->userdata('user');
		$this->load->view('log',$data);
	}
	
	
}
