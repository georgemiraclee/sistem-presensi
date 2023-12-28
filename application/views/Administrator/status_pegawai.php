<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Data Status Pegawai</h1>
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
                            <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addData"><i class="fa fa-plus"></i> Tambah Status Pegawai</button>
                        </div>
                        <div class="card-body">
                            <table id="tbl_status_user" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="1">No</th>
                                        <th>Nama Status Pegawai</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $key => $value) : ?>
                                        <?php
                                        $cekUser = $this->Db_select->select_where('tb_user', 'status_user = "' . $value->id_status_user . '"');

                                        if ($cekUser) {
                                            $delete = ' <button disabled class="btn btn-danger btn-sm text-white"><i class="fa fa-trash"></i></button>';
                                        } else {
                                            $delete = ' <button onclick="hapus(' . $value->id_status_user . ')" class="btn btn-danger btn-sm text-white"><i class="fa fa-trash"></i></button>';
                                        }
                                        if ($value->is_default) {
                                            $delete = ' <button disabled class="btn btn-danger btn-sm text-white"><i class="fa fa-trash"></i></button>';
                                            $edit = '<button disabled class="btn btn-info btn-sm text-white"><i class="fa fa-pencil-alt"></i></button>';
                                        } else {
                                            $delete = ' <button onclick="hapus(' . $value->id_status_user . ')" class="btn btn-danger btn-sm text-white"><i class="fa fa-trash"></i></button>';
                                            $edit = '<button onclick="updateData(' . $value->id_status_user . ')" class="btn btn-info btn-sm text-white"><i class="fa fa-pencil-alt"></i></button>';
                                        }

                                        $is_aktif = "";
                                        if ($value->is_aktif == 1) {
                                            $is_aktif = 'checked';
                                        }
                                        ?>
                                        <tr>
                                            <td><?= $key + 1; ?></td>
                                            <td><?= $value->nama_status_user; ?></td>
                                            <td>
                                                <input data-bootstrap-switch name="my-checkbox" id="is_aktif<?= $value->id_status_user; ?>" data-id-status-user="<?= $value->id_status_user; ?>" type="checkbox" <?= $is_aktif ? 'checked' : null; ?> <?= $value->is_default ? 'disabled' : null; ?>>
                                            </td>
                                            <td><?= $edit . $delete; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
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
                <h5 class="modal-title" id="addDataLabel">Tambah Data Status Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email_address_2"><span class="text-danger">*</span> Nama Status</label>
                        <input type="text"  name="nama_status_user" id="nama_status_user"class="form-control" placeholder="Nama Status" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="clearbtn" class="btn btn-sm btn-danger" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
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
                <h5 class="modal-title" id="updateDataLabel">Ubah Data Status Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="update-form" action="javascript:void(0);" method="post">
                <input type="hidden" id="id_status_user" name="id_status_user">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_status_userUpdate"><span class="text-danger">*</span> Nama Status</label>
                        <input type="text" id="nama_status_userUpdate" name="nama_status_user" class="form-control" placeholder="Nama Status" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><span class="fa fa-save"></span> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    var urlInsert = "<?= base_url('Administrator/status_pegawai/insert'); ?>";
    var urlUpdate = "<?= base_url('Administrator/status_pegawai/update'); ?>";
    var urlAktif = "<?= base_url('Administrator/status_pegawai/update_status'); ?>";
    var urlDelete = "<?= base_url('Administrator/status_pegawai/delete'); ?>";

    $(document).ready(function() {
        $("input[data-bootstrap-switch]").each(function(_, element) {
            $(element).bootstrapSwitch('state', $(element).prop('checked'));

            $(element).on('switchChange.bootstrapSwitch', {
                id_status_user: $(element).attr('data-id-status-user')
            }, toggleActiveStatus);
        });

        $('#tbl_status_user').DataTable({
            "order": [
                [0, "asc"]
            ],
            "columnDefs": [{
                    "orderable": false,
                    "targets": 1
                },
                {
                    "orderable": false,
                    "targets": 2
                },
                {
                    "orderable": false,
                    "targets": 3
                },
            ]
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
                        swal("Warning!", "Terjadi kesalahan sistem", "warning");
                    }
                },
                error: function(data) {
                    swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }));

        const nama_status_user = document.getElementById("nama_status_user");
        const clearbtn = document.getElementById("clearbtn");

        clearbtn.addEventListener("click", function(){
            nama_status_user.value="";
        });

        function toggleActiveStatus(e) {
            $.ajax({
                method: 'POST',
                url: urlAktif,
                async: true,
                data: {
                    id_status_user: e.data.id_status_user,
                    is_aktif: document.querySelector('#is_aktif' + e.data.id_status_user).checked ? 1 : 0,
                },
                error: function(data) {
                    swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }

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
                            id_status_user: id
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
        $.get("<?= base_url(); ?>Administrator/status_pegawai/detail/" + id, function(data, status, xhr) {
            const res = JSON.parse(xhr.responseText)

            if (res.status) {
                $('#id_status_user').val(res.data.id_status_user)
                $('#nama_status_userUpdate').val(res.data.nama_status_user)
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