<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/application/tmpl/formemp.php
 ^ 
 * Description: template for long employment application form
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
 global $mainframe;
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
	<tr><td height="0"></td></tr>
	<tr><td class="curloc">
		<?php $vct = $this->vct; 
			if ($vct == '1'){ //my cover letters ?> 
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_MY_COVER_LETTERS'); ?></a> > <?php echo JText::_('JS_VIEW_COVER_LETTER'); ?>
		<?php }elseif ($vct == '2'){ //view cover letters - search ?> 
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_APPLIED_RESUME'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&jobid=<?php echo $_GET['jobid']; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_JOB_APPLIED_APPLICATIONS'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=2&rd=<?php echo $_GET['rd']; ?>&jobid=<?php echo $_GET['jobid']; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_VIEW_RESUME'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_coverletters&vts=1&clu=<?php echo $_GET['clu']; ?>&rd=<?php echo $_GET['rd']; ?>&jobid=<?php echo $_GET['jobid']; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_VIEW_COVER_LETTERS'); ?></a>	 > <?php echo JText::_('JS_VIEW_COVER_LETTER'); ?>
		<?php }elseif ($vct == '3'){ //view cover letters - search ?> 
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resumesearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_RESUME'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_RESUME_SEARCH_RESULT'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_coverletters&vts=2&clu=<?php echo $_GET['clu']; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_VIEW_COVER_LETTERS'); ?></a> > <?php echo JText::_('JS_VIEW_COVER_LETTER'); ?>
		<?php } ?>
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
		<?php echo JText::_('JS_VIEW_COVER_LETTER'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>

*/
?>
<div id="table-jobs">
	<?php if( isset($this->coverletter)){ ?>
			<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
						
							<tr class="<?php echo $this->theme['odd']; ?>">
								<td width="150" align="right" class="textfieldtitle"><strong>
									<label id="titlemsg" for="title"><?php echo JText::_('JS_TITLE'); ?></label></td>
								<td>
									<?php if (isset($this->coverletter)) echo $this->coverletter->title;?>
								</td>
							</tr>
							<tr  class="<?php echo $this->theme['even']; ?>">
								<td align="right" class="textfieldtitle"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_TXT'); ?></label></td>
								<td>
									<?php if(isset($this->coverletter)) echo $this->coverletter->description; ?>
								</td>
							</tr>
							



			</table>
	<?php }else echo JText::_('JS_RESULT_NOT_FOUND'); ?>
<?php
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
