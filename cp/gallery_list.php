<?php 
include("access.php");
if(isset($_GET['delimg'])){
	include("../includes/db.conn.php");
	include("../includes/conf.class.php");
	include("../includes/admin.class.php");
	$bsiAdminMain->deletegallery();
	header("location:gallery_list.php");
	exit;
}
include("../includes/admin.class.php");
include("header.php"); 
?>
<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen">
<link rel="stylesheet" type="text/css" href="css/gallery.css" media="screen">
<div id="container-inside"> <span style="font-size:16px; font-weight:bold"><?php echo HOTEL_PHOTO_GALLERY_TEXT; ?></span>
  <input type="button" value="<?php echo ADD_PHOTO_TEXT; ?>" onClick="window.location.href='add_gallery.php'" style="background: #EFEFEF; float:right"/>
  
    <?php if(isset($_SESSION['hotelgal'])){echo $bsiAdminMain->getroomtypewithcapacity($_SESSION['hotelgal']);}else{echo $bsiAdminMain->getroomtypewithcapacity();}?>
  </select>
  <hr />
  <div class="indent gallery" id="gallery"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#hotelid').change(function(){
		if($('#hotelid').val() != "0"){
			getImage();
		}if($('#hotelid').val() == "0"){
			$('#gallery').html('<?php echo PLEASE_SELECT_A_ROOM_TYPE_ALERT; ?>');
		}
	});
	
	if($('#hotelid').val() != ""){
		getImage();
	}
	
	function getImage(){
		var querystr='actioncode=6&type_capacity_id='+$('#hotelid').val();
		//alert(querystr)
		$.post("admin_ajax_processor.php", querystr, function(data){						 
			if(data.errorcode == 0){
				
				$('#gallery').html(data.viewcontent);
			}else{
				$('#gallery').html(data.strmsg);
			}
		}, "json");	
	}
});	

function deleteImage(delimg){
	var roomtype_with_capacity = document.getElementById('hotelid').value;
	window.location.href='gallery_list.php?delimg='+delimg;	
}
</script> 

<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.js"></script> 
<?php include("footer.php"); ?>
