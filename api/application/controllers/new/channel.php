<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class channel extends CI_Controller
{	
	function __construct()
	{
		parent::__construct();

		$this->load->library(array('global_lib','loghistory'));
		$this->loghistory = new loghistory();

		/* pengecekan token expired */
		$this->global_lib->authentication();
	}

  public function index()
  {
    $getUser = $this->db_select->query('select a.*, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = '.$this->input->post('nip'));

    if ($getUser) {
      $getChannel = $this->db_select->select_where('tb_channel', ['id_channel' => $getUser->id_channel]);

      if ($getChannel) {
        $data['nip'] = $getUser->nip;
        $data['nama'] = $getUser->nama_user;
        $data['nama_channel'] = $getChannel->nama_channel;
        $data['code_channel'] = $getChannel->code_channel;
        $data['package'] = $getChannel->package;
        
        $result['status'] = true;
        $result['message'] = 'Success';
        $result['data'] = $data;
      } else {
        $result['status'] = false;
        $result['message'] = 'Data channel tidak ditemukan';
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data user tidak ditemukan';
    }

    $this->loghistory->createLog($result);
    echo json_encode($result, JSON_NUMERIC_CHECK);
  }
}