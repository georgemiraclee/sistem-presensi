<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Ubah Data Pemerintah</h2>
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="body">
                            <form id="form_validation" method="POST" action="javascript:void(0);">
                                <div class="form-group form-float">
                                    <label class="form-label">Logo Pemerintah</label>
                                    <div class="form-line">
                                        <input type="file" class="form-control" name="userfile">
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Nama</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="nama_channel" required placeholder="Nama Pemerintah" value="<?php echo $akun->nama_channel;?>">
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Alamat</label>
                                    <div class="form-line">
                                        <textarea rows="4" class="form-control no-resize" name="alamat_channel" placeholder="Alamat Pemerintah"><?php echo $akun->alamat_channel;?></textarea>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">No. Telp</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="telp_channel" required placeholder="Nomor Telepon" value="<?php echo $akun->telp_channel;?>">
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">No. Fax</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="fax_channel" required placeholder="Nomor Fax" value="<?php echo $akun->fax_channel;?>">
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Email Pemerintah</label>
                                    <div class="form-line">
                                        <input type="email" class="form-control" name="email_channel" required placeholder="Alamat E-mail" value="<?php echo $akun->email_channel;?>">
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Website</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="website_channel" required placeholder="Website" value="<?php echo $akun->website_channel;?>">
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <label class="form-label">Deskripsi</label>
                                    <div class="form-line">
                                        <textarea rows="4" class="form-control no-resize" name="deskripsi_channel" placeholder="Deskripsi"><?php echo $akun->deskripsi_channel;?></textarea>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                <button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url();?>Administrator/akun'"><span class="fa fa-ban"></span> Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
    var urlInsert = "<?php echo base_url('Administrator/akun/update'); ?>";

    $(document).ready(function() {
        $('#example').DataTable();

        $('#form_validation').on('submit',(function(e) {
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
                            window.location='<?php echo base_url();?>Administrator/akun';
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