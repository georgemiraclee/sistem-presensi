<style> 
.switch {
  position: relative;
  display: inline-block;
  width: 55px;
  height: 28px;
}
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 24px;
  width: 24px;
  left: 1px;
  bottom: 2px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* slider bulat*/
.slider.round {
  border-radius: 32px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Pengaturan Cuti</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form  id="add-form" action="javascript:void(0);" method="post">
                            <div class="card-body">
                                <input type="hidden" name="id_channel" value="<?php echo $id_channel;?>">
                                <div class="form-group">
                                    <!-- <label for="jumlahCuti"><span class="text-danger">*</span> Jumlah Cuti</label> -->
                                    <input type="hidden" id="jumlahCuti" class="form-control" name="cuti" required value="14" onchange="setMax(this.value)">
                                </div>
                                <div class="form-group">
                                    <label for="batasan"><span class="text-danger">*</span> Batas Pengambilan Cuti (/Bulan)</label>
                                    <input type="number" id="batasan" min="0" max="<?php echo $cuti; ?>" class="form-control" value="<?php echo $jatah; ?>" name="batasan" required>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" id="checkbox" name="aktif_batasan" <?php echo $batas;?>>
                                    <span class="slider round"></span>
                                </label>
                                <label>Batasi Pengambilan Cuti Bulanan</label>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary float-right" type="submit"><span class="fa fa-save"></span> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    var urlInsert = "<?php echo base_url('Administrator/Pengaturan_cuti/insert'); ?>";
 
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
                  swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                }
            });
        }));
    });

    $("input[data-bootstrap-switch]").each(function(_,element){
        $(element).bootstrapSwitch('state',$(element).prop('checked'));

        $(element).on('switchChange.bootstrapSwitch',{
            id: $(element).attr('')
        })
    })

    function setMax(max) {
        document.getElementById("batasan").max = max;
    }
</script>