<?php if ( 
	in_array($this->config->getTemParam('listingDetailsImagesOutputMode','1'),array(1,2)) 
	&& 
	is_array($this->images) 
	&& 
	!empty($this->images)
	&&
	(
		($this->config->getTemParam('skipFirstImage','0') == 1 && count($this->images) > 1)
		||
		$this->config->getTemParam('skipFirstImage','0') == 0
	)
	): ?>
<div class="images">
	<div class="content"><?php
		$i = 0;
		$maxImages = $this->config->getTemParam('MaxNumOfImages','6');
		foreach ($this->images AS $image): 
			
 			if ($this->config->getTemParam('skipFirstImage','0') == 1 && $i == 0) {
				$i++;
				$maxImages++;
				continue;
			}
			
			if ($this->config->getTemParam('listingDetailsImagesOutputMode','1') == 2 && $i >= $maxImages) {
				break;
			}
		?>
		<div class="thumbnail-left"><a href="<?php echo JRoute::_('index.php?option=com_mtree&task=viewimage&img_id=' . $image->id . '&Itemid=' . $this->Itemid); ?>"><img src="<?php 
		echo $this->jconf['live_site'] . $this->mtconf['relative_path_to_listing_small_image'] . $image->filename;
	 ?>" img="" /></a></div><?php 
			$i++;
		endforeach; 
		
		if ($this->config->getTemParam('listingDetailsImagesOutputMode','1') == 2 && $this->total_images > $maxImages):
		?>
		<div class="more"><a href="<?php echo JRoute::_('index.php?option=com_mtree&task=viewgallery&link_id=' . $this->link->link_id . '&Itemid=' . $this->Itemid); ?>"><?php echo JText::_( 'More images...' ); ?></a></div>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>