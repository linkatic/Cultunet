<div id="listings"><?php

if( $this->task == "search" && empty($this->links) ) {

	if( empty($this->categories) ) {
	?>
	<div class="error">
		<div class="warning">
			<h2 style="color:red"><strong><?php echo JText::_( 'Your search does not return any result' ) ?></strong></h2>
		</div>
	</div>
	<?php
	}
	
} else {
	?>


	<div class="pages-links">
		<span class="xlistings"><?php echo $this->pageNav->getResultsCounter(); ?></span>
		<?php echo $this->pageNav->getPagesLinks(); ?>
	</div>

	<div class="border-container">
	<?php
	
	$num_items = count($this->links ); 
	foreach ($this->links AS $link) {
		$fields = $this->fields[$link->link_id];
		include $this->loadTemplate('sub_listingSummary.tpl.php');
	}
	
	?>
	<div class="clear">&nbsp;</div>
	</div><!-- Fin boder-container -->
	<?php

	if( $this->pageNav->total > $this->pageNav->limit ) { ?>
	<div class="pages-links">
		<span class="xlistings"><?php echo $this->pageNav->getResultsCounter(); ?></span>
		<?php echo $this->pageNav->getPagesLinks(); ?>
	</div>
	<?php
	}
	
}
?></div>