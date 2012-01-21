<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/applications/tmpl/loadcountryformfile.php
 ^ 
 * Description: Load country, states, counties and cities form file
 ^ 
 * History:		NONE
 ^ 
 */
 
// this is the basic listing scene when you click on the component 
// in the component menu
defined('_JEXEC') or die('Restricted access');
JRequest :: setVar('layout', 'loadaddressdata');
$_SESSION['cur_layout']='loadaddressdata';

JHTMLBehavior::formvalidation(); 

$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';
?>
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
<table width="100%">
	<tr>
		<td align="left" width="175" valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
			<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
			<table class="adminform" border="0">
				<thead>
				<tr valign="center" align="center" style="align:center;">
					<th>
						<?php echo JText::_('JS_LOAD_ADDRESS_TITLE'); ?>
					</th>
				</tr>
				</thead>
				<tr><td height="20"></td></tr>
				<?php if ($this->error != 2){ ?>
				<tr valign="top" >
					<td>
						<?php echo JText::_('JS_FILE'); ?> :&nbsp;<font color="red">*</font>&nbsp;<input type="file" class="inputbox  required" name="loadaddressdata" id="loadaddressdata" size="20" maxlenght='30'/>
					</td>
				</tr>
				<input type="hidden" name="actiontype" value="1" />
				<?php }else { ?>
				<tr valign="top" >
					<td>
						<?php echo JText::_('JS_ACTION'); ?> :&nbsp;<font color="red">*</font>&nbsp;<select name="actiontype" class="inputbox  required">
							<option value="3"><?php echo JText::_('JS_DELETE_OLD_INSERT_THIS'); ?></option>
							<option value="4"><?php echo JText::_('JS_INSERT_THIS'); ?></option>
						
					</td>
				</tr>
				<?php } ?>
				<tr><td height="20"></td></tr>
				<tr>
					<td align="center">
					<input class="button" type="submit" name="submit_app"  value="<?php echo JText::_('JS_LOAD_ADDRESS_DATA'); ?>" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="loadaddressdata" />
			<input type="hidden" name="check" value="" />
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