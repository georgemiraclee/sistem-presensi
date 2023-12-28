<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">
                        <a href="<?= base_url('Administrator/data_absensi/rekap'); ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a> 
                        Rekap Absensi Harian 
                        <span style="font-size: 12px;">(<?= $start_date; ?> s/d <?= $end_date; ?>)</span>
                    </h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <?php
                                $filters = $this->input->get(null, true);
                                $filterUrlString = '';
                                foreach ($filters as $key => $filter) {
                                    $filterUrlString .= "$key=$filter&";
                                }
                            ?>
                            <a href="<?= base_url('Administrator/data_absensi/downloadAbsensiHarian?' . $filterUrlString); ?>" target="_blank" class="btn btn-primary float-right"><span class="fa fa-file-export"></span> Export Data</a>
                        </div>
                        <div class="card-body">
                            <table class="table" id="datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NIK</th>
                                        <th>NAMA</th>
                                        <th>TANGGAL</th>
                                        <th>JAM MASUK</th>
                                        <th>JAM ISTIRAHAT</th>
                                        <th>JAM KEMBALI</th>
                                        <th>JAM PULANG</th>
                                        <th>Overtime</th>
                                        <th>STATUS KEHADIRAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($list as $key => $value) {
                                        $keterlambatan = "";
                                        $statusAbsensi = $value->status_absensi;
                                        if ($value->status_absensi == 'Terlambat') {
                                            $keterlambatan = "(" . $value->keterlambatan . ")";
                                        }

                                        if ($value->day_off) {
                                            $statusAbsensi = $value->status_absensi . " (" . $value->day_off_desc . ")";
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $key + 1; ?></td>
                                            <td><?php echo $value->nip; ?></td>
                                            <td><?php echo $value->nama_user; ?></td>
                                            <td><?php echo $value->created_absensi; ?></td>
                                            <td><?php echo $value->waktu_datang ? $value->waktu_datang : "-"; ?></td>
                                            <td><?php echo $value->waktu_istirahat ? $value->waktu_istirahat : "-"; ?></td>
                                            <td><?php echo $value->waktu_kembali ? $value->waktu_kembali : "-"; ?></td>
                                            <td><?php echo $value->waktu_pulang ? $value->waktu_pulang : "-"; ?></td>
                                            <td><?php echo $value->overtime ? $value->overtime : "-"; ?></td>
                                            <td><?php echo $statusAbsensi . " " . $keterlambatan; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            responsive: true
        });
    });
</script>