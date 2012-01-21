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
<h3><?php echo JText::_('GESTION BLOG'); ?></h3>
<div class="border-container bg-container">
<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
	<div id="lyftenbloggie" class="lyftenbloggie">
		<div class="dashboard">
			<div class="dash_title">
				<h1><?php echo JText::_('MY ENTRIES'); ?> (<?php echo $this->totalentries; ?>)</h1>
				<div class="menu_blog clrfix">
					<ul>
						<li><a href="<?php echo JRoute::_('/'.LyftenBloggieHelperRoute::getAccountRoute()); ?>" class="active"><span><span><?php echo JText::_('ALL ENTRIES'); ?></span></span></a></li>
						<?php if(BloggieFactory::allowAccess('admin.can_approve')) { ?>
						<li><a href="<?php echo JRoute::_('/'.LyftenBloggieHelperRoute::getAccountRoute('pending')); ?>"><span><span><?php echo JText::_('PENDING ENTRIES'); ?></span></span></a></li>
						<?php } ?>
						<li><a href="<?php echo JRoute::_('/'.LyftenBloggieHelperRoute::getAccountRoute('form')); ?>"><span><span><?php echo JText::_('NEW ENTRY'); ?></span></span></a></li>
						<?php /* <li><a href="<?php echo JRoute::_(LyftenBloggieHelperRoute::getAccountRoute('profile')); ?>"><span><span><?php echo JText::_('MY PROFILE'); ?></span></span></a></li> */?>
					</ul>
				</div>
			</div>
			<table class="gridtable" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<?php if($this->photo != 'avatar') echo '<th class="photo">'.JText::_('PHOTO').'</th>'; ?>
						<th class="item_name"><?php echo JText::_('ENTRY NAME'); ?></th>
						<th class="status"><?php echo JText::_('STATUS'); ?></th>
						<th class="category"><?php echo JText::_('CATEGORY'); ?></th>
						<th class="acciones"><?php echo JText::_('QUICK ACTIONS'); ?></th>
					</tr>
				<?php
				$i = 0;
				foreach ($this->entries as $entry)
				{
					$entry = $this->_prepareEntry($entry);
					if ( $entry->state == 1 ) {
						$class = 'published';
					} else if ( $entry->state == -1 ) {
						$class = 'unpublished';
					} else if ( $entry->state == 2 ) {
						$class = 'pending';
					} else if ( $entry->state == 3 ) {
						$class = 'delpending';
					}

					//Get Display Image
					$entry->image = ($this->photo != 'avatar') ? BloggieFactory::getEntryImage($entry->image, $entry->created_by, $entry->modified_by) : '';
				?>
					<tr class="<?php echo ($i % 2) ? 'row1' : 'row0'; ?>">
						<?php if($this->photo != 'avatar') { ?>
						<td><a href="<?php echo JRoute::_( LyftenBloggieHelperRoute::getEntryRoute($entry->archive, $entry->slug) ); ?>" title="<?php echo $entry->title; ?>" class="img_small"><img src="<?php echo $entry->image; ?>" alt=""/></a></td>
						<?php } ?>
						<td class="item_name"><a href="<?php echo JRoute::_( LyftenBloggieHelperRoute::getEntryRoute($entry->archive, $entry->slug) ); ?>" title="<?php echo $entry->title; ?>"><?php echo $entry->title; ?></a></td>
						<td class="status"><span class="<?php echo $class; ?>"><?php echo JText::_('STATUS_'.strtoupper($class)); ?></span></td>
						<td class="category"><?php echo $entry->category; ?></td>
						<td class="acciones">
							<ul>
								<li><a href="<?php echo JRoute::_( LyftenBloggieHelperRoute::getEntryRoute($entry->archive, $entry->slug) ); ?>" class="view" title="<?php echo JText::_('VIEW'); ?>"><?php echo JText::_('VIEW'); ?></a> - </li>
								<?php if($entry->editable) { ?>
								<li><a href="<?php echo $entry->editable; ?>" class="edit" title="<?php echo JText::_('EDIT'); ?>"><span><span><?php echo JText::_('EDIT'); ?></span></span></a> - </li>
								<?php } ?>
								<li><a href="<?php echo JRoute::_('index.php?option=com_lyftenbloggie&view=entry&task=remove&'.JUtility::getToken().'=1'.$entry->archive.'&id='. $entry->id); ?>" onclick="return confirm('<?php echo JText::_('DELETE BLOG ENTRY CONF'); ?>')" class="delete" title="<?php echo JText::_('DELETE'); ?>"><span><span><?php echo JText::_('DELETE'); ?></span></span></a></li>
							</ul>
						</td>
					</tr>
				<?php 
					$i++;
				}
				if(!$i) {
					echo '<tr class="row1"><td colspan="5" style="text-align:center;">'.JText::_('NO ENTRIES FOUND').'</td></tr>';
				}
				?>
				</tbody>
			</table>
			<br style="clear:left;"/>
			<?php if($this->pageNav->limit < $this->pageNav->total) { ?>
			<table style="width:100%;">
				<tr>
					<td valign="top" align="center">
						<?php echo $this->pageNav->getPagesLinks(); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="center">
						<?php echo $this->pageNav->getPagesCounter(); ?>
					</td>
				</tr>
			</table>
			<?php } ?>
			<br style="clear:left;"/>
		</div>
	</div>
</td>
</tr>
</tbody></table>
</div>