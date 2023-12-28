<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">Pengumuman</h1>
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
              <a href="<?php echo base_url(); ?>Administrator/pengumuman/add" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Tambah Pengumuman</a>
            </div>
            <div class="card-body">
              <table class="table" id="table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Target</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($pengumuman as $key => $value) { ?>
                    <?php
                    $is_aktif = "";
                    if ($value->is_aktif == 1) {
                      $is_aktif = 'checked';
                    }

                    $target = "Semua User";
                    if ($value->is_all == 1) {
                      $target = "Semua User";
                    } elseif ($value->user_id != null || $value->user_id != "") {
                      // get data user
                      $userData = $this->Db_select->select_where('tb_user', 'user_id = ' . $value->user_id);
                      $target = $userData->nama_user;
                    } elseif ($value->id_unit != null || $value->id_unit != "") {
                      // get data unit
                      $userUnit = $this->Db_select->select_where('tb_unit', 'id_unit = ' . $value->id_unit);
                      $target = $userUnit->nama_unit;
                    }
                    ?>
                    <tr>
                      <td><?php echo $key + 1; ?></td>
                      <td><?php echo date('d M Y', strtotime($value->start_date)); ?></td>
                      <td><?php echo date('d M Y', strtotime($value->end_date)); ?></td>
                      <td><?php echo $value->judul_pengumuman; ?></td>
                      <td><input type="checkbox" name="my-checkbox" data-id-pengumuman="<?= $value->id_pengumuman ?>" <?php echo $is_aktif; ?> id="is_aktif<?php echo $value->id_pengumuman; ?>" data-bootstrap-switch></td>
                      <td><?php echo $target; ?></td>
                      <td>
                        <a href="<?php echo base_url('Administrator/pengumuman/edit/' . $value->id_pengumuman); ?>" style="color: grey" class="btn btn-info btn-sm text-white">
                          <span class="fa fa-pencil-alt"></span>
                        </a>
                        <a href="#" style="color: grey" data-type="ajax-loader" onclick="hapus(<?php echo $value->id_pengumuman; ?>)" class="btn btn-danger btn-sm text-white">
                          <span class="fa fa-trash"></span>
                        </a>
                      </td>
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

<script>
  var urlDelete = "<?php echo base_url('Administrator/pengumuman/delete'); ?>";
  var urlAktif = "<?php echo base_url('Administrator/pengumuman/update_status'); ?>";

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
            method: 'POST',
            url: urlDelete,
            async: true,
            data: {
              id_pengumuman: id
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

  $(document).ready(function() {
    $("input[data-bootstrap-switch]").each(function(_, element) {
      $(element).bootstrapSwitch('state', $(element).prop('checked'));

      $(element).on('switchChange.bootstrapSwitch', {
        id: $(element).attr('data-id-pengumuman')
      }, is_aktif);
    });

    $('#table').DataTable({
      "order": [
        [0, "asc"]
      ],
      "columnDefs": [{
          "orderable": false,
          "targets": 4
        },
        {
          "orderable": false,
          "targets": 6
        },
      ],
      "buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });

    function is_aktif(e) {
      $.ajax({
        method: 'POST',
        url: urlAktif,
        async: true,
        data: {
          id_pengumuman: e.data.id,
          is_aktif: document.querySelector('#is_aktif' + e.data.id).checked ? 1 : 0,
        },
        success: function(data, status, xhr) {},
        error: function(data) {
          swal("Warning!", "Terjadi kesalahan sistem.", "warning");
        }
      });
    }
  });
</script>