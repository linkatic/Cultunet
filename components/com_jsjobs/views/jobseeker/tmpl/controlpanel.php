<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jul 08, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/resume/tmpl/controlpanel.php
 ^ 
 * Description: template view for job categories 
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
  global $mainframe;

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
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_JOB_SEEKER_C_P'); ?>
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
		<?php echo JText::_('JS_JOB_SEEKER_C_P'); ?> 
	</td></tr>

	<tr><td height="10"></td></tr>
</table>

<?php

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
if ($allowed == true) { 
?>

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr>
			<td>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
					<tr height="30">
						<td width="250"></td>
						<td>  </td>
					</tr>
				</table>
			</td>
		</tr>	
		<tr>
			<td>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
					<tr>
						<td width="47%" valign="top">
							<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
								<tr>
									<td> <img width="32" height="32" src="components/com_jsjobs/images/new.png" alt="<?php echo JText::_('JS_ADD_RESUME'); ?>" /></td>
									<td><a class="cplinks" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&vm=2&Itemid=<?php echo $this->Itemid; ?>"><?php echo JText::_('JS_ADD_RESUME'); ?>  </a></td>	
								</tr>
								<tr>
									<td> <img width="32" height="32" src="components/com_jsjobs/images/resume.png" alt="<?php echo JText::_('JS_MY_RESUMES'); ?>" /></td>
									<td><a class="cplinks" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid=<?php echo $this->Itemid; ?>"><?php echo JText::_('JS_MY_RESUMES'); ?> </a> </td>	
								</tr>
								<tr>
									<td> <img width="32" height="32" src="components/com_jsjobs/images/new.png" alt="<?php echo JText::_('JS_ADD_COVER_LETTER'); ?>" /></td>
									<td><a class="cplinks" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&Itemid=<?php echo $this->Itemid; ?>"><?php echo JText::_('JS_ADD_COVER_LETTER'); ?>  </a></td>	
								</tr>
								<tr>
									<td> <img width="32" height="32" src="components/com_jsjobs/images/coverletter.png" alt="<?php echo JText::_('JS_MY_COVER_LETTERS'); ?>" /></td>
									<td><a class="cplinks" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid=<?php echo $this->Itemid; ?>"><?php echo JText::_('JS_MY_COVER_LETTERS'); ?> </a> </td>	
								</tr>
								
							</table>	
						</td>		
						<td width="6%"> </td>
						<td width="47%" valign="top">
							<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
								<tr>
									<td> <img width="32" height="32" src="components/com_jsjobs/images/jobcat.png" alt="<?php echo JText::_('JS_JOBS_BY_CATEGORIES'); ?>" /></td>
									<td><a class="cplinks" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid=<?php echo $this->Itemid; ?>"><?php echo JText::_('JS_JOBS_BY_CATEGORIES'); ?>  </a></td>	
								</tr>
								<tr>
									<td> <img width="32" height="32" src="components/com_jsjobs/images/appliedjobs.png" alt="<?php echo JText::_('JS_NEWEST_JOBS'); ?>" /></td>
									<td><a class="cplinks" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid=<?php echo $this->Itemid; ?>"><?php echo JText::_('JS_NEWEST_JOBS'); ?>  </a></td>	
								</tr>
								<tr>
									<td> <img width="32" height="32" src="components/com_jsjobs/images/appliedjobs.png" alt="<?php echo JText::_('JS_MY_APPLIED_JOBS'); ?>" /></td>
									<td><a class="cplinks" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid=<?php echo $this->Itemid; ?>"><?php echo JText::_('JS_MY_APPLIED_JOBS'); ?>  </a></td>	
								</tr>
								<tr>
									<td> <img width="32" height="32" src="components/com_jsjobs/images/search.png" alt="<?php echo JText::_('JS_SEARCH_JOB'); ?>" /></td>
									<td><a class="cplinks" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid=<?php echo $this->Itemid; ?>"><?php echo JText::_('JS_SEARCH_JOB'); ?>  </a></td>	
								</tr>
								<tr>
									<td> <img width="32" height="32" src="components/com_jsjobs/images/save.png" alt="<?php echo JText::_('JS_JOB_SAVE_SEARCHES'); ?>" /></td>
									<td><a class="cplinks" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=my_jobsearches&Itemid=<?php echo $this->Itemid; ?>"><?php echo JText::_('JS_JOB_SAVE_SEARCHES'); ?>  </a></td>	
								</tr>
							</table>	
						</td>		
					</tr>
					
				</table>
			</td>
		</tr>	
		<tr>
			<td>
			</td>
		</tr>		
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

