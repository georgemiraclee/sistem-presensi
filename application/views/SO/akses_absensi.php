<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Akses Absensi</h2>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <a href="<?php echo base_url();?>SO/akses_absensi/add" class="btn btn-warning">
                                <i class="material-icons">add</i>
                                <span>Tambah Akses Pegawai</span>
                            </a>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th width="1">NIP</th>
                                            <th width="400">Nama Pegawai</th>
                                            <th width="400">Akses Absensi</th>
                                            <th width="400">Berlaku Hingga</th>
                                            <th width="200">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $list_akses_absensi;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<script>
    var urlDelete = "<?php echo base_url('SO/akses_absensi/delete'); ?>";
    var urlAktif = "<?php echo base_url('SO/akses_absensi/update_status'); ?>";

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
                url     : urlDelete,
                async   : true,
                data    : {
                    id_akses_absensi: id
                },
                success: function(data, status, xhr) {
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
            $.ajax({
                method  : 'POST',
                url     : urlDelete,
                async   : true,
                data    : {
                    id_komponen_pendapatan: id
                },
                success: function(data, status, xhr) {
                  swal(result.message, {
                    icon: "success",
                  }).then((acc) => {
                    location.reload();
                  });
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
</script>