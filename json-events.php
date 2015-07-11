<?php
session_start(); 
include("includes/db.conn.php");
include('includes/conf.class.php');

$cid=$bsiCore->ClearInput($_GET['capacity_id']);
$rid=$bsiCore->ClearInput($_GET['roomtype_id']);
	//echo $_GET['roomtype_id'];	
	 function getDateRangeArray($startDate, $endDate, $nightAdjustment = true) {	
		$date_arr = array(); 
	     $time_from = mktime(1,0,0,substr($startDate,5,2), substr($startDate,8,2),substr($startDate,0,4));
		 $time_to = mktime(1,0,0,substr($endDate,5,2), substr($endDate,8,2),substr($endDate,0,4));		
		if ($time_to >= $time_from) { 
			if($nightAdjustment){
				while ($time_from < $time_to) {      
					array_push($date_arr, date('Y-m-d',$time_from));
					$time_from+= 86400; // add 24 hours
				}
			}else{
				while($time_from <= $time_to) {      
					array_push($date_arr, date('Y-m-d',$time_from));
					$time_from+= 86400; // add 24 hours
				}
			}			
		}  
		return $date_arr;		
	}
	
	$from_date= date("Y-m-d");
	
	$to_date= date('Y-m-d', strtotime($from_date. ' + '.$bsiCore->config['conf_maximum_global_years'].' days'));
	
	$total_dt=getDateRangeArray($from_date,$to_date);
	$selected_dt=getDateRangeArray($_SESSION['sv_mcheckindate'],$_SESSION['sv_mcheckoutdate'],false);
	$jsonArray = array();
	foreach ($total_dt as $key => $value) {
		
		$sql_booked=mysql_query("SELECT br.room_id FROM `bsi_bookings` as bb LEFT JOIN bsi_reservation as br ON bb.`booking_id`=br.bookings_id JOIN bsi_room as brm on br.room_id=  brm.room_ID WHERE ('".$value."' between bb.`start_date` and DATE_SUB(bb.end_date, INTERVAL 1 DAY)) and br.room_type_id=".$rid." and bb.payment_success=1 and  is_deleted=0  and brm.capacity_id=".$cid);
		
		$sql_total=mysql_query("SELECT `room_ID` FROM `bsi_room` where `roomtype_id`=".$rid." and `capacity_id`=".$cid);
		
		$room_available=(mysql_num_rows($sql_total)-mysql_num_rows($sql_booked));
		
		$sql_1=mysql_query("SELECT * FROM `bsi_priceplan` WHERE ('".$value."' between  `start_date` and `end_date`) and `roomtype_id`=".$rid." and `capacity_id`=".$cid."  and `default_plan`=0");
		$sql_2=mysql_query("SELECT * FROM `bsi_priceplan` WHERE `roomtype_id`=".$rid." and `capacity_id`=".$cid." and `default_plan`=1");
		 $a=strtolower(date('D', strtotime($value)));
		if(mysql_num_rows($sql_1)){			
			$row1=mysql_fetch_assoc($sql_1);			
			$b=round($row1[$a],1);
		}else{
		    $row2=mysql_fetch_assoc($sql_2);		   
			$b=round($row2[$a],1);
		}
		
		if($bsiCore->config['conf_tax_amount'] > 0 && $bsiCore->config['conf_price_with_tax']==1){
			 $b=round($b+(($b*$bsiCore->config['conf_tax_amount'])/100),1);
		}
		
		$sql_sp=mysql_query("select * from bsi_special_offer where '".$value."' between  `start_date` and `end_date` and (room_type=".$rid ." or room_type=0)");
		$row99=mysql_fetch_assoc($sql_sp);
		
			if(mysql_num_rows($sql_sp)){			
				if (in_array($value, $selected_dt) &&  $_SESSION['sv_nightcount'] < $row99['min_stay']) {	
				$b=$bsiCore->get_currency_symbol($_SESSION['sv_currency']).$bsiCore->getExchangemoney($b,$_SESSION['sv_currency']);
				}else{
					 $c=round($b - (($b*$row99['price_deduc'])/100),1);
				 $c=$bsiCore->get_currency_symbol($_SESSION['sv_currency']).$bsiCore->getExchangemoney($c,$_SESSION['sv_currency']);
				 $d=$bsiCore->get_currency_symbol($_SESSION['sv_currency']).$bsiCore->getExchangemoney($b,$_SESSION['sv_currency']);
				 $b='<del style="color:#fb7982">'.$d.'</del>  '.$c;
					
				}
				
			}else{
				$b=$bsiCore->get_currency_symbol($_SESSION['sv_currency']).$bsiCore->getExchangemoney($b,$_SESSION['sv_currency']);
			}
		
		 
		
		if (in_array($value, $selected_dt)) {
			if($_SESSION['sv_mcheckindate']==$value){
				$cellclass='cell_custom_available_checkin';
				$cellcolor='#296905';
			}elseif($_SESSION['sv_mcheckoutdate']==$value){
				$cellclass='cell_custom_available_checkout';
				$cellcolor='#296905';
			}else{
				$cellclass='cell_custom_available_1';
				$cellcolor='#6d067c';
			}
			
			
			if($room_available <= 0){
			$buildjson = array('id' => $key+1, 'title' => "$b", 'start' => "$value", 'className'=>"cell_custom" );
		    $buildjson1 = array('id' => $key+1, 'title' => "Booked", 'start' => "$value",'backgroundColor'=>"$cellcolor", 'className'=>"$cellclass");
			}else{
			$buildjson = array('id' => $key+1, 'title' => "$b", 'start' => "$value", 'className'=>"cell_custom" );
		    $buildjson1 = array('id' => $key+1, 'title' => "Available($room_available)", 'start' => "$value",'backgroundColor'=>"$cellcolor", 'className'=>"$cellclass");
			}
		}else{
		
		if($room_available >= 1){
			$buildjson = array('id' => $key+1, 'title' => "$b", 'start' => "$value", 'className'=>"cell_custom" );
		 $buildjson1 = array('id' => $key+1, 'title' => "Available($room_available)", 'start' => "$value",'backgroundColor'=>"#296905", 'className'=>"cell_custom_available_1");
	
		}elseif($room_available <= 0 ){
			$buildjson = array('id' => $key+1, 'title' => "$b", 'start' => "$value", 'className'=>"cell_custom" );
		 $buildjson1 = array('id' => $key+1, 'title' => "Booked", 'start' => "$value",'backgroundColor'=>"#e0061b", 'className'=>"cell_custom_booked");
		}
		}
		
		  array_push($jsonArray, $buildjson);
		  array_push($jsonArray, $buildjson1);
	}
	
echo json_encode($jsonArray);
?>
