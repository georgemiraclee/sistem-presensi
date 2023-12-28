<?php defined('BASEPATH') or exit('No direct script access allowed');

class unit extends CI_Controller
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
		$id_channel = $user['id_channel'];
		if ($user['akses'] != "admin_channel" && $user['akses'] != "admin_utama") {
			redirect(base_url());
			exit();
		}
		$data['list'] = $this->Db_select->select_all_where('tb_unit', [
			'id_channel' => $id_channel,
			'is_deleted' => 0
		], ['id_unit', 'nama_unit', 'is_aktif', 'icon_unit', 'alamat_unit']);
		foreach ($data['list'] as $key => $value) {
			$selectUser = $this->Db_select->select_where('tb_user', ['id_unit' => $value->id_unit], 'user_id');
			if ($selectUser) {
				$value->delete = false;
			} else {
				$value->delete = true;
			}
		}
		$menu['main'] = 'pengaturan';
		$menu['child'] = 'pengaturan_dept';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/list_unit');
		$this->load->view('Administrator/footer');
	}

	public function insert()
	{
		$user = $this->session->userdata('user');
		$id_channel = $user['id_channel'];
		$insert = array();
		$insert['nama_unit'] = $this->input->post('nama_unit', true);
		$insert['id_channel'] = $id_channel;
		$insert['alamat_unit'] = $this->input->post('alamat_unit', true);
		$insert['data_lokasi'] = json_encode($this->input->post('data_lokasi', true));
		$insert['data_jaringan'] = json_encode($this->input->post('data_jaringan', true));
		if ($_FILES['userfile']['name']) {
			$this->load->library('upload');
			$nmfile = "file_" . time(); //nama file saya beri nama langsung dan diikuti fungsi time
			$config['upload_path'] = './assets/images/unit'; //path folder
			$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
			$config['max_size'] = '2048'; //maksimum besar file 2M
			$config['file_name'] = $nmfile; //nama yang terupload nantinya
			$this->upload->initialize($config);
			if ($this->upload->do_upload('userfile')) {
				$gbr = $this->upload->data();
				$name3 = $gbr['file_name'];
				$insert['icon_unit'] = $name3;
			}
		}
		if (count($insert) > 0) {
			$insertData = $this->Db_dml->normal_insert('tb_unit', $insert);
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
		$where['id_unit'] = $this->input->post('id_unit', true);
		$delete = $this->Db_dml->update('tb_unit', ['is_deleted' => 1], $where);
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
		$sess = $this->session->userdata('user');
		$where['id_unit'] = $this->input->post('id_unit', true);
		$update['nama_unit'] = $this->input->post('nama_unit', true);
		$update['alamat_unit'] = $this->input->post('alamat_unit', true);
		$update['data_lokasi'] = json_encode($this->input->post('data_lokasi', true));
		$update['data_jaringan'] = json_encode($this->input->post('data_jaringan', true));
		if ($_FILES['userfile']['name']) {
			$this->load->library('upload');
			$nmfile = "file_" . time(); //nama file saya beri nama langsung dan diikuti fungsi time
			$config['upload_path'] = './assets/images/unit'; //path folder
			$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
			$config['max_size'] = '2048'; //maksimum besar file 2M
			$config['file_name'] = $nmfile; //nama yang terupload nantinya
			$this->upload->initialize($config);
			if ($this->upload->do_upload('userfile')) {
				$gbr = $this->upload->data();
				$name3 = $gbr['file_name'];
				$update['icon_unit'] = $name3;
			}
		}
		if (count($update) > 0) {
			$updateData = $this->Db_dml->update('tb_unit', $update, $where);
			if ($updateData == 1 || $updateData == 0) {
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
		$where['id_unit'] = $this->input->post('id_unit', true);
		$update['is_aktif'] = $this->input->post('is_aktif', true);
		$updateData = $this->Db_dml->update('tb_unit', $update, $where);
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

	public function add()
	{
		$user = $this->session->userdata('user');
		$area_absensi = $this->Db_select->select_all_where('tb_lokasi', ['id_channel' => $user['id_channel']], ['id_lokasi', 'nama_lokasi']);
		$jaringan_absensi = $this->Db_select->select_all_where('tb_jaringan', ['id_channel' => $user['id_channel']], ['id_jaringan', 'ssid_jaringan']);
		$list_area = "";
		$list_jaringan = "";
		foreach ($area_absensi as $key => $value) {
			$list_area .= "<option value='" . $value->id_lokasi . "'>" . ucwords($value->nama_lokasi) . "</option>";
		}
		foreach ($jaringan_absensi as $key => $value) {
			$list_jaringan .= "<option value='" . $value->id_jaringan . "'>" . ucwords($value->ssid_jaringan) . "</option>";
		}
		$data['area_absensi'] = $list_area;
		$data['jaringan_absensi'] = $list_jaringan;
		$menu['main'] = 'pengaturan';
		$menu['child'] = 'pengaturan_dept';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/tambah_unit');
		$this->load->view('Administrator/footer');
	}

	public function edit($id)
	{
		$user = $this->session->userdata('user');
		$data['data_unit'] = $this->Db_select->select_where('tb_unit', ['id_unit' => $id], ['id_unit', 'nama_unit', 'alamat_unit', 'data_lokasi', 'data_jaringan']);
		$data['unit_area'] = json_decode($data['data_unit']->data_lokasi);
		$data['unit_jaringan'] = json_decode($data['data_unit']->data_jaringan);
		$data['area_absensi'] = $this->Db_select->query_all("SELECT id_lokasi, nama_lokasi FROM tb_lokasi WHERE id_channel = ?", [$user['id_channel']]);
		$data['jaringan_absensi'] = $this->Db_select->query_all("SELECT id_jaringan, ssid_jaringan FROM tb_jaringan WHERE id_channel = ?", [$user['id_channel']]);
		$menu['main'] = 'pengaturan';
		$menu['child'] = 'pengaturan_dept';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/edit_unit');
		$this->load->view('Administrator/footer');
	}

	function get_data_user($value = null)
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama") {
			redirect(base_url());
			exit();
		}
		$tb = 'tb_unit';
		$wr = 'id_channel=' . $id_channel;
		$fld =  array(null, 'nama_unit', 'created_unit', 'icon_unit', 'is_aktif', 'alamat_unit', 'data_lokasi', 'data_jaringan', 'id_channel');
		$src = array('nama_unit');
		$ordr = array('created_unit' => 'desc');;
		$list = $this->Db_datatable->get_datatables2($tb, $wr, $fld, $src, $ordr);

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {

			$selectUser = $this->Db_select->select_where('tb_user', 'id_unit = "' . $field->id_unit . '"');
			if ($selectUser) {
				$delete = false;
			} else {
				$delete = true;
			}
			if ($delete == true) {
				$del = ' <a href="#" style="color: grey" data-type="ajax-loader" onclick="hapus(' . $field->id_unit . ')"><span class="material-icons">delete</span></a>';
			} else {
				$del = '';
			}
			if ($field->is_aktif == 1) {
				$ck = 'checked';
			} else {
				$ck = '';
			}
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_unit;
			$row[] = $field->icon_unit;
			$row[] = $field->alamat_unit;
			$row[] = '
            	<div class="demo-switch">
                    <div class="switch">
                        <label>Tidak Aktif<input value="' . $field->id_unit . '" onclick="is_aktif(' . $field->id_unit . ')" type="checkbox" id="is_aktif' . $field->id_unit . '" ' . $ck . ' ><span class="lever"></span>Aktif</label>
                    </div>
                </div>';
			$row[] = '<a href="' . base_url() . 'Administrator/unit/edit/' . $field->id_unit . '" style="color: white" class="btn btn-warning btn-sm">Edit</a> <a href="' . base_url() . 'Administrator/unit/divisi/' . $field->id_unit . '" style="color: white" class="btn btn-info btn-sm">Divisi</a>' . $del;
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

	public function divisi($id)
	{
		$departemen = $this->Db_select->query('select nama_unit from tb_unit where id_unit = ' . $id);
		$data['departemen'] = $departemen->nama_unit;
		$data['id_unit'] = $id;
		$list = $this->Db_select->select_all_where('tb_divisi', 'id_unit="' . $id . '"');
		$data['dd'] = $list;
		$data['list'] = '';
		$data['modal'] = '';
		$data['js'] = '';
		foreach ($list as $key => $value) {
			$cek_user = $this->Db_select->select_where('tb_user', 'id_divisi = ' . $value->id_divisi);

			if ($cek_user == "" || $cek_user == null) {
				$hapus = '<a href="#" data-type="ajax-loader" onclick="hapus(' . $value->id_divisi . ')" style="color: white" class="btn btn-warning btn-sm">hapus</a>';
			} else {
				$hapus = "";
			}
			$no = $key + 1;
			$data['list'] .= '
				<tr>
					<td>' . $no . '</td>
					<td>' . $value->nama_divisi . '</td>
					<td>' . $departemen->nama_unit . '</td>
					<td><a href="#" data-toggle="modal" data-target="#myModal' . $value->id_divisi . '" style="color: white" class="btn btn-success btn-sm">Edit</a> ' . $hapus . '</td>
				</tr>
    		';
			$data['modal'] .= '
				<div id="myModal' . $value->id_divisi . '" class="modal fade" role="dialog">
				  <div class="modal-dialog">
				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Edit Data Divisi</h4>
				      </div>
				      <div class="modal-body">
				        <form class="form-horizontal" id="update-form' . $value->id_divisi . '" action="javascript:void(0);" method="post">
							<div class="row clearfix">
								<div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                    <label for="email_address_2">Nama Divisi</label>
                                </div>
                                <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="nama_divisi" value="' . $value->nama_divisi . '" class="form-control" placeholder="Nama Jabatan">
                                            <input type="hidden" name="id_divisi" value="' . $value->id_divisi . '">
                                        </div>
                                    </div>
                                </div>
							</div>
				      </div>
				      <div class="modal-footer">
				        <button type="submit" class="btn btn-info"><span class="fa fa-save"></span> Simpan</button>
				        </form>
				      </div>
				    </div>

				  </div>
				</div>
    		';
		}


		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/list_divisi');
		$this->load->view('Administrator/footer');
	}

	public function insert_divisi()
	{
		$insert = array();
		$insert['nama_divisi'] = $this->input->post('nama_divisi');
		$insert['id_unit'] = $this->input->post('id_unit');

		if (count($insert) > 0) {
			$insertData = $this->Db_dml->normal_insert('tb_divisi', $insert);
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

	public function update_divisi()
	{
		$where['id_divisi'] = $this->input->post('id_divisi');
		$update['nama_divisi'] = $this->input->post('nama_divisi');

		if (count($update) > 0) {
			$updateData = $this->Db_dml->update('tb_divisi', $update, $where);
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

	public function delete_divisi()
	{
		$where['id_divisi'] = $this->input->post('id_divisi');
		$delete = $this->Db_dml->delete('tb_divisi', $where);
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
