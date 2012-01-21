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

<table cellpadding="0" cellspacing="0" border="0" width="100%" >
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<?php if ($this->config['cur_location'] == 1) {?>
	<tr><td class="curloc">
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_SEARCH_JOB'); ?>
	</td></tr>
	<?php } ?>
	<tr><td>
			<?php 
			if (sizeof($this->jobseekerlinks) != 0){
				echo '<div id="toplinks"><ul>';
				foreach($this->jobseekerlinks as $lnk)	{ ?>
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
		<?php echo JText::_('JS_SEARCH_JOB'); ?>
	</td></tr>
	<tr><td height="10"></td></tr>
</table>
<?php
if (isset($this->userrole->rolefor)){
	if ($this->userrole->rolefor != ''){
		if ($this->userrole->rolefor == 2) // job seeker
			$allowed = true;
		else
			$allowed = false;
	}else{
		$allowed = true;
	}
}else $allowed = true; // user not logined
if ($allowed == true) { 
?>
<!--<form action="index.php" method="POST" name="adminForm" enctype="multipart/form-data">-->
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='. $this->Itemid); ?>" method="post" name="adminForm" id="adminForm">
    <input type="hidden" name="isjobsearch" value="1" />
	<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
      <tr>
        <td width="20%" align="right"><?php echo JText::_('JS_JOB_TITLE'); ?></td>
          <td width="60%"><input class="inputbox" type="text" name="title" size="40" maxlength="255"  />
        </td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_CATEGORIES'); ?></td>
        <td><?php echo $this->searchoptions['jobcategory']; ?></td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_JOBTYPE'); ?></td>
        <td><?php echo $this->searchoptions['jobtype']; ?></td>
      </tr>
      <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_JOBSTATUS'); ?></td>
        <td><?php echo $this->searchoptions['jobstatus']; ?></td>
      </tr>
      <?php if ( $this->config['search_job_salaryrange'] == '1' ) { ?>
	  <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_SALARYRANGE'); ?></td>
        <td><?php echo $this->searchoptions['jobsalaryrange']; ?></td>
      </tr>
       <?php } ?>
      <?php if ( $this->config['search_job_heighesteducation'] == '1' ) { ?>
	   <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION'); ?></td>
        <td><?php echo $this->searchoptions['heighestfinisheducation']; ?></td>
      </tr>
       <?php } ?>
      <?php if ( $this->config['search_job_shift'] == '1' ) { ?>
	   <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_SHIFT'); ?></td>
        <td><?php echo $this->searchoptions['shift']; ?></td>
      </tr>
       <?php } ?>
      <?php if ( $this->config['search_job_experience'] == '1' ) { ?>
	   <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_EXPERIENCE'); ?></td>
        <td><input class="inputbox" type="text" name="experience" size="10" maxlength="15"  /></td>
      </tr>
       <?php } ?>
      <?php if ( $this->config['search_job_durration'] == '1' ) { ?>
	   <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_DURATION'); ?></td>
        <td><input class="inputbox" type="text" name="durration" size="10" maxlength="15"  /></td>
      </tr>
       <?php } ?>
      <?php if ( $this->config['search_job_startpublishing'] == '1' ) { ?>
	   <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_START_PUBLISHING'); ?></td>
        <td><input class="inputbox" type="text" name="startpublishing" id="startpublishing" readonly class="Shadow Bold" size="10" value="" />
			        <input type="reset" class="button" value="..." onclick="return showCalendar('startpublishing','%Y-%m-%d');"  /></td>
      </tr>
       <?php } ?>
      <?php if ( $this->config['search_job_stoppublishing'] == '1' ) { ?>
	   <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_STOP_PUBLISHING'); ?></td>
        <td><input class="inputbox" type="text" name="stoppublishing" id="stoppublishing" readonly class="Shadow Bold" size="10" value="" />
			        <input type="reset" class="button" value="..." onclick="return showCalendar('stoppublishing','%Y-%m-%d');"  /></td>
      </tr>
       <?php } ?>

      <tr>
        <td align="right"><?php echo JText::_('JS_COMPANYNAME'); ?></td>
        <td><?php echo $this->searchoptions['companies']; ?>
        </td>
      </tr>
      <tr>
        <td align="right"><?php echo JText::_('JS_COUNTRY'); ?></td>
        <td id="country">
		      <?php //echo $this->options['country']; ?>
			  <input class="inputbox" type="text" name="country" size="40" maxlength="100" />
        </td>
      </tr>
      <tr>
        <td align="right"><?php echo JText::_('JS_STATE'); ?></td>
        <td id="state">
				<input class="inputbox" type="text" name="state" size="40" maxlength="100" />
		</td>
      </tr>
      <tr>
        <td align="right"><?php echo JText::_('JS_COUNTY'); ?></td>
        <td id="county">
				<input class="inputbox" type="text" name="county" size="40" maxlength="100" />
		 </td>
      </tr>
      <tr>
        <td align="right"><?php echo JText::_('JS_CITY'); ?></td>
        <td id="city">
				<input class="inputbox" type="text" name="city" size="40" maxlength="100"  />
        </td>
      </tr>
      <tr>
        <td align="right"><?php echo JText::_('JS_ZIPCODE'); ?></td>
        <td><input class="inputbox" type="text" name="zipcode" size="40" maxlength="100"  />
        </td>
      </tr>
	<tr>
		<td colspan="2" align="center">
		<input type="submit" class="button" name="submit_app" onclick="document.adminForm.submit();" value="<?php echo JText::_('JS_SEARCH_JOB'); ?>" />
		</td>
	</tr>
    </table>

			<input type="hidden" name="view" value="jobseeker" />
			<input type="hidden" name="layout" value="job_searchresults" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task11" value="view" />
			
		  
		  
<script language=Javascript>
function dochange(src, val){
	var xhr; 
	try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
	catch (e) 
	{
		try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
		catch (e2) 
		{
		  try {  xhr = new XMLHttpRequest();     }
		  catch (e3) {  xhr = false;   }
		}
	 }

	xhr.onreadystatechange = function(){
   
      if(xhr.readyState == 4 && xhr.status == 200){
 
        	document.getElementById(src).innerHTML=xhr.responseText; //retuen value

			if(src=='state'){
			countyhtml = "<input class='inputbox' type='text' name='county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
			document.getElementById('county').innerHTML=countyhtml; //retuen value
			document.getElementById('city').innerHTML=cityhtml; //retuen value
			}else if(src=='county'){
				cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
				document.getElementById('city').innerHTML=cityhtml; //retuen value
			}
 
      }
    }
 
	xhr.open("GET","index2.php?option=com_jsjobs&task=listsearchaddressdata&data="+src+"&val="+val,true);
	xhr.send(null);

}

window.onLoad=dochange('country', -1);         // value in first dropdown
</script>
			  

		</form>
<?php

} else{ // not allowed job posting
echo JText::_('EA_YOU_ARE_NOT_ALLOWED_TO_VIEW');
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
