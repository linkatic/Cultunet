<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 15, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	router.php
 ^ 
 * Description: for Joomla SEF
 ^ 
 * History:		NONE
 ^ 
 */
 
 
function JSJobsBuildRoute( &$query )
{
       $segments = array();
	   
       if(isset( $query['c'] )) { $segments[] = $query['c']; unset( $query['c'] );};
       if(isset( $query['view'] )) { $segments[] = $query['view']; unset( $query['view'] ); };
       if(isset( $query['layout'] )) { $segments[] = $query['layout']; unset( $query['layout'] );};
	   // list_jobs
       if(isset( $query['fr'] )) { $segments[] = $query['fr']; unset( $query['fr'] );};
       if(isset( $query['jobcat'] )) { $segments[] = $query['jobcat']; unset( $query['jobcat'] );};
       if(isset( $query['cn'] )) { $segments[] = $query['cn']; unset( $query['cn'] );};
       if(isset( $query['cd'] )) { $segments[] = $query['cd']; unset( $query['cd'] );};
       if(isset( $query['cm'] )) { $segments[] = $query['cm']; unset( $query['cm'] );};
	
	   // my companies
       if(isset( $query['md'] )) { $segments[] = $query['md']; unset( $query['md'] );};
	
	   // view company
       if(isset( $query['vm'] )) { $segments[] = $query['vm']; unset( $query['vm'] );};

	   // form job
       if(isset( $query['bd'] )) { $segments[] = $query['bd']; unset( $query['bd'] );};

	   // view job
       if(isset( $query['oi'] )) { $segments[] = $query['oi']; unset( $query['oi'] );};
       if(isset( $query['vj'] )) { $segments[] = $query['vj']; unset( $query['vj'] );};

	   // view resume search
       if(isset( $query['rs'] )) { $segments[] = $query['rs']; unset( $query['rs'] );};

	   // form resume
       if(isset( $query['rd'] )) { $segments[] = $query['rd']; unset( $query['rd'] );};

	   // view cover letter
       if(isset( $query['vct'] )) { $segments[] = $query['vct']; unset( $query['vct'] );};
       if(isset( $query['cl'] )) { $segments[] = $query['cl']; unset( $query['cl'] );};

	   // view resume search
       if(isset( $query['js'] )) { $segments[] = $query['js']; unset( $query['js'] );};

	   // apply now
       if(isset( $query['bi'] )) { $segments[] = $query['bi']; unset( $query['bi'] );};
       if(isset( $query['aj'] )) { $segments[] = $query['aj']; unset( $query['aj'] );};
	   
	   // view cover letters
       if(isset( $query['vts'] )) { $segments[] = $query['vts']; unset( $query['vts'] );};
       if(isset( $query['clu'] )) { $segments[] = $query['clu']; unset( $query['clu'] );};


       if(isset( $query['sortby'] )) { $segments[] = $query['sortby']; unset( $query['sortby'] );};

       if(isset( $query['task'] )) { $segments[] = $query['task']; unset( $query['task'] );};

	   //  echo '<br> item '.$query['Itemid'];
       if(isset( $query['Itemid'] )) { 
		$_SESSION['JSItemid'] = $query['Itemid'];
	   };
	   
       return $segments;
}

