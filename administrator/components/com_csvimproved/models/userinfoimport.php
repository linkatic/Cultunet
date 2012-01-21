<?php
/**
 * User info import
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: userinfoimport.php 955 2009-08-13 22:23:26Z Roland $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Processor for product details
 *
 * Main processor for importing categories.
 *
 * @package CSVImproved
 * @todo format registerdate
 */
class CsvimprovedModelUserinfoimport extends JModel {
	
	/* Private tables */
	/** @var object contains the vm_user_info table */
	private $_vm_user_info = null;
	/** @var object contains the vm_shopper_vendor_xref table */
	private $_vm_shopper_vendor_xref = null;
	/** @var object contains the user table */
	private $_user = null;
	/** @var object contains the user aro table */
	private $_core_acl_aro = null;
	
	/* Public variables */
	/** @var integer contains the unique user ID string for a billing or shipping address */
	public $user_info_id = null;
	/** @var integer contains the unique Joomla user ID */
	public $user_id = null;
	
	/* Private variables */
	/** @var mixed contains the data of the current field being processed */
	private $_datafield = null;
	/** @var object contains general import functions */
	private $_importmodel = null;
	
	/**
	 * Here starts the processing
	 *
	 * @todo change cdate/mdate to use JDate
	 */
	public function getStart() {
		/* Get the general import functions */
		$this->_importmodel = new CsvimprovedModelimport();
		
		/* Get the imported fields */
		$csv_fields = JRequest::getVar('csv_fields');
		
		/* Get the statistics */
		$csvilog = JRequest::getVar('csvilog');
		
		/* Check if the fields match the data */
		if (count($csv_fields) != count(JRequest::getVar('csvi_data'))) {
			$find = array('{configfields}', '{filefields}');
			$replace = array(count($csv_fields), count(JRequest::getVar('csvi_data')));
			$csvilog->AddStats('incorrect', str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT')), true);
			return false;
		}
		/* All good, let's continue */
		else {
			/* Get the uploaded values */
			foreach ($csv_fields as $name => $details) {
				if ($details['published']) {
					$this->_datafield = $this->_importmodel->ValidateCsvInput($name);
					if ($this->_datafield !== false) {
						/* Check if the field needs extra treatment */
						switch ($name) {
							case 'cdate':
								$this->$name = $this->_datafield;
								break;
							case 'mdate':
								$this->$name = $this->_datafield;
								break;
							case 'address_type':
								switch (strtolower($this->_datafield)) {
									case 'billing address':
									case 'bt':
										$this->$name = 'BT';
										break;
									case 'shipping address':
									case 'st':
										$this->$name = 'ST';
										break;
								}
								break;
							default:
								$this->$name = $this->_datafield;
								break;
						}
					}
					else {
						/* Columns do not match */
						JRequest::setVar('error_found', true);
						$csvi_data = JRequest::getVar('csvi_data');
						$find = array('{configfields}', '{filefields}');
						$replace = array(count($csv_fields), count($csvi_data));
						$fields = array_keys($csv_fields);
						$message =  str_ireplace($find, $replace, JText::_('INCORRECT_COLUMN_COUNT'));
						$message .= '<br />'.JText::_('FIELDS');
						foreach($csv_fields as $fieldname => $field_details) {
							$message .= '<br />'.$field_details['order'].': '.$fieldname;
						}
						$message .= '<br />'.JText::_('VALUE');
						foreach ($csvi_data AS $key => $data) {
							$message .= '<br />'.$key.': '.$data;
						}
						$csvilog->AddStats('incorrect', $message, true);
						
						return false;
					}
				}
			}
		}
		return true;
	}
	
	/**
	 * Getting the product table info
	 */
	private function getTables() {
		$this->_vm_user_info = $this->getTable('vm_user_info');
		$this->_vm_shopper_vendor_xref = $this->getTable('vm_shopper_vendor_xref');
		$this->_user = $this->getTable('users');
		$this->_core_acl_aro = $this->getTable('core_acl_aro');
	}
	
	/**
	 * Getting the product table info
	 */
	private function getCleanTables() {
		$this->_vm_user_info->reset();
		$this->_vm_shopper_vendor_xref->reset();
		$this->_user->reset();
		$this->_core_acl_aro->reset();
		$this->reset();
	}
	
	/**
	 * Clean the variables
	 */
	private function reset() {
		$class_vars = get_class_vars(get_class($this));
		foreach ($class_vars as $name => $value) {
			if (substr($name, 0, 1) != '_') $this->$name = $value;
		}
	}
	
	/**
	 * Process each record and store it in the database
	 */
	public function getProcessRecord() {
		/* Get the imported values */
		$csv_fields = JRequest::getVar('csv_fields');
		$product_data = JRequest::getVar('csvi_data');
		$csvilog = JRequest::getVar('csvilog');
		$db = JFactory::getDBO();
		$authorize = JFactory::getACL();
	   	$newUsertype = 'Registered';
	   	$userdata = array();
	   	$hash_secret = "VirtueMartIsCool";
	   	jimport('joomla.user.helper');
		
		/* See if we have a user_info_id  */
		if (empty($this->user_info_id)) {
			/* No user_info_id, maybe we have user_id, address_type and address_type_name */
			if ((!isset($this->user_id) && (!isset($this->user_email))) || !isset($this->address_type) || !isset($this->address_type_name)) {
				/* No way to identify what needs to be updated, set error and return */
				$csvilog->AddStats('incorrect', JText::_('MISSING_REQUIRED_FIELDS'));
				return false;
			}
		}
		/* We have a user_info_id, do we have a user_id */
		else {
			$q = "SELECT user_id FROM #__vm_user_info WHERE user_info_id = ".$db->Quote($this->user_info_id);
			$db->setQuery($q);
			$csvilog->AddMessage('debug', JText::_('DEBUG_FIND_USER_ID_FROM_VM'), true);
			$this->user_id = $db->loadResult();
		}
		
		/* Get the tables loaded */
		$this->getTables();
		
		/* Check for the user_info_id */
		if (empty($this->user_info_id)) {
			/* See if we have a user_id or user_email */
			if (!isset($this->user_id) && isset($this->user_email)) {
				/* We have an e-mail address, find the user_id */
				$q = "SELECT id
					FROM #__users
					WHERE email = ".$db->Quote($this->user_email);
				$db->setQuery($q);
				$csvilog->AddMessage('debug', JText::_('DEBUG_FIND_USER_ID_FROM_JOOMLA'), true);
				$this->user_id = $db->loadResult();
			}
			if ($this->user_id) {
				/* if we have a user_id we can get the user_info_id */
				$q = "SELECT user_info_id
					FROM #__vm_user_info
					WHERE user_id = ".$db->Quote($this->user_id)."
					AND address_type = ".$db->Quote($this->address_type)."
					AND address_type_name = ".$db->Quote($this->address_type_name);
				$db->setQuery($q);
				$csvilog->AddMessage('debug', JText::_('DEBUG_FIND_USER_INFO_ID'), true);
				$this->user_info_id = $db->loadResult();
				if ($db->getAffectedRows() == 0) $this->user_info_id = null; 
			}
		}
		
		/* If it is a new Joomla user but no username is set, we must set one */
		if ((!isset($this->user_id) || !$this->user_id) && !isset($this->username)) {
			$userdata['username'] = $this->user_email;
		}
		/* Set the username */
		else if (isset($this->username)) $userdata['username'] = $this->username;
		
		/* No user id, need to create a user if possible */
		if (!isset($this->user_id) 
			&& isset($this->user_email)
			&& isset($this->password)) {
			
			$salt		= JUserHelper::genRandomPassword(32);
			$crypt		= JUserHelper::getCryptedPassword($this->password, $salt);
			$password	= $crypt.':'.$salt;
			$userdata['password'] = $password;
			
			/* Set the creation date */
			$date = JFactory::getDate();
			$userdata['registerDate'] = $date->toMySQL();
			
			/* Check if the user has set a usertype */
			if (!isset($this->usertype)) $userdata['usertype'] = $newUsertype;
			else $userdata['usertype'] = $this->usertype;
		}
		else if (!isset($this->user_id)
			&& (!isset($this->user_email)
			|| !isset($this->password))) {
			$csvilog->AddStats('incorrect', JText::_('NO_NEW_USER_PASSWORD_EMAIL'));
			return false;
		}
		else {
			/* Set the id */
			$userdata['id'] = $this->user_id;
		}
		
		/* Check if the user has set a group id */
		if (!isset($this->gid)) $userdata['gid'] = $authorize->get_group_id( '', $newUsertype, 'ARO' );
		else $userdata['gid'] = $this->gid;
		
		/* Only store the Joomla user if there is an e-mail address supplied */
		if (isset($this->user_email)) {
			
			/* Set the name */
			if (isset($this->name)) $userdata['name'] = $this->name;
			else {
				$fullname = false;
				if (isset($this->first_name)) $fullname .= $this->first_name.' ';
				if (isset($this->last_name)) $fullname .= $this->last_name;
				if (!$fullname) $fullname = $this->user_email;
				$userdata['name'] = trim($fullname);
			
			}
			
			/* Set the email */
			if (isset($this->user_email)) $userdata['email'] = $this->user_email;
			
			/* Set if the user is blocked */
			if (isset($this->block)) $userdata['block'] = $this->block;
			
			/* Set the sendEmail */
			if (isset($this->sendemail)) $userdata['sendEmail'] = $this->sendemail;
			
			/* Set the registerDate */
			if (isset($this->registerdate)) $userdata['registerDate'] = $this->registerdate;
			
			/* Set the activation */
			if (isset($this->activation)) $userdata['activation'] = $this->activation;
			
			/* Set the params */
			if (isset($this->params)) $userdata['params'] = $this->params;
			
			/* Bind the data */
			$this->_user->bind($userdata);
			
			/* Store/update the user */
			if ($this->_user->store()) {
				$csvilog->AddMessage('debug', JText::_('DEBUG_JOOMLA_USER_STORED'), true);
				/* Get the new user ID */
				$this->user_id = $this->_user->id;
				/* Store it in the ARO table */
				$this->_core_acl_aro->section_value = 'users';
				$this->_core_acl_aro->value = $this->user_id;
				$this->_core_acl_aro->order_value = 0;
				if (array_key_exists('name', $userdata)) $this->_core_acl_aro->name = $userdata['name'];
				$this->_core_acl_aro->hidden = 0;
				/* Check if there is already an entry */
				$this->_core_acl_aro->check();
				
				/* Store the ACL */
				if ($this->_core_acl_aro->store()) {
					$csvilog->AddMessage('debug', JText::_('DEBUG_JOOMLA_USER_ARO_STORED'), true);
					/* Check if there is already */
					$q = "INSERT INTO #__core_acl_groups_aro_map VALUES (".$userdata['gid'].", '', ".$this->_core_acl_aro->id.")
						ON DUPLICATE KEY UPDATE aro_id = ".$this->_core_acl_aro->id;
					$db->setQuery($q);
					$csvilog->AddMessage('debug', JText::_('DEBUG_JOOMLA_USER_ARO_MAP'), true);
					$db->query();
				}
				else $csvilog->AddMessage('debug', JText::_('DEBUG_JOOMLA_USER_ARO_NOT_STORED'), true);
			}
			else $csvilog->AddMessage('debug', JText::_('DEBUG_JOOMLA_USER_NOT_STORED'), true);
		}
		else $csvilog->AddMessage('debug', JText::_('DEBUG_JOOMLA_USER_SKIPPED'));
		
		/* Set the modified date */
		if (!isset($this->mdate) && empty($this->mdate)) $this->mdate = time();
		
		/* Bind the VM user data */
		$this->_vm_user_info->bind($this);
		
		/* Check if the user */
		
		if (!$this->_vm_user_info->store()) {
			$csvilog->AddStats('incorrect', JText::_('User info has not been added').$this->_vm_user_info->getError());
		}
		else $csvilog->AddStats('added', JText::_('User info has been added'));
		
		$csvilog->AddMessage('debug', JText::_('Stored user info'), true);
		
		/* See if there is any shopper group information to be stored */
		/* user_id, vendor_id, shopper_group_id, customer number */
		/* Get the user_id */
		if (!isset($this->user_id) && isset($this->_vm_user_info->user_info_id)) {
			$q = "SELECT user_id
				FROM #__vm_user_info
				WHERE user_info_id = ".$db->Quote($this->_vm_user_info->user_info_id);
			$db->setQuery($q);
			$this->user_id = $db->loadResult();
		}
		
		/* Get the vendor_id */
		if (!isset($this->vendor_id) && isset($this->vendor_name)) {
			$q = "SELECT vendor_id
				FROM #__vm_vendor
				WHERE vendor_name = ".$db->Quote($this->vendor_name);
			$db->setQuery($q);
			$this->vendor_id = $db->loadResult();
		}
		else $this->vendor_id = $this->_importmodel->GetVendorId();
		
		/* Get the shopper_group_id */
		if (!isset($this->shopper_group_id) && isset($this->shopper_group_name)) {
			$q = "SELECT shopper_group_id
				FROM #__vm_shopper_group
				WHERE shopper_group_name = ".$db->Quote($this->shopper_group_name);
			$db->setQuery($q);
			$this->shopper_group_id = $db->loadResult();
		}
		else if (!isset($this->shopper_group_id) && !isset($this->shopper_group_name)) $this->shopper_group_id = $this->_importmodel->GetDefaultShopperGroupID();
		
		/* Bind the data */
		$this->_vm_shopper_vendor_xref->bind($this);
		if (!$this->_vm_shopper_vendor_xref->store()) {
			$csvilog->AddStats('incorrect', JText::_('USER_SHOPPER_INFO_NOT_STORED').$this->_vm_shopper_vendor_xref->getError());
		}
		else $csvilog->AddStats('added', JText::_('USER_SHOPPER_INFO_STORED'));
		
		$csvilog->AddMessage('debug', JText::_('DEBUG_SHOPPER_INFO_STORED'), true);
		
		/* Clean the tables */
		$this->getCleanTables();
	}
}
?>