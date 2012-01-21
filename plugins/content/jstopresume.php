<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 11, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	Pplugin/jsnewestresume.php
 ^ 
 * Description: Plugin for JS Jobs
 ^ 
 * History:		NONE
 ^ 
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Import Joomla! Plugin library file
jimport('joomla.plugin.plugin');
 
//The Content plugin Loadmodule
class plgContentJSTopResume extends JPlugin
{
        public function onPrepareContent( &$row, &$params, $page=0 )
        {
                if ( JString::strpos( $row->text, 'jstopresume' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jstopresume\s*.*?}/i';
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
                        $load = str_replace( 'jstopresume', '', $matches[0][$i] );
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
				$noofresumes = $this->params->get('noofresumes');
				$theme = $this->params->get('theme');
				//$theme = 1; //js jobs  theme
				if ($theme == 1){ // js jobs theme
					$trclass = array("odd", "even");
					$query = "SELECT * FROM ". $db->nameQuote('#__js_job_config')." WHERE configname = 'theme' ";
					$db->setQuery($query);
					//echo $query;
					if ($config = $db->loadObject()) $css = $config->configvalue; else $css = 'jsjobs01.css'; 
					if ($css == 'templatetheme.css') $trclass = array("sectiontableentry1", "sectiontableentry2");
				?>
					<link rel="stylesheet" type="text/css" media="all" href="components/com_jsjobs/css/<?php echo $css; ?>" />
				<?php
				}elseif ($theme == 2){ // template theme
					$trclass = array("sectiontableentry1", "sectiontableentry2");
				}else 	$trclass = array("", ""); //no theme

				$query = "SELECT resume.id, resume.application_title, resume.first_name, resume.middle_name, resume.last_name
					, resume.gender, resume.job_category, resume.iamavailable, resume.jobsalaryrange, resume.jobtype, resume.heighestfinisheducation
					, resume.total_experience
					, resume.create_date, cat.cat_title
					, jobtype.title AS jobtypetitle , education.title as educationtitle
					,country.name AS countryname, salary.rangestart, salary.rangeend
					FROM ". $db->nameQuote('#__js_job_resume')." AS resume 
					JOIN ". $db->nameQuote('#__js_job_categories')." AS cat ON resume.job_category = cat.id 
					JOIN ". $db->nameQuote('#__js_job_jobtypes')." AS jobtype ON resume.jobtype = jobtype.id 
					LEFT JOIN ". $db->nameQuote('#__js_job_heighesteducation')." AS education ON resume.heighestfinisheducation = education.id 
					LEFT JOIN ". $db->nameQuote('#__js_job_salaryrange')." AS salary ON resume.jobsalaryrange = salary.id 
					LEFT JOIN ". $db->nameQuote('#__js_job_countries')." AS country ON resume.nationality = country.code 
					WHERE resume.status = 1 
					ORDER BY resume.hits DESC LIMIT ".$noofresumes;
				//echo $query;
				$db->setQuery($query);
				$resumes = $db->loadObjectList();

				$contents = '';
				$noofcols = $this->params->get('noofcols', 3);
				$shtitle = $this->params->get('shtitle');
				$title = $this->params->get('title');
				$applicationtitle = $this->params->get('applicationtitle', 1);
				$nationality = $this->params->get('nationality', 1);
				$gender = $this->params->get('gender', 1);
				$available = $this->params->get('available', 1);

				$category = $this->params->get('category', 1);
				$jobtype = $this->params->get('jobtype', 1);
				$salaryrange = $this->params->get('salaryrange', 0);
				$highesteducation = $this->params->get('highesteducation', 0);
				$experience = $this->params->get('experience', 0);
				$posteddate = $this->params->get('posteddate', 1);
				$separator = $this->params->get('separator', 1);
				$colwidth = 100 / $noofcols;
				if (isset($resumes)) { 
				    $contents = '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
					$isodd = 1;
					$count = 1;
					if ($shtitle == 1){
							$contents .=  '<tr class="'.$trclass[$isodd].'">';
						$contents .=  '<td colspan="'.$noofcols.'">';
						$contents .=  '<h2><u>'.$title.'</u></h2>';	
						if ($separator == 1) $contents .= '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';
						$contents .= '</td>';
						$contents .= '</tr>';
					}	
					if ($salaryrange == 1){
						$query = "SELECT * FROM ".$db->nameQuote('#__js_job_config')." WHERE configname = 'currency' ";
						$db->setQuery( $query );
						$cur = $db->loadObject();
						if ($cur) $currency = $cur->configvalue;
						else $currency = 'Rs';
					}	
					foreach ($resumes as $resume) {
					    $isodd = 1 - $isodd;
						if ($count == 1){
							$contents .=  '<tr class="'.$trclass[$isodd].'">';
						}	
						$contents .=  '<td width="'.$colwidth.'%">'
							. '<a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=4&rd='. $resume->id .'&Itemid='.$itemid.'"><u><strong>'
					        . $resume->first_name.' '.$resume->last_name . '</strong></u></a><br />';
						if ($title == 1) $contents .= '<small>Title: '.$resume->application_title.'</small><br />';	
						if ($category == 1) $contents .= '<small>Category: '.$resume->cat_title.'</small><br />';	
						if ($jobtype == 1) $contents .= '<small>Type: '.$resume->jobtypetitle.'</small><br />';	

						if ($highesteducation == 1) $contents .= '<small>Highest Education: '.$resume->educationtitle.'</small><br />';	
						if ($salaryrange == 1)	$contents .= '<small>Salary Range: '.$currency.' '.$resume->rangestart.' - '.$currency.' '.$resume->rangeend .'</small><br />';	
						if ($experience == 1) $contents .= '<small>Experience: '.$resume->total_experience.'</small><br />';	
						if ($available == 1) { if ($resume->iamavailable == 1) $availabletext = 'Yes'; else $availabletext = 'No'; $contents .= '<small>Available: '.$availabletext.'</small><br />';}	
						if ($gender == 1) { if ($resume->gender == 1) $gendertext='Male'; else $gendertext='Female'; $contents .= '<small>Gender: '.$gendertext.'</small><br />';}	
						if ($nationality == 1) $contents .= '<small>Nationality: '.$resume->countryname.'</small><br />';	

						if ($posteddate == 1) $contents .= "<small>Posted: ".strftime('%B %d, %Y',strtotime($resume->create_date))."</small><br /><br />";
						if ($separator == 1) $contents .= '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';

						$contents .= '</td>';
						if ($count == $noofcols){
							$contents .= '</tr>';
							$count = 0;
						}	
						$count = $count + 1;
				    }
					if ($count-1 < $noofcols){
						for ($i = $count; $i <= $noofcols; $i++){
						$contents .= '<td></td>';
						}	
						$contents .= '</tr>';
					}	
					$contents .= '</table>';
				}

               return $contents;
        }
}



?>
