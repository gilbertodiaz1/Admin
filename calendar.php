<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("language.php");
include('languages/avail-calendar.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='js/fullcalendar/cupertino/theme.css' />
		<link href='js/fullcalendar/fullcalendar.css' rel='stylesheet' />
		<link href='js/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script src='js/fullcalendar/fullcalendar.js'></script>
		<script>
			$(document).ready(function() {
			
				$('#calendar').fullCalendar({
		<?php echo lang_fullcalendar($langauge_selcted); ?>
		height: 530,
		theme: true,
		firstDay: 1,
		month:<?php echo (date('n', strtotime($_SESSION['sv_mcheckindate'])) - 1); ?>,
		
		events: "json-events.php?roomtype_id=<?=$_GET['rtype']?>&capacity_id=<?=$_GET['cid']?>",
		
		eventDrop: function(event, delta) {
		alert(event.title + ' was moved ' + delta + ' days\n' +
		'(should probably update your database)');
		},
		
		loading: function(bool) {
		if (bool) $('#loading').show();
		else $('#loading').hide();
		}
		
		});
		
		});
		</script>
		<style>
			body {
				margin: 0px 0px 0px 0px;
				text-align: center;
				font-size: 16px;
				font-weight:bold;
				font-family:Arial, Helvetica, sans-serif;
				}
				
		#loading {
				position:absolute;
				top: 5px;
				right: 300px;
				color:#C33;
				}
			#calendar {
				width: 650px;
				margin: 0 auto;
				}
			.cell_custom{
				text-align:center;
				font-size: 11px;
				font-weight:bold;
				
			}
			.cell_custom_available_all{
				text-align:center;
				font-size: 11px;
				
			}
			.cell_custom_booked{
				text-align:center;
				padding-top:4px;
				padding-bottom:4px;
				font-size: 14px;
				
			}
			.cell_custom_available_1{
				text-align:center;
				padding-top:4px;
				padding-bottom:4px;
				font-size: 12px;
			
				
			}
			
			.cell_custom_available_checkin{
				text-align:center;
				padding-top:4px;
				padding-bottom:4px;
				font-size: 12px;
				background:url(images/cin.png) no-repeat;
				
			}
			
			.cell_custom_available_checkout{
				text-align:center;
				padding-top:4px;
				padding-bottom:4px;
				font-size: 12px;
				background:url(images/cout.png) no-repeat;
				
			}
		</style>
	</head>
	<body>
		<div id='loading' style='display:none'><?php echo LOADING_TEXT; ?>...</div>
		<div id='calendar'></div>
	</body>
</html>