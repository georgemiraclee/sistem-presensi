<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class potongan_saldo extends CI_Controller
{
	private $file = 'appconfig/auto_respon.txt';
	
	function __construct()
	{
		parent::__construct();
        $this->load->library('Ceksession');
        $this->ceksession->login();
	}
	public function index()
	{
		$sess = $this->session->userdata('user');
        $id_channel = $sess['id_channel']; 
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
            redirect(base_url());exit();
        }
        $data['komponen_keterlambatan'] = $this->Db_select->select_all_where('tb_potongan_keterlambatan','id_channel='.$id_channel);
        $data['komponen_tidakhadir'] = $this->Db_select->select_all_where('tb_potongan_mangkir','id_channel='.$id_channel);
        $data['komponen_keluar'] = $this->Db_select->select_all_where('tb_potongan_keluar_jamkerja','id_channel = '.$id_channel);
        $data['komponenApel'] = $this->Db_select->select_all_where('tb_potongan_apel','id_channel='.$id_channel);
        $data['komponenPotongan'] = $this->Db_select->query('select besar_potongan from tb_potongan_batal_absensi where id_channel = '.$id_channel);
        $data['komponenLembur'] = $this->Db_select->query('select nominal, is_custom from tb_komponen_lembur where id_channel = '.$id_channel);
        if ($data['komponenLembur'] == null) {
            $data['komponenLembur'] = json_decode('{"nominal":"0","is_custom":"1"}') ;
        }        
        $data['list'] = '<input name="aktif" value="1" type="radio" id="radio_30" class="with-gap radio-col-red"/><label for="radio_30">Ya</label><input name="aktif" value="0" type="radio" id="radio_31" class="with-gap radio-col-pink" checked /><label for="radio_31">Tidak</label>';
        $data['list2'] = '<input name="aktif_lembur" value="1" type="radio" id="radio_322" class="with-gap radio-col-red"/><label for="radio_322">Ya</label><input name="aktif_lembur" value="0" type="radio" id="radio_333" class="with-gap radio-col-pink" checked /><label for="radio_333">Tidak</label>';
		$this->load->view('Administrator/header', $data);
		$this->load->view('Administrator/gaji');
		$this->load->view('Administrator/footer');
	}
	
	public function update()
	{
		$sess = $this->session->userdata('user');
        $id_channel=$sess['id_channel'];
    	$data['nama_komponen_pendapatan'] = $this->input->post('nama_komponen');
    	$data['tipe_pendapatan'] = $this->input->post('tipe_pendapatan');
        $where['id_komponen_pendapatan'] = $this->input->post('id_komponen');
    	$where['id_channel'] = $id_channel;
    	$update = $this->Db_dml->update('tb_komponen_pendapatan', $data, $where);
    	if ($update == 1) {
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
    public function update2()
    {
        $sess = $this->session->userdata('user');
        $id_channel=$sess['id_channel'];
        $data['durasi_keterlambatan'] = $this->input->post('durasi');
        $data['potongan_keterlambatan'] = $this->input->post('potongan');
        $where['id_keterlambatan'] = $this->input->post('id_keterlambatan');
        $where['id_channel'] = $id_channel;
        $update = $this->Db_dml->update('tb_potongan_keterlambatan', $data, $where);
        if ($update == 1) {
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
    public function update3()
    {
        $sess = $this->session->userdata('user');
        $id_channel=$sess['id_channel'];
        
        $potongan = $this->input->post('potongan');
        $data['besar_potongan'] = str_replace(',', '.', $potongan);
        $where['id_mangkir'] = $this->input->post('id_mangkir');
        $where['id_channel'] = $id_channel;
        $update = $this->Db_dml->update('tb_potongan_mangkir', $data, $where);
        if ($update == 1) {
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
    public function update4()
    {
        $sess = $this->session->userdata('user');
        $id_channel=$sess['id_channel'];
        $potongan = $this->input->post('potongan');
        $data['besar_potongan'] = str_replace(',', '.', $potongan);
        $where['id_meninggalkan_kantor'] = $this->input->post('id_meninggalkan_kantor');
        $where['id_channel'] = $id_channel;
        $update = $this->Db_dml->update('tb_potongan_keluar_jamkerja', $data, $where);
        if ($update == 1) {
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
    public function update6()
    {
        $sess = $this->session->userdata('user');
        $id_channel=$sess['id_channel'];
        $potongan = $this->input->post('potongan');
        $data['besar_potongan'] = str_replace(',', '.', $potongan);
        $where['id_potongan_apel'] = $this->input->post('id_potongan_apel');
        $where['id_channel'] = $id_channel;
        $update = $this->Db_dml->update('tb_potongan_apel', $data, $where);
        if ($update == 1) {
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
    public function deleteKeterlambatan()
    {
        $where['id_keterlambatan'] = $this->input->post('id_keterlambatan');
        $delete = $this->Db_dml->delete('tb_potongan_keterlambatan', $where);
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
    public function insert()
    {
    	$sess = $this->session->userdata('user');
        $id_channel=$sess['id_channel'];
    	$data['nama_komponen_pendapatan'] = $this->input->post('nama_komponen');
    	$data['tipe_pendapatan'] = $this->input->post('tipe_pendapatan');
    	$data['status_pendapatan'] = 'custom';
    	$insert = $this->Db_dml->normal_insert('tb_komponen_pendapatan', $data);
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
    public function insertPembatalan()
    {
        $sess = $this->session->userdata('user');
        $id_channel = $sess['id_channel'];
        $data['besar_potongan'] = $this->input->post('besar_potonganNew');
        $data['id_channel'] = $id_channel;
        $where['id_potongan_batal_absensi'] = 1;
        $insert = $this->Db_dml->update('tb_potongan_batal_absensi', $data, $where);
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
     public function insertLembur()
    {
        $sess = $this->session->userdata('user');
        $id_channel = $sess['id_channel'];
        $data['nominal'] = $this->input->post('nominal');
        $data['is_custom'] = $this->input->post('is_custom');
        $where['id_channel'] = $id_channel;
        $insert = $this->Db_dml->update('tb_komponen_lembur', $data, $where);
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
    public function insertKeterlambatan()
    {
         $sess = $this->session->userdata('user');
        $id_channel=$sess['id_channel'];
        $data['durasi_keterlambatan'] = $this->input->post('durasi');
        $data['id_channel']=$id_channel;
        $potongan = $this->input->post('potongan');
        $data['potongan_keterlambatan'] = str_replace(',', '.', $potongan);
        $insert = $this->Db_dml->normal_insert('tb_potongan_keterlambatan', $data);
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
    public function update_pengurangan()
    {
        $sess = $this->session->userdata('user');
        $data['potongan_keterlambatan'] = $this->input->post('aktif');
        $where['id_pengaturan'] = 1;
        $updateData = $this->Db_dml->update('tb_pengaturan', $data, $where);
        if ($updateData == 1) {
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
    public function update_lembur()
    {
        $sess = $this->session->userdata('user');
        $data['is_lembur'] = $this->input->post('aktif_lembur');
        $where['id_pengaturan'] = 1;
        $updateData = $this->Db_dml->update('tb_pengaturan', $data, $where);
        if ($updateData == 1) {
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
    public function insert_lembur()
    {
        $perhitungan_lembur = $this->input->post('perhitungan_lembur');
        if ($perhitungan_lembur == 3) {
            $data['nominal_lembur'] = $this->input->post('nominal');
        }
        $data['perhitungan_lembur'] = $perhitungan_lembur;
        $where['id_pengaturan'] = 1;
        $updatData = $this->Db_dml->update('tb_pengaturan', $data,$where);
        if ($updatData == 1) {
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