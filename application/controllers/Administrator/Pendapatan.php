<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Pendapatan extends CI_Controller
{
    public function __construct()
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
		$data['data_komponen'] = $this->Db_select->select_all_where('tb_komponen_pendapatan', 'id_channel = "'.$sess['id_channel'].'"');

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/komponen_pendapatan');
		$this->load->view('Administrator/footer');
    }

    public function getData()
	{
		$sess = $this->session->userdata('user');
		$columns = array( 
			0 =>  'no', 
			1 =>  'nama_komponen_pendapatan',
			2 =>  'is_aktif',
			3 =>  'aksi'
        );
        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $order  = $columns[$this->input->post('order')[0]['column']];
        $dir    = $this->input->post('order')[0]['dir'];
        $query = 'select *from tb_komponen_pendapatan where id_channel = "'.$sess['id_channel'].'"';
        $totalData = $this->Db_global->allposts_count_all($query);
        $totalFiltered = $totalData;
        if(empty($this->input->post('search')['value'])){
            $posts = $this->Db_global->allposts_all($query." order by ".$order." ".$dir." limit ".$start.",".$limit."");
        }else{
            $search = $this->input->post('search')['value'];
            $posts = $this->Db_global->posts_search_all($query." and nama_komponen_pendapatan like '%".$search."%' or is_aktif like '%".$search."%' order by ".$order." ".$dir." limit ".$start.",".$limit."");
            $totalFiltered = $this->Db_global->posts_search_count_all($query." and nama_komponen_pendapatan like '%".$search."%' or is_aktif like '%".$search."%'");
        }
        $data = array();
        if(!empty($posts)){
        	foreach ($posts as $key => $post){
        		$cekUser = $this->Db_select->select_where('tb_pendapatan','id_komponen_pendapatan = "'.$post->id_komponen_pendapatan.'"');
        		if ($cekUser) {
        			$delete = "";
        		}else{
        			$delete = '<a href="#" style="color: grey" data-type="ajax-loader" onclick="hapus('.$post->id_komponen_pendapatan.')"><span class="material-icons">delete</span></a>';
        		}
        		$is_aktif = "";
        		if ($post->is_aktif == 1) {
        			$is_aktif = 'checked';
        		}
                $nestedData['no']  = $key+1;
                $nestedData['nama_komponen_pendapatan']  = ucwords($post->nama_komponen_pendapatan);
                $nestedData['status']  = '<span class="label label-success">Aktif</span>';
                $nestedData['aksi']  = '
                	<a href="#" style="color: grey" data-toggle="modal" data-target="#updateModal'.$post->id_komponen_pendapatan.'"><span class="material-icons">mode_edit</span></a>'.$delete;
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
    
    public function insert()
	{
		$sess = $this->session->userdata('user');
		$id_channel= $sess['id_channel'];
		$insert = array();
		if ($this->input->post('nama_komponen_pendapatan')) {
			$insert['nama_komponen_pendapatan'] = $this->input->post('nama_komponen_pendapatan');
			$insert['id_channel'] =$id_channel;
		}
		if (count($insert) > 0) {
			$insertData = $this->Db_dml->normal_insert('tb_komponen_pendapatan', $insert);
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

    public function update()
	{
		$sess = $this->session->userdata('user');
		$where['id_komponen_pendapatan'] = $this->input->post('id_komponen_pendapatan');
		$update = array();
		if ($this->input->post('nama_komponen_pendapatan')) {
			$update['nama_komponen_pendapatan'] = $this->input->post('nama_komponen_pendapatan');
		}
		if (count($update) > 0) {
			$updateData = $this->Db_dml->update('tb_komponen_pendapatan', $update, $where);
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
    
    public function delete()
	{
		$where['id_komponen_pendapatan'] = $this->input->post('id_komponen_pendapatan');
		$delete = $this->Db_dml->delete('tb_komponen_pendapatan', $where);

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
}
