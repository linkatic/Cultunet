<?php
/**
* @name MOOJ Proforms 
* @version 1.0
* @package proforms
* @copyright Copyright (C) 2008-2010 Mad4Media. All rights reserved.
* @author Dipl. Inf.(FH) Fahrettin Kutyol
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Please note that some Javascript files are not under GNU/GPL License.
* These files are under the mad4media license
* They may edited and used infinitely but may not repuplished or redistributed.  
* For more information read the header notice of the js files.
**/

	/**  MACEDONIAN VERSION. */


	defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
	
	$m4j_lang_elements[1]= 'Штиклирање';
	$m4j_lang_elements[2]= 'Да/Не селекција';
	$m4j_lang_elements[10]= 'Датум';
	$m4j_lang_elements[20]= 'Текст поле';
	$m4j_lang_elements[21]= 'Текст површина';
	$m4j_lang_elements[30]= 'Мени(единечен избор)';
	$m4j_lang_elements[31]= 'Одбери мени(единечен избор)';
	$m4j_lang_elements[32]= 'Радио копчиња(единечен избор)';
	$m4j_lang_elements[33]= 'Група штиклирања(множински избор)';
	$m4j_lang_elements[34]= 'Листа(множински избор)';
	
	
	define('M4J_LANG_FORMS','Форми');
	define('M4J_LANG_TEMPLATES','Шаблони');
	define('M4J_LANG_CATEGORY','Категорија');
	define('M4J_LANG_CONFIG','Конфигурација');
	define('M4J_LANG_HELP','Инфо и Помош');
	define('M4J_LANG_CANCEL','Откажи');
	define('M4J_LANG_PROCEED','Изврши');
	define('M4J_LANG_SAVE','Зачувај');
	define('M4J_LANG_NEW_FORM','Нова форма');
	define('M4J_LANG_NEW_TEMPLATE','Нов шаблон');
	define('M4J_LANG_ADD','Додади');
	define('M4J_LANG_EDIT_NAME','Уредете име и опис на овој шаблон');
	define('M4J_LANG_NEW_TEMPLATE_LONG','Нов шаблон');
	define('M4J_LANG_TEMPLATE_NAME','Име на овој шаблон');
	define('M4J_LANG_TEMPLATE_NAME_EDIT','Уредете го името наовој шаблон');
	define('M4J_LANG_TEMPLATE_DESCRIPTION','Краток опис (за интерна употреба. Може да остане празно)');
	define('M4J_LANG_TEMPLATE_DESCRIPTION_EDIT','Уредете краток опис');
	define('M4J_LANG_DELETE','Избриши');
	define('M4J_LANG_DELETE_CONFIRM','Дали навистина сакате да го избришете овој дел?');
	define('M4J_LANG_NEW_CATEGORY','Нова категорија');
	define('M4J_LANG_NAME','Име');
	define('M4J_LANG_SHORTDESCRIPTION','Краток опис');
	define('M4J_LANG_ID','ID');
	define('M4J_LANG_ITEMS','Делови');
	define('M4J_LANG_EDIT','Уреди');
	define('M4J_LANG_EDIT_TEMPLATE_ITEMS','Делови -> Уреди');
	define('M4J_LANG_TEMPLATE_NAME_REQUIRED','Молам внесете име за овој шаблон!');
	define('M4J_LANG_AT_LEAST_ONE','Овој дел не може да биде избришан!<br/>Мора да има барем еден елемент тука.');	

	
	define('M4J_LANG_EDIT_ELEMENT','Уредете дел од шаблонот: ');
	define('M4J_LANG_CATEGORY_NAME_ERROR','Молам внесете име на категорија');
	define('M4J_LANG_NONE_LEGAL_EMAIL','Молам внесете валидна e-mail адреса или оставете празно.<br/>');
	define('M4J_LANG_EMAIL','e-Mail');
	define('M4J_LANG_POSITION','Редослед');
	define('M4J_LANG_ACTIVE','Активно');
	define('M4J_LANG_UP','Горе');
	define('M4J_LANG_DOWN','Долу');
	define('M4J_LANG_EDIT_CATEGORY','Уредете категорија');
	define('M4J_LANG_TEMPLATE_ELEMENTS','Елементи од шаблон: ');
	define('M4J_LANG_NEW_ELEMENT_LONG','Вметнете нов елемент во шаблонот: ');	
	define('M4J_LANG_ELEMENT_NO_QUESTION_ERROR','Молам вметнете прашање.');
	define('M4J_LANG_REQUIRED','Барано');
	define('M4J_LANG_QUESTION','Прашање');
	define('M4J_LANG_TYPE','Тип');
	define('M4J_LANG_YES','Да');		
	define('M4J_LANG_NO','Не');	
	define('M4J_LANG_ALL_FORMS','Сите форми');
	define('M4J_LANG_NO_CATEGORYS','Без категорија');
	define('M4J_LANG_FORMS_OF_CATEGORY','Форми од категорија: ');
	define('M4J_LANG_PREVIEW','Првоглед');
	define('M4J_LANG_DO_COPY','Копирај');		
	define('M4J_LANG_COPY','Копирај');
	define('M4J_LANG_VERTICAL','Вертикално');
	define('M4J_LANG_HORIZONTAL','Хоризонтално');
	define('M4J_LANG_EXAMPLE','Пример');
	define('M4J_LANG_CHECKBOX','Копче');	
	define('M4J_LANG_DATE','Датум');
	define('M4J_LANG_TEXTFIELD','Текст поле');
	define('M4J_LANG_OPTIONS','Посочете избор');
	define('M4J_LANG_CHECKBOX_DESC','Едноставно Да/Не прашање.');
	define('M4J_LANG_DATE_DESC','Корисникот треба да внесе датум.');
	define('M4J_LANG_TEXTFIELD_DESC','Корисникот треба да внесе текст.');
	define('M4J_LANG_OPTIONS_DESC','Корисникот одбира едно или повеќе од посочените. ');
	define('M4J_LANG_CLOSE_PREVIEW','Затвори првоглед');
	define('M4J_LANG_Q_WIDTH','Широчина на колоната за прашање. (лево)');
	define('M4J_LANG_A_WIDTH','Широчина на колоната за одговор. (десно)');
	define('M4J_LANG_OVERVIEW','Преглед');
	define('M4J_LANG_UPDATE_PROCEED','и изврши');
	define('M4J_LANG_NEW_ITEM','Нов дел');
	define('M4J_LANG_EDIT_ITEM','Уреди дел');
	define('M4J_LANG_CATEGORY_NAME','Имена категорија');
	define('M4J_LANG_EMAIL_ADRESS','E-mail адреса');
	define('M4J_LANG_ADD_NEW_ITEM','Додадете нов дел:');
	define('M4J_LANG_YOUR_QUESTION','Вашето прашање');
	define('M4J_LANG_REQUIRED_LONG','Барани?');
	define('M4J_LANG_HELP_TEXT','Помошен текст. Му дава на корисникот помош околу вашето прашање.(незадолжително)');
	define('M4J_LANG_TYPE_OF_CHECKBOX','Тип на копче:');
	define('M4J_LANG_ITEM_CHECKBOX','Штиклирање.');
	define('M4J_LANG_YES_NO_MENU','Да/Не мени.');
	define('M4J_LANG_YES_ON','Да/Вкл.');
	define('M4J_LANG_NO_OFF','Не/Иск.');
	define('M4J_LANG_INIT_VALUE','Почетна вредност:');
	define('M4J_LANG_TYPE_OF_TEXTFIELD','Тип на текст поле:');
	define('M4J_LANG_ITEM_TEXTFIELD','Текст поле');
	define('M4J_LANG_ITEM_TEXTAREA','Текст површина');
	define('M4J_LANG_MAXCHARS_LONG','Макс. карактери:');
	define('M4J_LANG_OPTICAL_ALIGNMENT','Визуелно порамнување:');
	define('M4J_LANG_ITEM_WIDTH_LONG','<b>Широчина во пиксели</b> <br/>(Додадете \'%\' за проценти. Празно = автособирање)<br/><br/>');
	define('M4J_LANG_ROWS_TEXTAREA','<b>Број на видливи редови:</b><br/> (Само за текст површина)<br/><br/>');
	define('M4J_LANG_DROP_DOWN','<b>Мени</b>');
	define('M4J_LANG_RADIOBUTTONS','<b>Радио копчиња</b>');
	define('M4J_LANG_LIST_SINGLE','<b>Листа</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(единечен избор)');
	define('M4J_LANG_CHECKBOX_GROUP','<b>Група за штиклирање</b>');
	define('M4J_LANG_LIST_MULTIPLE','<b>Листа</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(множински избор, со \'CTRL\' и левото копче од глувчето)');
	define('M4J_LANG_SINGLE_CHOICE_LONG','Единечен избор (Само еден дел може да биде одбран):');
	define('M4J_LANG_MULTIPLE_CHOICE_LONG','Множински избор (Повеќе делови можат да бидат избрани):');
	define('M4J_LANG_TYPE_OF_OPTIONS','Тип на избор:');
	define('M4J_LANG_ROWS_LIST','<b>Број на видливи редови:</b><br/> (Само за листи)<br/><br/>');
	define('M4J_LANG_ALIGNMENT_GROUPS','<b>Порамнување: </b> <br/>(Само за Радио копчиња и Групи за штиклирање)<br/><br/>');
	define('M4J_LANG_OPTIONS_VALUES_INTRO','<b>Посочете одговори.<br/>Празните полиња ќе бидат игнорирани.</b>');
	define('M4J_LANG_CATEGORY_INTRO_LONG','Воведен текст. Ќе биде прикажан единствено на страниците на категориите.');
	define('M4J_LANG_TITLE','Наслов');
	define('M4J_LANG_ERROR_NO_TITLE','Молам внесете наслов.');
	define('M4J_LANG_USE_HELP','Помошен текст за во балонче');
	define('M4J_LANG_TITLE_FORM','Наслов на форма');
	define('M4J_LANG_INTROTEXT','Воведен текст');
	define('M4J_LANG_MAINTEXT','Главен текст');
	define('M4J_LANG_EMAIL_HIDDEN_TEXT','Воведен текст за E-mail. (Ќе биде прикажан само за поштата.)');
	define('M4J_LANG_TEMPLATE','Шаблон');
	define('M4J_LANG_LINK_TO_MENU','Поврзи до мени');
	define('M4J_LANG_LINK_CAT_TO_MENU','Поврзете ја категоријата со мени');
	define('M4J_LANG_LINK_TO_CAT','Поврзи категорија: ');
	define('M4J_LANG_LINK_TO_FORM','Поврзи форма: ');
	define('M4J_LANG_LINK_TO_NO_CAT','Врска за приказ на сите форми без категории ');
	define('M4J_LANG_LINK_TO_ALL_CAT','Врска за приказ на \'Сите категории\'');
	define('M4J_LANG_CHOOSE_MENU','Одберете мени за врска до: ');
	define('M4J_LANG_MENU','Мени: ');
	define('M4J_LANG_NO_LINK_NAME','Молам внесете име за врската.');
	define('M4J_LANG_PUBLISHED','Објавено:');
	define('M4J_LANG_PARENT_LINK','Родителска врска');
	define('M4J_LANG_LINK_NAME','Име на врска');
	define('M4J_LANG_ACCESS_LEVEL','Ниво на пристап:');
	define('M4J_LANG_EDIT_MAIN_DATA','Уредете основни податоци');
	define('M4J_LANG_LEGEND','Легенда');
	define('M4J_LANG_LINK','Врска');
	define('M4J_LANG_IS_VISIBLE',' е објавена');
	define('M4J_LANG_IS_HIDDEN',' не е објавена');
	define('M4J_LANG_FORM','Форма');
	define('M4J_LANG_ITEM','Дел');
	define('M4J_LANG_IS_REQUIRED','Барано');
	define('M4J_LANG_IS_NOT_REQUIRED','Небарано');
	define('M4J_LANG_ASSIGN_ORDER','Редослед');
	define('M4J_LANG_ASSIGN_ORDER_HINT','* Редоследот не е можен за \'Сите форми\' !<br/>');
	define('M4J_LANG_EDIT_FORM','Уредете форми');
	define('M4J_LANG_CAPTCHA','Captcha');
	define('M4J_LANG_HOVER_ACTIVE_ON','Објавено! Клик=Необјавено');
	define('M4J_LANG_HOVER_ACTIVE_OFF','Необјавено! Клик=Објавено');
	define('M4J_LANG_HOVER_REQUIRED_ON','Барано! Клик= Небарано');
	define('M4J_LANG_HOVER_REQUIRED_OFF','Неабарано! Клик= Барано');
	define('M4J_LANG_DESCRIPTION','Опис');
	define('M4J_LANG_AREA','Површина');
	define('M4J_LANG_ADJUSTMENT','Конфигурација');
	define('M4J_LANG_VALUE','Вредност');
	define('M4J_LANG_MAIN_CONFIG','Главна конфигурација:');
	define('M4J_LANG_MAIN_CONFIG_DESC','Сите главни поставки можете да ги конфигурирате тука. Ако сакате да се вратите на сите зададени поставки (вклучително и CSS) тогаш кликнете ресет.');
	define('M4J_LANG_CSS_CONFIG','CSS поставки:');
	define('M4J_LANG_CSS_CONFIG_DESC','Овие поставки се потребни за визуелно претставување на предницата. Ако немате искуство со вметнување надворешно (ваше) CSS, не гименувајте овие поставки!');
	define('M4J_LANG_RESET','Ресет');
			
	define('M4J_LANG_EMAIL_ROOT', 'Главна E-mail адреса: ' );
	define('M4J_LANG_MAX_OPTIONS', 'Максимум одговори <br/> Посочете избор: ' );
	define('M4J_LANG_PREVIEW_WIDTH', 'Првоглед на широчина: ' );
	define('M4J_LANG_PREVIEW_HEIGHT', 'Првоглед на висина: ' );
	define('M4J_LANG_CAPTCHA_DURATION', 'Captcha траење (во минути): ' );
	define('M4J_LANG_HELP_ICON', 'Иконка за помош: ' );
	define('M4J_LANG_HTML_MAIL', 'HTML E-mail: ' );
	define('M4J_LANG_SHOW_LEGEND', 'Прикажи легенда: ' );
	
	define('M4J_LANG_EMAIL_ROOT_DESC', 'Главната e-mail адреса ќе се користи ако ниту категоријата ниту формата немаат посочено e-mail адреса.' );
	define('M4J_LANG_MAX_OPTIONS_DESC', 'Оваа вредност го ограничува максималниот број одговори за \'Посочи избор\' делот. Вредноста мора дад е бројна.' );	
	define('M4J_LANG_PREVIEW_WIDTH_DESC', 'Широчина за првоглед на шаблонот. Вредноста се користи само ако немате посочено широчина за првоглед за основните податоци за шаблонот.' );
	define('M4J_LANG_PREVIEW_HEIGHT_DESC', 'Височина за првоглед на шаблонот. ' );
	define('M4J_LANG_CAPTCHA_DURATION_DESC', 'Се однесува на предницата. Вредноста одредува максимално траење на captcha-та. Ако времето истече, нов captcha код ќе треба да биде внесен иако стариот е правилно впишан.' );
	define('M4J_LANG_HELP_ICON_DESC', 'Одредете боја на иконката за помош.' );
	define('M4J_LANG_HTML_MAIL_DESC', 'Ако сакате да добивате HTML пошта одберете да. ' );
	define('M4J_LANG_SHOW_LEGEND_DESC', 'Ако сакате да ја прикажете легендата на позадината одберете да.' );
	
	define('M4J_LANG_CLASS_HEADING', 'Главно заглавие:' );
	define('M4J_LANG_CLASS_HEADER_TEXT', 'Насловен текст' );
	define('M4J_LANG_CLASS_LIST_WRAP', 'Листинг- Збиено ' );
	define('M4J_LANG_CLASS_LIST_HEADING', 'Листинг- Заглавие' );
	define('M4J_LANG_CLASS_LIST_INTRO', 'Листинг- Воведен текст ' );
	define('M4J_LANG_CLASS_FORM_WRAP', 'Форма- Збиено ' );
	define('M4J_LANG_CLASS_FORM_TABLE', 'Форма- Табела ' );
	define('M4J_LANG_CLASS_ERROR', 'Текст за грешка' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP', 'Збиено копче за прифаќање' );
	define('M4J_LANG_CLASS_SUBMIT', 'Копче за прифаќање ' );
	define('M4J_LANG_CLASS_REQUIRED', 'Барано * css ' );
	
	define('M4J_LANG_CLASS_HEADING_DESC', '<strong>DIV-Tag</strong> - Заглавие на страницата ' );
	define('M4J_LANG_CLASS_HEADER_TEXT_DESC', '<strong>DIV-Tag</strong> - Содржина по заглавието. ' );
	define('M4J_LANG_CLASS_LIST_WRAP_DESC', '<strong>DIV-Tag</strong> - Збиена листа од деловите.' );
	define('M4J_LANG_CLASS_LIST_HEADING_DESC', '<strong>DIV-Tag</strong> - Заглавија на листаните делови. ' );
	define('M4J_LANG_CLASS_LIST_INTRO_DESC', '<strong>DIV-Tag</strong> - Воведен текст од листаните делови. ' );
	define('M4J_LANG_CLASS_FORM_WRAP_DESC', '<strong>DIV-Tag</strong> - Збиена површина на форма. ' );
	define('M4J_LANG_CLASS_FORM_TABLE_DESC', '<strong>TABLE-Tag</strong> - Табела каде сите делови на формата ссе прикажани.' );
	define('M4J_LANG_CLASS_ERROR_DESC', '<strong>SPAN-Tag</strong> - CSS класа за пораки за грешка. ' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP_DESC', '<strong>DIV-Tag</strong> - Збиено копче за прифаќање ' );
	define('M4J_LANG_CLASS_SUBMIT_DESC', '<strong>INPUT-Tag</strong> - CSS класа за копче за прифаќање. ' );
	define('M4J_LANG_CLASS_REQUIRED_DESC', '<strong>SPAN-Tag</strong> - CSS класа за \' <b>*</b> \' знакот кој ги посочува браните полиња.' );
	
	define('M4J_LANG_INFO_HELP','Инфо и Помош');
	
	// New to Version 1.1.5
	define('M4J_LANG_CHOOSE_CAPTCHA', 'Captcha техника: ' ); 
	define('M4J_LANG_CSS','CSS');
	define('M4J_LANG_SIMPLE','Едноставно');
	
	//New To Version 1.1.7
		define('M4J_LANG_CONFIG_RESET','Конфигурацијата е успешно ресетирана.');
		define('M4J_LANG_CONFIG_SAVED','Конфигурацијата е успешно сочувана.');
		define('M4J_LANG_CAPTCHA_DESC', 'Може да имате мали проблеми со standard-css-captcha и некои сервери и шаблони. За таков случај имате можност за одбир меѓу standard-css-captcha и едноставна captcha. Ако едноставната captcha не ви го реши проблемот одберете Специјално' );
		define('M4J_LANG_SPECIAL','Специјално');
	
	
	define('M4J_LANG_MAIL_ISO','Главен сет на знаци');
	define('M4J_LANG_MAIL_ISO_DESC','utf-8 , iso-8859-1 (Western Europe), iso-8859-4 (Balto), iso-8859-5 (Cyrillic), iso-8859-6 (Arabic), iso-8859-7 (Greek), iso-8859-8 (Hebrew),iso-8859-9 (Turkish), iso-8859-10 (Nordic),iso-8859-11 (Thai)');		
	
	
	// New to Version 1.1.8
	$m4j_lang_elements[40]= 'Прикачи';	
	define('M4J_LANG_ATTACHMENT','Прикачете документ');
	define('M4J_LANG_ATTACHMENT_DESC','Корисникот може да испрати документ преку формата.');
	define('M4J_LANG_TYPE_OF_ATTACHMENT','Внесете параметри во поелто за пренос:');
	define('M4J_LANG_ALLOWED_ENDINGS','Одобрени наставки за документ.');
	define('M4J_LANG_MAXSIZE','Максимална големина.');
	define('M4J_LANG_BYTE','Бајти');
	define('M4J_LANG_KILOBYTE','Килобајти');
	define('M4J_LANG_MEGABYTE','Мегабајти');
	define('M4J_LANG_ELEMENT_ATTACHMENT_DESC','Ве молиме внесете ги сите наставки за документи без точка (.) и одделете ги со <b>запирка</b>. Ако оставите празно, сите наставки ќе бидат прифатени и сите големини одобрени. За да си ја олесните работата, можете да одберете поставки кои ќе се прикажат автоматски.');
	define('M4J_LANG_IMAGES','Слики');
	define('M4J_LANG_DOCS','Документи');
	define('M4J_LANG_AUDIO','Аудио');
	define('M4J_LANG_VIDEO','Видео');										   
    define('M4J_LANG_DATA','Податоци');
	define('M4J_LANG_COMPRESSED','Пакети');
	define('M4J_LANG_OTHERS','Останато');
	define('M4J_LANG_ALL','Сите');
	
	// New to Version 1.1.9
	define('M4J_LANG_FROM_NAME','Име на форма');
	define('M4J_LANG_FROM_EMAIL','Е-mail  на форма');
	define('M4J_LANG_FROM_NAME_DESC','Внесете име на формата од која ќе добивате пошта<br/>');
	define('M4J_LANG_FROM_EMAIL_DESC','Внесете e-mail адреса за формата од која ќе очекувате пошта.<br/>');
	define('M4J_LANG_TEMPLATE_DELETE_CAUTION',' Внимание! Сите форми што го содржат овој шаблон истотака ќе бидат избришани!');
	
	
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