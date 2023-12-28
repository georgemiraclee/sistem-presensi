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
                          <h5><strong>Tambah Data Pegawai</strong></h5>
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
                                <div class="card setup-content" id="step-1">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nip"><span class="text-danger">*</span> ID Pegawai</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" id="nip" class="form-control" name="nip" required placeholder="ID Pegawai">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="unit"><span class="text-danger">*</span> Unit Kerja</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="id_unit" id="unit" required>
                                                            <option value="">-- Unit Kerja --</option>
                                                            <?php foreach ($departemen as $key => $value) {
                                                                ?>
                                                            <option value="<?php echo $value->id_unit;?>"><?php echo $value->nama_unit;?></option>
                                                            <?php } ?>
                                                        
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
                                                        <select class="form-control show-tick" id="status_pegawai" required="" name="status_user">
                                                            <option value="">-- Status Pekerjaan --</option>
                                                            <?php foreach ($status_staff as $key => $value) { ?>
                                                                <option value="<?php echo $value->id_status_user;?>"><?php echo $value->nama_status_user;?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="jabatan"><span class="text-danger">*</span> Jabatan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" id="jabatan" name="jabatan" required="">
                                                            <option value="">-- Pilih Jabatan --</option>
                                                            <?php foreach ($jabatan as $key => $value) {
                                                                ?>
                                                            <option value="<?php echo $value->id_jabatan;?>"><?php echo $value->nama_jabatan;?></option>
                                                            <?php } ?>
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
                                                        <input type="date" id="tanggal_kerja" class="form-control" name="tanggal_kerja" required="" placeholder="Tanggal Mulai Kontrak">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="tanggalAkhir"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="struktur"><span class="text-danger">*</span> Struktur Organisasi</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" id="struktur" required="" name="id_struktur" data-size="5" data-live-search="true">
                                                            <option value="">-- Struktur Organisasi --</option>
                                                            <?php foreach ($posisi as $key=>$value) { ?>
                                                                <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
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
                                                        <input type="number" class="form-control" min="0" id="cuti" name="cuti" required placeholder="Kuota Cuti Tahunan">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="password"><span class="text-danger">*</span> Password</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="password" class="form-control" name="password" id="password" required placeholder="Password">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="userfile">Foto Staff</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="file" name="userfile" id="userfile">
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
                                                    <select class="form-control show-tick" id="is_aktif" required="" name="is_aktif" data-size="5" data-live-search="true">
                                                        <option value="">-- Struktur Pegawai --</option>
                                                        <option value="1">Aktif</option>
                                                        <option value="0">Nonaktif</option>
                                                    </select>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="gaji_pokok"><span class="text-danger">*</span> Gaji</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" id="gaji_pokok" min="0" class="form-control" name="gaji_pokok" required placeholder="Gaji">
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
                                                        <input type="text" class="form-control" name="nama_user" id="nama_user" required placeholder="Nama User">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="pria"><span class="text-danger">*</span> Jenis Kelamin</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="jenis_kelamin" id="jenis_kelamin" required>
                                                            <option value="">-- Pilih jenis kelamin --</option>
                                                            <option value="l">Pria</option>
                                                            <option value="p">Wanita</option>
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
                                                        <input type="number" class="form-control" id="no_identitas" name="no_identitas" required placeholder="Nomor Identitas">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="jenis_nomor_identitas"><span class="text-danger">*</span> Jenis Identitas</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="jenis_nomor_identitas" id="jenis_nomor_identitas" required>
                                                            <option value="">-- Pilih jenis identitas --</option>
                                                            <option value="ktp">KTP</option>
                                                            <option value="sim">SIM</option>
                                                            <option value="paspor">Paspor</option>
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
                                                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"  required placeholder="Tempat Lahir">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="tanggal_lahir"><span class="text-danger">*</span> Tanggal Lahir</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="date" required class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Tanggal Lahir">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="status_pernikahan"><span class="text-danger">*</span> Status Pernikahan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" id="status_pernikahan" name="status_pernikahan" required="">
                                                            <option value="">-- Pilih Status Pernikahan --</option>
                                                            <option value="lajang">Lajang</option>
                                                            <option value="menikah">Menikah</option>
                                                            <option value="janda">Janda</option>
                                                            <option value="duda">Duda</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="agama"><span class="text-danger">*</span> Agama</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="agama" id="agama" required="">
                                                            <option value="">-- Pilih Agama --</option>
                                                            <option value="islam">Islam</option>
                                                            <option value="protestan">Katolik</option>
                                                            <option value="kaltolik">Protestan</option>
                                                            <option value="hindu">Budha</option>
                                                            <option value="budha">Hindu</option>
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
                                                        <textarea name="alamat_user" id="alamat_user" cols="30" rows="3" class="form-control" required placeholder="Alamat"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="email_user"><span class="text-danger">*</span> Email</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="email" id="email_user" placeholder="Email" class="form-control" name="email_user" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="telp_user"><span class="text-danger">*</span> Nomor Telepon</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" id="telp_user" name="telp_user"  required placeholder="Nomor Telepon">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nama_kontak_darurat"><span class="text-danger">*</span> Nama Kontak Darurat</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="nama_kontak_darurat" name="nama_kontak_darurat" required placeholder="Nama Kontak Darurat">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nomor_kontak_darurat"><span class="text-danger">*</span> Telepon Kontak Darurat</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="nomor_kontak_darurat" id="nomor_kontak_darurat" required placeholder="Nomor Telepon Darurat">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-success float-right" type="submit">Finish!</button>
                                    </div>
                                </div>

                                <div class="card setup-content" id="step-4">
                                    <div class="card-body">
                                        <h2 class="card-inside-title">Pasca Sarjana</h2>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nama_universitas1">Nama Universitas</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="nama_instansi_pendidikan[4][nama]" id="nama_universitas1" placeholder="Nama Universitas">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nama_jurusan">Jurusan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="nama_jurusan" name="nama_instansi_pendidikan[4][jurusan]" placeholder="Jurusan">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="thn_masuk1">Tahun Masuk</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" id="thn_masuk1" name="nama_instansi_pendidikan[4][tahun_masuk]" placeholder="Tahun Masuk" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="thn_lulus1">Tahun Lulus</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" id="thn_lulus1" name="nama_instansi_pendidikan[4][tahun_lulus]" placeholder="Tahun Lulus" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="nama_instansi_pendidikan[4][jenis_pendidikan]" value="Pasca Sarjana">
                                        <h2 class="card-inside-title">Universitas</h2>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nama_universitas2">Nama Universitas</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="nama_instansi_pendidikan[3][nama]" name="nama_universitas2" placeholder="Nama Universitas" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="jurusan2">Jurusan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="nama_instansi_pendidikan[3[jurusan]" id="jurusan2" placeholder="Jurusan" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="thn_masuk2">Tahun Masuk</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="nama_instansi_pendidikan[3][tahun_masuk]" id="thn_masuk2" placeholder="Tahun Masuk" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="th_lulus2">Tahun Lulus</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="nama_instansi_pendidikan[3][tahun_lulus]" id="th_lulus2" placeholder="Tahun Lulus" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="nama_instansi_pendidikan[3][jenis_pendidikan]" value="Universitas">
                                        <h2 class="card-inside-title">Sekolah Menegah Atas/Kejuruan (SMA/SMK)</h2>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nama_sekolah1">Nama Sekolah</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                      <input type="text" class="form-control" name="nama_instansi_pendidikan[2][nama]" id="nama_sekolah1" placeholder="Nama Sekolah" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="nama_jurusan3">Jurusan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="nama_instansi_pendidikan[2][jurusan]" id="nama_jurusan3" placeholder="Jurusan" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="thn_masuk3">Tahun Masuk</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="nama_instansi_pendidikan[2][tahun_masuk]" id="thn_masuk3" placeholder="Tahun Masuk" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="thn_lulus3">Tahun Lulus</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" id="thn_lulus3" name="nama_instansi_pendidikan[2][tahun_lulus]" placeholder="Tahun Lulus" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="nama_instansi_pendidikan[2][jenis_pendidikan]" value="sma/smk">
                                        <h2 class="card-inside-title">Sekolah Menegah Pertama (SMP)</h2>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nama_sekolah2">Nama Sekolah</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="nama_instansi_pendidikan[1][nama]" id="nama_sekolah2" placeholder="Nama Sekolah" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="thn_masuk5">Tahun Masuk</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="nama_instansi_pendidikan[1][tahun_masuk]" id="thn_masuk5" placeholder="Tahun Masuk" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="thn_lulus5">Tahun Lulus</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="nama_instansi_pendidikan[1][tahun_lulus]" id="thn_lulus5" placeholder="Tahun Lulus" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="nama_instansi_pendidikan[1][jurusan]" value="">
                                        <input type="hidden" name="nama_instansi_pendidikan[1][jenis_pendidikan]" value="smp">
                                        <h2 class="card-inside-title">Sekolah Dasar SD</h2>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="nama_sekolah4">Nama Sekolah</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="nama_instansi_pendidikan[5][nama]" id="nama_sekolah4" placeholder="Nama Sekolah" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="thn_masuk6">Tahun Masuk</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="nama_instansi_pendidikan[5][tahun_masuk]" id="thn_masuk6" placeholder="Tahun Masuk" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="thn_lulus6">Tahun Lulus</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="nama_instansi_pendidikan[5][tahun_lulus]" id="thn_lulus6" placeholder="Tahun Lulus" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="nama_instansi_pendidikan[5][jurusan]" value="" >
                                        <input type="hidden" name="nama_instansi_pendidikan[5][jenis_pendidikan]" value="sd">

                                        <button class="btn btn-primary nextBtn float-right" type="button">Next</button>
                                    </div>
                                </div>
                                
                                <div class="card setup-content" id="step-5">
                                    <div class="card-body">
                                        <h2 class="card-inside-title">Riwayat Pekerjaan I</h2>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nama_perusahaan1">Nama Perusahaan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[1][nama_perusahaan]" placeholder="Nama Perusahaan" id="nama_perusahaan1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="posisi1">Posisi</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[1][posisi]" placeholder="Posisi" id="posisi1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="deskripsi1">Deskripsi Pekerjaan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[1][deskripsi]" placeholder="Deskripsi Pekerjaan" id="deskripsi1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="lama_bekerja1">Lama Bekerja</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="perusahaan[1][lama_bekerja]" placeholder="Lama Bekerja" id="lama_bekerja1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="alasan1">Alasan Berhenti</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[1][alasan_berhenti]" placeholder="Alasan Berhenti" id="alasan1">
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="perusahaan[1][id_form]"  value="1">
                                        </div>
                                        <h2 class="card-inside-title">Riwayat Pekerjaan II</h2>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nama_perusahaan2">Nama Perusahaan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[2][nama_perusahaan]" placeholder="Nama Perusahaan" id="nama_perusahaan2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="posisi2">Posisi</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[2][posisi]" placeholder="Posisi" id="posisi2">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="deskripsi2">Deskripsi Pekerjaan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[2][deskripsi]" placeholder="Deskripsi Pekerjaan" id="deskripsi2">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="lama2">Lama Bekerja</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="perusahaan[2][lama_bekerja]" placeholder="Lama Bekerja" id="lama2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="alasan2">Alasan Berhenti</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[2][alasan_berhenti]" placeholder="Alasan Berhenti" id="alasan2">
                                                    </div>
                                                </div>
                                            </div>  
                                            <input type="hidden" name="perusahaan[2][id_form]"  value="2">
                                        </div>
                                        <h2 class="card-inside-title">Riwayat Pekerjaan III</h2>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nama_perusahaan3">Nama Perusahaan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[3][nama_perusahaan]" placeholder="Nama Perusahaan" id="nama_perusahaan3">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="posisi3">Posisi</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[3][posisi]"  placeholder="Posisi" id="posisi3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="deskripsi3">Deskripsi Pekerjaan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[3][deskripsi]" placeholder="Deskripsi Pekerjaan" id="deskripsi3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="lama3">Lama Bekerja</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="perusahaan[3][lama_bekerja]" placeholder="Lama Bekerja" id="lama3">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="alasan3">Alasan Berhenti</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[3][alasan_berhenti]" placeholder="Alasan Berhenti" id="alasan3">
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="perusahaan[3][id_form]"  value="3">
                                        </div>
                                        <h2 class="card-inside-title">Riwayat Pekerjaan IV</h2>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nama_perusahaan4">Nama Perusahaan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[4][nama_perusahaan]" placeholder="Nama Perusahaan" id="nama_perusahaan4">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="posisi4">Posisi</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[4][posisi]"  placeholder="Posisi" id="posisi4">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="deskripsi4">Deskripsi Pekerjaan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[4][deskripsi]" placeholder="Deskripsi Pekerjaan" id="deskripsi4">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="lama4">Lama Bekerja</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="perusahaan[4][lama_bekerja]" placeholder="Lama Bekerja" id="lama4">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="alasan4">Alasan Berhenti</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[4][alasan_berhenti]" placeholder="Alasan Berhenti" id="alasan4">
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="perusahaan[4][id_form]"  value="4">
                                        </div>
                                        <h2 class="card-inside-title">Riwayat Pekerjaan V</h2>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nama_perusahaan5">Nama Perusahaan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[5][nama_perusahaan]" placeholder="Nama Perusahaan" id="nama_perusahaan5">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="posisi5">Posisi</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[5][posisi]"  placeholder="Posisi" id="posisi5">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="deskripsi5">Deskripsi Pekerjaan</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[5][deskripsi]" placeholder="Deskripsi Pekerjaan" id="deskripsi5">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="lama5">Lama Bekerja</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="number" class="form-control" name="perusahaan[5][lama_bekerja]" placeholder="Lama Bekerja" id="lama5">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="alasan5">Alasan Berhenti</label>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="perusahaan[5][alasan_berhenti]" placeholder="Alasan Berhenti" id="alasan5">
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="perusahaan[5][id_form]" value="5">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/jquery.validate.js"></script>
<script src='<?php echo base_url();?>assets/js/struktur.js'></script>
<script>
    var urlInsert = "<?php echo base_url('Administrator/pegawai/insert'); ?>";
    var path = "<?php echo base_url();?>";
    var form = $("#contact");

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
        $('#unit').change(function() {
          $.LoadingOverlay("show");
          var e = $('#unit').val();
          $.ajax({
            success: function(html){
              $('#struktur_organisasi').load(path+"Administrator/pegawai/get_level/"+e);
              $.LoadingOverlay("hide");
            }
          });
        });

        $('#status_pegawai').change(function() {
          $.LoadingOverlay("show");
          var e = $("#status_pegawai option:selected").text();
          $.ajax({
            success: function(html){
              if (e != 'Tetap') {
                const rawHTML = '<label for="tanggal_akhir"><span class="text-danger">*</span> Tanggal Berakhir Kontrak</label>\
                  <div class="form-group">\
                      <div class="form-line">\
                          <input type="date" class="form-control" required id="tanggal_akhir" name="tanggal_akhir" placeholder="Tanggal Berakhir Kontrak">\
                      </div>\
                  </div>';
                $('#tanggalAkhir').html(rawHTML);
              } else {
                $('#tanggalAkhir').html('');
              }
              $.LoadingOverlay("hide");
            }
          });
        });

        $('#contact').on('submit',(function(e) {
          swal({
            title: "Info",
            text: "Proses telah selesai, apakah setuju ?",
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
                url     : urlInsert,
                data    : formData,
                contentType: false,
                processData: false,
                success: function(data, status, xhr) {
                  try {
                    var result = JSON.parse(xhr.responseText);
                    if (result.status) {
                      swal(result.message, {
                        icon: "success",
                      }).then((acc) => {
                        window.location = "<?php echo base_url();?>Administrator/pegawai";
                      });
                    } else {
                      swal("Warning!", result.message, "warning");
                    }
                  } catch (e) {
                    swal("Warning!", "Terjadi kesalahan sistem", "warning");
                  }
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
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
                curInputs = curStep.find("input[type='text'],#alamat_user, input[type='textarea'],input[type='email'],input[type='url'],input[type='number'],select,input[type='date'],input[type='password'],input[type='radio']"),
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
