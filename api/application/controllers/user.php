<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class user extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
    $this->load->library(array('global_lib','loghistory'));
    $this->global_lib->authentication();
    $this->loghistory = new loghistory;
    $this->result = array(
      'status' => false,
      'message' => 'Data tidak ditemukan',
      'data' => null
    );
	}

	public function detailUser()
	{
    $where['token_user'] = $this->input->post('token');
    $cek = $this->db_select->select_where('tb_user', $where);
    if ($cek) {
      if ($cek->is_aktif == 1) {
        $selectUnit = $this->db_select->query('select *from tb_unit where id_unit = '.$cek->id_unit.'');
        $selectJabatan = $this->db_select->query('select *from tb_jabatan where id_jabatan = "'.$cek->jabatan.'"');
    
        if (!$selectJabatan) {
          $nama_jabatan = "";
        }else{
          $nama_jabatan = $selectJabatan->nama_jabatan;
        }
    
        $data['nip'] = $cek->nip;
        $data['unit'] = $selectUnit->nama_unit;
        $data['nama'] = $cek->nama_user;
        $data['jabatan'] = $nama_jabatan;
        $data['email'] = $cek->email_user;
        $data['telp'] = $cek->telp_user;
        $data['alamat'] = $cek->alamat_user;
        $data['is_trial'] = $cek->is_trial;
    
        if (!is_null($cek->foto_user)) {
          $filename2 = realpath('../assets/images/member-photos/'.$cek->foto_user);

          if (file_exists($filename2)) {
            $data['foto'] = image_url() . 'images/member-photos/' . $cek->foto_user;
          }else{
            $data['foto'] = image_url() . 'images/member-photos/default_photo.jpg';
          }
        } else {
          $data['foto'] = image_url() . 'images/member-photos/default_photo.jpg';
        }
        $data['banner'] = image_url() . 'images/banner/default.png';

        $this->result = array(
          'status' => true,
          'message' => 'Data ditemukan',
          'data' => $data
        );
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Akun Anda telah di non aktifkan karena suatu alasan',
          'data' => null
        );
      }
    }else{
      $this->result = array(
        'status' => false,
        'message' => 'Data tidak ditemukan',
        'data' => null
      );
    }

    $this->loghistory->createLog($this->result);
    echo json_encode($this->result);
	}

	public function updateProfile()
	{
		$require = array('nip');
    $this->global_lib->input($require);
    $where['nip'] = $this->input->post('nip');
    $cek = $this->db_select->select_where('tb_user', $where);
    if ($cek) {
      if ($this->input->post('telp')) {
        $update['telp_user'] = $this->input->post('telp');
      }
      if ($this->input->post('nama')) {
        $update['nama_user'] = $this->input->post('nama');
      }
      if ($this->input->post('alamat')) {
        $update['alamat_user'] = $this->input->post('alamat');
      }
      if ($this->input->post('email')) {
        $update['email_user'] = $this->input->post('email');
      }
      if (isset($_FILES['userfile'])) {
        if ($_FILES['userfile']['name'] != '') {
          $path = realpath('../assets/images/member-photos');
          $time = $this->input->post('nip')."_".strtotime('now');
          $config = array(
            'allowed_types' => 'jpg|jpeg|gif|png',
            'upload_path' => $path,
            'encrypt_name' => false,
            'file_name' => $time
          );
          $this->upload->initialize($config);
          if ($this->upload->do_upload()) {
              $img_data = $this->upload->data();
              $new_imgname = $time.$img_data['file_ext'];
              $new_imgpath = $img_data['file_path'].$new_imgname;
              rename($img_data['full_path'], $new_imgpath);
              $update['foto_user'] = $new_imgname;
          } else {
            $this->result = array(
              'status' => false,
              'message' => $this->upload->display_errors(),
              'data' => null
            );
            $this->loghistory->createLog($this->result);
            echo json_encode($this->result);exit();
          }
        }
      }
      if (isset($update)) {
        $ubah = $this->db_dml->update('tb_user', $update, $where);
        if ($ubah == 1) {
          $select = $this->db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit join tb_jabatan c on a.jabatan = c.id_jabatan where a.nip = "'.$this->input->post('nip').'"');

          $sess['nip'] = $select->nip;
          $sess['unit'] = $select->nama_unit;
          $sess['nama'] = $select->nama_user;
          $sess['jabatan'] = $select->nama_jabatan;
          $sess['email'] = $select->email_user;
          $sess['telp'] = $select->telp_user;
          $sess['alamat'] = $select->alamat_user;
          $sess['is_trial'] = $select->is_trial;
          $sess['foto'] = (!is_null($select->foto_user)) ? image_url() . 'images/member-photos/' . $select->foto_user : image_url() . 'images/member-photos/default_photo.jpg';
          $sess['banner'] = (!is_null($select->banner)) ? image_url() . 'images/banner/' . $select->banner : image_url() . 'images/banner/default.png';
          $this->result = array(
            'status' => true,
            'message' => 'Data berhasil diubah',
            'data' => $sess
          );
        }else{
          $select = $this->db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit join tb_jabatan c on a.jabatan = c.id_jabatan where a.nip = "'.$this->input->post('nip').'"');

          $sess['nip'] = $select->nip;
          $sess['unit'] = $select->nama_unit;
          $sess['nama'] = $select->nama_user;
          $sess['jabatan'] = $select->nama_jabatan;
          $sess['email'] = $select->email_user;
          $sess['telp'] = $select->telp_user;
          $sess['alamat'] = $select->alamat_user;
          $sess['is_trial'] = $select->is_trial;
          $sess['foto'] = (!is_null($select->foto_user)) ? image_url() . 'images/member-photos/' . $select->foto_user : image_url() . 'images/member-photos/default_photo.jpg';
          $sess['banner'] = (!is_null($select->banner)) ? image_url() . 'images/banner/' . $select->banner : image_url() . 'images/banner/default.png';

          $this->result = array(
            'status' => true,
            'message' => 'Data berhasil diubah.',
            'data' => $sess
          );
        }
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Data harus diisi',
          'data' => null
        );
      }
    }else{
      $this->result = array(
        'status' => false,
        'message' => 'Data tidak ditemukan',
        'data' => null
      );
    }
    $this->loghistory->createLog($this->result);
    echo json_encode($this->result);
	}

	public function updatePassword()
	{
		$require = array('nip');
        $this->global_lib->input($require);
        $where['nip'] = $this->input->post('nip');
        $cek = $this->db_select->select_where('tb_user', $where);
        if ($cek) {
        	$require = array('password', 'password_lama', 'ulangi_password');
    		$this->global_lib->input($require);
    		$ulangiPassword = md5($this->input->post('ulangi_password'));
    		$passwordLama = md5($this->input->post('password_lama'));
            $password = $this->input->post('password');
            $update['password_user'] = md5($this->input->post('password'));
            if (strlen($password) > 5) {
            	if ($passwordLama == $cek->password_user) {
            		if ($update['password_user'] != $ulangiPassword) {
            			$this->result = array(
				            'status' => false,
				            'message' => 'Password dan ulangi password tidak cocok.',
				            'data' => null
				        );
            			$this->loghistory->createLog($this->result);
			        	echo json_encode($this->result);exit();
            		}
            	}else{
            		$this->result = array(
			            'status' => false,
			            'message' => 'Password lama salah.',
			            'data' => null
			        );
            		$this->loghistory->createLog($this->result);
			        echo json_encode($this->result);exit();
            	}
            }else{
            	$this->result = array(
		            'status' => false,
		            'message' => 'Panjang password minimal 6 karakter.',
		            'data' => null
		        );
            	$this->loghistory->createLog($this->result);
		        echo json_encode($this->result);exit();
            }
            if (isset($update)) {
            	$ubah = $this->db_dml->update('tb_user', $update, $where);
            	if ($ubah == 1) {
            		$select = $this->db_select->select_where('tb_user', $where);
            		$sess['nip'] = $select->nip;
                    $sess['foto'] = (!is_null($select->foto_user)) ? image_url() . 'images/member-photos/' . $select->foto_user : image_url() . 'images/member-photos/default_photo.jpg';
                    $sess['banner'] = (!is_null($select->banner)) ? image_url() . 'images/banner/' . $select->banner : image_url() . 'images/banner/default.png';
                    $sess['token'] = $select->token_user;
                    $sess['akses'] = ($select->is_admin == 1) ? 'admin' : 'user';
                    $this->result = array(
			            'status' => true,
			            'message' => 'Data berhasil diubah',
			            'data' => $sess
			        );
            	}else{
            		$this->result = array(
			            'status' => false,
			            'message' => 'Data gagal diubah.',
			            'data' => null
			        );
            	}
            }else{
            	$this->result = array(
		            'status' => false,
		            'message' => 'Data tidak ditemukan',
		            'data' => null
		        );
            }
        }else{
        	$this->result = array(
	            'status' => false,
	            'message' => 'Data tidak ditemukan',
	            'data' => null
	        );
        }
        $this->loghistory->createLog($this->result);
        echo json_encode($this->result);
	}

	public function updateFotoProfile()
	{
		$require = array('nip');
        $this->global_lib->input($require);
        $where['nip'] = $this->input->post('nip');
        $cek = $this->db_select->select_where('tb_user', $where);
        if ($cek) {
	        if (isset($_FILES['userfile'])) {
	        	if ($_FILES['userfile']['name'] != '') {
	                $path = realpath('../assets/images/member-photos');
	                $time = $this->input->post('nip')."_".strtotime('now');
	                $config = array('allowed_types' => 'jpg|jpeg|gif|png',
	                                'upload_path' => $path,
	                                'encrypt_name' => false,
	                                'file_name' => $time
	                               );
	                $this->upload->initialize($config);
	                if ($this->upload->do_upload()) {
	                    $img_data = $this->upload->data();
	                    $new_imgname = $time.$img_data['file_ext'];
	                    $new_imgpath = $img_data['file_path'].$new_imgname;
	                    rename($img_data['full_path'], $new_imgpath);
	                    $update['foto_user'] = $new_imgname;
	                } else {
	                	$this->result = array(
				            'status' => false,
				            'message' => $this->upload->display_errors(),
				            'data' => null
				        );
	                    $this->loghistory->createLog($this->result);
	    				echo json_encode($this->result);exit();
	                }
	                $ubah = $this->db_dml->update('tb_user', $update, $where);
	                if ($ubah == 1) {
	                	$select = $this->db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit join tb_jabatan c on a.jabatan = c.id_jabatan where a.nip = "'.$this->input->post('nip').'"');

	            		$sess['nip'] = $select->nip;
	            		$sess['unit'] = $select->nama_unit;
	            		$sess['nama'] = $select->nama_user;
	            		$sess['jabatan'] = $select->nama_jabatan;
	            		$sess['email'] = $select->email_user;
	            		$sess['telp'] = $select->telp_user;
	            		$sess['alamat'] = $select->alamat_user;
	            		$sess['is_trial'] = $select->is_trial;
	                    $sess['foto'] = (!is_null($select->foto_user)) ? image_url() . 'images/member-photos/' . $select->foto_user : image_url() . 'images/member-photos/default_photo.jpg';
	                    $sess['banner'] = (!is_null($select->banner)) ? image_url() . 'images/banner/' . $select->banner : image_url() . 'images/banner/default.png';
	                    $this->result = array(
				            'status' => true,
				            'message' => 'Data berhasil diubah',
				            'data' => $sess
				        );
	            	}else{
	            		$this->result = array(
				            'status' => false,
				            'message' => 'Data gagal diubah.',
				            'data' => null
				        );
	            	}
	        	}
	        }
        }else{
        	$this->result = array(
	            'status' => false,
	            'message' => 'Data tidak ditemukan',
	            'data' => null
	        );
        }
        $this->loghistory->createLog($this->result);
        echo json_encode($this->result);
	}

	public function updateFotoBanner()
	{
		$require = array('nip');
    $this->global_lib->input($require);
    $where['nip'] = $this->input->post('nip');
    $cek = $this->db_select->select_where('tb_user', $where);
    if ($cek) {
      if (isset($_FILES['userfile'])) {
        if ($_FILES['userfile']['name'] != '') {
          $path = realpath('../assets/images/banner');
          $time = $this->input->post('nip')."_".strtotime('now');
          $config = array('allowed_types' => 'jpg|jpeg|gif|png',
            'upload_path' => $path,
            'encrypt_name' => false,
            'file_name' => $time
          );
          $this->upload->initialize($config);
          if ($this->upload->do_upload()) {
              $img_data = $this->upload->data();
              $new_imgname = $time.$img_data['file_ext'];
              $new_imgpath = $img_data['file_path'].$new_imgname;
              rename($img_data['full_path'], $new_imgpath);
              $update['banner'] = $new_imgname;
          } else {
            $this->result = array(
              'status' => false,
              'message' => $this->upload->display_errors(),
              'data' => null
            );
            $this->loghistory->createLog($this->result);
            echo json_encode($this->result);exit();
          }
          $ubah = $this->db_dml->update('tb_user', $update, $where);
          
          if ($ubah == 1) {
            $select = $this->db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit join tb_jabatan c on a.jabatan = c.id_jabatan where a.nip = "'.$this->input->post('nip').'"');

            $sess['nip'] = $select->nip;
            $sess['unit'] = $select->nama_unit;
            $sess['nama'] = $select->nama_user;
            $sess['jabatan'] = $select->nama_jabatan;
            $sess['email'] = $select->email_user;
            $sess['telp'] = $select->telp_user;
            $sess['alamat'] = $select->alamat_user;
            $sess['is_trial'] = $select->is_trial;
            $sess['foto'] = (!is_null($select->foto_user)) ? image_url() . 'images/member-photos/' . $select->foto_user : image_url() . 'images/member-photos/default_photo.jpg';
            $sess['banner'] = (!is_null($select->banner)) ? image_url() . 'images/banner/' . $select->banner : image_url() . 'images/banner/default.png';
            $this->result = array(
              'status' => true,
              'message' => 'Data berhasil diubah',
              'data' => $sess
            );
          }else{
            $this->result = array(
              'status' => false,
              'message' => 'Data gagal diubah.',
              'data' => null
            );
          }
        }
      }
    }else{
      $this->result = array(
        'status' => false,
        'message' => 'Data tidak ditemukan',
        'data' => null
      );
    }
    $this->loghistory->createLog($this->result);
    echo json_encode($this->result);
	}

	public function LogAbsensi()
	{
		$require = array('nip');
    $this->global_lib->input($require);
    if (is_numeric($this->input->post('nip'))) {
      $getUser = $this->db_select->query('select a.*, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$this->input->post('nip').'"');
      $getpola = $this->db_select->query('select * FROM tb_pola_user a join tb_pola_kerja b on a.id_pola_kerja = b.id_pola_kerja where user_id ='.$getUser->user_id);
      if ($getpola) {
        $file = '../appconfig/new/'.$getpola->file_pola_kerja;
      }else{
        $getkerja = $this->db_select->query('select * FROM tb_pola_kerja where is_default = 1 and id_channel ='.$getUser->id_channel);
        $file = '../appconfig/new/'.$getkerja->file_pola_kerja;
      }
      if ($this->input->post('dari')) {
        $dari = $this->input->post('dari');
        $newDate = date("Y-m-d", strtotime($dari));
        $selectAbsensi = $this->db_select->query('select *from tb_absensi where user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.$newDate.'"');
        if ($selectAbsensi) {
          $selectAbsensi->tanggal = $newDate;
        }
      }else{
        $selectAbsensi = $this->db_select->query('select *from tb_absensi where user_id = '.$getUser->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
        if ($selectAbsensi) {
          $selectAbsensi->tanggal = $selectAbsensi->created_absensi;
        }
      }
      if ($selectAbsensi) {
        if ($selectAbsensi->url_file_absensi != null || $selectAbsensi->url_file_absensi != '') {
          $selectAbsensi->foto_absensi = image_url().'images/absensi/'.$selectAbsensi->url_file_absensi;
        }else{
          $selectAbsensi->foto_absensi = null;
        }
        if ($selectAbsensi->status_absensi == 'Terlambat') {
          $jadwal = json_decode(file_get_contents($file))->jam_kerja;
          $day = strtolower(date("l"));
          $jadwalNew = date_create($jadwal->$day->from);
          $jam_skrg = date_create(date("H:i", strtotime($selectAbsensi->waktu_datang)));
          $diff  = date_diff($jam_skrg, $jadwalNew);
          $telat = "";
          if ($diff->h != 0) {
            $telat .= $diff->h." Jam ";
          }
          if ($diff->i != 0) {
            $telat .= $diff->i." Menit ";
          }
          $selectAbsensi->status_absensi = "Terlambat ".$telat;
        }
        $selectAbsensi->keterangan = $selectAbsensi->status_absensi;
        unset($selectAbsensi->id_absensi);
        unset($selectAbsensi->nip);
        unset($selectAbsensi->created_absensi);
        $this->result = array(
          'status' => true,
          'message' => 'Data ditemukan',
          'data' => $selectAbsensi
        );
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Data tidak ditemukan',
          'data' => null
        );
      }
    }else{
      $this->result = array(
        'status' => false,
        'message' => 'Data tidak ditemukan',
        'data' => null
      );
    }
    $this->loghistory->createLog($this->result);
    echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}

	public function StatistikJamKerja()
	{
		$require = array('nip');
        $this->global_lib->input($require);
        $dataHari = ['Senin', 'Selasa', 'Rabu', 'Kamis','Jumat'];
        $where['nip'] = $this->input->post('nip');
        $cek = $this->db_select->select_where('tb_user', $where);
        if ($cek) {
        	if ($this->input->post('dari') && $this->input->post('sampai')) {
        		$selisihHari = $this->convertTanggal($this->input->post('dari'), $this->input->post('sampai'));
        		if ($selisihHari >= 0 && $selisihHari < 7) {
        			//tampilkan data di hari itu
        			$getJumlah = $this->db_select->query_all('select *from tb_absensi where user_id = "'.$cek->user_id.'" and date_format(created_absensi,"%Y-%m-%d") between "'.$this->input->post('dari').'" and "'.$this->input->post('sampai').'" and waktu_datang is not null and waktu_pulang is not null');
		        	$jam = array();
		        	$kategori = array();
		        	foreach ($getJumlah as $key => $value) {
		        		$waktu_datang = date_create($value->waktu_datang);
		        		$waktu_pulang = date_create($value->waktu_pulang);
		        		$rentan = date_diff($waktu_datang, $waktu_pulang)->h;
		        		$tanggalAbsen = date('Y-m-d', strtotime($value->created_absensi));
		        		array_push($jam, $rentan);
		        		array_push($kategori, $tanggalAbsen);
		        	}
		        	$data['yAxis']['title']['text'] = 'Jam Kerja';
	        		$data['xAxis']['categories'] = $kategori;
		        	$data['series']['data'] = $jam;
        		}elseif ($selisihHari >= 7 && $selisihHari < 30) {
        			//tampilkan data di minggu itu
        			$getJumlah = $this->db_select->query_all('select *from tb_absensi where user_id = "'.$cek->user_id.'" and date_format(created_absensi,"%Y-%m-%d") between "'.$this->input->post('dari').'" and "'.$this->input->post('sampai').'" and waktu_datang is not null and waktu_pulang is not null');
		        	$jam = array();
		        	$kategori = array();
		        	foreach ($getJumlah as $key => $value) {
		        		$waktu_datang = date_create($value->waktu_datang);
		        		$waktu_pulang = date_create($value->waktu_pulang);
		        		$rentan = date_diff($waktu_datang, $waktu_pulang)->h;
		        		$tanggalAbsen = date('Y-m-d', strtotime($value->created_absensi));
		        		array_push($jam, $rentan);
		        		array_push($kategori, $tanggalAbsen);
		        	}
		        	$data['yAxis']['title']['text'] = 'Jam Kerja';
	        		$data['xAxis']['categories'] = $kategori;
		        	$data['series']['data'] = $jam;
        		}elseif ($selisihHari >= 30 and $selisihHari < 365) {
        			$getJumlah = $this->db_select->query_all('select date_format(created_absensi, "%M") bulan, sum(timestampdiff(hour, waktu_datang, waktu_pulang)) waktu from tb_absensi where user_id = "'.$cek->user_id.'" and date_format(created_absensi,"%Y-%m-%d") between "'.$this->input->post('dari').'" and "'.$this->input->post('sampai').'" and waktu_datang is not null and waktu_pulang is not null group by month(created_absensi)');
		        	$jam = array();
		        	$kategori = array();
		        	foreach ($getJumlah as $key => $value) {
		        		array_push($jam, $value->waktu);
		        		array_push($kategori, $value->bulan);
		        	}
		        	$data['yAxis']['title']['text'] = 'Jam Kerja';
	        		$data['xAxis']['categories'] = $kategori;
		        	$data['series']['data'] = $jam;
        		}elseif ($selisihHari >= 365) {
        			$getJumlah = $this->db_select->query_all('select date_format(created_absensi, "%M") bulan, sum(timestampdiff(hour, waktu_datang, waktu_pulang)) waktu from tb_absensi where user_id = "'.$cek->user_id.'" and date_format(created_absensi,"%Y-%m-%d") between "'.$this->input->post('dari').'" and "'.$this->input->post('sampai').'" and waktu_datang is not null and waktu_pulang is not null group by month(created_absensi)');
		        	$jam = array();
		        	$kategori = array();
		        	foreach ($getJumlah as $key => $value) {
		        		array_push($jam, $value->waktu);
		        		array_push($kategori, $value->bulan);
		        	}
		        	$data['yAxis']['title']['text'] = 'Jam Kerja';
	        		$data['xAxis']['categories'] = $kategori;
		        	$data['series']['data'] = $jam;
        		}
        	}else{
	        	$getJumlah = $this->db_select->query_all('select *from tb_absensi where user_id = "'.$cek->user_id.'" and created_absensi < date_sub(now(), interval 1 week) and waktu_datang is not null and waktu_pulang is not null');
	        	$jam = array();
	        	$kategori = array();
	        	foreach ($getJumlah as $key => $value) {
	        		$waktu_datang = date_create($value->waktu_datang);
	        		$waktu_pulang = date_create($value->waktu_pulang);
	        		$rentan = date_diff($waktu_datang, $waktu_pulang)->h;
	        		$tanggalAbsen = date('Y-m-d', strtotime($value->created_absensi));
	        		array_push($jam, $rentan);
	        		array_push($kategori, $tanggalAbsen);
	        	}
	        	$data['yAxis']['title']['text'] = 'Jam Kerja';
        		$data['xAxis']['categories'] = $kategori;
	        	$data['series']['data'] = $jam;
        	}
        	$this->result = array(
	            'status' => true,
	            'message' => 'Data ditemukan',
	            'data' => $data
	        );
			echo json_encode($this->result); exit();
        }else{
        	$this->result = array(
	            'status' => false,
	            'message' => 'Data tidak ditemukan',
	            'data' => null
	        );
        }
        echo json_encode($this->result);
        $this->loghistory->createLog($this->result);
	}

	public function DonutJamKerja()
	{
		$require = array('nip');
    $this->global_lib->input($require);
    $where['nip'] = $this->input->post('nip');
    $cek = $this->db_select->select_where('tb_user', $where);
    
    if ($cek) {
      $tepatWaktu = $this->db_select->query('select count(*) total from tb_absensi where user_id = "'.$cek->user_id.'" and created_absensi > date_sub(now(), interval 1 month) and status_absensi = "Tepat Waktu"')->total;
      $tidakHadir = $this->db_select->query('select count(*) total from tb_absensi where user_id = "'.$cek->user_id.'" and created_absensi > date_sub(now(), interval 1 month) and status_absensi = "Tidak Hadir" and day_off = 0')->total;
      $terlambat = $this->db_select->query('select count(*) total from tb_absensi where user_id = "'.$cek->user_id.'" and created_absensi > date_sub(now(), interval 1 month) and status_absensi = "Terlambat"')->total;

      $data[0] = array('x' => 'Tepat waktu', 'y' => (int) $tepatWaktu);
      $data[1] = array('x' => 'Tidak Hadir', 'y' => (int) $tidakHadir);
      $data[2] = array('x' => 'Terlambat', 'y' => (int) $terlambat);
      
      $this->result = array(
          'status' => true,
          'message' => 'Data ditemukan',
          'data' => $data
      );
    }else{
      $this->result = array(
          'status' => false,
          'message' => 'Data tidak ditemukan',
          'data' => null
      );
    }

    echo json_encode($this->result);
    $this->loghistory->createLog($this->result);
	}

	public function convertTanggal($dari, $sampai)
	{
		$tgl1 = $dari;  // 1 Oktober 2009
		$tgl2 = $sampai;  // 10 Oktober 2009
		// memecah tanggal untuk mendapatkan bagian tanggal, bulan dan tahun
		// dari tanggal pertama
		$pecah1 = explode("-", $tgl1);
		$date1 = $pecah1[2];
		$month1 = $pecah1[1];
		$year1 = $pecah1[0];
		// memecah tanggal untuk mendapatkan bagian tanggal, bulan dan tahun
		// dari tanggal kedua
		$pecah2 = explode("-", $tgl2);
		$date2 = $pecah2[2];
		$month2 = $pecah2[1];
		$year2 =  $pecah2[0];
		// menghitung JDN dari masing-masing tanggal
		$jd1 = GregorianToJD($month1, $date1, $year1);
		$jd2 = GregorianToJD($month2, $date2, $year2);
		// hitung selisih hari kedua tanggal
		$selisih = $jd2 - $jd1;
		return abs($selisih);
	}

	public function informasiSaldo()
	{
		$require = array('nip');
        $this->global_lib->input($require);
        $where['nip'] = $this->input->post('nip');
        $cek = $this->db_select->select_where('tb_user', $where);
        if ($cek) {
			$selectUsr = $this->db_select->query('select nama_jabatan, nama_unit, c.id_channel from tb_user a join tb_jabatan b on a.jabatan = b.id_jabatan join tb_unit c on a.id_unit = c.id_unit where a.user_id = "'.$cek->user_id.'"');
        	
        	//data user
        	$user['nama_user'] = $cek->nama_user;
        	$user['nip'] = $cek->nip;
        	$user['jabatan'] = $selectUsr->nama_jabatan;
        	$user['tanggal_periode'] = date('Y-m-01')." - ".date('Y-m-t');
        	$user['departemen'] = $selectUsr->nama_unit;
        	//Saldo
        	if ($selectUsr->id_channel == 7) {
    			$cek_keterlambatan = $this->db_select->query("select sum(total_potongan) total from tb_hstry_potongan_keterlambatan where user_id = '".$cek->user_id."' and date_format(created_hstry_keterlambatan, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d') group by user_id");
        	}else{
    			$cek_keterlambatan = $this->db_select->query("select sum(total_potongan) total from tb_hstry_potongan_keterlambatan where user_id = '".$cek->user_id."' and date_format(created_hstry_keterlambatan, '%Y-%m') = date_format(now(), '%Y-%m') group by user_id");
			}
			if ($cek_keterlambatan) {
				$cek_keterlambatan->total;
			}else{
				$cek_keterlambatantotal = 0;
			}
			$cek_tidakhadir = $this->db_select->query("select sum(total_potongan) total from tb_hstry_potongan_mangkir where user_id = '".$cek->user_id."' and date_format(created_hstry_mangkir, '%Y-%m') = date_format(now(), '%Y-%m') group by user_id");
			if ($cek_tidakhadir) {
				$cek_tidakhadirtotal = $cek_tidakhadir->total;
			}else{
				$cek_tidakhadirtotal = 0;
			}
    		//potongan lainnya
			$cek_apel = $this->db_select->query("select sum(total_potongan) total from tb_hstry_potongan_apel where user_id = '".$cek->user_id."' and date_format(created_hstry_potongan_apel, '%Y-%m') = date_format(now(), '%Y-%m') group by user_id");
			if ($cek_apel) {
				$cek_apeltotal = $cek_apel->total;
			}else{
				$cek_apeltotal = 0;
			}
			$cek_batalAbsen = $this->db_select->query("select sum(total_potongan) total from tb_hstry_potongan_batal_absensi where user_id = '".$cek->user_id."' and date_format(created_hstry_potongan_batal_absensi, '%Y-%m') = date_format(now(), '%Y-%m') group by user_id");
			if ($cek_batalAbsen) {
				$cek_batalAbsentotal = $cek_batalAbsen->total;
			}else{
				$cek_batalAbsentotal = 0;
			}
			$cek_pulang = $this->db_select->query("select sum(total_potongan) total from tb_hstry_potongan_keluar_jamkerja where user_id = '".$cek->user_id."' and date_format(created_hstry_meninggalkan_kantor, '%Y-%m') = date_format(now(), '%Y-%m') group by user_id");
			if ($cek_pulang) {
				$cek_pulangtotal = $cek_pulang->total;
			}else{
				$cek_pulangtotal = 0;
			}
        	$slipGaji['saldo_user'] = $cek->gaji_pokok;
        	$slipGaji['potongan_keterlambatan'] = $cek_keterlambatantotal;
        	$slipGaji['potongan_tidak_hadir'] = $cek_tidakhadirtotal;
        	$slipGaji['potongan_lainnya'] = $cek_apeltotal+$cek_batalAbsentotal+$cek_pulangtotal;
        	$slipGaji['sisa_saldo'] = $slipGaji['saldo_user']-$slipGaji['potongan_keterlambatan']-$slipGaji['potongan_tidak_hadir']-$slipGaji['potongan_lainnya'];
        	$data['user'] = $user;
        	$data['slip_gaji'] = $slipGaji;
        	$this->result = array(
	            'status' => true,
	            'message' => 'Data ditemukan',
	            'data' => $data
	        );
        }else{
			$this->result = array(
	            'status' => false,
	            'message' => 'Data tidak ditemukan',
	            'data' => null
	        );
        }
        $this->loghistory->createLog($this->result);
        echo json_encode($this->result);
	}

	public function listPegawai()
	{
		$require = array('page');
    $this->global_lib->input($require);
    
    $getUser = $this->db_select->select_where('tb_user', ['token_user' => $this->input->post('token')]);
    if ($getUser) {
      $page = $this->input->post('page');
      $limit = 15;
      
      if (is_numeric($page) && $page > 0) {
        $start = ($page - 1) * $limit;
        $cekUser = $this->db_select->query('select user_id, a.id_unit, id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where user_id = "'.$getUser->user_id.'"');
        
        $listPegawai = $this->db_select->query_all('select a.user_id, a.nama_user, a.jabatan, a.foto_user, a.tanggal_lahir, b.created_absensi, b.url_file_absensi, b.manual_absen, x.id_channel from tb_user a join tb_unit x on a.id_unit = x.id_unit left outer join (select user_id, created_absensi, url_file_absensi, manual_absen from tb_absensi where date_format(created_absensi, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")) b on a.user_id = b.user_id where a.is_aktif = 1 and a.id_unit = "'.$cekUser->id_unit.'" or a.open_to = '.$getUser->user_id.' group by a.nip order by ifnull(b.created_absensi, "9999-99-99 99:99:99"), a.nama_user asc limit '.$start.','.$limit);
        
        $totalPegawai = $this->db_select->query('select count(*) total from tb_user a join tb_unit x on a.id_unit = x.id_unit left outer join (select user_id, created_absensi from tb_absensi where date_format(created_absensi, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")) b on a.user_id = b.user_id where a.is_aktif = 1 and a.id_unit = "'.$cekUser->id_unit.'" or a.open_to = '.$getUser->user_id.' group by x.id_channel');

        foreach ($listPegawai as $key => $value) {
          $namaJabatan = $this->db_select->query('select nama_jabatan from tb_jabatan where id_jabatan = "'.$value->jabatan.'"');
          if ($namaJabatan) {
            $jabatan = $namaJabatan->nama_jabatan;
          }else{
            $jabatan = "-";
          }

          /* cek absensi */
          $cekKehadiran = $this->db_select->query('select status_absensi, waktu_datang, waktu_istirahat, waktu_kembali, waktu_pulang from tb_absensi where user_id = "'.$value->user_id.'" and date_format(created_absensi, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")');
          if ($cekKehadiran) {
            $kehadiran = $cekKehadiran->status_absensi;
          }else{
            $kehadiran = "Tidak Hadir";
          }

          /* cek pengajuan tidak masuk kerja */
          $cekPengajuan = $this->db_select->query('select *from tb_pengajuan a join tb_status_pengajuan b on a.`status_pengajuan` = b.`id_status_pengajuan` where a.status_approval = 1 and user_id = '.$value->user_id.' and date(now()) between date(tanggal_awal_pengajuan) and date(tanggal_akhir_pengajuan)');


          if ($cekPengajuan) {
            $kehadiran = $cekPengajuan->nama_status_pengajuan;
          }

          if ($value->url_file_absensi == null || $value->url_file_absensi == '') {
            $fotoUser = image_url()."/images/member-photos/default_photo.jpg";
          }else{
            $fotoUser = image_url()."/images/absensi/".$value->url_file_absensi;
          }
          
          if (date('Y-m-d', strtotime($value->tanggal_lahir)) == date('Y-m-d')) {
            $is_ultah = true;
          }else{
            $is_ultah = false;
          }

          if (!$cekKehadiran) {
            $waktu_datang = null;
            $waktu_istirahat = null;
            $waktu_kembali = null;
            $waktu_pulang = null;
          }else{
            $waktu_datang = $cekKehadiran->waktu_datang;
            $waktu_istirahat = $cekKehadiran->waktu_istirahat;
            $waktu_kembali = $cekKehadiran->waktu_kembali;
            $waktu_pulang = $cekKehadiran->waktu_pulang;
          }

          $data[$key]['nama_user'] = $value->nama_user;
          $data[$key]['jabatan'] = $jabatan;
          $data[$key]['status_kehadiran'] = $kehadiran;
          $data[$key]['kehadiran'] = $kehadiran;
          $data[$key]['waktu_datang'] = $waktu_datang;
          $data[$key]['waktu_istirahat'] = $waktu_istirahat;
          $data[$key]['waktu_kembali'] = $waktu_kembali;
          $data[$key]['waktu_pulang'] = $waktu_pulang;
          $data[$key]['foto_user'] = $fotoUser;
          $data[$key]['tgl_ultah'] = date('Y-m-d', strtotime($value->tanggal_lahir));
          $data[$key]['is_ultah'] = $is_ultah;
          $data[$key]['manual_absen'] = (bool)$value->manual_absen;
        }
        
        $this->result = array(
          'status' => true,
          'message' => 'Data ditemukan',
          'total' => $totalPegawai->total,
          'data' => $data
        );
        $this->loghistory->createLog($this->result);
        echo json_encode($this->result); exit();
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Data tidak ditemukan',
          'data' => null
          );
      }
		}else{
      $this->result = array(
        'status' => false,
        'message' => 'Data tidak ditemukan',
        'data' => null
      );
    }

    $this->loghistory->createLog($this->result);
    echo json_encode($this->result);
	}

	public function getNotification()
	{
		$require = array('nip', 'page');
    $this->global_lib->input($require);
    if (is_numeric($this->input->post('nip'))) {
      $getUser = $this->db_select->query('select a.user_id, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$this->input->post('nip').'"');
      if ($getUser) {
        $data['notifikasi'] = array();
        $limit = 15;
        $page = $this->input->post('page');
        if (is_numeric($page) && $page > 0) {
          //Select Data Ulang Tahun Pegawai
          $getUltah = $this->db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_channel = "'.$getUser->id_channel.'" and a.is_admin = 0 and date_format(a.tanggal_lahir, "%m-%d") = "'.date('m-d').'" order by a.tanggal_lahir desc limit '.($page - 1) * $limit.','.$limit);
          if ($getUltah) {
            foreach ($getUltah as $value) {
              $notif['keterangan'] = $value->nama_user." Berulang Tahun Hari Ini.";
              $notif['tanggal_notifikasi'] = date('Y-m-d');
              $notif['kategori'] = "ultah";
              array_push($data['notifikasi'], $notif);
            }
            $limit = $limit-count($getUltah);
          }
          
          //Select Data Apel H-1
          $getApel = $this->db_select->query_all('select a.nama_apel, b.nama_lokasi, a.tanggal_apel from tb_apel a join tb_lokasi b on a.id_lokasi = b.id_lokasi where a.id_channel = "'.$getUser->id_channel.'" and date_format(subdate(a.tanggal_apel, 1), "%Y-%m-%d") = date_format(now(), "%Y-%m-%d") order by tanggal_apel desc limit '.($page - 1) * $limit.','.$limit);
          if ($getApel) {
            foreach ($getApel as $value) {
              $tanggal = date('D',strtotime($value->tanggal_apel));
              if ($tanggal == 'Mon') {
                $tanggal_apel = 'Senin';
              }elseif ($tanggal == 'Tue') {
                $tanggal_apel = 'Selasa';
              }elseif ($tanggal == 'Wed') {
                $tanggal_apel = 'Rabu';
              }elseif ($tanggal == 'Thu') {
                $tanggal_apel = 'Kamis';
              }elseif ($tanggal == 'Fri') {
                $tanggal_apel = "Jum'at";
              } elseif ($tanggal == 'Sat') {
                $tanggal_apel = 'Sabtu';
              } elseif ($tanggal == 'Sun') {
                $tanggal_apel = 'Minggu';
              }
              
              $notif['keterangan'] = "Apel ".$value->nama_apel." di ".$value->nama_lokasi." pada hari ".$tanggal_apel;
              $notif['tanggal_notifikasi'] = date('Y-m-d');
              $notif['kategori'] = "apel";
              array_push($data['notifikasi'], $notif);
            }
            $limit = $limit-count($getApel);
          }
          
          // Select Data Notifikasi yg lainnya
          $getNotif = $this->db_select->query_all('select *from tb_notifikasi where user_id = "'.$getUser->user_id.'" order by created_at desc limit '.($page - 1) * $limit.','.$limit);
          if ($getNotif) {
            foreach ($getNotif as $value) {
              if ($value->jenis_notifikasi = "pengumuman") {
                $notif['id'] = $value->id;
                $notif['id_notifikasi'] = $value->id_notifikasi;
                $notif['is_read'] = $value->is_read;
                $notif['keterangan'] = $value->keterangan;
                $notif['tanggal_notifikasi'] = date('Y-m-d', strtotime($value->created_at));
                $notif['kategori'] = "pengumuman";
              }elseif ($value->jenis_notifikasi = "acc_pengajuan") {
                $notif['keterangan'] = "Pengajuan anda telah disetujui.";
                $notif['tanggal_notifikasi'] = date('Y-m-d', strtotime($value->created_at));
                $notif['kategori'] = "pengajuan";
              }elseif ($value->jenis_notifikasi = "pembatalan_absensi") {
                $notif['keterangan'] = "Absensi anda hari ini telah dibatalkan.";
                $notif['tanggal_notifikasi'] = date('Y-m-d', strtotime($value->created_at));
                $notif['kategori'] = "pembatalan";
              }elseif ($value->jenis_notifikasi = "reject_pengajuan") {
                $notif['keterangan'] = "Pengajuan anda tidak disetujui.";
                $notif['tanggal_notifikasi'] = date('Y-m-d', strtotime($value->created_at));
                $notif['kategori'] = "pengajuan";
              }

              array_push($data['notifikasi'], $notif);
              $update['notif_read'] = 1;
              $where['id_notifikasi'] = $value->id_notifikasi;
              $this->db_dml->update('tb_notifikasi', $update, $where);
            }
          }
          $this->result = array(
              'status' => true,
              'message' => 'Data ditemukan',
              'data' => $data
          );
        }else{
          $this->result = array(
              'status' => false,
              'message' => 'Data tidak ditemukan',
              'data' => null
          );
        }
      }else{
        $this->result = array(
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null
        );
      }
    }else{
      $this->result = array(
          'status' => false,
          'message' => 'Data tidak ditemukan',
          'data' => null
      );
    }
    $this->loghistory->createLog($this->result);
    echo json_encode($this->result);
	}

	public function getTotalNotifikasi()
	{
		$require = array('nip');
    $this->global_lib->input($require);
    if (is_numeric($this->input->post('nip'))) {
      $getUser = $this->db_select->select_where('tb_user', 'nip = "'.$this->input->post('nip').'"');
      $getUltah = $this->db_select->query_all('select *from tb_user where is_admin = 0 and date_format(tanggal_lahir, "%m-%d") = "'.date('m-d').'"');
      $getApel = $this->db_select->query_all('select a.nama_apel, b.nama_lokasi, a.tanggal_apel from tb_apel a join tb_lokasi b on a.id_lokasi = b.id_lokasi where date_format(subdate(a.tanggal_apel, 1), "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")');
      $getNotif = $this->db_select->query_all('select *from tb_notifikasi where user_id = "'.$getUser->user_id.'" and is_read = 0 and notif_read = 0');
      if ($getNotif) {
        $data['total'] = count($getUltah)+count($getApel)+count($getNotif);
        if ($data['total'] > 0) {
          $this->result = array(
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $data
          );
        }else{
          $this->result = array(
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null
          );
        }
      }else{
        $data['total'] = 0;
        $this->result = array(
          'status' => true,
          'message' => 'Data ditemukan',
          'data' => $data
        );
      }
    }else{
      $this->result = array(
        'status' => false,
        'message' => 'Data tidak ditemukan',
        'data' => null
      );
    }
    $this->loghistory->createLog($this->result);
    echo json_encode($this->result);
	}

	public function historyPengajuan()
	{
		$require = array('nip','page','kategori');
    $this->global_lib->input($require);
    if (is_numeric($this->input->post('nip'))) {
      $page = $this->input->post('page');
      $limit = 10;

      if (is_numeric($page) && $page > 0) {
        $start = ($page - 1) * $limit;
        /* get user */
        $getUser = $this->db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$this->input->post('nip').'"');

        $kategori = strtolower($this->input->post('kategori'));

        $where = "";
        if ($kategori == 'izin') {
          $where .= "and b.nama_status_pengajuan = 'Izin'";
        } elseif ($kategori == 'cuti') {
          $where .= "and b.nama_status_pengajuan = 'Cuti'";
        } elseif ($kategori == 'sakit') {
          $where .= "and b.nama_status_pengajuan = 'Sakit'";
        } elseif ($kategori == 'lembur') {
          $where .= "and b.nama_status_pengajuan = 'Lembur'";
        } else{
          $where .= "and b.nama_status_pengajuan not in ('Izin','Cuti','Sakit', 'Lembur')";
        }        
        $getData = $this->db_select->query_all('select date(tanggal_awal_pengajuan) tanggal_awal_pengajuan, date(tanggal_akhir_pengajuan) tanggal_akhir_pengajuan, url_file_pengajuan, keterangan_pengajuan, status_approval, nama_status_pengajuan kategori from tb_pengajuan a join tb_status_pengajuan b on a.status_pengajuan = b.id_status_pengajuan where user_id = "'.$getUser->user_id.'" '.$where.' and year(now()) = year(tanggal_awal_pengajuan) order by a.created_at desc limit '.$start.','.$limit);
        $getTotalData = $this->db_select->query('select count(*) total from tb_pengajuan a join tb_status_pengajuan b on a.status_pengajuan = b.id_status_pengajuan where user_id = "'.$getUser->user_id.'" '.$where.' and year(now()) = year(tanggal_awal_pengajuan)');

      foreach ($getData as $value) {
        if ($value->status_approval == 1) {
          $value->status_approval = "Approve";
        }elseif ($value->status_approval == 2) {
          $value->status_approval = "Decline";
        }else{
          $value->status_approval = "Pending";
        }

        if ($value->url_file_pengajuan != null || $value->url_file_pengajuan != "") {
          $value->url_file_pengajuan = image_url().'images/pengajuan_sakit/'.$value->url_file_pengajuan;
        }
      }

      /* get data cuti */
      $getCuti = $this->db_select->select_where('tb_user','nip = "'.$this->input->post('nip').'"');
      if ($getCuti) {
        $banyak_cuti = (int)$getCuti->cuti;
      }else{
        $banyak_cuti = 0;
      }

      /* get total cuti */
      $getTotalCuti = $this->db_select->query_all('select *from tb_pengajuan a join tb_status_pengajuan b on a.status_pengajuan = b.id_status_pengajuan where a.user_id = "'.$getUser->user_id.'" and a.status_pengajuan = 1 and year(a.tanggal_akhir_pengajuan) = year(now())');
      if ($getTotalCuti) {
        $getTotalCuti = count($getTotalCuti);
      }else{
        $getTotalCuti = 0;
      }

      $data['data'] = $getData;
      $data['detail'] = array(
        'kuota_cuti' => $banyak_cuti,
        'pemakaian' => $getTotalCuti
      );
      $this->result = array(
        'status' => true,
        'message' => 'Data ditemukan',
        'total' => $getTotalData->total,
        'data' => $data
      );
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Data tidak ditemukan',
          'data' => null
        );
      }

      $this->loghistory->createLog($this->result);
      echo json_encode($this->result);
    }
	}

	public function jadwalKerja()
	{
		$require = array('nip','tanggal');
        $this->global_lib->input($require);

        $nip = $this->input->post('nip');
        $tanggal = $this->input->post('tanggal');

        $getUser = $this->db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$this->input->post('nip').'"');

        if ($getUser) {
        	/* pengecekan pola user */
        	$tglNow = date('Y-m-d', strtotime($tanggal));
            $cekPolaUser = $this->db_select->query('select *from tb_pola_user where user_id = "'.$getUser->user_id.'" and "'.$tglNow.'" between start_pola_kerja and end_pola_kerja');

            if ($cekPolaUser) {
                $getPola = $this->db_select->select_where('tb_pola_kerja','id_pola_kerja = "'.$cekPolaUser->id_pola_kerja.'"');
                $file = '../appconfig/new/'.$getPola->file_pola_kerja;
            }else{
                $where['id_channel'] = $getUser->id_channel;
                $where['is_default'] = 1;
                $getPola = $this->db_select->select_where('tb_pola_kerja', $where);
                $file = '../appconfig/new/'.$getPola->file_pola_kerja;
            }

            /* get nama hari */
            $day = strtolower(date("l", strtotime($tanggal)));
            $jadwal = json_decode(file_get_contents($file))->jam_kerja;
            $jadwal = $jadwal->$day;

            if ($jadwal) {
            	$this->result = array(
		            'status' => true,
		            'message' => 'Data ditemukan',
		            'data' => $jadwal
		        );
            }else{
            	$this->result = array(
		            'status' => false,
		            'message' => 'Data tidak ditemukan',
		            'data' => null
		        );
            }
        }else{
			$this->result = array(
	            'status' => false,
	            'message' => 'Data tidak ditemukan',
	            'data' => null
	        );
        }

        $this->loghistory->createLog($this->result);
		echo json_encode($this->result);
	}

	public function getJadwal()
	{
		$require = array('bulan');
      $this->global_lib->input($require);

        /* set param */
        $bulan = date("Y-m-d", strtotime($this->input->post('bulan')));
        $nip = $this->input->post('nip');

        /* get data user menggunakan NIP yang di inputkan */
        $getUser = $this->global_lib->getDataUser($nip);

        if ($getUser) {
        	/* set tanggal akhir dari bulan yang di inputkan */
        	$tglAkhir = date('t', strtotime($bulan));
        	$data = array();
        	for ($i=0; $i < $tglAkhir; $i++) { 
        		/* set tanggal untuk get data dari database */
				$tgl = date("Y-m", strtotime($bulan)) . "-" . ($i+1);
				$tgl = date("Y-m-d", strtotime($tgl));
				$result[] = $tgl;
				array_push($data, $result);
        	}
        	echo json_encode($data); exit();

        	/* get data dari table pola user */
        	$getPolaUser = $this->db_select->select_all_where('tb_pola_user', 'user_id = ' . $getUser->user_id);
        	if ($getPolaUser) {
        		/* ambil data jadwal dari pola user terlebih dahulu */

        	}else{
        		/* ambil data jadwal dari default jadwal kerja */

        	}
        } else {
        	/* set respon jika data user tidak ditemukan */
        	$this->result = array(
	            'status' => false,
	            'message' => 'Data user tidak ditemukan',
	            'data' => null
	        );
        }

        /* respon API */
        $this->loghistory->createLog($this->result);
		echo json_encode($this->result);
	}

	public function historyLembur()
	{
		$require = array('page');
    $this->global_lib->input($require);

    $nip = $this->input->post('nip');
    $page = $this->input->post('page');
    if (is_numeric($page)) {
      $limit = 10;
      $start = ($page - 1) * $limit;

      $getUser = $this->global_lib->getDataUser($nip);

      if ($getUser) {
        $getData = $this->db_select->query_all('select a.tanggal_lembur, a.lama_lembur, a.keterangan, a.status, a.userfile from tb_lembur a join tb_user b on a.user_id = b.user_id where b.user_id = "'.$getUser->user_id.'" order by id_lembur desc limit '.$start.','.$limit);
        $getTotalData = $this->db_select->query('select count(*) total from tb_lembur a join tb_user b on a.user_id = b.user_id where b.user_id = "'.$getUser->user_id.'" order by id_lembur');

        foreach ($getData as $value) {
          if ($value->status == 1) {
            $value->status = "Approve";
          } elseif ($value->status == 2) {
            $value->status = "Decline";
          } else {
            $value->status = "Pending";
          }

          if ($value->userfile) {
            $value->url_file_pengajuan = image_url().'images/lembur/'.$value->userfile;
          }
        }

        $this->result = array(
          'status' => true,
          'message' => 'Data ditemukan',
          'total' => $getTotalData->total,
          'data' => $getData
        );
      } else {
        $this->result = array(
          'status' => false,
          'message' => 'Data user tidak ditemukan',
          'data' => null
        );
      }
    }else{
      $this->result = array(
        'status' => false,
        'message' => 'Data tidak ditemukan',
        'data' => null
      );
    }

    $this->loghistory->createLog($this->result);
		echo json_encode($this->result);
	}

  public function getJumlahJam()
  {
    $getData = $this->db_select->query("select ta.total_jam from tb_user tu
    left join (select user_id, count(user_id) total, SUM(TIMESTAMPDIFF(SECOND, waktu_datang, COALESCE(waktu_pulang, waktu_datang))) total_jam from tb_absensi where status_absensi != 'Tidak Hadir' and date_format(created_absensi, '%Y-%m') = '".mdate("%Y-%m", time())."' group by user_id) as ta on tu.user_id = ta.user_id where tu.nip = '".$this->input->post('nip')."' order by ta.total desc");

    $init = $getData->total_jam ? $getData->total_jam : 0;
    $hours = floor($init / 3600);
    $minutes = floor(($init / 60) % 60);
    $seconds = $init % 60;
    $data['status'] = true;
    $data['data'] = "$hours:$minutes:$seconds";
    $data['message'] = 'Succeess';

    echo json_encode($data);
  }
}