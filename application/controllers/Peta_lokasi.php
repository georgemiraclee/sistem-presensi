<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Peta_lokasi extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function showMaps($id_channel)
	{
		$data['map'] = $this->DataMaps();

		$this->load->view('Super_admin/header', $data);
		$this->load->view('Super_admin/maps');
		$this->load->view('Super_admin/footer');
	}

	public function DataMaps()
	{
        $query = 'select a.nama_user, a.foto_user, c.lat, c.lng, c.created_history_absensi from tb_user a join tb_absensi b on a.nip = b.nip join tb_history_absensi c on b.id_absensi = c.id_absensi where c.lat is not null and c.lng is not null';
        $query .= ' group by a.nip order by c.created_history_absensi desc';

		$getData = $this->db_select->query_all($query);

		foreach ($getData as $key => $value) {
			if ($value->foto_user == null || $value->foto_user == "") {
				$value->foto_user = "default_photo.jpg";
			}
			$day = date('d', strtotime($value->created_history_absensi));
			$years = date('Y', strtotime($value->created_history_absensi));
			$month = date('m', strtotime($value->created_history_absensi));
			$dayMonth = array(
				'01' => 'Januari',
				'02' => 'Februari',
				'03' => 'Maret',
				'04' => 'April',
				'05' => 'Mei',
				'06' => 'Juni',
				'07' => 'Juli',
				'08' => 'Agustus',
				'09' => 'September',
				'10' => 'Oktober',
				'11' => 'November',
				'12' => 'Desember'
			);
			$value->created_history_absensi = $day.' '.$dayMonth[$month].' '.$years;
		}
		
		return json_encode($getData);
	}
}