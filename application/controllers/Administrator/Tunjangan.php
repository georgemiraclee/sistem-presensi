<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
/**
* 
*/
class tunjangan extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('ceksession');
		$this->ceksession->login();
    }

    /* tampilan awal */
    public function index()
    {
        $sess = $this->session->userdata('user');
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
        	redirect(base_url());exit();
		}
		
		$data['data_tunjangan'] = $this->Db_select->query_all('select a.*, b.id_tunjangan_jabatan, b.tunjangan_jabatan, b.tunjangan_pakdin from tb_jabatan a left join tb_tunjangan_jabatan b on a.id_jabatan = b.id_jabatan where a.id_channel = '.$sess['id_channel']);
		$data['id_channel'] = $sess['id_channel'];

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/tunjangan');
		$this->load->view('Administrator/footer');
    }
    /* end */

    /* set update data */
    public function update()
    {
        /* cek apakah data sudah ada di table tb_tunjangan_jabatan */
        $where['id_jabatan'] = $this->input->post('id_jabatan');
        $checkData = $this->Db_select->select_where('tb_tunjangan_jabatan', $where);

        if ($checkData) {
            /* proses update data */
            $update['tunjangan_jabatan'] = $this->input->post('tunjangan_jabatan');
            $update['tunjangan_pakdin'] = $this->input->post('tunjangan_pakdin');

            $updateData = $this->Db_dml->update('tb_tunjangan_jabatan', $update, $where);
            if ($updateData) {
                /* respon berhasil diubah */
                $result['status'] = true;
                $result['message'] = 'Data berhasil disimpan.';
                $result['data'] = array();
                /* end */
            }else{
                /* respon gagal diubah */
                $result['status'] = false;
                $result['message'] = 'Data gagal disimpan.';
                $result['data'] = array();
                /* end */
            }
            /* end */
        }else{
            /* proses insert data */
            $insert['id_jabatan'] = $this->input->post('id_jabatan');
            $insert['tunjangan_jabatan'] = $this->input->post('tunjangan_jabatan');
            $insert['tunjangan_pakdin'] = $this->input->post('tunjangan_pakdin');

            $insertData = $this->Db_dml->normal_insert('tb_tunjangan_jabatan', $insert);

            if ($insertData) {
                /* respon berhasil disimpan */
                $result['status'] = true;
                $result['message'] = 'Data berhasil disimpan.';
                $result['data'] = array();
                /* end */
            }else{
                /* respon gagal disimpan */
                $result['status'] = false;
                $result['message'] = 'Data gagal disimpan.';
                $result['data'] = array();
                /* end */
            }
            /* end */
        }

        echo json_encode($result); exit();
    }
    /* end */

    /* get data untuk datatable serverside */
    public function getData()
    {
        $sess = $this->session->userdata('user');
		$columns = array( 
			0 =>  'no', 
			1 =>  'a.nama_jabatan',
			2 =>  'b.tunjangan_jabatan',
			3 =>  'b.tunjangan_pakdin',
			4 =>  'aksi'
        );
        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $order  = $columns[$this->input->post('order')[0]['column']];
		$dir    = $this->input->post('order')[0]['dir'];
		
        $query = 'select a.*, b.id_tunjangan_jabatan, b.tunjangan_jabatan, b.tunjangan_pakdin from tb_jabatan a left join tb_tunjangan_jabatan b on a.id_jabatan = b.id_jabatan where a.id_channel = "'.$sess['id_channel'].'"';
        $totalData = $this->Db_global->allposts_count_all($query);
		$totalFiltered = $totalData;
		
        if(empty($this->input->post('search')['value'])){
            $posts = $this->Db_global->allposts_all($query." order by ".$order." ".$dir." limit ".$start.",".$limit."");
        }else{
            $search = $this->input->post('search')['value'];
            $posts = $this->Db_global->posts_search_all($query." and a.nama_jabatan like '%".$search."%' or tunjangan_jabatan like '%".$search."%' or tunjangan_pakdin like '%".$search."%' order by ".$order." ".$dir." limit ".$start.",".$limit."");
            $totalFiltered = $this->Db_global->posts_search_count_all($query." and a.nama_jabatan like '%".$search."%' or tunjangan_jabatan like '%".$search."%' or tunjangan_pakdin like '%".$search."%'");
		}
		
        $data = array();
        if(!empty($posts)){
        	foreach ($posts as $key => $post){
                if ($post->tunjangan_jabatan == null || $post->tunjangan_jabatan == "") {
                    $post->tunjangan_jabatan = 0;
                }

                if ($post->tunjangan_pakdin == null || $post->tunjangan_pakdin == "") {
                    $post->tunjangan_pakdin = 0;
                }

                $nestedData['no']  = $key+1;
                $nestedData['nama_jabatan']  = ucwords($post->nama_jabatan);
                $nestedData['tunjangan_jabatan']  = $post->tunjangan_jabatan;
                $nestedData['tunjangan_pakdin']  = $post->tunjangan_pakdin;
                $nestedData['aksi']  = '
                	<a href="#" style="color: grey" data-toggle="modal" data-target="#updateModal'.$post->id_jabatan.'"><span class="material-icons">mode_edit</span></a>';
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
    /* end */
}