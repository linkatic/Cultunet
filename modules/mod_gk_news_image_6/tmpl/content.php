<?php 

/**
* Gavick News Image VI - content template
* @package Joomla!
* @Copyright (C) 2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.1 $
**/

// access restriction
defined('_JEXEC') or die('Restricted access');
//
$block_width = $this->module_width + $this->tabsbar_width + $this->tabsbar_margin;
//
$text_overlay_bgcolor = ($this->text_block_background == 0) ? $this->text_block_bgcolor : $this->base_bgcolor;

?>

<div class="gk_ni_6_wrapper" id="gk_news_image_6-<?php echo $this->module_id;?>" style="width:<?php echo $block_width;?>px;<?php echo ($this->module_bg == '') ? '' : 'background-color: #'.$this->module_bg.';'; ?>">
	<div style="width:<?php echo $this->module_width;?>px;height: auto;float: <?php echo (($this->tabs_position == 'left') ? 'right':'left');?>;">
		<div class="gk_ni_6_wrapper2" style="width:<?php echo $this->module_width;?>px;height:<?php echo $this->module_height;?>px;background-color: <?php echo $this->base_bgcolor;?>;float: <?php echo (($this->tabs_position == 'left') ? 'right':'left');?>;">
			<div class="gk_ni_6_json"><?php echo $this->JSON;?></div>
				
			<?php $highest_layer = 0; ?>
			
			<?php for($i = 0; $i < count($this->slides); $i++) : ?>
				<?php if($this->preloading == 0) : ?>
					<div class="gk_ni_6_slide" style="background-image:url(<?php echo $this->base_path_to_images; ?>/thumbm/<?php echo $this->slides[$i]['name']; ?>);z-index:<?php echo $i; ?>;width: <?php echo $this->module_width; ?>px;height: <?php echo $this->module_height; ?>px;"></div>
				<?php else : ?>
					<div class="gk_ni_6_slide" style="z-index: <?php echo $i; ?>;width: <?php echo $this->module_width; ?>px;height: <?php echo $this->module_height; ?>px;"><?php echo $this->base_path_to_images; ?>/thumbm/<?php echo $this->slides[$i]['name']; ?></div>
				<?php endif; ?>
				<?php $highest_layer = $i; ?>
			<?php endfor; ?>
		
		<?php $highest_layer += 5; ?>
		
		<?php if($this->show_text_block == 1) : ?>
				<div class="gk_ni_6_text_bg" style="z-index:<?php echo $highest_layer;?>;	background-color: <?php echo $text_overlay_bgcolor; ?>;opacity: <?php echo $this->text_block_opacity;?>;height: <?php echo $this->start_h; ?>px;top: <?php echo $this->module_height - $this->start_h; ?>px;"></div>
				<?php $highest_layer += 1; ?>
				<div class="gk_ni_6_text" style="z-index:<?php echo $highest_layer;?>;height: <?php echo $this->start_h; ?>px;top: <?php echo $this->module_height - $this->start_h; ?>px;"></div>
		<?php endif; ?>
		
		</div>
		<?php if($this->readmore_button == 1) : ?>
		<div class="gk_ni_6_readmore_wrapper">	
			<div class="gk_ni_6_readmore_button">
				<span><a href="#"><?php echo $this->readmore_text; ?></a></span>
			</div>
		</div>
		<?php endif; ?>
	</div>

	<div style="width:<?php echo $this->tabsbar_width; ?>px;height: auto;margin-<?php echo ($this->tabs_position == 'left') ? 'right' : 'left'; ?>: <?php echo $this->tabsbar_margin; ?>px;overflow: hidden;float: left;">
		<div class="gk_ni_6_tabsbar_wrap" style="width:<?php echo $this->tabsbar_width; ?>px;height:<?php echo $this->module_height;?>px;">
	
		<?php if((count($this->slides) * ((($this->module_height - ($this->tabs_margin*($this->tabs_per_page-1)))/$this->tabs_per_page) + $this->tabs_margin)) < $this->module_height) : ?>
			<div class="gk_ni_6_tabsbar" style="width:<?php echo $this->tabsbar_width; ?>px;height: auto; ?>px;">	
					
				<?php for($i = 0;$i < count($this->slides);$i++) : ?>
					<?php 
						$field_title = "";
						$field_text = "";
						($this->slides[$i]["title"] == '') ? $field_title = "ctitle" : $field_title = "title";
						($this->slides[$i]["text"] == '') ? $field_text = "introtext" : $field_text = "text";
					?>
					
					<div class="gk_ni_6_tab" style="margin-bottom: <?php echo $this->tabs_margin; ?>px;height: <?php echo (($this->module_height - ($this->tabs_margin*($this->tabs_per_page-1)))/$this->tabs_per_page); ?>px;">
						<img src="<?php echo $this->base_path_to_images; ?>/thumbs/<?php echo $this->slides[$i]['name']; ?>" alt="News Image" />
						<h4><?php echo htmlspecialchars(strip_tags(JString::substr($this->slides[$i][$field_title], 0, $this->title_limit))); ?></h4>
						<p><?php echo htmlspecialchars(strip_tags(JString::substr($this->slides[$i][$field_text], 0, $this->text_limit))); ?>...</p>
						<div class="gk_ni_6_hover"><div></div></div>
					</div>
				<?php endfor; ?>
			
			</div>
		<?php else : ?>
			<div class="gk_ni_6_tabsbar" style="width:<?php echo $this->tabsbar_width; ?>px;height: auto;">	
				
			<?php for($i = 0;$i < count($this->slides);$i++) : ?>
				<?php
					$field_title = "";
					$field_text = "";
					($this->slides[$i]["title"] == '') ? $field_title = "ctitle" : $field_title = "title";
					($this->slides[$i]["text"] == '') ? $field_text = "introtext" : $field_text = "text";
					$first_margin = ($i == 0) ? 'margin-top: 0px;' : '';
				?>
				
				<div class="gk_ni_6_tab" style="margin-bottom: <?php echo $this->tabs_margin; ?>px;height: <?php echo (($this->module_height - ($this->tabs_margin*($this->tabs_per_page-1)))/$this->tabs_per_page); ?>px;">
					<img src="<?php echo $this->base_path_to_images; ?>/thumbs/<?php echo $this->slides[$i]['name']; ?>" alt="News Image" />
					<h4><?php echo htmlspecialchars(strip_tags(JString::substr($this->slides[$i][$field_title], 0, $this->title_limit))); ?></h4>
					<p><?php echo htmlspecialchars(strip_tags(JString::substr($this->slides[$i][$field_text], 0, $this->text_limit))); ?>...</p>
					<div class="gk_ni_6_hover"><div style="margin-top: <?php echo ((($this->module_height - ($this->tabs_margin*($this->tabs_per_page-1)))/$this->tabs_per_page)-41)/2; ?>px;"></div></div>
				</div>
		
			<?php endfor; ?>
		
			</div>
		<?php endif; ?>
		</div>
		
		<?php if(!((count($this->slides) * ((($this->module_height - ($this->tabs_margin*($this->tabs_per_page-1)))/$this->tabs_per_page)) + $this->tabs_margin)) < $this->module_height) : ?>
		    <div class="gk_ni_6_tabsbar_slider" style="width:<?php echo $this->tabsbar_width; ?>px;">
				<div class="gk_ni_6_tabsbar_up"></div>		
				<div class="gk_ni_6_tabsbar_down"></div>
			</div>
		<?php endif; ?>
	</div>
	
	<?php if($this->preloading == 1) : ?>
		<div class="gk_ni_6_preloader" style="z-index: 1000;width:<?php echo $block_width;?>px;height:<?php echo $this->module_height+24;?>px;"></div>
	<?php endif; ?>
</div>