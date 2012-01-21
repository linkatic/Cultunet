<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="vote-best" class="border-container">
<?php 

if ($show_header) { 
?><tr><?php
	for( $i=1; $i<=count($order); $i++ ) {
		if ( $i == $order["rank"] )		{ echo '<th width="5%">'.$caption_rank.'</th>'; }
		if ( $i == $order["name"] )		{ echo '<th width="35%">'.JText::_( 'Name' ).'</th>'; }
		if ( $i == $order["category"] )	{ echo '<th width="35%">'.JText::_( 'Category' ).'</th>'; }
		if ( $i == $order["rating"] )	{ echo '<th width="12%">'.JText::_( 'Rating' ).'</th>'; }
		if ( $i == $order["votes"] )	{ echo '<th width="12%">'.JText::_( 'Votes' ).'</th>'; }
	}
?></tr><?php
}

$tabclass = array( 'sectiontableentry1', 'sectiontableentry2' );
$rank = 1;
$k=0;

echo '<ol>';
foreach( $listings AS $l ) {
	echo '<li>';
	// Image
	echo '<a href="' . $l->link . '">';
	echo '<img class="image-left" src="'. $l->image_path . '" alt="' . $l->link_name . '" />';
	echo '</a>';
	
	//Valoración - Estrellas
	
	$star = round($l->link_rating, 0);
	
	echo '<span class="stars">';
	// Print stars
	for( $i=0; $i<$star; $i++) {
		echo '<img src="components/com_mtree/img/star_10.png" width="16" height="16" hspace="1" alt="Star10" />';
	}
	// Print blank star
	for( $i=$star; $i<5; $i++) {
		echo '<img src="components/com_mtree/img/star_00.png" width="16" height="16" hspace="1" alt="Star00" />';
	}
	echo '</span>';
	for( $i=1; $i<=count($order); $i++ ) {
		if ( $i == $order["rank"] )			{ echo '<span class="rank">'.$rank.'. </span>'; }
		if ( $i == $order["name"] ) 		{ echo '<span class="name"><a href="' . $l->link . '">' . $l->trimmed_link_name . '</a></span>'; }
		if ( $i == $order["category"] ) 	{ echo '<br /><span class="category"><small><a href="' . $l->cat_link . '">'. $l->category . '</a></small></span>'; }
		if ( $i == $order["rating"] ) 		{ echo '<br /><span class="rating">'.JText::_( 'Rating' ).': '.$l->link_rating.'</span>'; }
		if ( $i == $order["votes"] ) 		{ echo '<br /><span class="votes">'.JText::_( 'Votes' ).': '.$l->link_votes.'</span>'; }
	}

	echo '<div class="clear">&nbsp;</div>';
	echo '</li>';	
	$rank++;
	$k = 1 - $k;
}
if ( $show_more ) {
	echo '<li class="mas-info"><a href="' . $show_more_link . '">' . $caption_showmore . '</a><img title="Ficha del recurso con información extendida" alt="Ficha del recurso con información extendida" src="templates/gestionyculturatemplate/images/flecha_vermas.png"</li>';	
}
echo '</ol>';

?>
</div>