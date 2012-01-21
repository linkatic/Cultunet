<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php 
 */
defined('_JEXEC') or die();
?>
<!-- GROUP DISCUSSION SIDEBAR -->
<div class="cSidebar clrfix">
		 <!-- GROUP DISCUSSION SIDEBAR 1 -->
        <div class="cModule clrfix">
            <h3><?php echo JText::_('CC LATEST DISCUSSION');?></h3>
            <div class="app-box-content">
                <ul class="cTextList">
								<?php
								if(! empty($discussions))
								{
									foreach($discussions as $item)
									{
								?>
									<li class="group-discussion-list">
									<a href="<?php echo CRoute::_('/index.php?option=com_community&view=groups&task=viewdiscussion&groupid=' . $item->groupid. '&topicid=' . $item->id ); ?>" class="title"><?php echo $item->title; ?></a>
									<div class="small">
									<?php
									if(! empty($item->commentorName))
									{ 
									echo JText::sprintf('CC DISCUSSION LAST REPLIED BY', '<a href="' . CUrlHelper::userLink( $item->lastreplied_by ) . '">' . $item->commentorName . '</a>' );
									} 
									else
									{ 
									echo JText::sprintf('CC DISCUSS STARTED BY' , '<a href="' . CUrlHelper::userLink( $item->creator ) . '">' . $item->creatorName . '</a>');
									} 
									?>
									</div>
									</li>                        
								<?php
											}//end for
									}//end if
								?>
                </ul>
            </div>
        </div>
		<!-- GROUP DISCUSSION SIDEBAR 1 -->
</div>
<!-- GROUP DISCUSSION SIDEBAR -->