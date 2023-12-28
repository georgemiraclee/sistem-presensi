<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class notification extends CI_Controller
{
  function __construct() {
    parent::__construct();
    $this->load->library(array('global_lib','loghistory'));
    $this->global_lib->authentication();
    $this->loghistory = new loghistory;
    $this->result = array(
      'status' => false,
      'message' => 'Data tidak ditemukan',
      'data' => null
    );
  }

  public function changeStatusNotification()
  {
    $require = array('id_notifikasi');
    $this->global_lib->input($require);

    $getData = $this->db_select->select_where('tb_notifikasi', 'id_notifikasi = ' . $this->input->post('id_notifikasi'));

    if ($getData) {
      /* melakukan */
      $update['is_read'] = 1;
      $where['id_notifikasi'] = $this->input->post('id_notifikasi');

      $updateData = $this->db_dml->update('tb_notifikasi', $update, $where);
      if ($updateData) {
        $this->result = array(
          'status' => true,
          'message' => 'Data berhasil disimpan',
          'data' => array()
        );
      } else {
        $this->result = array(
          'status' => false,
          'message' => 'Data gagal disimpan',
          'data' => array()
        );
      }
    } else {
      $this->result = array(
        'status' => false,
        'message' => 'Data tidak ditemukan',
        'data' => array()
      );
    }
    
    $this->loghistory->createLog($this->result);
    echo json_encode($this->result);
  }
}