<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class LyftenBloggieViewLyftenBloggie extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		//initialize variables
		$settings 		= & BloggieSettings::getInstance();
		$title			= $settings->get('title');
		$description	= $settings->get('mainBlogDesc');
		$lg			 	= &JFactory::getLanguage();
		$language		= $lg->get('name');
		$feedURL		= JRoute::_(LyftenBloggieHelperRoute::getBlogFeedRoute('index.php?option=com_lyftenbloggie'));
		$createdate 	= & JFactory::getDate();
		$document 		=& JFactory::getDocument();

		// Get data from the model
		$rows	=  $this->get('feed');

		// set feed title
		$document->setTitle($title );
		$document->setLink($feedURL);

		foreach($rows as $row)
		{
			// create feed item
			$item = new JFeedItem();
	
			$title 			= $this->escape( $row->title );
			$item->title 	= html_entity_decode( $title );
	
			$archive		= ($row->created) ? JHTML::_('date',  $row->created, '&year=%Y&month=%m&day=%d') : '&category='.$row->catslug;
			$link		 	= htmlentities( JRoute::_(LyftenBloggieHelperRoute::getEntryRoute($archive, $row->slug)) );	
			$item->link 	= $link;
				
			$item->description = strip_tags( $this->_makeContent($row) );
	
			$item->date 	= date('r', strtotime($row->created));
			$item->pubDate 	= time();

			$document->addItem($item);
		}
	}

	/**
	* Escapes a value for output in a view script.
	*/
	function _makeContent($row)
	{
		global $mainframe;
			
		// Get the component configuration
		$settings 	= & BloggieSettings::getInstance();
		
		if($settings->get('feedSummarize'))
		{
			if(empty($row->fulltext)){
				$ending = strpos($row->introtext, '</p>');
				
				$pos=-1;
				$pos_array = array();
				while (($pos=strpos($row->introtext,'</p>',$pos+1))!==false) 
					$pos_array[]=$pos;
				
				$pNum = $settings->get('feedLength');
				if (count($pos_array) <= $pNum) {
				   $text 			= $row->introtext;
				} else {
					$ending 		= $pos_array[$pNum-1];
					$row->introtext = substr($row->introtext, 0, $ending + 4);
					$row->introtext = EntriesHelper::cleanHTML(preg_replace('#\s*<[^>]+>?\s*$#','',$row->introtext));
				}
			}
			else if( !empty($row->fulltext) && empty($row->introtext) )
			{
				// Strip x paragraphs
				$ending = strpos($row->fulltext, '</p>');
				
				$pos=-1;
				$pos_array = array();
				while (($pos=strpos($row->fulltext,'</p>',$pos+1))!==false) 
				$pos_array[]=$pos;
				
				$pNum = $settings->get('feedLength');
				if (count($pos_array) <= $pNum) {
					$text 			= $row->fulltext;
				} else {
					$ending = $pos_array[$pNum-1];
					$row->fulltext 	= substr($row->fulltext, 0, $ending + 4);
					$row->fulltext 	= EntriesHelper::cleanHTML(preg_replace('#\s*<[^>]+>?\s*$#','',$row->fulltext));
				}
			}
			
			$text =( empty($row->introtext) )?$row->fulltext:$row->introtext;

		} else{

			$text = (empty($row->introtext))?$row->fulltext:$row->introtext;

		}
		
		// Clean up the final text
		$text 	= str_replace(array('{mosimage}', '{mospagebreak}', '{readmore}'), '', $text);
		return $text;
	}
}
?>