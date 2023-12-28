<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class bpjs extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('ceksession');
		$this->load->model('Db_datatable');

		$this->ceksession->login();
	}
	public function index()
	{
		$sess = $this->session->userdata('user');
		$id_channel = $sess['id_channel'];
        if ($sess['akses'] !="admin_channel" && $sess['akses'] !="admin_utama") {
        	redirect(base_url());exit();
        }
		$que = $this->Db_select->select_where('pengaturan_payroll','id_channel='.$id_channel);

		$user = $this->Db_select->query_all('select a.nama_user,a.gaji_pokok from tb_user a join tb_unit b on a.id_unit = b.id_unit where id_channel='.$id_channel.' and a.is_aktif = 1');

		if ($que == null || $que == "") {
        	redirect(base_url()."Administrator/setting_payroll");exit();
		}

		//pph21 start


		$data['table']="";
		foreach ($user as $key => $value) {
			if ($value->gaji_pokok == null || $value->gaji_pokok == "") {
				$value->gaji_pokok =0;
			}
			$gaji_pokok = $value->gaji_pokok * 12;
			if($gaji_pokok > 54000000){
				$jkk = ($que->jkk*$value->gaji_pokok/100)*12;
				$jkm = ($que->jkm*$value->gaji_pokok/100)*12;	
				$kes_p = ($que->jk_perusahaan*$value->gaji_pokok/100)*12;	
				$jht_p = ($que->jht_perusahaan*$value->gaji_pokok/100)*12;
				$jp_p = 	($que->jp_perusahaan*$value->gaji_pokok/100)*12;

				//bruto
				$bruto = $gaji_pokok + $jkk + $jkm + $kes_p + $jht_p + $jp_p;
				//potongan jabatan
				$bj = 5 * $bruto/100;

				$jht = ($que->jht*$value->gaji_pokok/100) * 12;
				$jp = ($que->jp*$value->gaji_pokok/100) * 12;
				$kes = ($que->jk*$value->gaji_pokok/100) * 12;
				//neto
				$neto = $bruto - $bj - $jht - $jp - $kes;

				$pkp = $neto - $que->ptkp_pribadi;

				$ratusan = substr($pkp, -3);
				$pkp_bulat = $pkp - $ratusan;

				$pph_taunan = 5* $pkp_bulat/100;

				$pph_bulanan = $pph_taunan / 12;

			}else{

				$neto = "0";
				$bruto = "0";
				$pkp_bulat = "0";
				$pph_taunan = "0";
				$pph_bulanan = "0";			
			}

			$data['table'] .=  "
				<tr>
                	<td> ".$value->nama_user."</td>
                	<td> Rp.".number_format($gaji_pokok,0,'.','.')."</td>
                	<td> Rp.".number_format($bruto,0,'.','.')."</td>
                	<td> Rp.".number_format($neto,0,'.','.')."</td>
                	<td> Rp.".number_format($que->ptkp_pribadi,0,'.','.')."</td>
                	<td> Rp.".number_format($pkp_bulat,0,'.','.')."</td>
                	<td> Rp.".number_format($pph_taunan,0,'.','.')."</td>
                	<td> Rp.".number_format(ceil($pph_bulanan),0,'.','.')."</td>
                </tr>
			";
		}
		//pph21 end
		//bpjs kesehatan start
		$data['table_bpjs']="";
		foreach ($user as $key => $value) {
			if ($value->gaji_pokok == null || $value->gaji_pokok == "") {
				$value->gaji_pokok =0;
			}
			if ($value->gaji_pokok > $que->nilai_pengali) {
				$pengali = $que->nilai_pengali;
			}
			elseif ($value->gaji_pokok < $que->upah_minimum) {
				$pengali = $que->upah_minimum;
			}
			else {
				$pengali =  $value->gaji_pokok;
			}

			$jk_perusahaan_persen = $que->jk_perusahaan."%";
			$jk_persen = $que->jk."%";

			$potongan_perusahaan = $que->jk_perusahaan*$pengali/100;
			$potongan_individu =  $que->jk*$pengali/100;

			$data['table_bpjs'].="
					<tr>
                	<td> ".$value->nama_user."</td>
                	<td> Rp.".number_format($value->gaji_pokok,0,'.','.')."</td>
                	<td> Rp.".number_format($potongan_perusahaan,0,'.','.')."</td>
                	<td> ".$jk_perusahaan_persen."</td>
                	<td> Rp.".number_format($potongan_individu,0,'.','.')."</td>
                	<td> ".$jk_persen."</td>
                </tr>
			";


		}
		//bpjs kesehatan end

		//bpjs ketenagakerjaan
		$data['table_kt']="";
		foreach ($user as $key => $value) {
			if ($value->gaji_pokok == null || $value->gaji_pokok == "") {
				$value->gaji_pokok =0;
			}
			if ($value->gaji_pokok > $que->nilai_pengali) {
				$pengali = $que->nilai_pengali;
			}
			elseif ($value->gaji_pokok < $que->upah_minimum) {
				$pengali = $que->upah_minimum;
			}
			else {
				$pengali =  $value->gaji_pokok;
			}

			$jkk_persen = $que->jkk."%";
			$jkk = $que->jkk * $pengali/100;

			$jkm_persen = $que->jkm."%";
			$jkm = $que->jkm * $pengali/100;

			$jhtp_persen = $que->jht_perusahaan."%";
			$jhtp = $que->jht_perusahaan * $pengali/100;

			$jht_persen = $que->jht."%";
			$jht = $que->jht * $pengali/100;

			$jpp_persen = $que->jp_perusahaan."%";
			$jpp = $que->jp_perusahaan * $pengali/100;

			$jp_persen = $que->jp."%";
			$jp = $que->jp * $pengali/100;
			$data['table_kt'].="
					<tr>
                	<td> ".$value->nama_user."</td>
                	<td> Rp.".number_format($value->gaji_pokok,0,'.','.')."</td>
                	<td> Rp.".number_format($jkk,0,'.','.')."</td>
                	<td> ".$jkk_persen."</td>
                	<td> Rp.".number_format($jkm,0,'.','.')."</td>
                	<td> ".$jkm_persen."</td>
                	<td> Rp.".number_format($jhtp,0,'.','.')."</td>
                	<td> ".$jhtp_persen."</td>
                	<td> Rp.".number_format($jht,0,'.','.')."</td>
                	<td> ".$jht_persen."</td>
                	<td> Rp.".number_format($jpp,0,'.','.')."</td>
                	<td> ".$jpp_persen."</td>
                	<td> Rp.".number_format($jp,0,'.','.')."</td>
                	<td> ".$jp_persen."</td>
                </tr>
			";
		}

		//bpjs ketenagakerjaan end



		$this->load->view('Administrator/header');
		$this->load->view('Administrator/bpjs',$data);
		$this->load->view('Administrator/footer');

	}
}