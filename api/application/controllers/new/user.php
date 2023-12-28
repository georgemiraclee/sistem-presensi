<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class user extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();

		$this->load->library(array('global_lib','loghistory'));
		$this->loghistory = new loghistory();
	}

	public function checkUser()
	{
		$require = array('username');
        $this->global_lib->new_input($require);

        $username = $this->input->get('username');

        $getUser = $this->db_select->query('select *from tb_user where nip = "'.$username.'" or email_user = "'.$username.'"');

        if ($getUser) {
        	$this->result = array(
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => array(
                	"user_id" => $getUser->user_id
                )
            );
        }else{
        	$this->result = array(
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            );
        }

        $this->loghistory->createLog($this->result);
        echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}

  function obfuscate_email($email)
  {
    $em   = explode("@",$email);
    $name = implode('@', array_slice($em, 0, count($em)-1));
    $len  = floor(strlen($name)/2);

    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);   
  }

	public function sendRequest()
	{        
		$require = array('user_id');
    $this->global_lib->input($require);

    $user_id = $this->input->post('user_id');
    if (is_numeric($user_id)) {
      $getData = $this->db_select->select_where('tb_user', 'user_id = ' . $user_id);
      if ($getData) {
        $sendEmail = $this->sendEmail($getData);
        
        if ($sendEmail) {
          $this->result = array(
            'status' => true,
            'message' => 'Link reset kata sandi telah berhasil dikirim ke email '.$this->obfuscate_email($getData->email_user).'. Silahkan periksa kota masuk atau spam di email Anda.',
            'data' => null
          );
        }else{
          $this->result = array(
            'status' => false,
            'message' => 'Data gagal dikirim',
            'data' => null
          );
        }
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Data gagal dikirim',
          'data' => null
        );
      }
    } else {
      $this->result = array(
        'status' => false,
        'message' => 'Data gagal dikirim',
        'data' => null
      );
    }

    $this->loghistory->createLog($this->result);
    echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}

    public function sendEmail($user)
    {
      $body = $this->load->view('template_email', $user, TRUE);

      $this->email->from('hello@pressensi.com', 'Info Pressensi');
      $this->email->to($user->email_user);
      $this->email->subject('Pressensi App Password Reset');
      $this->email->message($body);

      if ($this->email->send()) {
          return true;
      }else{
          return false;
      }
    }

    public function sendEmailNew()
    {
      $require = array('user_id');
      $this->global_lib->input($require);

      $user_id = $this->input->post('user_id');

      $user = $this->db_select->query("select *from tb_user a join tb_channel b on a.admin_channel = b.id_channel where a.user_id = " . $user_id);

      if ($user) {
        $body = $this->load->view('template_email', $user, TRUE);

        $this->email->from('hello@pressensi.com', 'Info Pressensi');
        $this->email->to($user->email_channel);
        $this->email->subject('Pressensi App Password Reset');
        $this->email->message($body);

        if ($this->email->send()) {
          $this->result = array(
            'status' => true,
            'message' => 'Data berhasil dikirim',
            'data' => null
          );
        }else{
          $this->result = array(
            'status' => false,
            'message' => 'Data gagal dikirim',
            'data' => null
          );
        }
      }else {
        $this->result = array(
          'status' => false,
          'message' => 'Data user tidak ditemukan',
          'data' => null
        );
      }

      echo json_encode($this->result);
    }
}