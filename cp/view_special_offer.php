<?php
include("access.php");
   include("header.php"); 
    include("../includes/admin.class.php");
	include("../includes/conf.class.php");
	$bsiAdminMain->del_sp_pri_adj();
  ?>
    <script type="text/javascript">
function dynamiclist_del(cid){
	var ans=confirm('<?php echo DO_YOU_WANT_TO_DELETE_SELECTED_SPECIAL_OFFER ;?>');
	if(ans){
		window.location='<?=$_SERVER['PHP_SELF']?>?delid1='+cid;
		return true;
		
	}else{
		return false;
		
	}
	
}
</script>
  
  <div id="container-inside">
  <span style="font-size:16px; font-weight:bold"><?php echo VIEW_DYNAMIC_LIST; ?></span>
           &nbsp;  &nbsp;      <?php if(isset($_SESSION['val1']))
							  {
							   echo "<font color='#FF0033'> ".ROOM_TYPE_ALLREADY_ASSIGN_WITHIN_THIS_RANGE." </font> "; 
							   unset($_SESSION['val1']); 
							  }
							  ?>
    <input type="button" value="<?php echo ADD_SPECIAL_OFFER; ?>" onClick="window.location.href='entry_edit_special_offer.php?id=0'" style="background: #EFEFEF; float:right"/>
     <hr />
   
       <table class="display datatable" border="0">
    <thead>
      <tr>
      <th><?php echo OFFER_NAME_TEXT; ?></th>
        <th ><?php echo START_DATE;?></th>
        <th><?php echo END_DATE;?></th>
         <th><?php echo ROOM_TYPE;?></th>
          <th><?php echo PRICE_DEDUCTED;?></th>
          <th><?php echo MINIMUM_STAY;?></th>
           <th></th>
          
      </tr>
    </thead>
  <?php echo $bsiAdminMain->sp_off_mnt(); ?>
  </table>
</div>
<script type="text/javascript" src="js/DataTables/jquery.dataTables.js"></script> 
<script>
 $(document).ready(function() {
	 	var oTable = $('.datatable').dataTable( {
				"bJQueryUI": true,
				"sScrollX": "",
				"bSortClasses": false,
				"aaSorting": [[0,'asc']],
				"bAutoWidth": true,
				"bInfo": true,
				"sScrollY": "100%",	
				"sScrollX": "100%",
				"bScrollCollapse": true,
				"sPaginationType": "full_numbers",
				"bRetrieve": true,
				"oLanguage": {
								"sSearch": "<?=DT_SEARCH?>:",
								"sInfo": "<?=DT_SINFO1?> _START_ <?=DT_SINFO2?> _END_ <?=DT_SINFO3?> _TOTAL_ <?=DT_SINFO4?>",
								"sInfoEmpty": "<?=DT_INFOEMPTY?>",
								"sZeroRecords": "<?=DT_ZERORECORD?>",
								"sInfoFiltered": "(<?=DT_FILTER1?> _MAX_ <?=DT_FILTER2?>)",
								"sEmptyTable": "<?=DT_EMPTYTABLE?>",
								"sLengthMenu": "<?=DT_LMENU?> _MENU_ <?=DT_SINFO4?>",
								
							 }
	} );
} );
</script> 
<script type="text/javascript" src="js/bsi_datatables.js"></script>
<link href="css/data.table.css" rel="stylesheet" type="text/css" />
<link href="css/jqueryui.css" rel="stylesheet" type="text/css" />
 <?php include("footer.php"); ?>
