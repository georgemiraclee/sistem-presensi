<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Pengaturan Libur Perusahaan</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#smallModal"><i class="fa fa-plus"></i> Tambah Hari Libur</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover dataTable">
                                <thead>
                                  <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Event</th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody class="demo-masked-input" id="tes">
                                    <?php foreach ($tanggal as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo date('j F',strtotime($value->tanggal_event));?></td>
                                            <td><?php echo $value->nama_event;?></td>
                                            <td>
                                                <button onclick='editData(<?php echo json_encode($value);?>)' class="btn btn-info btn-sm">
                                                    <span class="fa fa-pencil-alt" ></span>
                                                </button>
                                                <a href="#" data-toggle="tooltip" data-placement="top"  id="hapus<?php echo $key;?>" title="Hapus Event" data-type="ajax-loader" onclick="hapus(<?php echo $value->id_event;?>)" class="btn btn-danger btn-sm">
                                                    <span class="fa fa-trash"></span>
                                                </a>
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
    </section>
</div>

<div class="modal fade" id="smallModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="smallModalLabel">Tambah Event</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add-form" action="javascript:void(0);" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_event">Nama Event</label>
                        <input type="text" class="form-control" name="nama_event" required id="nama_event">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_event">Tanggal Event</label>
                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
                        <input type="text" id="tanggal_event" class="form-control" name="tanggal_event" required>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><span class="fa fa-save"></span> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="smallModalLabel">Edit Event</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formUpdate" action="javascript:void(0);" method="post">
              <div class="modal-body">
                  <div class="form-group form-float form-group-sm">
                    <div class="form-line">
                        <label for="nama_eventUpdate" class="form-label">Nama Event</label>
                        <input type="hidden" class="form-control" id="id_event" name="id_event">
                        <input type="text" id="nama_eventUpdate" class="form-control" name="nama_event" required>
                      </div>
                  </div>
                  <div class="form-group form-float form-group-sm">
                      <div class="form-line">
                        <label for="tanggal_eventUpdate" class="form-label">Tanggal Event</label>
                        <input type="date" class="form-control" id="tanggal_eventUpdate" required name="tanggal_event">
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><span class="fa fa-save"></span> Simpan</button>
              </div>
            </form>
        </div>
    </div>
</div>

<script>
    var urlDelete = "<?php echo base_url('Administrator/kebijakan_absensi/delete'); ?>";
    function cb(start, end) {
        if (start._d == "Invalid Date" && end._d == "Invalid Date") {
            $('#start_date').val('');
            $('#end_date').val('');
        } else {
            dari = start.format('YYYY-MM-DD');
            end_date = end.format('YYYY-MM-DD');
            $('#start_date').val(dari);
            $('#end_date').val(end_date);
        }
    }
    
    $(document).ready(function() {
        $('#tanggal_event').daterangepicker({
            locale: {
                format: 'DD/M/YYYY'
            },
            opens: 'center',
            drops: 'down'
        }, cb);
        $('.dataTable').DataTable({
            "columnDefs": [{
                "targets": 2,
                "orderable": false
            }]
        });

        $('#formUpdate').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Administrator/kebijakan_absensi/update_event'); ?>",
                data    : formData,
                contentType: false,
                processData: false,
                success: function(data, status, xhr) {
                    try {
                        var result = JSON.parse(xhr.responseText);
                        if (result.status == true) {
                            swal(result.message, {
                                icon: "success",
                                title: "Success",
                                text: "Data saved successfully",
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
                    swal("Warning!", "Sistem error.", "warning");
                }
            });
        }));

        $('#add-form').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Administrator/kebijakan_absensi/insert_event'); ?>",
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
    });

    function hapus(id) {
        swal({
            title: "Apakah anda yakin ?",
            text: "Data yang sudah di hapus, tidak dapat di kembalikan lagi.",
            icon: "warning",
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
                        id_event: id
                    },
                    success: function(data, status, xhr) {
                        try {
                            var result = JSON.parse(xhr.responseText);
                            if (result.status) {
                                swal(result.message, {
                                    icon: "success",
                                }).then((acc) => {
                                    location.reload();
                                });
                            } else {
                                swal("Warning!", "Terjadi kesalahan sistem", "warning");
                            }
                        } catch (e) {
                            swal("Warning!", "Terjadi kesalahan sistem", "warning");
                        }
                    },
                    error: function(data) {
                        swal("Warning!", "Terjadi kesalahan sistem", "warning");
                    }
                });
            }
        });
    }

    function editData(data) {
        $("#id_event").val(data.id_event);
        $("#nama_eventUpdate").val(data.nama_event);
        $("#tanggal_eventUpdate").val(moment(data.tanggal_event).format('YYYY-MM-DD'));
        $('#editModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }
</script>