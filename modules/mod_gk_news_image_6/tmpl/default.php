<?php

/**
* Gavick News Image VI - default template
* @package Joomla!
* @Copyright (C) 2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.1 $
**/

// access restriction
defined('_JEXEC') or die('Restricted access');

?>

<?php if(!$this->clean_code) : ?>
<script type="text/javascript">
	try {$Gavick;}catch(e){$Gavick = {};};
	$Gavick["gk_news_image_6-<?php echo $this->module_id;?>"] = {
		"anim_speed":<?php echo $this->animation_slide_speed;?>,
		"anim_interval":<?php echo $this->animation_interval;?>,
		"autoanim":<?php echo $this->autoanimation;?>,
		"anim_type":<?php echo $this->animation_slide_type;?>,
		"anim_type_t":<?php echo $this->animation_text_type;?>,
		"thumb_w":<?php echo $this->thumbnail_width;?>,
		"thumb_h":<?php echo $this->thumbnail_height;?>,
		"t_amount":<?php echo $this->tabs_amount;?>,
		"bgcolor":"<?php echo $this->base_bgcolor;?>",
		"opacity":<?php echo $this->text_block_opacity;?>
	};
</script>
<?php endif; ?>

<?php if($this->useMoo == 1) : ?>
<script type="text/javascript" src="<?php echo $uri->root(); ?>modules/mod_gk_news_image_6/js/mootools.js"></script>
<?php endif; ?>

<?php if($this->useScript == 1) : ?>
<script type="text/javascript" src="<?php echo $uri->root(); ?>modules/mod_gk_news_image_6/js/engine<?php echo ($this->compress_js) ? '_compressed' : ''; ?>.js"></script>
<?php endif; ?>