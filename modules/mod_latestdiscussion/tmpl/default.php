<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$html = "<div>";
if(!empty($latest)){
	$html .= "<ul style=\"padding: 0; margin: 0; list-style: none;\">";
	foreach($latest as $data)
	{
		$gid = $data->groupid;
		$creator = CFactory::getUser( $data->creator );
		
		$model	=& CFactory::getModel( 'groups' );
		$group = $model->getGroup($gid);
		$table	=& JTable::getInstance( 'Group' , 'CTable' );
		$table->load( $data->groupid );
		
		if(!in_array($data->groupid, $done_group))
		{			
			$groupstr[$gid] = "<li style=\" background: none; padding: 5px 0; border-bottom: solid 1px #cccccc;\">";				
			if($showavatar == 1){
				$groupstr[$gid] .= "<div style=\"float: left;\">";
					$groupstr[$gid] .= "<img src=\"".$table->getAvatar( 'thumb' )."\" alt=\"". CStringHelper::escape( $group->name )."\" style=\"width: 32px; padding: 2px; border: solid 1px #ccc;\"/>";
				$groupstr[$gid] .= "</div>";
			}				
			
			array_push($done_group, $gid);
		}
		else
		{
			if($repeatAvatar)
			{
				$groupstr[$gid] .= "</li><li style=\" background: none; padding: 5px 0; border-bottom: solid 1px #cccccc;\">";	
				
				if($showavatar)
				{
					$groupstr[$gid] .= "<div style=\"float: left;\">";
						$groupstr[$gid] .= "<img src=\"".$table->getAvatar( 'thumb' )."\" alt=\"".CStringHelper::escape( $group->name )."\" style=\"width: 32px; padding: 2px; border: solid 1px #ccc;\"/>";
					$groupstr[$gid] .= "</div>";
				}
			}
			else
			{
				$groupstr[$gid] .= "<div style=\"margin-left: 42px; margin-top: 4px; margin-bottom: 4px; border-bottom: solid 1px #cccccc;\"></div>";
			}
		}
		
		$groupstr[$gid] .= "<div style=\"margin-left: 42px; line-height: normal;\">";
			$groupstr[$gid] .= "<a href=\"".CRoute::_("index.php?option=com_community&view=groups&task=viewdiscussion&groupid=".$gid."&topicid=".$data->id)."\">";
				$groupstr[$gid] .= $data->title;
			$groupstr[$gid] .= "</a>";
		$groupstr[$gid] .= "</div>";
		$groupstr[$gid] .= "<div style=\"margin-left: 42px; line-height: normal;\">";
			$groupstr[$gid] .= "<small>by <a href=\"".CRoute::_("index.php?option=com_community&view=profile&userid=".$data->creator)."\">". CStringHelper::escape( $creator->getDisplayName() ) ."</a> &nbsp;";
				$postscript = JText::sprintf( (cIsPlural($data->counter)) ? 'MOD_LATESTDISC REPLY MANY' : 'MOD_LATESTDISC REPLY', $data->counter);
			$groupstr[$gid] .= $postscript."</small>";
		$groupstr[$gid] .= "</div>";
		$groupstr[$gid] .= "<div style=\"clear: both;\"></div>";		
	}
	
	foreach($done_group as $gid){
		$groupstr[$gid] .= "</li>";
		$html .= $groupstr[$gid];
	}
	
	$html .= "</ul>";
}else{
	$html .= JText::_("MOD_LATESTDISC NO DISCUSSION");
}
$html .= "</div>";

echo $html;

?>
