<?php
/**
* @package BSI
* @author BestSoft Inc see README.php
* @copyright BestSoft Inc.
* See COPYRIGHT.php for copyright notices and details.
*/
class bsiSearch
{
	public $checkInDate = '';
    public $checkOutDate = '';	
	public $mysqlCheckInDate = '';
    public $mysqlCheckOutDate = '';
	public $currency = '';
	public $guestsPerRoom = 0;
	public $childPerRoom = 0;
	public $extrabedPerRoom = false;
	public $nightCount = 0;	
	public $fullDateRange;
	public $roomType = array();	
	public $multiCapacity = array();
	public $searchCode = "SUCCESS";
	const SEARCH_CODE = "SUCCESS";
	function bsiSearch() {				
		$this->setRequestParams();
		$this->getNightCount();
		$this->checkSearchEngine();		
		if($this->searchCode == self::SEARCH_CODE){
			$this->loadMultiCapacity();			
			$this->loadRoomTypes();
			$this->fullDateRange = $this->getDateRangeArray($this->mysqlCheckInDate, $this->mysqlCheckOutDate);
			$this->setMySessionVars();
		}	
	}
	private function setRequestParams() {		
		global $bsiCore;
	    $tmpVar = isset($_POST['check_in'])? $_POST['check_in'] : $_SESSION['sv_checkindate'];
		$this->setMyParamValue($this->checkInDate, $bsiCore->ClearInput($tmpVar), NULL, true);
		$tmpVar = isset($_POST['check_out'])? $_POST['check_out'] : $_SESSION['sv_checkoutdate']; 
		$this->setMyParamValue($this->checkOutDate, $bsiCore->ClearInput($tmpVar), NULL, true); 
	    $tmpVar = isset($_POST['capacity'])? $_POST['capacity'] : $_SESSION['sv_guestperroom'];
		$this->setMyParamValue($this->guestsPerRoom, $bsiCore->ClearInput($tmpVar), 0, true); 
		
		$tmpVar = isset($_POST['child_per_room'])? $_POST['child_per_room'] : $_SESSION['sv_childcount'] ;
		$this->setMyParamValue($this->childPerRoom, $bsiCore->ClearInput($tmpVar), 0, true);
		
		$tmpVar = isset($_REQUEST['currency'])? $_REQUEST['currency'] : $_SESSION['sv_currency'] ;
		$this->setMyParamValue($this->currency, $bsiCore->ClearInput($tmpVar), 0, true);
				
		$this->mysqlCheckInDate = $bsiCore->getMySqlDate($this->checkInDate);   	  
		$this->mysqlCheckOutDate = $bsiCore->getMySqlDate($this->checkOutDate);	  			
	}
	
	private function setMyParamValue(&$membervariable, $paramvalue, $defaultvalue, $required = false){
		if($required){if(!isset($paramvalue)){$this->invalidRequest();}}
		if(isset($paramvalue)){  $membervariable = $paramvalue;}else{$membervariable = $defaultvalue;}
	}
	private function setMySessionVars(){
		if(isset($_SESSION['sv_checkindate'])) unset($_SESSION['sv_checkindate']);
		if(isset($_SESSION['sv_checkoutdate'])) unset($_SESSION['sv_checkoutdate']);
		if(isset($_SESSION['sv_mcheckindate'])) unset($_SESSION['sv_mcheckindate']);
		if(isset($_SESSION['sv_mcheckoutdate'])) unset($_SESSION['sv_mcheckoutdate']);
		if(isset($_SESSION['sv_nightcount'])) unset($_SESSION['sv_nightcount']);
		if(isset($_SESSION['sv_guestperroom'])) unset($_SESSION['sv_guestperroom']);
		if(isset($_SESSION['sv_childcount'])) unset($_SESSION['sv_childcount']);
		if(isset($_SESSION['sv_currency'])) unset($_SESSION['sv_currency']);
		
	    $_SESSION['sv_checkindate'] = $this->checkInDate;
		$_SESSION['sv_checkoutdate'] = $this->checkOutDate;
		$_SESSION['sv_mcheckindate'] = $this->mysqlCheckInDate;
		$_SESSION['sv_mcheckoutdate'] = $this->mysqlCheckOutDate;
		$_SESSION['sv_nightcount'] = $this->nightCount;		
		$_SESSION['sv_guestperroom'] = $this->guestsPerRoom;		
		$_SESSION['sv_childcount'] = $this->childPerRoom;	
		$_SESSION['sv_currency'] = $this->currency;	
		$_SESSION['svars_details'] = array();
	}
	
