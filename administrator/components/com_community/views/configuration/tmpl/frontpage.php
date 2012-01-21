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
	<legend><?php echo JText::_( 'CC FRONTPAGE' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC FRONTPAGE TITLE' ); ?>::<?php echo JText::_('CC FRONTPAGE TITLE TIPS'); ?>">
					<?php echo JText::_( 'CC FRONTPAGE TITLE' ); ?>
					</span>
				</td>
				<td valign="top">
					<input type="text" name="sitename" value="<?php echo $this->config->get('sitename');?>" size="40" />
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>