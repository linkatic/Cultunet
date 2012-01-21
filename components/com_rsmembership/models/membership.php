<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class RSMembershipModelMembership extends JModel
{
	function __construct()
	{
		parent::__construct();
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
	}
	
	function getMembership()
	{
		$cid = JRequest::getInt('cid');
		
		$row =& JTable::getInstance('RSMembership_Memberships','Table');
		$row->load($cid);
		
		if (!empty($row))
			$row->extras = $this->_getList("SELECT * FROM `#__rsmembership_membership_extras` `me` LEFT JOIN `#__rsmembership_extras` `e` ON (`me`.`extra_id` = `e`.`id`) WHERE `me`.`membership_id`='".(int) $row->id."' AND `e`.`published`='1' ORDER BY `e`.`ordering`");
		
		$currency = RSMembershipHelper::getConfig('currency');
		// {price} placeholder
		$price = RSMembershipHelper::getPriceFormat($row->price);
		// {buy} placeholder
		$buy = '<input type="submit" class="button" value="'.JText::_('RSM_SUBSCRIBE').'" name="Submit" />';
		// {extras} placeholder
		$extras = '';
		foreach ($row->extras as $extra)
		{
			$extras .= '<h3 class="rsm_extra_title">'.$extra->name.'</h3>';
			$extras .= $extra->description;
			$extras .= '<span class="rsm_clear"></span>';
			
			$extra_values = $this->_getExtraValues($extra->id);
			switch ($extra->type)
			{
				case 'dropdown':
				$values = array();
				$extras .= '<select name="rsmembership_extra['.$extra->id.']" class="rsm_extra">';
				$extras .= '<option value="0">'.JText::_('RSM_PLEASE_SELECT_EXTRA').'</option>';
				foreach ($extra_values as $value)
				{
					$value_price = RSMembershipHelper::getPriceFormat($value->price);
					$extras .= '<option '.($value->checked ? 'selected="selected"' : '').' value="'.$value->id.'">'.$value->name.' - '.$value_price.'</option>';
				}
				$extras .= '</select>';
				break;
				
				case 'radio':
				$values = array();
				foreach ($extra_values as $i => $value)
				{
					$i++;
					$value_price = RSMembershipHelper::getPriceFormat($value->price);
					$extras .= '<input type="radio" '.($value->checked ? 'checked="checked"' : '').' value="'.$value->id.'" id="extras'.$value->id.'" name="rsmembership_extra['.$extra->id.']" class="rsm_extra" />';
					$extras .= '<label for="extras'.$value->id.'" class="rsm_extra">'.$value->name.' - '.$value_price.'</label>';
				}
				break;
				
				case 'checkbox':
				foreach ($extra_values as $i => $value)
				{
					$i++;
					$value_price = !empty($value->price) ? $value->price.' '.$currency : JText::_('RSM_FREE');
					$extras .= '<input type="checkbox" '.($value->checked ? 'checked="checked"' : '').' value="'.$value->id.'" id="extras'.$value->id.'" name="rsmembership_extra['.$extra->id.'][]" class="rsm_extra" />';
					$extras .= '<label for="extras'.$value->id.'" class="rsm_extra">'.$value->name.' - '.$value_price.'</label>';
				}
				break;
			}
			$extras .= '<span class="rsm_clear"></span>';
		}
		
		if ($row->stock == -1)
		{
			$buy = '';
			JError::raiseWarning(500, JText::_('RSM_OUT_OF_STOCK'));
		}
		
		$replace = array('{price}', '{buy}', '{extras}');
		$with = array($price, $buy, $extras);
		
		$row->description = str_replace($replace, $with, $row->description);
		
		return $row;
	}
	
	function getCategory()
	{
		$catid = JRequest::getInt('catid');
		if (empty($catid))
			return false;
		
		$this->_db->setQuery("SELECT id, name FROM #__rsmembership_categories WHERE id='".$catid."'");
		$return = $this->_db->loadObject();
		if (empty($return))
			return false;
		
		return $return;
	}
	
	function _getExtraValues($id)
	{
		return $this->_getList("SELECT * FROM #__rsmembership_extra_values WHERE `published`='1' AND `extra_id`='".(int) $id."' ORDER BY `ordering`");
	}
}
?>