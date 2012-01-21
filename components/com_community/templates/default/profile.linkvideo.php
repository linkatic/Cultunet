<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	my	Current browser's CUser object.
 **/
defined('_JEXEC') or die();
?>

<div class="video-full">
	<div class="video-head">
		<div class="ctitle">
			<h2><?php echo JText::_('CC CURRENT PROFILE VIDEO HEADING');?></h2>
			<?php if(!empty($video->id)){ ?>
			<span class="video-remove">
			<a onclick="joms.videos.removeConfirmProfileVideo(<?php echo $video->creator; ?>, <?php echo $video->id; ?>);" href="javascript:void(0);" class="icon-videos-remove"><?php echo JText::_('CC PROFILE VIDEO REMOVE'); ?></a>
			</span>
			<?php } ?>
		</div>
	</div>
	
	<div class="cRow clrfix">               
	<?php if(!empty($video->id)){ ?>
		<div class="video-player">
			<?php echo $video->player; ?>
			<div class="clr"></div>
		</div>
		<div class="ctitle"><h2><?php echo JText::_('CC PROFILE VIDEO DESCRIPTION'); ?></h2></div>
		<p><?php echo $this->escape($video->description); ?></p>

	<?php } else { ?>
		<div style="text-align: center;"><img src="<?php echo JURI::root(); ?>/components/com_community/assets/video_thumb.png" alt="<?php echo JText::_('CC NO PROFILE VIDEO'); ?>" title="<?php echo JText::_('CC NO PROFILE VIDEO'); ?>" /></div>
		<p align="center"><?php echo JText::_('CC USER NO PROFILE VIDEO'); ?></p>
	<?php } ?>
	</div>


</div>

<?php

echo $sortings;

if ($videos) { ?>

	<?php foreach($videos as $video) { ?>

	<div class="video-item jomTips tipFullWidth" id="<?php echo "video-" . $video->id ?>" title="<?php echo $video->title . '::' . CStringHelper::trim($this->escape($video->description) , VIDEO_TIPS_LENGTH ); ?>">
		<div class="video-item">
		
		    <div class="video-thumb">
		        <?php if ($video->status=='pending'): ?>
		            <img src="<?php echo JURI::root(); ?>/components/com_community/assets/video_thumb.png" width="<?php echo $videoThumbWidth; ?>" height="<?php echo $videoThumbHeight; ?>" alt="" />
		        <?php else: ?>            
		            <a class="video-thumb-url" href="<?php echo $video->url; ?>" style="width: <?php echo $videoThumbWidth; ?>px; height:<?php echo $videoThumbHeight; ?>px;"><img src="<?php echo $video->thumb; ?>" width="<?php echo $videoThumbWidth; ?>" height="<?php echo $videoThumbHeight; ?>" alt="" /></a>
		            <span class="video-durationHMS"><?php echo $video->durationHMS; ?></span>
		        <?php endif; ?>                
		    </div>
		
		    <div class="video-summary">
		        <div class="video-title">
		            <?php
		            if ($video->status=='pending') {
		                echo $video->title;
		            } else {
		            ?>
		                <a href="<?php echo $video->url; ?>"><?php echo $video->title; ?></a>
		            <?php } ?>
		        </div>
		        
		        <div class="video-details small">
		            <div class="video-hits"><?php echo JText::sprintf('CC VIDEO HITS COUNT', $video->hits) ?></div>                    
		            <div class="video-lastupdated"><?php echo JText::sprintf('CC VIDEO LAST UPDATED', $video->lastupdated );?></div>
		        </div>
		    
		        
		        <div class="video-actions small">
					<a class="video-action link" href="javascript:void(0);" onclick="joms.videos.linkConfirmProfileVideo('<?php echo $video->id; ?>', '<?php echo $redirectUrl;?>');"><span><?php echo JText::_('CC PROFILE VIDEO LINK') ?></span></a>
		    	</div>
		    
		    	<div class="clr"></div>
			</div>
		</div>
	</div>
<?php   

	    
	} ?>
<div class="clr"></div>

<?php 
}
else 
{
	$isMine	= ( isset($video) && $video->creator==$my->id);
	$msg	= $isMine ? JText::_('CC NO VIDEOS') : JText::sprintf('CC USER NO VIDEOS', $my->getDisplayName());
	?>
		<div><?php echo $msg; ?></div>
	<?php
}
?>

<div class="pagination-container">
	<?php echo $pagination->getPagesLinks(); ?>
</div>
