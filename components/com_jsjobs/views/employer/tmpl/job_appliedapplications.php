<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/application/tmpl/job_appliedapplications.php
 ^ 
 * Description: template view for my job applied application
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 jimport('joomla.application.component.model');
 require_once( JPATH_BASE . '/includes/pageNavigation.php' );
 
 global $mainframe;

 if(isset($this->resume[0]))$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd='.$this->resume[0]->id.'&Itemid='.$this->Itemid;

?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />

<div id="community-wrap" class="comjobs">
	<h3><?php echo $this->config['title']; ?></h3>
	<div class="cSubmenu clrfix">
		<ul class="submenu">
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid=3"><?php echo JText::_('JS_ADD_OFERTA'); ?></a></li>
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid=3"><?php echo JText::_('JS_ADD_MIS_OFERTAS'); ?></a></li>
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid=3" class="active"><?php echo JText::_('JS_ADD_CVS_RECIBIDOS'); ?></a></li>
		</ul>
	</div>
</div>

<?php if ($this->config['offline'] == '1'){ ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	<?php echo $this->config['title']; ?></td></tr>
	<tr><td height="25"></td></tr>
	<tr><td class="jsjobsmsg">
		<?php echo $this->config['offline_text']; ?>
	</td></tr>
</table>	
<?php }else{ ?>
<?php

/*
 * 
 * Ocultamos la barra de administración específica del componente
 * 
 */
 
 /*

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<?php if ($this->config['cur_location'] == 1) {?>
	<tr><td class="curloc">
		<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_APPLIED_RESUME'); ?></a> > <?php echo JText::_('JS_JOB_APPLIED_APPLICATIONS'); ?>
	</td></tr>
	<?php } ?>
	<tr><td>
		<?php 
			if (sizeof($this->employerlinks) != 0){
				echo '<div id="toplinks"><ul>';
				foreach($this->employerlinks as $lnk)	{ ?>
					<span <?php if($lnk[2] == 1)echo 'class="first"'; elseif($lnk[2] == -1)echo 'class="last"';  ?>>
						<a href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
					</span>
				<?php }
				echo '<ul></div>';			
			}
		?>
	</td></tr>	
	<tr><td height="3"></td></tr>
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo JText::_('JS_JOB_APPLIED_APPLICATIONS'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>

*/
?>

<div id="table-jobs">

<?php
if ($this->userrole->rolefor == 1) { // employer

if ($this->totalresults != 0) {
	if ($this->sortlinks['sortorder'] == 'ASC')
		$img = "images/M_images/sort0.png";
	else
		$img = "images/M_images/sort1.png";
?>

	<table cellpadding="1" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr  class="<?php echo $this->theme['sortlinks']; ?>" height="17" valign="center">
			<td><strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['name']; ?>"><?php echo JText::_('JS_NAME'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'name') { ?> <img src="<?php echo $img ?>"> <?php } ?></td>
			<td><strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['category']; ?>"><?php echo JText::_('JS_CATEGORY'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'category') { ?> <img src="<?php echo $img ?>"> <?php } ?></td>
			<td><strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('JS_JOB_PREFFERD'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?></td>
			<td><strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobsalaryrange']; ?>"><?php echo JText::_('JS_SALARY'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'jobsalaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?></td>
			<td><strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['apply_date']; ?>"><?php echo JText::_('JS_APPLIED_DATE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'apply_date') { ?> <img src="<?php echo $img ?>"> <?php } ?></td>
			<td><strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['email']; ?>"><?php echo JText::_('JS_EMAIL'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'email') { ?> <img src="<?php echo $img ?>"> <?php } ?></td>
			<td class="<?php echo $this->theme['sortlinks']; ?>"><strong>	</strong></td>
		</tr>
		<tr>
		<td colspan="5"></td>
		</tr>
			<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		if ( isset($this->resume) ){
		foreach($this->resume as $app)	{ 
			$isodd = 1 - $isodd; ?>
			<tr class="<?php echo $tdclass[$isodd]; ?>">
			<td class="maintext"><?php echo $app->first_name.' '.$app->last_name; ?></td>
			<td class="maintext"><?php echo $app->cat_title; ?></td>
			<td class="maintext"><?php echo $app->jobtypetitle; ?></td>
			<td class="maintext"><?php echo $this->config['currency'] . $app->rangestart . ' - ' . $this->config['currency'] . $app->rangeend ?></td>
			<td class="maintext"><?php echo date('d-m-Y',strtotime($app->apply_date)); ?></td>
			<td class="maintext"><?php echo $app->email_address; ?></td>
			<td class="maintext">
				<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=2&rd='.$app->appid.'&bd='.$app->id.'&Itemid='.$this->Itemid; ?>
				<a class="pageLink" href="<?php echo $link?>"><?php echo JText::_('JS_RESUME');?></a>
			</td>
			
		</tr>
		<?php 
		}
		}
		?>		
		<tr><td height="15"></td></tr>
	</table>

<?php
// paging		
$total=$this->totalresults;
$limit=$this->limit;
$limitstart=$this->limitstart;

/* $limit = $limit ? $limit : $mainframe->getCfg('list_limit');
if ( $total <= $limit ) { 
    $limitstart = 0;
} 
*/
 $pageNav = new mosPageNav( $total, $limitstart, $limit );
?>
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd='.$this->resume[0]->id.'&Itemid='.$this->Itemid); ?>" method="post">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
            <tr><td align="center" colspan="2">
                <?php
                    echo $pageNav->writePagesLinks( $link );
                ?>
            </td></tr>
            
            <tr><td align="left">
			<?php echo JText::_('JS_DISPLAY_#'); ?>
                    <?php
                        echo $pageNav->getLimitBox( $link );
                    ?>
            </td>
			<td align="right">
				<?php
					echo $pageNav->writePagesCounter(); 
				?>
			</td></tr>	
</table>
</form>	
<?php
}else{ // no result found in this category
	echo JText::_('JS_RESULT_NOT_FOUND');
}

} else{ // not allowed job posting
echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');
}	
}//ol
?>	
</div><!-- Fin table-jobs -->
<div width="100%">
<?php 
if($this->config['fr_cr_txsh']) {
	echo 
	'<table width="100%" style="table-layout:fixed;">
		<tr><td height="15"></td></tr>
		<tr><td style="vertical-align:top;" align="center">'.$this->config['fr_cr_txa'].$this->config['fr_cr_txb'].'</td></tr>
	</table>';
}	
?>
</div>

