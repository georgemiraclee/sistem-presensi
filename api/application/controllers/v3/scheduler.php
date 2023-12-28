<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class scheduler extends CI_Controller
{	
	function __construct()
	{
		parent::__construct();

		$this->load->library(array('global_lib'));
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
      foreach ($getData as $value) {
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

  public function tidakHadir()
  {
    $pegawai = $this->db_select->query_all('select a.user_id, b.id_channel from tb_user a join tb_unit b on a.id_unit = b.id_unit where a.is_admin = 0 and a.is_aktif = 1 and a.user_id not in (select user_id from tb_absensi where date_format(created_absensi,"%Y-%m-%d") = "'.date('Y-m-d').'")');
    if (count($pegawai)) {
      foreach ($pegawai as $item) {
        $cek_cuti = $this->db_select->query('select *from tb_pengajuan where user_id = "'.$item->user_id.'" and date_format(now(), "%Y-%m-%d") >= date_format(tanggal_awal_pengajuan, "%Y-%m-%d") and date_format(now(), "%Y-%m-%d") <= date_format(tanggal_akhir_pengajuan, "%Y-%m-%d")');
        if (!$cek_cuti) {
          /* get hari libut tanggal merah */
          $tanggalMerah = $this->db_select->query('select *from tb_event where id_channel = "'.$item->id_channel.'" and date_format(tanggal_event, "%Y-%m-%d") = date_format(now(), "%Y-%m-%d")');

          if (!$tanggalMerah) {
            $getPolaDefault = $this->db_select->select_where('tb_pola_kerja', ['id_channel' => $item->id_channel, 'is_default' => 1]);
            if ($getPolaDefault) {
              /* cek jam kerja */
              $pola_kekar = $this->db_select->query('select b.file_pola_kerja from tb_pola_user a join tb_pola_kerja b on a.id_pola_kerja = b.id_pola_kerja where a.user_id = '.$item->user_id.' and date_format(now(), "%Y-%m-%d") >= date_format(a.start_pola_kerja, "%Y-%m-%d") and date_format(now(), "%Y-%m-%d") <= date_format(a.end_pola_kerja, "%Y-%m-%d")');
              
              $file = '../appconfig/new/'.$getPolaDefault->file_pola_kerja;
              if ($pola_kekar) {
                $file = '../appconfig/new/'.$pola_kekar->file_pola_kerja;
              }
              
              $day = strtolower(date("l"));
              $jadwal = json_decode(file_get_contents($file))->jam_kerja;
              if ($jadwal) {
                $jadwal = $jadwal->$day;
                if ($jadwal->libur) {
                  $insert['user_id'] = $item->user_id;
                  $insert['status_absensi'] = 'Tidak Hadir';
                  $insert['day_off'] = 1;
                  $insert['day_off_desc'] = $day;
                  $this->db_dml->normal_insert('tb_absensi', $insert);
                } else {
                  $to = strtotime(date($jadwal->to));
                  $time = strtotime(date('H:i'));
                  
                  if (!$jadwal->libur && $time >= $to) {
                    $insert['user_id'] = $item->user_id;
                    $insert['status_absensi'] = 'Tidak Hadir';
                    $this->db_dml->normal_insert('tb_absensi', $insert);
                  }
                }                
              }
            }
          } else {
            $insert['user_id'] = $item->user_id;
            $insert['status_absensi'] = 'Tidak Hadir';
            $insert['day_off'] = 1;
            $insert['day_off_desc'] = $tanggalMerah->nama_event;
            $this->db_dml->normal_insert('tb_absensi', $insert);
          }
        }
      }
    }
  }

  public function changeNotificationStatus()
  {
    $getNotification = $this->db_select->query_all('select *from tb_notifikasi where created_at between created_at and date_sub(now(), INTERVAL 1 WEEK)');

    if ($getNotification) {
      foreach ($getNotification as $value) {
        $this->db_dml->update('tb_notifikasi', ['is_read' => 1], ['id_notifikasi' => $value->id_notifikasi]);
      }
    }
  }
}