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
	$m4j_lang_elements[2]= 'Yes/No Selection';
	$m4j_lang_elements[10]= 'Date';
	$m4j_lang_elements[20]= 'Textfield';
	$m4j_lang_elements[21]= 'Textarea';
	$m4j_lang_elements[30]= 'Menu(single choice)';
	$m4j_lang_elements[31]= 'Select Menu(sincgle choice)';
	$m4j_lang_elements[32]= 'Radiobuttons(single choice)';
	$m4j_lang_elements[33]= 'Checkbox Group(multiple choice)';
	$m4j_lang_elements[34]= 'List(multiple choice)';
	
	
	define('M4J_LANG_FORMS','Forms');
	define('M4J_LANG_TEMPLATES','Templates');
	define('M4J_LANG_CATEGORY','Category');
	define('M4J_LANG_CONFIG','Configuration');
	define('M4J_LANG_HELP','Info & Help');
	define('M4J_LANG_CANCEL','Cancel');
	define('M4J_LANG_PROCEED','Proceed');
	define('M4J_LANG_SAVE','Save');
	define('M4J_LANG_NEW_FORM','New Form');
	define('M4J_LANG_NEW_TEMPLATE','New Template');
	define('M4J_LANG_ADD','Add');
	define('M4J_LANG_EDIT_NAME','Edit name and description of this template');
	define('M4J_LANG_NEW_TEMPLATE_LONG','New Template');
	define('M4J_LANG_TEMPLATE_NAME','Name of this Template');
	define('M4J_LANG_TEMPLATE_NAME_EDIT','Edit the name of this template');
	define('M4J_LANG_TEMPLATE_DESCRIPTION','Shortdescription (for internal use. can be left empty)');
	define('M4J_LANG_TEMPLATE_DESCRIPTION_EDIT','Edit Shortdescription');
	define('M4J_LANG_DELETE','Delete');
	define('M4J_LANG_DELETE_CONFIRM','Do you want realy delete this item?');
	define('M4J_LANG_NEW_CATEGORY','New Category');
	define('M4J_LANG_NAME','Name');
	define('M4J_LANG_SHORTDESCRIPTION','Shortdescription');
	define('M4J_LANG_ID','ID');
	define('M4J_LANG_ITEMS','Items');
	define('M4J_LANG_EDIT','Edit');
	define('M4J_LANG_EDIT_TEMPLATE_ITEMS','Items -> Edit');
	define('M4J_LANG_TEMPLATE_NAME_REQUIRED','Please enter a name for this template !');
	define('M4J_LANG_AT_LEAST_ONE','This Item can not be deleted!<br/>There must be at least one element in here.');	
	define('M4J_LANG_NEW_CATEGORY','New Category');
	
		define('M4J_LANG_EDIT_ELEMENT','Edit element of Template: ');
	define('M4J_LANG_CATEGORY_NAME_ERROR','Please insert a categoryname');
	define('M4J_LANG_NONE_LEGAL_EMAIL','Please insert a valid emailadress or leave this empty.<br/>');
	define('M4J_LANG_EMAIL','eMail');
	define('M4J_LANG_POSITION','Ordering');
	define('M4J_LANG_ACTIVE','Active');
	define('M4J_LANG_UP','Up');
	define('M4J_LANG_DOWN','Down');
	define('M4J_LANG_EDIT_CATEGORY','Edit Category');
	define('M4J_LANG_TEMPLATE_ELEMENTS','Elements of Template: ');
	define('M4J_LANG_NEW_ELEMENT_LONG','Insert new element to template: ');	
	define('M4J_LANG_ELEMENT_NO_QUESTION_ERROR','Please insert a question.');
	define('M4J_LANG_REQUIRED','Required');
	define('M4J_LANG_QUESTION','Question');
	define('M4J_LANG_TYPE','Type');
	define('M4J_LANG_YES','Yes');		
	define('M4J_LANG_NO','No');	
	define('M4J_LANG_ALL_FORMS','All Forms');
	define('M4J_LANG_NO_CATEGORYS','Without Category');
	define('M4J_LANG_FORMS_OF_CATEGORY','Forms of Category: ');
	define('M4J_LANG_PREVIEW','Preview');
	define('M4J_LANG_DO_COPY','Copy');		
	define('M4J_LANG_COPY','Copy');
	define('M4J_LANG_VERTICAL','Vertical');
	define('M4J_LANG_HORIZONTAL','Horizontal');
	define('M4J_LANG_EXAMPLE','Example');
	define('M4J_LANG_CHECKBOX','Button');	
	define('M4J_LANG_DATE','Date');
	define('M4J_LANG_TEXTFIELD','Textfield');
	define('M4J_LANG_OPTIONS','Specified Choice');
	define('M4J_LANG_CHECKBOX_DESC','A simple Yes/No question.');
	define('M4J_LANG_DATE_DESC','User has to enter a date.');
	define('M4J_LANG_TEXTFIELD_DESC','User has to enter a individual Text.');
	define('M4J_LANG_OPTIONS_DESC','User selects one or more answers out of specified items. ');
	define('M4J_LANG_CLOSE_PREVIEW','Close Preview');
	define('M4J_LANG_Q_WIDTH','Width of the question column. (left)');
	define('M4J_LANG_A_WIDTH','Width of the anwer column. (right)');
	define('M4J_LANG_OVERVIEW','Overview');
	define('M4J_LANG_UPDATE_PROCEED','& Proceed');
	define('M4J_LANG_NEW_ITEM','New Item');
	define('M4J_LANG_EDIT_ITEM','Edit Item');
	define('M4J_LANG_CATEGORY_NAME','Category Name');
	define('M4J_LANG_EMAIL_ADRESS','Email Adress');
	define('M4J_LANG_ADD_NEW_ITEM','Add new Item:');
	define('M4J_LANG_YOUR_QUESTION','Your Question');
	define('M4J_LANG_REQUIRED_LONG','Required?');
	define('M4J_LANG_HELP_TEXT','Help Text. Give users a hint to your question.(not essential)');
	define('M4J_LANG_TYPE_OF_CHECKBOX','Type of the Button:');
	define('M4J_LANG_ITEM_CHECKBOX','Checkbox.');
	define('M4J_LANG_YES_NO_MENU','Yes/No Menu.');
	define('M4J_LANG_YES_ON','Yes/On.');
	define('M4J_LANG_NO_OFF','No/Off.');
	define('M4J_LANG_INIT_VALUE','Initial Value:');
	define('M4J_LANG_TYPE_OF_TEXTFIELD','Type of Textfield:');
	define('M4J_LANG_ITEM_TEXTFIELD','Textfield');
	define('M4J_LANG_ITEM_TEXTAREA','Textarea');
	define('M4J_LANG_MAXCHARS_LONG','Maximum Chars:');
	define('M4J_LANG_OPTICAL_ALIGNMENT','Visual Alignment:');
	define('M4J_LANG_ITEM_WIDTH_LONG','<b>Width in Pixel</b> <br/>(Add \'%\' for percentage. Empty = Automatic Fit)<br/><br/>');
	define('M4J_LANG_ROWS_TEXTAREA','<b>Amount of visible rows:</b><br/> (Only for Textarea)<br/><br/>');
	define('M4J_LANG_DROP_DOWN','<b>Menu</b>');
	define('M4J_LANG_RADIOBUTTONS','<b>Radiobuttons</b>');
	define('M4J_LANG_LIST_SINGLE','<b>List</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(single choice)');
	define('M4J_LANG_CHECKBOX_GROUP','<b>Checkbox Group</b>');
	define('M4J_LANG_LIST_MULTIPLE','<b>List</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(multiple choice, with \'CTRL\' & left mousebutton)');
	define('M4J_LANG_SINGLE_CHOICE_LONG','Single Choice (Only one item can be selected):');
	define('M4J_LANG_MULTIPLE_CHOICE_LONG','Multiple Choice (Multiple Items can be selected):');
	define('M4J_LANG_TYPE_OF_OPTIONS','Type of Selection:');
	define('M4J_LANG_ROWS_LIST','<b>Amount of visible rows:</b><br/> (Only for Lists)<br/><br/>');
	define('M4J_LANG_ALIGNMENT_GROUPS','<b>Allignment: </b> <br/>(Only for Radiobuttons and Checkbox Groups)<br/><br/>');
	define('M4J_LANG_OPTIONS_VALUES_INTRO','<b>Specify the answers.<br/>Empty fields will be ignored.</b>');
	define('M4J_LANG_CATEGORY_INTRO_LONG','Introtext. Will only be displayed on category sites.');
	define('M4J_LANG_TITLE','Title');
	define('M4J_LANG_ERROR_NO_TITLE','Please enter a title.');
	define('M4J_LANG_USE_HELP','Help Text for frontend baloontips');
	define('M4J_LANG_TITLE_FORM','Form Title');
	define('M4J_LANG_INTROTEXT','Introtext');
	define('M4J_LANG_MAINTEXT','Maintext');
	define('M4J_LANG_EMAIL_HIDDEN_TEXT','Email Introtext. (Will only be displayed in emails.)');
	define('M4J_LANG_TEMPLATE','Template');
	define('M4J_LANG_LINK_TO_MENU','Link to Menu');
	define('M4J_LANG_LINK_CAT_TO_MENU','Link current category to a menu');
	define('M4J_LANG_LINK_TO_CAT','Link Category: ');
	define('M4J_LANG_LINK_TO_FORM','Link Form: ');
	define('M4J_LANG_LINK_TO_NO_CAT','Link to display all Forms without a category ');
	define('M4J_LANG_LINK_TO_ALL_CAT','Link to display \'All Categories\'');
	define('M4J_LANG_CHOOSE_MENU','Choose a menue to link to: ');
	define('M4J_LANG_MENU','Menu: ');
	define('M4J_LANG_NO_LINK_NAME','Please insert a link name.');
	define('M4J_LANG_PUBLISHED','Published:');
	define('M4J_LANG_PARENT_LINK','Parent Link');
	define('M4J_LANG_LINK_NAME','Link Name');
	define('M4J_LANG_ACCESS_LEVEL','Access Level:');
	define('M4J_LANG_EDIT_MAIN_DATA','Edit Basic Data');
	define('M4J_LANG_LEGEND','Legend');
	define('M4J_LANG_LINK','Link');
	define('M4J_LANG_IS_VISIBLE',' is published');
	define('M4J_LANG_IS_HIDDEN',' is not published');
	define('M4J_LANG_FORM','Form');
	define('M4J_LANG_ITEM','Item');
	define('M4J_LANG_IS_REQUIRED','Required');
	define('M4J_LANG_IS_NOT_REQUIRED','Not Required');
	define('M4J_LANG_ASSIGN_ORDER','Ordering');
	define('M4J_LANG_ASSIGN_ORDER_HINT','* Ordering is not possible for \'All Forms\' !<br/>');
	define('M4J_LANG_EDIT_FORM','Edit Forms');
	define('M4J_LANG_CAPTCHA','Captcha');
	define('M4J_LANG_HOVER_ACTIVE_ON','Published! Click=Unpublish');
	define('M4J_LANG_HOVER_ACTIVE_OFF','Unpublished! Click=Publish');
	define('M4J_LANG_HOVER_REQUIRED_ON','Required! Click= Not Required');
	define('M4J_LANG_HOVER_REQUIRED_OFF','Not Required! Click= Required');
	define('M4J_LANG_DESCRIPTION','Description');
	define('M4J_LANG_AREA','Area');
	define('M4J_LANG_ADJUSTMENT','Configuration');
	define('M4J_LANG_VALUE','Value');
	define('M4J_LANG_MAIN_CONFIG','Main Configuration:');
	define('M4J_LANG_MAIN_CONFIG_DESC','You can configure all main settings here. If you want to reset all main settings (incl. CSS) to default click reset.');
	define('M4J_LANG_CSS_CONFIG','CSS Settings:');
	define('M4J_LANG_CSS_CONFIG_DESC','These settings are required for visual presentation of the frontend. If you are not experienced by including external (own) CSS, don\'t change these values !');
	define('M4J_LANG_RESET','Reset');
			
	define('M4J_LANG_EMAIL_ROOT', 'Main Email Adress: ' );
	define('M4J_LANG_MAX_OPTIONS', 'Maximum Answers <br/> Specified Choice: ' );
	define('M4J_LANG_PREVIEW_WIDTH', 'Preview Width: ' );
	define('M4J_LANG_PREVIEW_HEIGHT', 'Preview Height: ' );
	define('M4J_LANG_CAPTCHA_DURATION', 'Captcha Duration (in min): ' );
	define('M4J_LANG_HELP_ICON', 'Help Icon: ' );
	define('M4J_LANG_HTML_MAIL', 'HTML Email: ' );
	define('M4J_LANG_SHOW_LEGEND', 'Show Legend: ' );
	
	define('M4J_LANG_EMAIL_ROOT_DESC', 'The main email adress is used if neither a category nor a form has assigned an email adress.' );
	define('M4J_LANG_MAX_OPTIONS_DESC', 'This value limits the maximum count of answers for a \'Specified Choice\' item. This value must be numeric.' );	
	define('M4J_LANG_PREVIEW_WIDTH_DESC', 'Width of the template preview. This value is only used if you don\'t assign a preview width in the basic data of a template.' );
	define('M4J_LANG_PREVIEW_HEIGHT_DESC', 'Height of the template preview. ' );
	define('M4J_LANG_CAPTCHA_DURATION_DESC', 'Belongs to the frontend. This value assigns the maximum duration of a captcha\'s validity. If this duration expires, a new captcha code has to be entered even if the old code was valid.' );
	define('M4J_LANG_HELP_ICON_DESC', 'Define the color of a help icon.' );
	define('M4J_LANG_HTML_MAIL_DESC', 'If you want to receive HTML emails choose yes. ' );
	define('M4J_LANG_SHOW_LEGEND_DESC', 'If you want to display a legend at the backend choose yes.' );
	
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
	
	define('M4J_LANG_INFO_HELP','Info and Help');
	
	// New to Version 1.1.5
	define('M4J_LANG_CHOOSE_CAPTCHA', 'Captcha Technique: ' ); 
	define('M4J_LANG_CSS','CSS');
	define('M4J_LANG_SIMPLE','Ordinary');
	
	//New To Version 1.1.7
		define('M4J_LANG_CONFIG_RESET','Configuration has been successfully reset.');
		define('M4J_LANG_CONFIG_SAVED','Configuration has been successfully saved.');
		define('M4J_LANG_CAPTCHA_DESC', ' There might be some problems with the standard-css-captcha and some servers or templates. For this case you have the alternative to choose between the standard-css-captcha and an ordinary captcha. If the ordinary captcha doesn\'t solve your problem then choose Special' );
		define('M4J_LANG_SPECIAL','Special');
	
	
	define('M4J_LANG_MAIL_ISO','Mail Charset');
	define('M4J_LANG_MAIL_ISO_DESC','utf-8 , iso-8859-1 (Western Europe), iso-8859-4 (Balto), iso-8859-5 (Cyrillic), iso-8859-6 (Arabic), iso-8859-7 (Greek), iso-8859-8 (Hebrew),iso-8859-9 (Turkish), iso-8859-10 (Nordic),iso-8859-11 (Thai)');		
	
	
	// New to Version 1.1.8
	$m4j_lang_elements[40]= 'Attachment';	
	define('M4J_LANG_ATTACHMENT','File Attachment');
	define('M4J_LANG_ATTACHMENT_DESC','User can transmit a file by a form.');
	define('M4J_LANG_TYPE_OF_ATTACHMENT','Enter parameters for this file transfer field:');
	define('M4J_LANG_ALLOWED_ENDINGS','Approved file extensions.');
	define('M4J_LANG_MAXSIZE','Maximum filesize.');
	define('M4J_LANG_BYTE','Byte');
	define('M4J_LANG_KILOBYTE','Kilobyte');
	define('M4J_LANG_MEGABYTE','Megabyte');
	define('M4J_LANG_ELEMENT_ATTACHMENT_DESC','Please enter all file extensions without a point(dot) and separated by <b>commas</b>.If you leave blank fields, all file extension will be accepted or any size will be approved. To ease the work, you can choose out of the extensions below which will be included automatically.');
	define('M4J_LANG_IMAGES','Images');
	define('M4J_LANG_DOCS','Documents');
	define('M4J_LANG_AUDIO','Audio');
	define('M4J_LANG_VIDEO','Video');										   
    define('M4J_LANG_DATA','Data');
	define('M4J_LANG_COMPRESSED','Compression');
	define('M4J_LANG_OTHERS','Others');
	define('M4J_LANG_ALL','All');
	
	// New to Version 1.1.9
	define('M4J_LANG_FROM_NAME','From name');
	define('M4J_LANG_FROM_EMAIL','From email');
	define('M4J_LANG_FROM_NAME_DESC','Insert a from name for the emails you will achieve<br/>');
	define('M4J_LANG_FROM_EMAIL_DESC','Insert a from email addresss for the emails you will achieve.<br/>');
	define('M4J_LANG_TEMPLATE_DELETE_CAUTION',' Caution! All forms that contain this template will also be deleted!');
	

?>