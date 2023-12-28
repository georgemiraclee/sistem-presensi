<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class unit extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library(array('global_lib','loghistory'));

		$this->loghistory = new loghistory;

        $this->result = array(
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null
        );
	}

	public function getUnit()
	{
		$unit = $this->db_select->query_all('select id_unit, nama_unit, icon_unit from tb_unit');

		if ($unit) {
			$this->result = array(
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $unit
            );
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