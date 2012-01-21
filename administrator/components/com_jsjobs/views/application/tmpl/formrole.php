<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/formrole.php
 * 
 * Description: Form template for a single category
 * 
 * History:		NONE
 * 
 */
 
defined('_JEXEC') or die('Restricted access'); 

$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';
JHTMLBehavior::formvalidation(); 
 ?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>../components/com_jsjobs/css/jsjobs01.css" />
<script type="text/javascript">

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
<table width="100%" border="0">
	<tr>
		<td align="left" width="175" valign="top">
		<!-- table-layout:fixed; -->
			<table style="width:100%;"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">

	<form action="index.php" method="POST" name="adminForm" id="adminForm" onsubmit="return validate_form(this)">
	<table cellpadding="0" cellspacing="0" border="1" width="100%" class="adminform">
	<?php
	if($this->msg != ''){
	?>
	 <tr>
        <td align="center"><font color="red"><strong><?php echo JText::_($this->msg); ?></strong></font></td>
      </tr>
	  <tr><td  height="10"></td></tr>	
	<?php
	}
	?>
	 <tr class ="row1">
        <td width="40%" align="right"><label id="titlemsg" for="title"><?php echo JText::_('JS_TITLE'); ?> : </label></td>
		<td><input class="inputbox required" type="text" name="title" size="40" maxlength="255" value="<?php if(isset($this->role)) echo $this->role->title; ?>" />
        </td>
      </tr>
	 <tr class ="row2">
        <td align="right"><?php echo JText::_('JS_FOR'); ?> : </td>
		<td><?php echo $this->lists['rolefor']; ?>
        </td>
      </tr>
	 <tr class ="row1">
        <td align="right"><?php echo JText::_('JS_COMPANIES_ALLOWED'); ?> : </td>
		<td><input class="inputbox required validate-numeric" type="text" name="companies" size="40" maxlength="255" value="<?php if(isset($this->role)) echo $this->role->companies; ?>" />&nbsp;&nbsp;&nbsp;<?php echo JText::_('JS_MIN1_UNLIMITED'); ?>
        </td>
      </tr>
	 <tr class ="row2">
        <td align="right"><?php echo JText::_('JS_JOBS_ALLOWED'); ?> : </td>
		<td><input class="inputbox required validate-numeric" type="text" name="jobs" size="40" maxlength="255" value="<?php if(isset($this->role)) echo $this->role->jobs; ?>" />&nbsp;&nbsp;&nbsp;<?php echo JText::_('JS_MIN1_UNLIMITED'); ?>
        </td>
      </tr>
	 <tr class ="row1">
        <td align="right"><?php echo JText::_('JS_RESUME_ALLOWED'); ?> : </td>
		<td><input class="inputbox required validate-numeric" type="text" name="resumes" size="40" maxlength="255" value="<?php if(isset($this->role)) echo $this->role->resumes; ?>" />&nbsp;&nbsp;&nbsp;<?php echo JText::_('JS_MIN1_UNLIMITED'); ?>
        </td>
      </tr>
	 <tr class ="row2">
        <td align="right"><?php echo JText::_('JS_SAVE_JOB_SEARCH'); ?> : </td>
		<td><input class="inputbox required validate-numeric" type="text" name="savesearchjob" size="40" maxlength="255" value="<?php if(isset($this->role)) echo $this->role->savesearchjob; ?>" />&nbsp;&nbsp;&nbsp;<?php echo JText::_('JS_MIN1_UNLIMITED'); ?>
        </td>
      </tr>
	 <tr class ="row1">
        <td align="right"><?php echo JText::_('JS_SAVE_RESUME_SEARCH'); ?> : </td>
		<td><input class="inputbox required validate-numeric" type="text" name="savesearchresume" size="40" maxlength="255" value="<?php if(isset($this->role)) echo $this->role->savesearchresume; ?>" />&nbsp;&nbsp;&nbsp;<?php echo JText::_('JS_MIN1_UNLIMITED'); ?>
        </td>
      </tr>
	 <tr class ="row2">
        <td align="right"><?php echo JText::_('JS_COVER_LETTER_ALLOWED'); ?> : </td>
		<td><input class="inputbox required validate-numeric" type="text" name="coverletters" size="40" maxlength="255" value="<?php if(isset($this->role)) echo $this->role->coverletters; ?>" />&nbsp;&nbsp;&nbsp;<?php echo JText::_('JS_MIN1_UNLIMITED'); ?>
        </td>
      </tr>
      <tr class ="row1">
        <td align="right"><?php echo JText::_('JS_PUBLISHED'); ?> : </td>
		<td><input type="checkbox" name="published" value="1" <?php if(isset($this->role))  {if ($this->role->published == '1') echo 'checked';} ?>/>
		  </td>
      </tr>
	<tr><td colspan="2" height="20"></td></tr>
	<tr class ="row2">
		<td colspan="2" align="center">
		<input type="submit" name="submit_app" value="<?php echo JText::_('JS_SAVE_ROLE'); ?>" />
		</td>
	</tr>

    </table>
			<input type="hidden" name="id" value="<?php if(isset($this->role)) echo $this->role->id; ?>" />
			<input type="hidden" name="view" value="application" />
			<input type="hidden" name="layout" value="roles" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="task" value="saverole" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />

		
	
  </form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;">
			<?php
				include_once('components/com_jsjobs/views/jscr.php');
			?>
			</td>
			</tr></table>
		</td>
	</tr>
	
</table>							