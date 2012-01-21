<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

/*

* @copyright (C) 2006, 2007, 2008, 2009 Theodore Root  & CompDAT LLC
*
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
******************************************************************************
* 
* This class will charge a fixed shipping rate based on the product SKUs and their quantities in the users cart
*  
*******************************************************************************
*/
class ship_product_qty
{



  var $classname = "ship_product_qty";
  
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
			$sql = "Select min(base_amount) as `base_amount`, min(additional_charge) as `additional_charge` from #__{vm}_product_qty_shipping, ".
			"#__{vm}_product where #__{vm}_product_qty_shipping.quantity <= $quantity and #__{vm}_product.product_sku = " .
			"#__{vm}_product_qty_shipping.product_sku and #__{vm}_product.product_id=" 
			. $product_id;
				
			$db->query($sql);
			
			if ( !$db->next_record() )
				die("No shipping amount specified for $quantity units of $product_id");
			
			$base_amt = $db->f('base_amount');
			$additional_charge = $db->f('additional_charge');
			
			if ( $quantity >= 1)
				$total += round($base_amt + ($additional_charge*($quantity)), 2);
			else
				$total += 0;
			
			/**
			if ( $quantity == 1 )
				$total += round($base_amt, 2);
			elseif ( $quantity > 1)
				$total += round($base_amt + ($additional_charge*($quantity-1)), 2);
			else
				$total += 0;
			**/
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
    	global $VM_LANG;
     
    	 /** Read current Configuration ***/
      require_once(CLASSPATH ."shipping/".$this->classname.".cfg.php");
    	
		// Determine if the appropriate configuration table exists
		// if not, create it
		
		$sql = "Select id, product_sku, base_amount, quantity, additional_charge from #__{vm}_product_qty_shipping";
		$db->query($sql);
		
		//echo "Execing" . $sql;
		
		if (!$db->next_record())
		{
			//There is no current configuration or the table doesn't exist
			//Therefore, we will create an initial table
			$sql = "CREATE TABLE `#__{vm}_product_qty_shipping` " . 
			"(`id` int NOT NULL AUTO_INCREMENT, ".
			"`product_sku` varchar(64) NOT NULL, " .
			"`base_amount` decimal(10,2) NOT NULL, ".
			"`quantity` int NOT NULL, " .
			"`additional_charge` decimal(10,2) NOT NULL, " .
			"PRIMARY KEY  (`id`) )";
			$db->query($sql);
			
			echo "No records";
		}
		
		/** Read current Configuration ***/

		echo "<table>";
		
      	$sql = "Select id, product_sku, base_amount, quantity, additional_charge from #__{vm}_product_qty_shipping";
      	$db->query($sql);
      	
      	while ($db->next_record())
      	{
      		echo '<tr><td>Shipping Amount for ' . 
      		$db->f('product_sku') . 
      		'</td><td>'.
      		'Quantity: <input type="text" size="4" name="quantity_' .
      		$db->f('id') .
      		'" value="' . 
      		$db->f('quantity') . '"></td><td>'.
      		'Base Charge: <input type="text" size="4" name="base_amount_' .
      		$db->f('id') .
      		'" value="' . 
      		$db->f('base_amount') .
      		'"></td><td>'.
      		'Additional Charge<input type="text" size="4" name="additional_charge_' .
      		$db->f('id') .
      		'" value="' . 
      		$db->f('additional_charge') .
      		'">' .
      		
      		'</td>' . 
      		'<td>' . vmToolTip("Enter new amount(s), or leave all the amounts blank to remove a value shipping record") . '</td>' .
      		'</tr>';
      	}
      	
