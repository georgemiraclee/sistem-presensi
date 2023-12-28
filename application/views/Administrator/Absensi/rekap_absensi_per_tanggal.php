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
                            <a href="<?php echo base_url('Administrator/data_absensi/downloadAbsensiPerTanggal?' . $filterUrlString . ''); ?>" target="_blank" class="btn btn-primary float-right">
                                <span class="fa fa-file-export"></span> Export Data
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table" id="datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>HARI</th>
                                        <th>TANGGAL</th>
                                        <th>TOTAL HADIR</th>
                                        <th>TOTAL TIDAK HADIR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list_absensi_per_tanggal as $key => $rekap) : ?>
                                        <tr>
                                            <td><?= $key + 1 ?></td>
                                            <td><?= $rekap->hari ?></td>
                                            <td><?= $rekap->tanggal ?></td>
                                            <td><?= $rekap->total_hadir ?></td>
                                            <td><?= $rekap->total_tidak_hadir ?></td>
                                        </tr>
                                    <?php endforeach; ?>
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