	private function invalidRequest(){
		header('Location: booking-failure.php?error_code=9');
		die;
	}
	private function getNightCount() {		
		$checkin_date = getdate(strtotime($this->mysqlCheckInDate));
		$checkout_date = getdate(strtotime($this->mysqlCheckOutDate));
		$checkin_date_new = mktime( 12, 0, 0, $checkin_date['mon'], $checkin_date['mday'], $checkin_date['year']);
		$checkout_date_new = mktime( 12, 0, 0, $checkout_date['mon'], $checkout_date['mday'], $checkout_date['year']);
		$this->nightCount = round(abs($checkin_date_new - $checkout_date_new) / 86400);
	}
	private function getDateRangeArray($startDate, $endDate, $nightAdjustment = true) {	
		$date_arr = array(); 
		$day_array=array(); 
		$total_array=array();
	     $time_from = mktime(1,0,0,substr($startDate,5,2), substr($startDate,8,2),substr($startDate,0,4));
		 $time_to = mktime(1,0,0,substr($endDate,5,2), substr($endDate,8,2),substr($endDate,0,4));		
		if ($time_to >= $time_from) { 
			if($nightAdjustment){
				while ($time_from < $time_to) {      
					array_push($date_arr, date('Y-m-d',$time_from));
					array_push($day_array, date('D',$time_from));
					$time_from+= 86400; // add 24 hours
				}
			}else{
				while($time_from <= $time_to) {      
					array_push($date_arr, date('Y-m-d',$time_from));
					array_push($day_array, $time_from);
					$time_from+= 86400; // add 24 hours
				}
			}			
		}  
		array_push($total_array, $date_arr);
		array_push($total_array, $day_array);
		return $total_array;		
	}
	private function checkSearchEngine(){
		global $bsiCore;
		if(intval($bsiCore->config['conf_booking_turn_off']) > 0){
			$this->searchCode = "SEARCH_ENGINE_TURN_OFF";
			return 0;
		}
		$diffrow = mysql_fetch_assoc(mysql_query("SELECT DATEDIFF('".$this->mysqlCheckOutDate."', '".$this->mysqlCheckInDate."') AS INOUTDIFF"));
		 $dateDiff = intval($diffrow['INOUTDIFF']);
		if($dateDiff < 0){
			$this->searchCode = "OUT_BEFORE_IN";
			return 0;
		}else if($dateDiff < intval($bsiCore->config['conf_min_night_booking'])){
			$this->searchCode = "NOT_MINNIMUM_NIGHT";
			return 0;
		}
		$userInputDate = strtotime($this->mysqlCheckInDate);
		$hotelDateTime = strtotime(date("Y-m-d"));
		$timezonediff =  ($userInputDate - $hotelDateTime);
		if($timezonediff < 0){
			$this->searchCode = "TIME_ZONE_MISMATCH";
			return 0;
		}		
	}
	private function loadRoomTypes() {			
		$sql = mysql_query("SELECT * FROM bsi_roomtype");
		while($currentrow = mysql_fetch_assoc($sql)){	
			array_push($this->roomType, array('rtid'=>$currentrow["roomtype_ID"], 'rtname'=>$currentrow["type_name"], 'rtimg'=>$currentrow["img"]));
		}
		mysql_free_result($sql);
	}	
	private function loadMultiCapacity() {	
		$sql = mysql_query("SELECT * FROM bsi_capacity WHERE capacity >= ".$this->guestsPerRoom);
		while($currentrow = mysql_fetch_assoc($sql)){			
			$this->multiCapacity[$currentrow["id"]] = array('capval'=>$currentrow["capacity"],'captitle'=>$currentrow["title"]);
			
		}	
		mysql_free_result($sql);
	}
	
