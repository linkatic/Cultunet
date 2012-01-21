<?php
/**
 * @version $Id: aecaccess.php
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage Access
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.event.plugin');

/**
 * AEC Authentication plugin
 *
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @package AEC Component
 */
class plgUserAECaccess extends JPlugin
{

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgUserAECaccess( &$subject, $config ) {
		parent::__construct( $subject, $config );
	}

	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access	public
	 * @param   array 	$credentials Array holding the user credentials
	 * @param 	array   $options     Array of extra options
	 * @param	object	$response	 Authentication response object
	 * @return	boolean
	 * @since 1.5
	 */
	function onLoginUser( $credentials, $remember )
	{
		if ( strpos( JPATH_BASE, '/administrator' ) ) {
			// Don't act when entering admin area
			return true;
		}

		if ( file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			include_once( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" );

			global $aecConfig;

			// process AEC verifications
			return $this->verify( $credentials );
		} else {
			return true;
		}
	}

	function onLoginFailure( $credentials, $response )
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT id'
		. ' FROM #__users'
		. ' WHERE username = \'' . AEC_AUTH_ERROR_UNAME . '\''
		;
		$db->setQuery( $query );
		$id = $db->loadResult();

		$redirect = false;

		switch ( AEC_AUTH_ERROR_MSG ) {
			case 'pending':
			case 'open_invoice':
				$redirect = JURI::root() . 'index.php?option=com_acctexp&task=pending&userid=' . $id;
				break;
			case 'expired':
				$redirect = JURI::root() . 'index.php?option=com_acctexp&task=expired&userid=' . $id ;
				break;
			case 'hold':
				$redirect = JURI::root() . 'index.php?option=com_acctexp&task=hold&userid=' . $id ;
				break;
			case 'subscribe':
				$redirect = JURI::root() . 'index.php?option=com_acctexp&task=subscribe&userid=' . $id ;
				break;
		}

		$app =& JFactory::getApplication();

		$app->logout();

		if ( $redirect ) {
			$app->redirect( $redirect );
		}
	}

	function verify( $credentials)
	{
		$savetask = '';
		if ( isset( $_REQUEST['task'] ) ) {
			$_REQUEST['task'] = '';
			$savetask = $_REQUEST['task'];
		}

		include_once( JPATH_ROOT .DS.'components'.DS.'com_acctexp'.DS.'acctexp.php' );

		$_REQUEST['task'] = $savetask;

		$verification = AECToolbox::VerifyUser( $credentials['username'] );

		if ( $verification === true ) {
			return true;
		} else {
			define( 'AEC_AUTH_ERROR_MSG', $verification );
			define( 'AEC_AUTH_ERROR_UNAME', $credentials['username'] );
			return false;
		}
	}
}

?>
