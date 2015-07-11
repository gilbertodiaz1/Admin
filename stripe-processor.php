<?php
session_start();
if(!isset($_SESSION['bookingId'])){
	header('Location: booking-failure.php?error_code=9');
}


include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/mail.class.php");

if (isset($_POST['stripeToken'])){
$row_default_lang=mysql_fetch_assoc(mysql_query("select * from bsi_language where `lang_default`=true"));
	include("languages/".$row_default_lang['lang_file']);
}else{
	include("language.php");
}

$bsiMail = new bsiMail();
$paymentGatewayDetails = $bsiCore->loadPaymentGateways();		
$emailContent=$bsiMail->loadEmailContent();
$st_account=explode("#",$paymentGatewayDetails['st']['account']);

require_once('includes/stripe/Stripe.php');
$stripe = array(
'secret_key'      => $st_account[0],
'publishable_key' => $st_account[1]
);
Stripe::setApiKey($stripe['secret_key']); 

if ($_POST) {
    $error = NULL;
    try {
      if (!isset($_POST['stripeToken']))
        throw new Exception("The Stripe Token was not generated correctly");
		  
		  if($st_account[2]){
		  $customer = Stripe_Customer::create(array(
			  "card" => $_POST['stripeToken'],
			  "email" => $_SESSION['clientEmail'],
			  "description"=>"Booking#".$_SESSION['bookingId']
			  ));
			  
			Stripe_Charge::create(array(
			  "amount" => round(($_SESSION['paymentAmount']*100)), # amount in cents, again
			  "currency" => $bsiCore->currency_code(),
			  "customer" => $customer->id)
			);
			
		  }else{
			  
			   $customer = Stripe_Customer::create(array(
			  "card" => $_POST['stripeToken'],
			  "email" => $_SESSION['clientEmail'],
			  "description"=>"Booking#".$_SESSION['bookingId']
			  ));
			  
			 
		  }
			
    }
    catch (Exception $e) {
      $error = $e->getMessage();
    }

    if ($error == NULL) {
	//*****************************************************************************************
			   	mysql_query("UPDATE bsi_bookings SET payment_success=true WHERE booking_id='".$_SESSION['bookingId']."'");
	
				$invoiceROWS = mysql_fetch_assoc(mysql_query("SELECT client_name, client_email, invoice FROM bsi_invoice WHERE booking_id='".$_SESSION['bookingId']."'"));
				mysql_query("UPDATE bsi_clients SET existing_client = 1 WHERE email='".$invoiceROWS['client_email']."'");
				
				$invoiceHTML = $invoiceROWS['invoice'];		
				$invoiceHTML.= '<br><br><table  style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:700px; border:none;" cellpadding="4" cellspacing="1"><tr><td align="left" colspan="2" style="font-weight:bold; font-variant:small-caps; background:#eeeeee">'.mysql_real_escape_string(INV_PAY_DETAILS).'</td></tr><tr><td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background:#ffffff">'.mysql_real_escape_string(INV_PAY_OPTION).'</td><td align="left" style="background:#ffffff">'.$paymentGatewayDetails['st']['name'].'</td></tr></table>';
				
				mysql_query("UPDATE bsi_invoice SET invoice = '$invoiceHTML' WHERE booking_id='".$_SESSION['bookingId']."'");
				
				
				
				$emailBody = "Dear ".$invoiceROWS['client_name'].",<br><br>";
				$emailBody .= html_entity_decode($emailContent['body'])."<br><br>";
				$emailBody .= $invoiceHTML;
				$emailBody .= "<br><br>".mysql_real_escape_string(PP_REGARDS).",<br>".$bsiCore->config['conf_hotel_name'].'<br>'.$bsiCore->config['conf_hotel_phone'];
				$emailBody .= "<br><br><font style=\"color:#F00; font-size:10px;\">[ ".mysql_real_escape_string(PP_CARRY)." ]</font>";
				$flag = 1;
				$bsiMail->sendEMail($invoiceROWS['client_email'], $emailContent['subject'], $emailBody, $_SESSION['bookingId'], $flag);
				
				/* Notify Email for Hotel about Booking */
				$notifyEmailSubject = "Booking no.".$_SESSION['bookingId']." - Notification of Room Booking by ".$invoiceROWS['client_name'];
				
				$bsiMail->sendEMail($bsiCore->config['conf_notification_email'], $notifyEmailSubject, $invoiceHTML);
			//*****************************************************************************************	
	
     header('Location: booking-confirm.php?success_code=1');
	 die;
    }
    else {
		header('Location: booking-failure.php?error_code=99&rescode='.htmlentities($error));
		die;
    }
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

        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="js/jquery.validate.js"></script>

        <script type="text/javascript">
            $(window).load(function() {
                
            });
        </script>
        <script id="demo" type="text/javascript">
			$(document).ready(function() {
				$("#payment-form").validate();
			});
			</script>
			<script type="text/javascript" src="https://js.stripe.com/v1/"></script>
			  <script type="text/javascript">
				// this identifies your website in the createToken call below
				Stripe.setPublishableKey("<?php echo $stripe['publishable_key']; ?>");
			
				function stripeResponseHandler(status, response) {
					if (response.error) {
						// re-enable the submit button
						$('#registerButton1').removeAttr("disabled");
						// show the errors on the form
						//$(".payment-errors").html(response.error.message);
						alert(response.error.message);
					} else {
						var form$ = $("#payment-form");
						// token contains id, last4, and card type
						var token = response['id'];
						// insert the token into the form so it gets submitted to the server
						form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
								// and submit
						form$.get(0).submit();
					}
				}
			
				$(document).ready(function() {
					$("#payment-form").submit(function(event) {
						// disable the submit button to prevent repeated clicks
						$('#registerButton1').attr("disabled", "disabled");
						// createToken returns immediately - the supplied callback submits the form if there are no errors
						Stripe.createToken({
							number: $('#card-number').val(),
							cvc: $('#card-cvc').val(),
							exp_month: $('#card-expiry-month').val(),
							exp_year: $('#card-expiry-year').val()
						}, stripeResponseHandler);
						return false; // submit from callback
					});
				});

    </script>
    </head>
    <body>
        
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
						<div id="body-div">
                        <h1><?php echo $bsiCore->config['conf_hotel_name']; ?></h1>
                        <div class="wizard">
                            <div class="step1 greenStep2"><img src="images/wizard/1.png" alt="" /><p><?php echo SELECT_DATES_TEXT; ?></p></div>
                            <div class="step2 greenStep2"><img src="images/wizard/2.png" alt="" /><p><?php echo ROOMS_TEXT; ?> &amp; <?php echo RATES_TEXT; ?></p></div>
                            <div class="step3 greenStep"><img src="images/wizard/3.png" alt="" /><p><?php echo YOUR_DETAILS_TEXT; ?></p></div>
                            <div class="step4 yellowStep"><img src="images/wizard/4.png" alt="" /><p><?php echo PAYMENT_TEXT; ?></p></div>
                            <div class="step5 grayLastStep"><img src="images/wizard/5.png" alt="" /><p><?php echo CONFIRM_TEXT; ?></p></div>
                         </div>
                          <div class="progress">
                         	<div class="bar bar-success" style="width: 60%;">60% Complete</div>
						 	<div class="bar bar-warning" style="width: 20%;"></div>
                     	</div>
                         
                        <div class="wrapper">
                            <div class="htitel">
                                <h2 class="fl" style="border:0; margin:0;"><?php echo CC_DETAILS; ?> (Stripe)</h2>
                            </div>
                            <!-- start of search row --> 
                            <div class="container-fluid" style="margin:0; padding:0;">
                                <div class="row-fluid" style="background-color: #faac59; padding: 1% 0">
                                    <div class="span12">
                                        <form name="signupform" id="payment-form"   action="stripe-processor.php" method="post" class="form-horizontal" style="width: 95%; margin: 0 2.5%">                                            
                                            <div class="control-group">
                                                <label class="control-label" for="ccn"><?php echo CC_NUMBER; ?>:</label>
                                                <div class="controls">
                                                    <input type="text" autocomplete="off" name="card-number" id="card-number" maxlength="16" class="input-large digits">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="ed"><?php echo CC_EXPIRY; ?> (mm/yyyy):</label>
                                                <div class="controls">
                                                    <input type="text" name="card-expiry-month" id="card-expiry-month" maxlength="2" class="input-mini digits">/<input type="text" name="card-expiry-year" id="card-expiry-year" maxlength="4" class="input-mini required digits" />
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="cc">CCV/CCV2:</label>
                                                <div class="controls">
                                                    <input type="text" autocomplete="off" name="card-cvc"  id="card-cvc" maxlength="4" class="input-mini  number">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="ad"><?php echo CC_AMOUNT; ?></label>
                                                <div class="controls"><strong><?php echo $bsiCore->currency_symbol().$_SESSION['paymentAmount']; ?></strong><br />
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="tos" id="tos" value="" class="required">
                                                        <?php echo CC_TOS1; ?> <?php echo $bsiCore->config['conf_hotel_name']; ?>
               <?php echo CC_TOS2; ?> <?php echo $bsiCore->currency_symbol().$_SESSION['paymentAmount']?> <?php echo CC_TOS3; ?>.</a>
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
                                            <button id="registerButton" type="button" onClick="window.location.href='booking-failure.php?error_code=26'" ><?php echo BTN_CANCEL; ?></button>
                                        </div>
                                       
                                        <div class="continue1">
                                            <button id="registerButton" type="submit" class="conti" ><?php echo CC_SUBMIT; ?></button>
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
