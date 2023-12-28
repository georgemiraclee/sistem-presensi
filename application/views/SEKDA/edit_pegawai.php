<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Tambah Pegawai</h2>
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
                                                    <label class="form-label"><i class="col-red">*</i> NIP</label>
                                                    <div class="form-line">
                                                        <script>
                                                            $(document).ready(function(){
                                                                $("#nip").change(function(){
                                                                    
                                                                      var x = document.getElementById("nip").value;
                                                                        var taun= x.substring(0,4);         
                                                                        var bulan = x.substring(4,6);
                                                                        var tanggal = x.substring(6,8);
                                                                        
                                                                        if( taun < 1000){
                                                                            var ket = "";
                                                                             $('#tanggal_lahir').val(ket);
                                                                            document.getElementById("tanggal_lahir").disabled = false;
                                                                        }else{
                                                                            var lahir = String(taun) + "-" + String(bulan)+"-"+String(tanggal);
                                                                            $('#tanggal_lahir').val(lahir);
                                                                            document.getElementById("tanggal_lahir").disabled = false;
                                                                        }        
                                                                });
                                                            });
                                                        </script>
                                                        <input type="text" id="nip" class="form-control" name="nip" required value="<?php echo $data_staff->nip;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Password <i class="col-red" style="font-size: 11px;">(lewati jika tidak ingin mengganti password)</i></label>
                                                    <div class="form-line">
                                                        <input type="password" class="form-control" name="password">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Nama Pegawai</label>
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="nama_user" required value="<?php echo $data_staff->nama_user;?>">
                                                        <input type="hidden" name="user_id" value="<?php echo $data_staff->user_id;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Jenis Kelamin</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="jenis_kelamin">
                                                            <option></option>
                                                            <?php echo $list_jenkel;?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Tempat Lahir</label>
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="tempat_lahir" value="<?php echo $data_staff->tempat_lahir;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Tanggal Lahir</label>
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Please choose a date..." value="<?php echo $data_staff->tanggal_lahir;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Agama</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="agama">
                                                            <option></option>
                                                            <?php echo $list_agama;?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Status Pernikahan</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" name="status_pernikahan">
                                                            <option></option>
                                                            <?php echo $list_status_pernikahan;?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Bekerja Sejak Tanggal ?</label>
                                                    <div class="form-line">
                                                        <input type="date" name="tanggal_kerja" value="<?php echo $data_staff->tanggal_kerja;?>" class="form-control" required>
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
                                                    <label class="form-label"><i class="col-red">*</i> Nomor Telepon</label>
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="telp_user" required value="<?php echo $data_staff->telp_user;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Email</label>
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" name="email_user" required value="<?php echo $data_staff->email_user;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Alamat</label>
                                                    <div class="form-line">
                                                        <textarea rows="4" class="form-control no-resize" required name="alamat_user"><?php echo $data_staff->alamat_user;?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Status Pekerjaan</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" id="status_user" name="status_user" required>
                                                            <option></option>
                                                            <?php echo $list_status_pekerjaan;?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Posisi</label>
                                                    <div class="form-line">
                                                        <select class="form-control show-tick" id="struktur" name="id_struktur" required>
                                                            <option></option>
                                                            <?php foreach ($posisi as $key => $value) { ?>
                                                                <option value="<?php echo $value->id;?>" <?php if ($value->id == $data_staff->id_struktur): echo 'selected' ?><?php endif ?>><?php echo $value->name;?></option>
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
                                                            <?php echo $list_is_aktif;?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Gaji Pokok</label>
                                                    <div class="form-line">
                                                        <input type="number" min="1" onKeyPress="return goodchars(event,'0123456789',this)" name="gaji_pokok" class="form-control" placeholder="Gaji Pokok" required="" value="<?php echo $data_staff->gaji_pokok;?>">
                                                    </div>
                                                </div>
                                                <!-- <div id="list_ttp"></div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                <button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url();?>Administrator/pegawai'"><span class="fa fa-ban"></span> Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src='<?php echo base_url();?>assets/js/struktur.js'></script>
<script>
    var path = "<?php echo base_url();?>";
    $(document).ready(function(){
        var e = $('#struktur').val();
        var f = "<?php echo $data_staff->nip;?>";
        $.ajax({
            success: function(html){
                $('#struktur_organisasi').load(path+"Administrator/pegawai/get_level/"+e+"/"+f);
            }
        });
        var x = $('#status_user').val();
        var y = "<?php echo $data_staff->user_id;?>";
        $.ajax({
            success: function(html){
                $('#list_ttp').load(path+"Administrator/pegawai/list_ttpNew/"+x+"/"+y);
            }
        });
        $('#status_user').change(function() {
            var e = $('#status_user').val();
            var d = "<?php echo $data_staff->user_id;?>";
            $.ajax({
                success: function(html){
                    $('#list_ttp').load(path+"Administrator/pegawai/list_ttpNew/"+e+"/"+d);
                }
            });
        });
    });
</script>
<script>
    var urlUpdate = "<?php echo base_url('Administrator/pegawai/update'); ?>";
    $(document).ready(function() {
        $('#form_validation').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            if ($("#form_validation").valid()) {
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
                                window.location='<?php echo base_url();?>Administrator/pegawai';
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