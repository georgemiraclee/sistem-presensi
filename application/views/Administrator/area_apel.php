<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Area Apel</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#defaultModal">
                                <i class="material-icons">add</i>
                                <span>Tambah Area</span>
                            </button>
                            <a href="<?php echo base_url();?>assets/polygon/FORMAT_WILAYAH.csv" download class="btn btn-info"><i class="material-icons">save</i><span>Download Format Wilayah</span></a>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th width="1">No</th>
                                            <th width="400">Lokasi Apel</th>
                                            <th>URL File</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data_area as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $key+1;?></td>
                                                <td><?php echo $value->nama_lokasi;?></td>
                                                <td><?php echo $value->url_file_lokasi;?></td>
                                                <td>
                                                    <a href="#" style="color: grey" data-toggle="modal" data-target="#updateModal<?php echo $value->id_lokasi ?>"><span class="material-icons">mode_edit</span></a>
                                                    <?php if ($value->delete == true) { ?>
                                                        <a href="#" style="color: grey" data-type="ajax-loader" onclick="hapus(<?php echo $value->id_lokasi;?>)"><span class="material-icons">delete</span></a>
                                                    <?php } ?>
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
                                    <input type="hidden" name="is_apel" value="1">
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="email_address_2">Nama Area</label>
                                    </div>
                                    <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" name="nama_lokasi" class="form-control" placeholder="Nama Area">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="email_address_2">File Kordinat Wilayah</label>
                                    </div>
                                    <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <input type="file" name="userfile" class="form-control">
                                            <cite class="col-pink">* (csv)</cite>
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
            <?php foreach ($data_area as $key => $value) { ?>
                <div class="modal fade" id="updateModal<?php echo $value->id_lokasi;?>" tabindex="-1" role="dialog">
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
                                            <label for="email_address_2">Nama Area</label>
                                        </div>
                                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" name="nama_lokasi" value="<?php echo $value->nama_lokasi;?>" class="form-control" placeholder="Nama SSID">
                                                    <input type="hidden" name="id_lokasi" value="<?php echo $value->id_lokasi;?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="email_address_2">File Kordinat Wilayah</label>
                                        </div>
                                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <input type="file" name="userfile" class="form-control">
                                            <cite class="col-pink">* kosongkan jika tidak diubah (.csv)</cite>
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
    var urlInsert = "<?php echo base_url('Administrator/Area_apel/insert_area'); ?>";
    var urlUpdate = "<?php echo base_url('Administrator/Area_apel/update_area'); ?>";
    var urlAktif = "<?php echo base_url('Administrator/Area_apel/update_status_area'); ?>";
    var urlDelete = "<?php echo base_url('Administrator/Area_apel/delete_area'); ?>";
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
                    id_lokasi: id
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
    function is_aktif() {
        var checkedValue = 0; 
        var inputElements = document.getElementById('is_aktif');
        var id_lokasi = document.getElementById('is_aktif').value;
        if(inputElements.checked){
            checkedValue = 1;
        }
        $.ajax({
            method  : 'POST',
            url     : urlAktif,
            async   : true,
            data    : {
                is_aktif: checkedValue,
                id_lokasi: id_lokasi
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
                  swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                }
            });
        }));
        <?php foreach ($data_area as $key => $value) { ?>
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