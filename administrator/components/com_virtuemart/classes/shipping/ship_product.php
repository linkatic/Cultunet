<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

/*

* @copyright (C) 2006, 2007, 2008, 2009 Theodore Root  & CompDAT LLC
*
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
******************************************************************************
* 
* This class will charge a fixed shipping rate based on the products in the users cart
*  
*******************************************************************************
*/
class ship_product 
{



  var $classname = "ship_product";
  
  function list_rates( &$d ) {
	global $total, $tax_total, $CURRENCY_DISPLAY;
	$db =& new ps_DB;
	$dbv =& new ps_DB;
	
	$cart = $_SESSION['cart'];

    /** Read current Configuration ***/
	require_once(CLASSPATH ."shipping/".$this->classname.".cfg.php");
	
	if ( $_SESSION['auth']['show_price_including_tax'] != 1 ) {
	  $taxrate = 1;
	  $order_total = $total + $tax_total;
	}
	else 
	{
	  $taxrate = $this->get_tax_rate() + 1;
	  $order_total = $total;  
	}
		$rate = $this->get_rate($d);
		
	  $shipping_rate_id = urlencode($this->classname."|STD|Standard Shipping|" . $rate . '|' . $rate);
	  
	  $html = "";
	  $html .= "\n<input type=\"radio\" name=\"shipping_rate_id\" checked=\"checked\" value=\"$shipping_rate_id\" />\n";
	  $html .= "Standard Shipping: ".$CURRENCY_DISPLAY->getFullValue($rate);
	  $_SESSION[$shipping_rate_id] = 1;
	
	echo $html;
	return true;
  

  }
	
  function get_rate( &$d ) 
  {	
  	$db =& new ps_DB;
  	$total = 0.00;
  	
  	foreach($_SESSION['cart'] as $key=>$value)
	{
		//Query for that products shipping amount
		//Multiply by quantity and add to sum

		if ($key !== "idx")
		{
			$product_id = $value['product_id'];
			$quantity = $value['quantity'];
			$sql = "Select amount from #__{vm}_product_shipping, ".
			"#__{vm}_product where #__{vm}_product.product_sku = " .
			"#__{vm}_product_shipping.product_sku and #__{vm}_product.product_id=" 
			. $product_id;
				
			$db->query($sql);
			$db->next_record();
			
			$shipping_amt = $db->f('amount');
			
			$total += round(($shipping_amt*$quantity), 2);
						
		}
		
	}
  	
  	
  	return $total;
	
  }
  
	
  function get_tax_rate() 
  {
	
    /** Read current Configuration ***/
	require_once(CLASSPATH ."shipping/".$this->classname.".cfg.php");
	
	if( intval(SHIPPRODUCT_TAX_CLASS)== 0 )
	  return( 0 );
	else {
	  require_once( CLASSPATH. "ps_tax.php" );
	  $tax_rate = ps_tax::get_taxrate_by_id( intval(SHIPPRODUCT_TAX_CLASS) );
	  return $tax_rate;
	}
  }
  
