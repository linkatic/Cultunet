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

	/**  ENGLISH VERSION. NEEDS TO BE TRANSLATED */


	defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
	
	$m4j_lang_elements[1]= 'Checkbox';
	$m4j_lang_elements[2]= 'Selezione Si/No';
	$m4j_lang_elements[10]= 'Data';
	$m4j_lang_elements[20]= 'Campo testo';
	$m4j_lang_elements[21]= 'Textarea';
	$m4j_lang_elements[30]= 'Menu(a scelta singola)';
	$m4j_lang_elements[31]= 'Menu selezione(a scelta singola)';
	$m4j_lang_elements[32]= 'Radiobutton(a scelta singola)';
	$m4j_lang_elements[33]= 'Checkbox(a scelta multipla)';
	$m4j_lang_elements[34]= 'Lista(a scelta multipla)';
	
	
	define('M4J_LANG_FORMS','Formulari');
	define('M4J_LANG_TEMPLATES','Modelli');
	define('M4J_LANG_CATEGORY','Categoria');
	define('M4J_LANG_CONFIG','Configurazione');
	define('M4J_LANG_HELP','Info & Aiuto');
	define('M4J_LANG_CANCEL','Cancella');
	define('M4J_LANG_PROCEED','Procedi');
	define('M4J_LANG_SAVE','Salva');
	define('M4J_LANG_NEW_FORM','Nuovo Formulario');
	define('M4J_LANG_NEW_TEMPLATE','Nuovo modello');
	define('M4J_LANG_ADD','Aggiungi');
	define('M4J_LANG_EDIT_NAME','Modifica nome e descrizione modello');
	define('M4J_LANG_NEW_TEMPLATE_LONG','Nuovo modello');
	define('M4J_LANG_TEMPLATE_NAME','Nome di questo modello');
	define('M4J_LANG_TEMPLATE_NAME_EDIT','Modifica nome di questo modello');
	define('M4J_LANG_TEMPLATE_DESCRIPTION','Descrizione breve (per uso interno: non indispensabile)');
	define('M4J_LANG_TEMPLATE_DESCRIPTION_EDIT','Modifica descrizione breve');
	define('M4J_LANG_DELETE','Cancella');
	define('M4J_LANG_DELETE_CONFIRM','Vuoi veramente cancellare questa voce?');
	define('M4J_LANG_NEW_CATEGORY','Nuova Categoria');
	define('M4J_LANG_NAME','Nome');
	define('M4J_LANG_SHORTDESCRIPTION','Descrizione breve');
	define('M4J_LANG_ID','ID');
	define('M4J_LANG_ITEMS','Voci');
	define('M4J_LANG_EDIT','Modifica');
	define('M4J_LANG_EDIT_TEMPLATE_ITEMS','Voci -> Modifica');
	define('M4J_LANG_TEMPLATE_NAME_REQUIRED','Prego inserire un nome per questo modello!');
	define('M4J_LANG_AT_LEAST_ONE','Questa voce non pu&ograve; essere cancellata!<br/>Ci deve essere almeno un elemento qui.');	

	
	define('M4J_LANG_EDIT_ELEMENT','Modifica elemento del modello: ');
	define('M4J_LANG_CATEGORY_NAME_ERROR','Prego inserire nome categoria');
	define('M4J_LANG_NONE_LEGAL_EMAIL','Prego inserire un valido indirizzo email oppure lasciar vuoto.<br/>');
	define('M4J_LANG_EMAIL','Email');
	define('M4J_LANG_POSITION','Riordina');
	define('M4J_LANG_ACTIVE','Attiva');
	define('M4J_LANG_UP','Su');
	define('M4J_LANG_DOWN','Gi&ugrave;');
	define('M4J_LANG_EDIT_CATEGORY','Modifica Categoria');
	define('M4J_LANG_TEMPLATE_ELEMENTS','Elementi del Modello: ');
	define('M4J_LANG_NEW_ELEMENT_LONG','Inserisci nuovo elemento al modello: ');	
	define('M4J_LANG_ELEMENT_NO_QUESTION_ERROR','Prego inserire una domanda.');
	define('M4J_LANG_REQUIRED','Richiesto');
	define('M4J_LANG_QUESTION','Domanda');
	define('M4J_LANG_TYPE','Tipo');
	define('M4J_LANG_YES','Si');		
	define('M4J_LANG_NO','No');	
	define('M4J_LANG_ALL_FORMS','Tutti i formulari');
	define('M4J_LANG_NO_CATEGORYS','Senza Categoria');
	define('M4J_LANG_FORMS_OF_CATEGORY','Formulario della Categoria: ');
	define('M4J_LANG_PREVIEW','Anteprima');
	define('M4J_LANG_DO_COPY','Copia');		
	define('M4J_LANG_COPY','Copia');
	define('M4J_LANG_VERTICAL','Verticale');
	define('M4J_LANG_HORIZONTAL','Orizzontale');
	define('M4J_LANG_EXAMPLE','Esempio');
	define('M4J_LANG_CHECKBOX','Spunta');	
	define('M4J_LANG_DATE','Data');
	define('M4J_LANG_TEXTFIELD','Campo testo');
	define('M4J_LANG_OPTIONS','Scelta');
	define('M4J_LANG_CHECKBOX_DESC','Semplice domanda con risposta Si/No.');
	define('M4J_LANG_DATE_DESC','L\'utente deve inserire una data.');
	define('M4J_LANG_TEXTFIELD_DESC','L\'utente deve inserire del testo.');
	define('M4J_LANG_OPTIONS_DESC','L\'utente seleziona una o pi&ugrave; risposte dalle voci specificate. ');
	define('M4J_LANG_CLOSE_PREVIEW','Chiudi anteprima');
	define('M4J_LANG_Q_WIDTH','Larghezza della colonna contenente le domande (parte sinistra)');
	define('M4J_LANG_A_WIDTH','Larghezza della colonna contenente le risposte (parte destra)');
	define('M4J_LANG_OVERVIEW','Visualizza');
	define('M4J_LANG_UPDATE_PROCEED','& Procedi');
	define('M4J_LANG_NEW_ITEM','Nuova voce');
	define('M4J_LANG_EDIT_ITEM','Edita voce');
	define('M4J_LANG_CATEGORY_NAME','Nome Categoria');
	define('M4J_LANG_EMAIL_ADRESS','Indirizzo Email');
	define('M4J_LANG_ADD_NEW_ITEM','Aggiungi nuova voce:');
	define('M4J_LANG_YOUR_QUESTION','La tua domanda');
	define('M4J_LANG_REQUIRED_LONG','Richiesta?');
	define('M4J_LANG_HELP_TEXT','Testo di aiuto. Fornisci un aiuto riguardante la domanda (non indispensabile)');
	define('M4J_LANG_TYPE_OF_CHECKBOX','Tipo di spunta:');
	define('M4J_LANG_ITEM_CHECKBOX','Checkbox.');
	define('M4J_LANG_YES_NO_MENU','Menu Si/No.');
	define('M4J_LANG_YES_ON','Si/On.');
	define('M4J_LANG_NO_OFF','No/Off.');
	define('M4J_LANG_INIT_VALUE','Valore iniziale:');
	define('M4J_LANG_TYPE_OF_TEXTFIELD','Tipo di campo testo:');
	define('M4J_LANG_ITEM_TEXTFIELD','Textfield (a riga singola)');
	define('M4J_LANG_ITEM_TEXTAREA','Textarea (a pi&ugrave; righe)');
	define('M4J_LANG_MAXCHARS_LONG','Massimo numero caratteri:');
	define('M4J_LANG_OPTICAL_ALIGNMENT','Allineamento testo:');
	define('M4J_LANG_ITEM_WIDTH_LONG','<b>Larghezza in Pixel</b> <br/>(Aggiungi \'%\' per impostare larghezza in percenutale. Vuoto =&gt; Larghezza automatica)<br/><br/>');
	define('M4J_LANG_ROWS_TEXTAREA','<b>Numero righe visibili:</b><br/> (Solo per Textarea)<br/><br/>');
	define('M4J_LANG_DROP_DOWN','<b>Menu</b>');
	define('M4J_LANG_RADIOBUTTONS','<b>Radiobuttons</b>');
	define('M4J_LANG_LIST_SINGLE','<b>Lista</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(scelta singola)');
	define('M4J_LANG_CHECKBOX_GROUP','<b>Checkbox Group</b>');
	define('M4J_LANG_LIST_MULTIPLE','<b>Lista</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(scelta multipla, premendo \'CTRL\' e sinistro mouse');
	define('M4J_LANG_SINGLE_CHOICE_LONG','Scelta singola (Solo una voce pu&ograve; essere selezionata):');
	define('M4J_LANG_MULTIPLE_CHOICE_LONG','Scelta multipla (Possono essere selezionate pi&ugrave; voci):');
	define('M4J_LANG_TYPE_OF_OPTIONS','Tipo di selezione:');
	define('M4J_LANG_ROWS_LIST','<b>Numero righe visibili:</b><br/> (Solo per le Liste)<br/><br/>');
	define('M4J_LANG_ALIGNMENT_GROUPS','<b>Allineamento: </b> <br/>(Solo per Radiobuttons e Checkbox Groups)<br/><br/>');
	define('M4J_LANG_OPTIONS_VALUES_INTRO','<b>Specifica la risposta.<br/>Se vuota, viene ignorata.</b>');
	define('M4J_LANG_CATEGORY_INTRO_LONG','Testo introduttivo. Viene visualizzato solo sulle categorie.');
	define('M4J_LANG_TITLE','Titolo');
	define('M4J_LANG_ERROR_NO_TITLE','Prego specificare un titolo.');
	define('M4J_LANG_USE_HELP','Testo di aiuto per il suggerimento (baloontip)');
	define('M4J_LANG_TITLE_FORM','Titolo formulario');
	define('M4J_LANG_INTROTEXT','Testo introduttivo');
	define('M4J_LANG_MAINTEXT','Testo principale');
	define('M4J_LANG_EMAIL_HIDDEN_TEXT','Testo introduttivo riportato nell\'email che sar&agrave; generata');
	define('M4J_LANG_TEMPLATE','Modello');
	define('M4J_LANG_LINK_TO_MENU','Inserisci Link al Menu');
	define('M4J_LANG_LINK_CAT_TO_MENU','Inserisci Link al Menu per la Categoria corrente');
	define('M4J_LANG_LINK_TO_CAT','Link Categoria: ');
	define('M4J_LANG_LINK_TO_FORM','Link Formulario: ');
	define('M4J_LANG_LINK_TO_NO_CAT','Link per visualizzare tutti i Formulari senza categoria ');
	define('M4J_LANG_LINK_TO_ALL_CAT','Link per visualizzare \'Tutte le categorie\'');
	define('M4J_LANG_CHOOSE_MENU','Scegli un menu da linkare: ');
	define('M4J_LANG_MENU','Menu: ');
	define('M4J_LANG_NO_LINK_NAME','prego inserire il nome del link.');
	define('M4J_LANG_PUBLISHED','Pubblicato:');
	define('M4J_LANG_PARENT_LINK','Link precedente');
	define('M4J_LANG_LINK_NAME','Nome Link');
	define('M4J_LANG_ACCESS_LEVEL','Access Level:');
	define('M4J_LANG_EDIT_MAIN_DATA','Modifica valori principali');
	define('M4J_LANG_LEGEND','Legenda');
	define('M4J_LANG_LINK','Link');
	define('M4J_LANG_IS_VISIBLE',' &egrave; pubblicato');
	define('M4J_LANG_IS_HIDDEN',' non &egrave; pubblicato');
	define('M4J_LANG_FORM','Formulario');
	define('M4J_LANG_ITEM','Item');
	define('M4J_LANG_IS_REQUIRED','Richiesto');
	define('M4J_LANG_IS_NOT_REQUIRED','Non richiesto');
	define('M4J_LANG_ASSIGN_ORDER','Riordina');
	define('M4J_LANG_ASSIGN_ORDER_HINT','* Riordino non possibile per \'Tutti i Formulari\' !<br/>');
	define('M4J_LANG_EDIT_FORM','Modifica Formulari');
	define('M4J_LANG_CAPTCHA','Captcha');
	define('M4J_LANG_HOVER_ACTIVE_ON','Pubblicato! Click=&gt;rimuovi pubblicazione');
	define('M4J_LANG_HOVER_ACTIVE_OFF','Non pubblicato! Click=&gt;pubblica');
	define('M4J_LANG_HOVER_REQUIRED_ON','Richiesto! Click=&gt;non richiesto');
	define('M4J_LANG_HOVER_REQUIRED_OFF','Non richiesto! Click=&gtk;richiesto');
	define('M4J_LANG_DESCRIPTION','Descrizione');
	define('M4J_LANG_AREA','Area');
	define('M4J_LANG_ADJUSTMENT','Configurazione');
	define('M4J_LANG_VALUE','Valore');
	define('M4J_LANG_MAIN_CONFIG','Configuration principale:');
	define('M4J_LANG_MAIN_CONFIG_DESC','Puoi configurare i valori principali qui. Se vuoi ripristinare i valori principali (incluso CSS) ai valori di default, premi Reset.');
	define('M4J_LANG_CSS_CONFIG','Impostazioni CSS:');
	define('M4J_LANG_CSS_CONFIG_DESC','Queste impostazioni sono richiste per la visualizzazione all\'utente. Se non hai esperienza con l\'inclusione di tuoi CSS esterni, non cambiare questi valori!');
	define('M4J_LANG_RESET','Reset');
			
	define('M4J_LANG_EMAIL_ROOT', 'Indirizzo Email principale: ' );
	define('M4J_LANG_MAX_OPTIONS', 'Massimo numero di risposte <br/> Scelte specificate: ' );
	define('M4J_LANG_PREVIEW_WIDTH', 'Larghezza anteprima: ' );
	define('M4J_LANG_PREVIEW_HEIGHT', 'Altezza anteprima: ' );
	define('M4J_LANG_CAPTCHA_DURATION', 'Durata del Captcha (in min): ' );
	define('M4J_LANG_HELP_ICON', 'Icona di aiuto: ' );
	define('M4J_LANG_HTML_MAIL', 'Email in HTML: ' );
	define('M4J_LANG_SHOW_LEGEND', 'Visualizza Legenda: ' );
	
	define('M4J_LANG_EMAIL_ROOT_DESC', 'L\'indirizzo email principale viene usato se al formulario non &egrave; stato specificato n&eacute; una categoria n&eacute un indirizzo email.' );
	define('M4J_LANG_MAX_OPTIONS_DESC', 'Questo valore numerico limita il numero massimo di risposte per una voce \'Scelta\'.' );	
	define('M4J_LANG_PREVIEW_WIDTH_DESC', 'Larghezza dell\'anteprima modello. Questo valore viene usato solo qualora non sia stato assegnato al template la larghezza dell\'anteprima.' );
	define('M4J_LANG_PREVIEW_HEIGHT_DESC', 'Altenzza dell\'anteprima modello. ' );
	define('M4J_LANG_CAPTCHA_DURATION_DESC', 'Trascorso il valore di minuti impostati, il Captcha sar&agrave; considerato invalido e dovr&agrave; quindi essere ricaricato (e quindi reinserito) per poter trasmettere il formulario.' );
	define('M4J_LANG_HELP_ICON_DESC', 'Definisce il colore dell\'icona di aiuto.' );
	define('M4J_LANG_HTML_MAIL_DESC', 'Se preferisci ricevere mail in HTML, scegli Si. ' );
	define('M4J_LANG_SHOW_LEGEND_DESC', 'Se vuoi visualizzare una legenda, scegli Si.' );
	
	define('M4J_LANG_CLASS_HEADING', 'Main Headline:' );
	define('M4J_LANG_CLASS_HEADER_TEXT', 'Header Text' );
	define('M4J_LANG_CLASS_LIST_WRAP', 'Listing- Wrap ' );
	define('M4J_LANG_CLASS_LIST_HEADING', 'Listing- Headline' );
	define('M4J_LANG_CLASS_LIST_INTRO', 'Listing- Introtext ' );
	define('M4J_LANG_CLASS_FORM_WRAP', 'Form- Wrap ' );
	define('M4J_LANG_CLASS_FORM_TABLE', 'Form- Table ' );
	define('M4J_LANG_CLASS_ERROR', 'Error Text' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP', 'Submit Button Wrap' );
	define('M4J_LANG_CLASS_SUBMIT', 'Submit Button ' );
	define('M4J_LANG_CLASS_REQUIRED', 'Required * css ' );
	
	define('M4J_LANG_CLASS_HEADING_DESC', '<strong>DIV-Tag</strong> - Headline of a site ' );
	define('M4J_LANG_CLASS_HEADER_TEXT_DESC', '<strong>DIV-Tag</strong> - Content after the headline. ' );
	define('M4J_LANG_CLASS_LIST_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap of a listing item.' );
	define('M4J_LANG_CLASS_LIST_HEADING_DESC', '<strong>DIV-Tag</strong> - Headline of a listing item. ' );
	define('M4J_LANG_CLASS_LIST_INTRO_DESC', '<strong>DIV-Tag</strong> - Introtext of a listing item. ' );
	define('M4J_LANG_CLASS_FORM_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap of a form area. ' );
	define('M4J_LANG_CLASS_FORM_TABLE_DESC', '<strong>TABLE-Tag</strong> - Table where all the form items are displayed.' );
	define('M4J_LANG_CLASS_ERROR_DESC', '<strong>SPAN-Tag</strong> - CSS class of error messages. ' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap of the submit button ' );
	define('M4J_LANG_CLASS_SUBMIT_DESC', '<strong>INPUT-Tag</strong> - CSS class of the submit button. ' );
	define('M4J_LANG_CLASS_REQUIRED_DESC', '<strong>SPAN-Tag</strong> - CSS class of the \' <b>*</b> \' char to declare required fields.' );
	
	define('M4J_LANG_INFO_HELP','Informazioni ed aiuto');
	
	// New to Version 1.1.5
	define('M4J_LANG_CHOOSE_CAPTCHA', 'Tecnica generazione Captcha: ' ); 
	define('M4J_LANG_CSS','CSS');
	define('M4J_LANG_SIMPLE','Semplice');
	
	//New To Version 1.1.7
		define('M4J_LANG_CONFIG_RESET','La configurazione &egrave; stata ripristinata correttamente.');
		define('M4J_LANG_CONFIG_SAVED','La configurazione &egrave; stata salvata correttamente.');
		define('M4J_LANG_CAPTCHA_DESC', ' Sono stati riscontrati alcuni problemi utilizzando la tecnica captcha CSS con alcuni server e modelli. In questo caso puoi scegliere fra tecnica captcha CSS e Semplice. Se il Captcha Semplice non risolve il problema, scegli \'Speciale\'' );
		define('M4J_LANG_SPECIAL','Speciale');
	
	
	define('M4J_LANG_MAIL_ISO','Codifica Email');
	define('M4J_LANG_MAIL_ISO_DESC','utf-8 , iso-8859-1 (Western Europe), iso-8859-4 (Balto), iso-8859-5 (Cyrillic), iso-8859-6 (Arabic), iso-8859-7 (Greek), iso-8859-8 (Hebrew),iso-8859-9 (Turkish), iso-8859-10 (Nordic),iso-8859-11 (Thai)');		
	
	
	// New to Version 1.1.8
	$m4j_lang_elements[40]= 'Allegato';	
	define('M4J_LANG_ATTACHMENT','File Allegato');
	define('M4J_LANG_ATTACHMENT_DESC','L\'utente pu&ograve; trasferire un file attraverso il formulario.');
	define('M4J_LANG_TYPE_OF_ATTACHMENT','Inserire parametri per questo campo trasferimento file:');
	define('M4J_LANG_ALLOWED_ENDINGS','Estensioni file accettate.');
	define('M4J_LANG_MAXSIZE','Massima dimensione file.');
	define('M4J_LANG_BYTE','Byte');
	define('M4J_LANG_KILOBYTE','Kilobyte');
	define('M4J_LANG_MEGABYTE','Megabyte');
	define('M4J_LANG_ELEMENT_ATTACHMENT_DESC','Prego inserire tutte le estensioni, senza punto, suddivise da virgole.Se lasci in bianco, tutte le estensioni saranno accettate. Per facilitare l\'inserimento, cliccare sulla parte a destra dove sono elencate le estensioni.');
	define('M4J_LANG_IMAGES','Immagini');
	define('M4J_LANG_DOCS','Documenti');
	define('M4J_LANG_AUDIO','Audio');
	define('M4J_LANG_VIDEO','Video');										   
    define('M4J_LANG_DATA','Dati');
	define('M4J_LANG_COMPRESSED','Archivi');
	define('M4J_LANG_OTHERS','Altri');
	define('M4J_LANG_ALL','Tutti');
	
	// New to Version 1.1.9
	define('M4J_LANG_FROM_NAME','Email Da: Nome');
	define('M4J_LANG_FROM_EMAIL','Email Da: indirizzo');
	define('M4J_LANG_FROM_NAME_DESC','Inserisci un nome utilizzato come mittente per le email generate<br/>');
	define('M4J_LANG_FROM_EMAIL_DESC','Inserisci un indirizzo Email utilizzato per le email generate.<br/>');
	define('M4J_LANG_TEMPLATE_DELETE_CAUTION',' Attenzione! Tutti i formulari con questo Modello saranno cancellate!');
	
	
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
