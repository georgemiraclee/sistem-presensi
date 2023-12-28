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
                                  <button class="btn btn-primary btn-sm float-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                      <span class="fa fa-filter"></span> FILTER
                                  </button>
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
                                                  <div class="col-md-4">
                                                      <h6>JABATAN</h6>
                                                      <div style=" overflow: hidden;">
                                                          <?php foreach ($jabatan as $key => $value) {?>
                                                          <div class="demo-checkbox">
                                                                  <input type="checkbox" id="2basic_checkbox_<?php echo $value->id_jabatan;?>" name="jabatan[]" value="<?php echo $value->id_jabatan;?>" />
                                                                  <label for="2basic_checkbox_<?php echo $value->id_jabatan;?>"> <?php echo ucwords($value->nama_jabatan);?></label>
                                                              </div>
                                                          <?php } ?>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <h6>JENIS KELAMIN</h6>
                                                      <div style="overflow: hidden;">
                                                          <div class="demo-checkbox">
                                                              <input type="checkbox" id="basic_checkbox_L" value="l" name="jenkel[]"/>
                                                              <label for="basic_checkbox_L">Laki - Laki</label>
                                                          </div>
                                                          <div class="demo-checkbox">
                                                              <input type="checkbox" id="basic_checkbox_P" value="p" name="jenkel[]"/>
                                                              <label for="basic_checkbox_P">Perempuan</label>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <h6>STATUS</h6>
                                                      <div style="overflow: hidden;">
                                                          <div class="demo-checkbox">
                                                              <input type="checkbox" id="basic_checkbox_T" name="status[]"  value="Tepat Waktu"/>
                                                              <label for="basic_checkbox_T">Tepat Waktu</label>
                                                          </div>
                                                          <div class="demo-checkbox">
                                                              <input type="checkbox" id="basic_checkbox_K" name="status[]"  value="Terlambat"/>
                                                              <label for="basic_checkbox_K">Terlambat</label>
                                                          </div>
                                                          <div class="demo-checkbox">
                                                              <input type="checkbox" id="basic_checkbox_A" name="status[]"  value="Tidak hadir"/>
                                                              <label for="basic_checkbox_A">Tidak hadir</label>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-2">
                                                      <h6>STATUS USER</h6>
                                                      <div style="overflow: hidden;">
                                                          <div class="demo-checkbox">
                                                              <input type="checkbox" id="basic_checkbox_on" name="status_user[]"  value="1"/>
                                                              <label for="basic_checkbox_on">Aktif</label>
                                                          </div>
                                                          <div class="demo-checkbox">
                                                              <input type="checkbox" id="basic_checkbox_off" name="status_user[]"  value="0"/>
                                                              <label for="basic_checkbox_off">Tidak Aktif</label>
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
                                <th>#</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Tanggal</th>
                                <th>Jam masuk</th>
                                <th>Jam Istirahat</th>
                                <th>Jam Kembali</th>
                                <th>Jam Pulang</th>
                                <th>Status Absen</th>
                                <th>Aksi</th>
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
</div>

<script>
  var table;
  var dari = "<?php echo $this->input->get('dari'); ?>";
  var sampai = "<?php echo $this->input->get('sampai'); ?>";
  var skpdNew = "<?php echo $this->input->get('skpd');?>";
  var jabatanNew = "<?php echo $this->input->get('jabatan');?>";
  var jenkelNew = "<?php echo $this->input->get('jenkel');?>";    
  var statusNew = "<?php echo $this->input->get('status');?>";    
  var dariNew = "<?php echo $this->input->get('dari');?>";    
  var sampaiNew = "<?php echo $this->input->get('sampai');?>";  
  var userNew = "<?php echo $this->input->get('status_user');?>";  
  var urlDelete = "<?php echo base_url('Administrator/data_absensi/batalkan'); ?>";

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
          title: "Warning!",
          text: "Apakah anda yakin ?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
      })
      .then((willDelete) => {
          if (willDelete) {
              $.ajax({
                  method  : 'POST',
                  url     : urlDelete,
                  async   : true,
                  data    : {
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
                              swal("Warning!", "Terjadi kesalahan sistem", "warning");
                          }
                      } catch (e) {
                          swal("Warning!", "Terjadi kesalahan sistem", "warning");
                      }
                  },
                  error: function(data) {
                      swal("Warning!", "Terjadi kesalahan sistem", "warning");
                  }
              });
          }
      });
  }

  $(document).ready(function(){
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

      table = $('#table').DataTable({ 
          "processing": true, 
          "serverSide": true, 
          "order": [], 
          
          "ajax": {
              "url": "<?php echo site_url('Leader/data_absensi/get_data_user')?>?skpd="+skpdNew+'&jabatan='+jabatanNew+'&jenkel='+jenkelNew+'&status='+statusNew+'&dari='+dariNew+'&sampai='+sampaiNew+'&status_user='+userNew,
              "type": "POST",
              // success: function(data, status, xhr) {
              //     console.log(xhr.responseText);
              // }
          },
          
          "columnDefs": [
          { 
              "targets": [ 0 ], 
              "orderable": false, 
          },
          ],
          lengthMenu: [
                      [ 10, 25, 50, -1 ],
                      [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                      ],
          dom: "<'row'<'col-md-6'l><'col-md-6'f>><'row'<'col-md-12'B>><'row'<'col-md-12't>><'row'<'col-md-6'i><'col-md-6'p>>",
          buttons: [
              'copy', 'excel', 'pdf', 'print'
          ],
          select: true,
      });

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
          //Get JABATAN
          var jabatan = document.getElementsByName("jabatan[]");
          var newjabatan = "";
          for (var i=0, n=jabatan.length;i<n;i++) {
              if (jabatan[i].checked) {
                  newjabatan += ","+jabatan[i].value;
              }
          }
          if (newjabatan) newjabatan = newjabatan.substring(1);
          //Get JENKEL
          var jenkel = document.getElementsByName("jenkel[]");
          var newjenkel = "";
          for (var i=0, n=jenkel.length;i<n;i++) {
              if (jenkel[i].checked) {
                  newjenkel += ","+jenkel[i].value;
              }
          }
          if (newjenkel) newjenkel = newjenkel.substring(1);
          //Get STATUS
          var status = document.getElementsByName("status[]");
          var newstatus = "";
          for (var i=0, n=status.length;i<n;i++) {
              if (status[i].checked) {
                  newstatus += ","+status[i].value;
              }
          }
          if (newstatus) newstatus = newstatus.substring(1);
          //Get STATUS User
          var status_user = document.getElementsByName("status_user[]");
          var newuser = "";
          for (var i=0, n=status_user.length;i<n;i++) {
              if (status_user[i].checked) {
                  newuser += ","+status_user[i].value;
              }
          }
          if (newuser) newuser = newuser.substring(1);
          var newDari = document.getElementById("dari").value;
          var newSampai = document.getElementById("sampai").value;
          url += 'Administrator/data_absensi'+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai+'&status_user='+newuser;
          // url2 += 'Administrator/data_absensi/get_data_user'+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai;
            window.location = url;
      }));
  });
</script>