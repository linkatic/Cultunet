<?php
defined('_JEXEC') or die('Restricted access');

/**
* Base plugin class.
*/
require_once JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'Savant2'.DS.'Plugin.php';

class Savant2_Plugin_mt_checkboxes extends Savant2_Plugin {

	function plugin( $name,	$data,	$checked = null,	$seperator="<br />", $attr = null)
	{
		$html = '';
		$i=0;
		foreach( $data AS $v => $k ) {
			
			// start the checkbox tag with name and value
			//echo "[ $v | $k ]";
			$html .= '<input type="checkbox"';
			$html .= ' name="' . htmlspecialchars($name) . '[]"';

			/* This conditional detect if array indexes is being assign automatically.
				 It is done by assuming if the first array index is 0, second is 1 and so
				 on, then it is automatically assigned.
			*/
			if ( $v == $i ) {
				$v = $k;
			}
			$html .= ' value="' . htmlspecialchars($v) . '"';
			
			// is the checkbox checked?
			$checked_array = explode(",",$checked);
			if (in_array($v, $checked_array)) {
				$html .= ' checked="checked"';
			}
			
			// add extra attributes
			if (is_array($attr)) {
				// add from array
				foreach ($attr as $key => $val) {
					$key = htmlspecialchars($key);
					$val = htmlspecialchars($val);
					$html .= " $key=\"$val\"";
				}
			} elseif (! is_null($attr)) {
				// add from scalar
				$html .= " $attr";
			}
			
			// close the checkbox tag and return
			$html .= ' />';

			if ( isset($k) ) {
				$html .= " ".$k;
			}
			$html .= $seperator;
			$i++;
		}
		return $html;
	}
}
?>