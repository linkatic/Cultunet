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
	'PHPSHOP_ADMIN_CFG_PRICES_INCLUDE_TAX' => 'Mostrar precios con IVA?',
	'PHPSHOP_ADMIN_CFG_PRICES_INCLUDE_TAX_EXPLAIN' => 'Si está marcado, los precios serán mostrados con su IVA correspondiente.',
	'PHPSHOP_SHOPPER_FORM_ADDRESS_LABEL' => 'Dirección 2',
	'PHPSHOP_SHOPPER_GROUP_LIST_LBL' => 'Lista de Grupo de Compradores',
	'PHPSHOP_SHOPPER_GROUP_LIST_NAME' => 'Nombre',
	'PHPSHOP_SHOPPER_GROUP_LIST_DESCRIPTION' => 'Descripción',
	'PHPSHOP_SHOPPER_GROUP_FORM_LBL' => 'Formulario de Grupo de Compradores',
	'PHPSHOP_SHOPPER_GROUP_FORM_NAME' => 'Nombre',
	'PHPSHOP_SHOPPER_GROUP_FORM_DESC' => 'Descripción',
	'PHPSHOP_SHOPPER_GROUP_FORM_DISCOUNT' => 'Precio con descuento por defecto en grupo de compradores(en %)',
	'PHPSHOP_SHOPPER_GROUP_FORM_DISCOUNT_TIP' => 'Una cantidad positiva X significa: Si el producto no tiene ningun precio asignado a ESTE Grupo de Comprador, el precio por defecto disminuirá por X %. Una cantidad negativa tiene el efecto opuesto'
); $VM_LANG->initModule( 'shopper', $langvars );
?>