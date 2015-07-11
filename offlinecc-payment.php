<?php
$pos2 = strpos($_SERVER['HTTP_REFERER'],$_SERVER['SERVER_NAME']);
if(!$pos2){
	header('Location: booking-failure.php?error_code=9');
}
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("language.php");
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
                                <h2 class="fl" style="border:0; margin:0;"><?php echo CC_DETAILS; ?></h2>
                            </div>
                            
                            <!-- start of search row --> 
                            <div class="container-fluid" style="margin:0; padding:0;">
                                <div class="row-fluid" style="background-color: #faac59; padding: 1% 0">
                                    <div class="span12">
                                       <form name="signupform" id="form1"  action="cc_process.php" method="post" onSubmit="return testCreditCard(); style="width: 95%; margin: 0 2.5%" class="form-horizontal">
                                       <input type="hidden" name="bookingid" value="<?php echo $_POST['x_invoice_num']; ?>" />
                                            <div class="control-group">
                                                <label class="control-label" for="ln"><?php echo CC_HOLDER; ?>:</label>
                                                <div class="controls">
                                                    <input type="text" name="cc_holder_name" id="cc_holder_name" class="input-large required" />
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="ln"><?php echo CC_TYPE; ?>:</label>
                                                <div class="controls">
                                                    <select name="CardType" id="CardType" class="input-large">
                                                        <option value="AmEx">AmEx</option>
                                                        <option value="DinersClub">DinersClub</option>
                                                        <option value="Discover">Discover</option>
                                                        <option value="JCB">JCB</option>
                                                        <option value="Maestro">Maestro</option>
                                                        <option value="MasterCard">MasterCard</option>
                                                        <option value="Solo">Solo</option>
                                                        <option value="Switch">Switch</option>
                                                        <option value="Visa">Visa</option>
                                                        <option value="VisaElectron">VisaElectron</option>
                                                      </select>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="ccn"><?php echo CC_NUMBER; ?>:</label>
                                                <div class="controls">
                                                    <input type="text" name="CardNumber" id="CardNumber" maxlength="16" class="input-large required" />
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="ed"><?php echo CC_EXPIRY; ?> (mm/yy):</label>
                                                <div class="controls">
                                                    <input type="text" name="cc_exp_dt" id="cc_exp_dt" maxlength="5" class="input-mini required" />
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="cc">CCV/CCV2:</label>
                                                <div class="controls">
                                                    <input type="text" name="cc_ccv" id="cc_ccv"  maxlength="4" class="input-mini required"/>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="ad"><?php echo CC_AMOUNT; ?></label>
                                                <div class="controls"><strong><?php echo (($bsiCore->config['conf_payment_currency']=='1')? $bsiCore->get_currency_symbol($_SESSION['sv_currency']) : $bsiCore->currency_symbol())?><?php echo $_POST['total']; ?></strong><br />
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="tos" id="tos" value="" class="required"/> <?php echo CC_TOS1; ?> <?php echo $bsiCore->config['conf_hotel_name']?>
               <?php echo CC_TOS2; ?> <?php echo $bsiCore->currency_symbol(); ?><?php echo $_POST['total']; ?> <?php echo CC_TOS3; ?>.
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
