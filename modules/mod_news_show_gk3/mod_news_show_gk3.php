<?php

/**
* Gavick News Show GK3 - main file
* @package Joomla!
* @Copyright (C) 2008-2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 3.2 $
**/

/**
	access restriction
**/
defined('_JEXEC') or die('Restricted access');

/**
	Loading helper class
**/

require_once (dirname(__FILE__).DS.'helper.php');
require_once (dirname(__FILE__).DS.'date.class.php');

$helper =& new GK3NewsShowHelper();

$helper->validateVariables($params);
$helper->getDatas();
$helper->renderLayout();

?>