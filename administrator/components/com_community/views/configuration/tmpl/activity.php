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
	<legend><?php echo JText::_( 'CC ACTIVITY' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ACTIVITY PRIVACY' ); ?>::<?php echo JText::_('CC ACTIVITY PRIVACY TIPS'); ?>">
						<?php echo JText::_( 'CC ACTIVITY PRIVACY'); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'respectactivityprivacy' , null , $this->config->get('respectactivityprivacy') , JText::_('CC RESPECT PRIVACY') , JText::_('CC PUBLIC PRIVACY') ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>