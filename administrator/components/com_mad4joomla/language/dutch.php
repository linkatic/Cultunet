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
	$m4j_lang_elements[2]= 'Ja/Nee Selectie';
	$m4j_lang_elements[10]= 'Datum';
	$m4j_lang_elements[20]= 'Tekstveld';
	$m4j_lang_elements[21]= 'Tekstgebied';
	$m4j_lang_elements[30]= 'Menu(enkelvoudige keuze)';
	$m4j_lang_elements[31]= 'Selectie Menu(enkelvoudige keuze)';
	$m4j_lang_elements[32]= 'Radiobuttons(enkelvoudige keuze)';
	$m4j_lang_elements[33]= 'Checkbox Groep(meervoudige keuze)';
	$m4j_lang_elements[34]= 'Lijst(meervoudige keuze)';
	
	
	define('M4J_LANG_FORMS','Formulieren');
	define('M4J_LANG_TEMPLATES','Templates');
	define('M4J_LANG_CATEGORY','Categorie');
	define('M4J_LANG_CONFIG','Configuratie');
	define('M4J_LANG_HELP','Info & Help');
	define('M4J_LANG_CANCEL','Annuleren');
	define('M4J_LANG_PROCEED','Doorgaan');
	define('M4J_LANG_SAVE','Opslaan');
	define('M4J_LANG_NEW_FORM','Nieuw Formulier');
	define('M4J_LANG_NEW_TEMPLATE','Nieuwe Template');
	define('M4J_LANG_ADD','Toevoegen');
	define('M4J_LANG_EDIT_NAME','Naam en beschrijving van deze template bewerken');
	define('M4J_LANG_NEW_TEMPLATE_LONG','Nieuwe Template');
	define('M4J_LANG_TEMPLATE_NAME','Naam van deze Template');
	define('M4J_LANG_TEMPLATE_NAME_EDIT','Naam van deze template bewerken');
	define('M4J_LANG_TEMPLATE_DESCRIPTION','Korte beschrijving (Voor intern gebruik. Mag leeg gelaten worden.)');
	define('M4J_LANG_TEMPLATE_DESCRIPTION_EDIT','Korte beschrijving bewerken');
	define('M4J_LANG_DELETE','Verwijderen');
	define('M4J_LANG_DELETE_CONFIRM','Bent u zeker dat u dit item wilt verwijderen?');
	define('M4J_LANG_NEW_CATEGORY','Nieuwe categorie');
	define('M4J_LANG_NAME','Naam');
	define('M4J_LANG_SHORTDESCRIPTION','Korte beschrijving');
	define('M4J_LANG_ID','ID');
	define('M4J_LANG_ITEMS','Items');
	define('M4J_LANG_EDIT','Bewerken');
	define('M4J_LANG_EDIT_TEMPLATE_ITEMS','Items -> Bewerken');
	define('M4J_LANG_TEMPLATE_NAME_REQUIRED','Gelieve een naam voor deze template in te voeren!');
	define('M4J_LANG_AT_LEAST_ONE','Dit item kan niet verwijderd worden<br/>Hier moet minstens &eacute;&eacute;n element aanwezig zijn.');	
	define('M4J_LANG_NEW_CATEGORY','Nieuwe Categorie');
	
		define('M4J_LANG_EDIT_ELEMENT','Element van template bewerken: ');
	define('M4J_LANG_CATEGORY_NAME_ERROR','Gelieve een categorienaam in te voeren');
	define('M4J_LANG_NONE_LEGAL_EMAIL','Gelieve een geldig e-mailadres in te voeren of het veld leeg te laten.<br/>');
	define('M4J_LANG_EMAIL','E-mail');
	define('M4J_LANG_POSITION','Rangschikking');
	define('M4J_LANG_ACTIVE','Actief');
	define('M4J_LANG_UP','Omhoog');
	define('M4J_LANG_DOWN','Omlaag');
	define('M4J_LANG_EDIT_CATEGORY','Categorie bewerken');
	define('M4J_LANG_TEMPLATE_ELEMENTS','Elementen van template: ');
	define('M4J_LANG_NEW_ELEMENT_LONG','Voeg nieuw element in template in: ');	
	define('M4J_LANG_ELEMENT_NO_QUESTION_ERROR','Gelieve een vraag in te voeren.');
	define('M4J_LANG_REQUIRED','Vereist');
	define('M4J_LANG_QUESTION','Vraag');
	define('M4J_LANG_TYPE','Type');
	define('M4J_LANG_YES','Ja');		
	define('M4J_LANG_NO','Nee');	
	define('M4J_LANG_ALL_FORMS','Alle Formulieren');
	define('M4J_LANG_NO_CATEGORYS','Zonder Categorie');
	define('M4J_LANG_FORMS_OF_CATEGORY','Formulieren uit de categorie: ');
	define('M4J_LANG_PREVIEW','Voorbeeld');
	define('M4J_LANG_DO_COPY','Kopi&euml;ren');		
	define('M4J_LANG_COPY','Kopi&euml;ren');
	define('M4J_LANG_VERTICAL','Vertikaal');
	define('M4J_LANG_HORIZONTAL','Horizontaal');
	define('M4J_LANG_EXAMPLE','Voorbeeld');
	define('M4J_LANG_CHECKBOX','Knop');	
	define('M4J_LANG_DATE','Datum');
	define('M4J_LANG_TEXTFIELD','Tekstveld');
	define('M4J_LANG_OPTIONS','Gespecifieerde Opties');
	define('M4J_LANG_CHECKBOX_DESC','Een eenvoudige Ja/Nee vraag.');
	define('M4J_LANG_DATE_DESC','Gebruiker moet een datum invullen.');
	define('M4J_LANG_TEXTFIELD_DESC','Gebruiker moet een woord invullen.');
	define('M4J_LANG_OPTIONS_DESC','Gebruiker selecteert een of meer antwoorden uit de gespecifi&euml;erde items. ');
	define('M4J_LANG_CLOSE_PREVIEW','Voorbeeld sluiten');
	define('M4J_LANG_Q_WIDTH','Breedte van de vraag-kolom. (links)');
	define('M4J_LANG_A_WIDTH','Breedte van de antwoord-kolom (rechts)');
	define('M4J_LANG_OVERVIEW','Overzicht');
	define('M4J_LANG_UPDATE_PROCEED','& Doorgaan');
	define('M4J_LANG_NEW_ITEM','Nieuw Item');
	define('M4J_LANG_EDIT_ITEM','Item Bewerken');
	define('M4J_LANG_CATEGORY_NAME','Categorie Naam');
	define('M4J_LANG_EMAIL_ADRESS','E-mailadres');
	define('M4J_LANG_ADD_NEW_ITEM','Nieuw Item Toevoegen:');
	define('M4J_LANG_YOUR_QUESTION','Uw Vraag');
	define('M4J_LANG_REQUIRED_LONG','Vereist?');
	define('M4J_LANG_HELP_TEXT','Help Tekst. Geef gebruikers een hint of uitleg bij uw vraag. (niet noodzakelijk)');
	define('M4J_LANG_TYPE_OF_CHECKBOX','Soort knop:');
	define('M4J_LANG_ITEM_CHECKBOX','Checkbox.');
	define('M4J_LANG_YES_NO_MENU','Ja/Nee Menu.');
	define('M4J_LANG_YES_ON','Ja/Aan.');
	define('M4J_LANG_NO_OFF','Nee/Uit.');
	define('M4J_LANG_INIT_VALUE','Initi&euml;le Waarde:');
	define('M4J_LANG_TYPE_OF_TEXTFIELD','Soort Tekstveld:');
	define('M4J_LANG_ITEM_TEXTFIELD','TekstVeld');
	define('M4J_LANG_ITEM_TEXTAREA','Tekstgebied');
	define('M4J_LANG_MAXCHARS_LONG','Maximum Karakters:');
	define('M4J_LANG_OPTICAL_ALIGNMENT','Uitlijning:');
	define('M4J_LANG_ITEM_WIDTH_LONG','<b>Breedte in Pixels</b> <br/>(Voeg \'%\' toe voor percentage. Leeg = Automatisch passend)<br/><br/>');
	define('M4J_LANG_ROWS_TEXTAREA','<b>Aantal zichtbare regels:</b><br/> (Enkel voor Tekstgebied)<br/><br/>');
	define('M4J_LANG_DROP_DOWN','<b>Menu</b>');
	define('M4J_LANG_RADIOBUTTONS','<b>Radiobuttons</b>');
	define('M4J_LANG_LIST_SINGLE','<b>Lijst</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(enkelvoudige keuze)');
	define('M4J_LANG_CHECKBOX_GROUP','<b>Checkbox Groep</b>');
	define('M4J_LANG_LIST_MULTIPLE','<b>Lijst</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(meervoudige keuze, met \'CTRL\' & linker muisknop)');
	define('M4J_LANG_SINGLE_CHOICE_LONG','Enkelvoudige Keuze (Slechts &eacute;&eacute;n item kan geselecteerd worden):');
	define('M4J_LANG_MULTIPLE_CHOICE_LONG','Meervoudige Keuze (Meerdere items kunnen geselecteerd worden):');
	define('M4J_LANG_TYPE_OF_OPTIONS','Type van Selectie:');
	define('M4J_LANG_ROWS_LIST','<b>Aantal zichtbare regels:</b><br/> (Enkel voor Lijsten)<br/><br/>');
	define('M4J_LANG_ALIGNMENT_GROUPS','<b>Uitlijning: </b> <br/>(Enkel voor Radiobuttons en Checkbox Groepen)<br/><br/>');
	define('M4J_LANG_OPTIONS_VALUES_INTRO','<b>Specifi&euml;er de antwoorden.<br/>Lege velden worden genegeerd.</b>');
	define('M4J_LANG_CATEGORY_INTRO_LONG','Introtekst. Zal enkel getoond worden op categorie-sites.');
	define('M4J_LANG_TITLE','Titel');
	define('M4J_LANG_ERROR_NO_TITLE','Gelieve een titel in te voeren.');
	define('M4J_LANG_USE_HELP','Help Tekst voor Frontend Hulp');
	define('M4J_LANG_TITLE_FORM','Formulier Titel');
	define('M4J_LANG_INTROTEXT','Introtekst');
	define('M4J_LANG_MAINTEXT','Hoofdtekst');
	define('M4J_LANG_EMAIL_HIDDEN_TEXT','E-mail Introtekst. (Zal enkel getoond worden in e-mails.)');
	define('M4J_LANG_TEMPLATE','Template');
	define('M4J_LANG_LINK_TO_MENU','Link aan Menu');
	define('M4J_LANG_LINK_CAT_TO_MENU','Link huidige categorie aan een menu');
	define('M4J_LANG_LINK_TO_CAT','Link Categorie: ');
	define('M4J_LANG_LINK_TO_FORM','Link Formulier: ');
	define('M4J_LANG_LINK_TO_NO_CAT','Link om alle formulieren zonder een categorie te tonen');
	define('M4J_LANG_LINK_TO_ALL_CAT','Link om \'Alle Categori&euml;en\' te tonen');
	define('M4J_LANG_CHOOSE_MENU','Kies een menu om naar te linken: ');
	define('M4J_LANG_MENU','Menu: ');
	define('M4J_LANG_NO_LINK_NAME','Gelieve een linknaam in te voeren.');
	define('M4J_LANG_PUBLISHED','Gepubliceerd:');
	define('M4J_LANG_PARENT_LINK','Parent Link');
	define('M4J_LANG_LINK_NAME','Linknaam');
	define('M4J_LANG_ACCESS_LEVEL','Toegangsniveau:');
	define('M4J_LANG_EDIT_MAIN_DATA','Basisdata bewerken');
	define('M4J_LANG_LEGEND','Legende');
	define('M4J_LANG_LINK','Link');
	define('M4J_LANG_IS_VISIBLE',' is gepubliceerd');
	define('M4J_LANG_IS_HIDDEN',' is niet gepubliceerd');
	define('M4J_LANG_FORM','Formulier');
	define('M4J_LANG_ITEM','Item');
	define('M4J_LANG_IS_REQUIRED','Vereist');
	define('M4J_LANG_IS_NOT_REQUIRED','Niet Vereist');
	define('M4J_LANG_ASSIGN_ORDER','Rangschikking');
	define('M4J_LANG_ASSIGN_ORDER_HINT','* Rankschikking is niet mogelijk voor \'Alle Formulieren\' !<br/>');
	define('M4J_LANG_EDIT_FORM','Formulieren bewerken');
	define('M4J_LANG_CAPTCHA','Captcha');
	define('M4J_LANG_HOVER_ACTIVE_ON','Gepubliceerd! Klik=Niet gepubliceerd');
	define('M4J_LANG_HOVER_ACTIVE_OFF','Niet Gepubliceerd! Klik=Gepubliceerd');
	define('M4J_LANG_HOVER_REQUIRED_ON','Vereist! Klik= Niet Vereist');
	define('M4J_LANG_HOVER_REQUIRED_OFF','Niet Vereist! Klik= Vereist');
	define('M4J_LANG_DESCRIPTION','Beschrijving');
	define('M4J_LANG_AREA','Gebied');
	define('M4J_LANG_ADJUSTMENT','Configuratie');
	define('M4J_LANG_VALUE','Waarde');
	define('M4J_LANG_MAIN_CONFIG','Hoofdconfiguratie:');
	define('M4J_LANG_MAIN_CONFIG_DESC','U kunt alle hoofdparameters hier wijzigen. Als u alle hoofdparameters (incl. CSS)wenst te resetten naar hun default-waarden, klik reset.');
	define('M4J_LANG_CSS_CONFIG','CSS Parameters:');
	define('M4J_LANG_CSS_CONFIG_DESC','Deze parameters zijn vereist voor visuele presentatie van de Fontend. Indien niet ervaren bent met het gebruik van (eigen) externe CSS, laat u deze waarden best ongewijzigd!');
	define('M4J_LANG_RESET','Reset');
			
	define('M4J_LANG_EMAIL_ROOT', 'Hoofd E-mailadres: ' );
	define('M4J_LANG_MAX_OPTIONS', 'Maximum aantal antwoorden <br/> Gespecifi&euml;erde keuze: ' );
	define('M4J_LANG_PREVIEW_WIDTH', 'Voorbeeld Breedte: ' );
	define('M4J_LANG_PREVIEW_HEIGHT', 'Voorbeeld Hoogte: ' );
	define('M4J_LANG_CAPTCHA_DURATION', 'Duur Captcha (in min): ' );
	define('M4J_LANG_HELP_ICON', 'Help Icoon: ' );
	define('M4J_LANG_HTML_MAIL', 'HTML E-mail: ' );
	define('M4J_LANG_SHOW_LEGEND', 'Toon Legende: ' );
	
	define('M4J_LANG_EMAIL_ROOT_DESC', 'Het hoofd e-mailadres wordt gebruikt indien de categorie noch het formulier een e-mailadres gespecifi&euml;erd krijgen.' );
	define('M4J_LANG_MAX_OPTIONS_DESC', 'Deze waarde beperkt het maximum aantal antwoorden voor een lijst \'Gespecifieerde Opties\'. Deze waarde moet numeriek zijn.' );	
	define('M4J_LANG_PREVIEW_WIDTH_DESC', 'Breedte van het template-voorbeeld. Deze waarde wordt enkel gebruikt indien u geen voorbeeldbreedte specifi&euml;ert in de basisdata van een template.' );
	define('M4J_LANG_PREVIEW_HEIGHT_DESC', 'Hoogte van het template-voorbeeld. ' );
	define('M4J_LANG_CAPTCHA_DURATION_DESC', 'Hoort bij de Frontend. Deze waarde bepaalt de maximumduur van een Captcha\'s geldigheid. Indien deze duur verstrijkt, dient een nieuwe Captcha-code ingevoerd te worden, zelfs indien de voorgaande correct was.' );
	define('M4J_LANG_HELP_ICON_DESC', 'Definieer de kleur van het help-icoon.' );
	define('M4J_LANG_HTML_MAIL_DESC', 'Kies Ja als u HTML e-mails wenst te ontvangen. ' );
	define('M4J_LANG_SHOW_LEGEND_DESC', 'Kies Ja als u een legende wenst te tonen in de Backend.' );
	
	define('M4J_LANG_CLASS_HEADING', 'Hoofd Titel:' );
	define('M4J_LANG_CLASS_HEADER_TEXT', 'Tekst Hoofding:' );
	define('M4J_LANG_CLASS_LIST_WRAP', 'Listing- Wrap:' );
	define('M4J_LANG_CLASS_LIST_HEADING', 'Listing- Titel:' );
	define('M4J_LANG_CLASS_LIST_INTRO', 'Listing- Introtekst:' );
	define('M4J_LANG_CLASS_FORM_WRAP', 'Form- Wrap:' );
	define('M4J_LANG_CLASS_FORM_TABLE', 'Form- Tabel:' );
	define('M4J_LANG_CLASS_ERROR', 'Error Tekst:' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP', 'Verzendknop Wrap:' );
	define('M4J_LANG_CLASS_SUBMIT', 'Verzendknop:' );
	define('M4J_LANG_CLASS_REQUIRED', 'Vereist * css:' );
	
	define('M4J_LANG_CLASS_HEADING_DESC', '<strong>DIV-Tag</strong> - Titel van een site ' );
	define('M4J_LANG_CLASS_HEADER_TEXT_DESC', '<strong>DIV-Tag</strong> - Inhoud na de titel. ' );
	define('M4J_LANG_CLASS_LIST_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap van een listing item.' );
	define('M4J_LANG_CLASS_LIST_HEADING_DESC', '<strong>DIV-Tag</strong> - Titel van een listing item. ' );
	define('M4J_LANG_CLASS_LIST_INTRO_DESC', '<strong>DIV-Tag</strong> - Introtekst van een listing item. ' );
	define('M4J_LANG_CLASS_FORM_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap van een form area. ' );
	define('M4J_LANG_CLASS_FORM_TABLE_DESC', '<strong>TABLE-Tag</strong> - Tabel waarin alle formulier-items worden getoond.' );
	define('M4J_LANG_CLASS_ERROR_DESC', '<strong>SPAN-Tag</strong> - CSS class van error-teksten. ' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP_DESC', '<strong>DIV-Tag</strong> - Wrap van de verzendknop.' );
	define('M4J_LANG_CLASS_SUBMIT_DESC', '<strong>INPUT-Tag</strong> - CSS class  van de verzendknop. ' );
	define('M4J_LANG_CLASS_REQUIRED_DESC', '<strong>SPAN-Tag</strong> - CSS class van het \' <b>*</b> \' karakter om de vereiste velden aan te geven.' );
	
	define('M4J_LANG_INFO_HELP','Info & Help');


		
	// New to Version 1.1.5
	define('M4J_LANG_CHOOSE_CAPTCHA', 'Captcha Techniek: ' ); 
	define('M4J_LANG_CSS','CSS');
	define('M4J_LANG_SIMPLE','Gewoon');
	
		//New To Version 1.1.7
	define('M4J_LANG_CONFIG_RESET','Configuratie is succesvol gereset.');
	define('M4J_LANG_CONFIG_SAVED','Configuratie is succesvol opgeslaan.');
	define('M4J_LANG_CAPTCHA_DESC', ' Er kunnen enkele problemen optreden bij het gebruik van de standaard-css-captcha in combinatie met sommige servers en templates. In dit geval hebt u het alternatief te kiezen voor een een gewone captcha. Als u nog steeds problemen met behulp van "bijzondere"' );
	define('M4J_LANG_SPECIAL','Bijzondere');
	
	define('M4J_LANG_MAIL_ISO','E-mail tekenset');
	define('M4J_LANG_MAIL_ISO_DESC','utf-8 , iso-8859-1 (Western Europe), iso-8859-4 (Balto), iso-8859-5 (Cyrillic), iso-8859-6 (Arabic), iso-8859-7 (Greek), iso-8859-8 (Hebrew),iso-8859-9 (Turkish), iso-8859-10 (Nordic),iso-8859-11 (Thai)');	
	
	
	// New to Version 1.1.8
	$m4j_lang_elements[40]= 'Attachment'; 
	define('M4J_LANG_ATTACHMENT','Bestand bijlage');
 	define('M4J_LANG_ATTACHMENT_DESC','Gebruiker kan een bestand verzenden via een formulier.');
 	define('M4J_LANG_TYPE_OF_ATTACHMENT','Vul de parameters in voor dit bestands-upload-veld:');
 	define('M4J_LANG_ALLOWED_ENDINGS','Toegelaten bestandsextensies.');
 	define('M4J_LANG_MAXSIZE','Maximum bestandsgrootte.');
 	define('M4J_LANG_BYTE','Byte');
 	define('M4J_LANG_KILOBYTE','Kilobyte');
 	define('M4J_LANG_MEGABYTE','Megabyte');
 	define('M4J_LANG_ELEMENT_ATTACHMENT_DESC','Gelieve alle bestandsformaten in te voeren, zonder punt ervoor en van elkaar gescheiden door <b>kommas</b>. Als u velden leeg laat, zullen alle bestandsextensies aanvaard worden en/of alle groottes toegestaan zijn. Voor de gemakkelijkheid, kunt u kiezen uit onderstaande extensies die automatisch toegevoegd zullen worden.');
 	define('M4J_LANG_IMAGES','Afbeeldingen');
 	define('M4J_LANG_DOCS','Documenten');
 	define('M4J_LANG_AUDIO','Audio');
 	define('M4J_LANG_VIDEO','Video');            
 	define('M4J_LANG_DATA','Data');
 	define('M4J_LANG_COMPRESSED','Compressie');
 	define('M4J_LANG_OTHERS','Andere');
 	define('M4J_LANG_ALL','Alle');
	
	// New to Version 1.1.9
	define('M4J_LANG_FROM_NAME','From name');
	define('M4J_LANG_FROM_EMAIL','From email');
	define('M4J_LANG_FROM_NAME_DESC','Insert a from name for the emails you will achieve<br/>');
	define('M4J_LANG_FROM_EMAIL_DESC','Insert a from email addresss for the emails you will achieve.<br/>');
	define('M4J_LANG_TEMPLATE_DELETE_CAUTION',' Caution! All forms that contain this template will also be deleted!');
		
?>