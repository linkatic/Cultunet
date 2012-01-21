<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/employer/tmpl/viewjob.php
 ^ 
 * Description: template view for a company
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
  global $mainframe;
$comma = 0;	

?>

<script type="text/javascript"> 
	alert('Desactivado temporalmente por el equipo de desarrollo');
	window.location="index.php?option=com_mtree&task=listcats&cat_id=83&Itemid=2";
</script> 

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
	<tr><td height="0"></td></tr>
	<tr><td class="curloc">
		<?php 	$vm =  $this->vm;
			if ($vm == '1'){ //my companies?> 
				<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_MY_COMPANIES'); ?></a> > <?php echo JText::_('JS_COMPANY_INFO'); ?>
			<?php }elseif ($vm == '2'){ //list jobs?>
				<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_CATEGORIES'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&cn=&jobcat=<?php echo $_GET['jobcat']; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOBS_LIST_BY_CATEGORY'); ?></a> ><?php echo JText::_('JS_COMPANY_INFO'); ?>
			<?php }elseif ($vm == '3'){ //job search?>
				<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_JOB'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_SEARCH_RESULT'); ?></a> > <?php echo JText::_('JS_COMPANY_INFO'); ?>
			<?php }else if ($vm == '4'){ $vm=2; //my applied jobs?>
				<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_APPLIED_JOBS'); ?></a> > <?php echo JText::_('JS_COMPANY_INFO'); ?>
			<?php }else if ($vm == '5'){ $vm=2; //newest jobs?>
				<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_NEWEST_JOBS'); ?></a> > <?php echo JText::_('JS_COMPANY_INFO'); ?>
			<?php } ?>
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
		<?php echo JText::_('JS_COMPANY_INFO'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table>
	<?php if(isset($this->company)){ ?>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
		<?php
		$trclass = array("odd", "even");
		$i = 0;
		$isodd = 1;
		
		?>
	  <tr class="odd" height="25"><td width="5">&nbsp;</td>
		<td colspan="2" align="center" class="maintext"><font size="+1"><strong><?php echo $this->company->name; ?></strong></font></td>
	  </tr>
	  <tr> <td colspan="3" height="1"></td> </tr>
	  <tr height="200" class="odd"><td ></td>
		<td width="200" valign="top">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
				<tr>
					<td height="210" style="max-width:200px;overflow:hidden;text-overflow:ellipsis" align="center">
					  <?php if ($this->company->logoisfile !=-1) { ?>
							<img  src="components/com_jsjobs/data/employer/comp_<?php echo $this->company->id;?>/logo/<?php echo $this->company->logofilename;?>" />
					  <?php }else { ?>
							<img width="200" height="54" src="components/com_jsjobs/images/blank_logo.png" />
					  <?php } ?>
					</td>
				</tr>
				<tr>
					<td align="center">
							<?php if (isset($vm)) $vm = $vm; else $vm='';
							if ($vm != '1'){?>
								<a class="pageLink" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&fr=cj&cd=<?php echo $this->company->id; ?>&cm=<?php echo $this->company->name; ?>&Itemid=<?php echo $this->Itemid; ?>" >
								<strong><?php echo JText::_('JS_VIEW_ALL_JOBS'); ?></strong></a>
							<?php } ?>	
					</td>
				</tr>
				<tr><td height="3"></td></tr>	
			</table>	
		</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
				      <tr> <td colspan="3" height="1"></td> </tr>
				      <?php if ($this->company->url) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td width="7"></td>
				        <td class="maintext"><b><?php echo JText::_('JS_URL'); ?></b></td>
						<td class="maintext"><?php echo $this->company->url; ?></td>
				      </tr>
					  <?php } ?>
				      <?php if ($this->company->contactname) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CONTACTNAME'); ?></b></td>
						<td class="maintext"><?php echo $this->company->contactname; ?></td>
				      </tr>
					  <?php } ?>
				      <?php if ($this->company->contactemail) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CONTACTEMAIL'); ?></b></td>
						<td class="maintext"><?php echo $this->company->contactemail; ?></td>
				      </tr>
					  <?php } ?>
					  <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CONTACTPHONE'); ?></b></td>
						<td class="maintext"><?php echo $this->company->contactphone; ?></td>
				      </tr>
				      <?php if ($this->company->address1) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_ADDRESS1'); ?></b></td>
						<td class="maintext"><?php echo $this->company->address1; ?></td>
				      </tr>
					  <?php } ?>
				      <?php if ($this->company->address2) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_ADDRESS2'); ?></b></td>
						<td class="maintext"><?php echo $this->company->address2; ?></td>
				      </tr>
					  <?php } ?>
					  <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_LOCATION'); ?></b></td>
						<td class="maintext">
					      <?php if ($this->company->cityname) { echo $this->company->cityname; $comma = 1; } elseif ($this->company->city) { echo $this->company->city; $comma = 1; }
						   if ($this->company->countyname) { if ($comma) echo ', '; else $comma = 1; echo $this->company->countyname; } elseif ($this->company->county) { if ($comma) echo ', '; else $comma = 1; echo $this->company->county; }
						   if ($this->company->statename) { if ($comma) echo ', '; else $comma = 1; echo $this->company->statename; } elseif ($this->company->state) { if ($comma) echo ', '; else $comma = 1; echo $this->company->state; }
						   if ($this->company->countryname) { if ($comma) echo ', '; else $comma = 1; echo $this->company->countryname; }
						   if ($this->company->zipcode) { if ($comma) echo ', '; else $comma = 1; echo $this->company->zipcode; }?>
					  </td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
			</table>
		</td>
	  </tr>
	  <tr> <td colspan="3" height="1"></td> </tr>
		<?php
		$trclass = array("odd", "even");
		$i = 0;
		$isodd = 0;
		foreach($this->fieldsordering as $field){ 
			//echo '<br> uf'.$field->field;
			switch ($field->field) {
				case "jobcategory": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CATEGORIES'); ?></b></td>
						<td class="maintext"><?php echo $this->company->cat_title; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
				<?php break;
				case "contactphone": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CONTACTPHONE'); ?></b></td>
						<td class="maintext"><?php echo $this->company->contactphone; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
					  <?php } ?>
				<?php break;
				case "contactfax": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CONTACTFAX'); ?></b></td>
						<td class="maintext"><?php echo $this->company->contactfax; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
					  <?php } ?>
				<?php break;
				case "since": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_SINCE'); ?></b></td>
						<td class="maintext"><?php echo $this->company->since; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
				  <?php } ?>
				<?php break;
				case "companysize": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_COMPANY_SIZE'); ?></b></td>
						<td class="maintext"><?php echo $this->company->companysize; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
				  <?php } ?>
				<?php break;
				case "income": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_INCOME'); ?></b></td>
						<td class="maintext"><?php echo $this->company->income; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
					  <?php } ?>
				<?php break;
				case "description": $isodd = 1 - $isodd; ?>
					  <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_DESCRIPTION'); ?></b></td>
						<td class="maintext"><?php echo $this->company->description; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
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
					
						foreach($this->userfields as $ufield){ 
							if($field->field == $ufield[0]->id) {
								$isodd = 1 - $isodd; 
								$userfield = $ufield[0];
								$i++;
								echo '<tr class="'.$this->theme[$trclass[$isodd]] .'"><td></td>';
								echo '<td class="maintext"><b>'. $userfield->title .'</b></td>';
								if ($userfield->type != "select"){
									if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								}elseif ($userfield->type == "select"){
									if(isset($ufield[2])){ $fvalue = $ufield[2]->fieldvalue; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								}
								echo '<td class="maintext">'.$fvalue.'</td>';	
								echo '</tr>';
								echo '<tr><td colspan="3" height="1"></td></tr>';
							}
						}	 
					}	
			}
			
		} 
		?>

    <tr>
    <tr>
        <td colspan="2" height="10"></td>
      <tr>
    </table>
	<?php }else echo JText::_('JS_RESULT_NOT_FOUND'); ?>
<?php
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
