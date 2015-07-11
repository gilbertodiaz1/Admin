<?php 
include("access.php");
if(isset($_POST['act_sbmt'])){

		include("../includes/db.conn.php");

		include("../includes/conf.class.php");

		include("../includes/admin.class.php");

		$bsiAdminMain->payment_gateway_post();

		header("location:payment_gateway.php");

}

include("header.php"); 

include("../includes/conf.class.php");

include("../includes/admin.class.php");

$payment_gateway_val=$bsiAdminMain->payment_gateway();

?>    

 <link rel="stylesheet" type="text/css" href="css/jquery.validate.css" />

      <div id="container-inside">

      <span style="font-size:16px; font-weight:bold"><?php echo PAYMENT_GATEWAY_SETTING;?></span>

      <hr />

        <form action="<?=$_SERVER['PHP_SELF']?>" method="post" id="form1">

          <table cellpadding="5" cellspacing="2" border="0">

          <thead>

          <tr><th align="left"><?php echo ENABLED;?></th><th align="left"><?php echo GATEWAY;?></th><th align="left"><?php echo PAYMENT_GATEWAY_TITLE;?></th><th align="left"><?php echo ACCOUNT_INFO;?></th></tr>

          <tr><th colspan="4"><hr /></th></tr>

          </thead>

          <tbody>

           

            <tr>

            <td><input type="checkbox" value="pp" name="pp"  id="pp" <?=($payment_gateway_val['pp_enabled']) ? 'checked="checked"' : ''; ?> /></td>

            <td><strong><?php echo PAYPAL;?>:</strong></td>

            <td><input type="text" name="pp_title" id="pp_title" value="<?=$payment_gateway_val['pp_gateway_name']?>"  class="required"/></td>

            <td><input type="text" name="paypal_id" id="paypal_id" class="required email" value="<?=$payment_gateway_val['pp_account']?>" size="40"/>

              <?php echo ENTER_YOUR_PAYPAL_EMAIL;?></td>

          </tr>

         

          <tr>

            <td><input type="checkbox" value="poa" name="poa" id="poa" <?=($payment_gateway_val['poa_enabled']) ? 'checked="checked"' : ''; ?> /></td>

            <td><strong><?php echo MANUAL;?>:</strong></td>

            <td><input type="text"  name="poa_title" id="poa_title" value="<?=$payment_gateway_val['poa_gateway_name']?>" class="required" /></td>

            <td></td>

          </tr>

          <tr>

            <td><input type="checkbox" value="cc" name="cc" id="cc" <?=($payment_gateway_val['cc_enabled']) ? 'checked="checked"' : ''; ?> /></td>

            <td><strong><?php echo CREDIT_CARD;?>:</strong></td>

            <td><input type="text"  name="cc_title" id="cc_title" value="<?=$payment_gateway_val['cc_gateway_name']?>" class="required" /></td>

            <td></td>

          </tr>
          
        <tr>
        <td><input type="checkbox" value="checkout" name="checkout" id="checkout" <?php echo ($payment_gateway_val['co_enabled']) ? 'checked="checked"' : ''; ?>/></td>
        <td><strong>2Checkout:</strong></td>
        <td><input type="text" name="checkout_title" id="checkout_title" class="required" value="<?php echo $payment_gateway_val['co_gateway_name'] ?>"  /></td>
        <td><input type="text" name="checkout_acc" id="checkout_acc"  value="<?php echo $payment_gateway_val['co_account'] ?>" class="required" size="40"/>
        (enter your 2checkout vendor id)</td>
        </tr>
        
        <tr>
        <td><input type="checkbox" value="strip" name="strip" id="strip" <?php echo ($payment_gateway_val['strip_enabled']) ? 'checked="checked"' : ''; ?>/></td>
        <td><strong>Stripe:</strong></td>
        <td><input type="text" name="strip_title" id="strip_title" class="required" value="<?php echo $payment_gateway_val['strip_gateway_name'] ?>"  /></td>
        <td nowrap="nowrap">Secret Key:<input type="text" name="secret_key" id="secret_key" class="required" value="<?php echo $payment_gateway_val['secret_key'] ?>" size="12"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Publishable Key:
        <input type="text" name="publishable_key" id="publishable_key" class="required" value="<?php echo $payment_gateway_val['publishable_key'] ?>" size="12"/>&nbsp;&nbsp;&nbsp;&nbsp;
        Save & charge CC:  
        <input type="checkbox" value="charge" name="charge" id="charge"  <?php echo ($payment_gateway_val['charge']) ? 'checked="checked"' : ''; ?>/>
        </td>
        </tr>
        
        
    <tr>
    <td><input type="checkbox" value="an" name="an" id="an" <?php echo ($payment_gateway_val['an_enabled']) ? 'checked="checked"' : ''; ?>/></td>
    <td><strong>Authorize.Net:</strong></td>
    <td><input type="text" name="an_title" id="an_title" class="required" value="<?php echo $payment_gateway_val['an_gateway_name'] ?>" /></td>
    <td nowrap="nowrap">API Login ID:
    <input type="text" name="an_loginid" id="an_loginid" class="required" value="<?php echo $payment_gateway_val['an_api_id'] ?>" size="12" />&nbsp;&nbsp;&nbsp;&nbsp;
    MD5 Hash:
    <input type="text" name="an_md5hash" id="an_md5hash" class="required" value="<?php echo $payment_gateway_val['an_md_hash'] ?>" size="12" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Transaction Key:
    <input type="text" name="an_txnkey" id="an_txnkey" class="required" value="<?php echo $payment_gateway_val['an_trans_key'] ?>" size="12"/></td>
    </tr>

          

          

          <tr>

            

              <td colspan="2"></td>

              <td colspan="2"><input type="hidden" name="act_sbmt" value="1" /><input type="submit" value="<?php echo UPDATE;?>" style="background:#e5f9bb; cursor:pointer; cursor:hand;"/></td>

            </tr>

            </tbody>

          </table>

        </form>

      </div>

<script type="text/javascript">

	$().ready(function() {

		$("#form1").validate();

		

     });

         

</script>      

<script src="js/jquery.validate.js" type="text/javascript"></script>

<?php include("footer.php"); ?> 

