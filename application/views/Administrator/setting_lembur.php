<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;">
                                <h2>Pengaturan Lembur</h2>
                            </div>
                        </div>                            
                    </div>
                    <?php echo $this->session->flashdata('pesan'); ?>
                    <div class="body">
                        <form id="form_validation" method="POST" action="javascript:void(0);">
                            <label for="email_address">Pengaturan Lembur</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="komponen_lembur" required>
                                        <option value="">-- Please select --</option>
                                        <option value="semua">All</option>
                                        <option value="departemen">Unit Kerja</option>
                                        <option value="jabatan">Jabatan</option>
                                        <option value="status_pegawai">Status Pegawai</option>
                                    </select>
                                </div>
                            </div>
                                <i style="color: red; font-size: 10px;">(*) Pemberlakuan perhitungan lembur berdasarkan komponen yang dipilih</i><br>
                                <i style="color: red; font-size: 10px;">(**) Komponen yang telah terpilih akan menjadi perhitungan lembur default (untuk melakukan perubahan harap hubungi ...)</i>
                            <button type="submit" class="btn btn-block btn-lg btn-primary"><span class="fa fa-save"></span> Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var urlInsert = "<?php echo base_url('Administrator/admin_skpd/insert'); ?>";
    $(document).ready(function() {
        $('#form_validation').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            if ($("#form_validation").valid()) {
                
            }
        }));
    });
</script>