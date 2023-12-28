<style>
  .classBorder {
    background-color: rgba(255,0,0,0.2) !important;
    border-color: red;
    border-style: solid;
    border-width: 2px;
  }
</style>

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
                            <a href="<?php echo base_url();?>Administrator/jadwal_kerja/tambah_pola" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Tambah Pola Kerja</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover dataTable display" id="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pola Kerja</th>
                                        <th>Lama Pola</th>
                                        <th>Lama Hari Kerja</th>
                                        <th>Lama Hari Libur</th>
                                        <th>Status</th>
                                        <th width="12%">Aksi</th>
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

<?php echo $modalList;?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#table').dataTable({
            "processing": true, 
            "serverSide": true, 
            "order" : [[1,"asc"]],
            "ajax": {
                "url": "<?php echo site_url('Administrator/jadwal_kerja/getData')?>",
                "type": "POST",
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columnDefs": [
                { "orderable": false, "targets": 5 },
                { "orderable": false, "targets": 6 }
            ],
            "columns" : [
                {"data": "no"},
                {"data": "nama_pola_kerja"},
                {"data": "lama_pola_kerja"},
                {"data": "lama_hari_kerja"},
                {"data": "lama_hari_libur"},
                {"data": "status"},
                {"data": "aksi"},
            ]
        });
    });

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
                url     : '<?php echo base_url();?>Administrator/jadwal_kerja/delete',
                async   : true,
                data    : {
                    id: id
                },
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
          }
      });
    }
</script>