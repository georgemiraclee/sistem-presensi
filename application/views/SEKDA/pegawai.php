<div class="content-wrapper">
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-md-12">
                  <h1 class="m-0 text-dark">Data Staff</h1>
              </div>
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">
      <?php echo $this->session->flashdata('pesan'); ?>
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover dataTable" id="myTable2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama Staff</th>
                        <th>Jabatan</th>
                        <th>Unit Kerja</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
    var urlInsert = "<?php echo base_url('Leader/jabatan/insert'); ?>";
    var urlUpdate = "<?php echo base_url('Leader/jabatan/update'); ?>";
    var urlAktif = "<?php echo base_url('Leader/jabatan/update_status'); ?>";
    var urlDelete = "<?php echo base_url('Leader/pegawai/delete'); ?>";
    
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
                    nip: id
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

    function is_aktif() {
        var checkedValue = 0; 
        var inputElements = document.getElementById('is_aktif');
        var nip = document.getElementById('is_aktif').value;
        if(inputElements.checked){
            checkedValue = 1;
        }
        $.ajax({
            method  : 'POST',
            url     : urlAktif,
            async   : true,
            data    : {
                is_aktif: checkedValue,
                nip: nip
            },
            success: function(data, status, xhr) {
            },
            error: function(data) {
              swal("Warning!", "Terjadi kesalahan sistem", "warning");
            }
        });
    }

    $(document).ready(function() {
      $('#add-form').on('submit',(function(e) {
          e.preventDefault();
          var formData = new FormData(this);
          $.ajax({
              method  : 'POST',
              url     : urlInsert,
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
                swal("Warning!", "Terjadi kesalahan sistem.", "warning");
              }
          });
      }));

      $('#update-form').on('submit',(function(e) {
          e.preventDefault();
          var formData = new FormData(this);
          $.ajax({
              method  : 'POST',
              url     : urlUpdate,
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
                swal("Warning!", "Terjadi kesalahan sistem.", "warning");
              }
          });
      }));
      
      $('#myTable2').DataTable({
          "processing": true, 
          "serverSide": true, 
          "order" : [[2,"asc"]],
          "ajax": {
              "url": "<?php echo site_url('Leader/pegawai/getData')?>",
              "type": "POST",
              // success: function(data, status, xhr) {
              //     console.log(xhr.responseText);
              // }
          },
          "columnDefs": [
              { "orderable": false, "targets": 0 },
              { "orderable": false, "targets": 7 }
          ],
          "columns" : [
              {"data": "no"},
              {"data": "nip"},
              {"data": "nama_user"},
              {"data": "jabatan"},
              {"data": "departemen"},
              {"data": "tipe"},
              {"data": "status"},
              {"data": "aksi"},
          ],
          "lengthMenu": [
              [ 10, 25, 50, -1 ],
              [ '10 rows', '25 rows', '50 rows', 'Show all' ]
          ],
          "dom": 'Bfrtip',
          "responsive": true,
          "buttons": [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ]
      });

    });
</script>