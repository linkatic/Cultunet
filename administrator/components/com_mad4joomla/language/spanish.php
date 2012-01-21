<?php
	/**
	* @version $Id: Mooj 10041 2008-03-18 21:48:13Z fahrettinkutyol $
	* @package joomla
	* @copyright Copyright (C) 2008 Mad4Media. All rights reserved.
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
	* Joomla! is free software. This version may have been modified pursuant
	* to the GNU General Public License, and as distributed it includes or
	* is derivative of works licensed under the GNU General Public License or
	* other free or open source software licenses.
	* See COPYRIGHT.php for copyright notices and details.
	* @copyright (C) mad4media , www.mad4media.de
	*/

	/**  SPANISH VERSION.*/
	
	/**  TRANSLATED BY Jorge Mayor Lázaro http://www.virtualizza.es  */


	defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
	
	$m4j_lang_elements[1]= 'Checkbox';
	$m4j_lang_elements[2]= 'Selección Sí/No ';
	$m4j_lang_elements[10]= 'Fecha';
	$m4j_lang_elements[20]= 'Campo de texto';
	$m4j_lang_elements[21]= 'Area de texto';
	$m4j_lang_elements[30]= 'Menu(elección simple)';
	$m4j_lang_elements[31]= 'Menú selectivo(elección simple)';
	$m4j_lang_elements[32]= 'Botón de radio(elección simple)';
	$m4j_lang_elements[33]= 'Grupo Checkbox(elección múltiple)';
	$m4j_lang_elements[34]= 'Lista(elección múltiple)';
	
	
	define('M4J_LANG_FORMS','Formularios');
	define('M4J_LANG_TEMPLATES','Plantillas');
	define('M4J_LANG_CATEGORY','Categorías');
	define('M4J_LANG_CONFIG','Configuración');
	define('M4J_LANG_HELP','Info & Ayuda');
	define('M4J_LANG_CANCEL','Cancelar');
	define('M4J_LANG_PROCEED','Proceder');
	define('M4J_LANG_SAVE','Guardar');
	define('M4J_LANG_NEW_FORM',' Nuevo Formulario');
	define('M4J_LANG_NEW_TEMPLATE','Nueva Plantilla');
	define('M4J_LANG_ADD','Añadir');
	define('M4J_LANG_EDIT_NAME','Editar nombre y descripción de esta plantilla');
	define('M4J_LANG_NEW_TEMPLATE_LONG','Nueva plantilla');
	define('M4J_LANG_TEMPLATE_NAME','Nombre de esta plantilla');
	define('M4J_LANG_TEMPLATE_NAME_EDIT','Editar el nombre de esta plantilla');
	define('M4J_LANG_TEMPLATE_DESCRIPTION','Descripción corta(para uso interno. Se puede dejar vacía)');
	define('M4J_LANG_TEMPLATE_DESCRIPTION_EDIT','Editar descripción corta');
	define('M4J_LANG_DELETE','Borrar');
	define('M4J_LANG_DELETE_CONFIRM','¿Desea borrar este artículo?');
	define('M4J_LANG_NEW_CATEGORY','Nueva categoría');
	define('M4J_LANG_NAME','Nombre');
	define('M4J_LANG_SHORTDESCRIPTION','Descripción corta');
	define('M4J_LANG_ID','ID');
	define('M4J_LANG_ITEMS','Artículos');
	define('M4J_LANG_EDIT','Editar');
	define('M4J_LANG_EDIT_TEMPLATE_ITEMS','Artículos -> Editar');
	define('M4J_LANG_TEMPLATE_NAME_REQUIRED','¡Por favor, introduzcsa un nombre para esta plantilla!');
	define('M4J_LANG_AT_LEAST_ONE','¡Este artículo no se puede borrar!<br/>Tiene que haber por lo menos un elemento.');	

	
		define('M4J_LANG_EDIT_ELEMENT','Editar elemento de plantilla: ');
	define('M4J_LANG_CATEGORY_NAME_ERROR','Por favor, inserte un nombre de categoría');
	define('M4J_LANG_NONE_LEGAL_EMAIL','Por favor, inserte un email válido o déjelo vacío.<br/>');
	define('M4J_LANG_EMAIL','Email');
	define('M4J_LANG_POSITION','Ordenación');
	define('M4J_LANG_ACTIVE','Activo');
	define('M4J_LANG_UP','Arriba');
	define('M4J_LANG_DOWN','Abajo');
	define('M4J_LANG_EDIT_CATEGORY','Editar categoría');
	define('M4J_LANG_TEMPLATE_ELEMENTS','Elementos de plantilla: ');
	define('M4J_LANG_NEW_ELEMENT_LONG','Insertar nuevo elemento de plantilla: ');	
	define('M4J_LANG_ELEMENT_NO_QUESTION_ERROR','Por favor, inserte una pregunta.');
	define('M4J_LANG_REQUIRED','Obligatorio');
	define('M4J_LANG_QUESTION','Pregunta');
	define('M4J_LANG_TYPE','Tipo');
	define('M4J_LANG_YES','Sí');		
	define('M4J_LANG_NO','No');	
	define('M4J_LANG_ALL_FORMS','Todos los formularios');
	define('M4J_LANG_NO_CATEGORYS','Sin categoría');
	define('M4J_LANG_FORMS_OF_CATEGORY','Formularios de la categoría: ');
	define('M4J_LANG_PREVIEW','Previsualizar');
	define('M4J_LANG_DO_COPY','Copiar');		
	define('M4J_LANG_COPY','Copiar');
	define('M4J_LANG_VERTICAL','Vertical');
	define('M4J_LANG_HORIZONTAL','Horizontal');
	define('M4J_LANG_EXAMPLE','Ejemplo');
	define('M4J_LANG_CHECKBOX','Botón');	
	define('M4J_LANG_DATE','Fecha');
	define('M4J_LANG_TEXTFIELD','Campo de texto');
	define('M4J_LANG_OPTIONS','Elección especificada');
	define('M4J_LANG_CHECKBOX_DESC','Una pregunta Sí/No.');
	define('M4J_LANG_DATE_DESC','El usuario tiene que introducir una fecha.');
	define('M4J_LANG_TEXTFIELD_DESC','El usuario tiene que introducir un texto.');
	define('M4J_LANG_OPTIONS_DESC','El usuario selecciona una o más respuestas a parte de las referencias especificadas. ');
	define('M4J_LANG_CLOSE_PREVIEW','Cerrar previsualización');
	define('M4J_LANG_Q_WIDTH','Ancho de la columna pregunta. (izquierda)');
	define('M4J_LANG_A_WIDTH','Ancho de la columna respuesta. (derecha)');
	define('M4J_LANG_OVERVIEW','Visión general');
	define('M4J_LANG_UPDATE_PROCEED','& Proceder');
	define('M4J_LANG_NEW_ITEM','Nuevo elemento');
	define('M4J_LANG_EDIT_ITEM','Editar elemento');
	define('M4J_LANG_CATEGORY_NAME','Nombre de categoría');
	define('M4J_LANG_EMAIL_ADRESS','Email');
	define('M4J_LANG_ADD_NEW_ITEM','Añadr un nuevo elemento:');
	define('M4J_LANG_YOUR_QUESTION','Su pregunta');
	define('M4J_LANG_REQUIRED_LONG','¿Obligatorio?');
	define('M4J_LANG_HELP_TEXT','Texto de ayuda. Les da a los usuarios una pista sobre las preguntas.(no es esencial)');
	define('M4J_LANG_TYPE_OF_CHECKBOX','Tipo de botón:');
	define('M4J_LANG_ITEM_CHECKBOX','Checkbox.');
	define('M4J_LANG_YES_NO_MENU','Menú tipo Sí/NO.');
	define('M4J_LANG_YES_ON','Sí/Activo.');
	define('M4J_LANG_NO_OFF','No/Inactivo.');
	define('M4J_LANG_INIT_VALUE','Valor incial:');
	define('M4J_LANG_TYPE_OF_TEXTFIELD','Tipo de cuadro de texto:');
	define('M4J_LANG_ITEM_TEXTFIELD','Campo de texto');
	define('M4J_LANG_ITEM_TEXTAREA','Area de texto');
	define('M4J_LANG_MAXCHARS_LONG','Caracteres máximo:');
	define('M4J_LANG_OPTICAL_ALIGNMENT','Alineación:');
	define('M4J_LANG_ITEM_WIDTH_LONG','<b>Ancho en pixel</b> <br/>(Add \'%\' para porcentaje. Vacío = Ajuste automático)<br/><br/>');
	define('M4J_LANG_ROWS_TEXTAREA','<b>Cantidad de filas visibles:</b><br/> (Sólo para area de texto)<br/><br/>');
	define('M4J_LANG_DROP_DOWN','<b>Menú</b>');
	define('M4J_LANG_RADIOBUTTONS','<b>Botones de radio</b>');
	define('M4J_LANG_LIST_SINGLE','<b>Lista</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(elección simple)');
	define('M4J_LANG_CHECKBOX_GROUP','<b>Grupo de Checkbox</b>');
	define('M4J_LANG_LIST_MULTIPLE','<b>Lista</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(elección múltiple, con \'CTRL\' & botón izquierdo de ratón)');
	define('M4J_LANG_SINGLE_CHOICE_LONG','Elección simple (Sólo se puede seleccionar una opción):');
	define('M4J_LANG_MULTIPLE_CHOICE_LONG','Elección múltiple (Se pueden selecciónar varias opciones):');
	define('M4J_LANG_TYPE_OF_OPTIONS','Tipo de selección:');
	define('M4J_LANG_ROWS_LIST','<b>Cantidad de filas visibles:</b><br/> (Sólo para listas)<br/><br/>');
	define('M4J_LANG_ALIGNMENT_GROUPS','<b>Alineación: </b> <br/>(Sólo para botones de radioy grupos de Checkbox )<br/><br/>');
	define('M4J_LANG_OPTIONS_VALUES_INTRO','<b>Especificar las respuestas.<br/>Campos vacíos serán ignorados.</b>');
	define('M4J_LANG_CATEGORY_INTRO_LONG','Texto introductorio. Sólo se visualizará en páginas de categoría.');
	define('M4J_LANG_TITLE','Título');
	define('M4J_LANG_ERROR_NO_TITLE','Por favor, introduzca un título.');
	define('M4J_LANG_USE_HELP','Texto ayuda para los rótulos con consejos en la web');
	define('M4J_LANG_TITLE_FORM','Título del formulario');
	define('M4J_LANG_INTROTEXT','Texto introductorio');
	define('M4J_LANG_MAINTEXT','Texto principal');
	define('M4J_LANG_EMAIL_HIDDEN_TEXT','Texto introductorio del Email. (Sólo se visualizará en los emails.)');
	define('M4J_LANG_TEMPLATE','Plantilla');
	define('M4J_LANG_LINK_TO_MENU','Enlzar con el menú');
	define('M4J_LANG_LINK_CAT_TO_MENU','Enlazar la categoría actual con un menú');
	define('M4J_LANG_LINK_TO_CAT','Enlazar categoría: ');
	define('M4J_LANG_LINK_TO_FORM','Enlazar formulario: ');
	define('M4J_LANG_LINK_TO_NO_CAT','Enlace para visualizar todos los formularios sin una categoría ');
	define('M4J_LANG_LINK_TO_ALL_CAT','Enlace para visualizar \'Todas las categorías\'');
	define('M4J_LANG_CHOOSE_MENU','Elija un menú para enlazar con: ');
	define('M4J_LANG_MENU','Menú: ');
	define('M4J_LANG_NO_LINK_NAME','Por favor, inserte un nombre de enlace.');
	define('M4J_LANG_PUBLISHED','Publicado:');
	define('M4J_LANG_PARENT_LINK','Enlace padre');
	define('M4J_LANG_LINK_NAME','Nombre de enlace');
	define('M4J_LANG_ACCESS_LEVEL','Nivel de acceso:');
	define('M4J_LANG_EDIT_MAIN_DATA','Editar datos básicos');
	define('M4J_LANG_LEGEND','Leyenda');
	define('M4J_LANG_LINK','Enlazar');
	define('M4J_LANG_IS_VISIBLE',' es publicado');
	define('M4J_LANG_IS_HIDDEN',' no es publicado');
	define('M4J_LANG_FORM','Formulario');
	define('M4J_LANG_ITEM','Elemento');
	define('M4J_LANG_IS_REQUIRED','Obligatorio');
	define('M4J_LANG_IS_NOT_REQUIRED','No obligatorio');
	define('M4J_LANG_ASSIGN_ORDER','Ordenación');
	define('M4J_LANG_ASSIGN_ORDER_HINT','* Ordenación no es posible para  \'Todos los formularios\' !<br/>');
	define('M4J_LANG_EDIT_FORM','Editar formularios');
	define('M4J_LANG_CAPTCHA','Captcha');
	define('M4J_LANG_HOVER_ACTIVE_ON','¡Publicado! Clic=Despublicar');
	define('M4J_LANG_HOVER_ACTIVE_OFF','¡Despublicar! Clic=Publicar');
	define('M4J_LANG_HOVER_REQUIRED_ON','¡Obligatorio! Clic= No obligatorio');
	define('M4J_LANG_HOVER_REQUIRED_OFF','¡No obligatorio! Clic= Obligatorio');
	define('M4J_LANG_DESCRIPTION','Descripción');
	define('M4J_LANG_AREA','Area');
	define('M4J_LANG_ADJUSTMENT','Configuración');
	define('M4J_LANG_VALUE','Valor');
	define('M4J_LANG_MAIN_CONFIG','Configuración principal:');
	define('M4J_LANG_MAIN_CONFIG_DESC','Puede configurar todos los parámetros principales aqui. Si quiere reiniciar todos los parámetros, incluidos los CSS, y volver a los predeterminados haga clic en reiniciar.');
	define('M4J_LANG_CSS_CONFIG','Configuraciones CSS :');
	define('M4J_LANG_CSS_CONFIG_DESC','Estas configuraciones son obligatorias para una visualización de la web. ¡Si no tiene experiencia con CSS externos o propios, no modifique estos valores!');
	define('M4J_LANG_RESET','Reiniciar');
			
	define('M4J_LANG_EMAIL_ROOT', 'Email principal: ' );
	define('M4J_LANG_MAX_OPTIONS', 'Respuestas máximas <br/> Elección especificada: ' );
	define('M4J_LANG_PREVIEW_WIDTH', 'Ancho de la vista preliminar: ' );
	define('M4J_LANG_PREVIEW_HEIGHT', 'Alto de la vista preliminar: ' );
	define('M4J_LANG_CAPTCHA_DURATION', 'duarción del Captcha  (en min): ' );
	define('M4J_LANG_HELP_ICON', 'Icono de ayuda: ' );
	define('M4J_LANG_HTML_MAIL', 'HTML Email: ' );
	define('M4J_LANG_SHOW_LEGEND', 'Ver leyenda: ' );
	
	define('M4J_LANG_EMAIL_ROOT_DESC', 'El emael principal es utilizado cuando ni la categoría ni el formulario ha asignado un email.' );
	define('M4J_LANG_MAX_OPTIONS_DESC', 'Esto valor limita el número de respuestas para un campo de selección. Este valor ha de ser númerico.' );	
	define('M4J_LANG_PREVIEW_WIDTH_DESC', 'Ancho de la vista preliminar de la plantilla. Este valor es utilizado únicamente si no asigna un ancho en los datos básicos de la plantilla.' );
	define('M4J_LANG_PREVIEW_HEIGHT_DESC', 'Alto de la visión preliminar de la plantilla. ' );
	define('M4J_LANG_CAPTCHA_DURATION_DESC', 'Pertenece a la web. Este valor asigna la duración máxima de la validación del captcha (campo antispam). Si la duración expira ha de introducirse un nuevo valor aunque se visualice el antiguo.' );
	define('M4J_LANG_HELP_ICON_DESC', 'Definir el color del icono de ayuda.' );
	define('M4J_LANG_HTML_MAIL_DESC', 'Si desea recibir emails HTML elija Sí. ' );
	define('M4J_LANG_SHOW_LEGEND_DESC', 'Si desea visualizar una leyenda en el panel de control elija Sí.' );
	
	define('M4J_LANG_CLASS_HEADING', 'Título principal:' );
	define('M4J_LANG_CLASS_HEADER_TEXT', 'Texto de cabecera' );
	define('M4J_LANG_CLASS_LIST_WRAP', 'Listado resumen ' );
	define('M4J_LANG_CLASS_LIST_HEADING', 'Listado de título' );
	define('M4J_LANG_CLASS_LIST_INTRO', 'Listado texto intro ' );
	define('M4J_LANG_CLASS_FORM_WRAP', 'Formulario Wrap' );
	define('M4J_LANG_CLASS_FORM_TABLE', 'Formulario tabla ' );
	define('M4J_LANG_CLASS_ERROR', 'Texto de error' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP', 'Wrap de botón Enviar' );
	define('M4J_LANG_CLASS_SUBMIT', 'Botón de envío ' );
	define('M4J_LANG_CLASS_REQUIRED', 'css * obligatorio ' );
	
	define('M4J_LANG_CLASS_HEADING_DESC', '<strong>DIV-Tag</strong> - Título de una web ' );
	define('M4J_LANG_CLASS_HEADER_TEXT_DESC', '<strong>DIV-Tag</strong> - Contenido tras el título. ' );
	define('M4J_LANG_CLASS_LIST_WRAP_DESC', '<strong>DIV-Tag</strong> - Recapitulación de un campo tipo lista.' );
	define('M4J_LANG_CLASS_LIST_HEADING_DESC', '<strong>DIV-Tag</strong> - Título de un campo tipo lista. ' );
	define('M4J_LANG_CLASS_LIST_INTRO_DESC', '<strong>DIV-Tag</strong> - Texto intro de un campo tipo lista. ' );
	define('M4J_LANG_CLASS_FORM_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap de un area de formulario. ' );
	define('M4J_LANG_CLASS_FORM_TABLE_DESC', '<strong>TABLE-Tag</strong> - Tabla donde se visualizan todos los campos del formulario.' );
	define('M4J_LANG_CLASS_ERROR_DESC', '<strong>SPAN-Tag</strong> - Clase CSS de mensajes de error. ' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap del botón de envío ' );
	define('M4J_LANG_CLASS_SUBMIT_DESC', '<strong>INPUT-Tag</strong> - Clase CSS del botón de envío. ' );
	define('M4J_LANG_CLASS_REQUIRED_DESC', '<strong>SPAN-Tag</strong> - Clase CSS del  carácter \' <b>*</b> \' para definir los campos obligatorios.' );
	
	define('M4J_LANG_INFO_HELP','Info y ayuda');
	
	// New to Version 1.1.5
	define('M4J_LANG_CHOOSE_CAPTCHA', 'Técnica Captcha: ' ); 
	define('M4J_LANG_CSS','CSS');
	define('M4J_LANG_SIMPLE','Ordinario');
	
	//New To Version 1.1.7
		define('M4J_LANG_CONFIG_RESET','La configuración se reinició correctamente.');
		define('M4J_LANG_CONFIG_SAVED','La configuración se guardó correctamente.');
		define('M4J_LANG_CAPTCHA_DESC', 'Puede haber problemas con los css-captcha estándar y algunos servidores o plantillas. Para este caso tiene la opción de elegir entre los css-captcha estándar y un captcha ordinario o nomal. Si el captcha ordinario no solucionara el problema elija especial' );
		define('M4J_LANG_SPECIAL','especial');
	
	
	define('M4J_LANG_MAIL_ISO','Tipo de caracteres de envío');
	define('M4J_LANG_MAIL_ISO_DESC','utf-8 , iso-8859-1 (Western Europe), iso-8859-4 (Balto), iso-8859-5 (Cyrillic), iso-8859-6 (Arabic), iso-8859-7 (Greek), iso-8859-8 (Hebrew),iso-8859-9 (Turkish), iso-8859-10 (Nordic),iso-8859-11 (Thai)');		
	
	
	// New to Version 1.1.8
	$m4j_lang_elements[40]= 'Adjunto';	
	define('M4J_LANG_ATTACHMENT','Archivo adjunto');
	define('M4J_LANG_ATTACHMENT_DESC','El usuario puede transmitir un archivo a través de formulario.');
	define('M4J_LANG_TYPE_OF_ATTACHMENT','Introduzca los parámetros para este campo de transferencia de archivo:');
	define('M4J_LANG_ALLOWED_ENDINGS','Extensiones de archivo aprovadas.');
	define('M4J_LANG_MAXSIZE','Tamaño máximo de archivo.');
	define('M4J_LANG_BYTE','Byte');
	define('M4J_LANG_KILOBYTE','Kilobyte');
	define('M4J_LANG_MEGABYTE','Megabyte');
	define('M4J_LANG_ELEMENT_ATTACHMENT_DESC','Por favor, introduzca todas las extensiones de archivo sin punto y separadas por <b>comas</b>.Si deja campos en blanco todas las extensiones de archivo serán aceptadas o cualquier tamañao será aprovado. Para facilitar el trabajo puede elegir entre las extensiones de abajo que serán incluidas automáticamente.');
	define('M4J_LANG_IMAGES','Imágenes');
	define('M4J_LANG_DOCS','Documentos');
	define('M4J_LANG_AUDIO','Audio');
	define('M4J_LANG_VIDEO','Video');										   
    define('M4J_LANG_DATA','Datos');
	define('M4J_LANG_COMPRESSED','Compresión');
	define('M4J_LANG_OTHERS','Otros');
	define('M4J_LANG_ALL','Todo');
	
	// New to Version 1.1.9
	define('M4J_LANG_FROM_NAME','Desde nombre');
	define('M4J_LANG_FROM_EMAIL','Desde email');
	define('M4J_LANG_FROM_NAME_DESC','Insertar un nombre origen para los emails que va a recibir<br/>');
	define('M4J_LANG_FROM_EMAIL_DESC','Insertar un email origen para los emails que va a recibir.<br/>');
	define('M4J_LANG_TEMPLATE_DELETE_CAUTION',' ¡Atención! Todos los formularios que contengan esta plantilla serán borrados!');
	
	
	// New to Proforms 1.0
	
	define('M4J_LANG_STORAGES','Database records of the form: ');
	define('M4J_LANG_READ_STORAGES','Database records');
	define('M4J_LANG_EXPORT','Export');
	define('M4J_LANG_CSV_EXPORT','CSV Export');
	define('M4J_LANG_WORKAREA','Workarea');
	define('M4J_LANG_WORKAREA_DESC','Width in pixel of the admin working window');
	define('M4J_LANG_STORAGE_WIDTH','Width of a database item');
	define('M4J_LANG_STORAGE_WIDTH_DESC','Width in pixel of a database item which will be listed in a database record.<br> Don\'t add px or % !');
	define('M4J_LANG_RECEIVED','Received');
	define('M4J_LANG_PROCESS','Process');
	define('M4J_LANG_DATABASE','Database');
	define('M4J_LANG_USERMAIL','Unique email address');
	define('M4J_LANG_USERMAIL_DESC','Hereby you can assign the specific field which represents the unique email address of the user.You can not use the confirmation (copy mail) function without assigning an unique email address. There can always be only one unique email address for each form template. Activating this will delete the current unique email address.');
	define('M4J_LANG_USERMAIL_TOOLTIP','This field is the unique email address. The unique email address is always set to `required`!');
	define('M4J_LANG_MATH','Mathematical');
	define('M4J_LANG_RE_CAPTCHA','reCAPTCHA');
	define('M4J_LANG_ITEM_PASSWORD','Password');
	$m4j_lang_elements[22]= 'Password';
	define('M4J_LANG_MAX_UPLOAD_ALLOWED','Your server allows a maximal upload size of ');
	define('M4J_LANG_CSS_EDIT', 'Edit CSS');
	define('M4J_LANG_NO_FRONT_STYLESHEET','The frontend stylesheet file doesn\'t exist! ');
	define('M4J_LANG_HTML','HTML');
	define('M4J_LANG_HTML_DESC','Allows you to display custom HTML code between the form elements.');
	$m4j_lang_elements[50]= 'HTML';
	define('M4J_LANG_EXTRA_HTML',' - EXTRA HTML - ');
	define('M4J_LANG_RESET_DESC','Resetting the configuration to the default setting.');
	define('M4J_LANG_SECURITY','Captcha &amp; Security');
	define('M4J_LANG_RECAPTCHA_THEME','reCaptcha Theme');
	define('M4J_LANG_RECAPTCHA_THEME_DESC','If you are using reCaptcha, you can change the theme.');
	define('M4J_LANG_SUBMISSION_TIME','Sending speed (in ms)');
	define('M4J_LANG_SUBMISSION_TIME_DESC','This value is in milliseconds, determines the acceptable time between the appearance of a form and its dispatch. If a dispatch is quicker than the specified context, it will be declared and rejected as spam.');
	define('M4J_LANG_FORM_TITLE','Show title');
	define('M4J_LANG_FORM_TITLE_DESC','Determines whether the title of a form is displayed. Applies only to the form display and not for the listing in one category.');
	define('M4J_LANG_SHOW_NO_CATEGORY','Show "Without Category"');
	define('M4J_LANG_SHOW_NO_CATEGORY_DESC','Here you can determine the appearance of the pseudo-category "without category". Depending on the setting it will be displayed in the main category listing or not.');
	define('M4J_LANG_FORCE_CALENDAR','Force English calendar');
	define('M4J_LANG_FORCE_CALENDAR_DESC','Under some front-end languages the calendar may not function properly. You can force the use of an English-calendar.');
	define('M4J_LANG_LINK_THIS_CAT_ALL','Link the listing of all categories to a menu.');
	define('M4J_LANG_LINK_THIS_NO_CAT','Link all forms as belonging to a category list to a menu.');
	define('M4J_LANG_LINK_THIS_CAT','Link all forms in the category \'%s\'as a list to a menu.');
	define('M4J_LANG_LINK_THIS_FORM','Link this form to a menu.');
	define('M4J_LANG_LINK_ADVICE','You can link categories and forms only with the given buttons [%s] to a menu!');
	define('M4J_LANG_HELP_TEXT_SHORT','Helptext');
	define('M4J_LANG_ROWS','Rows');
	define('M4J_LANG_WIDTH','Width');
	define('M4J_LANG_ALIGNMENT','Alignment');
	define('M4J_LANG_SHOW_USER_INFO','Show user info');
	define('M4J_LANG_SHOW_USER_INFO_DESC','Displays a list of the collected user data in emails. For example: Joomla Username, Joomla User Email, browser, OS, etc. This data will not be displayed in confirmation emails for the sender of the form.');
	define('M4J_LANG_FRONTEND','Frontend');
	define('M4J_LANG_ADMIN','Admin');
	define('M4J_LANG_DISPLAY','Display');
	define('M4J_LANG_FORCE_ADMIN_LANG','Force admin language');
	define('M4J_LANG_FORCE_ADMIN_LANG_DESC','In normal Proform recognizes the Admin language automatically. Here you can force a language.');
	define('M4J_LANG_USE_JS_VALIDATION','Javascript validation');
	define('M4J_LANG_USE_JS_VALIDATION_DESC','Here you can switch the javascript form validation. If this is off, the fields will be evaluated after submitting.');
	define('M4J_LANG_PLEASE_SELECT','Please Select');
	define('M4J_LANG_LAYOUT','Layout');
	define('M4J_LANG_DESC_LAYOUT01','One column');
	define('M4J_LANG_DESC_LAYOUT02','Two columns');
	define('M4J_LANG_DESC_LAYOUT03','Three columns');
	define('M4J_LANG_DESC_LAYOUT04','Head with two columns and footer with one column');
	define('M4J_LANG_DESC_LAYOUT05','Head with one column and footer with two columns');
	define('M4J_LANG_USE_FIELDSET','Use fieldset:');
	define('M4J_LANG_LEGEND_NAME','Legend:');
	define('M4J_LANG_LEFT_COL','Left column:');
	define('M4J_LANG_RIGHT_COL','Right column:');
	define('M4J_LANG_FOR_POSITION',' for position %s');
	define('M4J_LANG_LAYOUT_POSITION','Layout position');
	define('M4J_LANG_PAYPAL','PayPal');
	define('M4J_LANG_EMAIL_TEXT','Email text');
	define('M4J_LANG_CODE','Code');
	define('M4J_LANG_NEVER','Never');
	define('M4J_LANG_EVER','Ever');
	define('M4J_LANG_ASK','Ask');
	define('M4J_LANG_AFTER_SENDING','After sending');
	define('M4J_LANG_CONFIRMATION_MAIL','Confirmation Mail');
	define('M4J_LANG_TEXT_FOR_CONFIRMATION','Email text only for confirmation mail?');
	define('M4J_LANG_SUBJECT','Subject');
	define('M4J_LANG_ADD_TEMPLATE','Add form template');
	define('M4J_LANG_INCLUDED_TEMPLATES','Included form template(s)');
	define('M4J_LANG_ADVICE_USERMAIL_ERROR',"A form can only ever have one unique email address. You already have assigned a form template with unique email address to this form.");
	define('M4J_LANG_STANDARD_TEXT','Standard text');
	define('M4J_LANG_REDIRECT','Redirection');
	define('M4J_LANG_CUSTOM_TEXT','Custom text');
	define('M4J_LANG_ERROR_NO_FORMS','You can only create a form if you have at least created one form template. You have not created yet a form template. Do you wish to create a new form template?');
	define('M4J_LANG_USE_PAYPAL','Use PayPal');
	define('M4J_LANG_USE_PAYPAL_SANDBOX','Use PayPal Sandbox');
	define('M4J_LANG_HEIGHT','Height');
	define('M4J_LANG_CLASS_RESET','Reset Button');
	define('M4J_LANG_CLASS_RESET_DESC','<b>INPUT-Tag</b> - CSS class for the reset button.');
	define('M4J_LANG_PAYPAL_PARAMETERS','Paypal configuration');
	define('M4J_LANG_PAYPAL_ID','Your PayPal ID (email)');
	define('M4J_LANG_PAYPAL_PRODUCT_NAME','Product name');
	define('M4J_LANG_PAYPAL_QTY','Quantity');
	define('M4J_LANG_PAYPAL_NET_AMOUNT','Net amount (unit price)');
	define('M4J_LANG_PAYPAL_CURRENCY_CODE','Currency Code');
	define('M4J_LANG_PAYPAL_ADD_TAX','Plus TAX (Overall, not a percentage!) ');
	define('M4J_LANG_PAYPAL_RETURN_URL','Return address after a successful transaction (URL with http)');
	define('M4J_LANG_PAYPAL_CANCEL_RETURN_URL','Return address when the transaction is aborted (URL with http) ');
	define('M4J_LANG_SERVICE','Service');
	define('M4J_LANG_SERVICE_KEY','Service Key');
	define('M4J_LANG_EDIT_KEY','Edit / Renew Key');
	define('M4J_LANG_CONNECT','Connect');
	define('M4J_LANG_NONE','None');
	define('M4J_LANG_ALPHABETICAL','Alphabetical');
	define('M4J_LANG_ALPHANUMERIC','Alphanumeric');
	define('M4J_LANG_NUMERIC','Numeric');
	define('M4J_LANG_INTEGER','Integer');
	define('M4J_LANG_FIELD_VALIDATION','Validation');
	define('M4J_LANG_SEARCH','Search');
	define('M4J_LANG_ANY','-ANY-');
	define('M4J_LANG_JOBS_EMAIL_INFO','If you don\'t enter an e-mail address here the address of the corresponding category will be used. <br /> If there is no address appended the global address will be used (see configuration).');
	define('M4J_LANG_JOBS_INTROTEXT_INFO','The intro text is the text which will be displayed by a (category) forms list. This does not appear on the form itself.');
	define('M4J_LANG_JOBS_MAINTEXT_INFO','The main text appears at the top of the form.');
	define('M4J_LANG_JOBS_AFTERSENDING_INFO','Here you can determine what will be displayed after submitting the form data.');
	define('M4J_LANG_JOBS_PAYPAL_INFO','After sending you can redirect the user directly to Paypal. Please enter the amounts with a point instead of a comma: 24.50 instead of 24,50! <br /> If you use PayPal, the action you\'ve choosen in "After sending" will passed over !');
	define('M4J_LANG_JOBS_CODE_INFO','You also can enter custom code (HTML, JS <b> but no PHP </b>) at the end of the form or at the after sending page:<br /> e.g. Google Analytics or Conversion. The "after-sending-code" will not be  included if you use an after-sending redirection or the PayPal function.');
	define('M4J_LANG_ERROR_COLOR','Error color');
	define('M4J_LANG_ERROR_COLOR_DESC','If the javascript form validation detects an error ther border of a cell will displayed in a special color. Here you can determine the color (Hexadecimal without #).');
	define('M4J_LANG_CONFIG_DISPLAY_INFO','Here you can change values which are influencing the representation of the front or the back end.');
	define('M4J_LANG_CONFIG_CAPTCHA_INFO','Here you can determine the technology of the security check (captcha) and other security settings.');
	define('M4J_LANG_CONFIG_RESET_INFO','The style sheet file will not be reset, only the CSS class name of the CSS settings!');
	define('M4J_LANG_SERVICE_DESC1',
	'
	If you have a service key you will get access to the Proforms Service Helpdesk here.<br/> 
	To do so, enter the key and save it. Afterwards you need to connect through the "Connect" button with the Service Help Desk Server.<br/>  
	<br/> 
	You can reach the service desk only from the admin area of Proforms.<br/>  
	Direct access is not allowed.<br/>  
	<br/> 
	Each service key is temporary and can\'t be used by the end of the service period. The service key is only valid for one domain / Joomla installation. At the first visit of the helpdesk, you will be asked if you want to register the key on the current Domain / Joomla installation. When you click OK, you get access to the helpdesk. Then you can reach the help desk with this key only from the admin area of the registered domain / Joomla installation.<br/>  
	<br/><span style="color:red"> 
	If this installation (domain) is behind a firewall or is generally not publicly accessible (e.g. running on a local server), we can\'t offer the service for technical reasons (see Technical Requirements and Conditions of use).<br/>  
	</span><br/> 
	The Proforms Helpdesk offers information about the product, the opportunity to contact us (Direct Requests via our website or by email will be ignored) and downloads to upgrade packages, and other modules or plug-ins for Mooj Proforms.<br/>  
	<br/> 
	The help desk is under construction and is growing every day. When the construction is completed you will receive an update package that provides an automatic upgrade function.<br/>  
	<br/> 
	The domain restriction applies only to the help desk service. Proform\'s  functionality and portability are not affected.<br/> 
	<br/> 
	');
	define('M4J_LANG_SEARCH_IN',' Search in ');

?>