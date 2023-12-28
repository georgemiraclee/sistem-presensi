<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class fitur extends CI_Controller
{
	public function selfie_validation()
	{
		$data['image']= "selfie_val.jpg";
		$data['keterangan']= "Fitur ini pengguna dapat melakukan absensi masuk dengan mengambil foto selfie terlebih dahulu. Sehingga masing-masing atasan/pimpinan perusahaan dapat lebih memastikan kebenaran absensi dari setiap orang pegawainya.";
		$data['judul'] = "Selfie Validation";
		$this->load->view('fitur',$data);
	}
	public function location_validation()
	{
		$data['image']= "location_val.jpg";
		$data['keterangan']= "Dalam aplikasi pressensi pun setiap perusahaan dapat menentukan titik lokasi dalam bentuk radius untuk melakukan absensi. Misalnya hanya di area perusahaan/kantor saja yang dapat melakukan absensi, diluar lokasi yang telah di tentukan pegawai tidak dapat melakukan absensi.";
		$data['judul'] = "Location Validation";

		$this->load->view('fitur',$data);
	}     
	public function network_validation()
	{
		$data['image']= "network_val.jpg";
		$data['keterangan']= "Serupa dengan Location Validation, fitur ini pun dapat mensetting jaringan/wifi yang dapat digunakan sebagai absensi. Misalnya, hanya jaringan/wifi kantor saja yang dapat digunakan sebagai sarana absensi. Sehingga apabila pengguna/pegawai yang tidak terkoneksi dengan jaringan yang telah di tentukan, proses absensi tidak dapat dilakukan.";
		$data['judul'] = "Network Validation";

		$this->load->view('fitur',$data);
	}
	public function realtime_tracking()
	{
		$data['image']= "tracking_low.jpg";
		$data['keterangan']= "Dengan fitur ini, aplikasi dapat memungkinkan merekam setiap pergerakan pegawai selama masih dalam jam kerja yang ditentukan. Sehingga atasan/pimpinan perusahaan dapat melihat kemana saja pegawai tersebut selama jam kerja.";
		$data['judul'] = "Realtime Tracking";

		$this->load->view('fitur',$data);
	}         
	
}