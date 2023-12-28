<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class absensi_lib extends CI_Controller {
    public function hari_libur($id_channel)
    {
        $tanggal_hari_ini = mdate("%Y-%m-%d", time());
        $cek_libur = $this->db_select->query('select *from tb_event where date_format(tanggal_event, "%Y-%m-%d") = "'.$tanggal_hari_ini.'" and id_channel = "'.$id_channel.'"');
        
        if ($cek_libur) {
            $this->result = array(
                'status' => false,
                'message' => 'Maaf anda tidak dapat absen pada hari libur.',
                'data' => null
            );

            echo json_encode($this->result); exit();
        }
    }

    public function getPolaUser($user_id)
    {
        /* get channel */
        $id_channel = $this->db_select->query('select *from tb_user a join tb_unit b on a.id_unit = b.id_unit where user_id ='.$user_id)->id_channel;

        $tglNow = date('Y-m-d');
        $cekPolaUser = $this->db_select->query('select *from tb_pola_user where user_id = "'.$user_id.'" and "'.$tglNow.'" between start_pola_kerja and end_pola_kerja');

        if ($cekPolaUser) {
            $getPola = $this->db_select->select_where('tb_pola_kerja','id_pola_kerja = "'.$cekPolaUser->id_pola_kerja.'"');
            $file = '../appconfig/new/'.$getPola->file_pola_kerja;
        }else{
            $wherea['id_channel'] = $id_channel;
            $wherea['is_default'] = 1;
            $getPola = $this->db_select->select_where('tb_pola_kerja', $wherea);
            $file = '../appconfig/new/'.$getPola->file_pola_kerja;
        }

        $data['getPola'] = $getPola;
        $data['file'] = $file;
        return $data;
    }

    public function getDispensasi($user_id)
    {
        $tanggalSkrg = mdate("%Y-%m-%d", time());
        $cekDispensasi = $this->db_select->select_where('tb_dispensasi', 'tanggal_dispensasi = "'.$tanggalSkrg.'" and user_id = "'.$user_id.'"');

        if ($cekDispensasi) {
            return true;
        }else{
            return false;
        }
    }

    public function getStatusAbsensi($getPola, $jadwal)
    {
        if ($getPola->toleransi_keterlambatan == 1) {
            $dispensasiKeterlambatan = $getPola->waktu_toleransi_keterlambatan;
            if ($dispensasiKeterlambatan == null || $dispensasiKeterlambatan == "") {
                $dispensasiKeterlambatan = 0;
            }
        }else{
            $dispensasiKeterlambatan = 0;
        }

        $day = strtolower(date("l"));
        $jadwal = $jadwal->$day;
        $newJamMasuk = $jadwal->from;
        $time = strtotime($newJamMasuk);
        $endTime = date("H:i", strtotime('+'.$dispensasiKeterlambatan.' minutes', $time));
        if (date('H:i', strtotime(mdate("%Y-%m-%d %H:%i:%s", time()))) > $endTime) {
            return 'Terlambat';
        }else{
            return 'Tepat Waktu';
        }
    }
}