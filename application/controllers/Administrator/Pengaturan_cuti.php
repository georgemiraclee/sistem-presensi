<?php defined('BASEPATH') or exit('No direct script access allowed');
class Pengaturan_cuti extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$sess = $this->session->userdata('user');
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama") {
			redirect(base_url());
			exit();
		}
	}

	public function index()
	{
		$user = $this->session->userdata('user');
		$id_channel = $user['id_channel'];
		$query = $this->Db_select->select_where('tb_pengaturan_cuti', ['id_channel' => $id_channel], ['jumlah_cuti_tahunan', 'lebaran', 'natal', 'jatah_cuti_bulanan', 'batasan_cuti']);

		if ($query) {
			$data['id_channel'] = $id_channel;
			$data['cuti'] = $query->jumlah_cuti_tahunan;
			$data['lebaran'] = $query->lebaran;
			$data['natal'] = $query->natal;
			$data['jatah'] = $query->jatah_cuti_bulanan;
			if ($query->batasan_cuti == 1) {
				$data['batas'] = "checked";
			} else {
				$data['batas'] = "";
			}
		} else {
			$input['jumlah_cuti_tahunan'] = 14;
			$input['jatah_cuti_bulanan'] = 3;
			$input['batasan_cuti'] = 0;
			$input['id_channel'] = $id_channel;

			$this->Db_dml->insert('tb_pengaturan_cuti', $input);

			$data['id_channel'] = $id_channel;
			$data['cuti'] = "14";
			$data['lebaran'] = "0";
			$data['natal'] = "0";
			$data['jatah'] = "3";
			$data['batas'] = "";
		}
		$menu['main'] = 'pengaturan';
		$menu['child'] = 'pengaturan_cuti';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/pengaturan_cuti');
		$this->load->view('Administrator/footer');
	}

	public function insert()
	{
		$user = $this->session->userdata('user');
		$id_channel = $user['id_channel'];
		$where['id_channel'] = $id_channel;
		$insert = array();
		if ($this->input->post('id_channel', true)) {
			$insert['id_channel'] = $this->input->post('id_channel', true);
			$insert['jumlah_cuti_tahunan'] = $this->input->post('cuti', true);
			$insert['jatah_cuti_bulanan'] = $this->input->post('batasan', true);
			$insert['lebaran'] = $this->input->post('lebaran', true);
			$insert['natal'] = $this->input->post('natal', true);
			if (isset($_POST['aktif_batasan'])) {
				$hospValue = 1;
			} else {
				$hospValue = 0;
			}
			$insert['batasan_cuti'] = $hospValue;
		}
		$cek_update = $this->Db_select->select_where('tb_pengaturan_cuti', ['id_channel' => $insert['id_channel']], ['id_pengaturan_cuti']);
		if ($cek_update == null) {
			$insertData = $this->Db_dml->normal_insert('tb_pengaturan_cuti', $insert);
			if ($insertData) {
				$result['status'] = true;
				$result['message'] = 'Data baru berhasil disimpan.';
				$result['data'] = array();
			} else {
				$result['status'] = false;
				$result['message'] = 'Data baru gagal disimpan.';
				$result['data'] = array();
			}
		} else {
			$updateData = $this->Db_dml->update('tb_pengaturan_cuti', $insert, $where);
			if ($updateData == 1 || $updateData == 0) {
				$result['status'] = true;
				$result['message'] = 'Data berhasil diubah.';
				$result['data'] = array();
			} else {
				$result['status'] = false;
				$result['message'] = 'Data gagal diubah.';
				$result['data'] = array();
			}
		}
		echo json_encode($result);
	}
}
