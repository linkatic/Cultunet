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
	<legend><?php echo JText::_( 'CC VIDEOS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE VIDEOS' ); ?>::<?php echo JText::_('CC ENABLE VIDEOS TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE VIDEOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enablevideos' , null ,  $this->config->get('enablevideos') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
                        <tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE PROFILE VIDEO' ); ?>::<?php echo JText::_('CC ENABLE PROFILE VIDEO TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE PROFILE VIDEO' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enableprofilevideo' , null ,  $this->config->get('enableprofilevideo') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE VIDEO UPLOADS' ); ?>::<?php echo JText::_('CC ENABLE VIDEO UPLOADS TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE VIDEO UPLOADS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enablevideosupload' , null ,  $this->config->get('enablevideosupload') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC VIDEO CREATION LIMIT' ); ?>::<?php echo JText::_('CC VIDEO CREATION LIMIT TIPS'); ?>">
						<?php echo JText::_( 'CC VIDEO CREATION LIMIT' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="videouploadlimit" value="<?php echo $this->config->get('videouploadlimit' );?>" size="10" />
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC DELETE ORIGINAL VIDEOS' ); ?>::<?php echo JText::_('CC DELETE ORIGINAL VIDEOS TIPS'); ?>">
						<?php echo JText::_( 'CC DELETE ORIGINAL VIDEOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'deleteoriginalvideos' , null ,  $this->config->get('deleteoriginalvideos') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC VIDEOS ROOT FOLDER' ); ?>::<?php echo JText::_('CC VIDEOS ROOT FOLDER TIPS'); ?>">
						<?php echo JText::_( 'CC VIDEOS ROOT FOLDER' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" size="40" name="videofolder" value="<?php echo $this->config->get('videofolder');?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC MAXIMUM UPLOAD SIZE' ); ?>::<?php echo JText::_('CC MAXIMUM UPLOAD SIZE TIPS'); ?>">
						<?php echo JText::_( 'CC MAXIMUM UPLOAD SIZE' ); ?>
					</span>
				</td>
				<td valign="top">
					<div><input type="text" size="3" name="maxvideouploadsize" value="<?php echo $this->config->get('maxvideouploadsize');?>" /> (MB)</div>
					<div><?php echo JText::sprintf('CC MAXIMUM UPLOAD SIZE DEFINED IN PHP', $this->uploadLimit );?></div>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FFMPEG PATH' ); ?>::<?php echo JText::_('CC FFMPEG PATH TIPS'); ?>">
						<?php echo JText::_( 'CC FFMPEG PATH' ); ?>
					</span>
				</td>
				<td valign="top">
					<input name="ffmpegPath" type="text" size="60" value="<?php echo $this->config->get('ffmpegPath');?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FLVTOOL2 PATH' ); ?>::<?php echo JText::_('CC FLVTOOL2 PATH TIPS'); ?>">
						<?php echo JText::_( 'CC FLVTOOL2 PATH' ); ?>
					</span>
				</td>
				<td valign="top">
					<input name="flvtool2" type="text" size="60" value="<?php echo $this->config->get('flvtool2');?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC VIDEO QUANTIZER SCALE' ); ?>::<?php echo JText::_('CC VIDEO QUANTIZER SCALE TIPS'); ?>">
						<?php echo JText::_( 'CC VIDEO QUANTIZER SCALE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->lists['qscale']; ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC VIDEO SIZE' ); ?>::<?php echo JText::_('CC VIDEO SIZE TIPS'); ?>">
						<?php echo JText::_( 'CC VIDEO SIZE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->lists['videosSize']; ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC CUSTOM COMMAND' ); ?>::<?php echo JText::_('CC CUSTOM COMMAND TIPS'); ?>">
						<?php echo JText::_( 'CC CUSTOM COMMAND' ); ?>
					</span>
				</td>
				<td valign="top">
					<input name="customCommandForVideo" type="text" size="60" value="<?php echo $this->config->get('customCommandForVideo');?>" />
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC PSEUDO STREAMING' ); ?>::<?php echo JText::_('CC PSEUDO STREAMING TIPS'); ?>">
						<?php echo JText::_( 'CC PSEUDO STREAMING' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enablevideopseudostream' , null ,  $this->config->get('enablevideopseudostream') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC VIDEO DEBUGGING' ); ?>::<?php echo JText::_('CC VIDEO DEBUGGING TIPS'); ?>">
						<?php echo JText::_( 'CC VIDEO DEBUGGING' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'videodebug' , null ,  $this->config->get('videodebug') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>