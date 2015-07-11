<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("language.php");

include("includes/details.class.php");
$bsibooking = new bsiBookingDetails();
$bsiCore->clearExpiredBookings();
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

        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.validate.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/bootstrap-datepicker.js"></script>

        <script type="text/javascript">
            $(window).load(function() {
                
            });
        </script>
        
        <script type="text/javascript">
	$().ready(function() {
		$("#form1").validate();
    });        
</script>
<script type="text/javascript">
$(document).ready(function(){
	$('#btn_exisitng_cust').click(function() {
		if($('#btn_exisitng_cust').val() == '1'){
	    $('#exist_wait').html("<img src='images/ajax-loader.gif' border='0'>")
		var querystr = 'actioncode=2&existing_email='+$('#email_addr_existing').val()+'&login_password='+$('#login_password').val(); 
		$.post("ajaxreq-processor.php", querystr, function(data){ 						 
			if(data.errorcode == 0){
				$('#title').html(data.title)
				$('#fname').val(data.first_name)
				$('#lname').val(data.surname)
				$('#str_addr').val(data.street_addr)
				$('#city').val(data.city)
				$('#state').val(data.province)
				$('#zipcode').val(data.zip)
				$('#country').val(data.country)
				$('#phone').val(data.phone)
				$('#fax').val(data.fax)
				$('#email').val(data.email)
				$('#login_c').html('leave blank for unchange password!')
				$('#id_type').val(data.id_type)
				$('#id_number').val(data.id_number)
				$('#exist_wait').html("")
				$('#btn_exisitng_cust').html('Logout')
				$('#btn_exisitng_cust').val('2')
				$('#forgot_pass').html('')
				$("#email_addr_existing").attr("disabled", "disabled"); 
				$("#login_password").attr("disabled", "disabled");  
				$("#password").removeClass("required");
			}else { 
				alert(data.strmsg);
				$('#fname').val('')
				$('#lname').val('')
				$('#str_addr').val('')
				$('#city').val('')
				$('#state').val('')
				$('#zipcode').val('')
				$('#country').val('')
				$('#phone').val('')
				$('#fax').val('')
				$('#email').val('')
				$('#login_c').html('')
				$('#id_type').val('')
				$('#id_number').val('')
				$('#exist_wait').html("")
			}	
		}, "json");
		
		}else if($('#btn_exisitng_cust').val() == '2'){
			 $('#exist_wait').html("<img src='images/ajax-loader.gif' border='0'>")
				$('#btn_exisitng_cust').html('Login')
				$('#btn_exisitng_cust').val('1')
				$("#email_addr_existing").removeAttr("disabled"); 
				$("#login_password").removeAttr("disabled");  
				$('#email_addr_existing').val('')
				$('#login_password').val('')
			    $('#fname').val('')
				$('#lname').val('')
				$('#str_addr').val('')
				$('#city').val('')
				$('#state').val('')
				$('#zipcode').val('')
				$('#country').val('')
				$('#phone').val('')
				$('#fax').val('')
				$('#email').val('')
				$('#login_c').html('')
				$('#id_type').val('')
				$('#id_number').val('')
				$('#forgot_pass').html('Forgot Password?')
				$("#password").addClass("required");
				$('#exist_wait').html("")
		}else if($('#btn_exisitng_cust').val() == '3'){
			$('#exist_wait').html("<img src='images/ajax-loader.gif' border='0'>")
			var querystr = 'actioncode=9&existing_email='+$('#email_addr_existing').val(); 
			$.post("ajaxreq-processor.php", querystr, function(data){ 
			if(data.errorcode == 0){
				alert('Your password has been reset. Please check your email and login!')
				$('#label_pass').show()
				$('#input_pass').show()
				$('#btn_exisitng_cust').val('1')
				$('#btn_exisitng_cust').html('Login')
				$('#exist_wait').html("")
			}else{
				alert(data.strmsg);
				$('#exist_wait').html("")
			}
			}, "json");
		}
	});
	
	$('#forgot_pass').toggle(function() {
				$('#label_pass').hide()
				$('#input_pass').hide()
				$('#btn_exisitng_cust').val('3')
				$('#btn_exisitng_cust').html('Submit')
				$('#forgot_pass').html('Back to Login')
		}, function() {
		        $('#label_pass').show()
				$('#input_pass').show()
				$('#btn_exisitng_cust').val('1')
				$('#btn_exisitng_cust').html('Login')
				$('#exist_wait').html("")	
				$('#forgot_pass').html('Forgot Password?')
		
	});
});
function myPopup2(booking_id){
		var width = 730;
		var height = 650;
		var left = (screen.width - width)/2;
		var top = (screen.height - height)/2;
		var url='terms-and-services.php?bid='+booking_id;
		var params = 'width='+width+', height='+height;
		params += ', top='+top+', left='+left;
		params += ', directories=no';
		params += ', location=no';
		params += ', menubar=no';
		params += ', resizable=no';
		params += ', scrollbars=yes';
		params += ', status=no';
		params += ', toolbar=no';
		newwin=window.open(url,'Chat', params);
		if (window.focus) {newwin.focus()}
		return false;
   }
