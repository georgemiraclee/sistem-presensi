<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class my404 extends CI_Controller
{
		
		function __construct()
	{
			parent::__construct();
		$this->load->library('ceksession');
		$this->ceksession->login();
	}
	public function index()
	{
		// $sess = $this->session->userdata('user');
  //   	$id_channel = $sess['id_channel'];
  //       if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
	 //        redirect(base_url());exit();
  //       }
		$this->output->set_status_header('404');
		$this->load->view('my404');
	}  
	
	
}