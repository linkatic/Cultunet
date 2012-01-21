<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/application/tmpl/resumeview.php
 ^ 
 * Description: template view for a employment application
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 global $mainframe;
 

?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $this->config['theme']; ?>" />
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<tr><td height="13"></td></tr>
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo JText::_('JS_VIEW_EMP_APP'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
      <tr ><td width="5%">&nbsp;</td>
        <td class="maintext" ><b><?php echo $this->application->resume; ?></b></td>
      </tr>
	  
</table>

<div width="100%">
<?php include_once('components/com_jsjobs/views/fr_jscr.php'); ?>
</div>

