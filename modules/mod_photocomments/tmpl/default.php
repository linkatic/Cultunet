<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="photocomments<?php echo $params->get( 'moduleclass_sfx' ) ?>">
<ul>
<?php
if( $comments )
{
	$i		= 1;
	$total	= count( $comments );
	
	foreach( $comments as $comment )
	{
		$poster	= CFactory::getUser( $comment->post_by );
		
		if( $comment->phototype == PHOTOS_USER_TYPE )
		{
			$link	= CRoute::_('index.php?option=com_community&view=photos&task=photo&albumid=' . $comment->albumid . '&photoid=' . $comment->contentid . '&userid=' . $comment->creator ) . '#photoid=' . $comment->contentid;
		}
		else
		{
			$link	= CRoute::_('index.php?option=com_community&view=photos&task=photo&albumid=' . $comment->albumid . '&photoid=' . $comment->contentid . '&groupid=' . $comment->groupid ) . '#photoid=' . $comment->contentid;
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
		<div style="<?php echo $params->get('show_avatar') ? 'margin-left: 42px;' : '';?>line-height: normal;overflow:hidden;">
			<span style="width: 100%;"><a href="<?php echo $link;?>"><?php echo $comment->caption;?></a></span>
			<span style="display: block;margin-top: 3px;"><?php echo $comment->comment;?></span>
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
	<li><?php echo JText::_('MOD_PHOTOCOMMENTS NO COMMENTS');?></li>
<?php
}
?>
</ul>
</div>