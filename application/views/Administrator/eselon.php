<style type="text/css">
    .dt-buttons {
        display: none;
    }
    <?php foreach ($data_eselon as $key => $value) { ?>
        <?php if ($value->delete != true) { ?>
            #hapus<?php echo $key;?> {
                pointer-events: none;
                opacity: 0.4;
            }
        <?php } ?>
    <?php } ?>
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Eselon</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <button type="button" class="btn btn-
                            " data-toggle="modal" data-target="#defaultModal">
                                <i class="material-icons">add</i>
                                <span>Tambah Eselon</span>
                            </button>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th width="1">No</th>
                                            <th width="400">Nama Eselon</th>
                                            <th width="400">Posisi</th>
                                            <th width="200">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data_eselon as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $key+1;?></td>
                                                <td><?php echo $value->nama_eselon;?></td>
                                                <td><?php echo $value->nama_struktur;?></td>
                                                <td>
                                                    <a href="#" style="color: grey" data-toggle="modal" data-target="#updateModal<?php echo $value->id_eselon ?>"><span class="material-icons">mode_edit</span></a>
                                                    <a href="#" id="hapus<?php echo $key;?>" style="color: grey" data-type="ajax-loader" onclick="hapus(<?php echo $value->id_eselon;?>)"><span class="material-icons">delete</span></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Insert eselon -->
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
                                        <label for="email_address_2">Nama Eselon</label>
                                    </div>
                                    <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="nama_eselon" class="form-control" placeholder="Nama Eselon">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="email_address_2">Posisi</label>
                                        </div>
                                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="form-control" name="id_struktur">
                                                        <option></option>
                                                        <?php foreach ($data_struktur as $key => $valuez) {?>
                                                            <option value="<?php echo $valuez->id_struktur;?>"><?php echo ucwords($valuez->nama_struktur);?></option>
                                                        <?php } ?>
                                                    </select>
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
            <!-- Modal Update eselon -->
            <?php foreach ($data_eselon as $key => $value) { ?>
                <div class="modal fade" id="updateModal<?php echo $value->id_eselon;?>" tabindex="-1" role="dialog">
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
                                            <label for="email_address_2">Nama eselon</label>
                                        </div>
                                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="nama_eselon" value="<?php echo $value->nama_eselon;?>" class="form-control" placeholder="Nama eselon">
                                                    <input type="hidden" name="id_eselon" value="<?php echo $value->id_eselon;?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="email_address_2">Posisi</label>
                                        </div>
                                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select class="form-control" name="id_struktur">
                                                        <option></option>
                                                        <?php foreach ($data_struktur as $key => $valuez) {?>
                                                            <option <?php if ($valuez->id_struktur == $value->id_struktur) { echo 'selected';}?> value="<?php echo $valuez->id_struktur;?>"><?php echo ucwords($valuez->nama_struktur);?></option>
                                                        <?php } ?>
                                                    </select>
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
    var urlInsert = "<?php echo base_url('Administrator/eselon/insert'); ?>";
    var urlUpdate = "<?php echo base_url('Administrator/eselon/update'); ?>";
    var urlAktif = "<?php echo base_url('Administrator/eselon/update_status'); ?>";
    var urlDelete = "<?php echo base_url('Administrator/eselon/delete'); ?>";
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
                    id_eselon: id
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
        var id_eselon = id;
        if(inputElements.checked){
            checkedValue = 1;
        }
        $.ajax({
            method  : 'POST',
            url     : urlAktif,
            async   : true,
            data    : {
                is_aktif: checkedValue,
                id_eselon: id_eselon
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
        <?php foreach ($data_eselon as $key => $value) { ?>
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