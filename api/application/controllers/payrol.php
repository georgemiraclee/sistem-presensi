<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class payrol extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lembur($idLembur)
    {
        if (is_numeric($idLembur)) {
            /* get data lembur */
            $getLembur = $this->db_select->query('select a.id_lembur, a.lama_lembur, a.tanggal_lembur, a.user_id, c.id_channel from tb_lembur a join tb_user b on a.user_id = b.user_id join tb_unit c on b.id_unit = c.id_unit where a.id_lembur = "'.$idLembur.'"');
            
            if ($getLembur) {
                /* check tanggal libur atau bukan */
                $tanggalLembur = mdate("%Y-%m-%d", strtotime($getLembur->tanggal_lembur));
                
                /* get hari kerja */
                $jadwal = $this->getPolaKerja($getLembur->user_id, $tanggalLembur, $getLembur->id_channel)['pola'];

                /* get data pengaturan lembur */
                $lemburSetting = $this->db_select->select_where('tb_komponen_lembur', 'id_channel = "'.$getLembur->id_channel.'"');

                if ($lemburSetting) {
                    if ($lemburSetting->is_custom) {
                        /* ambil perhitungan lembur custom */
                        $this->perhitunganCustom();
                    }else{
                        /* check tanggal merah */
                        if($this->tanggalMerah($tanggalLembur, $getLembur->id_channel, $getLembur->user_id)){
                            /* ambil perhitungan lembur di hari libur */
                            $this->perhitunganLibur($jadwal->lama_hari_libur, $getLembur->lama_lembur, $getLembur->user_id);
                        }else{
                            /* ambil perhitungan lembur di hari kerja */
                            $this->perhitungan($getLembur->lama_lembur, $getLembur->user_id);
                        }
                    }
                }
            }else{
                echo 'false';
            }
        }else{
            echo 'false';
        }
    }

    public function perhitunganLibur($hariKerja, $lamaLembur, $userId)
    {
        /* hitung upah lembur perjam */
        $upahPerJam = $this->hitungUpah($userId);
        $totalJumlah = 0;
        /* start perhitungan lembur di hari libur */
        if ($hariKerja == 5) {
            /* Untuk perusahaan dengan 5 hari kerja, rate adalah 2x upah sejam untuk 8 jam pertama, 3x upah sejam untuk jam ke-9, dan 4x upah sejam untuk jam ke-10 dan ke-11 */
            $countLama = 1;
            for ($i=0; $i < $lamaLembur ; $i++) { 
                if ($countLama <= 8) {
                    /* 2x */
                    $hitung = floor(2*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }elseif($countLama == 9){
                    /* 3x */
                    $hitung = floor(3*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }elseif ($countLama >= 10) {
                    /* 4x */
                    $hitung = floor(3*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }
                $countLama++;
            }
        }elseif ($hariKerja > 5) {
            /* Untuk perusahaan dengan 6 hari kerja, rate adalah 2x upah sejam untuk 7 jam pertama, 3x upah sejam untuk jam ke-8, dan 4x upah sejam untuk jam ke-9 dan ke-10 */
            $countLama = 1;
            for ($i=0; $i < $lamaLembur ; $i++) { 
                if ($countLama <= 7) {
                    /* 2x */
                    $hitung = floor(2*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }elseif($countLama == 8){
                    /* 3x */
                    $hitung = floor(3*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }elseif ($countLama >= 9) {
                    /* 4x */
                    $hitung = floor(4*$upahPerJam);
                    $totalJumlah = $totalJumlah+$hitung;
                }
                $countLama++;
            }
        }

        return $totalJumlah;
    }

    public function perhitungan($lamaLembur, $userId)
    {
        /* hitung upah lembur perjam */
        $upahPerJam = $this->hitungUpah($userId);
        $totalJumlah = 0;
        /* start perhitungan lembur di hari kerja */
        $countLama = 1;
        for ($i=0; $i < $lamaLembur; $i++) { 
            /* Untuk lembur pada hari kerja, rate upah lembur adalah 1,5x upah sejam pada jam pertama lembur dan 2x upah sejam pada jam seterusnya */
            if ($countLama == 1) {
                /* 1.5x */
                $hitung = floor(1.5*$upahPerJam);
                $totalJumlah = $totalJumlah+$hitung;
            }elseif ($countLama >= 2) {
                /* 2x */
                $hitung = floor(2*$upahPerJam);
                $totalJumlah = $totalJumlah+$hitung;
            }
            $countLama++;
        }
    }

    public function hitungUpah($userId)
    {
        $getUser = $this->db_select->select_where('tb_user', 'user_id = "'.$userId.'"');

        if ($getUser) {
            /* hitung upah sejam gapok x 1/173 */
            $upahLembur = floor($getUser->gaji_pokok*1/173);

            return $upahLembur;
        }else{
            return 0;
        }
    }

    public function tanggalMerah($tanggal, $idChannel, $userId)
    {
        $cek_libur = $this->db_select->query('select *from tb_event where date_format(tanggal_event, "%Y-%m-%d") = "'.$tanggal.'" and id_channel = "'.$idChannel.'"');
        if ($cek_libur) {
            $isLibur = 1;
        }else{
            $isLibur = 0;
        }

        $jadwal = $this->getPolaKerja($userId, $tanggal, $idChannel)['jadwal'];
        $day = strtolower(date("l", strtotime($tanggal)));
        $jadwal = $jadwal->$day;

        if ($jadwal->libur) {
            $isLibur = 1;
        }else{
            $isLibur = 0;
        }

        if ($isLibur) {
            return true;
        }else{
            return false;
        }
    }

    public function getPolaKerja($userId, $tanggal, $idChannel)
    {
        $cekPolaUser = $this->db_select->query('select *from tb_pola_user where user_id = "'.$userId.'" and "'.$tanggal.'" between start_pola_kerja and end_pola_kerja');

        if ($cekPolaUser) {
            $getPola = $this->db_select->select_where('tb_pola_kerja','id_pola_kerja = "'.$cekPolaUser->id_pola_kerja.'"');
            $file = '../appconfig/new/'.$getPola->file_pola_kerja;
        }else{
            $where['id_channel'] = $idChannel;
            $where['is_default'] = 1;
            $getPola = $this->db_select->select_where('tb_pola_kerja', $where);
            $file = '../appconfig/new/'.$getPola->file_pola_kerja;
        }

        $jadwal['jadwal'] = json_decode(file_get_contents($file))->jam_kerja;
        $jadwal['pola'] = $getPola;

        return $jadwal;
    }
}