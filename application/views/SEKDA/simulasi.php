<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Simulasi Pemotongan TTP</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <?php echo $this->session->flashdata('pesan'); ?>
                        <div class="body">
                            <h2 class="card-inside-title">Tipe Keterangan</h2>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <select class="form-control show-tick" id="tipe_keterangan">
                                        <option value="">-- Please select --</option>
                                        <option value="1">Keterlambatan</option>
                                        <option value="2">Tidak Masuk Karena Sakit (Tanpa Surat Dokter)</option>
                                        <option value="3">Tidak Masuk Karena Izin</option>
                                        <option value="4">Tidak Masuk Tanpa Keterangan</option>
                                        <option value="5">Pulang Cepat Karena Sakit (Tanpa Surat Dokter)</option>
                                        <option value="6">Pulang Cepat Karena Izin</option>
                                        <option value="7">Pulang Cepat Tanpa Keterangan</option>
                                        <option value="8">Tidak Mengikuti Apel Karena Sakit (Tanpa Surat Dokter)</option>
                                        <option value="9">Tidak Mengikuti Apel Karena Izin</option>
                                        <option value="10">Tidak Mengikuti Apel Tanpa Keterangan</option>
                                    </select>
                                </div>
                            </div>
                            <div id="detail_tipeKeterangan"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
    var path = '<?php echo base_url();?>';
    $(document).ready(function(){
        $('#tipe_keterangan').change(function() {
            var e = $('#tipe_keterangan').val();
            $.ajax({
                success: function(html){
                    $('#detail_tipeKeterangan').load(path+"Administrator/pegawai/simulasi/"+e);
                }
            });
        });
    });
</script>
