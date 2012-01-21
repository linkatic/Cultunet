<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class FieldsViewFields extends JView
{
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();
		parent::display($tpl);
	}
	function form(){
		$fieldid = acymailing::getCID('fieldid');
		$fieldsClass = acymailing::get('class.fields');
		if(!empty($fieldid)){
			$field = $fieldsClass->get($fieldid);
		}else{
			$field = null;
			$field->published = 1;
			$field->type = 'text';
			$field->backend = 1;
		}
		if(!empty($field->fieldid)) $fieldTitle = ' : '.$field->namekey;
		else $fieldTitle = '';
		acymailing::setTitle(JText::_('FIELD').$fieldTitle,'fields','fields&task=edit&fieldid='.$fieldid);
		$script = 'function addLine(){
			var myTable=window.document.getElementById("tablevalues");
			var newline = document.createElement(\'tr\');
			var column = document.createElement(\'td\');
			var column2 = document.createElement(\'td\');
			var input = document.createElement(\'input\');
			var input2 = document.createElement(\'input\');
			input.type = \'text\';
			input2.type = \'text\';
			input.name = \'fieldvalues[title][]\';
			input2.name = \'fieldvalues[value][]\';
			column.appendChild(input);
			column2.appendChild(input2);
			newline.appendChild(column);
			newline.appendChild(column2);
			myTable.appendChild(newline);
		}';
		$doc =& JFactory::getDocument();
		$doc->addScriptDeclaration( $script);
		$bar = & JToolBar::getInstance('toolbar');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
		$bar->appendButton( 'Pophelp','fields-form');
		$this->assignRef('fieldtype',acymailing::get('type.fields'));
		$this->assignRef('field',$field);
		$this->assignRef('fieldsClass',$fieldsClass);
	}
	function listing(){
		$db =& JFactory::getDBO();
		$db->setQuery('SELECT * FROM `#__acymailing_fields` ORDER BY `ordering` ASC');
		$rows = $db->loadObjectList();
		$bar = & JToolBar::getInstance('toolbar');
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('ACY_VALIDDELETEITEMS'));
		JToolBarHelper::divider();
		$bar->appendButton( 'Pophelp','fields-listing');
		$bar->appendButton( 'Link', 'acymailing', JText::_('JOOMEXT_CPANEL'), acymailing::completeLink('dashboard') );
		jimport('joomla.html.pagination');
		$total = count($rows);
		$pagination = new JPagination($total, 0,$total);
		acymailing::setTitle(JText::_('EXTRA_FIELDS'),'fields','fields');
		$this->assignRef('rows',$rows);
		$this->assignRef('toggleClass',acymailing::get('helper.toggle'));
		$this->assignRef('pagination',$pagination);
		$this->assignRef('fieldtype',acymailing::get('type.fields'));
		$this->assignRef('fieldsClass',acymailing::get('class.fields'));
	}
}