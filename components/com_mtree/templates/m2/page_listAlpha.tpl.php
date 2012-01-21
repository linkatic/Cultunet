 

<?php if( $this->config->getTemParam('displayAlphaIndex','1') ) { $this->display( 'sub_alphaIndex.tpl.php' ); } 

if ( count($this->categories) > 0 || count($this->links) > 0) {

	if ( count($this->categories) > 0 ) { include $this->loadTemplate( 'sub_subCats.tpl.php' ); } 
	
	if (is_array($this->links) && !empty($this->links)) {

		?><div id="listings">
		<div class="title"><?php echo JText::_( 'Listings' ); ?></div>
		<!-- <div class="pages-counter-top"><?php echo $this->pageNav->getPagesCounter(); ?></div> -->
		<div class="pages-links">
			<span class="xlistings"><?php echo $this->pageNav->getResultsCounter(); ?></span>
			<?php echo $this->pageNav->getPagesLinks(); ?>
		</div>
		<?php
		foreach ($this->links AS $link) {
			$fields = $this->fields[$link->link_id];
			include $this->loadTemplate('sub_listingSummary.tpl.php');
		}

		if( $this->pageNav->total > 0 ) { ?>
		<div class="pages-links">
			<span class="xlistings"><?php echo $this->pageNav->getResultsCounter(); ?></span>
			<?php echo $this->pageNav->getPagesLinks(); ?>
		</div>
		<?php }
		
		?></div><?php
	} 
} else {
	?><center><?php echo sprintf(JText::_( 'There are no cat or listings' ), ( (is_numeric($this->alpha)) ? JText::_( 'Number' ) : strtoupper($this->alpha)) )?></center><?php 
}
?>
