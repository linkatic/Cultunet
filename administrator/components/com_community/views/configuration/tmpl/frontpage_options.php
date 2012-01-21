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
	<legend><?php echo JText::_( 'Frontpage' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FRONTPAGE ACTIVITIES COUNT' ); ?>::<?php echo JText::_('CC FRONTPAGE ACTIVITIES COUNT TIPS'); ?>">
					<?php echo JText::_( 'CC FRONTPAGE ACTIVITIES COUNT' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="maxactivities" value="<?php echo $this->config->get('maxactivities');?>" size="4" /> 
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FRONTPAGE DEFAULT ACTIVITY' ); ?>::<?php echo JText::_('CC FRONTPAGE DEFAULT ACTIVITY TIPS'); ?>">
					<?php echo JText::_( 'CC FRONTPAGE DEFAULT ACTIVITY' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="frontpageactivitydefault">
						<option <?php echo ( $this->config->get('frontpageactivitydefault') == 'all' ) ? 'selected="true"' : ''; ?> value="all"><?php echo JText::_('CC SHOW ALL');?></option>
						<option <?php echo ( $this->config->get('frontpageactivitydefault') == 'friends' ) ? 'selected="true"' : ''; ?> value="friends"><?php echo JText::_('CC USER AND FRIENDS');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FRONTPAGE MEMBERS' ); ?>::<?php echo JText::_('CC FRONTPAGE MEMBERS TIPS'); ?>">
					<?php echo JText::_( 'CC FRONTPAGE MEMBERS' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="frontpageusers" value="<?php echo $this->config->get('frontpageusers' );?>" size="4" /> <?php echo JText::_('CC USERS');?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FRONTPAGE VIDEOS' ); ?>::<?php echo JText::_('CC FRONTPAGE VIDEOS TIPS'); ?>">
					<?php echo JText::_( 'CC FRONTPAGE VIDEOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="frontpagevideos" value="<?php echo $this->config->get('frontpagevideos');?>" size="4" /> <?php echo JText::_('CC VIDEOS');?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FRONTPAGE PHOTOS' ); ?>::<?php echo JText::_('CC FRONTPAGE PHOTOS TIPS'); ?>">
					<?php echo JText::_( 'CC FRONTPAGE PHOTOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="frontpagephotos" value="<?php echo $this->config->get('frontpagephotos' );?>" size="4" /> <?php echo JText::_('CC PHOTOS');?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FRONTPAGE GROUPS' ); ?>::<?php echo JText::_('CC FRONTPAGE GROUPS TIPS'); ?>">
					<?php echo JText::_( 'CC FRONTPAGE GROUPS' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="frontpagegroups" value="<?php echo $this->config->get('frontpagegroups' );?>" size="4" /> <?php echo JText::_('CC GROUPS');?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SHOW SEARCH' ); ?>::<?php echo JText::_('CC SHOW SEARCH TIPS'); ?>">
					<?php echo JText::_( 'CC SHOW SEARCH' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="showsearch">
						<option <?php echo ( $this->config->get('showsearch') == '0' ) ? 'selected="true"' : ''; ?> value="0"><?php echo JText::_('CC HIDE');?></option>
						<option <?php echo ( $this->config->get('showsearch') == '1' ) ? 'selected="true"' : ''; ?> value="1"><?php echo JText::_('CC SHOW');?></option>
						<option <?php echo ( $this->config->get('showsearch') == '2' ) ? 'selected="true"' : ''; ?> value="2"><?php echo JText::_('CC MEMBERS ONLY');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SHOW WHOSE ONLINE' ); ?>::<?php echo JText::_('CC SHOW WHOSE ONLINE TIPS'); ?>">
					<?php echo JText::_( 'CC SHOW WHOSE ONLINE' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="showonline">
						<option <?php echo ( $this->config->get('showonline') == '0' ) ? 'selected="true"' : ''; ?> value="0"><?php echo JText::_('CC HIDE');?></option>
						<option <?php echo ( $this->config->get('showonline') == '1' ) ? 'selected="true"' : ''; ?> value="1"><?php echo JText::_('CC SHOW');?></option>
						<option <?php echo ( $this->config->get('showonline') == '2' ) ? 'selected="true"' : ''; ?> value="2"><?php echo JText::_('CC MEMBERS ONLY');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SHOW LATEST MEMBERS' ); ?>::<?php echo JText::_('CC SHOW LATEST MEMBERS TIPS'); ?>">
					<?php echo JText::_( 'CC SHOW LATEST MEMBERS' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="showlatestmembers">
						<option <?php echo ( $this->config->get('showlatestmembers') == '0' ) ? 'selected="true"' : ''; ?> value="0"><?php echo JText::_('CC HIDE');?></option>
						<option <?php echo ( $this->config->get('showlatestmembers') == '1' ) ? 'selected="true"' : ''; ?> value="1"><?php echo JText::_('CC SHOW');?></option>
						<option <?php echo ( $this->config->get('showlatestmembers') == '2' ) ? 'selected="true"' : ''; ?> value="2"><?php echo JText::_('CC MEMBERS ONLY');?></option>
					</select>
				</td>
			</tr>

			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SHOW ACTIVITY STREAM' ); ?>::<?php echo JText::_('CC SHOW ACTIVITY STREAM TIPS'); ?>">
					<?php echo JText::_( 'CC SHOW ACTIVITY STREAM' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="showactivitystream">
						<option <?php echo ( $this->config->get('showactivitystream') == '0' ) ? 'selected="true"' : ''; ?> value="0"><?php echo JText::_('CC HIDE');?></option>
						<option <?php echo ( $this->config->get('showactivitystream') == '1' ) ? 'selected="true"' : ''; ?> value="1"><?php echo JText::_('CC SHOW');?></option>
						<option <?php echo ( $this->config->get('showactivitystream') == '2' ) ? 'selected="true"' : ''; ?> value="2"><?php echo JText::_('CC MEMBERS ONLY');?></option>
					</select>
				</td>
			</tr>

			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SHOW LATEST VIDEOS' ); ?>::<?php echo JText::_('CC SHOW LATEST VIDEOS TIPS'); ?>">
					<?php echo JText::_( 'CC SHOW LATEST VIDEOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="showlatestvideos">
						<option <?php echo ( $this->config->get('showlatestvideos') == '0' ) ? 'selected="true"' : ''; ?> value="0"><?php echo JText::_('CC HIDE');?></option>
						<option <?php echo ( $this->config->get('showlatestvideos') == '1' ) ? 'selected="true"' : ''; ?> value="1"><?php echo JText::_('CC SHOW');?></option>
						<option <?php echo ( $this->config->get('showlatestvideos') == '2' ) ? 'selected="true"' : ''; ?> value="2"><?php echo JText::_('CC MEMBERS ONLY');?></option>
					</select>
				</td>
			</tr>

			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SHOW LATEST GROUPS' ); ?>::<?php echo JText::_('CC SHOW LATEST GROUPS TIPS'); ?>">
					<?php echo JText::_( 'CC SHOW LATEST GROUPS' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="showlatestgroups">
						<option <?php echo ( $this->config->get('showlatestgroups') == '0' ) ? 'selected="true"' : ''; ?> value="0"><?php echo JText::_('CC HIDE');?></option>
						<option <?php echo ( $this->config->get('showlatestgroups') == '1' ) ? 'selected="true"' : ''; ?> value="1"><?php echo JText::_('CC SHOW');?></option>
						<option <?php echo ( $this->config->get('showlatestgroups') == '2' ) ? 'selected="true"' : ''; ?> value="2"><?php echo JText::_('CC MEMBERS ONLY');?></option>
					</select>
				</td>
			</tr>

			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SHOW LATEST PHOTOS' ); ?>::<?php echo JText::_('CC SHOW LATEST PHOTOS TIPS'); ?>">
					<?php echo JText::_( 'CC SHOW LATEST PHOTOS' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="showlatestphotos">
						<option <?php echo ( $this->config->get('showlatestphotos') == '0' ) ? 'selected="true"' : ''; ?> value="0"><?php echo JText::_('CC HIDE');?></option>
						<option <?php echo ( $this->config->get('showlatestphotos') == '1' ) ? 'selected="true"' : ''; ?> value="1"><?php echo JText::_('CC SHOW');?></option>
						<option <?php echo ( $this->config->get('showlatestphotos') == '2' ) ? 'selected="true"' : ''; ?> value="2"><?php echo JText::_('CC MEMBERS ONLY');?></option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>