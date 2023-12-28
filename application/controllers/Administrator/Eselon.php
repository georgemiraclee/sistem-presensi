<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class eselon extends CI_Controller
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
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
        redirect(base_url());exit();
        }

		$data['data_eselon'] = $this->Db_select->query_all('select *from tb_eselon a join tb_struktur_organisasi b on a.id_struktur = b.id_struktur order by a.nama_eselon');
		$data['data_struktur'] = $this->Db_select->select_all('tb_struktur_organisasi');

		foreach ($data['data_eselon'] as $key => $value) {
			$selectUser = $this->Db_select->select_where('tb_user','eselon = '.$value->id_eselon);
			if ($selectUser) {
				$value->delete = false;
			}else{
				$value->delete = true;
			}
		}

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/eselon');
		$this->load->view('Administrator/footer');
	}

	public function insert()
	{
		$sess = $this->session->userdata('user');
		$insert['nama_eselon'] = $this->input->post('nama_eselon');
		$insert['id_struktur'] = $this->input->post('id_struktur');

		if (count($insert) > 0) {
			$insertData = $this->Db_dml->normal_insert('tb_eselon', $insert);

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
		$where['id_eselon'] = $this->input->post('id_eselon');

		$delete = $this->Db_dml->delete('tb_eselon', $where);

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
		$where['id_eselon'] = $this->input->post('id_eselon');
		$update['nama_eselon'] = $this->input->post('nama_eselon');
		$update['id_struktur'] = $this->input->post('id_struktur');

		if (count($update) > 0) {
			$updateData = $this->Db_dml->update('tb_eselon', $update, $where);

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