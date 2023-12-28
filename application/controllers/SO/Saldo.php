<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Saldo extends CI_Controller
{ 
    function __construct()
    {
        parent::__construct();
        $this->load->library('Ceksession');
        $this->ceksession->login();
    }

    public function index()
    {   
         $sess = $this->session->userdata('user');
        if ($sess['akses'] =="admin_channel"||$sess['akses'] =="sekda"||$sess['akses'] =="staff") {
        redirect(base_url());exit();
        }
        $data['sess'] = $this->session->userdata('user');
        $sess = $this->session->userdata('user');
        $user=$sess['id_user'];
        $data_user=$this->Db_select->query('select * from tb_user where user_id = '.$user.'');
                $data['saldo'] = $data_user->saldo;

       $absen =$this->Db_select->query_all('select * from tb_absensi where user_id = '.$user.'');
             // echo json_encode($absen);exit();
        $data['tabel']='';
        $total_potongan = 0;
       foreach ($absen as $key => $value) {
        $tanggal=date('Y-m-d', strtotime($value->created_absensi));

        $potongan_apel=$this->Db_select->query('select total_potongan from tb_hstry_potongan_apel where user_id = '.$value->user_id.' and  date(created_hstry_potongan_apel) = "'.$tanggal.'" ');
             if ($potongan_apel!= null) {
                $apel_potongan = $potongan_apel->total_potongan;
                // echo json_encode($potongan_apel); exit();
            }else{
                $apel_potongan="0";
            }
            $potongan_batal=$this->Db_select->query('select total_potongan from tb_hstry_potongan_batal_absensi where user_id = '.$value->user_id.'  and  date(created_hstry_potongan_batal_absensi) = "'.$tanggal.'"');
            if ($potongan_batal!= null) {
                $batal_potongan = $potongan_batal->total_potongan;
            }else{
                $batal_potongan="0";
            }
            $potongan_mabal=$this->Db_select->query('select total_potongan from tb_hstry_potongan_keluar_jamkerja where user_id = '.$value->user_id.'  and  date(created_hstry_meninggalkan_kantor) = "'.$tanggal.'" ');
            if ($potongan_mabal!= null) {
                $mabal_potongan = $potongan_mabal->total_potongan;
            }else{
                $mabal_potongan="0";
            }
            $potongan_telat=$this->Db_select->query('select total_potongan from tb_hstry_potongan_keterlambatan where user_id = '.$value->user_id.'  and  date(created_hstry_keterlambatan) = "'.$tanggal.'"');
            
            if ($potongan_telat!= null) {
                $telat_potongan = $potongan_telat->total_potongan;
            }else{
                $telat_potongan="0";
            }
             $potongan_alpa=$this->Db_select->query('select total_potongan from tb_hstry_potongan_mangkir where user_id = '.$value->user_id.'  and  date(created_hstry_mangkir) = "'.$tanggal.'"');
            if ($potongan_alpa!= null) {
            $alpa_potongan = $potongan_alpa->total_potongan;
            }else{
                $alpa_potongan="0";
            }
            $all_potongan = $apel_potongan + $batal_potongan + $mabal_potongan + $telat_potongan + $alpa_potongan;
            
            $total_potongan = $total_potongan+$all_potongan;

            if ($all_potongan > 0) {
                $data['tabel'] .='       <tr>
                                            <th>'.$tanggal.'</th>
                                            <th>'.$apel_potongan.'</th>
                                            <th>'.$telat_potongan.'</th>
                                            <th>'.$batal_potongan.'</th>
                                            <th>'.$mabal_potongan.'</th>
                                            <th>'.$alpa_potongan.'</th>
                                            <th>'.$all_potongan.'</th>
                                        </tr>

                ';
            }   
       }
       $data['semua_potongan'] = $total_potongan;
         
		$this->load->view('SO/header', $data);
		$this->load->view('SO/saldo');
		$this->load->view('SO/footer');
    }
   
}