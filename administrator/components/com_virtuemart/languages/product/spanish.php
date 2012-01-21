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
	'PHPSHOP_MODULE_LIST_ORDER' => 'Pedido',
	'PHPSHOP_PRODUCT_INVENTORY_LBL' => 'Inventario de Productos',
	'PHPSHOP_PRODUCT_INVENTORY_STOCK' => 'el stock',
	'PHPSHOP_PRODUCT_INVENTORY_WEIGHT' => 'Peso',
	'PHPSHOP_PRODUCT_LIST_PUBLISH' => 'Publicar',
	'PHPSHOP_PRODUCT_LIST_SEARCH_BY_DATE' => 'Buscar producto',
	'PHPSHOP_PRODUCT_LIST_SEARCH_BY_DATE_TYPE_PRODUCT' => 'modificado',
	'PHPSHOP_PRODUCT_LIST_SEARCH_BY_DATE_TYPE_PRICE' => 'con precio modificado',
	'PHPSHOP_PRODUCT_LIST_SEARCH_BY_DATE_TYPE_WITHOUTPRICE' => 'sin precio',
	'PHPSHOP_PRODUCT_LIST_SEARCH_BY_DATE_AFTER' => 'Despu�s',
	'PHPSHOP_PRODUCT_LIST_SEARCH_BY_DATE_BEFORE' => 'Antes',
	'PHPSHOP_PRODUCT_FORM_SHOW_FLYPAGE' => 'Ver Detalles de productos en esta tienda',
	'PHPSHOP_PRODUCT_FORM_NEW_PRODUCT_LBL' => 'Nuevo Producto',
	'PHPSHOP_PRODUCT_FORM_PRODUCT_INFO_LBL' => 'Informaci�n del Producto',
	'PHPSHOP_PRODUCT_FORM_PRODUCT_STATUS_LBL' => 'Estado',
	'PHPSHOP_PRODUCT_FORM_PRODUCT_DIM_WEIGHT_LBL' => 'Dimensiones y Peso',
	'PHPSHOP_PRODUCT_FORM_PRODUCT_IMAGES_LBL' => 'Imagenes',
	'PHPSHOP_PRODUCT_FORM_UPDATE_ITEM_LBL' => 'Actualizar Art�culo',
	'PHPSHOP_PRODUCT_FORM_ITEM_INFO_LBL' => 'Informaci�n',
	'PHPSHOP_PRODUCT_FORM_ITEM_STATUS_LBL' => 'Estado',
	'PHPSHOP_PRODUCT_FORM_ITEM_DIM_WEIGHT_LBL' => 'Dimensiones y Peso',
	'PHPSHOP_PRODUCT_FORM_ITEM_IMAGES_LBL' => 'Imagenes',
	'PHPSHOP_PRODUCT_FORM_IMAGE_UPDATE_LBL' => 'Para actualizar la imagen actual, ingrese el directorio a la nueva imagen.',
	'PHPSHOP_PRODUCT_FORM_IMAGE_DELETE_LBL' => 'Introduzca ',
	'PHPSHOP_PRODUCT_FORM_PRODUCT_ITEMS_LBL' => 'Art�culos',
	'PHPSHOP_PRODUCT_FORM_ITEM_ATTRIBUTES_LBL' => 'Cualidades',
	'PHPSHOP_PRODUCT_FORM_DELETE_PRODUCT_MSG' => 'Esta seguro de que desea borrar este producto\ny los Art�culos relacionados a el?',
	'PHPSHOP_PRODUCT_FORM_DELETE_ITEM_MSG' => 'Esta seguro de que desea borrar este Art�culo?',
	'PHPSHOP_PRODUCT_FORM_MANUFACTURER' => 'Fabricante',
	'PHPSHOP_PRODUCT_FORM_SKU' => 'REF',
	'PHPSHOP_PRODUCT_FORM_NAME' => 'Nombre',
	'PHPSHOP_PRODUCT_FORM_CATEGORY' => 'Categor�a',
	'PHPSHOP_PRODUCT_FORM_AVAILABLE_DATE' => 'Fecha de Disponibilidad',
	'PHPSHOP_PRODUCT_FORM_SPECIAL' => 'Especial',
	'PHPSHOP_PRODUCT_FORM_DISCOUNT_TYPE' => 'Tipo de Descuento',
	'PHPSHOP_PRODUCT_FORM_PUBLISH' => 'Publicar?',
	'PHPSHOP_PRODUCT_FORM_LENGTH' => 'Longitud',
	'PHPSHOP_PRODUCT_FORM_WIDTH' => 'Anchura',
	'PHPSHOP_PRODUCT_FORM_HEIGHT' => 'Altura',
	'PHPSHOP_PRODUCT_FORM_DIMENSION_UOM' => 'Unidad de Medida',
	'PHPSHOP_PRODUCT_FORM_WEIGHT_UOM' => 'Unidad de Medida',
	'PHPSHOP_PRODUCT_FORM_FULL_IMAGE' => 'Imagen Completa',
	'PHPSHOP_PRODUCT_FORM_WEIGHT_UOM_DEFAULT' => 'kg',
	'PHPSHOP_PRODUCT_FORM_DIMENSION_UOM_DEFAULT' => 'cm',
	'PHPSHOP_PRODUCT_FORM_PACKAGING' => 'Unidades en embalaje',
	'PHPSHOP_PRODUCT_FORM_PACKAGING_DESCRIPTION' => 'Aqu� puede introducir la cantidad de unidades por embalaje. (max. 65535)',
	'PHPSHOP_PRODUCT_FORM_BOX' => 'Unidades en caja',
	'PHPSHOP_PRODUCT_FORM_BOX_DESCRIPTION' => 'Aqu� puede introducir la cantidad de unidades por paquete. (max. 65535)',
	'PHPSHOP_PRODUCT_DISPLAY_ADD_PRODUCT_LBL' => 'Mostrar Producto A�adido',
	'PHPSHOP_PRODUCT_DISPLAY_UPDATE_PRODUCT_LBL' => 'Mostrar Producto Actualizado',
	'PHPSHOP_PRODUCT_DISPLAY_ADD_ITEM_LBL' => 'Mostrar Art�culo A�adido',
	'PHPSHOP_PRODUCT_DISPLAY_UPDATE_ITEM_LBL' => 'Mostrar Art�culo Actualizado',
	'PHPSHOP_CATEGORY_FORM_LBL' => 'Formulario de Informaci�n',
	'PHPSHOP_CATEGORY_FORM_NAME' => 'Nombre de Categor�a',
	'PHPSHOP_CATEGORY_FORM_PARENT' => 'Pariente de Categor�a',
	'PHPSHOP_CATEGORY_FORM_DESCRIPTION' => 'Descripci�n de Categor�a',
	'PHPSHOP_CATEGORY_FORM_PUBLISH' => 'Publicar?',
	'PHPSHOP_CATEGORY_FORM_FLYPAGE' => 'Categor�a Detallada',
	'PHPSHOP_ATTRIBUTE_LIST_LBL' => 'Lista de Cualidad por',
	'PHPSHOP_ATTRIBUTE_LIST_NAME' => 'Nombre de Cualidad',
	'PHPSHOP_ATTRIBUTE_LIST_ORDER' => 'Cualidad de Pedido',
	'PHPSHOP_ATTRIBUTE_FORM_LBL' => 'Formulario de Cualidad',
	'PHPSHOP_ATTRIBUTE_FORM_NEW_FOR_PRODUCT' => 'Nueva Cualidad por Producto',
	'PHPSHOP_ATTRIBUTE_FORM_UPDATE_FOR_PRODUCT' => 'Cualidad Actualizada por Producto',
	'PHPSHOP_ATTRIBUTE_FORM_NEW_FOR_ITEM' => 'Nueva Cualidad por Art�culo',
	'PHPSHOP_ATTRIBUTE_FORM_UPDATE_FOR_ITEM' => 'Cualidad Actualizada por Art�culo',
	'PHPSHOP_ATTRIBUTE_FORM_NAME' => 'Nombre de cualidad',
	'PHPSHOP_ATTRIBUTE_FORM_ORDER' => 'Cualidad de Pedido',
	'PHPSHOP_PRICE_LIST_FOR_LBL' => 'Precios por',
	'PHPSHOP_PRICE_LIST_GROUP_NAME' => 'Grupo de Lista del Precio',
	'PHPSHOP_PRICE_LIST_PRICE' => 'Lista de Precio',
	'PHPSHOP_PRODUCT_LIST_CURRENCY' => 'Moneda de Lista del Producto',
	'PHPSHOP_PRICE_FORM_LBL' => 'Informaci�n',
	'PHPSHOP_PRICE_FORM_NEW_FOR_PRODUCT' => 'Nuevo Precio por Producto',
	'PHPSHOP_PRICE_FORM_UPDATE_FOR_PRODUCT' => 'Actualizar Precio por Producto',
	'PHPSHOP_PRICE_FORM_NEW_FOR_ITEM' => 'Nuevo Precio por Art�culo',
	'PHPSHOP_PRICE_FORM_UPDATE_FOR_ITEM' => 'Actualizar Precio por Art�culo',
	'PHPSHOP_PRICE_FORM_PRICE' => 'Precio',
	'PHPSHOP_PRICE_FORM_CURRENCY' => 'Moneda',
	'PHPSHOP_PRICE_FORM_GROUP' => 'Grupo de Compradores',
	'PHPSHOP_LEAVE_BLANK' => '(dejar en BLANCO si usted tiene<br />no archivo php individual)',
	'PHPSHOP_PRODUCT_FORM_ITEM_LBL' => 'articulo',
	'PHPSHOP_PRODUCT_DISCOUNT_STARTDATE' => 'Fecha de comienzo del descuento',
	'PHPSHOP_PRODUCT_DISCOUNT_STARTDATE_TIP' => 'Especifique el d�a cuando empieze el descuento',
	'PHPSHOP_PRODUCT_DISCOUNT_ENDDATE' => 'Fecha final de descuento',
	'PHPSHOP_PRODUCT_DISCOUNT_ENDDATE_TIP' => 'Especifique el d�a que se acaba el descuento',
	'PHPSHOP_FILEMANAGER_PUBLISHED' => 'publicado?',
	'PHPSHOP_FILES_LIST' => 'FileManager::Imagen/Lista del archivo para',
	'PHPSHOP_FILES_LIST_FILENAME' => 'Nombre del archivo',
	'PHPSHOP_FILES_LIST_FILETITLE' => 'Titulo del archivo',
	'PHPSHOP_FILES_LIST_FILETYPE' => 'Tipo del archivo',
	'PHPSHOP_FILES_LIST_FULL_IMG' => 'Imagen Completa',
	'PHPSHOP_FILES_LIST_THUMBNAIL_IMG' => 'Thumbnail Image',
	'PHPSHOP_FILES_FORM' => 'Subir archivo para',
	'PHPSHOP_FILES_FORM_CURRENT_FILE' => 'Archivo actual',
	'PHPSHOP_FILES_FORM_FILE' => 'Archivo',
	'PHPSHOP_FILES_FORM_IMAGE' => 'Imagen',
	'PHPSHOP_FILES_FORM_UPLOAD_TO' => 'Subir a',
	'PHPSHOP_FILES_FORM_UPLOAD_IMAGEPATH' => 'defecto Producto paso de Imagen',
	'PHPSHOP_FILES_FORM_UPLOAD_OWNPATH' => 'Especifique la localizaci�n del archivo',
	'PHPSHOP_FILES_FORM_UPLOAD_DOWNLOADPATH' => 'paso de descargar (e.j. para vender downloadables!)',
	'PHPSHOP_FILES_FORM_AUTO_THUMBNAIL' => 'Auto-Crear Thumbnail?',
	'PHPSHOP_FILES_FORM_FILE_PUBLISHED' => 'Est� publicado el archivo?',
	'PHPSHOP_FILES_FORM_FILE_TITLE' => 'Titulo del archivo (que ven los clientes)',
	'PHPSHOP_FILES_FORM_FILE_URL' => 'Archivo URL (optional)',
	'PHPSHOP_PRODUCT_FORM_AVAILABILITY_TOOLTIP1' => 'Completar cualquir texto aqu� que estar� mostrado para el cliente en el detalle del producto. <br />e.j.: 24h, 48 hours, 3 - 5 d�as, en el pedido.....',
	'PHPSHOP_PRODUCT_FORM_AVAILABILITY_TOOLTIP2' => 'O seleccionar una Imagen para mostrar en la p�gina del detalle (flypage).<br />Las imagenes residen en el directorio <i>%s</i><br />',
	'PHPSHOP_PRODUCT_FORM_CUSTOM_ATTRIBUTE_LIST_EXAMPLES' => '<h4>Ejemplos para el formato de la lista de la aduana stributo:</h4>
        <span class="sectionname"><strong>Name;Extras;</strong>...</span>',
	'PHPSHOP_IMAGE_ACTION' => 'Image Action',
	'PHPSHOP_PARAMETERS_LBL' => 'Parameters',
	'PHPSHOP_PRODUCT_TYPE_LBL' => 'Product Type',
	'PHPSHOP_PRODUCT_PRODUCT_TYPE_LIST_LBL' => 'Lista de tipo de producto para',
	'PHPSHOP_PRODUCT_PRODUCT_TYPE_FORM_LBL' => 'A�adir tipo de producto para',
	'PHPSHOP_PRODUCT_PRODUCT_TYPE_FORM_PRODUCT_TYPE' => 'Tipo de producto',
	'VM_PRODUCT_PRODUCT_TYPE_ADD_MULTIPLE_PRODUCTS' => ' M�ltiples productos',
	'PHPSHOP_PRODUCT_TYPE_FORM_NAME' => 'Nombre de tipo de producto',
	'PHPSHOP_PRODUCT_TYPE_FORM_DESCRIPTION' => 'Descripci�n de tipo de producto',
	'PHPSHOP_PRODUCT_TYPE_FORM_PARAMETERS' => 'Par�metros',
	'PHPSHOP_PRODUCT_TYPE_FORM_LBL' => 'Informaci�n sobre tipo de producto',
	'PHPSHOP_PRODUCT_TYPE_FORM_PUBLISH' => 'Publicar?',
	'PHPSHOP_PRODUCT_TYPE_FORM_BROWSEPAGE' => 'Navegaci�n para tipo de producto',
	'PHPSHOP_PRODUCT_TYPE_FORM_FLYPAGE' => 'Product Type Flypage',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_LIST_LBL' => 'Par�metros del Tipo de producto',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_LBL' => 'Informaci�n de par�metro',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_NOT_FOUND' => 'Tipo de producto no encontrado!',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_NAME' => 'Nombre de par�metro',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_NAME_DESCRIPTION' => 'This name will be column name of table. Must be unicate and without space.<br/>For example: main_material',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_LABEL' => 'Etiqueta de par�metro',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_INTEGER' => 'Integer',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_TEXT' => 'Texto',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_SHORTTEXT' => 'Text breve',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_FLOAT' => 'Float',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_CHAR' => 'Char',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_DATETIME' => 'Fecha y Hora',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_DATE' => 'Fecha',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_TIME' => 'Hora',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_BREAK' => 'L�nea de separaci�n',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_TYPE_MULTIVALUE' => 'Valores m�ltiples',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_VALUES' => 'Valores posibles',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_MULTISELECT' => 'Mostrar valores posibles como selecci�n m�ltiple?',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_VALUES_DESCRIPTION' => '<strong>Si se introducen valores posibles, el par�metro solo puede tener esos valores. Ejemplo para valores posibles:</strong><br/><span class="sectionname">Acero;Mader;Pl�stico;...</span>',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_DEFAULT' => 'Valor por defecto',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_DEFAULT_HELP_TEXT' => 'For Parameter Default Value use this format:<ul><li>Date: YYYY-MM-DD</li><li>Time: HH:MM:SS</li><li>Date & Time: YYYY-MM-DD HH:MM:SS</li></ul>',
	'PHPSHOP_PRODUCT_TYPE_PARAMETER_FORM_UNIT' => 'Unidad',
	'PHPSHOP_PRODUCT_CLONE' => 'Clonar Productos',
	'PHPSHOP_HIDE_OUT_OF_STOCK' => 'Hide out of stock products',
	'PHPSHOP_FEATURED_PRODUCTS_LIST_LBL' => 'Productos destacados y con descuento',
	'PHPSHOP_FEATURED' => 'Featured',
	'PHPSHOP_SHOW_FEATURED_AND_DISCOUNTED' => 'destacado Y descontado',
	'PHPSHOP_SHOW_DISCOUNTED' => 'Productos con descuento',
	'PHPSHOP_FILTER' => 'Filter',
	'PHPSHOP_PRODUCT_FORM_DISCOUNTED_PRICE' => 'Precio con descuento',
	'PHPSHOP_PRODUCT_FORM_DISCOUNTED_PRICE_TIP' => 'Here you can override the discount setting fill in a special discount price for this product.<br/>
