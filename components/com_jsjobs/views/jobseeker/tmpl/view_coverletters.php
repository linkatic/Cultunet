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

 $vct = '&vct=1'; //view cover letter
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
	<tr><td height="0"></td></tr>
	<tr><td class="curloc">
		<?php if ($this->vts == '1'){ //viewresume ?> 
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_APPLIED_RESUME'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd=<?php echo $this->bd; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_JOB_APPLIED_APPLICATIONS'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=2&rd=<?php echo $this->rd; ?>&bd=<?php echo $this->bd; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_VIEW_RESUME'); ?></a> > <?php echo JText::_('JS_VIEW_COVER_LETTERS'); ?>			
			<?php $vct = '&vct=2&rd='.$this->rd.'&bd='.$this->bd; ?>
		<?php }elseif ($this->vts == '2'){ //search resume ?> 
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resumesearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_RESUME'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_RESUME_SEARCH_RESULT'); ?></a> > <?php echo JText::_('JS_VIEW_COVER_LETTERS'); ?>
			<?php $vct = '&vct=3'; //view cover letter
		 } ?>
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
	<?php echo JText::_('JS_VIEW_COVER_LETTERS'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>

<?php

if ($this->totalresults != 0) {
?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr  class="<?php echo $this->theme['sortlinks']; ?>" height="17" valign="center">
			<td width="75%"><?php echo JText::_('JS_TITLE'); ?></td>
			<td width="15"><?php echo JText::_('JS_CREATED'); ?></td>
			<td width="10"></td>
		</tr>
		<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isnew = date("Y-m-d H:i:s", strtotime("-$this->config['newdays'] days"));
		//$tdclass = array("odd", "even");
		$isodd =1;
		jimport('joomla.filter.output');
		if ( isset($this->coverletters) ){
		$i=0;
			foreach($this->coverletters as $letter)	{ 

			$row = $letter;
			$checked = JHTML::_('grid.id', $i, $row->id);
			$link = JFilterOutput::ampReplace('index.php?option='.$option.'&task=edit&cid[]='.$row->id);

			$i++;
					$isodd = 1 - $isodd; ?>
				<tr height="30" class="<?php echo $tdclass[$isodd]; ?>" > 
					<td class="maintext"><?php echo $letter->title;	?></td>
					<td class="maintext"><?php echo $letter->created;	?></td>
					<td class="maintext" align="center" valign="middle"><a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_coverletter<?php echo $vct; ?>&cl=<?php echo $letter->id; ?>&clu=<?php echo $letter->uid; ?>">
						<img width="15" height="15" src="components/com_jsjobs/images/view.png" />
					</td>
				</tr>
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
	 $limit = $limit ? $limit : $mainframe->getCfg('list_limit');
	if ( $total <= $limit ) { 
	    $limitstart = 0;
	} 

	 $pageNav = new mosPageNav( $total, $limitstart, $limit );

	?>
	<form action="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid=<?php echo $this->Itemid; ?>" method="post">
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


