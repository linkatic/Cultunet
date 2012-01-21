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

	$cm = '';
	if ($this->listfor == 'lj') { // list jobs
		$ptitle = '';
		if (isset($_GET['cn'])) $cn=$_GET['cn']; else $cn='';
		$link = ("index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&fr=lj&jobcat=". $this->categoryid."&Itemid=".$this->Itemid);
		if (isset($this->jobs[0]))if ($this->jobs[0]->cat_title != '') $ptitle = $this->jobs[0]->cat_title; else $ptitle = $_GET['cn'];	
		$ptitle = JText::_('JS_CATEGORY') . ' : ' . $ptitle;
	}else{ // list company jobs
		$cm = $this->cm;
		$link = ("index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&fr=cj&cd=". $this->companyid."&cm=".$cm."&Itemid=".$this->Itemid);
		if ($this->jobs[0]->companyname != '') $ptitle = $this->jobs[0]->companyname; else $ptitle = $cm;		
		$ptitle = JText::_('JS_ALL_JOBS_OF') . ' ' . $ptitle;
	}
	
 $jobcatlink = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$this->Itemid);

if (isset($this->userrole->rolefor)){
	if ($this->userrole->rolefor != ''){
		if ($this->userrole->rolefor == 2) // job seeker
			$allowed = true;
		else
			$allowed = false;
	}else{
		$allowed = true;
	}
}else $allowed = true; // user not logined

// echo 's '.$this->options;
?>


<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />
<?php if ($this->config['offline'] == '1'){ ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	<?php echo $this->config['title']; ?></td></tr>
	<tr><td height="25"></td></tr>
	<tr><td class="jsjobsmsg">
		<?php echo $this->config['offline_text']; ?>
	</td></tr>
</table>	
<?php }else{ ?>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<?php if ($this->config['cur_location'] == 1) {?>
	<tr><td height="5"></td></tr>
	<tr><td class="curloc">
		<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="<?php echo $jobcatlink; ?>" class="curloclnk"><?php echo JText::_('JS_JOB_CATEGORIES'); ?></a> > <?php echo JText::_('JS_JOBS_LIST_BY_CATEGORY'); ?>
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
	<?php if ($allowed == true) { ?>
		<tr><td height="3"></td></tr>
		<tr><td>
		<form action="<?php echo $link; ?>" method="post" name="adminForm">
		<?php
			require_once( 'job_filters.php' );
		?>	
		</form>
		</td></tr>
	<?php } ?>
	<tr><td height="3"></td></tr>
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo $ptitle; ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>

<?php
if ($allowed == true) { 

if ($this->totalresults != 0) {
	if ($this->sortlinks['sortorder'] == 'ASC')
		$img = "images/M_images/sort0.png";
	else
		$img = "images/M_images/sort1.png";
?>

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr class="<?php echo $this->theme['sortlinks']; ?>">
			<td class="<?php echo $this->theme['sortlinks']; ?>"  valign="center" align="center">&nbsp;&nbsp;<strong><?php echo JText::_('JS_SORT_BY'); ?> : </strong> 
				&nbsp;&nbsp;&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['title']; ?>"><?php echo JText::_('JS_TITLE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'title') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;&nbsp;|&nbsp;&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo JRoute::_($link.'&sortby='. $this->sortlinks['jobtype']); ?>"><?php echo JText::_('JS_JOBTYPE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;&nbsp;|&nbsp;&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobstatus']; ?>"><?php echo JText::_('JS_JOBSTATUS'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'jobstatus') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;&nbsp;|&nbsp;&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['company']; ?>"><?php echo JText::_('JS_COMPANY'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'company') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;&nbsp;|&nbsp;&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['country']; ?>"><?php echo JText::_('JS_COUNTRY'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'country') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;&nbsp;|&nbsp;&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('JS_SALARY_RANGE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;&nbsp;|&nbsp;&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('JS_DATEPOSTED'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?>
			</td>
		</tr>
		<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$days = $this->config['newdays'];
		$isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
		//$tdclass = array("odd", "even");
		$isodd =1;
		if ( isset($this->jobs) ){
		foreach($this->jobs as $job)	{ 
		$isodd = 1 - $isodd; ?>
		<tr height="20" class="<?php echo $tdclass[$isodd]; ?>" > <td colspan="5">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr><td height="3"></td></tr>
				<tr>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_TITLE'); ?>	</strong></td>
					<td class="maintext" colspan="3"><?php echo $job->title;
					if ($job->created > $isnew)
						echo "<font color='red'> ".JText::_('JS_NEW')." </font>";
					?></td>
				</tr>
				<tr>
					<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBTYPE'); ?>	</strong></td>
					<td class="maintext" width="30%"><?php echo $job->jobtype;//value -1 for array index ?></td>
					<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBSTATUS'); ?>	</strong></td>
					<td class="maintext" width="30%"><?php echo $job->jobstatus; ?></td>
				</tr>
				<tr>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_COMPANY'); ?>	</strong></td>
					<td class="maintext">
						<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=2&md='.$job->companyid.'&jobcat='.$job->jobcategory.'&Itemid='.$this->Itemid; ?>
						<a href="<?php echo $link?>"><strong><?php echo $job->companyname; ?></strong></a>
					</td>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_COMPANYURL'); ?>	</strong></td>
					<td class="maintext"><a class="jplnks" href='<?php echo $job->url; ?>' target='_blank'><?php echo $job->url; ?></a></td>
				</tr>
				<tr>
					<?php if($job->countryname != '') { ?>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_COUNTRY'); ?>	</strong></td>
						<td class="maintext"><?php echo $job->countryname; ?></td>
					<?php }else{ echo '<td></td><td></td>'; } ?>
					 <?php if ( $job->jobsalaryrange != '' ) { 
								if ($job->jobsalaryrange != 0){ ?>
									<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_SALARY_RANGE'); ?>	</strong></td>
									<td class="maintext"><?php $salary = $this->config['currency'] . $job->rangestart . ' - ' . $this->config['currency'] . $job->rangeend . ' /month';
									echo $salary; ?></td>
							<?php } 
							}?>
				</tr>
				<tr>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_DATEPOSTED'); ?>	</strong></td>
					<td class="maintext"><?php echo $job->created; ?></td>
					<?php if ($job->noofjobs != 0){ ?>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_NOOFJOBS'); ?>	</strong></td>
					<td class="maintext"><?php echo $job->noofjobs; ?></td>
					<?php } ?>
				</tr>
				<tr>
					<td></td><td></td>
					<td class="maintext" align="right"> 
						<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&fr='.$this->fr.'&vj=2&jobcat='.$job->jobcategory.'&oi='.$job->id.'&Itemid='.$this->Itemid; ?>
						<a href="<?php echo $link?>" class="pageLink"><strong><?php echo JText::_('JS_VIEW'); ?></strong></a>
					&nbsp;&nbsp;</td>
					<td class="maintext" align="left">&nbsp;&nbsp;
						<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_apply&fr='.$this->fr.'&aj=1&jobcat='.$job->jobcategory.'&bi='.$job->id.'&Itemid='.$this->Itemid; ?>
						<a href="<?php echo $link?>" class="pageLink"><strong><?php echo JText::_('JS_APPLYNOW'); ?></strong></a>
					</td>
				
				</tr>
				<tr><td height="3"></td></tr>
			</table>	
		</td></tr>
		<tr> <td colspan="5" height="1">	</td></tr>
		<?php
		}
		} ?>		
	</table>
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
	<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&fr='.$this->listfor.'&jobcat='.$this->jobs[0]->jobcategory.'&Itemid='.$this->Itemid); ?>" method="post">
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

