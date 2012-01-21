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
	remember_cid();
	$out = null;
	$reset =  mosGetParam($_REQUEST, 'reset');
	$advice = intval(mosGetParam($_REQUEST, 'advice'));
	$main_data = array(
					   'email_root','max_options','preview_width','preview_height','mail_iso','captcha_duration','from_name','from_email',
					   'help_icon','html_mail','show_legend','captcha'
					   );
	
	$css_data = array(
					'class_heading','class_list_heading','class_list_intro','class_list_wrap','class_header_text','class_form_wrap',                       
					'class_form_table','class_error','class_submit_wrap','class_submit','class_required'                       
					  );
	
	
	
	for($t=0;$t<sizeof($main_data);$t++)
		$$main_data[$t] = mosGetParam($_REQUEST, $main_data[$t]);
	
	$captcha_duration = intval($captcha_duration);
	$help_icon = intval($help_icon);
	($html_mail=='true')?$html_mail = 'true':$html_mail='false';
	($show_legend=='true')?$show_legend = 'true':$show_legend='false';
	($captcha=='CSS' || $captcha=='SIMPLE'  || $captcha=='SPECIAL')?$captcha=$captcha:$captcha='CSS';
	
	for($t=0;$t<sizeof($css_data);$t++)
		$$css_data[$t] = mosGetParam($_REQUEST, $css_data[$t]);
	
	
	
	if($reset)
		{
		copy(M4J_INCLUDE_RESET_CONFIGURATION, M4J_INCLUDE_CONFIGURATION);	
		mosRedirect(M4J_CONFIG.M4J_REMEMBER_CID_QUERY.'&advice=1');
		};  
	if($task=='update')
		{
		$out = "<?PHP\n";	
		for($t=0;$t<8;$t++)
			$out.= "define('M4J_". strtoupper($main_data[$t])."', '".$$main_data[$t]."' );\n";
		for($t=8;$t<11;$t++)
			$out.= "define('M4J_". strtoupper($main_data[$t])."', ".$$main_data[$t]." );\n";
		$out.= "define('M4J_". strtoupper($main_data[11])."', '".$$main_data[11]."' );\n";
		$out.= "\n";
		for($t=0;$t<sizeof($css_data);$t++)
			$out.= "define('M4J_". strtoupper($css_data[$t])."', '".$$css_data[$t]."' );\n";
		$out .= "?>\n";

		$file = fopen (M4J_INCLUDE_CONFIGURATION, "w");
		fwrite($file, $out);
		fclose ($file);  
		
		mosRedirect(M4J_CONFIG.M4J_REMEMBER_CID_QUERY.'&advice=2');
		}
	
	
	  
	  
  HTML_m4j::head(M4J_CONFIG);
   
    $helpers->caption(M4J_LANG_CONFIG); 
	$helpers->config_feedback($advice);


    HTML_m4j::configuration();
   
 
   
  HTML_m4j::footer();

?>
