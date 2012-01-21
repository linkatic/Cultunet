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
		<th colspan="2"><?php echo JText::_('FEED SETTINGS');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('BLOG RSS FEEDS');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[useRSSFeed]', '', $this->settings->get('useRSSFeed'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('YOUR BLOG TITLE');?></td>
		<td><input name="settings[title]" value="<?php echo $this->settings->get('title');?>" class="text_area" type="text"></td>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('BLOG DESCRIPTION');?></td>
		<td><textarea name="settings[mainBlogDesc]" cols="30" rows="3" class="text_area"><?php echo $this->settings->get('mainBlogDesc');?></textarea></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('SUMMARIZE FEED');?> <span onmouseout="HideHelp('feedSummarize');" onmouseover="ShowHelp('feedSummarize', '<?php echo JText::_('SUMMARIZE FEED');?>', '<?php echo JText::_('FEED SUMMARIZE DESC');?>')" border="0" class="helptp">[?]</span><div style="display:none" id="feedSummarize"></div></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[feedSummarize]', '', $this->settings->get('feedSummarize'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('FEED LENGTH');?> <span onmouseout="HideHelp('feedLength');" onmouseover="ShowHelp('feedLength', '<?php echo JText::_('FEED LENGTH');?>', '<?php echo JText::_('FEED LENGTH DESC');?>')" border="0" class="helptp">[?]</span><div style="display:none" id="feedLength"></div></td>
		<td><input name="settings[feedLength]" value="<?php echo $this->settings->get('feedLength', 2);?>" class="text_area" type="text"> (<?php echo JText::_('NUMBER OF PARAGRAPHS'); ?>)</td>
	</tr>

	<tr class="row2">
		<td colspan="2"></td>
	</tr>
	<tr>
		<th colspan="2"><?php echo JText::_('FEEDS OFFERED');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('RSS1');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[useRSS1]', '', $this->settings->get('useRSS1'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('ATOM');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[useAtom]', '', $this->settings->get('useAtom'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
 </table>