<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class TemplateViewTemplate extends JView
{
	var $selection =  array('a.tempid','a.name','a.description','a.created','a.published','a.premium','a.ordering');
	var $filters = array();
	var $button = true;
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();
		parent::display($tpl);
	}
	function listing(){
		$app =& JFactory::getApplication();
		$pageInfo = null;
		$paramBase = ACYMAILING_COMPONENT.'.'.$this->getName().$this->getLayout();
		$pageInfo->filter->order->value = $app->getUserStateFromRequest( $paramBase.".filter_order", 'filter_order',	'a.ordering','cmd' );
		$pageInfo->filter->order->dir	= $app->getUserStateFromRequest( $paramBase.".filter_order_Dir", 'filter_order_Dir',	'asc',	'word' );
		$pageInfo->search = $app->getUserStateFromRequest( $paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower( $pageInfo->search );
		$pageInfo->limit->value = $app->getUserStateFromRequest( $paramBase.'.list_limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$pageInfo->limit->start = $app->getUserStateFromRequest( $paramBase.'.limitstart', 'limitstart', 0, 'int' );
		$database	=& JFactory::getDBO();
		if(!empty($pageInfo->search)){
			$searchVal = '\'%'.$database->getEscaped($pageInfo->search,true).'%\'';
			$this->filters[] = "a.name LIKE $searchVal OR a.description LIKE $searchVal OR a.tempid LIKE $searchVal";
		}
		$query = 'SELECT SQL_CALC_FOUND_ROWS '.implode(',',$this->selection).' FROM '.acymailing::table('template').' as a';
		if(!empty($this->filters)){$query .= ' WHERE ('.implode(') AND (',$this->filters).')';}
		if(!empty($pageInfo->filter->order->value)){
			$query .= ' ORDER BY '.$pageInfo->filter->order->value.' '.$pageInfo->filter->order->dir;
		}
		$database->setQuery($query,$pageInfo->limit->start,$pageInfo->limit->value);
		$this->rows = $database->loadObjectList();
		$database->setQuery('SELECT FOUND_ROWS()');
		$pageInfo->elements->total = $database->loadResult();
		if(!empty($pageInfo->search)){
			$rows = acymailing::search($pageInfo->search,$this->rows);
		}else{
			$rows =& $this->rows;
		}
		$pageInfo->elements->page = count($rows);
		jimport('joomla.html.pagination');
		$pagination = new JPagination( $pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value );
		if($this->button){
			acymailing::setTitle(JText::_('ACY_TEMPLATES'),'acytemplate','template');
			$bar = & JToolBar::getInstance('toolbar');
			$bar->appendButton( 'Popup', 'upload', JText::_('IMPORT'), "index.php?option=com_acymailing&ctrl=template&task=upload&tmpl=component");
			JToolBarHelper::divider();
			JToolBarHelper::addNew();
			JToolBarHelper::editList();
			JToolBarHelper::deleteList(JText::_('ACY_VALIDDELETEITEMS',true));
			JToolBarHelper::spacer();
			JToolBarHelper::custom( 'copy', 'copy.png', 'copy.png', JText::_('ACY_COPY') );
			JToolBarHelper::divider();
			$bar->appendButton( 'Pophelp','template-listing');
			$bar->appendButton( 'Link', 'acymailing', JText::_('JOOMEXT_CPANEL'), acymailing::completeLink('dashboard') );
		}
		$toggleClass = acymailing::get('helper.toggle');
		$order = null;
		$order->ordering = false;
		$order->orderUp = 'orderup';
		$order->orderDown = 'orderdown';
		$order->reverse = false;
		if($pageInfo->filter->order->value == 'a.ordering'){
			$order->ordering = true;
			if($pageInfo->filter->order->dir == 'desc'){
				$order->orderUp = 'orderdown';
				$order->orderDown = 'orderup';
				$order->reverse = true;
			}
		}
		$this->assignRef('order',$order);
		$this->assignRef('toggleClass',$toggleClass);
		$this->assignRef('rows',$rows);
		$this->assignRef('pageInfo',$pageInfo);
		$this->assignRef('pagination',$pagination);
	}
	function form(){
		$tempid = acymailing::getCID('tempid');
		$app =& JFactory::getApplication();
		if(!empty($tempid)){
			$templateClass = acymailing::get('class.template');
			$template = $templateClass->get($tempid);
			if(!empty($template->body)) $template->body = acymailing::absoluteURL($template->body);
		}else{
			$template->body = '';
			$template->tempid = 0;
			$template->published = 1;
		}
		$editor = acymailing::get('helper.editor');
		$editor->setTemplate($template->tempid);
		$editor->name = 'editor_body';
		$editor->content = $template->body;
		$editor->prepareDisplay();
		if(version_compare(JVERSION,'1.6.0','<')){
			$script = 	'function submitbutton(pressbutton){
						if (pressbutton == \'cancel\') {
							submitform( pressbutton );
							return;
						}';
		}else{
			$script = 	'Joomla.submitbutton = function(pressbutton) {
						if (pressbutton == \'cancel\') {
							Joomla.submitform(pressbutton,document.adminForm);
							return;
						}';
		}
		$script .= 'if(window.document.getElementById("name").value.length < 2){alert(\''.JText::_('ENTER_TITLE',true).'\'); return false;}';
		$script .= "if(pressbutton == 'test' && window.document.getElementById('sendtest') && window.document.getElementById('sendtest').style.display == 'none'){ window.document.getElementById('sendtest').style.display = 'block'; return false;}";
		$script .= $editor->jsCode();
		if(version_compare(JVERSION,'1.6.0','<')){ $script .= 'submitform( pressbutton );} ';
		}else{ $script .= 'Joomla.submitform(pressbutton,document.adminForm);}; ';}
		$script .= "function insertTag(tag){ try{jInsertEditorText(tag,'editor_body'); return true;} catch(err){alert('Your editor does not enable AcyMailing to automatically insert the tag, please copy/paste it manually in your Newsletter'); return false;}}";
		$script .= "window.addEvent('domready', function(){ mytoolbar = $('toolbar'); if(!mytoolbar) return true; mytoolbar.addEvent('mouseover', function(){ try{IeCursorFix();}catch(e){} }); }); ";
		$script .= 'function addStyle(){
		var myTable=window.document.getElementById("classtable");
		var newline = document.createElement(\'tr\');
		var column = document.createElement(\'td\');
		var column2 = document.createElement(\'td\');
		var input = document.createElement(\'input\');
		var input2 = document.createElement(\'input\');
		input.type = \'text\';
		input2.type = \'text\';
		input.size = \'30\';
		input2.size = \'50\';
		input.name = \'otherstyles[classname][]\';
		input2.name = \'otherstyles[style][]\';
		input.value = "'.JText::_('CLASS_NAME',true).'";
		input2.value = "'.JText::_('CSS_STYLE',true).'";
		column.appendChild(input);
		column2.appendChild(input2);
		newline.appendChild(column);
		newline.appendChild(column2);
		myTable.appendChild(newline);
		}';
		$doc =& JFactory::getDocument();
		$doc->addScriptDeclaration( $script);
		$paramBase = ACYMAILING_COMPONENT.'.'.$this->getName();
		$infos = null;
		$infos->receiver_type = $app->getUserStateFromRequest( $paramBase.".receiver_type", 'receiver_type', '','string' );
		$infos->test_email = $app->getUserStateFromRequest( $paramBase.".test_email", 'test_email', '','string' );
		acymailing::setTitle(JText::_('ACY_TEMPLATE'),'acytemplate','template&task=edit&tempid='.$tempid);
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Popup', 'tag', JText::_('TAGS'), "index.php?option=com_acymailing&ctrl=tag&task=tag&tmpl=component",750,550);
		JToolBarHelper::divider();
		JToolBarHelper::custom('test', 'send', '',JText::_('SEND_TEST'), false);
		JToolBarHelper::spacer();
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
		$bar->appendButton( 'Pophelp','template-form');
		$this->assignRef('editor',$editor);
		$this->assignRef('receiverClass',acymailing::get('type.testreceiver'));
		$this->assignRef('template',$template);
		$this->assignRef('colorBox',acymailing::get('type.color'));
		$this->assignRef('infos',$infos);
		jimport('joomla.html.pane');
		$tabs	=& JPane::getInstance('tabs');
		$this->assignRef('tabs',$tabs);
	}
	function theme(){
		$this->selection[] = 'a.*';
		$this->filters[] = 'a.published = 1';
		$this->button = false;
		$this->listing();
		$js = "function applyTemplate(tempid){
				window.parent.changeTemplate(window.document.getElementById('htmlcontent_'+tempid).innerHTML,window.document.getElementById('textcontent_'+tempid).innerHTML,window.document.getElementById('subject_'+tempid).innerHTML,window.document.getElementById('stylesheet_'+tempid).innerHTML,window.document.getElementById('fromname_'+tempid).innerHTML,window.document.getElementById('fromemail_'+tempid).innerHTML,window.document.getElementById('replyname_'+tempid).innerHTML,window.document.getElementById('replyemail_'+tempid).innerHTML,tempid);
				try{ window.top.document.getElementById('sbox-window').close(); }catch(err){ window.top.SqueezeBox.close(); } }";
		$doc =& JFactory::getDocument();
		$doc->addScriptDeclaration( $js );
	}
	function upload(){
		$doc =& JFactory::getDocument();
		$doc->addStyleSheet( ACYMAILING_CSS.'frontendedition.css' );
	}
}