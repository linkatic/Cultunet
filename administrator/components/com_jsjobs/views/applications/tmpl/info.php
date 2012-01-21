<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/views/application/tmpl/info.php
 ^ 
 * Description: JS Jobs Information
 ^ 
 * History:		NONE
 ^ 
 */

 JRequest :: setVar('layout', 'info');
$_SESSION['cur_layout']='info';

$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';

?>
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
			<form action="index.php" method="POST" name="adminForm">
			  
			    <table cellpadding="2" cellspacing="4" border="1" width="100%" class="adminform">
			      <tr align="left" height="55" valign="middle" class="adminform">
			         <td align="left" valign="middle"><h1><?php echo JText::_('JS Jobs') ; ?></h1></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_CREATE_BY') . ' :<strong> ' . JText::_('Ahmad Bilal').'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_COMPANY') . ' :<strong> ' . JText::_('Joom Sky').'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_PROJECT_NAME') . ' :<strong> ' . JText::_('com_jsjobs').'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_VIRSION') . ' : <strong>1.0.5.8 beta - c</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_DESCCRIPTION') . ' :<strong> ' . JText::_('A component for job posting and resume submission.').'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_CONTACT_INFO') . ' : <strong>' . JText::_('+92 322 556 4668').'</strong>'; ?>
			         <?php echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='mailto:ahmad@joomsky.com'><strong>" . JText::_('ahmad@joomsky.com') . "</strong></a>"; ?>
			         <?php echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://www.joomsky.com' target='_blank'><strong>" . JText::_('www.joomsky.com') ."</strong></a>"; ?>
					 </td>
			      </tr>
			    </table>
			  



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
<script language="javascript" type="text/javascript">
	dhtml.cycleTab('tab1');
</script>
