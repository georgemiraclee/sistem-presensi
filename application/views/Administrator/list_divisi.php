<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Divisi Unit Kerja <?php echo $departemen;?></h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <a href="#" data-toggle="modal" data-target="#add" class="btn btn-warning">
                                <i class="material-icons">add</i>
                                <span>Tambah Divisi Baru</span>
                            </a>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable display" id="table">
                                    <thead>
                                        <tr>
                                            <th width="1">No</th>
                                            <th width="400">Nama Divisi</th>
                                            <th width="400">Unit Kerja</th>
                                            <th width="400">Aksi</th>
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
            <?php echo $modal;?>
            <div id="add" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah data divisi</h4>
                  </div>
                  <div class="modal-body">
                    <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="email_address_2">Nama Divisi</label>
                            </div>
                            <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="nama_divisi" class="form-control" placeholder="Nama Divisi">
                                        <input type="hidden" name="id_unit" value="<?php echo $id_unit;?>" >
                                    </div>
                                </div>
                            </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-info"><span class="fa fa-save"></span> Simpan</button>
                  </div>
                </div>

              </div>
            </div>
        </div>
    </section>
    <script>
    var urlInsert = "<?php echo base_url('Administrator/unit/insert_divisi'); ?>";
    var urlUpdate = "<?php echo base_url('Administrator/unit/update_divisi'); ?>";

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
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            });
        }));

    <?php foreach ($dd as $key => $value) {?>
        $('#update-form<?php echo $value->id_divisi;?>').on('submit',(function(e) {
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
    <?php }?>





    });
</script>
<script type="text/javascript">
    var table;
    $(document).ready(function() {  
        //datatables
        table = $('#table').DataTable({ 
            
        });
    });
</script>