The Shop will create a new discount record from the discounted price.',
	'PHPSHOP_PRODUCT_LIST_QUANTITY_START' => 'Cantidad Inicial',
	'PHPSHOP_PRODUCT_LIST_QUANTITY_END' => 'Cantidad Final',
	'VM_PRODUCTS_MOVE_LBL' => 'Mover productos de una categor�a a otra',
	'VM_PRODUCTS_MOVE_LIST' => 'Has seleccionado mover los siguientes %s productos',
	'VM_PRODUCTS_MOVE_TO_CATEGORY' => 'Mover a la siguiente categor�a',
	'VM_PRODUCT_LIST_REORDER_TIP' => 'Select a product category to reorder products in a category',
	'VM_REVIEW_FORM_LBL' => 'A�adir revisi�n',
	'PHPSHOP_REVIEW_EDIT' => 'A�adir/Editar una Revisi�n',
	'SEL_CATEGORY' => 'Seleccionar una categor�a',
	'VM_PRODUCT_FORM_MIN_ORDER' => 'Cantidad m�nima de compra',
	'VM_PRODUCT_FORM_MAX_ORDER' => 'Cantidad m�xima de compra',
	'VM_DISPLAY_TABLE_HEADER' => 'Mostrar cabecera de tabla',
	'VM_DISPLAY_LINK_TO_CHILD' => 'Enlace a producto hijo de la lista',
	'VM_DISPLAY_INCLUDE_PRODUCT_TYPE' => 'Incluir tipo de producto con hijo',
	'VM_DISPLAY_USE_LIST_BOX' => 'Usar lista para productos hijo',
	'VM_DISPLAY_LIST_STYLE' => 'Estilo de lista',
	'VM_DISPLAY_USE_PARENT_LABEL' => 'Usar ajustes padre:',
	'VM_DISPLAY_LIST_TYPE' => 'Lista:',
	'VM_DISPLAY_QUANTITY_LABEL' => 'Cantidad:',
	'VM_DISPLAY_QUANTITY_DROPDOWN_LABEL' => 'Valores de casilla desplegable',
	'VM_DISPLAY_CHILD_DESCRIPTION' => 'Mostrar descripci�n hijo',
	'VM_DISPLAY_DESC_WIDTH' => 'Anchura de la descripci�n del hijo',
	'VM_DISPLAY_ATTRIB_WIDTH' => 'Anchura del atributo del hijo',
	'VM_DISPLAY_CHILD_SUFFIX' => 'Sufijo de la clase del hijo',
	'VM_INCLUDED_PRODUCT_ID' => 'ID de productos a incluir',
	'VM_EXTRA_PRODUCT_ID' => 'Extra IDs',
	'PHPSHOP_DISPLAY_RADIOBOX' => 'Usar bot�n radio',
	'PHPSHOP_PRODUCT_FORM_ITEM_DISPLAY_LBL' => 'Mostrar opciones',
	'PHPSHOP_DISPLAY_USE_PARENT' => 'Sobreescribir valores para mostrar productos hijo y usar padres',
	'PHPSHOP_DISPLAY_NORMAL' => 'Casilla est�ndar de cantidad',
	'PHPSHOP_DISPLAY_HIDE' => 'Ocultar casilla de cantidad',
	'PHPSHOP_DISPLAY_DROPDOWN' => 'Usar casilla desplegable',
	'PHPSHOP_DISPLAY_CHECKBOX' => 'Usar casilla de verificaci�n',
	'PHPSHOP_DISPLAY_ONE' => 'Un solo bot�n de "Comprar"',
	'PHPSHOP_DISPLAY_MANY' => 'Bot�n de "Comprar" para cada hijo',
	'PHPSHOP_DISPLAY_START' => 'Valor inicial',
	'PHPSHOP_DISPLAY_END' => 'Valor final',
	'PHPSHOP_DISPLAY_STEP' => 'Valor de paso',
	'PRODUCT_WAITING_LIST_TAB' => 'Lista de espera',
	'PRODUCT_WAITING_LIST_USERLIST' => 'Clientes pendientes de ser notificados cuando este producto vuelva al stock',
	'PRODUCT_WAITING_LIST_NOTIFYUSERS' => 'Notificar a esos clientes ahora (cuando el n�mero de productos en stock sea actualizado)',
	'PRODUCT_WAITING_LIST_NOTIFIED' => 'notificado',
	'VM_PRODUCT_FORM_AVAILABILITY_SELECT_IMAGE' => 'Seleccionar imagen',
	'VM_PRODUCT_RELATED_SEARCH' => 'Buscar productos o categor�as aqu�:',
	'VM_FILES_LIST_ROLE' => 'Role',
	'VM_FILES_LIST_UP' => 'Arriba',
	'VM_FILES_LIST_GO_UP' => 'Ir arriba',
	'VM_CATEGORY_FORM_PRODUCTS_PER_ROW' => 'Mostrar x products por fila',
	'VM_CATEGORY_FORM_BROWSE_PAGE' => 'Category Browse Page',
	'VM_PRODUCT_CLONE_OPTIONS_TAB' => 'Clone Product Otions',
	'VM_PRODUCT_CLONE_OPTIONS_LBL' => 'Also clone these Child Items',
	'VM_PRODUCT_LIST_MEDIA' => 'Media',
	'VM_REVIEW_LIST_NAMEDATE' => 'Nombre/Fecha',
	'VM_PRODUCT_SELECT_ONE_OR_MORE' => 'Seleccionar uno o m�s productos',
	'VM_PRODUCT_SEARCHING' => 'Buscando...',
	'PHPSHOP_PRODUCT_FORM_ATTRIBUTE_LIST_EXAMPLES' => '<h4>Ejemplos para la lista de atributos:</h4>
T�tulo = Color, Propiedad = Rojo ; Hacer clic en "Nueva Propiedad" para a�adir m�s colores: Verde ; Despu�s hacer clic en "Nuevo Atributo" para a�adir un atributo nuevo, etc,.
<h4>Atributos avanzados para los precios:</h4>
Precio = +10 esto se a�ade al precio establecido.<br />  Precio = -10 para restar al precio establecido.<br />  Precio = 10 para mantener esta cantidad.',
	'VM_FILES_FORM_PRODUCT_IMAGE' => 'Imagen del producto (completa y miniatura)',
	'VM_FILES_FORM_DOWNLOADABLE' => 'Archivo descargable (para ser vendido!)',
	'VM_FILES_FORM_RESIZE_IMAGE' => 'Redimensionar imagen completa?'
); $VM_LANG->initModule( 'product', $langvars );
?>