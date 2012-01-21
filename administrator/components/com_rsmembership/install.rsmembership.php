<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

$db = &JFactory::getDBO();

// Get a new installer
$plg_installer = new JInstaller();

// Install the System Plugin
$plg_installer->install($this->parent->getPath('source').DS.'plg_rsmembership');
// Must be published by default
$db->setQuery("UPDATE #__plugins SET published=1 WHERE `element`='rsmembership' AND `folder`='system'");
$db->query();
// Install the Wire Payment Plugin
$plg_installer->install($this->parent->getPath('source').DS.'plg_rsmembershipwire');
// Must be published by default
$db->setQuery("UPDATE #__plugins SET published=1 WHERE `element`='rsmembershipwire' AND `folder`='system'");
$db->query();

// BEGIN UPDATE PROCEDURE

// REVISION 2 UPDATES
// term_id was introduced in R2
$db->setQuery("SHOW COLUMNS FROM `#__rsmembership_memberships` WHERE `Field`='term_id'");
if (!$db->loadResult())
{
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `term_id` INT NOT NULL AFTER `description`");
	$db->query();
	
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `recurring` TINYINT( 1 ) NOT NULL AFTER `price`");
	$db->query();
	
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `share_redirect` TEXT NOT NULL AFTER `recurring`");
	$db->query();
}

// user_email was introduced in R2
$db->setQuery("SHOW COLUMNS FROM `#__rsmembership_transactions` WHERE `Field`='user_email'");
if (!$db->loadResult())
{
	$db->setQuery("ALTER TABLE `#__rsmembership_transactions` ADD `user_email` VARCHAR( 255 ) NOT NULL AFTER `user_id`");
	$db->query();
	
	$db->setQuery("ALTER TABLE `#__rsmembership_transactions` ADD `user_data` TEXT NOT NULL AFTER `user_email`");
	$db->query();
}

// rsmembership_membership_folders was renamed to rsmembership_membership_shared in R2
$db->setQuery("SHOW TABLES LIKE '".$db->getPrefix()."rsmembership_membership_folders'");
if ($db->loadResult())
{
	$db->setQuery("SELECT * FROM #__rsmembership_membership_folders");
	$rows = $db->loadObjectList();
	foreach ($rows as $row)
	{
		$db->setQuery("INSERT INTO #__rsmembership_membership_shared SET `membership_id` = '".$row->membership_id."', `params` = '".$db->getEscaped($row->path)."', `type`='folder', `published`='".$row->published."', `ordering`='".$row->ordering."'");
		$db->query();
	}
}

// rsmembership_extra_value_folders was renamed to rsmembership_extra_value_shared in R9
$db->setQuery("SHOW TABLES LIKE '".$db->getPrefix()."rsmembership_extra_value_folders'");
if ($db->loadResult())
{
	$db->setQuery("SELECT * FROM #__rsmembership_extra_value_folders");
	$rows = $db->loadObjectList();
	foreach ($rows as $row)
	{
		$db->setQuery("INSERT INTO #__rsmembership_extra_value_shared SET `extra_value_id` = '".$row->extra_value_id."', `params` = '".$db->getEscaped($row->path)."', `type`='folder', `published`='".$row->published."', `ordering`='".$row->ordering."'");
		$db->query();
	}
	$db->setQuery("DROP TABLE #__rsmembership_membership_folders");
	$db->query();
}

// check if we've added countries twice
$db->setQuery("SELECT COUNT(`name`) FROM #__rsmembership_countries WHERE `name`='United States'");
$count = $db->loadResult();
if ($count > 1)
{
	$delete = $count - 1;
	
	$db->setQuery("SELECT DISTINCT(`name`) FROM #__rsmembership_countries");
	$countries = $db->loadResultArray();
	foreach ($countries as $country)
	{
		$db->setQuery("DELETE FROM #__rsmembership_countries WHERE `name`='".$country."' LIMIT ".$delete);
		$db->query();
	}
	$db->setQuery("DROP TABLE #__rsmembership_extra_value_folders");
	$db->query();
}

// add primary key to rsmembership_countries
$db->setQuery("DESCRIBE `#__rsmembership_countries`");
$result = $db->loadObject();
if ($result->Key != 'PRI')
{
	$db->setQuery("ALTER IGNORE TABLE `#__rsmembership_countries` ADD UNIQUE (`name`)");
	$db->query();
}

// REVISION 10 UPDATES
$db->setQuery("SELECT name FROM #__rsmembership_configuration WHERE `name`='disable_registration'");
if (!$db->loadResult())
{
	$db->setQuery("INSERT INTO #__rsmembership_configuration SET `name`='disable_registration', `value`='0'");
	$db->query();
}
$db->setQuery("SELECT name FROM #__rsmembership_configuration WHERE `name`='registration_page'");
if (!$db->loadResult())
{
	$db->setQuery("INSERT INTO #__rsmembership_configuration SET `name`='registration_page', `value`=''");
	$db->query();
}

