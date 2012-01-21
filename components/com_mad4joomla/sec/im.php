<?PHP
header("Content-type: image/png");
include_once('../../../configuration.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$user =null;
$dead = null;
if (isset($HTTP_GET_VARS['id'])) $user = addslashes(strip_tags($HTTP_GET_VARS['id']));

if(isset($mosConfig_host))
	{
	$dbname=$mosConfig_db;
	$dbhost=$mosConfig_host;
	$dbuser=$mosConfig_user;
	$dbpass=$mosConfig_password;
	}
else
	{
	$config= new JConfig();
	$dbname=$config->db;
	$dbhost=$config->host;
	$dbuser=$config->user;
	$dbpass=$config->password;
	$mosConfig_dbprefix = $config->dbprefix;
	}

mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname);


$query = "SELECT captcha, dead FROM ".$mosConfig_dbprefix."m4j_captcha WHERE user = '".$user."'";
$result = mysql_query($query);

$captcha = null;
while ($line = mysql_fetch_array($result))
	{
	$captcha = $line['captcha']."<br/>";
	if($line['dead'] >1) die();
	}

if($captcha==null) die();

$im = imagecreate(160,32);
$abc    = imagecreatefrompng("abc.png");

for($t=0; $t<5;$t++)
	{
	$ascii = ord(substr($captcha,$t,1));
	if(!(($ascii>=48 && $ascii<58) || ($ascii>65 && $ascii<91))) die();
	if($ascii>=48 && $ascii<58) $ascii = $ascii+7;
	$ascii = $ascii-55;
	$slide = rand(-2,2);
	imagecopy($im,$abc,($t*32),0,($ascii*32)+$slide,0,32,32);
	}

 imagepng($im);
 imagedestroy($im);

 $query = "SELECT dead  FROM ".$mosConfig_dbprefix."m4j_captcha WHERE user = '".$user."'";
 $result = mysql_query($query);
 $line = mysql_fetch_array($result);
 $dead = $line['dead']+1;

 $query = "UPDATE ".$mosConfig_dbprefix."m4j_captcha SET dead = '".$dead."' WHERE user = '".$user."'";
 $result = mysql_query($query);

?>