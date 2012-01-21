<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/application/tmpl/listjobs.php
 ^ 
 * Description: template view for list jobs of a category
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 jimport('joomla.application.component.model');

 global $mainframe;
 require_once( JPATH_BASE . '/includes/pageNavigation.php' );

 $link = "index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid=".$this->Itemid;
 $resumecatlink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$this->Itemid;
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />
<div id="community-wrap" class="comjobs">
	<h3><?php echo $this->config['title']; ?></h3>
	<div class="cSubmenu clrfix">
		<ul class="submenu">
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&vm=2"><?php echo JText::_('JS_ADD_CV'); ?></a></li>
			<?php /* <li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&Itemid=3" class="active"><?php echo JText::_('JS_ADD_CARTA'); ?></a></li> */ ?>
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid=3" class="active"><?php echo JText::_('JS_CV_GUARDADOS'); ?></a></li>
			<?php /* <li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid=3"><?php echo JText::_('JS_CARTAS_GUARDADAS'); ?></a></li> */ ?>
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid=3"><?php echo JText::_('JS_SOLICITUDES'); ?></a></li>
			<?php /* <li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=my_jobsearches&Itemid=3"><?php echo JText::_('JS_OFERTAS_PREFERIDAS'); ?></a></li> */ ?>
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
	<tr><td height="0"></td></tr>
	<tr><td class="curloc">
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_MY_RESUME'); ?>
	</td></tr>
	<?php } ?>
	<tr><td>
		<?php 
			$cutomlink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&vm=2&Itemid='.$this->Itemid;
			$cutomlinktext = JText::_('JS_ADD_RESUME');
			$count = 0;
			if (sizeof($this->jobseekerlinks) != 0){
				echo '<div id="toplinks"><ul>';
				foreach($this->jobseekerlinks as $lnk)	{ 
					if ($count == 1) { ?>
						<span>
							<a href="<?php echo $cutomlink; ?>"> <?php echo $cutomlinktext; ?></a>
						</span>
					<?php }	?>
					<span <?php if($lnk[2] == 1)echo 'class="first"'; elseif($lnk[2] == -1)echo 'class="last"';  ?>>
						<a href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
					</span>
				<?php $count++;	
				}
				echo '<ul></div>';			
			} 
	?>
	</td></tr>	
	<tr><td height="3"></td></tr>
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo JText::_('JS_MY_RESUME'); ?>  
	</td></tr>
	<tr><td height="3"></td></tr>
</table>
*/
?>
<?php
if ($this->userrole->rolefor == 2) { // job seeker

if ($this->totalresults != 0) {
	if ($this->sortlinks['sortorder'] == 'ASC')
		$img = "images/M_images/sort0.png";
	else
		$img = "images/M_images/sort1.png";
?>
<div id="table-jobs">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr class="<?php echo $this->theme['sortlinks']; ?>">
			<td class="<?php echo $this->theme['sortlinks']; ?>"  valign="center" align="center">&nbsp;&nbsp;<strong><?php echo JText::_('JS_SORT_BY'); ?> : </strong> 
				&nbsp;&nbsp;&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['application_title']; ?>"><?php echo JText::_('JS_TITLE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'application_title') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;&nbsp;|&nbsp;&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('JS_JOBTYPE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;&nbsp;|&nbsp;&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('JS_SALARY_RANGE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;&nbsp;|&nbsp;&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('JS_DATEPOSTED'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?>
			</td>
		</tr>
		<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isnew = date("Y-m-d H:i:s", strtotime("-$this->config['newdays'] days"));
		//$tdclass = array("odd", "even");
		$isodd =1;
		if ( isset($this->resumes) ){
		foreach($this->resumes as $resume)	{ 
		$isodd = 1 - $isodd; ?>
		<tr height="20" class="<?php echo $tdclass[$isodd]; ?>" > <td colspan="5">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_TITLE'); ?>	</strong></td>
					<td class="maintext" colspan="3"><?php echo $resume->application_title;	?></td>
				</tr>
				<tr>
					<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_JOB_CATEGORY'); ?>	</strong></td>
					<td class="maintext" width="30%"><?php echo $resume->cat_title;//value -1 for array index ?></td>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_SALARY_RANGE'); ?>	</strong></td>
					<td class="maintext"><?php $salary = $this->config['currency'] . $resume->rangestart . ' - ' . $this->config['currency'] . $resume->rangeend . JText::_('JS_PERMONTH');
									echo $salary; ?></td>
				</tr>
				<tr>
					<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBTYPE'); ?>	</strong></td>
					<td class="maintext" width="30%"><?php echo $resume->jobtypetitle;?></td>
					<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_EMAIL'); ?>	</strong></td>
					<td class="maintext" width="30%"><?php echo $resume->email_address; ?></td>
				</tr>
				<tr>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_CREATED'); ?>	</strong></td>
					<td class="maintext"><?php echo $resume->create_date; ?></td>
					<td class="maintext">&nbsp;</td>
					<td class="maintext"></td>
				</tr>
			</table>	
		</td></tr>
		<?php
		}
		} ?>		
	</table>
		<div class="jobstatusmsg">
		<?php
			if ($job->status == '0')
				echo JText::_('JS_STATUS') .' : ' . JText::_('JS_APPROVALWAITING');
				else if ($job->status == '-1')	
				echo JText::_('JS_STATUS') .' : ' . JText::_('JS_NOTAPPROVED');
		?>
	</div>
	<div class="botonera-jobs">
		<?php $link = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=1&rd='.$resume->id.'&Itemid='.$this->Itemid); ?>
		<?php //$link = JRoute::_($link); ?>
		<a href="<?php echo $link?>" class="pageLink"><?php echo JText::_('JS_VIEW'); ?></a>
		<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&vm=1&rd='.$resume->id.'&Itemid='.$this->Itemid; ?>
		<a href="<?php echo $link?>" class="pageLink"><?php echo JText::_('JS_EDIT'); ?></a>
	</div>
	<?php
	// paging		
	$total=$this->totalresults;
	$limit=$this->limit;
	$limitstart=$this->limitstart;
/*
	$limit = $limit ? $limit : $mainframe->getCfg('list_limit');
	if ( $total <= $limit ) { 
	    $limitstart = 0;
	} 
*/
	 $pageNav = new mosPageNav( $total, $limitstart, $limit );

	?>
	<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$this->Itemid); ?>" method="post">
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
echo JText::_('EA_YOU_ARE_NOT_ALLOWED_TO_VIEW');
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
