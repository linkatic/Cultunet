
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

	defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
	
	$m4j_lang_elements[1]= 'Kontrollk&auml;stchen';
	$m4j_lang_elements[2]= 'Ja/Nein Schalter';
	$m4j_lang_elements[10]= 'Datum';
	$m4j_lang_elements[20]= 'Textfeld';
	$m4j_lang_elements[21]= 'Textbereich';
	$m4j_lang_elements[30]= 'Men&uuml;(Einfach)';
	$m4j_lang_elements[31]= 'Optionsschaltergruppe(Einfach)';
	$m4j_lang_elements[32]= 'Liste(Einfach)';
	$m4j_lang_elements[33]= 'Kontrollschaltergruppe(Mehrfach)';
	$m4j_lang_elements[34]= 'Liste(Mehrfach)';
	
	define('M4J_LANG_FORMS','Formulare');
	define('M4J_LANG_TEMPLATES','Vorlagen');
	define('M4J_LANG_CATEGORY','Kategorie');
	define('M4J_LANG_CONFIG','Konfiguration');
	define('M4J_LANG_HELP','Info & Hilfe');
	define('M4J_LANG_CANCEL','Abbruch');
	define('M4J_LANG_PROCEED','Weiter');
	define('M4J_LANG_SAVE','Speichern');
	define('M4J_LANG_NEW_FORM','Neues Formular');
	define('M4J_LANG_NEW_TEMPLATE','Neue Vorlage');
	define('M4J_LANG_ADD','Hinzuf&uuml;gen');
	define('M4J_LANG_EDIT_NAME','Bearbeitung der Formularvorlage (Grunddaten)');
	define('M4J_LANG_NEW_TEMPLATE_LONG','Anlegen einer neuen Formularvorlage (Grunddaten)');
	define('M4J_LANG_TEMPLATE_NAME','Name der Formularvorlage');
	define('M4J_LANG_TEMPLATE_NAME_EDIT','Name der Formularvorlage bearbeiten');
	define('M4J_LANG_TEMPLATE_DESCRIPTION','Kurzbeschreibung (f&uuml;r den Internen gebrauch. Kann leer gelassen werden)');
	define('M4J_LANG_TEMPLATE_DESCRIPTION_EDIT','Kurzbeschreibung bearbeiten');
	define('M4J_LANG_DELETE','L&ouml;schen');
	define('M4J_LANG_DELETE_CONFIRM','Wirklich l&ouml;schen?');
	define('M4J_LANG_NEW_CATEGORY','Neue Kategorie');
	define('M4J_LANG_NAME','Name');
	define('M4J_LANG_SHORTDESCRIPTION','Kurzbeschreibung');
	define('M4J_LANG_ID','ID');
	define('M4J_LANG_ITEMS','Elemenete');
	define('M4J_LANG_EDIT','Bearbeiten');
	define('M4J_LANG_EDIT_TEMPLATE_ITEMS','Elemente bearbeiten');
	define('M4J_LANG_TEMPLATE_NAME_REQUIRED','Bitte geben Sie einen Namen f&uuml;r die Formularvolage an !');
	define('M4J_LANG_AT_LEAST_ONE','L&ouml;schung nicht m&ouml;glich!<br/>Mindestens ein Element muss bestehen bleiben.');
	define('M4J_LANG_NEW_CATEGORY','Neue Kategorie');

	define('M4J_LANG_EDIT_ELEMENT','Bearbeitung eines Elementes der Vorlage: ');
	define('M4J_LANG_CATEGORY_NAME_ERROR','Geben Sie bitte einen Kategorienamen an');
	define('M4J_LANG_NONE_LEGAL_EMAIL','Geben Sie eine g&uuml;ltige email Adresse ein oder lassen Sie dieses Feld frei.<br/>');
	define('M4J_LANG_EMAIL','EMail');
	define('M4J_LANG_POSITION','Position');
	define('M4J_LANG_ACTIVE','Aktiv');
	define('M4J_LANG_UP','Hoch');
	define('M4J_LANG_DOWN','Runter');
	define('M4J_LANG_EDIT_CATEGORY','Kategorie bearbeiten');
	define('M4J_LANG_TEMPLATE_ELEMENTS','Elemente der Vorlage: ');
	define('M4J_LANG_NEW_ELEMENT_LONG','Neues Element hinzuf&uuml;gen zur Vorlage: ');	
	define('M4J_LANG_ELEMENT_NO_QUESTION_ERROR','Bitte geben Sie eine Frage ein.');
	define('M4J_LANG_REQUIRED','Pflicht');
	define('M4J_LANG_QUESTION','Frage');
	define('M4J_LANG_TYPE','Typ');
	define('M4J_LANG_YES','Ja');		
	define('M4J_LANG_NO','Nein');	
	define('M4J_LANG_ALL_FORMS','Alle Formulare');
	define('M4J_LANG_NO_CATEGORYS','Ohne Kategorie');
	define('M4J_LANG_FORMS_OF_CATEGORY','Formulare der Kategorie: ');
	define('M4J_LANG_PREVIEW','Vorschau');
	define('M4J_LANG_DO_COPY','Kopie erstellen');		
	define('M4J_LANG_COPY','Kopie');
	define('M4J_LANG_VERTICAL','Vertikal');
	define('M4J_LANG_HORIZONTAL','Horizontal');
	define('M4J_LANG_EXAMPLE','Beispiel');
	define('M4J_LANG_CHECKBOX','Schalter');	
	define('M4J_LANG_DATE','Datum');
	define('M4J_LANG_TEXTFIELD','Eingabefeld');
	define('M4J_LANG_OPTIONS','Vorgaben');
	define('M4J_LANG_CHECKBOX_DESC','Der Benutzer wird nach einer Ja/Nein Antwort gefragt.');
	define('M4J_LANG_DATE_DESC','Der Benutzer muss ein Datum eingeben.');
	define('M4J_LANG_TEXTFIELD_DESC','Der Benutzer gibt einen individuellen Text ein.');
	define('M4J_LANG_OPTIONS_DESC','Der Benutzer w&auml;hlt aus vorgegebenen Antworten aus. ');
	define('M4J_LANG_CLOSE_PREVIEW','Vorschau schlie&szlig;en');
	define('M4J_LANG_Q_WIDTH','Breite der Spalte in der die Fragen stehen. (Links)');
	define('M4J_LANG_A_WIDTH','Breite der Spalte in der die Antworten stehen. (Rechts)');
	define('M4J_LANG_OVERVIEW','&Uuml;bersicht');
	define('M4J_LANG_UPDATE_PROCEED','& Weiter');
	define('M4J_LANG_NEW_ITEM','Neues Element');
	define('M4J_LANG_EDIT_ITEM','Element bearbeiten');
	define('M4J_LANG_CATEGORY_NAME','Name der Kategorie');
	define('M4J_LANG_EMAIL_ADRESS','Email Adresse');
	define('M4J_LANG_ADD_NEW_ITEM','Neues Element hinzuf&uuml;gen:');
	define('M4J_LANG_YOUR_QUESTION','Ihre Frage');
	define('M4J_LANG_REQUIRED_LONG','Ist dies ein Pflichtfeld');
	define('M4J_LANG_HELP_TEXT','Hilfe Text. Geben Sie dem Nutzer eine Hilfestellung zu Ihrer Frage (nicht zwingend erforderlich)');
	define('M4J_LANG_TYPE_OF_CHECKBOX','Hier geben Sie an von welchem Typ Ihr Schalter sein soll:');
	define('M4J_LANG_ITEM_CHECKBOX','Kontrollk&auml;stchen.');
	define('M4J_LANG_YES_NO_MENU','Ja/Nein Auswahl.');
	define('M4J_LANG_YES_ON','Ja bzw. An.');
	define('M4J_LANG_NO_OFF','Nein bzw. Aus.');
	define('M4J_LANG_INIT_VALUE','Anfangswert:');
	define('M4J_LANG_TYPE_OF_TEXTFIELD','Hier geben Sie an von welchem Typ Ihr Eingabefeld sein soll:');
	define('M4J_LANG_ITEM_TEXTFIELD','Textfeld');
	define('M4J_LANG_ITEM_TEXTAREA','Textbereich');
	define('M4J_LANG_MAXCHARS_LONG','Maximale Zahl der einzugebenden Zeichen:');
	define('M4J_LANG_OPTICAL_ALIGNMENT','Optische Anpassungen:');
	define('M4J_LANG_ITEM_WIDTH_LONG','<b>Breite in Pixel</b> <br/>(F&uuml;r Prozentangaben \'%\' anh&auml;ngen. Leer = Automatische Anpassung)<br/><br/>');
	define('M4J_LANG_ROWS_TEXTAREA','<b>Anzahl der sichtbaren Zeilen:</b><br/> (Nur bei Textbereich)<br/><br/>');
	define('M4J_LANG_DROP_DOWN','<b>Men&uuml;</b>');
	define('M4J_LANG_RADIOBUTTONS','<b>Optionsschaltergruppe</b>');
	define('M4J_LANG_LIST_SINGLE','<b>Liste</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Einfach)');
	define('M4J_LANG_CHECKBOX_GROUP','<b>Kontrollschaltergruppe</b>');
	define('M4J_LANG_LIST_MULTIPLE','<b>Liste</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Mehrfach, mit \'STRG\' & linke Mousetaste)');
	define('M4J_LANG_SINGLE_CHOICE_LONG','Einfachauswahl (Nur eine Vorgabe kann ausgew&auml;hlt werden):');
	define('M4J_LANG_MULTIPLE_CHOICE_LONG','Mehrfachauswahl (Mehrere Vorgaben k&ouml;nnen ausgew&auml;hlt werden):');
	define('M4J_LANG_TYPE_OF_OPTIONS','Hier geben Sie an von welchem Typ Ihr Auswahlfeld sein soll:');
	define('M4J_LANG_ROWS_LIST','<b>Anzahl der sichtbaren Zeilen:</b><br/> (Nur bei Liste)<br/><br/>');
	define('M4J_LANG_ALIGNMENT_GROUPS','<b>Ausrichtung: </b> <br/>(Nur bei Optionsschalter- oder Kontrollschaltergruppe)<br/><br/>');
	define('M4J_LANG_OPTIONS_VALUES_INTRO','<b>Geben Sie hier die Antwort- Vorgaben f&uuml;r Ihre Frage ein.<br/>Frei gelassene Felder werden ignoriert.</b>');
	define('M4J_LANG_CATEGORY_INTRO_LONG','Geben Sie hier einen Einf&uuml;hrungstext ein. Wird nur bei der Darstellungen von Kategorien angezeigt.');
	define('M4J_LANG_TITLE','Titel');
	define('M4J_LANG_ERROR_NO_TITLE','Bitte geben Sie einen Titel ein.');
	define('M4J_LANG_USE_HELP','Hilfe Text als Baloontip im Frontend anzeigen ?');
	define('M4J_LANG_TITLE_FORM','Titel des Formulars');
	define('M4J_LANG_INTROTEXT','Introtext');
	define('M4J_LANG_MAINTEXT','Haupttext');
	define('M4J_LANG_EMAIL_HIDDEN_TEXT','Introtext der EMail. (Erscheint als &Uuml;berschrift in der EMail die an Sie geschickt wird)');
	define('M4J_LANG_TEMPLATE','Formularvorlage');
	define('M4J_LANG_LINK_TO_MENU','Zu einem Men&uuml; verlinken');
	define('M4J_LANG_LINK_CAT_TO_MENU','Aktuelle Kategorie zu einem Men&uuml; verlinken');
	define('M4J_LANG_LINK_TO_CAT','Verlinkung der Kategorie: ');
	define('M4J_LANG_LINK_TO_FORM','Verlinkung des Formulars: ');
	define('M4J_LANG_LINK_TO_NO_CAT','Verlinkung zur Auflistung aller Formulare ohne Kategorie');
	define('M4J_LANG_LINK_TO_ALL_CAT','Verlinkung zur Auflistung aller Kategorien');
	define('M4J_LANG_CHOOSE_MENU','W&auml;hlen Sie das Menu aus, zu dem Sie verlinken m&ouml;chten: ');
	define('M4J_LANG_MENU','Men&uuml;: ');
	define('M4J_LANG_NO_LINK_NAME','Bitte geben Sie einen Namen f&uuml;r diesen Link an.');
	define('M4J_LANG_PUBLISHED','Publiziert:');
	define('M4J_LANG_PARENT_LINK','&Uuml;bergeordneter Link');
	define('M4J_LANG_LINK_NAME','Name des Links');
	define('M4J_LANG_ACCESS_LEVEL','Zugriffsebene:');
	define('M4J_LANG_EDIT_MAIN_DATA','Grunddaten bearbeiten');
	define('M4J_LANG_LEGEND','Legende');
	define('M4J_LANG_LINK','Verlinkung');
	define('M4J_LANG_IS_VISIBLE',' wird angezeigt');
	define('M4J_LANG_IS_HIDDEN',' wird nicht angezeigt');
	define('M4J_LANG_FORM','Formular');
	define('M4J_LANG_ITEM','Element');
	define('M4J_LANG_IS_REQUIRED','Ist ein Pflichtfeld');
	define('M4J_LANG_IS_NOT_REQUIRED','Ist kein Pflichtfeld');
	define('M4J_LANG_ASSIGN_ORDER','Bestimmung der Reihenfolge');
	define('M4J_LANG_ASSIGN_ORDER_HINT','* Die Bestimmung der Reihenfolge ist bei der Auflistung aller Formulare nicht m&ouml;glich !<br/>');
	define('M4J_LANG_EDIT_FORM','Formular bearbeiten');
	define('M4J_LANG_CAPTCHA','Captcha');
	define('M4J_LANG_HOVER_ACTIVE_ON','Ver&ouml;ffentlicht! Klick=Verstecken');
	define('M4J_LANG_HOVER_ACTIVE_OFF','Nicht ver&ouml;ffentlicht! Klick=Ver&ouml;ffentlichen');
	define('M4J_LANG_HOVER_REQUIRED_ON','Pflichtfeld! Klick= Kein Pflichtfeld');
	define('M4J_LANG_HOVER_REQUIRED_OFF','Kein Pflichtfeld! Klick= Pflichtfeld');
	define('M4J_LANG_DESCRIPTION','Erkl&auml;rung');
	define('M4J_LANG_AREA','Bereich');
	define('M4J_LANG_ADJUSTMENT','Einstellung');
	define('M4J_LANG_VALUE','Wert');
	define('M4J_LANG_MAIN_CONFIG','Haupteinstellungen:');
	define('M4J_LANG_MAIN_CONFIG_DESC','Hier k&ouml;nnen Sie die Haupteinstellungen  &auml;ndern. Wenn Sie alle Einstellungen (inkl. CSS) in Ihren Grundzustand zur&uuml;cksetzen m&ouml;chten klicken Sie unten auf Zur&uuml;cksetzen.');
	define('M4J_LANG_CSS_CONFIG','CSS Einstellungen:');
	define('M4J_LANG_CSS_CONFIG_DESC','Diese Werte bestimmen die visuelle Darstellung des Frontend. Sollten Sie keine Erfahrungen in der Einbindung externer (eigener) CSS haben, ver&auml;ndern Sie diese Einstellungen nicht !');
	define('M4J_LANG_RESET','Zur&uuml;cksetzen');
			
	define('M4J_LANG_EMAIL_ROOT', 'Haupt- Emailadresse: ' );
	define('M4J_LANG_MAX_OPTIONS', 'Maximale Vorgaben: ' );
	define('M4J_LANG_PREVIEW_WIDTH', 'Vorschau Breite: ' );
	define('M4J_LANG_PREVIEW_HEIGHT', 'Vorschau H&ouml;he: ' );
	define('M4J_LANG_CAPTCHA_DURATION', 'Captcha Dauer (in min): ' );
	define('M4J_LANG_HELP_ICON', 'Hilfe Symbol: ' );
	define('M4J_LANG_HTML_MAIL', 'HTML Email: ' );
	define('M4J_LANG_SHOW_LEGEND', 'Legende anzeigen: ' );
	
	define('M4J_LANG_EMAIL_ROOT_DESC', 'Die Hauptemailadresse wird verwendet, wenn weder bei der Kategorie noch bei einem Formular eine Mailadresse angegeben wurde. ' );
	define('M4J_LANG_MAX_OPTIONS_DESC', 'Wenn Sie einer Formularvorlage ein Vorgabenelement hinzuf&uuml;gen, beschreibt dieser Wert die Maximale Anzahl der m&ouml;glichen Vorgaben. Dieser Wert muss eine Zahl sein. ' );	
	define('M4J_LANG_PREVIEW_WIDTH_DESC', 'Die Breite der Vorschau einer Vorlage. Wird nur verwendet, wenn bei den Vorlage- Grunddaten die Breite der linken und rechten Spalte nicht angegben wurde. ' );
	define('M4J_LANG_PREVIEW_HEIGHT_DESC', 'Die H&ouml;he der Vorlagenvorschau. ' );
	define('M4J_LANG_CAPTCHA_DURATION_DESC', 'Betrifft das Frontend. Diese Zahl gibt die mindest Dauer an in der ein Captcha g&uuml;ltig ist. Sollte diese Dauer &uuml;berschritten werden, muss die Eingabe nochmal get&auml;tigt werden, auch wenn der Sicherheitscode richtig war. ' );
	define('M4J_LANG_HELP_ICON_DESC', 'Wenn Sie dem Benutzer die M&ouml;glichkeit einer Hilfestellung geben m&ouml;chten, erscheint ein Hilfesymbol. Hier bestimmen Sie die Farbe des Symbols. ' );
	define('M4J_LANG_HTML_MAIL_DESC', 'Wenn Sie die Nachrichten im HTML Format erhalten m&ouml;chten, geben Sie Ja ein. ' );
	define('M4J_LANG_SHOW_LEGEND_DESC', 'Wenn Sie eine Legende im Adminbereich eingeblendet haben wollen, geben Sie Ja ein. ' );
	
	define('M4J_LANG_CLASS_HEADING', 'Haupt&uuml;berschrift:' );
	define('M4J_LANG_CLASS_HEADER_TEXT', 'Seitenkopftext' );
	define('M4J_LANG_CLASS_LIST_WRAP', 'Listen- H&uuml;lle ' );
	define('M4J_LANG_CLASS_LIST_HEADING', 'Listen- &Uuml;berschrift ' );
	define('M4J_LANG_CLASS_LIST_INTRO', 'Listen- Introtext ' );
	define('M4J_LANG_CLASS_FORM_WRAP', 'Formular- H&uuml;lle ' );
	define('M4J_LANG_CLASS_FORM_TABLE', 'Formular- Tabelle ' );
	define('M4J_LANG_CLASS_ERROR', 'Fehlertext' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP', 'Senden - Button H&uuml;lle ' );
	define('M4J_LANG_CLASS_SUBMIT', 'Senden- Button ' );
	define('M4J_LANG_CLASS_REQUIRED', 'Pflichtfeld * css ' );
	
	define('M4J_LANG_CLASS_HEADING_DESC', '<strong>DIV-Tag</strong> - Die &Uuml;berschrift einer Seite ' );
	define('M4J_LANG_CLASS_HEADER_TEXT_DESC', '<strong>DIV-Tag</strong> - Der auf die Haupt&uuml;berschrift folgende Inhalt. ' );
	define('M4J_LANG_CLASS_LIST_WRAP_DESC', '<strong>DIV-Tag</strong> - Die H&uuml;lle eines Elementes bei der Auflistung. ' );
	define('M4J_LANG_CLASS_LIST_HEADING_DESC', '<strong>DIV-Tag</strong> - Die &Uuml;berschrift eines Listenelementes. ' );
	define('M4J_LANG_CLASS_LIST_INTRO_DESC', '<strong>DIV-Tag</strong> - Der Introtext eines Listenelementes. ' );
	define('M4J_LANG_CLASS_FORM_WRAP_DESC', '<strong>DIV-Tag</strong> - Die H&uuml;lle eines Formulars. ' );
	define('M4J_LANG_CLASS_FORM_TABLE_DESC', '<strong>TABLE-Tag</strong> - Die Tabelle in der die Formularfelder angezeigt werden. ' );
	define('M4J_LANG_CLASS_ERROR_DESC', '<strong>SPAN-Tag</strong> - Erscheint bei Fehleingaben. ' );
	define('M4J_LANG_CLASS_SUBMIT_WRAP_DESC', '<strong>DIV-Tag</strong> - Die H&uuml;lle die den Senden Button umschlie&szlig;t. ' );
	define('M4J_LANG_CLASS_SUBMIT_DESC', '<strong>INPUT-Tag</strong> - Der Sendenbutton an sich. ' );
	define('M4J_LANG_CLASS_REQUIRED_DESC', '<strong>SPAN-Tag</strong> - Die Texteigenschaft des Sterns, welcher ein Pflichtfeld ausweist. ' );
	
	define('M4J_LANG_INFO_HELP','Info und Hilfe');
	
	// New to Version 1.1.5
	define('M4J_LANG_CHOOSE_CAPTCHA', 'Captcha Technik: ' ); 
	define('M4J_LANG_CSS','CSS');
	define('M4J_LANG_SIMPLE','Einfach');
	
	//New To Version 1.1.7
		define('M4J_LANG_CONFIG_RESET','Konfiguration wurde erfolgreich zur&uuml;ckgesetzt.');
		define('M4J_LANG_CONFIG_SAVED','Konfiguration wurde erfolgreich gespeichert.');
		define('M4J_LANG_CAPTCHA_DESC', 'Bei manchen Servern und unter einigen Templates wird das Standard-CSS-Captcha nicht richtig angezeigt. W&auml;hlen Sie CSS f&uuml;r das Standard-CSS-Captcha oder Einfach f&uuml;r ein normales Captcha. Wenn Sie weiterhin Probleme haben verwenden Sie "Spezial"' );
	define('M4J_LANG_SPECIAL','Spezial');
	
	define('M4J_LANG_MAIL_ISO','E-Mail Zeichensatz');
	define('M4J_LANG_MAIL_ISO_DESC','utf-8 , iso-8859-1 (Western Europe), iso-8859-4 (Balto), iso-8859-5 (Cyrillic), iso-8859-6 (Arabic), iso-8859-7 (Greek), iso-8859-8 (Hebrew),iso-8859-9 (Turkish), iso-8859-10 (Nordic),iso-8859-11 (Thai)');
	
	// New to Version 1.1.8
	$m4j_lang_elements[40]= 'Datei Anhang';	
	define('M4J_LANG_ATTACHMENT','Datei Anhang');
	define('M4J_LANG_ATTACHMENT_DESC','Der Benutzer versendet eine Datei &uuml;ber das Formular.');
	define('M4J_LANG_TYPE_OF_ATTACHMENT','Geben Sie hier die Parameter f&uuml;r die Datei&uuml;bermittlung an:');
	define('M4J_LANG_ALLOWED_ENDINGS','Zugelassene Dateiendungen.');
	define('M4J_LANG_MAXSIZE','Maximale Dateigr&ouml;&szlig;e.');
	define('M4J_LANG_BYTE','Byte');
	define('M4J_LANG_KILOBYTE','Kilobyte');
	define('M4J_LANG_MEGABYTE','Megabyte');
	define('M4J_LANG_ELEMENT_ATTACHMENT_DESC','Bitte Endungen ohne einleitenden Punkt und getrennt durch <b>Kommas</b> eintragen.<br />
                                               Wenn Sie die Felder frei lassen werden alle Endungen zugelassen bzw. jede Gr&ouml;&szlig;e akzeptiert.
                                               Um Ihnen die Arbeit zu erleichtern, k&ouml;nnen Sie unterhalb auf die passende Endung klicken um diese automatisch hinzuzuf&uuml;gen.');
	define('M4J_LANG_IMAGES','Bilder');
	define('M4J_LANG_DOCS','Dokumente');
	define('M4J_LANG_AUDIO','Audio');
	define('M4J_LANG_VIDEO','Video');										   
    define('M4J_LANG_DATA','Daten');
	define('M4J_LANG_COMPRESSED','Kompression');
	define('M4J_LANG_OTHERS','Sonstige');
	define('M4J_LANG_ALL','Alle');
	
	// New to Version 1.1.9
	
	define('M4J_LANG_FROM_NAME','Absender Name');
	define('M4J_LANG_FROM_EMAIL','Absender Email');
	define('M4J_LANG_FROM_NAME_DESC','Geben Sie hier den Absendernamen an, der bei den Emails angezeigt werden soll.<br/>');
	define('M4J_LANG_FROM_EMAIL_DESC','Geben Sie hier die Absender- Emailadresse, an die bei den Emails eingebunden werden soll.<br/>');
	define('M4J_LANG_TEMPLATE_DELETE_CAUTION',' Achtung! Alle Formulare die diese Vorlage enthalten werden ebenfalls gel&ouml;scht!');
	
?>
