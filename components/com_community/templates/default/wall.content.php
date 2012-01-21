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
<div id="wall_<?php echo $id; ?>" class="wallComments">
    <div class="cavatar"><?php echo $avatarHTML; ?></div>
    <div class="ccontent-avatar">
    <div class="createby">
        <a href="<?php echo $authorLink; ?>"><?php echo $author; ?></a>,
    </div>
	
    <div class="content">
        <span id="wall-message-<?php echo $id;?>"><?php echo $content; ?></span>
    <?php if($config->get('wallediting')){ ?>
    <!--TIME LEFT TO EDIT REPLY-->
    <?php if($isEditable){?>
    <div class="small">
    <?php echo JText::sprintf('CC TIME LEFT TO EDIT REPLY' , $editInterval , '<a onclick="joms.walls.edit(\'' . $id . '\',\'' . $processFunc.'\');" href="javascript:void(0)">' . JText::_('CC EDIT') . '</a>' );?>
    </div>
    <?php } ?>
    <!--end: TIME LEFT TO EDIT REPLY-->
    <?php } ?>
        <?php echo $commentsHTML; ?>
    </div>
    
    <div class="date">
      <span class="createdate"><?php echo $created; ?></span>
    	<?php if($isMine) { ?>
    		<span class="remove"><a onclick="wallRemove(<?php echo $id; ?>);" href="javascript:void(0)">[<?php echo JText::_('CC WALL REMOVE');?>]</a></span>
    	<?php } ?>	
  	</div>
	</div>
	<div class="clr">&nbsp;</div>
</div>

