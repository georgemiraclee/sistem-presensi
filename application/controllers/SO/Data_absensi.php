<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit', '128M');
/**
* 
*/
class data_absensi extends CI_Controller
{ 
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('Ceksession', 'global_lib'));
        $this->ceksession->login();

        $this->global_lib = new global_lib;
    }

    public function index()
    {
         $sess = $this->session->userdata('user');
        if ($sess['akses'] =="admin_channel"||$sess['akses'] =="sekda"||$sess['akses'] =="staff") {
        redirect(base_url());exit();
        }
    	$filter = array();
    	$sess = $this->session->userdata('user');
        $parrent=$sess['id_user'];
		$data['skpd'] = $this->Db_select->query_all('select *from tb_unit order by nama_unit');
        $data['jabatan'] = $this->Db_select->query_all('select *from tb_jabatan where is_aktif = 1 order by nama_jabatan asc');


        //filter data
        $query = "select * from tb_absensi j join tb_user c on j.user_id = c.user_id where id_parent = ".$parrent." ";
        if ($this->input->get('skpd')) {
            $filter['skpd'] = $this->input->get('skpd');
            $query .= " and c.id_unit in(".$this->input->get('skpd').")";
        }
        if ($this->input->get('jabatan')) {
            $filter['jabatan'] = $this->input->get('jabatan');
            $filter['jabatan'] = explode(',', $filter['jabatan']);
            $jabatan = "";
            foreach ($filter['jabatan'] as $key => $value) {
                $jabatan .= ",'$value'";
            }
            $jabatan = substr($jabatan, 1);
            $query .= " and c.jabatan in(".$jabatan.")";
        }
        if ($this->input->get('jenkel')) {
            $filter['jenkel'] = $this->input->get('jenkel');
            $filter['jenkel'] = explode(',', $filter['jenkel']);
            $jenkel = "";
            foreach ($filter['jenkel'] as $key => $value) {
                $jenkel .= ",'$value'";
            }
            $jenkel = substr($jenkel, 1);
            $query .= " and c.jenis_kelamin in(".$jenkel.")";
        }
        if ($this->input->get('status')) {
            $filter['status'] = $this->input->get('status');
            $filter['status'] = explode(',', $filter['status']);
            $jenkel = "";
            foreach ($filter['status'] as $key => $value) {
                $jenkel .= ",'$value'";
            }
            $jenkel = substr($jenkel, 1);
            $query .= " and status_absensi in(".$jenkel.")";
        }
        if ($this->input->get('dari')) {
            $filter['dari'] = $this->input->get('dari');
            $query .= " and date(created_absensi)  >= '".$this->input->get('dari')."'";
        }else{
        }
        if ($this->input->get('sampai')) {
            $filter['sampai'] = $this->input->get('sampai');
            $query .= " and date(created_absensi) <= '".$this->input->get('sampai')."' ";
        }

        $getData = $query.' and is_admin = "0" limit 100';
        $datas = $this->Db_select->query_all($getData);


        $datas2 = $this->Db_select->select_all('tb_absensi');
        $data['table']="";
            // echo json_encode($query);exit();

            $datas = $this->Db_select->query_all($getData);

		foreach ($datas as $key => $value) {
			if ($value->waktu_istirahat==null||$value->waktu_istirahat=='') {
				$istirahat='-';
			}else{
				$istirahat= date('h:i', strtotime($value->waktu_istirahat));
			}
			if ($value->waktu_kembali==null||$value->waktu_kembali=='') {
				$kembali='-';
			}else{
				$kembali= date('h:i', strtotime($value->waktu_kembali));
			}
			if ($value->waktu_pulang==null||$value->waktu_pulang=='') {
				$pulang='-';
			}else{
				$pulang= date('h:i', strtotime($value->waktu_pulang));
			}

            $selectBatal = $this->Db_select->select_where('tb_pembatalan_absensi','id_absensi = '.$value->id_absensi.'');

            if ($selectBatal) {
                $batal = "Dibatalkan";
            }else{
                $batal = '<a href="'.base_url().'SO/data_absensi/batalkan/'.$value->id_absensi.' "><button class="btn bg-indigo btn-xs float-right" type="button">
                                Batalkan
                             </button></a>';
            }

            $potongan_apel=$this->Db_select->query('select total_potongan from tb_hstry_potongan_apel where user_id = '.$value->user_id.'');
             if ($potongan_apel!= null) {
                $apel_potongan = $potongan_apel->total_potongan;
            }else{
                $apel_potongan="0";
            }
            $potongan_batal=$this->Db_select->query('select total_potongan from tb_hstry_potongan_batal_absensi where user_id = '.$value->user_id.'');
            if ($potongan_batal!= null) {
                $batal_potongan = $potongan_batal->total_potongan;
            }else{
                $batal_potongan="0";
            }
            $potongan_mabal=$this->Db_select->query('select total_potongan from tb_hstry_potongan_keluar_jamkerja where user_id = '.$value->user_id.'');
            if ($potongan_mabal!= null) {
                $mabal_potongan = $potongan_mabal->total_potongan;
            }else{
                $mabal_potongan="0";
            }
            $potongan_telat=$this->Db_select->query('select total_potongan from tb_hstry_potongan_keterlambatan where user_id = '.$value->user_id.'');
            if ($potongan_telat!= null) {
                $telat_potongan = $potongan_telat->total_potongan;
            }else{
                $telat_potongan="0";
            }
             $potongan_alpa=$this->Db_select->query('select total_potongan from tb_hstry_potongan_mangkir where user_id = '.$value->user_id.'');
            if ($potongan_alpa!= null) {
            $alpa_potongan = $potongan_alpa->total_potongan;
            }else{
                $alpa_potongan="0";
            }
            
            $all_potongan = $apel_potongan + $batal_potongan + $mabal_potongan + $telat_potongan + $alpa_potongan;

			$data['table'].="
			<tr>
						<td>".$value->nip."</td>
						<td>".$value->nama_user."</td>
                        <td>".date('d-m-Y', strtotime($value->created_absensi))."</td>
                        <td>".date('h:i', strtotime($value->waktu_datang))."</td>
                        <td>".$istirahat."</td>
                        <td>".$kembali."</td>
                        <td>".$pulang."</td>
                        <td>".$value->status_absensi."</td>
                        <td>Rp ".$all_potongan."</td>
                        <td>".$batal."</td>
            </tr>

			";
		}

		$this->load->view('SO/header', $data);
		$this->load->view('SO/data_absensi', $data);
		$this->load->view('SO/footer');

    }
    public function batalkan($id)
    {
        $insert['id_absensi'] = $id;
        $selectUser = $this->Db_select->select_where('tb_absensi', $insert);
        $selectNewUser = $this->Db_select->select_where('tb_user', 'user_id = "'.$selectUser->user_id.'"');

        $sess = $this->session->userdata('user');

        if (count($insert) > 0) {
            $insertData = $this->Db_dml->normal_insert('tb_pembatalan_absensi', $insert);

            if ($insertData) {
                $this->potonganBatalAbsen($selectNewUser->user_id, $selectNewUser->saldo);
                $this->global_lib->send_notification_user($selectUser->user_id, 'pembatalan_absensi');

                // FCM
                $message = "Absen Anda Telah Dibatalkan";
                $this->global_lib->sendFCM('Pembatalan Absensi', $message, $selectUser->user_id);
                
                $pesan = "<div class='alert alert-success alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Absen Berhasil Dibatalkan</div>";

                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/data_absensi");exit();
            }else{
                $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Absen Gagal Dibatalkan</div>";

                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/data_absensi");exit();
            }
        }else{
            $pesan = "<div class='alert alert-danger alert-dismissable' style='margin-top:10px;'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>Data Gagal Disimpan</div>";

                $this->session->set_flashdata('pesan', $pesan);
                 redirect(base_url()."Administrator/data_absensi");exit();
        }

        echo json_encode($result);
    }

    public function potonganBatalAbsen($user_id, $saldo)
    {
        $selectPotongan = $this->Db_select->select_where('tb_potongan_batal_absensi', 'id_potongan_batal_absensi = 1');

        $pengurangan = $selectPotongan->besar_potongan*$saldo/100;
        $insert['id_potongan_batal_absensi'] = 1;
        $insert['user_id'] = $user_id;
        $insert['total_potongan'] = $pengurangan;

        $this->Db_dml->normal_insert('tb_hstry_potongan_batal_absensi', $insert);
    }
}