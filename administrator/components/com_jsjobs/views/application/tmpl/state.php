<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/views/application/tmpl/state.php
 * 
 * Description: Address AJAX
 * 
 * History:		NONE
 * 
 */

if(!defined("_VALID_MOS")) {
   DEFINE( "_VALID_MOS", 1 );
}
if(!defined("_JEXEC")) {
   DEFINE( "_JEXEC", 1 );
}

//defined('_JEXEC') or die('Restricted access');
//set IE read from page only not read from cache

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header("content-type: application/x-javascript; charset=tis-620");

$joomlaversion15 = "0";
//$path = str_replace("//components/com_jsjobs/jobposting.php", "", getenv("SCRIPT_FILENAME") );
//$path ='C:/Program Files/Apache Software Foundation/Apache2.2/htdocs/lern';
//echo '<br>path '.$path;

$path='../../../../../..';
require_once($path.'/configuration.php');
require_once( $path . "/configuration.php" );
if( class_exists( "JConfig" ) ) {
	$config = new JConfig();
	$joomlaversion15 = "1";
	
	$dbhost = $config->host;
	$dbuser = $config->user;
	$dbpass = $config->password;
	$dbname    = $config->db;
	$dbprefix = $config->dbprefix;
//echo 'path'.$mosConfig_absolute_path;

//	$rating_conn = mysql_connect($config->host, $config->user, $config->password) or die  ('Error connecting to mysql');
	mysql_pconnect($dbhost,$dbuser,$dbpass) or die ("Unable to connect to MySQL server");
	$rating_conn = mysql_connect($config->host, $config->user, $config->password) or die  ('Error connecting to mysql');
	mysql_select_db($config->db, $rating_conn);
	$mosConfig_absolute_path = $path;
	//$mosConfig_lang ="aaaaaa";
//	$dbprefix = $config->dbprefix;
} else {	
	require( '../../globals.php' );
	require_once( '../../includes/joomla.php' );
	global $database, $mosConfig_lang;
	$joomlaversion15 = "0";
}
/*
if (file_exists($mosConfig_absolute_path."/administrator/components/com_alphacontent/languages/".$mosConfig_lang.".php")){
	include_once($mosConfig_absolute_path."/administrator/components/com_alphacontent/languages/".$mosConfig_lang.".php");
}else{
	include_once($mosConfig_absolute_path."/administrator/components/com_alphacontent/languages/english.php");
}
*/

$data=$_GET['data'];
$val=$_GET['val'];

if ($data=='country') {  // country
  $query  = "SELECT code, name FROM " .$dbprefix."js_job_countries ORDER BY name ASC";
  echo "<select name='country' onChange=\"dochange('state', this.value)\">\n";
  echo "<option value='0'>==== choose country ====</option>\n";
  $result=mysql_db_query($dbname,$query);
  
  while(list($code, $name)=mysql_fetch_array($result)){
       echo "<option value=\"$code\" >$name</option> \n" ;
  }
 echo "</select>\n";

}else if ($data=='state') {  // states
    $query  = "SELECT code, name from " .$dbprefix."js_job_states WHERE countrycode= '$val' ORDER BY name ASC";
	$result=mysql_db_query($dbname,$query);
	if (mysql_num_rows($result)== 0)
	{
		echo "<input class='inputbox' type='text' name='state' size='40' maxlength='100'  />";
	}else
	{
		  echo "<select name='state' class='inputbox' onChange=\"dochange('county', this.value)\">\n";
		  echo "<option value='0'>==== choose state ====</option>\n";
		  
		  
		  while(list($code, $name)=mysql_fetch_array($result)){
		       echo "<option value=\"$code\" >$name</option> \n" ;
		  }
		echo "</select>\n";
	}
}else if ($data=='county') {  // states
    $query  = "SELECT code, name from " .$dbprefix."js_job_counties WHERE statecode= '$val' ORDER BY name ASC";
	$result=mysql_db_query($dbname,$query);
	if (mysql_num_rows($result)== 0)
	{
		echo "<input class='inputbox' type='text' name='county' size='40' maxlength='100'  />";
	}else
	{
		  echo "<select name='county' class='inputbox' onChange=\"dochange('city', this.value)\">\n";
		  echo "<option value='0'>==== choose county ====</option>\n";
		  
		  
		  while(list($code, $name)=mysql_fetch_array($result)){
		       echo "<option value=\"$code\" >$name</option> \n" ;
		  }
		echo "</select>\n";
	}


} else if ($data=='city') { // second dropdown
 // echo '<br>city';
    $query  = "SELECT code, name from " .$dbprefix."js_job_cities WHERE countycode= '$val' ORDER BY 'name'";
	$result=mysql_db_query($dbname,$query);
	if (mysql_num_rows($result)== 0)
	{
		echo "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
	}else
	{
		  echo "<select name='city' class='inputbox' onChange=\"dochange('zipcode', this.value)\">\n";
		  echo "<option value='0'>==== choose city ====</option>\n";
		  
		  
		  while(list($code, $name)=mysql_fetch_array($result)){
		       echo "<option value=\"$code\" >$name</option> \n" ;
		  }
		echo "</select>\n";
	}

}

/*

if ( !$joomlaversion15 ) {
	//connecting to the database to get some information
	$query = "SELECT total_votes, total_value, used_ips, component FROM #__alphacontent_rating WHERE id='".$id_sent."' AND component='".$component."'";
	$database->setQuery( $query );	
	$numbers = $database->loadObjectList();
	$checkIP = unserialize($numbers[0]->used_ips);
	$count = $numbers[0]->total_votes; //how many votes total
	$current_rating = $numbers[0]->total_value; //total number of rating added together and stored
} else {
	//connecting to the database to get some information
	$query = mysql_query("SELECT total_votes, total_value, used_ips, component FROM ".$dbprefix."alphacontent_rating WHERE id='".$id_sent."' AND component='".$component."'")or die(" Error: ".mysql_error());
	$numbers = mysql_fetch_assoc($query);
	$checkIP = unserialize($numbers['used_ips']);
	$count = $numbers['total_votes']; //how many votes total
	$current_rating = $numbers['total_value']; //total number of rating added together and stored
}
*/
?>
