<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Data List Pembayaran</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable" id="table">
                                <thead>
                                    <tr>
                                        <th width="200">No</th>
                                        <th width="400">User</th>
                                        <th width="400">Tanggal Transaksi</th>
                                        <th width="400">Aksi</th>
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

<script type="text/javascript">
    $(document).ready( function () {
        $('#table').DataTable({
            "processing": true, 
            "serverSide": true, 
            "order" : [[0,"asc"]],
            "ajax": {
                "url": "<?php echo site_url('Superadmin/pembayaran/getData')?>",
                "type": "POST",
                // success: function(data, status, xhr) {
                //     console.log(xhr.responseText);
                // }
            },
            "columns" : [
                {"data": "no"},
                {"data": "nama_user"},
                {"data": "transaction_time"},
                {"data": "aksi"}

            ]
        });
    });
</script>
