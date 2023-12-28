<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Akun extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function getAkun($id_channel)
	{
		$sess_channel['id_channel'] = $id_channel;
		$this->session->set_userdata('id_channel',$sess_channel);
		$Newsess_channel = $this->authen();

		$data_channel = $this->db_select->select_where('tb_channel', 'id_channel = '.$Newsess_channel);
		$data_channel->jumlah_personalia = 0;
		$data['data_channel'] = $data_channel;


		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/akun_channel');
	}

	public function ubahData()
	{
		$Newsess_channel = $this->authen();

		$data['data_channel'] = $this->db_select->select_where('tb_channel', 'id_channel = '.$Newsess_channel);

		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/ubah_channel');
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

	public function insert()
	{
		$where['id_channel'] = $this->input->post('id_channel');

		if ($this->input->post('nama_channel')) {
			$update['nama_channel'] = $this->input->post('nama_channel');
		}

		if ($this->input->post('alamat_channel')) {
			$update['alamat_channel'] = $this->input->post('alamat_channel');
		}

		if ($this->input->post('telp_channel')) {
			$update['telp_channel'] = $this->input->post('telp_channel');
		}

		if ($this->input->post('fax_channel')) {
			$update['fax_channel'] = $this->input->post('fax_channel');
		}

		if ($this->input->post('email_channel')) {
			$update['email_channel'] = $this->input->post('email_channel');
		}

		if ($this->input->post('website_channel')) {
			$update['website_channel'] = $this->input->post('website_channel');
		}

		if ($_FILES['userfile']['name']) {
			$path = realpath(APPPATH . '../assets/images/channel');
        	$namafile = mdate("%Y%m%d%H%i%s", time());

            $config = array('allowed_types' => 'gif|jpg|png|jpeg|bmp',
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

                $update['icon_channel'] = $new_imgname;
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
        	$updateData = $this->db_dml->update('tb_channel', $update, $where);

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

		echo json_encode($result);
	}
}