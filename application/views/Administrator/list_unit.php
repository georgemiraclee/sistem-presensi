<style>
    .error {
        color: red;
    }

    .btn-danger:hover {
        color: red;
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
                    <h1 class="m-0 text-dark">Data Unit Kerja</h1>
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
                            <a href="<?php echo base_url(); ?>Administrator/unit/add" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Tambah Unit Kerja</a>
                        </div>
                        <div class="card-body">
                            <table id="tbl_jabatan" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="1">No</th>
                                        <th width="400">Nama Unit Kerja</th>
                                        <th width="400">Logo Unit Kerja</th>
                                        <th width="400">Alamat Unit Kerja</th>
                                        <th>Status</th>
                                        <th width="400">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $key => $value) : ?>
                                        <?php
                                        $cekUser = $this->Db_select->select_where('tb_user', 'id_unit = "' . $value->id_unit . '"');

                                        if ($cekUser) {
                                            $delete = ' <button onclick="hapus(' . $value->id_unit . ')" class="btn btn-danger btn-sm text-white" title="Hapus"><i class="fa fa-trash"></i></button>';
                                        } else {
                                            $delete = ' <button onclick="hapus(' . $value->id_unit . ')" class="btn btn-danger btn-sm text-white" title="Hapus"><i class="fa fa-trash"></i></button>';
                                        }

                                        $is_aktif = "";
                                        if ($value->is_aktif == 1) {
                                            $is_aktif = 'checked';
                                        }
                                        ?>
                                        <tr>
                                            <td><?= $key + 1; ?></td>
                                            <td><?= $value->nama_unit; ?></td>
                                            <td><?= $value->icon_unit; ?></td>
                                            <td><?= $value->alamat_unit; ?></td>
                                            <td><input type="checkbox" name="my-checkbox" <?= $is_aktif ? 'checked' : null; ?> data-id-unit="<?= $value->id_unit; ?>" id="is_aktif<?= $value->id_unit; ?>" data-bootstrap-switch></td>
                                            <td><a href="<?= base_url(); ?>Administrator/unit/edit/<?= $value->id_unit; ?>" class="btn btn-primary btn-sm text-grey" title="Edit"><i class="fa fa-pencil-alt"></i></a><?= $delete; ?></td>
                                            <?php
                                            $delete = ' <button onclick="hapus(' . $value->id_unit . ')" class="btn btn-danger btn-sm text-white"><i class="fa fa-trash"></i></button>';
                                            $is_aktif = "";
                                            if ($value->is_aktif == 1) {
                                                $is_aktif = 'checked';
                                            }
                                            ?>
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


<script>
    var urlInsert = "<?php echo base_url('Administrator/unit/insert'); ?>";
    var urlUpdate = "<?php echo base_url('Administrator/unit/update'); ?>";
    var urlAktif = "<?php echo base_url('Administrator/unit/update_status'); ?>";
    var urlDelete = "<?php echo base_url('Administrator/unit/delete'); ?>";

    $(document).ready(function() {
        $("input[data-bootstrap-switch]").each(function(_, element) {
            $(element).bootstrapSwitch('state', $(element).prop('checked'));

            $(element).on('switchChange.bootstrapSwitch', {
                id_unit: $(element).attr('data-id-unit')
            }, toggleActiveStatus);

            function toggleActiveStatus(event) {
                $.ajax({
                    method: 'POST',
                    url: urlAktif,
                    async: true,
                    data: {
                        id_unit: event.data.id_unit,
                        is_aktif: document.querySelector('#is_aktif' + event.data.id_unit).checked ? 1 : 0,
                    },
                    success: function(data, status, xhr) {},
                    error: function(data) {
                        swal("Warning!", "Terjadi kesalahan sistem", "warning");
                    }
                });
            }
        });

        $('#tbl_jabatan').DataTable({
            "order": [
                [0, "asc"]
            ],
            "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                },
                {
                    "orderable": false,
                    "targets": 4
                },
                {
                    "orderable": false,
                    "targets": 5
                },
            ],
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
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
                            id_unit: id
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
</script>