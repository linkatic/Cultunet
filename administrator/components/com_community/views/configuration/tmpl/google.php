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
	<legend><?php echo JText::_( 'CC GOOGLE MAPS INTEGRATIONS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC STREET FIELD CODE' ); ?>::<?php echo JText::_('CC STREET FIELD CODE TIPS'); ?>">
						<?php echo JText::_( 'CC STREET FIELD CODE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getFieldCodes( 'fieldcodestreet' , $this->config->get('fieldcodestreet') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC CITY FIELD CODE' ); ?>::<?php echo JText::_('CC CITY FIELD CODE TIPS'); ?>">
						<?php echo JText::_( 'CC CITY FIELD CODE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getFieldCodes( 'fieldcodecity' , $this->config->get('fieldcodecity') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC STATE FIELD CODE' ); ?>::<?php echo JText::_('CC STATE FIELD CODE TIPS'); ?>">
						<?php echo JText::_( 'CC STATE FIELD CODE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getFieldCodes( 'fieldcodestate' ,  $this->config->get('fieldcodestate') ); ?>
				</td>
			</tr>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC COUNTRY FIELD CODE' ); ?>::<?php echo JText::_('CC COUNTRY FIELD CODE TIPS'); ?>">
						<?php echo JText::_( 'CC COUNTRY FIELD CODE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getFieldCodes( 'fieldcodecountry' , $this->config->get('fieldcodecountry') ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>