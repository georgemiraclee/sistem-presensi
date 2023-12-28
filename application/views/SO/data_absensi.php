<!-- <style type="text/css">
    .dt-buttons {
        display: none;
    }
</style> -->
<link href="<?php echo base_url();?>assets/admin/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Data Absensi</h2>
            </div>

            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <!-- <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#defaultModal">
                                <span>Data Potongan</span>
                            </button> -->
                              <button class="btn bg-indigo btn-xs float-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <i class="material-icons">filter_list</i><span class="icon-name">FILTER</span> 
                             </button>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="collapse" id="collapseExample">
                                    <div class="well" id="accOneColThree">
                                        <form method="post" id="formFilter" action="javascript:void(0);">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class='form-group form-group-sm'>
                                                        <a type="button" id="reportrange" class="btn btn-info">
                                                            <i class="material-icons">event</i>&nbsp;
                                                            <span></span> 
                                                            <b class="caret"></b>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input type="hidden" name="dari" id="dari">
                                                <input type="hidden" name="sampai" id="sampai">
                                                <div class="col-md-3">
                                                    <h6>SKPD</h6>
                                                    <div style="height: 200px; overflow: auto;">
                                                        <?php foreach ($skpd as $key => $value) {?>
                                                           <div class="demo-checkbox">
                                                                 <input type="checkbox" id="1basic_checkbox_<?php echo $value->id_unit;?>" name="skpd[]" value="<?php echo $value->id_unit;?>" />
                                                                <label for="1basic_checkbox_<?php echo $value->id_unit;?>"> <?php echo ucwords($value->nama_unit);?></label>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>JABATAN</h6>
                                                    <div style="height: 200px; overflow: auto;">
                                                        <?php foreach ($jabatan as $key => $value) {?>
                                                           <div class="demo-checkbox">
                                                                 <input type="checkbox" id="2basic_checkbox_<?php echo $value->id_jabatan;?>" name="jabatan[]" value="<?php echo $value->id_jabatan;?>" />
                                                                <label for="2basic_checkbox_<?php echo $value->id_jabatan;?>"> <?php echo ucwords($value->nama_jabatan);?></label>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>JENIS KELAMIN</h6>
                                                    <div style="height: 200px; overflow: auto;">
                                                        <div class="demo-checkbox">
                                                            <input type="checkbox" id="basic_checkbox_L" value="l" name="jenkel[]"/>
                                                            <label for="basic_checkbox_L">Laki - Laki</label>
                                                             <input type="checkbox" id="basic_checkbox_P" value="p" name="jenkel[]"/>
                                                            <label for="basic_checkbox_P">Perempuan</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <h6>STATUS</h6>
                                                    <div style="height: 200px; overflow: auto;">
                                                        <div class="demo-checkbox">
                                                            <input type="checkbox" id="basic_checkbox_T" name="status[]"  value="Tepat Waktu"/>
                                                                <label for="basic_checkbox_T">Tepat Waktu</label>
                                                            <input type="checkbox" id="basic_checkbox_K" name="status[]"  value="Kesiangan"/>
                                                                <label for="basic_checkbox_K">Kesiangan</label>
                                                            <input type="checkbox" id="basic_checkbox_A" name="status[]"  value="Tidak hadir"/>
                                                                <label for="basic_checkbox_A">Tidak hadir</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="submit" value="Filter" name="" class="btn btn-primary btn float-right">
                                                    <!-- <button >Filter</button> -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="body">
                            <?php echo $this->session->flashdata('pesan'); ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                           <th>NIP</th>
                                           <th>Nama Pegawai</th>
                                            <th>Tanggal</th>
                                            <th>Jam masuk</th>
                                            <th>Jam Istirahat</th>
                                            <th>Jam Kembali</th>
                                            <th>Jam Pulang</th>
                                            <th>Status Absen</th>
                                            <th>Potongan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                       <?php echo $table;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Exportable Table -->
 <!-- Jquery DataTable Plugin Js -->
<script type="text/javascript">
    
    $('#DataTables_Table_0').DataTable({
      dom: 'Bfrtip',
      buttons: [
          'copy', 'excel', 'pdf', 'print'
      ],
      select: true,
      columns: [
         {"title": "Parcel"},
         {"title": "Address"},
         {"title": "Owner"},
         {"title": "Account Num"}
      ]
  });
</script>
<script type="text/javascript">
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
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {

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

            var newDari = document.getElementById("dari").value;
            var newSampai = document.getElementById("sampai").value;

            url += 'SO/data_absensi'+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai;
             window.location = url;
        }));

       
    });
</script>