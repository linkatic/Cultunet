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
	<legend><?php echo JText::_('CC JSNETWORK CONFIGURATION');?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE JSNETWORK' ); ?>::<?php echo JText::_('CC ENABLE JSNETWORK TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE JSNETWORK' ); ?>
					</span>
				</td>
				<td><?php echo $this->lists['enable']; ?></td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC DESCRIPTION' ); ?>::<?php echo JText::_('CC DESCRIPTION TIPS'); ?>">
						<?php echo JText::_( 'CC DESCRIPTION' ); ?>
					</span>
				</td>
				<td><input type="text" class="inputbox" name="network_description" id="description" size="80" value="<?php echo $this->JSNInfo['network_description']; ?>"></td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC TAGS' ); ?>::<?php echo JText::_('CC TAGS TIPS'); ?>">
						<?php echo JText::_( 'CC TAGS' ); ?>
					</span>
				</td>
				<td><input type="text" class="inputbox" name="network_keywords" id="keywords" size="80" value="<?php echo $this->JSNInfo['network_keywords']; ?>"></td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC JSNETWORK JOIN URL' ); ?>::<?php echo JText::_('CC JSNETWORK JOIN URL TIPS'); ?>">
						<?php echo JText::_( 'CC JSNETWORK JOIN URL' ); ?>
					</span>
				</td>
				<td>
					<input type="text" class="inputbox" name="network_join_url" id="join_url" size="80" value="<?php echo $this->JSNInfo['network_join_url'] ?>">
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC UPDATE INTERVAL' ); ?>::<?php echo JText::_('CC UPDATE INTERVAL TIPS'); ?>">
						<?php echo JText::_( 'CC UPDATE INTERVAL' ); ?>
					</span>
				</td>
				<td><input type="text" class="inputbox" name="network_cron_freq" id="cron_freq" value="<?php echo $this->JSNInfo['network_cron_freq']; ?>"> (<?php echo JText::_('CC HOURS');?>)</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC JSNETWORK UPLOAD LOGO' ); ?>::<?php echo JText::_('CC JSNETWORK UPLOAD LOGO TIPS'); ?>">
						<?php echo JText::_( 'CC JSNETWORK UPLOAD LOGO' ); ?>
					</span>
				</td>
				<td>
					<input class="inputbox" type="file" id="file-upload" name="network_Filedata" style="color: #666;" />
					<input type="checkbox" class="inputbox" name="network_replace_image" id="replace_image" value="1">
					<label for="replace_image"><?php echo JText::_('CC REPLACE IMAGE');?></label>
				</td>
			</tr>
			<?php if( $this->JSNInfo['network_logo_url'] ) { ?>
			<tr>
				<td valign="top"  width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC CURRENT LOGO' ); ?>::<?php echo JText::_('CC CURRENT LOGO TIPS'); ?>">
						<?php echo JText::_( 'CC CURRENT LOGO' ); ?>
					</span>
				</td>
				<td>
					<?php echo JHTML::_('image', $this->JSNInfo['network_logo_url'], '', ''); ?>
					<input type="hidden" name="network_logo_url" value="<?php echo $this->JSNInfo['network_logo_url'] ?>" />
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<input type="hidden" name="network_cron_last_run" value="<?php echo $this->JSNInfo['network_cron_last_run'] ?>" />
</fieldset>