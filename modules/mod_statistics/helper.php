<?php
/**
 * @category	Modules
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

if(!class_exists('modStatisticsHelper'))
{
	class modStatisticsHelper
	{
		var $db;
		
		function modStatisticsHelper(){
			$this->db =& JFactory::getDBO();
		}
		
		function &getInstance(){
			static $instance;
			if(!$instance){
				$instance = new modStatisticsHelper();
			}
			return $instance;
		}
		
		function getStatisticsData(&$params)
		{
			$stats =& modStatisticsHelper::getInstance();
			
			$stats_arr = array();

			if($params->get('members', 1)){
				$members = $stats->getMemberCount();
				$params->def('t_members', $members);
				array_push($stats_arr, 't_members');
			}
			
			if($params->get('groups', 1)){
				$params->def('t_groups', $stats->getGroupCount());
				array_push($stats_arr, 't_groups');
			}
			
			if($params->get('discussions', 1)){
				$params->def('t_discussions', $stats->geDiscussionCount());
				array_push($stats_arr, 't_discussions');
			}
			
			if($params->get('albums', 1)){
				$params->def('t_albums', $stats->getAlbumsCount());
				array_push($stats_arr, 't_albums');
			}
			
			if($params->get('photos', 1)){
				$params->def('t_photos', $stats->getPhotosCount());
				array_push($stats_arr, 't_photos');
			}
			
			if($params->get('videos', 1)){
				$params->def('t_videos', $stats->getVideosCount());
				array_push($stats_arr, 't_videos');
			}
			
			if($params->get('bulletins', 1)){
				$params->def('t_bulletins', $stats->getBulletinsCount());
				array_push($stats_arr, 't_bulletins');
			}
			
			if($params->get('activities', 1)){
				$params->def('t_activities', $stats->getActivitiesCount());
				array_push($stats_arr, 't_activities');
			}
			
			if($params->get('walls', 1)){
				$params->def('t_walls', $stats->getWallsCount());
				array_push($stats_arr, 't_walls');	
			}
			
			if($params->get('genders', 0)){
				$gender_fieldcode 	= $params->get('genders_fieldcode','FIELD_GENDER');
				$gender_male 		= strtolower($params->get('genders_male_display','Male'));
				$gender_female 		= strtolower($params->get('genders_female_display','Female'));
				$genders 			= $stats->getGendersCount($gender_fieldcode, $gender_male, $gender_female);
				
				if(!empty($genders))
				{
					if(empty($members)){
						$members = $stats->getMemberCount();
					}
					$unspecified = $members - ($genders["male"] + $genders["female"]);
					$params->def('t_gender_males', $genders["male"]);
					$params->def('t_gender_females', $genders["female"]);
					$params->def('t_gender_unspecified', $unspecified);
					
					array_push($stats_arr, 'genders');
				}
			}
			
			return $stats_arr;
		}
		
		function getMemberCount(){
		
			$sql = "SELECT 
							COUNT(".$this->db->nameQuote('id').") AS total
					FROM	
							".$this->db->nameQuote('#__users')."  
					WHERE
							".$this->db->nameQuote('block')." = 0";
							
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObject();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }
		    
		    $total = $row->total;
		    
			return $total;
		    
		}
		
		function getGroupCount(){
		
			$sql = "SELECT 
							COUNT(".$this->db->nameQuote('id').") AS total
					FROM	
							".$this->db->nameQuote('#__community_groups')."
					WHERE
							".$this->db->nameQuote('published')." = 1";
							
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObject();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }
		    
		    $total = $row->total;
		    
			return $total;
		    
		}
		
		function geDiscussionCount(){
		
			$sql = "SELECT 
							COUNT(".$this->db->nameQuote('id').") AS total
					FROM	
							".$this->db->nameQuote('#__community_groups_discuss');
							
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObject();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }
		    
		    $total = $row->total;
		    
			return $total;
		    
		}
		
		function getAlbumsCount(){
		
			$sql = "SELECT 
							COUNT(".$this->db->nameQuote('id').") AS total
					FROM	
							".$this->db->nameQuote('#__community_photos_albums');
							
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObject();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }
		    
		    $total = $row->total;
		    
			return $total;
		}
		
		function getPhotosCount(){
		
			$sql = "SELECT 
							COUNT(".$this->db->nameQuote('id').") AS total
					FROM	
							".$this->db->nameQuote('#__community_photos')."
					WHERE
							".$this->db->nameQuote('published')." = 1";
							
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObject();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }
		    
		    $total = $row->total;
		    
			return $total;
		    
		}
		
		function getVideosCount(){
			$sql = "SELECT 
							COUNT(".$this->db->nameQuote('id').") AS total
					FROM	
							".$this->db->nameQuote('#__community_videos')."
					WHERE
							".$this->db->nameQuote('published')." = 1 AND 
							".$this->db->nameQuote('status')." = ".$this->db->quote('ready');
							
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObject();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }
		    
		    $total = $row->total;
		    
			return $total;		  
		}
		
		function getBulletinsCount(){
		
			$sql = "SELECT 
							COUNT(".$this->db->nameQuote('id').") AS total
					FROM	
							".$this->db->nameQuote('#__community_groups_bulletins')."
					WHERE
							".$this->db->nameQuote('published')." = 1";
							
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObject();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }
		    
		    $total = $row->total;
		    
			return $total;
		    
		}
		
		function getActivitiesCount(){
		
			$sql = "SELECT 
							COUNT(".$this->db->nameQuote('id').") AS total
					FROM	
							".$this->db->nameQuote('#__community_activities');
							
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObject();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }
		    
		    $total = $row->total;
		    
			return $total;		    
		}
		
		function getWallsCount(){
		
			$sql = "SELECT 
							COUNT(".$this->db->nameQuote('id').") AS total
					FROM	
							".$this->db->nameQuote('#__community_wall')."
					WHERE
							".$this->db->nameQuote('published')." = 1 AND
							".$this->db->nameQuote('type')." != ".$this->db->quote('discussions');
							
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObject();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }
		    
		    $total = $row->total;
		    
			return $total;		    
		}
		
		function getGendersCount($gender_fieldcode, $gender_male, $gender_female)
		{
			$sql = "SELECT 
							".$this->db->nameQuote('id')."
					FROM	
							".$this->db->nameQuote('#__community_fields')."
					WHERE
							".$this->db->nameQuote('fieldcode')." = ".$this->db->quote($gender_fieldcode);
			
			$query = $this->db->setQuery($sql);
			$row = $this->db->loadObject();
			if($this->db->getErrorNum()) {
				JError::raiseError( 500, $this->db->stderr());
		    }
		    
		    // the result might return empty records. If thats the case, then
		    // return male and female to zero.
		    if(empty($row))
		    {
		    	$gender = array('female' => 0, 'male' => 0);
		    	return $gender;
		    }
		    
		    $gender_id = $row->id;
		    if(!empty($gender_id)){
				$sql = "SELECT 
								".$this->db->nameQuote('value').", 
								COUNT(a.".$this->db->nameQuote('id').") AS total
						FROM	
								".$this->db->nameQuote('#__community_fields_values')." a,
								".$this->db->nameQuote('#__users')." b
						WHERE
								b.".$this->db->nameQuote('id')." = a.".$this->db->nameQuote('user_id')." AND
								".$this->db->nameQuote('block')." = 0 AND
								".$this->db->nameQuote('field_id')." = ".$this->db->quote($gender_id)."
						GROUP 
								BY ".$this->db->nameQuote('value');
				
				$query = $this->db->setQuery($sql);
				$row = $this->db->loadObjectList();
				if($this->db->getErrorNum()) {
					JError::raiseError( 500, $this->db->stderr());
			    }
			    
			    $gender = array();
			    
			    foreach($row as $data)
				{
			    	$case = JString::strtolower($data->value);
					switch($case)
					{
						case $gender_female:
							$gender['female'] = $data->total;
							if(empty($gender['male'])){
								$gender['male'] = 0;
							}
							break;
						case $gender_male:
							$gender['male'] = $data->total;
							if(empty($gender['female'])){
								$gender['female'] = 0;
							}
							break;
					}
				}
			    
				return $gender;    
			}
		}
	}
}
?>
