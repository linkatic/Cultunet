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

	/**  РУССКАЯ ВЕРСИЯ. ПЕРЕВОД www.biznetman.biz */


	defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
	
	$m4j_lang_elements[1]= 'Кнопка-флажок(Check-box)';
	$m4j_lang_elements[2]= 'Выбор Да/Нет';
	$m4j_lang_elements[10]= 'Дата';
	$m4j_lang_elements[20]= 'Текстовое поле';
	$m4j_lang_elements[21]= 'Текстовая область';
	$m4j_lang_elements[30]= 'Меню(одиночный выбор)';
	$m4j_lang_elements[31]= 'Меню с выбором(одиночный выбор)';
	$m4j_lang_elements[32]= 'Кнопки с зависимой фиксацией(Radiobuttons-одиночный выбор)';
	$m4j_lang_elements[33]= 'Группа кнопок-флажков(множественный выбор)';
	$m4j_lang_elements[34]= 'Список(множественный выбор)';
	
	
	define('M4J_LANG_FORMS','Формы');
	define('M4J_LANG_TEMPLATES','Шаблоны');
	define('M4J_LANG_CATEGORY','Категории');
	define('M4J_LANG_CONFIG','Конфигурации');
	define('M4J_LANG_HELP','Инфо&Помощь');
	define('M4J_LANG_CANCEL','Отмена');
	define('M4J_LANG_PROCEED','Далее');
	define('M4J_LANG_SAVE','Сохранить');
	define('M4J_LANG_NEW_FORM','Новая форма');
	define('M4J_LANG_NEW_TEMPLATE','Новый шаблон');
	define('M4J_LANG_ADD','Добавить');
	define('M4J_LANG_EDIT_NAME','Редактировать имя и описание для этого шаблона');
	define('M4J_LANG_NEW_TEMPLATE_LONG','Новый шаблон');
	define('M4J_LANG_TEMPLATE_NAME','Имя этого шаблона');
	define('M4J_LANG_TEMPLATE_NAME_EDIT','Редактировать имя этого шаблона');
	define('M4J_LANG_TEMPLATE_DESCRIPTION','Короткое описание (для внутреннего использования. Можно не заполнять)');
	define('M4J_LANG_TEMPLATE_DESCRIPTION_EDIT','Редактировать короткое описание');
	define('M4J_LANG_DELETE','Удалить');
	define('M4J_LANG_DELETE_CONFIRM','Вы действительно хотите удалить этот элемент?');
	define('M4J_LANG_NEW_CATEGORY','Новая категория');
	define('M4J_LANG_NAME','Имя');
	define('M4J_LANG_SHORTDESCRIPTION','Короткое описание');
	define('M4J_LANG_ID','ID');
	define('M4J_LANG_ITEMS','Элементы');
	define('M4J_LANG_EDIT','Редактировать');
	define('M4J_LANG_EDIT_TEMPLATE_ITEMS','Элементы -> Редактировать');
	define('M4J_LANG_TEMPLATE_NAME_REQUIRED','Пожалуйста, введите имя для этого шаблона!');
	define('M4J_LANG_AT_LEAST_ONE','Этот элемент не может быть удален!<br/>Здесь должен быть покрайней мере один элемент.');	

	
		define('M4J_LANG_EDIT_ELEMENT','Редактировать элемнт шаблона: ');
	define('M4J_LANG_CATEGORY_NAME_ERROR','Пожалуйста, вставьте название категории');
	define('M4J_LANG_NONE_LEGAL_EMAIL','Пожалуйста вставьте верный e-mail адрес или оставьте незаполненным.<br/>');
	define('M4J_LANG_EMAIL','e-mail');
	define('M4J_LANG_POSITION','Расположение');
	define('M4J_LANG_ACTIVE','Активный');
	define('M4J_LANG_UP','Вверх');
	define('M4J_LANG_DOWN','Вниз');
	define('M4J_LANG_EDIT_CATEGORY','Редактировать категорию');
	define('M4J_LANG_TEMPLATE_ELEMENTS','Элементы шаблона: ');
	define('M4J_LANG_NEW_ELEMENT_LONG','Вставить новый элемент в шаблон: ');	
	define('M4J_LANG_ELEMENT_NO_QUESTION_ERROR','Пожалуйста, вставьте вопрос.');
	define('M4J_LANG_REQUIRED','Необходимый');
	define('M4J_LANG_QUESTION','Вопрос');
	define('M4J_LANG_TYPE','Тип');
	define('M4J_LANG_YES','Да');		
	define('M4J_LANG_NO','Нет');	
	define('M4J_LANG_ALL_FORMS','Все формы');
	define('M4J_LANG_NO_CATEGORYS','Без категории');
	define('M4J_LANG_FORMS_OF_CATEGORY','Формы категории: ');
	define('M4J_LANG_PREVIEW','Просмотр');
	define('M4J_LANG_DO_COPY','Копировать');		
	define('M4J_LANG_COPY','Копия');
	define('M4J_LANG_VERTICAL','Вертикально');
	define('M4J_LANG_HORIZONTAL','Горизонтально');
	define('M4J_LANG_EXAMPLE','Пример');
	define('M4J_LANG_CHECKBOX','Кнопка');	
	define('M4J_LANG_DATE','Дата');
	define('M4J_LANG_TEXTFIELD','Текстовое поле');
	define('M4J_LANG_OPTIONS','Заданный выбор');
	define('M4J_LANG_CHECKBOX_DESC','Простой Да/Нет вопрос.');
	define('M4J_LANG_DATE_DESC','Пользователь должен ввести дату.');
	define('M4J_LANG_TEXTFIELD_DESC','Пользователь должен ввести Текст.');
	define('M4J_LANG_OPTIONS_DESC','Пользователь выбирает один или несколько ответов из представленныхэлементов. ');
	define('M4J_LANG_CLOSE_PREVIEW','Закрыть Просмотр');
	define('M4J_LANG_Q_WIDTH','Ширина колонки вопроса. (левый)');
	define('M4J_LANG_A_WIDTH','Ширина колонки ответа. (правый)');
	define('M4J_LANG_OVERVIEW','Обзор');
	define('M4J_LANG_UPDATE_PROCEED','& Далее');
	define('M4J_LANG_NEW_ITEM','Новый Элемент');
	define('M4J_LANG_EDIT_ITEM','Редактировать Элемен');
	define('M4J_LANG_CATEGORY_NAME','Название Категории');
	define('M4J_LANG_EMAIL_ADRESS','Email Адрес');
	define('M4J_LANG_ADD_NEW_ITEM','Добавит новый элемент:');
	define('M4J_LANG_YOUR_QUESTION','Ваш Вопрос');
	define('M4J_LANG_REQUIRED_LONG','Обязательный?');
	define('M4J_LANG_HELP_TEXT','Вспомогательный Текст. Дайте пользователям подсказку для вопроса.(необязательно)');
	define('M4J_LANG_TYPE_OF_CHECKBOX','Тип кнопки:');
	define('M4J_LANG_ITEM_CHECKBOX','Кнопка-флажок(Checkbox).');
	define('M4J_LANG_YES_NO_MENU','Да/Нет Меню.');
	define('M4J_LANG_YES_ON','Да/Вкл.');
	define('M4J_LANG_NO_OFF','Нет/Выкл.');
	define('M4J_LANG_INIT_VALUE','Начальное Значение:');
	define('M4J_LANG_TYPE_OF_TEXTFIELD','Тип Текстового поля:');
	define('M4J_LANG_ITEM_TEXTFIELD','Текстовое поле');
	define('M4J_LANG_ITEM_TEXTAREA','Текстовая область');
	define('M4J_LANG_MAXCHARS_LONG','Максимум Символов:');
	define('M4J_LANG_OPTICAL_ALIGNMENT','Визуальное выравнивание:');
	define('M4J_LANG_ITEM_WIDTH_LONG','<b>Ширина в Пикселях</b> <br/>(Добавить \'%\' в процентном соотношении. Пусто = Автоматическая подгонка)<br/><br/>');
	define('M4J_LANG_ROWS_TEXTAREA','<b>Количество визуальных строк:</b><br/> (Only for Textarea)<br/><br/>');
	define('M4J_LANG_DROP_DOWN','<b>Меню</b>');
	define('M4J_LANG_RADIOBUTTONS','<b>Кнопка с зависимой фиксацией (Radiobuttons)</b>');
	define('M4J_LANG_LIST_SINGLE','<b>Список</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(одиночный выбор)');
	define('M4J_LANG_CHECKBOX_GROUP','<b>Группа кнопок-флажков (Checkbox)</b>');
	define('M4J_LANG_LIST_MULTIPLE','<b>Список</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(множественный выбор, с \'CTRL\' & левой кнопкой мыши)');
	define('M4J_LANG_SINGLE_CHOICE_LONG','Одиночный Выбор (Only one item can be selected):');
	define('M4J_LANG_MULTIPLE_CHOICE_LONG','Множественный Выбор (Множество элементов может быть выбрано):');
	define('M4J_LANG_TYPE_OF_OPTIONS','Тип Выбора:');
	define('M4J_LANG_ROWS_LIST','<b>Количество визуальных строк:</b><br/> (Только для Списков)<br/><br/>');
	define('M4J_LANG_ALIGNMENT_GROUPS','<b>Выравнивание: </b> <br/>(Только для Кнопок с зависимой фиксацией (Radiobuttons) и Групп Кнопок-флажков (Checkbox))<br/><br/>');
	define('M4J_LANG_OPTIONS_VALUES_INTRO','<b>Специфичные ответы.<br/>Пустые поля будут проигнорированы.</b>');
	define('M4J_LANG_CATEGORY_INTRO_LONG','Вводный текст. Будет отображаться только в категориях сайта.');
	define('M4J_LANG_TITLE','Заголовок');
	define('M4J_LANG_ERROR_NO_TITLE','Пожалуйста, введите заголовок.');
	define('M4J_LANG_USE_HELP','Вспомогательный Текст для подсказок клиентской части');
	define('M4J_LANG_TITLE_FORM','Заголовок Формы');
	define('M4J_LANG_INTROTEXT','Вступительный текст');
	define('M4J_LANG_MAINTEXT','Главный текст');
	define('M4J_LANG_EMAIL_HIDDEN_TEXT','Вступительный текст для Email. (Будет отображаться только в электронных письмах.)');
	define('M4J_LANG_TEMPLATE','Шаблон');
	define('M4J_LANG_LINK_TO_MENU','Ссылка в Меню');
	define('M4J_LANG_LINK_CAT_TO_MENU','Ссылка текущей категории в меню');
	define('M4J_LANG_LINK_TO_CAT','Ссылка Категории: ');
	define('M4J_LANG_LINK_TO_FORM','Ссылка Формы: ');
	define('M4J_LANG_LINK_TO_NO_CAT','Ссылка на отображение всех Форм без категории ');
	define('M4J_LANG_LINK_TO_ALL_CAT','Ссылка на отображение \'Все Категории\'');
	define('M4J_LANG_CHOOSE_MENU','Выберите меню ссылку на: ');
	define('M4J_LANG_MENU','Меню: ');
	define('M4J_LANG_NO_LINK_NAME','Пожалуйста, вставьте название ссылки.');
	define('M4J_LANG_PUBLISHED','Опубликовать:');
	define('M4J_LANG_PARENT_LINK','Родительская Ссылка');
	define('M4J_LANG_LINK_NAME','Название Ссылки');
	define('M4J_LANG_ACCESS_LEVEL','Уровень доступа:');
	define('M4J_LANG_EDIT_MAIN_DATA','Редактировать базовые данные');
	define('M4J_LANG_LEGEND','Надпись');
	define('M4J_LANG_LINK','Ссылка');
	define('M4J_LANG_IS_VISIBLE',' опубликовано');
	define('M4J_LANG_IS_HIDDEN',' не опубликовано');
	define('M4J_LANG_FORM','Форма');
	define('M4J_LANG_ITEM','Элемент');
	define('M4J_LANG_IS_REQUIRED','Обязательный');
	define('M4J_LANG_IS_NOT_REQUIRED','Необязательный');
	define('M4J_LANG_ASSIGN_ORDER','Порядок');
	define('M4J_LANG_ASSIGN_ORDER_HINT','* Упорядочивание не возможно для \'Все Формы\' !<br/>');
	define('M4J_LANG_EDIT_FORM','Редактировать формы');
	define('M4J_LANG_CAPTCHA','Каптча');
	define('M4J_LANG_HOVER_ACTIVE_ON','Опубликовано! Щелкните=Скрыть');
	define('M4J_LANG_HOVER_ACTIVE_OFF','Неопубликовано! Щелкните=Опубликовать');
	define('M4J_LANG_HOVER_REQUIRED_ON','Обязательный! Щелкните= Необязательный');
	define('M4J_LANG_HOVER_REQUIRED_OFF','Необязательный! Щелкните= Обязательный');
	define('M4J_LANG_DESCRIPTION','Описание');
	define('M4J_LANG_AREA','Area');
	define('M4J_LANG_ADJUSTMENT','Конфигурация');
	define('M4J_LANG_VALUE','Value');
	define('M4J_LANG_MAIN_CONFIG','Главная Конфигурация:');
	define('M4J_LANG_MAIN_CONFIG_DESC','Здесь Вы можете сконфигурировать все основные настройки. Если Вы хотите переустановить все основные настройки (включая CSS) на ПО-УМОЛЧАНИЮ щелкните сброс.');
	define('M4J_LANG_CSS_CONFIG','CSS Установки:');
	define('M4J_LANG_CSS_CONFIG_DESC','Эти настройки обязательны для визуального представления клиентской части. Если Вы не опытны во включении внешнего (своего) CSS, не изменяйте эти значения!');
	define('M4J_LANG_RESET','Сброс');
			
	define('M4J_LANG_EMAIL_ROOT', 'Главный Email адрес: ' );
	define('M4J_LANG_MAX_OPTIONS', 'Максимум ответов <br/> Специфичный выбор: ' );
	define('M4J_LANG_PREVIEW_WIDTH', 'Ширина Просмотра: ' );
	define('M4J_LANG_PREVIEW_HEIGHT', 'Высота Просмотра: ' );
	define('M4J_LANG_CAPTCHA_DURATION', 'Продолжительность Каптчи (в мин): ' );
	define('M4J_LANG_HELP_ICON', 'Иконка Помощи: ' );
	define('M4J_LANG_HTML_MAIL', 'HTML Email: ' );
	define('M4J_LANG_SHOW_LEGEND', 'Показать Описание: ' );
	
	define('M4J_LANG_EMAIL_ROOT_DESC', 'Главный email адрес используется если ни категория ни форма не связаны email адресом.' );
	define('M4J_LANG_MAX_OPTIONS_DESC', 'Это значение ограничивает максимальное количество ответов для пункта \'Специфичный выбор\'. Это значение должно быть числовым.' );	
	define('M4J_LANG_PREVIEW_WIDTH_DESC', 'Ширина предварительного просмотра шаблона. Это значение используется только в случаях, когда Вы не задали ширину предварительного просмотра в базовых данных шаблона.' );
	define('M4J_LANG_PREVIEW_HEIGHT_DESC', 'Высота предварительного просмотра шаблона. ' );
	define('M4J_LANG_CAPTCHA_DURATION_DESC', 'Относится к клиентской части. Это значение определяет максимальный срок действия каптчи. Если этот срок действия истекает, нужно ввести новый код каптчи, даже если введенный ранее код был верным.' );
	define('M4J_LANG_HELP_ICON_DESC', 'Определить цвет иконки помощи.' );
	define('M4J_LANG_HTML_MAIL_DESC', 'Если Вы хотите получать HTML emails, выберите да. ' );
	define('M4J_LANG_SHOW_LEGEND_DESC', 'Если Вы хотите отобразить описание на админке, выберите да.' );
	
	define('M4J_LANG_CLASS_HEADING', 'Главный Заголовок:' );
	define('M4J_LANG_CLASS_HEADER_TEXT', 'Текст Заголовка' );
	define('M4J_LANG_CLASS_LIST_WRAP', 'Список- Оболочка ' );
	define('M4J_LANG_CLASS_LIST_HEADING', 'Список- Заголовок' );
	define('M4J_LANG_CLASS_LIST_INTRO', 'Список- Вступительный текст ' );
	define('M4J_LANG_CLASS_FORM_WRAP', 'Форма- Оболочка ' );
	define('M4J_LANG_CLASS_FORM_TABLE', 'Форма- Таблица ' );
	define('M4J_LANG_CLASS_ERROR', 'Текст Ошибки' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP', 'Оболочка Кнопки Отправить' );
	define('M4J_LANG_CLASS_SUBMIT', 'Кнопка Отправить ' );
	define('M4J_LANG_CLASS_REQUIRED', 'Обязательный * css ' );
	
	define('M4J_LANG_CLASS_HEADING_DESC', '<strong>DIV-Tag</strong> - Заголовок сайта ' );
	define('M4J_LANG_CLASS_HEADER_TEXT_DESC', '<strong>DIV-Tag</strong> - Контент ниже заголовка. ' );
	define('M4J_LANG_CLASS_LIST_WRAP_DESC', '<strong>DIV-Tag</strong> - Переход к следующему элементу в списке.' );
	define('M4J_LANG_CLASS_LIST_HEADING_DESC', '<strong>DIV-Tag</strong> - Заголовок элемента списка. ' );
	define('M4J_LANG_CLASS_LIST_INTRO_DESC', '<strong>DIV-Tag</strong> - Вступительный текст элемента списка. ' );
	define('M4J_LANG_CLASS_FORM_WRAP_DESC', '<strong>DIV-Tag</strong> - Переход к области формы. ' );
	define('M4J_LANG_CLASS_FORM_TABLE_DESC', '<strong>TABLE-Tag</strong> - Таблица, отображающая все пункты формы.' );
	define('M4J_LANG_CLASS_ERROR_DESC', '<strong>SPAN-Tag</strong> - CSS класс сообщений об ошибке. ' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP_DESC', '<strong>DIV-Tag</strong> - Переход к кнопке отправить ' );
	define('M4J_LANG_CLASS_SUBMIT_DESC', '<strong>INPUT-Tag</strong> - CSS класс кнопки отправить. ' );
	define('M4J_LANG_CLASS_REQUIRED_DESC', '<strong>SPAN-Tag</strong> - CSS класс \' <b>*</b> \' символа, обозначающего обязательные поля.' );
	
	define('M4J_LANG_INFO_HELP','Инфо и Помощь');
	
	// Новое в Версии 1.1.5
	define('M4J_LANG_CHOOSE_CAPTCHA', 'Каптча Метод: ' ); 
	define('M4J_LANG_CSS','CSS');
	define('M4J_LANG_SIMPLE','Обычный');
	
	//Новое в Версии 1.1.7
		define('M4J_LANG_CONFIG_RESET','Конфигурация была успешно сброшена.');
		define('M4J_LANG_CONFIG_SAVED','Конфигурация была успешно сохранена.');
		define('M4J_LANG_CAPTCHA_DESC', ' Возможны проблемы со стандартной css-каптчей и некоторыми серверами или шаблонами. В этом случае Вы можете выбрать между стандартной css-каптчей и обычной каптчей. Если обычная каптча не решила Вашей проблемы, выберите Специальный' );
		define('M4J_LANG_SPECIAL','Специальный');
	
	
	define('M4J_LANG_MAIL_ISO','Mail кодировка');
	define('M4J_LANG_MAIL_ISO_DESC','utf-8 , iso-8859-1 (Western Europe), iso-8859-4 (Balto), iso-8859-5 (Cyrillic), iso-8859-6 (Arabic), iso-8859-7 (Greek), iso-8859-8 (Hebrew),iso-8859-9 (Turkish), iso-8859-10 (Nordic),iso-8859-11 (Thai)');		
	
	
	// Новое в Версии 1.1.8
	$m4j_lang_elements[40]= 'Прикрепить';	
	define('M4J_LANG_ATTACHMENT','Прикрепить Файл');
	define('M4J_LANG_ATTACHMENT_DESC','Пользователь может передать файл, используя форму.');
	define('M4J_LANG_TYPE_OF_ATTACHMENT','Введите параметры поля передачи файла:');
	define('M4J_LANG_ALLOWED_ENDINGS','Разрешенные расширения файлов.');
	define('M4J_LANG_MAXSIZE','Максимальный размер файлов.');
	define('M4J_LANG_BYTE','Байт');
	define('M4J_LANG_KILOBYTE','Килобайт');
	define('M4J_LANG_MEGABYTE','Мегабайт');
	define('M4J_LANG_ELEMENT_ATTACHMENT_DESC','Пожалуйста, введите все расширения файлов без точек и отделенные <b>запятыми</b>.Если Вы оставляете пустые поля, будут приняты все расширения файлов или будет разрешен любой размер. Чтобы облегчить работу, Вы можете выбрать из расширений внизу, включенных автоматически.');
	define('M4J_LANG_IMAGES','Изображения');
	define('M4J_LANG_DOCS','Документы');
	define('M4J_LANG_AUDIO','Аудио');
	define('M4J_LANG_VIDEO','Видео');										   
    define('M4J_LANG_DATA','Данные');
	define('M4J_LANG_COMPRESSED','Сжатие');
	define('M4J_LANG_OTHERS','Другие');
	define('M4J_LANG_ALL','Все');
	
	// Новое в Версии 1.1.9
	define('M4J_LANG_FROM_NAME','От кого');
	define('M4J_LANG_FROM_EMAIL','С какого email');
	define('M4J_LANG_FROM_NAME_DESC','Вставьте от кого для писем, которые Вы будете получать <br/>');
	define('M4J_LANG_FROM_EMAIL_DESC','Вставьте с какого email адреса для писем, которые Вы будете получать.<br/>');
	define('M4J_LANG_TEMPLATE_DELETE_CAUTION',' Внимание! Все формы, содержащие этот шаблон, также будут удалены!');
	
	
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