<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("language.php");
session_destroy();
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

        <script type="text/javascript">
            $(window).load(function() {
                
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
                            <div class="step3 greenStep2"><img src="images/wizard/3.png" alt="" /><p><?php echo YOUR_DETAILS_TEXT; ?></p></div>
                            <div class="step4 greenStep"><img src="images/wizard/4.png" alt="" /><p><?php echo PAYMENT_TEXT; ?></p></div>
                            <div class="step5 yellowLastStep"><img src="images/wizard/5.png" alt="" /><p><?php echo CONFIRM_TEXT; ?></p></div>
                         </div>
                          <div class="progress">
                         	<div class="bar bar-success" style="width: 100%;">100% Complete</div>
                     	</div>
                        <div class="wrapper">
                            <div class="htitel">
                                <h2 class="fl" style="border:0; margin:0;"><?php echo BOOKING_COMPLETED_TEXT; ?></h2>
                            </div>
                            <!-- start of search row --> 
                            <div class="container-fluid" style="margin:0; padding:0;">
                                <div class="row-fluid" style="background-color: #faac59; padding: 1% 0">
                                    <div class="span12" style="text-align:center; padding:20px 0">
                                    	<p><strong><?php echo THANK_YOU_TEXT; ?>!</strong></p>
                                        <p><?php echo YOUR_BOOKING_CONFIRMED_TEXT; ?>. <?php echo INVOICE_SENT_EMAIL_ADDRESS_TEXT; ?>.</p>
                                         <div style="width:100%;">
                                            <button id="registerButton" style="margin:0 auto" type="button" onClick="window.location.href='index.php'" ><?php echo BACK_TO_HOME; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <!-- end of search row --> 
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
