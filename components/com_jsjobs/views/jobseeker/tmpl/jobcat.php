<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/resume/tmpl/jobcat.php
 ^ 
 * Description: template view for job categories 
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
  global $mainframe;

	$link = "index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid=".$this->Itemid;
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
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_JOB_CATEGORIES'); ?>
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
	<?php if ($allowed == true) { ?>
		<tr><td height="3"></td></tr>
		<tr><td>
		<form action="<?php echo JRoute::_($link); ?>" method="post" name="adminForm">
		<?php
			require_once( 'job_filters.php' );
		?>	
		</form>
		</td></tr>
	<?php } ?>
	<tr><td height="3"></td></tr>
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo JText::_('JS_JOB_CATEGORIES'); ?> 
	</td></tr>

	<tr><td height="3"></td></tr>
</table>

<?php
if ($allowed == true) { 
?>

	<table cellpadding="3" cellspacing="0" border="0" width="100%" class="contentpane">
		<?php
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$rowcount=1;
		$istr =1;
		$isodd =1;
		if ( isset($this->application) ){
			foreach($this->application as $app)
			{
				$isodd = 1 - $isodd;	
				if ($istr==1)
					echo " <tr >";//class='".$tdclass[$isodd]."'>";
				$istr = 1 - $istr;	
				$lnks = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&fr=lj&jobcat='. $app->id .'&Itemid='.$this->Itemid; 
				//$lnks = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&fr=l'; 
				//echo $lnks;
				$lnks = JRoute::_($lnks);
				//echo '<br>'.$lnks;
				?>
					<td width="50%" class="<?php echo $tdclass[$isodd]; ?>" align="left"><a href="<?php echo $lnks; ?>" ><?php echo $app->cat_title; ?> (<?php echo $app->catinjobs; ?>)</a></td>
				<?php	
				if ($istr==1){
						echo ' </tr>';
						$isodd = 1 - $isodd;	

				}
				$rowcount = $rowcount+1;
			}
		}
		if ($istr==0)
			echo ' <td></td></tr>';
		
		?>		
		
	</table>
<?php
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
