<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Liem extends CI_Controller {
		public function __construct(){
		parent::__construct();
		$this->load->model('DB_super_admin');
		$this->load->helper(array('form', 'url','download'));
	}

	public function index()
	{
		$this->load->view('liem');
	}

}

