<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* The configuration file for VirtueMart
*
* @package VirtueMart
* @subpackage core
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

global $mosConfig_absolute_path,$mosConfig_live_site;
if( !class_exists( 'jconfig' )) {
	$global_lang = $GLOBALS['mosConfig_lang'];

	@include( dirname( __FILE__ ).'/../../../configuration.php' );

	$GLOBALS['mosConfig_lang'] = $mosConfig_lang = $global_lang;
}
// Check for trailing slash
if( $mosConfig_live_site[strlen( $mosConfig_live_site)-1] == '/' ) {
	$app = '';
}
else {
	$app = '/';
}
// these path and url definitions here are based on the Joomla! Configuration
define( 'URL', 'http://www.cultunet.com/' );
define( 'SECUREURL', 'http://www.cultunet.com/' );

if ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == '443' ) {
	define( 'IMAGEURL', SECUREURL .'components/com_virtuemart/shop_image/' );
	define( 'VM_THEMEURL', SECUREURL.'components/com_virtuemart/themes/default/' );
} else {
	define( 'IMAGEURL', URL .'components/com_virtuemart/shop_image/' );
	define( 'VM_THEMEURL', URL.'components/com_virtuemart/themes/default/' );
}
define( 'VM_THEMEPATH', $mosConfig_absolute_path.'/components/com_virtuemart/themes/default/' );

define( 'COMPONENTURL', URL .'administrator/components/com_virtuemart/' );
define( 'ADMINPATH', $mosConfig_absolute_path.'/administrator/components/com_virtuemart/' );
define( 'CLASSPATH', ADMINPATH.'classes/' );
define( 'PAGEPATH', ADMINPATH.'html/' );
define( 'IMAGEPATH', $mosConfig_absolute_path.'/components/com_virtuemart/shop_image/' );

