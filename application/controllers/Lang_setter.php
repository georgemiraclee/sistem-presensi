<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class lang_setter extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('cookie');
	}
	public function set_to($language)
	{
		if(strtolower($language) === 'english') {
		    $lang = 'en';
	   	} else {
	    	$lang = 'in';
	   	}
	   	$cookie = array(
			'name' => 'lang_is',
			'value' => $lang,
			'expire'  => '8650',
			'prefix'  => ''
	    );
	    $this->input->set_cookie($cookie);
	   
	   	if($this->input->server('HTTP_REFERER')){
			redirect($this->input->server('HTTP_REFERER'));
	   	}
	}
}