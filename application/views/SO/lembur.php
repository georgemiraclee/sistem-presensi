<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Lembur Pegawai</h2>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#defaultModal">
                                <i class="material-icons">add</i>
                                <span>Tambah Lembur</span>
                            </button>
                        </div>
                        <?php echo $this->session->flashdata('pesan'); ?>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>NIP</th>
                                            <th>Nama Staff</th>
                                            <th>Tanggal Lembur</th>
                                            <th>Lama Lembur</th>
                                            <th width="40">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data_cuti as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $value->nip;?></td>
                                                <td><?php echo ucwords($value->nama_user);?></td>
                                                <<td><?php echo date('Y-m-d', strtotime($value->tanggal_lembur));?></td>
                                                <td><?php echo $value->lama_lembur;?> Jam</td>
                                                <td>
                                                    <a href="#" style="color: grey" onclick="selectDetail(<?php echo $value->user_id; ?>)" data-toggle="modal" data-target="#updateModal"><span class="material-icons">mode_edit</span></a>
                                                    <a href="#" style="color: grey" data-type="ajax-loader" onclick="hapus(<?php echo $value->user_id;?>)"><span class="material-icons">delete</span></a>
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
        </div>
    </section>

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
                                <label for="email_address_2">Pilih Pegawai</label>
                            </div>
                            <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="user_id" data-live-search="true" required>
                                            <option></option>
                                            <?php echo $list_pegawai;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="email_address_2">Tanggal Lembur</label>
                            </div>
                            <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" name="tanggal_lembur" id="tanggalLembur" class="form-control" placeholder="Please choose a date...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="email_address_2">Lama Lembur</label>
                            </div>
                            <div class="col-lg-6 col-md-7 col-sm-5 col-xs-4">
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="lama_lembur" placeholder="Lama Lembur">
                                    </div>
                                    <span class="input-group-addon">Jam</span>
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

    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Tambah Data</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="update-form" action="javascript:void(0);" method="post">
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="email_address_2">Pegawai</label>
                            </div>
                            <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="user_id" readonly class="form-control">
                                        <input type="hidden" id="user_id2" name="user_id" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="email_address_2">Tanggal Lembur</label>
                            </div>
                            <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" id="tanggal_lembur" name="tanggal_lembur" class="form-control" placeholder="Please choose a date...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="email_address_2">Lama Lembur</label>
                            </div>
                            <div class="col-lg-6 col-md-7 col-sm-5 col-xs-4">
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" id="lama_lembur" class="form-control" name="lama_lembur" placeholder="Lama Lembur">
                                    </div>
                                    <span class="input-group-addon">Jam</span>
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

<script type="text/javascript">
    $(document).ready(function(){
        $('#tanggalLembur').bootstrapMaterialDatePicker({
            format : 'YYYY-MM-DD', 
            minDate : new Date(),
            time : false
        });
        $('#tanggal_lembur').bootstrapMaterialDatePicker({
            format : 'YYYY-MM-DD', 
            minDate : new Date(),
            time : false
        });
    });
</script>

<script>
    var urlInsert = "<?php echo base_url('SO/lembur/insert'); ?>";
    var urlUpdate = "<?php echo base_url('SO/lembur/update'); ?>";
    var urlAktif = "<?php echo base_url('SO/lembur/update_status'); ?>";
    var urlSelect = "<?php echo base_url('SO/lembur/select'); ?>";
    var urlDelete = "<?php echo base_url('SO/lembur/delete'); ?>";
    var urlACC = "<?php echo base_url('SO/cuti/update_status'); ?>";

    function selectDetail(id) {
        $.ajax({
            method  : 'POST',
            url     : urlSelect,
            data    : {
                id: id
            },
            success: function(data, status, xhr) {
                try {
                    var result = JSON.parse(xhr.responseText);

                    if (result.status == true) {
                        var dataResult = result.data;
                        var date = formatDate(dataResult.tanggal_lembur);

                        $('#user_id').val(dataResult.nama_user);
                        $('#user_id2').val(dataResult.user_id);
                        $('#tanggal_lembur').val(date);
                        $('#lama_lembur').val(dataResult.lama_lembur);
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
    }
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
                    user_id: id
                },
                success: function(data, status, xhr) {
                    location.reload();
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                }
            });
          }
      });
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
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

        $('#update-form').on('submit',(function(e) {
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
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }));
    });
</script>