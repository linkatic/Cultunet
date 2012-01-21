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
		<th colspan="2"><?php echo JText::_('GENERAL');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('DEFAULT EDITOR');?></td>
		<td><?php echo $this->lists['editors']; ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('CURRECT IMAGE URL');?> <span onmouseout="HideHelp('correctImageUrl');" onmouseover="ShowHelp('correctImageUrl', '<?php echo JText::_('CURRECT IMAGE URL');?>', '<?php echo JText::_('CURRECT IMAGE URL DESC');?>')" class="helptp">[?]</span><div style="display:none" id="correctImageUrl"></div></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[correctImageUrl]', '', $this->settings->get('correctImageUrl'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
	<tr>
		<th colspan="2"><?php echo JText::_('TRACKBACKS');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('ALLOW TRACKBACKS');?> <span onmouseout="HideHelp('trackbacks');" onmouseover="ShowHelp('trackbacks', '<?php echo JText::_('ALLOW TRACKBACKS');?>', '<?php echo JText::_('ALLOW TRACKBACK DESC');?>')" class="helptp">[?]</span><div style="display:none" id="trackbacks"></div></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[allowTrackbacks]', '', $this->settings->get('allowTrackbacks'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('TRACKBACKS SPAM FILTER');?> <span onmouseout="HideHelp('filterTrackbacks');" onmouseover="ShowHelp('filterTrackbacks', '<?php echo JText::_('TRACKBACKS SPAM FILTER');?>', '<?php echo JText::_('TRACKBACKS SPAM DESC');?>')" class="helptp">[?]</span><div style="display:none" id="filterTrackbacks"></div></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[filterTrackbacks]', '', $this->settings->get('filterTrackbacks'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
	<tr>
		<th colspan="2"><?php echo JText::_('DISPLAY IMAGE');?> <span onmouseout="HideHelp('display_image');" onmouseover="ShowHelp('display_image', '<?php echo JText::_('DISPLAY IMAGE');?>', '<?php echo JText::_('DISPLAY IMAGE DESC');?>')" class="helptp">[?]</span><div style="display:none" id="display_image"></div></th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('DISPLAY IMAGE WIDTH');?></td>
		<td><input name="settings[display_img_h]" value="<?php echo $this->settings->get('display_img_h', '200');?>" class="text_area" type="text"> (px)</td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('DISPLAY IMAGE HEIGHT');?></td>
		<td><input name="settings[display_img_w]" value="<?php echo $this->settings->get('display_img_w', '200');?>" class="text_area" type="text"> (px)</td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
	<tr>
		<th colspan="2"><?php echo JText::_('UPDATE SERVICES');?> </th>
	</tr>
	<tr class="row1">
		<td class="title"><?php echo JText::_('USE UPDATE SERVICES');?></td>
		<td><?php echo JHTML::_('select.booleanlist', 'settings[useUpdateServices]', '', $this->settings->get('useUpdateServices'), JText::_('ENABLED'), JText::_('DISABLED')); ?></td>
	</tr>
	<tr class="row0">
		<td class="title"><?php echo JText::_('SERVICES');?> <span onmouseout="HideHelp('updateServices');" onmouseover="ShowHelp('updateServices', '<?php echo JText::_('SERVICES');?>', '<?php echo JText::_('UPDATE SERVICES DESC');?>')" class="helptp">[?]</span><div style="display:none" id="updateServices"></div></td>
		<td><textarea name="settings[updateServices]" cols="80" rows="3" class="text_area"><?php echo $this->settings->get('updateServices', 'http://rpc.pingomatic.com/'); ?></textarea></td>
	</tr>
	<tr class="row2">
		<td colspan="2"></td>
	</tr>
 </table>