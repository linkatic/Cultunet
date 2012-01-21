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
	<legend><?php echo JText::_( 'CC FACEBOOK API CONFIGURATIONS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="350" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FACEBOOK API KEY' ); ?>::<?php echo JText::_('CC FACEBOOK API KEY TIPS'); ?>">
						<?php echo JText::_( 'CC FACEBOOK API KEY' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="fbconnectkey" value="<?php echo $this->config->get('fbconnectkey' , '' );?>" size="50" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FACEBOOK APPLICATION SECRET' ); ?>::<?php echo JText::_('CC FACEBOOK APPLICATION SECRET TIPS'); ?>">
						<?php echo JText::_( 'CC FACEBOOK APPLICATION SECRET' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="fbconnectsecret" value="<?php echo $this->config->get('fbconnectsecret' , '' );?>" size="50" />
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>