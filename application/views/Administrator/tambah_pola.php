<style>
  .classBorder {
    background-color: rgba(255,0,0,0.2);
    border-color: red;
    border-style: solid;
    border-width: 2px;
  }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Tambah Pola dan Jadwal Kerja Baru</h1>
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
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Nama Pola Kerja</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="nama_pola_kerja" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="toleransi" name="toleransi_keterlambatan" onchange="toleransiSet()">
                                    <label class="form-check-label" for="toleransi">
                                        Terapkan toleransi keterlambatan
                                    </label>
                                </div>
                                <div class="mt-2" id="setToleransi">
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                        <label style="font-size: 12px;">Jadwal Kerja</label>
                                    </div>
                                </div>
                                <!-- Hari -->
                                <div class="row px-5">
                                    <div class="col-lg-12">
                                        <table class="table">
                                            <tbody>
                                                <tr id="setSenin">
                                                    <td>Senin</td>
                                                    <td>
                                                        <select class="form-control show-tick" name="mondayA" onchange="setHari('setSenin', this.value)">
                                                            <option value="0">Hari Kerja</option>
                                                            <option value="1">Hari Libur</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly name="mondayB" value="08:00" class="timepicker form-control">
                                                    </td>
                                                    <td>s/d</td>
                                                    <td>
                                                        <input type="text" readonly name="mondayC" value="17:00" class="timepicker form-control">
                                                    </td>
                                                </tr>
                                                <tr id="setSelasa">
                                                    <td>Selasa</td>
                                                    <td>
                                                        <select class="form-control show-tick" name="tuesdayA" onchange="setHari('setSelasa', this.value)">
                                                            <option value="0">Hari Kerja</option>
                                                            <option value="1">Hari Libur</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly name="tuesdayB" value="08:00" class="timepicker form-control">
                                                    </td>
                                                    <td>s/d</td>
                                                    <td>
                                                        <input type="text" readonly name="tuesdayC" value="17:00" class="timepicker form-control">
                                                    </td>
                                                </tr>
                                                <tr id="setRabu">
                                                    <td>Rabu</td>
                                                    <td>
                                                        <select class="form-control show-tick" name="wednesdayA" onchange="setHari('setRabu', this.value)">
                                                            <option value="0">Hari Kerja</option>
                                                            <option value="1">Hari Libur</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly value="08:00" name="wednesdayB" class="timepicker form-control">
                                                    </td>
                                                    <td>s/d</td>
                                                    <td>
                                                        <input type="text" readonly value="17:00" name="wednesdayC" class="timepicker form-control">
                                                    </td>
                                                </tr>
                                                <tr id="setKamis">
                                                    <td>Kamis</td>
                                                    <td>
                                                        <select class="form-control show-tick" name="thursdayA" onchange="setHari('setKamis', this.value)">
                                                            <option value="0">Hari Kerja</option>
                                                            <option value="1">Hari Libur</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly name="thursdayB" value="08:00" class="timepicker form-control">
                                                    </td>
                                                    <td>s/d</td>
                                                    <td>
                                                        <input type="text" readonly name="thursdayC" value="17:00" class="timepicker form-control">
                                                    </td>
                                                </tr>
                                                <tr id="setJumat">
                                                    <td>Jum'at</td>
                                                    <td>
                                                        <select class="form-control show-tick" name="fridayA" onchange="setHari('setJumat', this.value)">
                                                            <option value="0">Hari Kerja</option>
                                                            <option value="1">Hari Libur</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly name="fridayB" value="08:00" class="timepicker form-control">
                                                    </td>
                                                    <td>s/d</td>
                                                    <td>
                                                        <input type="text" readonly name="fridayC" value="17:00" class="timepicker form-control">
                                                    </td>
                                                </tr>
                                                <tr id="setSabtu">
                                                    <td>Sabtu</td>
                                                    <td>
                                                        <select class="form-control show-tick" name="saturdayA" onchange="setHari('setSabtu', this.value)">
                                                            <option value="0">Hari Kerja</option>
                                                            <option value="1">Hari Libur</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly name="saturdayB" value="08:00" class="timepicker form-control">
                                                    </td>
                                                    <td>
                                                        s/d
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly name="saturdayC" value="17:00" class="timepicker form-control">
                                                    </td>
                                                </tr>
                                                <tr id="setMinggu">
                                                    <td>Minggu</td>
                                                    <td>
                                                        <select class="form-control show-tick" name="sundayA" onchange="setHari('setMinggu', this.value)">
                                                            <option value="0">Hari Kerja</option>
                                                            <option value="1">Hari Libur</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly name="sundayB" value="08:00" class="timepicker form-control">
                                                    </td>
                                                    <td>
                                                        s/d
                                                    </td>
                                                    <td>
                                                        <input type="text" readonly name="sundayC" value="17:00" class="timepicker form-control">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="float-right">
                                  <a href="<?php echo base_url();?>Administrator/jadwal_kerja" class="btn m-t-15 btn-danger"><span class="fa fa-ban"></span> Batal</a>
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
    var urlInsert = "<?php echo base_url('Administrator/jadwal_kerja/insert_pola'); ?>";
    $(document).ready(function() {
      $('.timepicker').bootstrapMaterialDatePicker({
        format: 'HH:mm',
        clearButton: true,
        date: false
      });
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
                                window.location = "<?php echo base_url();?>Administrator/jadwal_kerja";
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
    function toleransiSet() {
        var checked = document.getElementById('toleransi');
        if (checked.checked) {
            $('#setToleransi').html('<div class="form-group row">\
                <label for="inputPassword" class="col-sm-2 col-form-label">Toleransi Keterlambatan</label>\
                <div class="col-sm-3">\
                    <input type="number" name="waktu_toleransi_keterlambatan" class="form-control" min="0" required>\
                    <small id="emailHelp" class="form-text text-muted">Menit</span>\
                </div>\
            </div>')
        } else {
            $('#setToleransi').html(' ');
        }
    }
    function setHari(id, val) {
      if (val == 1) {
        var element = document.getElementById(id);
        element.classList.add("classBorder");
      }else{
        $('#'+id).removeClass("classBorder");
      }
    }
</script>