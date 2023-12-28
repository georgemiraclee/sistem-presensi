<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller {
		public function __construct(){
		parent::__construct();
		$this->load->model('DB_super_admin');
		$this->load->helper(array('form', 'url','download'));
	}

	public function getPegawai($id_channel){
		//ID CHANNEL
		$sess_channel['id_channel'] = $id_channel;
		$this->session->set_userdata('id_channel',$sess_channel);
		$Newsess_channel = $this->authen();

		$data['select_admin_unit'] = $this->db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where is_superadmin = 0 and b.id_channel = '.$Newsess_channel.'');
		$data['data_unit'] = $this->db_select->select_all_where('tb_unit','id_channel = '.$Newsess_channel.'');
		$data['data_jabatan'] = $this->db_select->select_all_where('tb_jabatan','id_channel = '.$Newsess_channel.' and is_aktif = 1');
		$data['status_pegawai'] = $this->db_select->select_all_where('tb_status_user','id_channel = '.$Newsess_channel.' and is_aktif = 1');
		$data['user'] = $this->session->userdata('user');
		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/pegawai',$data);
	}
	public function update($nip){
		$Newsess_channel = $this->authen();

		$where['nip'] = $nip;
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
				   	$input = array(
						'nama_user' => $this->input->post('nama_user'),
						'email_user' => $this->input->post('email_user'),
						'telp_user' => $this->input->post('telp_user'),
						'alamat_user' => $this->input->post('alamat_user'),
						'id_unit' => $this->input->post('id_unit'),
						'jabatan' => $this->input->post('jabatan'),
						'password_user' => md5($this->input->post('password_user')),
						'foto_user' => $name,
						'is_admin' => $this->input->post('is_admin')
					);
				   	$update = $this->db_dml->update('tb_user', $input, $where);

					if ($update == 1) {
						$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data berhasil diubah</p>');
					 	redirect('Pegawai/getPegawai/'.$Newsess_channel);
					}else{
						$this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data gagal diubah</p>');
					 	redirect('Pegawai/getPegawai/'.$Newsess_channel);
					}
				   }else{
				   		 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Icon tidak sesuai, coba ganti yang lain atau hubungi dokter</p>');
			 			redirect('Pegawai/getPegawai/'.$Newsess_channel);
				   }
		}else{
			$input = array(
				'nama_user' => $this->input->post('nama_user'),
				'email_user' => $this->input->post('email_user'),
				'telp_user' => $this->input->post('telp_user'),
				'alamat_user' => $this->input->post('alamat_user'),
				'id_unit' => $this->input->post('id_unit'),
				'jabatan' => $this->input->post('jabatan'),
				'password_user' => md5($this->input->post('password_user')),
				'is_admin' => $this->input->post('is_admin')
			);

			$update = $this->db_dml->update('tb_user', $input, $where);

			if ($update == 1) {
				$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data berhasil diubah</p>');
			 	redirect('Pegawai/getPegawai/'.$Newsess_channel);
			}else{
				$this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data gagal diubah</p>');
			 	redirect('Pegawai/getPegawai/'.$Newsess_channel);
			}
		}

		
	}
	public function insert(){
		$Newsess_channel = $this->authen();

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
				   	$email = $this->input->post('email_user');
				   	$tlp = $this->input->post('telp_user');
						$data=array(
							'nama_user'=>$this->input->post('nama_user'),
							'email_user'=>$this->input->post('email_user'),
							'telp_user'=>$this->input->post('telp_user'),
							'alamat_user'=>$this->input->post('alamat_user'),
							'jenis_kelamin'=>$this->input->post('jenis_kelamin'),
							'id_unit'=>$this->input->post('id_unit'),
							'jabatan'=>$this->input->post('jabatan'),
							'is_admin'=>$this->input->post('is_admin'),
							'nip'=>$this->input->post('nip'),
							'password_user'=>md5($this->input->post('password_user')),
							'foto_user'=>$name3			
							);

									if ($this->DB_super_admin->checkemail($email)) {
										 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Disimpan!Email sudah pernah di Gunakan</p>');
						               redirect('Pegawai/getPegawai/'.$Newsess_channel);
									}
									if ($this->DB_super_admin->checktlp($tlp)) {
										 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Disimpan!Nomor telepon sudah pernah di Gunakan</p>');
						               redirect('Pegawai/getPegawai/'.$Newsess_channel);
									}
									if ($this->DB_super_admin->checknip($nip)) {
										 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Disimpan!NIP sudah pernah di Gunakan</p>');
						               redirect('Pegawai/getPegawai/'.$Newsess_channel);
									}
						             if($this->DB_super_admin->insert_admin_unit($data)){//akses model untuk menyimpan ke database
						             	$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Disimpan !</p>');
						               redirect('Pegawai/getPegawai/'.$Newsess_channel);  
						            }else{
						              $this->session->set_flashdata('notif',' <p class="alert alert-warning text-center">Data Gagal Disimpan! Pastikan Anda mengisinya dengan benar</p>');
						               redirect('Pegawai/getPegawai/'.$Newsess_channel);
						            }
						}else{
							$error = array('error' => $this->upload->display_errors());
					 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">'.$eror.' fatal eror hubungi programer</p>');
					redirect('Pegawai/getPegawai/'.$Newsess_channel);
					}
       }else{
       	 $data = array(
						'nama_user'=>$this->input->post('nama_user'),
						'email_user'=>$this->input->post('email_user'),
						'telp_user'=>$this->input->post('telp_user'),
						'alamat_user'=>$this->input->post('alamat_user'),
						'jenis_kelamin'=>$this->input->post('jenis_kelamin'),
						'id_unit'=>$this->input->post('id_unit'),
						'is_admin'=>$this->input->post('is_admin'),
						'nip'=>$this->input->post('nip'),
						'password_user'=>md5($this->input->post('password_user')),
						'jabatan'=>$this->input->post('jabatan')		
						);
       	 				if ($this->DB_super_admin->checkemail($email)) {
								$this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Disimpan!Email sudah pernah di Gunakan</p>');
						            redirect('Pegawai/getPegawai/'.$Newsess_channel);
								}
						if ($this->DB_super_admin->checktlp($tlp)) {
									$this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Disimpan!Nomor telepon sudah pernah di Gunakan</p>');
						            redirect('Pegawai/getPegawai/'.$Newsess_channel);
								}
						if ($this->DB_super_admin->checknip($nip)) {
										 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Disimpan!NIP sudah pernah di Gunakan</p>');
						               redirect('Pegawai/getPegawai/'.$Newsess_channel);
									}
						 if($this->DB_super_admin->insert_admin_unit($data)){//akses model untuk menyimpan ke database
						 	$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Disimpan !</p>');
						    redirect('Pegawai/getPegawai/'.$Newsess_channel);  
						 }else{
						       $this->session->set_flashdata('notif',' <p class="alert alert-warning text-center">Data Gagal Disimpan! Pastikan Anda mengisinya dengan benar</p>');
						       redirect('Pegawai/getPegawai/'.$Newsess_channel);
						 }

       }

	}

	public function delete($id){
		$Newsess_channel = $this->authen();

		if (!$this->DB_super_admin->delete_admin_unit($id)) {
			 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Dihapus!</p>');
			redirect('Pegawai/getPegawai/'.$Newsess_channel);
		}else{
				$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Dihapus !</p>');
			redirect('Pegawai/getPegawai/'.$Newsess_channel);
		}
	}
	public function Log($id){
		$Newsess_channel = $this->authen();

		$select=$this->DB_super_admin->select_detail_pegawai($id);
		foreach ($select as $key => $value) {
			$ut=$value->id_unit;
			$select2=$this->DB_super_admin->select_detail_unit($ut);
			foreach ($select2 as $key => $values) {
				$unt=$values->nama_unit;
			}

			if ($value->jenis_kelamin == 'l') {
				$value->jenis_kelamin = 'Laki-Laki';
			}elseif ($value->jenis_kelamin == 'p') {
				$value->jenis_kelamin = 'Perempuan';
			}else{
				$value->jenis_kelamin = '-';
			}

			if ($value->alamat_user == null || $value->alamat_user == '') {
				$value->alamat_user = '-';
			}

			if ($value->foto_user == null || $value->foto_user == '') {
				$value->foto_user = 'default_photo.jpg';
			}

			$data['nip']=$value->nip;
			$data['nama']=$value->nama_user;
			$data['alamat']=$value->alamat_user;
			$data['email']=$value->email_user;
			$data['telepon']=$value->telp_user;
			$data['jabatan']=$value->jabatan;
			$data['unit']=$unt;
			$data['jenis_kelamin']=$value->jenis_kelamin;
			$data['foto']=$value->foto_user;
			$nip=$value->nip;
		}
		$data['dt']=$this->DB_super_admin->select_detail_absen($nip);


		$data2['user'] = $this->session->userdata('user');
		$this->load->view('Super_admin/header', $data2);
		$this->load->view('Super_admin/log',$data);
	}
	
	public function authen()
	{
		$Newsess_channel = $this->session->userdata('id_channel');

		if ($Newsess_channel['id_channel'] == null || $Newsess_channel['id_channel'] == '') {
			redirect();
		}else{
			return $Newsess_channel['id_channel'];
		}
	}
}
