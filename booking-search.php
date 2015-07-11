<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/search.class.php");
include("language.php");
$bsisearch = new bsiSearch();
$bsiCore->clearExpiredBookings();
$pos2 = strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']);
if($bsisearch->nightCount==0 and !$pos2){
	header('Location: booking-failure.php?error_code=9');
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $bsiCore->config['conf_hotel_name']; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/bootstrap-responsive.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="css/datepicker.css" type="text/css" media="screen"/>

        <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>

        <link rel="stylesheet" type="text/css" href="css/jquery.lightbox-0.5.css" media="screen" />
        <link rel="stylesheet" href="css/colorbox.css" />
        <link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />

        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="js/hotelvalidation.js"></script>

        <script type="text/javascript" src="js/jquery.lightbox-0.5.min.js"></script>
        <script src="js/jquery.colorbox.js"></script>
        <script defer src="js/jquery.flexslider.js"></script>

        <script type="text/javascript">
            $(window).load(function() {
                $('.flexslider').flexslider({
                    animation: "fade",
                    controlNav: true,
                    directionNav: true
                });
            });
        </script>
        <script>
		function currency_change(ccode)
		{
			window.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?currency=' + ccode;
		}	
	
</script>
        
    </head>
    <body>
        
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
					<div id="body-div">
                        <h1> <?php echo $bsiCore->config['conf_hotel_name']; ?></h1>
                           <div class="wizard">
                            <div class="step1 greenStep"><img src="images/wizard/1.png" alt="" /><p><?php echo SELECT_DATES_TEXT; ?></p></div>
                            <div class="step2 yellowStep"><img src="images/wizard/2.png" alt="" /><p><?php echo ROOMS_TEXT; ?> &amp; <?php echo RATES_TEXT; ?></p></div>
                            <div class="step3 grayStep"><img src="images/wizard/3.png" alt="" /><p><?php echo YOUR_DETAILS_TEXT; ?></p></div>
                            <div class="step4 grayStep"><img src="images/wizard/4.png" alt="" /><p><?php echo PAYMENT_TEXT; ?></p></div>
                            <div class="step5 grayLastStep"><img src="images/wizard/5.png" alt="" /><p><?php echo CONFIRM_TEXT; ?></p></div>
                         </div>
                         <div class="progress">
                         	<div class="bar bar-success" style="width: 20%;">20% Complete</div>
						 	<div class="bar bar-warning" style="width: 20%;"></div>
                     	</div>
                     
                        <div class="wrapper">
                            <h2><?php echo SEARCH_INPUT_TEXT; ?> (<a href="index.php"><?php echo MODIFY_SEARCH_TEXT; ?></a>)</h2>
                            <table class="normal-table">
                                <tbody>
                                    <tr>
                                      <td><strong>
                                        <?php echo CHECK_IN_D_TEXT; ?>
                                        :</strong></td>
                                      <td><?php echo $bsisearch->checkInDate; ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong>
                                        <?php echo CHECK_OUT_D_TEXT; ?>
                                        :</strong></td>
                                      <td><?php echo $bsisearch->checkOutDate; ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong>
                                        <?php echo TOTAL_NIGHTS_TEXT; ?>
                                        :</strong></td>
                                      <td><?php echo $bsisearch->nightCount; ?></td>
                                    </tr>
                                    <tr>
                                      <td><strong>
                                        <?php echo ADULT_ROOM_TEXT; ?>
                                        :</strong></td>
                                      <td><?php echo $bsisearch->guestsPerRoom?></td>
                                    </tr>
                                    
                                     <?php if($bsisearch->childPerRoom){ ?>
                                     <tr>
                                     <td><strong><?php echo CHILD_PER_ROOM_TEXT; ?>:</strong></td>
                                     <td><?php echo $bsisearch->childPerRoom; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            
                             <form name="searchresult" id="searchresult" method="post" action="booking_details.php" onSubmit="return validateSearchResultForm('<?php echo SELECT_ONE_ROOM_ALERT; ?>');">
                        </div>
                        <div class="wrapper">
                            <div class="htitel">
                                <h2 class="fl" style="border:0; margin:0;"><?php echo SEARCH_RESULT_TEXT; ?></h2>
                                <div class="control-group fr">
                                    <div class="controls">
                                        <?php echo $bsiCore->get_currency_combo2($bsisearch->currency); ?>
                                    </div>
                                </div>
                            </div>
                            
                                    <?php
										$gotSearchResult = false;
										$idgenrator = 0;
										$ik=1;
										foreach($bsisearch->roomType as $room_type){
											foreach($bsisearch->multiCapacity as $capid => $capvalues){
												$room_result = $bsisearch->getAvailableRooms($room_type['rtid'], $room_type['rtname'], $capid);
													
													$sqlroomcheck=mysql_query("select * from bsi_room where roomtype_id=".$room_type['rtid']." and capacity_id=".$capid);
													if(mysql_num_rows($sqlroomcheck)){
													echo '<script> $(document).ready(function() { ';
													echo '
														$("#iframe_'.str_replace(" ","",$room_type['rtid']).'_'.str_replace(" ","",$capid).$ik.'").colorbox({iframe:true, width: $(\'#body-div\').width() + \'px\', height: "90%"});
														$("#iframe_details_'.str_replace(" ","",$room_type['rtid']).'_'.str_replace(" ","",$capid).$ik.'").colorbox({iframe:true,  width: $(\'#body-div\').width() + \'px\', height: "60%"}); 
														$(".group_'.$room_type['rtid'].'_'.$capid.'").colorbox({rel:\'group_'.$room_type['rtid'].'_'.$capid.'\', maxWidth: $(\'#body-div\').width(), maxHeight:"80%", slideshow:true, slideshowSpeed:5000});
														
															';
													echo '}); </script>';
													echo '<script type="text/javascript">
															$(document).ready(function() {
																$("#mySlides_'.$capid.'_'.$room_type['rtid'].' a").lightBox();
															});
													  </script>';	
				  
	                          ?>
                            <!-- start of search row --> 
                            <div class="container-fluid" style="margin:0; padding:0;">
                                <div class="row-fluid" style="background-color: #faac59; padding: 1% 0">
                                   
                                    <div class="span12">
                                        <div class="span5">
                                            <div class="search-gallery">
                                                <div class="flexslider" style="cursor:pointer; cursor:hand;" >
                                                    <ul class="slides">
                                                        <?php echo $bsiCore->roomtype_photos($room_type['rtid'],$capid); ?>        
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span7">
                                            <div class="search-details">
                                                <table cellpadding="5" cellspacing="0" border="0" width="100%"  bgcolor="#faac59" style="text-align:left; " >
                                                 <?php if($room_result['specail_price_flag']){ $offertag='style="background:url(images/offer.png) no-repeat left top; padding-left:23px; height:35px;"'; }else{ $offertag='';  }?>
                                                
                                                    <tr>
                                                        <td width="100%" <?php echo $offertag; ?>>&nbsp; 
                                                            <span  style="font-size:18px;"><strong><?php echo $room_type['rtname']; ?></strong> (<?php echo $capvalues['captitle']; ?>) <?php if($room_result['child_flag']){ ?> <?php echo WITH_CHILD; ?> <?php } ?></strong> </span>  <span style="float:right;">
                                                                <a href="roomtype-details.php?tid=<?php echo $room_type['rtid']; ?>" id='iframe_details_<?php echo str_replace(" ","",$room_type['rtid']).'_'.str_replace(" ","",$capid).$ik; ?>' style="font-weight:bold; color:#FFF;" ><?php echo VIEW_ROOM_FACILITIES_TEXT; ?></a></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="100%" valign="top" style="font-size:13px">
                                                            <table  width="100%" class="table table-bordered table2">
                                                                <tr>
                                                                    <td colspan="2" bgcolor="#faa448" >
                                                                        <span style=" font-size:14px; font-weight:bold">
                                                                            <a style="color:#fff" id='iframe_<?php echo str_replace(" ","",$room_type['rtid']).'_'.str_replace(" ","",$capid).$ik; ?>' href="calendar.php?rtype=<?php echo $room_type['rtid']; ?>&cid=<?php echo $capid; ?>" title='<span  style="font-size:16px;"><strong><?php echo $room_type['rtname']; ?></strong> ( <?php echo $capvalues['captitle']; ?> ) </span>' ><?php echo VIEW_NIGHTLY_PRICE_TEXT; ?> &amp; <?php echo CALENDAR_AVAILABILITY_TEXT; ?></a>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                <tr> 
                                                                    <td bgcolor="#faa448"><strong><?php echo MAX_OCCUPENCY_TEXT; ?></strong></td>
                                                                    <td bgcolor="#faa448"><?php echo $capvalues['capval']; ?>
                    <?php echo ADULT_TEXT; ?> <?php if($room_result['child_flag']){ ?> <?php echo AND_TEXT; ?> <?php echo $bsisearch->childPerRoom;?> <?php echo CHILD_TEXT; ?><?php } ?> <?php echo PER_ROOM_TEXT; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#faa448"><strong> <?php echo TOTAL_PRICE_OR_ROOM_TEXT; ?> </strong></td>
                                                                     <?php if($room_result['specail_price_flag']){ ?>
                                                                    <td bgcolor="#faa448"><span style="font-weight:bold; color:#cc0000;"> <del><?php echo $bsiCore->get_currency_symbol($bsisearch->currency).$bsiCore->getExchangemoney($room_result['totalprice'],$bsisearch->currency); ?></del> </span>  <strong><?php echo $bsiCore->get_currency_symbol($bsisearch->currency).$bsiCore->getExchangemoney($room_result['total_specail_price'],$bsisearch->currency); ?></strong> <?php if($room_result['child_flag']){ ?> (included <span style="color:#cc0000; text-decoration:line-through;"><?php echo $bsiCore->get_currency_symbol($bsisearch->currency).$bsiCore->getExchangemoney($room_result['total_child_price'],$bsisearch->currency); ?></span> <?php echo $bsiCore->get_currency_symbol($bsisearch->currency).$bsiCore->getExchangemoney($room_result['total_child_price2'],$bsisearch->currency); ?> <?php echo FOR_TEXT; ?> <?php echo $bsisearch->childPerRoom;?> <?php echo CHILD_TEXT; ?>) <?php }?></td>
                                                                    
                                                                    <?php }else{ ?>
                                                                    <td bgcolor="#faa448"><span style="font-weight:bold;"><strong><?php echo $bsiCore->get_currency_symbol($bsisearch->currency).$bsiCore->getExchangemoney($room_result['totalprice'],$bsisearch->currency); ?></strong> <?php if($room_result['child_flag']){ ?> (included <?php echo $bsiCore->get_currency_symbol($bsisearch->currency).$bsiCore->getExchangemoney($room_result['total_child_price'],$bsisearch->currency); ?> <?php echo FOR_TEXT; ?> <?php echo $bsisearch->childPerRoom;?> <?php echo CHILD_TEXT; ?>) <?php }?></td>
                                                                     <?php } ?>
                                                                </tr>
                                                                <tr>
														      <?php
                                                                    if(intval($room_result['roomcnt']) > 0){
                                                                    $gotSearchResult = true;
                                                                    ?>
                                                                
                                                                    <td bgcolor="#faa448"><strong><?php echo SELECT_NUMBER_OF_ROOM_TEXT; ?></strong></td>
                                                                    <td bgcolor="#faa448">
                                                                        <select name="svars_selectedrooms[]" class="input-mini">
                                                                            <?php echo $room_result['roomdropdown']; ?>
                                                                        </select>
                                                                    </td>
                                                                    <?php }else{ 
																	echo '<script> $(document).ready(function() { ';
																		echo '
																			$("#iframe2_'.str_replace(" ","",$room_type['rtid']).'_'.str_replace(" ","",$capid).$ik.'").colorbox({iframe:true, width: $(\'.container-fluid\').width() + \'px\', height: "80%"});
																			
																				';
																		echo '}); </script>';
					                                               ?>
                                                                    
                                                                    <td bgcolor="#faa448" colspan="2">
                                                                       <strong>Not Available</strong></span> ( <a style="color:#fff; font-size:13px" id='iframe2_<?php echo str_replace(" ","",$room_type['rtid']).'_'.str_replace(" ","",$capid).$ik; ?>' href="calendar.php?rtype=<?php echo $room_type['rtid']; ?>&cid=<?php echo $capid; ?>" title='<span  style="font-size:16px;"><strong><?php echo $room_type['rtname']; ?></strong> ( <?php echo $capvalues['captitle']; ?> ) </span>' ><strong><?php echo CHECK_AVILABILITY; ?> </strong></a> )
                                                                    </td>
                                                                   <?php } ?> 
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>  
                            <!-- end of search row --> 
                            <?php } } }
							
							$flag88=0;
								if($gotSearchResult){
									echo '<div id="" style="width:600px !important;"><table cellpadding="5" cellspacing="0" border="0" width="100%" >';
									echo '<tr><td align="right" style="padding-right:30px;"></td></tr>';
									echo '</table></div>';	
								$flag88=1;
								}else{
									echo '<table cellpadding="4" cellspacing="0" width="100%"><tbody><tr><td style="font-size:13px; color:#F00;" align="center"><br /><br />';
									if($bsisearch->searchCode == "SEARCH_ENGINE_TURN_OFF"){
										echo SORRY_ONLINE_BOOKING_CURRENTLY_NOT_AVAILABLE_TEXT;				
									}else if($bsisearch->searchCode == "OUT_BEFORE_IN"){
										echo SORRY_YOU_HAVE_ENTERED_A_INVALID_SEARCHING_CRITERIA_TEXT;				
									}else if($bsisearch->searchCode == "NOT_MINNIMUM_NIGHT"){
										echo MINIMUM_NUMBER_OF_NIGHT_SHOULD_NOT_BE_LESS_THAN_TEXT.' '.$bsiCore->config['conf_min_night_booking'].' '. PLEASE_MODIFY_YOUR_SEARCHING_CRITERIA_TEXT;
									}else if($bsisearch->searchCode == "TIME_ZONE_MISMATCH"){
										$tempdate = date("l F j, Y G:i:s T");
										echo BOOKING_NOT_POSSIBLE_FOR_CHECK_IN_DATE_TEXT.' '.$bsisearch->checkInDate.' '. PLEASE_MODIFY_YOUR_SEARCHING_CRITERIA_TO_HOTELS_DATE_TIME_TEXT.'<br>'. HOTELS_CURRENT_DATE_TIME_TEXT.' '.$tempdate; 
									}else{
										echo SORRY_NO_ROOM_AVAILABLE_AS_YOUR_SEARCHING_CRITERIA_TRY_DIFFERENT_DATE_SLOT;
									}
									echo '<br /><br /><br /></td></tr></tbody></table>';
								}
							
							 ?>
                            
                        
                        </div>
                        <div class="wrapper" style="margin-bottom: 20px">
                            <div class="container-fluid" style="margin:0; padding:0;">
                                <div class="row-fluid" style="background-color: #faac59; padding: 1% 0">
                                <?php if($flag88){  ?>
                                    <div class="span12">
                                        <div class="back1">
                                            <button id="registerButton" type="button" onClick="window.location.href='index.php'" ><?php echo BACK_TEXT; ?></button>
                                        </div>
                                        <div class="continue2">
                                            <button id="registerButton" type="submit" class="conti2" ><?php echo CONTINUE_TEXT; ?></button>
                                        </div>
                                    </div>
                                    <?php }else{ ?>
                                    
                                       <div class="span12">
                                        <div class="back1">
                                            <button id="registerButton" type="button" onClick="window.location.href='index.php'" ><?php echo BACK_TEXT; ?></button>
                                        </div>
                                        
                                    </div>
                                     <?php } ?> 
                                </div> 
                               </form> 
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