	/* Validate this Shipping method by checking if the SESSION contains the key
    * @returns boolean False when the Shipping method is not in the SESSION
    */
	function validate( $d ) {
	
	  $shipping_rate_id = $d["shipping_rate_id"];
	  
	  if( array_key_exists( $shipping_rate_id, $_SESSION ))
		return true;
	  else
		return false;
	}
	/**
    * Show all configuration parameters for this Shipping method
    * @returns boolean False when the Shipping method has no configration
    */
    function show_configuration() 
    { 
		$db =& new ps_DB;
    	global $PHPSHOP_LANG;
     
    	 /** Read current Configuration ***/
      require_once(CLASSPATH ."shipping/".$this->classname.".cfg.php");
    	
		// Determine if the appropriate configuration table exists
		// if not, create it
		
		$sql = "Select product_sku, amount from #__{vm}_product_shipping";
		$db->query($sql);
		
		if (!$db->next_record())
		{
			//There is no current configuration or the table doesn't exist
			//Therefore, we will create an initial table
			$sql = "CREATE TABLE `#__{vm}_product_shipping` " . 
			"(`product_sku` varchar(64) NOT NULL, `amount` float NOT NULL, " .
			"PRIMARY KEY  (`product_sku`) )";
			$db->query($sql);
			
			echo "No records";
		}
		
		/** Read current Configuration ***/

		echo "<table>";
		
      	$sql = "Select product_sku, amount from #__{vm}_product_shipping";
      	$db->query($sql);
      	
      	while ($db->next_record())
      	{
      		echo '<tr><td>Shipping Amount for ' . 
      		$db->f('product_sku') . 
      		'</td><td><input type="text" size="4" name="' .
      		$db->f('product_sku') .
      		'" value="' . 
      		$db->f('amount') .
      		'"></td>' . 
      		'<td>' . vmToolTip("Enter a new amount, or leave the amount blank to remove a value shipping record") . '</td>' .
      		'</tr>';
      	}
      	
      	//display a box to input new entries
      	
      	echo '<tr><td>New Product: <input type="text" name="__new_product" size="20"></td>';
      	echo '<td>Amount: <input type="text" name="__new_product_amount" size="4"></td>';
      	
      	echo '<td>' . vmToolTip("Enter a product SKU and amount to create a new value shipping record") . '</td>';
      	
      	echo '</tr>';
    
      	?>
      	 <tr>
		<td><strong><?php echo $PHPSHOP_LANG->_PHPSHOP_UPS_TAX_CLASS ?></strong></td>
		<td>
		  <?php
		  require_once(CLASSPATH.'ps_tax.php');
		  ps_tax::list_tax_value("SHIPPRODUCT_TAX_CLASS", SHIPPRODUCT_TAX_CLASS) ?>
		</td>
		<td><?php echo vmToolTip("Use the following tax class on the shipping charge.  The shipping charge values above will then be inclusive of this tax rate.") ?><td>
	  </tr>	
	  <?php  
      echo "</table>";
      return true;
   }
   
   
  /**
  * Returns the "is_writeable" status of the configuration file
  * @param void
  * @returns boolean True when the configuration file is writeable, false when not
  */
   function configfile_writeable() 
   {
       return is_writeable( CLASSPATH."shipping/".$this->classname.".cfg.php" );
   }
   
	/**
	* Writes the configuration file for this shipping method
	* @param array An array of objects
	* @returns boolean True when writing was successful
	*/
   function write_configuration( &$d ) 
   {
   		//if there is a new item, insert it:
   		$db =& new ps_DB;
   		
   		if (isset($d['__new_product']) && 
   			isset($d['__new_product_amount']) && 
   			$d['__new_product'] != '' && 
   			$d['__new_product_amount'] != '')
   		{
   		
   			$product = $d['__new_product'];
   			$amount = round(floatval($d['__new_product_amount']), 2);
   			
   			$sql = "Insert into #__{vm}_product_shipping values(" .
   			"'$product', $amount)";
   			$db->query($sql);
   		}
   		
   		//lookup all existing records, iterate through items and
   		//update as necassary, removing items whose price is now blank or null
   		
   		$sql = "Select product_sku, amount from #__{vm}_product_shipping";
      	$db->query($sql);
      	$sqlstmts = array();
      	
      	while ($db->next_record())
      	{
      		$product_sku = $db->f('product_sku');
      		$amount = $db->f('amount');
      		
      		//first case, we have a changed shipping amount
      		if (isset($d[$product_sku]) && 	
      				$d[$product_sku] != '' && $d[$product_sku] != $amount)
      		{
      			$new_amount = round(floatval($d[$product_sku]),2);
      			$sqlstmts[] = "Update #__{vm}_product_shipping set amount = $new_amount ".
      			"where product_sku = '$product_sku'";
      		}
      		//second case, we have an existing product that has been blanked
      		elseif (isset($d[$product_sku]) && $d[$product_sku] == '')
      		{
      			$sqlstmts[] = "Delete from #__{vm}_product_shipping ".
      			"where product_sku = '$product_sku'";
      			
      		}
      	}
      	
      	foreach($sqlstmts as $sql)
      	{
      		$db->query($sql);
      	}
   		
      	$my_config_array = array(
							  "SHIPPRODUCT_TAX_CLASS" => $d['SHIPPRODUCT_TAX_CLASS']
							  );
      $config = "<?php\n";
      $config .= "if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); \n\n";
      foreach( $my_config_array as $key => $value ) 
      {
        $config .= "define ('$key', '$value');\n";
      }
      
      $config .= "?>";
  
      if ($fp = fopen(CLASSPATH ."shipping/".$this->classname.".cfg.php", "w")) 
      {
          fputs($fp, $config, strlen($config));
          fclose ($fp);
          return true;
      }
      else 
      {
		$vmLogger->err( "Error writing to configuration file" );
   		return true;
      }
   }
      
      
}
	

?>