function JSJobsParseRoute( $segments )
{
       $vars = array();
	   $count = count($segments);
//		   echo '<br> count '.$count;
       $menu = &JMenu::getInstance('site');
//       $item = &$menu->getActive();
		$menu	= &JSite::getMenu();

		$item	= &$menu->getActive();
		$layout = $segments[2];

		
			//   echo '<br> layout '.$layout;
		switch($layout)
        {
               case 'jobcat':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = 'jobcat';
                       break;
               case 'list_jobs':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = 'list_jobs';
                        $vars['fr'] = $segments[3];
                       if ($segments[3] == 'cj'){ //company list
							$vars['cd'] = $segments[4];
							if ($count == 6) $vars['cm'] = $segments[5];
							if ($count == 7) {
								$vars['cm'] = $segments[5];
								$vars['sortby'] = $segments[6];
							}	
					   }else{
							$vars['jobcat'] = $segments[4];
							if ($count == 6) $vars['sortby'] = $segments[5];
						}	
                       break;
               case 'controlpanel':
                       $vars['view'] = $segments[1];
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = 'controlpanel';
                       break;
               case 'mycompanies':
                       $vars['view'] = 'employer';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = 'mycompanies';
                       break;
               case 'formcompany':
                       $vars['view'] = 'employer';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = 'formcompany';
					   if ($count == 4) $vars['md'] = $segments[3];
                       break;
               case 'view_company':
                       $vars['view'] = $segments[1];
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 4){
							$vars['md'] = $segments[3];
					   }elseif ($count == 5){
							$vars['md'] = $segments[3];
							$vars['vm'] = $segments[4];
					   }elseif ($count == 6){
						   $vars['jobcat'] = $segments[3];
							$vars['md'] = $segments[4];
							$vars['vm'] = $segments[5];
						}	
                       break;
               case 'myjobs':
                       $vars['view'] = 'employer';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 4) $vars['sortby'] = $segments[3];
                       break;
               case 'formjob':
                       $vars['view'] = 'employer';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 4) $vars['bd'] = $segments[3];
                       break;
               case 'view_job':
                       $vars['view'] = 'employer';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 5){
						   $vars['oi'] = $segments[3];
						   $vars['vj'] = $segments[4];
						}elseif ($count == 7){
						   $vars['fr'] = $segments[3];
						   $vars['jobcat'] = $segments[4];
						   $vars['oi'] = $segments[5];
						   $vars['vj'] = $segments[6];
						}	
                       break;
               case 'alljobsappliedapplications':
                       $vars['view'] = $segments[1];
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 4) $vars['sortby'] = $segments[3];
                       break;
               case 'job_appliedapplications':
                       $vars['view'] = $segments[1];
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   $vars['bd'] = $segments[3];
					   if ($count == 5) $vars['sortby'] = $segments[4];
                       break;
               case 'resumesearch':
                       $vars['view'] = 'employer';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
                       break;
               case 'resume_searchresults':
                       $vars['view'] = 'employer';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 4) $vars['sortby'] = $segments[3];
                       break;
               case 'my_resumesearches':
                       $vars['view'] = 'employer';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
                       break;
               case 'viewresumesearch':
                       $vars['view'] = 'employer';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   $vars['rs'] = $segments[3];
                       break;
               case 'myresumes':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 4) $vars['sortby'] = $segments[3];
                       break;
               case 'formresume':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   $vars['vm'] = $segments[3];
					   if ($count == 5) $vars['rd'] = $segments[4];
                       break;
               case 'view_resume':
					   $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 4) $vars['vm'] = $segments[3];
					   elseif ($count == 5){
							$vars['vm'] = $segments[3];
							$vars['rd'] = $segments[4];
					   }elseif ($count == 6){
							$vars['vm'] = $segments[3];
							$vars['bd'] = $segments[4];
							$vars['rd'] = $segments[5];
						}	
                       break;
               case 'mycoverletters':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
                       break;
               case 'formcoverletter':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 4) $vars['cl'] = $segments[3];
                       break;
               case 'view_coverletter':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   $vars['vct'] = $segments[3];
					   $vars['cl'] = $segments[4];
                       break;
               case 'view_coverletters':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 5){
						   $vars['vts'] = $segments[3];
						   $vars['clu'] = $segments[4];
					   }elseif ($count == 7){
						   $vars['bd'] = $segments[3];
						   $vars['rd'] = $segments[4];
						   $vars['vts'] = $segments[5];
						   $vars['clu'] = $segments[6];
						}   
                       break;
               case 'myappliedjobs':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 4) $vars['sortby'] = $segments[3];
                       break;
               case 'jobsearch':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
                       break;
               case 'job_searchresults':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 4) $vars['sortby'] = $segments[3];
                       break;
               case 'my_jobsearches':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
                       break;
               case 'viewjobsearch':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   $vars['js'] = $segments[3];
                       break;
               case 'job_apply':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
					   if ($count == 6){
						   $vars['jobcat'] = $segments[3];
						   $vars['bi'] = $segments[4];
						   $vars['aj'] = $segments[5];
						}elseif($count == 7){
						   $vars['fr'] = $segments[3];
						   $vars['jobcat'] = $segments[4];
						   $vars['bi'] = $segments[5];
						   $vars['aj'] = $segments[6];
						}	
                       break;
               case 'deletejobsearch':
                       $vars['task'] = 'deletejobsearch';
                       $vars['c'] = 'jsjobs';
                       $vars['js'] = $segments[1];
                       break;
               case 'deleteresumesearch':
                       $vars['task'] = 'deleteresumesearch';
                       $vars['c'] = 'jsjobs';
                       $vars['rs'] = $segments[1];
                       break;
               case 'deletejob':
                       $vars['task'] = 'deletejob';
                       $vars['c'] = 'jsjobs';
                       $vars['bd'] = $segments[1];
                       break;
               case 'deletecompany':
                       $vars['task'] = 'deletecompany';
                       $vars['c'] = 'jsjobs';
                       $vars['md'] = $segments[1];
                       break;
			   case 'deleteresume':
                       $vars['task'] = 'deleteresume';
                       $vars['c'] = 'jsjobs';
                       $vars['rd'] = $segments[1];
                       break;
               case 'deletecoverletter':
                       $vars['task'] = 'deletecoverletter';
                       $vars['c'] = 'jsjobs';
                       $vars['cl'] = $segments[1];
                       break;
               case 'listnewestjobs':
                       $vars['view'] = 'jobseeker';
                       $vars['c'] = 'jsjobs';
                       $vars['layout'] = $layout;
                       break;
       }
       if(isset( $_SESSION['JSItemid'] )) { 
		$vars['Itemid'] = $_SESSION['JSItemid'];
		}
       return $vars;

}

