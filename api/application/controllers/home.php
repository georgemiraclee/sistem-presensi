<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $data['status'] = false;
    $data['message'] = 'Access denied';
    $data['data'] = null;

    echo json_encode($data); exit();
  }
}
