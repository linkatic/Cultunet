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

	defined( '_JEXEC' ) or die( 'Direkt &aring;tkomst &auml;r f&ouml;rbjudet.' );

	define('M4J_LANG_FORM_CATEGORIES','Formul&auml;r kategori');
	define('M4J_LANG_ERROR_NO_CATEGORY','Formul&auml;ret existerar inte eller &auml;r inte publicerat');
	define('M4J_LANG_ERROR_NO_FORM','Formul&auml;ret existerar inte');
	define('M4J_LANG_YES','Ja');		
	define('M4J_LANG_NO','Nej');	
	
	define('M4J_LANG_NO_CATEGORY','Utan kategori');
	define('M4J_LANG_NO_CATEGORY_LONG','H&auml;r finns alla formul&auml;r som inte &auml;r kopplade till n&aring;gon kategori.');
	define('M4J_LANG_SUBMIT','Skicka');
	define('M4J_LANG_MISSING','Fyll i dessa formul&auml;r!:');
	define('M4J_LANG_ERROR_IN_FORM','Information som saknas:');
	define('M4J_LANG_ERROR_NO_MAIL_ADRESS','Det finns ingen m&aring;ladress angiven. Mailet kunde inte skickas!');
	define('M4J_LANG_ERROR_CAPTCHA','Fel s&auml;kerhetskod!');
	define('M4J_LANG_MAIL_SUBJECT','Formul&auml;rets meddelande: ');
	define('M4J_LANG_CAPTCHA_ADVICE','H&aring;ll muspekaren &ouml;ver v&auml;nstra bilden och skriv in koden i det h&ouml;gra f&auml;ltet.');
	define('M4J_LANG_REQUIRED_DESC','M&aring;ste fyllas i.');
	define('M4J_LANG_SENT_SUCCESS','Mailet &auml;r nu skickat!');
	
	//New To Version 1.1.8
	define('M4J_LANG_TO_LARGE','<br/> &nbsp;- F&ouml;r stor fil: ');
	define('M4J_LANG_WRONG_ENDING','<br/> &nbsp;- Otill&aring;ten fil&auml;ndelse !<br/> &nbsp;&nbsp; Till&aring;tna fil&auml;ndelser: ');
	
	//New To Version 1.1.9
	define('M4J_LANG_SENT_ERROR','Ett fel uppstod. <br/> Mailet blev inte skickat!');

	//New To Proforms
	define ( 'M4J_LANG_ERROR_USERMAIL', 'Du måste ange en giltig e-postadress:');
	define ( 'M4J_LANG_RESET', 'Återställ');
	define ( 'M4J_LANG_REQUIRED ',' krävs och kan inte vara tomt. ');
	define ( 'M4J_LANG_ERROR_PROMPT', 'Vår ursäkter. Några av inmatade data är inte giltig och kan inte behandlas. motsvarande fält är markerade.');
	define ( 'M4J_LANG_ALPHABETICAL ', 'måste vara alfabetisk. ');
	define ( 'M4J_LANG_NUMERIC ', 'måste vara numeriska. ');
	define ( 'M4J_LANG_INTEGER ', 'måste vara ett heltal. ');
	define ( 'M4J_LANG_URL ', 'måste vara en URL. ');
	define ( 'M4J_LANG_EMAIL ', 'måste vara en giltig e-postadress.');
	define ( 'M4J_LANG_ALPHANUMERIC ','måste vara alfanumeriska. ');
	define ( 'M4J_LANG_PLEASE_SELECT', 'Välj');
	define ( 'M4J_LANG_ASK2CONFIRM', 'Skicka mig en bekräftelse.');
	define ( 'M4J_LANG_ASK2CONFIRM_DESC', 'Om du aktiverar den här kryssrutan, kommer du att få en e-postbekräftelse av de inlämnade uppgifterna. ');
?>