<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/application/tmpl/myjobsearches.php
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
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_RESUME_SAVE_SEARCHES'); ?>
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
		<?php echo JText::_('JS_RESUME_SAVE_SEARCHES'); ?>  
	</td></tr>
	<tr><td height="3"></td></tr>
</table>

<?php
if ($this->userrole->rolefor == 1) { // employer

if ($this->totalresults != 0) {
?>
<form action="index.php" method="post" name="adminForm">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr  class="<?php echo $this->theme['sortlinks']; ?>" height="17" valign="center">
			<td width="75%"><?php echo JText::_('JS_TITLE'); ?></td>
			<td width="15"><?php echo JText::_('JS_CREATED'); ?></td>
			<td width="10"></td>
			<td width="10"></td>
		</tr>
		<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isnew = date("Y-m-d H:i:s", strtotime("-$this->config['newdays'] days"));
		//$tdclass = array("odd", "even");
		$isodd =1;
		jimport('joomla.filter.output');
		if ( isset($this->jobsearches) ){
		$i=0;
			foreach($this->jobsearches as $search)	{ 

			$row = $search;
			$checked = JHTML::_('grid.id', $i, $row->id);
			$link = JFilterOutput::ampReplace('index.php?option='.$option.'&task=edit&cid[]='.$row->id);

			$i++;
					$isodd = 1 - $isodd; ?>
				<tr height="20" class="<?php echo $tdclass[$isodd]; ?>" > 
					<td class="maintext"><?php echo $search->searchname;	?></td>
					<td class="maintext"><?php echo $search->created;	?></td>
					<td class="maintext" align="center" valign="middle"><a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=viewresumesearch&rs=<?php echo $search->id; ?>&Itemid=<?php echo $this->Itemid; ?>">
						<img width="15" height="15" src="components/com_jsjobs/images/view.png" /></td>
					<td class="maintext" align="center" valign="middle">
							<a href="index.php?option=com_jsjobs&c=jsjobs&task=deleteresumesearch&rs=<?php echo $search->id; ?>&Itemid=<?php echo $this->Itemid; ?>">
								<img  width="15" height="15" src="components/com_jsjobs/images/delete.png" />
							</a>
					</td>
				</tr>
				<tr> <td colspan="5" height="1">	</td></tr>
				<?php
			}
		} ?>		
	</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="deletejobsearch" />
			<input type="hidden" id="id" name="id" value="" />
			<input type="hidden" name="boxchecked" value="0" />

	</form>
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