$db->setQuery("SHOW COLUMNS FROM `#__rsmembership_membership_users` WHERE `Field`='from_transaction_id'");
if (!$db->loadResult())
{
	$db->setQuery("ALTER TABLE `#__rsmembership_membership_users` ADD `from_transaction_id` INT NOT NULL AFTER `notes`, ADD `last_transaction_id` INT NOT NULL AFTER `from_transaction_id`, ADD `custom_1` VARCHAR( 255 ) NOT NULL AFTER `last_transaction_id`, ADD `custom_2` VARCHAR( 255 ) NOT NULL AFTER `custom_1`, ADD `custom_3` VARCHAR( 255 ) NOT NULL AFTER `custom_2`");
	$db->query();
}

// R11 UPDATES
$db->setQuery("SHOW COLUMNS FROM #__rsmembership_memberships WHERE `Field`='price'");
$result = $db->loadObject();
if (strtolower($result->Type) == 'float')
{
	$db->setQuery("ALTER TABLE `#__rsmembership_extra_values` CHANGE `price` `price` DECIMAL( 10, 2 ) NOT NULL");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` CHANGE `price` `price` DECIMAL( 10, 2 ) NOT NULL");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_membership_upgrades` CHANGE `price` `price` DECIMAL( 10, 2 ) NOT NULL");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_membership_users` CHANGE `price` `price` DECIMAL( 10, 2 ) NOT NULL");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_transactions` CHANGE `price` `price` DECIMAL( 10, 2 ) NOT NULL");
	$db->query();
}

$db->setQuery("SELECT * FROM #__rsmembership_configuration WHERE `name` IN ('enable_field_address', 'enable_field_city', 'enable_field_state', 'enable_field_zip', 'enable_field_country')");
$old_fields = $db->loadObjectList();

$db->setQuery("SELECT COUNT(id) FROM #__rsmembership_fields");
$has_fields = $db->loadResult();

if (!empty($old_fields) || !$has_fields)
{
	$db->setQuery("DELETE FROM `#__rsmembership_configuration` WHERE `name` IN ('enable_field_address', 'enable_field_city', 'enable_field_state', 'enable_field_zip', 'enable_field_country');");
	$db->query();
	
	$new_fields = array(
		array('name' => 'address', 'label' => 'address', 'type' => 'textbox', 'values' => ''),
		array('name' => 'city', 'label' => 'City', 'type' => 'textbox', 'values' => ''),
		array('name' => 'state', 'label' => 'State', 'type' => 'textbox', 'values' => ''),
		array('name' => 'zip', 'label' => 'ZIP', 'type' => 'textbox', 'values' => ''),
		array('name' => 'country', 'label' => 'Country', 'type' => 'select', 'values' => "//<code>\r\n\$db = JFactory::getDBO();\r\n\$db->setQuery(\"SELECT name FROM #__rsmembership_countries\");\r\nreturn implode(\"\\n\", \$db->loadResultArray());\r\n//</code>")
	);
	
	foreach ($new_fields as $new_field)
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
		$row =& JTable::getInstance('RSMembership_Fields','Table');
		
		$row->bind($new_field);
		$row->required = 1;
		foreach ($old_fields as $old_field)
			if ($old_field->name == 'enable_field_'.$new_field['name'] && $old_field->value == '0')
				$row->published = 0;
		$row->ordering = $row->getNextOrder();
		
		if ($row->store())
		{
			$db->setQuery("SHOW COLUMNS FROM #__rsmembership_users WHERE `Field` = 'f".$row->id."'");
			if (!$db->loadResult())
			{
				$keyword = "CHANGE `".$new_field['name']."`";
				if (empty($old_fields))
					$keyword = 'ADD';
				$db->setQuery("ALTER TABLE `#__rsmembership_users` $keyword `f".$row->id."` VARCHAR( 255 ) NOT NULL");
				$db->query();
			}
		}
	}
	
	$db->setQuery("ALTER TABLE `#__rsmembership_users` DROP `address`, DROP `city`, DROP `state`, DROP `zip`, DROP `country`");
	$db->query();
}
if ($has_fields)
{
	$db->setQuery("SELECT id, type FROM #__rsmembership_fields");
	$rows = $db->loadObjectList();
	
	foreach ($rows as $row)
	{
		$type = 'VARCHAR(255)';
		if (in_array($row->type, array('freetext', 'textarea')))
			$type = 'TEXT';
			
		$db->setQuery("ALTER TABLE #__rsmembership_users ADD `f".$row->id."` ".$type." NOT NULL");
		$db->query();
	}
}

