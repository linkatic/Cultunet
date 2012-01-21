 
<h2 class="contentheading"><?php echo JText::sprintf( 'SEARCH RESULTS FOR KEYWORD', $this->searchword ) ?></h2>

<?php include $this->loadTemplate( 'sub_subCats.tpl.php' ) ?>

<?php include $this->loadTemplate( 'sub_listings.tpl.php' ) ?>