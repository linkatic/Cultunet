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
	<legend><?php echo JText::_( 'CC REPORTINGS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE REPORTING' ); ?>::<?php echo JText::_('CC ENABLE REPORTING TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE REPORTING' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enablereporting' , null , $this->config->get('enablereporting') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC EXECUTE DEFAULT REPORTING TASK' ); ?>::<?php echo JText::_('CC EXECUTE DEFAULT REPORTING TASK TIPS'); ?>">
						<?php echo JText::_( 'CC EXECUTE DEFAULT REPORTING TASK' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="maxReport" style="text-align: center;" value="<?php echo $this->config->get('maxReport'); ?>" size="5" />
					<?php echo JText::_('CC REPORTS');?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SEND REPORT NOTIFICATION EMAIL TO' ); ?>::<?php echo JText::_('CC SEND REPORT NOTIFICATION EMAIL TO TIPS'); ?>">
						<?php echo JText::_( 'CC SEND REPORT NOTIFICATION EMAIL TO' ); ?>
					</span>
				</td>
				<td valign="top">
					<div><input type="text" name="notifyMaxReport" value="<?php echo $this->config->get('notifyMaxReport'); ?>" size="45" /></div>
					<?php echo JText::_('CC COMMA SEPARATED');?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ALLOW GUESTS TO REPORT' ); ?>::<?php echo JText::_('CC ALLOW GUESTS TO REPORT TIPS'); ?>">
						<?php echo JText::_( 'CC ALLOW GUESTS TO REPORT' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enableguestreporting' , null , $this->config->get('enableguestreporting') , JText::_('Allow') , JText::_('Disallow') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC PREDEFINED TEXT' ); ?>::<?php echo JText::_('CC PREDEFINED TEXT TIPS'); ?>">
					<?php echo JText::_( 'CC PREDEFINED TEXT' ); ?>
					</span>
				</td>
				<td valign="top">
					<textarea name="predefinedreports" cols="30" rows="5"><?php echo $this->config->get('predefinedreports');?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>