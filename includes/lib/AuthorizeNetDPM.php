<?php
/**
 * Demonstrates the Direct Post Method.
 *
 * To implement the Direct Post Method you need to implement 3 steps:
 *
 * Step 1: Add necessary hidden fields to your checkout form and make your form is set to post to AuthorizeNet.
 *
 * Step 2: Receive a response from AuthorizeNet, do your business logic, and return
 *         a relay response snippet with a url to redirect the customer to.
 *
 * Step 3: Show a receipt page to your customer.
 *
 * This class is more for demonstration purposes than actual production use.
 *
 *
 * @package    AuthorizeNet
 * @subpackage AuthorizeNetDPM
 */

/**
 * A class that demonstrates the DPM method.
 *
 * @package    AuthorizeNet
 * @subpackage AuthorizeNetDPM
 */
class AuthorizeNetDPM extends AuthorizeNetSIM_Form
{

    const LIVE_URL = 'https://secure.authorize.net/gateway/transact.dll';
    const SANDBOX_URL = 'https://test.authorize.net/gateway/transact.dll';

    /**
     * Implements all 3 steps of the Direct Post Method for demonstration
     * purposes.
     */
    public static function directPost($url, $api_login_id, $transaction_key, $amount = "0.00", $md5_setting = "")
    {
		global $bsiCore;	
		global $bsiMail;	
		$paymentGatewayDetails = $bsiCore->loadPaymentGateways();		
		$emailContent=$bsiMail->loadEmailContent();
        
        // Step 1: Show checkout form to customer.
        if (!count($_POST) && !count($_GET))
        {
            $fp_sequence = time(); // Any sequential number like an invoice number.
            echo AuthorizeNetDPM::getCreditCardForm($amount, $fp_sequence, $url, $api_login_id, $transaction_key);
        }
        // Step 2: Handle AuthorizeNet Transaction Result & return snippet.
        elseif (count($_POST)) 
        {
            $response = new AuthorizeNetSIM($api_login_id, $md5_setting);
			
            if ($response->isAuthorizeNet()) 
            {
                if ($response->approved) 
                {
					
				//*****************************************************************************************
			   	mysql_query("UPDATE bsi_bookings SET payment_success=true, payment_txnid='".$response->transaction_id."' WHERE booking_id='".$response->booking_id."'");
	
				$invoiceROWS = mysql_fetch_assoc(mysql_query("SELECT client_name, client_email, invoice FROM bsi_invoice WHERE booking_id='".$response->booking_id."'"));
				mysql_query("UPDATE bsi_clients SET existing_client = 1 WHERE email='".$invoiceROWS['client_email']."'");
				
				$invoiceHTML = $invoiceROWS['invoice'];		
				$invoiceHTML.= '<br><br><table  style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:700px; border:none;" cellpadding="4" cellspacing="1"><tr><td align="left" colspan="2" style="font-weight:bold; font-variant:small-caps; background:#eeeeee">'.mysql_real_escape_string(INV_PAY_DETAILS).'</td></tr><tr><td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background:#ffffff">'.mysql_real_escape_string(INV_PAY_OPTION).'</td><td align="left" style="background:#ffffff">'.$paymentGatewayDetails['an']['name'].'</td></tr><tr><td align="left" style="font-weight:bold; font-variant:small-caps; background:#ffffff">'.mysql_real_escape_string(INV_TXN_ID).'</td><td align="left" style="background:#ffffff">'.$response->transaction_id.'</td></tr></table>';
				
				mysql_query("UPDATE bsi_invoice SET invoice = '$invoiceHTML' WHERE booking_id='".$response->booking_id."'");
				
				
				
				$emailBody = "Dear ".$invoiceROWS['client_name'].",<br><br>";
				$emailBody .= html_entity_decode($emailContent['body'])."<br><br>";
				$emailBody .= $invoiceHTML;
				$emailBody .= "<br><br>".mysql_real_escape_string(PP_REGARDS).",<br>".$bsiCore->config['conf_hotel_name'].'<br>'.$bsiCore->config['conf_hotel_phone'];
				$emailBody .= "<br><br><font style=\"color:#F00; font-size:10px;\">[ ".mysql_real_escape_string(PP_CARRY)." ]</font>";
				$flag = 1;
				$bsiMail->sendEMail($invoiceROWS['client_email'], $emailContent['subject'], $emailBody, $p->ipn_data['invoice'], $flag);
				
				/* Notify Email for Hotel about Booking */
				$notifyEmailSubject = "Booking no.".$response->booking_id." - Notification of Room Booking by ".$invoiceROWS['client_name'];
				
				$bsiMail->sendEMail($bsiCore->config['conf_notification_email'], $notifyEmailSubject, $invoiceHTML);
			//*****************************************************************************************
					
                    // Do your processing here.
                    $redirect_url = $url . '?response_code=1&transaction_id=' . $response->transaction_id; 
					
					 echo AuthorizeNetDPM::getRelayResponseSnippet($redirect_url);
					
                }
                else
                {
                    // Redirect to error page.
                    $redirect_url = $url . '?response_code='.$response->response_code . '&response_reason_text=' . $response->response_reason_text;
					 echo AuthorizeNetDPM::getRelayResponseSnippet($redirect_url);
                }
                // Send the Javascript back to AuthorizeNet, which will redirect user back to your site.
              
			  // print_r($_POST);
            }
            else
            {
                echo "Error -- not AuthorizeNet. Check your MD5 Setting.";
            }
        }
        // Step 3: Show receipt page to customer.
        elseif (!count($_POST) && count($_GET))
        {
            if ($_GET['response_code'] == 1)
            {
				header('Location: booking-confirm.php?success_code=1');
				die;
                //echo "Thank you for your purchase! Transaction id: " . htmlentities($_GET['transaction_id']);
            }
            else
            {
				header('Location: booking-failure.php?error_code=99&rescode='.htmlentities($_GET['response_reason_text']));
				die;
            
            }
        }
    }
    
