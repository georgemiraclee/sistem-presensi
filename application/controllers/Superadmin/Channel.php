<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class channel extends CI_Controller
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
    if ($sess['akses'] != "superadmin") {
      redirect(base_url());exit();
    }
		$data['data_channel'] = $this->Db_select->select_all_where('tb_channel', ['is_deleted' => 0]);
    
		$this->load->view('Superadmin/header', $data);
		$this->load->view('Superadmin/data_channel');
		$this->load->view('Superadmin/footer');
	}

	public function add(){
		$this->load->view('Superadmin/header');
		$this->load->view('Superadmin/tambah_channel');
		$this->load->view('Superadmin/footer');
	}

	public function insert()
	{
    /* check code channel */
    $checkCode = $this->Db_select->select_where('tb_channel', ['code_channel' => $this->input->post('code_company')]);

    if (!$checkCode) {
      $data['nama_channel'] = $this->input->post('nama_channel');
      $data['alamat_channel'] = $this->input->post('alamat_channel');
      $data['email_channel'] = $this->input->post('email_channel');
      $data['telp_channel'] = $this->input->post('telp_channel');
      $data['deskripsi_channel'] = $this->input->post('deskripsi_channel');
      $data['limit_user'] = $this->input->post('limit_user');
      $data['code_channel'] = $this->input->post('code_company');
      $data['package'] = $this->input->post('package');
      if($_FILES['userfile']['name']){
        $this->load->library('upload');
        $nmfile = "channel_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
        $config['upload_path'] = './assets/images/channel'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['max_size'] = '2048'; //maksimum besar file 2M
        $config['file_name'] = $nmfile; //nama yang terupload nantinya
        $this->upload->initialize($config);
        if ($this->upload->do_upload('userfile')){
          $gbr = $this->upload->data();
          $name3 = $gbr['file_name'];
          $data['logo_channel'] = $name3;
        }
      }
  
      $insertData = $this->Db_dml->insert('tb_channel', $data);
      if ($insertData) {  
        $struktur = array(
          'id_channel' => $insertData,
          'struktur_data' => '[{"id":"1","name":"Pimpinan","parent":"0","used":"1"},{"id":"2","name":"Staff","parent":"1","used":"1"}]'
        );
  
        $this->Db_dml->normal_insert('tb_struktur_organisasi', $struktur);
  
        $this->create($insertData);
        $this->addDefault($insertData);
        $this->addStatusPengajuan($insertData);
        $this->addDefaultLembur($insertData);
        $this->addStatusPegawai($insertData);
  
        $input['jumlah_cuti_tahunan'] = 14;
        $input['jatah_cuti_bulanan'] = 3;
        $input['batasan_cuti'] = 0;
        $input['id_channel'] = $insertData;
  
        $this->Db_dml->insert('tb_pengaturan_cuti', $input);
  
        $result['status'] = true;
        $result['message'] = 'Data berhasil disimpan.';
        $result['data'] = array();
      }else{
        $result['status'] = false;
        $result['message'] = 'Data gagal disimpan.';
        $result['data'] = array();
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Kode perusahaan tidak dapat digunakan.';
      $result['data'] = array();
    }
    

		echo json_encode($result);exit();
	}

	public function addDefaultLembur($id_channel)
	{
		$insert['id_channel'] = $id_channel;
		$insert['is_custom'] = 0;

		$this->Db_dml->insert('tb_komponen_lembur', $insert);
	}

  public function addStatusPegawai($id_channel)
  {
    $namaStatusPegawai = array('Tetap','Kontrak');
    for ($i=0; $i < count($namaStatusPegawai) ; $i++) { 
			$input['nama_status_user'] = $namaStatusPegawai[$i];
			$input['id_channel'] = $id_channel;
			$input['is_default'] = 1;
			$this->Db_dml->insert('tb_status_user', $input);
		}
  }

	public function addStatusPengajuan($id_channel)
	{
		$nama_status_pengajuan = array('Cuti','Izin','Sakit','Lembur');
		for ($i=0; $i < count($nama_status_pengajuan) ; $i++) { 
			$input['nama_status_pengajuan'] = $nama_status_pengajuan[$i];
			$input['id_channel'] = $id_channel;
			$input['is_default'] = 1;
			$this->Db_dml->insert('tb_status_pengajuan', $input);
		}
	}

	public function addDefault($id_channel)
	{
    $namaFile = 'jadwal_default_'.$id_channel.time().'.txt';
		$file = 'appconfig/new/'.$namaFile;
		$insertDB['nama_pola_kerja'] = 'Pola Kerja Default';
		$insertDB['toleransi_keterlambatan'] = 0;
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
		$this->Db_dml->insert('tb_pola_kerja', $insertDB);
	}

	public function update_status(){
		$where['id_channel'] = $this->input->post('id_channel');
		$update['status'] = $this->input->post('status');
		$updateData = $this->Db_dml->update('tb_channel', $update, $where);

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

	public function delete()
	{
		$where['id_channel'] = $this->input->post('id_channel');
		$checkData = $this->Db_select->select_where('tb_channel', $where);
    
    if ($checkData) {
      $data['is_deleted'] = 1;
      $delete = $this->Db_dml->update('tb_channel', $data, $where);

      if ($delete) {
        $result['status'] = true;
        $result['message'] = 'Data berhasil disimpan.';
        $result['data'] = array();
      }else{
        $result['status'] = false;
        $result['message'] = 'Data gagal disimpan.';
        $result['data'] = array();
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data tidak ditemukan.';
      $result['data'] = array();
    }
    


		echo json_encode($result);
	}
  
	public function edit($id)
	{
		$data['data_channel'] = $this->Db_select->select_where('tb_channel','id_channel = "'.$id.'"');

		$this->load->view('Superadmin/header', $data);
		$this->load->view('Superadmin/edit_channel');
		$this->load->view('Superadmin/footer');
	}

	public function update()
	{
    $where['id_channel'] = $this->input->post('id_channel');
    $checkData = $this->Db_select->select_where('tb_channel', $where);

    if ($checkData) {
      if ($checkData->code_channel != $this->input->post('code_company')) {
        $checkCode = $this->Db_select->select_where('tb_channel', ['code_channel' => $this->input->post('code_company')]);

        if ($checkCode) {
          $result['status'] = false;
          $result['message'] = 'Kode perusahaan tidak dapat digunakan.';
          $result['data'] = array();

          echo json_encode($result); exit();
        }
      }
      $data['nama_channel'] = $this->input->post('nama_channel');
      $data['alamat_channel'] = $this->input->post('alamat_channel');
      $data['email_channel'] = $this->input->post('email_channel');
      $data['telp_channel'] = $this->input->post('telp_channel');
      $data['deskripsi_channel'] = $this->input->post('deskripsi_channel');
      $data['limit_user'] = $this->input->post('limit_user');
      $data['code_channel'] = $this->input->post('code_company');
      $data['package'] = $this->input->post('package');
      if($_FILES['userfile']['name']){
        $this->load->library('upload');
        $nmfile = "channel_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
        $config['upload_path'] = './assets/images/channel'; //path folder
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
        $config['max_size'] = '2048'; //maksimum besar file 2M
        $config['file_name'] = $nmfile; //nama yang terupload nantinya
  
        $this->upload->initialize($config);
        if ($this->upload->do_upload('userfile')){
          $gbr = $this->upload->data();
          $name3 = $gbr['file_name'];
          $data['logo_channel'] = $name3;
        }
      }
  
      $update = $this->Db_dml->update('tb_channel', $data, $where);
      if ($update == 1) {
        $result['status'] = true;
        $result['message'] = 'Data berhasil disimpan.';
        $result['data'] = array();
      }else{
        $result['status'] = false;
        $result['message'] = 'Data gagal disimpan.';
        $result['data'] = array();
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data channel tidak ditemukan.';
      $result['data'] = array();
    }

		echo json_encode($result);exit();
	}

	public function admin_channel()
	{
		$sess = $this->session->userdata('user');
    if ($sess['akses'] != "superadmin") {
      redirect(base_url());exit();
    }
		$data['data_admin'] = $this->Db_select->query_all('select *from tb_user a left join tb_channel b on a.admin_channel = b.id_channel where is_admin = 1');

    $this->load->view('Superadmin/header', $data);
		$this->load->view('Superadmin/data_admin');
		$this->load->view('Superadmin/footer');
	}

	public function addAdmin()
  {
		$data['data_channel'] = $this->Db_select->select_all_where('tb_channel', 'status = 1');
		$this->load->view('Superadmin/header', $data);
		$this->load->view('Superadmin/tambah_admin');
		$this->load->view('Superadmin/footer');
	}

	public function insertAdmin()
	{
		$data['nama_user'] = $this->input->post('nama_user');
		$data['email_user'] = $this->input->post('email_user');
		$data['password_user'] = md5($this->input->post('password_user'));
		$data['admin_channel'] = $this->input->post('admin_channel');
		$data['is_aktif'] = $this->input->post('is_aktif');
		$data['nip'] = 1;
		$data['is_admin'] = 1;
		$cekEmail = $this->Db_select->select_where('tb_user', 'email_user = "'.$data['email_user'].'"');
		if ($cekEmail) {
			$result['status'] = false;
      $result['message'] = 'Username sudah digunakan.';
      $result['data'] = array();

      echo json_encode($result); exit();
		}

		$insertData = $this->Db_dml->normal_insert('tb_user', $data);
		if ($insertData == 1) {
			$result['status'] = true;
      $result['message'] = 'Data berhasil disimpan.';
      $result['data'] = array();
		}else{
			$result['status'] = false;
      $result['message'] = 'Data gagal disimpan.';
      $result['data'] = array();
		}

		echo json_encode($result);exit();
	}

	public function update_statusAdmin(){
		$where['user_id'] = $this->input->post('user_id');
		$update['is_aktif'] = $this->input->post('is_aktif');

		$updateData = $this->Db_dml->update('tb_user', $update, $where);
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

	public function deleteAdmin()
	{
		$where['user_id'] = $this->input->post('user_id');
		$delete = $this->Db_dml->delete('tb_user', $where);
    
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

	public function editAdmin($id)
	{
		$data['data_user'] = $this->Db_select->select_where('tb_user','user_id = "'.$id.'"');
		$data_channel = $this->Db_select->select_all_where('tb_channel', 'status = 1');
		$data['list_channel'] = "";
		foreach ($data_channel as $key => $value) {
			$data['list_channel'] .= "<option value='".$value->id_channel."' ".$this->selected($value->id_channel, $data['data_user']->admin_channel).">".$value->nama_channel."</option>";
		}
		$data['list_status'] = "
			<option value='1' ".$this->selected(1, $data['data_user']->is_aktif).">Aktif</option>
			<option value='0' ".$this->selected(0, $data['data_user']->is_aktif).">Tidak Aktif</option>
		";
		$this->load->view('Superadmin/header', $data);
		$this->load->view('Superadmin/edit_admin');
		$this->load->view('Superadmin/footer');
	}

	public function selected($value, $nama) {
    if ($value == $nama) {
      return " selected";
    } else {
      return "";
    }
  }

  public function updateAdmin()
	{
		$where['user_id'] = $this->input->post('user_id');
		$getUser = $this->Db_select->select_where('tb_user', $where);
		$data['nama_user'] = $this->input->post('nama_user');
		$data['email_user'] = $this->input->post('email_user');
		$data['admin_channel'] = $this->input->post('admin_channel');
		$data['is_aktif'] = $this->input->post('is_aktif');
		if ($this->input->post('password_user')) {
			$data['password_user'] = md5($this->input->post('password_user'));
		}

		if ($data['email_user'] != $getUser->email_user) {
			$cekEmail = $this->Db_select->select_where('tb_user', 'email_user = "'.$data['email_user'].'"');
			if ($cekEmail) {
				$result['status'] = false;
        $result['message'] = 'Username sudah digunakan.';
        $result['data'] = array();

        echo json_encode($result); exit();
			}
		}

		$update = $this->Db_dml->update('tb_user', $data, $where);
		if ($update == 1) {
			$result['status'] = true;
      $result['message'] = 'Data berhasil disimpan.';
      $result['data'] = array();
		}else{
			$result['status'] = false;
      $result['message'] = 'Data gagal disimpan.';
      $result['data'] = array();
		}

		echo json_encode($result);exit();
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
}