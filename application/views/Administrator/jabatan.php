<style>
    .btn-danger:hover {
        color: white;
        background-color: #8b0000;
        border-color: #8b0000;
    }

    .btn-primary:hover {
        color: white;
        background-color: darkblue;
        border-color: blue;
    }

    .btn-primary .fa-plus:hover {
        color: blue;
        background-color: white;
        border-color: white;
    }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Data Jabatan</h1>
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
                            <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addData"><i class="fa fa-plus"></i> Tambah Jabatan</button>
                        </div>
                        <div class="card-body">
                            <table id="tbl_jabatan" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="1">No</th>
                                        <th width="400">Nama Jabatan</th>
                                        <th>Status</th>
                                        <th width="200">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $key => $value) : ?>
                                        <?php
                                        $cekUser = $this->Db_select->select_where('tb_user', 'jabatan = "' . $value->id_jabatan . '"');

                                        if ($cekUser) {
                                            $delete = ' <button onclick="hapus(' . $value->id_jabatan . ')" class="btn btn-danger btn-outline text-white" title="hapus"><i class="fa fa-trash"></i></button>';
                                        } else {
                                            $delete = ' <button onclick="hapus(' . $value->id_jabatan . ')" class="btn btn-danger btn-outline text-white" title="Hapus"><i class="fa fa-trash"></i></button>';
                                        }

                                        $delete = ' <button onclick="hapus(' . $value->id_jabatan . ')" class="btn btn-danger btn-sm text-white" title="Hapus"><i  class="fa fa-trash"></i></button>';
                                        $is_aktif = "";

                                        if ($value->is_aktif == 1) {
                                            $is_aktif = 'checked';
                                        }
                                        ?>
                                        <tr>
                                            <td><?= $key + 1; ?></td>
                                            <td><?= $value->nama_jabatan; ?></td>
                                            <td><input type="checkbox" name="my-checkbox" <?= $is_aktif ? 'checked' : null; ?> data-id-jabatan="<?= $value->id_jabatan ?>" id="is_aktif<?= $value->id_jabatan; ?>" data-bootstrap-switch></td>
                                            <td><button onclick="updateData(<?= $value->id_jabatan; ?>)" class="btn btn-primary btn-sm text-grey" title="Edit"><i class="fa fa-pencil-alt"></i></button><?= $delete; ?></td>
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
                <h5 class="modal-title" id="addDataLabel">Tambah Data Jabatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tambah_jabatan"><span class="text-danger">*</span> Nama Jabatan</label>
                        <i style="color: red; font-size: 10px;">*Max 60 Karakter</i>
                        <input type="text" id="tambah_jabatan" name="nama_jabatan" maxlength="60" class="form-control" placeholder="Nama Jabatan" required>
                        <p id="hasil_jabatan" style="text-align:right"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="clearbtn" class="btn btn-danger btn-sm" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
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
                <h5 class="modal-title" id="updateDataLabel">Ubah Data Jabatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="update-form" action="javascript:void(0);" method="post">
                <input type="hidden" id="id_jabatan" name="id_jabatan">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_jabatan"><span class="text-danger">*</span> Nama Jabatan</label>
                        <i style="color: red; font-size: 10px;">*Max 60 Karakter</i>
                        <input type="text" id="nama_jabatan" name="nama_jabatan" maxlength="60" class="form-control" placeholder="Nama Jabatan" required>
                        <p id="result" style="text-align:right"></p>
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
    var urlInsert = "<?php echo base_url('Administrator/jabatan/insert'); ?>";
    var urlUpdate = "<?php echo base_url('Administrator/jabatan/update'); ?>";
    var urlAktif = "<?php echo base_url('Administrator/jabatan/update_status'); ?>";
    var urlDelete = "<?php echo base_url('Administrator/jabatan/delete'); ?>";

    $(document).ready(function() {
        $("input[data-bootstrap-switch]").each(function(_, element) {
            $(element).bootstrapSwitch('state', $(element).prop('checked'));
            $(element).on('switchChange.bootstrapSwitch', {
                id_jabatan: $(element).attr('data-id-jabatan'),
            }, toggleActiveStatus);
        });

        $('#tbl_jabatan').DataTable({
            "order": [
                [0, "asc"]
            ],
            "columnDefs": [{
                    "orderable": false,
                    "targets": 2
                },
                {
                    "orderable": false,
                    "targets": 3
                },
            ],
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
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
        
        const tambah_jabatan = document.getElementById("tambah_jabatan");
        const clearbtn = document.getElementById ("clearbtn");

        clearbtn.addEventListener("click", function(){
            tambah_jabatan.value = "";
        });
        
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

        function toggleActiveStatus(event) {
            $.ajax({
                method: 'POST',
                url: urlAktif,
                async: true,
                data: {
                    id_jabatan: event.data.id_jabatan,
                    is_aktif: document.querySelector('#is_aktif' + event.data.id_jabatan).checked ? 1 : 0,
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
                            id_jabatan: id
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

    var nama_jabatan = document.getElementById("nama_jabatan");
    var result = document.getElementById("result");
    var limit = 60;
    result.textContent = 0 + "/" + limit;

    nama_jabatan.addEventListener("input", function() {
        var textLength = nama_jabatan.value.length;
        result.textContent = textLength + "/" + limit;

        if (textLength > limit) {
            nama_jabatan.style.borderColor = "#ff2851";
            result.style.color = "#ff2851";
        } else {
            nama_jabatan.style.borderColor = "#b2b2b2";
            result.style.color = "#737373";
        }
    });
    var tambah_jabatan = document.getElementById("tambah_jabatan");
    var hasil_jabatan = document.getElementById("hasil_jabatan");
    var limit = 60;
    hasil_jabatan.textContent = 0 + "/" + limit;

    tambah_jabatan.addEventListener("input", function() {
        var textLength = tambah_jabatan.value.length;
        hasil_jabatan.textContent = textLength + "/" + limit;

        if (textLength > limit) {
            tambah_jabatan.style.borderColor = "#ff2851";
            hasil_jabatan.style.color = "#ff2851";
        } else {
            tambah_jabatan.style.borderColor = "#b2b2b2";
            hasil_jabatan.style.color = "#737373";
        }
    });

    function updateData(id) {
        $.get("<?php echo base_url(); ?>Administrator/jabatan/detail/" + id, function(data, status, xhr) {
            const res = JSON.parse(xhr.responseText)

            if (res.status) {
                $('#id_jabatan').val(res.data.id_jabatan)
                $('#nama_jabatan').val(res.data.nama_jabatan)
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