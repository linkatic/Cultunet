<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * LyftenBloggie INI Parser class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieINI extends JObject
{
	/**
	 * Converts an object into an INI formatted string
	 *
	 * @access public
	 * @param object $object Data Source Object
	 * @param array  $param  Parameters used by the formatter
	 * @return string INI Formatted String
	 **/
	function toString( $object )
	{
		// Initialize variables
		$retval 	= '';
		$prepend 	= '';

		// First handle groups (or first level key/value pairs)
		foreach ($object as $key => $level1)
		{
			if (is_array($level1))
			{
				// This field is an object, so we treat it as a section
				$retval .= "[".$key."]\n";
				foreach ($level1 as $key => $level2)
				{
					if (!is_array($level2) && !is_array($level2))
					{
						// Join lines
						$level2		= str_replace('|', '\|', $level2);
						$level2		= str_replace(array("\r\n", "\n"), '\\n', $level2);
						$retval		.= $key."=".$level2."\n";
					}
				}
				$retval .= "\n";
			}
			else
			{
				// Join lines
				$level1		= str_replace('|', '\|', $level1);
				$level1		= str_replace(array("\r\n", "\n"), '\\n', $level1);
				$prepend	.= $key."=".$level1."\n";
			}
		}

		return $prepend."\n".$retval;
	}

	/**
	 * Parse an .ini string
	 *
	 * @param mixed The INI string or array of lines
	 * @return object Object
	 **/
	function &toArray( $data )
	{
		static $inistocache;

		if (!isset( $inistocache )) {
			$inistocache = array();
		}

		$lines = explode("\n", $data);
		$hash = md5($data);

		if(array_key_exists($hash, $inistocache)) {
			return $inistocache[$hash];
		}

		$obj = array();

		$sec_name = '';
		$unparsed = 0;
		if (!$lines) {
			return $obj;
		}

		foreach ($lines as $line)
		{
			// ignore comments
			if ($line && $line{0} == ';') {
				continue;
			}

			$line = trim($line);

			if ($line == '') {
				continue;
			}

			$lineLen = strlen($line);
			if ($line && $line{0} == '[' && $line{$lineLen-1} == ']')
			{
				$sec_name = substr($line, 1, $lineLen - 2);
				$obj[$sec_name] = array();
			}
			else
			{
				if ($pos = strpos($line, '='))
				{
					$property = trim(substr($line, 0, $pos));

					// property is assumed to be ascii
					if ($property && $property{0} == '"')
					{
						$propLen = strlen( $property );
						if ($property{$propLen-1} == '"') {
							$property = stripcslashes(substr($property, 1, $propLen - 2));
						}
					}
					// AJE: 2006-11-06 Fixes problem where you want leading spaces
					// for some parameters, eg, class suffix
					// $value = trim(substr($line, $pos +1));
					$value = substr($line, $pos +1);

					if (strpos($value, '|') !== false && preg_match('#(?<!\\\)\|#', $value))
					{
						$newlines = explode('\n', $value);
						$values = array();
						foreach($newlines as $newlinekey=>$newline) {

							// Explode the value if it is serialized as an arry of value1|value2|value3
							$parts	= preg_split('/(?<!\\\)\|/', $newline);
							$array	= (strcmp($parts[0], $newline) === 0) ? false : true;
							$parts	= str_replace('\|', '|', $parts);

							foreach ($parts as $key => $value)
							{
								if ($value == 'false') {
									$value = false;
								}
								else if ($value == 'true') {
									$value = true;
								}
								else if ($value && $value{0} == '"')
								{
									$valueLen = strlen( $value );
									if ($value{$valueLen-1} == '"') {
										$value = stripcslashes(substr($value, 1, $valueLen - 2));
									}
								}
								if(!isset($values[$newlinekey])) $values[$newlinekey] = array();
								$values[$newlinekey][] = str_replace('\n', "\n", $value);
							}

							if (!$array) {
								$values[$newlinekey] = $values[$newlinekey][0];
							}
						}

						if ($sec_name != '') {
							$obj[$sec_name][$property] = $values[$newlinekey];
						} else {
							$obj[$property] = $values[$newlinekey];
						}
					}
					else
					{
						//unescape the \|
						$value = str_replace('\|', '|', $value);

						if ($value == 'false') {
							$value = false;
						}
						else if ($value == 'true') {
							$value = true;
						}
						else if ($value && $value{0} == '"')
						{
							$valueLen = strlen( $value );
							if ($value{$valueLen-1} == '"') {
								$value = stripcslashes(substr($value, 1, $valueLen - 2));
							}
						}

						$value = str_replace('\n', "\n", $value);
						if ($sec_name != '') {
							$obj[$sec_name][$property] = $value;
						} else {
							$obj[$property] = $value;
						}
					}
				}
				else
				{
					if ($line && $line{0} == ';') {
						continue;
					}
					$property = '__invalid'.$unparsed ++.'__';
					if ($sec_name != '') {
						$obj[$sec_name][$property] = trim($line);
					} else {
						$obj[$property] = trim($line);
					}
				}
			}
		}

		$inistocache[$hash] = $obj;
		return $obj;
	}
}
