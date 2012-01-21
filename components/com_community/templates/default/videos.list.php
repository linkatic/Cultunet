<?php
/**
 * @package        JomSocial
 * @subpackage     Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * 
 */
defined('_JEXEC') or die();
?>

<?php if ($videos) { ?>
<div class="video-items">
<?php foreach($videos as $video) { ?>

<div class="video-item jomTips tipFullWidth" id="<?php echo "video-" . $video->id ?>" title="<?php echo $video->title . '::' . CStringHelper::trim($this->escape($video->description) , VIDEO_TIPS_LENGTH ); ?>">
<div class="video-item">
		<!---VIDEO THUMB-->
    <div class="video-thumb">
        <?php if ($video->status=='pending'): ?>
            <img src="<?php echo JURI::root(); ?>/components/com_community/assets/video_thumb.png" width="<?php echo $videoThumbWidth; ?>" height="<?php echo $videoThumbHeight; ?>" alt="" />
        <?php else: ?>            
            <a class="video-thumb-url" href="<?php echo $video->url; ?>" style="width: <?php echo $videoThumbWidth; ?>px; height:<?php echo $videoThumbHeight; ?>px;"><img src="<?php echo $video->thumb; ?>" width="<?php echo $videoThumbWidth; ?>" height="<?php echo $videoThumbHeight; ?>" alt="" /></a>
            <span class="video-durationHMS"><?php echo $video->durationHMS; ?></span>
        <?php endif; ?>                
    </div>
		<!---end: VIDEO THUMB-->
		
		<!---VIDEO SUMMARY-->
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
            <?php if ( (!$video->isOwner && !$groupVideo) || ($groupVideo && !$allowManageVideos) ) { ?>
            <div class="video-creatorName"><a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$video->creator); ?>"><?php echo $video->creatorName; ?></a></div>
            <?php } ?>
        </div>
    
        <?php if ( $isCommunityAdmin || ($video->isOwner && !$groupVideo) || ($groupVideo && $allowManageVideos) ) { ?>
        <div class="video-actions small">
            <a class="video-action edit" href="javascript:void(0);" onclick="joms.videos.showEditWindow('<?php echo $video->id; ?>', '<?php echo $redirectUrl;?>');"><span><?php echo JText::_('CC EDIT') ?></span></a> | 
            <a class="video-action delete" href="javascript:void(0);" onclick="joms.videos.deleteVideo('<?php echo $video->id;?>','<?php echo $redirectUrl;?>');"><span><?php echo JText::_('CC DELETE') ?></span></a>
            <!-- begin: COMMUNITY_FREE_VERSION -->
			<?php if( !COMMUNITY_FREE_VERSION ){ ?>
			<?php
			if( $isCommunityAdmin && !$groupVideo && !JRequest::getCmd('task') )
			{
				if( !in_array($video->id, $featuredList) )
				{
			?>
					| <span id="featured-<?php echo $video->id; ?>">	            
			            <a onclick="joms.featured.add('<?php echo $video->id; ?>', 'videos');" href="javascript:void(0);">	            	            
			            <?php echo JText::_('CC MAKE FEATURED'); ?>
			            </a>
			        </span>
			<?php			
				}
			}
			?>
			<?php } ?>
			<!-- end: COMMUNITY_FREE_VERSION -->
        </div>
        <?php } ?>
    </div>
    <!---end: VIDEO SUMMARY-->
    <div class="clr"></div>
</div>
<!---end: VIDEO ITEM-->
</div>
<!---end: VIDEO ITEM-->

<?php } ?>
</div>
<!---end: VIDEO ITEM(S)-->
<div class="clr"></div>

<?php 
} else {
    $task	= JRequest::getVar('task');
	switch ($task)
	{
		case 'mypendingvideos':
			$msg	= JText::_('CC NO PENDING VIDEOS');
			break;
		case 'search':
			$msg	= JText::_('CC NO RESULT');
			break;
		case 'myvideos':
			$isMine	= ($user->id==$my->id);
			$msg	= $isMine ? JText::_('CC NO VIDEOS') : JText::sprintf('CC USER NO VIDEOS', $user->getDisplayName());
			break;
		default:
			$msg	= JText::_('CC NO VIDEOS');
			break;
	}
	?>
		<div class="video-not-found"><?php echo $msg; ?></div>
	<?php
}
?>