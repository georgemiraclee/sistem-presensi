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
        if (!$sess['akses']) {
        	redirect(base_url());exit();
        }
        
		$data['cari']= 'false';
		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/izin_pulang');
		$this->load->view('SEKDA/footer');
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
        if (!$sess['akses']) {
        	redirect(base_url());exit();
    	}

    	$parrent=$sess['id_user'];
    	$where = 'c.id_channel = "'.$sess['id_channel'].'" and b.id_parent = "'.$parrent.'"';
    	$columns = array( 
			0 =>  'no', 
			1 =>  'b.nama_user',
			2 =>  'a.created_izin_pulang',
			3 =>  'a.keterangan_izin_pulang',
			4 =>  'd.nama_status_pengajuan',
			5 =>  'a.status_approval',
			6 =>  'is_aktif'
	    );

     	$limit  = $this->input->post('length');
	    $start  = $this->input->post('start');
	    $order  = $columns[$this->input->post('order')[0]['column']];
	    $dir    = $this->input->post('order')[0]['dir'];

	    $totalData = $this->Db_global->allposts_count_all("select *from tb_izin_pulang a join tb_user b on a.user_id = b.user_id join tb_unit c on c.id_unit = b.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where ".$where."");
	    $totalFiltered = $totalData;

	    if(empty($this->input->post('search')['value'])){
	    	$posts = $this->Db_global->allposts_all("select *from tb_izin_pulang a join tb_user b on a.user_id = b.user_id join tb_unit c on c.id_unit = b.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where ".$where." order by ".$order." ".$dir." limit ".$start.",".$limit."");
	    }else{
	    	$search = $this->input->post('search')['value'];
	    	$posts = $this->Db_global->posts_search_all("select *from tb_izin_pulang a join tb_user b on a.user_id = b.user_id join tb_unit c on c.id_unit = b.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where ".$where." and (b.nama_user like '%".$search."%' or a.created_izin_pulang like '%".$search."%' or a.keterangan_izin_pulang like '%".$search."%' or d.nama_status_pengajuan like '%".$search."%' or a.status_approval like '%".$search."%') order by ".$order." ".$dir." limit ".$start.",".$limit."");
          	$totalFiltered = $this->Db_global->posts_search_count_all("select *from tb_izin_pulang a join tb_user b on a.user_id = b.user_id join tb_unit c on c.id_unit = b.id_unit join tb_status_pengajuan d on a.status_pengajuan = d.id_status_pengajuan where ".$where." and (b.nama_user like '%".$search."%' or a.created_izin_pulang like '%".$search."%' or a.keterangan_izin_pulang like '%".$search."%' or d.nama_status_pengajuan like '%".$search."%' or a.status_approval like '%".$search."%')");
	    }


	    $data = array();
	    if(!empty($posts)){
	    	foreach ($posts as $key => $post){
	    		if ($post->status_approval == 1) {
	                $post->status_approval = '<span class="badge bg-green">Approval</span>';
	            }elseif ($post->status_approval == 2) {
	                $post->status_approval = '<span class="badge bg-orange">Rejected</span>';
                }else{
	                $post->status_approval = '<span class="badge bg-red">Butuh Konfirmasi</span>';
	            }

	            $nestedData['no']  = $key+1;
	            $nestedData['nama_user'] = $post->nama_user;
	            $nestedData['tanggal'] = date('Y-m-d', strtotime($post->created_izin_pulang));
	            $nestedData['keterangan'] = $post->keterangan_izin_pulang;
	            $nestedData['tipe_pengajuan'] = $post->nama_status_pengajuan;
	            $nestedData['status'] = $post->status_approval;
	           
	            $nestedData['aksi'] = '
	            	<a href="<?php echo base_url();?>Administrator/izin_pulang/detail_izin_pulang/'.$post->id_izin_pulang.'">
	                                                <button class="btn bg-green btn-block btn-xs">Detail</button></a>
	            ';

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
