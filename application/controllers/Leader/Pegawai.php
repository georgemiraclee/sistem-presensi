<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
/**
* 
*/
class pegawai extends CI_Controller
{
	private $file = 'appconfig/auto_respon.txt';
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('ceksession');
		$this->ceksession->login();
	}
 
	public function index()
	{
		$sess = $this->session->userdata('user');
    if (!$sess['akses']) {
      redirect(base_url());exit();
    }
		$this->load->view('SEKDA/header');
		$this->load->view('SEKDA/pegawai');
		$this->load->view('SEKDA/footer');
	}

	public function add()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
		$data['data_staff'] = $this->Db_select->query_all('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit');
		$data['skpd'] = $this->Db_select->select_all_where('tb_unit', 'is_aktif = 1');
		$data['jabatan'] = $this->Db_select->select_all_where('tb_jabatan', 'is_aktif = 1');
		$data['status_staff'] = $this->Db_select->select_all_where('tb_status_user', 'is_aktif = 1 and id_channel = "'.$id_channel.'"');
		$struktur_organisasi = $this->Db_select->select_where('tb_struktur_organisasi','id_channel = "'.$id_channel.'"');
		$data['posisi'] = json_decode($struktur_organisasi->struktur_data);
		foreach ($data['data_staff'] as $key => $value) {
			$selectUser = $this->Db_select->select_where('tb_absensi','user_id = '.$value->user_id.'');
			if ($selectUser) {
				$value->delete = false;
			}else{
				$value->delete = true;
			}
		}
		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/tambah_pegawai');
		$this->load->view('SEKDA/footer');
	}
	public function insert(){
		$sess = $this->session->userdata('user');
		$cekEmail = $this->Db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_aktif = 1 and b.id_channel = "'.$sess['id_channel'].'" and a.email_user = "'.$this->input->post('email_user').'"');
		if (!$cekEmail) {
			$cekNip = $this->Db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_aktif = 1 and b.id_channel = "'.$sess['id_channel'].'" and a.nip = "'.$this->input->post('nip').'"');
			if (!$cekNip) {
				$cekTelp = $this->Db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_aktif = 1 and b.id_channel = "'.$sess['id_channel'].'" and a.telp_user = "'.$this->input->post('telp_user').'"');
				if ($cekTelp) {
					$result['status'] = false;
		            $result['message'] = 'Nomor telepon telah digunakan.';
		            $result['data'] = array();
					echo json_encode($result); exit();
				}
			}else{
				$result['status'] = false;
	            $result['message'] = 'NIP telah digunakan.';
	            $result['data'] = array();
				echo json_encode($result); exit();
			}
		}else{
			$result['status'] = false;
            $result['message'] = 'E-email telah digunakan.';
            $result['data'] = array();
			echo json_encode($result); exit();
		}
		$data['nama_user'] = $this->input->post('nama_user');
		$data['nip'] = $this->input->post('nip');
		$data['jenis_kelamin'] = $this->input->post('jenis_kelamin');
		$data['tempat_lahir'] = $this->input->post('tempat_lahir');
		$data['agama'] = $this->input->post('agama');
		$data['status_pernikahan'] = $this->input->post('status_pernikahan');
		$data['telp_user'] = $this->input->post('telp_user');
		$data['email_user'] = $this->input->post('email_user');
		$data['alamat_user'] = $this->input->post('alamat_user');
		$data['id_unit'] = $this->input->post('id_unit');
		$data['jabatan'] = $this->input->post('jabatan');
		$data['status_user'] = $this->input->post('status_user');
		$data['tanggal_lahir'] = date("Y-m-d", strtotime($this->input->post('tanggal_lahir')));
		$data['password_user'] = md5($this->input->post('password'));
		$data['eselon'] = $this->input->post('eselon');
		$data['id_struktur'] = $this->input->post('id_struktur');
		$data['id_parent'] = $this->input->post('id_parent');
		$data['nama_bidang'] = $this->input->post('nama_bidang');
		$data['saldo'] = $this->input->post('saldo');
		$data['is_aktif'] = $this->input->post('is_aktif');
		$data['gaji_pokok'] = $this->input->post('gaji_pokok');
		$data['tanggal_kerja'] = date("Y-m-d", strtotime($this->input->post('tanggal_kerja')));
       
        if($_FILES['userfile']['name']){
	        $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
	        $config['upload_path'] = './assets/images/member-photos'; //path folder
	        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
	        $config['max_size'] = '2048'; //maksimum besar file 2M
	        $config['file_name'] = $nmfile; //nama yang terupload nantinya
	        $time = $this->input->post('nip').'_'.strtotime('now');
	        
	        $this->load->library('upload', $config);
	        $this->upload->initialize($config);
        	if ($this->upload->do_upload('userfile')){
        		$gbr = $this->upload->data();
				$name3 = $gbr['file_name'];		
				$data['foto_user'] = $name3;		
			}else{
				$result['status'] = false;
	            $result['message'] = $this->upload->display_errors();
	            $result['data'] = array();
				echo json_encode($result); exit();
			}
       	}
        $insert = $this->Db_dml->normal_insert('tb_user', $data);
        if ($insert == 1) {
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
	public function update()
	{
		$sess = $this->session->userdata('user');
		$getUser = $this->Db_select->select_where('tb_user','user_id = '.$this->input->post('user_id'));
		$email = $this->input->post('email_user');
		$nip = $this->input->post('nip');
		$telp_user = $this->input->post('telp_user');
		if ($email != $getUser->email_user) {
			$whereEmail['email_user'] = $this->input->post('email_user');
			$cekEmail = $this->Db_select->select_where('tb_user',$whereEmail);
			if ($cekEmail) {
				$result['status'] = false;
	            $result['message'] = 'E-email telah digunakan.';
	            $result['data'] = array();
				echo json_encode($result); exit();
			}
		}
		if ($nip != $getUser->nip) {
			$whereNip['nip'] = $this->input->post('nip');
			$cekNip = $this->Db_select->select_where('tb_user',$whereNip);
			if ($cekNip) {
				$result['status'] = false;
	            $result['message'] = 'NIP telah digunakan.';
	            $result['data'] = array();
				echo json_encode($result); exit();
			}
		}
		if ($telp_user != $getUser->telp_user) {
			$whereTlp['telp_user'] = $this->input->post('telp_user');
			$cekTelp = $this->Db_select->select_where('tb_user',$whereTlp);
			if ($cekTelp) {
				$result['status'] = false;
	            $result['message'] = 'Nomor telepon telah digunakan.';
	            $result['data'] = array();
				echo json_encode($result); exit();
			}
		}
		$where['user_id'] = $this->input->post('user_id');
		$data['nama_user'] = $this->input->post('nama_user');
		$data['nip'] = $this->input->post('nip');
		$data['jenis_kelamin'] = $this->input->post('jenis_kelamin');
		$data['tempat_lahir'] = $this->input->post('tempat_lahir');
		$data['agama'] = $this->input->post('agama');
		$data['status_pernikahan'] = $this->input->post('status_pernikahan');
		$data['telp_user'] = $this->input->post('telp_user');
		$data['email_user'] = $this->input->post('email_user');
		$data['alamat_user'] = $this->input->post('alamat_user');
		$data['id_unit'] = $this->input->post('id_unit');
		$data['jabatan'] = $this->input->post('jabatan');
		$data['status_user'] = $this->input->post('status_user');
		$data['tanggal_lahir'] = date("Y-m-d", strtotime($this->input->post('tanggal_lahir')));
		$data['eselon'] = $this->input->post('eselon');
		$data['id_struktur'] = $this->input->post('id_struktur');
		$data['id_parent'] = $this->input->post('id_parent');
		$data['nama_bidang'] = $this->input->post('nama_bidang');
		$data['saldo'] = $this->input->post('saldo');
		$data['is_aktif'] = $this->input->post('is_aktif');
		$data['tanggal_kerja'] = date("Y-m-d", strtotime($this->input->post('tanggal_kerja')));
		if ($this->input->post('password')) {
			$data['password_user'] = md5($this->input->post('password'));
		}
        if($_FILES['userfile']['name']){
			$nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
	        $config['upload_path'] = './assets/images/member-photos'; //path folder
	        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
	        $config['max_size'] = '2048'; //maksimum besar file 2M
	        $config['file_name'] = $nmfile; //nama yang terupload nantinya
	        $time = $this->input->post('nip').'_'.strtotime('now');
	        $this->load->library('upload', $config);
	        $this->upload->initialize($config);
       		if ($this->upload->do_upload('userfile')){
			 	$gbr = $this->upload->data();
			   	$name3 = $gbr['file_name'];
	   	 		$data['foto_user'] = $name3;
			}else{
				$result['status'] = false;
	            $result['message'] = $this->upload->display_errors();
	            $result['data'] = array();
				echo json_encode($result); exit();
			}
		}
        $update = $this->Db_dml->update('tb_user', $data, $where);
        if ($update) {
        	$result['status'] = true;
            $result['message'] = 'Data berhasil disimpan.';
            $result['data'] = array();
        }else{
        	$result['status'] = false;
            $result['message'] = 'Tidak ada perubahan data yang terjadi.';
            $result['data'] = array();
        }
		echo json_encode($result);
	}
	public function delete()
	{
		$where['nip'] = $this->input->post('nip');
		$delete = $this->Db_dml->delete('tb_user', $where);
		if ($delete == 1) {
			$result['status'] = true;
            $pesan = "<div class='alert alert-success alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	Data telah di hapus.</div>";
		        $this->session->set_flashdata('pesan', $pesan);
		        redirect(base_url()."Leader/pegawai");exit();
		}else{
			$result['status'] = false;
           $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>\Data gagal di hapus.</div>";
		        $this->session->set_flashdata('pesan', $pesan);
		        redirect(base_url()."Leader/pegawai");exit();
		}
		echo json_encode($result);
	}
	public function edit($nip)
	{
		$sess = $this->session->userdata('user');
		$agama = ['islam','protestan','katolik','hindu','budha','khonghucu'];
		$jenkel = ['l','p'];
		$status_pernikahan = ['lajang','menikah','janda','duda'];
		$data['data_staff'] = $this->Db_select->query('select *, date_format(a.tanggal_lahir,"%Y-%m-%d") tanggal_lahir from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = '.$nip.' and b.id_channel = "'.$sess['id_channel'].'"');
		$struktur_organisasi = $this->Db_select->select_where('tb_struktur_organisasi','id_channel = "'.$sess['id_channel'].'"');
		$data['posisi'] = json_decode($struktur_organisasi->struktur_data);
		$skpd = $this->Db_select->select_all_where('tb_unit', 'is_aktif = 1');
		$jabatan = $this->Db_select->select_all_where('tb_jabatan', 'is_aktif = 1');
		$status_staff = $this->Db_select->select_all_where('tb_status_user', 'is_aktif = 1');
		$data['list_agama'] = "";
		for ($i=0; $i < count($agama); $i++) { 
			$data['list_agama'] .= '<option value="'.$agama[$i].'"'.$this->selected($agama[$i],$data['data_staff']->agama).'>'.ucwords($agama[$i]).'</option>';
		}
		$data['list_jenkel'] = "";
		for ($i=0; $i < count($jenkel) ; $i++) { 
			if ($jenkel[$i] == 'l') {
				$nama_jenkel = 'Laki-Laki';
			}elseif ($jenkel[$i] == 'p') {
				$nama_jenkel = 'Perempuan';
			}
			$data['list_jenkel'] .= '<option value="'.$jenkel[$i].'"'.$this->selected($jenkel[$i],$data['data_staff']->jenis_kelamin).'>'.ucwords($nama_jenkel).'</option>';
		}
		$data['list_status_pernikahan'] = "";
		for ($i=0; $i < count($status_pernikahan) ; $i++) { 
			$data['list_status_pernikahan'] .= '<option value="'.$status_pernikahan[$i].'"'.$this->selected($status_pernikahan[$i],$data['data_staff']->status_pernikahan).'>'.ucwords($status_pernikahan[$i]).'</option>';
		}
		$data['list_data_unit'] = "";
		foreach ($skpd as $key => $value) {
	        $data['list_data_unit'] .= '<option value="'.$value->id_unit.'"'.$this->selected($value->id_unit,$data['data_staff']->id_unit).'>'.$value->nama_unit.'</option>';
	    }
	    $data['list_jabatan'] = "";
	    foreach ($jabatan as $key => $value) {
	    $data['list_jabatan'] = '<option value="'.$value->id_jabatan.'"'.$this->selected($value->id_jabatan,$data['data_staff']->jabatan).'>'.$value->nama_jabatan.'</option>';
	    }
	    $data['list_status_pekerjaan'] = "";
	    foreach ($status_staff as $key => $value) {
	    $data['list_status_pekerjaan'] .= '<option value="'.$value->id_status_user.'"'.$this->selected($value->id_status_user,$data['data_staff']->status_user).'>'.$value->nama_status_user.'</option>';
	    }
	    $data['list_is_aktif'] = '
	    	<option value="1"'.$this->selected($data['data_staff']->is_aktif,1).'>Aktif</option>
	    	<option value="0"'.$this->selected($data['data_staff']->is_aktif,0).'>Nonaktif</option>';

    	if ($data['data_staff']->tanggal_kerja == null || $data['data_staff']->tanggal_kerja == "") {
    		$data['data_staff']->tanggal_kerja = date('Y-m-d', strtotime($data['data_staff']->created_user));
    	}else{
    		$data['data_staff']->tanggal_kerja = date('Y-m-d', strtotime($data['data_staff']->tanggal_kerja));
    	}

		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/edit_pegawai');
		$this->load->view('SEKDA/footer');	
	}
	public function selected($value, $nama) {
        if ($value == $nama) {
            return " selected";
        } else {
            return "";
        }
    }
    public function get_level($id = null, $newId = null){
    	$sess = $this->session->userdata('user');
    	if ($newId != null || $newId != '') {
    		$this->Newget_level($id, $newId);
    	}
    	$struktur_organisasi = $this->Db_select->select_where('tb_struktur_organisasi', 'id_channel = "'.$sess['id_channel'].'"');
    	$data = "";
    	if ($struktur_organisasi) {
    		$getStruktur = json_decode($struktur_organisasi->struktur_data);
    		$totalStruktur = count($getStruktur);
    		$x = 0;
    		$opsi = $this->list_jabatan();
    		$dataOPD = $this->list_OPD();
    		foreach ($getStruktur as $key => $value) {
    			if ($value->id == $id) {
    				if ($value->parent == 0) {
            			$data .= '<div class="form-group form-float">
		                    <label class="form-label"><i class="col-red">*</i> Unit Kerja</label>
		                    <div class="form-line">
		                        <select class="form-control show-tick" name="id_unit" required>
		                        	<option></option>
		                            '.$dataOPD.'
		                        </select>
		                    </div>
		                </div>';
    				}else{
    					$data .= '<div class="form-group form-float">
		                    <label class="form-label"><i class="col-red">*</i> Unit Kerja</label>
		                    <div class="form-line">
		                        <select class="form-control show-tick" id="id_unit" name="id_unit" required>
		                        	<option></option>
		                            '.$dataOPD.'
		                        </select>
		                    </div>
		                </div>
		                <script type="text/javascript">
			                $("#id_unit").change(function() {
				                var e = $("#id_unit").val();
				                var ee = "'.$value->parent.'";
				                $.ajax({
			                  		success: function(html){
				                    	$("#id_parent").load(path+"Leader/pegawai/getdown/"+e+"/"+ee);
			                  		}
				                });
			              	});
			            </script>
		                <div class="form-group form-float">
		                    <label class="form-label" for="id_parent">Atasan</label>
		                    <div class="form-line">
		                        <select class="form-control show-tick" name="id_parent" id="id_parent">
		                        	<option></option>
		                        </select>
		                    </div>
		                </div>';
    				}
    				$data .= '<div class="form-group form-float">
	                    <label class="form-label"><i class="col-red">*</i> Jabatan</label>
	                    <div class="form-line">
	                        <select class="form-control show-tick" name="jabatan" required>
	                        	<option></option>
	                            '.$opsi.'
	                        </select>
	                    </div>
	                </div>';
    			}
    		}
    	}
    	echo $data;
    }
    public function Newget_level($id = null, $newId = null){
    	$sess = $this->session->userdata('user');
    	$struktur_organisasi = $this->Db_select->select_where('tb_struktur_organisasi', 'id_channel = "'.$sess['id_channel'].'"');
    	$dataUser = $this->Db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.nip = "'.$newId.'" and b.id_channel = "'.$sess['id_channel'].'"');
    	$data = "";
    	if ($struktur_organisasi) {
    		$getStruktur = json_decode($struktur_organisasi->struktur_data);
    		$dataOPD = $this->list_OPD($dataUser->id_unit);
    		$opsi = $this->list_jabatan($dataUser->jabatan);
    		foreach ($getStruktur as $key => $value) {
    			if ($value->id == $id) {
    				if ($value->parent == 0) {
            			$data .= '<div class="form-group form-float">
		                    <label class="form-label"><i class="col-red">*</i> Unit Kerja</label>
		                    <div class="form-line">
		                        <select class="form-control show-tick" name="id_unit" required>
		                        	<option></option>
		                            '.$dataOPD.'
		                        </select>
		                    </div>
		                </div>';
    				}else{
    					$data .= '<div class="form-group form-float">
		                    <label class="form-label"><i class="col-red">*</i> Unit Kerja</label>
		                    <div class="form-line">
		                        <select class="form-control show-tick" id="id_unit" name="id_unit" required>
		                        	<option></option>
		                            '.$dataOPD.'
		                        </select>
		                    </div>
		                </div>
		                <script type="text/javascript">
		                	var e = $("#id_unit").val();
			                var ee = "'.$value->parent.'";
			                var f = "'.$dataUser->id_parent.'";
			                $.ajax({
			                  success: function(html){
			                    $("#id_parent").load(path+"Leader/pegawai/getdown/"+e+"/"+ee+"/"+f);
			                  }
			                });
			                $("#id_unit").change(function() {
			                var e = $("#id_unit").val();
			                var ee = "'.$value->parent.'";
			                $.ajax({
			                  success: function(html){
			                    $("#id_parent").load(path+"Leader/pegawai/getdown/"+e+"/"+ee);
			                  }
			                });
			              });
			            </script>
		                <div class="form-group form-float">
		                    <label class="form-label" for="id_parent">Atasan</label>
		                    <div class="form-line">
		                        <select class="form-control show-tick" name="id_parent" id="id_parent">
		                        	<option></option>
		                        </select>
		                    </div>
		                </div>';
    				}
    				$data .= '<div class="form-group form-float">
	                    <label class="form-label"><i class="col-red">*</i> Jabatan</label>
	                    <div class="form-line">
	                        <select class="form-control show-tick" name="jabatan" required>
	                        	<option></option>
	                            '.$opsi.'
	                        </select>
	                    </div>
	                </div>';
    			}
    		}
    	}
    	echo $data;exit();
    }
    public function getdown($id = null, $newId = null, $newId2 = null)
    {
    	$selectUser = $this->Db_select->query_all('select *from tb_user where id_unit = "'.$id.'" and id_struktur = "'.$newId.'"');
		$data = "<option></option>";
    	foreach ($selectUser as $key => $value) {
    		$data .= "<option value='".$value->user_id."'".$this->selected($newId2,$value->user_id).">".$value->nama_user."</option>";
    	}
    	echo $data;
    }
    public function list_jabatan($id = null)
    {
    	$sess = $this->session->userdata('user');
    	$data = "";
        $jabatan = $this->Db_select->select_all_where('tb_jabatan', 'is_aktif = 1 and id_channel = "'.$sess['id_channel'].'"');
        foreach ($jabatan as $key => $value) {
            $data .= "<option value='".$value->id_jabatan."'".$this->selected($id,$value->id_jabatan).">".$value->nama_jabatan."</option>";
        }
        return $data;
    }
    public function list_eselon($id = null, $newId = null)
    {
    	$data = "";
        $eselon = $this->Db_select->select_all_where('tb_eselon','id_struktur = "'.$id.'"');
        foreach ($eselon as $key => $value) {
            $data .= "<option value='".$value->id_eselon."'".$this->selected($newId,$value->id_eselon).">".$value->nama_eselon."</option>";
        }
        return $data;
    }
    public function list_atasan($id = null, $newId = null)
    {
    	$data = "";
        $atasan = $this->Db_select->query_all('select *from tb_user where id_struktur = "'.$id.'"');
        foreach ($atasan as $key => $value) {
            $data .= "<option value='".$value->user_id."'".$this->selected($newId,$value->user_id).">".$value->nama_user."</option>";
        }
        return $data;
    }
    public function list_OPD($id = null)
    {
    	$sess = $this->session->userdata('user');
    	$data = "";
        $OPD = $this->Db_select->select_all_where('tb_unit','is_aktif = 1 and id_channel = "'.$sess['id_channel'].'"');
        foreach ($OPD as $key => $value) {
            $data .= "<option value='".$value->id_unit."'".$this->selected($id,$value->id_unit).">".$value->nama_unit."</option>";
        }
        return $data;
    }
  public function detail($id)
	{
		$item = $this->Db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_admin = 0 AND nip = '.$id);

    $selectJabatan = $this->Db_select->select_where('tb_jabatan','id_jabatan = '.$item->jabatan);
    if ($selectJabatan) {
      $item->jabatan = $selectJabatan->nama_jabatan;
    }else{
      $item->jabatan = "-";
    }
    $selectTipe = $this->Db_select->select_where('tb_status_user','id_status_user = '.$item->status_user);
    if ($selectTipe) {
      $item->status_user = $selectTipe->nama_status_user;
    }else{
      $item->status_user = "-";
    }
    if ($item->foto_user==""||$item->foto_user==null) {
      $item->foto_user = base_url()."assets/images/member-photos/ava.png";
    }else{
      $filename = './assets/images/member-photos/'.$item->foto_user;
      if (!file_exists($filename)) {
        $item->foto_user = base_url()."assets/images/member-photos/ava.png";
      }else{
        $item->foto_user = base_url()."assets/images/member-photos/".$item->foto_user;
      }
    }

    if ($item->status_pernikahan == "" || $item->status_pernikahan==null) {
      $item->status_pernikahan = "-";
    }
    if ($item->pendidikan_terakhir == "" || $item->pendidikan_terakhir==null) {
      $item->pendidikan_terakhir = "-";
    }
    $data['item'] = $item;

		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/detail_pegawai');
		$this->load->view('SEKDA/footer');
	}
    public function list_ttp($id = null)
	{
		$cekStatus = $this->Db_select->select_where('tb_status_user','id_status_user = "'.$id.'"');
		$list = "";
		if ($cekStatus->pemotongan_tpp == 1) {
			$list .= '<div class="form-group form-float">
	                    <label class="form-label"><i class="col-red">*</i> 30% dari Tunjangan Kinerja</label>
	                    <div class="form-line">
	                        <input type="text" name="saldo" class="form-control" required>
	                    </div>
	                </div>';
		}
        echo $list;
	}
	public function list_ttpNew($id = null, $newId = null)
	{
		$getUser = $this->Db_select->select_where('tb_user','user_id = "'.$newId.'"');
		$cekStatus = $this->Db_select->select_where('tb_status_user','id_status_user = "'.$id.'"');
		$list = "";
		if ($cekStatus->pemotongan_tpp == 1) {
			$list .= '<div class="form-group form-float">
	                    <label class="form-label"><i class="col-red">*</i> 30% dari Tunjangan Kinerja</label>
	                    <div class="form-line">
	                        <input type="text" name="saldo" class="form-control" value="'.$getUser->saldo.'" required>
	                    </div>
	                </div>';
		}
        echo $list;
	}
	public function simulasi($id = null)
	{
		$list = '';
		if ($id != null) {
			if ($id == 1) {
				$list .= '<h2 class="card-inside-title">Jam Masuk</h2>
	                    <div class="row clearfix">
	                        <div class="col-sm-12">
	                            <input type="text" name="monday_from" id="jam_masuk" class="form-control time24" placeholder="07:30" size="1" required>
	                        </div>
	                    </div>
	                    <script type="text/javascript">
			                $("#jam_masuk").change(function() {
				                var e = $("#jam_masuk").val();
				                $.ajax({
				                	method  : "GET",
						            url     : path+"Leader/pegawai/pemotonganKeterlambatan/"+e,
						            async   : true,
			                  		success: function(data, status, xhr){
				                    	$("#besar_potongan").val(xhr.responseText);
			                  		}
				                });
			              	});
			            </script>';
	            $list .= '<h2 class="card-inside-title">Besar Potongan</h2>
	                    <div class="row clearfix">
	                        <div class="col-sm-12">
	                            <input type="text" id="besar_potongan" name="monday_from" class="form-control" required>
	                        </div>
	                    </div>';
			}else{
				//TidakMasuk
				if ($id == 2) {
					$besaPotongan = $this->pemotongantidakMasuk(1);
				}elseif ($id == 3) {
					$besaPotongan = $this->pemotongantidakMasuk(2);
				}elseif ($id == 4) {
					$besaPotongan = $this->pemotongantidakMasuk(3);
				}elseif ($id == 5) {
					$besaPotongan = $this->pemotonganPulangCepat(1);
				}elseif ($id == 6) {
					$besaPotongan = $this->pemotonganPulangCepat(2);
				}elseif ($id == 7) {
					$besaPotongan = $this->pemotonganPulangCepat(3);
				}elseif ($id == 8) {
					$besaPotongan = $this->pemotonganTidakApel(1);
				}elseif ($id == 9) {
					$besaPotongan = $this->pemotonganTidakApel(2);
				}elseif ($id == 10) {
					$besaPotongan = $this->pemotonganTidakApel(3);
				}else{
					$besaPotongan = 0;
				}
				$list .= '<h2 class="card-inside-title">Besar Potongan</h2>
	                    <div class="row clearfix">
	                        <div class="col-sm-12">
	                            <input type="text" id="besar_potongan" value="'.$besaPotongan.'" name="monday_from" class="form-control" required>
	                        </div>
	                    </div>';
			}
		}
		echo $list;
	}
	public function pemotonganKeterlambatan($id = null)
	{
		$sess = $this->session->userdata('user');
		$cekUser = $this->Db_select->select_where('tb_user','user_id = "'.$sess['id_user'].'"');
        
        // cek keterlambatan
        $jadwal = json_decode(file_get_contents($this->file))->jam_kerja;
        $day = strtolower(date("l"));
        $newJadwal = date('H:i:s', strtotime($jadwal->$day->from));
        // absen masuk
        $awal = date_create(date('H:i:s', strtotime($id)));
        // jam masuk
        $akhir = date_create($newJadwal);
        $pengurangan = 0;
        
        if ($awal > $akhir) {
            $diff = date_diff( $awal, $akhir );
            $newDate = strtotime($diff->h.":".$diff->i.":".$diff->s);
            $newDate = date('H:i:s', $newDate);
            $cekKeterlambatan = $this->Db_select->query('select * from tb_potongan_keterlambatan where "'.$newDate.'" < durasi_keterlambatan limit 1');
            $saldoUser = $cekUser->saldo;
            if ($cekKeterlambatan) {
                $pengurangan = $cekKeterlambatan->potongan_keterlambatan*$saldoUser/100;
            }else{
                $keterlambatanMax = $this->Db_select->query('select * from tb_potongan_keterlambatan order by durasi_keterlambatan desc limit 1');
                if ($keterlambatanMax) {
                	$pengurangan = $keterlambatanMax->potongan_keterlambatan*$saldoUser/100;
                }
            }
        }
        echo json_encode($pengurangan); exit();
	}
	public function pemotongantidakMasuk($id = null)
	{
		$sess = $this->session->userdata('user');
		if ($id != null) {
			$getUser = $this->Db_select->select_where('tb_user', 'user_id = "'.$sess['id_user'].'"');
			$selectPotongan = $this->Db_select->select_where('tb_potongan_mangkir', 'id_mangkir = "'.$id.'"');
			if ($selectPotongan) {
				if ($selectPotongan->besar_potongan == null || $selectPotongan->besar_potongan == "") {
					$selectPotongan->besar_potongan = 0;
				}

				if ($getUser->saldo == null || $getUser->saldo == "") {
					$getUser->saldo = 0;
				}

	        	$pengurangan = $selectPotongan->besar_potongan*$getUser->saldo/100;
			}else{
	        	$pengurangan = 0;
			}
	        return $pengurangan;
		}
	}
	public function pemotonganPulangCepat($id = null)
	{
		$sess = $this->session->userdata('user');
		if ($id != null) {
			$getUser = $this->Db_select->select_where('tb_user', 'user_id = "'.$sess['id_user'].'"');
			$selectPotongan = $this->Db_select->select_where('tb_potongan_keluar_jamkerja', 'id_meninggalkan_kantor = "'.$id.'"');
	        $pengurangan = $selectPotongan->besar_potongan*$getUser->saldo/100;
	        return $pengurangan;
		}
	}
	public function pemotonganTidakApel($id = null)
	{
		$sess = $this->session->userdata('user');
		if ($id != null) {
			$getUser = $this->Db_select->select_where('tb_user', 'user_id = "'.$sess['id_user'].'"');
			$selectPotongan = $this->Db_select->select_where('tb_potongan_apel', 'id_potongan_apel = "'.$id.'"');
	        $pengurangan = $selectPotongan->besar_potongan*$getUser->saldo/100;
	        return $pengurangan;
		}
	}
	public function search(){
		$sess = $this->session->userdata('user');
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
        	redirect(base_url());exit();
        }
		$this->load->view('SEKDA/header');
		$this->load->view('SEKDA/pegawai_search');
		$this->load->view('SEKDA/footer');
    }
    public function getData()
    {
    	$sess = $this->session->userdata('user');
		  $where = 'b.id_channel = "'.$sess['id_channel'].'" and a.id_parent = "'.$sess['id_user'].'"';
    	$columns = array( 
	      0 =>  'no', 
	      1 =>  'nip',
	      2 =>  'nama_user',
	      3 =>  'jabatan',
	      4 =>  'b.nama_unit',
	      5 =>  'd.nama_status_user',
	      6 =>  'a.is_aktif',
	      7 =>  'aksi'
	    );

	    $limit  = $this->input->post('length');
	    $start  = $this->input->post('start');
	    $order  = $columns[$this->input->post('order')[0]['column']];
	    $dir    = $this->input->post('order')[0]['dir'];
	    $totalData = $this->Db_global->allposts_count_all("select *, a.is_aktif as as_aktif from tb_user a join tb_unit b on a.id_unit = b.id_unit left outer join tb_jabatan c on a.jabatan = c.id_jabatan left outer join tb_status_user d on a.status_user = d.id_status_user where ".$where."");
	    $totalFiltered = $totalData;
	    
	    if(empty($this->input->post('search')['value'])){
        $posts = $this->Db_global->allposts_all("select *, a.is_aktif as as_aktif from tb_user a join tb_unit b on a.id_unit = b.id_unit left outer join tb_jabatan c on a.jabatan = c.id_jabatan left outer join tb_status_user d on a.status_user = d.id_status_user where ".$where." order by ".$order." ".$dir." limit ".$start.",".$limit."");
      }else{
        $search = $this->input->post('search')['value'];
        $posts = $this->Db_global->posts_search_all("select *, a.is_aktif as as_aktif from tb_user a join tb_unit b on a.id_unit = b.id_unit left outer join tb_jabatan c on a.jabatan = c.id_jabatan left outer join tb_status_user d on a.status_user = d.id_status_user where ".$where." and (a.nip like '%".$search."%' or a.nama_user like '%".$search."%' or a.jabatan like '%".$search."%' or b.nama_unit like '%".$search."%') order by ".$order." ".$dir." limit ".$start.",".$limit."");
        $totalFiltered = $this->Db_global->posts_search_count_all("select *, a.is_aktif as as_aktif from tb_user a join tb_unit b on a.id_unit = b.id_unit left outer join tb_jabatan c on a.jabatan = c.id_jabatan left outer join tb_status_user d on a.status_user = d.id_status_user where ".$where." and (a.nip like '%".$search."%' or a.nama_user like '%".$search."%' or a.jabatan like '%".$search."%' or b.nama_unit like '%".$search."%')");
      }

        $data = array();
	    if(!empty($posts)){
    		foreach ($posts as $key => $post){
    			if ($post->as_aktif == 1) {
    				$post->as_aktif = "Aktif";
    			}else{
    				$post->as_aktif = "Nonaktif";
    			}
    			$nestedData['no']  = $key+1;
    			$nestedData['nip']  = $post->nip;
    			$nestedData['nama_user']  = $post->nama_user;
    			$nestedData['jabatan']  = $post->nama_jabatan;
    			$nestedData['departemen']  = $post->nama_unit;
    			$nestedData['tipe']  = $post->nama_status_user;
    			$nestedData['status']  = $post->as_aktif;
    			$nestedData['aksi']  = '
    				<a href="'.base_url().'Leader/pegawai/detail/'.$post->nip.'" class="btn btn-info btn-sm"><span class="fa fa-eye"></span></a>';
    			
    			$data[] = $nestedData;
    		}
	    }
	    $json_data = array(
	      "draw"            => intval($this->input->post('draw')),  
	      "recordsTotal"    => intval($totalData),  
	      "recordsFiltered" => intval($totalFiltered), 
	      "data"            => $data   
	    );
        
    	echo json_encode($json_data);
    }
}