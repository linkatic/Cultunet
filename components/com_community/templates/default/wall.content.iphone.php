<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	author		string
 * @param	id			integer the wall object id 
 * @param	authorLink 	string link to author 
 * @param	created		string(date)
 * @param	content		string 
 * @param	avatar		string(link) to avatar
 * @params	isMine		boolean is this wall entry belong to me ? 
 */
defined('_JEXEC') or die();
?>

<div id="wall_<?php echo $id; ?>" class="wall-post" onclick="wall.toggle('wall_info_<?php echo $id; ?>');">
	<div class="ctitle iphone"></div>
	
	<div id="wall_info_<?php echo $id; ?>" style="display: none;" class="wall-info">
		<span class="createby">
			<a href="<?php echo $authorLink; ?>"><?php echo $author; ?></a>,
		</span>
		<span class="createdate"><?php echo $created; ?></span>
		<?php if($isMine) { ?>
			<span><a onclick="wallRemove(<?php echo $id; ?>);" href="javascript:void(0)">[<?php echo JText::_('CC WALL REMOVE');?>]</a></span>
		<?php } ?>		
	</div>	
	
	<div class="cavatar iphone"><?php echo $avatarHTML; ?></div>
	<div class="ccontent-avatar iphone"><?php echo $content; ?></div>
	<div class="clr">&nbsp;</div>

</div>

