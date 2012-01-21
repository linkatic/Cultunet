<?php
$numOfColumns = $this->config->getTemParam('numOfColumns',2);
$displayIndexListingCount = $this->config->getTemParam('displayIndexListingCount',1);
$displayIndexCatCount = $this->config->getTemParam('displayIndexCatCount',0);
$numOfSubcatsToDisplay = $this->config->getTemParam('numOfSubcatsToDisplay',3);
?>
 
<div id="index">
<div class="title"><?php echo JText::_( 'Categories' ); ?></div>
<div class="border-container bg-container">
<?php
if( $this->config->getTemParam('displayAlphaIndex','1') ) { $this->display( 'sub_alphaIndex.tpl.php' ); } 

if (is_array($this->categories)): ?>
	<?php 
	$i = 0;
	foreach ($this->categories as $cat): 
		if ( ($i % $numOfColumns) == 0) echo '<div class="row">';
		echo '<div class="category" style="width:' . floor(99/$numOfColumns) . '%">';
		if(!empty($cat->cat_image) && $this->config->getTemParam('displayIndexCatImage','0')) {
			echo '<a href="' . JRoute::_("index.php?option=$this->option&task=listcats&cat_id=$cat->cat_id&Itemid=$this->Itemid") . '">';
			echo '<img src="' . $this->config->getjconf('live_site') . $this->config->get('relative_path_to_cat_small_image') . $cat->cat_image . '" alt="' . htmlspecialchars($cat->cat_name) . '" />';
			echo '</a>';
		}

		?><h2><?php 
		
		$this->plugin('ahref', "index.php?option=$this->option&task=listcats&cat_id=$cat->cat_id&Itemid=$this->Itemid", htmlspecialchars($cat->cat_name) ); 

		if($displayIndexCatCount) {
			$count[]=$cat->cat_cats;
		}
		if($displayIndexListingCount) {
			$count[]=$cat->cat_links;
		}

		if( !empty($count) ) {
			echo '<span> ('.implode('/',$count).')</span>';
			unset($count);
		}
		
		?></h2><?php
		if(!empty($cat->cat_desc) && $this->config->getTemParam('displayCatDesc','0')){
			echo '<div class="desc">' . $cat->cat_desc . '</div>';
		}
		
		if (isset($this->sub_cats) && isset($this->sub_cats[$cat->cat_id]) && count($this->sub_cats[$cat->cat_id]) > 0) {
			$j = 0;
			echo '<div class="subcat">';
			
			foreach ($this->sub_cats[$cat->cat_id] AS $sub_cat): 
				$this->plugin('ahref', "index.php?option=$this->option&task=listcats&cat_id=$sub_cat->cat_id&Itemid=$this->Itemid", htmlspecialchars($sub_cat->cat_name)); 
				$j++;
				if ($this->sub_cats_total[$cat->cat_id] > $j) {
					$lastSubCat = end($this->sub_cats[$cat->cat_id]);
					if ($j >= $numOfSubcatsToDisplay || $lastSubCat->cat_id == $sub_cat->cat_id) {
						echo '...';
					} else {
						echo ', ';
					}
				} elseif($this->sub_cats_total[$cat->cat_id] == $j) {
					// No more sub-categories
				} 
			endforeach; 
			echo '</div>';
		}
		if(isset($this->cat_links) && !empty($this->cat_links[$cat->cat_id])) {
			echo '<ul class="listings">';
			foreach($this->cat_links[$cat->cat_id] AS $cat_link) {
				echo '<li>';
				$this->plugin('ahref', "index.php?option=$this->option&task=viewlink&link_id=$cat_link->link_id&Itemid=$this->Itemid", $cat_link->link_name, 'style="font-weight:normal;font-size:0.9em;text-decoration:none;"');
				echo '</li>';
			}
			echo '</ul>';
		}
		echo '</div>';
		if ( ($i++ % $numOfColumns) == ($numOfColumns-1) || $i == count($this->categories)) echo '</div>';
	endforeach; 
endif;
?>
<div class="clear">&nbsp;</div>
</div><!-- fin border-container -->
</div>
<?php //No permitimos mostrar listados en la categoría raiz
//if( $this->display_listings_in_root ) include $this->loadTemplate( 'sub_listings.tpl.php' ) ?>