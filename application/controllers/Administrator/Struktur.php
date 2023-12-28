<?php defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class struktur extends CI_Controller
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

		$data['dataStruktur'] = $this->getStruktur($user['id_channel']);
		$menu['main'] = 'pengaturan';
		$menu['child'] = 'pengaturan_stuktur';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/struktur');
		$this->load->view('Administrator/footer');
	}

	public function insert()
	{
		$user = $this->session->userdata('user');
		$id_channel = $user['id_channel'];

		$cek = $this->Db_select->select_where('tb_struktur_organisasi', ['id_channel' => $id_channel], ['id_struktur']);
		$data['id_channel'] = $id_channel;
		$data['struktur_data'] = json_encode($this->input->post('key', true));

		if ($cek) {
			$where['id_struktur'] = $cek->id_struktur;

			$update = $this->Db_dml->update('tb_struktur_organisasi', $data, $where);

			if ($update == 1 || $update == 0) {
				$result['status'] = true;
				$result['message'] = 'Data berhasil disimpan.';
				$result['data'] = array();
			} else {
				$result['status'] = false;
				$result['message'] = 'Data gagal disimpan2.';
				$result['data'] = array();
			}
		} else {
			$insert = $this->Db_dml->normal_insert('tb_struktur_organisasi', $data);

			if ($insert == 1 || $insert == 0) {
				$result['status'] = true;
				$result['message'] = 'Data berhasil disimpan.';
				$result['data'] = array();
			} else {
				$result['status'] = false;
				$result['message'] = 'Data gagal disimpan1.';
				$result['data'] = array();
			}
		}

		echo json_encode($result);
		exit();
	}

	public function getStruktur($id_channel)
	{
		$user = $this->session->userdata('user');
		$getData = $this->Db_select->select_where('tb_struktur_organisasi', ['id_channel' => $id_channel], ['struktur_data']);
		$newData = json_decode($getData->struktur_data);

		$getUser = $this->Db_select->query_all("SELECT id_struktur FROM tb_user a JOIN tb_unit b ON a.id_unit = b.id_unit WHERE b.id_channel = ? ", [$user['id_channel']]);

		foreach ($newData as $key => $valuez) {
			$valuez->used = 0;
		}

		foreach ($getUser as $key2 => $values) {
			foreach ($newData as $key => $value) {
				if ($values->id_struktur == $value->id) {
					$value->used = 1;
				}
			}
		}
		$getData->struktur_data = json_encode($getData->struktur_data);
		$getData->struktur_data = json_decode($getData->struktur_data);
		return $getData;
	}
}
