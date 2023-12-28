<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class apel extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('ceksession');
		$this->ceksession->login();
	}

	public function index()
	{
		$sess = $this->session->userdata('user');

		$data['data_apel'] = $this->Db_select->select_all('tb_apel');
		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/apel');
		$this->load->view('Administrator/footer');
	}

	public function insert()
	{
		$sess = $this->session->userdata('user');
		$insert = array();

		if ($this->input->post('nama_jabatan')) {
			$insert['nama_jabatan'] = $this->input->post('nama_jabatan');
		}

		if (count($insert) > 0) {
			$insertData = $this->Db_dml->normal_insert('tb_jabatan', $insert);

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

	public function delete()
	{
		$where['id_jabatan'] = $this->input->post('id_jabatan');

		$delete = $this->Db_dml->delete('tb_jabatan', $where);

		if ($delete == 1) {
			$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}
		echo json_encode($result);
	}

	public function update()
	{
		$sess = $this->session->userdata('user');
		$where['id_jabatan'] = $this->input->post('id_jabatan');
		$update = array();

		if ($this->input->post('nama_jabatan')) {
			$update['nama_jabatan'] = $this->input->post('nama_jabatan');
		}

		if (count($update) > 0) {
			$updateData = $this->Db_dml->update('tb_jabatan', $update, $where);

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

	public function update_status()
	{
		$sess = $this->session->userdata('user');
		$where['id_jabatan'] = $this->input->post('id_jabatan');

		$update['is_aktif'] = $this->input->post('is_aktif');

		$updateData = $this->Db_dml->update('tb_jabatan', $update, $where);

		if ($updateData) {
			$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}

		echo json_encode($result);
	}
}