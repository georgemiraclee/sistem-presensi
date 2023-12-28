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

              <table class="table table-bordered table-striped table-hover dataTable display" id="table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Staff</th>
                    <th>Tanggal Lembur</th>
                    <th>Jam Mulai</th>
                    <th>Lama Lembur</th>
                    <th>Status</th>
                    <th width="40">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    foreach ($data_cuti as $key => $value) { 
                      if ($value->status == 0 ){
                        $stat = "<span style='color:orange'>Pending</span>";
                      }
                      if ($value->status == 1 ){
                        $stat = "<span style='color:green'>ACC</span>";
                      }
                      if ($value->status == 2 ){
                        $stat = "<span style='color:red'>Ditolak</span>";
                      }
                  ?>
                    <tr>
                      <td><?php echo $value->nip;?></td>
                      <td><?php echo ucwords($value->nama_user);?></td>
                      <td><?php echo date('Y-m-d', strtotime($value->tanggal_lembur));?></td>
                      <td><?php echo date('H:i', strtotime($value->jam_mulai));?></td>
                      <td><?php echo $value->lama_lembur;?> Jam</td>
                      <td><?php echo $stat;?></td>
                      <td>
                        <a href="#" style="color: grey" onclick="selectDetail(<?php echo $value->id_lembur; ?>)" data-toggle="modal" data-target="#updateModal"><span class="material-icons">mode_edit</span></a>
                      </td>
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
            </div>
          </div>                        
        </div>
      </div>
      <div class="modal-footer">
          <button type="submit" id="acc" class="btn btn-success" style="margin-right: 5px">Aprove</button>
          </form>

          <form  id="update-form2"  action="javascript:void(0);" method="post" style="float:right">
              <input type="hidden" id="id_lembur2" name="id_lembur" readonly class="form-control">
              <input type="hidden" id name="status" value="2">
              <button type="submit" id="reject" class="btn btn-warning text-white">Reject</button>
          </form>
      </div>
    </div>
  </div>
</div>

<script>
  var urlUpdate = "<?php echo base_url('Leader/lembur/update'); ?>";
  var urlSelect = "<?php echo base_url('Leader/lembur/select'); ?>";
  var skpdNew = "<?php echo $this->input->get('skpd');?>";
  var dariNew = "<?php echo $this->input->get('dari');?>";    
  var sampaiNew = "<?php echo $this->input->get('sampai');?>"; 
  var dari = "<?php echo $this->input->get('dari'); ?>";
  var sampai = "<?php echo $this->input->get('sampai'); ?>";

  function selectDetail(id) {
    $.ajax({
      method  : 'POST',
      url     : urlSelect,
      data    : {
        id: id
      },
      success: function(data, status, xhr) {
        try {
          var result = JSON.parse(xhr.responseText);
          if (result.status == true) {
            var dataResult = result.data;
            var date = formatDate(dataResult.tanggal_lembur);
            if (dataResult.status == 0 ){
              var stat = "<span class='label label-warning'>Pending</span>";
              document.getElementById('acc').style.visibility = 'visible';
              document.getElementById('reject').style.visibility = 'visible';
            }
            if (dataResult.status == 1 ){
              var stat = "<span class='label label-success'>ACC</span>";
              document.getElementById('acc').style.visibility = 'hidden';
              document.getElementById('reject').style.visibility = 'hidden';
            }
            if (dataResult.status == 2 ){
              var stat = "<span class='label label-danger'>Ditolak</span>";
              document.getElementById('acc').style.visibility = 'hidden';
              document.getElementById('reject').style.visibility = 'hidden';
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

    $('#example').DataTable();

    $('#update-form').on('submit',(function(e) {
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
            swal("Warning!", "System error.", "warning");
          }
        },
        error: function(data) {
          swal("Warning!", "Terjadi kesalahan sistem", "warning");
        }
      });
    }));

    $('#update-form2').on('submit',(function(e) {
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

    //datatables
    $('#table').DataTable();

    $('#formFilter').on('submit',(function(e) {
      var url = "<?php echo base_url(); ?>";
      // Get SKPD
      var skpd = document.getElementsByName("skpd[]");
      var newSkpd = "";
      for (var i=0, n=skpd.length;i<n;i++) {
        if (skpd[i].checked) {
          newSkpd += ","+skpd[i].value;
        }
      }
      if (newSkpd) newSkpd = newSkpd.substring(1);
      var newDari = document.getElementById("dari").value;
      var newSampai = document.getElementById("sampai").value;
      url += 'Administrator/lembur'+'?skpd='+newSkpd+'&dari='+newDari+'&sampai='+newSampai;

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