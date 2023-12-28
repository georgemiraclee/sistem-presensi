<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Starter extends CI_Controller
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
    if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
      redirect(base_url());exit();
    }
		$data['data_apel'] = $this->Db_select->select_all_where('tb_apel', 'id_channel='.$id_channel);
		$data['data_area'] = $this->Db_select->select_all_where('tb_lokasi','id_channel='.$id_channel);

		$this->load->view('Administrator/starter', $data);
	}
	public function insert()
	{
		// echo json_encode($_POST); exit();
		$sess = $this->session->userdata('user');
		$id_channel= $sess['id_channel'];
		$insert = array();
		$insert2 = array();
		$insert3 = array();
		$insert4 = array();
		$insert5 = array();

		$insert['ssid_jaringan'] = $this->input->get('ssid_jaringan');
		$insert['lokasi_jaringan'] = $this->input->get('lokasi_jaringan');
		$insert['mac_address_jaringan'] = $this->input->get('mac_address_jaringan');
		$insert['id_channel'] = $id_channel;
		//insertjaringan
		$insertDataJaringan = $this->Db_dml->normal_insert('tb_jaringan', $insert);
		if ($insertDataJaringan) {
			$data['kordinat'] = $this->input->post('kordinat');

			// Load plugin PHPExcel nya
			include APPPATH.'third_party/PHPExcel.php';
			
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
				$excel->setActiveSheetIndex(0)->setCellValue('A'.$no, $value['lat']);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$no, $value['lng']);
				$no++;
			}

			$excel->setActiveSheetIndex(0)->setCellValue('A'.$no, $data['kordinat'][0]['lat']);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$no, $data['kordinat'][0]['lng']);

			// Set orientasi kertas jadi LANDSCAPE
			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

			// Set judul file excel nya
			$excel->getActiveSheet(0)->setTitle("Koordinat Lokasi");
			$excel->setActiveSheetIndex(0);

			// Proses file excel
			$namafile = mdate("%Y%m%d%H%i%s", time());
			$file_name = $id_channel.'_'.$namafile.".csv";
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename="'.$file_name.'"'); // Set nama file excel nya

			$write = PHPExcel_IOFactory::createWriter($excel, 'CSV');
			ob_end_clean();
			$save = $write->save("assets/polygon/".$file_name);

			$insert2['nama_lokasi'] = $this->input->post('area');
			$insert2['url_file_lokasi'] = $file_name;
			$insert2['id_channel'] = $id_channel;

			//insertArea
			$polygon_insert = $this->Db_dml->normal_insert('tb_lokasi', $insert2);
			if ($polygon_insert) {
				//insert data jabatan
				$insert3['nama_jabatan'] = $this->input->post('nama_jabatan');
				$insert3['id_channel'] =$id_channel;
				$insertDataJabatan = $this->Db_dml->normal_insert('tb_jabatan', $insert3);
				if ($insertDataJabatan) {
					$insert4['nama_status_user'] = $this->input->post('nama_status_user');
					$insert4['pemotongan_tpp'] = $this->input->post('pemotongan_tpp');
					$insert4['id_channel']= $id_channel;
					//insert status pekerjaan
					$insertDataStatus = $this->Db_dml->normal_insert('tb_status_user', $insert4);
					if ($insertDataStatus) {
						$namaFile = $id_channel.time().'_auto_respon.txt';
						$file = 'appconfig/new/'.$namaFile;

						$insertDB['nama_pola_kerja'] = $this->input->post('nama_pola_kerja');

						if (isset($_POST['toleransi_keterlambatan'])) {
							$insertDB['toleransi_keterlambatan'] = 1;
							$insertDB['waktu_toleransi_keterlambatan'] = $this->input->post('waktu_toleransi_keterlambatan');
						}else{
							$insertDB['toleransi_keterlambatan'] = 0;
						}

						$day = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
						$libur = 0;
						$hari_kerja = 7;

						for ($i=0; $i < count($day) ; $i++) { 
							if ($this->input->post($day[$i]."A") == 0) {
								$libur = $libur+1;
							}
						}

						$insertDB['lama_pola_kerja'] = $hari_kerja;
						$insertDB['lama_hari_kerja'] = $hari_kerja-$libur;
						$insertDB['lama_hari_libur'] = $libur;
						$insertDB['id_channel'] = $id_channel;

						$txt = array(
								"jam_kerja" => array(
									"monday" => array(
										"libur" => $this->input->post('mondayA'), "to" => $this->input->post('mondayC'), "from" => $this->input->post('mondayB')
									),"tuesday" => array(
										"libur" => $this->input->post('tuesdayA'), "to" => $this->input->post('tuesdayC'), "from" => $this->input->post('tuesdayB')
									),"wednesday" => array(
										"libur" => $this->input->post('wednesdayA'), "to" => $this->input->post('wednesdayC'), "from" => $this->input->post('wednesdayB')
									),"thursday" => array(
										"libur" => $this->input->post('thursdayA'), "to" => $this->input->post('thursdayC'), "from" => $this->input->post('thursdayB')
									),"friday" => array(
										"libur" => $this->input->post('fridayA'), "to" => $this->input->post('fridayC'), "from" => $this->input->post('fridayB')
									),"saturday" => array(
										"libur" => $this->input->post('saturdayA'), "to" => $this->input->post('saturdayC'), "from" => $this->input->post('saturdayB')
									),"sunday" => array(
										"libur" => $this->input->post('sundayA'), "to" => $this->input->post('sundayC'), "from" => $this->input->post('sundayB')
									)
								)
							);
						write_file($file, json_encode($txt));

						$insertDB['file_pola_kerja'] = $namaFile;

						$insertPola = $this->Db_dml->insert('tb_pola_kerja', $insertDB);

						if ($insertPola) {
							$insert5['id_channel'] = $this->input->post('id_channel');
							$insert5['jumlah_cuti_tahunan'] = $this->input->post('cuti');
							$insert5['jatah_cuti_bulanan'] = $this->input->post('batasan');
							if(isset($_POST['aktif_batasan']))
								{
									$hospValue = 1;
								}
								else
								{
									$hospValue = 0;
								}
								$insert5['batasan_cuti'] = $hospValue;
								$insertData = $this->Db_dml->normal_insert('tb_pengaturan_cuti', $insert);
								if ($insertData) {
									$result['status'] = true;
									$result['message'] = ' Seluruh data berhasil disimpan.';
									$result['data'] = array();
								}else{
									$result['status'] = false;
									$result['message'] = 'Data baru gagal disimpan.';
									$result['data'] = array();
								}
						}else{
							$result['status'] = false;
							$result['message'] = 'Data pola kerja gagal disimpan.';
							$result['data'] = array();
						}
					}else{
						$result['status'] = false;
						$result['message'] = 'Data gagal disimpan.';
						$result['data'] = array();
					}													
				}else{
					$result['status'] = false;
					$result['message'] = 'Data jabatan gagal disimpan.';
					$result['data'] = array();
				}
			}else{
				$result['status'] = true;
				$result['message'] = 'Data Area gagal disimpan';
				$result['data'] = array();
			}
		}else{
			$result['status'] = false;
			$result['message'] = 'Data Jaringan Gagal disimpan.';
			$result['data'] = array();
		}
		
		echo json_encode($result);
	}
	public function delete()
	{
		$where['id_apel'] = $this->input->post('id_apel');
		$delete = $this->Db_dml->delete('tb_apel', $where);
		if ($delete == 1) {
			 $pesan = "<div class='alert alert-success alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Berhasil Dihapus.</div>";
                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/apel");exit();
		}else{
			 $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Gagal Dihapus.</div>";
                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/apel");exit();
		}
		echo json_encode($result);
	}
	public function update()
	{
		$sess = $this->session->userdata('user');
		$where['id_apel'] = $this->input->post('id_apel');
		$update = array();
		if ($this->input->post('nama_apel')) {
		    $update['nama_apel'] = $this->input->post('nama_apel');
            $update['tanggal_apel'] = $this->input->post('tanggal_apel');
            $update['jam_mulai'] = $this->input->post('jam_mulai');
            $update['id_lokasi'] = $this->input->post('id_lokasi');
            $update['deskripsi_apel'] = $this->input->post('deskripsi_apel');
		}
		$updateData = $this->Db_dml->update('tb_apel', $update, $where);
        // echo json_encode($updateData);exit();
        if ($updateData) {
            $pesan = "<div class='alert alert-success alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Berhasil Diubah</div>";
            $this->session->set_flashdata('pesan', $pesan);
         	redirect(base_url()."Administrator/apel");exit();
        }else{
            $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Gagal Diubah</div>";
                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/apel");exit();
        }
		echo json_encode($result);
	}
	public function update_status()
	{
		$sess = $this->session->userdata('user');
		$where['id_jabatan'] = $this->input->post('id_jabatan');
		$update['is_aktif'] = $this->input->post('is_aktif');
		$updateData = $this->Db_dml->update('tb_jabatan', $update, $where);
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
	function get_data_user($value = null)
    {
    	$sess = $this->session->userdata('user');
		$id_channel= $sess['id_channel'];
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
        	redirect(base_url());exit();
    	}

        $tb= 'tb_apel';
        $wr= 'id_channel='.$id_channel ;
        $fld =  array(null,'nama_apel','tanggal_apel','jam_mulai','durasi_absen','id_lokasi','deskripsi_apel',null);
        $src = array('nama_apel');
        $ordr = array('created_apel' => 'desc');;
        $list = $this->Db_datatable->get_datatables2($tb,$wr,$fld,$src,$ordr);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
	       
			$selectUser = $this->Db_select->select_where('tb_lokasi','id_lokasi = "'.$field->id_lokasi.'"');
			$lokasi = $selectUser->nama_lokasi;
			 
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nama_apel;
            $row[] = date('d-m-Y', strtotime($field->tanggal_apel));
            $row[] = $field->jam_mulai;
            $row[] = $field->durasi_absen;
            $row[] = $lokasi;
            $row[] = $field->deskripsi_apel;
            $row[] = '<a href="#" style="color: grey" data-toggle="modal" data-target="#updateModal'.$field->id_apel.'"><span class="material-icons">mode_edit</span></a>
                      <a href="#" data-toggle="tooltip" data-placement="top"  id="hapus'.$no.'" title="Hapus Event" data-type="ajax-loader" onclick="hapus('.$field->id_apel.')"><span class="material-icons col-grey"  style="font-size: 20px;">delete</span></a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Db_datatable->count_all2($tb,$wr,$fld,$src,$ordr),
            "recordsFiltered" => $this->Db_datatable->count_filtered2($tb,$wr,$fld,$src,$ordr),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

}