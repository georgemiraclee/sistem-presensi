<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Tambah Akses Absensi</h2>
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="body">
                            <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Pegawai</label>
                                                    <div class="form-line">
                                                        <select class="form-control" name="user_id" required>
                                                            <option></option>
                                                            <?php echo $list_pegawai;?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Lokasi Akses Absensi</label>
                                                    <select id="optgroup" class="ms" name="id_unit[]" multiple="multiple" required>
                                                        <optgroup label="Data OPD">
                                                            <?php foreach ($data_opd as $key => $value) { ?>
                                                                <option value="<?php echo $value->id_unit;?>"><?php echo $value->nama_unit;?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                    </select>
                                                </div>

                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="col-red">*</i> Tanggal Akhir Akses</label>
                                                    <div class="form-line">
                                                        <input type="text" name="tanggal_akhir" class="datepicker3 form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                <button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url();?>SO/akses_absensi'"><span class="fa fa-ban"></span> Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
    $('.datepicker3').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false,
        minDate : new Date()
    });
    var urlInsert = "<?php echo base_url('SO/akses_absensi/insert'); ?>";

    $(document).ready(function() {
        $('#example').DataTable();

        $('#add-form').on('submit',(function(e) {
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
                            icon: "success",
                          }).then((acc) => {
                            window.location='<?php echo base_url();?>SO/akses_absensi';
                          });
                        } else {
                          swal("Warning!", result.message, "warning");
                        }
                    } catch (e) {
                      swal("Warning!", "Sistem error.", "warning");
                    }
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }));
    });
</script>