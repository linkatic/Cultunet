<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/application/tmpl/jobsearchresults.php
 ^ 
 * Description: template view job search results
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 jimport('joomla.application.component.model');
 global $mainframe;
JHTML::_('behavior.formvalidation');
 require_once( JPATH_BASE . '/includes/pageNavigation.php' );

 $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='.$this->Itemid;
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

<script language="javascript">
function myValidate(f) {
		if (document.formvalidator.isValid(f)) {
                //f.check.value='<?php echo JUtility::getToken(); ?>';//send token
                //returnvalue = true; 
        }
        else {
                alert('Search name is not acceptable.  Please retry.');
				return false;
        }
		return true;
}

function showsavesearch(val) {
		var ie= ((document.all)&&(!document.layers)) ? true : false;
		var ns= ((document.layers)&&(!document.getElementById)) ? true : false;
		var moz= ((document.getElementById)&&(!document.all)) ? true : false;
	
		if (val == 1){
			if(ie)
			{
				document.all['savesearch'].style.position = "relative";
				document.all['savesearch'].style.visibility="visible";
			}
			if(ns) {document.layers['savesearch'].visibility="show";}
			if(moz)
			{
				document.getElementById('savesearch').style.position = "relative";	
				document.getElementById('savesearch').style.visibility="visible";
			}
			if(ie)
			{
				document.all['savethis'].style.position = "absolute";
				document.all['savethis'].style.visibility="hidden";
			}
			if(ns) {document.layers['savethis'].visibility="hide";}
			if(moz)
			{
				document.getElementById('savethis').style.position = "absolute";	
				document.getElementById('savethis').style.visibility="hidden";
			}
		}else{	
			if(ie)
			{
				document.all['savethis'].style.position = "relative";
				document.all['savethis'].style.visibility="visible";
			}
			if(ns) {document.layers['savethis'].visibility="show";}
			if(moz)
			{
				document.getElementById('savethis').style.position = "relative";	
				document.getElementById('savethis').style.visibility="visible";
			}
			if(ie)
			{
				document.all['savesearch'].style.position = "absolute";
				document.all['savesearch'].style.visibility="hidden";
			}
			if(ns) {document.layers['savesearch'].visibility="hide";}
			if(moz)
			{
				document.getElementById('savesearch').style.position = "absolute";	
				document.getElementById('savesearch').style.visibility="hidden";
			}
		
		}

}
</script>

