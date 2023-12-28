<title>FULL CALENDAR</title>
<link href="fullcalender/lib/cupertino/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<link href="fullcalender/fullcalendar.css" rel="stylesheet" type="text/css" />
<link href="fullcalender/fullcalendar.print.css" rel="stylesheet" type="text/css" media="print" />
<script src="fullcalender/lib/moment.min.js" type="text/javascript"></script>
<script src="fullcalender/lib/jquery.min.js" type="text/javascript"></script>
<script src="fullcalender/lib/jquery-ui.custom.min.js" type="text/javascript"></script>
<script src="fullcalender/fullcalendar.min.js" type="text/javascript"></script>
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
			theme:true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultDate: '<?=tanggal_sekarang();?>',
			editable: true,
			eventLimit: true,
			events: {
				url: 'data.php',				
			},
			loading: function(bool) {
				$('#loading').toggle(bool);
			}
		});
		
	});

</script>
<style>
	#calendar {
		max-width: 700px;
		margin: 0 auto;
	}
</style>
<div id='loading'>loading...</div>
<div id='calendar'></div>