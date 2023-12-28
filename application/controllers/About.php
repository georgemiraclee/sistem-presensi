<?php defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {
    function __construct()
    {
        parent::__construct();
    }

    public function termandcondition()
    {
        $this->load->view('tnc');
    }

    public function kebijakan_privasi()
	{
		$this->load->view('kebijakan_privasi');
	}  
}
