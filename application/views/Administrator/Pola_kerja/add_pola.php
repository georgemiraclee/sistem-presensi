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
    .select2-selection {
      width: 100% !important;
      height: 100% !important;
    }
</style>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">Tambah Pola Kerja</h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Pilih Target <strong><?php echo $target;?></strong></h5>
            </div>
            <form class="form-horizontal" id="add-form" method="post" action="javascript:void(0);">
              <input type="hidden" name="type" value="<?php echo $type;?>">
              <div class="card-body">
                <?php if ($type == 2) { ?>
                  <div class="form-group row">
                      <label for="id_unit" class="col-sm-2 col-form-label"><span class="text-danger">*</span> Data Unit Kerja</label>
                      <div class="col-sm-10">
                          <select class="form-control show-tick js-example-basic-multiple" multiple="multiple" name="id_unit[]" id="id_unit" data-live-search="true" required>
                              <option value="">-- Pilih Data Unit Kerja --</option>
                              <?php foreach ($unit as $unit) { ?>
                                  <option value="<?php echo $unit->id_unit;?>">
                                      <?php echo $unit->nama_unit;?>        
                                  </option>
                              <?php } ?>
                          </select>
                      </div>
                  </div>
                <?php } elseif ($type == 3) { ?>
                  <div class="form-group row">
                      <label for="user_id" class="col-sm-2 col-form-label"><span class="text-danger">*</span> Data Karyawan</label>
                      <div class="col-sm-10">
                          <select class="form-control show-tick js-example-basic-multiple" multiple="multiple" name="user_id[]" id="user_id" data-live-search="true" required>
                              <option value="">-- Pilih Karyawan --</option>
                              <?php foreach ($staff as $staff) { ?>
                                  <option value="<?php echo $staff->user_id;?>">
                                      <?php echo $staff->nama_user;?>        
                                  </option>
                              <?php } ?>
                          </select>
                      </div>
                  </div>
                <?php } ?>
                <div class="form-group row">
                    <label for="pola_kerja" class="col-sm-2 col-form-label"><span class="text-danger">*</span> Pola Kerja</label>
                    <div class="col-sm-10">
                        <select class="form-control show-tick js-example-basic-multiple" name="pola_kerja" id="pola_kerja" data-live-search="true" required onchange="setTable(this.value)">
                            <option value="">-- Pilih Pola Kerja --</option>
                            <?php foreach ($pola as $key => $value) { ?>
                                <option value="<?php echo $value->id_pola_kerja;?>">
                                    <?php echo $value->nama_pola_kerja;?>        
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7" id="addTable">
                        
                    </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 col-form-label"><span class="text-danger">*</span> Tanggal Berlaku</label>
                  <div class="col-sm-5">
                    <input type="date" id="tglAwal" name="tanggalAwal" class="form-control" required min="<?php echo date('Y-m-d');?>" onchange="getDate(this.value)">
                  </div>
                  <div class="col-sm-5">
                    <input type="date" name="tanggalAkhir" id="tglAkhir" class="form-control" required disabled>
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
  var urlInsert = "<?php echo base_url('Administrator/pola_kerkar/newInsert'); ?>";
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
  function getDate(value) {
    $("#tglAkhir").prop('disabled', false);
    document.getElementById("tglAkhir").setAttribute("min", value);
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
</script>
