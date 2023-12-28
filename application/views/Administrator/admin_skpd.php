<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Staff</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <div class="row">
                                <div class="col-md-8">
                                    <a href="<?php echo base_url();?>Administrator/admin_skpd/add" class="btn btn-warning">
                                        <i class="material-icons">add</i>
                                        <span>Tambah Staff</span>
                                    </a>
                                </div>
                            </div>                            
                        </div>
                        <?php echo $this->session->flashdata('pesan'); ?>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable" id="myTable2">
                                    <thead>
                                        <tr>
                                            <th>ID Alat</th>
                                            <th>NIP</th>
                                            <th>Nama Staff</th>
                                            <th>Jabatan</th>
                                            <th>Unit Kerja</th>
                                            <th>SKPD</th>
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
            </div>
        </div>
    </section>
    <script>
    var urlInsert = "<?php echo base_url('Administrator/jabatan/insert'); ?>";
    var urlUpdate = "<?php echo base_url('Administrator/jabatan/update'); ?>";
    var urlAktif = "<?php echo base_url('Administrator/jabatan/update_status'); ?>";
    var urlDelete = "<?php echo base_url('Administrator/admin_skpd/delete'); ?>";
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
                  location.reload();
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
        // $('#example').DataTable();
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
                    swal("Warning!", "Terjadi kesalahan sistem", "warning");
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
                    swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }));
    });
</script>
<script type="text/javascript">
    var table;
    $(document).ready( function () {
        table = $('#myTable2').DataTable({
            "processing": true, 
            "serverSide": true, 
            "order" : [[2,"asc"]],
            "ajax": {
                "url": "<?php echo site_url('Administrator/admin_skpd/getData')?>",
                "type": "POST",
                //success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columnDefs": [
                { "orderable": false, "targets": 0 },
                { "orderable": false, "targets": 7 }
            ],
            "columns" : [
                {"data": "id_alat"},
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