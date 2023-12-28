<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
/**
* 
*/
class pendapatan_departemen extends CI_Controller
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
        $id_channel=$sess['id_channel'];
        
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
            redirect(base_url());exit();
        }
        
		$this->load->view('Administrator/header');
		$this->load->view('Administrator/komponen_departemen');
		$this->load->view('Administrator/footer');
    }
    /* end */

    /* detail komponen departemen */
    public function detail($id)
    {
        $sess = $this->session->userdata('user');
        $id_channel=$sess['id_channel'];
        // echo json_encode($id);exit();

        
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
            redirect(base_url());exit();
        }

        $getjabatan = $this->Db_select->query_all('SELECT id_jabatan, nama_jabatan FROM tb_user a join tb_jabatan b on a.jabatan = b.id_jabatan where id_unit = "'.$id.'" group by id_jabatan');

        $data['list'] = '';
       
        foreach ($getjabatan as $key => $values) {
            $getTJ = $this->Db_select->select_where('tb_tunjangan_jabatan','id_unit = '.$id.' and id_jabatan = '.$values->id_jabatan.' and nama_tunjangan = "Tunjangan Jabatan"');
            $getTP = $this->Db_select->select_where('tb_tunjangan_jabatan','id_unit = '.$id.' and id_jabatan = '.$values->id_jabatan.' and nama_tunjangan = "Tunjangan PAKDIN"');
            if ($getTP != null || $getTP != "") {
                $TP = $getTP->nominal;
            }else{
                $TP = 0;
            }
            if ($getTJ != null || $getTJ != "") {
                $TJ = $getTJ->nominal;
            }else{
                $TJ = 0;
            }

            $no = $key+1;
            $data['list'] .= '
                <tr>
                    <td>'.$no.'</td>
                    <td>'.$values->nama_jabatan.'</td>
                    <td>'.$TJ.'</td>
                    <td>'.$TP.'</td>
                    <td><a href="'.base_url().'Administrator/pendapatan_departemen/edit_komponen/'.$id.'/'.$values->id_jabatan.'" style="color: grey"> <button class="btn btn-info btn-xs"><i class="material-icons">input</i> <span class="icon-name"> Edit Komponen</span></button> </a>
                        
                        <button class="btn btn-warning btn-xs" onclick="addId('.$id.','.$values->id_jabatan.','.$TJ.','.$TP.')" data-toggle="modal" data-target="#defaultModal"><i class="material-icons">settings</i> <span class="icon-name">Setting Tunjangan </span></button>
                    </td>
                </tr>
            ';            
        }
        
        $this->load->view('Administrator/header', $data);
        $this->load->view('Administrator/jabatan_departemen');
        $this->load->view('Administrator/footer');
    }
    /* end */

    public function getData()
    {
        $sess = $this->session->userdata('user');
		$columns = array( 
			0 =>  'no', 
            1 =>  'nama_unit',
			2 =>  'umk',
			3 =>  'aksi'
        );
        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $order  = $columns[$this->input->post('order')[0]['column']];
        $dir    = $this->input->post('order')[0]['dir'];
        $query = 'select *from tb_unit where id_channel = "'.$sess['id_channel'].'" and id_unit = 56';
        $totalData = $this->Db_global->allposts_count_all($query);
        $totalFiltered = $totalData;
        if(empty($this->input->post('search')['value'])){
            $posts = $this->Db_global->allposts_all($query." order by ".$order." ".$dir." limit ".$start.",".$limit."");
        }else{
            $search = $this->input->post('search')['value'];
            $posts = $this->Db_global->posts_search_all($query." and nama_unit like '%".$search."%' order by ".$order." ".$dir." limit ".$start.",".$limit."");
            $totalFiltered = $this->Db_global->posts_search_count_all($query." and nama_unit like '%".$search."%' or is_aktif like '%".$search."%'");
        }
        $data = array();
        if(!empty($posts)){
        	foreach ($posts as $key => $post){
                $getUMK = $this->Db_select->select_where('tb_umk','id_unit ='.$post->id_unit);
                if ($getUMK == "" || $getUMK == null) {
                    $umk = 0 ;
                }else{
                    $umk = $getUMK->nominal;
                }
                $nestedData['no']  = $key+1;
                $nestedData['nama_unit']  = ucwords($post->nama_unit);
                $nestedData['umk']  ="Rp ".$umk;
                $nestedData['aksi']  = '
                    <a href="'.base_url().'Administrator/pendapatan_departemen/detail/'.$post->id_unit.'" style="color: grey"><button class="btn btn-info btn-xs"><i class="material-icons">open_in_new</i> <span class="icon-name">List jabatan</span></button></a>
                    <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#defaultModal" onclick="addId('.$post->id_unit.','.$umk.')"><i class="material-icons">settings</i> <span class="icon-name">Setting UMK </span></button>

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
    public function insert_umk()
    {
        $sess = $this->session->userdata('user');
        $id_channel = $sess['id_channel'];
        $data['nominal'] = $this->input->post('nominal');
        $data['id_unit'] = $this->input->post('id_unit');

        $cekData = $this->Db_select->select_where('tb_umk','id_unit ='.$this->input->post('id_unit'));
        if ($cekData == "" || $cekData == null) {
             $insertData = $this->Db_dml->normal_insert('tb_umk', $data);
            if ($insertData == 1) {
                $result['status'] = true;
                $result['message'] = 'Data UMK berhasil disimpan.';
                $result['data'] = array();
            }else{
                $result['status'] = false;
                $result['message'] = 'Data gagal disimpan.';
                $result['data'] = array();
            }
        }else{
            $where['id_unit'] = $this->input->post('id_unit');
            $datas['nominal'] = $this->input->post('nominal');
            $updateData = $this->Db_dml->update('tb_umk', $datas , $where);
            if ($updateData == 1) {
                $result['status'] = true;
                $result['message'] = 'Data UMK berhasil diubah.';
                $result['data'] = array();
            }else{
                $result['status'] = false;
                $result['message'] = 'Data gagal diubah.';
                $result['data'] = array();
            }

        }
       
       
        echo json_encode($result);exit();
    }
    public function edit_komponen($id,$idj)
    {
        $sess = $this->session->userdata('user');
        $id_channel=$sess['id_channel'];
        
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
            redirect(base_url());exit();
        }

        $data['data_komponen'] = $this->Db_select->select_all_where('tb_komponen_pendapatan','id_channel="'.$id_channel.'"');
        $komponen = $data['data_komponen'];
        $data['list_kom'] = '<optgroup label="Komponen">';
        foreach ($komponen as $key => $value) {
            $data['list_kom'] .= '
                <option value ="idk_'.$value->id_komponen_pendapatan.'">'.$value->nama_komponen_pendapatan.' (idk_'.$value->id_komponen_pendapatan.')</option>
            ';
        }
        $data['list_kom'].='</optgroup>
                            <optgroup label="Tunjangan Jabatan">';

        $getTunjangan = $this->Db_select->select_all_where('tb_tunjangan_jabatan','id_jabatan="'.$idj.'" and id_unit = '.$id);
        if ($getTunjangan != "" || $getTunjangan != null) {
            foreach ($getTunjangan as $key => $value) {
               $data['list_kom'] .= '
                    <option value ="idt_'.$value->id_tunjangan_jabatan.'">'.$value->nama_tunjangan.' (idt_'.$value->id_tunjangan_jabatan.')</option>
                ';
            
        }
            
        }
        $data['list_kom'].='</optgroup>
                            ';
        $getUMK = $this->Db_select->select_where('tb_umk','id_unit="'.$id.'"');
        if ($getUMK != "" || $getUMK != null) {
            $data['list_kom'] .= '
                <optgroup label="UMK">
                    <option value ="idu_'.$getUMK->id_umk.'">UMK (idu_'.$getUMK->id_umk.')</option>
                </optgroup>
            ';
        }
        $data['jabatan'] = $idj;
        $data['id_unit'] = $id;
        // echo json_encode($data);exit();
        
        $this->load->view('Administrator/header', $data);
        $this->load->view('Administrator/edit_komponen');
        $this->load->view('Administrator/footer');
    }
    public function insert_pendapatan()
    {
        $sess = $this->session->userdata('user');
        $id_channel = $sess['id_channel'];
        $id = $this->input->post('id_komponen_pendapatan');

        $data['id_jabatan'] = $this->input->post('id_jabatan');
        $data['id_unit'] = $this->input->post('id_unit');
        $data['id_komponen_pendapatan'] = $this->input->post('id_komponen_pendapatan');
        $data['is_formula'] = $this->input->post('is_formula');
        $data['nominal'] = $this->input->post('nominal');
        $data['formula'] = $this->input->post('displayResult'.$id);

        $cekData = $this->Db_select->select_where('tb_isi_komponen_pendapatan','id_komponen_pendapatan = '.$id);
        if ($cekData == "" || $cekData == null) {
            $insertData = $this->Db_dml->normal_insert('tb_isi_komponen_pendapatan', $data);
            if ($insertData == 1) {
                $result['status'] = true;
                $result['message'] = 'Data pendapatan komponen berhasil disimpan.';
                $result['data'] = array();
            }else{
                $result['status'] = false;
                $result['message'] = 'Data gagal disimpan.';
                $result['data'] = array();
            }
        }else{
            $where['id_komponen_pendapatan'] = $id;
            $updateData = $this->Db_dml->update('tb_isi_komponen_pendapatan', $data , $where);
            if ($updateData == 1) {
                $result['status'] = true;
                $result['message'] = 'Data pendapatan komponen berhasil disimpan.';
                $result['data'] = array();
            }else{
                $result['status'] = false;
                $result['message'] = 'Data gagal disimpan.';
                $result['data'] = array();
            }
        }

       
        
        echo json_encode($result);exit();
    }
    public function insert_tunjangan()
    {

        $data['nominal'] = $this->input->post('jabatan');
        $data['nama_tunjangan'] = "Tunjangan Jabatan";
        $data['id_unit'] = $this->input->post('id_unit');
        $data['id_jabatan'] = $this->input->post('id_jabatan');

        $data2['nominal'] = $this->input->post('pakdin');
        $data2['nama_tunjangan'] = "Tunjangan PAKDIN";
        $data2['id_unit'] = $this->input->post('id_unit');
        $data2['id_jabatan'] = $this->input->post('id_jabatan');

        $cekData = $this->Db_select->select_all_where('tb_tunjangan_jabatan','id_jabatan ='.$this->input->post('id_jabatan').' and id_unit = '.$this->input->post('id_unit') );
        // echo json_encode($cekData);exit();



        if ($cekData == "" || $cekData == null) {
             $insertData = $this->Db_dml->normal_insert('tb_tunjangan_jabatan', $data);
            if ($insertData == 1) {
                $insertData2 = $this->Db_dml->normal_insert('tb_tunjangan_jabatan', $data2);
                if ($insertData2 == 1) {
                    $result['status'] = true;
                    $result['message'] = 'Data Tunjangan berhasil disimpan.';
                    $result['data'] = array();
                }else{
                    $result['status'] = false;
                    $result['message'] = 'Data Tunjangan PAKDIN gagal disimpan.';
                    $result['data'] = array();
                }
            }else{
                $result['status'] = false;
                $result['message'] = 'Data Tunjangan jabatan gagal disimpan.';
                $result['data'] = array();
            }
        }else{
            foreach ($cekData as $key => $value) {
                if ($value->nama_tunjangan == "Tunjangan Jabatan") {
                    $where_update['id_tunjangan_jabatan'] = $value->id_tunjangan_jabatan;
                    $data_update['nominal'] = $this->input->post('jabatan');
                }else{
                    $where_update['id_tunjangan_jabatan'] = $value->id_tunjangan_jabatan;
                    $data_update['nominal'] = $this->input->post('pakdin');
                }

                $updateData = $this->Db_dml->update('tb_tunjangan_jabatan', $data_update, $where_update);
            }
            
            $result['status'] = true;
            $result['message'] = 'Data berhasil dirbuah.';
            $result['data'] = array();
        }       
        echo json_encode($result);exit();
    }
}