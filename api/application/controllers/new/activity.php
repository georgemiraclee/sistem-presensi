<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class activity extends CI_Controller
{	
	function __construct()
	{
		parent::__construct();

		$this->load->library(array('global_lib','loghistory'));
		$this->loghistory = new loghistory();

		/* pengecekan token expired */
		$this->global_lib->authentication();
	}

	/* fungsi untuk mengirim/menambahkan data activity kegiatan user/pegawai */
	public function send()
	{
		$require = array('keterangan', 'lat', 'lng');
    $this->global_lib->input($require);

    /* mengambil data user */
    $getUser = $this->global_lib->getDataUser($this->input->post('nip'));
    if ($getUser) {
      $data['keterangan'] = $this->input->post('keterangan');
      $data['lat'] = $this->input->post('lat');
      $data['lng'] = $this->input->post('lng');
      $data['private'] = $this->input->post('private');

      if ($_FILES) {
        if ($_FILES['userfile']['name'] != '') {
          $path = realpath('../assets/images/activity');
          $nameFile = $getUser->user_id."_".date(now())*1000;

          $config = array(
            'allowed_types' => 'jpg|jpeg|gif|png',
            'upload_path' => $path,
            'encrypt_name' => false,
            'file_name' => $nameFile
          );

          $this->upload->initialize($config);
          if ($this->upload->do_upload()) {
            $img_data = $this->upload->data();
          
            $data['file_activity'] = $img_data['file_name'];
          } else {
            $this->result = array(
              'status' => false,
              'message' => $this->upload->display_errors(),
              'data' => null
            );
          }
        }
      }

      $data['user_id'] = $getUser->user_id;

      $insertData = $this->db_dml->insert('daily_activity', $data);

      if ($insertData) {
        $this->result = array(
          'status' => true,
          'message' => 'Data berhasil disimpan',
          'data' => null
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
        'message' => 'Data user tidak ditemukan',
        'data' => null
      );
    }

    $this->loghistory->createLog($this->result);
    echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}

	/* fungsi untuk mengambil list data activity pegawai */
	public function listActivity()
	{
		$require = array('page');
    $this->global_lib->input($require);

    $page = $this->input->post('page');
    $limit = 15;

    if (is_numeric($page) && $page > 0) {
      $start = ($page - 1) * $limit;
      $getUser = $this->global_lib->getDataUser($this->input->post('nip'));

      $listActivity = $this->db_select->query_all('select *from daily_activity where user_id = "'.$getUser->user_id.'" order by id_daily_activity desc limit '.$start.','.$limit);
      $totalActivity = $this->db_select->query('select count(*) total from daily_activity where user_id = "'.$getUser->user_id.'"');

      if ($listActivity) {
        foreach ($listActivity as $key => $value) {
          if ($value->file_activity != null || $value->file_activity != "") {
            $value->file_activity = image_url()."/images/activity/".$value->file_activity;
          }
        }

        $this->result = array(
            'status' => true,
            'message' => 'Data ditemukan',
            'total' => $totalActivity->total,
            'data' => $listActivity
        );
      } else {
        $this->result = array(
              'status' => false,
              'message' => 'Data tidak ditemukan',
              'data' => null
          );
      }
    } else{
      $this->result = array(
            'status' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null
        );
    }

    $this->loghistory->createLog($this->result);
    echo json_encode($this->result, JSON_NUMERIC_CHECK);
	}

  public function timeline()
  {
    $require = array('page');
    $this->global_lib->input($require);

    $page = $this->input->post('page');
    $limit = 15;

    if (is_numeric($page) && $page > 0) {
      $start = ($page - 1) * $limit;
      $getUser = $this->global_lib->getDataUser($this->input->post('nip'));

      $listActivity = $this->db_select->query_all('select a.*, b.* from daily_activity a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where c.id_channel = "'.$getUser->id_channel.'" and a.private = 0 or (a.private = 1 and b.nip = '.$this->input->post('nip').') order by a.id_daily_activity desc limit '.$start.','.$limit);
      $totalActivity = $this->db_select->query('select count(*) total from daily_activity a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where c.id_channel = "'.$getUser->id_channel.'" and a.private = 0 or (a.private = 1 and b.nip = '.$this->input->post('nip').')');

      if ($listActivity) {
        foreach ($listActivity as $value) {
          if ($value->file_activity != null || $value->file_activity != "") {
            $value->file_activity = image_url()."images/activity/".$value->file_activity;
            $value->foto_user = image_url()."images/member-photos/".$value->foto_user;
          }
        }

        $this->result = array(
            'status' => true,
            'message' => 'Data ditemukan',
            'total' => $totalActivity->total,
            'data' => $listActivity
        );
      } else {
        $this->result = array(
              'status' => false,
              'message' => 'Data tidak ditemukan',
              'data' => null
          );
      }
    } else{
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