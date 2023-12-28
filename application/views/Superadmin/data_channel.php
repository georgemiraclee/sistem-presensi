<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">Data List Channel</h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <a href="<?php echo base_url();?>Superadmin/channel/add" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Tambah Channel</a>
        </div>
        <div class="card-body">
          <table id="table" class="table table-bordered table-striped table-hover dataTable">
            <thead>
              <tr>
                <th>Nama Channel</th>
                <th>Email Channel</th>
                <th>Telepon Channel</th>
                <th>Kode</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data_channel as $key => $value) { ?>
                <?php 
                  $is_aktif = "";
                  if ($value->status == 1) {
                    $is_aktif = 'checked';
                  }
                ?>
                <tr>
                  <td><?php echo ucwords($value->nama_channel);?></td>
                  <td><?php echo $value->email_channel;?></td>
                  <td><?php echo $value->telp_channel;?></td>
                  <td><span class="badge badge-info"><?php echo $value->code_channel;?></span></td>
                  <td>
                    <input type="checkbox" name="my-checkbox" onchange="is_aktif('<?php echo $value->id_channel;?>')" <?php echo $is_aktif;?> id="is_aktif<?php echo $value->id_channel;?>" data-bootstrap-switch>
                  </td>
                  <td>
                    <a href="<?php echo base_url();?>Superadmin/channel/edit/<?php echo $value->id_channel; ?>" class="btn btn-info btn-sm"><span class="fa fa-pencil-alt"></span></a>
                    <a href="#" class="btn btn-danger btn-sm" onclick="hapus(<?php echo $value->id_channel; ?>)"><span class="fa fa-trash"></span></a>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  var urlDelete = "<?php echo base_url('Superadmin/channel/delete'); ?>";
  var urlAktif = "<?php echo base_url('Superadmin/channel/update_status'); ?>";

  $(document).ready(function() {
    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    $('#table').DataTable({
      "order" : [[0,"asc"]],
      "columnDefs": [
        {"orderable": false, "targets": 3},
        {"orderable": false, "targets": 4},
      ],
      "buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });
  });

  function hapus(id) {
    swal({
      title: "Anda yakin akan mengapus data ini ?",
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
            id_channel: id
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
    var id_channel = id;
    if(inputElements.checked){
      checkedValue = 1;
    }

    $.ajax({
      method  : 'POST',
      url     : urlAktif,
      async   : true,
      data    : {
        status: checkedValue,
        id_channel: id_channel
      },
      success: function(data, status, xhr) {
      },
      error: function(data) {
        swal("Warning!", "Terjadi kesalahan sistem", "warning");
      }
    });
  }
</script>