<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class izin_pulang extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('ceksession');
		$this->load->model('Db_datatable');

		$this->ceksession->login();
	}
	public function index()
	{
		$sess = $this->session->userdata('user');
        $id_channel = $sess['id_channel'];
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
        redirect(base_url());exit();
        }
		// $data['data_cuti'] = $this->Db_select->query_all('select a.user_id, a.nip, a.nama_user, b.nama_unit, c.keterangan_izin_pulang, c.created_izin_pulang, c.status_approval, c.id_izin_pulang, d.nama_status_pengajuan from tb_user a join tb_unit b on a.id_unit = b.id_unit join tb_izin_pulang c on a.user_id = c.user_id join tb_status_pengajuan d on c.status_pengajuan = d.id_status_pengajuan where id_channel = '.$id_channel.' order by c.created_izin_pulang desc');
		$data['cari']= 'false';
		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/izin_pulang');
		$this->load->view('Administrator/footer');
	}
	public function insert()
	{
		$sess = $this->session->userdata('user');
		$insert = array();
		$insert['id_channel'] = $sess['id_channel'];
		$insert['nama_unit'] = $this->input->post('nama_unit');
		$insert['alamat_unit'] = $this->input->post('alamat_unit');
       	if($_FILES['userfile']['name']){
       		$this->load->library('upload');
	        $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
	        $config['upload_path'] = './assets/images/unit'; //path folder
	        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
	        $config['max_size'] = '2048'; //maksimum besar file 2M
	        $config['file_name'] = $nmfile; //nama yang terupload nantinya
	        $this->upload->initialize($config);
       		if ($this->upload->do_upload('userfile')){
				$gbr = $this->upload->data();
			   	$name3 = $gbr['file_name'];
			   	$insert['icon_unit'] = $name3;
			}
       	}
		if (count($insert) > 0) {
			$insertData = $this->Db_dml->normal_insert('tb_unit', $insert);
			if ($insertData) {
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
	public function delete()
	{
		$where['id_unit'] = $this->input->post('id_unit');
		$delete = $this->Db_dml->delete('tb_unit', $where);
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
	public function update()
	{
		$sess = $this->session->userdata('user');
		$where['id_unit'] = $this->input->post('id_unit');
		$update = array();
		if ($this->input->post('nama_unit')) {
			$update['nama_unit'] = $this->input->post('nama_unit');
		}
		if ($this->input->post('alamat_unit')) {
			$update['alamat_unit'] = $this->input->post('alamat_unit');
		}
		if($_FILES['userfile']['name']){
       		$this->load->library('upload');
	        $nmfile = "file_".time(); //nama file saya beri nama langsung dan diikuti fungsi time
	        $config['upload_path'] = './assets/images/unit'; //path folder
	        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
	        $config['max_size'] = '2048'; //maksimum besar file 2M
	        $config['file_name'] = $nmfile; //nama yang terupload nantinya
	        $this->upload->initialize($config);
       		if ($this->upload->do_upload('userfile')){
				$gbr = $this->upload->data();
			   	$name3 = $gbr['file_name'];
			   	$update['icon_unit'] = $name3;
			}
       	}
		if (count($update) > 0) {
			$updateData = $this->Db_dml->update('tb_unit', $update, $where);
			if ($updateData) {
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
	public function update_status()
	{
		$sess = $this->session->userdata('user');
		$where['id_izin_pulang'] = $this->input->post('id');
		$update['status_approval'] = 1;
		$updateData = $this->Db_dml->update('tb_izin_pulang', $update, $where);
		if ($updateData) {
			$selectIjin = $this->Db_select->select_where('tb_izin_pulang', 'id_izin_pulang = "'.$this->input->post('id').'"');
			$selectUser = $this->Db_select->select_where('tb_user', 'user_id = "'.$selectIjin->user_id.'"');
			if ($selectIjin->status_pengajuan == 2) {
				$this->potonganIzinPulang($selectIjin->status_pengajuan, $selectIjin->user_id, $selectUser->saldo);
			}
			$cek = $this->Db_select->select_where('tb_absensi', 'user_id = '.$selectIjin->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
            $update2['waktu_pulang'] = mdate("%Y-%m-%d %H:%i:%s", time());
            $update_absen = $this->Db_dml->update('tb_absensi', $update2, 'user_id = '.$selectIjin->user_id.' and date_format(created_absensi, "%Y-%m-%d") = "'.mdate("%Y-%m-%d", time()).'"');
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
	public function update_status2()
	{
		$sess = $this->session->userdata('user');
		$where['id_izin_pulang'] = $this->input->post('id');
		$update['status_approval'] = 2;
		$updateData = $this->Db_dml->update('tb_izin_pulang', $update, $where);
		if ($updateData) {
			$selectIjin = $this->Db_select->select_where('tb_izin_pulang', 'id_izin_pulang = "'.$this->input->post('id').'"');
			if ($selectIjin->status_pengajuan == 3) {
				$selectUser = $this->Db_select->select_where('tb_user', 'user_id = "'.$selectIjin->user_id.'"');
				$this->potonganIzinPulang(1, $selectIjin->user_id, $selectUser->saldo);
			}
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
	public function potonganIzinPulang($id, $user_id, $saldo)
    {
        $selectPotongan = $this->Db_select->select_where('tb_potongan_keluar_jamkerja', 'id_meninggalkan_kantor = "'.$id.'"');
        $pengurangan = $selectPotongan->besar_potongan*$saldo/100;
        $insert['id_meninggalkan_kantor'] = $id;
        $insert['user_id'] = $user_id;
        $insert['total_potongan'] = $pengurangan;
        $this->Db_dml->normal_insert('tb_hstry_potongan_keluar_jamkerja', $insert);
    }
	public function detail_izin_pulang($id)
	{
		$data_izin = $this->Db_select->query_all('select a.user_id, a.email_user, a.telp_user, a.alamat_user, a.foto_user, a.status_user, a.jabatan, a.nip, a.nama_user, b.nama_unit, c.keterangan_izin_pulang, c.created_izin_pulang, c.status_approval, c.id_izin_pulang, d.nama_status_pengajuan from tb_user a join tb_unit b on a.id_unit = b.id_unit join tb_izin_pulang c on a.user_id = c.user_id join tb_status_pengajuan d on c.status_pengajuan = d.id_status_pengajuan where c.id_izin_pulang = "'.$id.'"');
	 	foreach ($data_izin as $key => $value) {
		 	$data_jabatan=$this->Db_select->query('select * from tb_jabatan where id_jabatan = '.$value->jabatan.'');
		 	$jabatan = $data_jabatan->nama_jabatan;
		 	$data_status=$this->Db_select->query('select * from tb_status_user where id_status_user ='.$value->status_user.' ');
		 	$status=$data_status->nama_status_user;
		 	 if ($value->status_approval == 1) {
                $value->status_approval = '<span class="badge bg-green">Approval</span>';
                $data['tombol'] = '';
            }else if ($value->status_approval == 2) {
                $value->status_approval = '<span class="badge bg-orange">Rejected</span>';
                $data['tombol'] = '';
            }
            else {
                $value->status_approval = '<span class="badge bg-red">Butuh Konfirmasi</span>';
                $data['tombol'] = '
                	<button id="acc'.$value->id_izin_pulang.'" onclick="acc('.$value->id_izin_pulang.')" class="btn bg-blue btn-block btn-xs">ACC</button>
                	<button id="reject'.$value->id_izin_pulang.'" onclick="reject('.$value->id_izin_pulang.')" class="btn bg-orange btn-block btn-xs">Reject</button>';
            } 
            if ($value->foto_user==""||$value->foto_user==null) {
              	$data['foto']="http://placehold.it/500x400";
              }else{
              	$data['foto']=" ".base_url()."assets/images/member-photos/$value->foto_user ";
              }  
            // echo json_encode($data['tombol']);exit();
		 	$data['list']="
		 			<h5>".$value->nip."</h5>
		 			<h5>".$value->nama_user."</h5>
		 			<h5>".$value->nama_unit."</h5>
		 			<h5>".$jabatan."</h5>
		 			<h5>".$status."</h5>
		 			<h5>".$value->email_user."</h5>
		 			<h5>".$value->telp_user."</h5>
		 			<h5>".$value->alamat_user."</h5>
		 	";
		 	$data['cuti']="
                     <h5>Tipe Pengajuan : ".$value->nama_status_pengajuan." </h5>
		 			 <h5>Keterangan Pengajuan Cuti :".$value->keterangan_izin_pulang."</h5> 
                     <h5>Tanggal Pengajuan : ".date('Y-m-d',strtotime($value->created_izin_pulang))." </h5>
                     <h5>Waktu Pengajuan : ".date('H:i:s',strtotime($value->created_izin_pulang))." </h5>
                     <h5>Status Pengajuan : ".$value->status_approval." </h5>
		 	";
		}

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/detail_izin_pulang');
		$this->load->view('Administrator/footer');
	}
	public function search(){
		$sess = $this->session->userdata('user');
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
        	redirect(base_url());exit();
        }
        $data['cari']= 'true';
		$this->load->view('Administrator/header',$data);
		$this->load->view('Administrator/izin_pulang');
		$this->load->view('Administrator/footer');
    }
    function get_data_user($value = null)
    {
    	$sess = $this->session->userdata('user');
		$id_channel= $sess['id_channel'];
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
        	redirect(base_url());exit();
    	}

        $tb ='tb_izin_pulang';
        $j1 ='tb_user';
        $jj1 = 'tb_user.user_id = tb_izin_pulang.user_id';
        $j2 ='tb_unit';
        $jj2 = 'tb_user.id_unit = tb_unit.id_unit';
        $wr= 'id_channel='.$id_channel ;
        $fld =  array(null,'nama_user','created_izin_pulang','keterangan_izin_pulang','status_pengajuan','status_approval');
        $src = array('nama_user');
        $ordr = array('created_izin_pulang' => 'desc');
        $list = $this->Db_datatable->get_datatables3($tb,$j1,$jj1,$j2,$jj2,$wr,$fld,$src,$ordr);
        // echo json_encode($list);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {

        	$selectUser = $this->Db_select->select_where('tb_status_pengajuan','id_status_pengajuan = "'.$field->status_pengajuan.'"');
			$status_pengajuan = $selectUser->nama_status_pengajuan;
	       
	       if ($field->status_approval == 1) {
                $field->status_approval = '<span class="badge bg-green">Approval</span>';
            }elseif ($field->status_approval == 2) {
                $field->status_approval = '<span class="badge bg-orange">Rejected</span>';
                                            }
            else{
                $field->status_approval = '<span class="badge bg-red">Butuh Konfirmasi</span>';
            }
			 
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nama_user;
            $row[] = date('Y-m-d', strtotime($field->created_izin_pulang));
            $row[] = $field->keterangan_izin_pulang;
            $row[] = $status_pengajuan;
            $row[] = $field->status_approval;
           
            $row[] = '<a href="'.base_url().'Administrator/izin_pulang/detail_izin_pulang/'.$field->id_izin_pulang.'">
                                                <button class="btn bg-green btn-block btn-xs">Detail</button></a>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Db_datatable->count_all3($tb,$j1,$jj1,$j2,$jj2,$wr,$fld,$src,$ordr),
            "recordsFiltered" => $this->Db_datatable->count_filtered3($tb,$j1,$jj1,$j2,$jj2,$wr,$fld,$src,$ordr),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

}
