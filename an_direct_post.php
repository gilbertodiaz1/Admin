<?php
$pos2 = strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']);
if (!count($_POST) && !count($_GET)){
if(!$pos2){
	header('Location: booking-failure.php?error_code=9');
}
}
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/mail.class.php");
if (!count($_POST) && !count($_GET)){
include("language.php");
}else{
$row_default_lang=mysql_fetch_assoc(mysql_query("select * from bsi_language where `lang_default`=true"));
include("languages/".$row_default_lang['lang_file']);
}
$bsiMail = new bsiMail();

require 'includes/lib/shared/AuthorizeNetRequest.php';
require 'includes/lib/shared/AuthorizeNetTypes.php';
require 'includes/lib/shared/AuthorizeNetXMLResponse.php';
require 'includes/lib/shared/AuthorizeNetResponse.php';

require 'includes/lib/AuthorizeNetSIM.php';
require 'includes/lib/AuthorizeNetDPM.php';

$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
//$url = "http://www.bestsoftinc.com/projects/dpm/index.php"; 
$paymentGatewayDetails2 = $bsiCore->loadPaymentGateways();	
$an_account=explode("=|=",$paymentGatewayDetails2['an']['account']);
$api_login_id = $an_account[0]; 
$transaction_key = $an_account[1]; 
$md5_setting = $an_account[2]; // Your MD5 Setting 
$amount = $_SESSION['paymentAmount']; 
//$amount = 5.00;
//print_r($_SESSION);
echo AuthorizeNetDPM::directPost($url, $api_login_id, $transaction_key, $amount, $md5_setting);
?>
