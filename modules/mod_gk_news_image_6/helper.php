<?php

/**
* Gavick News Image VI - helper class
* @package Joomla!
* @Copyright (C) 2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.1 $
**/

// access restriction
defined('_JEXEC') or die('Restricted access');
// include com_content helper for article routing
require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class gkNewsImage6Helper
{	
	// ID of module
	var $module_id; 
	// ID of Photoslide GK2 group
	var $group_id; 
	// Showing block with text over slides ?
	var $show_text_block; 
	// Opacity of block with text
	var $text_block_opacity;
	// Type of text block background - Photoslide default or user own background
	var $text_block_background;
	// Color of background in block with text
	var $text_block_bgcolor; 
	// Option to cleaning xhtml content
	var $clean_xhtml;
	// Content of readmore link
	var $readmore_text; 
	// Showing readmore link ?
	var $readmore_button;
	// Showing title as link ?
	var $title_link;
	// Position of tabs
	var $tabs_position; 
	// Speed of slide animation
	var $animation_slide_speed; 
	// Interval of slide animation
	var $animation_interval;
	// Autoanimation of slides
	var $autoanimation;
	// Type of slide animation
	var $animation_slide_type;
	// Type of text animation
	var $animation_text_type; 
	// Include MooTools on site ?
	var $useMoo;
	// Include module script on site ?
	var $useScript;
	// Path to Photoslide image storage
	var $base_path_to_images;
	// Amount of tabs
	var $tabs_amount; 
	// Width of tabs area
	var $tabsbar_width;
	// Storage for JSON data
	var $JSON;
	// Width of module
	var $module_width;
	// Height of module
	var $module_height;
	// Width of thumbnails in tabs
	var $thumbnail_width;
	// Height of thumbnails in tabs
	var $thumbnail_height;
	// Basic background color 
	var $base_bgcolor;
	// Basic title color
	var $base_titlecolor;
	// Basic text color
	var $base_textcolor; 
	// Basic links color
	var $base_linkcolor;
	// Basic hover links color
	var $base_hlinkcolor; 
	// Storage for slides
	var $slides;
	// Module background
	var $module_bg;
	// Clean code in template ?
	var $clean_code;
	// Margin for tabs
	var $tabs_margin;
	// Margin beetween tabs and main image
	var $tabsbar_margin;
	// Border for tabs
	var $tabs_per_page;
	// Use compressed JavaScript engine ?
	var $compress_js;
	// Enable preloading ?
	var $preloading;
	// Fixed height of text block
	var $start_h;
	// Chars limit for title in tabs
	var $title_limit;
	// Chars limit for text in tabs
	var $text_limit;

	/**
		Initializing class variables
	**/
	
	function initialize(&$params)
	{
		// basic parameters of module
		$this->module_id = $params->get('module_id', 'news_image_6_1'); // Module unique ID
		$this->group_id = $params->get('group_id', 0); // Photoslide group ID
		$this->module_bg = $params->get('module_bg', ''); // Module background color
		// text configuration
		$this->show_text_block = $params->get('show_text_block', 1); // Show text block ?
		$this->text_block_opacity = $params->get('text_block_opacity', 0.45); // Value of opacity in text block
		$this->text_block_background = $params->get('text_block_background', 0); // Type of background in text block
		$this->text_block_bgcolor = $params->get('text_block_bgcolor', 0); // Color of background in text block
		$this->clean_xhtml = $params->get('clean_xhtml', 0); // Clean XHTML content in text block ?
		$this->readmore_button = $params->get('readmore_button',1); // Show readmore link ?
		$this->readmore_text = $params->get('readmore_text','Read more'); // Text content for readmore link
		$this->title_link = $params->get('title_link',1); // showing title as links ?
		// tabs configuration
		$this->tabsbar_width = $params->get('tabsbar_width', 200); // Width of block with tabs
		$this->tabs_amount = $params->get('tabs_amount', 10); // Amount of tabs
		$this->tabs_margin = $params->get('tabs_margin', 3); // Amount of tabs
		$this->tabs_per_page = $params->get('tabs_per_page', 4); // Amount of tabs
		$this->tabs_position = $params->get('tabs_position', 'left'); // Position of tabs
		$this->tabsbar_margin = $params->get('tabsbar_margin', 5); // Margin beetween tabs and main image
		// animation configuration
		$this->animation_slide_speed = $params->get('animation_slide_speed', 0); // Speed of slide animation
		$this->animation_interval = $params->get('animation_interval', 0); // Interval of slide animation
		$this->autoanimation = $params->get('autoanimation', 0); // Autoanimation ?
		$this->animation_slide_type = $params->get('animation_slide_type', 0); // Type of slide animation
		$this->animation_text_type = $params->get('animation_text_type', 0); // Type of text animation 
		// scripts configuration
		$this->clean_code = (bool) $params->get('clean_code', 1); // Use importer.php instead of inline JavaScript code in template ?
		$this->useMoo = $params->get('useMoo', 2); // Include MooTools on site ?
		$this->useScript = $params->get('useScript', 2); // Include script on site ?
		$this->compress_js = $params->get('compress_js', 1); // Use compressed JavaScript engine ?
		$this->preloading = $params->get('preloading', 1); // Enable preloading ?
		$this->start_h = $params->get('start_h', 120); // Fixed height of text block
		// text limits
		$this->title_limit = $params->get('title_limit', 40); // Title chars limit
		$this->text_limit = $params->get('text_limit', 100); // Text chars limit
		// generating base path
		$uri =& JURI::getInstance(); // getting instance of JURI class
		$this->base_path_to_images = $uri->root().'components/com_gk2_photoslide/images'; // generating path to Photoslide image storage
	}

	/**
		Getting datas from database and preparing it
	**/

	function getDatas()
	{	
		// creating SQL query to get data from Photoslide component
		$query = "SELECT * FROM #__gk2_photoslide_groups WHERE `id` = ".$this->group_id." LIMIT 1;";
		// creating base classes and variables
		$database = & JFactory::getDBO(); // getting instance of database interface
		$uri = JURI::getInstance(); // getting instance of JURI class
		$database->setQuery($query); // setting query
		$user =& JFactory::getUser(); // getting User informations
		$aid = $user->get('aid', 0); // getting AID value
		// If some data exists
		if( $data = $database->loadObjectList())
		{
			// Preparing data
			foreach($data as $item)
			{
				// module images parameters
				$this->module_width = $item->mediumThumbX; // width of main slide
				$this->module_height = $item->mediumThumbY; // height of main slide
				$this->thumbnail_width = $item->smallThumbX; // width of thumbnail
				$this->thumbnail_height = $item->smallThumbY; // height of thumbnail
				// module basic settings
				$this->base_bgcolor = $item->bgcolor; // basic background color
				$this->base_titlecolor = $item->titlecolor; // basic title color
				$this->base_textcolor = $item->textcolor; // basic text color
				$this->base_linkcolor = $item->linkcolor; // basic link color
				$this->base_hlinkcolor = $item->hlinkcolor; // basic hover link color
			}
		}
		// SQL query to get content connected with slides
		$query = "
		SELECT 
			`i`.`file` AS `name`, 
			`i`.`title` AS `title`, 
			`i`.`text` AS `text`, 
			`i`.`linktype` AS `linktype`, 
			`i`.`linkvalue` AS `linkvalue`, 
			`i`.`article` AS `article`, 
			`i`.`wordcount` AS `wordcount`, 
			`c`.`title` AS `ctitle`, 
			`c`.`sectionid` AS `sid`,
			`c`.`introtext` AS `introtext`, 
			`c`.`catid` AS `cid` 
		FROM 
			#__gk2_photoslide_slides AS `i` 
			LEFT JOIN 
				#__content AS `c` ON `i`.`article` = `c`.`id` 
		WHERE 
			`i`.`group_id` = ".$this->group_id." 
			AND
			`i`.`published` = 1
			AND 
			`i`.`access` <= ".(int) $aid."
		ORDER BY 
			`i`.`order` ASC 
		LIMIT ".$this->tabs_amount.";";
		// run query
		$database->setQuery($query);
		// preparing arrays
		$this->slides = array();
		$prepared_image = array();
		// if showing text block is enabled - preparing JSON wrapper
		$this->JSON = '<div class="gk_news_image_6_text_datas">';
		// loading datas
		if( $datas = $database->loadObjectList() )
		{
			// preparing data
			foreach($datas as $item)
			{
				// cleaning array with images 
				unset($prepared_image);
				// filling array with data
				$prepared_image = array(
					'name' => $item->name,
					'title' => $item->title,
					'text' => $item->text,
					'linktype' => $item->linktype,
					'linkvalue' => $item->linkvalue,
					'article' => $item->article,
					'wordcount' => $item->wordcount,
					'introtext' => $item->introtext,
					'ctitle' => $item->ctitle,
					'sid' => $item->sid,
					'cid' => $item->cid
				);
				// selecting title for items
				$slide_title = ($item->title == '') ? $item->ctitle: $item->title;
				$slide_text = ($item->text == '') ? $item->introtext: $item->text;
				$slide_textcolor = $this->base_textcolor;
				// cleaning of xhtml in content ?
				if($this->clean_xhtml == 1) $slide_text = strip_tags($slide_text);
				// limit for text ?
				if($item->wordcount != 0)
				{
					// preparing temporary string
					$str = $slide_text;
					// start position as 0
					$start_strpos = 0;
					// getting words
					for($i = 0; $i < $item->wordcount && $start_strpos < JString::strlen($str); $i++)
					{
						// searching of finish
						if(JString::strpos($str, ' ', $start_strpos) !== FALSE)
						{
							// increace start position
							$start_strpos = JString::strpos($str, ' ', $start_strpos) + 1;
						}	
					}
					// finishing text preparing
		    		$slide_text = JString::trim($str);
					$slide_text = JString::substr($slide_text, 0, $start_strpos);
					$slide_text .= "...";
				}
			 	// Switching type of link
				switch($item->linktype)
				{
					// link value
					case 0:
						$slide_link = $item->linkvalue; 
					break;
					// standard link value
					case 1: 
						$slide_link = JRoute::_(ContentHelperRoute::getArticleRoute($item->article, $item->cid, $item->sid));
					break;
					// blank link
					default: 
						$slide_link = '';
					break;
				}
				// links in titles are enabled ?
				if($this->title_link == 0)
				{
					$slide_text = '<h2 style="color:'.$this->base_titlecolor.';">'.$slide_title.'</h2><p style="color:'.$slide_textcolor.';">'.$slide_text;
				}
				else // in other situation
				{
					// preparing hover effect
					$hover_effect = ' style="color:'.$this->base_titlecolor.';" onmouseover="this.style.color = \''.$this->base_hlinkcolor.'\';" onmouseout="this.style.color = \''.$this->base_titlecolor.'\'" ';
					// and code with link and hover efect
					$slide_text = '<h2><a href="'.$slide_link.'" '.$hover_effect.' class="gk_news_image_6_title">'.$slide_title.'</a></h2><p style="color:'.$slide_textcolor.';">'.$slide_text;
				}
				// finishing slide text
				$slide_text .= '</p>';
				// filling JSON data tag
				$this->JSON .= '<div class="gk_ni_6_news_text">'.$slide_text.'</div><div class="gk_ni_6_news_link">'.$slide_link.'</div>';
				// push prepared data to slide storage
				array_push($this->slides, $prepared_image);
			}
		}
		// finishing JSON data tag
		$this->JSON .= '</div>';
	}
	
	/**
		Method for generating content
	**/
	
	function generateContent()
	{
		// create instances of basic Joomla! classes
		$document =& JFactory::getDocument(); // getting handle to document
		$uri =& JURI::getInstance(); // getting instance of JURI class
		// include file content.php and parse it
		require(JModuleHelper::getLayoutPath('mod_gk_news_image_6', 'content'));
		// add stylesheets to document header
		$document->addStyleSheet( $uri->root().'modules/mod_gk_news_image_6/css/style.css', 'text/css' );
		// init $headData variable
		$headData = false;
		// add scripts with automatic mode to document header
		if($this->useMoo == 2)
		{
			// getting module head section datas
			unset($headData);
			$headData = $document->getHeadData();
			// generate keys of script section
			$headData_keys = array_keys($headData["scripts"]);
			// set variable for false
			$mootools_founded = false;
			// searching phrase mootools in scripts paths
			for($i = 0;$i < count($headData_keys); $i++)
			{
				if(preg_match('/mootools/i', $headData_keys[$i]))
				{
					// if founded set variable to true and break loop
					$mootools_founded = true;
					break;
				}
			}
			// if mootools file doesn't exists in document head section
			if(!$mootools_founded)
			{
				// add new script tag connected with mootools from module
				$headData["scripts"][$uri->root().'modules/mod_gk_news_image_6/js/mootools.js'] = "text/javascript";
				// if added mootools from module then this operation have sense
				$document->setHeadData($headData);
			}
		}
		// if automatic including of JavaScript engine is enabled
		if($this->useScript == 2){
			// getting module head section datas
			unset($headData);
			$headData = $document->getHeadData();
			// generate keys of script section
			$headData_keys = array_keys($headData["scripts"]);
			// set variable for false
			$engine_founded = false;
			// searching phrase mootools in scripts paths
			if(array_search($uri->root().'modules/mod_gk_news_image_6/js/engine'.(($this->compress_js == 1) ? '_compressed' : '').'.js', $headData_keys) > 0)
			{
				// if founded set variable to true
				$engine_founded = true;
			}
			// if mootools file doesn't exists in document head section
			if(!$engine_founded)
			{
				// add new script tag connected with mootools from module
				$headData["scripts"][$uri->root().'modules/mod_gk_news_image_6/js/engine'.(($this->compress_js == 1) ? '_compressed' : '').'.js'] = "text/javascript";
				// if added mootools from module then this operation have sense
				$document->setHeadData($headData);
			}
		}
		// if clean code is enable use importer.php to include 
		// module settings in head section of document
		if($this->clean_code)
		{
			// add script tag with module configuration to document head section	
			// get head document section data 
			unset($headData);
			$headData = $document->getHeadData();
			// add new script tag to head document section data array	
			$headData["scripts"][$uri->root().'modules/mod_gk_news_image_6/js/importer.php?mid='.$this->module_id.'&amp;animation_slide_speed='.$this->animation_slide_speed.'&amp;animation_interval='.$this->animation_interval.'&amp;autoanimation='.$this->autoanimation.'&amp;animation_slide_type='.$this->animation_slide_type.'&amp;animation_text_type='.$this->animation_text_type.'&amp;thumbnail_width='.$this->thumbnail_width.'&amp;thumbnail_height='.$this->thumbnail_height.'&amp;tabs_amount='.$this->tabs_amount.'&amp;base_bgcolor='.str_replace('#','',$this->base_bgcolor).'&amp;text_block_opacity='.$this->text_block_opacity] = "text/javascript";
			// if added mootools from module then this operation have sense
			$document->setHeadData($headData);
		} 				
		// add default.php template to parse if it's necessary
		if($this->useMoo != 2 || $this->useScript != 2 || !$this->clean_code)
		{
			require(JModuleHelper::getLayoutPath('mod_gk_news_image_6', 'default'));
		}
	}
}

?>