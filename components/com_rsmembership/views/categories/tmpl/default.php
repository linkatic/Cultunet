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

<form action="<?php echo $this->action; ?>" method="post" name="adminForm">
<ul>
<?php $k = 1; ?>
<?php $i = 0; ?>
<?php foreach ($this->items as $item) { ?>
<li class="sectiontableentry<?php echo $k . $this->escape($this->params->get('pageclass_sfx')); ?>" >
	<a href="<?php echo JRoute::_('index.php?option=com_rsmembership&view=rsmembership&catid='.$item->id.':'.JFilterOutput::stringURLSafe($item->name).$this->Itemid); ?>"><?php echo $this->escape($item->name); ?></a><?php if ($this->params->get('show_memberships', 0)) { ?> (<?php echo $item->memberships; ?>)<?php } ?></li>
</li>
<?php $k = $k == 1 ? 2 : 1; ?>
<?php $i++; ?>
<?php } ?>

<?php if ($this->params->get('show_pagination', 1)) { ?>
<div class="sectiontablefooter<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" align="center">
	<?php echo $this->pagination->getPagesLinks(); ?>
	<div><?php echo $this->pagination->getPagesCounter(); ?></div>
</div>
<?php } ?>

<input type="hidden" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
<input type="hidden" name="limitstart" value="<?php echo $this->limitstart; ?>" />
</form>
