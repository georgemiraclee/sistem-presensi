<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Data Pengajuan Cuti</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="table" class="table table-bordered table-striped table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIP</th>
                                        <th>Nama Staff</th>
                                        <th>Tanggal Awal Cuti</th>
                                        <th>Tanggal AKhir Cuti</th>
                                        <th>Keterangan</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($data_cuti as $key => $value) { 
                                    $nama_status = $this->Db_select->select_where('tb_status_pengajuan', ['id_status_pengajuan' => $value->status_pengajuan]);
                                    if ($value->status_approval == 1) {
                                      $value->status_approval = '<span class="badge bg-green">Approval</span>';
                                    }elseif ($value->status_approval == 2) {
                                      $value->status_approval = '<span class="badge badge-danger">Rejected</span>';
                                    }else{
                                      $value->status_approval = '<span class="text-warning">PENDING</span>';
                                    }
                                  ?>
                                    <tr>
                                      <td><?php echo ($key+1);?></td>
                                      <td><?php echo $value->nip;?></td>
                                      <td><?php echo ucwords($value->nama_user);?></td>
                                      <td><?php echo date('d-m-Y', strtotime($value->tanggal_awal_pengajuan));?></td>
                                      <td><?php echo date('d-m-Y', strtotime($value->tanggal_akhir_pengajuan));?></td>
                                      <td><?php echo $value->keterangan_pengajuan;?></td>
                                      <td><?php echo $nama_status->nama_status_pengajuan;?></td>
                                      <td><?php echo $value->status_approval;?></td>
                                      <td><a href="<?php echo base_url();?>Leader/cuti/detail_cuti/<?php echo $value->id_pengajuan;?>">
                                      <button class="btn btn-primary btn-sm"><span class="fa fa-eye"></span></button></a></td>
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
    $('#table').DataTable();
  });
</script>