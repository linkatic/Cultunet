<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* Virtuemart Product Filter Module
*
* @version $Id: mod_vm_product_filter.php 1109 2010-12-10 10:30:30Z webgobe $
* @package VirtueMart
* @subpackage modules
*
* @copyright (C) 2004-2007 soeren - All Rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* VirtueMart is Free Software.
* VirtueMart comes with absolute no warranty.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* www.virtuemart.net
*/

// Load the virtuemart main parse code

global $mosConfig_absolute_path, $sess, $VM_LANG;
// Load the virtuemart main parse code
if( file_exists(dirname(__FILE__).'/../../components/com_virtuemart/virtuemart_parser.php' )) {
	require_once( dirname(__FILE__).'/../../components/com_virtuemart/virtuemart_parser.php' );
} else {
	require_once( dirname(__FILE__).'/../components/com_virtuemart/virtuemart_parser.php' );
}
$sess = new ps_session;
$text_before			= $params->get( 'text_before', '');
$product_type_id= $params->get( 'product_type_id', 1);
$current_url	= vmGet( $_SERVER, 'REQUEST_URI', '' );
$shopItemid 	= $sess->getShopItemid();
$Itemid  		= $params->get( 'Itemid', $shopItemid);
$db = new ps_DB;
$q  = "SELECT * FROM #__{vm}_product_type ";
$q .= "WHERE product_type_id='$product_type_id' ";
$q .= "AND product_type_publish='Y'";
$db->query($q);

