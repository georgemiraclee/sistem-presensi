<?php defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class jabatan extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('ceksession');
		$this->ceksession->login();
	}

	public function index()
	{
		$user = $this->session->userdata('user');
		if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama") {
			redirect(base_url());
			exit();
		}
    
    $data['list'] = $this->Db_select->query_all("SELECT id_jabatan, nama_jabatan, is_aktif from tb_jabatan WHERE id_channel = ? AND is_deleted = ?", [$user['id_channel'], 0]);
		$menu['main'] = 'pengaturan';
		$menu['child'] = 'pengaturan_jabatan';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/jabatan');
		$this->load->view('Administrator/footer');
	}

	public function detail($id)
	{
		$getData = $this->Db_select->select_where('tb_jabatan', ['id_jabatan' => $id], ['id_jabatan', 'nama_jabatan']);

		if ($getData) {
			$result['status'] = true;
			$result['message'] = 'Success';
			$result['data'] = $getData;
		} else {
			$result['status'] = false;
			$result['message'] = 'Data jabatan tidak ditemukan';
			$result['data'] = null;
		}

		echo json_encode($result);
	}

	public function insert()
	{
		$user = $this->session->userdata('user');
		$id_channel = $user['id_channel'];
		$insert = array();

		if ($this->input->post('nama_jabatan', true)) {
			$insert['nama_jabatan'] = $this->input->post('nama_jabatan', true);
			$insert['id_channel'] = $id_channel;
		}

		if ($this->input->post('batasan_lokasi', true)) {
			$insert['batasan_lokasi'] = $this->input->post('batasan_lokasi', true);
		}

		if (count($insert) > 0) {
			$insertData = $this->Db_dml->insert('tb_jabatan', $insert);

			if ($insertData) {
				$result['status'] = true;
				$result['message'] = 'Data berhasil disimpan.';
				$result['data'] = array();
			} else {
				$result['status'] = false;
				$result['message'] = 'Data gagal disimpan.';
				$result['data'] = array();
			}
		} else {
			$result['status'] = false;
			$result['message'] = 'Data gagal disimpan.';
			$result['data'] = array();
		}

		echo json_encode($result);
	}
	public function delete()
	{
		$where['id_jabatan'] = $this->input->post('id_jabatan', true);
		$delete = $this->Db_dml->update('tb_jabatan', ['is_deleted' => 1], $where);

		if ($delete == 1) {
			$result['status'] = true;
			$result['message'] = 'Data berhasil disimpan.';
			$result['data'] = array();
		} else {
			$result['status'] = false;
			$result['message'] = 'Data gagal disimpan.';
			$result['data'] = array();
		}

		echo json_encode($result);
	}

	public function update()
	{
		$where['id_jabatan'] = $this->input->post('id_jabatan', true);
    $update['nama_jabatan'] = $this->input->post('nama_jabatan', true);

		$updateData = $this->Db_dml->update('tb_jabatan', $update, $where);
		if ($updateData == 0 || $updateData == 1) {
			$result['status'] = true;
			$result['message'] = 'Data berhasil disimpan.';
			$result['data'] = array();
		} else {
			$result['status'] = false;
			$result['message'] = 'Data gagal disimpan.';
			$result['data'] = array();
		}
		echo json_encode($result);
	}

	public function update_status()
	{
		$where['id_jabatan'] = $this->input->post('id_jabatan', true);
		$update['is_aktif'] = $this->input->post('is_aktif', true);
		$updateData = $this->Db_dml->update('tb_jabatan', $update, $where);
		if ($updateData) {
			$result['status'] = true;
			$result['message'] = 'Data berhasil disimpan.';
			$result['data'] = array();
		} else {
			$result['status'] = false;
			$result['message'] = 'Data gagal disimpan.';
			$result['data'] = array();
		}
		echo json_encode($result);
	}
}
