<?php defined('BASEPATH') or exit('No direct script access allowed');

class dispensasi extends CI_Controller
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
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
			exit();
		}
		$akses_absensi = $this->Db_select->query_all('select *from tb_dispensasi a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit =c.id_unit where id_channel=' . $id_channel);
		$data['list_akses_absensi'] = "";
		foreach ($akses_absensi as $key => $value) {
			$data['list_akses_absensi'] .= "<tr>
        <td>" . $value->nip . "</td>
        <td>" . $value->nama_user . "</td>
        <td>" . $value->tanggal_dispensasi . "</td>
        <td>" . date('H:i', strtotime($value->jam_awal_dispensasi)) . "</td>
        <td>" . date('H:i', strtotime($value->jam_akhir_dispensasi)) . "</td>
        <td>
          <a href='" . base_url() . "Administrator/dispensasi/edit/" . $value->id_dispensasi . "' style='color: grey'><span class='material-icons'>mode_edit</span></a>
          <a href='#' style='color: grey' data-type='ajax-loader' onclick='hapus(" . $value->id_dispensasi . ")'><span class='material-icons'>delete</span></a>
        </td>
      </tr>";
		}

		$menu['main'] = 'dispensasi';
		$menu['child'] = '';
		$data['menu'] = $menu;
		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/dispensasi');
		$this->load->view('Administrator/footer');
	}

	public function add()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		$data_pegawai = $this->Db_select->query_all('select *from tb_user b join tb_unit c on b.id_unit =c.id_unit where id_channel=' . $id_channel . ' and is_admin = 0 order by nama_user');
		$list_pegawai = "";
		foreach ($data_pegawai as $key => $value) {
			$cek = $this->Db_select->query('select *from tb_akses_absensi where date_format(tanggal_akhir,"%Y-%m-%d") >= date_format(now(),"%Y-%m-%d") and user_id = "' . $value->user_id . '"');
			if (!$cek) {
				$list_pegawai .= "<option value='" . $value->user_id . "'>" . $value->nama_user . " (" . $value->nip . ")</option>";
			}
		}
		$data['list_pegawai'] = $list_pegawai;
		$data['data_opd'] = $this->Db_select->select_all('tb_unit');

		$menu['main'] = 'dispensasi';
		$menu['child'] = '';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/tambah_dispensasi');
		$this->load->view('Administrator/footer');
	}
	public function insert()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		$data['user_id'] = $this->input->post('user_id', true);
		$data['tanggal_dispensasi'] =  $this->input->post('tanggal_dispensasi', true);
		$data['jam_awal_dispensasi'] = $this->input->post('jam_awal_dispensasi', true);
		$data['jam_akhir_dispensasi'] = $this->input->post('jam_akhir_dispensasi', true);

		$pola_kerja_user = $this->Db_select->query(
			'SELECT tb_pola_kerja.file_pola_kerja
		FROM tb_pola_user
		JOIN tb_pola_kerja ON tb_pola_kerja.id_pola_kerja = tb_pola_user.id_pola_kerja
		WHERE user_id = ? 
		AND start_pola_kerja >= ? 
		AND end_pola_kerja <= ?',
			[$data['user_id'], $data['tanggal_dispensasi'], $data['tanggal_dispensasi']]
		);

		$is_jam_kerja_sesuai = true;
		$pola_kerja = $pola_kerja_user;

		if (is_null($pola_kerja)) {
			$pola_kerja = $this->Db_select->query('SELECT file_pola_kerja FROM tb_pola_kerja WHERE id_channel = ? AND is_default = ? AND is_deleted = ?', [$id_channel, 1, 0]);
		}

		$file_pola_kerja = fopen(FCPATH . '/appconfig/new/' . $pola_kerja->file_pola_kerja, 'r');
		$pola_kerja = json_decode(fread($file_pola_kerja, filesize(FCPATH . '/appconfig/new/' . $pola_kerja->file_pola_kerja)), true);
		fclose($file_pola_kerja);

		$pola_kerja = $pola_kerja['jam_kerja'][strtolower(date('l', strtotime($data['tanggal_dispensasi'])))];

		$jam_awal_pengajuan = strtotime($data['tanggal_dispensasi'] . ' ' . $data['jam_awal_dispensasi']);
		$jam_akhir_pengajuan = strtotime($data['tanggal_dispensasi'] . ' ' . $data['jam_akhir_dispensasi']);

		$jam_awal_pola_kerja = strtotime($data['tanggal_dispensasi'] . ' ' . $pola_kerja['from']);
		$jam_akhir_pola_kerja = strtotime($data['tanggal_dispensasi'] . ' ' . $pola_kerja['to']);

		if ($jam_awal_pengajuan < $jam_awal_pola_kerja || $jam_akhir_pengajuan > $jam_akhir_pola_kerja) {
			$result['status'] = false;
			$result['message'] = 'Jam kerja tidak sesuai. Jam kerja yang berlaku adalah: ' . $pola_kerja['from'] . '-' . $pola_kerja['to'];
			$result['data'] = array();
			$is_jam_kerja_sesuai = false;
		}

		if ($is_jam_kerja_sesuai) {
			$akses_absensi = $this->Db_select->query('SELECT id_dispensasi from tb_dispensasi WHERE user_id = ? AND tanggal_dispensasi = ?', [$data['user_id'], $data['tanggal_dispensasi']]);

			if (!is_null($akses_absensi)) {
				$insertData = $this->Db_dml->normal_insert('tb_dispensasi', $data);
				if ($insertData == 1) {
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
		}
		echo json_encode($result);
		exit();
	}
	public function delete()
	{
		$where['id_dispensasi'] = $this->input->post('id_dispensasi');
		$delete = $this->Db_dml->update('tb_dispensasi', ['is_deleted' => 1], $where);
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
		$data['akses_absensi'] = $this->Db_select->select_where('tb_dispensasi', 'id_dispensasi = "' . $id . '"');
		$data['list_pegawai'] = $this->Db_select->query_all('select *from tb_user where is_admin = 0 order by nama_user');

		$menu['main'] = 'dispensasi';
		$menu['child'] = '';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/edit_dispensasi');
		$this->load->view('Administrator/footer');
	}
	public function update()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		$where['id_dispensasi'] = $this->input->post('id_dispensasi', true);
		$data['tanggal_dispensasi'] = $this->input->post('tanggal_dispensasi', true);
		$data['jam_awal_dispensasi'] = $this->input->post('jam_awal_dispensasi', true);
		$data['jam_akhir_dispensasi'] = $this->input->post('jam_akhir_dispensasi', true);

		$pola_kerja_user = $this->Db_select->query(
			'SELECT tb_pola_kerja.file_pola_kerja
		FROM tb_pola_user
		JOIN tb_pola_kerja ON tb_pola_kerja.id_pola_kerja = tb_pola_user.id_pola_kerja
		WHERE user_id = ? 
		AND start_pola_kerja >= ? 
		AND end_pola_kerja <= ?',
			[$data['user_id'], $data['tanggal_dispensasi'], $data['tanggal_dispensasi']]
		);

		$is_jam_kerja_sesuai = true;
		$pola_kerja = $pola_kerja_user;

		if (is_null($pola_kerja)) {
			$pola_kerja = $this->Db_select->query('SELECT file_pola_kerja FROM tb_pola_kerja WHERE id_channel = ? AND is_default = ? AND is_deleted = ?', [$id_channel, 1, 0]);
		}

		$file_pola_kerja = fopen(FCPATH . '/appconfig/new/' . $pola_kerja->file_pola_kerja, 'r');
		$pola_kerja = json_decode(fread($file_pola_kerja, filesize(FCPATH . '/appconfig/new/' . $pola_kerja->file_pola_kerja)), true);
		fclose($file_pola_kerja);

		$pola_kerja = $pola_kerja['jam_kerja'][strtolower(date('l', strtotime($data['tanggal_dispensasi'])))];

		$jam_awal_pengajuan = strtotime($data['tanggal_dispensasi'] . ' ' . $data['jam_awal_dispensasi']);
		$jam_akhir_pengajuan = strtotime($data['tanggal_dispensasi'] . ' ' . $data['jam_akhir_dispensasi']);

		$jam_awal_pola_kerja = strtotime($data['tanggal_dispensasi'] . ' ' . $pola_kerja['from']);
		$jam_akhir_pola_kerja = strtotime($data['tanggal_dispensasi'] . ' ' . $pola_kerja['to']);

		if ($jam_awal_pengajuan < $jam_awal_pola_kerja || $jam_akhir_pengajuan > $jam_akhir_pola_kerja) {
			$result['status'] = false;
			$result['message'] = 'Jam kerja tidak sesuai. Jam kerja yang berlaku adalah: ' . $pola_kerja['from'].'-'. $pola_kerja['to'];
			$result['data'] = array();
			$is_jam_kerja_sesuai = false;
		}

		if ($is_jam_kerja_sesuai) {
			$update = $this->Db_dml->update('tb_dispensasi', $data, $where);
			if ($update == 1 || $update == 0) {
				$result['status'] = true;
				$result['message'] = 'Data berhasil disimpan.';
				$result['data'] = array();
			} else {
				$result['status'] = false;
				$result['message'] = 'Data gagal disimpan.';
				$result['data'] = array();
			}
		}

		echo json_encode($result);
		exit();
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
		} else {
			$result['status'] = false;
			$result['message'] = 'Data gagal disimpan.';
			$result['data'] = array();
		}
		echo json_encode($result);
	}
	function get_data_user($value = null)
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
			exit();
		}

		$tb = 'tb_dispensasi';
		$j1 = 'tb_user';
		$jj1 = 'tb_user.user_id = tb_dispensasi.user_id';
		$j2 = 'tb_unit';
		$jj2 = 'tb_user.id_unit = tb_unit.id_unit';
		$wr = 'id_channel=' . $id_channel . ' and tb_dispensasi.is_deleted = 0';
		$fld =  array(null, 'nama_user', 'tanggal_dispensasi', 'jam_awal_dispensasi', 'jam_akhir_dispensasi');
		$src = array('nama_user');
		$ordr = array('created_dispensasi' => 'desc');
		$list = $this->Db_datatable->get_datatables3($tb, $j1, $jj1, $j2, $jj2, $wr, $fld, $src, $ordr);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_user;
			$row[] = $field->tanggal_dispensasi;
			$row[] = date('H:i', strtotime($field->jam_awal_dispensasi));
			$row[] = date('H:i', strtotime($field->jam_akhir_dispensasi));

			$row[] = "<a class='btn btn-info btn-sm' href='" . base_url() . "Administrator/dispensasi/edit/" . $field->id_dispensasi . "'><span class='fa fa-pencil-alt'></span></a>
            <a href='#' class='btn btn-danger btn-sm' data-type='ajax-loader' onclick='hapus(" . $field->id_dispensasi . ")'><span class='fa fa-trash'></span></a>";
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Db_datatable->count_all3($tb, $j1, $jj1, $j2, $jj2, $wr, $fld, $src, $ordr),
			"recordsFiltered" => $this->Db_datatable->count_filtered3($tb, $j1, $jj1, $j2, $jj2, $wr, $fld, $src, $ordr),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}
}
