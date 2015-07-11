<?php
/**
* @package BSI
* @author BestSoft Inc see README.php
* @copyright BestSoft Inc.
* See COPYRIGHT.php for copyright notices and details.
*/
$bsiCore = new bsiHotelCore;
class bsiHotelCore{
	public $config = array();
	public $userDateFormat = "";		
	
	function bsiHotelCore(){		
		$this->getBSIConfig();
		$this->getUserDateFormat();		
	}	
	
	private function getBSIConfig(){
		$sql = mysql_query("SELECT conf_id, IFNULL(conf_key, false) AS conf_key, IFNULL(conf_value,false) AS conf_value FROM bsi_configure");
		while($currentRow = mysql_fetch_assoc($sql)){
			if($currentRow["conf_key"]){
				if($currentRow["conf_value"]){
				$this->config[trim($currentRow["conf_key"])] = trim($currentRow["conf_value"]);
				}else{
					$this->config[trim($currentRow["conf_key"])] = false;
				}
			}
		}
		mysql_free_result($sql);	
	}
	
	private function getUserDateFormat(){		
		$dtformatter = array('dd'=>'%d', 'mm'=>'%m', 'yyyy'=>'%Y', 'yy'=>'%Y');		
		$dtformat = preg_split("@[/.-]@", $this->config['conf_dateformat']);
		$dtseparator = ($dtformat[0] === 'yyyy')? substr($this->config['conf_dateformat'], 4, 1) : substr($this->config['conf_dateformat'], 2, 1);
		$this->userDateFormat = $dtformatter[$dtformat[0]].$dtseparator.$dtformatter[$dtformat[1]].$dtseparator.$dtformatter[$dtformat[2]];	
	}	
	
	public function getMySqlDate($date){
		if($date == "") return "";
		$dateformatter = preg_split("@[/.-]@", $this->config['conf_dateformat']);
		$date_part = preg_split("@[/.-]@", $date);		
		$date_array = array();		
		for($i=0; $i<3; $i++) {
			$date_array[$dateformatter[$i]] = $date_part[$i];
		}
		return $date_array['yy']."-".$date_array['mm']."-".$date_array['dd'];
	}	
	
	public function ClearInput($dirty){
		$dirty = mysql_real_escape_string($dirty);
		return $dirty;
	}	
	
	public function capacitycombo(){
		$chtml = '<select id="capacity" name="capacity" class="input-medium">';
		
		$capacityrow = mysql_fetch_assoc(mysql_query("SELECT Max(capacity) as capa FROM bsi_capacity WHERE `id` IN (SELECT DISTINCT (capacity_id) FROM bsi_room) ORDER BY capacity"));
				for($i=1; $i<=$capacityrow["capa"]; $i++){ 
					$chtml .=  '<option value="'.$i.'">'.$i.'</option>';
				}
		$chtml .= '</select>';	
		return $chtml;
	}
	
	public function clearExpiredBookings(){		
		$sql = mysql_query("SELECT booking_id FROM bsi_bookings WHERE payment_success = false AND ((NOW() - booking_time) > ".intval($this->config['conf_booking_exptime'])." )");
		while($currentRow = mysql_fetch_assoc($sql)){			
			mysql_query("DELETE FROM bsi_invoice WHERE booking_id = '".$currentRow["booking_id"]."'");
			mysql_query("DELETE FROM bsi_reservation WHERE bookings_id = '".$currentRow["booking_id"]."'");	
			mysql_query("DELETE FROM bsi_bookings WHERE booking_id = '".$currentRow["booking_id"]."'");			
		}
		mysql_free_result($sql);
	}
	
	public function loadPaymentGateways() {			
		$paymentGateways = array();
		$sql = mysql_query("SELECT * FROM bsi_payment_gateway where enabled=true");
		while($currentRow = mysql_fetch_assoc($sql)){	
			$paymentGateways[$currentRow["gateway_code"]] = array('name'=>$currentRow["gateway_name"], 'account'=>$currentRow["account"]);	 
		}
		mysql_free_result($sql);
		return $paymentGateways;
	}
	
