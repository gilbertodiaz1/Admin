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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<form class="form-horizontal" action="booking-process.php" method="post" id="form1" style="width: 95%; margin: 0 2.5%">
    <?php $bookingDetails = $bsibooking->generateBookingDetails(); ?>
    <div class="container">
        <div class="row">
            <div class="col s12 center">
                <h5>
                    Haga su reservaci&oacute;n en 5 pasos.
                </h5>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="container">
                    <ul class="collection z-depth-1">
                        <li class="collection-item center" style="padding: 0px 20px; line-height: 1rem;">
                            <p style="display: inline-block; margin-right: 6%; font-size: 2rem;padding: 20px; margin-bottom: 20px;"
                               class="white-text circle gold">3</p>
                            <h5 style="display: inline-block;">
                                <?php echo YOUR_DETAILS_TEXT; ?>
                            </h5>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <h4><?php echo BOOKING_DETAILS_TEXT; ?></h4>

                <div class="divider"></div>
                <br>
                <table cellpadding="4" cellspacing="1" class="striped centered">
                    <thead>
                    <tr>
                        <th>
                            <strong>
                                <?php echo CHECKIN_DATE_TEXT; ?>
                            </strong>
                        </th>
                        <th>
                            <strong>
                                <?php echo CHECKOUT_DATE_TEXT; ?>
                            </strong>
                        </th>
                        <th>
                            <strong>
                                <?php echo TOTAL_NIGHT_TEXT; ?>
                            </strong>
                        </th>
                        <th>
                            <strong>
                                <?php echo GRAND_TOTAL_TEXT; ?>
                            </strong>
                        </th>
                        <!--  <th>
                                <strong>
                                    <?php /*echo SUB_TOTAL_TEXT; */ ?>
                                </strong>
                            </th>-->

                    </tr>
                    </thead>
                    <tr>
                        <td>
                            <?php echo $bsibooking->checkInDate; ?>
                        </td>
                        <td>
                            <?php echo $bsibooking->checkOutDate; ?>
                        </td>
                        <td>
                            <?php echo $bsibooking->nightCount; ?>
                        </td>
                        <td>
                            <strong>
                                <span id="grandtotaldisplay">
                                    <?php echo $bsiCore->get_currency_symbol($_SESSION['sv_currency']) . $bsiCore->getExchangemoney($bsibooking->roomPrices['grandtotal'], $_SESSION['sv_currency']); ?>
                                </span>
                            </strong>
                        </td>
                        <!-- <td align="left" style="padding-right:5px;">
                            <strong>
                                <?php /*echo $bsiCore->get_currency_symbol($_SESSION['sv_currency']) . $bsiCore->getExchangemoney($bsibooking->roomPrices['subtotal'], $_SESSION['sv_currency']); */ ?>
                            </strong>
                        </td>-->
                        <!--  <?php
                        /*                        if ($bsiCore->config['conf_enabled_deposit'] && ($bsibooking->depositPlans['deposit_percent'] > 0 && $bsibooking->depositPlans['deposit_percent'] < 100)) {
                                                */ ?>

                        <tr id="advancepaymentdisplay">
                            <td class="al-r" colspan="3" bgcolor="#faa448">
                                <strong></strong> <?php /*echo ADVANCE_PAYMENT_TEXT; */ ?>(<span style="font-size:11px;">
                                            <?php /*echo $bsibooking->depositPlans['deposit_percent']; */ ?>
                                    %<?php /*echo OF_GRAND_TOTAL_TEXT; */ ?></span>)
                            </td>
                            <td class="al-r" bgcolor="#faa448" style="padding-right:5px;"><span
                                    id="advancepaymentamount"><?php /*echo $bsiCore->get_currency_symbol($_SESSION['sv_currency']) . $bsiCore->getExchangemoney($bsibooking->roomPrices['advanceamount'], $_SESSION['sv_currency']); */ ?> </span>
                            </td>
                        </tr>
                        --><?php
                        /*                        }
                                                */ ?>
                    </tr>
                    <!-- <tr>
                        <td colspan="3">
                            <strong>
                                <?php /*echo GRAND_TOTAL_TEXT; */ ?>
                            </strong>
                        </td>
                        <td style="padding-right:5px;">
                            <strong>
                                <span id="grandtotaldisplay">
                                    <?php /*echo $bsiCore->get_currency_symbol($_SESSION['sv_currency']) . $bsiCore->getExchangemoney($bsibooking->roomPrices['grandtotal'], $_SESSION['sv_currency']); */ ?>
                                </span>
                            </strong>
                        </td>
                    </tr>
                    <?php
                    /*                    if ($bsiCore->config['conf_enabled_deposit'] && ($bsibooking->depositPlans['deposit_percent'] > 0 && $bsibooking->depositPlans['deposit_percent'] < 100)) {
                                            */ ?>

                        <tr id="advancepaymentdisplay">
                            <td class="al-r" colspan="3" bgcolor="#faa448">
                                <strong></strong> <?php /*echo ADVANCE_PAYMENT_TEXT; */ ?>(<span style="font-size:11px;">
                                        <?php /*echo $bsibooking->depositPlans['deposit_percent']; */ ?>
                                    %<?php /*echo OF_GRAND_TOTAL_TEXT; */ ?></span>)
                            </td>
                            <td class="al-r" bgcolor="#faa448" style="padding-right:5px;"><span
                                    id="advancepaymentamount"><?php /*echo $bsiCore->get_currency_symbol($_SESSION['sv_currency']) . $bsiCore->getExchangemoney($bsibooking->roomPrices['advanceamount'], $_SESSION['sv_currency']); */ ?> </span>
                            </td>
                        </tr>
                        --><?php
                    /*                    }
                                        */ ?>
                </table>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col s12">
                <h4>
                    <?php echo CUSTOMER_DETAILS_TEXT; ?>
                </h4>
            </div>
            <!-- start of search row -->
            <div class="col s12">
                <h5>
                    <?php echo EXISTING_CUSTOMER_TEXT; ?>
                </h5>

                <div class="row">
                    <div class="input-field col s6">
                        <input id="email_addr_existing" type="email" class="validate">
                        <label for="email_addr_existing"><?php echo EMAIL_ADDRESS_TEXT; ?></label>
                    </div>
                    <div class="input-field col s6">
                        <input id="login_password" type="password" class="validate" maxlength="16">
                        <label for="login_password">Password</label>
                    </div>
                </div>

                <div class="control-group">
                    <div class="controls" id="exist_wait"></div>
                </div>
                <div class="control-group">
                    <label class="control-label"></label>

                    <div class="controls">
                        <button id="btn_exisitng_cust" type="button" class="btn waves-effect waves-light">
                            <?php echo FETCH_DETAILS_TEXT; ?>
                        </button>
                        <!-- <a href="javascript:;" id="forgot_pass" style="width:150px; display:inline-block; padding-left:10px; cursor:pointer;">
                             Forgot Password?
                         </a>-->
                    </div>
                </div>
                <br>
                <br>

                <div class="divider"></div>
                <br>
            </div>
            <div class="col s12">
                <h5 align="left">
                    <?php echo NEW_CUSTOMER_TEXT; ?>
                </h5>
                <input type="hidden" name="allowlang" id="allowlang" value="no"/>

                <div class="control-group">
                    <label class="control-label" name="title" for="title">Title </label>

                    <div class="controls">
                        <select id="title">
                            <option value="Mr.">
                                <?php echo MR_TEXT;; ?>.
                            </option>
                            <option value="Ms.">
                                <?php echo MS_TEXT; ?>.
                            </option>
                            <option value="Mrs.">
                                <?php echo MRS_TEXT; ?>.
                            </option>
                            <option value="Miss.">
                                <?php echo MISS_TEXT; ?>.
                            </option>
                            <option value="Dr.">
                                <?php echo DR_TEXT; ?>.
                            </option>
                            <option value="Prof.">
                                <?php echo PROF_TEXT; ?>.
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input name="fname" id="fname" type="text" class="validate">
                        <label for="fname">
                            <?php echo FIRST_NAME_TEXT; ?>
                        </label>
                    </div>
                    <div class="input-field col s6">
                        <input name="lname" id="lname" type="text" class="validate">
                        <label for="lname">
                            <?php echo LAST_NAME_TEXT; ?>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input name="str_addr" id="str_addr" type="text" class="validate">
                        <label for="str_addr">
                            <?php echo ADDRESS_TEXT; ?>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input name="city" id="city" type="text" class="validate">
                        <label for="city">
                            <?php echo CITY_TEXT; ?>
                        </label>
                    </div>
                    <div class="input-field col s6">
                        <input name="state" id="state" type="text" class="validate">
                        <label for="state">
                            <?php echo STATE_TEXT; ?>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input name="country" id="country" type="text" class="validate">
                        <label for="country">
                            <?php echo COUNTRY_TEXT; ?>
                        </label>
                    </div>
                    <div class="input-field col s6">
                        <input name="zipcode" id="zipcode" type="text" class="validate">
                        <label for="zipcode">
                            <?php echo POSTAL_CODE_TEXT; ?>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input name="phone" id="phone" type="text" class="validate">
                        <label for="phone">
                            <?php echo PHONE_TEXT; ?>
                        </label>
                    </div>
                    <div class="input-field col s6">
                        <input name="fax" id="fax" type="text" class="validate">
                        <label for="fax">
                            <?php echo FAX_TEXT; ?>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input name="id_type" id="id_type" type="text" class="validate">
                        <label for="id_type">
                            <?php echo ID_TYPE; ?> (e.g.: passport.)
                        </label>
                    </div>
                    <div class="input-field col s6">
                        <input name="id_number" id="id_number" type="text" class="validate">
                        <label for="id_number">
                            <?php echo ID_NUMBER; ?> (e.g.: passport number.)
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="email" type="email" class="validate">
                        <label for="email"><?php echo EMAIL_TEXT; ?></label>
                    </div>
                    <div class="input-field col s6">
                        <input id="password" type="password" class="validate" maxlength="16">
                        <label for="password">Password</label>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="pb">
                        <?php echo PAYMENT_BY_TEXT; ?>
                    </label>

                    <div class="controls">
                        <?php
                        $paymentGatewayDetails = $bsiCore->loadPaymentGateways();
                        foreach ($paymentGatewayDetails as $key => $value) {
                            echo '<p><input type="radio" name="payment_type" id="payment_type_' . $key . '" value="' . $key . '" class="required" /> ' . '<label class="radio" for="payment_type_' . $key . '">' . $value['name'] . '</label></p>';
                        }
                        ?>
                        <label class="error" generated="true" for="payment_type"
                               style="display:none;"><?php echo FIELD_REQUIRED_ALERT; ?>.</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="ar" class="materialize-textarea"></textarea>
                        <label for="ar"><?php echo ADDITIONAL_REQUESTS_TEXT; ?></label>
                    </div>
                    <p>
                        <input type="checkbox" class="filled-in" id="tos"/>
                        <label for="tos"><?php echo I_AGREE_WITH_THE_TEXT; ?></label> <a href="javascript: ;"
                                                                                         onClick="javascript:myPopup2();">
                            <?php echo TERMS_AND_CONDITIONS_TEXT; ?>.
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- end of search row -->
    <div class="row">
        <div class="col s4 center-align">
            <button id="registerButton" type="button" onClick="window.location.href='booking-search.php'"
                    class="btn waves-effect waves-light"><?php echo BACK_TEXT; ?></button>
        </div>
        <div class="col s4 center-align">
            <button id="registerButton" type="button" onClick="window.location.href='index.php'"
                    class="btn waves-effect waves-light"><?php echo HOME_TEXT; ?></button>
        </div>
        <div class="col s4 center-align">
            <button id="registerButton" type="submit"
                    class="btn waves-effect waves-light"><?php echo CONTINUE_TEXT; ?></button>
        </div>
    </div>
    </div>
    <br><br><br><br>
