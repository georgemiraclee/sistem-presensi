<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">Detail Pengajuan Cuti</h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-body">
              <?php echo $this->session->flashdata('pesan'); ?>
              <div class="row">
                <div class="col-md-4">
                  <div class="thumbnail">
                    <img src="<?php echo $foto;?>" class="rounded-circle" width="200" height="200">
                  </div>
                </div>
                <div class="col-md-4">
                  <h5>NIP <span class="float-right">:</span></h5>
                  <h5>Nama Pegawai <span class="float-right">:</span></h5>
                  <h5>Unit <span class="float-right">:</span></h5>
                  <h5>Jabatan <span class="float-right">:</span></h5>
                  <h5>Status <span class="float-right">:</span></h5>
                  <h5>Email <span class="float-right">:</span></h5>
                  <h5>Telepon <span class="float-right">:</span></h5>
                  <h5>Alamat <span class="float-right">:</span></h5> 
                </div>
                <div class="col-md-4">
                  <?php echo $list;?>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <?php echo $cuti; ?>
                </div>
              </div>
              <?php echo $image; ?>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <?php if ($item->status_approval == 0) { ?>
            <div class="card">
              <div class="card-header">
                Action
              </div>
              <div class="card-body">
                <?php echo $tombol;?>
              </div>
            </div>
          <?php }; ?>
          <div class="card">
            <div class="card-header">
              History
            </div>
            <div class="card-body">
              <?php 
                if ($history) { ?>
                  <div class="accordion" id="accordionExample">
                  <?php 
                    foreach ($history as $key => $historyItem) { 
                      $statusHistory = '';
                      if ($historyItem->status == 1) {
                        $statusHistory = '<div class="badge badge-success">Disetujui</div>';
                      } else {
                        $statusHistory = '<div class="badge badge-danger">Ditolak</div>';
                      }
                  ?>
                    <div class="card">
                      <div class="card-header" id="heading<?php echo $key;?>">
                        <h2 class="mb-0">
                          <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse<?php echo $key;?>" aria-expanded="false" aria-controls="collapse<?php echo $key;?>">
                            <?php echo $statusHistory;?>
                            <div class="float-right">
                              <?php echo date('d F Y', strtotime($historyItem->created_at)); ?>
                            </div>
                          </button>
                        </h2>
                      </div>

                      <div id="collapse<?php echo $key;?>" class="collapse" aria-labelledby="heading<?php echo $key;?>" data-parent="#accordionExample">
                        <div class="card-body">
                          <p style="margin-bottom: 0px !important;">by: <?php echo $historyItem->nama_user;?></p>
                          <p style="margin-bottom: 0px !important;">note: <?php echo $historyItem->note ? $historyItem->note : '-';?></p>

                        </div>
                      </div>
                    </div>
                  <?php } ?>
                  </div>
              <?php } else { ?>
                <div class="text-center">
                  ~ Empty ~
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  var urlACC = "<?php echo base_url('Leader/cuti/approve_pengajuan'); ?>";
  var urlreject = "<?php echo base_url('Leader/cuti/reject_pengajuan'); ?>";

  function acc(id) {
    var x = document.getElementById("acc"+id);
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      swal({
        title: "Info",
        text: "Anda yakin akan menerima pengajuan ini ?",
        icon: "info",
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          var x = document.getElementById("acc"+id);
          if (x.style.display === "none") {
            x.style.display = "block";
          } else {
            $.ajax({
              method  : 'POST',
              url     : urlACC,
              async   : true,
              data    : {
                id: id
              },
              success: function(data, status, xhr) {
                // console.log(xhr.responseText);
                try {
                  var result = JSON.parse(xhr.responseText);
                  if (result.status) {
                    swal(result.message, {
                      icon: "success",
                    }).then((acc) => {
                      location.reload();
                    });
                  } else {
                    swal("Warning!", result.message, "warning");
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
        }
      });
    }
  }
  
  function reject(id) {
    swal({
      title: "Warning ?",
      text: "Anda yakin akan menolak ?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        var x = document.getElementById("acc"+id);
        if (x.style.display === "none") {
          x.style.display = "block";
        } else {
          $.ajax({
            method  : 'POST',
            url     : urlreject,
            async   : true,
            data    : {
              id
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
                  swal("Warning!", result.message, "warning");
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
      }
    });
  }
</script>