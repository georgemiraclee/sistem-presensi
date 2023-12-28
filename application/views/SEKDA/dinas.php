<style type="text/css">

    .dt-buttons {

        display: none;

    }

</style>

<section class="content">

        <div class="container-fluid">

            <div class="block-header">

                <h2>Data Perjalanan Dinas Pegawai</h2>

            </div>


            <div class="row clearfix">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="card">

                        <div class="body">

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">

                                    <thead>

                                        <tr>

                                            <th width="1">NIP</th>

                                            <th width="400">Nama Pegawai</th>

                                            <th width="400">Tanggal Perjalanan</th>

                                            <th width="400">Lokasi Awal</th>

                                            <th width="200">Aksi</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php echo $list_akses_absensi;?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>