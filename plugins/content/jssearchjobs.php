<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 11, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	Pplugin/jssearchjobs.php
 ^ 
 * Description: Plugin for JS Jobs
 ^ 
 * History:		NONE
 ^ 
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Import Joomla! Plugin library file
jimport('joomla.plugin.plugin');
$document = &JFactory::getDocument();
$document->addScript( JURI::base() . '/includes/js/joomla.javascript.js');
JHTML :: _('behavior.calendar');
 
//The Content plugin Loadmodule
class plgContentJSSearchJobs extends JPlugin
{
        public function onPrepareContent( &$row, &$params, $page=0 )
        {
                if ( JString::strpos( $row->text, 'jssearchjobs' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jssearchjobs\s*.*?}/i';
                if ( !$this->params->get( 'enabled', 1 ) ) {
                        $row->text = preg_replace( $regex, '', $row->text );
                        return true;
                }
                preg_match_all( $regex, $row->text, $matches );
                $count = count( $matches[0] );
                if ( $count ) {
                        // Get plugin parameters
                        $style = $this->params->def( 'style', -2 );
                        $this->_process( $row, $matches, $count, $regex, $style );
                }
        }

        protected function _process( &$row, &$matches, $count, $regex, $style )
        {
                for ( $i=0; $i < $count; $i++ )
                {
                        $load = str_replace( 'jssearchjobs', '', $matches[0][$i] );
                        $load = str_replace( '{', '', $load );
                        $load = str_replace( '}', '', $load );
                        $load = trim( $load );
 
                        $modules       = $this->_load( $load, $style );
                        $row->text         = preg_replace( '{'. $matches[0][$i] .'}', $modules, $row->text );
                }
                $row->text = preg_replace( $regex, '', $row->text );
        }

        protected function _load( $position, $style=-2 )
        {
                $document      = &JFactory::getDocument();
                $renderer      = $document->loadRenderer('module');
                $params                = array('style'=>$style);
 
                $db = JFactory::getDBO();
$sh_title = $this->params->get('shtitle', 1);
$title = $this->params->get('title', 'Search Jobs');
$sh_category = $this->params->get('category', 1);
$sh_jobtype = $this->params->get('jobtype', 1);
$sh_jobstatus = $this->params->get('jobstatus', 1);
$sh_salaryrange = $this->params->get('salaryrange', 1);
$sh_heighesteducation = $this->params->get('heighesteducation', 1);
$sh_shift = $this->params->get('shift', 1);
$sh_experience = $this->params->get('experience', 1);
$sh_durration = $this->params->get('durration', 1);
$sh_startpublishing = $this->params->get('startpublishing', 1);
$sh_stoppublishing = $this->params->get('stoppublishing', 1);
$sh_company = $this->params->get('company', 1);
$sh_addresses = $this->params->get('addresses', 1);
$itemid =  JRequest::getVar('Itemid');

	// Categories *********************************************
	if ($sh_category == 1){
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_categories')." WHERE isactive = 1 ORDER BY cat_title ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if($rows){
			$jobcategories = array();
			$jobcategories[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row)
				$jobcategories[] =  array('value' => JText::_($row->id),'text' => JText::_($row->cat_title));
		}	
		$job_categories = JHTML::_('select.genericList', $jobcategories, 'jobcategory', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	}
	
	//Companies *********************************************
	if ($sh_company == 1){
		$query = "SELECT id, name FROM ".$db->nameQuote('#__js_job_companies')." ORDER BY name ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$companies = array();
			$companies[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row)
				$companies[] =  array('value' => $row->id,'text' => $row->name);
		}	
		$search_companies = JHTML::_('select.genericList', $companies, 'company', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	
	}
	//Job Types *********************************************
	if ($sh_jobtype == 1){
		$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_jobtypes')." WHERE isactive = 1 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if($rows){
			$jobtype = array();
			$jobtype[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row)
				$jobtype[] =  array('value' => JText::_($row->id),'text' => JText::_($row->title));
		}	
		$job_type = JHTML::_('select.genericList', $jobtype, 'jobtype', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');

	}
	//Job Status *********************************************
	if ($sh_jobstatus == 1){
		$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_jobstatus')." WHERE isactive = 1 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if($rows){
			$jobstatus = array();
			$jobstatus[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row)	
				$jobstatus[] =  array('value' => JText::_($row->id),	'text' => JText::_($row->title));
		}	
		$job_status = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');

	}
	//Job Heighest Education  *********************************************
	if ($sh_heighesteducation == 1){
		$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_heighesteducation')." WHERE isactive = 1 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if($rows){
			$heighesteducation = array();
			$heighesteducation[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row)	
				$heighesteducation[] =  array('value' => JText::_($row->id),	'text' => JText::_($row->title));
		}	
		$heighest_finisheducation = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	}
	
