<?php

/**
* Gavick Image Slide - Style 2
* @package Joomla!
* @Copyright (C) 2008 Gavick.com
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
$total_block_width = 0;
$total_thumbs_width = 0;
$URI = JURI::getInstance();
// parsing margins and paddings
$exploded_image_margins = explode(" ", trim($this->config["image_margin"]));
$exploded_image_paddings = explode(" ", trim($this->config["image_padding"]));
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
// calculating sizes
$total_block_width += $this->settings["image_x"];
$total_block_width += $image_parsed_margins["left"] + $image_parsed_margins["right"];
$total_block_width += $image_parsed_paddings["left"] + $image_parsed_paddings["right"];
$total_block_width += $this->config['image_border_width'] * 2;

?>

<div id="gk_is-<?php echo $this->ID;?>" class="gk_is_wrapper gk_is_wrapper-style2" style="width: <?php echo $total_block_width; ?>px;">

	<?php if($this->config["preloading"] == 'true') : ?>
	<div class="gk_is_preloader"></div>
	<?php endif; ?>

	<?php if($this->config["overlay"] == 'true') : ?>
	<div class="gk_is_overlay"></div>
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
	
	<div class="gk_is_image" style="width: <?php echo $this->settings["image_x"]; ?>px;height: <?php echo $this->settings["image_y"]; ?>px;<?php echo $image_style; ?>">
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
	</div>
</div>