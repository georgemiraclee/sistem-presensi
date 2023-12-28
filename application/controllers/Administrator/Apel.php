<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class apel extends CI_Controller
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
		$data['data_apel'] = $this->Db_select->select_all_where('tb_apel', 'id_channel='.$id_channel);
		$data['data_area'] = $this->Db_select->select_all_where('tb_lokasi','id_channel='.$id_channel);

		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/apel');
		$this->load->view('Administrator/footer');
	}
	public function insert()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
        $tgl = $this->input->post('tanggal_apel');
		$insert = array();
        $insert['nama_apel'] = $this->input->post('nama_apel');
        $insert['tanggal_apel'] = $tgl;
        $insert['jam_mulai'] = $this->input->post('jam_mulai');
        $insert['durasi_absen'] = $this->input->post('durasi_absen');
        $insert['id_lokasi'] = $this->input->post('id_lokasi');
        $insert['deskripsi_apel'] = $this->input->post('deskripsi_apel');
        $insert['id_channel'] = $id_channel;
		if (count($insert) > 0) {
			$insertData = $this->Db_dml->normal_insert('tb_apel', $insert);
			if ($insertData) {
				 $pesan = "<div class='alert alert-success alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Berhasil Disimpan</div>";
                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/apel");exit();
			}else{
				 $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Gagal Disimpan</div>";
                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/apel");exit();
			}
		}else{
			 $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Gagal Disimpan</div>";
                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/apel");exit();
		}
		echo json_encode($result);
	}
	public function delete()
	{
		$where['id_apel'] = $this->input->post('id_apel');
		$delete = $this->Db_dml->delete('tb_apel', $where);
		if ($delete == 1) {
			 $pesan = "<div class='alert alert-success alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Berhasil Dihapus.</div>";
                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/apel");exit();
		}else{
			 $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Gagal Dihapus.</div>";
                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/apel");exit();
		}
		echo json_encode($result);
	}
	public function update()
	{
		$sess = $this->session->userdata('user');
		$where['id_apel'] = $this->input->post('id_apel');
		$update = array();
		if ($this->input->post('nama_apel')) {
		    $update['nama_apel'] = $this->input->post('nama_apel');
            $update['tanggal_apel'] = $this->input->post('tanggal_apel');
            $update['jam_mulai'] = $this->input->post('jam_mulai');
            $update['id_lokasi'] = $this->input->post('id_lokasi');
            $update['deskripsi_apel'] = $this->input->post('deskripsi_apel');
		}
		$updateData = $this->Db_dml->update('tb_apel', $update, $where);
        // echo json_encode($updateData);exit();
        if ($updateData) {
            $pesan = "<div class='alert alert-success alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Berhasil Diubah</div>";
            $this->session->set_flashdata('pesan', $pesan);
         	redirect(base_url()."Administrator/apel");exit();
        }else{
            $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Gagal Diubah</div>";
                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/apel");exit();
        }
		echo json_encode($result);
	}
	public function update_status()
	{
		$sess = $this->session->userdata('user');
		$where['id_jabatan'] = $this->input->post('id_jabatan');
		$update['is_aktif'] = $this->input->post('is_aktif');
		$updateData = $this->Db_dml->update('tb_jabatan', $update, $where);
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
	function get_data_user($value = null)
    {
    	$sess = $this->session->userdata('user');
		$id_channel= $sess['id_channel'];
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
        	redirect(base_url());exit();
    	}

        $tb= 'tb_apel';
        $wr= 'id_channel='.$id_channel ;
        $fld =  array(null,'nama_apel','tanggal_apel','jam_mulai','durasi_absen','id_lokasi','deskripsi_apel',null);
        $src = array('nama_apel');
        $ordr = array('created_apel' => 'desc');;
        $list = $this->Db_datatable->get_datatables2($tb,$wr,$fld,$src,$ordr);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
	       
			$selectUser = $this->Db_select->select_where('tb_lokasi','id_lokasi = "'.$field->id_lokasi.'"');
			$lokasi = $selectUser->nama_lokasi;
			 
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nama_apel;
            $row[] = date('d-m-Y', strtotime($field->tanggal_apel));
            $row[] = $field->jam_mulai;
            $row[] = $field->durasi_absen;
            $row[] = $lokasi;
            $row[] = $field->deskripsi_apel;
            $row[] = '<a href="#" style="color: grey" data-toggle="modal" data-target="#updateModal'.$field->id_apel.'"><span class="material-icons">mode_edit</span></a>
                      <a href="#" data-toggle="tooltip" data-placement="top"  id="hapus'.$no.'" title="Hapus Event" data-type="ajax-loader" onclick="hapus('.$field->id_apel.')"><span class="material-icons col-grey"  style="font-size: 20px;">delete</span></a>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Db_datatable->count_all2($tb,$wr,$fld,$src,$ordr),
            "recordsFiltered" => $this->Db_datatable->count_filtered2($tb,$wr,$fld,$src,$ordr),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

}