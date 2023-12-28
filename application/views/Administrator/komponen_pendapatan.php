<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Komponen Pendapatan</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#defaultModal">
                                <i class="material-icons">add</i>
                                <span>Tambah Komponen</span>
                            </button>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table id="tbl_pendapatan" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th width="1">No</th>
                                            <th width="400">Nama Pendapatan</th>
                                            <th>Status</th>
                                            <th width="200">Aksi</th>
                                        </tr>
                                    </thead>
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
                        <div class="modal-body">
                            <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                                <div class="row clearfix">
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="email_address_2">Nama Komponen</label>
                                    </div>
                                    <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="nama_komponen_pendapatan" class="form-control" placeholder="Nama Komponen Pendapatan">
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
            <!-- Modal Update Jabatan -->
            <?php foreach ($data_komponen as $key => $value) { ?>
                <div class="modal fade" id="updateModal<?php echo $value->id_komponen_pendapatan;?>" tabindex="-1" role="dialog">
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
                                            <label for="email_address_2">Nama Komponen</label>
                                        </div>
                                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="nama_komponen_pendapatan" value="<?php echo $value->nama_komponen_pendapatan;?>" class="form-control" placeholder="Nama Jabatan">
                                                    <input type="hidden" name="id_komponen_pendapatan" value="<?php echo $value->id_komponen_pendapatan;?>">
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
    var urlInsert = "<?php echo base_url('Administrator/pendapatan/insert'); ?>";
    var urlUpdate = "<?php echo base_url('Administrator/pendapatan/update'); ?>";
    var urlAktif = "<?php echo base_url('Administrator/pendapatan/update_status'); ?>";
    var urlDelete = "<?php echo base_url('Administrator/pendapatan/delete'); ?>";
    $(document).ready(function() {
        var dataTable = $('#tbl_pendapatan').DataTable({
            "dom": 'Bfrtip',
            "responsive": true,
            // "processing" : true,
            "serverSide": true,
            "order" : [[1,"asc"]],
            "ajax": {
                url: '<?php echo base_url("Administrator/pendapatan/getData");?>',
                type: 'POST',
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columnDefs": [
                {"searchable": false, "orderable": false, "targets": 0},
                {"orderable": false, "targets": 3},
            ],
            "columns" : [
                {"data": "no"},
                {"data": "nama_komponen_pendapatan"},
                {"data": "status"},
                {"data": "aksi"}
            ],
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
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
                    id_komponen_pendapatan: id
                },
                success: function(data, status, xhr) {
                  swal(result.message, {
                    icon: "success",
                  }).then((acc) => {
                    location.reload();
                  });
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
          }
      });
    }
    function is_aktif(id) {
        var checkedValue = 0;
        var inputElements = document.getElementById('is_aktif'+id);
        var id_komponen_pendapatan = id;
        if(inputElements.checked){
            checkedValue = 1;
        }
        $.ajax({
            method  : 'POST',
            url     : urlAktif,
            async   : true,
            data    : {
                is_aktif: checkedValue,
                id_komponen_pendapatan: id_komponen_pendapatan
            },
            success: function(data, status, xhr) {
            },
            error: function(data) {
              swal("Warning!", "Terjadi kesalahan sistem", "warning");
            }
        });
    }
    $(document).ready(function() {
        $('#example').DataTable();
        $('#add-form').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : urlInsert,
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
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }));
        <?php foreach ($data_komponen as $key => $value) { ?>
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