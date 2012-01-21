<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
?>
<div id="recent_blog">
	<?php foreach ($list['data'] as $item) :  ?>
	<div class="blog_excerpt">
		<div class="entryDate" style="background-color:<?php echo $list['color']; ?>">
		<?php echo $item->created; ?>
		</div>
		<p class="entryTitle"><a href="<?php echo $item->link; ?>"><?php echo $item->text; ?></a></p>
		<small><?php echo ($item->author) ? JText::sprintf('POSTED BY IN', $item->author).' ' : JText::_('POSTED IN').' '; ?><a href="<?php echo $item->cat_url; ?>"><?php echo $item->cat_title; ?></a></small>
	</div>
	<?php endforeach; ?>
</div>

