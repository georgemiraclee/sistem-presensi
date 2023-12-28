<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */
class approval extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();

		$this->load->library(array('global_lib','loghistory'));
		$this->loghistory = new loghistory();
		
		/* pengecekan token expired */
		$this->global_lib->authentication();
	}

	/* fungsi untuk approval setiap pengajuan */
	public function approvalPengajuan()
	{
		$require = array('id_pengajuan','status');
		$this->global_lib->input($require);

		$id_pengajuan = $this->input->post('id_pengajuan');
		$status = $this->input->post('status');

		if (is_numeric($id_pengajuan)) {
			$getPengajuan = $this->db_select->select_where('tb_pengajuan', 'id_pengajuan = '.$id_pengajuan);

			if ($getPengajuan) {
				/* proses mengubah status user */
				if ($status == 1 || $status == 2) {
					$where['id_pengajuan'] = $id_pengajuan;
					$update['status_approval'] = $status;

					$updateData = $this->db_dml->update('tb_pengajuan', $update, $where);

					if ($updateData) {
						/* next disini catat siapa yang menindaklanjuti pengajuan ini */
						if ($status == 1) {
							$message = "Data pengajuan anda telah disetujui";
						}elseif ($status == 2) {
							$message = "Data pengajuan anda ditolak";
						} else {
							$message = "Data berhasil di ubah";
						}

						$this->result = array(
							'status' => true,
							'message' => $message,
							'data' => null
						);
					} else {
						$this->result = array(
							'status' => false,
							'message' => 'Data gagal diubah',
							'data' => null
						);
					}
				}else{
					$this->result = array(
						'status' => false,
						'message' => 'Format status salah',
						'data' => null
					);
				}
			}else{
				$this->result = array(
					'status' => false,
					'message' => 'Data pengajuan tidak ditemukan',
					'data' => null
				);
			}
		}else{
			$this->result = array(
				'status' => false,
				'message' => 'Data pengajuan tidak ditemukan',
				'data' => null
			);
		}

		$this->loghistory->createLog($this->result);
		echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}

	public function approvalLembur()
	{
		$require = array('id_lembur','status');
		$this->global_lib->input($require);

		$id_lembur = $this->input->post('id_lembur');
		$status = $this->input->post('status');

		if (is_numeric($id_lembur)) {
			$getLembur = $this->db_select->select_where('tb_lembur', 'id_lembur = '.$id_lembur);

			if ($getLembur) {
				/* proses mengubah status user */
				if ($status == 1 || $status == 2) {
					$where['id_lembur'] = $id_lembur;
					$update['status'] = $status;

					$updateData = $this->db_dml->update('tb_lembur', $update, $where);

					if ($updateData) {
						/* next disini catat siapa yang menindaklanjuti pengajuan ini */
						if ($status == 1) {
							$message = "Data pengajuan anda telah disetujui";
						}elseif ($status == 2) {
							$message = "Data pengajuan anda ditolak";
						} else {
							$message = "Data berhasil di ubah";
						}

						$this->result = array(
							'status' => true,
							'message' => $message,
							'data' => null
						);
					} else {
						$this->result = array(
							'status' => false,
							'message' => 'Data gagal diubah',
							'data' => null
						);
					}
				}else{
					$this->result = array(
						'status' => false,
						'message' => 'Format status salah',
						'data' => null
					);
				}
			}else{
				$this->result = array(
					'status' => false,
					'message' => 'Data lembur tidak ditemukan',
					'data' => null
				);
			}
		}else{
			$this->result = array(
				'status' => false,
				'message' => 'Data lembur tidak ditemukan',
				'data' => null
			);
		}

		$this->loghistory->createLog($this->result);
		echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}

	/* fungsi untuk mendapatkan semua list data pengajuan pegawai */
	public function listPengajuan()
	{
		$require = array('page','status');
		$this->global_lib->input($require);

		$page = $this->input->post('page');
		$limit = 15;

		if (is_numeric($page) && $page > 0) {
			$start = ($page - 1) * $limit;
			$getUser = $this->global_lib->getDataUser($this->input->post('nip'));

			/* menentukan id status */
			$status = $this->input->post('status');
			if ($status == "cuti") {
				$status = "and c.nama_status_pengajuan in('Cuti', 'Cuti Tahunan')";
			}elseif ($status == "izin") {
				$status = "and c.nama_status_pengajuan = 'Izin'";
			}elseif ($status == "sakit") {
				$status = "and c.nama_status_pengajuan = 'Sakit'";
			}else{
				$status = "and c.nama_status_pengajuan not in ('Cuti','Cuti Tahunan','Izin','Sakit')";
			}

			$getDataPengajuan = $this->db_select->query_all('select a.*, b.nama_user, c.nama_status_pengajuan, b.foto_user, b.nip, d.nama_jabatan from tb_pengajuan a join tb_user b on a.user_id = b.user_id join tb_status_pengajuan c on a.status_pengajuan = c.id_status_pengajuan join tb_jabatan d on b.jabatan = d.id_jabatan where b.id_parent = "'.$getUser->user_id.'" '.$status.' order by a.status_approval asc');
			$totalDataPengajuan = $this->db_select->query('select count(*) total from tb_pengajuan a join tb_user b on a.user_id = b.user_id join tb_status_pengajuan c on a.status_pengajuan = c.id_status_pengajuan where b.id_parent = "'.$getUser->user_id.'" '.$status.' order by a.id_pengajuan desc');

			if ($getDataPengajuan) {
				foreach ($getDataPengajuan as $key => $value) {
					if ($value->url_file_pengajuan != "" || $value->url_file_pengajuan != null) {
						$value->url_file_pengajuan = image_url()."/images/pengajuan_sakit/".$value->url_file_pengajuan;
					}

					// echo json_encode($value); exit();

					if ($value->foto_user != "" || $value->foto_user != null) {
						$url = realpath('../assets/images/member-photos/'.$value->foto_user);

						if (file_exists($url)) {
							$value->foto_user = image_url()."images/member-photos/".$value->foto_user;
						}else{
							$value->foto_user = image_url()."images/member-photos/default_photo.jpg";
						}
					}else{
						$value->foto_user = image_url()."images/member-photos/default_photo.jpg";
					}
				}
				$this->result = array(
					'status' => true,
					'message' => 'Data ditemukan',
					'total' => $totalDataPengajuan->total,
					'data' => $getDataPengajuan
				);
			}else{
				$this->result = array(
					'status' => false,
					'message' => 'Data tidak ditemukan',
					'data' => null
				);
			}
		} else{
			$this->result = array(
				'status' => false,
				'message' => 'Data tidak ditemukan',
				'data' => null
			);
		}

		$this->loghistory->createLog($this->result);
		echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}

	/* fungsi untuk mendapatkan semua list data lembur pegawai */
	public function listLembur()
	{
		$require = array('page');
		$this->global_lib->input($require);

		$page = $this->input->post('page');
		$limit = 15;

		if (is_numeric($page) && $page > 0) {
			$start = ($page - 1) * $limit;
			$getUser = $this->global_lib->getDataUser($this->input->post('nip'));

			/* get data lembur */
			$getLembur = $this->db_select->query_all('select a.id_lembur, a.user_id, a.tanggal_lembur, a.lama_lembur, a.keterangan, a.status, b.nip, b.nama_user, b.foto_user, c.nama_jabatan from tb_lembur a join tb_user b on a.user_id = b.user_id join tb_jabatan c on b.jabatan = c.id_jabatan where b.id_parent = "'.$getUser->user_id.'" order by a.status desc limit '.$start.','.$limit);
			$totalLembur = $this->db_select->query('select count(*) total from tb_lembur a join tb_user b on a.user_id = b.user_id where b.id_parent = "'.$getUser->user_id.'" order by a.status desc');

			if ($getLembur) {
				foreach ($getLembur as $key => $value) {
					if ($value->foto_user != "" || $value->foto_user != null) {
						$url = realpath('../assets/images/member-photos/'.$value->foto_user);

						if (file_exists($url)) {
							$value->foto_user = image_url()."images/member-photos/".$value->foto_user;
						}else{
							$value->foto_user = image_url()."images/member-photos/default_photo.jpg";
						}
					}else{
						$value->foto_user = image_url()."images/member-photos/default_photo.jpg";
					}
				}
				$this->result = array(
		            'status' => true,
		            'message' => 'Data ditemukan',
		            'total' => $totalLembur->total,
		            'data' => $getLembur
		        );
			}else{
				$this->result = array(
					'status' => false,
					'message' => 'Data tidak ditemukan',
					'data' => null
				);
			}
		} else{
			$this->result = array(
				'status' => false,
				'message' => 'Data tidak ditemukan',
				'data' => null
			);
		}

		$this->loghistory->createLog($this->result);
		echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}
}