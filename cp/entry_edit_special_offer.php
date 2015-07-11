<?php 
include("access.php");
if(isset($_POST['sbt_details'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
    include("../includes/admin.class.php");
	$bsiAdminMain->add_edit_spe_off();
	header("location:view_special_offer.php");
	exit;
}


include("header.php"); 
include("../includes/conf.class.php");
include("../includes/admin.class.php");
 if(isset($_GET['id']))
 {
   $id=$_GET['id'];
   if($id!='0')
   {
	  $row1 = $bsiAdminMain->edit_view1();
   }
   
 }
?>
<link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />
<script src="js/jquery.validate.js" type="text/javascript"></script>
<div id="container-inside">
<span style="font-size:16px; font-weight:bold"><?php echo ADD_EDIT_SPECIAL_OFFER; ?></span>
<hr />
<div id="container-inside">
  <form  id="form1" ame="form1" action="" method="post">
    <input type="hidden" value="<?php echo $id; ?>" name="id"  />
    <table cellpadding="5" cellspacing="2" border="0">
     <tr>
        <td valign="middle"><strong><?php echo OFFER_NAME_TEXT; ?>:</strong></td>
        <td><input type="text" id="offer_name" name="offer_name"  class="required" size="35" value="<?php echo $row1['offer_title'];?>" /></td> 
      </tr>
      <tr>
        <td valign="middle"><strong><?php echo START_DATE;?>:</strong></td>
        <td><input type="text" id="txtFromDate" name="fromDate" class="required" size="10" value="<?php echo $row1['start_date'];?>" />
          <a id="datepickerImage" href="javascript:;"><img src="../images/month.png" height="16px" width="16px" style=" margin-bottom:-4px;" border="0" /></a></td>
      </tr>
      <tr>
        <td valign="middle"><strong><?php echo END_DATE;?>:</strong></td>
        <td><input type="text" id="txtToDate" name="toDate" class="required" size="10" value="<?php echo $row1['end_date'];?>"/>
          <a id="datepickerImage1" href="javascript:;"><img src="../images/month.png" height="18px" width="18px" style=" margin-bottom:-4px;" border="0" /></a></td>
      </tr>
      <tr>
        <td valign="middle"><strong><?php echo ROOM_TYPE;?>:</strong></td>
        <td><?php echo $select_rtype=$bsiAdminMain->getRoomtype2($row1['room_type']); ?></td>
      </tr>
      <tr>
        <td valign="middle"><strong><?php echo PRICE_DEDUCTED;?>:</strong></td>
        <td><input type="text" id="pr_de" name="pr_de"  class="required number"  style="width:40px;" value="<?php echo $row1['price_deduc'];?>" />
          <?php echo "%";?></td>
      </tr>
      <tr>
        <td valign="middle"><strong><?php echo MINIMUM_STAY_OPTIONAL;?>:</strong></td>
        <td><input type="text" id="min_sty" name="min_sty"  size="10" value="<?php echo $row1['min_stay'];?>" />
          <?php echo NIGHTS;?> <span style="color:#900">(<?php echo LEAVE_BLANK_IF_NO_MINIMUM_NIGHT_RESTRICTION; ?>)</span></td>
      </tr>
      <tr>
        <td></td>
        <td><input type="submit" value="<?php echo SPECIAL_SUBMIT; ?>" name="sbt_details"  id="sbt_details"  style="background:#e5f9bb; cursor:pointer; cursor:hand;"   /></td>
      </tr>
    </table>
  </form>
</div>
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="../css/datepicker.css" />
<script type="text/javascript" src="../js/jquery-ui.min.js"></script> 
<script type="text/javascript" src="../js//dtpicker/jquery.ui.datepicker-<?=$langauge_selcted?>.js"></script> 
<script type="text/javascript" charset="">
   $(document).ready(function() {
   $("#priceplanaddeit").validate();
   $('#roomtype_id').change(function() {
         if($('#roomtype_id').val() != 0){
			var querystr = 'actioncode=3&roomtype_id='+$('#roomtype_id').val();		
			$.post("admin_ajax_processor.php", querystr, function(data){												 
				if(data.errorcode == 0){
					 $('#default_capacity').html(data.strhtml)
				}else{
				    $('#default_capacity').html("<span style=\"font-family:Arial, Helvetica, sans-serif; font-size:12px;\">'<?php  echo NOT_FOUND;?>'</span>")
				}
			}, "json");
		} else {
		 $('#default_capacity').html("<span style=\"font-family:Arial, Helvetica, sans-serif; font-size:12px;\">'<?php echo PLEASE_SELECT_ROOMTYPE_FROM_DROPDOWN_ALERT; ?>'</span>")
		}
	});
	
	if($('#roomtype').val() == 0){
		$('#default_capacity').html("<span style=\"font-family:Arial, Helvetica, sans-serif; font-size:12px;\">'<?php echo PLEASE_SELECT_ROOMTYPE_FROM_DROPDOWN_ALERT; ?>'</span>")
	}
});
 
$(document).ready(function(){
	$.datepicker.setDefaults( $.datepicker.regional[ "<?=$langauge_selcted?>" ] );
	$.datepicker.setDefaults({ dateFormat: '<?=$bsiCore->config['conf_dateformat']?>' });
    $("#txtFromDate").datepicker({
        minDate: 0,
        maxDate: "+<?php echo $bsiCore->config['conf_maximum_global_years']; ?>D",
        numberOfMonths: 2,
        onSelect: function(selected) {
        var date = $(this).datepicker('getDate');
        if(date){
            date.setDate(date.getDate() + <?=$bsiCore->config['conf_min_night_booking']?>);
        }
          $("#txtToDate").datepicker("option","minDate", date)
        }
    });
    $("#txtToDate").datepicker({ 
        minDate: 0,
        maxDate:"+<?php echo $bsiCore->config['conf_maximum_global_years']; ?>D",
        numberOfMonths: 2,
        onSelect: function(selected) {
           $("#txtFromDate").datepicker("option","maxDate", selected)
        }
    });
	
	$("#txtFromDate").datepicker();
	$("#datepickerImage").click(function() { 
		$("#txtFromDate").datepicker("show");
	});
	
	$("#txtToDate").datepicker();
	$("#datepickerImage1").click(function() { 
		$("#txtToDate").datepicker("show");
	});    
});
</script> 
<script type="text/javascript">
	$(document).ready(function() {
		$("#form1").validate();
     });  
</script> 
<script src="js/jquery.validate.js" type="text/javascript"></script>
<?php
   include("footer.php");
   ?>
