<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
?>
<?php if (is_array($tags)) : ?>
<ul class="tag-cloud">
	<?php foreach ($tags as $tag) : ?>
		<li><?php echo $tag; ?></li>
	<?php endforeach; ?>		
</ul>
<?php endif; ?>
