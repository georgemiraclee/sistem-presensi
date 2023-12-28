<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>informasi</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <a href="<?php echo base_url();?>Administrator/informasi/add" class="btn btn-warning">
                                <i class="material-icons">add</i>
                                <span>Tambah informasi</span>
                            </a>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable" id="table">
                                    <thead>
                                        <tr>
                                            <th width="1">Waktu</th>
                                            <th width="400">Judul</th>
                                            <th width="400">Status</th>
                                            <th width="200">Aksi</th>
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
    var urlDelete = "<?php echo base_url('Administrator/informasi/delete'); ?>";
    var urlAktif = "<?php echo base_url('Administrator/informasi/update_status'); ?>";
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
                    id_informasi: id
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
    function is_aktif(id) {
        var checkedValue = 0; 
        var inputElements = document.getElementById('is_aktif'+id);
        var id_informasi = id;
        if(inputElements.checked){
            checkedValue = 1;
        }

        $.ajax({
            method  : 'POST',
            url     : urlAktif,
            async   : true,
            data    : {
                is_aktif: checkedValue,
                id_informasi: id_informasi
            },
            success: function(data, status, xhr) {
            },
            error: function(data) {
              swal("Warning!", "Terjadi kesalahan sistem", "warning");
            }
        });
    }
</script>

<script type="text/javascript">
    var table;
    $(document).ready(function() {  

        //datatables
        table = $('#table').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": "<?php echo site_url('Administrator/informasi/get_data_user')?>",
                "type": "POST",
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            
            "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
            },
            ],
            lengthMenu: [
                        [ 10, 25, 50, -1 ],
                        [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                        ],
            dom: "<'row'<'col-md-6'l><'col-md-6'f>><'row'<'col-md-12'B>><'row'<'col-md-12't>><'row'<'col-md-6'i><'col-md-6'p>>",
              buttons: [
                  'copy', 'excel', 'pdf', 'print'
              ],
      select: true,
        });
    });
</script>