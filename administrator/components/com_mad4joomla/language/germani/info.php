<?PHP 
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
?>



<table width="944" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="400" height="309" align="left" valign="top"><img src="components/com_mad4joomla/images/mad4media-3d.png" width="400" height="309"></td>
        <td align="left" valign="top"><h3>Mad4Joomla Mailforms V <?PHP echo M4J_VERSION_NO; ?></h3>
          Diese Komponente wurde von Dipl. Informatiker Fahrettin Kutyol f&uuml;r Mad4Media entwickelt.<br>
          Mad4Media entwickelt Software unter dem Aspekt des User Centered Design. Unsere Produkte und Projekte werden benutzerorientiert konzipiert um eine bestm&ouml;gliche Ergonomie (Benutzerfreundlichkeit) zu erm&ouml;glichen. Neben der Auftragsprogrammierung in Java und PHP, bieten wir unseren Kunden eine individuelle Erweiterung der Systeme Joomla und osCommerce an. Sollten Sie an unseren Dienstleistungen interessiert sein, erfahren Sie mehr auf unsere Pr&auml;senz: <a href="http://www.mad4media.de" target="_blank">Mad4Media</a><br>
          <br>
          <strong>Lizenz und Garantie</strong><br>
          Mad4Joomla Mailforms steht unter der GNU GPL Lizenz. Mad4Media &uuml;bernimmt keinerlei Garantie auf Funktionalit&auml;t oder Vollst&auml;digkeit. Mad4Media haftet nicht f&uuml;r m&ouml;gliche, durch diese Komponente entstehende, Sch&auml;den.<br>
          <br>
          <strong>Verwendete freie Komponenten:</strong><br>
          <a href="http://www.dynarch.com/projects/calendar/" target="_blank">jsCalendar</a> - LGPL<br>
          Icons von <a href="http://www.dryicons.com" target="_blank">dryicons.com</a> - Creative Commons Attribution 3.0 License<br>
          <a href="http://www.dhtmlgoodies.com" target="_blank">Balloontip</a> - LGPL <br>
          <br>
          <br></td>
      </tr>
    </table>
	
	    <table width="100%" border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td width="50%" align="left" valign="top"><h3>&Uuml;ber Mad4Joomla Mailforms</h3>
		<p align="left">Mad4Joomla Mailforms ist eine leicht zu bedienende Komponente zur Erstellung von Email- Formularen.
		  Die Eigenschaften die dieses Produkt auszeichnen sind die Benutzerfreundlichkeit, Kategorisierung von Formulareinheiten,
		  das Arbeiten mit Vorlagen, Hilfetexte zu den Formularfeldern, ein ausgereiftes Routing zu den Empf&auml;nger- Emailadressen, 
		  die Einbindung von Inhalten und ein neuartiges Captcha. Dadurch ist es m&ouml;glich ganze Kontaktsysteme aufzubauen,
	  die sich erheblich von einfachen Kontaktformularen abheben. Beispiele: Jobangebote, Reservierungen etc.</p>
			<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100%" height="100%" align="left" valign="top"><h3>Mad4Joomla Home</h3>
                  <p>Auf der Homepage von Mad4Joomla erhalten Sie mehr Informationen &uuml;ber dieses Modul.<br />
                    Wir w&uuml;rden uns &uuml;ber &Uuml;bersetzungen freuen. Sie k&ouml;nnen auf der Homepage auch die Sprachdateien separat herunterladen und editieren. Schicken Sie uns einfach die &Uuml;bersetzungen zu und wir ver&ouml;ffentliche diese in einem neuen Paket.<br />
                    Sie erreichen die Seite unter: <a href="http://www.mad4media.de/mad4joomla-mailforms.html" target="_blank">www.mad4media.de/mad4joomla-mailforms.html
                  </a></p>
                  <p>Sie werden wie folgt, unten als &Uuml;bersetzer mit einem Link auf Ihre Pr&auml;senz aufgef&uuml;hrt.<br />
                      <br />
                  <h3>&Uuml;bersetzungen</h3>
                    English, Deutsch by <a href="www.mad4media.de" target="_blank">mad4media</a><br /> 
                  <br />
                    <br />  
                    <br />
                  </p>
                </td>
              </tr>
                        </table>			<h3>&nbsp;</h3></td>
            <td width="52%" align="left" valign="top"><h3>Kurzanleitung<br />
            </h3>
              <strong>1.Schritt:</strong><br />
                Ist eine Kategorie n&ouml;tig? <br />
                Wenn Sie z.B. mehrere Jobangebote ver&ouml;ffentlichen m&ouml;chten, ist es ratsam eine  Kategorie &bdquo;Jobs&ldquo; anzulegen. Der Inhalt (Text) den Sie bei der Eingabe angeben  k&ouml;nnen, wird am Kopf jeder Kategorieseite gezeigt. Wenn ein Formular keine  Empf&auml;nger- Emailadresse besitzt und einer speziellen Kategorie angeh&ouml;rt wird  die Emailadresse der Kategorie verwendet, die Sie hier angeben k&ouml;nnen. Sollte  die Kategorie keine Adresse besitzen, wird die in der Konfiguration angegebene  Haupt- Emailadresse verwendet.
  &nbsp;<br />
  <br />
                <strong>2. Schritt:</strong><br />
                Anlegen einer oder mehrerer Vorlagen.<br />
                Bei den Grunddaten k&ouml;nnen Sie eine Kurzbeschreibung angeben. Diese dient zur  internen Wiedererkennung von Vorlagen. Wichtig ist die Breite der linken und  rechten Tabellenspalte der Formulartabelle. Weiterhin k&ouml;nnen Sie hier angeben  ob Hilfetexte zu den Feldern angezeigt werden sollen.<br />
                Nach den Grunddaten bestimmen Sie die Elemente des Formulars. 
                <br />
                <br />
                <strong>3.Schritt</strong><br />
                Anlegen eines Formulars.<br />
                Geben Sie einen Titel an und bestimmen Sie die Kategorie.  Wenn Sie dieses Formular keiner Kategorie zuweisen m&ouml;chten verwenden Sie: Ohne  Kategorie.<br />
                Bestimmen Sie die Formularvorlage die verwendet werden soll. Wenn Sie das Feld  Email nicht ausf&uuml;llen, wird die Email-Adresse der Kategorie verwendet. Ist  diese auch leer, wird die Haupt- Emailadresse verwendet. Mit Captcha bestimmen  Sie ob ein Sicherheitscode eingegeben werden soll. Der Introtext wird nur bei  Auflistungen auf der Kategorieseite angezeigt. Der Haupttext wird nur auf der  eigentlichen Formularseite angezeigt. Introtext der Email ist ein Text der nur  in der Email angezeigt wird die Sie erhalten. Mit diesem Text k&ouml;nnen Sie sich  selbst einen Hinweis geben, um welches Formular es sich handelt. 
                <br />
                <br />
                <strong>4.Schritt</strong><br />
                Verlinken.<br />
            Auf der Formularseite im Backend haben Sie die M&ouml;glichkeit einzelne Formulare  oder ganze Kategorien zu bestehenden Men&uuml;s zu verlinken. Formulare ohne  Kategorie und die Einstellung: &bdquo;Alle Kategorien&ldquo;&nbsp; k&ouml;nnen ebenfalls verlinkt werden.</td>
          </tr>
      </table>      
      <p>&nbsp;</p></td>
  </tr>
</table>

