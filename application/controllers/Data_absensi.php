<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_absensi extends CI_Controller {
		public function __construct(){
		parent::__construct();
		$this->load->model('DB_super_admin');
		$this->load->helper(array('form', 'url','download'));
	}


	public function getAbsensi($id_channel){
		//ID CHANNEL
		$sess_channel['id_channel'] = $id_channel;
		$this->session->set_userdata('id_channel',$sess_channel);
		$Newsess_channel = $this->authen();

		$data['select_admin_unit'] = $this->db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = '.$Newsess_channel.'');

		$this->load->view('Super_admin/header');
		$this->load->view('Super_admin/data_absensi',$data);
	}
	
	public function authen()
	{
		$Newsess_channel = $this->session->userdata('id_channel');

		if ($Newsess_channel['id_channel'] == null || $Newsess_channel['id_channel'] == '') {
			redirect();
		}else{
			return $Newsess_channel['id_channel'];
		}
	}
}
