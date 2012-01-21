<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
?>

<?php
if (count($walls)>0)
{
?>

<ul style="list-style: none;">
<?php
	$charactersCount	= $params->get('charcount' , 100 );
	foreach( $walls as $wall )
	{
		$user			= CFactory::getUser( $wall->post_by );
		$wall->comment	= CComment::stripCommentData( $wall->comment );
		$comment		= JString::substr( $wall->comment , 0 , $charactersCount);		
		$comment		.= ( $charactersCount > JString::strlen( $wall->comment ) ) ? '' : '...';
		
		$groupname      = CStringHelper::escape($wall->groupname);
		$grouplink 		= CRoute::_('index.php?option=com_community&view=groups&task=viewgroup&groupid=' . $wall->contentid );
		$groupavatar	= rtrim( JURI::root() , '/' ) . '/' . $wall->thumbnail;
?>
	<li style="background: none; padding: 5px 0; border-bottom: solid 1px #ccc;">
	<?php
		if( $params->get('show_avatar') )
		{
	?>
		<div style="float: left;">
			<a title="<?php echo $groupname; ?>" href="<?php echo $grouplink; ?>">
				<img src="<?php echo $groupavatar; ?>" alt="<?php echo $groupname; ?>" width="32" />
			</a>
		</div>
	<?php
		}
	?>
		<div style="<?php echo $params->get('show_avatar') ? 'margin-left: 42px;' : '';?>line-height: normal;">
			<span style="width: 100%;"><a href="<?php echo $grouplink; ?>"><?php echo $groupname; ?></a></span>
			<span style="display: block;margin-top: 3px;"><?php echo $comment;?></span>
		</div>
		<div style="clear: both;"></div>
	</li>
<?php
	}
?>
</ul>
<?php
}
?>