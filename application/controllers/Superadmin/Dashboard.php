<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/

class Dashboard extends CI_Controller
{ 
  function __construct()
  {
    parent::__construct();
    $this->load->library('Ceksession');
    $this->ceksession->login();
  }

  public function index()
  {
    $data['channel_active'] = $this->Db_select->count_all_where('tb_channel', ['status' => 1]);
    $data['channel_inactive'] = $this->Db_select->count_all_where('tb_channel', ['status' => 0]);
    $data['all_user'] = $this->Db_select->count_all_where('tb_user', ['is_admin' => 0, 'is_superadmin' => 0]);
    $this->load->view('Superadmin/header', $data);
    $this->load->view('Superadmin/dashboard');
    $this->load->view('Superadmin/footer');
  }
}