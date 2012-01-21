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
		<th colspan="2"><?php echo JText::_('COMMENTS');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('COMMENT SYSTEM');?></td>
		<td><?php echo $this->lists['commentsys']; ?></td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
	<tr>
		<th colspan="2"><?php echo JText::_('LYFTENBLOGGIE COMMENT SYSTEM');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('ENTRY COMMENT LIMIT');?> <span onmouseout="HideHelp('commentEntryLimit');" onmouseover="ShowHelp('commentEntryLimit', '<?php echo JText::_('ENTRY COMMENT LIMIT');?>', '<?php echo JText::_('ENTRY COMMENT LIMIT DESC');?>')" border="0" class="helptp">[?]</span><div style="display:none" id="commentEntryLimit"></div></td>
		<td><input name="settings[commentEntryLimit]" value="<?php echo $this->settings->get('commentEntryLimit', '5');?>" class="text_area" type="text"></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('ANONYMOUS COMMENTING');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[allowAnon]', '', $this->settings->get('allowAnon'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('COMMENT REPORTING');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[allowReport]', '', $this->settings->get('allowReport', 0), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('CAPTCHA');?> <span onmouseout="HideHelp('enableCaptcha');" onmouseover="ShowHelp('enableCaptcha', '<?php echo JText::_('CAPTCHA');?>', '<?php echo JText::_('CAPTCHA DESC');?>')" border="0" class="helptp">[?]</span><div style="display:none" id="enableCaptcha"></div></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[enableCaptcha]', '', $this->settings->get('enableCaptcha'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('BADWORDS FILTER');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[enableBadWord]', '', $this->settings->get('enableBadWord'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('WORDS TO FILTER');?> <span onmouseout="HideHelp('theBadWords');" onmouseover="ShowHelp('theBadWords', '<?php echo JText::_('WORDS TO FILTER');?>', '<?php echo JText::_('BADWORDS DESC');?>')" border="0" class="helptp">[?]</span><div style="display:none" id="theBadWords"></div></td>
		<td><textarea name="settings[theBadWords]" cols="30" rows="3" class="text_area"><?php echo $this->settings->get('theBadWords'); ?></textarea></td>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('REPLASE WITH');?> <span onmouseout="HideHelp('replaceBadWords');" onmouseover="ShowHelp('replaceBadWords', '<?php echo JText::_('REPLASE WITH');?>', '<?php echo JText::_('REPLASE WITH DESC');?>')" border="0" class="helptp">[?]</span><div style="display:none" id="replaceBadWords"></div></td>
		<td><input name="settings[replaceBadWords]" value="<?php echo $this->settings->get('replaceBadWords', '@#$*!');?>" class="text_area" type="text"></td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
	<tr>
		<th colspan="2"><?php echo JText::_('AKISMET SPAM CHECK');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('CHECK FOR SPAM');?> <span onmouseout="HideHelp('spamCheck');" onmouseover="ShowHelp('spamCheck', '<?php echo JText::_('CHECK FOR SPAM');?>', '<?php echo JText::_('CHECK FOR SPAM DESC');?>')" border="0" class="helptp">[?]</span><div style="display:none" id="spamCheck"></div></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[spamCheck]', '', $this->settings->get('spamCheck'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('AKISMET API');?> <span onmouseout="HideHelp('AkismetApi');" onmouseover="ShowHelp('AkismetApi', '<?php echo JText::_('CHECK FOR SPAM');?>', '<?php echo JText::_('AKISMET API DESC');?>')" border="0" class="helptp">[?]</span><div style="display:none" id="AkismetApi"></div></td>
		<td><input name="settings[AkismetApi]" value="<?php echo $this->settings->get('AkismetApi', '');?>" class="text_area" type="text"></td>
	</tr>
	<tr class="row2">
		<td colspan="2" style="background:#FFFFEB;"><small><?php echo JText::_('AKISMET API TIP');?></small></td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
 </table>