    /**
     * A snippet to send to AuthorizeNet to redirect the user back to the
     * merchant's server. Use this on your relay response page.
     *
     * @param string $redirect_url Where to redirect the user.
     *
     * @return string
     */
    public static function getRelayResponseSnippet($redirect_url)
    {
        return "<html><head><script language=\"javascript\">
                <!--
                window.location=\"{$redirect_url}\";
                //-->
                </script>
                </head><body><noscript><meta http-equiv=\"refresh\" content=\"1;url={$redirect_url}\"></noscript></body></html>";
    }
    
    /**
     * Generate a sample form for use in a demo Direct Post implementation.
     *
     * @param string $amount                   Amount of the transaction.
     * @param string $fp_sequence              Sequential number(ie. Invoice #)
     * @param string $relay_response_url       The Relay Response URL
     * @param string $api_login_id             Your API Login ID
     * @param string $transaction_key          Your API Tran Key.
     * @param bool   $test_mode                Use the sandbox?
     * @param bool   $prefill                  Prefill sample values(for test purposes).
     *
     * @return string
     */
    public static function getCreditCardForm($amount, $fp_sequence, $relay_response_url, $api_login_id, $transaction_key, $test_mode = false, $prefill = true)
    {
		global $bsiCore;	
        $time = time();
        $fp = self::getFingerprint($api_login_id, $transaction_key, $amount, $fp_sequence, $time);
        $sim = new AuthorizeNetSIM_Form(
            array(
            'x_amount'        => $amount,
            'x_fp_sequence'   => $fp_sequence,
            'x_fp_hash'       => $fp,
            'x_fp_timestamp'  => $time,
            'x_relay_response'=> "TRUE",
            'x_relay_url'     => $relay_response_url,
            'x_login'         => $api_login_id,
			'x_invoice_num'         => $_SESSION['bookingId'],
            )
        );
        $hidden_fields = $sim->getHiddenFieldString();
        $post_url = ($test_mode ? self::SANDBOX_URL : self::LIVE_URL);
        
        $form = '
		<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>'.$bsiCore->config['conf_hotel_name'].'</title>
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
			$(document).ready(function() {
				$("#form1").validate();
			});
        </script>
    </head>
    <body>
        
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span12">
						<div id="body-div">
                        <h1>'.$bsiCore->config['conf_hotel_name'].'</h1>
                        <div class="wizard">
                            <div class="step1 greenStep2"><img src="images/wizard/1.png" alt="" /><p>'.SELECT_DATES_TEXT.'</p></div>
                            <div class="step2 greenStep2"><img src="images/wizard/2.png" alt="" /><p>'.ROOMS_TEXT.' &amp; '.RATES_TEXT.'</p></div>
                            <div class="step3 greenStep"><img src="images/wizard/3.png" alt="" /><p>'.YOUR_DETAILS_TEXT.'</p></div>
                            <div class="step4 yellowStep"><img src="images/wizard/4.png" alt="" /><p>'.PAYMENT_TEXT.'</p></div>
                            <div class="step5 grayLastStep"><img src="images/wizard/5.png" alt="" /><p>'.CONFIRM_TEXT.'</p></div>
                         </div>
                          <div class="progress">
                         	<div class="bar bar-success" style="width: 60%;">60% Complete</div>
						 	<div class="bar bar-warning" style="width: 20%;"></div>
                     	</div>
                         
                        <div class="wrapper">
                            <div class="htitel">
                                <h2 class="fl" style="border:0; margin:0;">'.CC_DETAILS.' (Authorize.Net)</h2>
                            </div>
                            <!-- start of search row --> 
                            <div class="container-fluid" style="margin:0; padding:0;">
                                <div class="row-fluid" style="background-color: #faac59; padding: 1% 0">
                                    <div class="span12">
                                        <form class="form-horizontal" method="post" action="'.$post_url.'" id="form1" style="width: 95%; margin: 0 2.5%">'.$hidden_fields.'
                                            <div class="control-group">
                                                <label class="control-label" for="fn">'.FIRST_NAME_TEXT.':</label>
                                                <div class="controls">
                                                    <input type="text" name="x_first_name" id="x_first_name" class="input-large required">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="ln">'.LAST_NAME_TEXT.':</label>
                                                <div class="controls">
                                                    <input type="text" name="x_last_name" id="x_last_name" class="input-large required">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="ccn">'.CC_NUMBER.':</label>
                                                <div class="controls">
                                                    <input type="text" name="x_card_num" id="x_card_num" maxlength="16" class="input-large digits">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="ed">'.CC_EXPIRY.' (mm/yy):</label>
                                                <div class="controls">
                                                    <input type="text" name="x_exp_date" id="x_exp_date" maxlength="5" class="input-mini required">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="cc">CCV/CCV2:</label> 
                                                <div class="controls">
                                                    <input type="text" name="x_card_code" id="x_card_code" maxlength="4" class="input-mini number">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="ad">'.CC_AMOUNT.'</label>
                                                <div class="controls"><strong>'. $bsiCore->currency_symbol().number_format($amount,2).'</strong><br />
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="tos" id="tos" value="" class="required">
                                                        '.CC_TOS1.' '.$bsiCore->currency_symbol().number_format($amount,2).' '.CC_TOS3.'.
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
                                            <button id="registerButton" type="button" onClick="window.location.href =\'booking-failure.php?error_code=26\'" >'.BTN_CANCEL.'</button>
                                        </div>
                                       
                                        <div class="continue1">
                                            <button id="registerButton" type="submit" class="conti" >'.CC_SUBMIT.'</button>
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

       ';
        return $form;
    }

}