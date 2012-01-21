<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * 
 */
defined('_JEXEC') or die();
?>

<div class="page-actions">
    <?php echo $reportHTML;?>
    <?php echo $bookmarksHTML;?>
    <div class="clr"></div>
</div>

<div class="video-full" id="<?php echo "video-" . $video->id ?>">
		<!--VIDEO PLAYER-->
    <div class="video-player">
			<?php echo $video->player; ?>
    </div>
    <!--end: VIDEO PLAYER-->
		
		
		
		
		<div class="cLayout clrfix">
			<div class="vidSubmenu clrfix">
				<!--VIDEO LINK-->
			<div class="video-permalink">
                <div class="video-label">
                    <label for="video-permalink"><?php echo JText::_('CC VIDEO PERMALINK') ?> :</label>
                </div>
                <div class="video-link">
                    <input id="video-permalink" type="text" readonly="" onclick="joms.jQuery(this).focus().select()" value="<?php echo $video->permalink; ?>" name="video_link" />
                </div>
			</div>
			<!--end: VIDEO LINK-->
			
				<ul class="submenu">
					<li><span><?php echo JText::_('CC VIDEO CREATE DATE') ?> <strong><?php echo JHTML::_('date', $video->created, JText::_('DATE_FORMAT_LC3')); ?></strong></span></li>
					<li><span><?php echo JText::_('CC VIDEO DURATION') ?> <strong><?php echo $video->durationHMS; ?></strong></span></li>
					<li><span><?php echo JText::_('CC VIDEO HITS') ?> <strong><?php echo $video->hits; ?></strong></span></li>
					<li><span><?php echo JText::_('CC VIDEO WALL POSTS') ?> <strong><?php echo $video->wallcount; ?></strong></span></li>
				</ul>
			</div>
			
			<div class="cRow">
				<div class="ctitle"><h2><?php echo JText::_('CC PROFILE VIDEO DESCRIPTION'); ?></h2></div>
				<p class="video-description"><?php echo $this->escape($video->description); ?></p>
			</div>
		</div>
		
		
		
    <!--<div class="video-summary" style="margin-left: <?php echo $video->_width; ?>px">	-->
		
	
   
    <div class="clr"></div>

    
    
             
    
    <div class="ctitle"><?php echo JText::_('CC COMMENTS') ?></div>
    <div class="video-wall">
		<div id="wallForm"><?php echo $wallForm; ?></div>			
        <div id="wallContent"><?php echo $wallContent; ?></div>
    </div>
</div>