$browsepage = $db->f("product_type_browsepage");
echo $text_before;
if (!$db->next_record()) { // There is no published Product Type
		echo $VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_BAD_PRODUCT_TYPE');
} else {
		echo $VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_IN_CATEGORY').": ".$db->f("product_type_name");
		
?>
<form action="<?php echo URL ?>index.php" method="post" name="attr_search" >
<input type="hidden" name="option" value="com_virtuemart" />
<input type="hidden" name="page" value="shop.browse" />
<input type="hidden" name="product_type_id" value="<?php echo $product_type_id ?>" />
<input type="hidden" name="Itemid" value="<?php echo $Itemid ?>" />
<?php 
	$q  = "SELECT * FROM #__{vm}_product_type_parameter ";
	$q .= "WHERE product_type_id=$product_type_id ";
	$q .= "ORDER BY parameter_list_order";
	$db->query($q);
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
	<?php
	if (!empty($browsepage)) { // show browsepage
		/** 
		*   Read the template file into a String variable.
		*
		* function read_file( $file, $defaultfile='') ***/
		$template = read_file( PAGEPATH."templates/".$browsepage.".php");
		//$template = str_replace( "{product_type_id}", $product_type_id, $template );	// If you need this, use it...
		while ($db->next_record()) {
			$item_name = "product_type_$product_type_id"."_".$db->f("parameter_name");
			$parameter_values=$db->f("parameter_values");
			$get_item_value = vmGet($_REQUEST, $item_name, "");
			$get_item_value_comp = vmGet($_REQUEST, $item_name."_comp", "");
			$parameter_type = $db->f("parameter_type");
			
			// Replace parameter value
			$template = str_replace( "{".$item_name."_value}", $get_item_value, $template );
				
			// comparison
			if (!empty($parameter_values) && $db->f("parameter_multiselect")=="Y") {
				if ($parameter_type == "V") { // type: Multiple Values
					// Multiple section List of values - comparison FIND_IN_SET
					$comp  = "<td width=\"10%\"  align=\"center\">\n";
					$comp .= "<select class=\"styled\" name=\"".$item_name."_comp\">\n";
					$comp .= "<option value=\"find_in_set_all\"".(($get_item_value_comp=="find_in_set_all")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_FIND_IN_SET_ALL')."</option>\n";
					$comp .= "<option value=\"find_in_set_any\"".(($get_item_value_comp=="find_in_set_any")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_FIND_IN_SET_ANY')."</option>\n";
					$comp .= "</select></td>";
				}
				else { // type: all other
					// Multiple section List of values - no comparison
					$comp = "<td><input type=\"hidden\" name=\"".$item_name."_comp\" value=\"in\" />\n</td>\n";
				}
			}
			else {
				switch( $parameter_type ) {
					case "C": // Char
						if (!empty($parameter_values)) { // List of values - no comparison
							$comp = "<input type=\"hidden\" name=\"".$item_name."_comp\" value=\"eq\" />\n";
							break;
						}
					case "I": // Integer
					case "F": // Float
					case "D": // Date & Time
					case "A": // Date
					case "M": // Time
						$comp  = "<select class=\"styled\" name=\"".$item_name."_comp\">\n";
						$comp .= "<option value=\"lt\"".(($get_item_value_comp=="lt")?" selected":"").">&lt;</option>\n";
						$comp .= "<option value=\"le\"".(($get_item_value_comp=="le")?" selected":"").">&lt;=</option>\n";
						$comp .= "<option value=\"eq\"".(($get_item_value_comp=="eq")?" selected":"").">=</option>\n";
						$comp .= "<option value=\"ge\"".((empty($get_item_value_comp)||$get_item_value_comp=="ge")?" selected":"").">&gt;=</option>\n";
						$comp .= "<option value=\"gt\"".(($get_item_value_comp=="gt")?" selected":"").">&gt;</option>\n";
						$comp .= "<option value=\"ne\"".(($get_item_value_comp=="ne")?" selected":"").">&lt;&gt;</option>\n";
						$comp .= "</select>\n";
						break;
					case "T": // Text
						if (!empty($parameter_values)) { // List of values - no comparison
							$comp = "<input type=\"hidden\" name=\"".$item_name."_comp\" value=\"texteq\" />\n";
							break;
						}
						$comp  = "<select class=\"styled\" name=\"".$item_name."_comp\">\n";
						$comp .= "<option value=\"like\"".(($get_item_value_comp=="like")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_IS_LIKE')."</option>\n";
						$comp .= "<option value=\"notlike\"".(($get_item_value_comp=="notlike")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_IS_NOT_LIKE')."</option>\n";
						$comp .= "<option value=\"fulltext\"".(($get_item_value_comp=="fulltext")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_FULLTEXT')."</option>\n";
						$comp .= "</select>";
						break;
					case "S": // Short Text
					default:  // Default type Short Text
						if (!empty($parameter_values)) { // List of values - no comparison
							$comp = "<input type=\"hidden\" name=\"".$item_name."_comp\" value=\"texteq\" />\n";
							break;
						}
						$comp  = "<select class=\"styled\" name=\"".$item_name."_comp\">\n";
						$comp .= "<option value=\"like\"".(($get_item_value_comp=="like")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_IS_LIKE')."</option>\n";
						$comp .= "<option value=\"notlike\"".(($get_item_value_comp=="notlike")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_IS_NOT_LIKE')."</option>\n";
						$comp .= "</select></td>";
				}
			}
			// Relace parameter comparison
			$template = str_replace( "{".$item_name."_comp}", $comp, $template );
			
			// Parameter field
			if (!empty($parameter_values)) { // List of values
				$fields=explode(";",$parameter_values);
				$attr = "<select class=\"styled\" name=\"$item_name";
				if ($db->f("parameter_multiselect")=="Y") {
					$size = min(count($fields),6);
					$attr .= "[]\" multiple size=\"$size\">\n";
					$selected_value = array();
					$get_item_value = vmGet($_REQUEST, $item_name, array());
					foreach($get_item_value as $value) {
						$selected_value[$value] = 1;
					}
					foreach($fields as $field) {
						$attr .= "<option value=\"$field\"".(($selected_value[$field]==1) ? " selected>" : ">"). $field."</option>\n";
					}
				}
				else {
					$attr .= "\">\n";
					$attr .= "<option value=\"\">".$VM_LANG->_('PHPSHOP_SELECT')."</option>\n";
					foreach($fields as $field) {
						$attr .= "<option value=\"$field\"".(($get_item_value==$field) ? " selected>" : ">"). $field."</option>\n";
					}
				}
				$attr .= "</select>";
			}
			else { // Input field					
				switch( $parameter_type ) {
					case "I": // Integer
					case "F": // Float
					case "D": // Date & Time
					case "A": // Date
					case "M": // Time
						$attr = "<input type=\"text\" class=\"styled\"  name=\"$item_name\" value=\"$get_item_value\" size=\"20\" />";
						break;
					case "T": // Text
						$attr = "<textarea class=\"styled\" name=\"$item_name\" cols=\"35\" rows=\"6\" >$get_item_value</textarea>";
						break;
					case "C": // Char
						$attr = "<input type=\"text\" class=\"styled\"  name=\"$item_name\" value=\"$get_item_value\" size=\"5\" />";
						break;
					case "S": // Short Text
					default: // Default type Short Text
						$attr = "<input type=\"text\" class=\"styled\" name=\"$item_name\" value=\"$get_item_value\" size=\"50\" />";
				}
			}
			// Relace parameter
			$template = str_replace( "{".$item_name."}", $attr, $template );
		}
		echo $template;
	}
	else { // show default list of parameters
		
		while ($db->next_record()) {
			$parameter_type = $db->f("parameter_type");
			if ($parameter_type!="B") {
				echo "<tr valign=\"top\">\n  <td   ><div align=\"right\"><strong>";
				echo $db->f("parameter_label");
				echo "&nbsp;:</strong></div>\n  </td>\n";
				
				$parameter_values=$db->f("parameter_values");
				$item_name = "product_type_$product_type_id"."_".$db->f("parameter_name");
				$get_item_value = vmGet($_REQUEST, $item_name, "");
				$get_item_value_comp = vmGet($_REQUEST, $item_name."_comp", "");
				
				// comparison
				if (!empty($parameter_values) && $db->f("parameter_multiselect")=="Y") {
					if ($parameter_type == "V") { // type: Multiple Values
						// Multiple section List of values - comparison FIND_IN_SET
						echo "<td width=\"10%\"  align=\"center\">\n";
						echo "<select class=\"styled\" name=\"".$item_name."_comp\">\n";
						echo "<option value=\"find_in_set_all\"".(($get_item_value_comp=="find_in_set_all")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_FIND_IN_SET_ALL')."</option>\n";
						echo "<option value=\"find_in_set_any\"".(($get_item_value_comp=="find_in_set_any")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_FIND_IN_SET_ANY')."</option>\n";
						echo "</select></td>";
					}
					else { // type: all other
						// Multiple section List of values - no comparison
						echo "<td><input type=\"hidden\" name=\"".$item_name."_comp\" value=\"in\" />\n</td>\n";
					}
				}
				else {
					switch( $parameter_type ) {
						case "C": // Char
							if (!empty($parameter_values)) { // List of values - no comparison
								echo "<td><input type=\"hidden\" name=\"".$item_name."_comp\" value=\"eq\" />\n</td>\n";
								break;
							}
						case "I": // Integer
						case "F": // Float
						case "D": // Date & Time
						case "A": // Date
						case "M": // Time
							echo "<td width=\"10%\"  align=\"center\">\n";
							echo "<select class=\"styled\" name=\"".$item_name."_comp\">\n";
							echo "<option value=\"lt\"".(($get_item_value_comp=="lt")?" selected":"").">&lt;</option>\n";
							echo "<option value=\"le\"".(($get_item_value_comp=="le")?" selected":"").">&lt;=</option>\n";
							echo "<option value=\"eq\"".(($get_item_value_comp=="eq")?" selected":"").">=</option>\n";
							echo "<option value=\"ge\"".((empty($get_item_value_comp)||$get_item_value_comp=="ge")?" selected":"").">&gt;=</option>\n";
							echo "<option value=\"gt\"".(($get_item_value_comp=="gt")?" selected":"").">&gt;</option>\n";
							echo "<option value=\"ne\"".(($get_item_value_comp=="ne")?" selected":"").">&lt;&gt;</option>\n";
							echo "</select></td>";
							break;
						case "T": // Text
							if (!empty($parameter_values)) { // List of values - no comparison
								echo "<td><input type=\"hidden\" name=\"".$item_name."_comp\" value=\"texteq\" />\n</td>\n";
								break;
							}
							echo "<td width=\"10%\"  align=\"center\">\n";
							echo "<select class=\"styled\" name=\"".$item_name."_comp\">\n";
							echo "<option value=\"like\"".(($get_item_value_comp=="like")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_IS_LIKE')."</option>\n";
							echo "<option value=\"notlike\"".(($get_item_value_comp=="notlike")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_IS_NOT_LIKE')."</option>\n";
							echo "<option value=\"fulltext\"".(($get_item_value_comp=="fulltext")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_FULLTEXT')."</option>\n";
							echo "</select></td>";
							break;
						case "V": // Multiple Value
							echo "<td><input type=\"hidden\" name=\"".$item_name."_comp\" value=\"find_in_set\" />\n</td>\n";
							break;
						case "S": // Short Text
						default:  // Default type Short Text
							if (!empty($parameter_values)) { // List of values - no comparison
								echo "<td><input type=\"hidden\" name=\"".$item_name."_comp\" value=\"texteq\" />\n</td>\n";
								break;
							}
							echo "<td width=\"10%\"  align=\"center\">\n";
							echo "<select class=\"styled\" name=\"".$item_name."_comp\">\n";
							echo "<option value=\"like\"".(($get_item_value_comp=="like")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_IS_LIKE')."</option>\n";
							echo "<option value=\"notlike\"".(($get_item_value_comp=="notlike")?" selected":"").">".$VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_IS_NOT_LIKE')."</option>\n";
							echo "</select></td>";
					}
				}
				
				if (!empty($parameter_values)) { // List of values
					$fields=explode(";",$parameter_values);
					echo "<td  >\n";
					echo "<select class=\"styled\" name=\"$item_name";
					if ($db->f("parameter_multiselect")=="Y") {
						$size = min(count($fields),6);
						echo "[]\" multiple size=\"$size\">\n";
						$selected_value = array();
						$get_item_value = vmGet($_REQUEST, $item_name, array());
						foreach($get_item_value as $value) {
							$selected_value[$value] = 1;
						}
						foreach($fields as $field) {
							echo "<option value=\"$field\"".(($selected_value[$field]==1) ? " selected>" : ">"). $field."</option>\n";
						}
					}
					else {
						echo "\">\n";
						echo "<option value=\"\">".$VM_LANG->_('PHPSHOP_SELECT')."</option>\n";
						foreach($fields as $field) {
							echo "<option value=\"$field\"".(($get_item_value==$field) ? " selected>" : ">"). $field."</option>\n";
						}
					}
					echo "</select>";
				}
				else { // Input field					
					echo "<td >\n";
					switch( $parameter_type ) {
						case "I": // Integer
						case "F": // Float
						case "D": // Date & Time
						case "A": // Date
						case "M": // Time
							echo "<input type=\"text\" class=\"styled\"  name=\"$item_name\" value=\"$get_item_value\" size=\"20\" />";
							break;
						case "T": // Text
							echo "<textarea class=\"styled\" name=\"$item_name\" cols=\"35\" rows=\"6\" >$get_item_value</textarea>";
							break;
						case "C": // Char
							echo "<input type=\"text\" class=\"styled\"  name=\"$item_name\" value=\"$get_item_value\" size=\"5\" />";
							break;
						case "S": // Short Text
						default: // Default type Short Text
							echo "<input type=\"text\" class=\"styled\" name=\"$item_name\" value=\"$get_item_value\" size=\"50\" />";
					}
				}
				echo " ".$db->f("parameter_unit");
				switch( $parameter_type ) {
					case "D": // Date & Time
						echo " (".$VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_DATE_FORMAT')." ";
						echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_TIME_FORMAT').")";
						break;
					case "A": // Date
						echo " (".$VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_DATE_FORMAT').")";
						break;
					case "M": // Time
						echo " (".$VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_TIME_FORMAT').")";
						break;
				}
			}
			else { // Break line (type == "B")
				echo "<tr valign=\"top\">\n  <td colspan=\"3\" ><hr>";
			}
			echo "  </td>\n</tr>";
		}
	}
	// Search Button
?>	
	<tr valign="top"><td colspan="3" height="2" ><div align="center">
		<input type="submit" class="button" name="search" value="<?php echo $VM_LANG->_('PHPSHOP_SEARCH_TITLE') ?>">
		</div></td>
	</tr>
</table>
<?php
  } // end - There is a published Product Type
/** Changed Product Type - End */
?>
</form>