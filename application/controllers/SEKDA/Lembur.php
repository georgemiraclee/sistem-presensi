<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class lembur extends CI_Controller
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
        if ($sess['akses'] !="sekda") {
        redirect(base_url());exit();
        }
		$parrent=$sess['id_user'];

		$data['data_cuti'] = $this->Db_select->query_all('select *from tb_user a join tb_lembur b on a.user_id = b.user_id  where id_parent = '.$parrent.'');


		$data_pegawai = $this->Db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit  where id_parent = '.$parrent.'');

		$data['list_pegawai'] = "";
		foreach ($data_pegawai as $key => $value) {
			$data['list_pegawai'] .= "<option value='".$value->user_id."'>".ucwords($value->nama_user)." (".$value->nip.")</option>";
		}

		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/lembur');
		$this->load->view('SEKDA/footer');
	}

	public function select()
	{
		$where['user_id'] = $this->input->post('id');

        $data = $this->Db_select->query('select *from tb_user a join tb_lembur b on a.user_id = b.user_id where b.user_id = '.$where['user_id'].'');

        if ($data) {
            $result['status'] = true;
            $result['message'] = 'Data ditemukan.';
            $result['data'] = $data;
        } else {
            $result['status'] = false;
            $result['message'] = 'Data tidak ditemukan.';
            $result['data'] = array();
        }

        echo json_encode($result);
	}

	public function insert()
	{
		$data['user_id'] = $this->input->post('user_id');
		$data['tanggal_lembur'] = date("Y-m-d", strtotime($this->input->post('tanggal_lembur')));
		$data['lama_lembur'] = $this->input->post('lama_lembur');

		$data_pengajuan=$this->Db_select->query('select *from tb_pengajuan where user_id = "'.$data['user_id'].'" and "'.$data['tanggal_lembur'].'" >= tanggal_awal_pengajuan and "'.$data['tanggal_lembur'].'" <=tanggal_akhir_pengajuan ');
		if (!$data_pengajuan) {
			$insert = $this->Db_dml->normal_insert('tb_lembur', $data);
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan, pegawai sedang dalam masa data_cuti.';
            $result['data'] = array();
			echo json_encode($result); exit();
		}

		if ($insert == 1) {
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
		$where['user_id'] = $this->input->post('user_id');
		$data['tanggal_lembur'] = $this->input->post('tanggal_lembur');
		$data['lama_lembur'] = $this->input->post('lama_lembur');

		$updateData = $this->Db_dml->update('tb_lembur', $data, $where);
		if ($updateData == 1) {
			$result['status'] = true;
            $result['message'] = 'Data ditemukan.';
            $result['data'] = $data;
        } else {
            $result['status'] = false;
            $result['message'] = 'Data tidak ditemukan.';
            $result['data'] = array();
        }

        echo json_encode($result);
	}

	public function delete()
	{
		$where['user_id'] = $this->input->post('user_id');

		$delete = $this->Db_dml->delete('tb_lembur', $where);

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
}