<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Potongan & Tunjangan Kinerja</h2>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <?php echo $this->session->flashdata('pesan'); ?>
                        <div class="body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Tunjangan Kinerja<button type="button" class="btn btn-block btn-lg btn-info">Rp<?php echo $saldo;?></button></h5> 
                                </div>
                                <div class="col-md-6"> 
                                    <h5>Jumlah Potongan<button type="button" class="btn btn-block btn-lg btn-warning">Rp<?php echo $semua_potongan?></button></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Apel</th>
                                            <th>Terlambat</th>
                                            <th>Batal Absen</th>
                                            <th>Mangkir</th>
                                            <th>Alfa</th>
                                            <th>Jumlah</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php echo $tabel;?>
                                        
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

