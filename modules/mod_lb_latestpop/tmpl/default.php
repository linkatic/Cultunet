<?php // no direct access
defined('_JEXEC') or die('Restricted access');  ?>
<ul class="lb_latestpop">
	<?php foreach ($list as $item) :  ?>
	<li>
		<a href="<?php echo $item->link; ?>" class="lb_lpImg"><?php echo $item->mainImage; ?></a>
		<a href="<?php echo $item->link; ?>" title="<?php echo $item->text; ?>"><?php echo $item->text; ?></a>
		<small><?php echo ($item->author) ? JText::_('POSTED BY').' '.$item->author.'<br>' : ''; ?><?php echo JText::_('POSTED IN'); ?> <a href="<?php echo $item->cat_url; ?>" class="smallLink"><?php echo $item->cat_title; ?></a></small>
	</li>
	<?php endforeach; ?>
</ul>