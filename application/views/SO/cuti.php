<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Pengajuan Cuti</h2>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <?php echo $this->session->flashdata('pesan'); ?>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama Staff</th>
                                            <th>Tanggal Awal Cuti</th>
                                            <th>Tanggal AKhir Cuti</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th width="40">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data_cuti as $key => $value) { 
                                            if ($value->status_approval == 1) {
                                                $value->status_approval = '<span class="badge bg-green">Approval</span>';
                                            }elseif ($value->status_approval == 2) {
                                                $value->status_approval = '<span class="badge bg-orange">Rejected</span>';
                                            }else{
                                                $value->status_approval = '<span class="badge bg-red">Butuh Konfirmasi</span>';
                                            }
                                        ?>
                                            <tr>
                                                <td><?php echo $value->nip;?></td>
                                                <td><?php echo ucwords($value->nama_user);?></td>
                                                <td><?php echo date('d-m-Y', strtotime($value->tanggal_awal_pengajuan));?></td>
                                                <td><?php echo date('d-m-Y', strtotime($value->tanggal_akhir_pengajuan));?></td>
                                                <td><?php echo $value->keterangan_pengajuan;?></td>
                                                <td><?php echo $value->status_approval;?></td>
                                                <td><a href="<?php echo base_url();?>SO/cuti/detail_cuti/<?php echo $value->id_pengajuan;?>">
                                                <button class="btn bg-green btn-block btn-xs">Detail</button></a></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

