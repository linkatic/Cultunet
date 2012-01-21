<?php // no direct access
defined('_JEXEC') or die('Restricted access');
if(is_array($list)) {
?>
<ul class="menu">
<?php foreach ($list as $item) :  ?>
	<li<?php echo $item->current; ?>>
		<a href="<?php echo $item->link; ?>"><span><?php echo $item->author; ?> (<?php echo $item->count; ?>)</span></a>
	</li>
<?php endforeach; ?>
</ul>
<?php } ?>