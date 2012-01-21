<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 10, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	Pplugin/jstopjobs.php
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
class plgContentJSHotJobs extends JPlugin
{
        public function onPrepareContent( &$row, &$params, $page=0 )
        {
                if ( JString::strpos( $row->text, 'jshotjobs' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jshotjobs\s*.*?}/i';
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
                        $load = str_replace( 'jshotjobs', '', $matches[0][$i] );
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
				$curdate = date('Y-m-d H:i:s');
				$noofjobs = $this->params->get('noofjobs');
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

				$query = "SELECT COUNT(apply.jobid) as totalapply, job.id, job.title, job.jobcategory, job.created, cat.cat_title
					, company.id AS companyid, company.name AS companyname, jobtype.title AS jobtypetitle 
					FROM ". $db->nameQuote('#__js_job_jobs')." AS job 
					JOIN ". $db->nameQuote('#__js_job_jobapply')." AS apply ON job.id = apply.jobid 
					JOIN ". $db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id 
					JOIN ". $db->nameQuote('#__js_job_jobtypes')." AS jobtype ON job.jobtype = jobtype.id 
					LEFT JOIN ". $db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
					WHERE job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate) ."
					GROUP BY apply.jobid ORDER BY totalapply DESC LIMIT ".$noofjobs;
				//echo $query;
				$db->setQuery($query);
				$jobs = $db->loadObjectList();

				$contents = '';
				$noofcols = $this->params->get('noofcols');
				//$noofcols = 7;
				$shtitle = $this->params->get('shtitle');
				$title = $this->params->get('title');
				$company = $this->params->get('company');
				$category= $this->params->get('category');
				$jobtype= $this->params->get('jobtype');
				$posteddate= $this->params->get('posteddate');
				$separator= $this->params->get('separator');
				$colwidth = 100 / $noofcols;
				if (isset($jobs)) { 
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
					foreach ($jobs as $job) {
					    $isodd = 1 - $isodd;
						if ($count == 1){
							$contents .=  '<tr class="'.$trclass[$isodd].'">';
						}	
						$contents .=  '<td width="'.$colwidth.'%">'
							. '<a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&fr=lj&vj=2&jobcat=' .$job->jobcategory . '&oi=' . $job->id . '&Itemid='.$itemid.'"><u><strong>'
					        . $job->title . '</strong></u></a><br />';
						if ($company == 1) $contents .=  '<small>Company: <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=2&md=' .$job->companyid . '&jobcat=' .$job->jobcategory . '&Itemid='.$itemid.'">'.$job->companyname.'</a></small><br />';	
						if ($category == 1) $contents .= '<small>Category: '.$job->cat_title.'</small><br />';	
						if ($jobtype == 1) $contents .= '<small>Type: '.$job->jobtypetitle.'</small><br />';	
						if ($posteddate == 1) $contents .= "<small>Posted: ".strftime('%B %d, %Y',strtotime($job->created))."</small><br /><br />";
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
