<?php

/**
* Gavick Image Show
* @package Joomla!
* @Copyright (C) 2008-2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.0 $
**/

// access restriction
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class GKImageShowHelper{
	// Module parameters
	var $module_id;
	var $group_id;
	var $style;
	var $variation;
	var $settings;
	var $useMoo;
	var $useScript;
	var $compress_js;
	// Basic objects
	var $uri;
	var $base_path;
	// Storage
	var $group_settings;
	var $slide_data;
	
	/**
		Initializing Class variables
	**/
	
	function initialize(&$params)
	{
		// Base settings
		$this->module_id = $params->get('module_id', '-mod');//
		$this->group_id = $params->get('group_id', 0);//
		$this->style = $params->get('style', 'style1');//
		$this->variation = $params->get('variation', 'style1');//
		$this->settings = $params->get('settings', '');//
		// Scripts configuration
		$this->useMoo = $params->get('useMoo', 2);
		$this->useScript = $params->get('useScript', 2);
		$this->compress_js = $params->get('compress_js', 1);
		// Basic objects
		$this->uri =& JURI::getInstance();
		$this->base_path = $this->uri->root().'components/com_gk3_photoslide/images';
		// Storage
		$this->group_settings = array(
			"thumb_x" => 0,
			"thumb_y" => 0,
			"image_x" => 0,
			"image_y" => 0 
		);
		// informations about slides
		$this->slide_data = array();
	}

	/**
		Getting base configuration of group
	**/
	
	function getDatas()
	{
		// get SQL query
		$query = "
		SELECT 
			* 
		FROM 
			#__gk3_photoslide_groups 
		WHERE 
			`id` = ".$this->group_id." 
		LIMIT 1;
		";
		$database = & JFactory::getDBO();
		$user =& JFactory::getUser();
		$aid = $user->get('aid', 0);
		//
		$uri = JURI::getInstance();
		$database->setQuery($query);
		//
		if( $dane = $database->loadObjectList() )
		{
			foreach($dane as $item)
			{
				$this->group_settings["thumb_x"] = $item->thumb_x;
				$this->group_settings["thumb_y"] = $item->thumb_y;
				$this->group_settings["image_x"] = $item->image_x;
				$this->group_settings["image_y"] = $item->image_y;
			}
		}

		/**
			Getting informations about images
		**/

		// SQL query for slides
		$query = '
		SELECT 
			`c`.`sectionid` AS `sid`,
			`c`.`catid` AS `cid`,
			`c`.`title` AS `ctitle`, 
			`c`.`introtext` AS `introtext`,
			`i`.`name` AS `name`, 
			`i`.`filename` AS `filename`, 
			`i`.`article` AS `article`, 
			`i`.`title` AS `title`,
			`i`.`link_type` AS `link_type`, 
			`i`.`link` AS `link`, 
			`i`.`content` AS `content`
		FROM 
			#__gk3_photoslide_slides AS `i` 
		LEFT JOIN 
			#__content AS `c` 
			ON 
			`i`.`article` = `c`.`id` 
		WHERE 
			`i`.`group_id` = '.$this->group_id.'
			AND
			`i`.`published` = 1
			AND 
			`i`.`access` <= '.(int) $aid.'
		ORDER BY 
			`i`.`order`,
			`i`.`access` ASC;
		';
		// running query
		$database->setQuery($query);
		// if results exists
		if( $datas = $database->loadObjectList() )
		{
			// parsing data
			foreach($datas as $item)
			{
				// cleaning array
				unset($prepared_image);
				// array with prepared image
				$prepared_image = array(
					'sid' => $item->sid,
					'cid' => $item->cid,
					'ctitle' => $item->ctitle,
					'introtext' => $item->introtext,
					'name' => $item->name,
					'filename' => $item->filename,
					'article' => $item->article,
					'title' => $item->title,
					'link_type' => $item->link_type,
					'link' => $item->link,
					'content' => $item->content
				);
				//JRoute::_(ContentHelperRoute::getArticleRoute($item->article, $item->cid, $item->sid)) : $item->linkvalue;
				array_push($this->slide_data, $prepared_image);
			}
		}
	}
	
	/**
		Generating content
	**/
	
	function generateContent()
	{		
		if($this->style != 'template')
		{
			// create instances of basic Joomla! classes
			$document =& JFactory::getDocument();
			$uri =& JURI::getInstance();
			//
			$document->addStyleSheet( $uri->root().'modules/mod_gk_image_show/css/'.$this->style.'/'.$this->variation.'.css', 'text/css' );
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
					$headData["scripts"][$uri->root().'modules/mod_gk_image_show/js/mootools.js'] = "text/javascript";
					// if added mootools from module then this operation have sense
					$document->setHeadData($headData);
				}
			}
			//
			if($this->useScript == 2)
			{
				// getting module head section datas
				unset($headData);
				$headData = $document->getHeadData();
				// generate keys of script section
				$headData_keys = array_keys($headData["scripts"]);
				// set variable for false
				$engine_founded = false;
				// searching phrase mootools in scripts paths
				if(array_search($uri->root().'modules/mod_gk_image_show/js/'.$this->style.'/engine'.(($this->compress_js == 1) ? '_compress' : '').'.js', $headData_keys) > 0)
				{
					// if founded set variable to true
					$engine_founded = true;
				}
				// if mootools file doesn't exists in document head section
				if(!$engine_founded)
				{
					// add new script tag connected with mootools from module
					$headData["scripts"][$uri->root().'modules/mod_gk_image_show/js/'.$this->style.'/engine'.(($this->compress_js == 1) ? '_compress' : '').'.js'] = "text/javascript";
					// if added mootools from module then this operation have sense
					$document->setHeadData($headData);
				}
			}
			// include style file and parse it
			require(dirname(__FILE__).DS.'tmpl'.DS.$this->style.DS.'tmpl'.DS.$this->style.'.php');
		}	
		else
		{
			// include style file and parse it	
			require(JModuleHelper::getLayoutPath('mod_gk_image_show', 'template'));			
		}			
	}
}

?>