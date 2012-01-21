<?php
/**
 * @version		$Id$
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */


// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

jimport( 'joomla.registry.registry' );

//Register the element class with the loader
JLoader::register('JElement', dirname(__FILE__).DS.'parameter'.DS.'element.php');

/**
 * Parameter handler for Mosets Tree custom fields. This is extended from Joomla
 * framework's JParameter which allows XML setup data passed directly as string
 * rather than as path.
 *
 * @author 		Lee Cher Yeong <mtree@mosets.com>
 * @package 	Mosets Tree
 * @subpackage	Parameter
 * @since		2.1
 */
class MParameter extends JParameter
{
	/**
	 * This constructor is overloaded to accept XML setup data directly
	 *
	 * @access	protected
	 * @param	string The raw parms text
	 * @param	string The xml setup data
	 * @since	1.5
	 */
	function __construct($data, $xmldata = '')
	{
		parent::__construct('_default');

		if (trim( $data )) {
			$this->loadINI($data);
		}
		
		if (!empty($xmldata)) {
			$this->loadSetupData($xmldata);
		}

	}
	
	/**
	 * Loads an xml setup data and parses it
	 *
	 * @access	public
	 * @param	string	The xml setup data
	 * @return	object
	 * @since	1.5
	 */
	function loadSetupData($xmldata)
	{
		$result = false;

		if (!empty($xmldata))
		{
			$xml = & JFactory::getXMLParser('Simple');
			$xml->_parse($xmldata);
			if ($params = & $xml->document->params) {
				foreach ($params as $param)
				{
					$this->setXML( $param );
					$result = true;
				}
			}
		}
		else
		{
			$result = true;
		}

		return $result;
	}

}