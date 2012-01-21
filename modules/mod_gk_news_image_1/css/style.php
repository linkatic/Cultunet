<?php

/**
* Gavick News Image I
* @package Joomla!
* @Copyright (C) 2008-2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 2.2 $
**/

// access restriction for this file haven't any sense

/*
	This file generate CSS file for specified in $_GET variables module
*/
	
//	$outter_interface,
//  $outter_interface_width,	
	
// set document type as text/javascript	
header("Content-Type: text/css");
//
$suffix = $_GET['modid'];
$text_overlay_bgcolor =  ($_GET['text_block_background'] == 0) ? '#'.$_GET['text_block_bgcolor'] : '#'.$_GET['base_bgcolor'];
$text_block_width = ($_GET['text_block_width'] == 0) ? $_GET['slide_width'] : $_GET['text_block_width'];
$text_block_height = $_GET['module_height'] - (($_GET['thumbnail_bar'] == 1) ? (($_GET['thumbnail_height'] + (2*$_GET['thumbnail_margin']))):0);
$text_block_top = ($_GET['thumbnail_bar_position'] == 0 && $_GET['thumbnail_bar'] == 1) ? $_GET['thumbnail_height'] + (2*($_GET['thumbnail_margin'])) : 0;
$th_height = $_GET['thumbnail_height'] + (2*($_GET['thumbnail_margin'])+(2*($_GET['thumbnail_border'])));

?>

#gk_news_image_1-<?php echo $suffix; ?>{
	overflow: hidden;
	border: none;
	position: relative;
	width: <?php echo $_GET['module_width'] + (($_GET['outter_interface'] == 1) ? $_GET['outter_interface_width'] : 0) + 2 * $_GET['wrapper_border']; ?>px;
	height: <?php echo $_GET['module_height'] + 2 * $_GET['wrapper_border']; ?>px;
	background-color: <?php echo ($_GET['base_bgcolor'] == 'transparent') ? 'transparent' : '#'.$_GET['base_bgcolor']; ?>;
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_main_wrapper{
	overflow: hidden;
	width: <?php echo $_GET['module_width']; ?>px;
	height: <?php echo $_GET['module_height']; ?>px;		
	float: right;
	position: relative;
	<?php if($_GET['wrapper_border'] > 0) : ?>border: <?php echo $_GET['wrapper_border']; ?>px solid #e6e6e6;<?php endif; ?>
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_outter_wrapper{
	overflow: hidden;
	width: <?php echo $outter_interface_width;?>px;
	height: <?php echo $_GET['module_height']; ?>px;
	float: left;
}

#gk_news_image_1-<?php echo $suffix; ?> .gk_news_image_1_slide{
	top: <?php echo $_GET['image_y']; ?>px;
	left: <?php echo $_GET['image_x']; ?>px;
	position: absolute;
	display: block;
} 

#gk_news_image_1-<?php echo $suffix; ?> h2{
	margin-bottom: 15px;
}

#gk_news_image_1-<?php echo $suffix; ?> h2 a{
	font:normal 28px Geneva, Arial, Helvetica, sans-serif;
	text-decoration: none;
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_text_datas{
	display: none;
}

#gk_news_image_1-<?php echo $suffix; ?> .gk_news_image_1_text_bg{
	<?php if($_GET['text_block_position'] == 1) : ?>
	padding-right: 10px;
	padding-left: 10px;
	<?php endif; ?>
	<?php if($_GET['text_block_position'] == 1) : ?>width: <?php echo $text_block_width; ?>px;<?php else : ?>width: <?php echo $_GET['slide_width'];?>px;<?php endif; ?>
	<?php if($_GET['text_block_position'] == 1) : ?>height: <?php echo $_GET['slide_height']; ?>px;<?php else : ?>height: <?php echo $_GET['text_block_height']; ?>px;<?php endif; ?>
	position: absolute;
	<?php if($_GET['text_block_position'] == 1) : ?>top: <?php echo $_GET['image_y']; ?>px;<?php endif; ?>
	<?php if($_GET['text_block_position'] == 0) : ?>bottom: <?php echo $_GET['module_height']-$_GET['slide_height']-$_GET['image_y'];?>px;<?php endif; ?>
	<?php if($_GET['text_block_position'] == 1) : ?>left: <?php echo $_GET['text_block_margin']; ?>px;<?php else : ?>left: <?php echo $_GET['image_x']; ?>px;<?php endif; ?>
	background-color: <?php echo $text_overlay_bgcolor;?>;
	opacity: <?php echo $_GET['text_block_opacity']; ?>;	
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_text{
	<?php if($_GET['text_block_position'] == 1) : ?>padding: 10px;<?php endif; ?>
	overflow: hidden;
	<?php if($_GET['text_block_position'] == 1) : ?>width: <?php echo $text_block_width-10; ?>px;<?php else : ?>width: 90%;margin: 0 5%;<?php endif; ?>
	<?php if($_GET['text_block_position'] == 1) : ?>height: <?php echo $text_block_height-10; ?>px;<?php else : ?>height: <?php echo $_GET['text_block_height']; ?>px;<?php endif; ?>
	<?php if($_GET['text_block_position'] == 1) : ?>left: <?php echo $_GET['text_block_margin']; ?>px;<?php endif; ?>
	position: absolute;
	<?php if($_GET['text_block_position'] == 1) : ?>top: <?php echo $text_block_top; ?>px;<?php else : ?>bottom: 0;<?php endif; ?>
}

