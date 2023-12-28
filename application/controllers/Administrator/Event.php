<?php defined('BASEPATH') or exit('No direct script access allowed');

class event extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('Ceksession');
		$this->ceksession->login();
	}

	public function index()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		$file = 'appconfig/' . $id_channel . '_auto_respon.txt';
		$sess = $this->session->userdata('user');
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
			exit();
		}
		if (!read_file($file)) {
			$this->create();
		}
		$data['data'] = json_decode(file_get_contents($file));

		/*get data cuti*/
		$getDataCuti = $this->Db_select->select_where('tb_jatah_cuti', 'id_channel = "' . $id_channel . '"');
		if ($getDataCuti) {
			$data['jatah_cuti'] = $getDataCuti->banyak_cuti;
		} else {
			$data['jatah_cuti'] = "-";
		}

		$data['tanggal'] = $this->Db_select->select_all_where('tb_event', 'date_format(tanggal_event,"%Y") = date_format(now(),"%Y") And id_channel=' . $id_channel.' and is_deleted = 0');

		$menu['main'] = 'kalender';
		$menu['child'] = '';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/event');
		$this->load->view('Administrator/footer');
	}
	public function tambah_kebijakan()
	{
		$sess = $this->session->userdata('user');
		$data['data_jabatan'] = $this->Db_select->select_all('tb_jabatan');
		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/tambah_kebijakan');
		$this->load->view('Administrator/footer');
	}
	public function update()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		$file = 'appconfig/' . $id_channel . '_auto_respon.txt';
		$txt = array(
			"jam_kerja" => array(
				"monday" => array(
					"from" => $this->input->post('monday_from'),
					"to" => $this->input->post('monday_to')
				), "tuesday" => array(
					"from" => $this->input->post('tuesday_from'),
					"to" => $this->input->post('tuesday_to')
				), "wednesday" => array(
					"from" => $this->input->post('wednesday_from'),
					"to" => $this->input->post('wednesday_to')
				), "thursday" => array(
					"from" => $this->input->post('thursday_from'),
					"to" => $this->input->post('thursday_to')
				), "friday" => array(
					"from" => $this->input->post('friday_from'),
					"to" => $this->input->post('friday_to')
				), "saturday" => array(
					"from" => $this->input->post('saturday_from'),
					"to" => $this->input->post('saturday_to')
				), "sunday" => array(
					"from" => $this->input->post('sunday_from'),
					"to" => $this->input->post('sunday_to')
				)
			),
			"jam_istirahat" => array(
				"monday" => array(
					"from" => $this->input->post('new_monday_from'),
					"to" => $this->input->post('new_monday_to')
				), "tuesday" => array(
					"from" => $this->input->post('new_tuesday_from'),
					"to" => $this->input->post('new_tuesday_to')
				), "wednesday" => array(
					"from" => $this->input->post('new_wednesday_from'),
					"to" => $this->input->post('new_wednesday_to')
				), "thursday" => array(
					"from" => $this->input->post('new_thursday_from'),
					"to" => $this->input->post('new_thursday_to')
				), "friday" => array(
					"from" => $this->input->post('new_friday_from'),
					"to" => $this->input->post('new_friday_to')
				), "saturday" => array(
					"from" => $this->input->post('new_saturday_from'),
					"to" => $this->input->post('new_saturday_to')
				), "sunday" => array(
					"from" => $this->input->post('new_sunday_from'),
					"to" => $this->input->post('new_sunday_to')
				)
			),
			"dispensasi" => $this->input->post('dispensasi')
		);
		write_file($file, json_encode($txt));
		$result['status'] = true;
		$result['message'] = "Data berhasil disimpan";
		$result['data'] = array();
		echo json_encode($result);
	}
	public function create()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		$file = 'appconfig/' . $id_channel . '_auto_respon.txt';
		$txt = array(
			"jam_kerja" => array(
				"monday" => array(
					"to" => "15:30", "from" => "07:30"
				), "tuesday" => array(
					"to" => "15:30", "from" => "07:30"
				), "wednesday" => array(
					"to" => "15:30", "from" => "07:30"
				), "thursday" => array(
					"to" => "15:30", "from" => "07:30"
				), "friday" => array(
					"to" => "15:30", "from" => "07:30"
				), "saturday" => array(
					"to" => "15:30", "from" => "07:30"
				), "sunday" => array(
					"to" => "15:30", "from" => "07:30"
				)
			),
			"jam_istirahat" => array(
				"monday" => array(
					"to" => "15:30", "from" => "07:30"
				), "tuesday" => array(
					"to" => "15:30", "from" => "07:30"
				), "wednesday" => array(
					"to" => "15:30", "from" => "07:30"
				), "thursday" => array(
					"to" => "15:30", "from" => "07:30"
				), "friday" => array(
					"to" => "15:30", "from" => "07:30"
				), "saturday" => array(
					"to" => "15:30", "from" => "07:30"
				), "sunday" => array(
					"to" => "15:30", "from" => "07:30"
				)
			),
			"dispensasi" => "0"
		);
		write_file($file, json_encode($txt));
	}
	public function insert_event()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		$data['nama_event'] = $this->input->post('nama_event');
		$data['tanggal_event'] = date("Y-m-d", strtotime($this->input->post('tanggal_event')));
		$data['id_channel'] = $id_channel;
		$insert = $this->Db_dml->normal_insert('tb_event', $data);
		if ($insert == 1) {
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
	public function update_event()
	{
		$data['nama_event'] = $this->input->post('nama_event');
		$data['tanggal_event'] = date("Y-m-d", strtotime($this->input->post('tanggal_event')));
		$where['id_event'] = $this->input->post('id_event');
		$updateData = $this->Db_dml->update('tb_event', $data, $where);
		if ($updateData == 1 || $updateData == 0) {
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

	public function insert_jatah_cuti()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];

		$where['id_channel'] = $id_channel;
		$cekData = $this->Db_select->select_where('tb_jatah_cuti', $where);
		if ($cekData) {
			/* update */
			$data['banyak_cuti'] = $this->input->post('banyak_cuti');
			$update = $this->Db_dml->update('tb_jatah_cuti', $data, $where);

			if ($update) {
				$result['status'] = true;
				$result['message'] = 'Data Berhasil Disimpan';
			} else {
				$result['status'] = false;
				$result['message'] = 'Data Gagal Disimpan';
			}
		} else {
			/* insert */
			$data['banyak_cuti'] = $this->input->post('banyak_cuti');
			$data['id_channel'] = $id_channel;
			$insert = $this->Db_dml->insert('tb_jatah_cuti', $data);

			if ($insert) {
				$result['status'] = true;
				$result['message'] = 'Data Berhasil Disimpan';
			} else {
				$result['status'] = false;
				$result['message'] = 'Data Gagal Disimpan';
			}
		}

		echo json_encode($result);
	}
	public function delete()
	{
		$where['id_event'] = $this->input->post('id_event');
		$delete = $this->Db_dml->delete('tb_event', $where);
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
