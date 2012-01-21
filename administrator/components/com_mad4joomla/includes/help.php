<?php
	/**
	* @version $Id: mad4joomla 10041 2008-03-18 21:48:13Z fahrettinkutyol $
	* @package joomla
	* @copyright Copyright (C) 2008 Mad4Media. All rights reserved.
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
	* Joomla! is free software. This version may have been modified pursuant
	* to the GNU General Public License, and as distributed it includes or
	* is derivative of works licensed under the GNU General Public License or
	* other free or open source software licenses.
	* See COPYRIGHT.php for copyright notices and details.
	* @copyright (C) mad4media , www.mad4media.de
	*/

    defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
	remember_cid();

 
	
  HTML_m4j::head(M4J_HELP);
   
 
	$banner = M4J_IMAGES."proforms-add-banner-en.png";
	 if($mosConfig_lang =="german" || $mosConfig_lang =="germani" || $mosConfig_lang =="germanf" ){
	 	$banner = M4J_IMAGES."proforms-add-banner-de.png";
	 }	  
	
	 echo '<a href="http://www.mooj.org" target="_blank" ><img border="0" src="'.$banner.'"></img></a>';
	
 $helpers->caption(M4J_LANG_INFO_HELP); 
 if(file_exists(M4J_LANG.$mosConfig_lang.'/info.php')) include_once(M4J_LANG.$mosConfig_lang.'/info.php');
	else include_once(M4J_LANG.'english/info.php');


  HTML_m4j::footer();

?>
