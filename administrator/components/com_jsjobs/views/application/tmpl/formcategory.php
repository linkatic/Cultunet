<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/views/application/tmpl/formcategory.php
 * 
 * Description: Form template for a single category
 * 
 * History:		NONE
 * 
 */
 
defined('_JEXEC') or die('Restricted access'); 
jimport('joomla.html.pane');
JHTMLBehavior::formvalidation(); 

$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';
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
	<form action="index.php" method="POST" name="adminForm" id="adminForm" >
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminlist">
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
	 <tr>
        <td width="100%" align="center"><?php echo JText::_('JS_CATEGORY_TITLE'); ?> : 
		  &nbsp;&nbsp;&nbsp;<input class="inputbox required" type="text" name="cat_title" size="40" maxlength="255" value="<?php if(isset($this->application)) echo $this->application->cat_title; ?>" />
        </td>
      </tr>
      <tr>
        <td align="center"><?php echo JText::_('JS_PUBLISHED'); ?> : 
		  &nbsp;&nbsp;&nbsp;<input type="checkbox" name="isactive" value="1" <?php if(isset($this->application))  {if ($this->application->isactive == '1') echo 'checked';} ?>/>
		  </td>
      </tr>
	<tr><td  height="20"></td></tr>
	<tr>
		<td  align="center">
		<input type="submit" class="button" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo JText::_('JS_SAVE_CATEGORY'); ?>" />
		</td>
	</tr>

    </table>
			<input type="hidden" name="id" value="<?php if(isset($this->application)) echo $this->application->id; ?>" />
			<input type="hidden" name="nisactive" value="1" />
			<input type="hidden" name="view" value="application" />
			<input type="hidden" name="layout" value="categories" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="task" value="savecategory" />
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