<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0 text-dark">
        Dashboard
      </h1>

      <div class="row clearfix">
          <div class="col-md-12">
              <div class="card">
                  <?php echo $this->session->flashdata('pesan'); ?>
                  <div class="card-body">
                    <div class="row">
                        <?php echo $pegawai;?>
                        <div class="col-md-8">                            
                            <!-- START PROJECTS BLOCK -->
                            <div class="panel panel-default">
                                <div class="panel-body panel-body-table">
                                    <div id="container2" style="max-height: 400px"></div>                                   
                                </div>
                            </div>
                            <!-- END PROJECTS BLOCK -->
                        </div>
                    </div>
                    <div class="row">
                      <div id='calendar'></div>
                    </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url();?>assets/js/plugins/highcharts/code/highcharts.js"></script>

<script src="https://code.highcharts.com/highcharts-3d.js"></script>

<script src="<?php echo base_url();?>assets/js/plugins/highcharts/code/modules/exporting.js"></script>

    <?php echo $line_chart;?>



<link href="<?php echo base_url();?>assets/fullcalendar/fullcalender/lib/cupertino/jquery-ui.min.css" rel="stylesheet" type="text/css" />

<link href="<?php echo base_url();?>assets/fullcalendar/fullcalender/fullcalendar.css" rel="stylesheet" type="text/css" />

<link href="<?php echo base_url();?>assets/fullcalendar/fullcalender/fullcalendar.print.css" rel="stylesheet" type="text/css" media="print" />

<script src="<?php echo base_url();?>assets/fullcalendar/fullcalender/lib/moment.min.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>assets/fullcalendar/fullcalender/lib/jquery.min.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>assets/fullcalendar/fullcalender/lib/jquery-ui.custom.min.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>/assets/fullcalendar/fullcalender/fullcalendar.min.js" type="text/javascript"></script>

<?php

function tanggal_sekarang($time=FALSE)

{

    date_default_timezone_set('Asia/Jakarta');

    $str_format='';

    if($time==FALSE)

    {

        $str_format= date("Y-m-d");

    }else{

        $str_format= date("Y-m-d H:i:s");

    }

    return $str_format;

}

?>

<script>



    $(document).ready(function() {

    

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

<style>

    #calendar {

        max-width: 100%;

        margin: 0 auto;

    }

</style>