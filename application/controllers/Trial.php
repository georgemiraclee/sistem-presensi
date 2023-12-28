<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trial extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('WS_Curl_Class');
		$this->WS_Curl_Class = new WS_Curl_Class();
	}
	public function index()
	{
		if ($this->input->get('source')) {
			/* action tambah data */
			$insert['total'] = 1;
			$this->Db_dml->insert('count_data', $insert);
			// $getCount = $this->Db_select->select_where('count_data', 'id = 1')->total;
			// $tambah = $getCount+1;
			// $updateData['total'] = $tambah;
			// $whereData['id'] = 1;
			// $this->Db_dml->update('count_data', $updateData, $whereData);
		}
		$this->load->view('trial');
	}

	public function invite_me()
	{
		$this->load->view('invite');
	}

	public function insert_invite()
	{
		$where['email_user'] = $this->input->post('email');
		$cekAkun = $this->Db_select->select_where('invite_me', $where);

		if (!$cekAkun) {
			$insert['nama_user'] = $this->input->post('nama');
			$insert['email_user'] = $this->input->post('email');
			$insert['telp_user'] = $this->input->post('telepon');
			$insert['nama_perusahaan'] = $this->input->post('perusahaan');
			$insert['bidang_usaha'] = $this->input->post('bidang_usaha');
			$insert['jumlah_karyawan'] = $this->input->post('jumlah_karyawan');
			$insert['metode'] = $this->input->post('metode');
			$insert['implementasi'] = $this->input->post('implementasi');
			$insert['alamat'] = $this->input->post('alamat');

			$insertData = $this->Db_dml->normal_insert('invite_me', $insert);

			if ($insertData) {
				$data['telepon'] = $this->input->post('telepon');
				$data['nama_user'] = $this->input->post('nama');
				$data['perusahaan'] = $this->input->post('perusahaan');
				$data['email'] = $this->input->post('email');
				$data['jumlah_pegawai'] = $this->input->post('jumlah_karyawan');
				$data['alamat'] = $this->input->post('alamat');

				/* input to CRM */
				$this->sendToCRM($data);
				/* create account trial */
				// $this->insertChannel();
				/* kirim notifikasi email */
				$dataEmail = array(
					'subject' => 'Invitation Request Demo Pressensi',
						'nama'=> $this->input->post('nama'),
						'perusahaan'=> $this->input->post('perusahaan'),
						'email'=> $this->input->post('email'),
					);
				$this->send_email($dataEmail);
			}
		}

		$result['status'] = true;
		$result['message'] = 'Data berhasil disimpan.';
		$result['data'] = array();

		echo json_encode($result);
	}

	public function insert()
    {
        $data['nama_user'] = $this->input->post('nama');
        $data['email'] = $this->input->post('email');
        $data['password'] = md5($this->input->post('password')) ;
        $data['perusahaan'] = $this->input->post('perusahaan');
        $data['jumlah_pegawai'] = $this->input->post('jumlah_pegawai');
        $data['telepon'] = $this->input->post('telepon');
		$data['alamat'] = $this->input->post('alamat');
		
        /* cek dulu email */
        $cekData = $this->Db_select->select_where('tb_new_trial', 'email = "'.$this->input->post('email').'"');
        
        if (!$cekData) {
			$insertData = $this->Db_dml->normal_insert('tb_new_trial', $data);
			if ($insertData == 1) {
				/* input to CRM */
				$this->sendToCRM($data);
				/* create account trial */
				// $this->insertChannel();
				/* kirim notifikasi email */
				$dataEmail = array(
					'subject' => 'Demo Account Request Accepted',
						'nama'=> $this->input->post('nama'),
						'perusahaan'=> $this->input->post('perusahaan'),
						'email'=> $this->input->post('email'),
					);
				$this->send_email($dataEmail);

				$result['status'] = true;
				$result['message'] = 'Data berhasil disimpan.';
				$result['data'] = array();
			}else{
				$result['status'] = true;
				$result['message'] = 'Data berhasil disimpan.';
				$result['data'] = array();
			}
        }else{
        	$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();
        }

        echo json_encode($result);exit();
    }

    public function insertChannel()
    {
        $data['nama_channel'] = $this->input->post('perusahaan');
		$data['alamat_channel'] = $this->input->post('alamat');
		$data['email_channel'] = $this->input->post('email');
		$data['telp_channel'] = $this->input->post('telepon');
        $data['limit_user'] = 5;
    
        $insertData = $this->Db_dml->insert('tb_channel', $data);
		if ($insertData) {
            /* create admin channel */
            $dataInsertUser['nama_user'] = $this->input->post('nama');
            $dataInsertUser['email_user'] = $this->input->post('email');
            $dataInsertUser['password_user'] = md5($this->input->post('password'));
            $dataInsertUser['admin_channel'] = $insertData;
            $dataInsertUser['is_aktif'] = 1;
            $dataInsertUser['nip'] = 1;
            $dataInsertUser['is_admin'] = 1;
            $dataInsertUser['is_new'] = 1;
            $dataInsertUser['is_trial'] = 1;

            $insertData = $this->Db_dml->normal_insert('tb_user', $dataInsertUser);
            /* end admin create channel */

			$potongan_apel = array(
				array(
					'nama_tipe' => 'Sakit (Tanpa Surat Dokter)',
					'id_channel' => $insertData
				),
				array(
					'nama_tipe' => 'Keperluan Pribadi',
					'id_channel' => $insertData
				),
				array(
					'nama_tipe' => 'Tanpa Keterangan',
					'id_channel' => $insertData
				),
			);
			$this->Db_dml->insert_batch('tb_potongan_apel', $potongan_apel);
			$this->Db_dml->insert_batch('tb_potongan_keluar_jamkerja', $potongan_apel);
			$this->Db_dml->insert_batch('tb_potongan_mangkir', $potongan_apel);
			$struktur = array(
				'id_channel' => $insertData,
				'struktur_data' => '[{"id":"1","name":"Pimpinan","parent":"0","used":"1"},{"id":"2","name":"Staff","parent":"1","used":"1"}]'
			);
			$this->Db_dml->normal_insert('tb_struktur_organisasi', $struktur);
			$this->create($insertData);
			$this->addDefault($insertData);
			$this->addStatusPengajuan($insertData);

			$input['jumlah_cuti_tahunan'] = 14;
			$input['jatah_cuti_bulanan'] = 3;
			$input['batasan_cuti'] = 0;
			$input['id_channel'] = $insertData;

			$this->Db_dml->insert('tb_pengaturan_cuti', $input);

			return true;
		}else{
			return false;
		}
    }

    public function create($id_channel) {
		$file = 'appconfig/'.$id_channel.'_auto_respon.txt';
        $txt = 	array(
        			"jam_kerja" => array(
        				"monday" => array(
        					"to" => "15:30", "from" => "07:30"
        				),"tuesday" => array(
        					"to" => "15:30", "from" => "07:30"
        				),"wednesday" => array(
        					"to" => "15:30", "from" => "07:30"
        				),"thursday" => array(
        					"to" => "15:30", "from" => "07:30"
        				),"friday" => array(
        					"to" => "15:30", "from" => "07:30"
        				),"saturday" => array(
        					"to" => "15:30", "from" => "07:30"
        				),"sunday" => array(
        					"to" => "15:30", "from" => "07:30"
        				)
        			),
        			"jam_istirahat" => array(
	    				"monday" => array(
	    					"to" => "15:30", "from" => "07:30"
	    				),"tuesday" => array(
	    					"to" => "15:30", "from" => "07:30"
	    				),"wednesday" => array(
	    					"to" => "15:30", "from" => "07:30"
	    				),"thursday" => array(
	    					"to" => "15:30", "from" => "07:30"
	    				),"friday" => array(
	    					"to" => "15:30", "from" => "07:30"
	    				),"saturday" => array(
	    					"to" => "15:30", "from" => "07:30"
	    				),"sunday" => array(
	    					"to" => "15:30", "from" => "07:30"
	    				)
	    			),
	    			"dispensasi"=> "0",
	    			"hari_kerja" => "5",
            	);
        write_file($file, json_encode($txt));
    }

    public function addDefault($id_channel)
	{
        $namaFile = 'jadwal_default_'.$id_channel.time().'.txt';
		$file = 'appconfig/new/'.$namaFile;
		$insertDB['nama_pola_kerja'] = 'Pola Kerja Default';
		$insertDB['toleransi_keterlambatan'] = 0;
		$day = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
		$libur = 2;
		$hari_kerja = 7;
		$insertDB['lama_pola_kerja'] = $hari_kerja;
		$insertDB['lama_hari_kerja'] = $hari_kerja-$libur;
		$insertDB['lama_hari_libur'] = $libur;
		$insertDB['id_channel'] = $id_channel;
		$insertDB['is_default'] = 1;
		$txt = array(
				"jam_kerja" => array(
    				"monday" => array(
    					"libur" => 0, "to" => '16:00', "from" => '08:00'
    				),"tuesday" => array(
    					"libur" => 0, "to" => '16:00', "from" => '08:00'
    				),"wednesday" => array(
    					"libur" => 0, "to" => '16:00', "from" => '08:00'
    				),"thursday" => array(
    					"libur" => 0, "to" => '16:00', "from" => '08:00'
    				),"friday" => array(
    					"libur" => 0, "to" => '16:00', "from" => '08:00'
    				),"saturday" => array(
    					"libur" => 1, "to" => '16:00', "from" => '08:00'
    				),"sunday" => array(
    					"libur" => 1, "to" => '16:00', "from" => '08:00'
    				)
    			)
            );
		write_file($file, json_encode($txt));
		$insertDB['file_pola_kerja'] = $namaFile;
		$insert = $this->Db_dml->insert('tb_pola_kerja', $insertDB);
    }
    
    public function addStatusPengajuan($id_channel)
	{
		if ($id_channel != 1) {
			$nama_status_pengajuan = array('Cuti','Izin','Sakit');
			for ($i=0; $i < count($nama_status_pengajuan) ; $i++) { 
				$input['nama_status_pengajuan'] = $nama_status_pengajuan[$i];
				$input['id_channel'] = $id_channel;
				$input['is_default'] = 1;
				$this->Db_dml->insert('tb_status_pengajuan', $input);
			}
		}
	}

    public function cek()
    {
        $data = array(
            'subject' => 'Demo Account Request Accepted',
            'nama'=> "",
            'perusahaan'=> "",
            'email'=> "",
        );
        $this->load->view('email/welcome_email', $data);
    	// $this->send_email('rhmtsaepuloh@gmail.com', 'Demo Account Request Accepted');
    }

    public function send_email($data)
    {
		$message = $this->load->view('email/welcome_email', $data, TRUE);
		
    	$this->email->clear();
        $this->email->to($data['email']);
        $this->email->from('admin@pressensi.com', 'Admin Pressensi');
        $this->email->subject($data['subject']);
        $this->email->message($message);
        $this->email->send();
	}
	
	public function sendToCRM($data)
	{
		if ($this->WS_Curl_Class->login()) {
			$type = 'Leads';
			// echo json_encode($this->WS_Curl_Class->describe("Leads")); exit();
			
			/* declare element yang akan di kirim */
			$element = array(
				"firstname" => "",
				"phone" => $data['telepon'],
				"lastname" => $data['nama_user'],
				"mobile" => $data['telepon'],
				"company" => $data['perusahaan'],
				"email" => $data['email'],
				"leadsource" => "",
				"industry" => "Other",
				"leadstatus" => "Contact in Future",
				"noofemployees" => $data['jumlah_pegawai'],
				"lane" => $data['alamat'],
				"assigned_user_id" => "19x1"
			);

			/* Action input to CRM */
			$filepath = "";
			$result = $this->WS_Curl_Class->create($type, $element, $filepath);
		}
	}
	public function sukses()
	{
		$this->load->view('success');
	}
	public function old_insert()
    {
        $data['nama_user'] = $this->input->post('nama');
        $data['email'] = $this->input->post('email');
        $data['password'] = md5($this->input->post('password')) ;
        $data['perusahaan'] = $this->input->post('perusahaan');
        $data['jumlah_pegawai'] = $this->input->post('jumlah_pegawai');
        $data['telepon'] = $this->input->post('telepon');
		$data['alamat'] = $this->input->post('alamat');
		
        /* cek dulu email */
        $cekData = $this->Db_select->select_where('tb_trial', 'email = "'.$this->input->post('email').'"');
        
        if (!$cekData) {
            /* cek email akun */
            $cekAkun = $this->Db_select->select_where('tb_user', 'email_user = "'.$this->input->post('email').'"');

            if (!$cekAkun) {
                $insertData = $this->Db_dml->normal_insert('tb_trial', $data);
                if ($insertData == 1) {
					/* input to CRM */
					$this->sendToCRM($data);
                    /* create account trial */
                    $this->insertChannel();
                    /* kirim notifikasi email */
                    $dataEmail = array(
                        'subject' => 'Demo Account Request Accepted',
                         'nama'=> $this->input->post('nama'),
                         'perusahaan'=> $this->input->post('perusahaan'),
                         'email'=> $this->input->post('email'),
                     );
                    $this->send_email($dataEmail);
    
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
                $result['message'] = 'Email sudah terdaftar sebagai Client kami.';
                $result['data'] = array();
            }
        }else{
        	$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();
        }

        echo json_encode($result);exit();
    }
}