define('PSHOP_IS_OFFLINE', '');
define('PSHOP_OFFLINE_MESSAGE', '<img border="0" style="width: 565px;" title="Próxima apertura de tienda especializada en libros de Gestión Cultural" alt="Próxima apertura de tienda especializada en libros de Gestión Cultural" src="images/stories/banners/tienda_es.jpg" />');
define('USE_AS_CATALOGUE', '');
define('VM_TABLEPREFIX', 'vm');
define('VM_PRICE_SHOW_PACKAGING_PRICELABEL', '');
define('VM_PRICE_SHOW_INCLUDINGTAX', '');
define('VM_PRICE_ACCESS_LEVEL', 'Public Frontend');
define('VM_REGISTRATION_TYPE', 'NORMAL_REGISTRATION');
define('VM_BROWSE_ORDERBY_FIELD', 'product_list');
define('VM_GENERALLY_PREVENT_HTTPS', '1');
define('VM_ALLOW_EXTENDED_CLASSES', '');
define('VM_SHOW_REMEMBER_ME_BOX', '');
define('VM_REVIEWS_MINIMUM_COMMENT_LENGTH', '100');
define('VM_REVIEWS_MAXIMUM_COMMENT_LENGTH', '2000');
define('VM_SHOW_PRINTICON', '1');
define('VM_SHOW_EMAILFRIEND', '1');
define('PSHOP_PDF_BUTTON_ENABLE', '1');
define('VM_REVIEWS_AUTOPUBLISH', '');
define('VM_PROXY_URL', '');
define('VM_PROXY_PORT', '');
define('VM_PROXY_USER', '');
define('VM_PROXY_PASS', '');
define('VM_ONCHECKOUT_SHOW_LEGALINFO', '');
define('VM_ONCHECKOUT_LEGALINFO_SHORTTEXT', 'Estás en la web de Cultunet. Desde este servicio de compra on-line puedes adquirir todas las publicaciones de g+c, así como diversa bibliografía de gestión cultural. También ponemos a tu disposición un correo electrónico de atención al cliente, info@gestionycultura.com, desde el cual podrás realizar tus consultas, pudiendo ser atendido en castellano, catalán, gallego, francés e inglés.

Los envíos se realizarán, previa Confirmación por parte del Departamento Comercial de Área de Trabajo S.L., en la dirección indicada por el cliente, que puede ser distinta a la de su residencia habitual: dirección de trabajo, terceras personas, etc. En el caso de que alguno de los artículos solicitados no estuviera disponible, Área de Trabajo, S.L. se pondrá en contacto con el cliente para anular o modificar el pedido.

El cliente dispondrá de 24 horas para la comprobación del pedido. Pasadas estas 24 horas no se aceptaran reclamaciones.

La empresa se reserva la capacidad de anular un pedido en cualquier momento.

En principio los precios de publicaciones periódicas incluyen los gastos de envío, sin embargo esto está sujeto a que la suma de los pedidos de todos los clientes recibidos por Área de Trabajo SL alcance un número mínimo desde el último envío. En caso de no alcanzarse este mínimo de pedidos Área de Trabajo SL se pondrá en contacto con el cliente para informarle de los costes de envío o procederá a anular el pedido.');
define('VM_ONCHECKOUT_LEGALINFO_LINK', '29');
define('ENABLE_DOWNLOADS', '1');
define('DOWNLOAD_MAX', '3');
define('DOWNLOAD_EXPIRE', '432000');
define('ENABLE_DOWNLOAD_STATUS', 'C');
define('DISABLE_DOWNLOAD_STATUS', 'X');
define('DOWNLOADROOT', '/usr/home/cultunet/clientes_vm/');
define('VM_DOWNLOADABLE_PRODUCTS_KEEP_STOCKLEVEL', '1');
define('_SHOW_PRICES', '1');
define('ORDER_MAIL_HTML', '1');
define('HOMEPAGE', 'shop.index');
define('CATEGORY_TEMPLATE', 'managed');
define('FLYPAGE', 'flypage.tpl');
define('PRODUCTS_PER_ROW', '3');
define('ERRORPAGE', 'shop.error');
define('NO_IMAGE', 'noimage.gif');
define('DEBUG', '');
define('SHOWVERSION', '');
define('TAX_VIRTUAL', '1');
define('TAX_MODE', '1');
define('MULTIPLE_TAXRATES_ENABLE', '');
define('PAYMENT_DISCOUNT_BEFORE', '1');
define('PAYMENT_DISCOUNT_VAT_ID', '');
define('PSHOP_ALLOW_REVIEWS', '1');
define('PSHOP_AGREE_TO_TOS_ONORDER', '');
define('SHOW_CHECKOUT_BAR', '1');
define('CHECK_STOCK', '');
define('ENCODE_KEY', '33a0838970dda407e2160432dd77184a');
define('NO_SHIPPING', '');
define('NO_SHIPTO', '');
define('AFFILIATE_ENABLE', '');
define('PSHOP_ALLOW_FRONTENDADMIN_FOR_NOBACKENDERS', '');
define('PSHOP_IMG_RESIZE_ENABLE', '1');
define('PSHOP_IMG_WIDTH', '150');
define('PSHOP_IMG_HEIGHT', '150');
define('PSHOP_COUPONS_ENABLE', '');
define('PSHOP_SHOW_PRODUCTS_IN_CATEGORY', '1');
define('PSHOP_SHOW_TOP_PAGENAV', '1');
define('PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS', '1');
define('VM_CURRENCY_CONVERTER_MODULE', 'convertECB');
define('VM_CONTENT_PLUGINS_ENABLE', '1');
define('VM_ENABLE_COOKIE_CHECK', '1');
define('VM_FEED_ENABLED', '1');
define('VM_FEED_CACHE', '1');
define('VM_FEED_CACHETIME', '3600');
define('VM_FEED_TITLE', 'Últimos Productos de {storename}');
define('VM_FEED_TITLE_CATEGORIES', '{storename} - Últimos productos de la categoría: {catname}');
define('VM_FEED_SHOW_IMAGES', '1');
define('VM_FEED_SHOW_PRICES', '1');
define('VM_FEED_SHOW_DESCRIPTION', '1');
define('VM_FEED_DESCRIPTION_TYPE', 'product_s_desc');
define('VM_FEED_LIMITTEXT', '1');
define('VM_FEED_MAX_TEXT_LENGTH', '250');
define('VM_STORE_CREDITCARD_DATA', '1');
define('VM_ENCRYPT_FUNCTION', 'AES_ENCRYPT');
define('VM_COMPONENT_NAME', 'com_virtuemart');
define('VM_LOGFILE_ENABLED', '');
define('VM_LOGFILE_NAME', '');
define('VM_LOGFILE_LEVEL', 'PEAR_LOG_WARNING');
define('VM_DEBUG_IP_ENABLED', '');
define('VM_DEBUG_IP_ADDRESS', '');
define('VM_LOGFILE_FORMAT', '%{timestamp} %{ident} [%{priority}] [%{remoteip}] [%{username}] %{message}');

/* OrderByFields */
global $VM_BROWSE_ORDERBY_FIELDS;
$VM_BROWSE_ORDERBY_FIELDS = array( 'product_name','product_price','product_cdate' );

/* Shop Modules that run with https only*/
global $VM_MODULES_FORCE_HTTPS;
$VM_MODULES_FORCE_HTTPS = array( 'account','checkout' );

// Checkout Steps and their order
global $VM_CHECKOUT_MODULES;
$VM_CHECKOUT_MODULES = array( 'CHECK_OUT_GET_SHIPPING_ADDR'=>array('order'=>1,'enabled'=>1),
'CHECK_OUT_GET_SHIPPING_METHOD'=>array('order'=>2,'enabled'=>1),
'CHECK_OUT_GET_PAYMENT_METHOD'=>array('order'=>3,'enabled'=>1),
'CHECK_OUT_GET_FINAL_CONFIRMATION'=>array('order'=>4,'enabled'=>1) );

/* Shipping Methods Definition */
global $PSHOP_SHIPPING_MODULES;
$PSHOP_SHIPPING_MODULES[0] = "standard_shipping";
?>