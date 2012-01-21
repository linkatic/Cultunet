<?PHP
/**
* @version $Id: mad4jobs.php 10041 2008-03-18 21:48:13Z fahrettinkutyol $
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
if(defined('_JEXEC')) 
	define('_VALID_MOS',1);
	
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

error_reporting(E_ERROR | E_WARNING | E_PARSE);

if(defined('_JEXEC')){
	// get the file class
	jimport('joomla.filesystem.file'); 
	// get the folder class
	jimport('joomla.filesystem.folder'); 
	// get the path class
	jimport('joomla.filesystem.path'); 
}



define ('ACTION_ROOT',0);
define ('ACTION_CATEGORY',1);
define ('ACTION_FORM',2);
define ('ACTION_SEND',3);
	
	if(defined('_JEXEC'))
		{
		define("M4J_ABS", JPATH_ROOT);
		if(!defined('_JLEGACY')) require_once(M4J_ABS . '/administrator/components/com_mad4joomla/includes/evolution.php');
		}
	else define("M4J_ABS", $mainframe->getCfg('absolute_path'));

$current_language = $mosConfig_lang;
(substr($current_language,0,6) == "german")?$german=true:$german=false;

	
require_once(M4J_ABS . '/components/com_mad4joomla/frontend.defines.mad4joomla.php');
require_once(M4J_ABS . '/components/com_mad4joomla/mad4joomla.html.php');


if(file_exists(M4J_INCLUDE_CONFIGURATION)) require_once(M4J_INCLUDE_CONFIGURATION);
else require_once(M4J_INCLUDE_RESET_CONFIGURATION);

if(file_exists(M4J_LANG.'frontend.'.$current_language.'.php')) include_once(M4J_LANG.'frontend.'.$current_language.'.php');
else include_once(M4J_LANG.'frontend.english.php');

require_once(M4J_INCLUDE_CALENDAR);
require_once(M4J_INCLUDE_FUNCTIONS);
require_once(M4J_INCLUDE_VALIDATE);

$print = new HTML_mad4jobs();
$GLOBALS['print'] = $print;


$print->include_balloontips();
$print->stylesheet();
init_calendar();



$mainframe->SetPageTitle($menu->name);


			   

$route = M4J_ROOT;

$cid = intval(mosGetParam($_REQUEST, 'cid'));
$jid = intval(mosGetParam($_REQUEST, 'jid'));
$send = mosGetParam($_REQUEST, 'send');
$submit = mosGetParam($_REQUEST, 'submit');
$itemID = intval(mosGetParam($_REQUEST, 'Itemid', NULL));
$GLOBALS['cid'] = $cid;
$GLOBALS['jid'] = $jid;
$GLOBALS['send'] = $send;
$GLOBALS['submit'] = $submit;
$GLOBALS['itemID'] = $itemID;

if($cid) $route = ACTION_CATEGORY;
else if($jid) 
		  if($send)	$route = ACTION_SEND;	
		  else $route = ACTION_FORM;

		
switch($route)
	{
	case ACTION_ROOT:
	root();
	break;
	
	case ACTION_CATEGORY:
	
	category();
	break;
	
	case ACTION_FORM:
	form();
	break;	
	
	case ACTION_SEND:
	send();
	break;	
	}
	finalize();

function root()
	{
	  global $database, $print;
	  
	  $print->heading(M4J_LANG_FORM_CATEGORIES);
	  metaTitle(M4J_LANG_FORM_CATEGORIES);	
		$query = "SELECT COUNT(*) as `count` FROM #__m4j_jobs WHERE  `cid` = '-1' ";
	  $database->setQuery( $query );	
	  $count = $database->loadObjectList();

	  if($count[0]->count>0)
	  	$print->listing(M4J_LANG_NO_CATEGORY,M4J_LANG_NO_CATEGORY_LONG,(M4J_CID.'-1'));	

	  $query = "SELECT `name`, `cid`, `introtext` FROM #__m4j_category WHERE  `active` = '1'";
	  $database->setQuery( $query );	
	  $rows = $database->loadObjectList();
	  foreach ($rows as $row)
	  	{
		$print->listing($row->name,$row->introtext,(M4J_CID.$row->cid));
		}
	  $print->security_check();
	}

function category()
	{
	global $database, $print, $cid;
	
	$rows=null;
	
	if($cid==-1)
		{
		 $print->heading(M4J_LANG_NO_CATEGORY);
		 metaTitle(M4J_LANG_NO_CATEGORY);	
		 $print->headertext(M4J_LANG_NO_CATEGORY_LONG);
		}
	else
		{	
		$query = "SELECT `name`, `introtext` FROM #__m4j_category WHERE `cid` = '".$cid."'  AND `active` = '1'";
		$database->setQuery( $query );	
		$rows = $database->loadObjectList();
		}
		
	if($rows !=null || $cid==-1)
		{
		  if($cid !=-1)
		  	{
			  metaTitle($rows[0]->name);
			  $print->heading($rows[0]->name);
			  $print->headertext($rows[0]->introtext);
			}
		
		  $query = "SELECT `jid`, `title`, `introtext` FROM #__m4j_jobs WHERE `cid`= '".$cid."' AND active = '1' ORDER BY `sort_order`";
		  $database->setQuery( $query );	
		  $rows = $database->loadObjectList();
		  foreach ($rows as $row)
			{
			$print->listing($row->title,$row->introtext,(M4J_JID.$row->jid));
			}
		}
	else $print->error_no_category();
	$print->security_check();
	
	
	}
	
function form()
	{
	global $database, $print, $jid;
	
	$query = "SELECT `title`, `maintext` ,`fid` ,`captcha` FROM #__m4j_jobs WHERE `jid` = '".$jid."'  AND `active` = '1'";
	$database->setQuery( $query );	
	$rows = $database->loadObjectList();
	if($rows[0]->captcha==1) define('M4J_IS_CAPTCHA',true);
	else define('M4J_IS_CAPTCHA',false);
	
	$query = "SELECT question_width as left_col, answer_width as right_col, use_help  FROM #__m4j_forms WHERE fid = '".$rows[0]->fid."'";
	$database->setQuery( $query );	
	$form = $database->loadObjectList();
	if($form[0]->use_help==1) define('M4J_USE_HELP',1);

		if($rows && $form)
			{
			define('M4J_LEFT_COL',$form[0]->left_col);
			define('M4J_RIGHT_COL',$form[0]->right_col);
			
			metaTitle($rows[0]->title);
			$print->heading($rows[0]->title);
			
			$query = "SELECT COUNT(*) AS count FROM #__m4j_formelements WHERE fid= '".$rows[0]->fid."' AND required = '1' ";
		 	$database->setQuery( $query );	
		 	$c = $database->loadObjectList();
					
			$print->form_head(null,$jid,$jid);
			$print->headertext($rows[0]->maintext);
			if(intval($c[0]->count) >0)$print->required_advice();	
			
			$query = "SELECT * FROM #__m4j_formelements WHERE fid= '".$rows[0]->fid."' AND active = '1' ORDER BY sort_order ASC";
		 	 $database->setQuery( $query );	
		 	 $rows = $database->loadObjectList();
		  	foreach ($rows as $row)
				{
				
					$option_count = sizeof(explode(';',$row->options))-1;
					if($option_count==-1) $option_count=null ;
					
					$html = $row->html;
					switch($row->form)
						{
						case 1:
						break;
						
						case 2:
						$html = $print->replace_yes_no($html);
						break;
						
						case ($row->form>=10 && $row->form<30):
						$html = str_replace('{'.$row->eid.'}', '', $html);
						break;
											
						case ($row->form>=30 && $row->form<40):
						if(option_count)
							{
							for($t=0;$t<$option_count;$t++)
								$html= str_replace('{'.$row->eid.'-'.$t.'}', '', $html);
							}
						break;
						
				
												
						}
		
					$print->form_row($row->question,stripslashes($html),$row->required,$row->help);
				
				}//EOF foreach
			
			//* CAPTCHA
			if(M4J_IS_CAPTCHA)
				{
				purge_captcha();
				$captcha = random_string();
				$proceed = true;
				$user = null;
				while($proceed)
					{
					$user = random_string(32);
					$query = "SELECT COUNT(*) as count  FROM #__m4j_captcha WHERE user= '".$user."'";
		 	 		$database->setQuery( $query );	
		 	 		$rows = $database->loadObjectList();
					if($rows[0]->count == 0) $proceed = false;
					}
				$query = "INSERT INTO #__m4j_captcha"
						. "\n ( date, user, captcha )"
						. "\n VALUES"
						. "\n ( CURRENT_TIMESTAMP, '".$user."', '".$captcha."' )";
				$database->setQuery($query);
				if (!$database->query()) $print->dbError($database->getErrorMsg()); 		
				$print->form_footer($print->captcha($user));
					
				}
			
			else $print->form_footer();
			//EOF CAPTCHA
			}
		else $print->error_no_form();
	echo $print->security_check();
	}
	
function send()
	{
	global $database, $print, $jid ,$send, $mosConfig_sitename,$submit;
	
	if (($_SERVER['HTTP_REFERER']== '') || !isset($_SERVER['HTTP_REFERER'])) die("No direct sending allowed");
	
	$upload_heap = NULL;
	
	$query = "SELECT * FROM #__m4j_jobs WHERE jid = '".$jid."'  AND active = '1'";
	$database->setQuery( $query );	
	$rows = $database->loadObjectList();
	if($rows[0]->captcha==1) define('M4J_IS_CAPTCHA',true);
	else define('M4J_IS_CAPTCHA',false);
	$error = null;
	metaTitle($rows[0]->title);
	//* VALIDATE CAPTCHA
	if(M4J_IS_CAPTCHA)
				{
				if(M4J_CAPTCHA =="CSS")
					{
					$user = mosGetParam($_REQUEST, 'user');
					$validate = mosGetParam($_REQUEST, 'validate');
					$query = "SELECT COUNT(*) as hits FROM #__m4j_captcha WHERE user = '".$user."' AND captcha ='".$validate."'";
					$database->setQuery( $query );	
					$capture = $database->loadObjectList();
					if($capture[0]->hits==0) $error .= $print->add_error(M4J_LANG_ERROR_CAPTCHA);
					if(!$error)
						{
						$query = "DELETE FROM #__m4j_captcha WHERE user = '".$user."' AND captcha ='".$validate."'";
						$database->setQuery($query);
						if (!$database->query()) $print->dbError($database->getErrorMsg()); 
						}
					}	
				else
					{
					if(defined('_JEXEC'))
						{
						$session =& JFactory::getSession();
						$captcha_code= $session->get("m4j_captcha");
						}
					else 
						{
						session_start( );
						$captcha_code=  $_SESSION["m4j_captcha"];
						}
					
					$validate = mosGetParam($_REQUEST, 'validate');
					if($validate != $captcha_code || $captcha_code == "") $error .= $print->add_error(M4J_LANG_ERROR_CAPTCHA);
					}
					
				}
	//* EOF VALIDATE CAPTCHA
	
	$values = null;
	$query = "SELECT * FROM #__m4j_jobs WHERE jid = '".$jid."'  AND active = '1'";
	$database->setQuery( $query );	
	$jobs = $database->loadObjectList();

	$email = M4J_EMAIL_ROOT;
	if($jobs==null) mosRedirect( $mosConfig_live_site );
	if($jobs[0]->email) $email = $jobs[0]->email;
	else
		{
		$query = "SELECT email FROM #__m4j_category WHERE cid = '".$jobs[0]->cid."'  AND active = '1'";
		$database->setQuery( $query );	
		$cat = $database->loadObjectList();
		if($cat[0]->email) $email = $cat[0]->email;
		}
	
	$query = "SELECT * FROM #__m4j_formelements WHERE fid = '".$jobs[0]->fid."'  AND active = '1' ORDER BY sort_order ASC";
	$database->setQuery( $query );	
	$elements = $database->loadObjectList();
	foreach($elements as $element)
		{
			if($element->form != 40 )
				$value = mosGetParam($_REQUEST, 'm4j-'.$element->eid, NULL);
			else 
				{
				$file = mosGetParam( $_FILES, 'm4j-'.$element->eid , NULL );
				if($file) $value = $file['name'];
				else $value = NULL;
				}	
			
		if ($value == NULL && $element->required==1) 
			{
			$error .= $print->add_error(M4J_LANG_MISSING.$element->question);
			}
		else
			{
			$values[$element->eid]= $value;
			}
		// Validate Attachement Upload		

		if($element->parameters !="") $parameter = getParameters($element->parameters);		
		if(intval($element->form) == 40 && isset($parameter) && $value != NULL)
			{
			// Check Ending
			if(! $ending = check_ending($file['name'],$parameter)) $error .= $print->add_error($element->question.M4J_LANG_WRONG_ENDING. str_replace("\n","",$parameter->endings));
			if(! $size = check_size($file['size'],$parameter)) 
				{
				$measure = "MB";
				switch (intval($parameter->measure))
					{
					case 1 : $measure = "B"; break;
					case 1024 : $measure = "KB"; break;	
					}
				$error .= $print->add_error($element->question.M4J_LANG_TO_LARGE. $parameter->maxsize . " ".$measure);
				}
			if ($ending && $size) $upload_heap[] = 'm4j-'.$element->eid;
			}	
		 // EOF Validate Attachement Upload
		}
	
	if($error) 
		{
		//* ERROR - Reprint Form
		if($submit) $print->error($error);
		
		$query = "SELECT question_width as left_col, answer_width as right_col, use_help  FROM #__m4j_forms WHERE fid = '".$jobs[0]->fid."'";
		$database->setQuery( $query );	
		$form = $database->loadObjectList();
		if($form[0]->use_help==1) define('M4J_USE_HELP',1);

		if($form)
			{
			define('M4J_LEFT_COL',$form[0]->left_col);
			define('M4J_RIGHT_COL',$form[0]->right_col);
			
			metaTitle($rows[0]->title);
			$print->heading($jobs[0]->title);
		  	$print->headertext($jobs[0]->maintext);
					
			$query = "SELECT COUNT(*) AS count FROM #__m4j_formelements WHERE fid= '".$jobs[0]->fid."' AND required = '1' ";
		 	$database->setQuery( $query );	
		 	$c = $database->loadObjectList();
			if(intval($c[0]->count) >0)$print->required_advice();
			
			$print->form_head(null,$jid,$jid);
			$query = "SELECT * FROM #__m4j_formelements WHERE fid= '".$jobs[0]->fid."' AND active = '1' ORDER BY sort_order ASC";
		 	 $database->setQuery( $query );	
		 	 $rows = $database->loadObjectList();
		  
		  	 foreach ($rows as $row)
				{
					$options = null;
					$option_count = sizeof(explode(';',$row->options))-1;
					if($option_count==-1) $option_count=null ;
					else $options = explode(';',$row->options);
					
					$html = $row->html;
					switch($row->form)
						{
						case 1:
						break;
						
						case 2:
						$html = $print->replace_yes_no($html);
						break;
						
						case ($row->form>=10 && $row->form<30):
						$html = str_replace('{'.$row->eid.'}', $values[$row->eid], $html);
						break;
											
						case ($row->form>=30 && $row->form<33):
						if($option_count)
							{
	
							for($t=0;$t<$option_count;$t++)
								{
								$html= str_replace('{'.$row->eid.'-'.$t.'}', $print->is_selected($values[$row->eid],$options[$t],$row->form), $html);
								}
							}
						break;			
						
						case ($row->form>=33 && $row->form<40):
						if($option_count)
							{
							$heap = $values[$row->eid];
							$heap_size = sizeof($heap);										
							$i=0;
							for($t=0;$t<$option_count;$t++)
								{
								$replace ='';
								if($heap_size>0 && $i<$heap_size) 
									{
									$replace = $print->is_selected($heap[$i],$options[$t],$row->form);									
									if($replace !='') $i++;
									}
								$html= str_replace('{'.$row->eid.'-'.$t.'}', $replace, $html);
								}
							}
						break;	
											
										
						}
					$print->form_row($row->question,$html,$row->required,$row->help);
					
				}
				//* CAPTCHA
				if(M4J_IS_CAPTCHA)
					{
					purge_captcha();
					$captcha = random_string();
					$proceed = true;
					$user = null;
					while($proceed)
						{
						$user = random_string(32);
						$query = "SELECT COUNT(*) as hits  FROM #__m4j_captcha WHERE user= '".$user."'";
						$database->setQuery( $query );	
						$rows = $database->loadObjectList();
						if($rows[0]->hits == 0) $proceed = false;
						}
					$query = "INSERT INTO #__m4j_captcha"
							. "\n ( date, user, captcha )"
							. "\n VALUES"
							. "\n ( CURRENT_TIMESTAMP, '".$user."', '".$captcha."' )";
					$database->setQuery($query);
					if (!$database->query()) $print->dbError($database->getErrorMsg()); 		
					$print->form_footer($print->captcha($user));	
					}
				else $print->form_footer();
				//EOF CAPTCHA
			}
		else $print->error_no_form();
		}
	else //* ++++ SENDING THE MAIL ++++ *//
		{
		purge_captcha();
		if(!$email || $email=='') $print-> mail_error();
		else
			{		
			$to = $email;
			$subject = $jobs[0]->title;
			$body = $print->body_header($jobs[0]->title,$jobs[0]->hidden);
			$body .= $print->values_head();
			foreach ($elements as $element)
				$body .= $print->values($element->question, $print->format_value($values[$element->eid],$element->form));
			$body .= $print->server_data();
			$body .= $print->values_footer();
			
			$mail = mosCreateMail( M4J_FROM_EMAIL, M4J_FROM_NAME, $subject, $body );
			$mail->CharSet = M4J_MAIL_ISO;
			$mail->IsHTML(M4J_HTML_MAIL);
			$mail->AddAddress($to);
			
			$tmp_dir = M4J_TMP.md5(uniqid("",true));
			$remove_heap= NULL;
						
			if($upload_heap)
				{
					
				if(defined('_JEXEC')){
					JFolder::create($tmp_dir);
				}else{
					mkdir ($tmp_dir,0755);
				}
				
				foreach ($upload_heap as $upload_element)
					{
					if(($name = $_FILES[$upload_element]['name']) != NULL)
						{
							$dest = $tmp_dir."/".$name;
							
							if(defined('_JEXEC')){
								JFile::upload($_FILES[$upload_element]['tmp_name'],$dest);
							}else{
								move_uploaded_file($_FILES[$upload_element]['tmp_name'],$dest);
							}
							$remove_heap[] = $dest;
							$mail->AddAttachment($dest);
							
					 	}
					}//EOF foreach
				}//EOF upload_heap
				
			if($mail->Send()) $print->sent_success();
			else $print->sent_error();
			//Removing the temporary files
			if($upload_heap)
				{
					if(defined('_JEXEC')){
						foreach ($remove_heap as $kill){
							JPath::setPermissions($kill,777);
							JFile::delete($kill);	
						}
						JPath::setPermissions($tmp_dir,777,777);
						JFolder::delete($tmp_dir);
					}else{	
						foreach ($remove_heap as $kill) unlink($kill);	
						rmdir($tmp_dir);
					}
				}	
			}//EOF else
		
		}
	$print->security_check();
	}

	function random_string($cols=5)
		{
		$chars = array('0','1','2','3','4','5','6','7','8','9',
			   'A','B','C','D','E','F','G','H','I','J',
			   'K','L','M','N','O','P','Q','R','S','T',
			   'U','V','W','X','Y','Z');
		$out = null;
		for ($t=0;$t<$cols;$t++) $out .= $chars[rand(0,35)];
		return $out;
		}

	function purge_captcha()
		{
		global $database, $print;
		$border = time()-(60*M4J_CAPTCHA_DURATION); // Current Time Minus Duration in Minutes
		$time = date("Y-m-d H:i:s",$border);
		$query = "DELETE FROM #__m4j_captcha WHERE date < '".$time."'";
		$database->setQuery($query);
		if (!$database->query()) $print->dbError($database->getErrorMsg()); 
		}
	
	function endsWith($H, $N){ return strrpos($H, $N) === strlen($H)-strlen($N); }
	
	function check_ending($file_name, $p)
		{
		if($file_name == NULL) return true;
		
		if(trim($p->endings) == NULL) return true;
		
		$endings = explode(',',$p->endings);
		foreach($endings as $ending)
			{
			if(endsWith($file_name,'.'.trim($ending))) return true;
			}
		return false;		
		}	
	
	function check_size($size,$p)
		{
		
		$measure = intval($p->measure);
		$maxsize = intval($p->maxsize);

		if($measure !=1 && $measure !=1024 && $measure !=1048576) return false;
		if($maxsize == 0 || $maxsize == NULL) return true;	
		$size = intval($size);
		if ($size> ($maxsize*$measure)) return false;
		else return true;	
		}
	// Class to Convert an Array in Arrow Style
	class arrowStyle {function arrowStyle($a){reset($a); foreach($a as $k=>$v) if(!empty($k)) $this->$k = $v;}}
		
	 function getParameters($parameters)
	 	{
		$p_array = null;
		$chopped = explode(';',trim($parameters));
		foreach($chopped as $atom)
			{
			$split = explode('=',$atom);
			if (sizeof($split)==2)
				$p_array[trim($split[0])]= trim($split[1]);		
			}
		$arrowStyle = new arrowStyle($p_array);
		return $arrowStyle;
		}
		

?>