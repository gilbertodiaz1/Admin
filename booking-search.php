<?php
session_start();
include("includes/db.conn.php");
include("includes/conf.class.php");
include("includes/search.class.php");
include("language.php");
$bsisearch = new bsiSearch();
$bsiCore->clearExpiredBookings();
$pos2 = strpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME']);
if ($bsisearch->nightCount == 0 and !$pos2) {
    header('Location: booking-failure.php?error_code=9');
}
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
<form name="searchresult" id="searchresult" method="post" action="booking_details.php"
      onSubmit="return validateSearchResultForm('<?php echo SELECT_ONE_ROOM_ALERT; ?>');">
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
                               class="white-text circle gold">2</p>
                            <h5 style="display: inline-block;">
                                <?php echo ROOMS_TEXT; ?> &amp; <?php echo RATES_TEXT; ?>
                            </h5>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 center">
                <h6>
                    <?php echo SEARCH_INPUT_TEXT; ?> (<a href="index.php"><?php echo MODIFY_SEARCH_TEXT; ?></a>)
                </h6>
            </div>
        </div>
        <div class="row">
            <!-- start of search row -->
            <?php
            $gotSearchResult = false;
            $idgenrator = 0;
            $ik = 1;
            foreach ($bsisearch->roomType as $room_type) {
                foreach ($bsisearch->multiCapacity as $capid => $capvalues) {
                    $room_result = $bsisearch->getAvailableRooms($room_type['rtid'], $room_type['rtname'], $capid);
                    $sqlroomcheck = mysql_query("select * from bsi_room where roomtype_id=" . $room_type['rtid'] . " and capacity_id=" . $capid);
                    if (mysql_num_rows($sqlroomcheck)) {
                        echo '<script> $(document).ready(function() { ';
                        echo '$("#iframe_' . str_replace(" ", "", $room_type['rtid']) . '_' . str_replace(" ", "", $capid) . $ik . '").colorbox({iframe:true,width: $(\'#body-div\').width() + \'px\', height: "90%"}); $("#iframe_details_' . str_replace(" ", "", $room_type['rtid']) . '_' . str_replace(" ", "", $capid) . $ik . '").colorbox({iframe:true,  width: $(\'#body-div\').width() + \'px\', height: "60%"}); $(".group_' . $room_type['rtid'] . '_' . $capid . '").colorbox({rel:\'group_' . $room_type['rtid'] . '_' . $capid . '\', maxWidth: $(\'#body-div\').width(), maxHeight:"80%", slideshow:true, slideshowSpeed:5000});';
                        echo '}); </script>';
                        echo '<script type="text/javascript">$(document).ready(function() {$("#mySlides_' . $capid . '_' . $room_type['rtid'] . ' a").lightBox(); }); </script>';
                        ?>
                        <div class="container">
                            <div class="row">
                                <div class="col s12">
                                    <div class="row">
                                        <div class="col s6">
                                            <div class="card-panel gold center"
                                                 style="padding-left: 0; padding-right: 0;">
                                                <i class="material-icons white-text">today</i>
                                                <h6 class="white-text" style="font-size: 20px;">
                                                    <?php echo CHECK_IN_D_TEXT; ?>
                                                </h6>
                                                <strong class="white-text">
                                                    <?php echo $bsisearch->checkInDate; ?>
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col s6">
                                            <div class="card-panel gold center"
                                                 style="padding-left: 0; padding-right: 0;">
                                                <i class="material-icons white-text">today</i>
                                                <h6 class="white-text" style="font-size: 20px;">
                                                    <?php echo CHECK_OUT_D_TEXT; ?>
                                                </h6>
                                                <strong class="white-text">
                                                    <?php echo $bsisearch->checkOutDate; ?>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-panel gold center" style="padding-left: 0; padding-right: 0; padding-bottom: 1px;">
                                        <div class="row">
                                            <div class="col s6">
                                                <h6 class="white-text" style="font-size: 18px;">
                                                    <?php echo TOTAL_NIGHTS_TEXT; ?>
                                                </h6>
                                                <h6 class="white-text" style="font-size: 18px;">
                                                    <?php echo $bsisearch->nightCount; ?>
                                                </h6>
                                            </div>
                                            <div class="col s6">
                                                <h6 class="white-text" style="font-size: 18px;">
                                                    <?php echo ADULT_ROOM_TEXT; ?>
                                                </h6>
                                                <h6 class="white-text" style="font-size: 18px;">
                                                    <?php echo $bsisearch->guestsPerRoom ?>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                         <div class="col s12 center">
                                            <h5>
                                                <strong>
                                                    <?php echo TOTAL_PRICE_OR_ROOM_TEXT; ?>
                                                </strong>
                                            <?php if ($room_result['specail_price_flag']) { ?>
                                                    <span style="font-weight:bold; color:#cc0000;">
                                                        <del>
                                                            <?php echo $bsiCore->get_currency_symbol($bsisearch->currency) . $bsiCore->getExchangemoney($room_result['totalprice'], $bsisearch->currency); ?>
                                                        </del>
                                                    </span>
                                                    <strong>
                                                        <?php echo $bsiCore->get_currency_symbol($bsisearch->currency) . $bsiCore->getExchangemoney($room_result['total_specail_price'], $bsisearch->currency); ?>
                                                    </strong>
                                                    <?php if ($room_result['child_flag']) { ?> (included
                                                        <span style="color:#cc0000; text-decoration:line-through;">
                                                            <?php echo $bsiCore->get_currency_symbol($bsisearch->currency) . $bsiCore->getExchangemoney($room_result['total_child_price'], $bsisearch->currency); ?>
                                                        </span>
                                                        <?php echo $bsiCore->get_currency_symbol($bsisearch->currency) . $bsiCore->getExchangemoney($room_result['total_child_price2'], $bsisearch->currency); ?>
                                                        <?php echo FOR_TEXT; ?>
                                                        <?php echo $bsisearch->childPerRoom; ?>
                                                        <?php echo CHILD_TEXT; ?>)
                                                    <?php } ?>

                                            <?php } else { ?>
                                                    <span style="font-weight:bold;">
                                                        <strong>
                                                            <?php echo $bsiCore->get_currency_symbol($bsisearch->currency) . $bsiCore->getExchangemoney($room_result['totalprice'], $bsisearch->currency); ?>
                                                        </strong>
                                                    </span>
                                            <?php } ?>
                                            </h5>
                                        </div>
                                    </div>
                                    <table>
                                        <tr style="display: none;">
                                            <td>
                                                <strong>
                                                    <?php echo MAX_OCCUPENCY_TEXT; ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?php echo $capvalues['capval']; ?>
                                                <?php echo ADULT_TEXT; ?>
                                            </td>
                                        </tr>
                                        <tr>

                                        </tr>
                                        <tr style="display: none;">
                                            <?php
                                            if (intval($room_result['roomcnt']) > 0) {
                                                $gotSearchResult = true;
                                                ?>
                                                <td>
                                                    <strong>
                                                        <?php echo SELECT_NUMBER_OF_ROOM_TEXT; ?>
                                                    </strong>
                                                </td>
                                                <td>
                                                    <select name="svars_selectedrooms[]" class="input-mini">
                                                        <option value="1" selected="selected">1</option>
                                                    </select>
                                                </td>
                                            <?php } else {
                                                echo '<script> $(document).ready(function() { ';
                                                echo '$("#iframe2_' . str_replace(" ", "", $room_type['rtid']) . '_' . str_replace(" ", "", $capid) . $ik . '").colorbox({iframe:true, width: $(\'.container-fluid\').width() + \'px\', height: "80%"});';
                                                echo '}); </script>';
                                                ?>
                                                <td colspan="2">
                                                    <strong>
                                                        Not Available
                                                    </strong>
                                                    ( <a
                                                        style="color:#fff; font-size:13px"
                                                        id='iframe2_<?php echo str_replace(" ", "", $room_type['rtid']) . '_' . str_replace(" ", "", $capid) . $ik; ?>'
                                                        href="calendar.php?rtype=<?php echo $room_type['rtid']; ?>&cid=<?php echo $capid; ?>"
                                                        title='<span  style="font-size:16px;"><strong><?php echo $room_type['rtname']; ?></strong> ( <?php echo $capvalues['captitle']; ?> ) </span>'>
                                                        <strong>
                                                            <?php echo CHECK_AVILABILITY; ?>
                                                        </strong>
                                                    </a> )
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            } ?>
            <!-- end of search row -->
            <?php
            $flag88 = 0;
            if ($gotSearchResult) {
                $flag88 = 1;
            } else {
                echo '<table cellpadding="4" cellspacing="0" width="100%"><tbody><tr><td style="font-size:13px; color:#F00;" align="center"><br /><br />';
                if ($bsisearch->searchCode == "SEARCH_ENGINE_TURN_OFF") {
                    echo SORRY_ONLINE_BOOKING_CURRENTLY_NOT_AVAILABLE_TEXT;
                } else if ($bsisearch->searchCode == "OUT_BEFORE_IN") {
                    echo SORRY_YOU_HAVE_ENTERED_A_INVALID_SEARCHING_CRITERIA_TEXT;
                } else if ($bsisearch->searchCode == "NOT_MINNIMUM_NIGHT") {
                    echo MINIMUM_NUMBER_OF_NIGHT_SHOULD_NOT_BE_LESS_THAN_TEXT . ' ' . $bsiCore->config['conf_min_night_booking'] . ' ' . PLEASE_MODIFY_YOUR_SEARCHING_CRITERIA_TEXT;
                } else if ($bsisearch->searchCode == "TIME_ZONE_MISMATCH") {
                    $tempdate = date("l F j, Y G:i:s T");
                    echo BOOKING_NOT_POSSIBLE_FOR_CHECK_IN_DATE_TEXT . ' ' . $bsisearch->checkInDate . ' ' . PLEASE_MODIFY_YOUR_SEARCHING_CRITERIA_TO_HOTELS_DATE_TIME_TEXT . '<br>' . HOTELS_CURRENT_DATE_TIME_TEXT . ' ' . $tempdate;
                } else {
                    echo SORRY_NO_ROOM_AVAILABLE_AS_YOUR_SEARCHING_CRITERIA_TRY_DIFFERENT_DATE_SLOT;
                }
                echo '<br /><br /><br /></td></tr></tbody></table>';
            }
            ?>
        </div>
        <div class="container">
            <div class="row center">
                <?php if ($flag88) { ?>
                    <div class="col s6">
                        <button id="registerButton" type="button" onClick="window.location.href='index.php'"
                                class="btn waves-effect waves-light">
                            <?php echo BACK_TEXT; ?>
                        </button>
                    </div>
                    <div class="col s6">
                        <button id="registerButton" type="submit" class="btn waves-effect waves-light">
                            <?php echo CONTINUE_TEXT; ?>
                        </button>
                    </div>
                <?php } else { ?>
                    <div class="col s12">
                        <button id="registerButton" type="button" onClick="window.location.href='index.php'"
                                class="btn waves-effect waves-light">
                            <?php echo BACK_TEXT; ?>
                        </button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</form>
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