	//Shifts *********************************************
	if ($sh_shift == 1){
		$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_shifts')." WHERE isactive = 1 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if($rows){
			$shifts = array();
			$shifts[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row)	
				$shifts[] =  array('value' => JText::_($row->id),	'text' => JText::_($row->title));
		}						
		$search_shift = JHTML::_('select.genericList', $shifts, 'shift', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
		
	}
	// Salary Rnage *********************************************
	if ( $sh_salaryrange == 1 ) { 
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_config')." WHERE configname = 'currency' ";
		$db->setQuery( $query );
		$cur = $db->loadObject();
		if ($cur) $currency = $cur->configvalue;
		else $currency = 'Rs';
		
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_salaryrange')." ORDER BY 'id' ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($rows){
			$jobsalaryrange = array();
			$jobsalaryrange[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row){
				$salrange = $currency .' '.$row->rangestart.' - '.$currency .' '. $row->rangeend;
				$jobsalaryrange[] =  array('value' => JText::_($row->id),'text' => JText::_($salrange));
			}
		}	
		$job_salaryrange = JHTML::_('select.genericList', $jobsalaryrange, 'jobsalaryrange', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	}


				$istr = 1;
				$slink = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='.$itemid);
				    $contents = '<form action="'.$slink.'" method="post" name="adminForm" id="adminForm">';
					$contents .= '<input type="hidden" name="isjobsearch" value="1" />';
					$contents .= '<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">';
				      if ($sh_title == 1)$contents .= '<tr><td colsapn="4"><h2><u>'.$title.'</u></h2></td></tr>';
				      $contents .= '<tr><td width="25%" align="right">'.JText::_('Job Title').'</td>';
						$contents .= '<td><input class="inputbox" type="text" name="title" size="27" maxlength="255"  /> </td> </tr>';
				       if ( $sh_category == 1 ) { 
						if ($istr == 1){$contents .= '<tr>'; $istr = 0;} else $istr = 1;
						$contents .= '<tr><td valign="top" align="right">'. JText::_('Categories').'</td>';
						$contents .= '<td>'. $job_categories.'</td>';
					      if ($istr == 1)$contents .= '</tr>'; 
				       } 
				      if ( $sh_jobtype == 1 ) { 
					  if ($istr == 1){$contents .= '<tr>'; $istr = 0;} else $istr = 1;
				      $contents .= '<td valign="top" align="right">'. JText::_('Job Types').'</td>';
					  $contents .= '<td>'. $job_type.'</td> ';
					      if ($istr == 1)$contents .= '</tr>'; 
				       } 
				      if ( $sh_jobstatus == 1 ) { 
						if ($istr == 1){$contents .= '<tr>'; $istr = 0;} else $istr = 1;
				        $contents .= '<td valign="top" align="right">'. JText::_('Job Status').'</td>';
				        $contents .= '<td>'. $job_status.'</td>';
						if ($istr == 1)$contents .= '</tr>'; 
				       } 
				      if ( $sh_salaryrange == 1 ) { 
						if ($istr == 1){$contents .= '<tr>'; $istr = 0;} else $istr = 1;
					  $contents .= '<td valign="top" align="right">'. JText::_('Salary Range').'</td>';
				        $contents .= '<td>'. $job_salaryrange.'</td>';
						if ($istr == 1)$contents .= '</tr>'; 
				       } 
				      if ( $sh_heighesteducation == 1 ) { 
						if ($istr == 1){$contents .= '<tr>'; $istr = 0;} else $istr = 1;
				        $contents .= '<td valign="top" align="right">'. JText::_('Highest Education').'</td>';
				        $contents .= '<td>'. $heighest_finisheducation.'</td>';
						if ($istr == 1)$contents .= '</tr>'; 
				       } 
				      if ( $sh_shift == 1 ) { 
						if ($istr == 1){$contents .= '<tr>'; $istr = 0;} else $istr = 1;
				        $contents .= '<td valign="top" align="right">'. JText::_('Shift').'</td>';
				        $contents .= '<td>'. $search_shift.'</td>';
						if ($istr == 1)$contents .= '</tr>'; 
				       } 
				      if ( $sh_experience == 1 ) { 
						if ($istr == 1){$contents .= '<tr>'; $istr = 0;} else $istr = 1;
				        $contents .= '<td valign="top" align="right">'. JText::_('Experience').'</td>';
				        $contents .= '<td><input class="inputbox" type="text" name="experience" size="10" maxlength="15"  /></td>';
						if ($istr == 1)$contents .= '</tr>'; 
				       } 
				      if ( $sh_durration == 1 ) { 
						if ($istr == 1){$contents .= '<tr>'; $istr = 0;} else $istr = 1;
				        $contents .= '<td valign="top" align="right">'. JText::_('Duration').'</td>';
				        $contents .= '<td><input class="inputbox" type="text" name="durration" size="10" maxlength="15"  /></td>';
						if ($istr == 1)$contents .= '</tr>'; 
				       } 
				      if ( $sh_startpublishing == 1 ) { 
						if ($istr == 1){$contents .= '<tr>'; $istr = 0;} else $istr = 1;
				        $contents .= '<td valign="top" align="right">'. JText::_('Start Publishing').'</td>';
				        $contents .= '<td><input class="inputbox" type="text" name="startpublishing" id="startpublishingpgsr" readonly class="Shadow Bold" size="10" value="" />';
						$contents .= ' <input type="reset" class="button" value="..." onclick="return showCalendar(\'startpublishingpgsr\',\'%Y-%m-%d\');"  /></td>';
						if ($istr == 1)$contents .= '</tr>'; 
				       } 
				      if ( $sh_stoppublishing == 1 ) { 
						if ($istr == 1){$contents .= '<tr>'; $istr = 0;} else $istr = 1;
				        $contents .= '<td valign="top" align="right">'. JText::_('Stop Publishing').'</td>';
				        $contents .= '<td><input class="inputbox" type="text" name="stoppublishing" id="stoppublishingpgsr" readonly class="Shadow Bold" size="10" value="" />';
							        $contents .= '<input type="reset" class="button" value="..." onclick="return showCalendar(\'stoppublishingpgsr\',\'%Y-%m-%d\');"  /></td>';
						if ($istr == 1)$contents .= '</tr>'; 
				       } 

				      if ( $sh_company == 1 ) { 
						if ($istr == 1){$contents .= '<tr>'; $istr = 0;} else $istr = 1;
				        $contents .= '<td align="right">'. JText::_('Company Name').'</td>';
				        $contents .= '<td>'. $search_companies.'';
				        $contents .= '</td>';
						if ($istr == 1)$contents .= '</tr>'; 
				       } 
					  if ($istr == 0)$contents .= '<td></td><td></td></tr>'; 
					$contents .= '<tr>';
						$contents .= '<td colspan="4" align="center">';
						$contents .= '<input type="submit" class="button" name="submit_app" onclick="document.adminForm.submit();" value="'. JText::_('Search Jobs').'" />';
						$contents .= '</td>';
					$contents .= '</tr>';
				    $contents .= '</table>';

							$contents .= '<input type="hidden" name="view" value="jobseeker" />';
							$contents .= '<input type="hidden" name="layout" value="job_searchresults" />';
							$contents .= '<input type="hidden" name="option" value="com_jsjobs" />';
							
						  
						  
							  
						$contents .= '</form>';

	
               return $contents;
        }
}



?>
