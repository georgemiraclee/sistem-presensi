<style>
    .ms-container {
        width: 100% !important;
    }
    .error{
        color: red;
    }
    .form-label {
    margin-left:  25px;
    margin-inline: 10px;
    }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Edit Unit Kerja</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form enctype="multipart/form-data" id="form_validation" method="POST"  action="javascript:void(0);">                                
                            <div class="card-body">
                                <?php echo $this->session->flashdata('pesan'); ?>                                
                                <div class="row">                                    
                                    <div class="col-md-12">                                        
                                        <div class="col-md-12">                                            
                                            <div class="col-md-12">                                               
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="text-danger">*</i> Nama Unit Kerja</label>
                                                    <div class="form-line">
                                                        <input type="hidden" class="form-control" name="id_unit" value="<?php echo $data_unit->id_unit;?>" required>
                                                        <input type="text" class="form-control" name="nama_unit" value="<?php echo $data_unit->nama_unit;?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="text-danger">*</i> Alamat Unit Kerja</label>
                                                    <div class="form-line">
                                                        <textarea class="form-control" name="alamat_unit" required><?php echo $data_unit->alamat_unit;?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Icon Unit Kerja<i style="color: red; font-size: 10px;">*(1,5 MB (PNG, JPG, JPEG, GIF))</i></label>
                                                    <div class="form-line">
                                                        <input type="file" name="userfile">
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">List Area Absensi</label>
                                                    <label class="col-md-6 mb-3 text-right ml-5 mr-4">List Area Absensi</label>
                                                    <select id="optgroup" name="data_lokasi[]" class="ms" multiple="multiple">
                                                        <optgroup label="Data Area Absensi">
                                                            <?php foreach ($area_absensi as $key => $value) { ?>
                                                                <option value="<?php echo $value->id_lokasi;?>" id="lokasi<?php echo $value->id_lokasi;?>"><?php echo $value->nama_lokasi;?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                    </select>
                                                    <p style="color: red; font-size: 10px;">Klik pilihan area dari kotak bagian kiri ke kotak bagian kanan untuk mengaktifkan batasan jaringan</p>
                                                </div>
                                                <div class="form-row">
                                                    <label class="col-md-4 mb-3">List Jaringan Absensi</label>
                                                    <label class="col-md-4 mb-3 text-right ml-4">List Jaringan Absensi</label>
                                                    <select id="optgroup2" class="ms" name="data_jaringan[]" multiple="multiple">
                                                        <optgroup label="Data Area Absensi">
                                                            <?php foreach ($jaringan_absensi as $key => $value) { ?>
                                                                <option value="<?php echo $value->id_jaringan;?>" id="jaringan<?php echo $value->id_jaringan;?>"><?php echo $value->ssid_jaringan;?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                    </select>
                                                    <p style="color: red; font-size: 10px;">Klik pilihan jaringan dari kotak bagian kiri ke kotak bagian kanan untuk mengaktifkan batasan jaringan</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="float-right">
                                    <a href="<?php echo base_url();?>Administrator/unit" class="btn btn-danger"><span class="fa fa-ban"></span> Batal</a>
                                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    var path = "<?php echo base_url();?>";
    var urlUpdate = "<?php echo base_url('Administrator/unit/update'); ?>";
    $(document).ready(function() {
        $('#optgroup').multiSelect({ selectableOptgroup: true });
        $('#optgroup2').multiSelect({ selectableOptgroup: true });
        $('#optgroup').multiSelect('select', <?php echo json_encode($unit_area);?>);
        $('#optgroup2').multiSelect('select', <?php echo json_encode($unit_jaringan);?>);

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
                                    window.location='<?php echo base_url();?>Administrator/unit';
                                });
                            } else {
                                swal("Warning!", result.message, "warning");
                            }
                        } catch (e) {
                          swal("Warning!", "Sistem error.", "warning");
                        }
                    }, error: function(data) {
                      swal("Warning!", "Sistem error.", "warning");
                    }
                });
            }
        }));
    });
</script>