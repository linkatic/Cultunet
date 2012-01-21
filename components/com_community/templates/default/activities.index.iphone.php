<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 */
defined('_JEXEC') or die();


// Get the configuration object.
$config		=& CFactory::getConfig();
$iphoneApps	= $config->get( 'iphoneactivitiesapps' );



// 
$tmpIphoneApps	= explode(',', $iphoneApps);
$tmpfilteredAct	= array();
$filteredAct	= array();
//$arrIphoneApps	= array_fill_keys( $tmpIphoneApps , 'x' );

$emptyArr		= array_fill(0, count($tmpIphoneApps), 'x');
$arrIphoneApps	= array_combine($tmpIphoneApps, $emptyArr);




//process the 2-passes filtering here
/**
 * If pre->title == cur->title, we take the cur->title and continue with cur->content
 *
 */  



if(! empty($arrIphoneApps)) 
{
	foreach($activities as $act)
	{
		if($act->type == 'content')
		{
			if(array_key_exists($act->app, $arrIphoneApps))
			{
				$tmpfilteredAct[] = $act;
			}
		}
		else
		{
			$tmpfilteredAct[] = $act;
		}
	}
	
 	$previousType	= 'title'; //default to title. since we know....the title will alway be the day / date.
 	$previousAct	= array();
	if(! empty($tmpfilteredAct))
	{
		foreach($tmpfilteredAct as $act)		
		{
			if($act->type == 'title' && $previousType == 'title')
			{				 
				$previousType	= $act->type;
				$previousAct	= $act;							
			}
			else
			{
				if((! empty($previousAct)) && ($previousAct->type == 'title') && ($act->type == 'content'))
				{
					$filteredAct[]	= $previousAct;
					$filteredAct[]	= $act;					
				}
				else if ((! empty($previousAct)) && ($previousAct->type == 'content') && ($act->type == 'content'))
				{
					$filteredAct[]	= $act;				
				}
				
				$previousType	= $act->type;
				$previousAct	= $act;					
			}					
		}		
	}		
	
}
else
{
	$filteredAct	= $activities;
}


?>
<div class="appsBoxTitle"><?php echo JText::_('CC IPHONE LATEST ACTIVITIES'); ?></div>
<?php foreach($filteredAct as $act): ?>
<?php if($act->type =='title'): ?>
<div class="ctitle iphone" style="font-weight: bold;"><?php echo $act->title; ?></div>
<?php else: $actor = CFactory::getUser($act->actor); ?>
<div id="<?php echo $idprefix; ?>profile-newsfeed-item<?php echo $act->id; ?>" class="newsfeed-item">
	<table style="overflow: hidden; z-index: -1;" cellpadding="3" cellspacing="0"><tr>
		<?php
		if($config->get('showactivityavatar'))
		{
		?>
		<td width="55" valign="top">
			<?php	
			if(!empty($actor->id))
			{
			?>
				<a href="<?php echo CUrlHelper::userLink($actor->id); ?>"><img class="avatar" src="<?php echo $actor->getThumbAvatar(); ?>" width="45" border="0" alt=""/></a>
			<?php
			}
			else
			{
			?>
				<img class="avatar" src="<?php echo $actor->getThumbAvatar(); ?>" width="45" border="0" alt=""/>
			<?php
			}
			?>
		</td>
		<?php
		}
		?>
		
		<td valign="top">
			<?php echo $act->title; ?>
		</td>
		
		<td class="act-date" width="50" style="">
			<?php echo $act->created; ?>
		</td>
		
		<!--
		<?php if($isMine): ?>
		<td width="20">
			<a class="remove" onclick="jax.call('community', 'activities,ajaxHideActivity' , '<?php echo $my->id; ?>' , '<?php echo $act->id; ?>');" href="javascript:void(0);">
				<?php echo JText::_('CC HIDE');?>
			</a>
		</td>
		<?php endif; ?>
		-->
	</tr></table>
	<a style="width: 100%; z-index: 1000; cursor: pointer; position: absolute; top: 0; left: 0; height: 100%; display: block; text-decoration: none;" href="<?php echo CUrlHelper::userLink($actor->id); ?>">&nbsp;</a>
</div>
<?php endif; ?>
<?php endforeach; ?>
