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
	'PHPSHOP_ACC_CUSTOMER_ACCOUNT' => 'Cuenta de Cliente:',
	'PHPSHOP_ACC_UPD_BILL' => 'Aquí puede revisar y actualizar sus datos personales.',
	'PHPSHOP_ACC_UPD_SHIP' => 'Aquí puede añadir direcciones para el envío.',
	'PHPSHOP_ACC_ACCOUNT_INFO' => 'Información de Cuenta',
	'PHPSHOP_ACC_SHIP_INFO' => 'Información de Envío',
	'PHPSHOP_DOWNLOADS_CLICK' => 'Haga clic en el nombre del producto para descargar archivo(s).',
	'PHPSHOP_DOWNLOADS_EXPIRED' => 'Ha descargado el archivo más veces de lo permitido, o bien, el tiempo de descarga ha caducado.'
); $VM_LANG->initModule( 'account', $langvars );
?>