#gk_news_image_1-<?php echo $suffix; ?> a.gk_news_image_1_prev,
#gk_news_image_1-<?php echo $suffix; ?> a.gk_news_image_1_next, 
#gk_news_image_1-<?php echo $suffix; ?> a.gk_news_image_1_play, 
#gk_news_image_1-<?php echo $suffix; ?> a.gk_news_image_1_pause{
	background: transparent url('../images/buttons.png') no-repeat;
	display: block;
	float: left;
	width: 21px;
	height: 21px;
	margin-left: 2px;
}

#gk_news_image_1-<?php echo $suffix; ?> a.gk_news_image_1_prev{
	background-position: -21px 0;
}

#gk_news_image_1-<?php echo $suffix; ?> a.gk_news_image_1_play{
	background-position: -42px 0;
}

#gk_news_image_1-<?php echo $suffix; ?> a.gk_news_image_1_pause{
	background-position: -63px 0;
}

#gk_news_image_1-<?php echo $suffix; ?> a:hover.gk_news_image_1_next{
	background-position: 0 100%;
}

#gk_news_image_1-<?php echo $suffix; ?> a:hover.gk_news_image_1_prev{
	background-position: -21px 100%;
}

#gk_news_image_1-<?php echo $suffix; ?> a:hover.gk_news_image_1_play{
	background-position: -42px 100%;
}

#gk_news_image_1-<?php echo $suffix; ?> a:hover.gk_news_image_1_pause{
	background-position: -63px 100%;
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_tb_prev,
#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_tb_next{
	background: #FFF;
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_tb_prev{
	background: transparent url('../images/s_prev.png') no-repeat 0 50%;
	float: left;
	width: 20px;
	height: <?php echo $th_height; ?>px;
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_tb_next{
	background: transparent url('../images/s_next.png') no-repeat 100% 50%;
	float:left;
	width:20px;
	height: <?php echo $th_height; ?>px;
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_tb_prev:hover{
	background: transparent url('../images/s_prev-h.png') no-repeat 0 50%;
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_tb_next:hover{
	background: transparent url('../images/s_next-h.png') no-repeat 100% 50%;
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_tb{
	overflow: hidden;
	float: left;
	height: <?php echo $th_height; ?>px;
	width: <?php echo ($text_block_width-40); ?>px;
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_thumbnails{
	bottom: 100px;
	margin-left: 10px;
	width: <?php echo $text_block_width; ?>px;
	height: <?php echo $th_height; ?>px;
	position: absolute;
	left: <?php echo $_GET['text_block_margin']; ?>px;
	top: <?php echo (($text_block_top == 0) ? $text_block_height - 12 : 0 ); ?>px;
}

#gk_news_image_1-<?php echo $suffix; ?> .gk_news_image_1_thumb{
	margin: <?php echo $_GET['thumbnail_margin']; ?>px;
	border: <?php echo $_GET['thumbnail_border']; ?>px solid #<?php echo $_GET['thumbnail_border_color_inactive'];?>;
	width: <?php echo $_GET['thumbnail_width']; ?>px;
	height: <?php echo $_GET['thumbnail_height']; ?>px;
	float: left;
	display:block;
}

#gk_news_image_1-<?php echo $suffix; ?> .gk_news_image_1_tbo{
	width: <?php echo ((($_GET['slides_count'] * (($_GET['thumbnail_width'] + (2 * $_GET['thumbnail_margin']) + (2 * $_GET['thumbnail_border'])))) + $_GET['slides_count'])); ?>px;
}

#gk_news_image_1-<?php echo $suffix; ?> .gk_news_image_1_interface_buttons{
	position:absolute;
	bottom: <?php echo $_GET['interface_y']; ?>px;
	right: <?php echo $_GET['interface_x']; ?>px;
}

#gk_news_image_1-<?php echo $suffix; ?> ul.gk_news_image_1_tick_buttons{
	position: absolute;
	list-style-type: none;
	top: <?php echo $_GET['tick_y']; ?>px;
	left: <?php echo $_GET['tick_x']; ?>px;
	margin: 0px;
	padding: 0px;
}

#gk_news_image_1-<?php echo $suffix; ?> ul.gk_news_image_1_tick_buttons li{
	float: left;
	padding: 0px !important;
	margin-right: 3px; 
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_preloader{
	position: absolute;
	width: <?php echo $_GET['module_width'] + (($_GET['outter_interface'] == 1) ? $_GET['outter_interface_width'] : 0) + 2 * $_GET['wrapper_border']; ?>px;
	height: <?php echo $_GET['module_height']+ 2 * $_GET['wrapper_border']; ?>px;
	background: url('../images/load.gif') no-repeat center center #000;
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_outter_wrapper ul.gk_news_image_1_tick_buttons{
	position: static;
}

#gk_news_image_1-<?php echo $suffix; ?> ul.gk_news_image_1_tick_buttons li{
	display: block;
	width: 16px;
	height: 16px;
	background: url('../images/tick.png') no-repeat 0 0;
	text-indent: -999em;
	cursor: pointer;
}

#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_outter_wrapper ul.gk_news_image_1_tick_buttons li{
	float: none;
	cursor: pointer;
	margin-bottom: 5px;
}

#gk_news_image_1-<?php echo $suffix; ?> ul.gk_news_image_1_tick_buttons li.active,
#gk_news_image_1-<?php echo $suffix; ?> div.gk_news_image_1_outter_wrapper ul.gk_news_image_1_tick_buttons li.active{
	background: url('../images/tick_active.png') no-repeat 0 0;	
	cursor: pointer;
}

#gk_news_image_1-<?php echo $suffix; ?> div.outter_readmore{
	width: 24px;
	height: 21px;
	position: absolute;
	bottom: 0px;
	background: url('../images/readmore.png') no-repeat 0 0;
	text-indent: -999em;	
	cursor: pointer;	
}