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
	<legend><?php echo JText::_( 'Events' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="350" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE EVENTS' ); ?>::<?php echo JText::_('CC ENABLE EVENT TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE EVENTS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enableevents' , null , $this->config->get('enableevents') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ALLOW EVENTS CREATION' ); ?>::<?php echo JText::_('CC ALLOW EVENTS CREATION TIPS'); ?>">
						<?php echo JText::_( 'CC ALLOW EVENTS CREATION' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'createevents' , null , $this->config->get('createevents') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC EVENT CREATION LIMIT' ); ?>::<?php echo JText::_('CC EVENT CREATION LIMIT TIPS'); ?>">
						<?php echo JText::_( 'CC EVENT CREATION LIMIT' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="eventcreatelimit" value="<?php echo $this->config->get('eventcreatelimit' );?>" size="10" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE ICAL EXPORT' ); ?>::<?php echo JText::_('CC EVENT CREATION LIMIT TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE ICAL EXPORT' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'eventexportical' , null , $this->config->get('eventexportical') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC EVENT SHOW MAP' ); ?>::<?php echo JText::_('CC EVENT SHOW MAP TIPS'); ?>">
						<?php echo JText::_( 'CC EVENT SHOW MAP' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'eventshowmap' , null , $this->config->get('eventshowmap') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			
		</tbody>
	</table>
</fieldset>

<fieldset class="adminform">
	<legend><?php echo JText::_( 'Time format' ); ?></legend>	
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC EVENT LISTING DATE FORMAT' ); ?>::<?php echo JText::_('CC EVENT LISTING DATE FORMAT TIPS'); ?>">
						<?php echo JText::_( 'CC EVENT LISTING DATE FORMAT' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="eventdateformat">
							<option value="%b %d"<?php echo ( $this->config->get('eventdateformat') == '%b %d' ) ? ' selected="true"' : '';?>><?php echo JText::_('CC EVENT MONTH DAY FORMAT');?></option>
							<option value="%d %b"<?php echo ( $this->config->get('eventdateformat') == '%d %b' ) ? ' selected="true"' : '';?>><?php echo JText::_('CC EVENT DAY MONTH FORMAT');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC EVENT TIME FORMAT' ); ?>::<?php echo JText::_('CC EVENT TIME FORMAT TIPS'); ?>">
						<?php echo JText::_( 'CC EVENT TIME FORMAT' ); ?>
					</span>
				</td>
				<td valign="top">
					<select name="eventshowampm">
							<option value="1"<?php echo ( $this->config->get('eventshowampm') == '1' ) ? ' selected="true"' : '';?>><?php echo JText::_('CC EVENT SHOW AMPM');?></option>
							<option value="0"<?php echo ( $this->config->get('eventshowampm') == '0' ) ? ' selected="true"' : '';?>><?php echo JText::_('CC EVENT SHOW 2400');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SHOW EVENT TIMEZONE' ); ?>::<?php echo JText::_('CC SHOW EVENT TIMEZONE TIPS'); ?>">
						<?php echo JText::_( 'CC SHOW EVENT TIMEZONE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'eventshowtimezone' , null , $this->config->get('eventshowtimezone') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			
		</tbody>
	</table>
</fieldset>