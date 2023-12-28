<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark"><a href="<?php echo base_url('Administrator/data_absensi/rekap'); ?>"><i class="fas fa-chevron-left"></i></a> Rekap Jumlah Absensi <span style="font-size: 12px;">(<?php echo $start_date; ?> s/d <?php echo $end_date; ?>)</span></h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <?php
                        $filters = $this->input->get(null, true);
                        $filterUrlString = '';
                        foreach ($filters as $key => $filter) {
                            $filterUrlString .= "$key=$filter&";
                        }
                        ?>
                        <div class="card-header">
                            <a href="<?php echo base_url('Administrator/data_absensi/downloadAbsensiAkumulatif?' . $filterUrlString . ''); ?>" target="_blank" class="btn btn-primary float-right">
                                <span class="fa fa-file-export"></span> Export Data
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table" id="datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NIK</th>
                                        <th>NAMA</th>
                                        <th>JABATAN</th>
                                        <th>UNIT KERJA</th>
                                        <th>JUMLAH HADIR</th>
                                        <th>JUMLAH TIDAK HADIR</th>
                                        <th>RATA RATA HADIR</th>
                                        <th>PERSENTASE HADIR</th>
                                        <th>TOTAL JAM KERJA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($list as $key => $value) {
                                        $init = $value->total_jam ? $value->total_jam : 0;
                                        $hours = floor($init / 3600);
                                        $minutes = floor(($init / 60) % 60);
                                        $seconds = $init % 60;
                                    ?>
                                        <tr>
                                            <td><?= $key + 1; ?></td>
                                            <td><?= $value->nik; ?></td>
                                            <td><?= $value->nama_user; ?></td>
                                            <td><?= $value->nama_jabatan; ?></td>
                                            <td><?= $value->nama_unit; ?></td>
                                            <td><?= $value->total_absen ? $value->total_absen : '0'; ?></td>
                                            <td><?= $value->total_absen ? ($difDate + 1) - $value->total_absen : ($difDate + 1); ?></td>
                                            <td><?= $value->rata_rata_absensi; ?></td>
                                            <td><?= "$value->persentase_absensi%"; ?></td>
                                            <td><?= "$hours:$minutes:$seconds"; ?></td>
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