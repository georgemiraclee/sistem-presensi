<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo base_url();?>landingpage/assets/css/index.css" />
    <title>Pressensi | Absen dan Monitor Pegawai</title>
    <link rel="shortcut icon" href="<?php echo base_url();?>landingpage/assets/img/favicon.png">
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" rel="stylesheet"/>
    
  </head>
  <body>
    <nav class="navbar navbar-nav navbar-expand-lg navbar-light" id="navbar-toggler">
      <div class="container-fluid nav-container">
        <a class="navbar-brand" href="#">
          <img src="<?php echo base_url();?>landingpage/assets/img/logo-white-flat.png" class="d-inline-block align-top nav-logo" alt="">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="fas fa-bars"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <div class="me-auto"></div>
          <ul class="navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="#about">About <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#feature">Features</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#pricing">Pricing</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#journey">Journey</a>
            </li>
            <li class="nav-item">
              <a href="#contact" class="btn btn-outline-warning btn-sm btn-pill px-3">Coba Dulu</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <section class="hero hero1">
      <div class="row" style="padding-top: 180px;">
        <div class="col-md-6"></div>
        <div class="col-md-6">
          <img src="<?php echo base_url();?>landingpage/assets/img/logo-white-flat.png" alt="">
          <h3>Absen dan Monitor Pegawai</h3>
          <p>dengan validasi dan tracking online</p>
          <a href="#contact" class="btn btn-warning btn-pill px-4 text-white">Hubungi Kami</a>
          <div class="text-center">
          </div>
        </div>
      </div>
    </section>

    <section id="about" class="about">
      <div class="container">
        <h3>About</h3>

        <div class="content-right mt-4">
          <p>Pressensi adalah aplikasi absensi online dan monitoring pegawai berbasis mobile</p>

          <h5 class="mt-5">Dengan fitur validasi dan tracking </h5>
          <img src="<?php echo base_url();?>landingpage/assets/img/Group6.png" width="700px" alt="alur" class="my-20" />
        </div>
      </div>
      <div class="half-circle"></div>
      <img
        src="<?php echo base_url();?>landingpage/assets/img/Untitled.png"
        alt="mobile-logo"
        class="hand-mobile"
      />
    </section>

    <section id="feature" class="feature">
      <div class="container">
        <h3>Feature</h3>

        <div class="content-right mt-4">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-12 mt-4">
              <div class="card card-feature" style="height: 100%;">
                <img src="<?php echo base_url();?>landingpage/assets/img/selfie-validation.png" alt="icon-1" class="mx-auto" />
                <div class="text-center">
                  <h4>Selfie Validation</h4>
                  <p>
                    Pegawai absen dengan foto selfie
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mt-4">
              <div class="card card-feature" style="height: 100%;">
                <img src="<?php echo base_url();?>landingpage/assets/img/network-validation.png" alt="icon-1" class="mx-auto" />
                <div class="text-center">
                  <h4>Network Validation</h4>
                  <p>
                    Pegawai hanya bisa absen ketika pegawai berada di jaringan yang ditentukan
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mt-4">
              <div class="card card-feature" style="height: 100%;">
                <img src="<?php echo base_url();?>landingpage/assets/img/customize-work-shift.png" alt="icon-1" class="mx-auto" />
                <div class="text-center">
                  <h4>Customize Work Shift</h4>
                  <p>
                    Admin dapat menyesuaikan pola kerja yang berbeda dari masing-masing karyawan
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mt-4">
              <div class="card card-feature" style="height: 100%;">
                <img src="<?php echo base_url();?>landingpage/assets/img/location-validation.png" alt="icon-1" class="mx-auto" />
                <div class="text-center">
                  <h4>Location Validation</h4>
                  <p>
                    Absensi hanya bisa dilakukan di area/lokasi yang sudah ditentukan
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mt-4">
              <div class="card card-feature" style="height: 100%;">
                <img src="<?php echo base_url();?>landingpage/assets/img/role-management.png" alt="icon-1" class="mx-auto" />
                <div class="text-center">
                  <h4>Role Management</h4>
                  <p>
                    Dapatkan akses untuk mengatur setiap akun yang dapat di akses
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mt-4">
              <div class="card card-feature" style="height: 100%;">
                <img src="<?php echo base_url();?>landingpage/assets/img/track_daily_activity.png" alt="icon-1" class="mx-auto" />
                <div class="text-center">
                  <h4>Track Daily Activity</h4>
                  <p>
                    Setiap kegiatan dari pegawai dapat terpantau
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mt-4">
              <div class="card card-feature" style="height: 100%;">
                <img src="<?php echo base_url();?>landingpage/assets/img/blast-information.png" alt="icon-1" class="mx-auto" />
                <div class="text-center">
                  <h4>Blast Information</h4>
                  <p>
                    Melakukan penyebaran informasi dengan mudah dalam satu aplikasi
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mt-4">
              <div class="card card-feature" style="height: 100%;">
                <img src="<?php echo base_url();?>landingpage/assets/img/reimbursement.png" alt="icon-1" class="mx-auto" />
                <div class="text-center">
                  <h4>Reimbursement</h4>
                  <p>
                    Mempermudah proses pengembalian/reimburse biaya atau pengeluaran
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="oval-left"></div>
      <div class="oval-right"></div>
    </section>

    <section id="pricing" class="pricing">
      <div class="row">
        <div class="col-12 col-lg-6 col-md-12 p-5" style="background-color: #F3F3F3;">
          <div class="text-center">
            <h4>Pricing</h4>
            <h1>Rp. 12.000,-</h1>
            <p class="title">per Pegawai / Bulan</p>

            <div class="mt-5 grid grid-cols-2 text-left lg:px-20 xl:px-30 px-8">
              <p class="lg:text-sm text-xs">- Selfie Validation</p>
              <p class="lg:text-sm text-xs">- Location Validation</p>
              <p class="lg:text-sm text-xs">- Network Validation</p>
              <p class="lg:text-sm text-xs">- Track Daily Activity</p>
              <p class="lg:text-sm text-xs">- Report</p>
              <p class="lg:text-sm text-xs">- Central Monitoring Dashboard</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-6 col-md-12 p-5" style="background-color: #E8E8E8;">
          <div class="text-center">
            <h5>Butuh fitur tambahan dan kustomisasi khusus untuk Perusahaan Anda?</h5>
            <p class="title">Kontak dan konsultasi dengan Pressensi</p>

            <button class="btn btn-warning btn-pill content-right text-white mt-5 py-2">Hubungi Kami</button>
          </div>
        </div>
      </div>
    </section>

    <section id="contact" class="contact">
      <div class="bg-pressensi">
        <div class="container contact2">
          <div
            class="grid lg:grid-cols-2 md:grid-cols-1 gap-0"
            id="register"
          >
            <div class="flex items-center">
              <div>
                <p class="text-white text-5xl font-bold mb-3">Ingin Coba Dulu?</p>
                <p class="text-white">Silahkan isi data pribadi</p>
                <p class="text-white mb-10">anda untuk mendaftar</p>
                <img src="<?php echo base_url();?>landingpage/assets/img/arrow.png" alt="arrowIcon" class="arrow" />
              </div>
            </div>
            <div class="block">
              <form action="javascript:void(0)" method="POST" id="formSubscribe">
                <input type="email" class="w-full mb-3 p-3 rounded-lg" placeholder="Alamat Email" name="email" required />
                <input type="text" class="w-full mb-3 p-3 rounded-lg" placeholder="Nama Lengkap" name="name" required />
                <input type="tel" class="w-full mb-3 p-3 rounded-lg" placeholder="Nomor Telepon" name="phone" required/>
                <input type="text" class="w-full mb-3 p-3 rounded-lg" placeholder="Nama Perusahaan" name="company" required />
                <input type="number" class="w-full mb-3 p-3 rounded-lg" placeholder="Jumlah Karyawan" name="totalEmployer" required />
                <button class="btn btn-warning btn-block text-white btn-pill" type="submit">Coba Sekarang</button>
                <p class="text-center text-white py-3">Atau</p>
                <a href="https://wa.me/message/CGSDDDXJ4P4NJ1" class="btn btn-outline-primary btn-block text-white btn-pill">Hubungi Sekarang</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="journey" class="journey">
      <div class="container">
        <p class="font-bold text-3xl color-ims m-0">Journey</p>
        <div class="grid lg:grid-cols-5 md:grid-cols-3 gap-10 mb-10 mt-5 content-right">
          <div class="text-center">
            <img
              src="<?php echo base_url();?>landingpage/assets/img/journey/journey-1.png"
              alt="icon-1"
              class="mx-auto"
            />
            <h4>Beranda</h4>
          </div>
          <div class="text-center">
            <img
              src="<?php echo base_url();?>landingpage/assets/img/journey/journey-2.png"
              alt="icon-1"
              class="mx-auto"
            />
            <h4>Statistik</h4>
          </div>
          <div class="text-center">
            <img
              src="<?php echo base_url();?>landingpage/assets/img/journey/journey-3.png"
              alt="icon-1"
              class="mx-auto"
            />
            <h4>
            Absen Selfie Validation
            </h4>
          </div>
          <div class="text-center">
            <img
              src="<?php echo base_url();?>landingpage/assets/img/journey/journey-4.png"
              alt="icon-1"
              class="mx-auto"
            />
            <h4>Pengajuan Cuti</h4>
          </div>
          <div class="text-center">
            <img
              src="<?php echo base_url();?>landingpage/assets/img/journey/journey-5.png"
              alt="icon-1"
              class="mx-auto"
            />
            <h4>Aktivitas</h4>
          </div>
        </div>
      </div>
    </section>

    <section id="footer" class="footer">
      <div
        class="footer bg-pressensi banner-contact flex items-center justify-start relative"
      >
        <div class="col-span-1">
          <img
            src="<?php echo base_url();?>landingpage/assets/img/logo-pressensi.png"
            alt="logo"
          />
        </div>
        <div class="line"></div>
        <div>
          <p class="text-white font-bold lg:text-2xl md:text-2xl text-base">Hubungi Kami</p>
          <a
            href="https://wa.me/message/CGSDDDXJ4P4NJ1"
            target="blank"
            class="shadow flex text-center text-white rounded py-2 px-4 cursor-pointer lg:mt-8 mt-4 mb-4 text-sm lg:text-base"
          >
            Whatsapp Chat: 0851-5639-8529
          </a>
          <a
            href="mailto:hello@folkatech.com?subject=Konsultasi%20Kebutuhan%20Layanan%20IT"
            class="shadow flex text-center text-white rounded py-2 px-4 cursor-pointer text-sm lg:text-base"
          >
            E-mail: hello@folkatech.com
          </a>
          <div class="mt-5">
            <a href="<?php echo base_url();?>faq" class="btn btn-outline-primary text-white btn-pill content-right">FAQ</a>
            <a href="<?php echo base_url();?>kebijakan-privasi" class="btn btn-outline-primary text-white btn-pill content-right">Kebijakan Privasi</a>
          </div>
        </div>
        <div class="half-circle-footer" />
      </div>
    </section>

    <div class="floating-button">
      <a href="https://wa.me/message/CGSDDDXJ4P4NJ1" target="blank">
        <img
          src="<?php echo base_url();?>landingpage/assets/img/wa.png"
          alt="waIcon"
          class="image-floating-button"
        />
      </a>
    </div>
  </body>

  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
  <script>
    var urlInsert = "<?php echo base_url('Landingpage/subscribe'); ?>";

    $(document).scroll(function () {
      var $nav = $("#navbar-toggler");
      $nav.toggleClass('scrolled', $(this).scrollTop()> $nav.height());

      if ($(this).scrollTop()> $nav.height()) {
        $(".nav-logo").attr("src","<?php echo base_url();?>landingpage/assets/img/logo-color.png");
        $('#navbar-toggler').removeClass('navbar-bg-transparant').addClass('navbar-bg-light');
      } else {
        $(".nav-logo").attr("src","<?php echo base_url();?>landingpage/assets/img/logo-white-flat.png");
        $('#navbar-toggler').removeClass('navbar-bg-light').addClass('navbar-bg-transparant');
      }
    });

    $(document).ready(function() {
      var $nav = $("#navbar-toggler");
      $nav.toggleClass('scrolled', $(this).scrollTop()> $nav.height());

      if ($(this).scrollTop()> $nav.height()) {
        $(".nav-logo").attr("src","<?php echo base_url();?>landingpage/assets/img/logo-color.png");
        $('#navbar-toggler').removeClass('navbar-bg-transparant').addClass('navbar-bg-light');
      } else {
        $(".nav-logo").attr("src","<?php echo base_url();?>landingpage/assets/img/logo-white-flat.png");
        $('#navbar-toggler').removeClass('navbar-bg-light').addClass('navbar-bg-transparant');
      }
      $('#formSubscribe').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            method  : 'POST',
            url     : urlInsert,
            data    : formData,
            contentType: false,
            processData: false,
            success: function(data, status, xhr) {
              try {
                var result = JSON.parse(xhr.responseText);
                if (result.status == true) {
                  swal(result.message, {
                    title: "Success!",
                    icon: "success",
                  }).then((acc) => {
                    location.reload();
                  });
                } else {
                  swal("Warning!", result.message, "warning");
                }
              } catch (e) {
                swal("Warning!", "Sistem error.", "warning");
              }
            },
            error: function(data) {
              swal("Warning!", "Terjadi kesalahan sistem.", "warning");
            }
        });
      }));
    });
  </script>
</html>
