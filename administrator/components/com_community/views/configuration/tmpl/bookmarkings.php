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
	<legend><?php echo JText::_( 'CC SOCIAL BOOKMARKING' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC SOCIAL BOOKMARKING' ); ?>::<?php echo JText::_('CC SOCIAL BOOKMARKING TIPS'); ?>">
					<?php echo JText::_( 'CC SOCIAL BOOKMARKING' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enablesharethis' , null , $this->config->get('enablesharethis') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>