<?PHP
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// These are the parameters and function which are needed for dual development

$temp = new JConfig;
		foreach (get_object_vars($temp) as $k => $v) {
			$name = 'mosConfig_'.$k;
			$GLOBALS[$name] = $v;
		}

$lang =& JFactory::getLanguage();
$GLOBALS['mosConfig_live_site']		= substr_replace(JURI::root(), '', -1, 1);
$GLOBALS['mosConfig_lang']          = $lang->getBackwardLang();
$GLOBALS['database'] =  & JFactory::getDBO();
DEFINE( "_MOS_NOTRIM", 0x0001 );
DEFINE( "_MOS_ALLOWHTML", 0x0002 );
DEFINE( "_MOS_ALLOWRAW", 0x0004 );

function mosGetParam( &$arr, $name, $def=null, $mask=0 )
{
	// Static input filters for specific settings
	static $noHtmlFilter	= null;
	static $safeHtmlFilter	= null;

	$var = JArrayHelper::getValue( $arr, $name, $def, '' );

	// If the no trim flag is not set, trim the variable
	if (!($mask & 1) && is_string($var)) {
		$var = trim($var);
	}

	// Now we handle input filtering
	if ($mask & 2) {
		// If the allow html flag is set, apply a safe html filter to the variable
		if (is_null($safeHtmlFilter)) {
			$safeHtmlFilter = & JFilterInput::getInstance(null, null, 1, 1);
		}
		$var = $safeHtmlFilter->clean($var, 'none');
	} elseif ($mask & 4) {
		// If the allow raw flag is set, do not modify the variable
		$var = $var;
	} else {
		// Since no allow flags were set, we will apply the most strict filter to the variable
		if (is_null($noHtmlFilter)) {
			$noHtmlFilter = & JFilterInput::getInstance(/* $tags, $attr, $tag_method, $attr_method, $xss_auto */);
		}
		$var = $noHtmlFilter->clean($var, 'none');
	}
	return $var;
}

function editorArea($name, $content, $hiddenField, $width, $height, $col, $row)
{
	jimport( 'joomla.html.editor' );
	$editor =& JFactory::getEditor();
	echo $editor->display($hiddenField, $content, $width, $height, $col, $row);
}

function mosRedirect( $url, $msg='' ) {
	$app = new JApplication();
	$app->redirect($url,$msg);

}
function sefRelToAbs($value)
{
	$url = str_replace('&amp;', '&', $value);
	if(substr(strtolower($url),0,9) != "index.php") return $url;
	$uri    = JURI::getInstance();
	$prefix = $uri->toString(array('scheme', 'host', 'port'));
	return $prefix.JRoute::_($url);
}

function ampReplace( $text ) {
	return JFilterOutput::ampReplace($text);
}

function mosCreateMail( $from='', $fromname='', $subject, $body ) {

	$mail =& JFactory::getMailer();

	$mail->From 	= $from ? $from : $mail->From;
	$mail->FromName = $fromname ? $fromname : $mail->FromName;
	$mail->Subject 	= $subject;
	$mail->Body 	= $body;

	return $mail;
}

global $mosConfig_lang,$mosConfig_live_site,$database;
?>