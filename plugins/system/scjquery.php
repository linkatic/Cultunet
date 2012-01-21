<?php
/**
 * @version		1.0.0
 * @package		SC jQuery
 * @author 		Phil Snell
 * @author mail	phil@snellcode.com
 * @link		http://snellcode.com
 * @copyright	Copyright (C) 2010 Phil Snell - All rights reserved.
 * @license		GNU/GPL
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.plugin.plugin' );

/**
 * Example system plugin
 */
class plgSystemSCjQuery extends JPlugin
{
    /**
    * Constructor
    *
	* For php4 compatability we must not use the __constructor as a constructor for plugins
	* because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	* This causes problems with cross-referencing necessary for the observer design pattern.
	*
	* @access      protected
	* @paramobject  $subject The object to observe
	* @paramarray   $config  An array that holds the plugin configuration
	* @since1.0
	*/
	function plgSystemSCjQuery( &$subject, $config )
	{
		parent::__construct( $subject, $config );
		// Do some extra initialisation in this constructor if required
	}

	/**
	* load jQuery and jQuery UI in no conflict mode.
	*/
	function onAfterRoute()
	{
		$doc =& JFactory::getDocument();
		$app =& JFactory::getApplication();
		$Itemid = JRequest::getInt('Itemid');
		$uri =& JURI::getInstance();
		$scheme = $uri->getScheme();
				
		$enable_site = $this->params->get('enable_site', 1);
		$enable_admin = $this->params->get('enable_admin', 0);
		$enable_ui = $this->params->get('enable_ui', 0);
		$theme_ui = $this->params->get('theme_ui', 'ui-lightness');
		$code = $this->params->get('code', null);
		$exclude_menuitems = (array) $this->params->get('exclude_menuitems', null);
		$version_jq = $this->params->get('version_jq', '1.4.2');
		$version_ui = $this->params->get('version_ui', '1.8.1');
		
		if ($app->isSite() && $enable_site == 0) {
			return false;
		}

		if ($app->isAdmin() && $enable_admin == 0) {
			return false;
		}		
		
		if (!empty($exclude_menuitems) && $Itemid != 0) {
			if (in_array($Itemid, $exclude_menuitems)) {
				return false;
			}
		}
		
		$url_jq = $scheme.'://ajax.googleapis.com/ajax/libs/jquery/'.$version_jq.'/jquery.min.js';
		$url_ui = $scheme.'://ajax.googleapis.com/ajax/libs/jqueryui/'.$version_ui.'/jquery-ui.min.js';
		$url_theme_ui = $scheme.'://ajax.googleapis.com/ajax/libs/jqueryui/'.$version_ui.'/themes/'.$theme_ui.'/jquery-ui.css';
		$url_no_conflict = JURI::root().'plugins/system/scjquery/noconflict.js';
		
		$headData = $doc->getHeadData();
		@$headData['scripts'][$url_jq] = 'text/javascript';
		@$headData['scripts'][$url_no_conflict] = 'text/javascript';
		
		if ($enable_ui) {
			@$headData['scripts'][$url_ui] = 'text/javascript';
			@$headData['styleSheets'][$url_theme_ui] = array (
				'mime' => 'text/css'
				,'media' => null
				,'attribs' => array()
			);	
		}
		
		if ($code) {
			$code_formatted = "jQuery(function($) {\n"
			 . $code . "\n"
			 . "});\n"
			;
			@$headData['script']['text/javascript'] = $headData['script']['text/javascript'].$code_formatted;
		}
		
		$doc->setHeadData($headData);
		
		return true;
	}

	
}
