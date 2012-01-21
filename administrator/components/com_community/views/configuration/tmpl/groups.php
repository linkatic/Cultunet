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
	<legend><?php echo JText::_( 'Groups' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="350" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE GROUPS' ); ?>::<?php echo JText::_('CC ENABLE GROUPS TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE GROUPS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enablegroups' , null , $this->config->get('enablegroups') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC MODERATE GROUP CREATION' ); ?>::<?php echo JText::_('CC MODERATE GROUP CREATION TIPS'); ?>">
						<?php echo JText::_( 'CC MODERATE GROUP CREATION' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'moderategroupcreation' , null , $this->config->get('moderategroupcreation') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ALLOW GROUP CREATION' ); ?>::<?php echo JText::_('CC ALLOW GROUP CREATION TIPS'); ?>">
						<?php echo JText::_( 'CC ALLOW GROUP CREATION' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'creategroups' , null , $this->config->get('creategroups') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC GROUP CREATION LIMIT' ); ?>::<?php echo JText::_('CC GROUP CREATION LIMIT TIPS'); ?>">
						<?php echo JText::_( 'CC GROUP CREATION LIMIT' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="groupcreatelimit" value="<?php echo $this->config->get('groupcreatelimit' );?>" size="10" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC GROUP PHOTO UPLOAD LIMIT' ); ?>::<?php echo JText::_('CC GROUP PHOTO UPLOAD LIMIT TIPS'); ?>">
						<?php echo JText::_( 'CC GROUP PHOTO UPLOAD LIMIT' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="groupphotouploadlimit" value="<?php echo $this->config->get('groupphotouploadlimit' );?>" size="10" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC GROUP VIDEO UPLOAD LIMIT' ); ?>::<?php echo JText::_('CC GROUP VIDEO UPLOAD LIMIT TIPS'); ?>">
						<?php echo JText::_( 'CC GROUP VIDEO UPLOAD LIMIT' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="groupvideouploadlimit" value="<?php echo $this->config->get('groupvideouploadlimit' );?>" size="10" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CC ENABLE GROUP DISCUSSIONS' ); ?>::<?php echo JText::_('CC ENABLE GROUP DISCUSSIONS TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE GROUP DISCUSSIONS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'creatediscussion' , null , $this->config->get('creatediscussion') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CC ENABLE GROUP PHOTOS' ); ?>::<?php echo JText::_('CC ENABLE GROUP PHOTOS TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE GROUP PHOTOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'groupphotos' , null , $this->config->get('groupphotos') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CC ENABLE GROUP VIDEOS' ); ?>::<?php echo JText::_('CC ENABLE GROUP VIDEOS TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE GROUP VIDEOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'groupvideos' , null , $this->config->get('groupvideos') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CC ENABLE GROUP DISCUSSION NOTIFICATION' ); ?>::<?php echo JText::_('CC ENABLE GROUP DISCUSSION NOTIFICATION TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE GROUP DISCUSSION NOTIFICATION' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'groupdiscussnotification' , null , $this->config->get('groupdiscussnotification') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			
			
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC GROUP MAX DISCUSSION LIST' ); ?>::<?php echo JText::_('CC GROUP MAX DISCUSSION LIST TIPS'); ?>">
						<?php echo JText::_( 'CC GROUP MAX DISCUSSION LIST' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="groupdiscussionmaxlist" value="<?php echo $this->config->get('groupdiscussionmaxlist' );?>" size="10" />
				</td>
			</tr>

		</tbody>
	</table>
</fieldset>