<?php
/**
 * @copyright	Copyright (C) 2011 LINKATIC - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

class plgAcymailingTagempleo extends JPlugin
{

	var $allFields = array();

	function plgAcymailingTagempleo(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin =& JPluginHelper::getPlugin('acymailing', 'tagempleo');
			$this->params = new JParameter( $plugin->params );
		}
    }
	
	function acymailing_getPluginType() {
	 	$onePlugin = null;
	 	$onePlugin->name = 'Empleo';
	 	$onePlugin->function = 'acymailingtagempleo_show';
	 	$onePlugin->help = 'plugin-tagempleo';
	 	return $onePlugin;
	 }
	
	function acymailingtagempleo_show(){
		$text = '<table class="adminlist" cellpadding="1">';
		$db =& JFactory::getDBO();
		$tableInfos = $db->getTableFields(acymailing::table('js_job_jobs',false));
		$fields = reset($tableInfos);

		foreach($fields as $fieldname => $oneField){
			if(in_array($fieldname,array('params'))) continue;
			$type = '';
			if(strpos(strtolower($oneField),'date') !== false) $type = '|type:date';
			$text .= '<tr style="cursor:pointer" class="row'.$k.'" onclick="setTag(\'{empleo:'.$fieldname.$type.'}\');insertTag();" ><td>'.$fieldname.'</td></tr>';
			$k = 1-$k;
		}
		$text .= '</table>';
		echo $text;
	 }
	 
	 function acymailing_replacetags(&$email){
	 	$this->_replaceAuto($email);
	 	$this->_replaceOne($email);
	 }
	
	function _replaceOne(&$email){

		$match = '#{empleo:(.*)}#Ui';
		$variables = array('body','altbody');
		$found = false;
		foreach($variables as $var){
			if(empty($email->$var)) continue;
			$found = preg_match_all($match,$email->$var,$results[$var]) || $found;
			//we unset the results so that we won't handle it later... it will save some memory and processing
			if(empty($results[$var][0])) unset($results[$var]);
		}

		//If we didn't find anything...
		if(!$found) return;


		//We will need the mailer class as well
		$this->mailerHelper = acymailing::get('helper.mailer');

		$htmlreplace = array();
		$textreplace = array();
		foreach($results as $var => $allresults){
			foreach($allresults[0] as $i => $oneTag){
				//Don't need to process twice a tag we already have!
				if(isset($htmlreplace[$oneTag])) continue;

				$content = $this->_replaceContent($allresults,$i);
				$htmlreplace[$oneTag] = $content;
				$textreplace[$oneTag] = $this->mailerHelper->textVersion($content,true);
			}
		}

		$email->body = str_replace(array_keys($htmlreplace),$htmlreplace,$email->body);
		$email->altbody = str_replace(array_keys($textreplace),$textreplace,$email->altbody);
	 }
	 
	function _replaceContent(&$results,$i){
		//1 : Transform the tag properly...
		
		$arguments = explode('|',$results[1][$i]);
		$tag = null;
		$tag->stitle = $arguments[0];
		$tag->id = $arguments[1];

		//print_r($arguments);
		//2 : Load the content
		if($tag->stitle!=''){
			$result = '';
			$result.= '<a style="text-decoration:none;" href="'.ACYMAILING_LIVE.'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&oi='.$tag->id.'">';
			$result .= $tag->stitle;
			$result.= '</a>';
		}	
		return $result;
	}
	
	function _replaceAuto(&$email){
		$this->acymailing_generateautonews($email);

		if(!empty($this->tags)){
			$email->body = str_replace(array_keys($this->tags),$this->tags,$email->body);
			if(!empty($email->altbody)) $email->altbody = str_replace(array_keys($this->tags),$this->tags,$email->altbody);
		}
	}

	function acymailing_generateautonews(&$email){

		$return = null;
		$return->status = true;
		$return->message = '';

		$time = time();
		//Check if we should generate the autoNewsletter or not...
		$match = '#{autoempleo:(.*)}#Ui';
		$variables = array('body','altbody');
		$found = false;
		foreach($variables as $var){
			if(empty($email->$var)) continue;
			$found = preg_match_all($match,$email->$var,$results[$var]) || $found;
			//we unset the results so that we won't handle it later... it will save some memory and processing
			if(empty($results[$var][0])) unset($results[$var]);
		}

		//If we didn't find anything... so we won't try to stop the generation
		if(!$found) return $return;

		$this->tags = array();
		$db =& JFactory::getDBO();

		foreach($results as $var => $allresults){
			foreach($allresults[0] as $i => $oneTag){
				if(isset($this->tags[$oneTag])) continue;

				$arguments = explode('|',$allresults[1][$i]);
				//The first argument is a list of sections and cats...
				//$allcats = explode('-',$arguments[0]);
				$parameter = null;
				for($i=0;$i<count($arguments);$i++){
					$args = explode(':',$arguments[$i]);
					$arg0 = $args[0];
					if(isset($args[1])){
						$parameter->$arg0 = $args[1];
					}else{
						$parameter->$arg0 = true;
					}
				}
				
				$segundos = mktime(0, 0, 0, date("m"), date("d"), date("Y") );		
				$segundos = $segundos - (7*24*3600); //Restamos 7 días
				$date = date("Y-m-d", $segundos);			
				
						
				if($arguments[2]) $where = "WHERE jobcategory = '".$arguments[2]."' 
				AND created >= '".$date."' AND status = 1";
				else $where = "";
				
				$query = "SELECT DISTINCT `title`, `id`, `jobcategory` FROM `#__js_job_jobs` ".$where." ORDER BY `id` DESC LIMIT 50";
				
				
				$db->setQuery($query);
				$allArticles = $db->loadAssocList();

				/* if(!empty($parameter->min) AND count($allArticles) < $parameter->min){
					//We won't generate the Newsletter
					$return->status = false;
					$return->message = 'Not enough mosets listings for the tag '.$oneTag.' : '.count($allArticles).' / '.$parameter->min;
				} */

				$stringTag = '';
				if(!empty($allArticles)){
					if(file_exists(ACYMAILING_MEDIA.'plugins'.DS.'autoempleo.php')){
						ob_start();
						require(ACYMAILING_MEDIA.'plugins'.DS.'autoempleo.php');
						$stringTag = ob_get_clean();
					}else{
						//we insert the article tag one after the other in a table as they are already sorted
						$stringTag .= '<h2>Últimas ofertas de empleo publicadas en Cultunet</h2>';
						$stringTag .= '<p>Las ofertas de empleo de Cultunet se actualizan todos los días. 
						Algunas ofertas de empleo difundidas en este e-mail pueden no estar vigentes debido al tiempo transcurrido entre su 
						publicación y el envío del mailing.</p>';
						$stringTag .= '<ul type="disc">';
						foreach($allArticles as $oneArticleId){
							$stringTag .= '<li>';
							$args = array();
							$args[] = 'empleo:'.$oneArticleId['title'];
							if(!empty($parameter->id)) $args[] = $oneArticleId['id'];
							if(!empty($parameter->jobcategory)) $args[] = $oneArticleId['jobcategory'];
							$stringTag .= '{'.implode('|',$args).'}';
							$stringTag .= '</li>';
						}
						$stringTag .= '</ul>';
					}
				}

				$this->tags[$oneTag] = $stringTag;
			}
		}
		
		return $return;
	}
	 
}