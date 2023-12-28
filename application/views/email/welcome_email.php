<!DOCTYPE html>
<html>
<head>
	<title></title>

	<style>
		.button {
		  background-color: #4CAF50; /* Green */
		  border: none;
		  color: white;
		  padding: 15px 32px;
		  text-align: center;
		  text-decoration: none;
		  display: inline-block;
		  font-size: 16px;
		  color: white;
		}
	</style>
</head>
<body>
	Yth. Bapak/Ibu <?php echo $nama;?>, (<?php echo $perusahaan;?>)<br><br>
	Selamat! Anda telah mendapatkan akses untuk akun demo Aplikasi pressensi. Akun ini akan aktif selama 14 hari. Berikut ini adalah detail akun demo Anda:<br><br>
	Username:<br>
	<a href='mailto:<?php echo $email;?>'><?php echo $email;?></a>
	<br><br>
	Silakan klik tombol konfirmasi dibawah ini untuk mengaktifkan akun Anda.<br><br>
	<center><a href="https://pressensi.com/login" class="button">Konfirmasi</a></center><br><br>
	Jika Anda memiliki pertanyaan, silakan hubungi kami (022) 2010606. Terima Kasih.<br><br>
	Hormat kami,<br><br>
	pressensi Team. <br><br>

	<b>pressensi.com</b><br>
	Jl. Setra Sari Indah No 4 Bandung 40152<br>
	(022) 2010606 | pressensi.com
</body>
</html>