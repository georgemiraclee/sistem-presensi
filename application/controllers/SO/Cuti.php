<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class cuti extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('ceksession','global_lib'));
		$this->ceksession->login();

		$this->global_lib = new global_lib;
	}

	public function index()
	{
		 $sess = $this->session->userdata('user');
        if ($sess['akses'] =="admin_channel"||$sess['akses'] =="sekda"||$sess['akses'] =="staff") {
        redirect(base_url());exit();
        }
        $parrent=$sess['id_user'];

		$data['data_cuti'] = $this->Db_select->query_all('select *from tb_pengajuan a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where id_parent = '.$parrent.'');

		$this->load->view('SO/header', $data);
		$this->load->view('SO/cuti');
		$this->load->view('SO/footer');
	}

	public function insert()
	{
		$sess = $this->session->userdata('user');
		$insert = array();

		$insert['id_channel'] = $sess['id_channel'];
		$insert['nama_unit'] = $this->input->post('nama_unit');
		$insert['alamat_unit'] = $this->input->post('alamat_unit');

       	if($_FILES['userfile']['name']){
       		$this->load->library('upload');
	        $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
	        $config['upload_path'] = './assets/images/unit'; //path folder
	        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
	        $config['max_size'] = '2048'; //maksimum besar file 2M
	        $config['file_name'] = $nmfile; //nama yang terupload nantinya

	        $this->upload->initialize($config);

       		if ($this->upload->do_upload('userfile')){
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
			}else{
				$result['status'] = false;
                $result['message'] = 'Data gagal disimpan.';
                $result['data'] = array();
			}
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}

		echo json_encode($result);
	}

	public function delete()
	{
		$where['id_unit'] = $this->input->post('id_unit');

		$delete = $this->Db_dml->delete('tb_unit', $where);

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

	public function update()
	{
		$sess = $this->session->userdata('user');
		$where['id_unit'] = $this->input->post('id_unit');
		$update = array();

		if ($this->input->post('nama_unit')) {
			$update['nama_unit'] = $this->input->post('nama_unit');
		}

		if ($this->input->post('alamat_unit')) {
			$update['alamat_unit'] = $this->input->post('alamat_unit');
		}

		if($_FILES['userfile']['name']){
       		$this->load->library('upload');
	        $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
	        $config['upload_path'] = './assets/images/unit'; //path folder
	        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
	        $config['max_size'] = '2048'; //maksimum besar file 2M
	        $config['file_name'] = $nmfile; //nama yang terupload nantinya

	        $this->upload->initialize($config);

       		if ($this->upload->do_upload('userfile')){
				$gbr = $this->upload->data();
			   	$name3 = $gbr['file_name'];
			   	$update['icon_unit'] = $name3;
			}
       	}

		if (count($update) > 0) {
			$updateData = $this->Db_dml->update('tb_unit', $update, $where);

			if ($updateData) {
				$result['status'] = true;
                $result['message'] = 'Data berhasil disimpan.';
                $result['data'] = array();
			}else{
				$result['status'] = false;
                $result['message'] = 'Data gagal disimpan.';
                $result['data'] = array();
			}
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}

		echo json_encode($result);
	}

	public function update_status()
	{
		$sess = $this->session->userdata('user');
		$where['id_pengajuan'] = $this->input->post('id');
		$update['status_approval'] = 1;

		$updateData = $this->Db_dml->update('tb_pengajuan', $update, $where);
		$getData = $this->Db_select->query('select b.nama_status_pengajuan from tb_pengajuan a join tb_status_pengajuan b on a.status_pengajuan = b.id_status_pengajuan where a.id_pengajuan = "'.$this->input->post('id').'"');

		if ($updateData) {
			$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();

            $getUser = $this->Db_select->select_where('tb_pengajuan', $where)->user_id;
			$this->global_lib->send_notification_user($getUser, 'acc_pengajuan');

			// FCM
			$message = "Pengajuan  ".$getData->nama_status_pengajuan." Anda Telah Disetujui";
			$this->global_lib->sendFCM('Approval Pengajuan '.$getData->nama_status_pengajuan, $message, $getUser);
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}

		echo json_encode($result);
	}
	public function update_status2()
	{
		$sess = $this->session->userdata('user');
		$where['id_pengajuan'] = $this->input->post('id');
		$update['status_approval'] = 2;

		$updateData = $this->Db_dml->update('tb_pengajuan', $update, $where);
		$getData = $this->Db_select->query('select b.nama_status_pengajuan from tb_pengajuan a join tb_status_pengajuan b on a.status_pengajuan = b.id_status_pengajuan where a.id_pengajuan = "'.$this->input->post('id').'"');

		if ($updateData) {
			$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();

            $getUser = $this->Db_select->select_where('tb_pengajuan', $where)->user_id;
			$this->global_lib->send_notification_user($getUser, 'reject_pengajuan');

			// FCM
			$message = "Pengajuan  ".$getData->nama_status_pengajuan." Anda Tidak Disetujui";
			$this->global_lib->sendFCM('Approval Pengajuan '.$getData->nama_status_pengajuan,$message, $getUser);
		}else{
			$result['status'] = false;
            $result['message'] = 'Data gagal disimpan.';
            $result['data'] = array();
		}

		echo json_encode($result);
	}
	public function detail_cuti($id)
	{
		$data_cuti = $this->Db_select->query_all('select *from tb_pengajuan a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where id_pengajuan = '.$id.'');
		 foreach ($data_cuti as $key => $value) {
		 	$data_jabatan=$this->Db_select->query('select * from tb_jabatan where id_jabatan = '.$value->jabatan.'');
		 	$jabatan = $data_jabatan->nama_jabatan;
		 	$data_status=$this->Db_select->query('select * from tb_status_user where id_status_user ='.$value->status_user.' ');
		 	$status=$data_status->nama_status_user;

		 	 if ($value->status_approval == 1) {
                $value->status_approval = '<span class="badge bg-green">Approval</span>';
                $data['tombol'] = '';
            }else if ($value->status_approval == 2) {
                $value->status_approval = '<span class="badge bg-orange">Rejected</span>';
                $data['tombol'] = '';
            }
            else {
                $value->status_approval = '<span class="badge bg-red">Butuh Konfirmasi</span>';
                $data['tombol'] = '
                	<button id="acc'.$value->id_pengajuan.'" onclick="acc('.$value->id_pengajuan.')" class="btn bg-blue btn-block btn-xs">ACC</button>
                	<button id="reject'.$value->id_pengajuan.'" onclick="reject('.$value->id_pengajuan.')" class="btn bg-orange btn-block btn-xs">Reject</button>';
            } 

            if ($value->foto_user==""||$value->foto_user==null) {

              	$data['foto']="http://placehold.it/500x400";
              }else{
              	$data['foto']=" ".base_url()."assets/images/member-photos/$value->foto_user ";
              }  

            // echo json_encode($data['tombol']);exit();
		 	$data['list']="
		 			<h5>".$value->nip."</h5>
		 			<h5>".$value->nama_user."</h5>
		 			<h5>".$value->nama_unit."</h5>
		 			<h5>".$jabatan."</h5>
		 			<h5>".$status."</h5>
		 			<h5>".$value->email_user."</h5>
		 			<h5>".$value->telp_user."</h5>
		 			<h5>".$value->alamat_user."</h5>
		 	";
		 	$data['cuti']="
		 			 <h5>Keterangan Pengajuan Cuti :".$value->keterangan_pengajuan."</h5> 
                     <h5>Waktu Pengajuan Cuti : ".$value->tanggal_awal_pengajuan." sampai ".$value->tanggal_akhir_pengajuan." </h5>
                     <h5>Status Pengajuan Cuti : ".$value->status_approval." </h5>
		 	";
		 }
		
		$this->load->view('SO/header', $data);
		$this->load->view('SO/detail_cuti');
		$this->load->view('SO/footer');
	}
}