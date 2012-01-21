<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 */
defined('_JEXEC') or die();
?>
<div>
	<div class="video-thumb" style="padding: 0; padding-right: 8px;">
		<a class="video-thumb-url" href="<?php echo $url;?>"><img alt="<?php echo $this->escape( $video->getTitle() );?>" style="width: 112px; height: 84px;" src="<?php echo $video->getThumbnail();?>"/></a>
		<span class="video-durationHMS" style="left: 1px; bottom: 1px;"><?php echo $duration;?></span>
	</div>
	<?php echo JString::substr( $video->getDescription() , 0, $config->getInt('streamcontentlength'));?>...
	<div class="clr"></div>
</div>