<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/formcountry.php
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
	 <tr>
        <td width="100%" align="center"><?php echo JText::_('NAME'); ?> : 
		  &nbsp;&nbsp;&nbsp;<input class="inputbox required" type="text" name="name" size="40" maxlength="255" value="<?php if(isset($this->state)) echo $this->state->name; ?>" />
        </td>
      </tr>
      <tr>
        <td align="center"><?php echo JText::_('JS_PUBLISHED'); ?> : 
		  &nbsp;&nbsp;&nbsp;<input type="checkbox" name="enabled" value="Y" <?php if(isset($this->state))  {if ($this->state->enabled == 'Y') echo 'checked';} ?>/>
		  </td>
      </tr>

    </table>
			<input type="hidden" name="id" value="<?php if(isset($this->state)) echo $this->state->id; ?>" />
			<input type="hidden" name="countrycode" value="<?php echo $this->countrycode; ?>" />
			<input type="hidden" name="layout" value="countries" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="task" value="savestate" />
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