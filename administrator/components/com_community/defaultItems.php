<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

/**
 * Method to add menu's item.
 *
 * @return boolean true on success false on failure.
 */
function addMenuItems()
{
	// Get new component id.
	$db 	=& JFactory::getDBO();
	
	$query 	= 'SELECT ' . $db->nameQuote( 'id' ) . ' '
			. 'FROM ' . $db->nameQuote( '#__components' ) . ' AS a '
			. 'WHERE a.option=' . $db->Quote( 'com_community' ) . ' '
			. 'AND a.parent=' . $db->Quote( '0');
	$db->setQuery( $query );	
	$newId 	= $db->loadResult();
	
	$query 	= 'SELECT ' . $db->nameQuote( 'ordering' ) . ' '
			. 'FROM ' . $db->nameQuote( '#__menu' ) . ' '
			. 'ORDER BY ' . $db->nameQuote( 'ordering' ) . ' DESC LIMIT 1';
	$db->setQuery( $query );	
	$order 	= $db->loadResult() + 1;
	
	if( !$newId )
		return false;
		
	// Update the existing menu items.
	$query 	= 'INSERT INTO ' . $db->nameQuote( '#__menu' ) 
			. '(' 
				. $db->nameQuote( 'menutype' ) . ', '
				. $db->nameQuote( 'name' ) . ', '
				. $db->nameQuote( 'alias' ) . ', '
				. $db->nameQuote( 'link' ) . ', '
				. $db->nameQuote( 'type' ) . ', '
				. $db->nameQuote( 'published' ) . ', '
				. $db->nameQuote( 'parent' ) . ', '
				. $db->nameQuote( 'componentid' ) . ', '
				. $db->nameQuote( 'sublevel' ) . ', '
				. $db->nameQuote( 'ordering' ) . ' '
			. ') '
			. 'VALUES('
				. $db->quote( 'mainmenu' ) . ', '
				. $db->quote( 'JomSocial' ) . ', '
				. $db->quote( 'jomsocial' ) . ', '
				. $db->quote( 'index.php?option=com_community&view=frontpage' ) . ', '
				. $db->quote( 'component' ) . ', '
				. $db->quote( '1' ) . ', '
				. $db->quote( '0' ) . ', '
				. $db->quote( $newId ) . ', '
				. $db->quote( '0' ) . ', '
				. $db->quote( $order ) . ' '
			. ') ';

	$db->setQuery( $query );
	$db->query();
	
	if($db->getErrorNum())
	{
		return false;
	}	
	return true;
}

