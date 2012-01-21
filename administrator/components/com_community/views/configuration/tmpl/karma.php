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
	<legend><?php echo JText::_( 'CC KARMA' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC ENABLE KARMA' ); ?>::<?php echo JText::_('CC ENABLE KARMA TIPS'); ?>">
						<?php echo JText::_( 'CC ENABLE KARMA' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enablekarma' , null ,  $this->config->get('enablekarma') , JText::_('CC YES') , JText::_('CC NO') ); ?>
				</td>
			</tr>		
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC KARMA POINTS SMALLER THAN' ); ?>::<?php echo JText::_('CC KARMA POINTS TIPS'); ?>">
						<?php echo JText::_( 'CC KARMA POINTS SMALLER THAN' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getKarmaHTML( 'point0' , $this->config->get('point0'), true );?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC KARMA POINTS GREATER THAN' ); ?>::<?php echo JText::_('CC KARMA POINTS TIPS'); ?>">
						<?php echo JText::_( 'CC KARMA POINTS GREATER THAN' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getKarmaHTML( 'point1' , $this->config->get('point1') , false, 'point0');?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC KARMA POINTS GREATER THAN' ); ?>::<?php echo JText::_('CC KARMA POINTS TIPS'); ?>">
						<?php echo JText::_( 'CC KARMA POINTS GREATER THAN' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getKarmaHTML( 'point2' , $this->config->get('point2') );?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC KARMA POINTS GREATER THAN' ); ?>::<?php echo JText::_('CC KARMA POINTS TIPS'); ?>">
						<?php echo JText::_( 'CC KARMA POINTS GREATER THAN' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getKarmaHTML( 'point3' , $this->config->get('point3') );?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC KARMA POINTS GREATER THAN' ); ?>::<?php echo JText::_('CC KARMA POINTS TIPS'); ?>">
						<?php echo JText::_( 'CC KARMA POINTS GREATER THAN' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getKarmaHTML( 'point4' , $this->config->get('point4') );?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="hasTip" title="<?php echo JText::_( 'CC KARMA POINTS GREATER THAN' ); ?>::<?php echo JText::_('CC KARMA POINTS TIPS'); ?>">
						<?php echo JText::_( 'CC KARMA POINTS GREATER THAN' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo $this->getKarmaHTML( 'point5' , $this->config->get('point5') );?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>