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

 $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid='.$this->Itemid;
 
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

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<tr><td><table border="0" width="100%" cellpadding="0" cellspacing="0" >
	<tr>
		<?php if ($this->config['cur_location'] == 1) {?>
		<td class="curloc" valign="bottom">
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resumesearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_RESUME'); ?></a> > <?php echo JText::_('JS_RESUME_SEARCH_RESULT'); ?>
		</td>
		<?php } ?>
		<?php if ($this->config['search_resume_showsave'] == 1) {?>
		<td id="savethis" width="30" align="right">
				<input type="button" class="button" onclick="showsavesearch(1);" value="<?php echo JText::_('JS_SAVE_THIS_SEARCH'); ?>">
		</td>
		<?php } ?>
	</tr>
		<?php if ($this->config['search_resume_showsave'] == 1) {?>
			<form action="index.php" method="post" name="adminForm" id="adminForm" onsubmit="return myValidate(this);">
			<tr id="savesearch">
				<td colspan="2"> <?php echo JText::_('JS_SEARCH_NAME'); ?> &nbsp;: &nbsp;&nbsp;<input class="inputbox required" type="text" name="searchname" size="20" maxlength="30"  />
				&nbsp;&nbsp;&nbsp;<input type="submit" class="button validate" value="<?php echo JText::_('JS_SAVE'); ?>">
				</td>
			</tr>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="saveresumesearch" />
			</form>	
		<?php } ?>
	</table></td></tr>
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
		<?php echo JText::_('JS_RESUME_SEARCH_RESULT'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>

<?php
if ($this->userrole->rolefor == 1) { // employer

if ($this->totalresults != 0) {
	if ($this->sortlinks['sortorder'] == 'ASC')
		$img = "images/M_images/sort0.png";
	else
		$img = "images/M_images/sort1.png";

?>

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
				<tr><td height="3"></td></tr>
				<tr>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_TITLE'); ?>	</strong></td>
					<td class="maintext" colspan="3"><?php echo $resume->application_title;	?></td>
				</tr>
				<tr>
					<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_CATEGORY'); ?>	</strong></td>
					<td class="maintext" width="30%"><?php echo $resume->cat_title; ?></td>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_SALARY_RANGE'); ?>	</strong></td>
					<td class="maintext"><?php $salary = $this->config['currency'] . $resume->rangestart . ' - ' . $this->config['currency'] . $resume->rangeend . ' /month';
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
				<tr>
					<td></td>
					<td class="maintext" align="right"> 
						<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=3&rd='.$resume->id.'&Itemid='.$this->Itemid; ?>
						<a class="pageLink" href="<?php echo $link?>"><strong><?php echo JText::_('JS_VIEW'); ?></strong></a>
					&nbsp;&nbsp;</td>
					<td class="maintext" align="right" > 
						<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_coverletters&vts=2&clu='.$resume->uid.'&Itemid='.$this->Itemid; ?>
						<a class="pageLink" href="<?php echo $link?>"><strong><?php echo JText::_('JS_VIEW_COVER_LETTERS'); ?></strong></a>
					</td>
					<td class="maintext" align="left">&nbsp;&nbsp;
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
	<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid='.$this->Itemid); ?>" method="post">
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
