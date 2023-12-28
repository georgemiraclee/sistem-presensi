<?php defined('BASEPATH') OR exit('No direct script access allowed');
class dinas extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('ceksession');
		$this->ceksession->login();
	}
	public function index()
	{

		$sess = $this->session->userdata('user');
        if (!$sess['akses']) {
        	redirect(base_url());exit();
        }
		$parrent=$sess['id_user'];
		$akses_absensi = $this->Db_select->query_all('select *from tb_perjalanan j join tb_user k on j.user_id = k.user_id');
		// echo json_encode($akses_absensi);exit();
		$data['list_akses_absensi'] = "";
		foreach ($akses_absensi as $key => $value) {
			$location = json_decode($value->text_perjalanan);
			$lat=$location[0]->lat;
			$long = $location[0]->lng;
			$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false";
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_ENCODING, "");
			$curlData = curl_exec($curl);
			curl_close($curl);
			$address = json_decode($curlData);
			if ($address->results[0]->formatted_address) {
				$alamat = $address->results[0]->formatted_address;
			}else{
				$alamat = "-";
			}
			$data['list_akses_absensi'] .= "<tr>
                                                <td>".$value->nip."</td>
                                                <td>".$value->nama_user."</td>
                                                <td>".date('d M Y', strtotime($value->created_perjalanan))."</td>
                                                <td>".$alamat."</td>
                                                <td>
                                                    <a href='".base_url()."Leader/dinas/detail/".$value->id_perjalanan."' style='color: grey'><button class='btn btn-info'> Lihat Detail</button></a>
                                                </td>
                                            </tr>";
		}
		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/dinas');
		$this->load->view('SEKDA/footer');
	}
	public function detail($id){
		 $is = $this->Db_select->query('select *from tb_perjalanan where id_perjalanan='.$id);
		 $loc= $is->text_perjalanan;
		 $map =  json_decode($loc);
		 $data['line'] ="[";
		 foreach ($map as $key => $value) {
		 $data['line'] .="{lat:".$value->lat.",lng:".$value->lng."},";
		 }
		 $data['line'] .="]";



		 $data['map'] = $loc;
		$this->load->view('SEKDA/header', $data);
		$this->load->view('SEKDA/maps');
		$this->load->view('SEKDA/footer');
	}

}