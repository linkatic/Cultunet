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

	/** Czech translation by Milan Šedý - wortyr@woraif.cz, http://www.woraif.cz */


	defined( '_JEXEC' ) or die( 'Přímý přístup na toto místo není povolen.' );
	
	$m4j_lang_elements[1]= 'Zaškrtávací políčko';
	$m4j_lang_elements[2]= 'Přepínač ano/ne';
	$m4j_lang_elements[10]= 'Datum';
	$m4j_lang_elements[20]= 'Jednoduchý text';
	$m4j_lang_elements[21]= 'Víceřádkový text';
	$m4j_lang_elements[30]= 'Menu(výber jedné možnosti)';
	$m4j_lang_elements[31]= 'Výběrové menu(výber jedné možnosti)';
	$m4j_lang_elements[32]= 'Přepínač(výběr jedné možnosti)';
	$m4j_lang_elements[33]= 'Zašktávání(výběr více možností)';
	$m4j_lang_elements[34]= 'List(multiple choice)';
	
	
	define('M4J_LANG_FORMS','Formuláře');
	define('M4J_LANG_TEMPLATES','Šablony');
	define('M4J_LANG_CATEGORY','Kategorie');
	define('M4J_LANG_CONFIG','Konfigurace');
	define('M4J_LANG_HELP','Info & nápověda');
	define('M4J_LANG_CANCEL','Zrušit');
	define('M4J_LANG_PROCEED','Pokračovat');
	define('M4J_LANG_SAVE','Uložit');
	define('M4J_LANG_NEW_FORM','Nový formulář');
	define('M4J_LANG_NEW_TEMPLATE','Nová šablona');
	define('M4J_LANG_ADD','Přidat');
	define('M4J_LANG_EDIT_NAME','Upravit název a popis této šablony');
	define('M4J_LANG_NEW_TEMPLATE_LONG','Nová šablona');
	define('M4J_LANG_TEMPLATE_NAME','Název této šablony');
	define('M4J_LANG_TEMPLATE_NAME_EDIT','Upravit název této šablony');
	define('M4J_LANG_TEMPLATE_DESCRIPTION','Krátký popis (pro vnitřní potřeby; může zůstat prázdné)');
	define('M4J_LANG_TEMPLATE_DESCRIPTION_EDIT','Upravit krátký popis');
	define('M4J_LANG_DELETE','Smazat');
	define('M4J_LANG_DELETE_CONFIRM','Opravdu chcete tuto položku vymazat?');
	define('M4J_LANG_NEW_CATEGORY','Nová kategorie');
	define('M4J_LANG_NAME','Název');
	define('M4J_LANG_SHORTDESCRIPTION','Krátký popis');
	define('M4J_LANG_ID','ID');
	define('M4J_LANG_ITEMS','Položky');
	define('M4J_LANG_EDIT','Upravit');
	define('M4J_LANG_EDIT_TEMPLATE_ITEMS','Položky -> Upravit');
	define('M4J_LANG_TEMPLATE_NAME_REQUIRED','Vložte prosím název této šablony!');
	define('M4J_LANG_AT_LEAST_ONE','Není možné vymazat položku!<br/>Musí zde být alespoň jedna položka.');	

	
		define('M4J_LANG_EDIT_ELEMENT','Upravit položku šablony: ');
	define('M4J_LANG_CATEGORY_NAME_ERROR','Vložte prosím název kategorie');
	define('M4J_LANG_NONE_LEGAL_EMAIL','Vložte prosím platnou e-mailovou adresu, nebo nechte pole prázdné.<br/>');
	define('M4J_LANG_EMAIL','E-mail');
	define('M4J_LANG_POSITION','Objednávky');
	define('M4J_LANG_ACTIVE','Aktivní');
	define('M4J_LANG_UP','Nahoru');
	define('M4J_LANG_DOWN','Dolů');
	define('M4J_LANG_EDIT_CATEGORY','Upravit kategorii');
	define('M4J_LANG_TEMPLATE_ELEMENTS','Položky na šabloně: ');
	define('M4J_LANG_NEW_ELEMENT_LONG','Přidat novou položku na šablonu: ');	
	define('M4J_LANG_ELEMENT_NO_QUESTION_ERROR','Prosím vložte otázku.');
	define('M4J_LANG_REQUIRED','Povinné');
	define('M4J_LANG_QUESTION','Otázka');
	define('M4J_LANG_TYPE','Typ');
	define('M4J_LANG_YES','Ano');		
	define('M4J_LANG_NO','Ne');	
	define('M4J_LANG_ALL_FORMS','Všechny formuláře');
	define('M4J_LANG_NO_CATEGORYS','Bez kategorie');
	define('M4J_LANG_FORMS_OF_CATEGORY','Formuláře v kategorii: ');
	define('M4J_LANG_PREVIEW','Náhled');
	define('M4J_LANG_DO_COPY','Kopírovat');		
	define('M4J_LANG_COPY','Kopírovat');
	define('M4J_LANG_VERTICAL','Vertikální');
	define('M4J_LANG_HORIZONTAL','Horizontální');
	define('M4J_LANG_EXAMPLE','Příklad');
	define('M4J_LANG_CHECKBOX','Tlačítko');	
	define('M4J_LANG_DATE','Datum');
	define('M4J_LANG_TEXTFIELD','Textové pole');
	define('M4J_LANG_OPTIONS','Vybraná volba');
	define('M4J_LANG_CHECKBOX_DESC','Jednoduchá otázka ano/ne.');
	define('M4J_LANG_DATE_DESC','Uživatel musí vložit datum.');
	define('M4J_LANG_TEXTFIELD_DESC','Užvatel musí vložit vlastní text.');
	define('M4J_LANG_OPTIONS_DESC','Uživatel vybere jednu nebo více odpovědí z dostupné nabídky. ');
	define('M4J_LANG_CLOSE_PREVIEW','Zavřít náhled');
	define('M4J_LANG_Q_WIDTH','Šířka sloupce s otázkami. (vlevo)');
	define('M4J_LANG_A_WIDTH','Šířka sloupce s odpověďmi. (vpravo)');
	define('M4J_LANG_OVERVIEW','Přehled');
	define('M4J_LANG_UPDATE_PROCEED','& Pokračovat');
	define('M4J_LANG_NEW_ITEM','Nová položka');
	define('M4J_LANG_EDIT_ITEM','Upravit položku');
	define('M4J_LANG_CATEGORY_NAME','Jméno kategorie');
	define('M4J_LANG_EMAIL_ADRESS','E-mailová adresa');
	define('M4J_LANG_ADD_NEW_ITEM','Přidat novou položku:');
	define('M4J_LANG_YOUR_QUESTION','Vaše otázka');
	define('M4J_LANG_REQUIRED_LONG','Povinná?');
	define('M4J_LANG_HELP_TEXT','Text nápovědy. Podejté uživatelům nápovědu k vaší otázce (nepovinné)');
	define('M4J_LANG_TYPE_OF_CHECKBOX','Typ tlačítka:');
	define('M4J_LANG_ITEM_CHECKBOX','Zaškrávací pole.');
	define('M4J_LANG_YES_NO_MENU','Menu Ano/ne.');
	define('M4J_LANG_YES_ON','ano/ne.');
	define('M4J_LANG_NO_OFF','ne/vypnuto.');
	define('M4J_LANG_INIT_VALUE','Předvyplněná hodnota:');
	define('M4J_LANG_TYPE_OF_TEXTFIELD','Typ textového pole:');
	define('M4J_LANG_ITEM_TEXTFIELD','Textové pole');
	define('M4J_LANG_ITEM_TEXTAREA','Textová oblast');
	define('M4J_LANG_MAXCHARS_LONG','Maximální počet znaků:');
	define('M4J_LANG_OPTICAL_ALIGNMENT','Vizuální zarovnání:');
	define('M4J_LANG_ITEM_WIDTH_LONG','<b>Šířka v pixelech</b> <br/>(Přidáním \'%\' nastavíte šířku procentuálně. Prázdné = automaticky)<br/><br/>');
	define('M4J_LANG_ROWS_TEXTAREA','<b>Počet viditelných řádků:</b><br/> (pouze pro textové oblasti)<br/><br/>');
	define('M4J_LANG_DROP_DOWN','<b>Menu</b>');
	define('M4J_LANG_RADIOBUTTONS','<b>Přepínače</b>');
	define('M4J_LANG_LIST_SINGLE','<b>Seznam</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(výběr jedné položky)');
	define('M4J_LANG_CHECKBOX_GROUP','<b>Skupina zaškrtávacích polí</b>');
	define('M4J_LANG_LIST_MULTIPLE','<b>Seznam</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(výběr více možností užitím \'CTRL\' + levé tlačítko myši)');
	define('M4J_LANG_SINGLE_CHOICE_LONG','Jedna možnost (může být vybrána pouze 1 položka):');
	define('M4J_LANG_MULTIPLE_CHOICE_LONG','Více možností (může být vybráno více položek):');
	define('M4J_LANG_TYPE_OF_OPTIONS','Typ výběru:');
	define('M4J_LANG_ROWS_LIST','<b>Počet viditelných řádků:</b><br/> (pouze pro seznamy)<br/><br/>');
	define('M4J_LANG_ALIGNMENT_GROUPS','<b>Zarovnání: </b> <br/>(pouze pro přepínače a skupiny zaškrtávacích polí)<br/><br/>');
	define('M4J_LANG_OPTIONS_VALUES_INTRO','<b>Popište odpovědi.<br/>Prázdná pole budou ignorována.</b>');
	define('M4J_LANG_CATEGORY_INTRO_LONG','Úvodní text. Bude zobrazen pouze na stránce s kategoriemi.');
	define('M4J_LANG_TITLE','Nadpis');
	define('M4J_LANG_ERROR_NO_TITLE','Prosím vložte nadpis.');
	define('M4J_LANG_USE_HELP','Text nápovědy do bubliny');
	define('M4J_LANG_TITLE_FORM','Nadpis formuláře');
	define('M4J_LANG_INTROTEXT','Úvodní text');
	define('M4J_LANG_MAINTEXT','Hlavní text');
	define('M4J_LANG_EMAIL_HIDDEN_TEXT','Úvodní text odeslaného e-mailu. (Použije se pouze v e-mailech.)');
	define('M4J_LANG_TEMPLATE','Šablona');
	define('M4J_LANG_LINK_TO_MENU','Odkaz do menu');
	define('M4J_LANG_LINK_CAT_TO_MENU','Odkázat vybranou kategorii do menu.');
	define('M4J_LANG_LINK_TO_CAT','Odkázat kategorii: ');
	define('M4J_LANG_LINK_TO_FORM','Odkázat formulář: ');
	define('M4J_LANG_LINK_TO_NO_CAT','Odkaz k zobrazení všech formulářů bez kategorií ');
	define('M4J_LANG_LINK_TO_ALL_CAT','Odkaz k zobrazení přehledu \'Všechny kategorie\'');
	define('M4J_LANG_CHOOSE_MENU','Vyberte menu, na které se odkázat: ');
	define('M4J_LANG_MENU','Menu: ');
	define('M4J_LANG_NO_LINK_NAME','Vložte prosím název odkazu.');
	define('M4J_LANG_PUBLISHED','Zveřejněno:');
	define('M4J_LANG_PARENT_LINK','Nadřazený odkaz');
	define('M4J_LANG_LINK_NAME','Název odkazu');
	define('M4J_LANG_ACCESS_LEVEL','Přístupová úroveň:');
	define('M4J_LANG_EDIT_MAIN_DATA','Upravit základní data');
	define('M4J_LANG_LEGEND','Legenda');
	define('M4J_LANG_LINK','Odkaz');
	define('M4J_LANG_IS_VISIBLE',' je zveřejněn');
	define('M4J_LANG_IS_HIDDEN',' není zveřejněn');
	define('M4J_LANG_FORM','Formulář');
	define('M4J_LANG_ITEM','Položka');
	define('M4J_LANG_IS_REQUIRED','Povinné');
	define('M4J_LANG_IS_NOT_REQUIRED','Nepovinné');
	define('M4J_LANG_ASSIGN_ORDER','Řazení');
	define('M4J_LANG_ASSIGN_ORDER_HINT','* Řazení není dostupné pro kategorii \'Všechny formuláře\'!<br/>');
	define('M4J_LANG_EDIT_FORM','Upravit formuláře');
	define('M4J_LANG_CAPTCHA','Captcha');
	define('M4J_LANG_HOVER_ACTIVE_ON','Zveřejněno! Kliknutím zneveřejnit.');
	define('M4J_LANG_HOVER_ACTIVE_OFF','Neveřejné! Kliknutím zveřejnit.');
	define('M4J_LANG_HOVER_REQUIRED_ON','Povinné! Kliknutím nastavit na nepovinné.');
	define('M4J_LANG_HOVER_REQUIRED_OFF','Nepovinné! Kliknutím nastavit na povinné.');
	define('M4J_LANG_DESCRIPTION','Popis');
	define('M4J_LANG_AREA','Oblast');
	define('M4J_LANG_ADJUSTMENT','Konfigurace');
	define('M4J_LANG_VALUE','Hodnota');
	define('M4J_LANG_MAIN_CONFIG','Zázkadní konfigurace:');
	define('M4J_LANG_MAIN_CONFIG_DESC','Zde můžete nastavit všechna základní nastavení. Pokud chcete všechna základní nastavení resetovat (včetně CSS) na výchozí, klikněte na reset.');
	define('M4J_LANG_CSS_CONFIG','CSS nastavení:');
	define('M4J_LANG_CSS_CONFIG_DESC','Tyto nastavení jsou potřebná pro vizuální prezentaci na webu. Pokud nemáte zkušenosti s vkládáním vlastních CSS stylů, neměňte tyto hodnoty!');
	define('M4J_LANG_RESET','Reset');
			
	define('M4J_LANG_EMAIL_ROOT', 'Výchozí e-mailová adresa: ' );
	define('M4J_LANG_MAX_OPTIONS', 'Maximální počet odpovědí <br/> výběrových polí: ' );
	define('M4J_LANG_PREVIEW_WIDTH', 'Šířka náhledu: ' );
	define('M4J_LANG_PREVIEW_HEIGHT', 'Výška náhledu: ' );
	define('M4J_LANG_CAPTCHA_DURATION', 'Platnost Captcha obrázku (v min): ' );
	define('M4J_LANG_HELP_ICON', 'Ikona nápovědy: ' );
	define('M4J_LANG_HTML_MAIL', 'HTML E-mail: ' );
	define('M4J_LANG_SHOW_LEGEND', 'Zobrazit legendu: ' );
	
	define('M4J_LANG_EMAIL_ROOT_DESC', 'Výchozí e-mailová adresa se použije, pokud nebyl zadán e-mail ani u formuláře, ani u kategorie.' );
	define('M4J_LANG_MAX_OPTIONS_DESC', 'Tato hodnota určuje maximální počet odpovědí ve výběrovém poli. Hodnota musí být číselná.' );	
	define('M4J_LANG_PREVIEW_WIDTH_DESC', 'Šířka okna s náhledem šablony. Tato hodnota se použije pouze v případě, že nezvolíte šířku náhledu v základním nastavení.' );
	define('M4J_LANG_PREVIEW_HEIGHT_DESC', 'Šířka náhledu šablony. ' );
	define('M4J_LANG_CAPTCHA_DURATION_DESC', 'Určuje dobu, po kterou je platný kód z obrázku captcha, který musí uživatel opsat před odesláním.' );
	define('M4J_LANG_HELP_ICON_DESC', 'Určuje barvu ikony nápovědy.' );
	define('M4J_LANG_HTML_MAIL_DESC', 'Pokud chcete dostávat e-maily v HTML formátu, zvolte ano. ' );
	define('M4J_LANG_SHOW_LEGEND_DESC', 'Pokud chcete v administraci zobrazovat legendu, zvolte ano.' );
	
	define('M4J_LANG_CLASS_HEADING', 'Blok hlavičky:' );
	define('M4J_LANG_CLASS_HEADER_TEXT', 'Text hlavičky' );
	define('M4J_LANG_CLASS_LIST_WRAP', 'Oblast výpisu' );
	define('M4J_LANG_CLASS_LIST_HEADING', 'Výpis - hlavička' );
	define('M4J_LANG_CLASS_LIST_INTRO', 'Výpis - úvodní text ' );
	define('M4J_LANG_CLASS_FORM_WRAP', 'Oblast formuláře ' );
	define('M4J_LANG_CLASS_FORM_TABLE', 'Formulář - tabulka ' );
	define('M4J_LANG_CLASS_ERROR', 'Chybová hlášení' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP', 'Oblast tlačítka "Odeslat"' );
	define('M4J_LANG_CLASS_SUBMIT', 'Tlačitko  "Odeslat"' );
	define('M4J_LANG_CLASS_REQUIRED', 'CSS * - označení povinné' );
	
	define('M4J_LANG_CLASS_HEADING_DESC', '<strong>DIV-Tag</strong> - Nadpis stránky ' );
	define('M4J_LANG_CLASS_HEADER_TEXT_DESC', '<strong>DIV-Tag</strong> - Obsah po nadpisu stránky. ' );
	define('M4J_LANG_CLASS_LIST_WRAP_DESC', '<strong>DIV-Tag</strong> - Oblast výpisu položek.' );
	define('M4J_LANG_CLASS_LIST_HEADING_DESC', '<strong>DIV-Tag</strong> - Nadpis výpisu položek. ' );
	define('M4J_LANG_CLASS_LIST_INTRO_DESC', '<strong>DIV-Tag</strong> - Úvodní text výpisu položek. ' );
	define('M4J_LANG_CLASS_FORM_WRAP_DESC', '<strong>DIV-Tag</strong> - Oblast formuláře. ' );
	define('M4J_LANG_CLASS_FORM_TABLE_DESC', '<strong>TABLE-Tag</strong> -Tabulka, ve které jsou obsaženy položky formuláře.' );
	define('M4J_LANG_CLASS_ERROR_DESC', '<strong>SPAN-Tag</strong> - CSS třída výpisu chybových zpráv. ' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP_DESC', '<strong>DIV-Tag</strong> - Oblast s tlačítkem na odeslání dat.' );
	define('M4J_LANG_CLASS_SUBMIT_DESC', '<strong>INPUT-Tag</strong> - CSS třída tlačítka na odeslání dat. ' );
	define('M4J_LANG_CLASS_REQUIRED_DESC', '<strong>SPAN-Tag</strong> - CSS třída znaku \' <b>*</b> \', který označuje povinné položky.' );
	
	define('M4J_LANG_INFO_HELP','Info a nápověda');
	
	// New to Version 1.1.5
	define('M4J_LANG_CHOOSE_CAPTCHA', 'Captcha metoda: ' ); 
	define('M4J_LANG_CSS','CSS');
	define('M4J_LANG_SIMPLE','Běžná');
	
	//New To Version 1.1.7
		define('M4J_LANG_CONFIG_RESET','Nastavení úspěšně nastaveno na výchozí.');
		define('M4J_LANG_CONFIG_SAVED','Nastavení úspěšně uloženo.');
		define('M4J_LANG_CAPTCHA_DESC', ' Je možné, že se na některých serverech nebo u některých šablon objeví potíže výchozí CSS captcha. V tomto případě máte možnost volby "Běžná captcha". Pokud ani ta nevyřeší vaše problémy, použijte možnost "Speciální"' );
		define('M4J_LANG_SPECIAL','Speciální');
	
	
	define('M4J_LANG_MAIL_ISO','Kódování e-mailu');
	define('M4J_LANG_MAIL_ISO_DESC','utf-8 , iso-8859-1 (západní Evropa), iso-8859-4 (Balt), iso-8859-5 (cyrilice), iso-8859-6 (arabské), iso-8859-7 (řecké), iso-8859-8 (hebrejské),iso-8859-9 (turecké), iso-8859-10 (norské),iso-8859-11 (thajské)');		
	
	
	// New to Version 1.1.8
	$m4j_lang_elements[40]= 'Příloha';	
	define('M4J_LANG_ATTACHMENT','Soubor přílohy');
	define('M4J_LANG_ATTACHMENT_DESC','Uživatel může pomocí formuláře vložit soubor.');
	define('M4J_LANG_TYPE_OF_ATTACHMENT','Vložte parametry pro pole odeslání souboru:');
	define('M4J_LANG_ALLOWED_ENDINGS','Povolené přípony souborů.');
	define('M4J_LANG_MAXSIZE','Maximální velikost.');
	define('M4J_LANG_BYTE','Bajt');
	define('M4J_LANG_KILOBYTE','Kilobajt');
	define('M4J_LANG_MEGABYTE','Megabajt');
	define('M4J_LANG_ELEMENT_ATTACHMENT_DESC','Všechny přípony prosím vložte bez tečky a oddělte je <b>čárkami</b>. Pokud necháte pole prázdná, bude odeslán soubor s libovolnou příponou neomezené velikosti. K usnadnění práce si můžete vybrat s přípon níže, které budou zahrnuty automaticky.');
	define('M4J_LANG_IMAGES','Obrázky');
	define('M4J_LANG_DOCS','Dokumenty');
	define('M4J_LANG_AUDIO','Audio');
	define('M4J_LANG_VIDEO','Video');										   
    define('M4J_LANG_DATA','Data');
	define('M4J_LANG_COMPRESSED','Archivy');
	define('M4J_LANG_OTHERS','Ostatní');
	define('M4J_LANG_ALL','Všechny');
	
	// New to Version 1.1.9
	define('M4J_LANG_FROM_NAME','Odesláno od');
	define('M4J_LANG_FROM_EMAIL','Odesláno z e-mailu');
	define('M4J_LANG_FROM_NAME_DESC','Jméno (název), které bude uvedeno v emailech jako odesilatel<br/>');
	define('M4J_LANG_FROM_EMAIL_DESC','E-mail, který bude v odchozích e-mailech uveden jako odesílatel.<br/>');
	define('M4J_LANG_TEMPLATE_DELETE_CAUTION',' Vyrování! Všechny formuláře, které obsahují tuto šablonu budou také vymazány!');
	
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
	define('M4J_LANG_','');
?>