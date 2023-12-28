<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class remunerasi extends CI_Controller
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
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
        redirect(base_url());exit();
        }

		$data['data_staff'] = $this->Db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit join tb_status_user c on a.status_user = c.id_status_user');

		foreach ($data['data_staff'] as $key => $value) {
			$selectUser = $this->Db_select->select_where('tb_absensi','nip = '.$value->nip);
			if ($selectUser) {
				$value->delete = false;
			}else{
				$value->delete = true;
			}

			$selectJabatan = $this->Db_select->select_where('tb_jabatan','id_jabatan = '.$value->jabatan);

			if ($selectJabatan) {
				$value->jabatan = $selectJabatan->nama_jabatan;
			}else{
				$value->jabatan = "";
			}

			$tanggalEfektif = $this->Db_select->select_where('tb_tunjangan','user_id = '.$value->user_id);

			if ($tanggalEfektif) {
				$value->tanggal_efektif = $tanggalEfektif->masa_berlaku;
			}else{
				$value->tanggal_efektif = "";
			}
		}

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/remunerasi');
		$this->load->view('Administrator/footer');
	}

}