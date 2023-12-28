<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class dummy extends CI_Controller
{	
	function __construct()
	{
		parent::__construct();

		$this->load->library(array('global_lib'));
	}

  public function index()
  {
    $dataUser = $this->db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = 112244');

    if ($dataUser->id_parent) {
      $this->global_lib->sendFCMByUser('Pengajuan Baru', 'Terdapat pengajuan baru yang perlu di tindaklanjuti.', $dataUser->id_parent);
      /* push notif ke atasan */

      echo 'haha'; exit();
    }

    if ($dataUser->id_parent) {
      $parent = $this->db_select->select_where('tb_user', ['user_id' => $dataUser->id_parent]);
      echo json_encode($parent); exit();
    }
    $post = json_decode(file_get_contents('php://input'), true);

    $fcmMsg = array(
      'body' => $post['message'],
      'title' => $post['message'],
      'color' => "#203E78",
      'is_background' => true
    );
    $fcmFields = array(
      'to' => $post['reg_id'],
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
    
    echo $result;
  }

  public function pushAll()
  {
    $title = $this->input->post('title');
    $message = $this->input->post('message');
    $getAllUser = $this->db_select->query_all('select reg_id from tb_user where reg_id is not null and nip = "112255"');

    foreach ($getAllUser as $value) {
      $fcmMsg = array(
        'body' => $message,
        'title' => $title,
        'color' => "#203E78",
        'is_background' => true
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
      curl_setopt($ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
      curl_setopt($ch,CURLOPT_POST, true );
      curl_setopt($ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt($ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false );
      curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
      curl_exec($ch);
    }
  }

  public function clearJabatan()
  {
    $jabatan = $this->db_select->select_all('tb_jabatan');

    foreach ($jabatan as $value) {
      $pegawai = $this->db_select->select_where('tb_user', ['jabatan' => $value->id_jabatan]);
      if (!$pegawai) {
        $this->db_dml->delete('tb_jabatan', ['id_jabatan' => $value->id_jabatan]);
      }
    }

  }
}