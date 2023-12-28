<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
/**
* 
*/
class skpd extends CI_Controller
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
		$data['data_skpd'] = $this->Db_select->select_all('tb_skpd');
		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/skpd');
		$this->load->view('Administrator/footer');
	}
	public function insert()
	{
		$sess = $this->session->userdata('user');
		$insert = array();
		if ($this->input->post('nama_skpd')) {
			$insert['nama_skpd'] = $this->input->post('nama_skpd');
		}
		if (count($insert) > 0) {
			$insertData = $this->Db_dml->normal_insert('tb_skpd', $insert);
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
		$where['id_skpd'] = $this->input->post('id_skpd');
		$delete = $this->Db_dml->delete('tb_skpd', $where);
		echo json_encode($delete); exit();
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
		$where['id_skpd'] = $this->input->post('id_skpd');
		$update = array();
		if ($this->input->post('nama_skpd')) {
			$update['nama_skpd'] = $this->input->post('nama_skpd');
		}
		if (count($update) > 0) {
			$updateData = $this->Db_dml->update('tb_skpd', $update, $where);
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
		$where['id_skpd'] = $this->input->post('id_skpd');
		$update['is_aktif'] = $this->input->post('is_aktif');
		$updateData = $this->Db_dml->update('tb_skpd', $update, $where);
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
	public function getData()
	{
		$sess = $this->session->userdata('user');
		$columns = array( 
			0 =>  'no', 
			1 =>  'nama_skpd',
			2 =>  'is_aktif',
			3 =>  'aksi'
        );
        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $order  = $columns[$this->input->post('order')[0]['column']];
        $dir    = $this->input->post('order')[0]['dir'];
        $query = 'select *from tb_skpd';
        $totalData = $this->Db_global->allposts_count_all($query);
        $totalFiltered = $totalData;
        if(empty($this->input->post('search')['value'])){
            $posts = $this->Db_global->allposts_all($query." order by ".$order." ".$dir." limit ".$start.",".$limit."");
        }else{
            $search = $this->input->post('search')['value'];
            $posts = $this->Db_global->posts_search_all($query." and nama_skpd like '%".$search."%' or is_aktif like '%".$search."%' order by ".$order." ".$dir." limit ".$start.",".$limit."");
            $totalFiltered = $this->Db_global->posts_search_count_all($query." and nama_skpd like '%".$search."%' or is_aktif like '%".$search."%'");
        }
        $data = array();
        if(!empty($posts)){
        	foreach ($posts as $key => $post){
        		$is_aktif = "";
        		if ($post->is_aktif == 1) {
        			$is_aktif = 'checked';
        		}
                $nestedData['no']  = $key+1;
                $nestedData['nama_skpd']  = ucwords($post->nama_skpd);
                $nestedData['status']  = '<div class="demo-switch">
                	<div class="switch">
                		<label>Tidak Aktif
                			<input value="'.$post->id_skpd.'" onclick="is_aktif('.$post->id_skpd.')" type="checkbox" id="is_aktif'.$post->id_skpd.'" '.$is_aktif.'>
                			<span class="lever"></span>Aktif
                		</label>
            		</div>
        		</div>';
                $nestedData['aksi']  = '
                	<a href="#" style="color: grey" data-toggle="modal" data-target="#updateModal'.$post->id_skpd.'"><span class="material-icons">mode_edit</span></a><a href="#" style="color: grey" data-type="ajax-loader" onclick="hapus('.$post->id_skpd.')"><span class="material-icons">delete</span></a>';
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