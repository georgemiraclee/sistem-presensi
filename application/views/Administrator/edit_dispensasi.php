<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Edit Dispensasi</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <label class="form-label"><i class="text-danger">*</i> Pegawai</label>
                                    <div class="form-line">
                                        <input type="hidden" name="id_dispensasi" value="<?php echo $akses_absensi->id_dispensasi; ?>">
                                        <select class="form-control" data-live-search="true" id="user_id" name="user_id" disabled>
                                            <option></option>
                                            <?php foreach ($list_pegawai as $key => $value) { ?>
                                                <option <?php if ($value->user_id == $akses_absensi->user_id) {
                                                            echo 'selected';
                                                        } ?> value="<?php echo $value->user_id; ?>"><?php echo $value->nama_user; ?> (<?php echo $value->nip; ?>)</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <label class="form-label"><i class="text-danger">*</i> Tanggal Dispensasi</label>
                                    <div class="form-line">
                                        <input type="date" name="tanggal_dispensasi" class="form-control" value="<?php echo $akses_absensi->tanggal_dispensasi; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <label class="form-label"><i class="text-danger">*</i> Jam Awal</label>
                                    <div class="form-line">
                                        <input type="time" name="jam_awal_dispensasi" class="form-control" value="<?php echo date('H:i', strtotime($akses_absensi->jam_awal_dispensasi)); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <label class="form-label"><i class="text-danger">*</i> Jam Akhir</label>
                                    <div class="form-line">
                                        <input type="time" name="jam_akhir_dispensasi" class="form-control" value="<?php echo date('H:i', strtotime($akses_absensi->jam_akhir_dispensasi)); ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-right">
                            <button class="btn btn-secondary" type="button" onclick="window.location='<?php echo base_url(); ?>Administrator/dispensasi'"><span class="fa fa-ban"></span> Batal</button>
                            <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    var urlUpdate = "<?php echo base_url('Administrator/dispensasi/update'); ?>";

    $(document).ready(function() {
        $('#add-form').on('submit', (function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                method: 'POST',
                url: urlUpdate,
                data: formData,
                contentType: false,
                processData: false,
                success: function(data, status, xhr) {
                    try {
                        var result = JSON.parse(xhr.responseText);

                        if (result.status == true) {
                            swal(result.message, {
                                icon: "success",
                                title: "Success",
                                text: "Data saved successfully",
                            }).then((acc) => {
                                window.location = '<?php echo base_url(); ?>Administrator/dispensasi';
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