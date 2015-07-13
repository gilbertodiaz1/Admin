<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("language.php");
$bsiCore->exchange_rate_update();
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
                        <p style="display: inline-block; margin-right: 6%; font-size: 2rem;padding: 20px;"
                           class="white-text circle gold">1</p>
                        <h5 style="display: inline-block;">
                            <?php echo SELECT_DATES_TEXT; ?>
                        </h5>
                    </li>
                </ul>
            </div>
        </div>
        <br>
        <br>

        <div class="row">
            <form class="col s12" action="booking-search.php" method="post">
                <div class="row">
                    <div class="input-field col s6">
                        <i class="mdi-notification-event-available prefix"></i>
                        <input name="checkin" id="checkin" type="text" class="datepicker picker__input" readonly=""
                               tabindex="-1" aria-haspopup="true" aria-expanded="false" aria-readonly="false"
                               aria-owns="birthdate_root" value="21/07/2015">
                        <label for="checkin">
                            <?php echo CHECK_IN_DATE_TEXT; ?>
                        </label>
                    </div>
                    <div class="input-field col s6">
                        <i class="mdi-notification-event-available prefix"></i>
                        <input name="checkout" id="checkout" type="text" class="datepicker picker__input" readonly=""
                               tabindex="-1" aria-haspopup="true" aria-expanded="false" aria-readonly="false"
                               aria-owns="birthdate_root" value="25/07/2015">
                        <label for="checkout">
                            <?php echo CHECK_OUT_DATE_TEXT; ?>
                        </label>
                    </div>
                </div>

                <div class="control-group" style="padding-left: 25%; padding-right: 25%;">
                    <label class="control-label" for="checkInDate">
                        <?php echo ADULT_OR_ROOM_TEXT; ?>:
                    </label>

                    <div class="controls">
                        <?php echo $bsiCore->capacitycombo(); ?>
                    </div>
                </div>
                <?php echo $bsiCore->getChildcombo(); ?>
                <?php echo $bsiCore->get_currency_combo3($bsiCore->currency_code()); ?>
                <!--<button id="btn_room_search" class="sear-btn" type="submit" onClick="window.location.href ='search-result.html'">Search</button>-->
                <br>

                <div class="row center">
                    <div class="col s12">
                        <button id="btn_room_search" class="btn waves-effect waves-light" type="submit">
                            <?php echo SEARCH_TEXT; ?>
                            <i class="material-icons right">
                                send
                            </i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <script type="text/javascript" src="js/init.js"></script>
    <script>
        function langchange(lng) {
            window.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?lang=' + lng;
        }
    </script>
</body>
</html>