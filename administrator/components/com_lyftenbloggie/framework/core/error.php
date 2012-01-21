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
 * LyftenBloggie Framework Error class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieError
{
	var $_errors = array();

	/**
	 * Get BloggieError instance
	 *
	 * @return	object  BloggieError object
	 **/
	function &getInstance() {
		static $instance;

		if (!isset($instance)) {
			$instance = new BloggieError();
		}
		
		return $instance;
	}

	/**
	 * Get the most recent error message
	 *
	 * @param	string	option error index
	 * @param	string	indicates if jerror objects should return their error message
	 * @return	string  error message
	 **/
	function getError($i = null, $toString = true) {
		$error =& BloggieError::getInstance();
		return $error->get($i, $toString);
	}

	/**
	 * Return all errors, if any
	 *
	 * @return	array   error message array
	 **/
	function getErrors() {
		$error =& BloggieError::getInstance();
		return $error->getAll();
	}

	/**
	 * Static. Add an error message
	 *
	 * @return	void
	 **/
	function setError($e)
	{
		$error =& BloggieError::getInstance();
		$error->set($e);
	}

	/**
	 * Get the most recent error message
	 *
	 * @param	string	option error index
	 * @param	string	indicates if jerror objects should return their error message
	 * @return	string  error message
	 **/
	function get($i = null, $toString = true)
	{

		// find the error
		if ($i === null) {
			// default, return the last message
			$error = end($this->_errors);
		} else if (!array_key_exists($i, $this->_errors)) {
			// if $i has been specified but does not exist, return false
			return false;
		} else {
			$error = $this->_errors[$i];
		}

		// check if only the string is requested
		if (JError::isError($error) && $toString) {
			return $error->toString();
		}

		return $error;
	}

	/**
	 * Return all errors, if any
	 *
	 * @return	array   error message array
	 **/
	function getAll()
	{
		return $this->_errors;
	}

	/**
	 * Add an error message
	 *
	 * @return	void
	 **/
	function set($error)
	{
		array_push($this->_errors, $error);
	}

}