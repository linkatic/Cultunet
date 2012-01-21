<?php
	/**
	* @version $Id: mad4joomla 10041 2008-03-18 21:48:13Z fahrettinkutyol $
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

	/**  ENGLISH VERSION. NEEDS TO BE TRANSLATED */


	defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
	
	$m4j_lang_elements[1]= 'Checkbox';
	$m4j_lang_elements[2]= 'Áno/Nie Výber';
	$m4j_lang_elements[10]= 'Dátum';
	$m4j_lang_elements[20]= 'Textové Pole';
	$m4j_lang_elements[21]= 'Textová Oblasť';
	$m4j_lang_elements[30]= 'Menu(jeden výber)';
	$m4j_lang_elements[31]= 'Výberové Menu(jeden výber)';
	$m4j_lang_elements[32]= 'Radiobuttony(jeden výber)';
	$m4j_lang_elements[33]= 'Checkboxy Group(viac výberov)';
	$m4j_lang_elements[34]= 'Zoznam(viac výberov)';
	
	
	define('M4J_LANG_FORMS','Formuláre');
	define('M4J_LANG_TEMPLATES','Templaty');
	define('M4J_LANG_CATEGORY','Kategória');
	define('M4J_LANG_CONFIG','Konfigurácia');
	define('M4J_LANG_HELP','Info & Pomoc');
	define('M4J_LANG_CANCEL','Zrušiť');
	define('M4J_LANG_PROCEED','Pokračuj');
	define('M4J_LANG_SAVE','Ulož');
	define('M4J_LANG_NEW_FORM','Nový Formulár');
	define('M4J_LANG_NEW_TEMPLATE','Nový Template');
	define('M4J_LANG_ADD','Pridaj');
	define('M4J_LANG_EDIT_NAME','Zmen meno a popis tohto templatu');
	define('M4J_LANG_NEW_TEMPLATE_LONG','Nový Template');
	define('M4J_LANG_TEMPLATE_NAME','Meno tohto templatu');
	define('M4J_LANG_TEMPLATE_NAME_EDIT','Zmen meno tohto templatu');
	define('M4J_LANG_TEMPLATE_DESCRIPTION','Krátky popis (pre vnútorné použitie, môže byť prázdne)');
	define('M4J_LANG_TEMPLATE_DESCRIPTION_EDIT','Uprav krátky popis');
	define('M4J_LANG_DELETE','Zmaž');
	define('M4J_LANG_DELETE_CONFIRM','Checete skutočne zmazať túto položku?');
	define('M4J_LANG_NEW_CATEGORY','Nová Kategória');
	define('M4J_LANG_NAME','Meno');
	define('M4J_LANG_SHORTDESCRIPTION','Krátky popis');
	define('M4J_LANG_ID','ID');
	define('M4J_LANG_ITEMS','Položky');
	define('M4J_LANG_EDIT','Uprav');
	define('M4J_LANG_EDIT_TEMPLATE_ITEMS','Položky -> Uprav');
	define('M4J_LANG_TEMPLATE_NAME_REQUIRED','Prosím, zadjate meno pre tento template !');
	define('M4J_LANG_AT_LEAST_ONE','Táto položka nemôže byť zmazaná!<br/>Musí obsahovať aspoň jeden element.');	
	define('M4J_LANG_NEW_CATEGORY','New Category');
	
		define('M4J_LANG_EDIT_ELEMENT','Edituj element templatu: ');
	define('M4J_LANG_CATEGORY_NAME_ERROR','Prosím vložte meno kategórie');
	define('M4J_LANG_NONE_LEGAL_EMAIL','Prosím vložte validný email, alebo nechajte toto políčko prázdne.<br/>');
	define('M4J_LANG_EMAIL','eMail');
	define('M4J_LANG_POSITION','Objednávka');
	define('M4J_LANG_ACTIVE','Aktívne');
	define('M4J_LANG_UP','Hore');
	define('M4J_LANG_DOWN','Dole');
	define('M4J_LANG_EDIT_CATEGORY','Uprav kategóriu');
	define('M4J_LANG_TEMPLATE_ELEMENTS','Elementy templatu: ');
	define('M4J_LANG_NEW_ELEMENT_LONG','Vlož nový element do templatu: ');	
	define('M4J_LANG_ELEMENT_NO_QUESTION_ERROR','Prosím vložte otázku.');
	define('M4J_LANG_REQUIRED','Vyžadované');
	define('M4J_LANG_QUESTION','Otázka');
	define('M4J_LANG_TYPE','Typ');
	define('M4J_LANG_YES','Áno');		
	define('M4J_LANG_NO','Nie');	
	define('M4J_LANG_ALL_FORMS','Vśetky formuláre');
	define('M4J_LANG_NO_CATEGORYS','Bez kategórie');
	define('M4J_LANG_FORMS_OF_CATEGORY','Formuláre kategórie: ');
	define('M4J_LANG_PREVIEW','Náhľad');
	define('M4J_LANG_DO_COPY','Kopírovať');		
	define('M4J_LANG_COPY','Kopírovať');
	define('M4J_LANG_VERTICAL','Vertikálne');
	define('M4J_LANG_HORIZONTAL','Horizontálne');
	define('M4J_LANG_EXAMPLE','Príklad');
	define('M4J_LANG_CHECKBOX','Tlačidlo');	
	define('M4J_LANG_DATE','Dátum');
	define('M4J_LANG_TEXTFIELD','Textové pole');
	define('M4J_LANG_OPTIONS','Vybraná odpoveď');
	define('M4J_LANG_CHECKBOX_DESC','Jednoduchá Áno/Nie otázka.');
	define('M4J_LANG_DATE_DESC','Uživateľ musí poskytnúť dátum.');
	define('M4J_LANG_TEXTFIELD_DESC','Uživateľ musí poskytnúť individuálny text.');
	define('M4J_LANG_OPTIONS_DESC','Uživateľ vyberá jednu, alebo viac odpovedí, z vybraného zoznamu.');
	define('M4J_LANG_CLOSE_PREVIEW','Zatvor Náhlad');
	define('M4J_LANG_Q_WIDTH','Šírka kolónky na otázku. (vľavo)');
	define('M4J_LANG_A_WIDTH','Šírka kolónky na odpoveď. (vpravo)');
	define('M4J_LANG_OVERVIEW','Prehľad');
	define('M4J_LANG_UPDATE_PROCEED','& Pokračuj');
	define('M4J_LANG_NEW_ITEM','Nová položka');
	define('M4J_LANG_EDIT_ITEM','Uprav položku');
	define('M4J_LANG_CATEGORY_NAME','Meno kategórie');
	define('M4J_LANG_EMAIL_ADRESS','Emailová adresa');
	define('M4J_LANG_ADD_NEW_ITEM','Pridaj novú položku:');
	define('M4J_LANG_YOUR_QUESTION','Tvoja otázka');
	define('M4J_LANG_REQUIRED_LONG','Vyžadované?');
	define('M4J_LANG_HELP_TEXT','Pomocný Help. Dáva uźivateľovy info o otázke.(nieje potrebné)');
	define('M4J_LANG_TYPE_OF_CHECKBOX','Typ tlačidla:');
	define('M4J_LANG_ITEM_CHECKBOX','Checkbox.');
	define('M4J_LANG_YES_NO_MENU','Áno/Nie Menu.');
	define('M4J_LANG_YES_ON','Áno/Zapnúť.');
	define('M4J_LANG_NO_OFF','Nie/Vypnúť.');
	define('M4J_LANG_INIT_VALUE','Úvodná hodnota:');
	define('M4J_LANG_TYPE_OF_TEXTFIELD','Typ textového pola:');
	define('M4J_LANG_ITEM_TEXTFIELD','Textové pole');
	define('M4J_LANG_ITEM_TEXTAREA','Textová oblasť');
	define('M4J_LANG_MAXCHARS_LONG','Maximum znakov:');
	define('M4J_LANG_OPTICAL_ALIGNMENT','Visuálne usporiadanie:');
	define('M4J_LANG_ITEM_WIDTH_LONG','<b>Šírka v pixeloch</b> <br/>(Pridaj \'%\' pre percento. Prázdne = Automatická šírka)<br/><br/>');
	define('M4J_LANG_ROWS_TEXTAREA','<b>Množstvo viditeľných riadkov:</b><br/> (Jedine textová oblasť)<br/><br/>');
	define('M4J_LANG_DROP_DOWN','<b>Menu</b>');
	define('M4J_LANG_RADIOBUTTONS','<b>Radiobuttony</b>');
	define('M4J_LANG_LIST_SINGLE','<b>List</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(jeden výber)');
	define('M4J_LANG_CHECKBOX_GROUP','<b>Checkbox skupina</b>');
	define('M4J_LANG_LIST_MULTIPLE','<b>List</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(viac volieb, s \'CTRL\' & ľavou myšou)');
	define('M4J_LANG_SINGLE_CHOICE_LONG','Jeden výber (Dá sa vybrať len jedna položka):');
	define('M4J_LANG_MULTIPLE_CHOICE_LONG','Viac možností (Dá sa vybrať viac položiek):');
	define('M4J_LANG_TYPE_OF_OPTIONS','Typ výberu:');
	define('M4J_LANG_ROWS_LIST','<b>Množstvo vyiditelných riadkov:</b><br/> (Jedine pre Zoznam)<br/><br/>');
	define('M4J_LANG_ALIGNMENT_GROUPS','<b>Usporiadanie: </b> <br/>(Jedine pre Radiobuttony a Checkboxové skupiny)<br/><br/>');
	define('M4J_LANG_OPTIONS_VALUES_INTRO','<b>Vyber odpoveď.<br/>Prázdne polia budú ignorované.</b>');
	define('M4J_LANG_CATEGORY_INTRO_LONG','Introtext. je zobrazený len na stránkach s vybranou kategóriou.');
	define('M4J_LANG_TITLE','Nadpis');
	define('M4J_LANG_ERROR_NO_TITLE','Prosím vložte nadpis.');
	define('M4J_LANG_USE_HELP','Pomocný text pre frontend bublinové typy');
	define('M4J_LANG_TITLE_FORM','Nadpis formuláru');
	define('M4J_LANG_INTROTEXT','Introtext');
	define('M4J_LANG_MAINTEXT','Hlavný text');
	define('M4J_LANG_EMAIL_HIDDEN_TEXT','Email Introtext. (Zobrazí sa len pri emailoch emailoch.)');
	define('M4J_LANG_TEMPLATE','Template');
	define('M4J_LANG_LINK_TO_MENU','Link na menu');
	define('M4J_LANG_LINK_CAT_TO_MENU','Prelinkuj kategóriu na menu');
	define('M4J_LANG_LINK_TO_CAT','Linkuj kategóriu: ');
	define('M4J_LANG_LINK_TO_FORM','Linkuj formulár: ');
	define('M4J_LANG_LINK_TO_NO_CAT','Linkuj zobrazenie všetkých formulárov bez kategórie');
	define('M4J_LANG_LINK_TO_ALL_CAT','Link na zobrazenie \'Všetkých kategórií\'');
	define('M4J_LANG_CHOOSE_MENU','Vyber menu na prelinkovanie: ');
	define('M4J_LANG_MENU','Menu: ');
	define('M4J_LANG_NO_LINK_NAME','Prosím poskytnite linkové meno.');
	define('M4J_LANG_PUBLISHED','Zverejnené:');
	define('M4J_LANG_PARENT_LINK','Rodičovsky link');
	define('M4J_LANG_LINK_NAME','meno linku');
	define('M4J_LANG_ACCESS_LEVEL','Úroveň prístupu:');
	define('M4J_LANG_EDIT_MAIN_DATA','Uprav základné údaje');
	define('M4J_LANG_LEGEND','Legenda');
	define('M4J_LANG_LINK','Link');
	define('M4J_LANG_IS_VISIBLE',' je publikované');
	define('M4J_LANG_IS_HIDDEN',' nieje publikované');
	define('M4J_LANG_FORM','Formulár');
	define('M4J_LANG_ITEM','Položka');
	define('M4J_LANG_IS_REQUIRED','Vyžadované');
	define('M4J_LANG_IS_NOT_REQUIRED','Nieje vyžaodvané');
	define('M4J_LANG_ASSIGN_ORDER','Objednávka');
	define('M4J_LANG_ASSIGN_ORDER_HINT','* Objednávka nieje možná pre \'Všetky formuláre\' !<br/>');
	define('M4J_LANG_EDIT_FORM','Uprav formuláre');
	define('M4J_LANG_CAPTCHA','Captcha');
	define('M4J_LANG_HOVER_ACTIVE_ON','Publikované! Click=Odpublikuj');
	define('M4J_LANG_HOVER_ACTIVE_OFF','Odpublikované! Click=Publikuj');
	define('M4J_LANG_HOVER_REQUIRED_ON','Vyžadované! Click= Nevyžadované');
	define('M4J_LANG_HOVER_REQUIRED_OFF','Nevzžadované! Click= Vyžadované');
	define('M4J_LANG_DESCRIPTION','Popis');
	define('M4J_LANG_AREA','Oblasť');
	define('M4J_LANG_ADJUSTMENT','Konfigurácia');
	define('M4J_LANG_VALUE','Hodnota');
	define('M4J_LANG_MAIN_CONFIG','Hlavná konfigurácia:');
	define('M4J_LANG_MAIN_CONFIG_DESC','Tu môžeš upraviť všetky hlavné nastavenia. Ak chceš resetnúť všetky hlavné nmastavenia, včítane CSS, klikni na reset.');
	define('M4J_LANG_CSS_CONFIG','CSS Nastavenia:');
	define('M4J_LANG_CSS_CONFIG_DESC','Tieto nastavenia sú vyžadované pre vizuálnu prezentaáciu an frontende. Ak nieste skúseny v práci s css, nemente túto hodnotu !');
	define('M4J_LANG_RESET','Reset');
			
	define('M4J_LANG_EMAIL_ROOT', 'Hlavná emailová adresa: ' );
	define('M4J_LANG_MAX_OPTIONS', 'Maximum odpovedí <br/> Špecifikuj odpoveď: ' );
	define('M4J_LANG_PREVIEW_WIDTH', 'Náhladová šírka: ' );
	define('M4J_LANG_PREVIEW_HEIGHT', 'Náhladová výška: ' );
	define('M4J_LANG_CAPTCHA_DURATION', 'Captcha trvanie (v minútach): ' );
	define('M4J_LANG_HELP_ICON', 'Pomocná ikona: ' );
	define('M4J_LANG_HTML_MAIL', 'HTML Email: ' );
	define('M4J_LANG_SHOW_LEGEND', 'Ukáž Legendu: ' );
	
	define('M4J_LANG_EMAIL_ROOT_DESC', 'THlavná email adresa, použitá, ak nebola vybraná kategória, alebo poskytnutý kontaktný email.' );
	define('M4J_LANG_MAX_OPTIONS_DESC', 'Maximálne limit odpovedí pre položku \'Vybranú voľbu\'. Musí byť číslo.' );	
	define('M4J_LANG_PREVIEW_WIDTH_DESC', 'Šírka templatového náhladu. Táto hodnota sa použije len ak nezadáte náhladovú šírku pri template.' );
	define('M4J_LANG_PREVIEW_HEIGHT_DESC', 'Výška templatového náhladu. ' );
	define('M4J_LANG_CAPTCHA_DURATION_DESC', 'Časová platnosť cpatcha ochrany. Po vypršaní, treba captacha ochranu zadať znova.' );
	define('M4J_LANG_HELP_ICON_DESC', 'Definuj farbu pomocnej ikony.' );
	define('M4J_LANG_HTML_MAIL_DESC', 'Ak chcete dostávať HTML emaily, vyberte Áno. ' );
	define('M4J_LANG_SHOW_LEGEND_DESC', 'Ak chcete zobrazi´t legendu, vyberte Áno.' );
	
	define('M4J_LANG_CLASS_HEADING', 'Hlavná titulka:' );
	define('M4J_LANG_CLASS_HEADER_TEXT', 'Header text' );
	define('M4J_LANG_CLASS_LIST_WRAP', 'Listing- Wrap ' );
	define('M4J_LANG_CLASS_LIST_HEADING', 'Listing- Titulka' );
	define('M4J_LANG_CLASS_LIST_INTRO', 'Listing- Introtext ' );
	define('M4J_LANG_CLASS_FORM_WRAP', 'Formulár- Wrap ' );
	define('M4J_LANG_CLASS_FORM_TABLE', 'Formulár- Tabluľka ' );
	define('M4J_LANG_CLASS_ERROR', 'Chybný text' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP', 'Zašli tlačidlový Wrap' );
	define('M4J_LANG_CLASS_SUBMIT', 'Submit Tlačidlo ' );
	define('M4J_LANG_CLASS_REQUIRED', 'Vyžadované * css ' );
	
	define('M4J_LANG_CLASS_HEADING_DESC', '<strong>DIV-Tag</strong> - Nadpis stránky ' );
	define('M4J_LANG_CLASS_HEADER_TEXT_DESC', '<strong>DIV-Tag</strong> - Obsah pre nadpise. ' );
	define('M4J_LANG_CLASS_LIST_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap zoznamových položiek.' );
	define('M4J_LANG_CLASS_LIST_HEADING_DESC', '<strong>DIV-Tag</strong> - Nadpis zoznamových položiek. ' );
	define('M4J_LANG_CLASS_LIST_INTRO_DESC', '<strong>DIV-Tag</strong> - Introtext zoznamových položiek. ' );
	define('M4J_LANG_CLASS_FORM_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap formulárovej oblasti. ' );
	define('M4J_LANG_CLASS_FORM_TABLE_DESC', '<strong>TABLE-Tag</strong> - Tabulka, vktorej sú zobrazené všetky prvky formuláru.' );
	define('M4J_LANG_CLASS_ERROR_DESC', '<strong>SPAN-Tag</strong> - CSS class chybových hlášok. ' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap submit tlačidla ' );
	define('M4J_LANG_CLASS_SUBMIT_DESC', '<strong>INPUT-Tag</strong> - CSS class submit tlačidla. ' );
	define('M4J_LANG_CLASS_REQUIRED_DESC', '<strong>SPAN-Tag</strong> - CSS class \' <b>*</b> \' vyžadovaných položiek.' );
	
	define('M4J_LANG_INFO_HELP','Info a Pomoc');
	
	// New to Version 1.1.5
	define('M4J_LANG_CHOOSE_CAPTCHA', 'Captcha Technika: ' ); 
	define('M4J_LANG_CSS','CSS');
	define('M4J_LANG_SIMPLE','Ordinárna');
	
	//New To Version 1.1.7
		define('M4J_LANG_CONFIG_RESET','Konfigurácia bola úspešne resetnutá.');
		define('M4J_LANG_CONFIG_SAVED','Konfigurácia bola úspešne uložená.');
		define('M4J_LANG_CAPTCHA_DESC', ' Ak sa vyskytnú problémi pri captcha ochrane, zvolte inú techniku.' );
		define('M4J_LANG_SPECIAL','Special');
	
	
	define('M4J_LANG_MAIL_ISO','Mail Charset');
	define('M4J_LANG_MAIL_ISO_DESC','utf-8 , iso-8859-1 (Western Europe), iso-8859-4 (Balto), iso-8859-5 (Cyrillic), iso-8859-6 (Arabic), iso-8859-7 (Greek), iso-8859-8 (Hebrew),iso-8859-9 (Turkish), iso-8859-10 (Nordic),iso-8859-11 (Thai)');		
	
	
	// New to Version 1.1.8
	$m4j_lang_elements[40]= 'Príloha';	
	define('M4J_LANG_ATTACHMENT','Súborová príloha');
	define('M4J_LANG_ATTACHMENT_DESC','Uživateľ môže s formulárom poslať i súbor.');
	define('M4J_LANG_TYPE_OF_ATTACHMENT','Napíšte parameter pre toto súborové pole:');
	define('M4J_LANG_ALLOWED_ENDINGS','Povolené prípony súborov.');
	define('M4J_LANG_MAXSIZE','Maximálna velkosť.');
	define('M4J_LANG_BYTE','Byte');
	define('M4J_LANG_KILOBYTE','Kilobyte');
	define('M4J_LANG_MEGABYTE','Megabyte');
	define('M4J_LANG_ELEMENT_ATTACHMENT_DESC','Napíśte všetky prípony súborov bez bodky a separované <b>úvodzovkami</b>. Ak necháte pole prázdne, akceptované sú všetky súbory s ľubovonou velkosťou. Pre zjednodušenie práce môžte vybrať jedno z rozšírení, ktoré bude automaticky pridané.');
	define('M4J_LANG_IMAGES','Obrázky');
	define('M4J_LANG_DOCS','Dokumenty');
	define('M4J_LANG_AUDIO','Audio');
	define('M4J_LANG_VIDEO','Video');										   
    define('M4J_LANG_DATA','Údaje');
	define('M4J_LANG_COMPRESSED','Kompresia');
	define('M4J_LANG_OTHERS','Iné');
	define('M4J_LANG_ALL','Všetko');
	
	// New to Version 1.1.9
 define('M4J_LANG_FROM_NAME','Od (meno)');
 define('M4J_LANG_FROM_EMAIL','Od (email)');
 define('M4J_LANG_FROM_NAME_DESC','Vložte meno formulára pre emaily, ktoré chcete archivovať.<br/>');
 define('M4J_LANG_FROM_EMAIL_DESC','Vložte emailovú adresu, pre emaily, ktoré chcete archivovať.<br/>');
 define('M4J_LANG_TEMPLATE_DELETE_CAUTION',' Varovanie! Všetky formuláre obsahujúce tento template, budú tiež zmazané!');

	
?>