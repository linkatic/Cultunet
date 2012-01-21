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
	<legend><?php echo JText::_( 'CC EMAILS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC HTML EMAILS' ); ?>::<?php echo JText::_('CC HTML EMAILS TIPS'); ?>">
						<?php echo JText::_( 'CC HTML EMAILS' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'htmlemail' , null , $this->config->get('htmlemail') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>