	public function encryptCard($creditno){
		$key = 'sdj*sadt63423h&%$@c34234c346v4c43czxcx'; //Change the key here
		$td = mcrypt_module_open('tripledes', '', 'cfb', '');
		srand((double) microtime() * 1000000);
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$okey = substr(md5($key.rand(0, 9)), 0, mcrypt_enc_get_key_size($td));
		mcrypt_generic_init($td, $okey, $iv);
		$encrypted = mcrypt_generic($td, $creditno.chr(194));
		$code = $encrypted.$iv;
		$code = eregi_replace("'", "\'", $code);
		return $code;
	}
	
	public function decryptCard($code){
		$key = 'sdj*sadt63423h&%$@c34234c346v4c43czxcx'; // use the same key used for encrypting the data
		$td = mcrypt_module_open('tripledes', '', 'cfb', '');
		$iv = substr($code, -8);
		$encrypted = substr($code, 0, -8);
		for ($i = 0; $i < 10; $i++) {
			$okey = substr(md5($key.$i), 0, mcrypt_enc_get_key_size($td));
			mcrypt_generic_init($td, $okey, $iv);
			$decrypted = trim(mdecrypt_generic($td, $encrypted));
			mcrypt_generic_deinit($td);
			$txt = substr($decrypted, 0, -1);
			if (ord(substr($decrypted, -1)) == 194 && is_numeric($txt)) break;
		}
		mcrypt_module_close($td);
		return $txt;
	}
	
	public function paymentGateway($code){
		$row = mysql_fetch_assoc(mysql_query("SELECT gateway_name FROM bsi_payment_gateway where gateway_code='".$code."'"));
		return  $row['gateway_name'];
	}
	
	public function getInvoiceinfo($bid){
		$invoiceres=mysql_fetch_assoc(mysql_query("select * from bsi_invoice where booking_id='".$bid."'"));			
		return $invoiceres['invoice'];
	}
	public function paymentGatewayName($gcode){
		$row=mysql_fetch_row(mysql_query("select gateway_name from bsi_payment_gateway where gateway_code='".$gcode."'"));
		return $row[0];
	}
	
	public function getChildcombo(){
		$child_res=mysql_query("SELECT max(`no_of_child`) as mchild FROM `bsi_room`");
		$rowchild=mysql_fetch_assoc($child_res);
		$childhtml="";
		if($rowchild['mchild']){
			$childhtml.= '<div class="control-group">
                            <label class="control-label" for="checkInDate">'.CHILD_PER_ROOM_TEXT.':</label>
                            <div class="controls">
                            	<select class="input-medium" id="child_per_room" name="child_per_room"><option value="0" selected>'.NONE_TEXT.'</option>' ;
		
			for($k=1;$k<=$rowchild['mchild'];$k++){
				  $childhtml.='<option value="'.$k.'">'.$k.'</option>';
			}
			$childhtml.=' </select></div></div>';
		}
		
		return $childhtml;
	}
	
	
	 
	 public function getExchangemoney_update() {
		$sql=mysql_query("select * from bsi_currency where default_c = 0");
		$default2=mysql_fetch_assoc(mysql_query("select * from bsi_currency where default_c = 1"));
		while($row=mysql_fetch_assoc($sql)){
			$amount=1;
			$amount = urlencode($amount);
			$from_Currency = urlencode($default2['currency_code']);
			$to_Currency = urlencode($row['currency_code']);
			$url = "http://www.google.com/ig/calculator?hl=en&q=$amount$from_Currency=?$to_Currency";
			$ch = curl_init();
			$timeout = 0;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$rawdata = curl_exec($ch);
			curl_close($ch);
			$data = explode('"', $rawdata);
			$data = str_replace("\xC2\xA0", "", $data['3']);
			$data = explode(' ',$data);		
			$var = $data['0'];
		//return round($var,3);
			mysql_query("update bsi_currency set  exchange_rate ='".$var."' where currency_code='".$row['currency_code']."'");
		}
	}
		 
	 public function getExchangemoney($amount1,$to_Currency1){
	      $row=mysql_fetch_assoc(mysql_query("select * from bsi_currency where currency_code = '".$to_Currency1."'"));
		  $exchange_rate=$row['exchange_rate'];
		  $amount        = $amount1*$exchange_rate;
		  return number_format($amount,2);
	 }
	 
