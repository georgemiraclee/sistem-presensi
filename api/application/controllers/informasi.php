<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 
*/
class informasi extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
        $this->load->library(array('global_lib', 'loghistory'));
        $this->global_lib->authentication();
        $this->loghistory = new loghistory;
        $this->result = array(
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null
        );
	}

	public function getInformasi()
	{
		$require = array('page');
    $this->global_lib->input($require);
    $page = $this->input->post('page');
    $limit = 15;
    if (is_numeric($page) && $page > 0) {
      $idChannel = $this->global_lib->getChannel($this->input->post('nip'));

      $start = ($page - 1) * $limit;
      $timeline = $this->db_select->query_all('select *, date_format(created_pengumuman,"%d-%m-%Y %r") tanggal from tb_pengumuman where is_aktif = 1 and id_channel = "'.$idChannel.'" and now() between start_date and end_date order by created_pengumuman desc limit '.$start.','.$limit);
      $total = $this->db_select->query('select count(id_pengumuman) total from tb_pengumuman where is_aktif = 1 and id_channel = "'.$idChannel.'" and now() between start_date and end_date');

      if ($timeline) {
        foreach ($timeline as $value) {
          if ($value->url_file_pengumuman != null || $value->url_file_pengumuman != "") {
            $path = realpath('../assets/images/pengumuman/' . $value->url_file_pengumuman);

            if (file_exists($path)) {
              $value->url_file_pengumuman = image_url()."images/pengumuman/".$value->url_file_pengumuman;
            }else{
              $value->url_file_pengumuman = image_url().'images/pengumuman/default.jpg';
            }
          }else{
            $value->url_file_pengumuman = image_url().'images/pengumuman/default.jpg';
          }
          $value->foto_channel = image_url()."images/icon/pressensi.png";
          $value->deskripsi_pengumuman = utf8_encode($value->deskripsi_pengumuman);
          $value->judul_pengumuman = utf8_encode($value->judul_pengumuman);
          unset($value->created_pengumuman);
          unset($value->is_aktif);
          unset($value->id_channel);
        }
        $data['informasi'] = $timeline;
        $data['total'] = $total->total;
        $this->result = array(
          'status' => true,
          'message' => 'Data ditemukan',
          'data' => $data
        );
      }else{
        $this->result = array(
          'status' => false,
          'message' => 'Belum ada informasi',
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

    echo json_encode($this->result);
		$this->loghistory->createLog($this->result);
	}

	public function getDetailInformasi()
	{
		$require = array('id_pengumuman');
    $this->global_lib->input($require);
    $getInformasi = $this->db_select->query('select *, date_format(created_pengumuman, "%d-%m-%Y %r") tanggal from tb_pengumuman where id_pengumuman = "'.$this->input->post('id_pengumuman').'"');

    if ($getInformasi) {
      if ($getInformasi->url_file_pengumuman != null || $getInformasi->url_file_pengumuman != "") {
        $path = realpath('../assets/images/pengumuman/' . $getInformasi->url_file_pengumuman);

            if (file_exists($path)) {
                $getInformasi->url_file_pengumuman = image_url()."images/pengumuman/".$getInformasi->url_file_pengumuman;
            }else{
                $getInformasi->url_file_pengumuman = image_url().'images/pengumuman/default.jpg';
            }
      }else{
        $getInformasi->url_file_pengumuman = null;
      }
      unset($getInformasi->id_pengumuman);
      unset($getInformasi->is_aktif);
      unset($getInformasi->id_channel);
      unset($getInformasi->created_pengumuman);
      $this->result = array(
          'status' => true,
          'message' => 'Data ditemukan',
          'data' => $getInformasi
      );
    }else{
      $this->result = array(
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null
        );
    }
    echo json_encode($this->result);
		$this->loghistory->createLog($this->result);
	}
}