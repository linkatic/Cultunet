<div id="subCatindex">
<h2 class="contentheading">
	<?php 
	echo '<span class="rss">';
	echo ($this->mtconf['show_category_rss']) ? $this->plugin('showrssfeed','new') : ''; 
	echo '</span>';
	echo htmlspecialchars($this->cat_name); 
	?>
</h2>
	<div class="title">
		<?php  
			if (isset($this->cat_allow_submission) && $this->cat_allow_submission && $this->user_addlisting >= 0) {
				echo $this->plugin("ahref","index.php?option=com_mtree&task=addlisting&cat_id=$this->cat_id&Itemid=$this->Itemid",JText::_( 'Add your listing here' ),'class="add-listing"');
			}
		?>
	</div>
<?php
if ( (isset($this->cat_image) && $this->cat_image <> '') || (isset($this->cat_desc) && $this->cat_desc <> '') ) {
	echo '<div id="cat-desc">';
	if (isset($this->cat_image) && $this->cat_image <> '') {
		echo '<div id="cat-image">';
		$this->plugin( 'image', $this->config->getjconf('live_site').$this->config->get('relative_path_to_cat_small_image') . $this->cat_image , $this->cat_name, '', '', '' );
		echo '</div>';
	}
	if ( isset($this->cat_desc) && $this->cat_desc <> '') {	echo $this->cat_desc; }
	echo '</div>';
}
?>

<?php include $this->loadTemplate( 'sub_subCats.tpl.php' ) ?>

	<?php if( $this->cat_show_listings ) include $this->loadTemplate( 'sub_listings.tpl.php' ) ?>
</div><!-- Fin subCatindex -->