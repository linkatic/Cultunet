<?PHP
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

	function remember_cid($rcid=null)
	{
		if($rcid==null)
			{
				$remember_cid =  mosGetParam($_REQUEST, 'remember_cid');
				if(!$remember_cid) $remember_cid=-2;
				define('M4J_REMEMBER_CID_QUERY','&remember_cid='.$remember_cid);
				return $remember_cid;		
			}
		else 
		{
				define('M4J_REMEMBER_CID_QUERY','&remember_cid='.$rcid);
				return $rcid;
		}	
	}//EOF remember_cid




?>