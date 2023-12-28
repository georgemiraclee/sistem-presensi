<?php defined('BASEPATH') OR exit('No direct script access allowed');

class akses_absensi extends CI_Controller
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
		// echo json_encode($sess);exit();

		$akses_absensi = $this->Db_select->query_all('select *, a.id_unit newid_unit from tb_akses_absensi a join tb_user b on a.user_id = b.user_id where date_format(tanggal_akhir,"%Y-%m-%d") >= date_format(now(), "%Y-%m-%d")  and id_parent = '.$parrent.'');

		$data['list_akses_absensi'] = "";
		foreach ($akses_absensi as $key => $value) {
			$id_unit = json_decode($value->newid_unit);
			$nama_unit = "";
			for ($i=0; $i < count($id_unit); $i++) { 
				$getUnit = $this->Db_select->select_where('tb_unit', 'id_unit = "'.$id_unit[$i].'"');

				$nama_unit .= "(".$getUnit->nama_unit.") ";
			}
			$data['list_akses_absensi'] .= "<tr>
                                                <td>".$value->nip."</td>
                                                <td>".$value->nama_user."</td>
                                                <td>".$nama_unit."</td>
                                                <td>".date('d M Y', strtotime($value->tanggal_akhir))."</td>
                                                <td>
                                                    <a href='".base_url()."SEKDA/akses_absensi/edit/".$value->id_akses_absensi."' style='color: grey'><span class='material-icons'>mode_edit</span></a>
                                                    <a href='#' style='color: grey' data-type='ajax-loader' onclick='hapus(".$value->id_akses_absensi.")'><span class='material-icons'>delete</span></a>
                                                </td>
                                            </tr>";
		}

		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/akses_absensi');
		$this->load->view('SEKDA/footer');
	}

	public function add()
	{
		$sess = $this->session->userdata('user');
		$parrent=$sess['id_user'];

		$data_pegawai = $this->Db_select->query_all('select *from tb_user where is_admin = 0 and id_parent = '.$parrent.' order by nama_user');

		$list_pegawai = "";
		foreach ($data_pegawai as $key => $value) {
			$cek = $this->Db_select->query('select *from tb_akses_absensi where date_format(tanggal_akhir,"%Y-%m-%d") >= date_format(now(),"%Y-%m-%d") and user_id = "'.$value->user_id.'"');
			if (!$cek) {
				$list_pegawai .= "<option value='".$value->user_id."'>".$value->nama_user." (".$value->nip.")</option>";
			}
		}
		$data['list_pegawai'] = $list_pegawai;
		$data['data_opd'] = $this->Db_select->select_all('tb_unit');

		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/tambah_akses_absensi');
		$this->load->view('SEKDA/footer');
	}

	public function insert()
	{
		$sess = $this->session->userdata('user');
		$data['user_id'] = $this->input->post('user_id');
		$data['id_unit'] = json_encode($this->input->post('id_unit'));
		$data['tanggal_akhir'] = $this->input->post('tanggal_akhir');

		$insertData = $this->Db_dml->normal_insert('tb_akses_absensi', $data);

		if ($insertData == 1) {
			$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}

		echo json_encode($result);exit();
	}

	public function delete()
	{
		$where['id_akses_absensi'] = $this->input->post('id_akses_absensi');

		$delete = $this->Db_dml->delete('tb_akses_absensi', $where);

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

	public function edit($id)
	{
		$sess = $this->session->userdata('user');

		$data['akses_absensi'] = $this->Db_select->select_where('tb_akses_absensi','id_akses_absensi = "'.$id.'"');
		$data['list_pegawai'] = $this->Db_select->query_all('select *from tb_user where is_admin = 0 order by nama_user');
		$data['data_opd'] = $this->Db_select->select_all('tb_unit');
		$data['id_unit'] = json_decode($data['akses_absensi']->id_unit);

		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/edit_akses_absensi');
		$this->load->view('SEKDA/footer');
	}

	public function update()
	{
		$sess = $this->session->userdata('user');

		$where['id_akses_absensi'] = $this->input->post('id_akses_absensi');
		$data['tanggal_akhir'] = $this->input->post('tanggal_akhir');
		$data['id_unit'] = json_encode($this->input->post('id_unit'));

		$update = $this->Db_dml->update('tb_akses_absensi', $data, $where);

		if ($update == 1) {
			$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}

		echo json_encode($result);exit();
	}

	public function update_status()
	{
		$sess = $this->session->userdata('user');
		$where['id_pengumuman'] = $this->input->post('id_pengumuman');

		$update['is_aktif'] = $this->input->post('is_aktif');

		$updateData = $this->Db_dml->update('tb_pengumuman', $update, $where);

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