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
	<legend><?php echo JText::_( 'CC STORAGE METHODS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC PHOTOS' ); ?>::<?php echo JText::_('CC PHOTOS STORAGE METHOD TIPS'); ?>">
					<?php echo JText::_( 'CC PHOTOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="photostorage">
						<option <?php echo ( $this->config->get('photostorage') == 'file' ) ? 'selected="true"' : ''; ?> value="file"><?php echo JText::_('CC LOCAL SERVER');?></option>
						<?php if( !COMMUNITY_FREE_VERSION ) { ?>
						<option <?php echo ( $this->config->get('photostorage') == 's3' ) ? 'selected="true"' : ''; ?> value="s3"><?php echo JText::_('CC AMAZONS3');?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC VIDEOS' ); ?>::<?php echo JText::_('CC VIDEOS STORAGE METHOD TIPS'); ?>">
					<?php echo JText::_( 'CC VIDEOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="videostorage">
						<option <?php echo ( $this->config->get('videostorage') == 'file' ) ? 'selected="true"' : ''; ?> value="file"><?php echo JText::_('CC LOCAL SERVER');?></option>
						<?php if( !COMMUNITY_FREE_VERSION ) { ?>
						<option <?php echo ( $this->config->get('videostorage') == 's3' ) ? 'selected="true"' : ''; ?> value="s3"><?php echo JText::_('CC AMAZONS3');?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>