<style type="text/css">    .dt-buttons {        display: none;    }</style><section class="content">        <div class="container-fluid">            <div class="block-header">                <h2>Data Izin Pulang</h2>            </div>            <div class="row clearfix">                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                    <div class="card">                        <?php echo $this->session->flashdata('pesan'); ?>                        <div class="body">                            <div class="table-responsive">                                <table class="table table-bordered table-striped table-hover dataTable display" id="table">                                    <thead>                                        <tr>                                            <th>No</th>                                            <th>Nama Staff</th>                                            <th>Tanggal Pengajuan</th>                                            <th>Keterangan</th>                                            <th>Tipe Pengajuan</th>                                            <th>Status</th>                                            <th>Aksi</th>                                        </tr>                                    </thead>                                    <tbody>                                                                        </tbody>                                </table>                            </div>                         </div>                    </div>                </div>            </div>        </div>    </section>    <script type="text/javascript">    var table;    $(document).ready(function() {          //datatables        table = $('#table').DataTable({             "processing": true,             "serverSide": true,             "order" : [[2,"asc"]],            "ajax": {                "url": "<?php echo site_url('leader/Izin_pulang/get_data_user')?>",                "type": "POST",                // success: function(data, status, xhr) {                //     console.log(xhr.responseText);                // }            },                        "columnDefs": [                { "orderable": false, "targets": 0 }            ],            "columns" : [                {"data": "no"},                {"data": "nama_user"},                {"data": "tanggal"},                {"data": "keterangan"},                {"data": "tipe_pengajuan"},                {"data": "status"},                {"data": "aksi"},            ],            "lengthMenu": [                [ 10, 25, 50, -1 ],                [ '10 rows', '25 rows', '50 rows', 'Show all' ]            ],            "dom": 'Bfrtip',            "responsive": true,            "buttons": [                'copy', 'csv', 'excel', 'pdf', 'print'            ]        });    });</script>    