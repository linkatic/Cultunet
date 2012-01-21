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
	<legend><?php echo JText::_('CC SITE INFORMATION');?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SITE NAME' ); ?>::<?php echo JText::_('CC SITE NAME TIPS'); ?>">
						<?php echo JText::_( 'CC SITE NAME' ); ?>
					</span>
				</td>
				<td><?php echo $this->JSNInfo['network_site_name']; ?></td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SITE URL' ); ?>::<?php echo JText::_('CC SITE URL TIPS'); ?>">
						<?php echo JText::_( 'CC SITE URL' ); ?>
					</span>
				</td>
				<td><a href="<?php echo $this->JSNInfo['network_site_url'] ?>" target="_blank"><?php echo $this->JSNInfo['network_site_url'] ?></a></td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC LANGUAGE' ); ?>::<?php echo JText::_('CC LANGUAGE TIPS'); ?>">
						<?php echo JText::_( 'CC LANGUAGE' ); ?>
					</span>
				</td>
				<td><?php echo $this->JSNInfo['network_language']; ?></td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC MEMBERS COUNT' ); ?>::<?php echo JText::_('CC MEMBERS COUNT TIPS'); ?>">
						<?php echo JText::_( 'CC MEMBERS COUNT' ); ?>
					</span>
				</td>
				<td><?php echo $this->JSNInfo['network_member_count']; ?></td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC GROUPS COUNT' ); ?>::<?php echo JText::_('CC GROUPS COUNT TIPS'); ?>">
						<?php echo JText::_( 'CC GROUPS COUNT' ); ?>
					</span>
				</td>
				<td><?php echo $this->JSNInfo['network_group_count']; ?></td>
			</tr>
		</tbody>
	</table>
</fieldset>