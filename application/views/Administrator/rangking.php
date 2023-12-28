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
          <h1 class="m-0 text-dark">Ranking Pegawai</h1>
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

              <div class="row clearfix">
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
                          <div class="col-md-3" style="height: 200px; overflow: auto;">
                              <h6>SKPD</h6>
                              <?php foreach ($skpd as $key => $value) {
                                $skpd = (strlen($value->nama_unit) > 20) ? substr($value->nama_unit,0,20).'...' : $value->nama_unit;
                              ?>
                              <div class="demo-checkbox">
                                <input type="checkbox" id="1basic_checkbox_<?php echo $value->id_unit;?>" name="skpd[]" value="<?php echo $value->id_unit;?>" />
                                <label for="1basic_checkbox_<?php echo $value->id_unit;?>"> <?php echo ucwords($skpd);?></label>
                              </div>
                              <?php } ?>
                          </div>
                          <div class="col-md-3" style="height: 200px; overflow: auto;">
                              <h6>JABATAN</h6>
                              <?php 
                                foreach ($jabatan as $key => $value) {
                                  $nama_jabatan = (strlen($value->nama_jabatan) > 20) ? substr($value->nama_jabatan,0,20).'...' : $value->nama_jabatan;
                              ?>
                                <div class="demo-checkbox">
                                  <input type="checkbox" id="2basic_checkbox_<?php echo $value->id_jabatan;?>" name="jabatan[]" value="<?php echo $value->id_jabatan;?>" />
                                  <label for="2basic_checkbox_<?php echo $value->id_jabatan;?>"> <?php echo ucwords($nama_jabatan);?></label>
                                </div>
                              <?php } ?>
                          </div>
                          <div class="col-md-3" style="height: 200px; overflow: auto;">
                              <h6>TIPE PEGAWAI</h6>
                              <div style=" overflow: hidden;">
                                  <?php foreach ($tipe as $key => $value) {?>
                                      <div class="demo-checkbox">
                                          <input type="checkbox" id="1basic_checkbox_<?php echo $value->id_status_user;?>" name="tipe[]" value="<?php echo $value->id_status_user;?>" />
                                          <label for="1basic_checkbox_<?php echo $value->id_status_user;?>"> <?php echo ucwords($value->nama_status_user);?></label>
                                      </div>
                                  <?php } ?>
                              </div>
                          </div>
                          <div class="col-md-3" style="height: 200px; overflow: auto;">
                              <h6>JENIS KELAMIN</h6>
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
                        <button class="btn btn-primary btn float-right" type="submit">Filter</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <?php echo $rank;?>  
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
    var dari = "<?php echo $this->input->get('dari'); ?>";
    var sampai = "<?php echo $this->input->get('sampai'); ?>";
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
            var status = document.getElementsByName("tipe[]");
            var newstatus = "";
            for (var i=0, n=status.length;i<n;i++) {
                if (status[i].checked) {
                    newstatus += ","+status[i].value;
                }
            }
            if (newstatus) newstatus = newstatus.substring(1);
            

            var newDari = document.getElementById("dari").value;
            var newSampai = document.getElementById("sampai").value;
            url += 'Administrator/rangking'+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai;
            window.location = url;
        }));
    });
</script>