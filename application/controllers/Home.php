<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class home extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
    $this->load->helper(array('Cookie', 'String'));
    $this->encryption->initialize(
      array(
        'cipher' => 'aes-256',
        'mode' => 'ctr',
        'key' => 'super-secret-key'
      )
    );
	}
	public function index()
	{
		if ($this->session->userdata('user')['akses'] == 'admin_channel') {
			/* cek admin baru atau bukan */
			redirect('Administrator/dashboard');
		} else if ($this->session->userdata('user')['akses'] == 'admin_utama') {
			redirect('Administrator/dashboard');
		} else if ($this->session->userdata('user')['akses'] == 'admin_unit') {
			redirect('Dashboard_admin');
		} else if ($this->session->userdata('user')['akses'] == 'sekda') {
			$id_usr= $this->session->userdata('user')['id_user'];
			$cek = $this->Db_select->query('select *from tb_user where id_parent ='.$id_usr);
			redirect('Leader/dashboard');
		} else if ($this->session->userdata('user')['akses'] == 'staff') {
      $id_usr= $this->session->userdata('user')['id_user'];
      $user = $this->Db_select->select_where('tb_user', ['user_id' => $id_usr]);
      if ($user->role) {
        redirect('Administrator/dashboard');
      } else {
        $cek = $this->Db_select->query('select *from tb_user where id_parent ='.$id_usr);
        if ($cek) {
          redirect('Leader/dashboard');
        }else{
          redirect('Staff/dashboard');
        }
      }
		} else if ($this->session->userdata('user')['akses'] == 'superadmin') {
			redirect('Superadmin/dashboard');
		} else {
			$this->new_notlogin_page();
		}
	}
	public function new_notlogin_page()
	{
    $cookie = get_cookie('pressensiapps');
    $username = null;
    $password = null;
    $remember = null;
    if ($cookie <> '') {
      $dec = json_decode($this->encryption->decrypt($cookie));
      if ($dec) {
        $username = $dec->username;
        $password = $dec->password;
        $remember = "on";
      }
    }
    $data['username'] = $username;
    $data['password'] = $password;
    $data['remember_me'] = $remember;
		$this->load->view('Administrator/login', $data);
	}
	public function notlogin_page()
	{
		$this->load->view('login');
	}
	public function superadmin()
	{
		if ($this->session->userdata('user')['akses'] == 'superadmin') {
			redirect('Data_channel');
		}elseif ($this->session->userdata('user')['akses'] == 'administrator') {
			redirect('Dashboard_admin');
		}else{
			$this->notlogin_page();
		}
	}
	public function run_import(){
		if (isset($_FILES['database'])) {
			$file   = explode('.',$_FILES['database']['name']);
		    $length = count($file);
		    if($file[$length -1] == 'xlsx' || $file[$length -1] == 'xls'){
		        $tmp    = $_FILES['database']['tmp_name'];//Baca dari tmp folder jadi file ga perlu jadi sampah di server :-p
		        $this->load->library('PHPExcel');//Load library excelnya
		        $read   = PHPExcel_IOFactory::createReaderForFile($tmp);
		        $read->setReadDataOnly(true);
		        $excel  = $read->load($tmp);
		        $sheets = $read->listWorksheetNames($tmp);//baca semua sheet yang ada
		        foreach($sheets as $sheet){
	                $_sheet = $excel->setActiveSheetIndexByName($sheet);//Kunci sheetnye biar kagak lepas :-p
	                $maxRow = $_sheet->getHighestRow();
	                $maxCol = $_sheet->getHighestColumn();
	                $field  = array();
	                $sql    = array();
	                $maxCol = range('A',$maxCol);
	                foreach($maxCol as $key => $coloumn){
	                    $field[$key]    = $_sheet->getCell($coloumn.'1')->getCalculatedValue();//Kolom pertama sebagai field list pada table
	                }
	                for($i = 2; $i <= $maxRow; $i++){
	                    foreach($maxCol as $k => $coloumn){
	                        $sql[$field[$k]]  = $_sheet->getCell($coloumn.$i)->getCalculatedValue();
	                    }
	                    $data = array();
	                    if ($sql['NIP'] != null || $sql['NIP'] != "") {
		                    $nipNew = str_replace(' ', '', $sql['NIP']);
							$data['nip'] = preg_replace('/[^A-Za-z0-9\-]/', '', $nipNew);
	                    }
	                    if ($data['nip']) {
	                    	$whereNip['nip'] = $data['nip'];
	                    	$selectNip = $this->Db_select->select_where('tb_user', $whereNip);
	                    	if (!$selectNip) {
	                    		$data['nama_user'] = "";

	                    		if ($sql['GELAR DEPAN'] != null || $sql['GELAR DEPAN'] != "") {
									$data['nama_user'] .= $sql['GELAR DEPAN'];
			                    }
			                    if ($sql['NAMA'] != null || $sql['NAMA'] != "") {
									$data['nama_user'] .= $sql['NAMA'];
			                    }
			                    if ($sql['GELAR BELAKANG'] != null || $sql['GELAR BELAKANG'] != "") {
									$data['nama_user'] .= $sql['GELAR BELAKANG'];
			                    }
			                    if ($sql['JK'] != null || $sql['JK'] != "") {
									$data['jenis_kelamin'] = $sql['JK'];
			                    }
			                    if ($sql['TEMPAT LAHIR'] != null || $sql['TEMPAT LAHIR'] != "") {
									$data['tempat_lahir'] = $sql['TEMPAT LAHIR'];
			                    }
			                    if ($sql['TGL LAHIR'] != null || $sql['TGL LAHIR'] != "") {
									$data['tanggal_lahir'] = $sql['TGL LAHIR'];
			                    }
			                    if ($sql['STATUS MENIKAH'] != null || $sql['STATUS MENIKAH'] != "") {
									$data['status_pernikahan'] = $sql['STATUS MENIKAH'];
			                    }
			                    if ($sql['JABATAN'] != null || $sql['JABATAN'] != "") {
			                    	$cek_jabatan = $this->Db_select->query('select id_jabatan from tb_jabatan where nama_jabatan regexp "'.$sql['JABATAN'].'*" ');
			                    	if (count($cek_jabatan) > 0) {
			                    		// JIKA JABATAN SUDAH ADA? AMBIL ID JABATAN YANG ADA
										$data['jabatan'] = $cek_jabatan->id_jabatan;
			                    	}else{
			                    		// JIKA BELUM TERDAFTAR JABATAN, INSERT DAN AMBIL ID JABATANNYA
			                    		$data_insert = array(
			                    			'nama_jabatan' 	=> $sql['JABATAN'],
			                    			'is_aktif'		=> 0
			                    		);
			                    		$insert_jabatan = $this->Db_dml->insert('tb_jabatan', $data_insert);
			                    		if ($insert_jabatan > 0) {
			                    			$data['jabatan'] = $insert_jabatan;
			                    		}
			                    	}
			                    }
			                    if ($sql['OPD'] != null || $sql['OPD'] != "") {
			                    	$cek_opd = $this->Db_select->query('select id_unit from tb_unit where nama_unit regexp "'.$sql['OPD'].'*"');
			                    	if (count($cek_opd) > 0) {
			                    		// JIKA OPD SUDAH ADA? AMBIL ID OPD YANG ADA
										$data['id_unit'] = $cek_opd->id_unit;
			                    	}else{
			                    		// JIKA BELUM TERDAFTAR OPD, INSERT DAN AMBIL ID OPD NYA
			                    		$data_insert_unit = array(
			                    			'nama_unit' 	=> $sql['OPD'],
			                    			'is_aktif'		=> 0
			                    		);
			                    		$insert_opd = $this->Db_dml->insert('tb_unit', $data_insert_unit);
			                    		if ($insert_opd > 0) {
			                    			$data['jabatan'] = $insert_opd;
			                    		}
			                    	}
			                    }
			                    $data['is_aktif'] = 0;
			                    if ($data) {
			                    	$this->Db_dml->normal_insert('tb_user', $data);
			                    }
	                    	}
	                    }
	                }
		        }
		    }else{
		    	$result['status'] = false;
	            $result['message'] = 'Do Not Allowed to Upload.';
	            $result['data'] = array();
				echo json_encode($result); exit();
		    }
		    $result['status'] = true;
            $result['message'] = 'Data Berhasil disimpan.';
            $result['data'] = array();
			echo json_encode($result); exit();
		}else{
			$result['status'] = false;
            $result['message'] = 'Harap masukkan file.';
            $result['data'] = array();
			echo json_encode($result); exit();
		}
	}

	public function setCuti()
	{
		$getAll = $this->Db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where b.id_unit = 1');

		foreach ($getAll as $key => $value) {
			/* get status pengajuan */
			$whereP['id_channel'] = $value->id_channel;
			$whereP['nama_status_pengajuan'] = "Cuti";
			$statusPengajuan = $this->Db_select->select_where('tb_status_pengajuan', $whereP);

			/* set data */
			$tb_pengajuan['user_id'] = $value->user_id;
			$tb_pengajuan['status_pengajuan'] = $statusPengajuan->id_status_pengajuan;
			$tb_pengajuan['tanggal_awal_pengajuan'] = date('Y-m-d', strtotime("2018-12-24"))." 00:00:00";
			$tb_pengajuan['tanggal_akhir_pengajuan'] = date('Y-m-d', strtotime("2018-12-24"))." 00:00:00";
			$tb_pengajuan['keterangan_pengajuan'] = "Cuti bersama hari natal";
			$tb_pengajuan['status_approval'] = 1;

			$this->Db_dml->insert('tb_pengajuan', $tb_pengajuan);

			$dataU['cuti'] = $value->cuti+1;
			$whereU['user_id'] = $value->user_id;

			$this->Db_dml->update('tb_user', $dataU, $whereU);
		}
	}
}