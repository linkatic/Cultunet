<?PHP
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

class M4J_validate {

	var $tlds = array(
					"AC"=> true, "AD"=> true, "AE"=> true, "AERO"=> true, "AF"=> true, "AG"=> true, "AI"=> true, 
					"AL"=> true, "AM"=> true, "AN"=> true, "AO"=> true, "AQ"=> true, "AR"=> true, "ARPA"=> true, 
					"AS"=> true, "AT"=> true, "AU"=> true, "AW"=> true, "AZ"=> true, "BA"=> true, "BB"=> true, 
					"BD"=> true, "BE"=> true, "BF"=> true, "BG"=> true, "BH"=> true, "BI"=> true, "BIZ"=> true, 
					"BJ"=> true, "BM"=> true, "BN"=> true, "BO"=> true, "BR"=> true, "BS"=> true, "BT"=> true, 
					"BV"=> true, "BW"=> true, "BY"=> true, "BZ"=> true, "CA"=> true, "CC"=> true, "CD"=> true, 
					"CF"=> true, "CG"=> true, "CH"=> true, "CI"=> true, "CK"=> true, "CL"=> true, "CM"=> true, 
					"CN"=> true, "CO"=> true, "COM"=> true, "COOP"=> true, "CR"=> true, "CU"=> true, "CV"=> true, 
					"CX"=> true, "CY"=> true, "CZ"=> true, "DE"=> true, "DJ"=> true, "DK"=> true, "DM"=> true, 
					"DO"=> true, "DZ"=> true, "EC"=> true, "EDU"=> true, "EE"=> true, "EG"=> true, "ER"=> true, 
					"ES"=> true, "ET"=> true, "EU"=> true, "FI"=> true, "FJ"=> true, "FK"=> true, "FM"=> true, 
					"FO"=> true, "FR"=> true, "GA"=> true, "GB"=> true, "GD"=> true, "GE"=> true, "GF"=> true, 
					"GG"=> true, "GH"=> true, "GI"=> true, "GL"=> true, "GM"=> true, "GN"=> true, "GOV"=> true, 
					"GP"=> true, "GQ"=> true, "GR"=> true, "GS"=> true, "GT"=> true, "GU"=> true, "GW"=> true, 
					"GY"=> true, "HK"=> true, "HM"=> true, "HN"=> true, "HR"=> true, "HT"=> true, "HU"=> true, 
					"ID"=> true, "IE"=> true, "IL"=> true, "IM"=> true, "IN"=> true, "INFO"=> true, "INT"=> true, 
					"IO"=> true, "IQ"=> true, "IR"=> true, "IS"=> true, "IT"=> true, "JE"=> true, "JM"=> true, 
					"JO"=> true, "JP"=> true, "KE"=> true, "KG"=> true, "KH"=> true, "KI"=> true, "KM"=> true, 
					"KN"=> true, "KR"=> true, "KW"=> true, "KY"=> true, "KZ"=> true, "LA"=> true, "LB"=> true, 
					"LC"=> true, "LI"=> true, "LK"=> true, "LR"=> true, "LS"=> true, "LT"=> true, "LU"=> true, 
					"LV"=> true, "LY"=> true, "MA"=> true, "MC"=> true, "MD"=> true, "MG"=> true, "MH"=> true, 
					"MIL"=> true, "MK"=> true, "ML"=> true, "MM"=> true, "MN"=> true, "MO"=> true, "MP"=> true, 
					"MQ"=> true, "MR"=> true, "MS"=> true, "MT"=> true, "MU"=> true, "MUSEUM"=> true, "MV"=> true, 
					"MW"=> true, "MX"=> true, "MY"=> true, "MZ"=> true, "NA"=> true, "NAME"=> true, "NC"=> true, 
					"NE"=> true, "NET"=> true, "NF"=> true, "NG"=> true, "NI"=> true, "NL"=> true, "NO"=> true, 
					"NP"=> true, "NR"=> true, "NU"=> true, "NZ"=> true, "OM"=> true, "ORG"=> true, "PA"=> true, 
					"PE"=> true, "PF"=> true, "PG"=> true, "PH"=> true, "PK"=> true, "PL"=> true, "PM"=> true, 
					"PN"=> true, "PR"=> true, "PRO"=> true, "PS"=> true, "PT"=> true, "PW"=> true, "PY"=> true, 
					"QA"=> true, "RE"=> true, "RO"=> true, "RU"=> true, "RW"=> true, "SA"=> true, "SB"=> true, 
					"SC"=> true, "SD"=> true, "SE"=> true, "SG"=> true, "SH"=> true, "SI"=> true, "SJ"=> true, 
					"SK"=> true, "SL"=> true, "SM"=> true, "SN"=> true, "SO"=> true, "SR"=> true, "ST"=> true, 
					"SU"=> true, "SV"=> true, "SY"=> true, "SZ"=> true, "TC"=> true, "TD"=> true, "TF"=> true, 
					"TG"=> true, "TH"=> true, "TJ"=> true, "TK"=> true, "TL"=> true, "TM"=> true, "TN"=> true, 
					"TO"=> true, "TP"=> true, "TR"=> true, "TT"=> true, "TV"=> true, "TW"=> true, "TZ"=> true, 
					"UA"=> true, "UG"=> true, "UK"=> true, "UM"=> true, "US"=> true, "UY"=> true, "UZ"=> true, 
					"VA"=> true, "VC"=> true, "VE"=> true, "VG"=> true, "VI"=> true, "VN"=> true, "VU"=> true, 
					"WF"=> true, "WS"=> true, "YE"=> true, "YT"=> true, "YU"=> true, "ZA"=> true, "ZM"=> true, 
					"ZW"=> true
				   );


	function email($email) 
		{
			$email = trim($email);
			if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) return false;
			$email_array = explode("@", $email);
			$local_array = explode(".", $email_array[0]);
			for ($i = 0; $i < sizeof($local_array); $i++) 
				if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) 	return false;
					
			if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) 
			{ 
				$domain_array = explode(".", $email_array[1]);
				if (sizeof($domain_array) < 2) return false; 
				for ($i = 0; $i < sizeof($domain_array); $i++) 
					if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) 	return false;
					
				if(!$this->tlds[strtoupper($domain_array[(sizeof($domain_array)-1)])]) return false;
			}		
			return true;
		}


	function multipleEmail($email)
		{
		if(substr_count($email,";") == 0 && substr_count($email,",") == 0) return $this->email($email);
		else
			{
			$emails = preg_split("/[;,]+/", $email);
			
			$isMail = true;
			
			foreach($emails as $mail)
				{
				if($mail != "" && !$this->email($mail)) $isMail = false;
				}
			return $isMail;
			}
		
		
		}


	function url($url)
		{
		$url = trim($url);
		$regex = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
		if (preg_match ($regex, $url)) return true;
		else return false;
		}





}//EOF Class Validate

//* Create a validate object

$validate = new M4J_validate();
$GLOBALS['validate'] = $validate;

?>
