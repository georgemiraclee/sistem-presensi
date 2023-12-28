<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1>
            Detail Pengajuan <?php echo $item->nama_status_pengajuan;?>
            <div class="float-right">
              <span><?php echo $item->status_approval2;?></span>
            </div>
          </h1>
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
              <div class="row">
                <div class="col-md-4 text-center">
                  <img src="<?php echo $foto;?>" class="rounded-circle" width="200px" height="200px">
                </div>
                <div class="col-md-8">
                  <table>
                    <tr>
                      <td>Nama</td>
                      <td>:</td>
                      <td><strong><?php echo $user->nama_user;?></strong></td>
                    </tr>
                    <tr>
                      <td>NIK</td>
                      <td>:</td>
                      <td><strong><?php echo $user->nip;?></strong></td>
                    </tr>
                    <tr>
                      <td>Jabatan</td>
                      <td>:</td>
                      <td><strong><?php echo $user->nama_jabatan;?></strong></td>
                    </tr>
                    <tr>
                      <td>Status Karyawan</td>
                      <td>:</td>
                      <td><strong><?php echo $user->nama_status_user;?></strong></td>
                    </tr>
                    <tr>
                      <td>Email</td>
                      <td>:</td>
                      <td><strong><?php echo $user->email_user;?></strong></td>
                    </tr>
                    <tr>
                      <td>Nomor Telepon</td>
                      <td>:</td>
                      <td><strong><?php echo $user->telp_user;?></strong></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <table>
                    <tr>
                      <td>Jenis pengajuan yang diajukan</td>
                      <td>:</td>
                      <td><strong><?php echo $item->nama_status_pengajuan;?></strong></td>
                    </tr>
                    <?php if ($item->nama_status_pengajuan == 'Cuti' || $item->nama_status_pengajuan == 'Cuti Tahunan') { ?>
                      <tr>
                        <td>Sisa hak cuti</td>
                        <td>:</td>
                        <td><strong><?php echo $user->sisa_cuti;?></strong></td>
                      </tr>
                    <?php } ?>
                    <tr>
                      <td>Masa kerja</td>
                      <td>:</td>
                      <td><strong><?php echo date('d F Y', strtotime($user->tanggal_kerja));?></strong></td>
                    </tr>
                    <tr>
                      <td>Tanggal mulai pengajuan</td>
                      <td>:</td>
                      <td><strong><?php echo date('d F Y', strtotime($item->tanggal_awal_pengajuan));?></strong></td>
                    </tr>
                    <tr>
                      <td>Sampai dengan tanggal</td>
                      <td>:</td>
                      <td><strong><?php echo date('d F Y', strtotime($item->tanggal_akhir_pengajuan));?></strong></td>
                    </tr>
                    <tr>
                      <td>Keterangan/Alasan pengajuan</td>
                      <td>:</td>
                      <td><strong><?php echo $item->keterangan_pengajuan;?></strong></td>
                    </tr>
                  </table>
                  <?php echo $image; ?>

                  <?php if ($item->nama_status_pengajuan == 'Cuti' || $item->nama_status_pengajuan == 'Cuti Tahunan') { ?>
                    <div class="mt-3" style="font-size: 12px;">
                      <i>*) Jenis Cuti yang harus disetujui Kepala Biro Sumber Daya</i><br>
                      <i>**) Karyawan dapat melaksanakan CUTI "apabila proses IJIN CUTI telah dipenuhi"</i>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <?php if (!$item->status_approval) { ?>
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

  <div class="modal fade" id="modalReject" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="reject" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalReject">Alasan Ditolak</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="javascript:void(0)" method="POST" id="formreject">
          <input type="hidden" name="id" id="id" value="<?php echo $item->id_pengajuan;?>">
          <div class="modal-body">
            <div class="form-group">
              <label for="note" class="form-label"><span class="text-danger">*</span> Note</label>
              <div class="form-line">
                <textarea name="note" id="note" cols="30" rows="5" class="form-control" required placeholder="Note reject"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Close</button>
            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  var urlACC = "<?php echo base_url('Administrator/cuti/approve_pengajuan'); ?>";
  var urlreject = "<?php echo base_url('Administrator/cuti/reject_pengajuan'); ?>";


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
      })
      .then((willDelete) => {
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

  $('#formreject').on('submit',(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
      method  : 'POST',
      url     : urlreject,
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
  
  function reject(id) {
    $('#modalReject').modal('show');
  }
</script>