      	//display a box to input new entries
      	$i = 1;
      	while( $i < 21 )
      	{
      		echo "<tr><td>SKU: <input type=\"text\" name=\"__new_product_sku_$i\" size=\"20\"></td>";
	      	echo "<td>Quantity: <input type=\"text\" name=\"__new_product_quantity_$i\" size=\"4\"></td>";
    	  	echo "<td>Amount: <input type=\"text\" name=\"__new_product_base_amount_$i\" size=\"4\"></td>";
      		echo "<td>Additional Charge: <input type=\"text\" name=\"__new_product_additional_charge_$i\" size=\"4\"></td>";
      	
      		echo '<td>' . vmToolTip("Enter a product SKU, quantity, base amount and additional charge to create a new value shipping record") . '</td>';
      	
     	 	echo '</tr>';
     	 	$i++;
    	}
      	?>
      	 <tr>
		<td><strong><?php echo $VM_LANG->_('PHPSHOP_UPS_TAX_CLASS') ?></strong></td>
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
   		$db = new ps_DB;
   		$i = 1;
   		
   		while( $i < 21 )
   		{
   		
   		if (isset($d["__new_product_sku_$i"]) && 
   			isset($d["__new_product_quantity_$i"]) && 
   			isset($d["__new_product_base_amount_$i"]) &&
   			isset($d["__new_product_additional_charge_$i"]) && 
   			$d["__new_product_sku_$i"] != '' && 
   			$d["__new_product_quantity_$i"] != '' &&
   			$d["__new_product_base_amount_$i"] != '' &&
   			$d["__new_product_additional_charge_$i"] != ''
   			
   			)
   		{
   		
   			$product = $d["__new_product_sku_$i"];
   			$quantity = intval($d["__new_product_quantity_$i"]);
   			$base_amount = $d["__new_product_base_amount_$i"];
   			$additional_charge=$d["__new_product_additional_charge_$i"];
   			
   			$sql = "INSERT INTO #__{vm}_product_qty_shipping (product_sku,quantity,base_amount,additional_charge) VALUES (" .
   			"'$product', '$quantity', '$base_amount', '$additional_charge')";
			
			echo $sql;
			
   			$db->query($sql);
   			$new_record_id[] = $db->last_insert_id();

   		}
   		$i++;
   		}
   		//lookup all existing records, iterate through items and
   		//update as necassary, removing items whose price is now blank or null
   		
   		$sql = "Select id, product_sku, quantity, base_amount, additional_charge from #__{vm}_product_qty_shipping";
      	$db->query($sql);
      	$sqlstmts = array();
      	
      	while ($db->next_record())
      	{
      		$id = $db->f('id');
      		$product_sku = $db->f('product_sku');
      		$quantity = $db->f('quantity');
      		$base_amount = $db->f('base_amount');
      		$additional_charge = $db->f('additional_charge');
      		
      		//first case, we have a changed amounts
      		if ( (isset($d['base_amount_'.$id]) && $d['base_amount_'.$id] != $base_amount && $d['base_amount_'.$id] != '') || 	
      				(isset($d['quantity_'.$id]) && $d['quantity_'.$id] != $quantity && $d['quantity_'.$id] != '') || 
      				(isset($d['additional_charge_'.$id]) && $d['additional_charge_'.$id] != $additional_charge && $d['additional_charge_'.$id] != '') )
      		{
      			$new_quantity = intval($d['quantity_'.$id]);
      			$new_base_amount = round(floatval($d['base_amount_'.$id]),2);
      			$new_additional_charge = round(floatval($d['additional_charge_'.$id]),2);
      			
      			$sqlstmts[] = "Update #__{vm}_product_qty_shipping set quantity = $new_quantity, base_amount = $new_base_amount, additional_charge = $new_additional_charge ".
      			"where id = $id";
      		}
      		//second case, we have an existing product that has been blanked
      		elseif (empty($d['base_amount_'.$id]) && 	
      				empty($d['quantity_'.$id]) && empty($d['additional_charge_'.$id]) && array_search($id, $new_record_id) === false)
      		{
      			$sqlstmts[] = "DELETE FROM #__{vm}_product_qty_shipping ".
      			"where id = $id";
      			
      		}
      	}
      	
      	foreach($sqlstmts as $sql)
      	{
      		//echo "Executing $sql";
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
