<?php defined('BASEPATH') or exit('No direct script access allowed');

class status_pegawai extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('ceksession');
		$this->load->model('Db_datatable');
		$this->ceksession->login();
	}

	public function index()
	{
		$user = $this->session->userdata('user');

		if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama") {
			redirect(base_url());
			exit();
		}
		$data['list'] = $this->Db_select->query_all("SELECT id_status_user, nama_status_user, is_default, is_aktif FROM tb_status_user WHERE id_channel = ? and is_deleted = ?", [$user['id_channel'], 0]);
		$menu['main'] = 'pengaturan';
		$menu['child'] = 'pengaturan_status_pegawai';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/status_pegawai');
		$this->load->view('Administrator/footer');
	}

	public function insert()
	{
		$user = $this->session->userdata('user');
		$id_channel = $user['id_channel'];
		$insert['nama_status_user'] = $this->input->post('nama_status_user', true);
		$insert['pemotongan_tpp'] = $this->input->post('pemotongan_tpp', true);
		$insert['id_channel'] = $id_channel;
		if (count($insert) > 0) {
			$insertData = $this->Db_dml->normal_insert('tb_status_user', $insert);
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
		$where['id_status_user'] = $this->input->post('id_status_user', true);
		$delete = $this->Db_dml->update('tb_status_user', ['is_deleted' => 1],  $where);
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
		$where['id_status_user'] = $this->input->post('id_status_user', true);
		$update['nama_status_user'] = $this->input->post('nama_status_user', true);
		$update['pemotongan_tpp'] = $this->input->post('pemotongan_tpp', true);
		if (count($update) > 0) {
			$updateData = $this->Db_dml->update('tb_status_user', $update, $where);
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

	public function update_status()
	{
		$where['id_status_user'] = $this->input->post('id_status_user', true);
		$update['is_aktif'] = $this->input->post('is_aktif', true);
		$updateData = $this->Db_dml->update('tb_status_user', $update, $where);
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

	function getData()
	{
		$user = $this->session->userdata('user');
		$id_channel = $user['id_channel'];
		if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama") {
			redirect(base_url());
			exit();
		}

		$tb = 'tb_status_user';
		$wr = 'id_channel=' . $id_channel;
		$fld =  array(null, 'nama_status_user', 'pemotongan_tpp', 'is_aktif', null);
		$src = array('nama_status_user');
		$ordr = array('nama_status_user' => 'asc');;
		$list = $this->Db_datatable->get_datatables2($tb, $wr, $fld, $src, $ordr);

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {
			if ($field->is_aktif == 1) {
				$ck =  "checked";
			} else {
				$ck = "";
			}
			$selectUser = $this->Db_select->select_where('tb_user', ['status_user' => $field->id_status_user], 'user_id');
			if ($selectUser) {
				$delete = false;
			} else {
				$delete = true;
			}
			if ($delete == true) {
				$del = '<a href="#" style="color: grey" data-type="ajax-loader" onclick="hapus(' . $field->id_status_user . ')"><span class="material-icons">delete</span></a>';
			} else {
				$del = '';
			}
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_status_user;
			$row[] = ($field->pemotongan_tpp == 1) ? 'Ya' : 'Tidak';
			$row[] = '<div class="demo-switch">
                        <div class="switch">
                            <label>Tidak Aktif<input value="' . $field->id_status_user . '" onclick="is_aktif("' . $field->id_status_user . '")" type="checkbox" id="is_aktif' . $field->id_status_user . '" ' . $ck . ' ><span class="lever"></span>Aktif</label>
                        </div>
                    </div>';
			$row[] = '<a href="#" style="color: grey" data-toggle="modal" data-target="#updateModal' . $field->id_status_user . '"><span class="material-icons">mode_edit</span></a>' . $del;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Db_datatable->count_all2($tb, $wr, $fld, $src, $ordr),
			"recordsFiltered" => $this->Db_datatable->count_filtered2($tb, $wr, $fld, $src, $ordr),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	public function detail($id)
	{
		$check = $this->Db_select->select_where('tb_status_user', ['id_status_user' => $id], ['id_status_user', 'nama_status_user']);
		if ($check) {
			$result['status'] = true;
			$result['message'] = 'Success';
			$result['data'] = $check;
		} else {
			$result['status'] = false;
			$result['message'] = 'Data tidak ditemukan';
			$result['data'] = null;
		}

		echo json_encode($result);
	}
}
