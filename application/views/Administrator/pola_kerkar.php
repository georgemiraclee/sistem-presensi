<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Pengaturan Pola dan Jadwal Kerja</h1>
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
                            <a href="<?php echo base_url();?>Administrator/pola_kerkar/add" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Tambah Pola Kerja</a>
                        </div>
                        <div class="card-body">
                            <table class="table" id="table">
                                <thead>
                                    <tr>
                                        <th>Nama Pegawai</th>
                                        <th>Status Pegawai</th>
                                        <th>Pola Kerja (Jumlah Hari)</th>
                                        <th>Tanggal Berlaku</th>  
                                        <th>Tanggal Berakhir</th>                               
                                        <th>Aksi</th>
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
    $(document).ready(function() {
        $('#table').dataTable({
            "processing": true, 
            "serverSide": true, 
            "order" : [[0,"asc"]],
            "ajax": {
                "url": "<?php echo site_url('Administrator/pola_kerkar/getData')?>",
                "type": "POST",
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columnDefs": [
                { "orderable": false, "targets": 0 },
                { "orderable": false, "targets": 1 },
                { "orderable": false, "targets": 2 },
                { "orderable": false, "targets": 3 },
                { "orderable": false, "targets": 4 }, 
                { "orderable": false, "targets": 5 }
            ],
            "columns" : [
                {"data": "nama_user"},
                {"data": "nama_status_user"},
                {"data": "nama_pola_kerja"},
                {"data": "tanggal_berlaku"},
                {"data": "tanggal_berakhir"},
                {"data": "aksi"},
            ]
        });
    });
</script>
