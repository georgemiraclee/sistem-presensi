<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_unit extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('DB_super_admin');
		$this->load->helper(array('form', 'url','download'));
		
	}


	public function getUnit($id_channel){
		//ID CHANNEL
		$sess_channel['id_channel'] = $id_channel;
		$this->session->set_userdata('id_channel',$sess_channel);
		$Newsess_channel = $this->session->userdata('id_channel');

		$data['select_admin_unit'] = $this->db_select->query_all('select *from tb_unit a join tb_channel b on a.id_channel = b.id_channel where b.id_channel = '.$Newsess_channel['id_channel'].'');
		$data['select_channel']=$this->db_select->select_all_where('tb_channel','is_aktif = 1');
		$data['user'] = $this->session->userdata('user');
		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/data_unit',$data);
	}
	public function update($id_unit){
		$Newsess_channel = $this->session->userdata('id_channel');

		$where['id_unit'] = $id_unit;
		$this->load->library('upload');
        $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
        $config['upload_path'] = './assets/upload/logo'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['max_size'] = '2048'; //maksimum besar file 2M
        $config['file_name'] = $nmfile; //nama yang terupload nantinya

        $this->upload->initialize($config);
       	if($_FILES['ikon_unit']['name']){
       		if ($this->upload->do_upload('ikon_unit')){
				$gbr = $this->upload->data();
			   	$name = $gbr['file_name'];
			   	$input = array(
			   		'nama_unit' => $this->input->post('nama_unit'),
			   		'id_channel' => $Newsess_channel['id_channel'],
			   		'icon_unit' => $name
			   		);

			   	$update = $this->db_dml->update('tb_unit', $input, $where);
				if ($update == 1) {
				    $this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Diubah!</p>');
					redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);	
				}else{
					$this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Diubah!</p>');
					redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);	
				}
			   }else{
			    $this->session->set_flashdata('notif',' <p class="alert alert-warning text-center">Data Gagal Diubah!</p>');
		 		redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);
			   }
		}else{
				$input = array(
			   		'nama_unit' => $this->input->post('nama_unit'),
			   		'id_channel' => $Newsess_channel['id_channel']
			   		);
				$update = $this->db_dml->update('tb_unit', $input, $where);
				if ($update == 1) {
				    $this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Diubah!</p>');
					redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);	
				}else{
					$this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Diubah!</p>');
					redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);	
				}
		}
		
	}
	public function insert(){
		$Newsess_channel = $this->session->userdata('id_channel');

		$this->load->library('upload');
        $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
        $config['upload_path'] = './assets/upload/logo'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['max_size'] = '2048'; //maksimum besar file 2M
        $config['file_name'] = $nmfile; //nama yang terupload nantinya

        $this->upload->initialize($config);
       	if($_FILES['userfile']['name']){
       		if ($this->upload->do_upload('userfile')){
				$gbr = $this->upload->data();
				   	$name3 = $gbr['file_name'];
						$data=array(
							'nama_unit'=>$this->input->post('nama_unit'),
							'id_channel'=>$Newsess_channel['id_channel'],
							'icon_unit'=>$name3
							);
						             if($this->DB_super_admin->insert_data_unit($data)){//akses model untuk menyimpan ke database
						             	$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Disimpan !</p>');
						               redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);  
						            }else{
						              $this->session->set_flashdata('notif',' <p class="alert alert-warning text-center">Data Gagal Disimpan! Pastikan Anda mengisinya dengan benar</p>');
						               redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);
						            }
						}else{
							$error = array('error' => $this->upload->display_errors());
					 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">'.$eror.' fatal eror hubungi programer</p>');
					redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);
					}
       }else{
       	 $data = array(
						'nama_unit'=>$this->input->post('nama_unit'),	
						'id_channel'=>$Newsess_channel['id_channel']
						);
						 if($this->DB_super_admin->insert_data_unit($data)){//akses model untuk menyimpan ke database
						 	$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Disimpan !</p>');
						    redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);  
						    }else{
						       $this->session->set_flashdata('notif',' <p class="alert alert-warning text-center">Data Gagal Disimpan! Pastikan Anda mengisinya dengan benar</p>');
						       redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);
						    }

       }

	}
	public function delete($id){
		$Newsess_channel = $this->session->userdata('id_channel');

		if (!$this->DB_super_admin->delete_data_unit($id)) {
			 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Dihapus!</p>');
			redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);
		}else{
				$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Dihapus !</p>');
			redirect('Data_unit/getUnit/'.$Newsess_channel['id_channel']);
		}
	}
	
}