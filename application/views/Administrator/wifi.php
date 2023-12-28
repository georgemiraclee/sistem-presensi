<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Data Wifi</h1>
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
                            <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addData"><i class="fa fa-plus"></i> Tambah Akses Wifi</button>
                        </div>
                        <div class="card-body">
                            <table id="tbl_jaringan" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="1">No</th>
                                        <th width="400">Nama SSID</th>
                                        <th width="400">Mac address</th>
                                        <th>Lokasi</th>
                                        <th width="200">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach ($list as $key => $value) { 
                                            $selectUser = $this->Db_select->select_where('tb_history_absensi','id_jaringan = '.$value->id_jaringan);

                                            if (!$selectUser) {
                                                $del = ' <button class="btn btn-danger btn-sm" title="Hapus" data-type="ajax-loader" onclick="hapus('.$value->id_jaringan.')"><i class="fa fa-trash"></i></button>';
                                            }else{
                                                $del = ' <button disabled class="btn btn-danger btn-sm" data-type="ajax-loader"><i class="fa fa-trash"></i></button>';
                                            }
                                    ?>
                                        <tr>
                                            <td><?php echo $key+1;?></td>
                                            <td><?php echo $value->ssid_jaringan;?></td>
                                            <td><?php echo $value->mac_address_jaringan;?></td>
                                            <td><?php echo $value->lokasi_jaringan;?></td>
                                            <td>
                                                <button class="btn btn-primary btn-sm text-white" title="Edit" onclick="updateData(<?php echo $value->id_jaringan;?>)"><i class="fa fa-pencil-alt"></i></button><?php echo $del;?></td>
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
                <h5 class="modal-title" id="addDataLabel">Tambah Data Akses Wifi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ssid_jaringan"><span class="text-danger">*</span> Nama SSID</label>
                        <input type="text" name="ssid_jaringan" id="ssid_jaringan" class="form-control" placeholder="Input nama SSID" required>
                    </div>
                    <div class="form-group">
                        <label for="mac_address_jaringan"><span class="text-danger">*</span> Mac Address Wifi</label>
                        <input type="text" id="mac_address_jaringan" name="mac_address_jaringan" class="form-control" placeholder="Input mac address wifi" required>
                    </div>
                    <div class="form-group">
                        <label for="lokasi_jaringan"><span class="text-danger">*</span> Lokasi Wifi</label>
                        <input type="text" id="lokasi_jaringan" name="lokasi_jaringan" class="form-control" placeholder="Input nama lokasi wifi" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id ="clearbtn" class="btn btn-danger btn-sm" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
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
                <h5 class="modal-title" id="updateDataLabel">Ubah Data Akses Wifi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="update-form" action="javascript:void(0);" method="post">
                <input type="hidden" id="id_jaringan" name="id_jaringan">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ssid_jaringan2"><span class="text-danger">*</span> Nama SSID</label>
                        <input type="text" id="ssid_jaringan2" name="ssid_jaringan" class="form-control" placeholder="Input nama SSID" required>
                    </div>
                    <div class="form-group">
                        <label for="mac_address_jaringan2"><span class="text-danger">*</span> Mac Address Wifi</label>
                        <input type="text" id="mac_address_jaringan2" name="mac_address_jaringan" class="form-control" placeholder="Input mac address wifi" required>
                    </div>
                    <div class="form-group">
                        <label for="lokasi_jaringan2"><span class="text-danger">*</span> Lokasi Wifi</label>
                        <input type="text" id="lokasi_jaringan2" name="lokasi_jaringan" class="form-control" placeholder="Input nama lokasi wifi" required>
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
    var urlInsert = "<?php echo base_url('Administrator/data_jaringan/insert_wifi'); ?>";
    var urlUpdate = "<?php echo base_url('Administrator/data_jaringan/update_wifi'); ?>";
    var urlAktif = "<?php echo base_url('Administrator/data_jaringan/update_status_wifi'); ?>";
    var urlDelete = "<?php echo base_url('Administrator/data_jaringan/delete_wifi'); ?>";

    $(document).ready(function() {
        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
        $('#tbl_jaringan').DataTable({
            "order" : [[0,"asc"]],
            "columnDefs": [
                {"searchable": false, "orderable": false, "targets": 0},
                {"orderable": false, "targets": 3},
            ],
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

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
                        id_jaringan: id
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
        $.get("<?php echo base_url();?>Administrator/data_jaringan/detailWifi/"+id, function (data, status, xhr) {
            const res = JSON.parse(xhr.responseText)

            if (res.status) {
                $('#id_jaringan').val(res.data.id_jaringan)
                $('#ssid_jaringan2').val(res.data.ssid_jaringan)
                $('#mac_address_jaringan2').val(res.data.mac_address_jaringan)
                $('#lokasi_jaringan2').val(res.data.lokasi_jaringan)
                $('#updateData').modal({backdrop: 'static', keyboard: false});
            } else {
                swal("Warning!", res.message, "warning");
            }
        });
    }
</script>
<script>
    const ssid_jaringan = document.getElementById("ssid_jaringan");
    const mac_address_jaringan = document.getElementById("mac_address_jaringan");
    const lokasi_jaringan = document.getElementById("lokasi_jaringan");
    const clearbtn = document.getElementById("clearbtn");

    clearbtn.addEventListener("click", function(){
        ssid_jaringan.value = "";
        mac_address_jaringan.value = "";
        lokasi_jaringan.value = "";
    });
</script>