<?php
defined('_JEXEC') or die('Restricted access');

# In PHP 5.2 or higher we don't need to bring this in
if (!function_exists('json_encode'))
{
	require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'facebook' . DS . 'jsonwrapper' . DS . 'jsonwrapper_inner.php' );
} 
