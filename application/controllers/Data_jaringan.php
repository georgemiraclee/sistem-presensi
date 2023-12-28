<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_jaringan extends CI_Controller {
		public function __construct(){
		parent::__construct();
		$this->load->model('DB_super_admin');
		$this->load->helper(array('form', 'url','download'));
	}


	public function getJaringan($id_channel){
		//ID CHANNEL
		$sess_channel['id_channel'] = $id_channel;
		$this->session->set_userdata('id_channel',$sess_channel);
		$Newsess_channel = $this->authen();

		$data['select_admin_unit'] = $this->db_select->select_all_where('tb_jaringan', 'id_channel = '.$Newsess_channel.'');
		$data['user'] = $this->session->userdata('user');
		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/data_jaringan',$data);
	}

	public function getArea($id_channel){
		//ID CHANNEL
		$sess_channel['id_channel'] = $id_channel;
		$this->session->set_userdata('id_channel',$sess_channel);
		$Newsess_channel = $this->authen();

		$data['select_admin_unit'] = $this->db_select->select_all_where('tb_lokasi', 'id_channel = '.$Newsess_channel.'');
		$data['user'] = $this->session->userdata('user');
		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/data_area',$data);
	}

	public function updateArea()
	{
		$Newsess_channel = $this->authen();
		$where['id_lokasi'] = $this->input->post('id_lokasi');
		$update = array();

		$is_find = $this->db_select->select_where('tb_lokasi', $where);

		if ($is_find->nama_lokasi != $this->input->post('nama_lokasi')) {
			$whereFind['nama_lokasi'] = $this->input->post('nama_lokasi');

			$is_find = $this->db_select->select_where('tb_lokasi', $whereFind);
			if ($is_find) {
				$result['status'] = false;
	            $result['message'] = 'Nama lokasi sudah terdaftar.';
	            $result['data'] = array();

				echo json_encode($result); exit();
			}

			$update['nama_lokasi'] = $this->input->post('nama_lokasi');

		}

		if ($_FILES['userfile']['name']) {
			$path = realpath(APPPATH . '../assets/polygon');
        	$namafile = mdate("%Y%m%d%H%i%s", time());

            $config = array('allowed_types' => 'csv',
                'upload_path' => $path,
                'max_size' => 2000,
                'encrypt_name' => false,
                'file_name' => $namafile
            );
            $this->upload->initialize($config);

            if ($this->upload->do_upload()) {
                $img_data = $this->upload->data();
                $new_imgname = $namafile.$img_data['file_ext'];
                $new_imgpath = $img_data['file_path'].$new_imgname;

                rename($img_data['full_path'], $new_imgpath);

                $update['url_file_lokasi'] = $new_imgname;
            } else {
            	$text = $this->upload->display_errors();
            	$text=str_ireplace('<p>','',$text);
				$text=str_ireplace('</p>','',$text);
                $result['status'] = false;
                $result['message'] = $text;
                $result['data'] = array();
                echo json_encode($result); exit();
            }
        }

        if (count($update) > 0) {
        	$updateData = $this->db_dml->update('tb_lokasi', $update, $where);

        	if ($updateData == 1) {
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
		
		echo json_encode($result); exit();
	}

	public function update($id_jaringan){
		$Newsess_channel = $this->authen();
		$where['id_jaringan'] = $id_jaringan;

		$data = array(
			'ssid_jaringan'=>$this->input->post('ssid_jaringan'),	
			'lokasi_jaringan'=>$this->input->post('lokasi_jaringan'),	
			'id_channel'=>$Newsess_channel,	
		);
		$update = $this->db_dml->update('tb_jaringan', $data, $where);

		if ($update == 1) {
			$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Disimpan !</p>');
		    redirect('Data_jaringan/getJaringan/'.$Newsess_channel);  
		}else{
			$this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Disimpan !</p>');
		    redirect('Data_jaringan/getJaringan/'.$Newsess_channel);  
		}	
	}

	public function insertArea()
	{
		$Newsess_channel = $this->authen();
		$input['nama_lokasi'] = $this->input->post('nama_lokasi');

		$is_find = $this->db_select->select_where('tb_lokasi', $input);
		if (!$is_find) {
			if ($_FILES['userfile']['name']) {
				$path = realpath(APPPATH . '../assets/polygon');
            	$namafile = mdate("%Y%m%d%H%i%s", time());

                $config = array('allowed_types' => 'csv',
                    'upload_path' => $path,
                    'max_size' => 2000,
                    'encrypt_name' => false,
                    'file_name' => $namafile
                );
                $this->upload->initialize($config);

                if ($this->upload->do_upload()) {
                    $img_data = $this->upload->data();
                    $new_imgname = $namafile.$img_data['file_ext'];
                    $new_imgpath = $img_data['file_path'].$new_imgname;

                    rename($img_data['full_path'], $new_imgpath);

                    $input['url_file_lokasi'] = $new_imgname;
                    $input['id_channel'] = $Newsess_channel;

                    $polygon_insert = $this->db_dml->normal_insert('tb_lokasi', $input);

                    if ($polygon_insert) {
                        $result['status'] = true;
                        $result['message'] = 'Data berhasil disimpan';
                        $result['data'] = array();
                    }else{
                    	$result['status'] = true;
                        $result['message'] = 'Data gagal disimpan';
                        $result['data'] = array();
                    }
                } else {
                	$text = $this->upload->display_errors();
                	$text=str_ireplace('<p>','',$text);
					$text=str_ireplace('</p>','',$text);
                    $result['status'] = false;
                    $result['message'] = $text;
                    $result['data'] = array();
                    echo json_encode($result); exit();
                }
            }else{
                $result['status'] = false;
                $result['message'] = 'File wilayah wajib di isi';
                $result['data'] = array();
            }
		}else{
			$result['status'] = false;
            $result['message'] = 'Data sudah dibuat.';
            $result['data'] = array();
		}
		echo json_encode($result);
	}

	public function insert(){
		$Newsess_channel = $this->authen();
   	 	$data = array(
			'ssid_jaringan'=>$this->input->post('ssid_jaringan'),	
			'lokasi_jaringan'=>$this->input->post('lokasi_jaringan'),	
			'id_channel'=>$Newsess_channel,	
		);

	 	if($this->DB_super_admin->insert_data_jaringan($data)){//akses model untuk menyimpan ke database
		 	$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Disimpan !</p>');
		    redirect('Data_jaringan/getJaringan/'.$Newsess_channel);  
	    }else{
	       $this->session->set_flashdata('notif',' <p class="alert alert-warning text-center">Data Gagal Disimpan! Pastikan Anda mengisinya dengan benar</p>');
	       redirect('Data_jaringan/getJaringan/'.$Newsess_channel);
	    }

	}
	public function delete($id){
		$Newsess_channel = $this->authen();

		if (!$this->DB_super_admin->delete_data_jaringan($id)) {
			 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Dihapus!</p>');
			redirect('Data_jaringan/getJaringan/'.$Newsess_channel);
		}else{
				$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Dihapus !</p>');
			redirect('Data_jaringan/getJaringan/'.$Newsess_channel);
		}
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