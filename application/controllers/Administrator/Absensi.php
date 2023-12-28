<?php defined('BASEPATH') or exit('No direct script access allowed');

class Absensi extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('global_lib'));
		$this->load->model('DB_super_admin');
		$this->load->model('Db_datatable');
		$this->load->helper(array('form', 'url', 'download'));
		$sess = $this->session->userdata('user');
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
			exit();
		}
		$this->global_lib = new global_lib;
  }
  
	public function listAbsensi()
	{
		$menu['main'] = 'kehadiran';
		$menu['child'] = 'kehadiran_absen_ulang';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/list_absensi');
		$this->load->view('Administrator/footer');
	}

	public function select()
	{
		$sess = $this->session->userdata('user');
		$pg = $this->DB_super_admin->select_absensi_pegawai($sess['id_channel']);

		$count = 1;

		$pegawai = '';
		foreach ($pg as $key => $value) {
			$sekarang = date(now());
			$timestamp = strtotime($value->tanggal_lahir);
			$tgl_lhr = date("d", $timestamp);
			$tgl_skr = date("d", $sekarang);
			$bln_lhr = date("m", $timestamp);
			$bln_skr = date("m", $sekarang);
			if ($tgl_lhr == $tgl_skr && $bln_lhr == $bln_skr) {
				$ultah = '<img src="https://image.flaticon.com/icons/png/512/233/233881.png" style="width:50px; position:fixed">';
			} else {
				$ultah = '';
			}
			if ($count % 4 == 1) {
				if ($count == 1) {
					$tes = "active";
				} else {
					$tes = "";
				}
				$pegawai .= '<div class="item ' . $tes . '">
                                <div class="row"> ';
			}


			if ($value->status_absensi == null || $value->status_absensi == '' || $value->status_absensi == 'Tidak Hadir') {
				$ss = $this->Db_select->select_where('tb_pengajuan', 'user_id	=' . $value->user_id . ' and date_format(tanggal_awal_pengajuan, "%Y-%m-%d") <= date_format(now(), "%Y-%m-%d") and date_format(tanggal_akhir_pengajuan, "%Y-%m-%d") >= date_format(now(), "%Y-%m-%d") and status_approval = 1');

				if ($ss == "" || $ss == null) {
					$datang = "-";

					$class = "danger";

					$ket = "TIDAK HADIR";
				} else {
					$tt = $this->Db_select->select_where('tb_status_pengajuan', 'id_status_pengajuan = ' . $ss->status_pengajuan);
					$datang = "-";

					$class = "info";

					$ket = $tt->nama_status_pengajuan;
				}
			}
			if ($value->status_absensi == 'Tepat Waktu') {
				$datang = $value->waktu_datang;
				$class = "success";
				$ket = "TEPAT WAKTU";
			}
			if ($value->status_absensi == 'Terlambat') {
				$datang = $value->waktu_datang;
				$class = "warning";
				$ket = "TERLAMBAT";
			}

			if ($value->foto_user == null || $value->foto_user == '') {
				$value->foto_user = "default_photo.jpg";
			}
			if ($value->url_file_absensi == null || $value->url_file_absensi == '') {
				$value->url_file_absensi = "default_photo.jpg";
			}

			$filename = 'assets/images/absensi/' . $value->url_file_absensi;

			if (file_exists($filename)) {
				$value->url_file_absensi = $value->url_file_absensi;
			} else {
				$value->url_file_absensi = "default_photo.jpg";
			}

			$nama = $value->nama_user;
			if (strlen($nama) > 25)
				$nama = substr($nama, 0, 23) . '...';
			$pegawai .= '<div class="col-sm-3">
						<div class="col-item">
							' . $ultah . '
							<div class="photo">
								<img src="' . base_url() . 'assets/images/absensi/' . $value->url_file_absensi . '"  class="img-responsive"  style="width:200px; height: 200px; border-radius: 50%;"  border-radius: 50%" alt="a" />
							</div>
							<div class="info">
								<div class="row">
									<div class="price col-md-12">
										<h5 align="center">
											' . $nama . '</h5>

									</div>
								</div>
								<div class="separator clear-left" >
									<p class="btn-add">
										<i class="fa fa-clock-o"></i><a href="http://www.jquery2dotnet.com" class="hidden-sm">Jam Masuk</a></p>
									<p class="btn-details">
										<a href="" class="hidden-sm">' . $datang . '</a></p>
								</div>
								<br>
								<br>
								<button type="button" class="btn btn-block btn-lg btn-' . $class . '">' . $ket . '</button>
							</div>
						</div>
					</div>';
			if ($count % 4 == 0) {
				$pegawai .= '</div>
                                    </div>';
			}
			$count++;
		}
		if ($count % 4 != 1) {
			$pegawai .= '</div>
						</div>';
		}

		$tanggal = date("Y/m/d");
		$select_absen = $this->DB_super_admin->select_absensi_today($tanggal);
		$jml = $this->DB_super_admin->count_absen($tanggal, $sess['id_channel']);
		$jumlah_hadir = $jml->total;
		$jml_telat = $this->DB_super_admin->count_absen_telat2($tanggal, $sess['id_channel']);
		$jml_tepat = $this->DB_super_admin->count_absen_telat3($tanggal, $sess['id_channel']);
		$jumlah_telat = $jml_telat->total;
		$pgw = $this->DB_super_admin->count_pegawai($sess['id_channel']);
		$tidak_hadir = $pgw->total - $jml->total;
		$result['status'] = true;
		$result['message'] = 'Data ditemukan.';
		$result['data'] = $pegawai;
		$result['tepat_waktu'] = $jml_tepat->total;
		$result['jumlah_hadir'] = $jumlah_hadir;
		$result['jumlah_telat'] = $jumlah_telat;
		$result['tidak_hadir'] = $tidak_hadir;
		$result['jumlah_pegawai'] = $pgw->total;
		echo json_encode($result);
		exit();
	}

	public function batalkan()
	{
		$data['id_absensi'] = $this->input->post('id_absensi', true);

		/* proses penghapusan */
		$this->Db_dml->delete('tb_history_absensi', $data);
		$delete = $this->Db_dml->delete('tb_absensi', $data);
		if ($delete) {
			$result['status'] = true;
			$result['message'] = 'Absensi berhasil dihapus';
		} else {
			$result['status'] = false;
			$result['message'] = 'Transaksi gagal dilakukan';
		}

		echo json_encode($result);
	}

	function get_data_user()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		$file = 'appconfig/' . $id_channel . '_auto_respon.txt';

		$date_now = date('Y-m-d');
		$st = 'id_channel = ' . $id_channel . " AND created_absensi BETWEEN '$date_now 00:00:00' AND '$date_now 23:59:59'";
		$list = $this->Db_datatable->get_datatables($st, ['tb_absensi.id_absensi', 'tb_absensi.status_absensi', 'tb_absensi.waktu_keterlambatan', 'tb_absensi.waktu_datang', 'tb_absensi.created_absensi', 'tb_user.nip', 'tb_user.nama_user']);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {
			$no++;
			//batal
			$selectBatal = $this->Db_select->select_where('tb_pembatalan_absensi', ['id_absensi' => $field->id_absensi], ['id_pembatalan_absensi']);
			if ($selectBatal) {
				$batal2 = "Dibatalkan";
			} else {
				$batal2 = '<a href="#"  id="batal' . $no . '" data-type="ajax-loader" onclick="batal(' . $field->id_absensi . ')" ><button class="btn btn-warning btn-sm text-white" type="button"><span class="fa fa-undo-alt"></span> Absen Ulang</button></a>';
			}
			//batal    
			//keterangan
			$keteranganTerlambat = "";
			$statusAbsensi = $field->status_absensi;
			if ($field->status_absensi == "Terlambat") {
				if ($field->waktu_keterlambatan == 0) {
					$jadwal = json_decode(file_get_contents($file))->jam_kerja;
					$day = strtolower(date("l", strtotime($field->waktu_datang)));
					$jadwalNew = date_create($jadwal->$day->from);
					$jam_skrg = date_create(date("H:i", strtotime($field->waktu_datang)));
					$diff  = date_diff($jam_skrg, $jadwalNew);

					if ($diff->h != 0) {
						$keteranganTerlambat .= $diff->h . " Jam ";
					}
					if ($diff->i != 0) {
						$keteranganTerlambat .= $diff->i . " Menit ";
					}
				} else {
					$keteranganTerlambat = $this->global_lib->konversi_detik($field->waktu_keterlambatan);
				}

				$statusAbsensi = $field->status_absensi . " (" . $keteranganTerlambat . ")";
			}

			if ($field->waktu_datang == "" || $field->waktu_datang == null) {
				$waktu_datang = "-";
			} else {
				$waktu_datang = date('h:i', strtotime($field->waktu_datang));
			}

			//keterangan
			$row = array();
			$row[] = $no;
			$row[] = $field->nip;
			$row[] = $field->nama_user;
			$row[] = date('d-m-Y', strtotime($field->created_absensi));
			$row[] = $waktu_datang;
			$row[] = $statusAbsensi;
			$row[] = $batal2;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Db_datatable->count_all($st),
			"recordsFiltered" => $this->Db_datatable->count_filtered($st, ['COUNT(*) total'])->total,
			"data" => $data,
		);

		//output dalam format JSON
		echo json_encode($output);
	}
}
