<?php defined('BASEPATH') or exit('No direct script access allowed');
class tipe_reimburse extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('ceksession', 'global_lib'));
		$this->ceksession->login();
		$this->global_lib = new global_lib;
		$this->load->model('Db_datatable');
	}

	public function index()
	{
		$user = $this->session->userdata('user');
		$id_channel = $user['id_channel'];
		if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama" && $user['role_access'] != '1') {
			redirect(base_url());
			exit();
		}
		$data['tipe_reimburse'] = $this->Db_select->select_all_where('tb_tipe_reimburse', ['id_channel' => $id_channel], ['id_tipe_reimburse', 'nama_tipe_reimburse', 'maximum_amount']);

		$menu['main'] = 'pengaturan';
		$menu['child'] = 'pengaturan_tipe_reimburse';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/tipe_reimburse');
		$this->load->view('Administrator/footer');
	}

	function getData()
	{
		$user = $this->session->userdata('user');
		$id_channel = $user['id_channel'];
		if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama" && $user['role_access'] != '1') {
			redirect(base_url());
			exit();
		}
		$tb = 'tb_tipe_reimburse';
		$wr = 'id_channel=' . $id_channel.' and is_deleted = 0';
		$fld =  array(null, 'nama_tipe_reimburse', 'maximum_amount', 'created_at');
		$src = array('nama_tipe_reimburse', 'maximum_amount', 'created_at');
		$ordr = array('created_at' => 'desc');
		$list = $this->Db_datatable->get_datatables2($tb, $wr, $fld, $src, $ordr);

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_tipe_reimburse;
			$row[] = $field->maximum_amount;
			$row[] = '<a href="#" data-toggle="modal" data-target="#updateModal' . $field->id_tipe_reimburse . '" class="btn btn-info btn-sm"><span class="fa fa-pencil-alt"></span></a>
            	<a href="#" data-type="ajax-loader" onclick="hapus(' . $field->id_tipe_reimburse . ')" class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></a>
            ';
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Db_datatable->count_all2($tb, $wr, $fld, $src, $ordr),
			"recordsFiltered" => $this->Db_datatable->count_filtered2($tb, $wr, $fld, $src, $ordr),
			"data" => $data,
		);

		echo json_encode($output);
	}
	
	public function insert()
	{
		$user = $this->session->userdata('user');
		$id_channel = $user['id_channel'];
		$insert = array();
		if ($this->input->post('nama_tipe_reimburse', true)) {
			$insert['nama_tipe_reimburse'] = $this->input->post('nama_tipe_reimburse', true);
			$insert['maximum_amount'] = strtolower($this->input->post('maximum_amount', true));
			$insert['maximum_amount'] = str_replace('rp.', '', $insert['maximum_amount']);
			$insert['maximum_amount'] = join('', explode('.', $insert['maximum_amount']));
			$insert['required_receipt'] = "1";
			$insert['id_channel'] = $id_channel;
		}
		if (count($insert) > 0) {
			$insertData = $this->Db_dml->normal_insert('tb_tipe_reimburse', $insert);
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
	
	public function update()
	{
		$where['id_tipe_reimburse'] = $this->input->post('id_tipe_reimburse', true);
		$update = array();
		if ($this->input->post('nama_tipe_reimburse', true)) {
			$update['nama_tipe_reimburse'] = $this->input->post('nama_tipe_reimburse', true);
			$update['maximum_amount'] = strtolower($this->input->post('maximum_amount', true));
			$update['maximum_amount'] = str_replace('rp.', '', $update['maximum_amount']);
			$update['maximum_amount'] = trim(join('', explode('.', $update['maximum_amount'])));
		}
		if (count($update) > 0) {
			$updateData = $this->Db_dml->update('tb_tipe_reimburse', $update, $where);
			if ($updateData) {
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
		$where['id_tipe_reimburse'] = $this->input->post('id_tipe_reimburse', true);
		$delete = $this->Db_dml->update('tb_tipe_reimburse', ['is_deleted' => 1], $where);
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
}
