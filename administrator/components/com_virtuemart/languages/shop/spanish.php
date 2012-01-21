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
	'PHPSHOP_BROWSE_LBL' => 'Escoger Producto',
	'PHPSHOP_FLYPAGE_LBL' => 'Detalle del Producto',
	'PHPSHOP_PRODUCT_FORM_EDIT_PRODUCT' => 'Editar este producto',
	'PHPSHOP_DOWNLOADS_START' => 'empezar a descargar',
	'PHPSHOP_DOWNLOADS_INFO' => 'Por favor, introduzca la identificación de descarga que ha obtenido en e-mail y haga clic \'Start Download\'.',
	'PHPSHOP_WAITING_LIST_MESSAGE' => 'Por favor, introduzca su correo elctrónico a continuación para avisarle cuando el producto vuelva esté nuevamente disponible.
                                                                        No vamos a compartir, alquilar, vender ni utilizar su dirección de correo para ningun propósito excepto
                                                                        para avisarle cuando el producto vuelva a nuestro stock.<br /><br />Gracias!',
	'PHPSHOP_WAITING_LIST_THANKS' => 'Agredecemos su espera! ! <br />En cuanto dispongamos del producto deseado, le avisaremos.',
	'PHPSHOP_WAITING_LIST_NOTIFY_ME' => 'Avisarme!',
	'PHPSHOP_SEARCH_ALL_CATEGORIES' => 'Buscar en todas las categorías',
	'PHPSHOP_SEARCH_ALL_PRODINFO' => 'Buscar en todas las características del producto',
	'PHPSHOP_SEARCH_PRODNAME' => 'Buscar en el nombre del producto',
	'PHPSHOP_SEARCH_MANU_VENDOR' => '--',
	'PHPSHOP_SEARCH_DESCRIPTION' => 'Sólo en la descripción del producto',
	'PHPSHOP_SEARCH_AND' => 'y',
	'PHPSHOP_SEARCH_NOT' => 'no',
	'PHPSHOP_SEARCH_TEXT1' => 'La primera lista desplegable permite seleccionar la categoría para filtrar mejor su búsqueda.
        La segunda lista desplegable permite limitar la búsquedad a una sección específica de información del producto (e.j. Nombre).
        Una vez seleccionadas estas opciones, introduzca la palabra clave para comenzar la búsqueda. ',
	'PHPSHOP_SEARCH_TEXT2' => ' Puede definir mejor su búsqueda, añadiendo una segunda palabra clave y seleccionando Y o NO.
        Seleccionando Y significa que ambas palabras deben estar presentes en el producto buscado.
        Selecccionando NO significa que el producto buscado solamente deberá contener la primera palabra clave. ',
	'PHPSHOP_CONTINUE_SHOPPING' => 'Continuar comprando',
	'PHPSHOP_AVAILABLE_IMAGES' => 'Disponible Imagenes para',
	'PHPSHOP_BACK_TO_DETAILS' => 'Volver a detalles del producto',
	'PHPSHOP_IMAGE_NOT_FOUND' => 'no se ha encontrado la imagen!',
	'PHPSHOP_PARAMETER_SEARCH_TEXT1' => 'Do you will find products according to technical parametrs?<BR>You can used any prepared form:',
	'PHPSHOP_PARAMETER_SEARCH_NO_PRODUCT_TYPE' => 'Lo sentimos. No existen Tipos de Productos para buscar.',
	'PHPSHOP_PARAMETER_SEARCH_BAD_PRODUCT_TYPE' => 'Lo sentimos. No existen Tipos de Productos publicados con ese nombre.',
	'PHPSHOP_PARAMETER_SEARCH_IS_LIKE' => 'Similar a',
	'PHPSHOP_PARAMETER_SEARCH_IS_NOT_LIKE' => 'NO similar a',
	'PHPSHOP_PARAMETER_SEARCH_FULLTEXT' => 'Búsqueda texto completo',
	'PHPSHOP_PARAMETER_SEARCH_FIND_IN_SET_ALL' => 'Todo seleccionado',
	'PHPSHOP_PARAMETER_SEARCH_FIND_IN_SET_ANY' => 'Alguno seleccionado',
	'PHPSHOP_PARAMETER_SEARCH_RESET_FORM' => 'Restablecer formulario',
	'PHPSHOP_PRODUCT_NOT_FOUND' => 'Lo sentimos, NO se ha encontrado el producto requerido!',
	'PHPSHOP_PRODUCT_PACKAGING1' => 'Número {unit}s en embalaje:',
	'PHPSHOP_PRODUCT_PACKAGING2' => 'Número {unit}s en caja:',
	'PHPSHOP_CART_PRICE_PER_UNIT' => 'Precio por caja',
	'VM_PRODUCT_ENQUIRY_LBL' => 'Solicitar información',
	'VM_RECOMMEND_FORM_LBL' => 'Recomendar a alguien',
	'PHPSHOP_EMPTY_YOUR_CART' => 'Vaciar Cesta',
	'VM_RETURN_TO_PRODUCT' => 'Volver a producto',
	'EMPTY_CATEGORY' => 'Esta categoría está actualmente vacía.',
	'ENQUIRY' => 'Consulta',
	'NAME_PROMPT' => 'Introduzca su nombre',
	'EMAIL_PROMPT' => 'Correo Electrónico',
	'MESSAGE_PROMPT' => 'Escriba su mensaje',
	'SEND_BUTTON' => 'Enviar',
	'THANK_MESSAGE' => 'Gracias por su consulta. Contactaremos con usted lo antes posible.',
	'PROMPT_CLOSE' => 'Cerrar',
	'VM_RECOVER_CART_REPLACE' => 'Sustituir Cesta actual por Cesta guardada',
	'VM_RECOVER_CART_MERGE' => 'Añadir Cesta guardada a Cesta actual',
	'VM_RECOVER_CART_DELETE' => 'Eliminar Cesta guardada',
	'VM_EMPTY_YOUR_CART_TIP' => 'Eliminar todo el contenido de su Cesta',
	'VM_SAVED_CART_TITLE' => 'Cesta Guardada',
	'VM_SAVED_CART_RETURN' => 'Volver'
); $VM_LANG->initModule( 'shop', $langvars );
?>