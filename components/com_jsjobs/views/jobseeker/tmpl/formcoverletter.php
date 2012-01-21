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

$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;
?>
<?php JHTMLBehavior::formvalidation(); ?>

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
<script language="javascript">
function myValidate(f) {
        if (document.formvalidator.isValid(f)) {
                f.check.value='<?php echo JUtility::getToken(); ?>';//send token
        }else {
                alert('Some values are not acceptable.  Please retry.');
				return false;
        }
		return true;
}

</script>

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
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_COVER_LETTER_FORM'); ?>
	</td></tr>
	<?php } ?>
	<tr><td>
		<?php 
			$cutomlink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid='.$this->Itemid;
			$cutomlinktext = JText::_('JS_MY_COVER_LETTERS');
			$count = 0;
			if (sizeof($this->jobseekerlinks) != 0){
				echo '<div id="toplinks"><ul>';
				foreach($this->jobseekerlinks as $lnk)	{ 
					if ($count == 1) { ?>
						<span>
							<a href="<?php echo $cutomlink; ?>"> <?php echo $cutomlinktext; ?></a>
						</span>
					<?php }	?>
					<span <?php if($lnk[2] == 1)echo 'class="first"'; elseif($lnk[2] == -1)echo 'class="last"';  ?>>
						<a href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
					</span>
				<?php $count++;	
				}
				echo '<ul></div>';			
			} 
		?>
	</td></tr>	
	<tr><td height="3"></td></tr>
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo JText::_('JS_COVER_LETTER_FORM'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>
*/
?>

<?php
//$_SESSION['test'] = 111;

if ($this->userrole->rolefor == 2) { // job seeker
if ($this->canaddnewcoverletter == 1) { // add new coverletter, in edit case always 1
?>
<div id="form-jobseeker">
		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" onSubmit="return myValidate(this);">
			<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
						
							<tr>
								<td width="150" align="right" class="textfieldtitle">
									<label id="titlemsg" for="title"><?php echo JText::_('JS_TITLE'); ?></label>&nbsp;<span class="requerido">*</span>:</td>
								<td>
									<input class="inputbox required" type="text" name="title" id="title" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->coverletter)) echo $this->coverletter->title;?>" />
								</td>
							</tr>
							<tr>
								<td align="right" class="textfieldtitle"><label id="descriptionmsg" for="description"><?php echo JText::_('JS_TXT'); ?></label>&nbsp;<span class="requerido">*</span>:</td>
								<td>
									<textarea class="inputbox required" name="description" id="description" cols="60" rows="9"><?php if(isset($this->coverletter)) echo $this->coverletter->description; ?></textarea>
								</td>
							</tr>
							



					<tr><td colspan="2" height="10"></td></tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit"  class="button" value="<?php echo JText::_('JS_SAVE_COVER_LETTER'); ?>"/>


			</td></tr>
		<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;">
			<?php
				include_once('components/com_jsjobs/views/fr_jscr.php');
			?>
			</td>
			</tr></table>
		</td>
	</tr>
		</table>
			<?php 
				if(isset($this->coverletter)) {
					if (($this->coverletter->created=='0000-00-00 00:00:00') || ($this->coverletter->created==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->coverletter->created;
				}else
					$curdate = date('Y-m-d H:i:s');
				
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="id" value="<?php if (isset($this->coverletter)) echo $this->coverletter->id; ?>" />
			<input type="hidden" name="layout" value="empview" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="savecoverletter" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />

		
		
		</form>
		
<?php
} else{ // can not add new company
	echo "<strong><font color='red'>".JText::_('JS_COVERLETTER_LIMIT_EXCEED')." <a href='#'>".JText::_('JS_COVERLETTER_LIMIT_EXCEED_ADMIN')."</a></font></strong>";
}
} else{ // not allowed cover letter
echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');

}
}//ol
?>		
</div><!-- Fom form-jobseeker -->
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
