<?php

/**
 * DioneSoft Company
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the DioneSoft EULA that is bundled with
 * this package in the file GPL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/copyleft/gpl.html
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@dionesoft.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.dionesoft.com/ for more information
 * or send an email to sales@dionesoft.com
 *
 * @category   DioneSoft
 * @package    Dione Magic Calendar
* @copyright Copyright (C) 2010 DioneSoft Company (http://www.dionesoft.com)
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
 
class plgContentDioneMagicCalendar extends JPlugin
{
	function onPrepareContent( &$article, &$params, $page=0)
	{
		global $mainframe;
		
		// A database connection is created
        $db = JFactory::getDBO();		
		$view  = JRequest::getCmd('view');
		
		if(!$page) {
			$page = 0;
		}		
		
		// get current document
		$document =& JFactory::getDocument();		
		
		// Get Plugin info
		$plugin			=& JPluginHelper::getPlugin('content', 'dionemagiccalendar');
		$pluginParams	= new JParameter( $plugin->params );
		
		// simple performance check to determine whether bot should process further
		if ( JString::strpos( $article->text, 'dionemagiccalendar' ) === false ) {
			return true;
		}
		
		$plugin_block_regex = '/({dionemagiccalendar.*?}.*?{\/dionemagiccalendar})/s';
		
		$begin_block_regex = '/{dionemagiccalendar\s+id="([^"]+)?"\s+parameters="([^"]+)?"}/'; # pattern match the regex like {dionemagiccalendar id="..." parameters="..."}
		$end_block_regex = '/{\/dionemagiccalendar}/';
		
		if ( !$pluginParams->get( 'enabled', 1 ) ) {
			$article->text = preg_replace( $plugin_block_regex, '<br />', $article->text );
			return true;
		}		
		
		$plugin_text_matches = array();
		
		preg_match_all( $plugin_block_regex, $article->text, $plugin_text_matches );
		
		// Number of plugins
		$count = count( $plugin_text_matches[0] );				
			
		if ( $count ) {
			# parameters from the Back-End
			$lang_parameter     			= $this->params->get('lang');
			$showOn_parameter     			= $this->params->get('showOn');
			$appendText_parameter     		= $this->params->get('appendText');
			$buttonText_parameter     		= $this->params->get('buttonText');
			$buttonImage_parameter     		= $this->params->get('buttonImage');
			$buttonImageOnly_parameter  	= $this->params->get('buttonImageOnly');
			$changeMonth_parameter     		= $this->params->get('changeMonth');
			$changeYear_parameter     		= $this->params->get('changeYear');
			$yearRange_parameter     		= $this->params->get('yearRange');
			$showOtherMonths_parameter  	= $this->params->get('showOtherMonths');
			$selectOtherMonths_parameter   	= $this->params->get('selectOtherMonths');
			$showWeek_parameter     		= $this->params->get('showWeek');
			$shortYearCutoff_parameter     	= $this->params->get('shortYearCutoff');
			$numberOfMonths_parameter     	= $this->params->get('numberOfMonths');
			$showCurrentAtPos_parameter     = $this->params->get('showCurrentAtPos');
			$stepMonths_parameter     		= $this->params->get('stepMonths');
			$stepBigMonths_parameter    	= $this->params->get('stepBigMonths');
			$autoSize_parameter     		= $this->params->get('autoSize');
			
			# add the declaration for the JQuery Library and Library
			$document->addScript("plugins/content/dionemagiccalendar/js/jquery-1.4.2.min.js");
			
			if ($lang_parameter == "en"){
				$document->addScript("plugins/content/dionemagiccalendar/js/jquery.ui.datepicker.js");
			}
			elseif($lang_parameter == "de"){
				$document->addScript("plugins/content/dionemagiccalendar/js/jquery.ui.datepicker-de.js");
			}
			elseif($lang_parameter == "fr"){
				$document->addScript("plugins/content/dionemagiccalendar/js/jquery.ui.datepicker-fr.js");
			}
			elseif($lang_parameter == "it"){
				$document->addScript("plugins/content/dionemagiccalendar/js/jquery.ui.datepicker-it.js");
			}
			
			$document->addScript("plugins/content/dionemagiccalendar/js/jquery-ui-1.8.custom.min.js");
			
			# add the declaration for the CSS files
			$document->addStyleSheet("plugins/content/dionemagiccalendar/css/jquery-ui-1.8.custom.css");
			$document->addStyleSheet("plugins/content/dionemagiccalendar/css/jquery.ui.datepicker.css");
			
			# begin of the JS-code's defenition
			$script = 'jQueryDMC = jQuery.noConflict();';
			$script .= 'jQueryDMC(document).ready(function(){';
			
			for ( $i = 0; $i < $count; $i++ ){
				$plugin_id = -1;
				$plugin_text = $plugin_text_matches[0][$i];
				
				if (preg_match( $begin_block_regex, $plugin_text)){
					$head_matches = array();
					preg_match( $begin_block_regex, $plugin_text, $head_matches);
					
					$plugin_tag = $head_matches[0];
					$plugin_id = $head_matches[1];
					
					$plugin_params = "";
					if (isset($head_matches[2])){
						$plugin_params = $head_matches[2];
					}
					
					if ($plugin_params != ""){
						// Parse of input params from the template
						$params_matches = preg_split( "/,/", $plugin_params);
						
						for ( $j = 0; $j < count( $params_matches); $j++ ){
							$params_parts_matches = preg_split( "/:/", $params_matches[$j]);
							
							$param_name = $params_parts_matches[0];
							$param_value = $params_parts_matches[1];
							
							$param_name = trim($param_name);
							$param_value = trim($param_value);
							
							if ($param_name == "showOn"){
								$param_value = str_replace("'", "", $param_value);
								$showOn_parameter = $param_value;
							}
							
							if ($param_name == "appendText"){
								$param_value = str_replace("'", "", $param_value);
								$appendText_parameter = $param_value;
							}
							
							if ($param_name == "buttonText"){
								$param_value = str_replace("'", "", $param_value);
								$buttonText_parameter = $param_value;
							}
							
							if ($param_name == "buttonImage"){
								$param_value = str_replace("'", "", $param_value);
								$buttonImage_parameter = $param_value;
							}
							
							if ($param_name == "buttonImageOnly"){
								$param_value = str_replace("'", "", $param_value);
								$buttonImageOnly_parameter = $param_value;
							}
							
							if ($param_name == "changeMonth"){
								$param_value = str_replace("'", "", $param_value);
								$changeMonth_parameter = $param_value;
							}
							
							if ($param_name == "changeYear"){
								$param_value = str_replace("'", "", $param_value);
								$changeYear_parameter = $param_value;
							}
							
							if ($param_name == "yearRange"){
								$param_value = str_replace("'", "", $param_value);
								$yearRange_parameter = $param_value;
							}
							
							if ($param_name == "showOtherMonths"){
								$param_value = str_replace("'", "", $param_value);
								$showOtherMonths_parameter = $param_value;
							}
							
							if ($param_name == "selectOtherMonths"){
								$param_value = str_replace("'", "", $param_value);
								$selectOtherMonths_parameter = $param_value;
							}
							
							if ($param_name == "showWeek"){
								$param_value = str_replace("'", "", $param_value);
								$showWeek_parameter = $param_value;
							}
							
							if ($param_name == "shortYearCutoff"){
								$param_value = str_replace("'", "", $param_value);
								$shortYearCutoff_parameter = $param_value;
							}
							
							if ($param_name == "numberOfMonths"){
								$param_value = str_replace("'", "", $param_value);
								$numberOfMonths_parameter = $param_value;
							}
							
							if ($param_name == "showCurrentAtPos"){
								$param_value = str_replace("'", "", $param_value);
								$showCurrentAtPos_parameter = $param_value;
							}
							
							if ($param_name == "stepMonths"){
								$param_value = str_replace("'", "", $param_value);
								$stepMonths_parameter = $param_value;
							}
							
							if ($param_name == "stepBigMonths"){
								$param_value = str_replace("'", "", $param_value);
								$stepBigMonths_parameter = $param_value;
							}
							
							if ($param_name == "autoSize"){
								$param_value = str_replace("'", "", $param_value);
								$autoSize_parameter = $param_value;
							}
						}
					}
				}
				
				if (preg_match( $end_block_regex, $plugin_text)){
					$replaced_text = "<input type=\"text\" id=\"magiccalendar_".$plugin_id."\">";
					
					$script .= 'jQueryDMC("#magiccalendar_'.$plugin_id.'").datepicker({';
					$script .= '	showOn: \''.$showOn_parameter.'\',';
					$script .= '	appendText: \''.$appendText_parameter.'\',';
					$script .= '	buttonText: \''.$buttonText_parameter.'\',';
					$script .= '	buttonImage: \''.$buttonImage_parameter.'\',';
					$script .= '	buttonImageOnly: '.$buttonImageOnly_parameter.',';
					$script .= '	changeMonth: '.$changeMonth_parameter.',';
					$script .= '	changeYear: '.$changeYear_parameter.',';
					$script .= '	yearRange: \''.$yearRange_parameter.'\',';
					$script .= '	showOtherMonths: '.$showOtherMonths_parameter.',';
					$script .= '	selectOtherMonths: '.$selectOtherMonths_parameter.',';
					$script .= '	showWeek: '.$showWeek_parameter.',';
					$script .= '	shortYearCutoff: \''.$shortYearCutoff_parameter.'\',';
					$script .= '	numberOfMonths: '.$numberOfMonths_parameter.',';
					$script .= '	showCurrentAtPos: '.$showCurrentAtPos_parameter.',';
					$script .= '	stepMonths: '.$stepMonths_parameter.',';
					$script .= '	stepBigMonths: '.$stepBigMonths_parameter.',';
					$script .= '	autoSize: '.$autoSize_parameter.'';
					$script .= '});';
					
					$article->text = str_replace( $plugin_text, $replaced_text, $article->text );					
				}
			}
			
			# end of the JS-code's defenition
			$script .= '});';
			$document->addScriptDeclaration($script);			
		}
		
		return true;
	}
}