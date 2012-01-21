<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/application/tmpl/jobapply.php
 ^ 
 * Description: template view to apply for a job
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 global $mainframe;
 $user	=& JFactory::getUser();


$isShowButton = 1;
?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />
<div id="community-wrap" class="comjobs">
	<h3><?php echo $this->config['title']; ?></h3>
	<div class="cSubmenu clrfix">
		<ul class="submenu">
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&vm=2"><?php echo JText::_('JS_ADD_CV'); ?></a></li>
			<?php /* <li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&Itemid=3" class="active"><?php echo JText::_('JS_ADD_CARTA'); ?></a></li> */ ?>
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid=3"><?php echo JText::_('JS_CV_GUARDADOS'); ?></a></li>
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
	<tr><td class="curloc">
		<?php if ($this->aj == '1'){ $vm=2; ?>
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_CATEGORIES'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&fr=<?php echo $this->fr; ?>&jobcat=<?php echo $this->jobcat; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOBS_LIST_BY_CATEGORY'); ?></a> ><?php echo JText::_('JS_APPLYNOW'); ?>
		<?php }else if ($this->aj == '2'){ $vm=3; ?>
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_JOB'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_SEARCH_RESULT'); ?></a> > <?php echo JText::_('JS_APPLYNOW'); ?>
		<?php }else if ($this->aj == '3'){ $vm=5; ?>
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_NEWEST_JOBS'); ?></a> > <?php echo JText::_('JS_APPLYNOW'); ?>
		<?php } ?>
	</td></tr>
	<?php } ?>
	<tr><td>
		<?php 
			if (sizeof($this->jobseekerlinks) != 0){
				echo '<div id="toplinks"><ul>';
				foreach($this->jobseekerlinks as $lnk)	{ ?>
					<span class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>">
						<a href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
					</span>
				<?php }
				echo '<ul></div>';			
			} 
		?>
	</td></tr>	
	<tr><td height="3"></td></tr>
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo JText::_('JS_APPLYNOW'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>

*/ ?>
<?php
if ($this->userrole->rolefor == 2) { // job seeker

if ($this->totalresume > 0){ // Resume not empty

?>
<div id="form-jobseeker">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr><td colspan="4" height="15"></td></tr>
		<tr>
			<td align="center" class="<?php echo $this->theme['sectionheading']; ?>" colspan="4"><?php echo JText::_('JS_EMP_APP_INFO'); ?></td>
		</tr>
		<tr class="<?php echo $this->theme['odd']; ?>">
			<td></td>
			<td class="maintext" width="25%"><strong><?php echo JText::_('JS_MY_RESUME'); ?>	</strong></td>
			<td class="maintext"><?php echo $this->myresumes; ?></td>
			<td></td>
		</tr>
		<tr class="<?php echo $this->theme['odd']; ?>">
			<td></td>
			<td class="maintext" colspan="2" align="center"><font color="red"><strong>
			<?php /*  if ($this->myapplication->status == 0) { //waitng for approval
						echo JText::_('JS_EMP_APP_WAIT_APPROVAL'); 
						$isShowButton = 3;
				}else if ($this->myapplication->status == -1) {//reject
						echo JText::_('JS_EMP_APP_REJECT'); 
						$isShowButton = 4;
				}*/
			?>	
			</strong></font></td>
			<td></td>
		</tr>
		<tr><td colspan="4" height="15"></td></tr>
		<tr>
			<td align="center" class="<?php echo $this->theme['sectionheading']; ?>" colspan="4"><?php echo JText::_('JS_JOB_INFO'); ?></td>
		</tr>
		<tr class="<?php echo $this->theme['odd']; ?>">
			<td></td>
			<td class="maintext"><strong><?php echo JText::_('JS_TITLE'); ?>	</strong></td>
			<td class="maintext"><?php echo $this->job->title; 
			$days = $this->config['newdays'];
			$isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
			if ($this->job->created > $isnew)
				echo "<font color='red'> ".JText::_('JS_NEW')." </font>";
			?></td>
			<td></td>
		</tr>
		<tr class="<?php echo $this->theme['even']; ?>">
			<td></td>
			<td class="maintext"><strong><?php echo JText::_('JS_CATEGORY'); ?>	</strong></td>
			<td class="maintext"><?php echo $this->job->cat_title; ?></td>
			<td></td>
		</tr>
		<tr class="<?php echo $this->theme['odd']; ?>">
			<td></td>
			<td class="maintext"><strong><?php echo JText::_('JS_JOBTYPE'); ?>	</strong></td>
			<td class="maintext"><?php echo $this->job->jobtypetitle; ?></td>
			<td></td>
		</tr>
		<tr class="<?php echo $this->theme['even']; ?>">
			<td></td>
			<td class="maintext"><strong><?php echo JText::_('JS_JOBSTATUS'); ?>	</strong></td>
			<td class="maintext"><?php
				if (($this->job->jobstatus == 1) || ($this->job->jobstatus == 2))
					echo $this->job->jobstatustitle; 
				else{
					echo "<font color='red'><strong>" .$this->job->jobstatustitle . "</strong></font>"; 
					$isShowButton = 2;
				}
			?></td>
			<td></td>
		</tr>
		<?php if($this->job->companyname!="Cultunet") { ?>
		<tr class="<?php echo $this->theme['odd']; ?>">
			<td></td>
			<td class="maintext"><strong><?php echo JText::_('JS_COMPANY'); ?>	</strong></td>
			<td class="maintext">
					<?php if (isset($_GET['jobcat'])) $jobcat = $_GET['jobcat']; else $jobcat=null;
					$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm='.$vm.'&md='.$this->job->companyid.'&jobcat='.$jobcat.'&Itemid='.$this->Itemid; ?>
					<?php /* <a href="<?php echo $link?>"><strong><?php echo $this->job->companyname; ?></strong></a> */ ?>
					<?php echo $this->job->companyname; ?>
			</td>
			<td></td>
		</tr>
		<?php } ?>
		<tr class="<?php echo $this->theme['even']; ?>">
			<td></td>
			<td class="maintext"><strong><?php echo JText::_('JS_COMPANYURL'); ?>	</strong></td>
			<td class="maintext"><a class="jplnks" href='<?php echo $this->job->url; ?>' target='_blank'><?php echo $this->job->url; ?></a></td>
			<td></td>
		</tr>
		<tr class="<?php echo $this->theme['odd']; ?>">
			<td></td>
			<td class="maintext"><strong><?php echo JText::_('JS_LOCATION'); ?>	</strong></td>
			<td class="maintext"><?php echo $this->job->cityname; ?>
				<?php if ($this->job->countyname != '') echo ',  '.$this->job->countyname; ?>
				<?php if ($this->job->statename != '') echo ',  '.$this->job->statename; ?>
				<?php if ($this->job->countryname != '') echo ',  '.$this->job->countryname; ?>
			</td>
			<td></td>
		</tr>
		<?php /* $trclass=$this->theme['odd'];
			if($this->job->jobsalaryrange != 0){ 
			$trclass=$this->theme['even'];
			?>
			<tr class="<?php echo $this->theme['even']; ?>">
				<td></td>
				<td class="maintext"><strong><?php echo JText::_('JS_SALARY_RANGE'); ?>	</strong></td>
				<td class="maintext"><?php $salaryrange = $this->config['currency'] . $this->job->rangestart . ' - ' . $this->config['currency'] . $this->job->rangeend . ' /month';
					echo $salaryrange ?></td>
				<td></td>
			</tr>
		<?php 	} */?>
		<?php if($this->job->noofjobs != 0){ 
			if ($trclass == $this->theme['even']) $trclass = $this->theme['odd']; else $trclass = $this->theme['even'];
			?>
			<tr class="<?php echo $trclass ?>">
				<td></td>
				<td class="maintext"><strong><?php echo JText::_('JS_NOOFJOBS'); ?>	</strong></td>
				<td class="maintext"><?php echo $this->job->noofjobs ?></td>
				<td></td>
			</tr>
		<?php } 
		if ($trclass == $this->theme['even']) $trclass = $this->theme['odd']; else $trclass = $this->theme['even'];
		?>
		<tr class="<?php echo $trclass ?>">
			<td></td>
			<td class="maintext"><strong><?php echo JText::_('JS_DATEPOSTED'); ?>	</strong></td>
			<td class="maintext"><?php echo strftime('%d %B, %Y',strtotime($this->job->created)); ?></td>
			<td></td>
		</tr>
		<tr><td colspan="4" height="25"></td></tr>
		<tr>
			<td colspan="3" align="center">
			<?php if ($isShowButton == 1) { ?>
				<input type="submit" class="button" name="submit_app" onclick="document.adminForm.submit();"  value="<?php echo JText::_('JS_APPLYNOW'); ?>" /></td>
			<?php 
				}else if ($isShowButton == 2) { 
					echo "<font color='red'><strong>" . JText::_('JS_JOBSTATUS') . " : " . $jobstatus[$this->job->jobstatus-1] . "</strong></font>"; 
				}else if ($isShowButton == 3) { 
					echo "<font color='red'><strong>" . JText::_('JS_EMP_APP_WAIT_APPROVAL')  . "</strong></font>"; 
				}else if ($isShowButton == 4) { 
					echo "<font color='red'><strong>" . JText::_('JS_EMP_APP_REJECT')  . "</strong></font>"; 
				}
			?>
			</td>
			<td></td>
		</tr>
			<input type="hidden" name="view" value="application" />
			<input type="hidden" name="layout" value="static" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="jobapply" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="jobid" value="<?php echo $this->job->id; ?>" />
			<input type="hidden" name="oldcvid" value="<?php echo $this->myapplication->id; ?>" />
			<input type="hidden" name="apply_date" value="<?php if ($this->job->apply_date=='') { echo date('Y-m-d H:i:s'); } else { $this->job->apply_date; } ?>" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>
		
		
	</table>
<?php
}else{ // Employment application is empty
echo '<p align="center">'.JText::_('EA_EMP_APP_EMPTY');

//echo '<br><br>'.$this->emplinks['empapp'].'</p>';
}

} else{ // not allowed job posting
echo JText::_('EA_YOU_ARE_NOT_ALLOWED_TO_VIEW');
}
}//ol

?>
</div><!-- Fin form-jobseeker -->
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
