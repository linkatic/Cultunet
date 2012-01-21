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
 * LyftenBloggie Framework Settings class
 *
 * @static
 * @package	LyftenBloggie
 * @since	1.1.0
 **/
class BloggieEmail extends JObject
{
	var $_template;
	var $_db;
	var $_file;

	var $_values = array();
	var $_emails = array();

	/**
	 * Constructor
	 */
	function BloggieEmail()
	{
		parent::__construct();

		$this->_db = & JFactory::getDBO(); 
	}

	/**
	 * Returns a reference to a global Settings object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		$emailer = & BloggieEmail::getInstance();
	 *
	 * @access	public
	 * @return	BloggieEmail object.
	 */
	function &getInstance($sig = 'default')
	{
		static $instance;

		if (!isset($instance)) {
			$instance = array();
		}

		if (!isset($instance[$sig]))
		{
			$instance[$sig] = new BloggieEmail();
		}

		return $instance[$sig];
	}

	/**
	 * Template file name
	 *
	 * @param string $file
	 * @return 	void
	 **/
	public function setTemplate($file)
	{
		$file = str_replace('.tpl', '', $file);
		$this->_file = $file;
	}

	/**
	 * Push value, pattern assignment
	 *
	 * @param 	misc 	$value
	 * @param 	string 	$pattern
	 * @return 	void
	 **/
	public function assign($key, $value)
	{
		if(is_array($value))
		{
			foreach($value as $subkey=>$val)
			{
				if(is_string($val))
				{
					$this->_values[$key.'.'.$subkey] = $val;
				}
			}
		}else if(is_string($value))
		{
			$this->_values[$key] = $value;
		}
	}

	/**
	 * Method to determine to whom the email goes
	 *
	 * @return object
	 */
	function emailWho($entry_id = null)
	{
		//Emails from permissions
		$query = 'SELECT r.group'
			. ' FROM #__bloggies_groups AS r'
			. ' WHERE r.email_all = \'1\''
			. ' GROUP BY r.group';
		$this->_db->setQuery( $query );
		$groups = $this->_db->loadResultArray();
		$groups = implode( ',', $groups );

		$query = 'SELECT u.name, u.email, u.gid'
			. ' FROM #__users AS u'
			. ' WHERE u.gid IN ('. $groups .')';
		$this->_db->setQuery( $query );
		$emails = $this->_db->loadObjectList();

		//Email from entry
		if($entry_id)
		{
			//Set entry to template
			$this->setEntry($entry_id);

			//Lookup author email
			$query = 'SELECT u.name, u.email, u.gid'
				. ' FROM #__bloggies_entries AS e'
				. ' LEFT JOIN #__users AS u ON u.id = e.created_by'
				. ' WHERE e.id = \''.$entry_id.'\'';
			$this->_db->setQuery( $query );
			$users = $this->_db->loadObjectList();
			$emails = array_merge((array)$emails, (array)$users);
			unset($users);
		}

		//Set them to the Object
		if(!empty($emails))
		{
			foreach($emails as $email)
			{
				//Get access group
				$accessGroups = BloggieAccess::getInstance($email->gid);

				//Ensure the user can get the email
				if($accessGroups->get('emails.'.$this->_file) || $accessGroups->get('emails.receive_all'))
				{
					$this->_emails[$email->email] = $email->name;
				}
			}
		}
		unset($emails);

		return !empty($this->_emails);
	}

	/**
	 * Method to set entry to the template
	 *
	 * @return object
	 */
	function setEntry($entry_id = null)
	{
		if(!$entry_id) return;

		$query = 'SELECT e.title,'
			. ' CASE WHEN CHAR_LENGTH(e.alias) THEN CONCAT_WS(":", e.id, e.alias) ELSE e.id END as slug,'
			. ' CASE WHEN CHAR_LENGTH(c.slug) THEN c.slug ELSE 0 END as catslug'
			. ' FROM #__bloggies_entries AS e'
			. ' LEFT JOIN #__bloggies_categories AS c ON c.id = e.catid'
			. ' WHERE e.id = '.$entry_id
			;
		$this->_db->setQuery($query);
		$entry = $this->_db->loadAssoc();
		$entry['url'] = JRoute::_(JURI::base().'index.php?option=com_lyftenbloggie&view=entry&category='.$entry['catslug'].'&id='. $entry['slug']);
		unset($entry['slug']);
		unset($entry['catslug']);

		//assign entry to template
		$this->assign('entry', $entry);
	}

	/**
	 * Method to create a Joomla Mailer
	 *
	 * @return object
	 */
	function createMailObj($subject)
	{
		global $mainframe;

		//Load Joomla Mailer
		jimport('joomla.mail.mail');

		$mail	=& JFactory::getMailer();
		
		//Add Sender
		$mail->FromName	= $mainframe->getCfg('fromname');
		$mail->From		= $mainframe->getCfg('mailfrom');

		$mail->setSubject($subject);

		return $mail;
	}

	/**
	 * Method to send email
	 *
	 * @return boolean
	 */
	function sendMail($subject)
	{
		//Create Mailer
		$mailer = $this->createMailObj($subject);

		// Check if mail address is valid.
		$regexp	= "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";

		//Send each email separately
		foreach ($this->_emails as $email=>$user)
		{
			if(!empty($email) && eregi($regexp, $email))
			{
				//set email address
				$mailer->AddAddress($email);

				//Set receiver's name
				$this->assign('receiver.name', $user);

				//set body and send
				if($body = $this->output())
				{
					$mailer->setBody($body);
					$mailer->Send();
				}
			}
		}

		return true;
	}

	/**
	 * Get output from template
	 *
	 * @return string
	 **/
	public function output()
	{
		if (!file_exists(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'emails'.DS.$this->_file.'.tpl')) return;

		$output = file_get_contents(BLOGGIE_ADMIN_PATH.DS.'framework'.DS.'emails'.DS.$this->_file.'.tpl');
	 
		foreach ($this->_values as $key => $value)
		{
			$tagToReplace = '{'.$key.'}';
			$output = str_replace($tagToReplace, $value, $output);
		}
	 
		return $output;
	}
}
?>