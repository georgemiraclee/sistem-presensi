<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Data Komponen Unit Kerja</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <div class="table-responsive">
                            <table id="tbl_pendapatan" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="1">No</th>
                                        <th width="400">Nama Unit Kerja</th>
                                        <th width="200">Nilai UMK</th>
                                        <th width="230">Detail</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Setting UMK Unit Kerja</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="email_address_2">Nominal UMK</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="nominal" id="nominal" class="form-control" placeholder="">
                                </div>
                            </div>
                            <input type="hidden" name="id_unit" id="id_unit">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
            </div>
        </div>
    </div>
</div>
<script>
    var urlInsert = "<?php echo base_url('Administrator/pendapatan_departemen/insert_umk'); ?>";
    $(document).ready(function() {
        var dataTable = $('#tbl_pendapatan').DataTable({
            "dom": 'Bfrtip',
            "responsive": true,
            // "processing" : true,
            "serverSide": true,
            "order" : [[1,"asc"]],
            "ajax": {
                url: '<?php echo base_url("Administrator/pendapatan_departemen/getData");?>',
                type: 'POST',
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columnDefs": [
                {"searchable": false, "orderable": false, "targets": 0},
                {"orderable": false, "targets": 2},
                {"className": "dt-center", "targets": "_all"}
            ],
            "columns" : [
                {"data": "no"},
                {"data": "nama_unit"},
                {"data": "umk"},
                {"data": "aksi"}
            ],
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        
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
                            window.location='<?php echo base_url();?>Administrator/pendapatan_departemen';
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
    function addId(id, nominal){
        document.getElementById("id_unit").value = id;
        document.getElementById("nominal").value = nominal;
    }
</script>