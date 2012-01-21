<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
	$db =& JFactory::getDBO();
	$subfields = reset($db->getTableFields('#__acymailing_subscriber'));
	$postFields = JRequest::getVar('fields',array());
	?>
<table class="admintable" cellspacing="1">
<tr><td class="key"><?php echo JText::_('TABLENAME'); ?></td><td><input type="text" name="tablename" size="80" value="<?php echo JRequest::getString('tablename',''); ?>" /></td></tr>
	<?php
	foreach($subfields as $oneField => $type){
		if(in_array($oneField,array('subid','confirmed','enabled','key','userid','accept','html','created'))) continue;
		echo '<tr><td class="key">'.$oneField.'</td><td><input size="50" type="text" name="fields['.$oneField.']" value="'.@$postFields[$oneField].'" /></td></tr>';
	}
?>
</table>