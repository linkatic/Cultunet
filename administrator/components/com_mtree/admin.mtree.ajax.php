<?php
/**
 * @version		$Id: admin.mtree.ajax.php 652 2009-04-15 14:26:05Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

$parent_cat_id	= JRequest::getInt('parent_cat_id', 0);
$task2			= JRequest::getCmd('task2', '');

switch($task2){
	case 'spiderurl':
		spiderurl($option);
		break;
	case 'checklinkcomplete':
		checklinkcomplete();
		break;
		
	case 'checklink':
		checklink();
		break;
		
	case 'getcats':
		# Get pathway
		$mtPathWay = new mtPathWay($parent_cat_id);
		$return = $mtPathWay->printPathWayFromCat_withCurrentCat($parent_cat_id,0);
		$return .= "\n";
		
		$database->setQuery( 'SELECT cat_id, cat_name FROM #__mt_cats WHERE cat_parent = ' . $database->quote($parent_cat_id) . ' ORDER BY cat_name ASC' );
		$cats = $database->loadObjectList();
		if($parent_cat_id > 0) {
			$database->setQuery( 'SELECT cat_parent FROM #__mt_cats WHERE cat_id = ' . $database->quote($parent_cat_id) . ' LIMIT 1');
			$browse_cat_parent = $database->loadResult();
			$return .= $browse_cat_parent . "|" . JText::_( 'Arrow back' );
			if(count($cats)>0) {
				$return .= "\n";
			}
		} else {
			//
		}
		if(count($cats)>0) {
			foreach( $cats as $key => $cat )
			{
				$return .= $cat->cat_id . '|' . $cat->cat_name;
				if($key<(count($cats)-1)) {
					$return .=  "\n";
				}
			}
		}
		echo $return;
		break;
}
function spiderurl( $option ) {
	global $database;

	$url	= JRequest::getVar( 'url', '');
	$start	= JRequest::getVar( 'start', 0);
	
	$error = 0;

	if ( substr($url, 0, 7) <> "http://" ) {
		$url = "http://".$url;
	}
	
	if ( empty($url) || $start) {
		echo "jQuery('#spiderwebsite').html('<img src=\"../components/com_mtree/img/exclamation.png\" style=\"position:relative;top:3px\" /> " . JText::_( 'Unable to get metatags' ) . "')";
	} else {
		if(ini_get('allow_url_fopen')) {
			$metatags = get_meta_tags( $url ) or $error = 1;
		} else {
			$error = 1;
		}
		if ( !$error ) {
			if ( !empty($metatags['keywords']) ) {
				echo "document.getElementById('publishingmetakey').value='".htmlspecialchars($metatags['keywords'], ENT_QUOTES )."'; \n";
			}
			if ( !empty($metatags['description']) ) {
				echo "document.getElementById('publishingmetadesc').value='".htmlspecialchars($metatags['description'], ENT_QUOTES )."';";
			}
			echo "jQuery('#spiderwebsite').html('<img src=\"../components/com_mtree/img/accept.png\" style=\"position:relative;top:3px\" /> " . JText::_( 'Spider has been updated' ) . "')";
		} else {
			echo "jQuery('#spiderwebsite').html('<img src=\"../components/com_mtree/img/exclamation.png\" style=\"position:relative;top:3px\" /> " . JText::_( 'Unable to get metatags' ) . "')";
		}
	}
}

function checklink(){
	global $database;
	
	$database->setQuery( 'SELECT id, link_id, field, link_name, domain, path FROM #__mt_linkcheck WHERE check_status = \'0\' LIMIT 1');
	$link = $database->loadObject();

	$database->setQuery( 'UPDATE #__mt_linkcheck SET check_status=1 WHERE id = ' . $database->quote($link->id) . ' LIMIT 1' );
	$database->query();

	if( count($link) == 1 ) {
		$output = $link->id;
		$output .= '|'.$link->link_id;
		$output .= '|'.$link->field;
		$output .= '|'.$link->link_name;
		$output .= '|'.$link->domain;
		$output .= '|'.$link->path;

		$fp = @fsockopen($link->domain, 80, $errno, $errstr, 5);
		if (!$fp) {
		  // $output .= "Server unreachable: $errstr ($errno)";
		 	$output .= "HTTP/1.1 Unable to connect to the server";
			$database->setQuery( 'UPDATE #__mt_linkcheck SET check_status= \'-1\' WHERE id = ' . $database->quote($link->id) . ' LIMIT 1' );
			$database->query();
		
		} else {
			$request = "HEAD ".$link->path." HTTP/1.1\r\n";
			$request .= "Host: ".$link->domain."\r\n";
			$request .= "Connection: close\r\n";
			$request .= "Accept-Encoding: gzip\r\n";
			$request .= "Accept-Charset: iso-8859-1, utf-8, utf-16, *;q=0.1\r\n";
			$request .= "\r\n";
			fwrite($fp, $request);
			
			$response = fgets($fp, 256);
			$output .= '|'.trim($response);
			$output .= '|'.trim($response);
			while (!feof($fp)) {
				 $output .= "\n".trim(fgets($fp, 256));
			}
			$response = explode(' ',$response);
			
			//}
			fclose($fp);
			$database->setQuery( 'UPDATE #__mt_linkcheck SET check_status=2, status_code=' . $database->quote($response[1]) . ' WHERE id = ' . $database->quote($link->id) . ' LIMIT 1' );
			$database->query();
		}
		echo $output;
	}
}

function checklinkcomplete() {
	global $database, $mtconf;
	
	$jdate		= JFactory::getDate();
	$now		= $jdate->toMySQL();
	
	$database->setQuery('UPDATE #__mt_config SET value = '.$database->Quote($now).' WHERE varname = \'linkchecker_last_checked\' LIMIT 1');
	$database->query();
}
?>