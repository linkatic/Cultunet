 
<h2 class="contentheading"><?php 
	if( $this->my->id == $this->owner->id ) {
		echo JText::_( 'My page' ) ?> (<?php echo $this->owner->username ?>)<?php
	} else {
		echo $this->owner->username;
	}
?></h2>
<div class="users-tab">
<div class="users-listings"><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=viewowner&user_id=".$this->owner->id."&Itemid=$this->Itemid") ?>"><?php echo JText::_( 'Listings' ) ?></a>(<?php echo $this->total_links ?>)</div>
<div class="users-reviews-active"><span><?php echo JText::_( 'Reviews' ) ?></span>(<?php echo $this->pageNav->total ?>)</div>
<?php if($this->mtconf['show_favourite']) { ?><div class="users-favourites"><a href="<?php echo JRoute::_("index.php?option=com_mtree&task=viewusersfav&user_id=".$this->owner->id."&Itemid=$this->Itemid") ?>"><?php echo JText::_( 'Favourites' ) ?></a>(<?php echo $this->total_favourites ?>)</div><?php } ?>
</div>
<div class="reviews">
<?php if (is_array($this->reviews) && !empty($this->reviews)) { ?>

	<div class="pages-links">
		<span class="xlistings"><?php echo $this->pageNav->getResultsCounter(); ?></span>
		<?php echo $this->pageNav->getPagesLinks(); ?>
	</div>
	<?php

		foreach ($this->reviews AS $review): 
	?>
	<div class="review">
		<div class="review-listing"><?php $this->plugin('ahref', array("path"=>"index.php?option=".$this->option."&task=viewlink&link_id=".$review->link_id."&Itemid=".$this->Itemid), $review->link_name); ?></div>
		<div class="review-head">
		<div class="review-title"><?php 

		if($review->rating > 0) { ?><div class="review-rating"><?php $this->plugin( 'review_rating', $review->rating ); ?></div><?php }

		$this->plugin('ahref', array("path"=>"index.php?option=".$this->option."&task=viewlink&link_id=".$review->link_id."&Itemid=".$this->Itemid,"fragment"=>"rev-".$review->rev_id), $review->rev_title,'id="rev-'.$review->rev_id.'"'); 
		
		?></div><div class="review-info"><?php 
		echo JText::_( 'Reviewed by' ) ?><span class="review-owner"><?php echo ( ($review->user_id) ? $review->username : $review->guest_name); ?></span>, <?php echo date("F j, Y",strtotime($review->rev_date)) ?>
		</div><?php 
		
		echo '<div id="rhc'.$review->rev_id.'" class="found-helpful"'.( ($review->vote_total==0)?' style="display:none"':'' ).'>';
		echo '<span id="rh'.$review->rev_id.'">';
		if( $review->vote_total > 0 ) { 
			printf( JText::_( 'People find this review helpful' ), $review->vote_helpful, $review->vote_total );
		}
		echo '</span>';
		echo '</div>';
		
		echo '</div>';
		?>
		<div class="review-text">
		<?php 
		if ($review->link_image) {
			echo '<div class="thumbnail">';
			echo '<a href="index.php?option=com_mtree&task=viewlink&link_id=' . $review->link_id . '&Itemid=' . $this->Itemid . '">';
			$this->plugin( 'mt_image', $review->link_image, '3', $review->link_name );
			echo '</a>';
			echo '</div>';
		}
		
		echo $review->rev_text;

		if( !empty($review->ownersreply_text) && $review->ownersreply_approved ) {
			echo '<div class="owners-reply">';
			echo '<span>'.JText::_( 'Owners reply' ).'</span>';
			echo '<p>' . $review->ownersreply_text . '</p>';
			echo '</div>';
		}
		?>
		</div>
	</div>
	<?php
	endforeach; 

	if( $this->pageNav->total > $this->pageNav->limit ) {
		?><div class="pages-counter"><?php echo $this->pageNav->getPagesCounter(); ?></div>
		<div class="pages-links"><?php echo  $this->pageNav->getPagesLinks() ?></div><?php
	}


} else {

	?><center><?php
	if( $this->my->id == $this->owner->id ) {
		echo JText::_( 'You do not have any reviews' );
	} else {
		echo JText::_( 'This user do not have any reviews' );
	}
	?></center><?php
	
}
?></div>