<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Area_apel extends CI_Controller
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
		$id=1;
		$data['data_area'] = $this->Db_select->select_all_where('tb_lokasi','is_apel = "'.$id.'"');

		foreach ($data['data_area'] as $key => $value) {
			$selectUser = $this->Db_select->select_where('tb_history_absensi','id_lokasi = '.$value->id_lokasi);
			if ($selectUser) {
				$value->delete = false;
			}else{
				$value->delete = true;
			}
		}
		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/area_apel');
		$this->load->view('Administrator/footer');
	}
	public function insert_area()
	{
		$sess = $this->session->userdata('user');
		$input['is_apel'] = $this->input->post('is_apel');
		$input['nama_lokasi'] = $this->input->post('nama_lokasi');

		$is_find = $this->Db_select->select_where('tb_lokasi', $input);
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

                    $polygon_insert = $this->Db_dml->normal_insert('tb_lokasi', $input);

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

	public function delete_area()
	{
		$where['id_lokasi'] = $this->input->post('id_lokasi');

		$delete = $this->Db_dml->delete('tb_lokasi', $where);

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

	public function update_area()
	{
		$sess = $this->session->userdata('user');
		$where['id_lokasi'] = $this->input->post('id_lokasi');
		$update = array();

		$is_find = $this->Db_select->select_where('tb_lokasi', $where);

		if ($is_find->nama_lokasi != $this->input->post('nama_lokasi')) {
			$whereFind['nama_lokasi'] = $this->input->post('nama_lokasi');

			$is_find = $this->Db_select->select_where('tb_lokasi', $whereFind);
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
        	$updateData = $this->Db_dml->update('tb_lokasi', $update, $where);

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

	public function update_status_area()
	{
		$sess = $this->session->userdata('user');
		$where['id_jaringan'] = $this->input->post('id_jaringan');

		$update['is_aktif'] = $this->input->post('is_aktif');

		$updateData = $this->Db_dml->update('tb_lokasi', $update, $where);

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