<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<style>
  .stepwizard-step p {
    margin-top: 0px;
    color:#666;
  }
  .stepwizard-row {
    display: table-row;
  }
  .stepwizard {
    display: table;
    width: 100%;
    position: relative;
  }
  .stepwizard-step button[disabled] {
    opacity: 1 !important;
    filter: alpha(opacity=100) !important;
  }
  .stepwizard .btn.disabled, .stepwizard .btn[disabled], .stepwizard fieldset[disabled] .btn {
    opacity:1 !important;
    color:#bbb;
  }
  .stepwizard-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content:" ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
    z-index: 0;
  }
  .stepwizard-step {
    display: table-cell;
    text-align: center;
    position: relative;
  }
  .btn-circle {
    width: 30px;
    height: 30px;
    text-align: center;
    padding: 5px 0;
    font-size: 12px;
    line-height: 1.428571429;
    border-radius: 15px;
  }
</style>


<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card mt-5">
            <div class="card-header">
              <h5><strong>Ubah Data Pegawai</strong></h5>
            </div>
            <div class="card-body">
              <div class="stepwizard">
                <div class="stepwizard-row setup-panel">
                  <div class="stepwizard-step col-xs-4"> 
                      <a href="#step-1" type="button" class="btn btn-success btn-circle">1</a>
                      <p><small>Kepegawaian</small></p>
                  </div>
                  <div class="stepwizard-step col-xs-3"> 
                      <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                      <p><small>Identitas Diri</small></p>
                  </div>
                  <div class="stepwizard-step col-xs-5"> 
                      <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
                      <p><small>Informasi Kontak</small></p>
                  </div>
                  <!--<div class="stepwizard-step col-xs-2"> 
                      <a href="#step-4" type="button" class="btn btn-default btn-circle" disabled="disabled">4</a>
                      <p><small>Pendidikan</small></p>
                  </div>
                  <div class="stepwizard-step col-xs-3"> 
                      <a href="#step-5" type="button" class="btn btn-default btn-circle" disabled="disabled">5</a>
                      <p><small>Pekerjaan</small></p>
                  </div>-->
                </div>
              </div>
              
              <form role="form" id="contact" method="POST" action="javascript:void(0)">
                <input type="hidden" name="user_id" value="<?php echo $data_staff->user_id;?>">
                <div class="card setup-content" id="step-1">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="nip"><span class="text-danger">*</span> ID Pegawai</label>
                                <div class="form-group">
                                    <div class="form-line">
                                    <input type="text" id="nip" class="form-control" name="nip" value="<?php echo $data_staff->nip;?>" required placeholder="ID Pegawai">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="unit"><span class="text-danger">*</span> Unit Kerja</label>
                                        <select class="form-control show-tick" id="unit" name="id_unit" required>
                                            <option></option>
                                            <?php echo $departemen;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="list_divisi"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="status_pegawai"><span class="text-danger">*</span> Status Pekerjaan</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" id="status_pegawai" name="status_user" required>
                                            <option value="">-- Status Pekerjaan --</option>
                                            <?php echo $list_status_pekerjaan;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="jabatan"><span class="text-danger">*</span> Jabatan</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="jabatan" id="jabatan">
                                            <option value=""></option>
                                            <?php echo $list_jabatan;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tanggal_kerja"><span class="text-danger">*</span> Tanggal Mulai Kontrak</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="date" class="form-control" name="tanggal_kerja" id="tanggal_kerja"  value="<?php echo $data_staff->tanggal_kerja;?>" placeholder="Tanggal Bergabung">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="display: none;" id="tanggalAkhir">
                                <label for="tanggal_akhir"><span class="text-danger">*</span> Tanggal Berakhir Kontrak</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="date" class="form-control" name="tanggal_akhir" required id="tanggal_akhir" value="<?php echo $data_staff->tanggal_akhir;?>" placeholder="Tanggal Berakhir Kontrak">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="struktur"><span class="text-danger">*</span> Struktur Organisasi</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" id="struktur" required="" name="id_struktur" data-size="5" data-live-search="true">
                                            <option value="">-- Struktur Organisasi --</option>
                                            <?php foreach ($posisi as $key=>$value) { ?>
                                                <option value="<?php echo $value['id'];?>" <?php if ($value['id'] == $data_staff->id_struktur): echo 'selected' ?><?php endif ?>><?php echo $value['name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="struktur_organisasi" class="col-md-6"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="cuti"><span class="text-danger">*</span> Kuota Cuti Tahunan</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" class="form-control" id="cuti" name="cuti"  value="<?php echo $data_staff->cuti;?>" min="0" required placeholder="Kuota Cuti Tahunan">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="password"><span class="text-danger">*</span> Password</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                        <i class="text-danger" style="font-size: 10px;">(Kosongkan jika tidak mengubah)</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="userfile">Foto Staff</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="file" name="userfile" id="userfile" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="role"><span class="text-danger">*</span> Role Akses</label>
                                <div class="form-group">
                                    <select name="role" id="role" class="form-control" required>
                                      <option value="">-- Pilih role --</option>
                                      <option value="1">SDM / HR</option>
                                      <option value="0">Standar</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="is_aktif"><span class="text-danger">*</span> Status Pegawai</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="is_aktif" id="is_aktif" class="form-control show-tick" required="">
                                            <option value="">-- Status Pegawai --</option>
                                            <?php echo $list_is_aktif;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="gaji_pokok"><span class="text-danger">*</span> Gaji</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" id="gaji_pokok" class="form-control" min="0" name="gaji_pokok" value="<?php echo $data_staff->gaji_pokok;?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo $getKomponen;?>
                        <button class="btn btn-primary nextBtn float-right" type="button">Next</button>
                    </div>
                </div>

                <div class="card setup-content" id="step-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="nama_user"><span class="text-danger">*</span> Nama User</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="nama_user" id="nama_user" value="<?php echo $data_staff->nama_user;?>" placeholder="Nama Lengkap" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="jenis_kelamin"><span class="text-danger">*</span> Jenis Kelamin</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="jenis_kelamin" id="jenis_kelamin" required>
                                            <option value="">-- Jenis Kelamin --</option>
                                            <?php echo $list_jenkel;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="no_identitas"><span class="text-danger">*</span> Nomor Identitas</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" id="no_identitas" class="form-control" name="no_identitas"  value="<?php echo $data_staff->nomor_identitas;?>" required placeholder="Nomor Identitas">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="jenis_nomor_identitas"><span class="text-danger">*</span> Jenis Identitas</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" id="jenis_nomor_identitas" name="jenis_nomor_identitas" required>
                                            <option value="">-- Pilih jenis identitas --</option>
                                            <?php echo $list_jni;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tempat_lahir"><span class="text-danger">*</span> Tempat Lahir</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo $data_staff->tempat_lahir;?>" required placeholder="Tempat Lahir">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_lahir"><span class="text-danger">*</span> Tanggal Lahir</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="date" class="form-control" value="<?php echo $data_staff->tanggal_lahir;?>" name="tanggal_lahir" id="tanggal_lahir" placeholder="Tanggal Lahir" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="status_pernikahan">Status Pernikahan</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="status_pernikahan" id="status_pernikahan">
                                            <option value="">-- Pilih status pernikahan --</option>
                                            <?php echo $list_status_pernikahan;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="agama"><span class="text-danger">*</span> Agama</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="agama" id="agama" required>
                                            <option value="">-- Pilih agama --</option>
                                            <?php echo $list_agama;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary nextBtn float-right" type="button">Next</button>
                    </div>
                </div>

                <div class="card setup-content" id="step-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="alamat_user"><span class="text-danger">*</span> Alamat</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea name="alamat_user" id="alamat_user" cols="30" rows="3" class="form-control" required placeholder="Alamat User"><?php echo $data_staff->alamat_user;?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="email_address"><span class="text-danger">*</span> Email</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="email" class="form-control" name="email_user" value="<?php echo $data_staff->email_user;?>" placeholder="Email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="telp_user"><span class="text-danger">*</span> Nomor Telepon</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="telp_user" id="telp_user" value="<?php echo $data_staff->telp_user;?>" placeholder="Nomor Telepon" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="nama_kontak_darurat">Nama Kontak Darurat</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="nama_kontak_darurat" id="nama_kontak_darurat" value="<?php echo $data_staff->nama_kontak_darurat;?>" placeholder="Nama Kontak Darurat">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="nomor_kontak_darurat">Telepon Kontak Darurat</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" class="form-control" name="nomor_kontak_darurat" id="nomor_kontak_darurat" value="<?php echo $data_staff->telepon_kontak_darurat;?>" placeholder="Telepon Kontak Darurat">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success float-right" type="submit">Finish!</button>
                    </div>
                </div>

                <div class="card setup-content" id="step-4">
                    <div class="card-body">
                        <?php if ($riwayat_pendidikan == "" || $riwayat_pendidikan == null ) { ?>
                            <h2 class="card-inside-title">Pasca Sarjana</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Nama Universitas</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="nama_instansi_pendidikan[4][nama]" placeholder="Nama Universitas">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Jurusan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="nama_instansi_pendidikan[4][jurusan]"  >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Tahun Masuk</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="nama_instansi_pendidikan[4][tahun_masuk]" placeholder="Tahun Masuk" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Tahun Lulus</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="nama_instansi_pendidikan[4][tahun_lulus]" placeholder="Tahun Lulus" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="nama_instansi_pendidikan[4][jenis_pendidikan]" value="Pasca Sarjana">
                            <h2 class="card-inside-title">Universitas</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Nama Universitas</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="nama_instansi_pendidikan[3][nama]" placeholder="Nama Universitas" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Jurusan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="nama_instansi_pendidikan[3[jurusan]" placeholder="Jurusan" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Tahun Masuk</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="nama_instansi_pendidikan[3][tahun_masuk]" placeholder="Tahun Masuk" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Tahun Lulus</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="nama_instansi_pendidikan[3][tahun_lulus]" placeholder="Tahun Lulus" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="nama_instansi_pendidikan[3][jenis_pendidikan]" value="Universitas">
                            <h2 class="card-inside-title">Sekolah Menegah Atas/Kejuruan (SMA/SMK)</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Nama Sekolah</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="nama_instansi_pendidikan[2][nama]" placeholder="Nama Sekolah" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Jurusan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="nama_instansi_pendidikan[2][jurusan]" placeholder="Jurusan" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Tahun Masuk</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="nama_instansi_pendidikan[2][tahun_masuk]" placeholder="Tahun Masuk" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Tahun Lulus</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="nama_instansi_pendidikan[2][tahun_lulus]" placeholder="Tahun Lulus" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="nama_instansi_pendidikan[2][jenis_pendidikan]" value="sma/smk">
                            <h2 class="card-inside-title">Sekolah Menegah Pertama (SMP)</h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="email_address">Nama Sekolah</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="nama_instansi_pendidikan[1][nama]" placeholder="Nama Sekolah" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Tahun Masuk</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="nama_instansi_pendidikan[1][tahun_masuk]" placeholder="Tahun Masuk" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Tahun Lulus</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="nama_instansi_pendidikan[1][tahun_lulus]" placeholder="Tahun Lulus" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="nama_instansi_pendidikan[1][jurusan]" value="">
                            <input type="hidden" name="nama_instansi_pendidikan[1][jenis_pendidikan]" value="smp">
                            <h2 class="card-inside-title">Sekolah Dasar SD</h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="email_address">Nama Sekolah</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="nama_instansi_pendidikan[5][nama]" placeholder="Nama Sekolah" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Tahun Masuk</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="nama_instansi_pendidikan[5][tahun_masuk]" placeholder="Tahun Masuk" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Tahun Lulus</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="nama_instansi_pendidikan[5][tahun_lulus]" placeholder="Tahun Lulus" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="nama_instansi_pendidikan[5][jurusan]" value="" >
                            <input type="hidden" name="nama_instansi_pendidikan[5][jenis_pendidikan]" value="sd">
                        <?php } else {
                            echo $riwayat_pendidikan;
                        }?>
                        <button class="btn btn-primary nextBtn float-right" type="button">Next</button>
                    </div>
                </div>
                
                <div class="card setup-content" id="step-5">
                    <div class="card-body">
                        <?php if ($riwayat_pekerjaan == "" || $riwayat_pekerjaan == null) { ?>
                            <h2 class="card-inside-title">Riwayat Pekerjaan I</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Nama Perusahaan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[1][nama_perusahaan]" placeholder="Nama Perusahaan" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Posisi</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[1][posisi]" placeholder="Posisi" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="email_address">Deskripsi Pekerjaan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[1][deskripsi]"placeholder="Deskripsi Pekerjaan" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Lama Bekerja</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="perusahaan[1][lama_bekerja]"placeholder="Lama Bekerja" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Alasan Berhenti</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[1][alasan_berhenti]"placeholder="Alasan Berhenti" >
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="perusahaan[1][id_form]"  value="1">
                            </div>
                            <h2 class="card-inside-title">Riwayat Pekerjaan II</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Nama Perusahaan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[2][nama_perusahaan]"placeholder="Nama Perusahaan" >
                                            <label class="form-label">Nama Perusahaan*</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Posisi</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[2][posisi]" placeholder="Posisi" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="email_address">Deskripsi Pekerjaan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[2][deskripsi]" placeholder="Deskripsi Pekerjaan" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Lama Bekerja</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="perusahaan[2][lama_bekerja]" placeholder="Lama Bekerja" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Alasan Berhenti</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[2][alasan_berhenti]" placeholder="Alasan Berhenti" >
                                        </div>
                                    </div>
                                </div>  
                                <input type="hidden" name="perusahaan[2][id_form]"  value="2">
                            </div>
                            <h2 class="card-inside-title">Riwayat Pekerjaan III</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Nama Perusahaan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[3][nama_perusahaan]" placeholder="Nama Perusahaan" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Posisi</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[3][posisi]"  placeholder="Posisi" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="email_address">Deskripsi Pekerjaan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[3][deskripsi]" placeholder="Deskripsi Pekerjaan" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Lama Bekerja</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="perusahaan[3][lama_bekerja]" placeholder="Lama Bekerja" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Alasan Berhenti</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[3][alasan_berhenti]" placeholder="Alasan Berhenti" >
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="perusahaan[3][id_form]"  value="3">
                            </div>
                            <h2 class="card-inside-title">Riwayat Pekerjaan IV</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Nama Perusahaan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[4][nama_perusahaan]" placeholder="Nama Perusahaan" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Posisi</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[4][posisi]"  placeholder="Posisi" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="email_address">Deskripsi Pekerjaan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[4][deskripsi]" placeholder="Deskripsi Pekerjaan" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Lama Bekerja</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="perusahaan[4][lama_bekerja]" placeholder="Lama Bekerja" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Alasan Berhenti</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[4][alasan_berhenti]" placeholder="Alasan Berhenti" >
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="perusahaan[4][id_form]"  value="4">
                            </div>
                            <h2 class="card-inside-title">Riwayat Pekerjaan V</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Nama Perusahaan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[5][nama_perusahaan]" placeholder="Nama Perusahaan" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Posisi</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[5][posisi]"  placeholder="Posisi" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="email_address">Deskripsi Pekerjaan</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[5][deskripsi]" placeholder="Deskripsi Pekerjaan" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email_address">Lama Bekerja</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="number" class="form-control" name="perusahaan[5][lama_bekerja]" placeholder="Lama Bekerja" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_address">Alasan Berhenti</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="perusahaan[5][alasan_berhenti]" placeholder="Alasan Berhenti" >
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="perusahaan[5][id_form]" value="5">
                            </div>-->
                        <?php } else { 
                            echo $riwayat_pekerjaan;
                        } ?>
                    </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<script src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/jquery.validate.js"></script>
<script src='<?php echo base_url();?>assets/js/struktur.js'></script>
<script>
    var urlUpdate = "<?php echo base_url('Administrator/pegawai/update'); ?>";
    var path = "<?php echo base_url();?>";
    var form = $("#contact");
    const nip = "<?php echo $data_staff->nip;?>";

    function myFunction() {
        if (document.getElementById("pria").checked) {
            document.getElementById("jk").value = "l" ;
        }
        if (document.getElementById("wanita").checked) {
            document.getElementById("jk").value = "p" ;
        }
    }

    function myFunction2() {
        if (document.getElementById("ktp").checked) {
            document.getElementById("jni").value = "ktp";
        }
        if (document.getElementById("sim").checked) {
            document.getElementById("jni").value = "sim";
        }
        if (document.getElementById("paspor").checked) {
            document.getElementById("jni").value = "paspor";
        }
    }

    function getComboA(selectObject) {
        var value = selectObject.value;
    } 

    $(document).ready(function(){
        $('#role').val(<?php echo $data_staff->role;?>);
        /* set untuk mengecek status user */
        var tmpData = $("#status_pegawai option:selected").text();
        if (tmpData != 'Tetap') {
            $('#tanggalAkhir').show();
            document.getElementById("tanggal_akhir").required = true;
          } else {
            $('#tanggalAkhir').hide();
            document.getElementById("tanggal_akhir").required = false;
          }
        
        $('#status_pegawai').change(function() {
          var e = $("#status_pegawai option:selected").text();
          if (e != 'Tetap') {
            $('#tanggalAkhir').show();
            document.getElementById("tanggal_akhir").required = true;
          } else {
            $('#tanggalAkhir').hide();
            document.getElementById("tanggal_akhir").required = false;
          }
        });

        $('#unit').change(function() {
          $.LoadingOverlay("show");
          var e = $('#unit').val();
          $.ajax({
            success: function(html){
              $('#struktur_organisasi').load(path+"Administrator/pegawai/get_level/"+e+"/"+nip);
              $.LoadingOverlay("hide"); 
            }
          });
        });

        $('#status_user').change(function() {
            $.LoadingOverlay("show");
            var e = $('#status_user').val();
            $.ajax({
                success: function(html){
                    if (e != 1) {
                        $('#tanggalAkhir').show();
                    } else {
                        $('#tanggalAkhir').hide();
                    }
                    $.LoadingOverlay("hide");
                }
            });
        });

        var h = $('#unit').val();
        $.ajax({
            success: function(html){
              console.log(path+"Administrator/pegawai/get_level/"+h+"/"+nip);
              $('#struktur_organisasi').load(path+"Administrator/pegawai/get_level/"+h+"/"+nip);
            }
        });


        var g = "<?php echo $data_staff->user_id;?>";
        $.ajax({
            success: function(html){
                $('#list_divisi').load(path+"Administrator/pegawai/list_divisi/"+h+"/"+g);
            }
        });

        $('#contact').on('submit',(function(e) {
          swal({
            title: "Info",
            text: "Proses perubahan telah selesai, apakah setuju ?",
            icon: "info",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              e.preventDefault();
              var formData = new FormData(this);
              $.ajax({
                method  : 'POST',
                url     : urlUpdate,
                data    : formData,
                contentType: false,
                processData: false,
                success: function(data, status, xhr) {
                  try {
                    var result = JSON.parse(xhr.responseText);
                    if (result.status == true) {
                      swal(result.message, {
                        icon: "success",
                      }).then((acc) => {
                        window.location = "<?php echo base_url();?>Administrator/pegawai";
                      });
                    } else {
                      swal("Warning!", result.message, "warning");
                    }
                  } catch (e) {
                    swal("Warning!", 'Sistem error.', "warning");
                  }
                },
                error: function(data) {
                  swal("Warning!", 'Terjadi kesalahan sistem.', "warning");
                }
              });
            }
          });
        }));

        var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

        allWells.hide();

        navListItems.click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $item = $(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-success').addClass('btn-default');
                $item.addClass('btn-success');
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
            }
        });

        allNextBtn.click(function () {
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='text'], input[type='email'], input[type='number'], input[type='date'], input[type='url'], select, textarea"),
                isValid = true;

            $(".form-group").removeClass("has-error");
            for (var i = 0; i < curInputs.length; i++) {
                if (!curInputs[i].validity.valid) {
                    isValid = false;
                    $(curInputs[i]).closest(".form-group").addClass("has-error");
                }
            }

            if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
        });

        $('div.setup-panel div a.btn-success').trigger('click');
    });
</script>
