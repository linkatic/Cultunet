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
	<legend><?php echo JText::_('CC FEATURED LIMITS'); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC MAXIMUM FEATURED USERS' ); ?>::<?php echo JText::_('CC MAXIMUM FEATURED USERS TIPS'); ?>">
						<?php echo JText::_( 'CC MAXIMUM FEATURED USERS' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="featureduserslimit" value="<?php echo $this->config->get('featureduserslimit' );?>" size="4" /> <?php echo JText::_('CC USERS');?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC MAXIMUM FEATURED VIDEOS' ); ?>::<?php echo JText::_('CC MAXIMUM FEATURED VIDEOS TIPS'); ?>">
						<?php echo JText::_( 'CC MAXIMUM FEATURED VIDEOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="featuredvideoslimit" value="<?php echo $this->config->get('featuredvideoslimit');?>" size="4" /> <?php echo JText::_('CC VIDEOS');?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC MAXIMUM FEATURED GROUPS' ); ?>::<?php echo JText::_('CC MAXIMUM FEATURED GROUPS TISP'); ?>">
						<?php echo JText::_( 'CC MAXIMUM FEATURED GROUPS' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="featuredgroupslimit" value="<?php echo $this->config->get('featuredgroupslimit' );?>" size="4" /> <?php echo JText::_('CC GROUPS');?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>