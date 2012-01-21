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
	<legend><?php echo JText::_( 'CC MESSAGING' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE MESSAGING' ); ?>::<?php echo JText::_('CC ENABLE MESSAGING TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE MESSAGING' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enablepm' , null , $this->config->get('enablepm') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC LIMIT NUMBER OF NEW MESSAGES' ); ?>::<?php echo JText::_('CC LIMIT NUMBER OF NEW MESSAGES TIPS'); ?>">
						<?php echo JText::_( 'CC LIMIT NUMBER OF NEW MESSAGES' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="pmperday" value="<?php echo $this->config->get('pmperday');?>" size="4" /> <?php echo JText::_('CC PER DAY');?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>