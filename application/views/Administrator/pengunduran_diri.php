<style>
    ::-webkit-scrollbar {
        -webkit-appearance: none;
        width: 7px;
    }

    ::-webkit-scrollbar-thumb {
        border-radius: 4px;
        background-color: rgba(0, 0, 0, .5);
        box-shadow: 0 0 1px rgba(255, 255, 255, .5);
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Data Pengunduran</h1>
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
                            <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addData"><i class="fa fa-plus"></i> Pengunduran Diri</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3 float-right">
                                        <button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                            <span class="fa fa-filter"></span> FILTER
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row my-3">
                                <div class="col-md-12">
                                    <div class="collapse" id="collapseExample">
                                        <div class="well" id="accOneColThree">
                                            <form method="post" id="formFilter" action="javascript:void(0);">
                                                <div class="row">
                                                    <div class="col-md-4" style="height: 200px; overflow: auto;">
                                                        <h6>Unit Kerja</h6>
                                                        <div style=" overflow: hidden;">
                                                            <?php
                                                            foreach ($departemen as $key => $value) {
                                                                $nama_unit = (strlen($value->nama_unit) > 20) ? substr($value->nama_unit, 0, 20) . '...' : $value->nama_unit;
                                                            ?>
                                                                <div class="demo-checkbox">
                                                                    <input type="checkbox" id="unit_kerja_<?php echo $value->id_unit; ?>" name="departemen[]" value="<?php echo $value->id_unit; ?>" />
                                                                    <label for="unit_kerja_<?php echo $value->id_unit; ?>"> <?php echo ucwords($nama_unit); ?></label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="height: 200px; overflow: auto;">
                                                        <h6>JABATAN</h6>
                                                        <div style=" overflow: hidden;">
                                                            <?php
                                                            foreach ($jabatan as $key => $value) {
                                                                $nama_jabatan = (strlen($value->nama_jabatan) > 20) ? substr($value->nama_jabatan, 0, 20) . '...' : $value->nama_jabatan;
                                                            ?>
                                                                <div class="demo-checkbox">
                                                                    <input type="checkbox" id="jabatan_<?php echo $value->id_jabatan; ?>" name="jabatan[]" value="<?php echo $value->id_jabatan; ?>" />
                                                                    <label for="jabatan_<?php echo $value->id_jabatan; ?>"> <?php echo ucwords($nama_jabatan); ?></label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>TIPE PEGAWAI</h6>
                                                        <div style=" overflow: hidden;">
                                                            <?php foreach ($tipe as $key => $value) { ?>
                                                                <div class="demo-checkbox">
                                                                    <input type="checkbox" id="tipe_pegawai_<?php echo $value->id_status_user; ?>" name="tipe[]" value="<?php echo $value->id_status_user; ?>" />
                                                                    <label for="tipe_pegawai_<?php echo $value->id_status_user; ?>"> <?php echo ucwords($value->nama_status_user); ?></label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary float-right">Filter</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table" id="tableData">
                                <thead>
                                    <tr>
                                        <th width="1">No</th>
                                        <th>NIK</th>
                                        <th>Nama User</th>
                                        <th>Tanggal Pengunduran</th>
                                        <th>Alasan Pengunduran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                <h5 class="modal-title" id="addDataLabel">Pengunduran Diri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                <div class="modal-body">
                    <div class="form-group form-float">
                        <label class="form-label"><span class="text-danger">*</span> Nama pegawai</label>
                        <div class="form-line">
                            <select class="form-control" name="user_id" data-live-search="true">
                                <option value="">-- Data User --</option>
                                <?php echo $option; ?>
                            </select>
                        </div>
                    </div>
                    <div id="list_ttp"></div>
                    <div class="form-group form-float">
                        <label class="form-label"><span class="text-danger">*</span> Tanggal Pengunduran Diri</label>
                        <div class="form-line">
                            <input type="date" class="form-control" name="tanggal_pengunduran_diri" required>
                        </div>
                    </div>
                    <div class="form-group form-float">
                        <label class="form-label">Alasan pengunduran diri</label>
                        <div class="form-line">
                            <textarea class="form-control" name="alasan_pengunduran"></textarea>
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

<script type="text/javascript">
    // Datatable
    var table;
    var newSkpd = "<?= $this->input->get('departemen'); ?>";
    var newjabatan = "<?= $this->input->get('jabatan'); ?>";
    var newjenkel = "<?= $this->input->get('jenkel'); ?>";
    var newstatus = "<?= $this->input->get('tipe'); ?>";

    var departemen = newSkpd;
    departemen.split(",").forEach(element => {
        $("#unit_kerja_" + element).prop('checked', true);
    });

    var jabatan = newjabatan;
    jabatan.split(",").forEach(element => {
        $("#jabatan_" + element).prop('checked', true);
    });

    var tipe = newstatus;
    tipe.split(",").forEach(element => {
        $("#tipe_pegawai_" + element).prop('checked', true);
    });

    var urlInsert = "<?php echo base_url('Administrator/History_pengunduran_diri/insert'); ?>";
    $(document).ready(function() {
        table = $('#tableData').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": '<?php echo site_url('Administrator/history_pengunduran_diri/getData') ?>?departemen=' + newSkpd + '&jabatan=' + newjabatan + '&jenkel=' + newjenkel + '&tipe=' + newstatus,
                "type": "POST",
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columnDefs": [{
                    "orderable": false,
                    "targets": 4
                },
                {
                    "orderable": false,
                    "targets": 5
                },
            ],
            "columns": [{
                    "data": "no"
                },
                {
                    "data": "nip"
                },
                {
                    "data": "nama"
                },
                {
                    "data": "tanggal"
                },
                {
                    "data": "alasan"
                },
                {
                    "data": "aksi"
                },
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            "dom": 'Bfrtip',
            "responsive": true,
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
                    swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }));

        $('#formFilter').on('submit', (function(e) {
            var url = "<?php echo base_url(); ?>";
            // Get SKPD
            var skpd = document.getElementsByName("departemen[]");
            var newSkpd = "";
            for (var i = 0, n = skpd.length; i < n; i++) {
                if (skpd[i].checked) {
                    newSkpd += "," + skpd[i].value;
                }
            }
            if (newSkpd) newSkpd = newSkpd.substring(1);
            //Get JABATAN
            var jabatan = document.getElementsByName("jabatan[]");
            var newjabatan = "";
            for (var i = 0, n = jabatan.length; i < n; i++) {
                if (jabatan[i].checked) {
                    newjabatan += "," + jabatan[i].value;
                }
            }
            if (newjabatan) newjabatan = newjabatan.substring(1);
            //Get JENKEL
            var jenkel = document.getElementsByName("jenkel[]");
            var newjenkel = "";
            for (var i = 0, n = jenkel.length; i < n; i++) {
                if (jenkel[i].checked) {
                    newjenkel += "," + jenkel[i].value;
                }
            }
            if (newjenkel) newjenkel = newjenkel.substring(1);
            //Get STATUS
            var status = document.getElementsByName("tipe[]");
            var newstatus = "";
            for (var i = 0, n = status.length; i < n; i++) {
                if (status[i].checked) {
                    newstatus += "," + status[i].value;
                }
            }
            if (newstatus) newstatus = newstatus.substring(1);
            //Get STATUS User
            url += 'Administrator/history_pengunduran_diri' + '?departemen=' + newSkpd + '&jabatan=' + newjabatan + '&jenkel=' + newjenkel + '&tipe=' + newstatus;
            // url2 += 'Administrator/data_absensi/get_data_user'+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai;
            window.location = url;
        }));
    });

    var urlRollback = "<?php echo base_url('Administrator/History_pengunduran_diri/rollback'); ?>";

    function hapus(id) {
        swal({
                title: "Apakah anda yakin",
                text: "Data yang sudah di hapus, tidak dapat di kembalikan lagi.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        method: 'POST',
                        url: urlRollback,
                        async: true,
                        data: {
                            user_id: id
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