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
	'PHPSHOP_ORDER_PRINT_PAYMENT_LOG_LBL' => 'Registro de Pago',
	'PHPSHOP_ORDER_PRINT_SHIPPING_PRICE_LBL' => 'Precio de Envío',
	'PHPSHOP_ORDER_STATUS_LIST_CODE' => 'Código de Pedido',
	'PHPSHOP_ORDER_STATUS_LIST_NAME' => 'Nombre de Pedido',
	'PHPSHOP_ORDER_STATUS_FORM_LBL' => 'Pedido',
	'PHPSHOP_ORDER_STATUS_FORM_CODE' => 'Código de Pedido',
	'PHPSHOP_ORDER_STATUS_FORM_NAME' => 'Nombre de Pedido',
	'PHPSHOP_ORDER_STATUS_FORM_LIST_ORDER' => 'Lista de Pedido',
	'PHPSHOP_COMMENT' => 'Comentario',
	'PHPSHOP_ORDER_LIST_NOTIFY' => 'Notificar al cliente?',
	'PHPSHOP_ORDER_LIST_NOTIFY_ERR' => 'Por favor, antes debe cambiar el estado del pedido!',
	'PHPSHOP_ORDER_HISTORY_INCLUDE_COMMENT' => 'Incluir este comentario?',
	'PHPSHOP_ORDER_HISTORY_DATE_ADDED' => 'Fecha añadida',
	'PHPSHOP_ORDER_HISTORY_CUSTOMER_NOTIFIED' => 'Cliente notificado?',
	'PHPSHOP_ORDER_STATUS_CHANGE' => 'Order Status Change',
	'PHPSHOP_ORDER_LIST_PRINT_LABEL' => 'Print Label',
	'PHPSHOP_ORDER_LIST_VOID_LABEL' => 'Void Label',
	'PHPSHOP_ORDER_LIST_TRACK' => 'Track',
	'VM_DOWNLOAD_STATS' => 'DESCARGAR ESTADÍSTICAS',
	'VM_DOWNLOAD_NOTHING_LEFT' => 'no hay descargas restantes',
	'VM_DOWNLOAD_REENABLE' => 'Re-activar Descarga',
	'VM_DOWNLOAD_REMAINING_DOWNLOADS' => 'Descargas restantes',
	'VM_DOWNLOAD_RESEND_ID' => 'Reenviar ID de descarga',
	'VM_EXPIRY' => 'Caducidad',
	'VM_UPDATE_STATUS' => 'Actualizar estado',
	'VM_ORDER_LABEL_ORDERID_NOTVALID' => 'Porfavor, indique una ID de pedido válida y numérica, esto no es válido "{order_id}"',
	'VM_ORDER_LABEL_NOTFOUND' => 'Entrada de pedido no encontrada en la base de datos.',
	'VM_ORDER_LABEL_NEVERGENERATED' => 'La etiqueta no ha sido generado aún.',
	'VM_ORDER_LABEL_CLASSCANNOT' => 'La clase {ship_class} no puede obtener imágenes de etiquetas, ¿Para que estamos aquí?',
	'VM_ORDER_LABEL_SHIPPINGLABEL_LBL' => 'Etiqueta de envío',
	'VM_ORDER_LABEL_SIGNATURENEVER' => 'Signature was never retrieved',
	'VM_ORDER_LABEL_TRACK_TITLE' => 'Track',
	'VM_ORDER_LABEL_VOID_TITLE' => 'Etiqueta nula',
	'VM_ORDER_LABEL_VOIDED_MSG' => 'Label for waybill {tracking_number} has been voided.',
	'VM_ORDER_PRINT_PO_IPADDRESS' => 'IP-ADDRESS',
	'VM_ORDER_STATUS_ICON_ALT' => 'Icono de estado',
	'VM_ORDER_PAYMENT_CCV_CODE' => 'CVV Code',
	'VM_ORDER_NOTFOUND' => 'Pedido no encontrado! Puede haber sido eliminado.',
	'PHPSHOP_ORDER_EDIT_ACTIONS' => 'Acciones',
	'PHPSHOP_ORDER_EDIT' => 'Cambiar detalles del pedido',
	'PHPSHOP_ORDER_EDIT_ADD' => 'Añadir',
	'PHPSHOP_ORDER_EDIT_ADD_PRODUCT' => 'Añadir Producto',
	'PHPSHOP_ORDER_EDIT_EDIT_ORDER' => 'Modificar pedido',
	'PHPSHOP_ORDER_EDIT_ERROR_QUANTITY_MUST_BE_HIGHER_THAN_0' => 'Cantidad debe ser mayor que 0.',
	'PHPSHOP_ORDER_EDIT_PRODUCT_ADDED' => 'El producto ha sido añadido al pedido',
	'PHPSHOP_ORDER_EDIT_PRODUCT_DELETED' => 'El producto ha sido eliminado del pedido',
	'PHPSHOP_ORDER_EDIT_QUANTITY_UPDATED' => 'Cantidad ha sido actualizada',
	'PHPSHOP_ORDER_EDIT_RETURN_PARENTS' => 'Volver al producto padre',
	'PHPSHOP_ORDER_EDIT_CHOOSE_PRODUCT' => 'Seleccionar un producto',
	'PHPSHOP_ORDER_CHANGE_UPD_BILL' => 'Cambiar dirección para la facturación',
	'PHPSHOP_ORDER_CHANGE_UPD_SHIP' => 'Cambiar dirección para el envío',
	'PHPSHOP_ORDER_EDIT_SOMETHING_HAS_CHANGED' => ' ha sido modificado',
	'PHPSHOP_ORDER_EDIT_CHOOSE_PRODUCT_BY_SKU' => 'Seleccionar REF'
); $VM_LANG->initModule( 'order', $langvars );
?>
