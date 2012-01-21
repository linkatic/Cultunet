<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'CC PRIVACY' ); ?></legend>
	<h3><?php echo JText::_( 'CC DEFAULT USER PRIVACY' ); ?></h3>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC PROFILE PRIVACY' ); ?>::<?php echo JText::_('CC PROFILE PRIVACY TIPS'); ?>">
					<?php echo JText::_( 'CC PROFILE PRIVACY' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getPrivacyHTML( 'privacyprofile' , $this->config->get('privacyprofile') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FRIENDS PRIVACY' ); ?>::<?php echo JText::_('CC FRIENDS PRIVACY TIPS'); ?>">
					<?php echo JText::_( 'CC FRIENDS PRIVACY' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getPrivacyHTML( 'privacyfriends' , $this->config->get('privacyfriends') , true ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC PHOTOS PRIVACY' ); ?>::<?php echo JText::_('CC PHOTOS PRIVACY TIPS'); ?>">
					<?php echo JText::_( 'CC PHOTOS PRIVACY' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getPrivacyHTML( 'privacyphotos' , $this->config->get('privacyphotos') , true ); ?>
				</td>
			</tr>
		</tbody>
	</table>

	<h3><?php echo JText::_( 'CC DEFAULT EMAIL NOTIFICATIONS' ); ?></h3>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC RECEIVE SYSTEM MAIL' ); ?>::<?php echo JText::_('CC RECEIVE SYSTEM MAIL TIPS'); ?>">
					<?php echo JText::_( 'CC RECEIVE SYSTEM MAIL' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'privacyemail' , null , $this->config->get('privacyemail') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ALLOW APPLICATIONS' ); ?>::<?php echo JText::_('CC ALLOW APPLICATIONS TIPS'); ?>">
					<?php echo JText::_( 'CC ALLOW APPLICATIONS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'privacyapps' , null , $this->config->get('privacyapps') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC RECEIVE WALL COMMENT NOTIFICATION' ); ?>::<?php echo JText::_('CC RECEIVE WALL COMMENT NOTIFICATION TIPS'); ?>">
					<?php echo JText::_( 'CC RECEIVE WALL COMMENT NOTIFICATION' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'privacywallcomment' , null , $this->config->get('privacywallcomment') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>