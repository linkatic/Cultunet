<?PHP 

include_once('../../../configuration.php');



if(isset($mosConfig_host))
	{
	// Joomla 1.0x
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	session_start( );
	$alphanumeric = "0,1,2,3,4,5,6,7,8,9";
	$chars = explode(",",$alphanumeric);
	$captcha = "";
	for ($t=0;$t<6;$t++)
	 $captcha .= $chars[rand(0,9)];
	$_SESSION["m4j_captcha"] = $captcha;
	}
else
	{
	// Joomla 1.5.x
	define( '_JEXEC', 1 );
	define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../..' ));
	define( 'DS',DIRECTORY_SEPARATOR);
	
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	$mainframe =& JFactory::getApplication('site');
	$mainframe->initialise();
	
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	
	$alphanumeric = "0,1,2,3,4,5,6,7,8,9";
	$chars = explode(",",$alphanumeric);
	$GLOBALS['captcha'] = "";
	
	for ($t=0;$t<6;$t++)
	 $GLOBALS['captcha'] .= $chars[rand(0,9)];
	$session =& JFactory::getSession();
	$session->set('m4j_captcha',$GLOBALS['captcha']); 
	}	
	
header("Content-type: image/png");
$im = imagecreate(160,32);
imageantialias($im,true);
imagecolorallocate ($im, 255, 255, 255);
$letter_color = imagecolorallocate($im,0,0,0); 
imagettftext($im, 22, 8, 25, 32, $letter_color, "im3.ttf", $captcha);


$letter = imagecreate(160,32);
imageantialias($letter,true);
imagecolorallocate ($letter, 255, 255, 255);
$black = imagecolorallocate($letter,0,0,0); 


	for($n=0;$n<32;$n++)
		{
		$add = sin(rad2deg($n/240))*4;
		imagecopyresized($letter,$im,$add,$n,0,$n,160,1,160,1);
		}






ImagePNG ($letter);
imagedestroy($letter);
imagedestroy($im);

?>