</script>
    </head>
    <body>
       
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
 						<div id="body-div">
                        <h1><?php echo $bsiCore->config['conf_hotel_name']; ?> </h1>
                        <?php $bookingDetails = $bsibooking->generateBookingDetails(); ?>
                        <div class="wizard">
                            <div class="step1 greenStep2"><img src="images/wizard/1.png" alt="" /><p><?php echo SELECT_DATES_TEXT; ?></p></div>
                            <div class="step2 greenStep"><img src="images/wizard/2.png" alt="" /><p><?php echo ROOMS_TEXT; ?> &amp; <?php echo RATES_TEXT; ?></p></div>
                            <div class="step3 yellowStep"><img src="images/wizard/3.png" alt="" /><p><?php echo YOUR_DETAILS_TEXT; ?></p></div>
                            <div class="step4 grayStep"><img src="images/wizard/4.png" alt="" /><p><?php echo PAYMENT_TEXT; ?></p></div>
                            <div class="step5 grayLastStep"><img src="images/wizard/5.png" alt="" /><p><?php echo CONFIRM_TEXT; ?></p></div>
                     	</div>
                         <div class="progress">
                         	<div class="bar bar-success" style="width: 40%;">40% Complete</div>
						 	<div class="bar bar-warning" style="width: 20%;"></div>
                     	</div>
                        <div class="wrapper">
                            <h2><?php echo BOOKING_DETAILS_TEXT; ?></h2>
                            
                            <table cellpadding="4" cellspacing="1" class="table table-bordered table3">
                                <tr>
                                    <td bgcolor="#faa448" align="center"><strong><?php echo CHECKIN_DATE_TEXT; ?></strong></td>
                                    <td bgcolor="#faa448" align="center"><strong><?php echo CHECKOUT_DATE_TEXT; ?></strong></td>
                                    <td bgcolor="#faa448" align="center"><strong><?php echo TOTAL_NIGHT_TEXT; ?></strong></td>
                                    <td bgcolor="#faa448" align="center"><strong><?php echo TOTAL_ROOMS_TEXT; ?></strong></td>
                                </tr>
                                <tr>
                                    <td align="center" bgcolor="#fcb66c"><?php echo $bsibooking->checkInDate; ?></td>
                                    <td align="center" bgcolor="#fcb66c"><?php echo $bsibooking->checkOutDate; ?></td>
                                    <td align="center" bgcolor="#fcb66c"><?php echo $bsibooking->nightCount; ?></td>
                                    <td align="center" bgcolor="#fcb66c"><?php echo $bsibooking->totalRoomCount; ?></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#faa448" align="center"><strong><?php echo NUMBER_OF_ROOM_TEXT; ?></strong></td>
                                    <td bgcolor="#faa448" align="center"><strong><?php echo ROOM_TYPE_TEXT; ?></strong></td>
                                    <td bgcolor="#faa448" align="center"><strong><?php echo MAXI_OCCUPENCY_TEXT; ?></strong></td>
                                    <td bgcolor="#faa448" class="al-r" style="padding-right:5px;"><strong><?php echo GROSS_TOTAL_TEXT; ?></strong></td>
                                </tr>
                                 <?php		
									foreach($bookingDetails as $bookings){		
									$child_title2=($bookings['child_flag2'])? " + ".$bookings['childcount3']." ".CHILD_TEXT." ":"";
										echo '<tr>';
										echo '<td align="center" bgcolor="#fcb66c">'.$bookings['roomno'].'</td>';
										echo '<td align="center" bgcolor="#fcb66c">'.$bookings['roomtype'].' ('.$bookings['capacitytitle'].')</td>';				
										echo '<td align="center" bgcolor="#fcb66c">'.$bookings['capacity'].' '.INV_ADULT.' '.$child_title2.'</td>';
											
										echo '<td class="al-r" bgcolor="#fcb66c" style="padding-right:5px;">'.$bsiCore->get_currency_symbol($_SESSION['sv_currency']).$bsiCore->getExchangemoney($bookings['grosstotal'],$_SESSION['sv_currency']).'</td>';
										echo '</tr>';		
									}
								 ?>                                
                               
                                    <td  class="al-r" colspan="3" bgcolor="#faa448"><strong><?php echo SUB_TOTAL_TEXT; ?></strong></td>
                                    <td  class="al-r" bgcolor="#faa448" style="padding-right:5px;"><strong> <?php echo $bsiCore->get_currency_symbol($_SESSION['sv_currency']).$bsiCore->getExchangemoney($bsibooking->roomPrices['subtotal'],$_SESSION['sv_currency']); ?></strong></td>
                                </tr>
                                 <?php
									if($bsiCore->config['conf_tax_amount'] > 0 &&  $bsiCore->config['conf_price_with_tax']==0){
										$taxtext=""; 
									?>
                                <tr>
                                    <td class="al-r" colspan="3" bgcolor="#fcb66c"><?php echo TAX_TEXT; ?>(
     <?php echo $bsiCore->config['conf_tax_amount']; ?>
     %)</td>
                                    <td class="al-r" bgcolor="#fcb66c" style="padding-right:5px;"><span id="taxamountdisplay"><?php echo $bsiCore->get_currency_symbol($_SESSION['sv_currency']).$bsiCore->getExchangemoney($bsibooking->roomPrices['totaltax'],$_SESSION['sv_currency']); ?></span></td>
                                </tr>
                                <?php }else{
											$taxtext="(".BD_INC_TAX.")";
										}
										?>
                                
                                <tr>
                                    <td  class="al-r" colspan="3" bgcolor="#faa448"><strong><?php echo GRAND_TOTAL_TEXT; ?></strong> <?php echo $taxtext; ?></td>
                                    <td class="al-r" bgcolor="#faa448" style="padding-right:5px;"><strong> <span id="grandtotaldisplay"><?php echo $bsiCore->get_currency_symbol($_SESSION['sv_currency']).$bsiCore->getExchangemoney($bsibooking->roomPrices['grandtotal'],$_SESSION['sv_currency']); ?></span></strong></td>
                                </tr>
                                 <?php 
										if($bsiCore->config['conf_enabled_deposit'] && ($bsibooking->depositPlans['deposit_percent'] > 0 && $bsibooking->depositPlans['deposit_percent'] < 100)){
										?>
                                
                                <tr id="advancepaymentdisplay">
                                    <td class="al-r" colspan="3" bgcolor="#faa448"><strong></strong> <?php echo ADVANCE_PAYMENT_TEXT; ?>(<span style="font-size:11px;">
     <?php echo $bsibooking->depositPlans['deposit_percent']; ?>
     %<?php echo OF_GRAND_TOTAL_TEXT; ?></span>)</td>
                                    <td class="al-r" bgcolor="#faa448" style="padding-right:5px;"><span id="advancepaymentamount"><?php echo $bsiCore->get_currency_symbol($_SESSION['sv_currency']).$bsiCore->getExchangemoney($bsibooking->roomPrices['advanceamount'],$_SESSION['sv_currency']); ?> </span></td>
                                </tr>
                                <?php
                                }
							?>
                            </table>
                            
                        </div>
                        
                        <div class="wrapper">
                            <div class="htitel">
                                <h2 class="fl" style="border:0; margin:0;"><?php echo CUSTOMER_DETAILS_TEXT; ?></h2>
                            </div>
                            <!-- start of search row --> 
                            <div class="container-fluid" style="margin:0; padding:0;">
                                <div class="row-fluid" style="background-color: #faac59; padding: 1% 0">
                                    <div class="span12">
                                        <h3 style="margin-left:2.5%"><?php echo EXISTING_CUSTOMER_TEXT; ?>?</h3>
                                        
                                        <form class="form-horizontal" action="booking-process.php" method="post" id="form1" style="width: 95%; margin: 0 2.5%">
                                            <div class="control-group">
                                                <label class="control-label" for="ea"><?php echo EMAIL_ADDRESS_TEXT; ?>:</label>
                                                <div class="controls">
                                                    <input type="text" name="email_addr_existing" id="email_addr_existing"  class="input-large" />
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="pa">Password:</label>
                                                <div class="controls">
                                                    <input type="password" name="login_password" id="login_password"  class="input-large" />
                                                </div>
                                            </div>
                                             <div class="control-group">
                                                <div class="controls" id="exist_wait" >
                                                    
                                                </div>
                                              </div>
                                              
                                            <div class="control-group">
                                                <label class="control-label"></label>
                                                <div class="controls">
                                                    <button id="btn_exisitng_cust" type="button" style="display:inline-block"><?php echo FETCH_DETAILS_TEXT; ?></button>
                                                    <a href="javascript:;" id="forgot_pass" style="width:150px; display:inline-block; padding-left:10px; cursor:pointer;">Forgot Password?</a>
                                                </div>
                                            </div>
                                            
                                           <div class="text-center"><h1><?php echo OR_TEXT; ?></h1></div> 
                                            
                                      <h3 align="left" style="padding-left:5px; color:#999;"><?php echo NEW_CUSTOMER_TEXT; ?>?</h3>    
                                        <input type="hidden" name="allowlang" id="allowlang" value="no" />
                                            <div class="control-group">
                                                <label class="control-label" name="title" for="title">Title: </label>
                                                <div class="controls">
                                                    <select id="title" class="input-small">
                                                        <option value="Mr."><?php echo MR_TEXT; ; ?>.</option>
                                                       <option value="Ms."><?php echo MS_TEXT; ?>.</option>
                                                       <option value="Mrs."><?php echo MRS_TEXT; ?>.</option>
                                                       <option value="Miss."><?php echo MISS_TEXT; ?>.</option>
                                                       <option value="Dr."><?php echo DR_TEXT; ?>.</option>
                                                       <option value="Prof."><?php echo PROF_TEXT; ?>.</option>
                                                     </select>
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="fn"><?php echo FIRST_NAME_TEXT; ?>:</label>
                                                <div class="controls">
                                                    <input type="text" name="fname" id="fname" class="input-large required">
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="ln"><?php echo LAST_NAME_TEXT; ?>:</label>
                                                <div class="controls">
                                                    <input type="text" name="lname" id="lname" class="input-large required">
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="ln"><?php echo ADDRESS_TEXT; ?>:</label>
                                                <div class="controls">
                                                    <input type="text" name="str_addr" id="str_addr" class="input-large required">
                                                </div>
                                            </div>                                          
                                            
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="ct"><?php echo CITY_TEXT; ?>:</label>
                                                <div class="controls">
                                                    <input type="text" name="city"  id="city" class="input-large required">
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="st"><?php echo STATE_TEXT; ?>:</label>
                                                <div class="controls">
                                                    <input name="state"  id="state" type="text" class="input-large required">
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="pc"><?php echo POSTAL_CODE_TEXT; ?>:</label>
                                                <div class="controls">
                                                    <input name="zipcode"  id="zipcode" type="text" class="input-large required number">
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="co"><?php echo COUNTRY_TEXT; ?>:</label>
                                                <div class="controls">
                                                    <input name="country"  id="country" type="text" class="input-large required">
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="ph"><?php echo PHONE_TEXT; ?>:</label>
                                                <div class="controls">
                                                    <input name="phone"  id="phone" type="text" class="input-large required number">
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="fx"><?php echo FAX_TEXT; ?>:</label>
                                                <div class="controls">
                                                    <input name="fax"  id="fax" type="text" class="input-large">
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="it"><?php echo ID_TYPE; ?>:</label>
                                                <div class="controls">
                                                    <input name="id_type"  id="id_type" type="text" class="input-large"><span class="help-inline">(e.g.: passport.)</span>
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="in"><?php echo ID_NUMBER; ?>:</label>
                                                <div class="controls">
                                                    <input name="id_number"  id="id_number" type="text" class="input-large required"><span class="help-inline">(e.g.: passport number.)</span>
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="em"><?php echo EMAIL_TEXT; ?>:</label>
                                                <div class="controls">
                                                    <input name="email"  id="email" type="text" class="input-large required email">
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="ps">Password:</label>
                                                <div class="controls">
                                                    <input name="password"  id="password" type="password" class="input-large required">
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="pb"><?php echo PAYMENT_BY_TEXT; ?>:</label>
                                                <div class="controls">
                                                
                                                <?php
													$paymentGatewayDetails = $bsiCore->loadPaymentGateways();				
													foreach($paymentGatewayDetails as $key => $value){ 	
														echo '<label class="radio"><input type="radio" name="payment_type" id="payment_type_'.$key.'" value="'.$key.'" class="required" /> '.$value['name'].'</label>';
													}
													?>
                                                    <label class="error" generated="true" for="payment_type" style="display:none;"><?php echo FIELD_REQUIRED_ALERT; ?>.</label>                                                    
                                                </div>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label class="control-label" for="ar"><?php echo ADDITIONAL_REQUESTS_TEXT; ?> :</label>
                                                <div class="controls">
                                                    <textarea rows="3" id='ar' name="message" class="input-large"></textarea>
                                                    
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="tos" id="tos" value="" class="required">
                                                        <?php echo I_AGREE_WITH_THE_TEXT; ?> <a href="javascript: ;" onClick="javascript:myPopup2();"> <?php echo TERMS_AND_CONDITIONS_TEXT; ?>.</a>
                                                    </label>
                                                    
                                                </div>
                                            </div>                                        
                                    </div>
                                </div>
                            </div>  
                            <!-- end of search row --> 
                        </div>
                        <div class="wrapper" style="margin-bottom: 20px">
                            <div class="container-fluid" style="margin:0; padding:0;">
                                <div class="row-fluid" style="background-color: #faac59; padding: 1% 0">
                                    <div class="span12">
                                        <div class="back1">
                                            <button id="registerButton" type="button" onClick="window.location.href='booking-search.php'" ><?php echo BACK_TEXT; ?></button>
                                        </div>
                                        <div class="home">
                                            <button id="registerButton" type="button" class='home-btn' onClick="window.location.href='index.php'" ><?php echo HOME_TEXT; ?></button>
                                        </div>
                                        <div class="continue1">
                                            <button id="registerButton" type="submit" class="conti" ><?php echo CONTINUE_TEXT; ?></button>
                                        </div>
                                    </div>
                                </div> 
                            </div>   
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
