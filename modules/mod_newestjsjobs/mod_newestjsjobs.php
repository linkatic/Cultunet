<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Sep 30, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/newestjsjobs.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

$noofjobs = $params->get('noofjobs', 7);
$category = $params->get('category', 1);
$company = $params->get('company', 1);
$jobtype = $params->get('jobtype', 1);
$posteddate = $params->get('posteddate', 1);
$theme = $params->get('theme', 1);
$separator = $params->get('separator', 1);

$itemid =  JRequest::getVar('Itemid');
$db =& JFactory::getDBO();

//$theme = 1; //js jobs  theme
if ($theme == 1){ // js jobs theme
	$trclass = array("odd", "even");
	$query = "SELECT * FROM ". $db->nameQuote('#__js_job_config')." WHERE configname = 'theme' ";
	$db->setQuery($query);
	//echo $query;
	if ($config = $db->loadObject()) $css = $config->configvalue; else $css = 'jsjobs01.css'; 
	if ($css == 'templatetheme.css') $trclass = array("sectiontableentry1", "sectiontableentry2");
?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mainframe->getBasePath(); ?>components/com_jsjobs/css/<?php echo $css; ?>" />
<?php
}elseif ($theme == 2){ // template theme
	$trclass = array("sectiontableentry1", "sectiontableentry2");
}else 	$trclass = array("", ""); //no theme

$curdate = date('Y-m-d H:i:s');
	
$query = "SELECT job.id, job.title, job.jobcategory, job.created, cat.cat_title
	, company.id AS companyid, company.name AS companyname, jobtype.title AS jobtypetitle 
	FROM ". $db->nameQuote('#__js_job_jobs')." AS job 
	JOIN ". $db->nameQuote('#__js_job_categories')." AS cat ON job.jobcategory = cat.id 
	JOIN ". $db->nameQuote('#__js_job_shifts')." AS jobtype ON job.shift = jobtype.id 
	LEFT JOIN ". $db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id 
	WHERE job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate) ."
	ORDER BY created DESC LIMIT {$noofjobs}";
//echo $query;
$db->setQuery($query);

if ($jobs = $db->loadObjectList()) { 
	echo '<div id="newest_jobs">';
    echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
	$isodd = 1;
	foreach ($jobs as $job) {
	    $isodd = 1 - $isodd;
		echo '<tr class="'.$trclass[$isodd].'"><td>'
			. '<a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&fr=lj&vj=2&jobcat=' .$job->jobcategory . '&oi=' . $job->id . '&Itemid='.$itemid.'"><u><strong>'
	        . $job->title . '</strong></u></a><br />';
			
		//Obtenmos algunos datos extras de cada oferta de empleo
		$query_extra_data = "SELECT fieldtitle, company, city
		FROM ". $db->nameQuote('#__js_job_userfield_data')." AS fdata 
		INNER JOIN ". $db->nameQuote('#__js_job_userfieldvalues')." AS fvalues ON fvalues.id = fdata.data 
		INNER JOIN ". $db->nameQuote('#__js_job_jobs')." AS job ON job.id = fdata.referenceid 
		WHERE job.id=".$job->id;
		//echo $query_extra_data;
		
		$db->setQuery($query_extra_data);
		if ($extra_data = $db->loadObjectList()) 
		{
			//print_r($extra_data);
			$area_gestion = $extra_data[0]->fieldtitle;
			$area_profesional = $extra_data[1]->fieldtitle;
			$company = $extra_data[1]->company;
			$city = $extra_data[1]->city;
		}
		
		//Obtenemos el convocante si no hay nombre de empresa (para cuando ponen ofertas desde el backoffice)
		$query_extra_data2 = "SELECT data AS convocante
		FROM ". $db->nameQuote('#__js_job_userfield_data')." AS fdata 
		INNER JOIN ". $db->nameQuote('#__js_job_jobs')." AS job ON job.id = fdata.referenceid 
		WHERE fdata.field=9 AND job.id=".$job->id;
		//echo $query_extra_data;
		
		$db->setQuery($query_extra_data2);
		if ($extra_data2 = $db->loadObjectList()) 
		{
			//print_r($extra_data2);
			$convocante = $extra_data2[0]->convocante;
		}	
		
		
		//if($convocante=="") $convocante = $job->companyname;
		
		if ($posteddate == 1) echo  "<small><span class=\"field_title\">".JText::_('JS_POSTED').":</span> ".strftime('%d %B, %Y',strtotime($job->created))."</small><br />";
		if ($convocante) echo '<small><span class="field_title">'.JText::_('JS_COMPANY').':</span> '.$convocante.'</small><br />';	
		if ($category == 1) echo '<small><span class="field_title">'.JText::_('JS_CATEGORY').':</span> '.$job->cat_title.'</small><br />';	
		//echo '<small><span class="field_title">'.JText::_('JS_AREAGESTION').':</span> '.$area_gestion.'</small><br />';
		//echo '<small><span class="field_title">'.JText::_('JS_AREAPROFESIONAL').':</span> '.$area_profesional.'</small><br />';
		if ($jobtype == 1) echo  '<small><span class="field_title">'.JText::_('JS_TYPE').':</span> '.$job->jobtypetitle.'</small><br />';
		//if($city!="") echo '<small><span class="field_title">'.JText::_('JS_CITY').':</span> '.$city.'</small><br />';	
		if ($separator == 1) echo '<br/><hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';
		
		echo '</td></tr>';
    }
	echo '</table>';
	echo '</div>';
}
?>

