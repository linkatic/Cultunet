<?php

/**
* Gavick Image Show - Style 2
* @package Joomla!
* @Copyright (C) 2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.0 $
**/

// access restriction
defined('_JEXEC') or die('Restricted access');

require(JModuleHelper::getLayoutPath('mod_gk_image_show', 'style2'.DS.'tmpl'.DS.'class'));

////////////////////////////////////////////////////////////
//
// Generating module code
//
////////////////////////////////////////////////////////////

$newModuleObject = new GKImageShowStyle2( 
							$this->module_id, 
							$this->settings,
							$this->base_path, 
							$this->group_settings, 
							$this->slide_data 
						); 

$dataForJSEngine = $newModuleObject->returnJSData();

require(JModuleHelper::getLayoutPath('mod_gk_image_show', 'style2'.DS.'tmpl'.DS.'default'));

?>