function addDefaultCustomFields()
{
	$db		=& JFactory::getDBO();
	$query	= "INSERT INTO `#__community_fields` (`id`, `type`, `ordering`, `published`, `min`, `max`, `name`, `tips`, `visible`, `required`, `searchable`, `options`, `fieldcode`) VALUES
				(1, 'group', 1, 1, 10, 100, 'Basic Information', 'Basic information for user', 1, 1, 1, '', ''),
				(2, 'select', 2, 1, 10, 100, 'Gender', 'Select gender', 1, 1, 1, 'Male\nFemale', 'FIELD_GENDER'),
				(3, 'date', 3, 1, 10, 100, 'Birthday', 'Enter your date of birth so other users can know when to wish you happy birthday ', 1, 1, 1, '', 'FIELD_BIRTHDAY'),
				(4, 'textarea', 5, 1, 1, 800, 'About me', 'Tell us more about yourself', 1, 1, 1, '', 'FIELD_ABOUTME'),
				(5, 'group', 6, 1, 10, 100, 'Contact Information', 'Specify your contact details', 1, 1, 1, '', ''),
				(6, 'text', 7, 1, 10, 100, 'Mobile phone', 'Mobile carrier number that other users can contact you.', 1, 0, 1, '', 'FIELD_MOBILE'),
				(7, 'text', 8, 1, 10, 100, 'Land phone', 'Contact number that other users can contact you.', 1, 0, 1, '', 'FIELD_LANDPHONE'),
				(8, 'textarea', 9, 1, 10, 100, 'Address', 'Your Address', 1, 1, 1, '', 'FIELD_ADDRESS'),
				(9, 'text', 10, 1, 10, 100, 'State', 'Your state', 1, 1, 1, '', 'FIELD_STATE'),
				(10, 'text', 11, 1, 10, 100, 'City / Town', 'Your city or town name', 1, 1, 1, '', 'FIELD_CITY'),
				(11, 'country', 12, 1, 10, 100, 'Country', 'Your country', 1, 1, 1, 'Afghanistan\nAlbania\nAlgeria\nAmerican Samoa\nAndorra\nAngola\nAnguilla\nAntarctica\nAntigua and Barbuda\nArgentina\nArmenia\nAruba\nAustralia\nAustria\nAzerbaijan\nBahamas\nBahrain\nBangladesh\nBarbados\nBelarus\nBelgium\nBelize\nBenin\nBermuda\nBhutan\nBolivia\nBosnia and Herzegovina\nBotswana\nBouvet Island\nBrazil\nBritish Indian Ocean Territory\nBrunei Darussalam\nBulgaria\nBurkina Faso\nBurundi\nCambodia\nCameroon\nCanada\nCape Verde\nCayman Islands\nCentral African Republic\nChad\nChile\nChina\nChristmas Island\nCocos (Keeling) Islands\nColombia\nComoros\nCongo\nCook Islands\nCosta Rica\nCote D''Ivoire (Ivory Coast)\nCroatia (Hrvatska)\nCuba\nCyprus\nCzechoslovakia (former)\nCzech Republic\nDenmark\nDjibouti\nDominica\nDominican Republic\nEast Timor\nEcuador\nEgypt\nEl Salvador\nEquatorial Guinea\nEritrea\nEstonia\nEthiopia\nFalkland Islands (Malvinas)\nFaroe Islands\nFiji\nFinland\nFrance\nFrance, Metropolitan\nFrench Guiana\nFrench Polynesia\nFrench Southern Territories\nGabon\nGambia\nGeorgia\nGermany\nGhana\nGibraltar\nGreat Britain (UK)\nGreece\nGreenland\nGrenada\nGuadeloupe\nGuam\nGuatemala\nGuinea\nGuinea-Bissau\nGuyana\nHaiti\nHeard and McDonald Islands\nHonduras\nHong Kong\nHungary\nIceland\nIndia\nIndonesia\nIran\nIraq\nIreland\nIsrael\nItaly\nJamaica\nJapan\nJordan\nKazakhstan\nKenya\nKiribati\nKorea, North\nSouth Korea\nKuwait\nKyrgyzstan\nLaos\nLatvia\nLebanon\nLesotho\nLiberia\nLibya\nLiechtenstein\nLithuania\nLuxembourg\nMacau\nMacedonia\nMadagascar\nMalawi\nMalaysia\nMaldives\nMali\nMalta\nMarshall Islands\nMartinique\nMauritania\nMauritius\nMayotte\nMexico\nMicronesia\nMoldova\nMonaco\nMongolia\nMontserrat\nMorocco\nMozambique\nMyanmar\nNamibia\nNauru\nNepal\nNetherlands\nNetherlands Antilles\nNeutral Zone\nNew Caledonia\nNew Zealand\nNicaragua\nNiger\nNigeria\nNiue\nNorfolk Island\nNorthern Mariana Islands\nNorway\nOman\nPakistan\nPalau\nPanama\nPapua New Guinea\nParaguay\nPeru\nPhilippines\nPitcairn\nPoland\nPortugal\nPuerto Rico\nQatar\nReunion\nRomania\nRussian Federation\nRwanda\nSaint Kitts and Nevis\nSaint Lucia\nSaint Vincent and the Grenadines\nSamoa\nSan Marino\nSao Tome and Principe\nSaudi Arabia\nSenegal\nSeychelles\nS. Georgia and S. Sandwich Isls.\nSierra Leone\nSingapore\nSlovak Republic\nSlovenia\nSolomon Islands\nSomalia\nSouth Africa\nSpain\nSri Lanka\nSt. Helena\nSt. Pierre and Miquelon\nSudan\nSuriname\nSvalbard and Jan Mayen Islands\nSwaziland\nSweden\nSwitzerland\nSyria\nTaiwan\nTajikistan\nTanzania\nThailand\nTogo\nTokelau\nTonga\nTrinidad and Tobago\nTunisia\nTurkey\nTurkmenistan\nTurks and Caicos Islands\nTuvalu\nUganda\nUkraine\nUnited Arab Emirates\nUnited Kingdom\nUnited States\nUruguay\nUS Minor Outlying Islands\nUSSR (former)\nUzbekistan\nVanuatu\nVatican City State (Holy Sea)\nVenezuela\nViet Nam\nVirgin Islands (British)\nVirgin Islands (U.S.)\nWallis and Futuna Islands\nWestern Sahara\nYemen\nYugoslavia\nZaire\nZambia\nZimbabwe', 'FIELD_COUNTRY'),
				(12, 'url', 13, 1, 10, 100, 'Website', 'Your website', 1, 1, 1, '', 'FIELD_WEBSITE'),
				(13, 'group', 14, 1, 10, 100, 'Education', 'Educations', 1, 1, 1, '', ''),
				(14, 'text', 15, 1, 10, 200, 'College / University', 'Your college or university name', 1, 1, 1, '', 'FIELD_COLLEGE'),
				(15, 'text', 16, 1, 5, 100, 'Graduation Year', 'Graduation year', 1, 1, 1, '', 'FIELD_GRADUATION')";

	$db->setQuery( $query );
	$db->query();
	
	if($db->getErrorNum())
	{
		return false;
	}	
	return true;
}

