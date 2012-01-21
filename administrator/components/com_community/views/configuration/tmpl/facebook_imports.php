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
	<legend><?php echo JText::_( 'CC FACEBOOK SETTINGS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC IMPORT FACEBOOK PROFILE ON FIRST SIGNUP' ); ?>::<?php echo JText::_('CC IMPORT FACEBOOK PROFILE ON FIRST SIGNUP TIPS'); ?>">
						<?php echo JText::_( 'CC IMPORT FACEBOOK PROFILE ON FIRST SIGNUP' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'fbsignupimport' , null , $this->config->get( 'fbsignupimport') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ADD FACEBOOK WATERMARKS ON AVATAR' ); ?>::<?php echo JText::_('CC ADD FACEBOOK WATERMARKS ON AVATAR TIPS'); ?>">
						<?php echo JText::_( 'CC ADD FACEBOOK WATERMARKS ON AVATAR' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'fbwatermark' , null , $this->config->get( 'fbwatermark' ) , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FACEBOOK REIMPORT PROFILE ON LOGIN' ); ?>::<?php echo JText::_('CC FACEBOOK REIMPORT PROFILE ON LOGIN TIPS'); ?>">
						<?php echo JText::_( 'CC FACEBOOK REIMPORT PROFILE ON LOGIN' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'fbloginimportprofile' , null , $this->config->get( 'fbloginimportprofile' ) , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FACEBOOK REIMPORT AVATAR ON LOGIN' ); ?>::<?php echo JText::_('CC FACEBOOK REIMPORT AVATAR ON LOGIN TIPS'); ?>">
						<?php echo JText::_( 'CC FACEBOOK REIMPORT AVATAR ON LOGIN' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'fbloginimportavatar' , null , $this->config->get( 'fbloginimportavatar' ) , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FACEBOOK IMPORT USER STATUS' ); ?>::<?php echo JText::_('CC FACEBOOK IMPORT USER STATUS TIPS'); ?>">
						<?php echo JText::_( 'CC FACEBOOK IMPORT USER STATUS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'fbconnectupdatestatus' , null , $this->config->get( 'fbconnectupdatestatus' ) , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC POST FACEBOOK STATUS' ); ?>::<?php echo JText::_('CC POST FACEBOOK STATUS TIPS'); ?>">
						<?php echo JText::_( 'CC POST FACEBOOK STATUS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'fbconnectpoststatus' , null , $this->config->get( 'fbconnectpoststatus' ) , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>