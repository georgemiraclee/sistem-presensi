<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Landingpage extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index(){
		$this->load->view('new_landing');
	}

	public function kebijakan_privasi(){
		$this->load->view('kebijakan_privasi');
	}

	public function sukses()
	{
		$this->load->view('success');
	}

	public function subscribe()
	{
		$cek_email = $this->Db_select->select_where('tb_subscribe', ['email' => $this->input->post('email')]);
		if (!$cek_email) {
      $data['email'] = $this->input->post('email');
      $data['name'] = $this->input->post('name');
      $data['phone_number'] = $this->input->post('phone');
      $data['company'] = $this->input->post('company');
      $data['total_employe'] = $this->input->post('totalEmployer');
			$insert = $this->Db_dml->insert('tb_subscribe', $data);
			if ($insert) {
				$result['status'] = true;
				$result['message'] = 'Kami akan segera menghubungi Anda.';
			}else{
				$result['status'] = false;
				$result['message'] = 'Data gagal dikirim.';
			}
		}else{
			$result['status'] = true;
			$result['message'] = 'Kami akan segera menghubungi Anda.';
		}

		echo json_encode($result);
	}

	public function insert(){
		$em = $this->input->post('email');
		$cek_email = $this->Db_select->select_where('tb_user','email_user ="'.$em.'"');
		if (!$cek_email) {
			$insertUser['nip'] = 1;
			$insertUser['password_user'] = md5( $this->input->post('password'));
			$insertUser['nama_user'] = $this->input->post('nama_lengkap');
			$insertUser['email_user'] = $this->input->post('email');
			$insertUser['telp_user'] = $this->input->post('telp');
			$insertUser['alamat_user'] = $this->input->post('alamat_channel');
			$insertUser['is_admin'] = 1;
			$insertUser['is_actived'] = 0;

			$insertData = $this->Db_dml->insert('tb_user', $insertUser);

			if ($insertData) {
				$expired_date = date('Y-m-d', strtotime('+31 days', strtotime(date('Y-m-d'))));
				// Insert tb_subscriber
				$insertSubscriber['user_id'] = $insertData;
				$insertSubscriber['jenis_paket'] = $this->input->post('jenis_subscribe');
				$insertSubscriber['jumlah_user'] = $this->input->post('jumlah_pegawai');
				$insertSubscriber['expired_date'] = $expired_date;

				$insertDataS = $this->Db_dml->insert('tb_subscriber', $insertSubscriber);

				// set Session
				$sess['email'] = $this->input->post('email');
				$sess['telepon'] = $this->input->post('telp');
				$sess['nama'] = $this->input->post('nama_lengkap');
				$sess['alamat'] = $this->input->post('alamat_channel');
				$sess['pegawai'] = $this->input->post('jumlah_pegawai');
				$sess['harga'] = $this->input->post('harga_biaya');
				$sess['hargaitem'] = $this->input->post('hargaitem');
				$sess['paket'] = $this->input->post('paket');
				$sess['id_user'] = $insertData;

				$this->session->set_userdata('subscriber',$sess);
				$result['status'] = true;
				$result['message'] = 'Data berhasil disimpan.';
			}else{
				$result['status'] = false;
            	$result['message'] = 'Data anda sudah terdaftar.';
			}
		}else{
			$result['status'] = false;
            $result['message'] = 'Data anda sudah terdaftar.';
		}
		echo json_encode($result);
	}

	public function auth()
	{
		$password = md5($this->input->post('password'));

		$user = $this->Db_select->query('select *from tb_user where email_user like "%'.$this->input->post('username').'%"');

		if ($user) {
			if ($user->password_user == $password) {
				if ($user->is_actived == 1) {
					if ($user->is_admin == 1) {
						$sess['email_user'] = $user->email_user;
						$sess['nama'] = $user->nama_user;
						$sess['foto'] = $user->foto_user;
						$sess['id_user'] = $user->user_id;
						$sess['id_unit'] = $user->id_unit;
						$sess['id_channel'] = $user->admin_channel;
						$sess['is_new'] = $user->is_new;
						$sess['akses'] = 'admin_utama';
						$this->session->set_userdata('user',$sess);

                        $result['status'] = true;
                        $result['ready'] = true;
                        $result['message'] = 'Login berhasil.';
						$result['data'] = '';
					}
				}else{
					$sess['email'] = $user->email_user;
					$sess['telepon'] = $user->telp_user;
					$sess['nama'] = $user->nama_user;
					$sess['alamat'] = $user->alamat_user;
					$sess['pegawai'] = $this->input->post('jumlah_pegawai');
					$sess['harga'] = $this->input->post('harga_biaya');
					$sess['hargaitem'] = $this->input->post('hargaitem');
					$sess['paket'] = $this->input->post('paket');
					$sess['id_user'] = $user->user_id;
					$sess['id_channel'] = $user->admin_channel;
					$this->session->set_userdata('subscriber',$sess);

					$result['status'] = true;
					$result['ready'] = false;
                    $result['message'] = 'Success';
                    $result['data'] = '';
				}
			}else{
				$result['status'] = false;
				$result['ready'] = false;
                $result['message'] = 'Username dan password tidak cocok.';
                $result['data'] = '';
			}
		}else{
			$result['status'] = false;
			$result['ready'] = false;
            $result['message'] = 'Data tidak ditemukan.';
            $result['data'] = '';
		}

		echo json_encode($result);
	}
}