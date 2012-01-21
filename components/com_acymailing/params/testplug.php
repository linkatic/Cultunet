<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
if(version_compare(JVERSION,'1.6.0','<')){
	class JElementTestplug extends JElement
	{
		function fetchElement($name, $value, &$node, $control_name)
		{
			JHTML::_('behavior.modal','a.modal');
			$link = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=config&amp;task=plgtrigger&amp;plg='.$value.'&amp;plgtype='.$name;
			return '<a class="modal" title="Test"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}"><button onclick="return false">Test</button></a>';
		}
	}
}else{
	class JFormFieldTestplug extends JFormField
	{
		var $type = 'testplug';
		function getInput() {
			JHTML::_('behavior.modal','a.modal');
			$link = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=config&amp;task=plgtrigger&amp;plg='.$value.'&amp;plgtype='.$name;
			return '<a class="modal" title="Test"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}"><button onclick="return false">Test</button></a>';
		}
	}
}