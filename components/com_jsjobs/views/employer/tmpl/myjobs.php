<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/application/tmpl/myjobs.php
 ^ 
 * Description: template view for my jobs
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.component.model');
 require_once( JPATH_BASE . '/includes/pageNavigation.php' );
 global $mainframe;
 $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$this->Itemid;

?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />

<div id="community-wrap" class="comjobs">
	<h3><?php echo $this->config['title']; ?></h3>
	<div class="cSubmenu clrfix">
		<ul class="submenu">
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid=3"><?php echo JText::_('JS_ADD_OFERTA'); ?></a></li>
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid=3" class="active"><?php echo JText::_('JS_ADD_MIS_OFERTAS'); ?></a></li>
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid=3"><?php echo JText::_('JS_ADD_CVS_RECIBIDOS'); ?></a></li>
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
<?php } else{ ?>

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
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_MY_JOBS'); ?>
	</td></tr>
	<?php } ?>
	<tr nowrap><td >
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
		<?php echo JText::_('JS_MY_JOBS'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>

*/
?>


<?php
if ($this->userrole->rolefor == 1) { // employer

if ($this->totalresults != 0) {
	if ($this->sortlinks['sortorder'] == 'ASC')
		$img = "images/M_images/sort0.png";
	else
		$img = "images/M_images/sort1.png";

?>
<div id="table-jobs">
<form action="index.php" method="post" name="adminForm">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr  class="<?php echo $this->theme['sortlinks']; ?>" valign="middle">
			<td valign="center" class="<?php echo $this->theme['sortlinks']; ?>" align="center" ><?php echo JText::_('JS_SORT_BY'); ?> : 
				&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['title']; ?>"><?php echo JText::_('JS_TITLE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'title') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['category']; ?>"><?php echo JText::_('JS_CATEGORY'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'category') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('JS_JOBTYPE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobstatus']; ?>"><?php echo JText::_('JS_JOBSTATUS'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'jobstatus') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['company']; ?>"><?php echo JText::_('JS_COMPANY'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'company') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['country']; ?>"><?php echo JText::_('JS_COUNTRY'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'country') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('JS_SALARY_RANGE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('JS_DATEPOSTED'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?>
			</td>
		</tr>
		
		<?php 
		$days = $this->config['newdays'];
		$isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		if ( isset($this->jobs) ){
		foreach($this->jobs as $job)	{ 
			$isodd = 1 - $isodd; ?>
			<tr class="<?php echo $tdclass[$isodd]; ?>"> <td colspan="5">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_TITLE'); ?>	</strong></td>
						<td class="maintext"><?php echo $job->title;
						if ($job->created > $isnew)
							echo "<font color='red'> ".JText::_('JS_NEW')." </font>";
						?></td>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_CATEGORY'); ?>	</strong></td>
						<td class="maintext"><?php echo $job->cat_title; ?></td>
						
					</tr>
					<tr>
						<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBTYPE'); ?>	</strong></td>
						<td class="maintext" width="30%"><?php echo $job->jobtypetitle; ?></td>
						<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBSTATUS'); ?>	</strong></td>
						<td class="maintext" width="30%"><?php echo $job->jobstatustitle; ?></td>
					</tr>
					<tr>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_COMPANY'); ?>	</strong></td>
						<td class="maintext">
							<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&md='.$job->companyid.'&Itemid='.$this->Itemid; ?>
							<?php /* <a href="<?php echo $link?>"><strong><?php echo $job->companyname; ?></strong></a> */ ?>
							<?php echo $job->companyname; ?>
						</td>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_COMPANYURL'); ?>	</strong></td>
						<td class="maintext"><a class="jplnks" href='<?php echo $job->url; ?>' target='_blank'><?php echo $job->url; ?></a></td>
					</tr>
					<tr>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_COUNTRY'); ?>	</strong></td>
						<td class="maintext"><?php echo $job->countryname; ?></td>
						<?php if ($job->jobsalaryrange != 0){ ?>
									<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_SALARY_RANGE'); ?>	</strong></td>
									<td class="maintext"><?php $salary = $this->config['currency'] . $job->rangestart . ' - ' . $this->config['currency'] . $job->rangeend . ' /month';
									echo $salary; ?></td>
							<?php  
							} ?>
					</tr>
					<tr>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_DATEPOSTED'); ?>	</strong></td>
						<td class="maintext"><?php echo $job->created; ?></td>
						<?php if ($job->noofjobs != 0){ ?>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_NOOFJOBS'); ?>	</strong></td>
						<td class="maintext"><?php echo $job->noofjobs; ?></td>
						<?php } ?>
					</tr>
				</table>	
			</td></tr>
		<?php 
		}
		}?>		
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
		<?php 
		/*
		<?php $link = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=1&oi='.$job->id.'&Itemid='.$this->Itemid); ?>
		<?php //$link = JRoute::_($link); ?>
		<a href="<?php echo $link?>" class="pageLink"><?php echo JText::_('JS_VIEW'); ?></a>
		*/
		?>
		<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid=4&bd='.$job->id.'&Itemid='.$this->Itemid; ?>
		<a href="<?php echo $link?>" class="pageLink"><?php echo JText::_('JS_EDIT'); ?></a>
		<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&task=deletejob&Itemid=4&bd='.$job->id.'&Itemid='.$this->Itemid; ?>
		<a href="<?php echo $link?>" class="pageLink"><?php echo JText::_('JS_DELETE'); ?></a>
	</div>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="deletejob" />
			<input type="hidden" id="id" name="id" value="" />
			<input type="hidden" name="boxchecked" value="0" />

	</form>
	<div class="clearfix">&nbsp;</div>

<?php

// paging		
		$rows = $this->jobs;
$total=$this->totalresults;
$limit=$this->limit;
$limitstart=$this->limitstart;
/*$limit = $limit ? $limit : $mainframe->getCfg('list_limit');
if ( $total <= $limit ) { 
    $limitstart = 0;
} */
 $pageNav = new mosPageNav( $total, $limitstart, $limit );

?>
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$this->Itemid); ?>" method="post">
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
</div>

