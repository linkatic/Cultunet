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
 
  if ($this->config['showemployerlink'] == 0){ // user can not register as a employer
	$usertypeid='';
	if ($this->usertype) $usertypeid = $this->usertype->id;
		echo '<form action="index.php" method="POST" name="adminForm">';

			echo '<input type="hidden" name="usertype" value="2" />'; //2 for job seeker
			echo '<input type="hidden" name="dated" value="'. date('Y-m-d H:i:s') .'" />';
			echo '<input type="hidden" name="uid" value="'.  $this->uid .'" />';
			echo '<input type="hidden" name="id" value="'. $usertypeid .'" />';
			echo '<input type="hidden" name="option" value="'. $option .'" />';
			echo '<input type="hidden" name="task" value="savenewinjsjobs" />';
			echo '<script language=Javascript>';
				echo 'document.adminForm.submit();';
			echo '</script>';

		echo '</form>';			
  
  }

?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />
<h3><?php echo $this->config['title']; ?></h3>
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
	<tr><td height="0"></td></tr>
	<?php if ($this->config['cur_location'] == 1) {?>
	<tr><td height="0"></td></tr>
	<tr><td class="curloc">
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_WELCOME_JSJOBS'); ?>
	</td></tr>
	<?php } ?>
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo JText::_('JS_WELCOME_JSJOBS'); ?> 
	</td></tr>

	<tr><td height="10"></td></tr>
</table>

*/
?>
<?php
if ($this->config['showemployerlink'] == 1){ // ask user role
?>
<form action="index.php" method="POST" name="adminForm">

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr>
			<td align="center" colspan="2">
				<strong><?php echo JText::_('JS_WELCOME_JSJOBS_TEXT'); ?>  </strong>
			</td>
		</tr>	
		<tr><td height="15" colspan="2"></td></tr>	
		<tr>
			<td width="50%" align="right">
				<?php echo JText::_('JS_SELECT_ROLE'); ?> :&nbsp;
			</td>
			<td width="50%"> <?php echo $this->lists['usertype']; ?>
			</td>
		</tr>		
		<tr><td height="15" colspan="2"></td></tr>	
		<tr>
			<td align="center" colspan="2">
				<input type="submit" class="button" name="submit_app" onclick="document.adminForm.submit();" value="<?php echo JText::_('JS_SUBMIT'); ?>" />
			</td>
		</tr>	
	</table>
			<input type="hidden" name="dated" value="<?php echo date('Y-m-d H:i:s'); ?>" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="id" value="<?php if ($this->usertype) echo $this->usertype->id; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="savenewinjsjobs" />
</form>	
<?php }else{ // user can not register as a employer ?>
<div width="100%" align="center">
<br><br><h1>Please wait ...</h1>
</div>

<?php } 
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
