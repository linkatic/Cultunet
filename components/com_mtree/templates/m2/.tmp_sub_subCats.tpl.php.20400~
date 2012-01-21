<?php if (isset($this->categories) && is_array($this->categories) && !empty($this->categories)) { ?>
<div id="subcats">
<div class="title"><?php echo JText::_( 'Categories' ); ?></div>
<?php 
	$i = 0;
	
	#
	# Sub Categories
	#

	echo '<ul>';
	foreach ($this->categories as $cat) {
		if($this->task == 'listalpha' && $this->config->getTemParam('onlyShowRootLevelCatInListalpha',0) && $cat->cat_parent > 0) {
			continue;
		}
		echo '<li>';
		if($cat->cat_featured) echo '<strong>';
		$this->plugin('ahref', "index.php?option=$this->option&task=listcats&cat_id=$cat->cat_id&Itemid=$this->Itemid", htmlspecialchars($cat->cat_name), '' );
		
		if( $this->config->getTemParam('displaySubcatsCatCount','0') ) {
			$count[] = $cat->cat_cats;
		}
		if( $this->config->getTemParam('displaySubcatsListingCount','1') ) {
			$count[] = $cat->cat_links;
		}
		if( !empty($count) ) {
			echo ' <small>('.implode('/',$count).')</small>';
			unset($count);
		}
		if($cat->cat_featured) echo '</strong>';
		echo '</li>';
	}
	echo '</ul>';
?></div><?php 
}

	#
	# Related Categories
	#
	if ( isset($this->related_categories) && count($this->related_categories) > 0 ) {
		echo '<div id="relcats">';
		?><div class="title"><?php echo JText::_( 'Related categories' ); ?></div><?php
		echo '<ul>';
		foreach( $this->related_categories AS $related_category ) {
			echo '<li>';
			$this->plugin('ahref', "index.php?option=com_mtree&task=listcats&cat_id=".$related_category."&Itemid=$this->Itemid", $this->pathway->printPathWayFromCat_withCurrentCat( $related_category )); 
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';
	}
	?>