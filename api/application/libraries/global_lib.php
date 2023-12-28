<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class global_lib extends CI_Controller {
	public $status = false;
	public $message = 'Mohon login kembali';
	public $data = NULL;
  
  public function __construct()
  {
    parent::__construct();
  }

  public function authentication()
  {
    if ($this->input->post('token')) {
      $user = $this->db_select->query('select *from tb_user where nip = "'.$this->input->post('nip').'" and token_user = "'.$this->input->post('token').'"');

      if ($user) {
        $this->status = true;
        $this->message = '';
        $this->data = $user;
      } else {
        $this->get_error();
      }
    }else{
      $this->get_error();
    }
  }

  public function getDataUser($nip)
  {
    $getUser = $this->db_select->query('select a.*, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$nip.'"');

    if ($getUser) {
      return $getUser;
    }else{
      return false;
    }
  }

  public function new_authentication()
  {
    if (is_numeric($this->input->get('nip'))) {
      $where['nip'] = $this->input->get('nip');
      $where['token_user'] = $this->input->get('token');
      $user = $this->db_select->query('select *from tb_user where nip = "'.$this->input->get('nip').'" and token_user = "'.$this->input->get('token').'"');

      if ($user) {
        $this->status = true;
        $this->message = '';
        $this->data = $user;
      } else {
        $this->get_error();
      }
    }else{
      $this->get_error();
    }
  }

  public function get_error()
  {
    $result = array(
      'status' => $this->status,
      'message' => $this->message,
      'data' => $this->data
    );
    echo json_encode($result);die();
  }

  public function input($arr)
  {
    if (!is_array($this->input->post())) {
      $message = implode(', ', $arr) . ' tidak boleh kosong';
      $this->status = false;
      $this->message = $message;
      $this->data = null;
      $this->get_error();
    }
    $post = array_keys(array_filter($this->input->post()));
    $combine = array_intersect($arr, $post);
    if ($combine !== $arr) {
      $message = implode(', ', array_diff($arr, $combine)) . ' tidak boleh kosong';
      $this->status = false;
      $this->message = $message;
      $this->data = null;
      $this->get_error();
    }
    return true;
  }

  public function new_input($arr)
  {
    if (!is_array($this->input->get())) {
      $message = implode(', ', $arr) . ' tidak boleh kosong';
      $this->status = false;
      $this->message = $message;
      $this->data = null;
      $this->get_error();
    }
    $post = array_keys(array_filter($this->input->get()));
    $combine = array_intersect($arr, $post);
    if ($combine !== $arr) {
      $message = implode(', ', array_diff($arr, $combine)) . ' tidak boleh kosong';
      $this->status = false;
      $this->message = $message;
      $this->data = null;
      $this->get_error();
    }
    return true;
  }

  public function sendFCMAll($title, $message)
  {
    $getUser = $this->db_select->select_all_where('tb_user','is_admin = 0');
    if ($getUser) {
      foreach ($getUser as $key => $value) {
        if (!is_null($value->reg_id) && !empty($value->reg_id)) {
          $fcmMsg = array(
            'body' => $message,
            'title' => $title,
            'sound' => "default",
            'color' => "#203E78" 
          );
          $fcmFields = array(
            'to' => $value->reg_id,
            'priority' => 'high',
            'notification' => $fcmMsg
          );
          $headers = array(
            'Authorization: key=' . 'AAAAyFOofvM:APA91bHchv2OBeGftIN0_8ZRpqvyJp37gEQPbpMi2XHLdpGIwiEqDQuvEhu1UkgQDYvcyRFikY6oR5iJ9v4VNXymRnZRq7Im0OPh2rvsdGahACNZrUp3QAkUabbI6XfROPeoHoLZ0PR9',
            'Content-Type: application/json'
          );

          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
          $result = curl_exec($ch );
          curl_close( $ch );
        }
      }
    }
  }

  public function sendFCMAll2($title, $message, $id_channel)
  {
    $getUser = $this->db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where is_admin = 0 and id_channel = ' . $id_channel);
    if ($getUser) {
      foreach ($getUser as $value) {
        if (!is_null($value->reg_id) && !empty($value->reg_id)) {
          $fcmMsg = array(
            'body' => $message,
            'title' => $title,
            'sound' => "default",
            'color' => "#203E78" 
          );
          $fcmFields = array(
            'to' => $value->reg_id,
            'priority' => 'high',
            'notification' => $fcmMsg
          );
          $headers = array(
            'Authorization: key=' . 'AAAAyFOofvM:APA91bHchv2OBeGftIN0_8ZRpqvyJp37gEQPbpMi2XHLdpGIwiEqDQuvEhu1UkgQDYvcyRFikY6oR5iJ9v4VNXymRnZRq7Im0OPh2rvsdGahACNZrUp3QAkUabbI6XfROPeoHoLZ0PR9',
            'Content-Type: application/json'
          );

          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
          $result = curl_exec($ch );
          curl_close( $ch );
        }
      }
    }
  }

  public function sendFCMByUser($title, $message, $user_id)
  {
    $getUser = $this->db_select->select_where('tb_user', ['user_id' => $user_id]);
    if ($getUser) {
      $fcmMsg = array(
        'body' => $message,
        'title' => $title,
        'color' => "#203E78",
        'is_background' => true
      );
      $fcmFields = array(
        'to' => $getUser->reg_id,
        'priority' => 'high',
        'notification' => $fcmMsg
      );
      $headers = array(
        'Authorization: key=' . 'AAAAyFOofvM:APA91bHchv2OBeGftIN0_8ZRpqvyJp37gEQPbpMi2XHLdpGIwiEqDQuvEhu1UkgQDYvcyRFikY6oR5iJ9v4VNXymRnZRq7Im0OPh2rvsdGahACNZrUp3QAkUabbI6XfROPeoHoLZ0PR9',
        'Content-Type: application/json'
      );
  
      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
      curl_setopt( $ch,CURLOPT_POST, true );
      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
      curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
      curl_exec($ch);
      curl_close( $ch );
    }
  }

  public function send_notification2($jenis, $id_channel, $message, $id)
  {
    $insert_batch = array();
    $getUser = $this->CI->Db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_admin = 0 and b.id_channel = "'.$id_channel.'"');

    foreach ($getUser as $key => $value) {
      $insert['user_id'] = $value->user_id;
      $insert['jenis_notifikasi'] = $jenis;
      $insert['id'] = $id;
      $insert['keterangan'] = $message;

      array_push($insert_batch, $insert);
    }
    $this->CI->Db_dml->insert_batch('tb_notifikasi', $insert_batch);
  }

  public function getChannel($nip)
  {
    $getUser = $this->db_select->query('select b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$nip.'"');

    if ($getUser) {
      return $getUser->id_channel;
    }
  }

  public function getLevel($id)
  {
    $getUser = $this->db_select->query('select count(*) total from tb_user where id_parent = "'.$id.'"');

    if ($getUser) {
      return true;
    }

    return false;
  }

  public function sendMail($data)
  {
    $body = $this->load->view('template_email', $data, TRUE);
    $this->email->from('hello@pressensi.com', 'Info Pressensi');
    $this->email->to($data->email_user);
    $this->email->subject('Pressensi App Password Reset');
    $this->email->message($body);
    
    if ($this->email->send()) {
      return true;
    }else{
      echo json_encode($this->email->print_debugger()); exit();
      return false;
    }
  }
}