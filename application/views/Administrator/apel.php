<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Jadwal Apel</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#defaultModal">
                                <i class="material-icons">add</i>
                                <span>Tambah Jadwal Apel</span>
                            </button>
                        </div>
                        <div class="body">
                            <?php echo $this->session->flashdata('pesan'); ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable display" id="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Apel</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Durasi Absen</th>
                                            <th>Lokasi</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Insert Jabatan -->
            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Tambah Data</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form enctype="multipart/form-data" method="POST" action="<?php echo base_url();?>Administrator/apel/insert">
                        <div class="modal-body">
                            <div class="form-group form-float">
                                <label class="form-label">Nama Apel</label>
                                <div class="form-line">
                                    <input type="text" class="form-control" name="nama_apel" value="" required>
                                </div>
                                 <label class="form-label">Tanggal</label>
                                <div class="form-line">
                                    <input type="text" class="datepicker2  form-control" name="tanggal_apel" required>  
                                </div>
                                 <label class="form-label">Jam mulai</label>
                                <div class="form-line">
                                    <input type="text" class="timepicker  form-control" name="jam_mulai" required>  
                                </div>
                                 <label class="form-label">Durasi Absen</label>
                                <div class="form-line">
                                    <select class="form-control" name="durasi_absen" required>
                                            <option></option>
                                            <option value="10">10 menit</option>
                                            <option value="15">15 menit</option>
                                            <option value="20">20 menit</option>
                                            <option value="25">25 menit</option>
                                            <option value="30">20 menit</option>
                                    </select>   
                                </div>
                                <label class="form-label">Lokasi</label>
                                <div class="form-line">
                                    <select class="form-control" name="id_lokasi" required>
                                                        <option></option>
                                                        <?php foreach ($data_area as $key => $valuez) {?>
                                                            <option value="<?php echo $valuez->id_lokasi;?>"><?php echo ucwords($valuez->nama_lokasi);?></option>
                                                        <?php } ?>
                                                    </select>
                                </div>
                                <label class="form-label">Deskripsi</label>
                                <div class="form-line">
                                   <textarea rows="4" class="form-control no-resize" name="deskripsi_apel"></textarea>
                                </div>                                                       
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <!-- Modal Update Jabatan -->
            <?php foreach ($data_apel as $key => $value) { ?>
                <div class="modal fade" id="updateModal<?php echo $value->id_apel;?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="defaultModalLabel">Ubah Data</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                             <form enctype="multipart/form-data" method="POST" action="<?php echo base_url();?>Administrator/apel/update">
                             <div class="modal-body">
                                <div class="form-group form-float">
                                    <input type="hidden" name="id_apel" value="<?php echo $value->id_apel?>">
                                    <label class="form-label">Nama Apel</label>
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="nama_apel" value="<?php echo $value->nama_apel?>" required>
                                    </div>
                                     <label class="form-label">Tanggal</label>
                                    <div class="form-line">
                                        <input type="text" class="datepicker2  form-control" name="tanggal_apel" value="<?php echo date('Y-m-d', strtotime($value->tanggal_apel));?>" required>  
                                    </div>
                                    <label class="form-label">Jam mulai</label>
                                <div class="form-line">
                                    <input type="text" class=" timepicker form-control" name="jam_mulai" value="<?php echo $value->jam_mulai?>" required>  
                                </div>
                                 <label class="form-label">Durasi Absen</label>
                                <div class="form-line">
                                    <input type="text" class="form-control" name="jam_selesai" value="<?php echo $value->durasi_absen?> Menit" readonly required>  
                                </div>
                                    <label class="form-label">Lokasi Absen</label>
                                    <div class="form-line">
                                         <select class="form-control" name="id_lokasi" required>
                                        <?php foreach ($data_area as $key => $valuez) {?>
                                                            <option <?php if ($valuez->id_lokasi == $value->id_lokasi) { echo 'selected';}?> value="<?php echo $valuez->id_lokasi;?>"><?php echo ucwords($valuez->nama_lokasi);?></option>
                                                        <?php } ?>
                                         <select class="form-control" name="id_lokasi" required>
                                    </div>
                                    <label class="form-label">Deskripsi</label>
                                    <div class="form-line">
                                       <textarea rows="4" class="form-control no-resize" name="deskripsi_apel"><?php echo $value->deskripsi_apel?></textarea>
                                    </div>                                                       
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
    <script type="text/javascript">
    $('.datepicker2').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
    });
    var urlDelete = "<?php echo base_url('Administrator/apel/delete'); ?>";
    function hapus(id) {
      swal({
          title: "Apakah anda yakin ?",
          text: "Ketika data telah dihapus, tidak bisa dikembalikan lagi!",
          icon: "info",
          buttons: true,
          dangerMode: true,
      })
      .then((willDelete) => {
          if (willDelete) {
            $.ajax({
                method  : 'POST',
                url     : urlDelete,
                async   : true,
                data    : {
                    id_apel: id
                },
                success: function(data, status, xhr) {
                  swal(result.message, {
                      icon: "success",
                  }).then((acc) => {
                      location.reload();
                  });
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                }
            });
          }
      });
    }
</script>
<script type="text/javascript">
    var table;
    $(document).ready(function() {  
        //datatables
        table = $('#table').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            "ajax": {
                "url": "<?php echo site_url('Administrator/Apel/get_data_user')?>",
                "type": "POST",
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columnDefs": [
                { "orderable": false, "targets": 0 },
                { "orderable": false, "targets": 7 },
            ],
            lengthMenu: [
                        [ 10, 25, 50, -1 ],
                        [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                        ],
            dom: "<'row'<'col-md-6'l><'col-md-6'f>><'row'<'col-md-12'B>><'row'<'col-md-12't>><'row'<'col-md-6'i><'col-md-6'p>>",
              buttons: [
                  'copy', 'excel', 'pdf', 'print'
              ],
      select: true,
        });
    });
</script>