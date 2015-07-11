<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("language.php");
if(isset($_REQUEST["error_code"]))
$errorCode = $bsiCore->ClearInput($_REQUEST["error_code"]);
else
$errorCode=9;

$erroMessage = array(); 
$erroMsg[9] = BOOKING_FAILURE_ERROR_9;
$erroMsg[13] = BOOKING_FAILURE_ERROR_13;
$erroMsg[22] = BOOKING_FAILURE_ERROR_22;
$erroMsg[25] = BOOKING_FAILURE_ERROR_25;
$erroMsg[26] = BOOKING_FAILURE_ERROR_26;
if(isset($_GET['rescode'])){ $erroMsg[$errorCode]=$_GET['rescode']; }
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
                        <div class="wrapper">
                            <div class="htitel">
                                <h2 class="fl" style="border:0; margin:0;"><?php echo BOOKING_FAILURE_TEXT; ?></h2>
                            </div>
                            <!-- start of search row --> 
                            <div class="container-fluid" style="margin:0; padding:0;">
                                <div class="row-fluid" style="background-color: #faac59; padding: 1% 0">
                                    <div class="span12" style="text-align:center; padding:20px 0">
                                        <p style="color:#C00"><?php echo $erroMsg[$errorCode]; ?> </p>
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
