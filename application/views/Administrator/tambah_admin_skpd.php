<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Tambah admin_skpd</h2>
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="body">
                            <form enctype="multipart/form-data" id="form_validation" method="POST" action="javascript:void(0);">
                                <?php echo $this->session->flashdata('pesan'); ?>
                                <div class="form-group form-float">
                                    <h4 class="form-label">Info Pribadi</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> ID Alat <i class="col-red" style="font-size: 11px;"></i></label>
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="id_alat" name="id_alat" required>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> NIP <i class="col-red" style="font-size: 11px;">(digunakan untuk akses login)</i></label>
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="nip" name="nip" required>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Password <i class="col-red" style="font-size: 11px;">(digunakan untuk akses login)</i></label>
                                                    <div class="form-line">
                                                        <input type="password" class="form-control" name="password" required>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Nama Pegawai</label>
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="nama_user" required>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Jenis Kelamin</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="jenis_kelamin">
                                                            <option></option>
                                                            <option value="l">Laki-Laki</option>
                                                            <option value="p">Perempuan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Tempat Lahir</label>
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="tempat_lahir">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Tanggal Lahir</label>
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Agama</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="agama">
                                                            <option></option>
                                                            <option value="islam">Islam</option>
                                                            <option value="protestan">Prostestan</option>
                                                            <option value="katolik">Katolik</option>
                                                            <option value="hindu">Hindu</option>
                                                            <option value="budha">Budha</option>
                                                            <option value="khonghucu">Khonghucu</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Status Pernikahan</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="status_pernikahan">
                                                            <option></option>
                                                            <option value="lajang">Lajang</option>
                                                            <option value="menikah">Menikah</option>
                                                            <option value="janda">Janda</option>
                                                            <option value="duda">Duda</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <h4 class="form-label">Info Pribadi</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div class="form-group form-float">
                                                    <label class="form-label">Nomor Telepon</label>
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="telp_user">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Email</label>
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="email_user">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Alamat</label>
                                                    <div class="form-line">
                                                        <textarea rows="4" class="form-control no-resize" name="alamat_user"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Status Pekerjaan</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" id="status_user" name="status_user" required>
                                                            <option></option>
                                                            <?php foreach ($status_staff as $key => $value) { ?>
                                                                <option value="<?php echo $value->id_status_user;?>"><?php echo $value->nama_status_user;?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Struktur Organisasi</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" id="id_struktur" name="id_struktur" required>
                                                            <option></option>
                                                            <?php foreach ($posisi as $key => $value) { ?>
                                                                <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> SKPD</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" id="skpd" name="skpd" required>
                                                            <option></option>
                                                            <?php foreach ($skpd as $key => $value) { ?>
                                                                <option value="<?php echo $value->id_skpd;?>"><?php echo $value->nama_skpd;?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="struktur_organisasi"></div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Foto Staff</label>
                                                    <div class="form-line">
                                                        <input type="file" name="userfile">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Aktif</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="is_aktif">
                                                            <option></option>
                                                            <option value="1">Aktif</option>
                                                            <option value="0">Nonaktif</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="list_ttp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                <button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url();?>Administrator/admin_skpd'"><span class="fa fa-ban"></span> Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
    $(document).ready(function(){
        $('#status_user').change(function() {
            var e = $('#status_user').val();
            $.ajax({
                success: function(html){
                    $('#list_ttp').load(path+"Administrator/admin_skpd/list_ttp/"+e);
                }
            });
        });
    });
    var path = "<?php echo base_url();?>";
</script>
<script src='<?php echo base_url();?>assets/js/struktur.js'></script>
<script>
    var urlInsert = "<?php echo base_url('Administrator/admin_skpd/insert'); ?>";
    $(document).ready(function() {
        $('#form_validation').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            if ($("#form_validation").valid()) {
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
                                icon: "success",
                              }).then((acc) => {
                                window.location='<?php echo base_url();?>Administrator/admin_skpd';
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
            }
        }));
    });
</script>