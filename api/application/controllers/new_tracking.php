<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class new_tracking extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();

		$this->load->library(array('global_lib', 'polygon','loghistory'));
        $this->global_lib->new_authentication();

        $this->polygon = new polygon;
        $this->loghistory = new loghistory;

        $this->result = array(
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null
        );
	}

	public function addTracking()
	{
		$require = array('nip', 'id_absensi');
        $this->global_lib->new_input($require);

        if (is_numeric($this->input->get('nip')) && is_numeric($this->input->get('id_absensi'))) {
        	$data = array();

	        // CEK JARINGAN
	        $where_jaringan = array(
	            'ssid_jaringan' => $this->input->get('ssid')
	            );
	        $cek_jaringan = $this->db_select->select_where('tb_jaringan', $where_jaringan);
	        if ($cek_jaringan) {
	            $data['id_jaringan'] = $cek_jaringan->id_jaringan;
	        }else{
	            $data['id_jaringan'] = null;
	        }

	        // CEK LOKASI
	        $cek_lokasi = $this->db_select->select_all('tb_lokasi');
	        if ($cek_lokasi) {
	            foreach ($cek_lokasi as $key => $value) {
	                $check = $this->polygon->get_position($this->input->get('lat'), $this->input->get('lng'), '', $value->url_file_lokasi);
	                if ($check) {
	                    $data['id_lokasi'] = $value->id_lokasi;
	                    break;
	                }else{
	                    $data['id_lokasi'] = null;
	                }
	            }
	        }

	        $data['id_absensi'] = $this->input->get('id_absensi');
	        $data['lat'] = $this->input->get('lat');
	        $data['lng'] = $this->input->get('lng');

	        $insert = $this->db_dml->normal_insert('tb_history_absensi', $data);

	        if ($insert == 1) {
	        	$this->result = array(
	                'status' => true,
	                'message' => 'Data berhasil disimpan',
	                'data' => $data
	            );
	        }else{
	        	$this->result = array(
	                'status' => false,
	                'message' => 'Data gagal disimpan',
	                'data' => null
	            );
	        }
        }else{
        	$this->result = array(
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            );
        }

        $this->loghistory->createLog($this->result);
        echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}
}