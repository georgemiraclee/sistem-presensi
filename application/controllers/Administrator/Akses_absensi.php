<?php defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class akses_absensi extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('ceksession', 'global_lib'));
		$this->load->model('Db_datatable');

		$this->global_lib = new global_lib();

		$this->ceksession->login();
	}

	public function index()
	{
		$sess = $this->session->userdata('user');
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
			exit();
		}

		$menu['main'] = 'akses_absensi';
		$menu['child'] = '';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/akses_absensi');
		$this->load->view('Administrator/footer');
	}

	public function add()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		$data_pegawai = $this->Db_select->query_all('select *from tb_user b join tb_unit c on b.id_unit =c.id_unit where id_channel = ' . $id_channel . ' and is_admin = 0 order by nama_user');
		$list_pegawai = "";
		foreach ($data_pegawai as $key => $value) {
			$cek = $this->Db_select->query('select *from tb_akses_absensi where date_format(tanggal_akhir,"%Y-%m-%d") >= date_format(now(),"%Y-%m-%d") and user_id = "' . $value->user_id . '"');
			if (!$cek) {
				$list_pegawai .= "<option value='" . $value->user_id . "'>" . $value->nama_user . " (" . $value->nip . ")</option>";
			}
		}
		$data['list_pegawai'] = $list_pegawai;
		$data['data_opd'] = $this->Db_select->select_all_where('tb_unit', 'id_channel=' . $id_channel);
		$menu['main'] = 'akses_absensi';
		$menu['child'] = '';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/tambah_akses_absensi');
		$this->load->view('Administrator/footer');
	}

	public function insert()
	{
		$sess = $this->session->userdata('user');

		$coordinates = $this->input->post('kordinat');
		$coordinates = [$coordinates['lng'], $coordinates['lat']]; // [lon, lat]
		$radius = $this->input->post('radius'); // in meters
		$numberOfEdges = 32; // optional that defaults to 32

		$result2 = $this->global_lib->convert($coordinates, $radius, $numberOfEdges);

		$data['kordinat'] = $result2['coordinates'][0];
		$id_channel = $sess['id_channel'];

		// Load plugin PHPExcel nya
		include APPPATH . 'third_party/PHPExcel.php';

		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()->setCreator('Pressensi')
			->setLastModifiedBy('Pressensi')
			->setTitle("koordinat lokasi")
			->setSubject("koordinat")
			->setDescription("koordinat lokasi")
			->setKeywords("koordinat");

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "Lat"); // Set kolom A3 dengan tulisan "NO"
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Lng"); // Set kolom B3 dengan tulisan "NIS"

		/* isi data dari data koordinat */
		$no = 2;
		foreach ($data['kordinat'] as $key => $value) {
			$excel->setActiveSheetIndex(0)->setCellValue('A' . $no, $value[1]);
			$excel->setActiveSheetIndex(0)->setCellValue('B' . $no, $value[0]);
			$no++;
		}

		$excel->setActiveSheetIndex(0)->setCellValue('A' . $no, $data['kordinat'][0]['lat']);
		$excel->setActiveSheetIndex(0)->setCellValue('B' . $no, $data['kordinat'][0]['lng']);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Koordinat Lokasi");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		$namafile = mdate("%Y%m%d%H%i%s", time());
		$file_name = $id_channel . '_' . $namafile . "_akses.csv";
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $file_name . '"'); // Set nama file excel nya

		$write = PHPExcel_IOFactory::createWriter($excel, 'CSV');
		ob_end_clean();
		$write->save("assets/polygon/" . $file_name);

		$insert['user_id'] = $this->input->post('user_id');
		$insert['tanggal_akhir'] = $this->input->post('tanggal_akhir');
		$insert['url_file_lokasi'] = $file_name;
		$insert['lat'] = $this->input->post('kordinat')['lat'];
		$insert['lng'] = $this->input->post('kordinat')['lng'];
		$insert['radius'] = $this->input->post('radius');

		$insertData = $this->Db_dml->normal_insert('tb_akses_absensi', $insert);
		if ($insertData == 1) {
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
		$where['id_akses_absensi'] = $this->input->post('id_akses_absensi');
		$delete = $this->Db_dml->update('tb_akses_absensi', ['is_deleted' => 1], $where);
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
		$data['akses_absensi'] = $this->Db_select->select_where('tb_akses_absensi', 'id_akses_absensi = "' . $id . '"');
		$data['list_pegawai'] = $this->Db_select->query_all('select *from tb_user where is_admin = 0 order by nama_user');
		$data['data_opd'] = $this->Db_select->select_all('tb_unit');
		$data['id_unit'] = json_decode($data['akses_absensi']->id_unit);

		$data['data'] = $this->Db_select->select_where('tb_akses_absensi', 'id_akses_absensi = "' . $id . '"');

		$data['coor'] = array();
		if (($handle = fopen(base_url() . "assets/polygon/" . $data['data']->url_file_lokasi, "r")) !== FALSE) {
			$index = 0;
			$ctr = 0;
			while (($lock = fgetcsv($handle, 1000, ",")) !== FALSE) {
				if ($ctr != 0) {
					$num = count($lock);
					for ($c = 0; $c < $num; $c++) {
						if ($c == 0) {
							if (floatval($lock[$c]) != 0) {
								$data['coor'][$index]['lat'] = floatval($lock[$c]);
							}
						} else {
							if (floatval($lock[$c]) != 0) {
								$data['coor'][$index]['lng'] = floatval($lock[$c]);
							}
						}
					}
					$index++;
				}
				$ctr++;
			}
		}


		$menu['main'] = 'akses_absensi';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/edit_akses_absensi');
		$this->load->view('Administrator/footer');
	}
	public function update()
	{
		$sess = $this->session->userdata('user');
		$where['id_akses_absensi'] = $this->input->post('id_akses_absensi');
		$getData = $this->Db_select->select_where('tb_akses_absensi', $where);
		if ($getData) {
			$coordinates = $this->input->post('kordinat');
			$coordinates = [$coordinates['lng'], $coordinates['lat']]; // [lon, lat]
			$radius = $this->input->post('radius'); // in meters
			$numberOfEdges = 32; // optional that defaults to 32

			$result2 = $this->global_lib->convert($coordinates, $radius, $numberOfEdges);
			$data['kordinat'] = $result2['coordinates'][0];

			$id_channel = 1;

			// Load plugin PHPExcel nya
			include APPPATH . 'third_party/PHPExcel.php';

			// Panggil class PHPExcel nya
			$excel = new PHPExcel();

			// Settingan awal fil excel
			$excel->getProperties()->setCreator('Pressensi')
				->setLastModifiedBy('Pressensi')
				->setTitle("koordinat lokasi")
				->setSubject("koordinat")
				->setDescription("koordinat lokasi")
				->setKeywords("koordinat");

			// Buat header tabel nya pada baris ke 3
			$excel->setActiveSheetIndex(0)->setCellValue('A1', "Lat"); // Set kolom A3 dengan tulisan "NO"
			$excel->setActiveSheetIndex(0)->setCellValue('B1', "Lng"); // Set kolom B3 dengan tulisan "NIS"

			/* isi data dari data koordinat */
			$no = 2;
			foreach ($data['kordinat'] as $key => $value) {
				$excel->setActiveSheetIndex(0)->setCellValue('A' . $no, $value[1]);
				$excel->setActiveSheetIndex(0)->setCellValue('B' . $no, $value[0]);
				$no++;
			}

			$excel->setActiveSheetIndex(0)->setCellValue('A' . $no, $data['kordinat'][0]['lat']);
			$excel->setActiveSheetIndex(0)->setCellValue('B' . $no, $data['kordinat'][0]['lng']);


			// Set orientasi kertas jadi LANDSCAPE
			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

			// Set judul file excel nya
			$excel->getActiveSheet(0)->setTitle("Koordinat Lokasi");
			$excel->setActiveSheetIndex(0);

			// Proses file excel
			$file_name = $getData->url_file_lokasi;
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename="' . $file_name . '"'); // Set nama file excel nya

			$write = PHPExcel_IOFactory::createWriter($excel, 'CSV');
			ob_end_clean();
			$write->save("assets/polygon/" . $file_name);

			$insert['user_id'] = $this->input->post('user_id');
			$insert['tanggal_akhir'] = $this->input->post('tanggal_akhir');
			$insert['url_file_lokasi'] = $file_name;
			$insert['lat'] = $this->input->post('kordinat')['lat'];
			$insert['lng'] = $this->input->post('kordinat')['lng'];
			$insert['radius'] = $this->input->post('radius');

			$update = $this->Db_dml->update('tb_akses_absensi', $insert, $where);
			if ($update == 1 || $update == 0) {
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

	public function get_data_user($value = null)
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
			exit();
		}

		$tb = 'tb_akses_absensi';
		$j1 = 'tb_user';
		$jj1 = 'tb_user.user_id = tb_akses_absensi.user_id';
		$j2 = 'tb_unit';
		$jj2 = 'tb_user.id_unit = tb_unit.id_unit';
		$wr = 'date_format(tb_akses_absensi.tanggal_akhir,"%Y-%m-%d") >= date_format(now(), "%Y-%m-%d") and id_channel=' . $id_channel. ' and tb_akses_absensi.is_deleted = 0';
		$fld =  array(null, 'nama_user', 'tanggal_akhir', 'created_akses_absensi', 'nama_unit');
		$src = array('nama_user');
		$ordr = array('created_akses_absensi' => 'desc');
		$list = $this->Db_datatable->get_datatables3($tb, $j1, $jj1, $j2, $jj2, $wr, $fld, $src, $ordr);

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {
			$getTanggal = $this->Db_select->select_where('tb_akses_absensi', ['id_akses_absensi' => $field->id_akses_absensi]);
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->nama_user;
			$row[] = $field->url_file_lokasi;
			$row[] = date('d M Y', strtotime($getTanggal->tanggal_akhir));
			$row[] = "
        <a href='" . base_url() . "Administrator/akses_absensi/edit/" . $field->id_akses_absensi . "' class='btn btn-info btn-sm'><span class='fa fa-pencil-alt'></span></a>
        <a href='#' data-type='ajax-loader' onclick='hapus(" . $field->id_akses_absensi . ")' class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>";
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
