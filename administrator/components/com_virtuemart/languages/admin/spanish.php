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
	'PHPSHOP_USER_LIST_LBL' => 'Lista de Usuarios',
	'PHPSHOP_USER_LIST_USERNAME' => 'Nombre',
	'PHPSHOP_USER_LIST_FULL_NAME' => 'Nombre Completo',
	'PHPSHOP_USER_LIST_GROUP' => 'Grupo',
	'PHPSHOP_USER_FORM_LBL' => 'Informaci�n de Usuario',
	'PHPSHOP_USER_FORM_PERMS' => 'Permisos',
	'PHPSHOP_USER_FORM_CUSTOMER_NUMBER' => 'N�mero de cliente / ID',
	'PHPSHOP_MODULE_LIST_LBL' => 'Lista de Modulos',
	'PHPSHOP_MODULE_LIST_NAME' => 'M�dulo',
	'PHPSHOP_MODULE_LIST_PERMS' => 'Permisos',
	'PHPSHOP_MODULE_LIST_FUNCTIONS' => 'Funciones',
	'PHPSHOP_MODULE_FORM_LBL' => 'Informaci�n',
	'PHPSHOP_MODULE_FORM_MODULE_LABEL' => 'Etiqueta',
	'PHPSHOP_MODULE_FORM_NAME' => 'Nombre',
	'PHPSHOP_MODULE_FORM_PERMS' => 'Permisos',
	'PHPSHOP_MODULE_FORM_HEADER' => 'Encabezado',
	'PHPSHOP_MODULE_FORM_FOOTER' => 'Pie',
	'PHPSHOP_MODULE_FORM_MENU' => 'Menu?',
	'PHPSHOP_MODULE_FORM_ORDER' => 'Pedido',
	'PHPSHOP_MODULE_FORM_DESCRIPTION' => 'Descripci�n',
	'PHPSHOP_MODULE_FORM_LANGUAGE_CODE' => 'C�digo de Idioma',
	'PHPSHOP_MODULE_FORM_LANGUAGE_FILE' => 'Archivo de idioma',
	'PHPSHOP_FUNCTION_LIST_LBL' => 'Lista de Funciones',
	'PHPSHOP_FUNCTION_LIST_NAME' => 'Nombre',
	'PHPSHOP_FUNCTION_LIST_CLASS' => 'Nombre de clase',
	'PHPSHOP_FUNCTION_LIST_METHOD' => 'M�todo de Clase',
	'PHPSHOP_FUNCTION_LIST_PERMS' => 'Permisos',
	'PHPSHOP_FUNCTION_FORM_LBL' => 'Informaci�n',
	'PHPSHOP_FUNCTION_FORM_NAME' => 'Nombre',
	'PHPSHOP_FUNCTION_FORM_CLASS' => 'Nombre de Clase',
	'PHPSHOP_FUNCTION_FORM_METHOD' => 'M�todo de Clase',
	'PHPSHOP_FUNCTION_FORM_PERMS' => 'Permisos',
	'PHPSHOP_FUNCTION_FORM_DESCRIPTION' => 'Descripci�n',
	'PHPSHOP_CURRENCY_LIST_LBL' => 'Lista de monedas',
	'PHPSHOP_CURRENCY_LIST_NAME' => 'Nombre de moneda',
	'PHPSHOP_CURRENCY_LIST_CODE' => 'C�digo de moneda',
	'PHPSHOP_COUNTRY_LIST_LBL' => 'Lista de Pa�ses',
	'PHPSHOP_COUNTRY_LIST_NAME' => 'Nombre de Pa�s',
	'PHPSHOP_COUNTRY_LIST_3_CODE' => 'c�digo de Pa�s (3)',
	'PHPSHOP_COUNTRY_LIST_2_CODE' => 'c�digo de Pa�s (2)',
	'PHPSHOP_STATE_LIST_MNU' => 'Listar Estados',
	'PHPSHOP_STATE_LIST_LBL' => 'Lista de Estados para: ',
	'PHPSHOP_STATE_LIST_ADD' => 'A�adir/actualizar un Estado',
	'PHPSHOP_STATE_LIST_NAME' => 'Nombre de Estado',
	'PHPSHOP_STATE_LIST_3_CODE' => 'State Code (3)',
	'PHPSHOP_STATE_LIST_2_CODE' => 'State Code (2)',
	'PHPSHOP_ADMIN_CFG_GLOBAL' => 'Global',
	'PHPSHOP_ADMIN_CFG_SITE' => 'Sitio',
	'PHPSHOP_ADMIN_CFG_SHIPPING' => 'Env�o',
	'PHPSHOP_ADMIN_CFG_CHECKOUT' => 'Finalizaci�n de pedido',
	'PHPSHOP_ADMIN_CFG_DOWNLOADABLEGOODS' => 'Descargar',
	'PHPSHOP_ADMIN_CFG_USE_ONLY_AS_CATALOGUE' => 'Utilizar s�lo como cat�logo',
	'PHPSHOP_ADMIN_CFG_USE_ONLY_AS_CATALOGUE_EXPLAIN' => 'Si est� marcado, se inhabilitan todos los funcionamientos de compra.',
	'PHPSHOP_ADMIN_CFG_SHOW_PRICES' => 'Mostrar precios',
	'PHPSHOP_ADMIN_CFG_SHOW_PRICES_EXPLAIN' => 'Si est� marcado, no se mostrar�n los precios al p�blico. Puede utilizarse como cat�logo funcional.',
	'PHPSHOP_ADMIN_CFG_VIRTUAL_TAX' => 'Impuesto Virtual',
	'PHPSHOP_ADMIN_CFG_VIRTUAL_TAX_EXPLAIN' => 'A�ade el impuesto (IVA) para productos sin peso especificado. Modifique ps_checkout.php->calc_order_taxable() para adaptarlo.',
	'PHPSHOP_ADMIN_CFG_TAX_MODE' => 'Modo de Impuesto:',
	'PHPSHOP_ADMIN_CFG_TAX_MODE_SHIP' => 'Basado en la direcci�n del env�o',
	'PHPSHOP_ADMIN_CFG_TAX_MODE_VENDOR' => 'Basado en la direcci�n del vendedor',
	'PHPSHOP_ADMIN_CFG_TAX_MODE_EXPLAIN' => 'Aqu� se determina la tarifa de impuesto aplicada.:<br />
              		<ul><li>Modo de impuesto del pa�s de la tienda</li><br/>
                    <li>Modo de impuesto del lugar del comprador.</li></ul>',
	'PHPSHOP_ADMIN_CFG_MULTI_TAX_RATE' => 'Permitir m�ltiples tarifas de impuestos?',
	'PHPSHOP_ADMIN_CFG_MULTI_TAX_RATE_EXPLAIN' => 'Marque esto si tiene productos con varios tipos de IVA (e.j. 7% para alimentos, 16% para dem�s)',
	'PHPSHOP_ADMIN_CFG_SUBSTRACT_PAYEMENT_BEFORE' => 'Restar el descuento antes del IVA / Env�o?',
	'PHPSHOP_ADMIN_CFG_REVIEW' => 'Permitir Revisi�n de Cliente / Sistema de Valoraci�n',
	'PHPSHOP_ADMIN_CFG_REVIEW_EXPLAIN' => 'Si es posible, permitir� a los clientes <strong>valorar productos</strong> y <strong>escribir revisiones</strong> sobre ellos. <br />
                                                                                Tambi�n podr�n escribir sus experiencias con el producto para otros clientes.<br />',
	'PHPSHOP_ADMIN_CFG_SUBSTRACT_PAYEMENT_BEFORE_EXPLAIN' => 'Pone la bandera restar el descuento para el pago seleccionado ANTES(revisado) o DESPU�S de impuesto y env�o.',
	'PHPSHOP_ADMIN_CFG_AGREE_TERMS' => 'Tiene que estar de acuerdo con los condiciones del servicio?',
	'PHPSHOP_ADMIN_CFG_AGREE_TERMS_EXPLAIN' => 'Revisar si quiere que un comprador est� de acuerdo con sus condiciones de servicio antes de registrarse a la tienda.',
	'PHPSHOP_ADMIN_CFG_CHECK_STOCK' => 'Comprobar el Stock?',
	'PHPSHOP_ADMIN_CFG_CHECK_STOCK_EXPLAIN' => 'Marcar si se debe comprobar el nivel de stock cuando un usario agrega un articulo a su cesta de compra.
                                                                                          Si est� marcado, no se permitir� al usario agregar m�s articulos en su cesta de los que est�n disponibles en stock.',
	'PHPSHOP_ADMIN_CFG_MAIL_FORMAT' => 'Formato del email en los pedidos:',
	'PHPSHOP_ADMIN_CFG_MAIL_FORMAT_TEXT' => 'Texto mail',
	'PHPSHOP_ADMIN_CFG_MAIL_FORMAT_HTML' => 'HTML mail',
	'PHPSHOP_ADMIN_CFG_MAIL_FORMAT_EXPLAIN' => 'Esto se determina en que formato se env�an sus correos de confirmaci�n del pedido:<br />
                                                                                        <ul><li>email en texto plano</li>
                                                                                        <li>email html con imagenes.</li></ul>',
	'PHPSHOP_ADMIN_CFG_FRONTENDAMDIN' => 'Permitir administraci�n en el front-end para usarios storeadmins.',
	'PHPSHOP_ADMIN_CFG_FRONTENDAMDIN_EXPLAIN' => 'Activando esta opci�n se permite la administraci�n en el front-end para usarios que son
                                                                                              storeadmins, pero no pueden acceder al Backend (e.j. Registrado / Editor).',
	'PHPSHOP_ADMIN_CFG_URLSECURE' => 'SECUREURL',
	'PHPSHOP_ADMIN_CFG_URLSECURE_EXPLAIN' => 'URL seguro para su sitio. (https - con / en el final!)',
	'PHPSHOP_ADMIN_CFG_HOMEPAGE' => 'HOMEPAGE',
	'PHPSHOP_ADMIN_CFG_HOMEPAGE_EXPLAIN' => 'Est� es la p�gina que ser� cargada por defecto.',
	'PHPSHOP_ADMIN_CFG_ERRORPAGE' => 'ERRORPAGE',
	'PHPSHOP_ADMIN_CFG_ERRORPAGE_EXPLAIN' => 'Est� es la pag�na del defecto para mostrar el mensaje de error.',
	'PHPSHOP_ADMIN_CFG_DEBUG' => 'DEBUG ?',
	'PHPSHOP_ADMIN_CFG_DEBUG_EXPLAIN' => 'DEBUG?  	   	Enciende debug output. Esto hace para mostrar DEBUGPAGE en el fondo de cada p�gina. Es muy util durante el desarrollo de la tienda porque le ense�a el contenido de carros, forma los values del campo, etc.',
	'PHPSHOP_ADMIN_CFG_FLYPAGE' => 'Plantilla para vista detallada del producto',
	'PHPSHOP_ADMIN_CFG_FLYPAGE_EXPLAIN' => 'Define la plantilla por defecto para la vista detallada del producto.',
	'PHPSHOP_ADMIN_CFG_CATEGORY_TEMPLATE' => 'Plantilla para la vista del producto en cada categor�a',
	'PHPSHOP_ADMIN_CFG_CATEGORY_TEMPLATE_EXPLAIN' => 'Define la plantilla por defecto para mostrar la vista del producto en cada categor�a.<br />
                                                                                                      Puede crear nueva plantilla personalizando los archivos existentes <br />
                                                                                                      (est�n en <strong>COMPONENTPATH/html/templates/</strong> y comienzan por browse_)',
	'PHPSHOP_ADMIN_CFG_PRODUCTS_PER_ROW' => 'El n�mero por defecto de productos por fila',
	'PHPSHOP_ADMIN_CFG_PRODUCTS_PER_ROW_EXPLAIN' => 'Esto define el n�mero de productos en fila. <br />
                                                                                                      Ejempro: Si usted marca 4, plantilla de categor�a mostrar� 4 productos por fila',
	'PHPSHOP_ADMIN_CFG_NOIMAGEPAGE' => 'Imagen a mostrar "Sin imagen"',
	'PHPSHOP_ADMIN_CFG_NOIMAGEPAGE_EXPLAIN' => 'Saldr� esta imagen cuando no hay imagen de producto disponible.',
	'PHPSHOP_ADMIN_CFG_SHOWPHPSHOP_VERSION' => 'Mostrar logotipo Virtuemart',
	'PHPSHOP_ADMIN_CFG_SHOWPHPSHOP_VERSION_EXPLAIN' => 'Si est� marcado, se mostrar� el logotipo de Virtuemart en el pie de p�gina.',
	'PHPSHOP_ADMIN_CFG_STORE_SHIPPING_METHOD_STANDARD' => 'M�dulo est�ndar de env�o con configuraci�n individual en transporte y tarifa. <strong>RECOMENDADO !</strong>',
	'PHPSHOP_ADMIN_CFG_STORE_SHIPPING_METHOD_ZONE' => '  	modulo zona del env�o versi�n del pa�s 1.0<br />
                                                                                                            Para m�s informaci�n sobre este modulo por favor visite <a href="http://ZephWare.com">http://ZephWare.com</a><br />
                                                                                                            para detalles o contacto <a href="mailto:zephware@devcompany.com">ZephWare.com</a><br /> Marcar esta opci�n para activar el m�dulo de env�o por zona.',
	'PHPSHOP_ADMIN_CFG_STORE_SHIPPING_METHOD_DISABLE' => 'Inhabilite la selecci�n del m�todo del env�o. Elije si sus clientes compran los productos descargable que no necesitan a mandar.',
	'PHPSHOP_ADMIN_CFG_ENABLE_CHECKOUTBAR' => 'Permitir la opcion de Finalizar Compra',
	'PHPSHOP_ADMIN_CFG_ENABLE_CHECKOUTBAR_EXPLAIN' => 'Compruebelo, si quiere mostrar \'checkout-bar\' para los clientes durante el proceso de terminar ( 1 - 2 - 3 - 4 con gr�ficos).',
	'PHPSHOP_ADMIN_CFG_CHECKOUT_PROCESS' => 'Elegir el proceso de Finalizar Compra para la tienda.',
	'PHPSHOP_ADMIN_CFG_ENABLE_DOWNLOADS' => 'Activar Descargas',
	'PHPSHOP_ADMIN_CFG_ENABLE_DOWNLOADS_EXPLAIN' => 'Marque para permitir la capacidad de descargar. S�lo cuando quiere vender los productos que se pueden descargar.',
	'PHPSHOP_ADMIN_CFG_ORDER_ENABLE_DOWNLOADS' => 'Estado del pedido que permite descargar.',
	'PHPSHOP_ADMIN_CFG_ORDER_ENABLE_DOWNLOADS_EXPLAIN' => 'Seleccione el estado del pedido cual avisa al cliente sobre la desgarga v�a e-mail.',
	'PHPSHOP_ADMIN_CFG_ORDER_DISABLE_DOWNLOADS' => 'Estado de pedido que inhabilita las desgargas.',
	'PHPSHOP_ADMIN_CFG_ORDER_DISABLE_DOWNLOADS_EXPLAIN' => 'Determinar el estado del pedido que inhabilita las desgargas.',
	'PHPSHOP_ADMIN_CFG_DOWNLOADROOT' => 'DOWNLOADROOT',
	'PHPSHOP_ADMIN_CFG_DOWNLOADROOT_EXPLAIN' => 'La ruta f�sica a los archivos de la descarga de clientes. (/ al final!)<br>
        <span class="message">Para la seguridad de su tienda: Si puede, por favor,  utilize el directorio dondequiera fuera de WEBROOT</span>',
	'PHPSHOP_ADMIN_CFG_DOWNLOAD_MAX' => 'M�xima Descarga',
	'PHPSHOP_ADMIN_CFG_DOWNLOAD_MAX_EXPLAIN' => 'Coloque el n�mero de descargas que est� hecho con un ID de descargar, (Para un pedido)',
	'PHPSHOP_ADMIN_CFG_DOWNLOAD_EXPIRE' => 'La descarga caduca',
	'PHPSHOP_ADMIN_CFG_DOWNLOAD_EXPIRE_EXPLAIN' => 'Ajuste el tiempo <strong>in seconds</strong> durante el cual se permite el cliente a descargar.
  Esta intervalo empieza con la primera descarga! Cuando se ha acabado el tiempo, el download-ID est� invalido.<br />Note : 86400s=24h.',
	'PHPSHOP_COUPONS' => 'Cupones',
	'PHPSHOP_COUPONS_ENABLE' => 'Permitir el uso del cup�n descuento',
	'PHPSHOP_COUPONS_ENABLE_EXPLAIN' => 'Si est� marcado, los clientes podr�n usar los cupones descuento en los pedidos.',
	'PHPSHOP_ADMIN_CFG_PDF_BUTTON' => 'Bot�n PDF',
	'PHPSHOP_ADMIN_CFG_PDF_BUTTON_EXPLAIN' => 'Mostrar / ocultar bot�n PDF en la tienda.',
	'PHPSHOP_ADMIN_CFG_AGREE_TERMS_ONORDER' => 'Requerir el haber le�do las condiciones de servicio',
	'PHPSHOP_ADMIN_CFG_AGREE_TERMS_ONORDER_EXPLAIN' => 'Si est� marcado, los clientes deben aceptar las condiciones de servicio en CADA PEDIDO (antes del pedido).',
	'PHPSHOP_ADMIN_CFG_SHOW_OUT_OF_STOCK_PRODUCTS' => 'Mostrar productos fuera de stock?',
	'PHPSHOP_ADMIN_CFG_SHOW_OUT_OF_STOCK_PRODUCTS_EXPLAIN' => 'Si est� activado, se mostrar�n productos fuera de stock. De lo contrario se ocultar�n.',
	'PHPSHOP_ADMIN_CFG_SHOP_OFFLINE' => 'Desactivar tienda?',
	'PHPSHOP_ADMIN_CFG_SHOP_OFFLINE_TIP' => 'Si est� marcado, se mostrar� el mensaje de abajo indicando que la tienda est� desactivada.',
	'PHPSHOP_ADMIN_CFG_SHOP_OFFLINE_MSG' => 'Mensaje para tienda desactivada',
	'PHPSHOP_ADMIN_CFG_TABLEPREFIX' => 'Prefijo para las tablas de la tienda',
	'PHPSHOP_ADMIN_CFG_TABLEPREFIX_TIP' => 'Por defecto es <strong>vm</strong>',
	'PHPSHOP_ADMIN_CFG_NAV_AT_TOP' => 'Mostrar navegaci�n en la parte superior de la lista de productos?',
	'PHPSHOP_ADMIN_CFG_NAV_AT_TOP_TIP' => 'Si est� marcado se mostrar� la navegaci�n en la parte superior de la lista de productos.',
	'PHPSHOP_ADMIN_CFG_SHOW_PRODUCT_COUNT' => 'Mostrar el n�mero de productos?',
	'PHPSHOP_ADMIN_CFG_SHOW_PRODUCT_COUNT_TIP' => 'Muestra el n�mero de productos en cada categor�a. Ejemplo: Categor�a (4)?',
	'PHPSHOP_ADMIN_CFG_DYNAMIC_THUMBNAIL_RESIZING' => 'Activar redimensi�n din�mica de miniaturas?',
	'PHPSHOP_ADMIN_CFG_DYNAMIC_THUMBNAIL_RESIZING_TIP' => 'Si est� marcado, la redimensi�n din�mica de miniaturas se activar� para todas las miniaturas ajust�ndose a los valores introducidos abajo,
        usando las funciones GD2 de PHP (puedes comprobar si tienes soporte para GD2 aqu�: "Ayuda" -> "Informaci�n del sistema" -> "Informaci�n de PHP" -> gd. 
        La calidad de imagen de las miniaturas es m�s alta que las im�genes redimensionadas por el navegador. Las nuevas im�genes redimensionadas ser�n almacenadas en: /shop_image/prduct/resized. Si la imagen ya ha sido redimensionada, ser� enviada al navegador, as� ninguna imagen ser� redimensionada por duplicado.',
	'PHPSHOP_ADMIN_CFG_THUMBNAIL_WIDTH' => 'Anchura de la miniatura',
	'PHPSHOP_ADMIN_CFG_THUMBNAIL_WIDTH_TIP' => 'La <strong>anchura</strong> de la miniatura de imagen redimensionada.',
	'PHPSHOP_ADMIN_CFG_THUMBNAIL_HEIGHT' => 'Altura de la miniatura',
	'PHPSHOP_ADMIN_CFG_THUMBNAIL_HEIGHT_TIP' => 'LA <strong>altura</strong> de la miniatura de imagen redimensionada.',
	'PHPSHOP_ADMIN_CFG_SHIPPING_NO_SELECTION' => 'Selecciona por lo menos una casilla en la configuraci�n del env�o!',
	'PHPSHOP_ADMIN_CFG_PRICE_CONFIGURATION' => 'Configuraci�n del Precio',
	'PHPSHOP_ADMIN_CFG_PRICE_ACCESS_LEVEL' => 'Grupo de usuarios que puede ver los precios:',
	'PHPSHOP_ADMIN_CFG_PRICE_ACCESS_LEVEL_TIP' => 'El Grupo de usuarios seleccionado y todos los que posean permisos superiores a �ste podr�n ver los precios de los productos.',
	'PHPSHOP_ADMIN_CFG_PRICE_SHOW_INCLUDINGTAX' => 'Mostrar "(incluido XX% IVA)" cuando sea aplicable?',
	'PHPSHOP_ADMIN_CFG_PRICE_SHOW_INCLUDINGTAX_TIP' => 'Si est� marcado, los usuarios podr�n ver "(incluido xx% IVA)" cuando los precios tengan un impuesto.',
	'PHPSHOP_ADMIN_CFG_PRICE_SHOW_PACKAGING_PRICELABEL' => 'Mostrar el precio de embalaje?',
	'PHPSHOP_ADMIN_CFG_PRICE_SHOW_PACKAGING_PRICELABEL_TIP' => 'Si est� marcado, el precio mostrado estar� compuesto por el valor del embalaje y de la unidad del producto\'s :
<strong>Precio por unidad (10 unidades)<strong><br/>
Si no est� marcado, el precio ser� mostrado normalmente: <strong>Precio: $xx.xx</strong>',
	'PHPSHOP_ADMIN_CFG_MORE_CORE_SETTINGS' => 'm�s ajustes del n�cleo',
	'PHPSHOP_ADMIN_CFG_CORE_SETTINGS' => 'Ajustes del n�cleo',
	'PHPSHOP_ADMIN_CFG_FRONTEND_FEATURES' => 'Propiedades en el Frontend',
	'PHPSHOP_ADMIN_CFG_TAX_CONFIGURATION' => 'Configuraci�n de Tarifas',
	'PHPSHOP_ADMIN_CFG_USER_REGISTRATION_SETTINGS' => 'Ajustes de registro de usuarios',
	'PHPSHOP_ADMIN_CFG_ALLOW_REGISTRATION' => 'Permitir registro de usuarios?',
	'PHPSHOP_ADMIN_CFG_ACCOUNT_ACTIVATION' => 'Activaci�n de nuevas cuentas de usuarios?',
	'VM_FIELDMANAGER_NAME' => 'Campo de nombre',
	'VM_FIELDMANAGER_TITLE' => 'T�tulo del campo',
	'VM_FIELDMANAGER_TYPE' => 'Tipo de campo',
	'VM_FIELDMANAGER_REQUIRED' => 'Requerido',
	'VM_FIELDMANAGER_PUBLISHED' => 'Publicado',
	'VM_FIELDMANAGER_SHOW_ON_REGISTRATION' => 'Mostrar en el formulario de registro',
	'VM_FIELDMANAGER_SHOW_ON_ACCOUNT' => 'Show in account maintenance',
	'VM_USERFIELD_FORM_LBL' => 'A�adir / Editar Campos de usario',
	'VM_BROWSE_ORDERBY_DEFAULT_FIELD_LBL' => 'Orden de los productos por defecto',
	'VM_BROWSE_ORDERBY_DEFAULT_FIELD_LBL_TIP' => 'Define el orden de los productos por defecto en la navegaci�n',
	'VM_BROWSE_ORDERBY_FIELDS_LBL' => 'Campos disponibles para "Ordenar por"',
	'VM_BROWSE_ORDERBY_FIELDS_LBL_TIP' => 'Elegir los campos "Ordenar por" para la navegaci�n. Cada uno define un m�todo de orden en la navegaci�n. Si desmarca todos, los campos para el orden de los productos no ser�n mostrados.',
	'VM_GENERALLY_PREVENT_HTTPS' => 'Evitar conexiones HTTPS para todo el sitio',
	'VM_GENERALLY_PREVENT_HTTPS_TIP' => 'Si est� marcado, el comprador ser� redireccionado a la URL <strong>http</strong> cuando no se encuentre en las �reas marcadas, que son forzadas a usar https.',
	'VM_MODULES_FORCE_HTTPS' => '�reas que deben usar conexiones https',
	'VM_MODULES_FORCE_HTTPS_TIP' => 'Seleccione aqu� los m�dulos de la tienda (Ver "Admin" => "Listar M�dulos"), que deben usar conexiones https.',
	'VM_ALLOW_EXTENDED_CLASSES' => '�Permitir la utilizaci�n de clases extendidas desde el directorio de temas?',
	'VM_ALLOW_EXTENDED_CLASSES_TIP' => 'Si est� marcado, Virtuemart ejecutar� c�digo avanzado. El c�digo original puede ser extendido o reemplazado. En caso de duda, no marcar esta casilla',
	'VM_SHOW_REMEMBER_ME_BOX' => 'Mostrar la casilla "Recordarme" en el acceso?',
	'VM_SHOW_REMEMBER_ME_BOX_TIP' => 'Si est� marcado, la casilla "Recordarme" ser� usada en el proceso de compra. No recomendado en SSL compartido, porque el usuario podr�a elegir si aceptar una cookie o no -  pero esta cookie es necesaria para mantener la sesi�n del usuario en ambos dominios.',
	'VM_ADMIN_CFG_REVIEW_MINIMUM_COMMENT_LENGTH' => 'Longitud m�nima de comentario',
	'VM_ADMIN_CFG_REVIEW_MINIMUM_COMMENT_LENGTH_TIP' => 'Esta es la cantidad m�nima de caracteres para que el comentario pueda ser publicado.',
	'VM_ADMIN_CFG_REVIEW_MAXIMUM_COMMENT_LENGTH' => 'Longitud m�xima de comentario',
	'VM_ADMIN_CFG_REVIEW_MAXIMUM_COMMENT_LENGTH_TIP' => 'Esta es la cantidad m�xima de caracteres permitida en un comentario.
',
	'VM_ADMIN_SHOW_EMAILFRIEND' => 'Mostrar el enlace de "Recomendar a un amigo"?',
	'VM_ADMIN_SHOW_EMAILFRIEND_TIP' => 'Si est� marcado, se mostrar� un peque�o enlace que permitir� al cliente enviar un email de recomendaci�n para un producto espec�fico.',
	'VM_ADMIN_SHOW_PRINTICON' => 'Mostrar el enlace de "Vista imprimir"?',
	'VM_ADMIN_SHOW_PRINTICON_TIP' => 'Si est� marcado, se mostrar� un peque�o enlace que abrir� un Pop-up con el mismo contenido en versi�n imprimible.',
	'VM_REVIEWS_AUTOPUBLISH' => 'Auto-publicar opiniones?',
	'VM_REVIEWS_AUTOPUBLISH_TIP' => 'Si est� marcado, las opiniones de los usuarios ser�n publicadas autom�ticamente al ser enviadas. De lo contrario, el admin. debe aprobarlas/publicarlas.',
	'VM_ADMIN_CFG_PROXY_SETTINGS' => 'Ajustes globales de proxy',
	'VM_ADMIN_CFG_PROXY_URL' => 'URL del servidor proxy',
	'VM_ADMIN_CFG_PROXY_URL_TIP' => 'Ejemplo: <strong>http://10.42.21.1</strong>.<br />
Dejar en blanco si no est� seguro.</strong> Este valor ser� usado para conectar a Internet desde el servidor de la Tienda (e.g. when fetching shipping rates from UPS/USPS).',
	'VM_ADMIN_CFG_PROXY_PORT' => 'Puerto de proxy',
	'VM_ADMIN_CFG_PROXY_PORT_TIP' => 'El puerto usado para comunicaci�n con el servidor proxy (normalmente <b>80</b> o <b>8080</b>).',
	'VM_ADMIN_CFG_PROXY_USER' => 'Usuario de proxy',
	'VM_ADMIN_CFG_PROXY_USER_TIP' => 'Si el proxy requiere autentificaci�n, introduzca su nombre de usuario aqu�.',
	'VM_ADMIN_CFG_PROXY_PASS' => 'Contrase�a de proxy',
	'VM_ADMIN_CFG_PROXY_PASS_TIP' => 'Si el proxy requiere autentificaci�n, introduzca su contrase�a aqu�.',
	'VM_ADMIN_ONCHECKOUT_SHOW_LEGALINFO' => 'Mostrar informaci�n sobre la "Pol�tica de devoluciones" en la confirmaci�n de cada pedido?',
	'VM_ADMIN_ONCHECKOUT_SHOW_LEGALINFO_TIP' => 'Los propietarios de la tienda est�n obligados por Ley a informar a sus clientes sobre las pol�ticas de devoluciones y cancelaciones de pedidos en la mayor�a de los Estados miembros de la Uni�n Europea. Se recomienda activarla en la mayor�a de los casos.',
	'VM_ADMIN_ONCHECKOUT_LEGALINFO_SHORTTEXT' => 'Aviso Legal (versi�n abreviada).',
	'VM_ADMIN_ONCHECKOUT_LEGALINFO_SHORTTEXT_TIP' => 'Este texto describe de manera abreviada las pol�ticas de devoluciones y cancelaciones de pedidos. Ser� mostrado en el �ltimo paso del proceso de compra, exactamente sobre el bot�n de "Confirmar Pedido".',
	'VM_ADMIN_ONCHECKOUT_LEGALINFO_LINK' => 'Enlace a la Pol�tica de privacidad.',
	'VM_ADMIN_ONCHECKOUT_LEGALINFO_LINK_TIP' => 'Por favor, escriba art�culos sobre los detalles de las Pol�ticas de privacidad, devoluciones y cancelaciones.
Posteriormente podr� seleccionarlas desde aqu�.',
	'VM_SELECT_THEME' => 'Seleccionar plantilla para la Tienda',
	'VM_SELECT_THEME_TIP' => 'Las plantillas permiten personalizar la apariencia de la Tienda. <br />Si solo est� disponible la plantilla por defecto, es que no hay plantillas instaladas.',
	'VM_CFG_CONTENT_PLUGINS_ENABLE' => 'Activar mambots / plugins de contenido para las descripciones?',
	'VM_CFG_CONTENT_PLUGINS_ENABLE_TIP' => 'Si est� marcado, las descripciones de categor�as y productos ser�n manejadas por todos los mambots/plugins publicados.',
	'VM_CFG_CURRENCY_MODULE' => 'Seleccionar m�dulo conversor de moneda',
	'VM_CFG_CURRENCY_MODULE_TIP' => 'Esto permite elegir un determinado m�dulo conversor de moneda. Estos m�dulos recogen cuotas del servidor y realizan la conversi�n de moneda.',
	'PHPSHOP_ADMIN_CFG_TAX_MODE_EU' => 'European Union mode',
	'VM_ADMIN_CFG_DOWNLOAD_KEEP_STOCKLEVEL' => 'Mantener nivel de stock en las compras?',
	'VM_ADMIN_CFG_DOWNLOAD_KEEP_STOCKLEVEL_TIP' => 'Si est� marcado, el nivel de stock de un producto descargable no se reduce aunque se hayan efectuado compras.',
	'VM_USERGROUP_FORM_LBL' => 'A�adir/Editar a Grupo de usuario',
	'VM_USERGROUP_NAME' => 'Nombre de Grupo de usuario',
	'VM_USERGROUP_LEVEL' => 'Nivel de Grupo de usuario',
	'VM_USERGROUP_LEVEL_TIP' => 'Importante! Un n�mero grande significa <b>menos</b> permisos. El grupo <b>admin</b> es <em>nivel 0</em>, storeadmin es nivel 250, usuarios son nivel 500.',
	'VM_USERGROUP_LIST_LBL' => 'Lista de Grupo de usuario',
	'VM_ADMIN_CFG_COOKIE_CHECK' => 'Activar el chequeo de Cookies?',
	'VM_ADMIN_CFG_COOKIE_CHECK_EXPLAIN' => 'Si est� marcado, VirtueMart comprueba si el navegador del cliente acepta cookies o no. Esto es favorable para los usuarios, pero puede tener consecuencias negativas en la optimizaci�n SEO de la tienda.',
	'VM_CFG_REGISTRATION_TYPE' => 'Modo de registro de usuario',
	'VM_CFG_REGISTRATION_TYPE_TIP' => 'Elige el modo de registro para los usuarios de la tienda!<br />
<strong>Creaci�n de cuenta NORMAL</strong><br />
Este es el modo est�ndar de registro, donde el usuario elige sus propios datos de acceso.<br /><br />
<strong>Creaci�n de cuenta AUTOM�TICA</strong><br />
En este modo, el usuario no necesita elegir sus datos de acceso, ya que �stos ser�n creados autom�ticamente durante el registro y enviados a la direccion email indicada.
<br /><br />
<strong>Creaci�n de cuenta OPCIONAL</strong><br />
Esto permite al usuario elegir, si quiere registrarse o no. Si decide registrarse, deber� introducir los datos de acceso.
<br /><br />
<strong>SIN creaci�n de cuenta</strong><br />
Los usuarios no tendr�n ni podr�n registrarse si este modo est� activo.',
	'VM_CFG_REGISTRATION_TYPE_NORMAL_REGISTRATION' => 'Creaci�n de cuenta NORMAL',
	'VM_CFG_REGISTRATION_TYPE_SILENT_REGISTRATION' => 'Creaci�n de cuenta AUTOM�TICA',
	'VM_CFG_REGISTRATION_TYPE_OPTIONAL_REGISTRATION' => 'Creaci�n de cuenta OPCIONAL',
	'VM_CFG_REGISTRATION_TYPE_NO_REGISTRATION' => 'SIN creaci�n de cuenta',
	'VM_ADMIN_CFG_FEED_CONFIGURATION' => 'Suscripciones',
	'VM_ADMIN_CFG_FEED_ENABLE' => 'Activar suscripci�n a productos',
	'VM_ADMIN_CFG_FEED_ENABLE_TIP' => 'Si se activa, los clientes podr�n suscribirse a un Feed con los �ltimos productos (de todas o de alguna categor�a) de tu tienda.',
	'VM_ADMIN_CFG_FEED_CACHE' => 'Feed Cache Settings',
	'VM_ADMIN_CFG_FEED_CACHE_ENABLE' => 'Activar Cach�?',
	'VM_ADMIN_CFG_FEED_CACHETIME' => 'Tiempo de Cach� (segundos)',
	'VM_ADMIN_CFG_FEED_CACHE_TIP' => 'La cach� acelera el env�o de Feeds y reduce la carga del servidor, debido a que el feed se crea una sola vez y se guarda en un archivo.',
	'VM_ADMIN_CFG_FEED_SHOWPRICES' => 'Incluir el precio del producto en la descripci�n?',
	'VM_ADMIN_CFG_FEED_SHOWPRICES_TIP' => 'Si est� marcado, el precio estandar del producto ser� a�adido a la descripci�n',
	'VM_ADMIN_CFG_FEED_SHOWDESC' => 'Incluir la descripci�n del producto?',
	'VM_ADMIN_CFG_FEED_SHOWDESC_TIP' => 'Si est� marcado, la descripci�n del producto ser� a�adida al �tem del feed.',
	'VM_ADMIN_CFG_FEED_SHOWIMAGES' => 'Incluir im�genes en el Feed?',
	'VM_ADMIN_CFG_FEED_SHOWIMAGES_TIP' => 'Si est� marcado, las im�genes en miniatura ser�n inclu�das en el feed.',
	'VM_ADMIN_CFG_FEED_DESCRIPTION_TYPE' => 'Tipo de descripci�n del producto',
	'VM_ADMIN_CFG_FEED_DESCRIPTION_TYPE_TIP' => 'Elige el tipo de descripci�n que ser� inclu�da en el feed.',
	'VM_ADMIN_CFG_FEED_LIMITTEXT' => 'Limitar la descripci�n?',
	'VM_ADMIN_CFG_FEED_MAX_TEXT_LENGTH' => 'Longitud m�xima de descripci�n',
	'VM_ADMIN_CFG_MAX_TEXT_LENGTH_TIP' => 'Esta es la longitud m�xima en caracteres de cada �tem del feed.',
	'VM_ADMIN_CFG_FEED_TITLE_TIP' => 'T�tulo del Feed (el campo reservado {storename} contiene el nombre de la tienda).',
	'VM_ADMIN_CFG_FEED_TITLE_CATEGORIES_TIP' => 'T�tulo del feed de categor�a (\'{catname}\' es el campo reservado para el nombre de la categor�a, {storename} para el nombre de tienda).',
	'VM_ADMIN_CFG_FEED_TITLE' => 'T�tulo del Feed',
	'VM_ADMIN_CFG_FEED_TITLE_CATEGORIES' => 'T�tulo del Feed para categor�as',
	'VM_ADMIN_SECURITY' => 'Seguridad',
	'VM_ADMIN_SECURITY_SETTINGS' => 'Ajustes de seguridad',
	'VM_CFG_ENABLE_FEATURE' => 'Activar esta propiedad',
	'VM_CFG_CHECKOUT_SHOWSTEP_TIP' => 'Aqu� se pueden activar/desactivar y reordenar los pasos del proceso de compra. Puede mostrar m�ltiples pasos asignando el n�mero correspondiente.',
	'PHPSHOP_ADMIN_CFG_STORE_SHIPPING_METHOD_FLEX' => 'Flexible. Gastos de env�o fijos para un determinado valor base del pedido, a�adiendo porcentaje en caso de sobrepasar dicho valor.',
	'PHPSHOP_ADMIN_CFG_STORE_SHIPPING_METHOD_SHIPVALUE' => 'Gastos de env�o seg�n valor total. Se aplica un determinado gasto de env�o en cada caso para los importes totales especificados.',
	'VM_CFG_CHECKOUT_SHOWSTEPINCHECKOUT' => 'Mostrado en el paso: %s del proceso de compra.',
	'VM_ADMIN_ENCRYPTION_KEY' => 'LLave encriptada',
	'VM_ADMIN_ENCRYPTION_KEY_TIP' => 'Utilizada para almacenar y manejar datos sensibles (Informaci�n sobre tarjetas de cr�dito) encriptados en la base de datos.',
	'VM_ADMIN_STORE_CREDITCARD_DATA' => 'Almacenar informaci�n sobre tarjetas de cr�dito?',
	'VM_ADMIN_STORE_CREDITCARD_DATA_TIP' => 'VirtueMart almacena informaci�n sobre tarjetas de cr�dito de clientes encriptada en la base de datos. Esto tambi�n sucede si la informaci�n sobre la tarjeta de cr�dito es procesada por un servidor externo. <strong>Si no se necesita procesar esta informaci�n manualmente despu�s de un pedido, esta opci�n deber�a estar desactivada.</strong>',
	'VM_USERFIELDS_URL_ONLY' => 'Solo URL',
	'VM_USERFIELDS_HYPERTEXT_URL' => 'Hypertexto y URL',
	'VM_FIELDS_TEXTFIELD' => 'Campo de texto',
	'VM_FIELDS_CHECKBOX_SINGLE' => 'Casilla de verificaci�n (Single)',
	'VM_FIELDS_CHECKBOX_MULTIPLE' => 'Casilla de verificaci�n (Muliple)',
	'VM_FIELDS_DATE' => 'Fecha',
	'VM_FIELDS_DROPDOWN_SINGLE' => 'Desplegable (Single Select)',
	'VM_FIELDS_DROPDOWN_MULTIPLE' => 'Desplegable (Multi-Select)',
	'VM_FIELDS_EMAIL' => 'Direcci�n email',
	'VM_FIELDS_EUVATID' => 'EU VAT ID',
	'VM_FIELDS_EDITORAREA' => '�rea de texto Editor',
	'VM_FIELDS_TEXTAREA' => '�rea de texto',
	'VM_FIELDS_RADIOBUTTON' => 'Bot�n de selecci�n',
	'VM_FIELDS_WEBADDRESS' => 'Direcci�n web',
	'VM_FIELDS_DELIMITER' => '=== Fieldset delimiter ===',
	'VM_FIELDS_NEWSLETTER' => 'Suscripic�n a bolet�n',
	'VM_USERFIELDS_READONLY' => 'Leer-solo',
	'VM_USERFIELDS_SIZE' => 'Tama�o de campo',
	'VM_USERFIELDS_MAXLENGTH' => 'Longitud m�xima',
	'VM_USERFIELDS_DESCRIPTION' => 'Descripci�n, field-tip: texto o HTML',
	'VM_USERFIELDS_COLUMNS' => 'Columnas',
	'VM_USERFIELDS_ROWS' => 'Filas',
	'VM_USERFIELDS_EUVATID_MOVESHOPPER' => 'Mover un cliente al siguiente grupo de compradores una vez validada con �xito la EU VAT ID',
	'VM_USERFIELDS_ADDVALUES_TIP' => 'Usar la tabla debajo para a�adir nuevos valores.',
	'VM_ADMIN_CFG_DISPLAY' => 'Mostrar',
	'VM_ADMIN_CFG_LAYOUT' => 'Dise�o',
	'VM_ADMIN_CFG_THEME_SETTINGS' => 'Ajustes de plantilla',
	'VM_ADMIN_CFG_THEME_PARAMETERS' => 'Par�metros',
	'VM_ADMIN_ENCRYPTION_FUNCTION' => 'Funci�n de encriptado',
	'VM_ADMIN_ENCRYPTION_FUNCTION_TIP' => 'Aqu� puede seleccionar la funci�n de encriptado que se usar� para encriptar informaci�n sensible antes de ser almacenada en la base de datos. Se recomienda AES_ENCRYPT, por su alta seguridad. ENCODE no ofrece un ecriptado eficaz.',
	'SAVE_PERMISSIONS' => 'Guardar permisos',
	'VM_ADMIN_THEME_CFG_NOT_EXISTS' => 'El archivo de configuraci�n para esta plantilla no existe y no puede ser creado. No es posible la configuraci�n.',
	'VM_ADMIN_THEME_NOT_EXISTS' => 'La plantilla "{theme}" no existe.',
	'VM_USERFIELDS_ADDVALUE' => 'A�adir un valor',
	'VM_USERFIELDS_TITLE' => 'T�tulo',
	'VM_USERFIELDS_VALUE' => 'Valor',
	'VM_USER_FORM_LASTVISIT_NEVER' => 'Nunca',
	'VM_USER_FORM_TAB_GENERALINFO' => 'Informaci�n general de usuario',
	'VM_USER_FORM_LEGEND_USERDETAILS' => 'Detalles de usuario',
	'VM_USER_FORM_LEGEND_PARAMETERS' => 'Par�metros',
	'VM_USER_FORM_LEGEND_CONTACTINFO' => 'Informaci�n de contacto',
	'VM_USER_FORM_NAME' => 'Nombre',
	'VM_USER_FORM_USERNAME' => 'Nombre de usuario',
	'VM_USER_FORM_EMAIL' => 'Email',
	'VM_USER_FORM_NEWPASSWORD' => 'Nueva contrase�a',
	'VM_USER_FORM_VERIFYPASSWORD' => 'Verificar contrase�a',
	'VM_USER_FORM_GROUP' => 'Grupo',
	'VM_USER_FORM_BLOCKUSER' => 'Bloquear usuario',
	'VM_USER_FORM_RECEIVESYSTEMEMAILS' => 'Recibir emails del sistema',
	'VM_USER_FORM_REGISTERDATE' => 'Fecha de registro',
	'VM_USER_FORM_LASTVISITDATE' => 'Fecha de la �ltima visita',
	'VM_USER_FORM_NOCONTACTDETAILS_1' => 'No Contact details linked to this User:',
	'VM_USER_FORM_NOCONTACTDETAILS_2' => 'See \'Components -> Contact -> Manage Contacts\' for details.',
	'VM_USER_FORM_CONTACTDETAILS_NAME' => 'Nombre',
	'VM_USER_FORM_CONTACTDETAILS_POSITION' => 'Tratamiento',
	'VM_USER_FORM_CONTACTDETAILS_TELEPHONE' => 'Tel�fono',
	'VM_USER_FORM_CONTACTDETAILS_FAX' => 'Fax',
	'VM_USER_FORM_CONTACTDETAILS_CHANGEBUTTON' => 'Modificar detalles de contacto',
	'VM_ADMIN_CFG_LOGFILE_HEADER' => 'Configuraci�n del archivo LOG',
	'VM_ADMIN_CFG_LOGFILE_ENABLED' => 'Activar LOG?',
	'VM_ADMIN_CFG_LOGFILE_ENABLED_EXPLAIN' => 'Si est� desactivado, un "null" LOGGER ser� instanciado en su lugar, as� el vmFileLogger puede ser invocado sin errores.',
	'VM_ADMIN_CFG_LOGFILE_NAME' => 'Ruta al archivo LOG',
	'VM_ADMIN_CFG_LOGFILE_NAME_EXPLAIN' => 'Ruta al archivo LOG. Debe ser accesible y escribible.',
	'VM_ADMIN_CFG_LOGFILE_LEVEL' => 'Nivel de LOG',
	'VM_ADMIN_CFG_LOGFILE_LEVEL_EXPLAIN' => 'Los mensajes LOG que sobrepasen este umbral de prioridad ser�n ignorados.',
	'VM_ADMIN_CFG_LOGFILE_LEVEL_TIP' => 'TIP - 8',
	'VM_ADMIN_CFG_LOGFILE_LEVEL_DEBUG' => 'DEBUG - 7',
	'VM_ADMIN_CFG_LOGFILE_LEVEL_INFO' => 'INFO - 6',
	'VM_ADMIN_CFG_LOGFILE_LEVEL_NOTICE' => 'NOTICE - 5',
	'VM_ADMIN_CFG_LOGFILE_LEVEL_WARNING' => 'WARNING - 4',
	'VM_ADMIN_CFG_LOGFILE_LEVEL_ERR' => 'ERROR - 3',
	'VM_ADMIN_CFG_LOGFILE_LEVEL_CRIT' => 'CRITICAL - 2',
	'VM_ADMIN_CFG_LOGFILE_LEVEL_ALERT' => 'ALERT - 1',
	'VM_ADMIN_CFG_LOGFILE_LEVEL_EMERG' => 'EMERGENCY - 0',
	'VM_ADMIN_CFG_LOGFILE_FORMAT' => 'Formato de archivo LOG',
	'VM_ADMIN_CFG_LOGFILE_FORMAT_EXPLAIN' => 'Formato de archivo LOG individual para entradas de l�nea.',
	'VM_ADMIN_CFG_LOGFILE_FORMAT_EXPLAIN_EXTRA' => 'Los campos de formato de archivo LOG pueden incluir cualquiera de las siguientes: %{timestamp} %{ident} [%{priority}] [%{remoteip}] [%{username}] %{message} %{vmsessionid}.',
	'VM_ADMIN_CFG_LOGFILE_ERROR' => 'No se puede crear o acceder al LOG.  Por favor, contacte con el administrador del sistema.',
	'VM_ADMIN_CFG_DEBUG_MODE_ENABLED' => 'Activar el modo DEBUG?',
	'VM_ADMIN_CFG_DEBUG_IP_ENABLED' => 'Limitar a direcci�n IP determinada?',
	'VM_ADMIN_CFG_DEBUG_IP_ENABLED_EXPLAIN' => 'Limitar la vista DEBUG a una direcci�n IP determinada?',
	'VM_ADMIN_CFG_DEBUG_IP_ADDRESS' => 'Direcci�n IP cliente',
	'VM_ADMIN_CFG_DEBUG_IP_ADDRESS_EXPLAIN' => 'Si activa esta opci�n y escribe una direcci�n IP, la vista DEBUG solamente ser� mostrada a esa direcci�n IP. Otros clientes no ver�n el DEBUG.',
	'VM_FIELDMANAGER_SHOW_ON_SHIPPING' => 'Show in shipping form',
	'VM_USER_NOSHIPPINGADDR' => 'No shipping addresses.',
	'VM_UPDATE_CHECK_LBL' => 'Comprobar actualizaci�n de VirtueMart',
	'VM_UPDATE_CHECK_VERSION_INSTALLED' => 'Versi�n de Virtuemart instalada aqu�',
	'VM_UPDATE_CHECK_LATEST_VERSION' => '�ltima versi�n de Virtuemart',
	'VM_UPDATE_CHECK_CHECKNOW' => 'Comprobar actualizaciones ahora!',
	'VM_UPDATE_CHECK_DLUPDATE' => 'Descargar actualizaci�n',
	'VM_UPDATE_CHECK_CHECKING' => 'Comprobando...',
	'VM_UPDATE_CHECK_CHECK' => 'Comprobar',
	'VM_UPDATE_NOTDOWNLOADED' => 'La actualizaci�n no se pudo descargar.',
	'VM_UPDATE_PREVIEW_LBL' => 'Vista previa de actualizaci�n Virtuemart',
	'VM_UPDATE_WARNING_TITLE' => 'Aviso general',
	'VM_UPDATE_WARNING_TEXT' => 'Si realiza una actualizaci�n usando un parche, es posible que pueda da�ar su sitio,
si previamente ha realizado modificaciones en los archivos de Virtuemart. El proceso de parcheado sobreescribir� todos los archivos listados abajo - Si ha modificado c�digos en los archivos por su cuenta, es probable que se produzca un error o alguna inestabilidad en el sistema que pueda derivar en un funcionamiento defectuoso y perdida de datos.',
	'VM_UPDATE_PATCH_DETAILS' => 'Detalles del parche',
	'VM_UPDATE_PATCH_DESCRIPTION' => 'Descripci�n',
	'VM_UPDATE_PATCH_DATE' => 'Fecha de publicaci�n',
	'VM_UPDATE_PATCH_FILESTOUPDATE' => 'Archivos a actualizar',
	'VM_UPDATE_PATCH_STATUS' => 'Estado',
	'VM_UPDATE_PATCH_WRITABLE' => 'Escribible',
	'VM_UPDATE_PATCH_UNWRITABLE' => 'Archivo/Directorio no escribible',
	'VM_UPDATE_PATCH_QUERYTOEXEC' => 'Consultas a ejecutar en la base de datos',
	'VM_UPDATE_PATCH_CONFIRM_TEXT' => 'He le�do el <a href="#warning">Aviso</a> y estoy seguro de aplicar el parche a mi instalaci�n de Virtuemart.',
	'VM_UPDATE_PATCH_APPLY' => 'Aplicar parche ahora',
	'VM_UPDATE_PATCH_ERR_UNWRITABLE' => 'Hay algunos archivos/directorios no escribibles que necesitan ser actualizados. Por favor, corrija los permisos primero.',
	'VM_UPDATE_PATCH_PLEASEMARK' => 'Por favor, marca la casilla antes de aplicar el parche.',
	'VM_UPDATE_RESULT_TITLE' => 'Versi�n instalada actualmente',
	'VM_FIELDS_CAPTCHA' => 'Campo Captcha (usando com_securityimages)',
	'VM_FIELDS_AGEVERIFICATION' => 'Verificaci�n de edad (Date Select Fields)',
	'VM_FIELDS_AGEVERIFICATION_MINIMUM' => 'Especificar la edad m�nima'
); $VM_LANG->initModule( 'admin', $langvars );
?>