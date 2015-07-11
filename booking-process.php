<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
$row_default_lang=mysql_fetch_assoc(mysql_query("select * from bsi_language where `lang_default`=true"));
include("languages/".$row_default_lang['lang_file']);

$pos2 = strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']);
if(!$pos2){
	header('Location: booking-failure.php?error_code=9');
}
include("includes/mail.class.php");
include("includes/process.class.php");
$bookprs = new BookingProcess();
switch($bookprs->paymentGatewayCode){	
	case "poa":
		processPayOnArrival();
		break;
		
	case "pp": 		
		processPayPal();
		break;	
					
	case "cc":
		processCreditCard();
		break;	
				
	case "an":
		processAuthorizeNet();
		break;
		
	case "2co":
		process2Checkout();
		break;
		
			
	case "st":
		processStripe();
		break;
		
			
	default:
		processOther();
}
/* PAY ON ARIVAL: MANUAL PAYMENT */	
function processPayOnArrival(){	
	global $bookprs;
	global $bsiCore;
	$bsiMail = new bsiMail();
	$emailContent=$bsiMail->loadEmailContent();
	$subject    = $emailContent['subject'];
	
	mysql_query("UPDATE bsi_bookings SET payment_success=true WHERE booking_id = ".$bookprs->bookingId);
	mysql_query("UPDATE bsi_clients SET existing_client = 1 WHERE email = '".$bookprs->clientEmail."'");		
			
	$emailBody  = "Dear ".$bookprs->clientName.",<br><br>";
	$emailBody .= $emailContent['body']."<br><br>";
	$emailBody .= $bookprs->invoiceHtml;
	$emailBody .= '<br><br>'.mysql_real_escape_string(PP_REGARDS).',<br>'.$bsiCore->config['conf_hotel_name'].'<br>'.$bsiCore->config['conf_hotel_phone'];
	$emailBody .= '<br><br><font style=\"color:#F00; font-size:10px;\">[ '.mysql_real_escape_string(PP_CARRY).' ]</font>';	
				
	$returnMsg = $bsiMail->sendEMail($bookprs->clientEmail, $subject, $emailBody);
	
	if ($returnMsg == true) {		
		
		$notifyEmailSubject = "Booking no.".$bookprs->bookingId." - Notification of Room Booking by ".$bookprs->clientName;				
		$notifynMsg = $bsiMail->sendEMail($bsiCore->config['conf_hotel_email'], $notifyEmailSubject, $bookprs->invoiceHtml);
		
		header('Location: booking-confirm.php?success_code=1');
		die;
	}else {
		header('Location: booking-failure.php?error_code=25');
		die;
	}	
	//header('Location: booking-confirm.php?success_code=1');
}
/* PAYPAL PAYMENT */ 
function processPayPal(){
	global $bookprs;
	global $bsiCore;
	echo "<script language=\"JavaScript\">";
	echo "document.write('<form action=\"paypal.php\" method=\"post\" name=\"formpaypal\">');";
	echo "document.write('<input type=\"hidden\" name=\"amount\"  value=\"".(($bsiCore->config['conf_payment_currency']=='1')? $bsiCore->getExchangemoney($bookprs->totalPaymentAmount,$_SESSION['sv_currency']) : number_format($bookprs->totalPaymentAmount, 2))."\">');";
	echo "document.write('<input type=\"hidden\" name=\"invoice\"  value=\"".$bookprs->bookingId."\">');";
	echo "document.write('</form>');";
	echo "setTimeout(\"document.formpaypal.submit()\",500);";
	echo "</script>";	
}
/* CREDIT CARD PAYMENT */
function processCreditCard(){
	global $bookprs;
	global $bsiCore;	
	$paymentAmount = number_format($bookprs->totalPaymentAmount, 2, '.', '');
	
	echo "<script language=\"javascript\">";
	echo "document.write('<form action=\"offlinecc-payment.php\" method=\"post\" name=\"form2checkout\">');";
	echo "document.write('<input type=\"hidden\" name=\"x_invoice_num\" value=\"".$bookprs->bookingId."\"/>');";
	echo "document.write('<input type=\"hidden\" name=\"total\" value=\"".(($bsiCore->config['conf_payment_currency']=='1')? $bsiCore->getExchangemoney($paymentAmount,$_SESSION['sv_currency']) : $paymentAmount)."\">');"; 
	echo "document.write('</form>');";
	echo "setTimeout(\"document.form2checkout.submit()\",500);";
	echo "</script>";
}

function processAuthorizeNet(){
	global $bookprs;
	global $bsiCore;	
	$_SESSION['paymentAmount']=$bookprs->totalPaymentAmount;
	$_SESSION['bookingId']=$bookprs->bookingId;
	header('Location: an_direct_post.php');
	die;
}

/* PAYPAL PAYMENT */ 
function process2Checkout(){
	global $bookprs;
	global $bsiCore;
	$paymentGatewayDetails = $bsiCore->loadPaymentGateways();
	$_SESSION['paymentAmount']=$bookprs->totalPaymentAmount;
	echo "<script language=\"JavaScript\">";
	echo "document.write('<form action=\"https://www.2checkout.com/checkout/spurchase\" method=\"post\" name=\"twocopayment\">');";
	echo "document.write('<input type=\"hidden\" name=\"sid\" value=\"".$paymentGatewayDetails['2co']['account']."\" >');";
	echo "document.write('<input type=\"hidden\" name=\"mode\" value=\"2CO\" >');";
	//echo "document.write('<input type=\"hidden\" name=\"demo\" value=\"N\"/>');";
	echo "document.write('<input type=\"hidden\" name=\"li_0_type\" value=\"product\" >');";
	echo "document.write('<input type=\"hidden\" name=\"li_0_name\" value=\"Booking : ".$bsiCore->config['conf_hotel_name']."\" >');";
	echo "document.write('<input type=\"hidden\" name=\"li_0_price\" value=\"".$bookprs->totalPaymentAmount."\" >');";
	echo "document.write('<input type=\"hidden\" name=\"invoice\"  value=\"".$bookprs->bookingId."\">');";
	echo "document.write('</form>');";
	echo "setTimeout(\"document.twocopayment.submit()\",500);";
	echo "</script>";	
}

function processStripe(){
	global $bookprs;
	global $bsiCore;	
	$_SESSION['clientEmail']=$bookprs->clientEmail;
	$_SESSION['paymentAmount']=$bookprs->totalPaymentAmount;
	$_SESSION['bookingId']=$bookprs->bookingId;
	header('Location: stripe-processor.php');
	die;
}
/* OTHER PAYMENT */
function processOther(){
	/* not implemented yet */
	header('Location: booking-failure.php?error_code=22');
	die;
}
?>