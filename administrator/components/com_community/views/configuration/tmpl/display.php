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
	<legend><?php echo JText::_( 'CC DISPLAY SETTINGS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC DISPLAY NAME' ); ?>::<?php echo JText::_('CC DISPLAY NAME TIPS'); ?>">
					<?php echo JText::_( 'CC DISPLAY NAME' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="displayname">
						<?php
							$selectedRealName	= ( $this->config->get('displayname') == 'name' ) ? 'selected="true"' : '';
							$selectedUserName	= ( $this->config->get('displayname') == 'username' ) ? 'selected="true"' : '';
						?>
						<option <?php echo $selectedRealName; ?> value="name"><?php echo JText::_('CC REALNAME');?></option>
						<option <?php echo $selectedUserName; ?> value="username"><?php echo JText::_('CC USERNAME');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ALLOW HTML CODES' ); ?>::<?php echo JText::_('CC ALLOW HTML CODES TIPS'); ?>">
					<?php echo JText::_( 'CC ALLOW HTML CODES' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'allowhtml' , null , $this->config->get('allowhtml') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC USE EDITOR' ); ?>::<?php echo JText::_('CC USE EDITOR TIPS'); ?>">
					<?php echo JText::_( 'CC USE EDITOR' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php
						$editor	= $this->config->get('htmleditor', 'none');
					 	if( $editor == '1' || $editor == '0' )
					 	{
					 		$editor	= 'none';
						}
					?>
					<?php echo JHTML::_('select.genericlist' , $this->getEditors() , 'htmleditor' , null , 'value' , 'text' , $editor );?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SHOW AVATAR IN ACTIVITY' ); ?>::<?php echo JText::_('CC SHOW AVATAR IN ACTIVITY TIPS'); ?>">
					<?php echo JText::_( 'CC SHOW AVATAR IN ACTIVITY' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="showactivityavatar">
						<?php
							$showActivityAvatar	= ( $this->config->get('showactivityavatar') == '1' ) ? 'selected="true"' : '';
							$hideActivityAvatar	= ( $this->config->get('showactivityavatar') == '0' ) ? 'selected="true"' : '';
						?>
						<option <?php echo $showActivityAvatar; ?> value="1"><?php echo JText::_('CC YES');?></option>
						<option <?php echo $hideActivityAvatar; ?> value="0"><?php echo JText::_('CC NO');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SHOW ACTIVITY CONTENTS' ); ?>::<?php echo JText::_('CC SHOW ACTIVITY CONTENTS TIPS'); ?>">
					<?php echo JText::_( 'CC SHOW ACTIVITY CONTENTS' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="showactivitycontent">
						<?php
							$showActivityContent	= ( $this->config->get('showactivitycontent') == '1' ) ? 'selected="true"' : '';
							$hideActivityContent	= ( $this->config->get('showactivitycontent') == '0' ) ? 'selected="true"' : '';
						?>
						<option <?php echo $showActivityContent; ?> value="1"><?php echo JText::_('CC YES');?></option>
						<option <?php echo $hideActivityContent; ?> value="0"><?php echo JText::_('CC NO');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC STREAM CONTENTS LENGTH' ); ?>::<?php echo JText::_('CC STREAM CONTENTS LENGTH TIPS'); ?>">
					<?php echo JText::_( 'CC STREAM CONTENTS LENGTH' ); ?>
					</span>
				</td>
				<td valign="top">       
					<input type="text" name="streamcontentlength" value="<?php echo $this->config->get('streamcontentlength');?>" size="20" /> 
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SINGULAR NUMBER' ); ?>::<?php echo JText::_('CC SINGULAR NUMBER TIPS'); ?>">
					<?php echo JText::_( 'CC SINGULAR NUMBER' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="singularnumber" value="<?php echo $this->config->get('singularnumber');?>" size="20" /> 
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC PROFILE DATE FORMAT' ); ?>::<?php echo JText::_('CC PROFILE DATE FORMAT TIPS'); ?>">
					<?php echo JText::_( 'CC PROFILE DATE FORMAT' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="profileDateFormat" value="<?php echo $this->config->get('profileDateFormat');?>" size="20" />
					<a href="http://dev.mysql.com/doc/refman/5.1/en/date-and-time-functions.html#function_date-format" target="_blank"><?php echo JText::_('CC AVAILABLE FORMATS');?></a> 
				</td>
			</tr>		
		</tbody>
	</table>
</fieldset>