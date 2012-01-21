<?php

/**
* Gavick Image Slide I
* @package Joomla!
* @Copyright (C) 2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.0 $
**/

// access restriction
defined('_JEXEC') or die('Restricted access');
// vars
$highest_layer = 0;
// initializing variables
$image_parsed_paddings = array( "top" => 0, "right" => 0, "bottom" => 0, "left" => 0 );
$image_parsed_margins = array( "top" => 0, "right" => 0, "bottom" => 0, "left" => 0 );
$thumbs_parsed_paddings = array( "top" => 0, "right" => 0, "bottom" => 0, "left" => 0 );
$thumbs_parsed_margins = array( "top" => 0, "right" => 0, "bottom" => 0, "left" => 0 );
$total_block_width = 0;
$total_thumbs_width = 0;
$URI = JURI::getInstance();
// parsing margins and paddings
$exploded_image_margins = explode(" ", trim($this->config["image_margin"]));
$exploded_image_paddings = explode(" ", trim($this->config["image_padding"]));
$exploded_thumbs_margins = explode(" ", trim($this->config["thumbs_margin"]));
$exploded_thumbs_paddings = explode(" ", trim($this->config["thumbs_padding"]));
// parsing
// image margins
if(count($exploded_image_margins) == 1){
	$image_parsed_margins["top"] = str_replace("px", "", $exploded_image_margins[0]);
	$image_parsed_margins["right"] = str_replace("px", "", $exploded_image_margins[0]);
	$image_parsed_margins["bottom"] = str_replace("px", "", $exploded_image_margins[0]);
	$image_parsed_margins["left"] = str_replace("px", "", $exploded_image_margins[0]);
}elseif(count($exploded_image_margins) == 2){
	$image_parsed_margins["top"] = str_replace("px", "", $exploded_image_margins[0]);
	$image_parsed_margins["right"] = str_replace("px", "", $exploded_image_margins[1]);
	$image_parsed_margins["bottom"] = str_replace("px", "", $exploded_image_margins[0]);
	$image_parsed_margins["left"] = str_replace("px", "", $exploded_image_margins[1]);	
}elseif(count($exploded_image_margins) == 4){
	$image_parsed_margins["top"] = str_replace("px", "", $exploded_image_margins[0]);
	$image_parsed_margins["right"] = str_replace("px", "", $exploded_image_margins[1]);
	$image_parsed_margins["bottom"] = str_replace("px", "", $exploded_image_margins[2]);
	$image_parsed_margins["left"] = str_replace("px", "", $exploded_image_margins[3]);	
}
// image paddings
if(count($exploded_image_paddings) == 1){
	$image_parsed_paddings["top"] = str_replace("px", "", $exploded_image_paddings[0]);
	$image_parsed_paddings["right"] = str_replace("px", "", $exploded_image_paddings[0]);
	$image_parsed_paddings["bottom"] = str_replace("px", "", $exploded_image_paddings[0]);
	$image_parsed_paddings["left"] = str_replace("px", "", $exploded_image_paddings[0]);
}elseif(count($exploded_image_paddings) == 2){
	$image_parsed_paddings["top"] = str_replace("px", "", $exploded_image_paddings[0]);
	$image_parsed_paddings["right"] = str_replace("px", "", $exploded_image_paddings[1]);
	$image_parsed_paddings["bottom"] = str_replace("px", "", $exploded_image_paddings[0]);
	$image_parsed_paddings["left"] = str_replace("px", "", $exploded_image_paddings[1]);	
}elseif(count($exploded_image_paddings) == 4){
	$image_parsed_paddings["top"] = str_replace("px", "", $exploded_image_paddings[0]);
	$image_parsed_paddings["right"] = str_replace("px", "", $exploded_image_paddings[1]);
	$image_parsed_paddings["bottom"] = str_replace("px", "", $exploded_image_paddings[2]);
	$image_parsed_paddings["left"] = str_replace("px", "", $exploded_image_paddings[3]);	
}
// thumbs margins
if(count($exploded_image_margins) == 1){
	$thumbs_parsed_margins["top"] = str_replace("px", "", $exploded_thumbs_margins[0]);
	$thumbs_parsed_margins["right"] = str_replace("px", "", $exploded_thumbs_margins[0]);
	$thumbs_parsed_margins["bottom"] = str_replace("px", "", $exploded_thumbs_margins[0]);
	$thumbs_parsed_margins["left"] = str_replace("px", "", $exploded_thumbs_margins[0]);
}elseif(count($exploded_thumbs_margins) == 2){
	$thumbs_parsed_margins["top"] = str_replace("px", "", $exploded_thumbs_margins[0]);
	$thumbs_parsed_margins["right"] = str_replace("px", "", $exploded_thumbs_margins[1]);
	$thumbs_parsed_margins["bottom"] = str_replace("px", "", $exploded_thumbs_margins[0]);
	$thumbs_parsed_margins["left"] = str_replace("px", "", $exploded_thumbs_margins[1]);	
}elseif(count($exploded_thumbs_margins) == 4){
	$thumbs_parsed_margins["top"] = str_replace("px", "", $exploded_thumbs_margins[0]);
	$thumbs_parsed_margins["right"] = str_replace("px", "", $exploded_thumbs_margins[1]);
	$thumbs_parsed_margins["bottom"] = str_replace("px", "", $exploded_thumbs_margins[2]);
	$thumbs_parsed_margins["left"] = str_replace("px", "", $exploded_thumbs_margins[3]);	
}
// thumbs paddings
if(count($exploded_thumbs_paddings) == 1){
	$thumbs_parsed_paddings["top"] = str_replace("px", "", $exploded_thumbs_paddings[0]);
	$thumbs_parsed_paddings["right"] = str_replace("px", "", $exploded_thumbs_paddings[0]);
	$thumbs_parsed_paddings["bottom"] = str_replace("px", "", $exploded_thumbs_paddings[0]);
	$thumbs_parsed_paddings["left"] = str_replace("px", "", $exploded_thumbs_paddings[0]);
}elseif(count($exploded_thumbs_paddings) == 2){
	$thumbs_parsed_paddings["top"] = str_replace("px", "", $exploded_thumbs_paddings[0]);
	$thumbs_parsed_paddings["right"] = str_replace("px", "", $exploded_thumbs_paddings[1]);
	$thumbs_parsed_paddings["bottom"] = str_replace("px", "", $exploded_thumbs_paddings[0]);
	$thumbs_parsed_paddings["left"] = str_replace("px", "", $exploded_thumbs_paddings[1]);	
}elseif(count($exploded_thumbs_paddings) == 4){
	$thumbs_parsed_paddings["top"] = str_replace("px", "", $exploded_thumbs_paddings[0]);
	$thumbs_parsed_paddings["right"] = str_replace("px", "", $exploded_thumbs_paddings[1]);
	$thumbs_parsed_paddings["bottom"] = str_replace("px", "", $exploded_thumbs_paddings[2]);
	$thumbs_parsed_paddings["left"] = str_replace("px", "", $exploded_thumbs_paddings[3]);	
}
// calculating sizes
// calculating thumbs block width
$total_thumbs_width = ($thumbs_parsed_margins["left"] + $thumbs_parsed_margins["right"] + $thumbs_parsed_paddings["left"] + $thumbs_parsed_paddings["right"] + $this->config['thumbs_border_width'] * 2 + $this->settings["thumb_x"]) * $this->config['thumbs_cols'];
//
$total_block_width += $this->settings["image_x"];
$total_block_width += $image_parsed_margins["left"] + $image_parsed_margins["right"];
$total_block_width += $image_parsed_paddings["left"] + $image_parsed_paddings["right"];
$total_block_width += $this->config['image_border_width'] * 2;
if( $this->config["thumbs_position"] == 'left' || $this->config["thumbs_position"] == 'right' )
{
	$total_block_width += $total_thumbs_width;
}	
// changing block width when thumbs width is bigger than block width
// without checking thumbs position because if thums are floated then
// they are always smaller than total block width.
if($total_thumbs_width > $total_block_width)
{
	$total_block_width = $total_thumbs_width;
} 

