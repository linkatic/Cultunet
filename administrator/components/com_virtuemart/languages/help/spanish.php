<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version : spanish.php 1071 2007-12-03 08:42:28Z thepisu $
* @package VirtueMart
* @subpackage languages
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @translator soeren
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
global $VM_LANG;
$langvars = array (
	'CHARSET' => 'ISO-8859-1',
	'VM_HELP_YOURVERSION' => 'Tu {product} version',
	'VM_HELP_ABOUT' => '<span style="font-weight: bold;">
		VirtueMart</span> es la solución completa Open Source de comercio electrónico para Mambo and Joomla!. 
		Es una aplicación, que incluye un componente y más de 8 módulos y Mambots/Plugins.
		Está basado en un script de tienda virtual llamado "phpShop" (Autores: Edikon Corp. & the <a href="http://www.virtuemart.org/" target="_blank">phpShop</a> community).
		Traducción al Castellano: <a href="mailto:jpdeluxe@telefonica.net" target="_blank">Juan Alvarez</a>',
	'VM_HELP_LICENSE_DESC' => 'VirtueMart is licensed under the <a href="{licenseurl}" target="_blank">{licensename} License</a>.',
	'VM_HELP_TEAM' => 'Existe un equipo pequeño de desarrolladores implicados en este Shopping Cart Script.',
	'VM_HELP_PROJECTLEADER' => 'Líder de proyecto',
	'VM_HELP_HOMEPAGE' => 'Página principal',
	'VM_HELP_DONATION_DESC' => 'Please consider a small donation to the VirtueMart Project to help us keep up the work on this Component and create new Features.',
	'VM_HELP_DONATION_BUTTON_ALT' => 'Make payments with PayPal - it\'s fast, free and secure!'
); $VM_LANG->initModule( 'help', $langvars );
?>