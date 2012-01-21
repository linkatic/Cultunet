<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/application/tmpl/myappliedjobs.php
 ^ 
 * Description: template view for my applied jobs
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 jimport('joomla.application.component.model');

 global $mainframe;
 require_once( JPATH_BASE . '/includes/pageNavigation.php' );

 $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid='.$this->Itemid;
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
			<li><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid=3" class="active"><?php echo JText::_('JS_SOLICITUDES'); ?></a></li>
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
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_MY_APPLIED_JOBS'); ?>
	</td></tr>
	<?php } ?>
	<tr><td>
		<?php 
			if (sizeof($this->jobseekerlinks) != 0){
				echo '<div id="toplinks"><ul>';
				foreach($this->jobseekerlinks as $lnk)	{ ?>
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
		<?php echo JText::_('JS_MY_APPLIED_JOBS'); ?>
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
			<td class="<?php echo $this->theme['sortlinks']; ?>" height="17" valign="center" align="center"><?php echo JText::_('JS_SORT_BY'); ?> :
				&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['title']; ?>"><?php echo JText::_('JS_TITLE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'title') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['category']; ?>"><?php echo JText::_('JS_CATEGORY'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'category') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('JS_JOBTYPE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobstatus']; ?>"><?php echo JText::_('JS_JOBSTATUS'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'jobstatus') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['company']; ?>"><?php echo JText::_('JS_COMPANY'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'company') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['country']; ?>"><?php echo JText::_('JS_COUNTRY'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'country') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				<?php /* &nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('JS_SALARY_RANGE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } */?>
				&nbsp;|&nbsp;<strong><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['apply_date']; ?>"><?php echo JText::_('JS_APPLIEDDATE'); ?></a></strong><?php if ($this->sortlinks['sorton'] == 'apply_date') { ?> <img src="<?php echo $img ?>"> <?php } ?>
			</td>
		</tr>
		<?php 
		$isnew = date("Y-m-d H:i:s", strtotime("-$this->config['newdays'] days"));
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		if ( isset($this->application) ){
		foreach($this->application as $app)	{ 
			$isodd = 1 - $isodd; ?>
			<tr class="<?php echo $tdclass[$isodd]; ?>"> <td colspan="5">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_TITLE'); ?>	</strong></td>
						<td class="maintext"><?php echo $app->title; 
						if ($app->created > $isnew)
							echo "<font color='red'> ".JText::_('JS_NEW')." </font>";
						?></td>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_CATEGORY'); ?>	</strong></td>
						<td class="maintext"><?php echo $app->cat_title; ?></td>
						
					</tr>
					<tr>
						<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBTYPE'); ?>	</strong></td>
						<td class="maintext" width="30%"><?php echo $app->jobtypetitle; ?></td>
						<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBSTATUS'); ?>	</strong></td>
						<td class="maintext" width="30%"><?php echo $app->jobstatustitle; ?></td>
					</tr>
					<?php if($app->companyname!="Cultunet") { ?>
					<tr>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_COMPANY'); ?>	</strong></td>
						<td class="maintext">
							<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=4&md='.$app->companyid.'&Itemid='.$this->Itemid; ?>
							<?php /* <a href="<?php echo $link?>"><strong><?php echo $app->companyname; ?></strong></a> */ ?>
							<?php echo $app->companyname; ?>
						</td>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_COMPANYURL'); ?>	</strong></td>
						<td class="maintext"><a class="jplnks" href='<?php echo $app->url; ?>' target='_blank'><?php echo $app->url; ?></a></td>
					</tr>
					<?php } ?>
					<?php /*
					<tr>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_COUNTRY'); ?>	</strong></td>
						<td class="maintext"><?php echo $app->countryname; ?></td>
						<?php if ( $app->jobsalaryrange != 0 ) {
								if ($app->jobsalaryrange != 0){ ?>
									<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_SALARY_RANGE'); ?>	</strong></td>
									<td class="maintext"><?php $salary = $this->config['currency'] . $app->rangestart . ' - ' . $this->config['currency'] . $app->rangeend . JText::_('JS_PERMONTH');
								echo $salary; ?></td>
							<?php } 
							} ?>
					</tr>
					*/
					?>
					<tr>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_DATEPOSTED'); ?>	</strong></td>
						<td class="maintext"><?php echo strftime('%d %B, %Y',strtotime($app->created)); ?></td>
						<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_APPLIEDDATE'); ?>	</strong></td>
						<td class="maintext"><?php echo strftime('%d %B, %Y',strtotime($app->apply_date)); ?></td>
					</tr>
				</table>	
			</td></tr>
		<?php 
		}
		}?>		
	</table>
	<?php
	/*
	<div class="botonera-jobs">
		<?php $link = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=4&oi='.$app->id.'&Itemid='.$this->Itemid); ?>
		<?php //$link = JRoute::_($link); ?>
		<a href="<?php echo $link?>" class="pageLink"><?php echo JText::_('JS_VIEW'); ?></a>
	</div>
	*/
	?>

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

//$link = 'abc';
?>
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid='.$this->Itemid); ?>" method="post">
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

