<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="videocomments<?php echo $params->get( 'moduleclass_sfx' ) ?>">
<ul>
<?php
if( $comments )
{
	$i		= 1;
	$total	= count( $comments );
	
	foreach( $comments as $comment )
	{
		$poster	= CFactory::getUser( $comment->post_by );
		
		if( $comment->creator_type == VIDEO_USER_TYPE )
		{
			$link	= CRoute::_('index.php?option=com_community&view=videos&task=video&videoid=' . $comment->contentid . '&userid=' . $comment->creator );
		}
		else
		{
			$link	= CRoute::_('index.php?option=com_community&view=videos&task=video&videoid=' . $comment->contentid . '&groupid=' . $comment->groupid );
		}
		
?>
	<li style="background: none; padding: 5px 0; <?php echo ( $i != $total ) ? 'border-bottom: solid 1px #ccc;' : '';?>">
		<?php
			if( $params->get('show_avatar') )
			{
		?>
			<div style="float: left;">
				<img src="<?php echo $poster->getThumbAvatar(); ?>" width="32" />
			</div>
		<?php
			}
		?>
		<div style="<?php echo $params->get('show_avatar') ? 'margin-left: 42px;' : '';?>line-height: normal;">
			<span style="width: 100%;"><a href="<?php echo $link;?>"><?php echo $comment->title;?></a></span>
                        <span style="display: block;margin-top: 3px;"><?php echo CStringHelper::escape($comment->comment); ?></span>
		</div>
		<div style="clear: both;"></div>
	</li>
<?php
		$i++;
	}
}
else
{
?>
	<li><?php echo JText::_('MOD_VIDEOCOMMENTS NO COMMENTS');?></li>
<?php
}
?>
</ul>
</div>