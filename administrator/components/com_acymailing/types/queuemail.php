<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class queuemailType{
	function queuemailType(){
		$query = 'SELECT count(distinct a.subid) as totalsub, b.subject, a.mailid FROM '.acymailing::table('queue').' as a';
		$query .= ' LEFT JOIN '.acymailing::table('mail').' as b on a.mailid = b.mailid GROUP BY a.mailid ORDER BY b.subject ASC';
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$emails = $db->loadObjectList();
		$this->values = array();
		$this->values[] = JHTML::_('select.option', '0', JText::_('ALL_EMAILS') );
		foreach($emails as $oneMail){
			$this->values[] = JHTML::_('select.option', $oneMail->mailid, $oneMail->subject.' ( '.$oneMail->totalsub.' )' );
		}
	}
	function display($map,$value){
		return JHTML::_('select.genericlist',   $this->values, $map, 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', (int) $value );
	}
}