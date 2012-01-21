<?php
/**
 * @copyright	Copyright (C) 2011 LINKATIC - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

class plgAcymailingTagconvocatorias extends JPlugin
{

	var $allFields = array();

	function plgAcymailingTagconvocatorias(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin =& JPluginHelper::getPlugin('acymailing', 'Tagconvocatorias');
			$this->params = new JParameter( $plugin->params );
		}
    }
	
	function acymailing_getPluginType() {
	 	$onePlugin = null;
	 	$onePlugin->name = 'Convocatorias';
	 	$onePlugin->function = 'acymailingtagconvocatorias_show';
	 	$onePlugin->help = 'plugin-tagconvocatorias';
	 	return $onePlugin;
	 }
	
	function acymailingtagconvocatorias_show(){
		$text = '<table class="adminlist" cellpadding="1">';
		$db =& JFactory::getDBO();
		$tableInfos = $db->getTableFields(acymailing::table('mt_links',false));
		$fields = reset($tableInfos);

		foreach($fields as $fieldname => $oneField){
			if(in_array($fieldname,array('params'))) continue;
			$type = '';
			if(strpos(strtolower($oneField),'date') !== false) $type = '|type:date';
			$text .= '<tr style="cursor:pointer" class="row'.$k.'" onclick="setTag(\'{convocatorias:'.$fieldname.$type.'}\');insertTag();" ><td>'.$fieldname.'</td></tr>';
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

		$match = '#{convocatorias:(.*)}#Ui';
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
		$tag->link_name = $arguments[0];
		$tag->alias = $arguments[1];

		//print_r($arguments);
		//2 : Load the content
		if($tag->link_name!=''){
			$result = '';
			$result.= '<a style="text-decoration:none;" href="'.ACYMAILING_LIVE.'recursos-culturales/convocatorias/'.$tag->alias.'">';
			$result .= $tag->link_name;
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
		$match = '#{autoconvocatorias:(.*)}#Ui';
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

				
				$query = 'SELECT DISTINCT a.link_name , a.alias FROM `#__mt_links` as a ';
				$query .= 'LEFT JOIN `#__users` as b ON a.user_id = b.id ';
				$query .= 'LEFT JOIN `#__mt_cl` as c ON a.link_id = c.link_id AND main = 1 ';
				$query .= 'LEFT JOIN `#__mt_cats` as d ON c.cat_id = d.cat_id AND d.cat_id = 76 ';
				$query .= 'LEFT JOIN `#__mt_cfvalues` as e ON a.link_id = e.link_id AND e.cf_id = 32 ';
				
				if(isset($arguments[2])) $query .= 'WHERE a.publish_up >= \''.$date.'\' AND a.link_approved = 1 AND e.value= \''.$arguments[2].'\' ORDER BY a.link_created DESC';
				else $query .= 'WHERE a.publish_up >= \''.$date.'\' AND a.link_approved = 1 ORDER BY a.link_created DESC';
				
				$db->setQuery($query);
				$allArticles = $db->loadAssocList();

				/* if(!empty($parameter->min) AND count($allArticles) < $parameter->min){
					//We won't generate the Newsletter
					$return->status = false;
					$return->message = 'Not enough mosets listings for the tag '.$oneTag.' : '.count($allArticles).' / '.$parameter->min;
				} */

				$stringTag = '';
				if(!empty($allArticles)){
					if(file_exists(ACYMAILING_MEDIA.'plugins'.DS.'autoconvocatorias.php')){
						ob_start();
						require(ACYMAILING_MEDIA.'plugins'.DS.'autoconvocatorias.php');
						$stringTag = ob_get_clean();
					}else{
						//we insert the article tag one after the other in a table as they are already sorted
						$stringTag .= '<h2>Últimas convocatorias publicadas en Cultunet</h2>';
						$stringTag .= '<ul type="disc">';
						foreach($allArticles as $oneArticleId){
							$stringTag .= '<li>';
							$args = array();
							$args[] = 'convocatorias:'.$oneArticleId['link_name'];
							if(!empty($parameter->alias)) $args[] = $oneArticleId['alias'];
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