<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Tunjangan Jabatan</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive">
                                <table id="tbl_tunjangan" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th width="1">No</th>
                                            <th width="400">Nama Jabatan</th>
                                            <th width="400">Tunjangan Jabatan</th>
                                            <th width="400">Tunjangan Pakdin</th>
                                            <th width="200">Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Update tunjangan -->
            <?php foreach ($data_tunjangan as $key => $value) { ?>
                <div class="modal fade" id="updateModal<?php echo $value->id_jabatan;?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="defaultModalLabel">Ubah Data</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" id="update-form<?php echo $key;?>" action="javascript:void(0);" method="post">
                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="email_address_2">Nama Jabatan</label>
                                        </div>
                                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="nama_jabatan" value="<?php echo $value->nama_jabatan;?>" class="form-control" placeholder="Nama Jabatan">
                                                    <input type="hidden" name="id_jabatan" value="<?php echo $value->id_jabatan;?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                        if ($value->tunjangan_jabatan == null || $value->tunjangan_jabatan == "") {
                                            $value->tunjangan_jabatan = 0;
                                        }

                                        if ($value->tunjangan_pakdin == null || $value->tunjangan_pakdin == "") {
                                            $value->tunjangan_pakdin = 0;
                                        }
                                    ?>
                                    
                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="email_address_2">Tunjangan Jabatan</label>
                                        </div>
                                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" name="tunjangan_jabatan" value="<?php echo $value->tunjangan_jabatan;?>" class="form-control" placeholder="Tunjangan Jabatan" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="email_address_2">Tunjangan Pakdin</label>
                                        </div>
                                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" name="tunjangan_pakdin" value="<?php echo $value->tunjangan_pakdin;?>" class="form-control" placeholder="Tunjangan Pakdin" required>
                                                </div>
                                            </div>
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
    <script>
    var urlUpdate = "<?php echo base_url('Administrator/tunjangan/update'); ?>";
    
    /* Datatable Serverside */
    $(document).ready(function() {
        var dataTable = $('#tbl_tunjangan').DataTable({
            "dom": 'Bfrtip',
            "responsive": true,
            // "processing" : true,
            "serverSide": true,
            "order" : [[1,"asc"]],
            "ajax": {
                url: '<?php echo base_url("Administrator/tunjangan/getData");?>',
                type: 'POST',
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columnDefs": [
                {"searchable": false, "orderable": false, "targets": 0},
                {"orderable": false, "targets": 4},
            ],
            "columns" : [
                {"data": "no"},
                {"data": "nama_jabatan"},
                {"data": "tunjangan_jabatan"},
                {"data": "tunjangan_pakdin"},
                {"data": "aksi"}
            ],
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });

    $(document).ready(function() {
        <?php foreach ($data_tunjangan as $key => $value) { ?>
            $('#update-form<?php echo $key;?>').on('submit',(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    method  : 'POST',
                    url     : urlUpdate,
                    data    : formData,
                    contentType: false,
                    processData: false,
                    success: function(data, status, xhr) {
                        try {
                            var result = JSON.parse(xhr.responseText);
                            if (result.status == true) {
                              swal(result.message, {
                                icon: "success",
                              }).then((acc) => {
                                location.reload();
                              });
                            } else {
                              swal("Warning!", result.message, "warning");
                            }
                        } catch (e) {
                          swal("Warning!", "Sistem error.", "warning");
                        }
                    },
                    error: function(data) {
                      swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                    }
                });
            }));
        <?php } ?>
    });
</script>