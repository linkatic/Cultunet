<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/emailtemplate.php
 ^ 
 * Description: Form template for a job
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access'); 
jimport('joomla.html.pane');

$editor = &JFactory::getEditor();
JHTML::_('behavior.calendar');
JHTMLBehavior::formvalidation(); 

$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>../components/com_jsjobs/css/jsjobs01.css" />

<script language="javascript">
function submitbutton(pressbutton) {
	if (pressbutton) {
		document.adminForm.task.value=pressbutton;
	}
	if(pressbutton == 'save'){
		returnvalue = validate_form(document.adminForm);
	}else returnvalue  = true;
	
	if (returnvalue == true){
		try {
			  document.adminForm.onsubmit();
	        }
		catch(e){}
		document.adminForm.submit();
	}
}

function validate_form(f)
{
        if (document.formvalidator.isValid(f)) {
                f.check.value='<?php echo JUtility::getToken(); ?>';//send token
        }
        else {
                alert('Some values are not acceptable.  Please retry.');
				return false;
        }
		return true;
}
</script>

<table width="100%" >
	<tr>
		<td align="left" width="175"  valign="top">
			<table width="100%" ><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top" align="left">


<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
<input type="hidden" name="check" value="post"/>
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
				      <tr class="row1">
				        <td width="50" colspan="3" align="right"><label id="subjectmsg" for="subject"><?php echo JText::_('JS_SUBJECT'); ?></label>&nbsp;<font color="red">*</font>&nbsp;:&nbsp;&nbsp;&nbsp;
				          <input class="inputbox required" type="text" name="subject" id="subject" size="135" maxlength="255" value="<?php if(isset($this->template)) echo $this->template->subject; ?>" />
				        </td>
				      </tr>
							<tr><td height="10" colspan="2"></td></tr>
							<tr class="row2">
								<td colspan="3" valign="top" align="center"><label id="descriptionmsg" for="body"><strong><?php echo JText::_('JS_BODY'); ?></strong></label>&nbsp;<font color="red">*</font></td>
							</tr>
							<tr>
								<td colspan="2" align="center" width="600">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->template))
										echo $editor->display('body', $this->template->body, '550', '300', '60', '20', false);
									else
										echo $editor->display('body', '', '550', '300', '60', '20', false);
								?>	
								</td>
								<td width="35%" valign="top">
									<table  cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
										
										<tr class="row1"><td> <strong><u><?php echo JText::_('JS_PARAMETERS'); ?></u></strong></td>	</tr>
										<?php if(($this->template->templatefor == 'company-approval' ) || ($this->template->templatefor == 'company-rejecting' ) ) { ?>
											<tr><td>{COMPANY_NAME} :  <?php echo JText::_('JS_COMPANY_NAME'); ?></td></tr>
											<tr><td>{EMPLOYER_NAME} :  <?php echo JText::_('JS_EMPLOYER_NAME'); ?></td>	</tr>
										<?php } elseif(($this->template->templatefor == 'job-approval' ) || ($this->template->templatefor == 'job-rejecting' ) ) { ?>
											<tr><td>{JOB_TITLE} :  <?php echo JText::_('JS_JOB_TITLE'); ?></td></tr>
											<tr><td>{EMPLOYER_NAME} :  <?php echo JText::_('JS_EMPLOYER_NAME'); ?></td>	</tr>
										<?php } elseif(($this->template->templatefor == 'resume-approval' ) || ($this->template->templatefor == 'resume-rejecting' ) ) { ?>										
											<tr><td>{RESUME_TITLE} :  <?php echo JText::_('JS_RESUME_TITLE'); ?></td></tr>
											<tr><td>{JOBSEEKER_NAME} :  <?php echo JText::_('JS_JOBSEEKER_NAME'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'jobapply-jobapply' ) { ?>										
											<tr><td>{EMPLOYER_NAME} :  <?php echo JText::_('JS_EMPLOYER_NAME'); ?></td>	</tr>
											<tr><td>{JOBSEEKER_NAME} :  <?php echo JText::_('JS_JOBSEEKER_NAME'); ?></td>	</tr>
											<tr><td>{JOB_TITLE} :  <?php echo JText::_('JS_JOB_TITLE'); ?></td></tr>
										
										<?php } ?>
									</table>
								</td>
							</tr>
      <tr>
        <td colspan="2" height="5"></td>
      <tr>
    </table>


	<?php 
				if(isset($this->template)) {
					if (($this->template->created=='0000-00-00 00:00:00') || ($this->template->created==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->template->created;
				}else
					$curdate = date('Y-m-d H:i:s');
				
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="view" value="jobposting" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="id" value="<?php echo $this->template->id; ?>" />
			<input type="hidden" name="templatefor" value="<?php echo $this->template->templatefor; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="saveemailtemplate" />
			
		  <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		  
		  

		</form>

		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;">
			<?php
				//include($ADMINPATH.'\views\jscr.php');
				include_once('components/com_jsjobs/views/jscr.php');
			?>
			</td>
			</tr></table>
		</td>
	</tr>
	
</table>				