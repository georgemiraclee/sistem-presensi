<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">Data Lembur Pegawai</h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <?php echo $this->session->flashdata('pesan'); ?>
              <div class="row my-3">
                <div class="col-md-12">
                  <button class="btn btn-info btn-sm float-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    <i class="fa fa-filter"></i> FILTER
                  </button>
                </div>
              </div>

              <div class="row clearfix my-3">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                              foreach ($departemen as $key => $value) {
                                $nama_unit = (strlen($value->nama_unit) > 20) ? substr($value->nama_unit, 0, 20) . '...' : $value->nama_unit;
                              ?>
                                <div class="demo-checkbox">
                                  <input type="checkbox" id="unit_kerja_<?php echo $value->id_unit; ?>" name="departemen[]" value="<?php echo $value->id_unit; ?>" />
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
                            <h6>TIPE PEGAWAI</h6>
                            <div style=" overflow: hidden;">
                              <?php foreach ($tipe as $key => $value) { ?>
                                <div class="demo-checkbox">
                                  <input type="checkbox" id="tipe_pegawai_<?php echo $value->id_status_user; ?>" name="tipe[]" value="<?php echo $value->id_status_user; ?>" />
                                  <label for="tipe_pegawai_<?php echo $value->id_status_user; ?>"> <?php echo ucwords($value->nama_status_user); ?></label>
                                </div>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <button class="btn btn-primary float-right" type="submit">Filter</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <table class="table table-bordered table-striped table-hover dataTable display" id="table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama Staff</th>
                    <th>Tanggal Lembur</th>
                    <th>Jam Mulai</th>
                    <th>Lama Lembur</th>
                    <th>Status</th>
                    <th width="40">Aksi</th>
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
</div>

<div class="modal fade" id="updateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="defaultModalLabel">Detail Data Lembur Pegawai</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="update-form" action="javascript:void(0);" method="post">
          <div class="row clearfix">
            <div class="row container">
              <div class="col-md-12">
                <input type="hidden" id="id_lembur" name="id_lembur" readonly class="form-control">
                <input type="hidden" name="status" value="1">
                <table>
                  <tr>
                    <td><label for="Nama Pegawai">Nama Pegawai</label></td>
                    <td>&nbsp</td>
                    <td><label>:</label></td>
                    <td>&nbsp</td>
                    <td><label id="user_id"></label></td>
                  </tr>
                  <tr>
                    <td><label for="Status">Status</label></td>
                    <td>&nbsp</td>
                    <td><label>:</label></td>
                    <td>&nbsp</td>
                    <td><label id="status"></label></td>
                  </tr>
                  <tr>
                    <td><label for="Kegiatan">Kegiatan</label></td>
                    <td>&nbsp</td>
                    <td><label>:</label></td>
                    <td>&nbsp</td>
                    <td><label id="kegiatan"></label></td>
                  </tr>
                  <tr>
                    <td><label for="Tanggal Lembur">Tanggal Lembur</label></td>
                    <td>&nbsp</td>
                    <td><label>:</label></td>
                    <td>&nbsp</td>
                    <td><label id="tanggal_lembur"></label></td>
                  </tr>
                  <tr>
                    <td><label for="Durasi Lembur">Durasi Lembur</label></td>
                    <td>&nbsp</td>
                    <td><label>:</label></td>
                    <td>&nbsp</td>
                    <td><label id="lama_lembur"></label></td>
                  </tr>
                </table>
                <img src="#" alt="..." id="attachment" style="display: none;" width="100%">
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="acc" class="btn btn-success" style="margin-right: 5px">Aprove</button>
        </form>
        <form id="update-form2" action="javascript:void(0);" method="post" style="float:right">
          <input type="hidden" id="id_lembur2" name="id_lembur" readonly class="form-control">
          <input type="hidden" id name="status" value="2">
          <button type="submit" id="reject" class="btn btn-warning text-white">Reject</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  var urlUpdate = "<?php echo base_url('Administrator/lembur/update'); ?>";
  var urlSelect = "<?php echo base_url('Administrator/lembur/select'); ?>";
  var skpdNew = "<?php echo $this->input->get('departemen'); ?>";
  var dariNew = "<?php echo $this->input->get('dari'); ?>";
  var sampaiNew = "<?php echo $this->input->get('sampai'); ?>";
  var dari = "<?php echo $this->input->get('dari'); ?>";
  var newjabatan = "<?php echo $this->input->get('jabatan'); ?>";
  var newjenkel = "<?php echo $this->input->get('jenkel'); ?>";
  var newstatus = "<?php echo $this->input->get('tipe'); ?>";

  var departemen = skpdNew;
  departemen.split(",").forEach(element => {
    $("#unit_kerja_" + element).prop('checked', true);
  });

  var jabatan = newjabatan;
  jabatan.split(",").forEach(element => {
    $("#jabatan_" + element).prop('checked', true);
  });

  var tipe = newstatus;
  tipe.split(",").forEach(element => {
    $("#tipe_pegawai_" + element).prop('checked', true);
  });

  function selectDetail(id) {
    $.ajax({
      method: 'POST',
      url: urlSelect,
      data: {
        id: id
      },
      success: function(data, status, xhr) {
        try {
          var result = JSON.parse(xhr.responseText);
          if (result.status == true) {
            var dataResult = result.data;
            var date = formatDate(dataResult.tanggal_lembur);
            if (dataResult.status == 0) {
              var stat = "<span class='label label-warning'>Pending</span>";
              document.getElementById('acc').style.visibility = 'visible';
              document.getElementById('reject').style.visibility = 'visible';
            }
            if (dataResult.status == 1) {
              var stat = "<span class='label label-success'>ACC</span>";
              document.getElementById('acc').style.visibility = 'hidden';
              document.getElementById('reject').style.visibility = 'hidden';
            }
            if (dataResult.status == 2) {
              var stat = "<span class='label label-danger'>Ditolak</span>";
              document.getElementById('acc').style.visibility = 'hidden';
              document.getElementById('reject').style.visibility = 'hidden';
            }

            if (dataResult.userfile) {
              document.getElementById("attachment").style.display = 'block';
              $("#attachment").attr("src", dataResult.userfile);
            } else {
              $("#attachment").attr("src", '#');
              document.getElementById("attachment").style.display = 'none';
            }
            $('#user_id').html(dataResult.nama_user);
            $('#id_lembur').val(dataResult.id_lembur);
            $('#id_lembur2').val(dataResult.id_lembur);
            $('#tanggal_lembur').html(date);
            $('#lama_lembur').html(dataResult.lama_lembur);
            $('#kegiatan').html(dataResult.keterangan);
            $('#status').html(stat);
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
  }

  function formatDate(date) {
    var d = new Date(date),
      month = '' + (d.getMonth() + 1),
      day = '' + d.getDate(),
      year = d.getFullYear();
    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    return [year, month, day].join('-');
  }

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

  $(document).ready(function() {
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

    $('#update-form').on('submit', (function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        method: 'POST',
        url: urlUpdate,
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
            swal("Warning!", "System error.", "warning");
          }
        },
        error: function(data) {
          swal("Warning!", "Terjadi kesalahan sistem", "warning");
        }
      });
    }));

    $('#update-form2').on('submit', (function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        method: 'POST',
        url: urlUpdate,
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

    //datatables
    $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [
        [3, "desc"]
      ],
      "ajax": {
        "url": '<?php echo site_url('Administrator/Lembur/get_data_user') ?>?departemen=' + skpdNew + '&jabatan=' + newjabatan + '&jenkel=' + newjenkel + '&tipe=' + newstatus + '&dari=' + dariNew + '&sampai=' + sampaiNew,
        "type": "POST",
        // success: function(data, status, xhr) {
        //     console.log(xhr.responseText);
        // }
      },
      "columnDefs": [{
        "targets": [7],
        "orderable": false,
      }, ],
      "columns": [{
          "data": "no"
        },
        {
          "data": "nik"
        },
        {
          "data": "nama_user"
        },
        {
          "data": "tanggal_lembur"
        },
        {
          "data": "jam_mulai"
        },
        {
          "data": "lama_lembur"
        },
        {
          "data": "status"
        },
        {
          "data": "aksi"
        },
      ],
      "dom": 'Bfrtip',
      "responsive": true,
      "buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
    });

    $('#formFilter').on('submit', (function(e) {
      var url = "<?php echo base_url(); ?>";
      // Get SKPD
      var skpd = document.getElementsByName("departemen[]");
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
      var status = document.getElementsByName("tipe[]");
      var newstatus = "";
      for (var i = 0, n = status.length; i < n; i++) {
        if (status[i].checked) {
          newstatus += "," + status[i].value;
        }
      }
      if (newstatus) newstatus = newstatus.substring(1);
      var newDari = document.getElementById("dari").value;
      var newSampai = document.getElementById("sampai").value;
      //Get STATUS User
      url += 'Administrator/lembur' + '?departemen=' + newSkpd + '&jabatan=' + newjabatan + '&jenkel=' + newjenkel + '&tipe=' + newstatus + '&dari=' + newDari + '&sampai=' + newSampai;
      // url2 += 'Administrator/data_absensi/get_data_user'+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai;
      window.location = url;
    }));

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
  });
</script>