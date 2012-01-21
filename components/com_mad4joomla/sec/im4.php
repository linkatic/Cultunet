<?PHP 

include_once('../../../configuration.php');



if(isset($mosConfig_host))
	{
	// Joomla 1.0x
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	session_start( );
	$alphanumeric = "0,1,2,3,4,5,6,7,8,9,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
	$chars = explode(",",$alphanumeric);
	$captcha = "";
	for ($t=0;$t<6;$t++)
	 $captcha .= $chars[rand(0,61)];
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
	$alphanumeric = "0,1,2,3,4,5,6,7,8,9,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
	$chars = explode(",",$alphanumeric);
	$GLOBALS['captcha'] = "";
	
	for ($t=0;$t<6;$t++)
	 $GLOBALS['captcha'] .= $chars[rand(0,61)];
	$session =& JFactory::getSession();
	$session->set('m4j_captcha',$GLOBALS['captcha']); 
	}	
	
header("Content-type: image/png");
$im = imagecreate(160,32);
imageantialias($im,true);
imagecolorallocate ($im, 255, 255, 255);
$letter_color = imagecolorallocate($im,0,0,0); 
imagettftext($im, 22, -7, 25, 23, $letter_color, "im4.ttf", $captcha);

ImagePNG ($im);
imagedestroy($im);


?>