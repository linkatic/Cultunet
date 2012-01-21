<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	views/application/tmpl/viewjob.php
 ^ 
 * Description: template view for a job
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
  global $mainframe;


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

<!-- <table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<?php if ($this->config['cur_location'] == 1) {?>
	<tr><td height="0"></td></tr>
	<tr><td class="curloc">
		<?php $vj = $this->vj; 
			if ($vj == '1'){ $vm=1;//my jobs ?> 
				<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_MY_JOBS'); ?></a> > <?php echo JText::_('JS_VIEW_JOB'); ?>
			<?php }else if ($vj == '2'){ $vm=2; $aj=1;//job cat?>
				<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_CATEGORIES'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&fr=<?php echo $this->fr; ?>&jobcat=<?php echo $this->jobcat; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOBS_LIST_BY_CATEGORY'); ?></a> ><?php echo JText::_('JS_VIEW_JOB'); ?>
			<?php }else if ($vj == '3'){ $vm=3; $aj=2;//job search?>
				<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_JOB'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_SEARCH_RESULT'); ?></a> > <?php echo JText::_('JS_VIEW_JOB'); ?>
			<?php }else if ($vj == '4'){ $vm=4; //my applied jobs?>
				<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_APPLIED_JOBS'); ?></a> > <?php echo JText::_('JS_VIEW_JOB'); ?>
			<?php }else if ($vj == '5'){ $vm=5; $aj=3;//newest jobs?>
				<?php echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_NEWEST_JOBS'); ?></a> > <?php echo JText::_('JS_VIEW_JOB'); ?>
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
		<?php echo JText::_('JS_JOB_INFO'); ?>
	</td></tr>
	<tr><td height="3"></td></tr>
</table> -->
	<?php if( isset($this->job)){ ?>
	<?php echo '<h3>'.$this->config['title'].'</h3>'; ?>
	<div id="ficha_oferta_empleo">
		<div id="enviar_a_amigo">
			<a onclick="window.open(this.href,'win2','width=400,height=350,menubar=yes,resizable=yes'); return false;" title="E-mail" href="/gestionycultura.com/index.php?option=com_mailto&tmpl=component&link=<?php echo base64_encode($_SERVER['REQUEST_URI']); ?>"><img alt="E-mail" src="images/M_images/emailButton.png"> Enviar a un amigo</a>		
		</div>	
	<h2>
		<?php echo $this->job->title; ?>
		<?php 
		$days = $this->config['newdays'];
								$isnew = date("d-m-Y H:i:s", strtotime("-$days days"));
								if ($this->job->created > $isnew)
									echo "<span style='color:red;font-size:11px'> ".JText::_('JS_NEW')." </span>";
		?>
	</h2>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
      <tr>
        <td colspan="3" height="5"></td>
      </tr>
		<?php
		$trclass = array("odd", "even");
		$i = 0;
		$isodd = 1;
		foreach($this->fieldsordering as $field){ 
			//echo '<br> uf'.$field->field;
			switch ($field->field) {
				/*case "jobtitle": 
					$isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td width="5%">&nbsp;</td>
				        <td class="maintext" width="30%"><b><?php echo JText::_('JS_TITLE'); ?></b></td>
						<td class="maintext"><?php echo $this->job->title; 
							$days = $this->config['newdays'];
							$isnew = date("d-m-Y H:i:s", strtotime("-$days days"));
							if ($this->job->created > $isnew)
								echo "<font color='red'> ".JText::_('JS_NEW')." </font>";
						?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;*/
				case "company": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td width="5%"></td>
				        <td class="maintext"><b><?php echo JText::_('JS_COMPANY'); ?></b></td>
						<td class="maintext">
							
							<?php if (isset($_GET['jobcat'])) $jobcat = $_GET['jobcat']; else $jobcat=null;
							$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm='.$vm.'&md='.$this->job->companyid.'&jobcat='.$jobcat.'&Itemid='.$this->Itemid; ?>
							<?php /* <a href="<?php echo $link?>"><strong><?php echo $this->job->companyname; ?></strong></a> */ ?>
							
							
							<?php 
									//Si campo comvocante no es vacío lo mostramos
									if($this->convocante) echo $this->convocante; 
									else echo $this->job->companyname; 
							?>
						</td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
				<?php break;
				case "jobcategory": $isodd = 1 - $isodd; ?>
					 <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
						<td class="maintext"><b><?php echo JText::_('JS_CATEGORY'); ?></b></td>
						<td class="maintext"><?php echo $this->job->cat_title; ?></td>
					  </tr>
					  <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "jobtype": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_JOBTYPE'); ?></b></td>
						<td class="maintext"><?php echo $this->job->jobtypetitle; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td> </tr>
				<?php break;
				case "jobstatus": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_JOBSTATUS'); ?></b></td>
						<td class="maintext"><?php echo $this->job->jobstatustitle; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td> </tr>
				<?php break;
				case "jobshift": $isodd = 1 - $isodd; ?>
			      <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_SHIFT'); ?></b></td>
						<td class="maintext"><?php echo $this->job->shifttitle; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td> </tr>
				  <?php } ?>
				<?php /* break;
				case "jobsalaryrange":  ?>
			      <?php if ( $field->published == 1 ) { ?>
					<?php if ( $this->job->hidesalaryrange != 1 ) { // show salary 
					$isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_SALARYRANGE'); ?></b></td>
						<td class="maintext"><?php $salaryrange = $this->config['currency'] . $this->job->rangestart . ' - ' . $this->config['currency'] . $this->job->rangeend . JText::_('JS_PERMONTH');
							echo $salaryrange; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td></tr>
					<?php } ?>
				  <?php } */?>
				<?php /* break;
				case "heighesteducation": $isodd = 1 - $isodd; ?>
			      <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION'); ?></b></td>
						<td class="maintext"><?php echo $this->job->heighesteducationtitle; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td> </tr>
					<?php } */?>
				<?php break;
				case "noofjobs": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_NOOFJOBS'); ?></b></td>
						<td class="maintext"><?php echo $this->job->noofjobs; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php/* break;
				case "experience": $isodd = 1 - $isodd; ?>
			      <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_EXPERIENCE'); ?></b></td>
						<td class="maintext"><?php echo $this->job->experience; ?>&nbsp;<?php echo JText::_('JS_YEARS'); ?>
						</td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				  <?php } */?>
				<?php /* break;
				case "duration": $isodd = 1 - $isodd; ?>
			      <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_DURATION'); ?></b></td>
						<td class="maintext"><?php echo $this->job->duration; ?>
					</td>
			      </tr>
				      <tr><td colspan="3" height="1"></td></tr>
				  <?php } */?>
				<?php break;
				case "startpublishing": $isodd = 1 - $isodd; ?>
				      <?php //if ($vj == '1'){ //my jobs ?> 
						  <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
					        <td class="maintext"><b><?php echo JText::_('JS_START_PUBLISHING'); ?></b></td>
							<td class="maintext"><?php echo date('d-m-Y',strtotime($this->job->startpublishing)); ?></td>
						  </tr>
					      <tr><td colspan="3" height="1"></td></tr>
					  <?php //} ?>
				<?php break;
				case "stoppublishing": $isodd = 1 - $isodd; ?>
				      <?php //if ($vj == '1'){ //my jobs ?> 
						    <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
						        <td class="maintext"><b><?php echo JText::_('JS_STOP_PUBLISHING'); ?></b></td>
								<td class="maintext"><?php echo date('d-m-Y',strtotime($this->job->stoppublishing)); ?></td>
							</tr>
					      <tr><td colspan="3" height="1"></td></tr>
					  <?php //} */ ?>
				<?php break;
				case "description": $isodd = 1 - $isodd; ?>
				    <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_DESCRIPTION'); ?></b></td>
						<td class="maintext"><?php echo $this->job->description; ?></td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "qualifications": $isodd = 1 - $isodd; ?>
				    <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_QUALIFICATIONS'); ?></b></td>
						<td class="maintext"><?php echo $this->job->qualifications; ?></td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "prefferdskills": $isodd = 1 - $isodd; ?>
				    <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></b></td>
						<td class="maintext"><?php echo $this->job->prefferdskills; ?></td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "country": $isodd = 1 - $isodd; ?>
				    <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
						<td class="maintext"><?php echo $this->job->countryname; ?></td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "state": $isodd = 1 - $isodd; ?>
				    <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
						<td class="maintext"><?php if($this->job->statename != '') echo $this->job->statename; elseif($this->job->state != '') echo $this->job->state; ?></td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "county": $isodd = 1 - $isodd; ?>
				    <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
						<td class="maintext"><?php if($this->job->countyname != '') echo $this->job->countyname; elseif($this->job->county != '') echo $this->job->county; ?></td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "city": $isodd = 1 - $isodd; ?>
				    <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
				        <td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
						<td class="maintext"><?php if($this->job->cityname != '') echo $this->job->cityname; elseif($this->job->city != '') echo $this->job->city;?></td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				default:
					//echo '<br> default uf '.$filed->field;
					if ( $field->published == 1 ) { 
					
						//NO MUESTRA EL VALOR CORRECTO DEL AREA PROFESIONAL Y DE GESTION
						
						
						/* foreach($this->userfields as $ufield){ 
							if($field->field == $ufield[0]->id) {
								$isodd = 1 - $isodd; 
								$userfield = $ufield[0];
								$i++;
								echo '<tr class="'.$this->theme[$trclass[$isodd]] .'"><td></td>';
								echo '<td class="maintext"><b>'. $userfield->title .'</b></td>';
								if ($userfield->type != "select"){
									if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								}elseif ($userfield->type == "select"){
									if(isset($ufield[2])){ $fvalue = $ufield[2]->fieldtitle; $userdataid = $ufield[2]->id;}  else {$fvalue=""; $userdataid = ""; }
								}
								echo '<td class="maintext">'.$fvalue.'</td>';	
								echo '</tr>';
								echo '<tr><td colspan="3" height="1"></td></tr>';
							}
						}	 */ 
					}	

				?>
			<?php }
		}
		?>
		<?php $isodd = 1 - $isodd; ?>	
      <?php /*
      <tr class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td></td>
        <td class="maintext"><b><?php echo JText::_('JS_DATEPOSTED'); ?></b></td>
		<td class="maintext"><?php echo date('d-m-Y',strtotime($this->job->created)); ?></td>
      </tr>
	   */?>

      <tr>
        <td colspan="3" height="5"></td>
      </tr>
	<?php if (($vj == '2') || ($vj == '3') || ($vj == '5')){ ?>	
      <tr> 
        <td colspan="3" align="center"><br />
			<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_apply&aj='.$aj.'&jobcat='.$this->job->jobcategory.'&bi='.$this->job->id.'&Itemid='.$this->Itemid; ?>
						<a href="<?php echo $link?>" class="pageLink"><strong><?php echo JText::_('JS_APPLYNOW'); ?></strong></a>		   </td>
      </tr>
	<?php } ?>  
  
	</table>
	<?php }else echo JText::_('JS_RESULT_NOT_FOUND'); ?>
	</div><!-- Fin Ficha Oferta Empleo -->
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
