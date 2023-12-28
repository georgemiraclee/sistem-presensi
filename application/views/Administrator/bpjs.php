<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>BPJS & PPH21</h2>
            </div>
            <div class="row clearfix">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               BPJS & PPH21
                                <small>Perhitumhan nilai potong BPJS keteagakerjaan , BPJS kesehatan & PPH21</small>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu float-right">
                                        <li><a href="javascript:void(0);" class=" waves-block">Action</a></li>
                                        <li><a href="javascript:void(0);" class=" waves-block">Another action</a></li>
                                        <li><a href="javascript:void(0);" class=" waves-block">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                                <li role="presentation" class="active"><a href="#home" data-toggle="tab" aria-expanded="false">BPJS Ketenagakerjaan</a></li>
                                <li role="presentation" class=""><a href="#profile" data-toggle="tab" aria-expanded="false">BPJS Kesehatan</a></li>
                                <li role="presentation" class=""><a href="#messages" data-toggle="tab" aria-expanded="false">PPH 21</a></li>

                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="home">
                                    <b>
                                        Dasar Perhitungan Iuran BPJS Ketenagakerjaan
                                    </b>
                                    <p>
                                        Tarif iuran bulanan dihitung berdasarkan program yang dimiliki oleh bpjs ketenagakerjaan yang meliputi:
                                        <br>
                                        -   Program jaminan hari tua (JHT)<br>
                                        -   Program jaminan keselamatan kerja (JKK)<br>
                                        -   Program jaminan pensiun (JP)<br>
                                        -   Program jaminan Kematian (JKM)<br>
                                        <br>
                                    </p>
                                    <p>
                                        <b>Ketentuan perhitungannya sebagai berikut: </b>   <br>
                                        1.  Batas maksimum dasar perhitungan iuran PPU adalah Rp 8.000.000.<br>
                                        2.  Batas minimum upah sebagai dasar perhitungan adalah Upah Minimum Kota (UMK)/Upah Minimum Regional (UMR)/Upah Minum Provinsi (UMP).<br>
                                        3.  Jika upah karyawan di antara upah minimum dan Rp 8.000.000, iurannya dihitung dari upah karyawan.
                                        4.  Jika upah karyawan kurang dari upah minimum, iurannya dihitung dari upah minimum. Bukan dari total upah karyawan.<br>
                                        5.  Jika updah karyawan lebih dari nilai maksimum RP 8.000.000, iuran dihitung dari upah maksimum yaitu RP 8.000.000. bukan dari total upah karyawan.<br>
                                    </p>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Nama Karyawan</th>
                                                    <th>Gaji Pokok </th>
                                                    <th>Iuran JKK</th>
                                                    <th>JKK(%)</th>
                                                    <th>Iuran JKM</th>
                                                    <th>JKM(%)</th>
                                                    <th>Iuran JHT Perusahaan</th>
                                                    <th>JHT(%)</th>
                                                    <th>Iuran JHT</th>
                                                    <th>JHT(%)</th>
                                                    <th>Iuran JP Perusahaan</th>
                                                    <th>JP(%)</th>
                                                    <th>Iuran JP</th>
                                                    <th>JP(%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $table_kt?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Nama Karyawan</th>
                                                    <th>Gaji Pokok </th>
                                                    <th>Iuran JKK</th>
                                                    <th>JKK(%)</th>
                                                    <th>Iuran JKM</th>
                                                    <th>JKM(%)</th>
                                                    <th>Iuran JHT Perusahaan</th>
                                                    <th>JHT(%)</th>
                                                    <th>Iuran JHT</th>
                                                    <th>JHT(%)</th>
                                                    <th>Iuran JP Perusahaan</th>
                                                    <th>JP(%)</th>
                                                    <th>Iuran JP</th>
                                                    <th>JP(%)</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="profile">
                                    <b> Dasar Perhitungan Iuran BPJS Ketenagakerjaan</b>
                                    <p>
                                        Dasar perhitungan iuran BPJS Kesehatan adalah upah karyawan yang merupakan jumlah dari gaji pokok ditambah tunjangan tetap. Ketentuan perhitungannya sebagai berikut :
                                        <br>
                                       -    Batas maksimum dasar perhitungan iuran PPU adalah Rp 8.000.000 (bukan lagi berdasar 2 x Pendapatan Tidak Kena Pajak (PTKP)/K1)
                                        -   Batas minimum upah sebagai dasar perhitungan adalah Upah Minimum Kota (UMK)/Upah Minimum Regional (UMR)/Upah Minum Provinsi (UMP)
                                        -   Jika upah karyawan di antara upah minimum dan Rp 8.000.000, iurannya dihitung dari upah karyawan
                                        <br>
                                        <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Nama Karyawan</th>
                                                    <th>Gaji Pokok </th>                                              
                                                    <th>Iuran JK Perusahaan</th>
                                                    <th>JK(%)</th>
                                                    <th>Iuran JK</th>
                                                    <th>JK(%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $table_bpjs?>
                                            </tbody>
                                            <tfoot>
                                                <tr>

                                                    <th>Nama Karyawan</th>
                                                    <th>Gaji Pokok </th>                                              
                                                    <th>Iuran JK Perusahaan</th>
                                                    <th>JK(%)</th>
                                                    <th>Iuran JK</th>
                                                    <th>JK(%)</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    </p>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="messages">
                                    <b>PPH 21</b>
                                    <p>
                                        Perhitungan PPh 21 2016 selalu disesuaikan dengan tarif PTKP (Penghasilan Tidak Kena Pajak) terbaru yang ditetapkan DJP. PTKP 2016 ( PTKP terbaru ) yang tercantum pada Peraturan Direktur Jenderal Pajak Nomor PER-32/PJ/2015 adalah sebagai berikut:
                                        <br>
                                        <br>
                                        1.  Rp 54.000.000,- per tahun atau setara dengan Rp 4.500.000,- per bulan untuk wajib pajak orang pribadi.<br>
                                        2.  Rp   4.500.000,- per tahun atau setara dengan Rp    375.000,- per bulan tambahan untuk wajib pajak yang kawin (tanpa tanggungan).<br>
                                        3.  Rp   4.500.000,- per tahun atau setara dengan Rp    375.000,- per bulan tambahan untuk setiap anggota keluarga sedarah dan keluarga semenda dalam garis keturunan lurus atau anak angkat, yang menjadi tanggungan sepenuhnya, paling banyak 3 (orang) untuk setiap keluarga.<br>
                                        <br>
                                        <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Nama Karyawan</th>
                                                    <th>Gaji Pokok </th>                                              
                                                    <th>Bruto</th>
                                                    <th>Neto</th>
                                                    <th>PTKP</th>
                                                    <th>PTP</th>
                                                    <th>PPH 21 (Tahun)</th>
                                                    <th>PPH 21 (Bulan)</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>

                                                    <th>Nama Karyawan</th>
                                                    <th>Gaji Pokok </th>                                              
                                                    <th>Bruto</th>
                                                    <th>Neto</th>
                                                    <th>PTKP</th>
                                                    <th>PTP</th>
                                                    <th>PPH 21 (Tahun)</th>
                                                    <th>PPH 21 (Bulan)</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                               <?php echo $table;?>

                                            </tbody>
                                        </table>
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
    <script type="text/javascript">
        $('#myTable').DataTable( {
    responsive: true
} );
    </script>