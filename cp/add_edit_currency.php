<?php 
include("access.php");
if(isset($_POST['submitCapacity'])){
	include("../includes/db.conn.php"); 
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->add_edit_currency();
	header("location:currency_list.php");	
	exit;
}

include("header.php"); 
include("../includes/conf.class.php");
include("../includes/admin.class.php");
if(isset($_GET['id']) && $_GET['id'] != ""){

	$id = $bsiCore->ClearInput($_GET['id']);
	if($id){
		$result = mysql_query("select * from bsi_currency where id=".$id);
		$row    = mysql_fetch_assoc($result);
		$dflt=($row['default_c'])? 'checked="checked"':'';
	}else{
		$row    = NULL;
		$readonly = '';
		$dflt='';

	}

}else{

	header("location:currency_list.php");

	exit;

}

?>
<link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />

<div id="container-inside"> <span style="font-size:16px; font-weight:bold"><?php echo LANGAUGE_ADD_EDIT; ?></span>
 <hr />
 <form action="<?=$_SERVER['PHP_SELF']?>" method="post" id="form1">
  <table cellpadding="5" cellspacing="2" border="0">
   <tr>
    <td><strong><?php echo CURRENCY_CODE_LIST; ?>:</strong></td>
    <td valign="middle"><input type="text" name="currency_code" id="currency_code" class="required" value="<?=$row['currency_code']?>" style="width:150px;" />
     </td> 
   </tr>
   <tr>
    <td><strong><?php echo CURRENCY_SYMBOL_LIST; ?>:</strong></td>
    <td><input type="text" name="currency_symbol" id="currency_symbol" value="<?=$row['currency_symbl']?>" class="required " style="width:70px;"  /></td>
   </tr>
   
    <tr>
      <td><strong><?php echo DEFAULT_CURRENCY; ?>:</strong></td>
      <td valign="middle"><input type="checkbox" name="default_c" value="1"  <?=$dflt?>/></td>
    </tr>
   
    <td><input type="hidden" name="addedit" value="<?=$id?>"></td>
    <td><input type="submit" value="<?php echo ADD_EDIT_CAPACITY_SUBMIT;?>" name="submitCapacity" style="background:#e5f9bb; cursor:pointer; cursor:hand;"/></td>
   </tr>
  </table> 
 </form><br />
  <?php NOTE_EXCHANGE_RATE_WILL_BE_AUTO_GENERATED; ?>
</div>
<script type="text/javascript">

	$().ready(function() {

		$("#form1").validate();
     });

         

</script> 
<script src="js/jquery.validate.js" type="text/javascript"></script>
<?php include("footer.php"); ?>
