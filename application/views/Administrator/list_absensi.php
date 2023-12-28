<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">Data Absensi</h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <?php echo $this->session->flashdata('pesan'); ?>
              <table class="table table-bordered table-striped table-hover dataTable display" id="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>NIK</th>
                    <th>Nama Pegawai</th>
                    <th>Tanggal</th>
                    <th>Jam masuk</th>
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
</div>

<script>
    var urlDelete = "<?php echo base_url('Administrator/absensi/batalkan'); ?>";
    $(document).ready(function() {
      $('#table').DataTable({ 
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        
        "ajax": {
          "url": "<?php echo site_url('Administrator/Absensi/get_data_user')?>",
          "type": "POST",
          // success: function(data, status, xhr) {
          //     console.log(xhr.responseText);
          // }
        },
        
        "columnDefs": [
          {"targets": [6],"orderable": false}
        ],
        select: true,
      });
    });

    function batal(id) {
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
                id_absensi: id
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
