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
// include helper file
require_once (dirname(__FILE__).DS.'helper.php');
// creating new instance of helper class
$helper = new GKImageShowHelper();
// initialize module variables
$helper->initialize($params);
// get module datas
$helper->getDatas();
// generate module content
$helper->generateContent();	

?>