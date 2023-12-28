<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Data Akses Absensi</h1>
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
                            <a href="<?php echo base_url();?>Administrator/akses_absensi/add" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Tambah Akses Pegawai</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable" id="table">
                                <thead>
                                    <tr>
                                        <th width="1">No</th>
                                        <th width="400">Nama Pegawai</th>
                                        <th width="400">Lokasi Akses Absensi</th>
                                        <th width="400">Berlaku Hingga</th>
                                        <th width="200">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
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
    var urlDelete = "<?php echo base_url('Administrator/akses_absensi/delete'); ?>";
    var urlAktif = "<?php echo base_url('Administrator/akses_absensi/update_status'); ?>";
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
                        id_akses_absensi: id
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
    function is_aktif(id) {
        var checkedValue = 0; 
        var inputElements = document.getElementById('is_aktif'+id);
        var id_pengumuman = id;
        if(inputElements.checked){
            checkedValue = 1;
        }
        
        $.ajax({
            method  : 'POST',
            url     : urlAktif,
            async   : true,
            data    : {
                is_aktif: checkedValue,
                id_pengumuman: id_pengumuman
            },
            success: function(data, status, xhr) {
            },
            error: function(data) {
              swal("Warning!", "Terjadi kesalahan sistem", "warning");
            }
        });
    }

    $(document).ready(function() {  
        $('#table').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": "<?php echo site_url('Administrator/Akses_absensi/get_data_user')?>",
                "type": "POST",
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            
            "columnDefs": [
            { 
                "targets": [ 4 ], 
                "orderable": false, 
            },
            ],
            lengthMenu: [
                        [ 10, 25, 50, -1 ],
                        [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                        ],
            dom: "<'row'<'col-md-6'l><'col-md-6'f>><'row'<'col-md-12'B>><'row'<'col-md-12't>><'row'<'col-md-6'i><'col-md-6'p>>",
              buttons: [
                  'copy', 'excel', 'pdf', 'print'
              ],
            select: true,
        });
    });
</script>