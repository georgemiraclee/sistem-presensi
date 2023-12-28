<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Jabatan extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function getJabatan($id_channel)
	{
		//ID CHANNEL
		$sess_channel['id_channel'] = $id_channel;
		$this->session->set_userdata('id_channel',$sess_channel);
		$Newsess_channel = $this->authen();

		$data['data_jabatan'] = $this->db_select->select_all_where('tb_jabatan','id_channel = '.$Newsess_channel);
		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/data_jabatan',$data);
	}

	public function insert()
	{
		$Newsess_channel = $this->authen();
		$insert = array();

		if ($this->input->post('nama_jabatan')) {
			$insert['nama_jabatan'] = $this->input->post('nama_jabatan');
			$insert['id_channel'] = $Newsess_channel;
		}

		if (count($insert) > 0) {
			$insertData = $this->db_dml->normal_insert('tb_jabatan', $insert);

			if ($insertData) {
				$result['status'] = true;
                $result['message'] = 'Data berhasil disimpan.';
                $result['data'] = array();
			}else{
				$result['status'] = false;
                $result['message'] = 'Data gagal disimpan.';
                $result['data'] = array();
			}
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}

		echo json_encode($result);
	}

	public function update()
	{
		$Newsess_channel = $this->authen();
		$where['id_jabatan'] = $this->input->post('id_jabatan');
		$update = array();

		if ($this->input->post('nama_jabatan')) {
			$update['nama_jabatan'] = $this->input->post('nama_jabatan');
			$update['id_channel'] = $Newsess_channel;
		}

		if (count($update) > 0) {
			$updateData = $this->db_dml->update('tb_jabatan', $update, $where);

			if ($updateData) {
				$result['status'] = true;
                $result['message'] = 'Data berhasil disimpan.';
                $result['data'] = array();
			}else{
				$result['status'] = false;
                $result['message'] = 'Data gagal disimpan.';
                $result['data'] = array();
			}
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}

		echo json_encode($result);
	}

	public function authen()
	{
		$Newsess_channel = $this->session->userdata('id_channel');

		if ($Newsess_channel['id_channel'] == null || $Newsess_channel['id_channel'] == '') {
			redirect();
		}else{
			return $Newsess_channel['id_channel'];
		}
	}
}