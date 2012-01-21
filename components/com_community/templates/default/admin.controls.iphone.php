<?php
/**
 * @package	JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

if( !$isCommunityAdmin || !$isDefaultPhoto )
{
?>
<div id="community-admin-wrapper">
	<ul id="community-admin-controls">
		<?php
		if( !$isCommunityAdmin )
		{
		?>
		<li class="icon-block-user">
			<?php
				if( !$blocked )
				{
			?>
					<a href="javascript:void(0);" onclick="joms.users.banUser('<?php echo $userid; ?>', '0' );"><span><?php echo JText::_('CC BAN USER');?></span></a>
			<?php
				}
				else
				{
			?>
				<a href="javascript:void(0);" onclick="joms.users.banUser('<?php echo $userid; ?>' , '1');"><span><?php echo JText::_('CC UNBAN USER');?></span></a>
			<?php
				}
			?>
		</li>
		<?php
		}
		?>
		<?php
		if( !$isDefaultPhoto )
		{
		?>
		<li class="icon-remove-avatar">
			<a href="javascript:void(0);" onclick="joms.users.removePicture('<?php echo $userid;?>');"><span><?php echo JText::_('CC REMOVE PROFILE PICTURE');?></span></a>
		</li>
		<?php
		}
		?>
	</ul>
</div>
<?php
}
?>