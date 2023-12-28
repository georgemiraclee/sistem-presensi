<style type="text/css">
    .classBorder {
        background-color: rgba(255,0,0,0.2);
        border-color: red;
        border-style: solid;
        border-width: 2px;
    }
    #classBorder {
        background-color: rgba(255,0,0,0.2);
        border-color: red;
        border-style: solid;
        border-width: 2px;
    }
    #readonly { /* For Firefox */
        background-color: #E9E9E9;
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Pola Kerja Pegawai</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form class="form-horizontal" id="add-form" method="post" action="javascript:void(0);">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">Nama Pegawai</label>
                                    <div class="col-sm-10">
                                        <?php if ($selectUser) { ?>
                                            <?php foreach ($pegawai as $key => $value) { ?>
                                                <?php if ($value->user_id == $selectUser) { ?>
                                                    <input class="form-control" id="readonly" type="text" value="<?php echo $value->nama_user;?>" readonly>
                                                    <input class="form-control" type="hidden" name="pegawai[]" value="<?php echo $value->user_id;?>">
                                                <?php } ?>
                                            <?php } ?>
                                        <?php }else { ?>
                                            <select class="form-control js-example-basic-multiple" name="pegawai[]" multiple="multiple" required="">
                                                <?php foreach ($pegawai as $key => $value) { ?>
                                                    <option value="<?php echo $value->user_id;?>" data-subtext="<?php echo '#'.$value->nip;?>">
                                                        <?php echo $value->nama_user;?>        
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">Pola Kerja</label>
                                    <div class="col-sm-10">
                                        <select class="form-control show-tick" name="pola_kerja" data-live-search="true" required onchange="setTable(this.value)">
                                            <option value="">Pilih Pola Kerja</option>
                                            <?php foreach ($pola as $key => $value) { ?>
                                                <option value="<?php echo $value->id_pola_kerja;?>">
                                                    <?php echo $value->nama_pola_kerja;?>        
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                  <label for="inputEmail3" class="col-sm-2 col-form-label">Tanggal Berlaku</label>
                                  <div class="col-sm-5">
                                    <input type="date" id="tglAwal" name="tanggalAwal" class="form-control" value="<?php echo date('Y-m-d');?>" required="">
                                  </div>
                                  <div class="col-sm-5">
                                    <input type="date" name="tanggalAkhir" id="tglAkhir" class="form-control" value="<?php echo date('Y-m-d');?>" required="">
                                  </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7" id="addTable">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="float-right">
                                    <a href="<?php echo base_url();?>Administrator/pola_kerkar" class="btn btn-danger m-t-15"><span class="fa fa-ban"></span> Batal</a>
                                    <button class="btn btn-primary m-t-15" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    var urlInsert = "<?php echo base_url('Administrator/pola_kerkar/insertData'); ?>";
    function toleransiSet() {
        var checked = document.getElementById('toleransi');
        if (checked.checked) {
            $('#setToleransi').html('<div class="col-lg-2 form-control-label">\
                                    <label style="font-size: 12px;">Toleransi Keterlambatan</label>\
                                </div>\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\
                                    <div class="input-group">\
                                        <div class="form-line">\
                                            <input type="number" name="waktu_toleransi_keterlambatan" class="form-control" min="0" required>\
                                        </div>\
                                        <span class="input-group-addon">Menit</span>\
                                    </div>\
                                </div>')
        }else{
            $('#setToleransi').html(' ');
        }
    }
    function setHari(id, val) {
        if (val == 0) {
            var element = document.getElementById(id);
            element.classList.add("classBorder");
        }else{
            $('#'+id).removeClass("classBorder");
        }
    }
    function setTable(id) {
        if (id) {
            $.ajax({
                method  : 'POST',
                url     : '<?php echo base_url();?>Administrator/pola_kerkar/selectDay',
                async   : true,
                data    : {
                    id: id
                },
                success: function(data, status, xhr) {
                    $('#addTable').html(xhr.responseText);
                }
            });
        }else{
            $('#addTable').html(' ');
        }
    }
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
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
                            window.location = "<?php echo base_url();?>Administrator/pola_kerkar";
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