<!-- <table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<tr><td><table border="0" width="100%" cellpadding="0" cellspacing="0" >
	<tr>
		<?php if ($this->config['cur_location'] == 1) {?>
		<td class="curloc" valign="bottom">
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_JOB'); ?></a> > <?php echo JText::_('JS_JOB_SEARCH_RESULT'); ?>
		</td>
		<?php } ?>
		<?php if ($this->config['search_job_showsave'] == 1) {?>
			<?php if (($this->uid) && ($this->userrole->rolefor)) {?>
				<td id="savethis" width="30" align="right">
						<input type="button" class="button" onclick="showsavesearch(1);" value="<?php echo JText::_('JS_SAVE_THIS_SEARCH'); ?>">
				</td>
			<?php } ?>
		<?php } ?>
	</tr>
		<?php if ($this->config['search_job_showsave'] == 1) {?>
			<?php if (($this->uid) && ($this->userrole->rolefor)) {?>
				<form action="index.php" method="post" name="adminForm" id="adminForm" onsubmit="return myValidate(this);">
				<tr id="savesearch">
					<td colspan="2"> <?php echo JText::_('JS_SAVE_THIS_SEARCH'); ?> &nbsp;: &nbsp;&nbsp;<input class="inputbox required" type="text" name="searchname" size="20" maxlength="30"  />
					&nbsp;&nbsp;&nbsp;<input type="submit" class="button validate" value="<?php echo JText::_('JS_SAVE'); ?>">
					</td>
				</tr>
				<input type="hidden" name="option" value="<?php echo $option; ?>" />
				<input type="hidden" name="task" value="savejobsearch" />
				</form>	
			<?php } ?>
		<?php } ?>
	</table></td></tr>
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
		<?php echo JText::_('JS_JOB_SEARCH_RESULT'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table> -->

<?php
if (isset($this->userrole->rolefor)){
	if ($this->userrole->rolefor != ''){
		if ($this->userrole->rolefor == 2 || $this->userrole->rolefor == 1) // job seeker o employer
			$allowed = true;
		else
			$allowed = false;
	}else{
		$allowed = true;
	}
}else $allowed = true; // user not logined
if ($allowed == true) { 

if ($this->totalresults != 0) {
	if ($this->sortlinks['sortorder'] == 'ASC')
		$img = "images/M_images/sort0.png";
	else
		$img = "images/M_images/sort1.png";

?>

	<h3><?php echo $this->config['title']; ?></h3>
	<div class="pages-links-results">
		<span class="xlistings"><?php echo JText::_('JS_JOB_SEARCH_RESULT'); ?></span>
	</div>
	<div id="results_search_jobs">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr  class="<?php echo $this->theme['sortlinks']; ?>">
			<td class="<?php echo $this->theme['sortlinks']; ?>" height="17" valign="center" align="center" ><strong><?php echo JText::_('JS_SORT_BY'); ?> :</strong> 
				&nbsp;<span class="slink"><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['title']; ?>"><?php echo JText::_('JS_TITLE'); ?></a></span><?php if ($this->sortlinks['sorton'] == 'title') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<span class="slink"><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['category']; ?>"><?php echo JText::_('JS_CATEGORY'); ?></a></span><?php if ($this->sortlinks['sorton'] == 'category') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<span class="slink"><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('JS_JOBTYPE'); ?></a></span><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				<?/* &nbsp;|&nbsp;<span class="slink"><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobstatus']; ?>"><?php echo JText::_('JS_JOBSTATUS'); ?></a></span><?php if ($this->sortlinks['sorton'] == 'jobstatus') { ?> <img src="<?php echo $img ?>"> <?php } */?>
				&nbsp;|&nbsp;<span class="slink"><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['company']; ?>"><?php echo JText::_('JS_COMPANY'); ?></a></span><?php if ($this->sortlinks['sorton'] == 'company') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<span class="slink"><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['country']; ?>"><?php echo JText::_('JS_COUNTRY'); ?></a></span><?php if ($this->sortlinks['sorton'] == 'country') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				&nbsp;|&nbsp;<span class="slink"><a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('JS_SALARY_RANGE'); ?></a></span><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?>
				<?php /* &nbsp;|&nbsp;<span class="slink">	<a class="<?php echo $this->theme['sortlinks']; ?>" href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('JS_DATEPOSTED'); ?></a></span><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } */?>
			</td>
		</tr>
	</table>
		<?php 
		$days = $this->config['newdays'];
		$isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		if ( isset($this->application) ){
		foreach($this->application as $app)	{ 
			$isodd = 1 - $isodd; ?>
			<div class="listing-summary <?php echo $tdclass[$isodd]; ?>"> 
				<div class="header">
					<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=3&oi='.$app->id.'&Itemid='.$this->Itemid; ?>
					<h3>
						<?php echo '<a href="'.$link.'">'.$app->title.'</a>'; 
							if ($app->created > $isnew)
								echo "<span style=\"color:red; font-size:11px\"> ".JText::_('JS_NEW')." </span>";
						?>
					</h3>
					<p class="category">
						<small><?php echo $app->cat_title; ?></small>
					</p>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
					<tr>
						<td class="maintext">
							<?php 
								echo "<small><span class=\"field_title\">".JText::_('JS_DATEPOSTED').":</span> ".strftime('%d %B, %Y',strtotime($app->created))."</small><br />";
								if ($app->companyname!="Cultunet") echo '<small><span class="field_title">'.JText::_('JS_COMPANY').':</span> '.$app->companyname.'</small><br />';	
								//echo '<small><span class="field_title">'.JText::_('JS_AREAGESTION').':</span> '.$area_gestion.'</small><br />';
								//echo '<small><span class="field_title">'.JText::_('JS_AREAPROFESIONAL').':</span> '.$area_profesional.'</small><br />';
								echo  '<small><span class="field_title">'.JText::_('JS_JOBTYPE').':</span> '.$app->jobtypetitle.'</small><br />';	
							?>
							
						</td>
						<td class="maintext">
							<?php 
								echo "<small><span class=\"field_title\">".JText::_('JS_NOOFJOBS').":</span> ".$app->noofjobs."</small><br />";
								echo  '<small><span class="field_title">'.JText::_('JS_COUNTRY').':</span> '.$app->countryname.'</small><br />';	
							?>
							
						</td>
					</tr>
					<?php /* <tr>
						<td class="maintext">&nbsp;<span class="field_title"><?php echo JText::_('JS_COMPANY'); ?>	</span></td>
						<td class="maintext">
							<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=3&md='.$app->companyid.'&Itemid='.$this->Itemid; ?>
							<a href="<?php echo $link?>"><span class="field_title"><?php echo $app->companyname; ?></span></a>
						</td>
						<td class="maintext">&nbsp;<span class="field_title"><?php echo JText::_('JS_COMPANYURL'); ?>	</span></td>
						<td class="maintext"><a class="jplnks" href='<?php echo $app->url; ?>' target='_blank'><?php echo $app->url; ?></a></td>
					</tr>
					<tr>
						<td class="maintext">&nbsp;<span class="field_title"><?php echo JText::_('JS_COUNTRY'); ?>	</span></td>
						<td class="maintext"><?php echo $app->countryname; ?></td>
						<?php if ( $app->jobsalaryrange != 0 ) {
								if ($app->jobsalaryrange != 0){ ?>
									<td class="maintext">&nbsp;<span class="field_title"><?php echo JText::_('JS_SALARY_RANGE'); ?>	</span></td>
									<td class="maintext"><?php $salary = $this->config['currency'] . $app->rangestart . ' - ' . $this->config['currency'] . $app->rangeend . ' /month';
									echo $salary; ?></td>
							<?php } 
							}?>
					</tr>
					<tr>
						<td class="maintext">&nbsp;<span class="field_title"><?php echo JText::_('JS_DATEPOSTED'); ?>	</span></td>
						<td class="maintext"><?php echo $app->created; ?></td>
						<?php if ($app->noofjobs != 0){ ?>
						<td class="maintext">&nbsp;<span class="field_title"><?php echo JText::_('JS_NOOFJOBS'); ?>	</span></td>
						<td class="maintext"><?php echo $app->noofjobs; ?></td>
						<?php } ?>
					</tr> */?>
				</table>	
				</div><!-- Fin header -->
				<div class="mas-info">
					<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=3&oi='.$app->id.'&Itemid='.$this->Itemid; ?>
					<button onclick="location.href='<?php echo $link?>'"><?php echo JText::_('JS_VIEW'); ?></button>
					<?php if ($this->userrole->rolefor == 2 && $app->companyid!=7 && $app->companyid!=643) { ?>
					<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_apply&aj=2&jobcat='.$app->jobcategory.'&bi='.$app->id.'&Itemid='.$this->Itemid; ?>
						<button onclick="location.href='<?php echo $link?>'" class="btn_solicitar_empleo"><?php echo JText::_('JS_APPLYNOW'); ?></button>
					<?php } ?>
				</div>
				<?php if($app->companyid==7 || $app->companyid==643) { ?>
				<p><small><span style="font-weight:bold !important">* </span><span style="color:red !important;"><?php echo JText::_('JS_MESSAGE_OFERTA_EXT'); ?></span></small></p>
				<?php } ?>
			</div><!-- Fin listing-summary -->

		<?php 
		}	
		} ?>		

<?php
// paging		
		$rows = $this->application;//$database -> loadObjectList();
$total=$this->totalresults;
$limit=$this->limit;
$limitstart=$this->limitstart;

/*$limit = $limit ? $limit : $mainframe->getCfg('list_limit');
if ( $total <= $limit ) { 
    $limitstart = 0;
} */
 $pageNav = new mosPageNav( $total, $limitstart, $limit );

//$link = 'abc';

?>
<div class="clear">&nbsp;</div>
</div><!-- Fin results_search_jobs -->
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='.$this->Itemid); ?>" method="post">
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
?>
	<h3><?php echo $this->config['title']; ?></h3>
	<div class="pages-links-results">
		<span class="xlistings">
			<?php 
				echo JText::_('JS_RESULT_NOT_FOUND');
			?>
		</span>
	</div>

<?php	
}

} else{ // not allowed job posting
echo JText::_('EA_YOU_ARE_NOT_ALLOWED_TO_VIEW');
}	
?>	


<script language="javascript">
showsavesearch(0); 
</script>
<?php
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
