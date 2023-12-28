<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class cront extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	/* cron job untuk menonaktifkan pegawai yang resign */
	public function resign()
  {
    /* get tanggal hari ini */
    $tglSkrg = date("Y-m-d");

    /* ambil data pegawai resign yang sesuai dengan tanggal skrg */
    $getData = $this->db_select->query_all('select *from tb_history_pengunduran_diri where date(tanggal_pengunduran_diri) = "'.$tglSkrg.'" and is_cron = 0');

    if ($getData) {
      /* lakukan proses update status pegawai menjadi nonaktif */
      foreach ($getData as $key => $value) {
        $where['user_id'] = $value->user_id;
        $update['is_aktif'] = 0;
        $this->db_dml->update('tb_user', $update, $where);

        /* update is_cron di table tb_history_pengunduran_diri menjadi 1 */
        $whereCron['id_pengunduran_diri'] = $value->id_pengunduran_diri;
        $updateCron['is_cron'] = 1;
        $this->db_dml->update('tb_history_pengunduran_diri', $updateCron, $whereCron);
      }
    }
  }
}