<?php

class pola_kerkar extends CI_Controller
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
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
		}
		$menu['main'] = 'personalia';
		$menu['child'] = 'personalia_pola';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/pola_kerkar');
		$this->load->view('Administrator/footer');
	}

	public function add()
	{
		$sess = $this->session->userdata('user');
		$type = $this->input->get('type', true);

		if ($type) {
			if ($type == 1) {
				$target = 'Semua User';
			} elseif ($type == 2) {
				$target = 'Unit Kerja Terpilih';
				/* get data unit */
				$data['unit'] = $this->Db_select->select_all_where('tb_unit', ['id_channel' => $sess['id_channel'], 'is_aktif' => 1]);
			} else {
				$target = 'Pegawai Terpilih';
				$data['staff'] = $this->Db_select->query_all('select a.* from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = ' . $sess['id_channel'] . ' and a.is_deleted = 0 and a.is_aktif = 1 and a.is_admin = 0 and a.is_superadmin = 0');
			}
			$data['target'] = $target;
			$data['type'] = $type;
			/* start get pola kerja */
			$where['is_default'] = 0;
			$where['is_deleted'] = 0;
			$where['id_channel'] = $sess['id_channel'];

			$data['pola'] = $this->Db_select->select_all_where('tb_pola_kerja', $where);
			$menu['main'] = 'personalia';
			$menu['child'] = 'personalia_pola';
			$data['menu'] = $menu;
			/* end get pola kerja */
			$this->load->view('Administrator/header', $data);
			$this->load->view('Administrator/Pola_kerja/add_pola');
			$this->load->view('Administrator/footer');
		} else {
			$menu['main'] = 'personalia';
			$menu['child'] = 'personalia_pola';
			$data['menu'] = $menu;
			$this->load->view('Administrator/header', $data);
			$this->load->view('Administrator/Pola_kerja/add');
			$this->load->view('Administrator/footer');
		}
	}

	public function kelola_pola($user_id)
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
		}

		$data['user'] = $this->Db_select->select_where('tb_user', 'user_id = "' . $user_id . '"');

		$where['id_channel'] = $id_channel;
		$where['is_default'] = 0;
		$list = $this->Db_select->select_all_where('tb_pola_kerja', $where);
		$data['modalList'] = $this->modalList($list);

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/kelola_pola');
		$this->load->view('Administrator/footer');
	}

	public function modalList($data)
	{
		$modalList = "";

		if ($data) {
			foreach ($data as $key => $value) {
				$file = 'appconfig/new/' . $value->file_pola_kerja;
				if (!file_exists($file)) {
					$file = 'appconfig/new/jadwal_default_1.txt';
				}
				$jadwalKerja = json_decode(file_get_contents($file));

				$hariLibur = "Hari Libur";
				$hariKerja = "Hari Kerja";

				$idClass = 'classBorder';

				if ($jadwalKerja->jam_kerja->monday->libur == 1) {
					$hariSenin = $hariLibur;
					$idSenin = $idClass;
				} else {
					$idSenin = '';
					$hariSenin = $hariKerja;
				}

				if ($jadwalKerja->jam_kerja->tuesday->libur == 1) {
					$hariSelsasa = $hariLibur;
					$idSenin = $idClass;
				} else {
					$hariSelsasa = $hariKerja;
					$idSelasa = '';
				}

				if ($jadwalKerja->jam_kerja->wednesday->libur == 1) {
					$hariRabu = $hariLibur;
					$idRabu = $idClass;
				} else {
					$hariRabu = $hariKerja;
					$idRabu = '';
				}

				if ($jadwalKerja->jam_kerja->thursday->libur == 1) {
					$hariKamis = $hariLibur;
					$idKamis = $idClass;
				} else {
					$hariKamis = $hariKerja;
					$idKamis = '';
				}

				if ($jadwalKerja->jam_kerja->friday->libur == 1) {
					$hariJumat = $hariLibur;
					$idJumat = $idClass;
				} else {
					$hariJumat = $hariKerja;
					$idJumat = '';
				}

				if ($jadwalKerja->jam_kerja->saturday->libur == 1) {
					$hariSabtu = $hariLibur;
					$idSabtu = $idClass;
				} else {
					$hariSabtu = $hariKerja;
					$idSabtu = '';
				}

				if ($jadwalKerja->jam_kerja->sunday->libur == 1) {
					$hariMinggu = $hariLibur;
					$idMinggu = $idClass;
				} else {
					$hariMinggu = $hariKerja;
					$idMinggu = '';
				}

				if ($value->toleransi_keterlambatan == 1) {
					$toleransi = '<tr>
            <td width="200">Toleransi Keterlambatan</td>
            <td width="10">:</td>
            <td>' . $value->waktu_toleransi_keterlambatan . ' Menit</td>
          </tr>';
				} else {
					$toleransi = '';
				}

				$modalList .= '
              <div class="modal fade" id="defaultModal' . $value->id_pola_kerja . '" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title" id="defaultModalLabel">Detail Pola Jadwal Kerja</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              <table>
                                  <tr>
                                      <td width="200">Nama Pola Kerja</td>
                                      <td width="10">:</td>
                                      <td>' . $value->nama_pola_kerja . '</td>
                                  </tr>
                                  <tr>
                                      <td width="200">Lama Pola</td>
                                      <td width="10">:</td>
                                      <td>' . $value->lama_pola_kerja . ' Hari</td>
                                  </tr>
                                  ' . $toleransi . '
                              </table>
                              <table class="table table-striped">
                                  <thead>
                                      <tr>
                                          <th>Hari</th>
                                          <th>Status Kerja</th>
                                          <th>Jam Masuk</th>
                                          <th>Jam Keluar</th>
										  <th>Toleransi</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr class="' . $idSenin . '">
                                          <td>Senin</td>
                                          <td>' . $hariSenin . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->monday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->monday->to . '</td>
										  <td>' . $value->waktu_toleransi_keterlambatan . ' Menit</td>
                                      </tr>
                                      <tr class="' . $idSelasa . '">
                                          <td>Selasa</td>
                                          <td>' . $hariSelsasa . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->tuesday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->tuesday->to . '</td>
										  <td>' . $value->waktu_toleransi_keterlambatan . ' Menit</td>
                                      </tr>
                                      <tr class="' . $idRabu . '">
                                          <td>Rabu</td>
                                          <td>' . $hariRabu . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->wednesday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->wednesday->to . '</td>
										  <td>' . $value->waktu_toleransi_keterlambatan . ' Menit</td>
                                      </tr>
                                      <tr class="' . $idKamis . '">
                                          <td>Kamis</td>
                                          <td>' . $hariKamis . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->thursday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->thursday->to . '</td>
										  <td>' . $value->waktu_toleransi_keterlambatan . ' Menit</td>
                                      </tr>
                                      <tr class="' . $idJumat . '">
                                          <td>Jumat</td>
                                          <td>' . $hariJumat . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->friday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->friday->to . '</td>
										  <td>' . $value->waktu_toleransi_keterlambatan . ' Menit</td>
                                      </tr>
                                      <tr class="' . $idSabtu . '">
                                          <td>Sabtu</td>
                                          <td>' . $hariSabtu . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->saturday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->saturday->to . '</td>
										  <td>' . $value->waktu_toleransi_keterlambatan . ' Menit</td>
                                      </tr>
                                      <tr class="' . $idMinggu . '">
                                          <td>Minggu</td>
                                          <td>' . $hariMinggu . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->sunday->from . '</td>
                                          <td>' . $jadwalKerja->jam_kerja->sunday->to . '</td>
										  <td>' . $value->waktu_toleransi_keterlambatan . ' Menit</td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>';
			}
		}

		return $modalList;
	}

	public function tambah_pola($id = null)
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
		}

		$data['pegawai'] = $this->Db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = "' . $id_channel . '" and a.is_aktif = 1 order by a.nama_user asc');
		$where['is_default'] = 0;
		$where['is_deleted'] = 0;
		$where['id_channel'] = $id_channel;

		$data['pola'] = $this->Db_select->select_all_where('tb_pola_kerja', $where);
		$data['selectUser'] = $id;
		$menu['main'] = 'personalia';
		$menu['child'] = 'personalia_pola';
		$data['menu'] = $menu;

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/tambah_pola_kerkar');
		$this->load->view('Administrator/footer');
	}

	public function selectDay()
	{
		$where['id_pola_kerja'] = $this->input->post('id');

		$selectData = $this->Db_select->select_where('tb_pola_kerja', $where);

		if ($selectData) {
			$file = 'appconfig/new/' . $selectData->file_pola_kerja;
			if (!file_exists($file)) {
				$file = 'appconfig/new/jadwal_default_1.txt';
			}
			$jadwalKerja = json_decode(file_get_contents($file));

			$hariLibur = "Hari Libur";
			$hariKerja = "Hari Kerja";

			$idClass = 'classBorder';

			if ($jadwalKerja->jam_kerja->monday->libur == 1) {
				$hariSenin = $hariLibur;
				$idSenin = $idClass;
			} else {
				$idSenin = '';
				$hariSenin = $hariKerja;
			}

			if ($jadwalKerja->jam_kerja->tuesday->libur == 1) {
				$hariSelsasa = $hariLibur;
				$idSenin = $idClass;
			} else {
				$hariSelsasa = $hariKerja;
				$idSelasa = '';
			}

			if ($jadwalKerja->jam_kerja->wednesday->libur == 1) {
				$hariRabu = $hariLibur;
				$idRabu = $idClass;
			} else {
				$hariRabu = $hariKerja;
				$idRabu = '';
			}

			if ($jadwalKerja->jam_kerja->thursday->libur == 1) {
				$hariKamis = $hariLibur;
				$idKamis = $idClass;
			} else {
				$hariKamis = $hariKerja;
				$idKamis = '';
			}

			if ($jadwalKerja->jam_kerja->friday->libur == 1) {
				$hariJumat = $hariLibur;
				$idJumat = $idClass;
			} else {
				$hariJumat = $hariKerja;
				$idJumat = '';
			}

			if ($jadwalKerja->jam_kerja->saturday->libur == 1) {
				$hariSabtu = $hariLibur;
				$idSabtu = $idClass;
			} else {
				$hariSabtu = $hariKerja;
				$idSabtu = '';
			}

			if ($jadwalKerja->jam_kerja->sunday->libur == 1) {
				$hariMinggu = $hariLibur;
				$idMinggu = $idClass;
			} else {
				$hariMinggu = $hariKerja;
				$idMinggu = '';
			}

			$list = '<table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Hari</th>
                                                <th>Status Kerja</th>
                                                <th>Jam Masuk</th>
                                                <th>Jam Keluar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="' . $idSenin . '">
				                                <td>Senin</td>
				                                <td>' . $hariSenin . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->monday->from . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->monday->to . '</td>
				                            </tr>
				                            <tr id="' . $idSelasa . '">
				                                <td>Selasa</td>
				                                <td>' . $hariSelsasa . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->tuesday->from . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->tuesday->to . '</td>
				                            </tr>
				                            <tr id="' . $idRabu . '">
				                                <td>Rabu</td>
				                                <td>' . $hariRabu . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->wednesday->from . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->wednesday->to . '</td>
				                            </tr>
				                            <tr id="' . $idKamis . '">
				                                <td>Kamis</td>
				                                <td>' . $hariKamis . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->thursday->from . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->thursday->to . '</td>
				                            </tr>
				                            <tr id="' . $idJumat . '">
				                                <td>Jumat</td>
				                                <td>' . $hariJumat . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->friday->from . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->friday->to . '</td>
				                            </tr>
				                            <tr id="' . $idSabtu . '">
				                                <td>Sabtu</td>
				                                <td>' . $hariSabtu . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->saturday->from . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->saturday->to . '</td>
				                            </tr>
				                            <tr id="' . $idMinggu . '">
				                                <td>Minggu</td>
				                                <td>' . $hariMinggu . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->sunday->from . '</td>
				                                <td>' . $jadwalKerja->jam_kerja->sunday->to . '</td>
				                            </tr>
                                        </tbody>
                                    </table>';
		} else {
			$list = "";
		}

		echo $list;
	}

	public function insertData()
	{
		$pegawai = $this->input->post('pegawai');

		for ($i = 0; $i < count($pegawai); $i++) {
			$where['user_id'] = $pegawai[$i];
			$cekData = $this->Db_select->select_where('tb_pola_user', $where);

			$tanggalAwal = date('Y-m-d', strtotime($this->input->post('tanggalAwal')));
			$tanggalAkhir = date('Y-m-d', strtotime($this->input->post('tanggalAkhir')));

			if ($cekData) {
				/* kondisi 1 */
				$kondisi1 = $this->Db_select->query('select * from tb_pola_user where user_id = ' . $pegawai[$i] . ' and start_pola_kerja = "' . $tanggalAwal . '" and end_pola_kerja = "' . $tanggalAkhir . '" order by id_pola_user desc');
				if ($kondisi1) {
					$this->insertPola(1, $kondisi1, $pegawai[$i]);
				}

				/* kondisi 2 */
				$kondisi2 = $this->Db_select->query('select * from tb_pola_user where user_id = ' . $pegawai[$i] . ' and (start_pola_kerja > "' . $tanggalAwal . '" and start_pola_kerja < "' . $tanggalAkhir . '") or (end_pola_kerja > "' . $tanggalAwal . '" and end_pola_kerja < "' . $tanggalAkhir . '") order by id_pola_user desc');

				if ($kondisi2) {
					$this->insertPola(2, $kondisi2, $pegawai[$i]);
				}

				/* kondisi 2 */
				$kondisi3 = $this->Db_select->query('select *from tb_pola_user where user_id = ' . $pegawai[$i] . ' and ("' . $tanggalAwal . '" > start_pola_kerja and "' . $tanggalAwal . '" < end_pola_kerja) and ("' . $tanggalAkhir . '" > start_pola_kerja and "' . $tanggalAkhir . '" < end_pola_kerja) order by id_pola_user desc');

				if ($kondisi3) {
					$this->insertPola(3, $kondisi3, $pegawai[$i]);
				}

				/* kondisi 4 */
				$kondisi4 = $this->Db_select->query('select *from tb_pola_user a where "' . $tanggalAwal . '" > a.end_pola_kerja and a.user_id = "' . $pegawai[$i] . '" order by a.end_pola_kerja desc limit 1');


				if ($kondisi1 == null && $kondisi2 == null && $kondisi3 == null) {
					if ($kondisi4) {
						$this->insertPola(0, null, $pegawai[$i]);
					} else {
						/* UPDATE */
						$whereData['user_id'] = $pegawai[$i];
						$input['id_pola_kerja'] = $this->input->post('pola_kerja');

						$this->Db_dml->update('tb_pola_user', $input, $whereData);
					}
				}
			} else {
				$this->insertPola(0, null, $pegawai[$i]);
			}
		}

		$result['status'] = true;
		$result['message'] = 'Data berhasil disimpan.';
		$result['data'] = array();

		echo json_encode($result);
	}

	public function insertPola($key = null, $data = null, $user_id = null)
	{
		if ($key != 0) {
			if ($key == 1) {
				/* insert */
				$where['id_pola_user'] = $data->id_pola_user;
				$input['user_id'] = $user_id;
				$input['id_pola_kerja'] = $this->input->post('pola_kerja');
				$input['start_pola_kerja'] = date('Y-m-d', strtotime($this->input->post('tanggalAwal')));
				$input['end_pola_kerja'] = date('Y-m-d', strtotime($this->input->post('tanggalAkhir')));

				$this->Db_dml->update('tb_pola_user', $input, $where);
			} elseif ($key == 2) {
				$input = array();

				/* jadwal 1 */
				$input1['user_id'] = $user_id;
				$input1['id_pola_kerja'] = $data->id_pola_kerja;
				$input1['start_pola_kerja'] = $data->start_pola_kerja;
				$input1['end_pola_kerja'] = date('Y-m-d', strtotime('-7 day', strtotime($this->input->post('tanggalAwal'))));
				array_push($input, $input1);

				/* jadwal 2 */
				$input2['user_id'] = $user_id;
				$input2['id_pola_kerja'] = $this->input->post('pola_kerja');
				$input2['start_pola_kerja'] = date('Y-m-d', strtotime($this->input->post('tanggalAwal')));
				$input2['end_pola_kerja'] = date('Y-m-d', strtotime($this->input->post('tanggalAkhir')));
				array_push($input, $input2);

				$this->Db_dml->insert_batch('tb_pola_user', $input);
				$whereDelete['id_pola_user'] = $data->id_pola_user;
				$this->Db_dml->delete('tb_pola_user', $whereDelete);
			} elseif ($key == 3) {
				$input = array();

				/* jadwal 1 */
				$input1['user_id'] = $user_id;
				$input1['id_pola_kerja'] = $data->id_pola_kerja;
				$input1['start_pola_kerja'] = $data->start_pola_kerja;
				$input1['end_pola_kerja'] = date('Y-m-d', strtotime('-7 day', strtotime($this->input->post('tanggalAwal'))));
				array_push($input, $input1);

				/* jadwal 2 */
				$input2['user_id'] = $user_id;
				$input2['id_pola_kerja'] = $this->input->post('pola_kerja');
				$input2['start_pola_kerja'] = date('Y-m-d', strtotime($this->input->post('tanggalAwal')));
				$input2['end_pola_kerja'] = date('Y-m-d', strtotime($this->input->post('tanggalAkhir')));
				array_push($input, $input2);

				/* jadwal 3 */
				$input3['user_id'] = $user_id;
				$input3['id_pola_kerja'] = $data->id_pola_kerja;
				$input3['start_pola_kerja'] = date('Y-m-d', strtotime('+7 day', strtotime($this->input->post('tanggalAkhir'))));
				$input3['end_pola_kerja'] = $data->end_pola_kerja;
				array_push($input, $input3);

				$this->Db_dml->insert_batch('tb_pola_user', $input);
				$whereDelete['id_pola_user'] = $data->id_pola_user;
				$this->Db_dml->delete('tb_pola_user', $whereDelete);
			}
		} else {
			/* insert */
			$input['user_id'] = $user_id;
			$input['id_pola_kerja'] = $this->input->post('pola_kerja');
			$input['start_pola_kerja'] = date('Y-m-d', strtotime($this->input->post('tanggalAwal')));
			$input['end_pola_kerja'] = date('Y-m-d', strtotime($this->input->post('tanggalAkhir')));

			$this->Db_dml->insert('tb_pola_user', $input);
		}
	}

	public function getData()
	{
		$sess = $this->session->userdata('user');
		$where = 'b.id_channel = "' . $sess['id_channel'] . '" and a.is_aktif = 1 and a.is_deleted = 0';
		$columns = array(
			0 =>  'a.nama_user',
			1 =>  'c.nama_status_user',
			2 =>  'e.nama_pola_kerja',
			3 =>  'e.start_pola_kerja',
			4 =>  'end_pola_kerja',
			5 =>  'aksi'
		);
		$limit  = $this->input->post('length');
		$start  = $this->input->post('start');
		$order  = $columns[$this->input->post('order')[0]['column']];
		$dir    = $this->input->post('order')[0]['dir'];
		$totalData = $this->Db_global->allposts_count_all("select a.user_id, a.nama_user, c.nama_status_user, e.nama_pola_kerja, e.lama_pola_kerja, e.start_pola_kerja, end_pola_kerja, e.id_pola_user, e.id_pola_kerja from tb_user a join tb_unit b on a.id_unit = b.id_unit join tb_status_user c on a.status_user = c.id_status_user left outer join (select user_id, nama_pola_kerja, start_pola_kerja, end_pola_kerja, lama_pola_kerja, id_pola_user, i.id_pola_kerja from tb_pola_user h join tb_pola_kerja i on h.id_pola_kerja = i.id_pola_kerja order by id_pola_user) as e on a.user_id = e.user_id where " . $where . " group by a.user_id");
		$totalFiltered = $totalData;

		if (empty($this->input->post('search')['value'])) {
			$posts = $this->Db_global->allposts_all("select a.user_id, a.nama_user, c.nama_status_user, coalesce(e.nama_pola_kerja, 0) as nama_pola_kerja, coalesce(e.lama_pola_kerja, 0) as lama_pola_kerja, coalesce(e.start_pola_kerja, 0) as start_pola_kerja,coalesce(end_pola_kerja, 0) as end_pola_kerja, coalesce(e.id_pola_user, 0) as id_pola_user, coalesce(e.id_pola_kerja, 0) as id_pola_kerja from tb_user a join tb_unit b on a.id_unit = b.id_unit join tb_status_user c on a.status_user = c.id_status_user left outer join (select user_id, nama_pola_kerja, start_pola_kerja, end_pola_kerja, lama_pola_kerja, id_pola_user, i.id_pola_kerja from tb_pola_user h join tb_pola_kerja i on h.id_pola_kerja = i.id_pola_kerja order by id_pola_user) as e on a.user_id = e.user_id where " . $where . " group by a.user_id order by " . $order . " " . $dir . " limit " . $start . "," . $limit);
		} else {
			$search = $this->input->post('search', true)['value'];
			$posts = $this->Db_global->posts_search_all("select a.user_id, a.nama_user, c.nama_status_user, coalesce(e.nama_pola_kerja, 0) as nama_pola_kerja, coalesce(e.lama_pola_kerja, 0) as lama_pola_kerja, coalesce(e.start_pola_kerja, 0) as start_pola_kerja,coalesce(end_pola_kerja, 0) as end_pola_kerja, coalesce(e.id_pola_user, 0) as id_pola_user, coalesce(e.id_pola_kerja, 0) as id_pola_kerja from tb_user a join tb_unit b on a.id_unit = b.id_unit join tb_status_user c on a.status_user = c.id_status_user left outer join (select user_id, nama_pola_kerja, start_pola_kerja,end_pola_kerja, lama_pola_kerja, id_pola_user, i.id_pola_kerja from tb_pola_user h join tb_pola_kerja i on h.id_pola_kerja = i.id_pola_kerja order by id_pola_user) as e on a.user_id = e.user_id where " . $where . " and (a.nama_user like '%" . $search . "%' or c.nama_status_user like '%" . $search . "%' or e.nama_pola_kerja like '%" . $search . "%') group by a.user_id order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
			$totalFiltered = $this->Db_global->posts_search_count_all("select a.user_id, a.nama_user, c.nama_status_user, coalesce(e.nama_pola_kerja, 0) as nama_pola_kerja, coalesce(e.lama_pola_kerja, 0) as lama_pola_kerja, coalesce(e.start_pola_kerja, 0) as start_pola_kerja,coalesce(end_pola_kerja, 0) as end_pola_kerja, coalesce(e.id_pola_user, 0) as id_pola_user, coalesce(e.id_pola_kerja, 0) as id_pola_kerja from tb_user a join tb_unit b on a.id_unit = b.id_unit join tb_status_user c on a.status_user = c.id_status_user left outer join (select user_id, nama_pola_kerja, start_pola_kerja, end_pola_kerja, lama_pola_kerja, id_pola_user, i.id_pola_kerja from tb_pola_user h join tb_pola_kerja i on h.id_pola_kerja = i.id_pola_kerja order by id_pola_user) as e on a.user_id = e.user_id where " . $where . " and (a.nama_user like '%" . $search . "%' or c.nama_status_user like '%" . $search . "%' or e.nama_pola_kerja like '%" . $search . "%') group by a.user_id");
		}


		$data = array();
		if (!empty($posts)) {
			foreach ($posts as $key => $post) {
				$nestedData['nama_user']  = $post->nama_user;
				$nestedData['nama_status_user']  = $post->nama_status_user;
				if ($post->id_pola_kerja != 0) {
					$nestedData['aksi']  = '<a href="' . base_url('Administrator/pola_kerkar/kelola_pola/' . $post->user_id) . '" class="btn btn-primary">Kelola Pola Kerja</a>';
					$nestedData['nama_pola_kerja']  = $post->nama_pola_kerja . " (" . $post->lama_pola_kerja . " Hari)";
					$nestedData['tanggal_berlaku']  = date('d M Y', strtotime($post->start_pola_kerja));
					$nestedData['tanggal_berakhir']  = date('d M Y', strtotime($post->end_pola_kerja));
				} else {
					$nestedData['nama_pola_kerja']  = "";
					$nestedData['tanggal_berlaku']  = "";
					$nestedData['tanggal_berakhir'] = "";
					$nestedData['aksi']  = '<a href="' . base_url('Administrator/pola_kerkar/tambah_pola/' . $post->user_id) . '" class="btn btn-info"><span class="fa fa-plus"></span> Tambah Pola</a>';
				}

				$data[] = $nestedData;
			}
		}
		$json_data = array(
			"draw"            => intval($this->input->post('draw')),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
		);

		echo json_encode($json_data);
	}

	public function kelolaData($user_id)
	{
		$sess = $this->session->userdata('user');
		$where = 'b.id_channel = "' . $sess['id_channel'] . '" and a.is_aktif = 1';
		$columns = array(
			0 =>  'b.nama_pola_kerja',
			1 =>  'a.start_pola_kerja',
			2 =>  'a.end_pola_kerja',
			3 =>  'b.lama_hari_kerja',
			4 =>  'b.lama_hari_libur',
			5 =>  'status',
			6 =>  'aksi'
		);
		$limit  = $this->input->post('length');
		$start  = $this->input->post('start');
		$order  = $columns[$this->input->post('order')[0]['column']];
		$dir    = $this->input->post('order')[0]['dir'];
		$totalData = $this->Db_global->allposts_count_all("select *from tb_pola_user a join tb_pola_kerja b on a.id_pola_kerja = b.id_pola_kerja where a.user_id = '" . $user_id . "'");
		$totalFiltered = $totalData;

		if (empty($this->input->post('search')['value'])) {
			$posts = $this->Db_global->allposts_all("select *from tb_pola_user a join tb_pola_kerja b on a.id_pola_kerja = b.id_pola_kerja where a.user_id = '" . $user_id . "' order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
		} else {
			$search = $this->input->post('search')['value'];
			$posts = $this->Db_global->posts_search_all("select *from tb_pola_user a join tb_pola_kerja b on a.id_pola_kerja = b.id_pola_kerja where a.user_id = '" . $user_id . "' and (b.nama_pola_kerja like '%" . $search . "%' or a.start_pola_kerja like '%" . $search . "%' or a.end_pola_kerja like '%" . $search . "%' or b.lama_hari_kerja like '%" . $search . "%' or b.lama_hari_libur) order by " . $order . " " . $dir . " limit " . $start . "," . $limit . "");
			$totalFiltered = $this->Db_global->posts_search_count_all("select *from tb_pola_user a join tb_pola_kerja b on a.id_pola_kerja = b.id_pola_kerja where a.user_id = '" . $user_id . "' and (b.nama_pola_kerja like '%" . $search . "%' or a.start_pola_kerja like '%" . $search . "%' or a.end_pola_kerja like '%" . $search . "%' or b.lama_hari_kerja like '%" . $search . "%' or b.lama_hari_libur)");
		}

		$data = array();
		if (!empty($posts)) {
			foreach ($posts as $key => $post) {
				$nestedData['pola_kerja']  = $post->nama_pola_kerja;
				$nestedData['tanggal_berlaku']  = date('d M Y', strtotime($post->start_pola_kerja));
				$nestedData['tanggal_berakhir']  = date('d M Y', strtotime($post->end_pola_kerja));
				$nestedData['hari_masuk']  = $post->lama_hari_libur . ' Hari';
				$nestedData['hari_libur']  = $post->lama_hari_kerja . ' Hari';
				$dateNow = date('Y-m-d');
				$dateA = date('Y-m-d', strtotime($post->start_pola_kerja));
				$dateB = date('Y-m-d', strtotime($post->end_pola_kerja));
				if ($dateNow >= $dateA and $dateNow <= $dateB) {
					$nestedData['status']  = 'Aktif';
				} else {
					$nestedData['status']  = '';
				}
				$nestedData['aksi']  = '
    				<a href="#" data-toggle="modal" data-target="#defaultModal' . $post->id_pola_kerja . '" class="btn btn-sm btn-primary"><span class="fa fa-eye"></span></a>
    				<a href="' . base_url('Administrator/pola_kerkar/edit_pola/' . $post->id_pola_user) . '" class="btn btn-sm btn-warning text-white"><span class="fa fa-pencil-alt"></span></a>
    				<a href="#" onclick="hapus(' . $post->id_pola_user . ')" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></a>
				';

				$data[] = $nestedData;
			}
		}
		$json_data = array(
			"draw"            => intval($this->input->post('draw')),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
		);

		echo json_encode($json_data);
	}

	public function delete()
	{
		$where['id_pola_user'] = $this->input->post('id');

		$deleteData = $this->Db_dml->delete('tb_pola_user', $where);

		if ($deleteData) {
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

	public function edit_pola($id)
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		if ($sess['akses'] != "admin_channel" && $sess['akses'] != "admin_utama" && $sess['role_access'] != '1') {
			redirect(base_url());
		}

		$data['data'] = $this->Db_select->query('select *from tb_pola_user a join tb_user b on a.user_id = b.user_id where a.id_pola_user = "' . $id . '"');
		$data['pola'] = $this->Db_select->query_all('select *from tb_pola_kerja where id_channel = "' . $id_channel . '"');

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/edit_pola_kerkar');
		$this->load->view('Administrator/footer');
	}

	public function updateData()
	{
		$user_id = $this->input->post('user_id');
		$where['id_pola_user'] = $this->input->post('id_pola_user');
		$update['id_pola_kerja'] = $this->input->post('pola_kerja');
		$update['start_pola_kerja'] = date('Y-m-d', strtotime($this->input->post('tanggalAwal')));
		$update['end_pola_kerja'] = date('Y-m-d', strtotime($this->input->post('tanggalAkhir')));

		$cekJadwal = $this->Db_select->query('select * from tb_pola_user where user_id = ' . $user_id . ' and (start_pola_kerja > "' . $update['start_pola_kerja'] . '" and start_pola_kerja <= "' . $update['end_pola_kerja'] . '") or (end_pola_kerja > "' . $update['start_pola_kerja'] . '" and end_pola_kerja <= "' . $update['end_pola_kerja'] . '") and id_pola_user != "' . $where['id_pola_user'] . '" order by id_pola_user desc');

		if (!$cekJadwal) {
			$update = $this->Db_dml->update('tb_pola_user', $update, $where);
			if ($update) {
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
			$result['message'] = 'Jadwal sudah ada.';
			$result['data'] = array();
		}

		echo json_encode($result);
	}

	/* start new code */
	public function newInsert()
	{
		$sess = $this->session->userdata('user');
		$type = $this->input->post('type');
		$pola_kerja = $this->input->post('pola_kerja');
		$tanggalAwal = date('Y-m-d', strtotime($this->input->post('tanggalAwal')));
		$tanggalAkhir = date('Y-m-d', strtotime($this->input->post('tanggalAkhir')));

		if ($type == 1) {
			/* get all pegawai */
			$getPegawai = $this->Db_select->query_all('select a.user_id from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = ' . $sess['id_channel'] . ' and a.is_deleted = 0 and a.is_aktif = 1 and a.is_admin = 0 and a.is_superadmin = 0');
			if ($getPegawai) {
				foreach ($getPegawai as $value) {
					$where['user_id'] = $value->user_id;
					$cekData = $this->Db_select->select_where('tb_pola_user', $where);

					if ($cekData) {
						/* kondisi 1 */
						$kondisi1 = $this->Db_select->query('select * from tb_pola_user where user_id = ' . $value->user_id . ' and start_pola_kerja = "' . $tanggalAwal . '" and end_pola_kerja = "' . $tanggalAkhir . '" order by id_pola_user desc');
						if ($kondisi1) {
							$this->insertPola(1, $kondisi1, $value->user_id);
						}

						/* kondisi 2 */
						$kondisi2 = $this->Db_select->query('select * from tb_pola_user where user_id = ' . $value->user_id . ' and (start_pola_kerja > "' . $tanggalAwal . '" and start_pola_kerja < "' . $tanggalAkhir . '") or (end_pola_kerja > "' . $tanggalAwal . '" and end_pola_kerja < "' . $tanggalAkhir . '") order by id_pola_user desc');

						if ($kondisi2) {
							$this->insertPola(2, $kondisi2, $value->user_id);
						}

						/* kondisi 2 */
						$kondisi3 = $this->Db_select->query('select *from tb_pola_user where user_id = ' . $value->user_id . ' and ("' . $tanggalAwal . '" > start_pola_kerja and "' . $tanggalAwal . '" < end_pola_kerja) and ("' . $tanggalAkhir . '" > start_pola_kerja and "' . $tanggalAkhir . '" < end_pola_kerja) order by id_pola_user desc');

						if ($kondisi3) {
							$this->insertPola(3, $kondisi3, $value->user_id);
						}

						/* kondisi 4 */
						$kondisi4 = $this->Db_select->query('select *from tb_pola_user a where "' . $tanggalAwal . '" > a.end_pola_kerja and a.user_id = "' . $value->user_id . '" order by a.end_pola_kerja desc limit 1');


						if ($kondisi1 == null && $kondisi2 == null && $kondisi3 == null) {
							if ($kondisi4) {
								$this->insertPola(0, null, $value->user_id);
							} else {
								/* UPDATE */
								$whereData['user_id'] = $value->user_id;
								$input['id_pola_kerja'] = $pola_kerja;

								$this->Db_dml->update('tb_pola_user', $input, $whereData);
							}
						}
					} else {
						$this->insertPola(0, null, $value->user_id);
					}
				}

				$result['status'] = true;
				$result['message'] = 'Data berhasil disimpan.';
			} else {
				$result['status'] = false;
				$result['message'] = 'Data pegawai tidak ditemukan.';
			}
		} elseif ($type == 2) {
			$id_unit = $this->input->post('id_unit');
			if (count($id_unit)) {
				for ($i = 0; $i < count($id_unit); $i++) {
					$getPegawai = $this->Db_select->query_all('select user_id from tb_user where id_unit = ' . $id_unit[$i] . ' and is_deleted = 0 and is_aktif = 1 and is_admin = 0 and is_superadmin = 0');
					if ($getPegawai) {
						foreach ($getPegawai as $value) {
							$where['user_id'] = $value->user_id;
							$cekData = $this->Db_select->select_where('tb_pola_user', $where);

							if ($cekData) {
								/* kondisi 1 */
								$kondisi1 = $this->Db_select->query('select * from tb_pola_user where user_id = ' . $value->user_id . ' and start_pola_kerja = "' . $tanggalAwal . '" and end_pola_kerja = "' . $tanggalAkhir . '" order by id_pola_user desc');
								if ($kondisi1) {
									$this->insertPola(1, $kondisi1, $value->user_id);
								}

								/* kondisi 2 */
								$kondisi2 = $this->Db_select->query('select * from tb_pola_user where user_id = ' . $value->user_id . ' and (start_pola_kerja > "' . $tanggalAwal . '" and start_pola_kerja < "' . $tanggalAkhir . '") or (end_pola_kerja > "' . $tanggalAwal . '" and end_pola_kerja < "' . $tanggalAkhir . '") order by id_pola_user desc');

								if ($kondisi2) {
									$this->insertPola(2, $kondisi2, $value->user_id);
								}

								/* kondisi 2 */
								$kondisi3 = $this->Db_select->query('select *from tb_pola_user where user_id = ' . $value->user_id . ' and ("' . $tanggalAwal . '" > start_pola_kerja and "' . $tanggalAwal . '" < end_pola_kerja) and ("' . $tanggalAkhir . '" > start_pola_kerja and "' . $tanggalAkhir . '" < end_pola_kerja) order by id_pola_user desc');

								if ($kondisi3) {
									$this->insertPola(3, $kondisi3, $value->user_id);
								}

								/* kondisi 4 */
								$kondisi4 = $this->Db_select->query('select *from tb_pola_user a where "' . $tanggalAwal . '" > a.end_pola_kerja and a.user_id = "' . $value->user_id . '" order by a.end_pola_kerja desc limit 1');


								if ($kondisi1 == null && $kondisi2 == null && $kondisi3 == null) {
									if ($kondisi4) {
										$this->insertPola(0, null, $value->user_id);
									} else {
										/* UPDATE */
										$whereData['user_id'] = $value->user_id;
										$input['id_pola_kerja'] = $pola_kerja;

										$this->Db_dml->update('tb_pola_user', $input, $whereData);
									}
								}
							} else {
								$this->insertPola(0, null, $value->user_id);
							}
						}

						$result['status'] = true;
						$result['message'] = 'Data berhasil disimpan.';
					} else {
						$result['status'] = false;
						$result['message'] = 'Data pegawai tidak ditemukan.';
					}
				}
			} else {
				$result['status'] = false;
				$result['message'] = 'Data unit tidak ditemukan.';
			}
		} else {
			$user_id = $this->input->post('user_id');
			if (count($user_id)) {
				for ($i = 0; $i < count($user_id); $i++) {
					$where['user_id'] = $user_id[$i];
					$cekData = $this->Db_select->select_where('tb_pola_user', $where);

					if ($cekData) {
						/* kondisi 1 */
						$kondisi1 = $this->Db_select->query('select * from tb_pola_user where user_id = ' . $user_id[$i] . ' and start_pola_kerja = "' . $tanggalAwal . '" and end_pola_kerja = "' . $tanggalAkhir . '" order by id_pola_user desc');
						if ($kondisi1) {
							$this->insertPola(1, $kondisi1, $user_id[$i]);
						}

						/* kondisi 2 */
						$kondisi2 = $this->Db_select->query('select * from tb_pola_user where user_id = ' . $user_id[$i] . ' and (start_pola_kerja > "' . $tanggalAwal . '" and start_pola_kerja < "' . $tanggalAkhir . '") or (end_pola_kerja > "' . $tanggalAwal . '" and end_pola_kerja < "' . $tanggalAkhir . '") order by id_pola_user desc');

						if ($kondisi2) {
							$this->insertPola(2, $kondisi2, $user_id[$i]);
						}

						/* kondisi 2 */
						$kondisi3 = $this->Db_select->query('select *from tb_pola_user where user_id = ' . $user_id[$i] . ' and ("' . $tanggalAwal . '" > start_pola_kerja and "' . $tanggalAwal . '" < end_pola_kerja) and ("' . $tanggalAkhir . '" > start_pola_kerja and "' . $tanggalAkhir . '" < end_pola_kerja) order by id_pola_user desc');

						if ($kondisi3) {
							$this->insertPola(3, $kondisi3, $user_id[$i]);
						}

						/* kondisi 4 */
						$kondisi4 = $this->Db_select->query('select *from tb_pola_user a where "' . $tanggalAwal . '" > a.end_pola_kerja and a.user_id = "' . $user_id[$i] . '" order by a.end_pola_kerja desc limit 1');


						if ($kondisi1 == null && $kondisi2 == null && $kondisi3 == null) {
							if ($kondisi4) {
								$this->insertPola(0, null, $user_id[$i]);
							} else {
								/* UPDATE */
								$whereData['user_id'] = $user_id[$i];
								$input['id_pola_kerja'] = $pola_kerja;

								$this->Db_dml->update('tb_pola_user', $input, $whereData);
							}
						}
					} else {
						$this->insertPola(0, null, $user_id[$i]);
					}
				}
				$result['status'] = true;
				$result['message'] = 'Data berhasil disimpan.';
			} else {
				$result['status'] = false;
				$result['message'] = 'Data pegawai tidak ditemukan.';
			}
		}
		echo json_encode($result);
	}
	/* end new code */
}
