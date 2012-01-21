<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<?php
class ListViewList extends JView
{
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();
		parent::display($tpl);
	}
	function listing(){
		$app =& JFactory::getApplication();
		$pageInfo = null;
		$paramBase = ACYMAILING_COMPONENT.'.'.$this->getName();
		$pageInfo->filter->order->value = $app->getUserStateFromRequest( $paramBase.".filter_order", 'filter_order',	'a.ordering','cmd' );
		$pageInfo->filter->order->dir	= $app->getUserStateFromRequest( $paramBase.".filter_order_Dir", 'filter_order_Dir',	'asc',	'word' );
		$pageInfo->search = $app->getUserStateFromRequest( $paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower( $pageInfo->search );
		$selectedCreator = $app->getUserStateFromRequest( $paramBase."filter_creator",'filter_creator',0,'int');
		$pageInfo->limit->value = $app->getUserStateFromRequest( $paramBase.'.list_limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$pageInfo->limit->start = $app->getUserStateFromRequest( $paramBase.'.limitstart', 'limitstart', 0, 'int' );
		$database	=& JFactory::getDBO();
		$filters = array();
		if(!empty($pageInfo->search)){
			$searchVal = '\'%'.$database->getEscaped($pageInfo->search,true).'%\'';
			$filters[] = "a.name LIKE $searchVal OR a.description LIKE $searchVal OR a.listid LIKE $searchVal";
		}
		$filters[] = "a.type = 'list'";
		if(!empty($selectedCreator)) $filters[] = 'a.userid = '.$selectedCreator;
		$query = 'SELECT SQL_CALC_FOUND_ROWS a.*, d.name as creatorname, d.username, d.email';
		$query .= ' FROM '.acymailing::table('list').' as a';
		$query .=  ' LEFT JOIN '.acymailing::table('users',false).' as d on a.userid = d.id';
		$query .= ' WHERE ('.implode(') AND (',$filters).')';
		$query .= ' GROUP BY a.listid';
		if(!empty($pageInfo->filter->order->value)){
			$query .= ' ORDER BY '.$pageInfo->filter->order->value.' '.$pageInfo->filter->order->dir;
		}
		$database->setQuery($query,$pageInfo->limit->start,$pageInfo->limit->value);
		$rows = $database->loadObjectList();
		$database->setQuery('SELECT FOUND_ROWS()');
		$pageInfo->elements->total = $database->loadResult();
		$listids = array();
		foreach($rows as $oneRow){
			$listids[] = $oneRow->listid;
		}
		if(!empty($listids)){
			$querySelectSub = 'SELECT count(distinct subid) as nbsub,listid FROM '.acymailing::table('listsub').' WHERE listid IN ('.implode(',',$listids).') AND status = 1 GROUP BY listid';
			$querySelectUnsub = 'SELECT count(distinct subid) as nbunsub,listid FROM '.acymailing::table('listsub').' WHERE listid IN ('.implode(',',$listids).') AND status = -1 GROUP BY listid';
			$querySelectWaiting = 'SELECT count(distinct subid) as nbwait,listid FROM '.acymailing::table('listsub').' WHERE listid IN ('.implode(',',$listids).') AND status = 2 GROUP BY listid';
			$database->setQuery($querySelectSub);
			$subinfos = $database->loadObjectList('listid');
			$database->setQuery($querySelectUnsub);
			$unsubinfos = $database->loadObjectList('listid');
			$database->setQuery($querySelectWaiting);
			$waitinfos = $database->loadObjectList('listid');
		}
		foreach($rows as $i => $oneRow){
			$rows[$i]->nbsub = empty($subinfos[$oneRow->listid]) ? 0 : $subinfos[$oneRow->listid]->nbsub;
			$rows[$i]->nbunsub = empty($unsubinfos[$oneRow->listid]) ? 0 : $unsubinfos[$oneRow->listid]->nbunsub;
			$rows[$i]->nbwait = empty($waitinfos[$oneRow->listid]) ? 0 : $waitinfos[$oneRow->listid]->nbwait;
		}
		if(!empty($pageInfo->search)){
			$rows = acymailing::search($pageInfo->search,$rows);
		}
		$pageInfo->elements->page = count($rows);
		jimport('joomla.html.pagination');
		$pagination = new JPagination( $pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value );
		acymailing::setTitle(JText::_('LISTS'),'acylist','list');
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Link', 'filter', JText::_('ACY_FILTERS'), acymailing::completeLink('filter') );
		JToolBarHelper::divider();
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList(JText::_('ACY_VALIDDELETEITEMS'));
		JToolBarHelper::divider();
		$bar->appendButton( 'Pophelp','list-listing');
		$bar->appendButton( 'Link', 'acymailing', JText::_('JOOMEXT_CPANEL'), acymailing::completeLink('dashboard') );
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
		$filters = null;
		$listcreatorType = acymailing::get('type.listcreator');
		$filters->creator = $listcreatorType->display('filter_creator',$selectedCreator);
		$this->assignRef('filters',$filters);
		$this->assignRef('order',$order);
		$this->assignRef('toggleClass',acymailing::get('helper.toggle'));
		$this->assignRef('rows',$rows);
		$this->assignRef('pageInfo',$pageInfo);
		$this->assignRef('pagination',$pagination);
	}
	function form(){
		$listid = acymailing::getCID('listid');
		$listClass = acymailing::get('class.list');
		if(!empty($listid)){
			$list = $listClass->get($listid);
		}else{
			$list->published = 0;
			$list->visible = 1;
			$list->description = '';
			$user = JFactory::getUser();
			$list->creatorname = $user->name;
			$list->access_manage = 'none';
			$list->access_sub = 'all';
			$list->languages = 'all';
			$list->color = '#3366ff';
		}
		$editor = acymailing::get('helper.editor');
		$editor->name = 'editor_description';
		$editor->content = $list->description;
		$editor->setDescription();
		if(version_compare(JVERSION,'1.6.0','<')){
			$script = 'function submitbutton(pressbutton){
						if (pressbutton == \'cancel\') {
							submitform( pressbutton );
							return;
						}';
		}else{
			$script = 'Joomla.submitbutton = function(pressbutton) {
						if (pressbutton == \'cancel\') {
							Joomla.submitform(pressbutton,document.adminForm);
							return;
						}';
		}
		$script .= 'if(window.document.getElementById("name").value.length < 2){alert(\''.JText::_('ENTER_TITLE',true).'\'); return false;}';
		$script .= $editor->jsCode();
		if(version_compare(JVERSION,'1.6.0','<')){
			$script .= 'submitform( pressbutton );}';
		}else{
			$script .= 'Joomla.submitform(pressbutton,document.adminForm);}; ';
		}
		$script .= 'function affectUser(idcreator,name,email){
			window.document.getElementById("creatorname").innerHTML = name;
			window.document.getElementById("listcreator").value = idcreator;
		}';
		$doc =& JFactory::getDocument();
		$doc->addScriptDeclaration( $script );
		$colorBox = acymailing::get('type.color');
		acymailing::setTitle(JText::_('LIST'),'acylist','list&task=edit&listid='.$listid);
		$bar = & JToolBar::getInstance('toolbar');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		JToolBarHelper::divider();
		$bar->appendButton( 'Pophelp','list-form');
		$this->assignRef('colorBox',$colorBox);
		if(acymailing::level(1)){
			$this->assignRef('welcomeMsg',acymailing::get('type.welcome'));
			$this->assignRef('languages',acymailing::get('type.listslanguages'));
		}
		$this->assignRef('unsubMsg',acymailing::get('type.unsub'));
		$this->assignRef('list',$list);
		$this->assignRef('editor',$editor);
	}
}