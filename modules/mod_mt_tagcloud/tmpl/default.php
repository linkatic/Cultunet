<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
if( empty($tags) ) return; 

JHtml::stylesheet('mod_mt_tagcloud.css', 'modules/mod_mt_tagcloud/css/');

?><ol class="tagcloud<?php echo $class_sfx; ?>">
<?php

	foreach( $tags AS $tag )
	{
		echo '<li>';
		echo '<a href="'.$tag->link.'">';
		echo $tag->value;
		echo '</a>';
		echo '</li>';
	}
	
?></ol>