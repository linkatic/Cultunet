<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/application/tmpl/formjob.php
 ^ 
 * Description: template for job posting form
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

 global $mainframe;

$editor = & JFactory :: getEditor();
JHTML :: _('behavior.calendar');


$document = &JFactory::getDocument();
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

<script language="javascript">
function myValidate(f) {
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


<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpanea">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<?php if ($this->config['cur_location'] == 1) {?>
	<tr><td height="0"></td></tr>
	<tr><td class="curloc">
		<?php if (isset($this->company) && ($this->company->id == '')){	?>
			<?php echo JText::_('JS_CUR_LOC'); ?> :  <?php echo JText::_('JS_NEW_JOB_INFO'); ?>
		<?php }else{	?>
			<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_COMPANIES'); ?></a> > <?php echo JText::_('JS_COMPNAY_INFO'); ?>
		<?php }	?>
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
		<?php echo JText::_('JS_COMPNAY_INFO'); ?>
	</td></tr>
	<tr><td height="10"></td></tr>
</table>
<?php
if ($this->userrole->rolefor == 1) { // employer
if ($this->canaddnewcompany == 1) { // add new company, in edit case always 1

?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data"  onSubmit="return myValidate(this);">
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php
		$i = 0;
		foreach($this->fieldsordering as $field){ 
			//echo '<br> uf'.$field->field;
			switch ($field->field) {
				case "jobcategory": ?>
				      <tr>
				        <td valign="top" align="right"><label id="jobcategorymsg" for="jobcategory"><?php echo JText::_('JS_CATEGORIES'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td><?php echo $this->lists['jobcategory']; ?></td>
				      </tr>
				<?php break;
				case "name": ?>
				      <tr>
				        <td width="20%" align="right"><label id="namemsg" for="name"><?php echo JText::_('JS_COMPANYNAME'); ?></label>&nbsp;<font color="red">*</font></td>
				          <td width="60%"><input class="inputbox required" type="text" name="name" id="name" size="40" maxlength="255" value="<?php if(isset($this->company)) echo $this->company->name; ?>" />
				        </td>
				      </tr>
				<?php break;
				case "url": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="urlmsg" for="url"><?php echo JText::_('JS_URL'); ?></label></td>
						<td><input class="inputbox" type="text" name="url" size="40" maxlength="100" value="<?php if(isset($this->company)) echo trim ($this->company->url); ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "contactname": ?>
				      <tr>
				        <td align="right"><label id="contactnamemsg" for="contactname"><?php echo JText::_('JS_CONTACTNAME'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td><input class="inputbox required" type="text" name="contactname" id="contactname" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactname; ?>" />
				        </td>
				      </tr>
				<?php break;
				case "contactphone": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="contactphonemsg" for="contactphone"><?php echo JText::_('JS_CONTACTPHONE'); ?></label></td>
				        <td><input class="inputbox" type="text" name="contactphone" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactphone; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "contactfax": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="companyfaxmsg" for="companyfax"><?php echo JText::_('JS_CONTACTFAX'); ?></label></td>
				        <td><input class="inputbox" type="text" name="companyfax" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->companyfax; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "contactemail": ?>
				      <tr>
				        <td align="right"><label id="contactemailmsg" for="contactemail"><?php echo JText::_('JS_CONTACTEMAIL');?></label>&nbsp;<font color="red">*</font></td>
				        <td><input class="inputbox required validate-email" type="text" name="contactemail" id="contactemail" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactemail; ?>" />
				        </td>
				      </tr>
				<?php break;
				case "since": ?>
					  <?php if ( $field->published == 1 ) { ?>
				  <tr>
			        <td valign="top" align="right"><label id="sincemsg" for="since"><?php echo JText::_('JS_SINCE'); ?></label></td>
			        <td><input class="inputbox" type="text" name="since" id="since" readonly size="10" maxlength="10" value="<?php if(isset($this->company)) echo $this->company->since; ?>" />
			        <input type="reset" class="button" value="..." onclick="return showCalendar('since','%Y-%m-%d');" onBlur="CheckDate('since');" /></td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "companysize": ?>
					  <?php if ( $field->published == 1 ) { ?>
			       <tr>
			        <td valign="top" align="right"><label id="companysize" for="companysize"><?php echo JText::_('JS_COMPANY_SIZE'); ?></label></td>
			        <td><input class="inputbox" type="text" name="companysize" id="companysize" size="20" maxlength="20" value="<?php if(isset($this->company)) echo $this->company->companysize; ?>" />
			        </td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "income": ?>
					  <?php if ( $field->published == 1 ) { ?>
				       <tr>
				        <td valign="top" align="right"><label id="incomemsg" for="income"><?php echo JText::_('JS_INCOME'); ?></label></td>
				        <td><input class="inputbox" type="text" name="income" id="income" size="20" maxlength="10" value="<?php if(isset($this->company)) echo $this->company->income; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "description": ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if ( $this->config['comp_editor'] == '1' ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr>
								<td colspan="2" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<font color="red">*</font></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->company))
										echo $editor->display('description', $this->company->description, '550', '300', '60', '20', false);
									else
										echo $editor->display('description', '', '550', '300', '60', '20', false);
								?>	
								</td>
							</tr>
						<?php } else {?>
				       <tr>
				        <td valign="top" align="right"><label id="descriptionmsg" for="description"><?php echo JText::_('JS_DESCRIPTION'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td>
							<textarea class="inputbox required" name="description" id="description" cols="60" rows="5"><?php if(isset($this->company)) echo $this->company->description; ?></textarea>
						</td>
				      </tr>
						<?php } ?>
					  <?php } ?>
				<?php break;
				case "country": ?>
				      <tr>
				        <td align="right"><label id="countrymsg" for="country"><?php echo JText::_('JS_COUNTRY'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td id="company_country">
						      <?php echo $this->lists['country']; ?>
				        </td>
				      </tr>
				<?php break;
				case "state": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="statemsg" for="state"><?php echo JText::_('JS_STATE'); ?></label></td>
				        <td id="company_state">
						<?php
							if ((isset($this->lists['state'])) && ($this->lists['state']!='')){
								echo $this->lists['state']; 
							} else{ ?>
								<input class="inputbox" type="text" name="state" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->state; ?>" />
							<?php } ?>
						</td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "county": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="countymsg" for="county"><?php echo JText::_('JS_COUNTY'); ?></label></td>
				        <td id="company_county"><?php 
							if ((isset($this->lists['county'])) && ($this->lists['county']!='')){
								echo $this->lists['county']; 
							} else{ ?>
								<input class="inputbox" type="text" name="county" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->county; ?>" />
							<?php } ?>
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "city": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="citymsg" for="city"><?php echo JText::_('JS_CITY'); ?></label></td>
				        <td id="company_city"><?php 
							if((isset($this->lists['city'])) && ($this->lists['city']!='')){
								echo $this->lists['city']; 
							} else{ ?>
								<input class="inputbox" type="text" name="city" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->city; ?>" />
							<?php } ?>
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "zipcode": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="zipcodemsg" for="zipcode"><?php echo JText::_('JS_ZIPCODE'); ?></label></td>
				        <td><input class="inputbox" type="text" name="zipcode" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->zipcode; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "address1": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="address1msg" for="address1"><?php echo JText::_('JS_ADDRESS1'); ?></label></td>
				        <td><input class="inputbox" type="text" name="address1" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->address1; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "address2": ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr>
				        <td align="right"><label id="address2msg" for="address2"><?php echo JText::_('JS_ADDRESS2'); ?></label></td>
				        <td><input class="inputbox" type="text" name="address2" size="40" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->address2; ?>" />
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "logo": ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if (isset($this->company)){ 
									if($this->company->logofilename != '') {?>
										<tr><td></td><td><input type='checkbox' name='deletelogo' value='1'><?php echo JText::_('JS_DELETE_LOGO_FILE') .'['.$this->company->logofilename.']'; ?></td></tr>
						<?php }
							}?>				
						<tr>
							<td align="right" ><label id="logomsg" for="logo">	<?php echo JText::_('JS_COMPANY_LOGO'); ?>	</label></td>
							<td><input type="file" class="inputbox" name="logo" size="20" maxlenght='30'/>
							<br><small><?php echo JText::_('JS_MAXIMUM_WIDTH');?> : 200px)</small>
							<br><small><?php echo JText::_('JS_MAXIMUM_FILE_SIZE').' ('.$this->config['company_logofilezize']; ?>KB)</small></td>
						</tr>
					  <?php } ?>
				<?php break;
				case "smalllogo": ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if (isset($this->company)) 
									if($this->company->smalllogofilename != '') {?>
										<tr><td></td><td><input type='checkbox' name='deletesmalllogo' value='1'><?php echo JText::_('JS_DELETE_SMALL_LOGO_FILE') .'['.$this->company->smalllogofilename.']'; ?></td></tr>
						<?php } ?>				
						<tr>
							<td align="right" >	<label id="smalllogomsg" for="smalllogo"><?php echo JText::_('JS_COMPANY_SMALL_LOGO'); ?>	</label></td>
							<td><input type="file" class="inputbox" name="smalllogo" size="20" maxlenght='30'/></td>
						</tr>
					  <?php } ?>
				<?php break;
				case "aboutcompany": ?>
					  <?php if ( $field->published == 1 ) { ?>
						<?php if (isset($this->company)) 
									if($this->company->aboutcompanyfilename != '') {?>
										<tr><td></td><td><input type='checkbox' name='deleteaboutcompany' value='1'><?php echo JText::_('JS_DELETE_ABOUT_COMPANY_FILE') .'['.$this->company->aboutcompanyfilename.']'; ?></td></tr>
						<?php } ?>				
						<tr>
							<td align="right" >	<label id="aboutcompanymsg" for="aboutcompany"><?php echo JText::_('JS_ABOUT_COMPANY'); ?>	</label></td>
							<td><input type="file" class="inputbox" name="aboutcompany" size="20" maxlenght='30'/></td>
						</tr>
					  <?php } ?>
				<?php break;
				  
				default:
					//echo '<br> default uf '.$filed->field;
					if ( $field->published == 1 ) {
						if (isset($this->userfields))
						foreach($this->userfields as $ufield){ 
							if($field->field == $ufield[0]->id) {
								$userfield = $ufield[0];
								$i++;
								echo "<tr><td valign='top' align='right'>";
								if($userfield->required == 1){
									echo "<label id=".$userfield->name."msg for=$userfield->name>$userfield->title</label>&nbsp;<font color='red'>*</font>";
									$cssclass = "class ='inputbox required' ";
								}else{
									echo $userfield->title; $cssclass = "class='inputbox' ";
								}
								echo "</td><td>"	;
									
//									echo '<br> ft '.$userfield->type;
								$readonly = $userfield->readonly ? ' readonly="readonly"' : '';
		   						$maxlength = $userfield->maxlength ? 'maxlength="'.$userfield->maxlength.'"' : '';
								if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								echo '<input type="hidden" id="userfields_'.$i.'_id" name="userfields_'.$i.'_id"  value="'.$userfield->id.'"  />';
								echo '<input type="hidden" id="userdata_'.$i.'_id" name="userdata_'.$i.'_id"  value="'.$userdataid.'"  />';
								switch( $userfield->type ) {
									case 'text':
										echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
										break;
									case 'emailaddress':
										echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
										break;
									case 'date':
										echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
									    ?><input type="reset" class="button" value="..." onclick="return showCalendar('userfields_<?php echo $i; ?>','%Y-%m-%d');" /><?php
										break;
									case 'textarea':
										echo '<textarea name="userfields_'.$i.'" id="userfields_'.$i.'_field" cols="'.$userfield->cols.'" rows="'.$userfield->rows.'" '.$readonly.'>'.$fvalue.'</textarea>';
										break;	
									case 'checkbox':
										echo '<input type="checkbox" name="userfields_'.$i.'" id="userfields_'.$i.'_field" value="1" '.  'checked="checked"' .'/>';
										break;	
									case 'select':
										$htm = '<select name="userfields_'.$i.'" id="userfields_'.$i.'" >';
										if (isset ($ufield[2])){
											foreach($ufield[2] as $opt){
												if ($opt->id == $fvalue)
													$htm .= '<option value="'.$opt->id.'" selected="yes">'. $opt->fieldtitle .' </option>';
												else
													$htm .= '<option value="'.$opt->id.'">'. $opt->fieldtitle .' </option>';
											}
										}
										$htm .= '</select>';	
										echo $htm;
										break;	
									case 'editortext':
										$editor =& JFactory::getEditor();
										if(isset($this->company))
											echo $editor->display("userfields_$i", $fvalue, '550', '300', '60', '20', false);
										else
											echo $editor->display("userfields_$i", '', '550', '300', '60', '20', false);
									
								}
								echo '</td></tr>';
							}
						}
						
					}
			}
			
		} 
		echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="'.$i.'"  />';
		?>

    <tr>
        <td colspan="2" height="10"></td>
      <tr>
	<tr>
		<td colspan="2" align="center">
		<input class="button" type="submit" name="submit_app" value="<?php echo JText::_('JS_SAVE_COMPANY'); ?>" />
		</td>
	</tr>
    </table>


	<?php 
				if(isset($this->company)) {
					if (($this->company->created=='0000-00-00 00:00:00') || ($this->company->created==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->company->created;
				}else
					$curdate = date('Y-m-d H:i:s');
				
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="savecompany" />
			<input type="hidden" name="check" value="" />
			
		  <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		  <input type="hidden" name="id" value="<?php if(isset($this->company)) echo $this->company->id; ?>" />
		  
		  
<script language=Javascript>
function dochange(src, val){
	var pagesrc = 'company_'+src;
	document.getElementById(pagesrc).innerHTML="Loading ...";
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
		
        	document.getElementById(pagesrc).innerHTML=xhr.responseText; //retuen value

			if(src=='state'){
				countyhtml = "<input class='inputbox' type='text' name='county' size='40' maxlength='100'  />";
				cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
				document.getElementById('company_county').innerHTML=countyhtml; //retuen value
				document.getElementById('company_city').innerHTML=cityhtml; //retuen value
			}else if(src=='county'){
				cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
				document.getElementById('company_city').innerHTML=cityhtml; //retuen value
			}
      }
    }
 
	xhr.open("GET","index2.php?option=com_jsjobs&task=listaddressdata&data="+src+"&val="+val,true);
	xhr.send(null);
}

</script>
			  

		</form>
<?php 
} else{ // can not add new company
	echo "<strong><font color='red'>".JText::_('JS_COMPANY_LIMIT_EXCEED')." <a href='#'>".JText::_('JS_COMPANY_LIMIT_EXCEED_ADMIN')."</a></font></strong>";
}
} else{ // not allowed job posting
echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');

}
}//ol
?>
<div width="100%">
<?php include_once('components/com_jsjobs/views/fr_jscr.php'); ?>
</div>

