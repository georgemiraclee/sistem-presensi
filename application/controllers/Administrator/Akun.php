<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class akun extends CI_Controller
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

		$data['akun'] = $this->Db_select->select_where('tb_channel', 'id_channel = '.$sess['id_channel']);

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/akun');
		$this->load->view('Administrator/footer');
	}

	public function edit()
	{
		$sess = $this->session->userdata('user');

		$data['akun'] = $this->Db_select->select_where('tb_channel', 'id_channel = '.$sess['id_channel']);

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/edit_channel');
		$this->load->view('Administrator/footer');
	}

	public function update()
	{
		$sess = $this->session->userdata('user');
		$where['id_channel'] = $sess['id_channel'];

		$update['nama_channel'] = $this->input->post('nama_channel');
		$update['alamat_channel'] = $this->input->post('alamat_channel');
		$update['telp_channel'] = $this->input->post('telp_channel');
		$update['fax_channel'] = $this->input->post('fax_channel');
		$update['email_channel'] = $this->input->post('email_channel');
		$update['website_channel'] = $this->input->post('website_channel');
		$update['deskripsi_channel'] = $this->input->post('deskripsi_channel');

		if($_FILES['userfile']['name']){
       		$this->load->library('upload');
	        $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
	        $config['upload_path'] = './assets/images/channel'; //path folder
	        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
	        $config['max_size'] = '2048'; //maksimum besar file 2M
	        $config['file_name'] = $nmfile; //nama yang terupload nantinya

	        $this->upload->initialize($config);

       		if ($this->upload->do_upload('userfile')){
				$gbr = $this->upload->data();
			   	$name3 = $gbr['file_name'];
			   	$update['icon_channel'] = $name3;
			}
       	}

       	$updateData = $this->Db_dml->update('tb_channel', $update, $where);

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