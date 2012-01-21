<?php
/**
 * @copyright	Copyright (C) 2009-2010 ACYBA SARL - All rights reserved.
 * @license		http://www.acyba.com/commercial_license.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'FE_EDITION' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tr>
			<td class="key" >
			<?php echo acymailing::tooltip(JText::_('DEFAULT_SENDER_DESC'), JText::_('DEFAULT_SENDER'), '', JText::_('DEFAULT_SENDER')); ?>
			</td>
			<td>
				<?php echo JHTML::_('select.booleanlist', "config[frontend_sender]" , '',$this->config->get('frontend_sender',0) ); ?>
			</td>
		</tr>
		<tr>
			<td class="key" >
			<?php echo acymailing::tooltip(JText::_('DEFAULT_REPLY_DESC'), JText::_('DEFAULT_REPLY'), '', JText::_('DEFAULT_REPLY')); ?>
			</td>
			<td>
				<?php echo JHTML::_('select.booleanlist', "config[frontend_reply]" , '',$this->config->get('frontend_reply',0) ); ?>
			</td>
		</tr>
		<tr>
			<td class="key" >
			<?php echo acymailing::tooltip(JText::_('FE_MODIFICATION_DESC'), JText::_('FE_MODIFICATION'), '', JText::_('FE_MODIFICATION')); ?>
			</td>
			<td>
				<?php echo JHTML::_('select.booleanlist', "config[frontend_modif]" , '',$this->config->get('frontend_modif',1)); ?>
			</td>
		</tr>
		<tr>
			<td class="key" >
			<?php echo acymailing::tooltip(JText::_('FE_MODIFICATION_SENT_DESC'), JText::_('FE_MODIFICATION_SENT'), '', JText::_('FE_MODIFICATION_SENT')); ?>
			</td>
			<td>
				<?php echo JHTML::_('select.booleanlist', "config[frontend_modif_sent]" , '',$this->config->get('frontend_modif_sent',1)); ?>
			</td>
		</tr>
	</table>
</fieldset>