<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_unit extends CI_Controller {
		public function __construct(){
		parent::__construct();
		$this->load->model('DB_super_admin');
		$this->load->helper(array('form', 'url','download'));
	}


	public function index(){
		$data['select_admin_unit'] = $this->db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where is_superadmin = 0 and is_admin = 1');
		$data['data_unit'] = $this->db_select->select_all('tb_unit');
		$data['user'] = $this->session->userdata('user');
		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/admin_unit',$data);
	}
	public function update($nip){
		$select_password=$this->DB_super_admin->select_password($nip);
		foreach ($select_password as $key => $value) {
			$ps=$value->password_user;
		}
			if ($this->input->post('password_user')==null||$this->input->post('password_user')=='') {
			$pass=$ps;
		}else{
			$pass= md5($this->input->post('password_user'));
		}
		$this->load->library('upload');
        $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
        $config['upload_path'] = './assets/images/member-photos/'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['max_size'] = '2048'; //maksimum besar file 2M
        $config['file_name'] = $nmfile; //nama yang terupload nantinya

        $this->upload->initialize($config);
       	if($_FILES['foto_user']['name']){
       		if ($this->upload->do_upload('foto_user')){
				$gbr = $this->upload->data();
				   	$name = $gbr['file_name'];
				   	$data['insert']=$this->DB_super_admin->update_admin_unit($nip,$name,$pass);
						$this->load->vars($data);
						$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data berhasil diubah</p>');
						redirect('Admin_unit');
				   }else{
				   		 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Icon tidak sesuai, coba ganti yang lain atau hubungi dokter</p>');
			 			redirect('Admin_unit');
				   }
		}else{
				$data['insert']=$this->DB_super_admin->update_admin_unit2($nip,$pass);
				$this->load->vars($data);
			 	$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data berhasil diubah</p>');
			 	redirect('Admin_unit');
		}

		
	}
	public function insert(){
		
		$this->load->library('upload');
        $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
        $config['upload_path'] = './assets/images/member-photos/'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['max_size'] = '2048'; //maksimum besar file 2M
        $config['file_name'] = $nmfile; //nama yang terupload nantinya

        $this->upload->initialize($config);
       	if($_FILES['userfile']['name']){
       		if ($this->upload->do_upload('userfile')){
				$gbr = $this->upload->data();
				   	$name3 = $gbr['file_name'];
						$data=array(
							'nama_user'=>$this->input->post('nama_user'),
							'email_user'=>$this->input->post('email_user'),
							'telp_user'=>$this->input->post('telp_user'),
							'alamat_user'=>$this->input->post('alamat_user'),
							'id_unit'=>$this->input->post('id_unit'),
							'jabatan'=>$this->input->post('jabatan'),
							'is_admin'=>$this->input->post('is_admin'),
							'nip'=>$this->input->post('nip'),
							'password_user'=>md5($this->input->post('password_user')),
							'foto_user'=>$name3			
							);
						             if($this->DB_super_admin->insert_admin_unit($data)){//akses model untuk menyimpan ke database
						             	$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Disimpan !</p>');
						               redirect('Admin_unit');  
						            }else{
						              $this->session->set_flashdata('notif',' <p class="alert alert-warning text-center">Data Gagal Disimpan! Pastikan Anda mengisinya dengan benar</p>');
						               redirect('Admin_unit');
						            }
						}else{
							$error = array('error' => $this->upload->display_errors());
					 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">'.$eror.' fatal eror hubungi programer</p>');
					redirect('Admin_unit');
					}
       }else{
       	 $data = array(
						'nama_user'=>$this->input->post('nama_user'),
						'email_user'=>$this->input->post('email_user'),
						'telp_user'=>$this->input->post('telp_user'),
						'alamat_user'=>$this->input->post('alamat_user'),
						'id_unit'=>$this->input->post('id_unit'),
						'is_admin'=>$this->input->post('is_admin'),
						'nip'=>$this->input->post('nip'),
						'password_user'=>md5($this->input->post('password_user')),
						'jabatan'=>$this->input->post('jabatan')		
						);
						 if($this->DB_super_admin->insert_admin_unit($data)){//akses model untuk menyimpan ke database
						 	$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Disimpan !</p>');
						    redirect('Admin_unit');  
						    }else{
						       $this->session->set_flashdata('notif',' <p class="alert alert-warning text-center">Data Gagal Disimpan! Pastikan Anda mengisinya dengan benar</p>');
						       redirect('Admin_unit');
						    }

       }

	}

	public function delete($id){
		if (!$this->DB_super_admin->delete_admin_unit($id)) {
			 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Dihapus!</p>');
			redirect('Admin_unit');
		}else{
				$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Dihapus !</p>');
			redirect('Admin_unit');
		}
	}
	
	
}
