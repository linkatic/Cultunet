<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<?php if ($this->params->get('show_page_title', 1)) { ?>
<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php } ?>
<div class="greyblock">
<form method="post" action="<?php echo JRoute::_('index.php?option=com_rsmembership&task=upgrade&cid='.$this->membership->id); ?>" name="membershipForm">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_MEMBERSHIP'); ?>:</td>
	<td><?php echo $this->membership->name; ?>
	<?php if ($this->has_upgrades && ($this->membership->status == 0 || $this->membership->status == 2)) { ?>
		<?php echo $this->lists['upgrades']; ?> <input type="submit" name="submit" class="button" value="<?php echo JText::_('RSM_UPGRADE'); ?>" />
	<?php } ?>
	</td>
</tr>
<?php if (!empty($this->boughtextras)) { ?>
<tr>
	<td></td>
	<td><?php echo implode(', ', $this->boughtextras); ?></td>
</tr>
<?php } ?>
<?php if (!empty($this->extras)) { ?>
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_MEMBERSHIP_EXTRA'); ?>:</td>
	<td>
	<?php foreach ($this->extras as $extra) { ?>
		<p><?php echo $extra->name; ?> <a href="<?php echo JRoute::_('index.php?option=com_rsmembership&task=addextra&cid='.$this->membership->id.':'.JFilterOutput::stringURLSafe($this->membership->name).'&extra_id='.$extra->id); ?>"><?php echo JText::_('RSM_PURCHASE_EXTRA'); ?></a></p><?php } ?>
	</td>
</tr>
<?php } ?>
<?php if ($this->params->get('show_price', 1)) { ?>
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_PRICE'); ?>:</td>
	<td><?php echo RSMembershipHelper::getPriceFormat($this->membership->price); ?></td>
</tr>
<?php } ?>
<?php if ($this->params->get('show_status', 1)) { ?>
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_STATUS'); ?>:</td>
	<td><strong><?php echo JText::_('RSM_STATUS_'.$this->membership->status); ?></strong> <?php if ($this->membership->status > 1 && !$this->membership->no_renew) { ?><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&task=renew&cid='.$this->membership->id.':'.JFilterOutput::stringURLSafe($this->membership->name)) ?>"><?php echo JText::_('RSM_RENEW'); ?></a><?php } ?></td>
</tr>
<?php } ?>
<?php if ($this->params->get('show_expire', 1)) { ?>
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_MEMBERSHIP_START'); ?>:</td>
	<td><?php echo date($this->date_format, $this->membership->membership_start); ?></td>
</tr>
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_MEMBERSHIP_END'); ?>:</td>
	<td><?php echo $this->membership->membership_end > 0 ? date($this->date_format, $this->membership->membership_end) : JText::_('RSM_UNLIMITED'); ?></td>
</tr>
<?php 
foreach($this->books as $book) {?>
<tr>
	<td colspan="2">
		<img width="32" height="32" border="0" align="middle" alt="Pinche en este enlace para ver la revista" src="http://www.cultunet.com/components/com_virtuemart/shop_image/ps_image/goto.png">
		<?php 
			$link = JRoute::_('index.php?option=com_flippingbook&view=book&id='.$book['book_id']); 
			echo '<a href="'.$link.'">'.JText::_('VER_REVISTA').'</a>'.' ('.$book['title'].')';
		?>	
	</td>
</tr>
<?php } ?>
<?php } ?>
<?php if (!empty($this->membershipterms->id)) { ?>
<tr>
	<td width="30%" height="40"><?php echo JText::_('RSM_TERM'); ?>:</td>
	<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=terms&cid='.$this->membershipterms->id.':'.JFilterOutput::stringURLSafe($this->membershipterms->name)); ?>"><?php echo $this->membershipterms->name; ?></a></td>
</tr>
<?php } ?>
</table>

<?php if ($this->membership->status > 0) { ?>
<p><?php echo JText::sprintf('RSM_NOT_ACTIVE', JText::_('RSM_STATUS_'.$this->membership->status)); ?></p>
<?php } ?>

<?php if ($this->previous !== false || !empty($this->folders) || !empty($this->files)) { ?>
<p><?php echo JText::_('RSM_FILES_AVAILABLE'); ?></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php if ($this->params->get('show_headings', 1)) { ?>
<tr>
 	<td width="1%" class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">&nbsp;</td>
 	<td class="sectiontableheader<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo JText::_('RSM_NAME'); ?></td>
</tr>
<?php } ?>
<?php if ($this->previous !== false) { ?>
<tr class="sectiontableentry1<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" >
	<td align="center" valign="top"><?php echo JHTML::_('image', JURI::root().'components/com_rsmembership/assets/images/folder.gif', JText::_('RSM_FILE')); ?></td>
	<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=mymembership&cid='.$this->cid.($this->previous != '' ? '&path='.$this->previous.'&from='.$this->from : '')); ?>">..</a></td>
</tr>
<?php } ?>
<?php foreach ($this->folders as $folder) { ?>
<?php $image = !empty($folder->thumb) ? JURI::root().'components/com_rsmembership/assets/thumbs/files/'.$folder->thumb : JURI::root().'components/com_rsmembership/assets/images/folder.gif'; ?>
<tr class="sectiontableentry1<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" >
	<td align="center" valign="top"><?php echo JHTML::_('image', $image, JText::_('RSM_FILE')); ?></td>
	<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=mymembership&cid='.$this->cid.'&path='.$folder->fullpath.'&from='.$folder->from); ?>"><?php echo !empty($folder->name) ? $folder->name : $folder->fullpath; ?></a><?php if (!empty($folder->description)) { ?><p><?php echo $folder->description; ?></p><?php } ?></td>
</tr>
<?php } ?>
<?php foreach ($this->files as $file) { ?>
<?php $image = !empty($file->thumb) ? JURI::root().'components/com_rsmembership/assets/thumbs/files/'.$file->thumb : JURI::root().'components/com_rsmembership/assets/images/file.gif'; ?>
<tr class="sectiontableentry1<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" >
	<td align="center" valign="top"><?php echo JHTML::_('image', $image, JText::_('RSM_FILE')); ?></td>
	<td><a href="<?php echo JRoute::_('index.php?option=com_rsmembership&task=download&cid='.$this->cid.'&path='.$file->fullpath.'&from='.$file->from); ?>"><?php echo !empty($file->name) ? $file->name : $file->fullpath; ?></a><?php if (!empty($file->description)) { ?><p><?php echo $file->description; ?></p><?php } ?></td>
</tr>
<?php } ?>
</table>
<?php } ?>

<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="option" value="com_rsmembership" />
<input type="hidden" name="view" value="mymembership" />
<input type="hidden" name="task" value="upgrade" />
<input type="hidden" name="cid" value="<?php echo $this->membership->id; ?>" />
</form>
</div>