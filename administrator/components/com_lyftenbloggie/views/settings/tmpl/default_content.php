<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<table class="configtable cfgdesc" border="0" cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<th colspan="2"><?php echo JText::_('READ MORE SETTINGS');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('INTROTEXT');?> <span onmouseout="HideHelp('introtext');" onmouseover="ShowHelp('introtext', '<?php echo JText::_('INTROTEXT');?>', '<?php echo JText::_('USE INTRO DESC');?>')" border="0" class="helptp">[?]</span><div style="display:none" id="introtext"></div></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[useIntrotext]', '', $this->settings->get('useIntrotext'), JText::_('SHOW'), JText::_('HIDE')); ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('STRIP OBJECTS');?> <span onmouseout="HideHelp('stripObjects');" onmouseover="ShowHelp('stripObjects', '<?php echo JText::_('STRIP OBJECTS');?>', '<?php echo JText::_('STRIP OBJECTS DESC');?>')" border="0" class="helptp">[?]</span><div style="display:none" id="stripObjects"></div></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[stripObjects]', '', $this->settings->get('stripObjects'), JText::_('REMOVE'), JText::_('LEAVE')); ?></td>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('DEFAULT PARAGRAPHS FOR INTROTEXT');?> <span onmouseout="HideHelp('ReadmorePCount');" onmouseover="ShowHelp('ReadmorePCount', '<?php echo JText::_('DEFAULT PARAGRAPHS FOR INTROTEXT');?>', '<?php echo JText::_('DEFAULT PARAS DESC');?>')" border="0" class="helptp">[?]</span><div style="display:none" id="ReadmorePCount"></div></td>
		<td><input name="settings[autoReadmorePCount]" value="2" class="text_area" size="<?php echo $this->settings->get('autoReadmorePCount'); ?>" type="text"></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('SHOW READ MORE WHEN NECESSARY');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[necessaryReadmore]', '', $this->settings->get('necessaryReadmore'), JText::_('ENABLE'), JText::_('DISABLE')); ?></td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
 </table>