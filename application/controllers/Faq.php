<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {
    function __construct()
    {
      parent::__construct();
    }

    public function index()
    {
      $this->load->view('faq');
    }
    public function mobile()
    {
      $this->load->view('faq');
    }
}