	public function getAvailableRooms($roomTypeId, $roomTypeName, $capcityid){
		global $bsiCore;		
		$currency_symbol = $bsiCore->config['conf_currency_symbol'];		
		$searchresult = array('roomtypeid'=>$roomTypeId, 'roomtypename'=>$roomTypeName, 'capacityid'=>$capcityid, 'capacitytitle'=>$this->multiCapacity[$capcityid]['captitle'], 'capacity'=>$this->multiCapacity[$capcityid]['capval'], 'maxchild'=>$this->childPerRoom);
		$room_count = 0;
		$dropdown_html = '<option value="0" selected="selected">0</option>';
		$price_details_html = '';
		$total_price_amount = 0;
		$calculated_extraprice = 0;
		$total_specail_price=0;
		$specail_price_flag=0;
		$extraSearchParam = "";
		$minimum_night_flg=1;
		$variable_concat=1;
		$searchsql = "
		SELECT rm.room_ID, rm.room_no
		  FROM bsi_room rm
		 WHERE rm.roomtype_id = ".$roomTypeId."
			   AND rm.capacity_id = ".$capcityid."".$extraSearchParam."
			   AND rm.room_id NOT IN
					  (SELECT resv.room_id
						 FROM bsi_reservation resv, bsi_bookings boks
						WHERE     boks.is_deleted = FALSE
							  AND resv.bookings_id = boks.booking_id
							  AND resv.room_type_id = ".$roomTypeId."
							  AND (('".$this->mysqlCheckInDate."' BETWEEN boks.start_date AND DATE_SUB(boks.end_date, INTERVAL 1 DAY))
							   OR (DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY) BETWEEN boks.start_date AND DATE_SUB(boks.end_date, INTERVAL 1 DAY))			   OR (boks.start_date BETWEEN '".$this->mysqlCheckInDate."' AND DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY))   					   OR (DATE_SUB(boks.end_date, INTERVAL 1 DAY) BETWEEN '".$this->mysqlCheckInDate."' AND DATE_SUB('".$this->mysqlCheckOutDate."', INTERVAL 1 DAY))))";
						   
		$sql = mysql_query($searchsql);
		$tmpctr = 1;
		$searchresult['availablerooms'] = array();
		while($currentrow = mysql_fetch_assoc($sql)){				
			$dropdown_html.= '<option value="'.$tmpctr.'">'.$tmpctr.'</option>';
			array_push($searchresult['availablerooms'], array('roomid'=>$currentrow["room_ID"], 'roomno'=>$currentrow["room_no"]));
			$tmpctr++;
		}
		
		mysql_free_result($sql);
		if($tmpctr >= 1){
			$totalDays = $this->getDateRangeArray($this->mysqlCheckInDate, $this->mysqlCheckOutDate);	
			$totalamt3=0;
			
			 
			$dayName=array_count_values($totalDays[1]);
			$_month = date('M',strtotime($this->mysqlCheckInDate));
			$month_ = date('M',strtotime($this->mysqlCheckOutDate));
			$_color = '#f2f2f2';
			$color_ = '#f2f2f2';
			if($_month == $month_){
				$mon = $_month;
			}else{
				$mon = $_month.' - '.$month_;
			}
			 $price_details_html='<tr><td bgcolor='.$_color.'><b>'.MONTH.'</b></td>';
			  foreach($dayName as $days => $totalnum){
				
				 $$days=0;
				 $h1=$days.$variable_concat;
				 $$h1=0;
			 }
			 $total_child_price=0;
			 $total_child_price2=0;
			 $price_details_html.='<td bgcolor='.$color_.' align="right"><b>'.$this->nightCount.' Night(s)</b></td></tr>';
			 foreach($totalDays[0] as $date2 => $val){					
				$pricesql = mysql_query("SELECT * FROM bsi_priceplan WHERE roomtype_id = ".$roomTypeId." AND capacity_id = ".$capcityid." AND ('".$val."' BETWEEN start_date AND end_date)");
				if(mysql_num_rows($pricesql)){
					$row=mysql_fetch_assoc($pricesql);
				}else{
					$pricesql2 = mysql_query("SELECT * FROM bsi_priceplan WHERE roomtype_id = ".$roomTypeId." AND capacity_id = ".$capcityid." AND  default_plan=1");
					$row=mysql_fetch_assoc($pricesql2);
				}
				
				
				
				$day=date('D',strtotime($val));
				$$day+=$row[strtolower($day)];
				//*************************** specail offer ******************
				$sql_sp=mysql_query("select * from bsi_special_offer where '".$val."' between  `start_date` and `end_date` and (room_type=".$roomTypeId ." or room_type=0)");
				$row99=mysql_fetch_assoc($sql_sp);
				
				$h2=$day.$variable_concat;
					if(mysql_num_rows($sql_sp)){							
						$c999=round($row[strtolower($day)]- (($row[strtolower($day)]*$row99['price_deduc'])/100),1);
						$$h2+=$c999;
						if($row99['min_stay'] <= $minimum_night_flg){ $specail_price_flag=1; }					
						$minimum_night_flg++;					
					}else{
						$$h2+=$row[strtolower($day)];
					}
				
				//echo $$day.'/'.$$h2.' ';
				//*************************** specail offer ******************
				$chld_row2=mysql_fetch_assoc(mysql_query("SELECT distinct(`no_of_child`) FROM `bsi_room` WHERE `capacity_id`=".$capcityid." and `roomtype_id`=".$roomTypeId.""));
				if($chld_row2['no_of_child'] >= $this->childPerRoom && $this->childPerRoom != 0){
					$child_flag=true;
					$childpricesql = mysql_query("SELECT * FROM bsi_priceplan WHERE roomtype_id = ".$roomTypeId." AND capacity_id =1001 AND ('".$val."' BETWEEN start_date AND end_date)");
					 if(mysql_num_rows($pricesql)){
						$chrow=mysql_fetch_assoc($childpricesql);
				     }else{
						$childpricesql2 = mysql_query("SELECT * FROM bsi_priceplan WHERE roomtype_id = ".$roomTypeId." AND capacity_id =1001 AND  default_plan=1");
						$chrow=mysql_fetch_assoc($childpricesql2);
				      }
					  
					  $day=date('D',strtotime($val));					
					  $$day+=($chrow[strtolower($day)]*$this->childPerRoom); 		
					  //*************************** specail offer ******************
						$sql_sp1=mysql_query("select * from bsi_special_offer where '".$val."' between  `start_date` and `end_date` and (room_type=".$roomTypeId ." or room_type=0)");
						
						
						//$h22=$day.$variable_concat;
							if(mysql_num_rows($sql_sp1)){	
							$row999=mysql_fetch_assoc($sql_sp1);						
								$c999=round(($chrow[strtolower($day)]*$this->childPerRoom)- ((($chrow[strtolower($day)]*$this->childPerRoom)*$row999['price_deduc'])/100),1);
								//echo $c999;
								$$h2+=$c999;
								 $total_child_price2+=$c999;
								
							}else{
								
								$$h2+=($chrow[strtolower($day)]*$this->childPerRoom);
								  $total_child_price2+=($chrow[strtolower($day)]*$this->childPerRoom); 
							}
						
						//echo $$day.'/'.$$h2.' ';
						//*************************** specail offer ******************			  
					 $total_child_price+=($chrow[strtolower($day)]*$this->childPerRoom); 
					   
					 
					  
				}
			}
			$night_count_at_customprice = 0;	
			$searchresult['prices'] = array();	
			
							
				foreach($dayName as $days => $totalnum){				  
				   $totalamt3=$totalamt3+$$days;	
				   $h3=$days.$variable_concat;
				   $total_specail_price=$total_specail_price+$$h3;
				} 
			
			 $total_price_amount=$totalamt3;
			// echo $total_specail_price;
			 if($bsiCore->config['conf_tax_amount'] > 0 && $bsiCore->config['conf_price_with_tax']==1){ 
			 $total_price_amount=$total_price_amount+(($total_price_amount * $bsiCore->config['conf_tax_amount'])/100);
			 $total_specail_price=$total_specail_price+(($total_specail_price * $bsiCore->config['conf_tax_amount'])/100);
			 $total_child_price=$total_child_price+(($total_child_price * $bsiCore->config['conf_tax_amount'])/100);
			  $total_child_price2=$total_child_price2+(($total_child_price2 * $bsiCore->config['conf_tax_amount'])/100);
			 }
			
		}
		if($specail_price_flag){
			$searchresult['roomprice'] = $total_specail_price;	
		}else{
			$searchresult['roomprice'] = $total_price_amount;	
		}
		if($tmpctr > 1) array_push($_SESSION['svars_details'], $searchresult);
		unset($searchresult);
		
		return array(
		'roomcnt' => $tmpctr-1,		
		'roomdropdown' => $dropdown_html,
		'pricedetails' => $price_details_html,		
		'child_flag'=>$child_flag,
		'specail_price_flag'=>$specail_price_flag,
		'total_specail_price'=>$total_specail_price,
		'total_child_price'=>$total_child_price,
		'total_child_price2'=>$total_child_price2,
		'totalprice' => $total_price_amount); 
	}
}
?>