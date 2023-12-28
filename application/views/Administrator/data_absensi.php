<style>
  ::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 7px;
  }

  ::-webkit-scrollbar-thumb {
    border-radius: 4px;
    background-color: rgba(0, 0, 0, .5);
    box-shadow: 0 0 1px rgba(255, 255, 255, .5);
  }
</style>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">Data Absensi</h1>
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
              <div class="row">
                <div class="col-md-12">
                  <div class="float-right">
                    <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                      <span class="fa fa-filter"></span> FILTER
                    </button>
                    <button class="btn btn-warning text-white btn-sm" data-toggle="modal" data-target="#modalManualAbsensi"><span class="fa fa-plus"></span> Manual Absensi</button>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="collapse" id="collapseExample">
                    <div class="well" id="accOneColThree">
                      <form method="post" id="formFilter" action="javascript:void(0);">
                        <div class="row">
                          <div class="col-md-12">
                            <div class='form-group form-group-sm'>
                              <a type="button" id="reportrange" class="btn btn-info text-white">
                                <span class="fa fa-calendar-alt"></span>&nbsp;
                                <span class="fa fa-chevron-down"></span>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <input type="hidden" name="dari" id="dari">
                          <input type="hidden" name="sampai" id="sampai">
                          <div class="col-md-4" style="height: 200px; overflow: auto;">
                            <h6>Unit Kerja</h6>
                            <div style=" overflow: hidden;">
                              <?php
                              foreach ($skpd as $key => $value) {
                                $nama_unit = (strlen($value->nama_unit) > 25) ? substr($value->nama_unit, 0, 25) . '...' : $value->nama_unit;
                              ?>
                                <div class="demo-checkbox">
                                  <input type="checkbox" id="unit_kerja_<?php echo $value->id_unit; ?>" name="skpd[]" value="<?php echo $value->id_unit; ?>" />
                                  <label for="unit_kerja_<?php echo $value->id_unit; ?>"> <?php echo ucwords($nama_unit); ?></label>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="col-md-4" style="height: 200px; overflow: auto;">
                            <h6>JABATAN</h6>
                            <div style=" overflow: hidden;">
                              <?php
                              foreach ($jabatan as $key => $value) {
                                $nama_jabatan = (strlen($value->nama_jabatan) > 20) ? substr($value->nama_jabatan, 0, 20) . '...' : $value->nama_jabatan;
                              ?>
                                <div class="demo-checkbox">
                                  <input type="checkbox" id="jabatan_<?php echo $value->id_jabatan; ?>" name="jabatan[]" value="<?php echo $value->id_jabatan; ?>" />
                                  <label for="jabatan_<?php echo $value->id_jabatan; ?>"> <?php echo ucwords($nama_jabatan); ?></label>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <h6>STATUS</h6>
                            <div style="overflow: hidden;">
                              <div class="demo-checkbox">
                                <input type="checkbox" id="status_tepat_waktu" name="status[]" value="Tepat Waktu" />
                                <label for="status_tepat_waktu">Tepat Waktu</label>
                              </div>
                              <div class="demo-checkbox">
                                <input type="checkbox" id="status_terlambat" name="status[]" value="Terlambat" />
                                <label for="status_terlambat">Terlambat</label>
                              </div>
                              <div class="demo-checkbox">
                                <input type="checkbox" id="status_tidak_hadir" name="status[]" value="Tidak hadir" />
                                <label for="status_tidak_hadir">Tidak hadir</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-md-12">
                            <input type="submit" value="Filter" name="" class="btn btn-primary float-right">
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table" id="table">
                <thead>
                  <tr>
                    <th>NIP</th>
                    <th>Nama Pegawai</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Total Jam</th>
                    <th>Status Absen</th>
                    <th>Detail</th>
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
  </section>

  <div class="modal fade" id="modalManualAbsensi" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalManualAbsensiLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalManualAbsensiLabel">Manual Absensi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="javascript:void(0)" method="POST" id="formAbsenManual">
          <div class="modal-body">
            <div class="form-group">
              <label for="user_id"><span class="text-danger">*</span> Karyawan</label>
              <select name="user_id" id="user_id" class="form-control js-example-basic-single" required>
                <option value="">-- Pilih karyawan --</option>
                <?php foreach ($karyawan as $value) { ?>
                  <option value="<?php echo $value->user_id; ?>"><?php echo $value->nip . " - " . $value->nama_user; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="created_absensi2"><span class="text-danger">*</span> Tanggal Absensi</label>
              <input type="date" name="created_absensi" id="created_absensi2" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="jamMasuk2">Jam Masuk</label>
              <input type="time" class="form-control" id="jamMasuk2" name="waktu_datang">
            </div>
            <div class="form-group">
              <label for="jamIstirahat2">Jam Istirahat</label>
              <input type="time" class="form-control" id="jamIstirahat2" name="waktu_istirahat">
            </div>
            <div class="form-group">
              <label for="jamKembali2">Jam Kembali</label>
              <input type="time" class="form-control" id="jamKembali2" name="waktu_kembali">
            </div>
            <div class="form-group">
              <label for="jamPulang2">Jam Pulang</label>
              <input type="time" class="form-control" id="jamPulang2" name="waktu_pulang">
            </div>
            <div class="form-group">
              <label for="tagging2"><span class="text-danger">*</span> Tagging</label>
              <select name="tagging" id="tagging2" required class="form-control">
                <option value="">-- Pilih tagging --</option>
                <option value="WFO">WFO</option>
                <option value="WFH">WFH</option>
              </select>
            </div>
            <div class="form-group">
              <label for="location_tagging2">Location</label>
              <select name="location_tagging" id="location_tagging2" class="form-control">
                <option value="">-- Pilih lokasi tagging --</option>
                <?php foreach ($lokasi as $value) { ?>
                  <option value="<?php echo $value->nama_lokasi; ?>"><?php echo $value->nama_lokasi; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Note</label>
              <div class="form-line">
                <textarea class="form-control" name="note_absensi_manual" placeholder="Note"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Close</button>
            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalEditAbsensi" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalEditAbsensiLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditAbsensiLabel">Ubah Jam Kerja</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="#" method="POST" id="changeHistoryAbsensi">
          <div class="modal-body">
            <div class="form-group">
              <label for="jamMasuk">Jam Masuk</label>
              <input type="hidden" id="id_absensi" name="id_absensi">
              <input type="time" class="form-control" id="jamMasuk" name="waktu_datang">
            </div>
            <div class="form-group">
              <label for="jamIstirahat">Jam Istirahat</label>
              <input type="time" class="form-control" id="jamIstirahat" name="waktu_istirahat">
            </div>
            <div class="form-group">
              <label for="jamKembali">Jam Kembali</label>
              <input type="time" class="form-control" id="jamKembali" name="waktu_kembali">
            </div>
            <div class="form-group">
              <label for="jamPulang">Jam Pulang</label>
              <input type="time" class="form-control" id="jamPulang" name="waktu_pulang">
            </div>
            <div class="form-group">
              <label for="tagging"><span class="text-danger">*</span> Tagging</label>
              <select name="tagging" id="tagging" required class="form-control">
                <option value="">-- Pilih tagging --</option>
                <option value="WFO">WFO</option>
                <option value="WFH">WFH</option>
              </select>
            </div>
            <div class="form-group">
              <label for="location_tagging">Location</label>
              <select name="location_tagging" id="location_tagging" class="form-control">
                <option value="">-- Pilih lokasi tagging --</option>
                <?php foreach ($lokasi as $value) { ?>
                  <option value="<?php echo $value->nama_lokasi; ?>"><?php echo $value->nama_lokasi; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Note</label>
              <div class="form-line">
                <textarea class="form-control" name="note_absensi_manual" id="note_absensi_manual" placeholder="Note"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-ban"></span> Close</button>
            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  var table;
  var dari = "<?= $this->input->get('dari', true); ?>";
  var sampai = "<?= $this->input->get('sampai', true); ?>";
  var skpdNew = "<?= $this->input->get('skpd', true); ?>";
  var jabatanNew = "<?= $this->input->get('jabatan', true); ?>";
  var jenkelNew = "<?= $this->input->get('jenkel', true); ?>";
  var statusNew = "<?= $this->input->get('status', true); ?>";
  var dariNew = "<?= $this->input->get('dari', true); ?>";
  var sampaiNew = "<?= $this->input->get('sampai', true); ?>";
  var userNew = "<?= $this->input->get('status_user', true); ?>";
  var urlDelete = "<?= base_url('Administrator/data_absensi/batalkan'); ?>";

  var status = "<?= $this->input->get('status', true); ?>";
  status.split(",").forEach(element => {
    const changeName = element.toLocaleLowerCase().replace(" ", "_");
    $("#status_" + changeName).prop('checked', true);
  });

  var jabatan = "<?= $this->input->get('jabatan', true); ?>";
  jabatan.split(",").forEach(element => {
    $("#jabatan_" + element).prop('checked', true);
  });

  var skpd = "<?= $this->input->get('skpd', true); ?>";
  skpd.split(",").forEach(element => {
    $("#unit_kerja_" + element).prop('checked', true);
  });

  function cb(start, end) {
    if (start._d == "Invalid Date" && end._d == "Invalid Date") {
      $('#dari').val('');
      $('#sampai').val('');
    } else {
      dari = start.format('YYYY-MM-DD');
      sampai = end.format('YYYY-MM-DD');
      $('#dari').val(dari);
      $('#sampai').val(sampai);
    }
  }

  function hapus(id) {
    swal({
        title: "Apakah anda yakin ?",
        text: "Jika absensi ini dibatalkan, user tercatat menjadi tidak hadir.",
        icon: "info",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: 'POST',
            url: urlDelete,
            async: true,
            data: {
              id_absensi: id
            },
            success: function(data, status, xhr) {
              try {
                var result = JSON.parse(xhr.responseText);
                if (result.status) {
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
        }
      });
  }

  function changeAbsensi(id) {
    $.get("<?php echo base_url('Administrator/data_absensi/getDetail?id='); ?>" + id, function(data, status) {
      var result = JSON.parse(data);
      if (result.status) {
        const item = result.data;
        $('#id_absensi').val(item.id_absensi);
        $('#jamMasuk').val(item.waktu_datang);
        $('#jamIstirahat').val(item.waktu_istirahat);
        $('#jamKembali').val(item.waktu_kembali);
        $('#jamPulang').val(item.waktu_pulang);
        $('#tagging').val(item.tagging);
        $('#location_tagging').val(item.location_tagging);
        $('#note_absensi_manual').val(item.note_absensi_manual);
        $('#modalEditAbsensi').modal('show');
      }
    });
  }

  $(document).ready(function() {
    $('.js-example-basic-single').select2();
    if (dari != "" && sampai != "") {
      var start = moment(dari);
      var end = moment(sampai);
      $('#reportrange span').html(start.format('D-M-YYYY') + ' sampai ' + end.format('D-M-YYYY'));
    } else {
      var today = moment().format('MM/DD/YYYY');
      var newtoday = moment().subtract(6, 'days');
      newtoday = newtoday.format('MM/DD/YYYY');
      var start = today;
      var end = newtoday;
    }

    $('#reportrange').daterangepicker({
      ranges: {
        'Hari Ini': [moment(), moment()],
        'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Data 7 Hari': [moment().subtract(6, 'days'), moment()],
        'Data 30 Hari': [moment().subtract(29, 'days'), moment()],
        'Data Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
        'Data Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      "locale": {
        "direction": "ltr",
        "format": "MM/DD/YYYY",
        "separator": " - ",
        "applyLabel": "Apply",
        "cancelLabel": "Cancel",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
          "Su",
          "Mo",
          "Tu",
          "We",
          "Th",
          "Fr",
          "Sa"
        ],
        "monthNames": [
          "Januari",
          "Februari",
          "Maret",
          "April",
          "Mei",
          "Juni",
          "Juli",
          "Agustus",
          "September",
          "Oktober",
          "November",
          "Desember"
        ],
        "firstDay": 1
      },
      "startDate": start,
      "endDate": end
    }, cb);

    $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        "url": "<?= site_url('Administrator/Data_absensi/get_data_user') ?>?skpd=" + skpdNew + '&jabatan=' + jabatanNew + '&jenkel=' + jenkelNew + '&status=' + statusNew + '&dari=' + dariNew + '&sampai=' + sampaiNew + '&status_user=' + userNew,
        "type": "POST",
        //  success: function(data, status, xhr) {
        //     console.log(xhr.responseText);
        //  }
      },

      "columnDefs": [{
        "targets": [7],
        "orderable": false,
      }, ],
      lengthMenu: [
        [10, 25, 50, 100, 200],
        ['10 rows', '25 rows', '50 rows', '100 rows', '200 rows']
      ],
      select: true,
    });

    $('#formFilter').on('submit', (function(e) {
      var url = "<?php echo base_url(); ?>";
      // Get SKPD
      var skpd = document.getElementsByName("skpd[]");
      var newSkpd = "";
      for (var i = 0, n = skpd.length; i < n; i++) {
        if (skpd[i].checked) {
          newSkpd += "," + skpd[i].value;
        }
      }
      if (newSkpd) newSkpd = newSkpd.substring(1);
      //Get JABATAN
      var jabatan = document.getElementsByName("jabatan[]");
      var newjabatan = "";
      for (var i = 0, n = jabatan.length; i < n; i++) {
        if (jabatan[i].checked) {
          newjabatan += "," + jabatan[i].value;
        }
      }
      if (newjabatan) newjabatan = newjabatan.substring(1);
      //Get JENKEL
      var jenkel = document.getElementsByName("jenkel[]");
      var newjenkel = "";
      for (var i = 0, n = jenkel.length; i < n; i++) {
        if (jenkel[i].checked) {
          newjenkel += "," + jenkel[i].value;
        }
      }
      if (newjenkel) newjenkel = newjenkel.substring(1);
      //Get STATUS
      var status = document.getElementsByName("status[]");
      var newstatus = "";
      for (var i = 0, n = status.length; i < n; i++) {
        if (status[i].checked) {
          newstatus += "," + status[i].value;
        }
      }
      if (newstatus) newstatus = newstatus.substring(1);
      //Get STATUS User
      var status_user = document.getElementsByName("status_user[]");
      var newuser = "";
      for (var i = 0, n = status_user.length; i < n; i++) {
        if (status_user[i].checked) {
          newuser += "," + status_user[i].value;
        }
      }
      if (newuser) newuser = newuser.substring(1);
      var newDari = document.getElementById("dari").value;
      var newSampai = document.getElementById("sampai").value;
      url += 'Administrator/data_absensi' + '?skpd=' + newSkpd + '&jabatan=' + newjabatan + '&jenkel=' + newjenkel + '&status=' + newstatus + '&dari=' + newDari + '&sampai=' + newSampai + '&status_user=' + newuser;
      // url2 += 'Administrator/data_absensi/get_data_user'+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai;
      window.location = url;
    }));

    $('#changeHistoryAbsensi').on('submit', (function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        method: 'POST',
        url: "<?php echo base_url("Administrator/data_absensi/updateDataAbsensi"); ?>",
        data: formData,
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

    $('#formAbsenManual').on('submit', (function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        method: 'POST',
        url: "<?php echo base_url("Administrator/data_absensi/absenManual"); ?>",
        data: formData,
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
  });
</script>