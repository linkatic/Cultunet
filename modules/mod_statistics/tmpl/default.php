<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<style type="text/css">
<!--
#jSocStats div  { float: left; width: 100%; display: inline; padding: 0; margin: 0 0 3px 0; cursor: default; }
#jSocStats span { float: left; width: 100px; display: inline; padding: 0; margin: 0; }
-->
</style>
<div style="border-bottom: 1px solid #ccc; margin: 4px 0; font-weight: 700;">
	<?php echo JText::_('MOD_LATESTDISC STATISTICS');?>
</div>
<div id="jSocStats">
<?php
	foreach($stats as $stat)
	{
		switch($stat)
		{
			case 't_members':
				$name = JText::_("MOD_LATESTDISC MEMBERS");
				$total = $params->get('t_members');
				break;
			case 't_groups':
				$name = JText::_("MOD_LATESTDISC GROUPS");
				$total = $params->get('t_groups');
				break;
			case 't_discussions':
				$name = JText::_("MOD_LATESTDISC DISCUSSION");
				$total = $params->get('t_discussions');
				break;
			case 't_albums':
				$name = JText::_("MOD_LATESTDISC ALBUMS");
				$total = $params->get('t_albums');
				break;
			case 't_photos':
				$name = JText::_("MOD_LATESTDISC PHOTOS");
				$total = $params->get('t_photos');
				break;
			case 't_videos':
				$name = JText::_("MOD_LATESTDISC VIDEOS");
				$total = $params->get('t_videos');
				break;
			case 't_bulletins':
				$name = JText::_("MOD_LATESTDISC BULLETINS");
				$total = $params->get('t_bulletins');
				break;
			case 't_activities':
				$name = JText::_("MOD_LATESTDISC ACTIVITIES");
				$total = $params->get('t_activities');
				break;
			case 't_walls':
				$name = JText::_("MOD_LATESTDISC WALL POSTS");
				$total = $params->get('t_walls');
				break;
			case 'genders':
				$male = JText::_("MOD_LATESTDISC MALES");
				$female = JText::_("MOD_LATESTDISC FEMALES");
				$unspecified = JText::_("MOD_LATESTDISC UNSPECIFIED");						
				$total_males = $params->get('t_gender_males');
				$total_females = $params->get('t_gender_females');
				$total_unspecified = $params->get('t_gender_unspecified');
				break;
		}
		if($stat == "genders")
		{
			if($params->get('genders_male', 0))
			{
?>
	        <div title="<?php echo JText::_("MOD_LATESTDISC TOTAL") . " " . $male; ?> : <?php echo $total_males; ?>">
	            <span><?php echo JText::_("MOD_LATESTDISC TOTAL") . " " . $male; ?></span> : <?php echo $total_males; ?>
	        </div>
<?php 
			}
			if($params->get('genders_female', 0))
			{
?>
	        <div title="<?php echo JText::_("MOD_LATESTDISC TOTAL") . " " . $female; ?> : <?php echo $total_females; ?>">
	            <span><?php echo JText::_("MOD_LATESTDISC TOTAL") . " " . $female; ?></span> : <?php echo $total_females; ?>
	        </div>
<?php 
			}
			if($params->get('genders_unspecified', 0)){
?>
	        <div title="<?php echo JText::_("MOD_LATESTDISC TOTAL") . " " . $unspecified; ?> : <?php echo $total_unspecified; ?>">
	            <span><?php echo JText::_("MOD_LATESTDISC TOTAL") . " " . $unspecified; ?></span> : <?php echo $total_unspecified; ?>
	        </div>
<?php 
			}
		}else{
?>
        <div title="<?php echo JText::_("MOD_LATESTDISC TOTAL") . " " . $name; ?> : <?php echo $total; ?>">
            <span><?php echo JText::_("MOD_LATESTDISC TOTAL") . " " . $name; ?></span> : <?php echo $total; ?>
        </div>
<?php
		}
	}
?>
</div>
<div style="clear:both"></div>