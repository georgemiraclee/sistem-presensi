<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class ceksession {
    protected $CI;

    // We'll use a constructor, as you can't directly call a function
    // from a property definition.
    public function __construct()
    {
            // Assign the CodeIgniter super-object
            $this->CI =& get_instance();
            $this->CI->load->library('session');
    }

    public function login()
    {
        if (!$this->CI->session->userdata('user')) {
        	redirect();
        }
    }
}