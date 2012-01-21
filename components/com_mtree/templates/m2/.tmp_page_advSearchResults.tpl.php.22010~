<h2 class="contentheading"><?php echo JText::_( 'Advanced search results' ) ?></h2>

<div id="listings">
	<?php if ( !empty($this->links) ) { ?>

	<div class="pages-links">
		<span class="xlistings"><?php echo $this->pageNav->getResultsCounter(); ?></span>
		<?php echo $this->pageNav->getPagesLinks(); ?>
	</div>
	
	<div class="border-container">
	<?php
	
	$num_items = count($this->links ); 
	foreach ($this->links AS $link): 
	$fields = $this->fields[$link->link_id];
	
	include $this->loadTemplate('sub_listingSummary.tpl.php') ?>
	<?php endforeach; ?>


	
	<?php } else { ?>
		<div class="error">
			<div class="warning">
				<h2 style="color:red"><strong><?php echo JText::_( 'Your search does not return any result' ) ?></strong></h2>
			</div>
		</div>
	<?php } ?>
	
		<div class="clear">&nbsp;</div>
	</div><!-- Fin boder-container -->
	
		<?php
	if( $this->pageNav->total > 0 ) { ?>
	<div class="pages-links">
		<span class="xlistings"><?php echo $this->pageNav->getResultsCounter(); ?></span>
		<?php echo $this->pageNav->getPagesLinks(); ?>
	</div>
	<?php }
	?>
	
</div><!-- Fin listings -->
