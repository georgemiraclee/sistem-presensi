<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class reset extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	public function password($id)
	{
		$getData = $this->Db_select->query('select *from tb_user where md5(user_id) = "'.$id.'"');

		if ($getData) {
			/* aksi ketika user ditemukan */
			$data['user_id'] = $getData->user_id;
			$this->load->view("reset", $data);
		}else{
			/* aksi ketika user tidak ditemukan */
			redirect('/', 'refresh');
		}
	}

	public function send()
	{
		$where['user_id'] = $this->input->post('user_id');
		$update['password_user'] = md5($this->input->post('password'));

		$this->Db_dml->update('tb_user', $update, $where);

    $result['status'] = true;
    $result['message'] = "Password berhasil diubah";
    $result['data'] = array();

		echo json_encode($result); exit();
	}

	public function success()
	{
		$this->load->view("success_password");
	}
}