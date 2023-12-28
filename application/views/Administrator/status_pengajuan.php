<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Data Status Pengajuan</h1>
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
                            <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addData"><i class="fa fa-plus"></i> Tambah Status</button>
                        </div>
                        <div class="card-body">
                            <table id="tbl_status_pengajuan" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Status Pengajuan</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($status_pengajuan as $key => $value) {
                                        $aksi = "";
                                        if ($value->is_default != 1) {
                                            $cekUser = $this->Db_select->select_where('tb_pengajuan', 'status_pengajuan = "' . $value->id_status_pengajuan . '"');
                                            $aksi = '<a href="#" data-toggle="modal" onclick="updateData(' . $value->id_status_pengajuan . ')" class="btn btn-info btn-sm text-white"><span class="fa fa-pencil-alt"></span></a>';
                                            $aksi .= '&nbsp<a href="#" data-type="ajax-loader" onclick="hapus(' . $value->id_status_pengajuan . ')" class="btn btn-danger btn-sm text-white"><span class="fa fa-trash"></span></a>';
                                        }

                                        $is_default = "";

                                        if ($value->is_default == 1) {
                                            $is_default = "<span class='badge badge-warning text-white'>Default</span>";
                                        }
                                    ?>
                                        <tr>
                                            <td><?= $key + 1; ?></td>
                                            <td><?= $value->nama_status_pengajuan; ?></td>
                                            <td><input type="checkbox" name="my-checkbox" <?= $value->is_aktif ? 'checked' : null; ?> id="is_aktif<?= $value->id_status_pengajuan; ?>" data-id-status-pengajuan="<?= $value->id_status_pengajuan ?>" data-bootstrap-switch></td>
                                            <td><?= $is_default; ?></td>
                                            <td><?= $aksi; ?></td>
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

<div class="modal fade" id="addData" tabindex="-1" aria-labelledby="addDataLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDataLabel">Tambah Status Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_status_pengajuan"><span class="text-danger">*</span> Nama Status</label>
                        <input type="text" name="nama_status_pengajuan" autocomplete="off" id="nama_status_pengajuan" class="form-control" placeholder="Input nama status" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id ="clearbtn" class="btn btn-danger btn-sm" data-dismiss="modal" onclick="javascript:eraseText();"><span class="fa fa-ban"></span> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><span class="fa fa-save"></span> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateData" tabindex="-1" aria-labelledby="updateDataLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateDataLabel">Ubah Status Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="update-form" zaction="javascript:void(0);" method="post">
                <input type="hidden" id="id_status_pengajuan" name="id_status_pengajuan">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_status_pengajuanUpdate"><span class="text-danger">*</span> Nama Status</label>
                        <input type="text" id="nama_status_pengajuanUpdate" name="nama_status_pengajuan" class="form-control" placeholder="Nama Status" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><span class="fa fa-save"></span> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        var urlInsert = "<?= base_url('Administrator/status_pengajuan/insert'); ?>";
        var urlUpdate = "<?= base_url('Administrator/status_pengajuan/update'); ?>";
        var urlAktif = "<?= base_url('Administrator/status_pengajuan/toggleActiveStatusPengajuan'); ?>";
        var urlDelete = "<?= base_url('Administrator/status_pengajuan/delete'); ?>";

        var nama_status_pengajuan = "<?= $this->input->get('nama_status_pengajuan'); ?>";
        if (nama_status_pengajuan == "Input nama status") {
            $("#nama_status_pengajuan").prop('checked', true);
        }

        $("input[data-bootstrap-switch]").each(function(_, element) {
            $(element).bootstrapSwitch('state', $(element).prop('checked'));

            $(element).on('switchChange.bootstrapSwitch', {
                id: $(element).attr('data-id-status-pengajuan')
            }, toogleActiveStatusPengajuan);
        });

        $('#add-form').on('submit', (function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method: 'POST',
                url: urlInsert,
                data: formData,
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
                    swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                }
            });
        }));

        $('#update-form').on('submit', (function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method: 'POST',
                url: urlUpdate,
                data: formData,
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
                    swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                }
            });
        }));

        function toogleActiveStatusPengajuan(e) {
            $.ajax({
                method: 'POST',
                url: urlAktif,
                async: true,
                data: {
                    id: e.data.id,
                    is_aktif: document.querySelector('#is_aktif' + e.data.id).checked ? 1 : 0,
                },
                success: function(data, status, xhr) {},
                error: function(data) {
                    swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }
    });

    const nama_status_pengajuan = document.getElementById("nama_status_pengajuan");
    const clearbtn = document.getElementById("clearbtn");

    clearbtn.addEventListener("click", function(){
        nama_status_pengajuan.value ="";
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
                        method: 'POST',
                        url: urlDelete,
                        async: true,
                        data: {
                            id_status_pengajuan: id
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

    function updateData(id) {
        $.get("<?= base_url(); ?>Administrator/status_pengajuan/detail/" + id, function(data, status, xhr) {
            const res = JSON.parse(xhr.responseText)

            if (res.status) {
                $('#id_status_pengajuan').val(res.data.id_status_pengajuan)
                $('#nama_status_pengajuanUpdate').val(res.data.nama_status_pengajuan)
                $('#updateData').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                swal("Warning!", res.message, "warning");
            }
        });
    }
</script>