function addDefaultGroupCategories()
{
	$db 	=& JFactory::getDBO();
	
	$query 	= "INSERT INTO `#__community_groups_category` (`id`, `name`, `description`) VALUES
				(1, 'General', 'General group category.'),
				(2, 'Internet', 'Internet group category.'),
				(3, 'Business', 'Business groups category'),
				(4, 'Automotive', 'Automotive groups category'),
				(5, 'Music', 'Music groups category');";
	
	$db->setQuery( $query );
	
	$db->query();
	
	if($db->getErrorNum())
	{
		return false;
	}	
	return true;
}

function addDefaultVideosCategories()
{
	$db		=& JFactory::getDBO();
	
	$query	= "INSERT INTO ".$db->nameQuote("#__community_videos_category")."(`id`, `name`, `description`, `published` )
				VALUES ( NULL , 'General', 'General video channel', '1' );";
	
	$db->setQuery( $query );
	
	$db->query();
	
	if($db->getErrorNum())
	{
		return false;
	}	
	return true;
}

function addDefaultEventsCategories()
{
	$db		=& JFactory::getDBO();

	$query	= "INSERT INTO ".$db->nameQuote("#__community_events_category")."(`name`, `description` ) VALUES
				( 'General', 'General events'),
				( 'Birthday', 'Birthday events'),
				( 'Party', 'Party events');";

	$db->setQuery( $query );

	$db->query();

	if($db->getErrorNum())
	{
		return false;
	}
	return true;
}

function addDefaultUserPoints()
{
	$db 	=& JFactory::getDBO();

	$query = "INSERT INTO `#__community_userpoints` (`rule_name`, `rule_description`, `rule_plugin`, `action_string`, `component`, `access`, `points`, `published`, `system`) VALUES
				('Add Application', 'Give points when registered user add new application.', 'com_community', 'application.add', '', 1, 0, 0, 1),
				('Remove Application', 'Deduct points when registered user remove application.', 'com_community', 'application.remove', '', 1, 0, 1, 1),
				('Upload Photo', 'Give points when registered user upload photos.', 'com_community', 'photo.upload', '', 1, 0, 1, 1),
				('Add New Group', 'Give points when registered user created new group.', 'com_community', 'group.create', '', 1, 3, 1, 1),
				('Add New Group''s Discussion', 'Give points when registered user created new discussion on group.', 'com_community', 'group.discussion.create', '', 1, 2, 1, 1),
				('Leave Group', 'Deduct points when registered user leave a group.', 'com_community', 'group.leave', '', 1, -1, 1, 1),
				('Approve Friend Request', 'Give points when registered user approved friend request.', 'com_community', 'friends.request.approve', '', 1, 1, 1, 1),
				('Add New Photo Album', 'Give points when registered user added new photo album.', 'com_community', 'album.create', '', 1, 1, 1, 1),
				('Post Group Wall', 'Give points when registered user post wall on group.', 'com_community', 'group.wall.create', '', 1, 1, 1, 1),
				('Join Group', 'Give points when registered user joined a group.', 'com_community', 'group.join', '', 1, 1, 1, 1),
				('Reply Group''s Discussion', 'Give points when registered user replied on group''s discussion.', 'com_community', 'group.discussion.reply', '', 1, 1, 1, 1),
				('Post Wall', 'Give points when registered user post a wall on profile.', 'com_community', 'profile.wall.create', '', 1, 1, 1, 1),
				('Profile Status Update', 'Give points when registered user update their profile status.', 'com_community', 'profile.status.update', '', 1, 1, 1, 1),
				('Profile Update', 'Give points when registered user update their profile.', 'com_community', 'profile.save', '', 1, 1, 1, 1),
				('Update group', 'Give points when registered user update the group.', 'com_community', 'group.updated', '', 1, 1, 1, 1),
				('Upload group avatar', 'Give points when registered user upload avatar to group.', 'com_community', 'group.avatar.upload', '', 1, 0, 1, 1),
				('Create Group''s News', 'Give points when registered user add group''s news.', 'com_community', 'group.news.create', '', 1, 1, 1, 1),
				('Post Wall for photos', 'Give points when registered user post wall at photos.', 'com_community', 'photos.wall.create', '', 1, 1, 1, 1),
				('Remove Friend', 'Deduct points when registered user remove a friend.', 'com_community', 'friends.remove', '', 1, 0, 1, 1),
				('Upload profile avatar', 'Give points when registered user upload profile avatar.', 'com_community', 'profile.avatar.upload', '', 1, 0, 1, 1),
				('Update privacy', 'Give points when registered user updated privacy.', 'com_community', 'profile.privacy.update', '', 1, 0, 1, 1),
				('Reply Messages', 'Give points when registered user reply a message.', 'com_community', 'inbox.message.reply', '', 1, 0, 1, 1),
				('Send Messages', 'Give points when registered user send a message.', 'com_community', 'inbox.message.send', '', 1, 0, 1, 1),
				('Remove Group member', 'Deduct points when registered user remove a group memeber.', 'com_community', 'group.member.remove', '', 1, 0, 1, 1),
				('Delete news', 'Deduct points when registered user remove a news.', 'com_community', 'group.news.remove', '', 1, 0, 1, 1),
				('Remove Wall', 'Deduct points to original poster when registered user remove a wall.', 'com_community', 'wall.remove', '', 1, 0, 1, 1),
				('Remove Photo album', 'Deduct points when registered user remove a photo album.', 'com_community', 'album.remove', '', 1, 0, 1, 1),
				('Remove photos', 'Deduct points when registered user remove a photo.', 'com_community', 'photo.remove', '', 1, 0, 1, 1)";

	$db->setQuery( $query );
	$db->query();
	
	if($db->getErrorNum())
	{
		return false;
	}	
	return true;
}

