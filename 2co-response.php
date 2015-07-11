<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/mail.class.php");
$row_default_lang=mysql_fetch_assoc(mysql_query("select * from bsi_language where `lang_default`=true"));
include("languages/".$row_default_lang['lang_file']);
$bsiMail = new bsiMail();
$paymentGatewayDetails = $bsiCore->loadPaymentGateways();		
$emailContent=$bsiMail->loadEmailContent();

$hashSecretWord = 'bsitest';    //2Checkout Secret Word
$hashSid = $paymentGatewayDetails['2co']['account'];    //2Checkout account number 
$hashTotal = $_SESSION['paymentAmount'];    //Sale total to validate against
$hashOrder = $_REQUEST['order_number'];    //2Checkout Order Number
//$hashOrder = 1;    //2Checkout Order Number in demo mode
$StringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));
if ($StringToHash != $_REQUEST['key']) {
	header('Location: booking-failure.php?error_code=26');
	die;
} else {
	
	
	//*****************************************************************************************
			   	mysql_query("UPDATE bsi_bookings SET payment_success=true, payment_txnid='".$hashOrder."' WHERE booking_id='".$_REQUEST['invoice']."'");
	
				$invoiceROWS = mysql_fetch_assoc(mysql_query("SELECT client_name, client_email, invoice FROM bsi_invoice WHERE booking_id='".$_REQUEST['invoice']."'"));
				mysql_query("UPDATE bsi_clients SET existing_client = 1 WHERE email='".$invoiceROWS['client_email']."'");
				
				$invoiceHTML = $invoiceROWS['invoice'];		
				$invoiceHTML.= '<br><br><table  style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:700px; border:none;" cellpadding="4" cellspacing="1"><tr><td align="left" colspan="2" style="font-weight:bold; font-variant:small-caps; background:#eeeeee">'.mysql_real_escape_string(INV_PAY_DETAILS).'</td></tr><tr><td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background:#ffffff">'.mysql_real_escape_string(INV_PAY_OPTION).'</td><td align="left" style="background:#ffffff">'.$paymentGatewayDetails['2co']['name'].'</td></tr><tr><td align="left" style="font-weight:bold; font-variant:small-caps; background:#ffffff">'.mysql_real_escape_string(INV_TXN_ID).'</td><td align="left" style="background:#ffffff">'.$hashOrder.'</td></tr></table>';
				
				mysql_query("UPDATE bsi_invoice SET invoice = '$invoiceHTML' WHERE booking_id='".$_REQUEST['invoice']."'");
				
				
				
				$emailBody = "Dear ".$invoiceROWS['client_name'].",<br><br>";
				$emailBody .= html_entity_decode($emailContent['body'])."<br><br>";
				$emailBody .= $invoiceHTML;
				$emailBody .= "<br><br>".mysql_real_escape_string(PP_REGARDS).",<br>".$bsiCore->config['conf_hotel_name'].'<br>'.$bsiCore->config['conf_hotel_phone'];
				$emailBody .= "<br><br><font style=\"color:#F00; font-size:10px;\">[ ".mysql_real_escape_string(PP_CARRY)." ]</font>";
				$flag = 1;
				$bsiMail->sendEMail($invoiceROWS['client_email'], $emailContent['subject'], $emailBody, $_REQUEST['invoice'], $flag);
				
				/* Notify Email for Hotel about Booking */
				$notifyEmailSubject = "Booking no.".$_REQUEST['invoice']." - Notification of Room Booking by ".$invoiceROWS['client_name'];
				
				$bsiMail->sendEMail($bsiCore->config['conf_notification_email'], $notifyEmailSubject, $invoiceHTML);
			//*****************************************************************************************
			    header('Location: booking-confirm.php?success_code=1');
				die;
}
?>