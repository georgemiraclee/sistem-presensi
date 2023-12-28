<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_channel extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('DB_super_admin');
		$this->load->helper(array('form', 'url','download'));

		$this->load->library('upload');
	}


	public function index(){
		$select_channel = $this->db_select->select_all('tb_channel');
		$data['user'] = $this->session->userdata('user');

		$data['list'] = '<table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>No Telepon</th>
                                                <th>No Handphone</th>
                                                <th>Jam Masuk</th>
                                                <th>Jam Keluar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>     
                                            </tr>
                                        </thead>';

		foreach ($select_channel as $key => $value) {
			if ($value->sms_channel == null || $value->sms_channel == '') {
				$value->sms_channel = '-';
			}
			if ($value->is_aktif == 1) {
				$btnStatus = '<button type="button" class="btn btn-danger" id="btnStatusNA'.$key.'" onclick="Naktif('.$value->id_channel.','.$key.')">Non Aktif</button>';
				$value->is_aktif = '<span class="label label-success">Aktif</span>';
			}else{
				$value->is_aktif = '<span class="label label-danger">Tidak Aktif</span>';
				$btnStatus = '<button type="button" class="btn btn-success" id="btnStatusA'.$key.'" onclick="aktif('.$value->id_channel.','.$key.')">Aktif</button>';
			}
			$data['list'] .= '<tr>
                                <th>'.$value->id_channel.'</th>
                                <th>'.$value->nama_channel.'</th>
                                <th>'.$value->email_channel.'</th>
                                <th>'.$value->telp_channel.'</th>
                                <th>'.$value->sms_channel.'</th>
                                <th>'.$value->jam_masuk.'</th>
                                <th>'.$value->jam_keluar.'</th>
                                <th>'.$value->is_aktif.'</th>
                                <th>
                                	<a href="'.base_url().'Data_channel/editChannel/'.$value->id_channel.'" class=" btn btn-primary">Ubah</a> 
                                	'.$btnStatus.'
                            	</th>
                            </tr>';
		}

		$data['list'] .= '<tbody>
                                        </tbody>
                                    </table>';

		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/data_channel',$data);
	}

	public function changeStatus()
	{
		$where['id_channel'] = $this->input->post('id');
		$data['is_aktif'] = $this->input->post('status');

		$update = $this->db_dml->update('tb_channel', $data, $where);

		if ($update == 1) {
			$result['status'] = true;
		}else{
			$result['status'] = false;
		}
		echo json_encode($result);
	}

	public function editChannel($id_channel)
	{
		$data['user'] = $this->session->userdata('user');
		$data['data_channel'] = $this->db_select->select_where('tb_channel', 'id_channel = '.$id_channel.'');
		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/edit_channel',$data);
	}

	public function tambahChannel(){
		$data['user'] = $this->session->userdata('user');
		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/tambah_channel',$data);
	}

	public function updateChannel(){
		$update = array();
		$where['id_channel'] = $this->input->post('id_channel');
		$selectChannel = $this->db_select->select_where('tb_channel', $where);
		
		if ($selectChannel->nama_channel != $this->input->post('nama_channel')) {
			$nama = $this->cekChannel('nama_channel', $this->input->post('nama_channel'));
			if ($nama == false) {
				$update['nama_channel'] = $this->input->post('nama_channel');
			}else{
				$result['status'] = false;
	            $result['message'] = 'Nama Channel Sudah Digunakan';
	            $result['data'] = '';

	            echo json_encode($result); exit();
			}
		}
		if ($selectChannel->alamat_channel != $this->input->post('alamat_channel')) {
			$update['alamat_channel'] = $this->input->post('alamat_channel');
		}
		if ($selectChannel->email_channel != $this->input->post('email_channel')) {
			$nama = $this->cekChannel('email_channel', $this->input->post('email_channel'));
			if ($nama == false) {
				$update['email_channel'] = $this->input->post('email_channel');
			}else{
				$result['status'] = false;
	            $result['message'] = 'Email Channel Sudah Digunakan';
	            $result['data'] = '';

	            echo json_encode($result); exit();
			}
		}
		if ($selectChannel->telp_channel != $this->input->post('telp_channel')) {
			$nama = $this->cekChannel('telp_channel', $this->input->post('telp_channel'));
			if ($nama == false) {
				$update['telp_channel'] = $this->input->post('telp_channel');
			}else{
				$result['status'] = false;
	            $result['message'] = 'Nomor Telepon Channel Sudah Digunakan';
	            $result['data'] = '';

	            echo json_encode($result); exit();
			}
		}
		if ($selectChannel->sms_channel != $this->input->post('sms_channel')) {
			$nama = $this->cekChannel('sms_channel', $this->input->post('sms_channel'));
			if ($nama == false) {
				$update['sms_channel'] = $this->input->post('sms_channel');
			}else{
				$result['status'] = false;
	            $result['message'] = 'Nomor Handphone Channel Sudah Digunakan';
	            $result['data'] = '';

	            echo json_encode($result); exit();
			}
		}
		if ($selectChannel->deskripsi_channel != $this->input->post('deskripsi_channel')) {
			$update['deskripsi_channel'] = $this->input->post('deskripsi_channel');
		}
		if ($selectChannel->jam_masuk != $this->input->post('jam_masuk')) {
			$update['jam_masuk'] = $this->input->post('jam_masuk');
		}
		if ($selectChannel->jam_keluar != $this->input->post('jam_keluar')) {
			$update['jam_keluar'] = $this->input->post('jam_keluar');
		}

		if ($_FILES['logo_channel']['name'] != '') {
			$path = realpath(APPPATH . '../assets/images/channel/');
			$namafile = mdate("%Y%m%d%H%i%s", time());
            
            $config = array('allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => $path,
                            'encrypt_name' => false,
                            'file_name' => $namafile
                           );

            $this->upload->initialize($config);

            if ($this->upload->do_upload('logo_channel')) {
                $img_data = $this->upload->data();
                $new_imgname = $namafile.$img_data['file_ext'];
                $new_imgpath = $img_data['file_path'].$new_imgname;

                rename($img_data['full_path'], $new_imgpath);

				$update['logo_channel'] = $new_imgname;
            } else {
                $result['status'] = false;
                $result['message'] = $this->upload->display_errors();
                $result['data'] = '';

                echo json_encode($result); exit();
            }
        }
		
		if (count($update) > 0) {
			$post = $this->db_dml->update('tb_channel', $update, $where);

			if ($post == 1) {
				$result['status'] = true;
	            $result['message'] = 'Data Channel Berhasil Diubah';
	            $result['data'] = '';
			}else{
				$result['status'] = false;
	            $result['message'] = 'Data Channel Gagal Diubah';
	            $result['data'] = '';
			}
		}else{
			$result['status'] = false;
            $result['message'] = 'Tidak Ada Perubahan Data Apapun';
            $result['data'] = '';
		}
		echo json_encode($result); exit();
	}

	public function insertChannel(){
		$where['nama_channel'] = $this->input->post('nama_channel');
		$cek_channel = $this->db_select->select_where('tb_channel', $where);
		if (!$cek_channel) {
			$where_email['email_channel'] = $this->input->post('email_channel');
			$cek_email = $this->db_select->select_where('tb_channel', $where_email);

			if (!$cek_email) {
				$where_telp['telp_channel'] = $this->input->post('telp_channel');
				$cek_telp = $this->db_select->select_where('tb_channel', $where_telp);
				if (!$cek_telp) {					
					$data['nama_channel'] = $this->input->post('nama_channel');
					$data['alamat_channel'] = $this->input->post('alamat_channel');
					$data['email_channel'] = $this->input->post('email_channel');
					$data['telp_channel'] = $this->input->post('telp_channel');
					$data['sms_channel'] = $this->input->post('sms_channel');
					$data['deskripsi_channel'] = $this->input->post('deskripsi_channel');
					$data['jam_masuk'] = $this->input->post('jam_masuk');
					$data['jam_keluar'] = $this->input->post('jam_keluar');

					if ($_FILES['logo_channel']['name'] != '') {
						$path = realpath(APPPATH . '../assets/images/channel/');
        				$namafile = mdate("%Y%m%d%H%i%s", time());
	                    $config = array('allowed_types' => 'jpg|jpeg|gif|png',
	                                    'upload_path' => $path,
	                                    'encrypt_name' => false,
	                                    'file_name' => $namafile
	                                   );

	                    $this->upload->initialize($config);

	                    if ($this->upload->do_upload('logo_channel')) {
	                        $img_data = $this->upload->data();
	                        $new_imgname = $namafile.$img_data['file_ext'];
	                        $new_imgpath = $img_data['file_path'].$new_imgname;

	                        rename($img_data['full_path'], $new_imgpath);

							$data['logo_channel'] = $new_imgname;
	                    } else {
	                        $result['status'] = false;
	                        $result['message'] = $this->upload->display_errors();
	                        $result['data'] = '';

	                        echo json_encode($result); exit();
	                    }
	                }

	                $insert = $this->db_dml->normal_insert('tb_channel', $data);

	                if ($insert) {
	                	$result['status'] = true;
			            $result['message'] = 'Data Berhasil Disimpan';
			            $result['data'] = '';
	                }else{
	                	$result['status'] = false;
			            $result['message'] = 'Data Gagal Disimpan';
			            $result['data'] = '';
	                }
				}else{
					$result['status'] = false;
		            $result['message'] = 'Nomor Telepon Telah Digunakan';
		            $result['data'] = '';
				}
			}else{
				$result['status'] = false;
	            $result['message'] = 'Email Channel Telah Digunakan';
	            $result['data'] = '';
			}
		}else{
			$result['status'] = false;
            $result['message'] = 'Nama Channel Telah Digunakan';
            $result['data'] = '';
		}

		echo json_encode($result); exit();
	}
	public function delete($id){
		if (!$this->DB_super_admin->delete_data_unit($id)) {
			 $this->session->set_flashdata('notif',' <p class="alert alert-danger text-center">Data Gagal Dihapus!</p>');
			redirect('Data_unit');
		}else{
				$this->session->set_flashdata('notif',' <p class="alert alert-success text-center">Data Berhasil Dihapus !</p>');
			redirect('Data_unit');
		}
	}

	public function cekChannel($value, $data)
	{
		$where[$value] = $data;
		$get = $this->db_select->select_where('tb_channel', $where);

		if ($get) {
			return true;
		}else{
			return false;
		}
	}
	
}