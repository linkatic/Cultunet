<?php defined('_JEXEC') or die('Restricted access'); ?>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
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
foreach( $listings AS $l ) {

	if ( $use_alternating_bg ) {
		echo '<tr class="'.$tabclass[$k].'">';
	}	else {
		echo '<tr>';
	}

	for( $i=1; $i<=count($order); $i++ ) {
		if ( $i == $order["rank"] )			{ echo "<td>$rank</td>"; }
		if ( $i == $order["name"] ) 		{ echo '<td nowrap><a href="' . $l->link . '">' . $l->trimmed_link_name . '</a></td>'; }
		if ( $i == $order["category"] ) 	{ echo '<td nowrap><a href="' . $l->cat_link . '">'. $l->category . '</a></td>'; }
		if ( $i == $order["rating"] ) 		{ echo "<td>$l->link_rating</td>"; }
		if ( $i == $order["votes"] ) 		{ echo "<td>$l->link_votes</td>"; }
	}
	echo '</tr>';	
	$rank++;
	$k = 1 - $k;
}

if ( $show_more ) {
	echo '<tr><td colspan="4"><a href="' . $show_more_link . '">' . $caption_showmore . '</a></td></tr>';	
}

?></table>