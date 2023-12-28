<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Data Jabatan</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Jabatan</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jabatan as $key => $value) { ?>
                                    <tr>
                                        <th><?php echo $key+1;?></th>
                                        <td><?php echo $value->nama_jabatan;?></td>
                                        <td><a href="<?php echo base_url();?>Administrator/pendapatan_departemen/detail_jabatan/<?php echo $value->id_jabatan;?>">Detail</a></td>
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