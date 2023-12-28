<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class pengaturan extends CI_Controller
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
    	$id_channel = $sess['id_channel'];
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
	        redirect(base_url());exit();
        }
		$data['data_setting'] = $this->Db_select->select_all_where('tb_setting','id_channel='.$id_channel);
		// echo json_encode($data);exit();
		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/pengaturan');
		$this->load->view('Administrator/footer');
	}  
	public function update()
	{
			$sess = $this->session->userdata('user');
    	$id_channel = $sess['id_channel'];
		$where['id_setting'] = $this->input->post('id_setting');
		$where['id_channel'] = $id_channel;
		$data['nama_app'] = $this->input->post('nama_app');
		$data['deskripsi'] = $this->input->post('deskripsi');
		
		$nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
        $config['upload_path'] = './assets/images/icon'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['max_size'] = '2048'; //maksimum besar file 2M
        $config['file_name'] = $nmfile; //nama yang terupload nantinya
        $time = $this->input->post('nip').'_'.strtotime('now');
         $this->load->library('upload', $config);
        $this->upload->initialize($config);
       if($_FILES['userfile']['name']){
	       	if ($this->upload->do_upload('userfile'))
           		{
					 $gbr = $this->upload->data();
				   	$name3 = $gbr['file_name'];		
				   	 $data['icon'] = $name3;		
				}else{
						$pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>".$this->upload->display_errors()."</div>";
		        $this->session->set_flashdata('pesan', $pesan);
		         redirect(base_url()."Administrator/pengaturan");exit();
					}
       }
        $update = $this->Db_dml->update('tb_setting', $data, $where);
        if ($update == 1) {
	        	$pesan = "<div class='alert alert-success alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data berhasil disimpan.</div>";
	        $this->session->set_flashdata('pesan', $pesan);
	       	// echo json_encode($data);exit();
	        redirect(base_url()."Administrator/pengaturan");exit();
        }else{
	        	$pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data gagal disimpan.</div>";
	        $this->session->set_flashdata('pesan', $pesan);
	        // echo json_encode($data);exit();
	         redirect(base_url()."Administrator/pengaturan");exit();
        }
	}
}