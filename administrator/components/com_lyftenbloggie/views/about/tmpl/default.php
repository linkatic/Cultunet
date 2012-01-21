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
<table cellpadding="4" cellspacing="0" border="0" width="100%">
	<tr>
		<td width="100%">
			<img src="<?php echo BLOGGIE_ASSETS_URL . '/images/logo.png'; ?>" style="margin-left:10px;" />
		</td>
	</tr>
	<tr>
		<td>
			<blockquote>
				<p><?php echo JText::_('ABOUT LYFTENBLOGGIE'); ?></p>
				<p><?php echo JText::_('ABOUT LYFTENBLOGGIE VISIT'); ?></p>
				<p>&nbsp;</p>
			</blockquote>
		</td>
	</tr>
	<tr>
		<td>
			<div style="font-weight: 700;">
				<?php echo JText::sprintf( 'CURRENT_VERSION', BLOGGIE_COM_VERSION ); ?>
			</div>
		</td>
	</tr>
</table>