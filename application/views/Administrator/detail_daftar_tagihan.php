<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Daftar Tagihan Bulanan <a href="<?php echo base_url();?>Administrator/daftar_tagihan/export/<?php echo $id_unit;?>"><button class="btn btn-info float-right" >Export Data</button></a></h2>

            </div>
            <br>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable" id="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Pengguna</th>
                                            <th>Jabatan</th>
                                            <th>UMR/UMK</th>
                                            <th>Tunjangan Tetap</th>
                                            <th>BPJS</th>
                                            <th>BPJS</th>
                                            <th>Lembur Melekat Di gaji</th>
                                            <th>Hari Raya</th>
                                            <th>PAKDIN</th>
                                            <th>Fee</th>
                                            <th>PPN</th>
                                            <th>Jumlah Tagihan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $list;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
    var table;
    $(document).ready(function() {  

        //datatables
        table = $('#table').DataTable({ 
            "processing": false, 
            "serverSide": false,            
        });
    });
</script>