<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class trial extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
    }

    public function index()
    {
        $method = $this->input->server('REQUEST_METHOD');

        if ($method == "GET") {
            $this->index_get();
        }elseif ($method == "POST") {
            $this->index_post();
        }elseif ($method == "PUT") {
            echo 'put';
        }
    }

    public function index_get()
    {
        # code...
    }

    public function index_post()
    {
        /* generate by system */
        $nip = "";
        $password = "";
    }

    public function index_put()
    {
        # code...
    }

    public function index_delete($id)
    {
        echo $id;
    }
}