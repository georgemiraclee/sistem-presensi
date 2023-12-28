<style type="text/css">
    .dt-buttons {
        display: none;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Data Jabatan Unit Kerja</h2>
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
                                        <th width="200">Nama Jabatan</th>
                                        <th>Tunjangan Jabatan</th>
                                        <th>Tunjangan PAKDIN</th>
                                        <th width="450">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $list?>
                                </tbody>
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
                <h4 class="modal-title" id="defaultModalLabel">Setting Tunjangan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                    <input type="hidden" name="id_unit" id="id_unit">
                    <input type="hidden" name="id_jabatan" id="id_jabatan">


                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="email_address_2">Tunjangan Jabatan</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="jabatan" id="jabatan" class="form-control" placeholder="" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="email_address_2">Tunjangan PAKDIN</label>
                        </div>
                        <div class="col-lg-9 col-md-10 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="number" name="pakdin" id="pakdin" class="form-control" placeholder="" required="">
                                </div>
                            </div>
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
    var urlInsert = "<?php echo base_url('Administrator/pendapatan_departemen/insert_tunjangan '); ?>";

    function addId(id,id_jabatan, jabatan, pakdin){
        document.getElementById("id_unit").value = id;
        document.getElementById("id_jabatan").value = id_jabatan;
        document.getElementById("jabatan").value = jabatan;
        document.getElementById("pakdin").value = pakdin;
    }

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
</script>