function menuExist()
{
	$db		=& JFactory::getDBO();
	
	$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__menu' ) . ' '
			. 'WHERE ' . $db->nameQuote( 'link' ) . ' LIKE ' .  $db->Quote( '%option=com_community%');

	$db->setQuery( $query );

	$needUpdate	= ( $db->loadResult() >= 1 ) ? true : false;
	
	return $needUpdate;
}

/**
 * Method to update menu's component id.
 *
 * @return boolean true on success false on failure.
 */
function updateMenuItems()
{
	// Get new component id.
	$db 	=& JFactory::getDBO();
	
	$query 	= 'SELECT ' . $db->nameQuote( 'id' ) . ' '
			. 'FROM ' . $db->nameQuote( '#__components' ) . ' AS a '
			. 'WHERE a.option=' . $db->Quote( 'com_community' ) . ' '
			. 'AND a.parent=' . $db->Quote( '0');
	$db->setQuery( $query );
	
	$newId 	= $db->loadResult();
	
	if( !$newId )
		return false;
		
	// Update the existing menu items.
	$query 	= 'UPDATE ' . $db->nameQuote( '#__menu' ) . ' '
			. 'SET componentid=' . $db->Quote( $newId ) . ' '
			. 'WHERE link LIKE ' . $db->Quote('%option=com_community%');

	$db->setQuery( $query );
	$db->query();
	
	return true;
}

function needsDefaultCustomFields()
{
	$db		=& JFactory::getDBO();
	
	$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_fields' );
	$db->setQuery( $query );

	$needUpdate	= ( $db->loadResult() > 0 ) ? false : true;
	
	return $needUpdate;
}

function needsDefaultGroupCategories()
{
	$db		=& JFactory::getDBO();
	
	$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_groups_category' );
	$db->setQuery( $query );

	$needUpdate	= ( $db->loadResult() > 0 ) ? false : true;
	
	return $needUpdate;
}

function needsDefaultVideosCategories()
{
	$db		=& JFactory::getDBO();
	
	$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_videos_category' );
	$db->setQuery( $query );

	$needUpdate	= ( $db->loadResult() > 0 ) ? false : true;
	
	return $needUpdate;
}

function needsDefaultEventsCategories()
{
	$db		=& JFactory::getDBO();

	$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_events_category' );
	$db->setQuery( $query );

	$needUpdate	= ( $db->loadResult() > 0 ) ? false : true;

	return $needUpdate;
}

function needsDefaultUserPoints()
{
	$db		=& JFactory::getDBO();
	
	$query	= 'SELECT COUNT(*) FROM ' . $db->nameQuote( '#__community_userpoints' );
	$query	.= ' WHERE `system` = 1';
	$db->setQuery( $query );

	$needUpdate	= ( $db->loadResult() > 0 ) ? false : true;
	
	return $needUpdate;
}
?>
