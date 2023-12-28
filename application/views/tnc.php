<!DOCTYPE html>
<html lang="en">
  <head>
    <title>pressensi | Absensi Online & Monitoring</title>
    <meta charset="utf-8">
    <meta name="author" content="pressensiapp">
    <meta name="description" content="pressensi | Absensi online & monitoring data pegawai, merupakan aplikasi absensi online modern dimana pengguna dapat menggunakan fitur-fitur istimewa seperti absensi dengan 3 validasi(face recognition , lokasi & jaringan), monitoring data pegawai secara realtime, perhitungan jam kerja dan lembur, pengajuan cuti dan sakit, perhitungan gaji dan tunjangan serta perhitungan BPJS&PPH21">
    <meta name="keywords" content="pressensi, Absensi Online, absen online, kelola karyawan, perhitungan BPJS, Software Payroll, pph21, penggajian, aplikasi hris, sdm, aplikasi sdm, aplikasi hrd berbasis web, THR, perhitungan THR, perhitungan pph 21, perhitungan lembur, cuti, karyawan,  absensi, pegawai, hris indonesia, manajemen sumber daya manusia, aplikasi hris gratis">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Fav icon-->
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/icon/4.png">

    <!-- Font Family-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/font-awesome.min.css">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/bootstrap.css">
    <!-- Animation CSS-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/animate.min.css">
    <!-- Owl carousel css-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/owl.theme.default.min.css">
    <!-- Form validation css-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/validation.css">
    <!-- Style css-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/style.css">
    <!-- Responsive css-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/responsive.css">
    <!-- color variation-->
    <link id="color" rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/color/color-1.css" media="screen">
    <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url();?>assets/js/plugins/sweetalert/dist/sweetalert.css"/>
    <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/sweetalert/dist/sweetalert.min.js"></script>
  </head>
  <style type="text/css">
    a.kur{
      color: #3b5998;
    }
    a.kur:hover{
      color: #6AC160;
    }
    .color-picker{
        visibility: hidden;
      }
  </style>
  <body>
    <!-- Preloader-->
    <div class="loader-wrapper">
      <div class="loader"></div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light theme-nav fixed-top">
      <div class="container"><a class="navbar-brand" href="<?php echo base_url();?>"><img src="<?php echo base_url();?>assets/lp_2019/images/logow.png" style="max-width: 142px;" alt="logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse default-nav" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto" id="mymenu">
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>"><?php echo $this->lang->line('home');?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>#about"><?php echo $this->lang->line('about');?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>#feature"><?php echo $this->lang->line('feature');?></a></li>
            <!-- <li class="nav-item"><a class="nav-link" href="#screenshot">Video</a></li> -->
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>#testimonial" data-menuanchor="testimonial"><?php echo $this->lang->line('download');?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>login"><?php echo $this->lang->line('login');?></a></li>

            <!-- set button trial -->
            <li class="nav-item">
              <a class="btn btn-primary btn-sm" style="border-color: white; border: 0;" href="<?php echo base_url();?>Trial"><?php echo $this->lang->line('free_trial');?></a>
            </li>

            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>lang_setter/set_to/indonesia"><span class="flag-icon flag-icon-id"></span></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>lang_setter/set_to/english"><span class="flag-icon flag-icon-gb"></span></a></li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Preloader end-->
    <section class="authentication-form" >
        <div class="innerpage-decor" style=" margin-top: 90px">
        </div>
        <div>
            <div class="row">
                <div class="col-md-12">
                    <hr>
                    <h2 class="title text-center">Syarat & Ketentuan</h2>
                    <hr>
                    <div class="container">
                        <p>
                            Sebelum menggunakan Layanan yang ada di pressensi, pastikan Anda membaca dengan cermat dan hati-hati Syarat dan Ketentuan penggunaan dan layanan pressensi (“Syarat dan Ketentuan”) yang ada di halaman ini.<br><br>
                            Dengan mengakses atau menggunakan Layanan aplikasi pressensi, Anda setuju bahwa Anda telah membaca, memahami, menerima dan menyetujui serta terikat seccara hukum pada Syarat dan Ketentuan ini dan dokumen-dokumen lain sehubungan dengan itu. <br><br>
                            Syarat dan Ketentuan dalam dokumen ini menggambarkan dan menetapkan ketentuan yang mengendalikan serta mengatur hubungan hukum antara PT. Folka Indonesia Teknologi sebagai penyedia Layanan pressensi dan Anda atau User sebagai pengguna Aplikasi pressensi.
                        </p>
                            
                        <h3 style="padding-left: 130px">I. INFORMASI UMUM</h3>
                        <p>
                            1. pressensi adalah aplikasi Absensi Online dan Monitoring Pegawai berbasis mobile yang menyediakan fitur validasi dan tracking terbaik serta perhitungan gaji dan potongan pegawai.
                        </p>
                        <p>
                            2. Penyedia Layanan adalah PT. Folka Indonesia Teknologi, sebuah perseroan terbatas yang didirikan berdasarkan hukum Republik Indonesia.
                        </p>
                        <p>
                            3. Layanan yang tersedia dalam aplikasi pressensi terdiri dari: pengelolaan data karyawan, pencatatan absensi karyawan, pemantauan dan pengelolaan shift dan jadwal kerja, penghitungan penggajian dan Tunjangan Hari Raya karyawan, pengelolaan pengajuan dan izin karyawan, pengelolaan pencatatan dan penghitungan cuti, pencatatan pinjaman karyawan, penghitungan dan pembetulan PPh 21, penghitungan BPJS karyawan, penyediaan portal karyawan, pengelolaan pembukuan gaji dan multiapproval.  
                        </p>
                        <p>4. Hak-hak Akses dalam Layanan pressensi meliputi;</p>
                        <p style="padding-left: 40px">a. Hak Akses sebagai Administrator, yaitu hak yang diberikan kepada anggota User untuk memiliki akses penuh terhadap pengelolaan pressensi;</p>
                        <p style="padding-left: 40px">b. Hak Akses sebagai Administrator, yaitu hak yang diberikan kepada anggota User oleh Super Admin untuk memiliki akses penuh terhadap pengelolaan pressensi, namun tidak memiliki kewenangan untuk menunjuk Administrator lainnya.</p>
                        <p style="padding-left: 40px">c. Hak Akses sebagai Administrator Pendukung, yaitu hak yang diberikan kepada anggota User oleh Admin untuk memiliki akses terhadap fitur pressensi kecuali fitur Karir dan Remunerasi, Penggajian, Gaji dan LTHR, dan BPJS dan tidak memiliki kewenangan untuk menunjuk Administrator lainnya. </p>
                        <p>5. User atau Anda merupakan pengguna Layanan dan aplikasi pressensi sejauh sesuai dengan konteksnya terdiri dari Perusahaan, yaitu merupakan badan usaha yang mendaftarkan badan usahanya pada sistem pressensi.</p>
                        <p>6. Hak Kekayaan Intelektual termasuk namun tidak terbatas pada hak cipta dan merek, baik yang terdaftar maupun tidak.</p>
                        <p>7. Waktu Kerja adalah hari saat Penyedia Layanan melakukan kegiatan operasionalnya, yaitu Hari Senin hingga Jumat, kecuali libur nasional/publik, pukul 08.00- 18.00 WIB.</p>
                        <p>8. Tagihan adalah dokumen yang menuliskan sejumlah biaya yang harus dibayar oleh Perusahaan untuk penggunaan Layanan yang terdiri dari:</p>
                        <p style="padding-left: 40px">a. Tagihan Layanan Awal adalah tagihan yang harus dibayarkan User pada saat pertama kali mendaftar di pressensi sesuai dengan paket, kuota, dan jangka waktu Layanan yang dipilih oleh User atau 5 (lima) hari sebelum jangka waktu Layanan Percobaan habis sebagai bentuk penawaran kepada User untuk berlangganan pressensi; </p>
                        <p style="padding-left: 40px">b. Tagihan Perpanjangan adalah tagihan yang diberikan selambat-lambatnya 5 (lima) hari sebelum jangka waktu Layanan yang digunakan User berakhir. Apabila User melakukan pembayaran atas Tagihan Perpanjangan, berarti User setuju untuk memperpanjang penggunaan Layanan untuk 1 (satu) bulan berikutnya.</p>
                        <p style="padding-left: 40px">c. Tagihan Naikkan Layanan adalah tagihan yang diberikan Penyedia Layanan dan harus dibayar oleh User apabila User bermaksud meningkatkan paket layanan pressensi, yaitu semula Paket Standard menjadi Paket Sukses sesuai dengan jumlah Kuota Personalia;</p>
                        <p style="padding-left: 40px">d. Tagihan Tambah Kuota adalah Tagihan yang diberikan Penyedia Layanan dan harus dibayar oleh User apabila User bermaksud menambah karyawan yang didaftarkan ke dalam aplikasi pressensi</p>
                        <p>10. Tagihan Tambah Kuota adalah Tagihan yang diberikan Penyedia Layanan dan harus dibayar oleh User apabila User bermaksud menambah karyawan yang didaftarkan ke dalam aplikasi pressensi. Apabila Perusahaan menambah karyawan di tengah periode (sebelum jatuh tempo) maka tagihan yang dikenakan untuk tambahan kuota tersebut dihitung secara pro rata.</p>
                        <p>11. Kuota Personalia adalah jumlah maksimal Karyawan yang dapat didaftarkan oleh Perusahaan ke dalam akun pressensi.</p>
                        <h3 style="padding-left: 130px">II. PERSETUJUAN</h3>
                        <p>1. Anda dengan ini menyatakan dan menjamin bahwa:</p>
                        <p style="padding-left: 40px">a. Anda telah membaca dan menyetujui Syarat dan Ketentuan, Kebijakan Privasi dan Ketentuan Biaya kami;</p>
                        <p style="padding-left: 40px">b. Anda akan menggunakan dan/atau mengakses Layanan, Konten Pengguna Kami hanya untuk tujuan yang sah;</p>
                        <p style="padding-left: 40px">c. Semua informasi yang Anda berikan kepada Penyedia Layanan (termasuk namun tidak terbatas pada informasi data pribadi dan kontak) adalah akurat dan lengkap;</p>
                        <p style="padding-left: 40px">d. Penyedia Layanan berhak untuk mengubah, memodifikasi, menunda atau menghentikan semua atau sebagian dari Situs atau Layanan kapanpun. Penyedia Layanan juga dapat menentukan batas pada fitur-fitur tertentu atau membatasi akses Anda berdasarkan keputusan internal Penyedia Layanan atau peraturan terkait dengan penyelenggaraan pressensi dan Layanan. </p>
                        <p style="padding-left: 40px">e. Penyedia Layanan dari waktu ke waktu, tanpa memberikan alasan atau pemberitahuan apapun sebelumnya, dapat memperbarui, mengubah, menunda, menghentikan dan/atau mengakhiri semua konten pada Situs, secara keseluruhan atau sebagian, termasuk namun tidak terbatas pada desain, teks, gambar grafis, foto, gambar, citra, video, Aplikasi, musik, suara dan file lain, tarif, biaya, kuotasi, data historis, grafik, statistik, artikel, informasi kontak kami, setiap informasi lain, dan pemilihan serta pengaturannya.</p>
                        <p>2. Anda dengan ini menyatakan dan menjamin bahwa:</p>
                        <p style="padding-left: 40px">a. Anda bertanggung jawab untuk membuat semua pengaturan yang diperlukan agar Anda memiliki akses ke pressensi. Anda juga bertanggung jawab untuk memastikan bahwa semua orang yang mengakses pressensi melalui koneksi internet Anda mengetahui dan mematuhi Syarat dan Ketentuan ini serta ketentuan lain yang berlaku.</p>              
                        <p style="padding-left: 40px">b. Internet dapat mengalami gangguan, pemadaman transmisi, penundaan transmisi karena lalu lintas internet atau transmisi data yang salah sebagaimana hal-hal tersebut melekat pada internet yang bersifat terbuka bagi publik. </p>
                        <h3 style="padding-left: 130px">III. PENGGUNAAN LAYANAN DAN APLIKASI</h3>
                        <p>Dengan Anda melanjutkan penggunaan atau pengaksesan pressensi, berarti Anda telah menyatakan dan menjamin kepada Penyedia Layanan bahwa:</p>
                        <p>1. Anda hanya diperkenankan untuk mengakses dan/atau menggunakan pressensi untuk keperluan Perusahaan dan non-komersil, yang berarti bahwa layanan ini hanya boleh diakses dan digunakan secara langsung oleh Perusahaan yang sedang mencari produk atau Layanan untuk membantu mengelola sumber daya manusia. </p>
                        <p>2. Anda tidak diperkenankan menggunakan pressensi untuk hal sebagai berikut:</p>
                        <p style="padding-left: 40px">a. untuk menyakiti, menyiksa, mempermalukan, memfitnah, mencemarkan nama baik, mengancam, mengintimidasi atau mengganggu orang atau bisnis lain, atau apapun yang melanggar privasi atau yang Penyedia Layanan anggap penuh kebencian, tidak senonoh, tidak patut, tidak pantas, atau diskriminatif;</p>
                        <p style="padding-left: 40px">b. dengan cara yang melawan hukum, menipu, atau tindakan komersil;</p>
                        <p style="padding-left: 40px">c. melanggar atau menyalahi hak orang lain, termasuk tanpa terkecuali hak paten, merek dagang, hak cipta, rahasia dagang, publisitas, dan hak milik lainnya;</p>
                        <p style="padding-left: 40px">d. membuat, memeriksa, memperbarui, mengubah atau memperbaiki database, rekaman, atau direktori Anda ataupun orang lain;</p>
                        <p style="padding-left: 40px">e. menggunakan kode komputer otomatis, proses atau sistem “screen scraping”, program, robot, net crawler, spider, pemrosesan data, trawling atau kode komputer; dan/atau</p>
                        <p style="padding-left: 40px">f. melanggar Syarat dan Ketentuan, atau ketentuan lainnya yang ada pada pressensi. </p>
                        <p>3. Penyedia Layanan tidak bertanggung jawab atas kehilangan akibat kegagalan dalam mengakses pressensi atau metode penggunaan pressensi yang di luar kendali kami. </p>
                        <h3 style="padding-left: 130px">IV. LAYANAN PERCOBAAN</h3>
                        <p>1. Perusahaan dapat memperoleh uji coba akses penggunaan pressensi bebas biaya (“Layanan Percobaan”) untuk maksimal selama 7 (tujuh) hari kalender dengan ketentuan 10 user. </p>
                        <p>2. Layanan Percobaan diberikan kepada Perusahaan yang disetujui oleh Penyedia Layanan. </p>
                        <p>3. Layanan Percobaan ini diberikan dengan maksud untuk membantu Perusahaan mengambil keputusan apakah akan menjadi User berlangganan pressensi. </p>
                        <p>4.  Selambat-lambatnya 2 (dua) hari sebelum jangka waktu Layanan Percobaan berakhir, Penyedia Layanan akan mengirimkan Tagihan Layanan Awal kepada Perusahaan sebagai bentuk penawaran kepada Perusahaan untuk menjadi User berlangganan. Apabila Perusahaan membayar tagihan tersebut, berarti Perusahaan telah setuju untuk menjadi User berlangganan pressensi dan terikat pada Syarat dan Ketentuan ini.  </p>
                        <h3 style="padding-left: 130px">V. JANGKA WAKTU BERLANGGANAN</h3>
                        <p>1. Perusahaan dapat berlangganan pressensi untuk jangka waktu 1 (satu) tahun.</p>
                        <p>2. Untuk UMKM dapat berlangganan pressensi untuk jangka waktu 1 (satu) bulan.</p>
                        <h3 style="padding-left: 130px">VI. KEWAJIBAN USER</h3>
                        <p>1. Kewajiban Pembayaran Layanan</p>
                        <p style="padding-left: 40px">a. Tagihan untuk Biaya layanan pressensi akan dibuat setiap bulan, dimulai satu bulan dari tanggal User mulai berlangganan pressensi.</p>
                        <p style="padding-left: 40px">b. Perusahaan wajib membayarkan seluruh biaya yang ditagihkan dengan jumlah Karyawan yang didaftarkan pada pressensi.</p>
                        <p style="padding-left: 40px">c. Skema pembayaran yang dapat dipilih oleh Perusahaan adalah:</p>
                        <p style="padding-left: 60px">1) pembayaran untuk satu (satu) tahun, Untuk skema pembayaran ini User harus menandatangani Perjanjian Kerja Sama dengan Penyedia Layanan dan membayar keseluruhan pada saat penandatanganan perjanjian. </p>
                        <p style="padding-left: 60px">2) pembayaran untuk 1 (satu) bulan. Untuk skema pembayaran ini User bisa melakukan pembayaran langsung melalui aplikasi pressensi.</p>
                        <p>2. Kewajiban User untuk menghormati Hak Kekayaan Intelektual pressensi</p>
                        <p>Semua Hak Kekayaan Intelektual dalam pressensi dimiliki oleh Penyedia Layanan. Semua informasi dan bahan, termasuk namun tidak terbatas pada: Aplikasi, teks, data, grafik, citra, merek dagang, logo, ikon, kode html dan kode lainnya dalam  aplikasi pressensi  dilarang untuk dipublikasikan, dimodifikasi, disalin, direproduksi, digandakan atau diubah dengan cara apa pun tanpa izin yang dinyatakan secara tertulis oleh Penyedia Layanan. Jika User melanggar hak-hak ini, Penyedia Layanan berhak untuk membuat gugatan perdata untuk jumlah keseluruhan kerusakan atau kerugian yang diderita. Pelanggaran-pelanggaran ini juga bisa merupakan tindak pidana sebagaimana diatur oleh peraturan perundang-undangan yang berlaku.</p>
                        <p>3. Ganti Rugi</p>
                        <p>User setuju untuk mengganti rugi Penyedia Layanan dan petugasnya terhadap semua kerugian, pajak, biaya, biaya hukum, dan kewajiban (yang ada saat ini, di masa yang akan datang, kontingensi, atau apapun yang berbasis ganti rugi), yang diderita oleh Penyedia Layanan sebagai hasil atau hubungan dari pelanggaran Syarat dan Ketentuan ini atau dokumen terkait lainnya yang dilakukan oleh User dan/atau langkah-langkah yang dilakukan oleh Penyedia Layanan ketika terjadi pelanggaran Syarat dan Ketentuan ini atau dokumen terkait lainnya.</p>
                        <h3 style="padding-left: 130px">VII. PENGHENTIAN LAYANAN</h3>
                        <p>1. Penyedia Layanan dapat menghentikan pemberian Layanan dan akses pressensi kepada User atau mengakhiri Perjanjian Kerja Sama dengan User dengan alasan antara lain sebagai berikut:</p>
                        <p style="padding-left: 40px">a. User tidak melakukan pembayaran Tagihan Perpanjangan; </p>
                        <p style="padding-left: 40px">b. User melanggar sebagian atau seluruh isi Syarat dan Ketentuan ini; dan/atau</p>
                        <p style="padding-left: 40px">c. User melanggar sebagian atau seluruh dokumen yang berlaku lainnya.</p>
                        <p>2. User dapat menghentikan penggunaan Layanan dan aplikasi pressensi dengan membuat surat penghentian Layanan yang ditujukan kepada pressensi pada alamat:</p>
                        <p>Jl. Setra Sari Indah no.4 Bandung 40152 / 022 (2010606)</p>
                        <p>3. Apabila Penyedia Layanan melakukan penghentian Layanan atau penutupan akses pressensi kepada User yang disebabkan User tidak melakukan pembayaran biaya Layanan pressensi sesuai dengan jumlah kuota setelah tagihan pembayaran jatuh tempo, status akun User akan menjadi: </p>
                        <p style="padding-left: 40px">a. Tidak aktif.</p>
                        <p style="padding-left: 40px">Status yang diberikan oleh Penyedia Layanan apabila User tidak membayar tagihan perpanjang layanan pressensi setelah 10 (sepuluh) hingga 79 hari kalender sejak tanggal jatuh tempo. Setelah User tidak membayar lewat dari 10 (sepuluh) hari dari tanggal jatuh tempo, maka User masih dapat mengakses akun User namun tidak dapat menggunakan seluruh fitur yang ada. Namun, apabila di antara 10 (sepuluh) hingga 79 hari tersebut User melakukan pembayaran perpanjangan, User dapat menggunakan fitur kembali. </p>
                        <p style="padding-left: 40px">b. Ditutup.</p>
                        <p style="padding-left: 40px">Status yang diberikan oleh Penyedia Layanan apabila User tidak membayar tagihan perpanjang Layanan pressensi  setelah 79 hari kalender sejak tanggal jatuh tempo. Setelah User tidak membayar lewat dari 79 hari dari tanggal jatuh tempo, maka akun User ditutup secara permanen. Apabila User ingin menggunakan Layanan dan aplikasi pressensi maka proses berlangganan pressensi harus dimulai dari awal termasuk migrasi data. </p>
                        <p>4. Apabila setelah jangka waktu penggunaan pressensi berakhir User masih memiliki dana yang tersimpan pada Akun User, maka dana tersebut akan dikembalikan selambat-lambatnya 10 (sepuluh) hari Kerja setelah tanggal berakhirnya jangka waktu penggunaan pressensi. </p>
                        <h3 style="padding-left: 130px">VIII. PERJANJIAN TINGKAT LAYANAN</h3>
                        <p>1. Target Ketersediaan Layanan</p>
                        <p>Penyedia Layanan memberikan jaminan sehubungan dengan server uptime untuk 99,8 % untuk setiap bulan kalender.</p>
                        <p>2. Pengecualian</p>
                        <p>Kegagalan sistem tidak menjadi tanggung jawab Penyedia Layanan apabila kegagalan sistem tersebut disebabkan oleh:</p>
                        <p style="padding-left: 40px">a. penggunaan Layanan oleh User dengan cara yang tidak diizinkan dalam Syarat dan Ketentuan atau Perjanjian Kerja Sama yang berlaku;</p>
                        <p style="padding-left: 40px">b. masalah internet umum, kejadian  force majeure atau faktor lain di luar kendali Penyedia Layanan;</p>
                        <p style="padding-left: 40px">c. kegagalan atau malfungsi pada peralatan User termasuk namun tidak terbatas pada Aplikasi, koneksi jaringan atau infrastruktur lainnya; atau</p>
                        <p style="padding-left: 40px">d. kegagalan atau malfungsi sistem, tindakan atau kelalaian pihak ketiga; atau pemeliharaan terjadwal atau perawatan darurat yang wajar.</p>
                        <p>3. User menghubungi Penyedia Layanan untuk memperoleh bantuan penggunaan pressensi hanya melalui layanan telepon di Waktu Kerja. </p>
                        <h3 style="padding-left: 130px">IX. HUBUNGAN DENGAN PIHAK KETIGA</h3>
                        <p>1. Penyedia Layanan tidak akan memberikan Data User kepada pihak ketiga kecuali diwajibkan oleh hukum dan/atau atas perintah peraturan perundang-undangan atau lembaga pemerintah atau peradilan, kecuali atas persetujuan tertulis User. </p>
                        <p>2. Penyedia Layanan tidak bertanggung jawab atas layanan pihak ketiga yang bermitra dengan pressensi.</p>
                        <p>3. Seluruh resiko yang terjadi apabila diakibatkan oleh layanan pihak ketiga yang bermitra dengan Penyedia Layanan merupakan tanggung jawab pihak ketiga.</p>
                        <h3 style="padding-left: 130px">X. TRANSMISI ELEKTRONIK</h3>
                        <p>Syarat dan Ketentuan ini, dan setiap amandemennya, dengan cara apa pun yang diterima, harus diperlakukan sebagai kontrak sebagaimana mestinya dan harus dianggap memiliki akibat hukum yang mengikat sama seperti versi asli yang ditandatangani secara langsung.</p>
                        <h3 style="padding-left: 130px">XI. FORCE MAJEURE</h3>
                        <p>Dalam hal ini apabila Penyedia Layanan tidak dapat melaksanakan kewajiban baik sebagian maupun seluruhnya yang diakibatkan oleh hal-hal diluar kekuasaan atau kemampuan pressensi, termasuk namun tidak terbatas pada bencana alam, perang, huru-hara, adanya kebijakan/peraturan pemerintah yang tidak memperbolehkan atau yang membatasi pressensi untuk beroperasi dibawah jurisdiksi hukum Indonesia, serta kejadian-kejadian atau peristiwa-peristiwa diluar kekuasaan atau kemampuan Penyedia Layanan, maka dengan ini User membebaskan Penyedia Layanan dari segala macam tuntutan dalam bentuk apapun terkait dengan tidak dapat dilaksanakannya kewajiban oleh Penyedia Layanan.</p>
                        <h3 style="padding-left: 130px">XII. PENYELESAIAN SENGKETA</h3>
                        <p>1. Dalam hal terjadi sengketa atau perselisihan yang timbul dari atau sehubungan dengan Syarat dan Ketentuan ini, Penyedia Layanan dan Perusahaan melakukan pembahasan dengan itikad baik untuk mencapai penyelesaian berdasarkan kesepakatan bersama dalam waktu 30 (tiga puluh) Hari Kerja sejak tanggal pemberitahuan perselisihan. Namun, jika perselisihan tersebut tidak dapat diselesaikan melalui musyawarah dalam waktu 30 (tiga puluh) Hari Kerja, maka sengketa atau perselisihan tersebut akan diselesaikan melalui Pengadilan Negeri Jakarta Selatan.</p>
                        <h3 style="padding-left: 130px">XIII. KETENTUAN LAIN-LAIN</h3>
                        <p>1. Disclaimer</p>
                        <p style="padding-left: 40px">a. pressensi tidak bertanggung jawab terhadap segala macam bentuk kelalaian yang dilakukan oleh User.</p>
                        <p style="padding-left: 40px">b. Dengan menggunakan Layanan pressensi, User secara otomatis mengikuti sistem yang terdapat pada fitur-fitur pressensi.</p>
                        <p style="padding-left: 40px">c. User bertanggung jawab untuk memastikan kebenaran, keabsahan dan kejelasan dokumen-dokumen untuk pendaftaran pressensi, dan dengan ini User membebaskan pressensi dari segala gugatan, tuntutan dan/atau ganti rugi dari pihak manapun sehubungan dengan ketidakbenaran informasi, Data, keterangan, kewenangan dan atau kuasa yang diberikan oleh User.</p>
                        <p>2. Perubahan</p>
                        <p>Dengan memberikan pemberitahuan sebelumnya kepada User, sesuai dengan ketentuan yang berlaku, User dengan ini setuju bahwa setiap saat pressensi berhak mengubah, yang termasuk namun tidak terbatas pada memperbaiki, menambah atau mengurangi, ketentuan dalam Syarat dan Ketentuan, dan User terikat pada seluruh perubahan yang dilakukan oleh pressensi.</p>
                        <p>3. Komunikasi</p>
                        <p>User dapat menghubungi Penyedia Layanan melalui:</p>
                        <p style="padding-left: 40px">a.  Email                     : support@pressensi.id</p>
                        <p style="padding-left: 40px">b. Telepon                  : (022) 2010600 </p>
                        <p style="padding-left: 40px">c.  Kantor pressensi            :JL. Setra Sari Indah no.4 Bandung 40152</p>
                        <p>4. Sebagai tambahan dan pelengkap Syarat dan Ketentuan ini, dokumen-dokumen berikut juga berlaku terhadap penggunaan Layanan dan aplikasi pressensi oleh User;</p>
                        <p style="padding-left: 40px">a. Kebijakan Privasi, yang menetapkan ketentuan-ketentuan yang berlaku ketika Penyedia Layanan mengolah setiap Data yang dikumpulkan dari User, atau yang User berikan kepada Penyedia Layanan. Dengan menggunakan pressensi, Anda setuju dengan pengumpulan, penggunaan, pengungkapan Data Anda dan Anda menjamin bahwa semua Data yang Anda berikat adalah akurat;</p>
                        <p style="padding-left: 40px">b. Perjanjian Kerja Sama, yang berlaku untuk User yang berlangganan Layanan dan aplikasi pressensi untuk jangka waktu di atas 6 (enam) bulan;</p>
                        <p style="padding-left: 40px">c. Perjanjian Pengguna Akhir (apabila relevan).</p>
                        <p>5. Jika terdapat pertentangan antara Syarat dan Ketentuan ini dengan Perjanjian Kerja Sama, maka ketentuan yang ada dalam Syarat dan Ketentuan ini yang berlaku. </p>
                        <p>Dengan menggunakan pressensi, Anda mengakui bahwa Anda telah membaca, memahami, dan menyetujui Syarat dan Ketentuan ini.</p>
                        <h3 style="padding-left: 130px">XIX. PENGAJUAN REFUND</h3>
                        <p>pressensi berkomitmen untuk memproses refund dana dengan ketentuan sebagai berikut:</p>
                        <p style="padding-left: 40px">Refund dilakukan ke rekening bank pihak pengirim dana, dilakukan selambat-lambatnya dalam 7 (tujuh) hari kerja sejak permintaan refund dan data-data yang dibutuhkan sudah lengkap diberikan oleh pelanggan kepada Perusahaan dengan mengirim email ke fo@pressensi.id  dengan subject REFUND_Nama Perusahaan </p>  
                    </div>            
                </div>
            </div>
        </div>
    </section>
    <!-- facebook chat section start-->
    <div id="fb-root"></div>
    <!-- Your customer chat code-->
    <section class="p-0" style="background-color: #333231">
      <div class="container-fluid">
        <div class="bottom-section">
          <div class="row">
            <div class="col-md-3">
              <div class="container">
                <h3 style="color: white">Contact Us</h3>
                <hr style="border: solid 1px white">
                <ul class="">
                  <li>
                    <a href="mailto:support@pressensi.com"><p style="color: white"><i class="fa fa-envelope"></i> support@pressensi.com</p></a>
                  </li>
                  <li>
                    <a href="tel:0222010606"><p style="color: white"><i class="fa fa-phone-square"></i> 022-2010606</p></a>
                    <a href="tel:081310445410"><p style="color: white"><i class="fa fa-phone-square"></i> 081310445410</p></a>
                  </li>
                  <li>
                    <p style="color: white"><i class="fa fa-map-marker"></i> Jl. Setrasari Indah No.4. Sukarasa. Sukasari. Kota Bandung, Jawa Barat 40152</p>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-3">
              <div class="container">
                <h3 style="color: white">Features</h3>
                <hr style="border: solid 1px white">
                <ul class="">
                  <li>
                    <a href="<?php echo base_url();?>fitur/selfie_validation"><p style="color: white;">Selfie Validation</p></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url();?>fitur/location_validation"><p style="color: white;">Localtion Validation</p></a>
                  </li>
                  <li>
                    <a style="color: white;" href="<?php echo base_url();?>fitur/network_validation"><p style="color: white;">Network Validation</p></a>
                  </li>
                  <li>
                    <a style="color: white;" href="<?php echo base_url();?>fitur/realtime_tracking"><p style="color: white;">Realtime Tracking</p></a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-3">
              <div class="container">
                <h3 style="color: white">About Us</h3>
                <hr style="border: solid 1px white">
                <ul class="">
                  <li>
                    <a href="<?php echo base_url();?>about/termandcondition"><p style="color: white">Syarat & ketentuan</p></a>
                  </li>
                  <li>
                  <a href="<?php echo base_url();?>about/kebijakan_privasi"><p style="color: white">Kebijakan Privasi</p></a>
                  </li>
                  <li>
                    <a href="http://blog.pressensi.com"><p style="color: white;">Blog</p></a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="col-md-3">
              <div class="container">
                <h3 style="color: white">Find Us</h3>
                <hr style="border: solid 1px white">
                <ul class="">
                  <li>
                      <a href="https://www.youtube.com/channel/UC3_h22QjWKTMNlTWrj0wMgA"><p style="color: white"><i class="fa fa-instagram"></i> pressensiApp</p></a>
                  </li>
                  <li>
                      <a href="https://www.instagram.com/pressensiapp/"><p style="color: white"><i class="fa fa-youtube"></i> pressensiApp</p></a>
                  </li>
                  <li>
                    <a href="https://play.google.com/store/apps/details?id=com.folkatech.pressensi">
                      <img src="<?php echo base_url();?>assets/lp_2019/images/play-store.png" alt="">
                    </a>
                  </li>
                  <!-- <li style="margin-top: 10px;">
                    <a href="#" style="cursor: help;">
                      <img src="<?php echo base_url();?>assets/lp_2019/images/appstore.png" alt="">
                    </a>
                  </li> -->
                </ul>
              </div>
            </div>            
        </div>
      </div>
    </section>

    <div class="tap-top" style="bottom: 20px">
      <div><i class="fa fa-angle-double-up"></i></div>
    </div>
    <!-- Tap on Ends-->
    <!-- Footer Section start-->

    <div class="copyright-section index-footer" style="background-color: #333231">
      <p style="color: white">2019 copyright by pressensi powered by <a href="http://folkatech.com/" style="color: white;">Folkatech</a></p>
    </div>
    <!-- js file-->
    <script src="<?php echo base_url();?>assets/lp_2019/js/jquery-3.3.1.min.js"></script>
    <!-- bootstrap js file-->
    <script src="<?php echo base_url();?>assets/lp_2019/js/bootstrap.min.js"></script>
    <!-- popper js file-->
    <script src="<?php echo base_url();?>assets/lp_2019/js/popper.min.js"></script>
    <!-- Owl carousel js file-->
    <script src="<?php echo base_url();?>assets/lp_2019/js/owl.carousel.min.js"></script>
    <!-- script js file-->
    <script src="<?php echo base_url();?>assets/lp_2019/js/script.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/sweetalert/sweetalert.min.js"></script>
    
  </body>
</html>