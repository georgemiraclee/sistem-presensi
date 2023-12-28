<?php defined('BASEPATH') or exit('No direct script access allowed');
class pengumuman extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('ceksession', 'global_lib'));
		$this->load->model('Db_datatable');
		$this->ceksession->login();
		$this->global_lib = new global_lib;
	}

	public function index()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];

		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
			exit();
		}
		$data['pengumuman'] = $this->Db_select->select_all_where('tb_pengumuman', 'id_channel=' . $id_channel. ' and is_deleted = 0');
		$menu['main'] = 'pengumuman';
		$menu['child'] = '';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/pengumuman');
		$this->load->view('Administrator/footer');
	}

	public function add()
	{
		$menu['main'] = 'pengumuman';
		$menu['child'] = '';
		$data['menu'] = $menu;
		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/tambah_pengumuman');
		$this->load->view('Administrator/footer');
	}

	public function addPengumuman($type)
	{
		$sess = $this->session->userdata('user');

		if ($type == 1) {
			$data['html'] = '<div class="form-group form-float">
				<p style="font-size: 20px;">Semua User</p>
				</div>';
		} elseif ($type == 2) {
			/* get semua user */
			$getUser = $this->Db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = "' . $sess['id_channel'] . '" and a.is_aktif = 1 and a.is_deleted = 0');

			$list = "";
			if ($getUser) {
				foreach ($getUser as $key => $value) {
					$list .= "<option value='" . $value->user_id . "'>" . $value->nama_user . " | " . $value->nama_unit . "</option>";
				}
			}

			$data['html'] = '<div class="form-group form-float">
				<label class="form-label">Pegawai Terpilih</label>
				<select class="form-control js-example-basic-multiple" multiple="multiple" name="pegawai[]" required>
					<option>-- Pilih Pegawai --</option>
					' . $list . '
				</select>
			</div>';
		} elseif ($type == 3) {
			/* get semua user */
			$getUnit = $this->Db_select->query_all('select *from tb_unit where id_channel = "' . $sess['id_channel'] . '"');

			$list = "";
			if ($getUnit) {
				foreach ($getUnit as $key => $value) {
					$list .= "<option value='" . $value->id_unit . "'>" . $value->nama_unit . "</option>";
				}
			}

			$data['html'] = '<div class="form-group form-float">
				<label class="form-label">Unit Kerja Terpilih</label>
				<select class="form-control js-example-basic-multiple" name="departemen[]" required multiple="multiple">
					<option>-- Pilih Pegawai --</option>
					' . $list . '
				</select>
			</div>';
		} else {
			redirect('Administrator/pengumuman/add');
		}

		$data['id'] = $type;
		$menu['main'] = 'pengumuman';
		$menu['child'] = '';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/addPengumuman');
		$this->load->view('Administrator/footer');
	}

	public function insert()
	{
		$sess = $this->session->userdata('user');

		$id_channel = $sess['id_channel'];
		$data['judul_pengumuman'] = $this->input->post('judul_pengumuman');
		$data['deskripsi_pengumuman'] = $this->input->post('deskripsi_pengumuman');
		$data['start_date'] = $this->input->post('start_date');
		$data['end_date'] = $this->input->post('end_date');
		$data['id_channel'] = $id_channel;
		$data['is_aktif'] = 1;
		$dataImage = "";
		if ($_FILES['userfile']['name'] != '') {
			$path = realpath('./assets/images/pengumuman');
			$time = '1_' . strtotime('now');
			$config = array(
				'allowed_types' => 'jpg|jpeg|gif|png',
				'upload_path' => $path,
				'encrypt_name' => false,
				'file_name' => $time
			);
			$this->upload->initialize($config);
			if ($this->upload->do_upload()) {
				$img_data = $this->upload->data();
				$new_imgname = $time . $img_data['file_ext'];
				$new_imgpath = $img_data['file_path'] . $new_imgname;
				rename($img_data['full_path'], $new_imgpath);
				$data['url_file_pengumuman'] = $new_imgname;
				$dataImage = base_url() . "assets/images/pengumuman/" . $new_imgname;
			} else {
				$result['status'] = false;
				$result['message'] = $this->upload->display_errors();
				$result['data'] = array();
				echo json_encode($result);
				exit();
			}
		}

		$target = $this->input->post('idType');


		if ($target == 1) {
			$data['is_all'] = 1;
			$insertData = $this->Db_dml->insert('tb_pengumuman', $data);

			$message = "Pengumuman " . $this->input->post('judul_pengumuman');
			if ($insertData) {
				$this->global_lib->send_notification2('pengumuman', $id_channel, $message, $insertData);
			}
		} elseif ($target == 2) {
			$dataPegawai = $this->input->post('pegawai');
			for ($i = 0; $i < count($dataPegawai); $i++) {
				$data['user_id'] = $dataPegawai[$i];
				$insertData = $this->Db_dml->insert('tb_pengumuman', $data);

				$message = "Pengumuman " . $this->input->post('judul_pengumuman');
				if ($insertData) {
					$this->global_lib->sendNotifikasiUser('pengumuman', $data['user_id'], $message, $insertData);
				}
			}
		} elseif ($target == 3) {
			$dataDepartemen = $this->input->post('departemen');
			for ($i = 0; $i < count($dataDepartemen); $i++) {
				$data['id_unit'] = $dataDepartemen[$i];
				$insertData = $this->Db_dml->insert('tb_pengumuman', $data);

				$message = "Pengumuman " . $this->input->post('judul_pengumuman');
				if ($insertData) {
					$this->global_lib->sendNotifikasiDepartemen('pengumuman', $data['id_unit'], $message, $insertData);
				}
			}
		}

		if ($insertData) {
			$result['status'] = true;
			$result['message'] = 'Data berhasil disimpan.';
			$result['data'] = array();
		} else {
			$result['status'] = false;
			$result['message'] = 'Data gagal disimpan.';
			$result['data'] = array();
		}

		echo json_encode($result);
		exit();
	}

	public function delete()
	{
		$where['id_pengumuman'] = $this->input->post('id_pengumuman');
		$delete = $this->Db_dml->update('tb_pengumuman', ['is_deleted' => 1] ,$where);
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

	public function edit($id)
	{
		// GET PENGUMUMAN
		$where['id_pengumuman'] = $id;
		$pengumuman = $this->Db_select->select_where('tb_pengumuman', $where);
		$data['item'] = $pengumuman;
		$menu['main'] = 'pengumuman';
		$menu['child'] = '';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/edit_pengumuman');
		$this->load->view('Administrator/footer');
	}

	public function update()
	{
		$where['id_pengumuman'] = $this->input->post('id_pengumuman');
		$data['judul_pengumuman'] = $this->input->post('judul_pengumuman');
		$data['deskripsi_pengumuman'] = $this->input->post('deskripsi_pengumuman');
		$data['start_date'] = $this->input->post('start_date');
		$data['end_date'] = $this->input->post('end_date');

		if ($_FILES['userfile']['name'] != '') {
			$path = realpath('./assets/images/pengumuman');
			$time = '1_' . strtotime('now');
			$config = array(
				'allowed_types' => 'jpg|jpeg|gif|png',
				'upload_path' => $path,
				'encrypt_name' => false,
				'file_name' => $time
			);
			$this->upload->initialize($config);
			if ($this->upload->do_upload()) {
				$img_data = $this->upload->data();
				$new_imgname = $time . $img_data['file_ext'];
				$new_imgpath = $img_data['file_path'] . $new_imgname;
				rename($img_data['full_path'], $new_imgpath);
				$data['url_file_pengumuman'] = $new_imgname;
			} else {
				$pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" . $this->upload->display_errors() . "</div>";
				$this->session->set_flashdata('pesan', $pesan);
				redirect(base_url() . "Administrator/pegawai/edit/" . $this->input->post('user_id'));
				exit();
			}
		}

		$update = $this->Db_dml->update('tb_pengumuman', $data, $where);
		if ($update == 1 || $update == 0) {
			$result['status'] = true;
			$result['message'] = 'Data berhasil disimpan.';
			$result['data'] = array();
		} else {
			$result['status'] = false;
			$result['message'] = 'Data gagal disimpan.';
			$result['data'] = array();
		}

		echo json_encode($result);
		exit();
	}

	public function update_status()
	{
		$where['id_pengumuman'] = $this->input->post('id_pengumuman');
		$update['is_aktif'] = $this->input->post('is_aktif');
		$updateData = $this->Db_dml->update('tb_pengumuman', $update, $where);
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

	function getDatatable()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
			exit();
		}
		$tb = 'tb_pengumuman';
		$wr = 'id_channel=' . $id_channel;
		$fld =  array(null, 'judul_pengumuman', 'deskripsi_pengumuman', 'is_aktif', 'url_file_pengumuman');
		$src = array('judul_pengumuman');
		$ordr = array('created_pengumuman' => 'desc');;
		$list = $this->Db_datatable->get_datatables2($tb, $wr, $fld, $src, $ordr);

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {
			$target = "Semua User";
			if ($field->is_all == 1) {
				$target = "Semua User";
			} elseif ($field->user_id != null || $field->user_id != "") {
				// get data user
				$userData = $this->Db_select->select_where('tb_user', 'user_id = ' . $field->user_id);
				$target = $userData->nama_user;
			} elseif ($field->id_unit != null || $field->id_unit != "") {
				// get data unit
				$userUnit = $this->Db_select->select_where('tb_unit', 'id_unit = ' . $field->id_unit);
				$target = $userUnit->nama_unit;
			}

			$is_aktif = "";
			if ($field->is_aktif == 1) {
				$is_aktif = 'checked';
			}

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = date('d M Y', strtotime($field->start_date));
			$row[] = date('d M Y', strtotime($field->end_date));
			$row[] = $field->judul_pengumuman;
			$row[] = '<input type="checkbox" name="my-checkbox" onchange="is_aktif(' . $field->id_jabatan . ')" ' . $is_aktif . ' id="is_aktif' . $field->id_jabatan . '" data-bootstrap-switch>';
			$row[] = $target;
			$row[] = '<a href="' . base_url() . 'Administrator/pengumuman/edit/' . $field->id_pengumuman . '" style="color: grey" class="badge bg-orange">
        <span class="material-icons" style="font-size: 15px;">mode_edit</span>
      </a>
      <a href="#" style="color: grey" data-type="ajax-loader" onclick="hapus(' . $field->id_pengumuman . ')" class="badge bg-red"><span class="material-icons" style="font-size: 15px;">delete</span></a>';
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

	public function getUser()
	{
		$sess = $this->session->userdata('user');

		/* getUser */
		$getUser = $this->Db_select->query_all("select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = " . $sess['id_channel']);

		if ($getUser) {
			$result['status'] = true;
			$result['message'] = 'Success.';
			$result['data'] = $getUser;
		} else {
			$result['status'] = false;
			$result['message'] = 'Failed.';
			$result['data'] = array();
		}
		echo json_encode($result);
	}

	public function getDepartemen()
	{
		$sess = $this->session->userdata('user');

		/* getDepartemen */
		$getDepartemen = $this->Db_select->query_all("select *from tb_unit where id_channel = " . $sess['id_channel']);

		if ($getDepartemen) {
			$result['status'] = true;
			$result['message'] = 'Success.';
			$result['data'] = $getDepartemen;
		} else {
			$result['status'] = false;
			$result['message'] = 'Failed.';
			$result['data'] = array();
		}
		echo json_encode($result);
	}
}