</form>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script type="text/javascript" src="js/init.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript">
    $().ready(function () {
        $("#form1").validate();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#btn_exisitng_cust').click(function () {
            if ($('#btn_exisitng_cust').val() == '1') {
                $('#exist_wait').html("<img src='images/ajax-loader.gif' border='0'>")
                var querystr = 'actioncode=2&existing_email=' + $('#email_addr_existing').val() + '&login_password=' + $('#login_password').val();
                $.post("ajaxreq-processor.php", querystr, function (data) {
                    if (data.errorcode == 0) {
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
                    } else {
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

            } else if ($('#btn_exisitng_cust').val() == '2') {
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
            } else if ($('#btn_exisitng_cust').val() == '3') {
                $('#exist_wait').html("<img src='images/ajax-loader.gif' border='0'>")
                var querystr = 'actioncode=9&existing_email=' + $('#email_addr_existing').val();
                $.post("ajaxreq-processor.php", querystr, function (data) {
                    if (data.errorcode == 0) {
                        alert('Your password has been reset. Please check your email and login!')
                        $('#label_pass').show()
                        $('#input_pass').show()
                        $('#btn_exisitng_cust').val('1')
                        $('#btn_exisitng_cust').html('Login')
                        $('#exist_wait').html("")
                    } else {
                        alert(data.strmsg);
                        $('#exist_wait').html("")
                    }
                }, "json");
            }
        });

        $('#forgot_pass').toggle(function () {
            $('#label_pass').hide()
            $('#input_pass').hide()
            $('#btn_exisitng_cust').val('3')
            $('#btn_exisitng_cust').html('Submit')
            $('#forgot_pass').html('Back to Login')
        }, function () {
            $('#label_pass').show()
            $('#input_pass').show()
            $('#btn_exisitng_cust').val('1')
            $('#btn_exisitng_cust').html('Login')
            $('#exist_wait').html("")
            $('#forgot_pass').html('Forgot Password?')

        });
    });
    function myPopup2(booking_id) {
        var width = 730;
        var height = 650;
        var left = (screen.width - width) / 2;
        var top = (screen.height - height) / 2;
        var url = 'terms-and-services.php?bid=' + booking_id;
        var params = 'width=' + width + ', height=' + height;
        params += ', top=' + top + ', left=' + left;
        params += ', directories=no';
        params += ', location=no';
        params += ', menubar=no';
        params += ', resizable=no';
        params += ', scrollbars=yes';
        params += ', status=no';
        params += ', toolbar=no';
        newwin = window.open(url, 'Chat', params);
        if (window.focus) {
            newwin.focus()
        }
        return false;
    }
</script>
<script>
    function langchange(lng) {
        window.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?lang=' + lng;
    }
</script>
</body>
</html>
