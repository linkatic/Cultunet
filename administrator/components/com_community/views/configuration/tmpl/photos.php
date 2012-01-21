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
	<legend><?php echo JText::_( 'CC PHOTO GALLERY' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE PHOTOS' ); ?>::<?php echo JText::_('CC ENABLE PHOTOS TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE PHOTOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enablephotos' , null ,  $this->config->get('enablephotos') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
<!--
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'Photos Path' ); ?>::<?php echo JText::_('Set the path for storing photos'); ?>">
						<?php echo JText::_( 'Path to uploaded photos' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" size="40" name="photospath" value="<?php echo $this->config->get('photospath');?>" />
				</td>
			</tr>
-->
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC PHOTO CREATION LIMIT' ); ?>::<?php echo JText::_('CC PHOTO CREATION LIMIT TIPS'); ?>">
						<?php echo JText::_( 'CC PHOTO CREATION LIMIT' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="photouploadlimit" value="<?php echo $this->config->get('photouploadlimit' );?>" size="10" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC MAXIMUM UPLOAD SIZE' ); ?>::<?php echo JText::_('CC MAXIMUM UPLOAD SIZE TIPS'); ?>">
						<?php echo JText::_( 'CC MAXIMUM UPLOAD SIZE' ); ?>
					</span>
				</td>
				<td valign="top">
					<div><input type="text" size="3" name="maxuploadsize" value="<?php echo $this->config->get('maxuploadsize');?>" /> (MB)</div>
					<div><?php echo JText::sprintf('CC MAXIMUM UPLOAD SIZE DEFINED IN PHP', $this->uploadLimit );?></div>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC DELETE ORIGINAL PHOTOS' ); ?>::<?php echo JText::_('CC DELETE ORIGINAL PHOTOS TIPS'); ?>">
						<?php echo JText::_( 'CC DELETE ORIGINAL PHOTOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'deleteoriginalphotos' , null ,  $this->config->get('deleteoriginalphotos' ) , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC IMAGEMAGICK PATH' ); ?>::<?php echo JText::_('CC IMAGEMAGICK PATH TIPS'); ?>">
						<?php echo JText::_( 'CC IMAGEMAGICK PATH' ); ?>
					</span>
				</td>
				<td valign="top">
					<input name="magickPath" type="text" size="60" value="<?php echo $this->config->get('magickPath');?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC USE FLASH UPLOADER' ); ?>::<?php echo JText::_('CC USE FLASH UPLOADER TIPS'); ?>">
						<?php echo JText::_( 'CC USE FLASH UPLOADER' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'flashuploader' , null ,  $this->config->get('flashuploader') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC AUTO ALBUM COVER' ); ?>::<?php echo JText::_('CC AUTO ALBUM COVER TIPS'); ?>">
						<?php echo JText::_( 'CC AUTO ALBUM COVER' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'autoalbumcover' , null ,  $this->config->get('autoalbumcover' ) , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>