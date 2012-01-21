<?PHP
	/**
	* @version $Id: proforms 10041 2008-03-18 21:48:13Z fahrettinkutyol $
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

	define('M4J_LANG_FORM_CATEGORIES','Skjema Kategorier');
	define('M4J_LANG_ERROR_NO_CATEGORY','Valgt skjema kategori finnes ikke eller er ikke publisert');
	define('M4J_LANG_ERROR_NO_FORM','Valgt skjema finnes ikke eller er ikke publisert');
	define('M4J_LANG_YES','Ja');		
	define('M4J_LANG_NO','Nei');	
	
	define('M4J_LANG_NO_CATEGORY','Uten Kategori');
	define('M4J_LANG_NO_CATEGORY_LONG','Her kan du finne alle skjemaer som ikke er i en kategori.');
	define('M4J_LANG_SUBMIT','send');
	define('M4J_LANG_MISSING','Felt mangler: ');
	define('M4J_LANG_ERROR_IN_FORM','Nødvendig informasjon mangler:');
	define('M4J_LANG_ERROR_NO_MAIL_ADRESS','Det finnes ikke en adresse for dette skjemaet. Meldingen kunne ikke bli sendt.');
	define('M4J_LANG_ERROR_CAPTCHA','Feil sikkerhetskode eller det har gått for lang tid!');
	define('M4J_LANG_MAIL_SUBJECT','Skjema melding: ');
	define('M4J_LANG_CAPTCHA_ADVICE','Før musen over venstre bilde og skriv inn sikkerhetskode i tekstfeltet til høyre.');
	define('M4J_LANG_REQUIRED_DESC','Nødvendig information.');
	define('M4J_LANG_SENT_SUCCESS','Informasjonen ble sendt ok.');
	
	//New To Version 1.1.8
	define('M4J_LANG_TO_LARGE','<br/> &nbsp;- Filen er for stor! Maksimum: ');
	define('M4J_LANG_WRONG_ENDING','<br/> &nbsp;- Feil filtype!<br/> &nbsp;&nbsp; Tillatte filtyper: ');
	
	//New To Version 1.1.9
	define('M4J_LANG_SENT_ERROR','feil oppsto under sending <br/> Epost ble ikke sendt!');
	
	//New to Proforms
	define ( 'M4J_LANG_ERROR_USERMAIL', 'Du må oppgi en gyldig e-postadresse:');
	define ( 'M4J_LANG_RESET', 'Nullstill');
	define ( 'M4J_LANG_REQUIRED', 'er nødvendig og kan ikke være tomt. ');
	define ( 'M4J_LANG_ERROR_PROMPT', 'Vi beklager. Noen av inn data ikke er gyldig og kan ikke behandles. Tilsvarende felt er merket.');
	define ( 'M4J_LANG_ALPHABETICAL', 'must være alfabetisk.');
	define ( 'M4J_LANG_NUMERIC', 'must være numeriske.');
	define ( 'M4J_LANG_INTEGER', 'must være et heltall. ');
	define ( 'M4J_LANG_URL', 'must være en URL.');
	define ( 'M4J_LANG_EMAIL', 'must være en gyldig e-postadresse.');
	define ( 'M4J_LANG_ALPHANUMERIC', 'must være alfanumerisk.');
	define ( 'M4J_LANG_PLEASE_SELECT', 'Velg');
	define ( 'M4J_LANG_ASK2CONFIRM', 'Vennligst send meg en bekreftelse.');
	define ( 'M4J_LANG_ASK2CONFIRM_DESC', 'Hvis du aktiverer dette alternativet, vil du få en e-postbekreftelse av de innsendte data.');
?>