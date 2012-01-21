<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/application/tmpl/jobsearch.php
 ^ 
 * Description: template for job search
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');

$editor = & JFactory :: getEditor();

global $mainframe;

$document = &JFactory::getDocument();
 $document->addScript( JURI::base() . '/includes/js/joomla.javascript.js');

JHTML :: _('behavior.calendar');
$width_big = 40;
$width_med = 25;
$width_sml = 15;

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
	<tr><td class="curloc">
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_SEARCH_RESUME'); ?>
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
		<?php echo JText::_('JS_SEARCH_RESUME'); ?>
	</td></tr>
	<tr><td height="10"></td></tr>
</table>
<?php
if ($this->userrole->rolefor == 1) { // employer
?>
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid='.$this->Itemid); ?>" method="post" name="adminForm" id="adminForm">
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
      <?php if ( $this->config['search_resume_title'] == '1' ) { ?>
      <tr>
        <td width="20%" align="right"><?php echo JText::_('JS_APPLICATION_TITLE'); ?></td>
          <td width="60%"><input class="inputbox" type="text" name="title" size="40" maxlength="255"  />
        </td>
      </tr>
       <?php } ?>
      <?php if ( $this->config['search_resume_name'] == '1' ) { ?>
      <tr>
        <td width="20%" align="right"><?php echo JText::_('JS_NAME'); ?></td>
          <td width="60%"><input class="inputbox" type="text" name="name" size="40" maxlength="255"  />
        </td>
      </tr>
       <?php } ?>
	      <?php if ( $this->config['search_resume_nationality'] == '1' ) { ?>
      <tr>
        <td align="right"><?php echo JText::_('JS_NATIONALITY'); ?></td>
        <td><?php echo $this->searchoptions['nationality']; ?>
        </td>
      </tr>
       <?php } ?>
      <?php if ( $this->config['search_resume_gender'] == '1' ) { ?>
		<tr>
			<td  align="right" class="textfieldtitle">	<?php echo JText::_('JS_GENDER');  ?>	</td>
			<td><?php echo $this->searchoptions['gender'];	?>	</td>
		</tr>
       <?php } ?>
      <?php if ( $this->config['search_resume_available'] == '1' ) { ?>
			<tr>
				<td valign="top" align="right"><?php echo JText::_('JS_I_AM_AVAILABLE'); ?></td>
				<td><input type='checkbox' name='iamavailable' value='1' <?php if(isset($this->resume)) echo ($this->resume->iamavailable == 1) ? "checked='checked'" : ""; ?> /></td>
			</tr>
       <?php } ?>

     <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_CATEGORIES'); ?></td>
        <td><?php echo $this->searchoptions['jobcategory']; ?></td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_JOBTYPE'); ?></td>
        <td><?php echo $this->searchoptions['jobtype']; ?></td>
      </tr>
      <?php if ( $this->config['search_resume_salaryrange'] == '1' ) { ?>
	  <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_SALARYRANGE'); ?></td>
        <td><?php echo $this->searchoptions['jobsalaryrange']; ?></td>
      </tr>
       <?php } ?>
      <?php if ( $this->config['search_resume_heighesteducation'] == '1' ) { ?>
	   <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION'); ?></td>
        <td><?php echo $this->searchoptions['heighestfinisheducation']; ?></td>
      </tr>
       <?php } ?>
      <?php if ( $this->config['search_resume_experience'] == '1' ) { ?>
	   <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_EXPERIENCE'); ?></td>
        <td><input class="inputbox" type="text" name="experience" size="10" maxlength="15"  /></td>
      </tr>
       <?php } ?>

	<tr>
		<td colspan="2" align="center">
		<input type="submit" class="button" name="submit_app" onclick="document.adminForm.submit();" value="<?php echo JText::_('JS_SEARCH_RESUME'); ?>" />
		</td>
	</tr>
    </table>

			<input type="hidden" name="isresumesearch" value="1" />
			<input type="hidden" name="view" value="employer" />
			<input type="hidden" name="layout" value="resume_searchresults" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task11" value="view" />
			
		  
		  

		</form>
<?php

} else{ // not allowed employer
echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');
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