$db->setQuery("SHOW COLUMNS FROM #__rsmembership_memberships WHERE `Field`='coupon'");
if (!$db->loadResult())
{
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `use_renewal_price` TINYINT( 1 ) NOT NULL AFTER `price`");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `renewal_price` DECIMAL( 10, 2 ) NOT NULL AFTER `use_renewal_price`");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `use_coupon` TINYINT( 1 ) NOT NULL AFTER `renewal_price`");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `coupon` VARCHAR( 64 ) NOT NULL AFTER `use_coupon`");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `coupon_price` DECIMAL( 10, 2 ) NOT NULL AFTER `coupon`");
	$db->query();

	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `use_trial_period` TINYINT( 1 ) NOT NULL AFTER `period_type`");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `trial_period` INT NOT NULL AFTER `use_trial_period`");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `trial_period_type` VARCHAR( 1 ) NOT NULL AFTER `trial_period`");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `trial_price` DECIMAL( 10, 2 ) NOT NULL AFTER `trial_period_type`");
	$db->query();
}

$db->setQuery("SHOW COLUMNS FROM #__rsmembership_transactions WHERE `Field`='coupon'");
if (!$db->loadResult())
{
	$db->setQuery("ALTER TABLE `#__rsmembership_transactions` ADD `coupon` VARCHAR( 64 ) NOT NULL AFTER `price` ;");
	$db->query();
}

// R12
$db->setQuery("SHOW COLUMNS FROM `#__rsmembership_memberships` WHERE `Field`='category_id'");
if (!$db->loadResult())
{
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `category_id` INT NOT NULL DEFAULT 0 AFTER `id`");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD INDEX ( `category_id` )");
	$db->query();
}

// R13
$db->setQuery("SHOW COLUMNS FROM `#__rsmembership_membership_users` WHERE `Field`='notified'");
if (!$db->loadResult())
{
	$db->setQuery("ALTER TABLE `#__rsmembership_membership_users` ADD `notified` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `custom_3`");
	$db->query();
}
$db->setQuery("SHOW COLUMNS FROM `#__rsmembership_memberships` WHERE `Field`='user_email_expire_subject'");
if (!$db->loadResult())
{
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `user_email_expire_subject` VARCHAR( 255 ) NOT NULL AFTER `user_email_addextra_text`, ADD `user_email_expire_text` TEXT NOT NULL AFTER `user_email_expire_subject`, ADD `expire_notify_interval` INT( 3 ) NOT NULL AFTER `user_email_expire_text`");
	$db->query();
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `unique` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `trial_price`, ADD `no_renew` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `unique`");
	$db->query();
}

// R14
$db->setQuery("SHOW COLUMNS FROM `#__rsmembership_memberships` WHERE `Field`='gid_subscribe'");
if (!$db->loadResult())
{
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `gid_subscribe` TINYINT( 3 ) NOT NULL DEFAULT 18 AFTER `custom_code`, ADD `gid_expire` TINYINT( 3 ) NOT NULL DEFAULT 18 AFTER `gid_subscribe`, ADD `disable_expired_account` TINYINT( 1 ) NOT NULL AFTER `gid_expire`, ADD `user_email_approved_subject` VARCHAR( 255 ) NOT NULL AFTER `user_email_new_text`, ADD `user_email_approved_text` TEXT NOT NULL AFTER `user_email_approved_subject`");
	$db->query();
}

// R15
$db->setQuery("SHOW COLUMNS FROM `#__rsmembership_memberships` WHERE `Field`='gid_enable'");
if (!$db->loadResult())
{
	$db->setQuery("ALTER TABLE `#__rsmembership_memberships` ADD `gid_enable` TINYINT( 1 ) NOT NULL AFTER `custom_code`");
	$db->query();
	
	$db->setQuery("UPDATE #__rsmembership_memberships SET `gid_enable`='1' WHERE `gid_subscribe` != 18 OR `gid_expire` != 18");
	$db->query();
}

// END UPDATE PROCEDURE
?>
	<div align="left" width="100%">
 		<img src="../administrator/components/com_rsmembership/assets/images/rsmembership.jpg" alt="RSMembership! Logo"/>
 	</div>
 	<br/>
	<table class="adminform">
		<tr>
			<td align="left">
			<strong>RSMembership! 1.0.0 Component for Joomla!</strong><br/>
			&copy; 2009-2010 by <a href="http://www.rsjoomla.com" target="_blank">http://www.rsjoomla.com</a><br/>
			All rights reserved.
			<br/><br/>
			This Joomla! Component has been released under <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPL</a>.<br/>
			<br/><br/>

			<b>Visit <a href="http://www.rsjoomla.com/" target="_blank">http://www.rsjoomla.com/</a> - for common issues, tech support, user manual, additional downloads and up to date knowledge base articles related to RSMembership!.</b><br/><br/>

			Thank you for using RSMembership!.
			<br/><br/>
			The RSJoomla.com team.
			<br/><br/>
			</td>
		</tr>
	</table><br/>
<br/><br/>
	<div align="left" width="100%"><b>RSMembership! 1.0.0 Installed</b></div>