?>

<div id="gk_is-<?php echo $this->ID;?>" class="gk_is_wrapper gk_is_wrapper-style1" style="width: <?php echo $total_block_width; ?>px;">

	<?php if($this->config["preloading"] == 'true') : ?>
	<div class="gk_is_preloader"></div>
	<?php endif; ?>
	
	<?php if($this->config["thumbs_position"] == "left" || $this->config["thumbs_position"] == "top") : ?>
	<div class="gk_si_thumbs" style="width: <?php echo $total_thumbs_width; ?>px;<?php if($this->config["thumbs_position"] == "left") echo 'float:left;'; ?>">
		<?php for($i = 0; $i < count($this->slides); $i++) : ?>
			
			<?php 
			
				// cleaning variables
				unset($path, $title, $thumb_style);
				// creating slide path
				$path = $URI->root().'components/com_gk3_photoslide/thumbs_small/'.$this->slides[$i]["filename"];
				// creating slide title
				$title = htmlspecialchars(($this->slides[$i]["ctitle"] == "") ? $this->slides[$i]["title"] : $this->slides[$i]["ctitle"]);
				// crating thumbnail styles
				$thumb_style = '';
				if($this->config["thumbs_margin"] != '0')
					$thumb_style .= 'margin:'.$this->config["thumbs_margin"].';';
				if($this->config["thumbs_padding"] != '0')
					$thumb_style .= 'padding:'.$this->config["thumbs_padding"].';';
				if($this->config["thumbs_border_width"] != '0')
					$thumb_style .= 'border:'.$this->config["thumbs_border"].';';
					
			?>
			
			<img src="<?php echo $path; ?>" class="gk_is_thumb gk_is_tt" alt="<?php echo $title; ?>" style="<?php echo $thumb_style; ?>" title="<?php echo $title; ?>" />
			
		<?php endfor; ?>
	</div>		
	<?php endif; ?>
	
	<?php
	
		$image_style = '';
		if($this->config["image_margin"] != '0')
			$image_style .= 'margin:'.$this->config["image_margin"].';';
		if($this->config["image_padding"] != '0')
			$image_style .= 'padding:'.$this->config["image_padding"].';';
		if($this->config["image_border_width"] != '0')
			$image_style .= 'border:'.$this->config["image_border"].';';
	
	?>
	
	<div class="gk_is_image" style="width: <?php echo $this->settings["image_x"]; ?>px;height: <?php echo $this->settings["image_y"]; ?>px;<?php  echo ($this->config["thumbs_position"] == "left" || $this->config["thumbs_position"] == "right") ? 'float: left;' : ''; ?><?php echo $image_style; ?>">
		<?php for($i = 0; $i < count($this->slides); $i++) : ?>
			
			<?php 
			
				// cleaning variables
				unset($path, $title, $link);
				// creating slide path
				$path = $URI->root().'components/com_gk3_photoslide/thumbs_big/'.$this->slides[$i]["filename"];
				// creating slide title
				$title = htmlspecialchars(($this->slides[$i]["ctitle"] == "") ? $this->slides[$i]["title"] : $this->slides[$i]["ctitle"]);
				// creating slide link
				$link = ($this->slides[$i]["link_type"] != 0) ? JRoute::_(ContentHelperRoute::getArticleRoute($this->slides[$i]["article"], $this->slides[$i]["cid"], $this->slides[$i]["sid"])) : $this->slides[$i]["link"];	
				
			?>
			
			<?php if($this->config["preloading"] == 'false') : ?>
				<img src="<?php echo $path; ?>" class="gk_is_slide" style="z-index: <?php echo $i+1; ?>;" alt="<?php echo $link; ?>" title="<?php echo $title; ?>" />
			<?php else: ?>
				<div class="gk_is_slide" style="z-index: <?php echo $i+1; ?>;" title="<?php echo $title; ?>"><?php echo $path; ?><a href="<?php echo $link; ?>">link</a></div>
			<?php endif; ?>
			
		<?php endfor; ?>
	
	
		<?php if($this->config['show_text_block'] == "true") : ?>
		<?php if($this->config['text_block_bgimage'] == "true"): ?>
		<div class="gk_is_text_bg" style="height:<?php echo $this->config['text_block_height']; ?>px;<?php echo ($this->config['text_block_position'] == 'bottom') ? 'bottom:0;': 'top:0;'; ?>"></div>
		<?php else : ?>
		<div class="gk_is_text_bg" style="height:<?php echo $this->config['text_block_height']; ?>px;opacity:<?php echo $this->config['text_block_opacity']; ?>;background-color:<?php echo $this->config['text_block_bgcolor']; ?>;<?php echo ($this->config['text_block_position'] == 'bottom') ? 'bottom:0;': 'top:0;'; ?>"></div>
		<?php endif; ?>
		<div class="gk_is_text" style="height:<?php echo $this->config['text_block_height']; ?>px;<?php echo ($this->config['text_block_position'] == 'bottom') ? 'bottom:0;': 'top:0;'; ?>"></div>
		
		<div class="gk_is_text_data">
			<?php for($i = 0; $i < count($this->slides); $i++) : ?>
			
			<?php 
			
				// cleaning variables
				unset($text, $title, $link, $exploded_text);
				// creating slide text
				$text = ($this->slides[$i]["content"] == "") ? $this->slides[$i]["introtext"] : $this->slides[$i]["content"];
				if($this->config["clean_xhtml"] == "true") $text = strip_tags($text);
				$exploded_text = explode(" ", $text);
				$text = '';
				for($j = 0; $j < $this->config["wordcount"]; $j++)
				{
					if(isset($exploded_text[$j]))
					{
						$text .= $exploded_text[$j]." ";
					}
				}
				// creating slide title
				$title = ($this->slides[$i]["ctitle"] == "") ? $this->slides[$i]["title"] : $this->slides[$i]["ctitle"];
				// creating slide link
				$link = ($this->slides[$i]["link_type"] != 0) ? JRoute::_(ContentHelperRoute::getArticleRoute($this->slides[$i]["article"], $this->slides[$i]["cid"], $this->slides[$i]["sid"])) : $this->slides[$i]["link"];
				
			?>
			
			<div class="gk_is_text_item">
				<?php if($this->config["title"] == "true"): ?>
					<?php if($this->config["title_link"] == "true"): ?>
						<h4><span><a href="<?php echo $link; ?>"><?php echo $title; ?></a></span></h4>
					<?php else: ?>
						<h4><span><?php echo $title; ?></span></h4>
					<?php endif; ?>
				<?php endif; ?>
				<p>
					<?php echo $text; ?>
					<?php if($this->config["readmore_button"]) : ?>
					<a href="<?php echo $link; ?>"><?php echo $this->config["readmore_text"]; ?></a>
					<?php endif; ?>
				</p>
			</div>
		<?php endfor; ?>
		</div>
		<?php endif; ?>
	</div>
	
	<?php if($this->config["thumbs_position"] == "right" || $this->config["thumbs_position"] == "bottom") : ?>
	<div class="gk_is_thumbs" style="width: <?php echo $total_thumbs_width; ?>px;<?php if($this->config["thumbs_position"] == "right") echo 'float:right;'; ?>">
		<?php for($i = 0; $i < count($this->slides); $i++) : ?>
			
			<?php 
			
				// cleaning variables
				unset($path, $title, $thumb_style);
				// creating slide path
				$path = $URI->root().'components/com_gk3_photoslide/thumbs_small/'.$this->slides[$i]["filename"];
				// creating slide title
				$title = htmlspecialchars(($this->slides[$i]["ctitle"] == "") ? $this->slides[$i]["title"] : $this->slides[$i]["ctitle"]);
				// crating thumbnail styles
				$thumb_style = '';
				if($this->config["thumbs_margin"] != '0')
					$thumb_style .= 'margin:'.$this->config["thumbs_margin"].';';
				if($this->config["thumbs_padding"] != '0')
					$thumb_style .= 'padding:'.$this->config["thumbs_padding"].';';
				if($this->config["thumbs_border_width"] != '0')
					$thumb_style .= 'border:'.$this->config["thumbs_border"].';';
					
			?>
			
			<img src="<?php echo $path; ?>" class="gk_is_thumb gk_is_tt" alt="<?php echo $title; ?>" style="<?php echo $thumb_style; ?>" title="<?php echo $title; ?>" />
			
		<?php endfor; ?>
	</div>		
	<?php endif; ?>
</div>