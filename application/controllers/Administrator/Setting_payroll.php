<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class setting_payroll extends CI_Controller
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
		$que = $this->Db_select->select_where('pengaturan_payroll','id_channel='.$id_channel);

		// echo json_encode($que);exit();

		if ($que == null) {
			$data['judul']="<span style='color:red;'>(Anda belum melakukan konfigurasi)</span>";
			$data['upah_minimum'] = "3100000";
			$data['nilai_pengali'] = "8000000";
			$data['jkk'] = 'checked="checked"';
			$data['jkk2'] = "";
			$data['jkk3'] = "";
			$data['jkk4'] = "";
			$data['jkk5'] = "";
			$data['jkm'] = "0.3";
			$data['jht'] = "2";
			$data['jht_perusahaan'] = "3.7";
			$data['jp'] = "1";
			$data['jp_perusahaan'] = "2";
			$data['jk'] = "1";
			$data['jk_perusahaan'] = "4";
			$data['metode_pph'] = 'checked="checked"';
			$data['metode_pph2'] = "";
			$data['metode_pph3'] = "";
			$data['ptkp_pribadi'] = "54000000";
			$data['ptkp_tanggungan'] = "";
		}else{
			//cek jkk	

			

				if ($que->jkk == 0.24) {
					$data['jkk'] = 'checked="checked"';	
				}else{
					$data['jkk'] = '';	
				}
				if ($que->jkk == 0.54) {
					$data['jkk2'] = 'checked="checked"';	
				}else{
					$data['jkk2'] = '';	
				}
				if ($que->jkk == 0.89) {
					$data['jkk3'] = 'checked="checked"';	
				}else{
					$data['jkk3'] = '';	
				}
				if ($que->jkk == 1.27) {
					$data['jkk4'] = 'checked="checked"';	
				}else{
					$data['jkk4'] = '';	
				}
				if ($que->jkk == 1.74) {
					$data['jkk5'] = 'checked="checked"';	
				}else{
					$data['jkk5'] = '';	
				}
			//cek jkk end
			//cek metode
			
				if ($que->metode_pph == "Gross Up Method") {
					$data['metode_pph'] = 'checked="checked"';	
				}else{
					$data['metode_pph'] = '';	
				}
				if ($que->metode_pph == "Gross Method") {
					$data['metode_pph2'] = 'checked="checked"';	
				}else{
					$data['metode_pph2'] = '';	
				}
				if ($que->metode_pph == "Nett Method") {
					$data['metode_pph3'] = 'checked="checked"';	
				}else{
					$data['metode_pph3'] = '';	
				}
			//cek metodeend	
			$data['judul'] = "";
			$data['upah_minimum'] = $que->upah_minimum;
			$data['nilai_pengali'] = $que->nilai_pengali;
			$data['jkm'] = $que->jkm;
			$data['jht'] = $que->jht;
			$data['jht_perusahaan'] = $que->jht_perusahaan;
			$data['jp'] = $que->jp;
			$data['jp_perusahaan'] = $que->jp_perusahaan;
			$data['jk'] = $que->jk;
			$data['jk_perusahaan'] = $que->jk_perusahaan;
			$data['ptkp_pribadi'] = $que->ptkp_pribadi;
			$data['ptkp_tanggungan'] = $que->ptkp_tanggungan;
		}



		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/setting_payroll');
	}
	public function insert()
	{
		$sess = $this->session->userdata('user');
		$id_channel= $sess['id_channel'];
		$where['id_channel'] =$id_channel;
		$insert = array();
			$insert['id_channel'] =$id_channel;
			$insert['upah_minimum'] = $this->input->post('upah_minimum');
			$insert['nilai_pengali'] = $this->input->post('nilai_pengali');
			$insert['jkk'] = $this->input->post('jkk');
			$insert['jkm'] = $this->input->post('jkm');
			$insert['jht'] = $this->input->post('jht');
			$insert['jht_perusahaan'] = $this->input->post('jht_perusahaan');
			$insert['jp'] = $this->input->post('jp');
			$insert['jp_perusahaan'] = $this->input->post('jp_perusahaan');
			$insert['jk'] = $this->input->post('jk');
			$insert['jk_perusahaan'] = $this->input->post('jk_perusahaan');
			$insert['metode_pph'] = $this->input->post('metode_pph');
			$insert['ptkp_pribadi'] = $this->input->post('ptkp_pribadi');
			$insert['ptkp_tanggungan'] = $this->input->post('ptkp_tanggungan');

			// echo json_encode($insert);exit();

		$is_find = $this->Db_select->select_where('pengaturan_payroll', $where);


		if (!$is_find) {
			if (count($insert) > 0) {
				$insertData = $this->Db_dml->normal_insert('pengaturan_payroll', $insert);
				if ($insertData) {
					$result['status'] = true;
	                $result['message'] = 'Data berhasil disimpan.';
	                $result['data'] = array();
				}else{
					$result['status'] = false;
	                $result['message'] = 'Data gagal disimpan.';
	                $result['data'] = array();
				}
			}
			else{
				$result['status'] = false;
	            $result['message'] = 'Data gagal disimpan.';
	            $result['data'] = array();
			}
		}

		else{
			if (count($insert) > 0) {
				$updateData = $this->Db_dml->update('pengaturan_payroll', $insert, $where);
				if ($updateData) {
					$result['status'] = true;
	                $result['message'] = 'Data berhasil diubah.';
	                $result['data'] = array();
				}else{
					$result['status'] = false;
	                $result['message'] = 'Data gagal diubah.';
	                $result['data'] = array();
				}
			}
			else{
				$result['status'] = false;
	            $result['message'] = 'Data gagal diubah.';
	            $result['data'] = array();
			}
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

}