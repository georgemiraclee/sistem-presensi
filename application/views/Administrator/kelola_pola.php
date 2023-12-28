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
                    <h1 class="m-0 text-dark">Kelola Pola Kerja</h1>
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
                            <a href="<?php echo base_url();?>Administrator/pola_kerkar/tambah_pola/<?php echo $user->user_id;?>" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Tambah Pola Kerja</a>
                        </div>
                        <div class="card-body">
                            <table class="table" id="table">
                                <thead>
                                    <tr>
                                      <th>Pola Kerja</th>
                                      <th>Tanggal Berlaku</th>
                                      <th>Tanggal Berakhir</th>
                                      <th>Jumlah Hari Masuk</th>
                                      <th>Jumlah Hari Libur</th>
                                      <th>Status</th>
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

    <?php echo $modalList;?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#table').dataTable({
            "processing": true, 
            "serverSide": true,
            "order" : [[1,"asc"]], 
            "ajax": {
                "url": "<?php echo site_url('Administrator/pola_kerkar/kelolaData/'.$user->user_id)?>",
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
                { "orderable": false, "targets": 5 },
                { "orderable": false, "targets": 6 },
            ],
            "columns" : [
                {"data": "pola_kerja"},
                {"data": "tanggal_berlaku"},
                {"data": "tanggal_berakhir"},
                {"data": "hari_masuk"},
                {"data": "hari_libur"},
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
                url     : '<?php echo base_url();?>Administrator/pola_kerkar/delete',
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
