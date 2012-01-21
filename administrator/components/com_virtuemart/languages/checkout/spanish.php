<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @package VirtueMart
* @subpackage languages
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
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
	'PHPSHOP_NO_CUSTOMER' => 'Lo sentimos, pero usted no es un cliente registrado.<br>
                                    Por favor, regístrese en nuestra tienda.<br>
                                    Gracias.',
	'PHPSHOP_THANKYOU' => 'Gracias por su pedido.',
	'PHPSHOP_EMAIL_SENDTO' => 'Un correo de confirmacion le ha sido enviado a',
	'PHPSHOP_CHECKOUT_NEXT' => 'Próximo',
	'PHPSHOP_CHECKOUT_CONF_BILLINFO' => 'Información de Factura',
	'PHPSHOP_CHECKOUT_CONF_COMPANY' => 'Razón social',
	'PHPSHOP_CHECKOUT_CONF_NAME' => 'Nombre',
	'PHPSHOP_CHECKOUT_CONF_ADDRESS' => 'Dirección',
	'PHPSHOP_CHECKOUT_CONF_EMAIL' => 'Correo Electrónico',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO' => 'Información del Envío',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO_COMPANY' => 'Razón social',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO_NAME' => 'Nombre',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO_ADDRESS' => 'Dirección',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO_PHONE' => 'Teléfono',
	'PHPSHOP_CHECKOUT_CONF_SHIPINFO_FAX' => 'Fax',
	'PHPSHOP_CHECKOUT_CONF_PAYINFO_METHOD' => 'Forma de Pago',
	'PHPSHOP_CHECKOUT_CONF_PAYINFO_REQINFO' => 'Información requerida cuando Pago vía Tarjeta de Crédito es seleccionada',
	'PHPSHOP_PAYPAL_THANKYOU' => 'Gracias por su pago. La transacción ha sido aceptada.  Recibirá un E-mail de confirmación para la transacción de PayPal.
        ahora puede continuar o ingresar a  <a href=http://www.paypal.com>www.paypal.com</a> para ver el detalle de la transacción.',
	'PHPSHOP_PAYPAL_ERROR' => 'Ha ocurrido un error durante su proceso de transacción. No se ha podido actualizar su pedido.',
	'PHPSHOP_THANKYOU_SUCCESS' => 'Su pedido se ha enviado correctamente, Gracias',
	'VM_CHECKOUT_TITLE_TAG' => 'Proceso de compra: Paso %s de %s',
	'VM_CHECKOUT_ORDERIDNOTSET' => 'ID de pedido no ha sido creada o está vacía!',
	'VM_CHECKOUT_FAILURE' => 'Fallido',
	'VM_CHECKOUT_SUCCESS' => 'Éxito',
	'VM_CHECKOUT_PAGE_GATEWAY_EXPLAIN_1' => 'Esta página se encuentra alojada en la web de la tienda virtual.',
	'VM_CHECKOUT_PAGE_GATEWAY_EXPLAIN_2' => 'La puerta de enlace ejecuta la página en el sitio web, y muestra los resultados encriptados mediante SSL.',
	'VM_CHECKOUT_CCV_CODE' => 'Código de Validación Tarjeta de Crédito',
	'VM_CHECKOUT_CCV_CODE_TIPTITLE' => '¿Qué es el código de Validación de la Tarjeta de Crédito?',
	'VM_CHECKOUT_MD5_FAILED' => 'MD5 Chequeo fallido',
	'VM_CHECKOUT_ORDERNOTFOUND' => 'Pedido no encontrado',
	'PHPSHOP_EPAY_PAYMENT_CARDTYPE' => 'El proceso de pago ha sido generado
por %s <img
src="/components/com_virtuemart/shop_image/ps_image/epay_images/%s"
border="0">'
); $VM_LANG->initModule( 'checkout', $langvars );
?>