	 public function currency_symbol(){
		 $default2=mysql_fetch_assoc(mysql_query("select * from bsi_currency where default_c = 1"));
		 return $default2['currency_symbl'];
		 
	 }
	 public function currency_code(){
		 $default2=mysql_fetch_assoc(mysql_query("select * from bsi_currency where default_c = 1"));
		 return $default2['currency_code'];
	 }
	 
	 public function get_currency_symbol($c_code){
		 $default2=mysql_fetch_assoc(mysql_query("select * from bsi_currency where currency_code = '".$c_code."'"));
		 return $default2['currency_symbl'];
	 }
	 
	  public function get_currency_combo3($c_code){
		  
		  $sql=mysql_query("select * from bsi_currency order by currency_code");
		  $combo='<div class="control-group">
                            <label class="control-label" for="checkInDate">'.CURRENCY_TEXT.':</label>
                            <div class="controls">
                            	<select class="input-medium" name="currency" id="currency">';
		   
		   
		  while($row=mysql_fetch_assoc($sql)){
			  if($row['currency_code'] == $c_code)
			  $combo.='<option value="'.$row["currency_code"].'"  selected="selected">'.$row['currency_code'].'</option>';
			  else
			  $combo.='<option value="'.$row["currency_code"].'">'.$row['currency_code'].'</option>';
		  }
		  $combo.='  </select>
                            </div>
                        </div>';
	      if(mysql_num_rows($sql) == 1){
			
			   $combo='<input type="hidden" name="currency" value="'.$this->currency_code().'" />';
		  }
		  
		  return $combo;
	  }
	  
	   public function get_currency_combo2($c_code){
		  $combo='<select name="currency"  class="input-small" onchange="currency_change(this.value)">';
		   $sql=mysql_query("select * from bsi_currency order by currency_code");
		  while($row=mysql_fetch_assoc($sql)){
			  if($row['currency_code'] == $c_code)
			  $combo.='<option value="'.$row["currency_code"].'"  selected="selected">'.$row['currency_code'].'</option>';
			  else
			  $combo.='<option value="'.$row["currency_code"].'">'.$row['currency_code'].'</option>';
		  }
		   
		  $combo.='</select>';
		  if(mysql_num_rows($sql) == 1){
			  $combo='';
		  }
		  return $combo;
	  }
	  
	  public function exchange_rate_update($type=1){
		  if($type){
			  if($this->config['conf_currency_update_time']!=''){
				  if(time() > ($this->config['conf_currency_update_time'] + 12 * 3600)){
					   $this->getExchangemoney_update();
				 	   mysql_query("update bsi_configure set conf_value='".time()."' where conf_key='conf_currency_update_time'");
				  }
				
				  
			  }else{
				  $this->getExchangemoney_update();
				  mysql_query("update bsi_configure set conf_value='".time()."' where conf_key='conf_currency_update_time'");
			  }
		  
	      }else{
			  $this->getExchangemoney_update();
		  }
		  
	  }
	  
	  public function roomtype_photos($rid,$cid){
		  $sql=mysql_query("select * from bsi_gallery where roomtype_id=".$rid." and capacity_id=".$cid);
		  $list_img='';
		  $lbox='';
		  if(mysql_num_rows($sql)){
			  while($row=mysql_fetch_assoc($sql)){
			   $list_img.='<li><a class="group_'.$rid.'_'.$cid.'" href="gallery/'.$row['img_path'].'" style="text-decoration:none; " ><img src="gallery/thumb_'.$row['img_path'].'" style="border-style: none" /></a></li>';
			  
			  }
		  }else{
			   $list_img.='<li><img src="images/no_photo.jpg" /></li>';
		  }
		  
		  return $list_img;
	  }
	  
	  public function bt_date_format(){
		  if($this->config['conf_dateformat']=='yy-mm-dd')
		  $df='yy'.$this->config['conf_dateformat'];
		  else
		   $df=$this->config['conf_dateformat'].'yy';
		   
		   return $df;
	  }
}