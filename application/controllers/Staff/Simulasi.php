<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**

* 

*/

class simulasi extends CI_Controller

{ 

    function __construct()

    {

        parent::__construct();

        $this->load->library('Ceksession');

        $this->ceksession->login();

    }



    public function index()

    {   $sess = $this->session->userdata('user');

        if ($sess['akses'] !="staff") {

        redirect(base_url());exit();

        }

         

		$this->load->view('Staff/header');

		$this->load->view('Staff/simulasi');

		$this->load->view('Staff/footer');

    }

   

}