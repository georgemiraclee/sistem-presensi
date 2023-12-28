<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Invoice Unit Kerja</h2>
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
    <script>
    $(document).ready(function() {
        var dataTable = $('#tbl_pendapatan').DataTable({
            "responsive": true,
            // "processing" : true,
            "serverSide": true,
            "order" : [[1,"desc"]],
            "ajax": {
                url: '<?php echo base_url("Administrator/daftar_tagihan/getData");?>',
                type: 'POST',
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columnDefs": [
                {"searchable": false, "orderable": false, "targets": 0},
                {"orderable": false, "targets": 2}
            ],
            "columns" : [
                {"data": "no"},
                {"data": "nama_unit"},
                {"data": "aksi"}
            ],
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
    function addId(id){
          document.getElementById("id_unit").value = id;
    }
</script>