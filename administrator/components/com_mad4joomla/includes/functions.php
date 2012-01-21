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
	
	function parameters($string){
	
		$p_array = null;
		$chopped = explode(';',$string);
		foreach($chopped as $atom)
			{
			$split = explode('=',$atom);
			if (sizeof($split)==2)
				$p_array[$split[0]]= $split[1];		
			}
		
		return $p_array;
	}

	function make_param($key)
		{
		$value = mosGetParam( $_REQUEST,$key);
		if($value!=null)
		return $key.'='.$value.';';
		else return $key.'=;';
		}

  function menu_parameters($string){
	
		$p_array = null;
		$chopped = explode('\n',$string);
		foreach($chopped as $atom)
			{
			$split = explode('=',$atom);
			if (sizeof($split)==2)
				$p_array[$split[0]]= $split[1];		
			}
		
		return $p_array;
	}
?>