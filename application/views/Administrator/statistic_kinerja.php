<style>
  #calendar {
    max-width: 100%;
    margin: 0 auto;
  }
</style>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">
            <a href="<?php echo base_url('Administrator/kinerja_pegawai');?>"><i class="fas fa-chevron-left"></i></a> Detail Kinerja Pegawai</h1>
            
            <div class="float-right">
              <form method="post" id="formFilter" action="javascript:void(0);">
                <div class="btn-group" role="group">

                <button type="button" id="reportrange" class="btn btn-primary">
                  <span class="fa fa-calendar-alt"></span>
                </button>
                <input type="hidden" name="dari" id="dari">
                <input type="hidden" name="sampai" id="sampai">
                <button button type="submit" class="btn btn-primary">Filter</button>
              </form>
            </div>
          </h1>
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
                          <div class="row">
                            <div class="col-md-3">
                              <a href="javascript:void(0);">
                                <?php
                                  if ($foto_user==""||$foto_user==null) {
                                    $url = base_url()."assets/images/member-photos/default_photo.jpg";
                                  }else{
                                    $url = base_url()."assets/images/member-photos/".$foto_user."";
                                  }
                                ?>
                                <img class="media-object" src="<?php echo $url;?>" width="160" height="160" style = "border-radius: 50%;">
                              </a>
                            </div>
                            <div class="col-md-9">
                              <h3 class="media-heading">
                                <?php echo $nama_user;?> <small class="float-right"><?php echo $nip;?></small>
                              </h3>
                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th>Rata2 Jam Kerja</th>
                                    <th>Tepat Waktu</th>
                                    <th>Terlambat</th>
                                    <th>Izin/Sakit</th>
                                    <th>Status Kinerja</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><?php echo $rata;?> Jam/hari</td>
                                    <td><?php echo $tepat;?></td>
                                    <td><?php echo $telat;?></td>
                                    <td><?php echo $ijin;?></td>
                                    <td><?php echo $sts;?></td>
                                  </tr>
                                </tbody>
                              </table>

                              <button type="button" class="btn btn-primary btn-block btn-sm" data-toggle="modal" data-target="#largeModal">
                                <span class="fa fa-calendar-alt"></span>
                                Izin & Cuti
                              </button>
                            </div>
                            <div id='calendar'></div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title" id="largeModalLabel">List Data pengajuan</h4>
              <div class="float-right">
                <span class="badge badge-success"><b>Jatah cuti : <?php echo $cuti;?></b></span>
              </div>
          </div>
          <div class="modal-body table-responsive">
            <table class="table" id="example">
              <thead>
                <tr>
                  <th>Tangggal</th>
                  <th>Jenis</th>
                  <th>Durasi Pengajuan</th>
                  <th>Keterangan pengajuan</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php echo $list;?>
              </tbody>
            </table>

          </div>
      </div>
  </div>
</div>

<script type="text/javascript">
  $(function () {
      getMorris('area', 'area_chart');
  });
  function getMorris(type, element) {
      if (type === 'area') {
          Morris.Area({
              element: element,
              data: [<?php echo $crt;?>],
              xkey: 'period',
              ykeys: ['Jam_kerja'],
              labels: ['Jam_kerja'],
              pointSize: 2,
              hideHover: 'auto',
              lineColors: [ 'rgb(0, 188, 212)', 'rgb(0, 150, 136)']
          });
      } 
  }
</script>
<link href="<?php echo base_url();?>assets/fullcalendar/fullcalender/lib/cupertino/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/fullcalendar/fullcalender/fullcalendar.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/fullcalendar/fullcalender/fullcalendar.print.css" rel="stylesheet" type="text/css" media="print" />
<script src="<?php echo base_url();?>assets/fullcalendar/fullcalender/lib/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/fullcalendar/fullcalender/fullcalendar.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            defaultDate: '<?php echo date('Y-m-d');?>',
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            eventLimit: true, // allow "more" link when too many events 
            events: [
                <?php echo $stamp;?>
                // {
                //     id: 999,
                //     title: 'Repeating Event',
                //     start: '2017-10-09T16:00:00'
                // },
                // {
                //     id: 999,
                //     title: 'Repeating Event',
                //     start: '2017-10-16T16:00:00'
                // },
                // {
                //     title: 'Conference',
                //     start: '2017-10-11',
                //     end: '2017-10-13'
                // },
                // {
                //     title: 'Meeting',
                //     start: '2017-10-12T10:30:00',
                //     end: '2017-10-12T12:30:00'
                // },
                // {
                //     title: 'Lunch',
                //     start: '2017-10-12T12:00:00'
                // },
                // {
                //     title: 'Meeting',
                //     start: '2017-10-12T14:30:00'
                // },
                // {
                //     title: 'Happy Hour',
                //     start: '2017-10-12T17:30:00'
                // },
                // {
                //     title: 'Dinner',
                //     start: '2017-10-12T20:00:00'
                // },
                // {
                //     title: 'Birthday Party',
                //     start: '2017-10-13T07:00:00'
                // },
                // {
                //     title: 'Click for Google',
                //     url: 'http://google.com/',
                //     start: '2017-10-28'
                // }
            ]
        });

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
            $('#reportrange span').html(' '+start.format('D-M-YYYY') + ' - ' + end.format('D-M-YYYY'));
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
            var newDari = document.getElementById("dari").value;
            var newSampai = document.getElementById("sampai").value;
            url += 'Administrator/kinerja_pegawai/statistic/<?php echo $id;?>'+'?dari='+newDari+'&sampai='+newSampai;

            window.location = url;
        }));
    });
</script>