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

<?php foreach ($this->items as $item) {
$catid = $item->category_id ? '&catid='.$item->category_id.':'.JFilterOutput::stringURLSafe($item->category_name) : '';
$link = JRoute::_('index.php?option=com_rsmembership&view=membership'.$catid.'&cid='.$item->id.':'.JFilterOutput::stringURLSafe($item->name).$this->Itemid);
$price = RSMembershipHelper::getPriceFormat($item->price);
$image = !empty($item->thumb) ? JHTML::_('image', JURI::root().'components/com_rsmembership/assets/thumbs/'.$item->thumb, $item->name, 'class="rsm_thumb'.$this->escape($this->params->get('pageclass_sfx')).'"') : '';

$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
$tagPos	= preg_match($pattern, $item->description);
if ($tagPos == 0)
	$description = $item->description;
else
	list($description, $fulldescription) = preg_split($pattern, $item->description, 2);

$replace = array('{price}', '{buy}', '{extras}');
$with = array($price, '<a href="'.$link.'">'.JText::_('RSM_SUBSCRIBE').'</a>', '');
$description = str_replace($replace, $with, $description);
?>
<div class="rsm_container<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
<h2 class="rsm_title contentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php if ($this->params->get('show_category', 0)) { ?><?php echo $item->category_id ? $item->category_name : JText::_('RSM_NO_CATEGORY'); ?> - <?php } ?><a href="<?php echo $link; ?>"><?php echo $item->name; ?></a> - <?php echo $price; ?></h2>
<?php echo $image; ?>
<?php echo $description; ?>
<a href="<?php echo $link; ?>" class="readon"><?php echo JText::_('RSM_DETAILS'); ?></a>
</div>

<span class="rsm_clear"></span>
<?php } ?>

<?php if ($this->params->get('show_pagination', 1)) { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
<tr>
	<td align="center" colspan="4" class="sectiontablefooter<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->pagination->getPagesLinks(); ?></td>
</tr>
<tr>
	<td colspan="5" align="right"><?php echo $this->pagination->getPagesCounter(); ?></td>
</tr>
</table>
<?php } ?>