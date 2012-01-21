<?php

/**
 * 
 * @version		3.0.0
 * @package		Joomla
 * @subpackage	Photoslide GK3
 * @copyright	Copyright (C) 2008 - 2009 GavickPro. All rights reserved.
 * @license		GNU/GPL
 * 
 * ==========================================================================
 * 
 * Group model.
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class ModelGroup
{	
	var $_db;
	
	/**
	 * ModelGroup::__construct()
	 * 
	 * @return null
	 */
	 
	function ModelGroup()
	{
		$this->_db =& JFactory::getDBO();
	}
	
	/**
	 * 
	 * ModelGroup::getGroups()
	 * 
	 * @return DBO
	 * 
	 */
	 
	function getGroups()
	{
		// SQL query
		$query = '
		SELECT 
			`g`.`id` AS `id`, 
			`g`.`name` AS `name`, 
			`g`.`desc` AS `desc`,
			`g`.`type` AS `type`,
			`g`.`thumb_x` AS `thumb_x`,
			`g`.`thumb_y` AS `thumb_y`,
			`g`.`image_x` AS `image_x`,
			`g`.`image_y` AS `image_y`,
			`g`.`background` AS `background`,
			`g`.`default_quality` AS `default_quality`,
			`g`.`default_image` AS `default_image`,
			COUNT( DISTINCT `s`.`id` ) AS `amount` 
		FROM 
			#__gk3_photoslide_groups AS `g` 
		LEFT JOIN 
			#__gk3_photoslide_slides AS `s` 
			ON 
			`s`.`group_id` = `g`.`id`  
		GROUP BY 
			`g`.`id`
		LIMIT 100;';
		// run SQL query	
		$this->_db->setQuery($query);
		// return results of SQL query
		return $this->_db->loadObjectList();
	}
	
	/**
	 * 
	 * ModelGroup::addGroup()
	 * 
	 * @return DBO
	 * 
	 */
	 
	function addGroup()
	{
		$query = '
		INSERT INTO 
			#__gk3_photoslide_groups
		VALUES(
			DEFAULT,
			\''.htmlspecialchars($_POST['name']).'\',
			\''.htmlspecialchars($_POST['desc']).'\',
			\''.htmlspecialchars($_POST['type']).'\',
			'.htmlspecialchars($_POST['thumb_x']).',
			'.htmlspecialchars($_POST['thumb_y']).',
			'.htmlspecialchars($_POST['image_x']).',
			'.htmlspecialchars($_POST['image_y']).',
			\''.htmlspecialchars($_POST['background']).'\',
			'.htmlspecialchars($_POST['default_quality']).',
			'.htmlspecialchars($_POST['default_image']).'
		);';
		//
		$this->_db->setQuery($query);
		//
		return $this->_db->query();
	}
	
	/**
	 * ModelGroup::getGroupData()
	 * 
	 * @param mixed $id
	 * @return bool or DBO
	 */
	 
	function getGroupData($id)
	{
		$query = "
			SELECT 
				`g`.`id` AS `id`, 
				`g`.`name` AS `name`, 
				`g`.`desc` AS `desc`,
				`g`.`type` AS `type`,
				`g`.`thumb_x` AS `thumb_x`,
				`g`.`thumb_y` AS `thumb_y`,
				`g`.`image_x` AS `image_x`,
				`g`.`image_y` AS `image_y`,
				`g`.`background` AS `background`,
				`g`.`default_quality` AS `default_quality`,
				`g`.`default_image` AS `default_image`
			FROM 
				#__gk3_photoslide_groups AS `g`
			WHERE 
				`g`.`id` = ".$id."
			LIMIT 1;";
				
		$this->_db->setQuery($query);
		$this->_db->query();
		//	
		if($this->_db->getNumRows() > 0)
		{
			$row = $this->_db->loadRow();
			return $row;			
		}
		else
		{
			return FALSE;
		}		
	}
	
	/**
	 * ModelGroup::editGroup()
	 * 
	 * @return DBO
	 */
	 
	function editGroup()
	{
		$query = '
		SELECT
			`thumb_x`,
			`thumb_y`,
			`image_x`,
			`image_y`,
			`background`,
			`default_quality`
		FROM
			#__gk3_photoslide_groups
		WHERE
			`id` = '.$_POST['id'].'
		LIMIT 1		
		;';
		//
		$this->_db->setQuery($query);
		$this->_db->query();
		$row = $this->_db->loadRow();
		//
		$thumb_x = $row[0];
		$thumb_y = $row[1];
		$image_x = $row[2];
		$image_y = $row[3];
		$background = $row[4];
		$default_quality = $row[5];
		//
		if(
			$thumb_x != $_POST['thumb_x'] ||
			$thumb_y != $_POST['thumb_y'] ||
			$image_x != $_POST['image_x'] ||
			$image_y != $_POST['image_y'] ||
			$background != $_POST['background'] ||
			$default_quality != $_POST['default_quality']
		){
			//
			require_once(JPATH_COMPONENT.DS.'classes'.DS.'class.image.php');
			$img = new Image();
			jimport('joomla.filesystem.file');
			//
			$query = '
			SELECT 
				`filename`,
				`stretch`,
				`image_x`,
				`image_y` 
			FROM
				#__gk3_photoslide_slides
			WHERE
				`group_id` = '.$_POST['id'].'
			';				
			//
			$this->_db->setQuery($query);
			foreach($this->_db->loadObjectList() as $slide){
				//
				if(JFile::exists(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_big'.DS.$slide->filename)){
					JFile::delete(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_big'.DS.$slide->filename);
					$img->createThumbnail(
						JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'original'.DS.$slide->filename, 
						$slide->filename,
						($slide->image_x == 0) ? $_POST['image_x'] : $slide->image_x,
						($slide->image_y == 0) ? $_POST['image_y'] : $slide->image_y,
						's_big',
						(bool) $slide->stretch,
						$_POST['background'],
						JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS, 
						$_POST['default_quality']);	
				}
				//
				if(JFile::exists(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_small'.DS.$slide->filename)){
					JFile::delete(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_small'.DS.$slide->filename);
					$img->createThumbnail(
						JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'original'.DS.$slide->filename, 
						$slide->filename,
						$_POST['thumb_x'],
						$_POST['thumb_y'],
						's_small',
						(bool) $slide->stretch,
						$_POST['background'],
						JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS, 
						$_POST['default_quality']);	
				}
			}
		}
		//
		$query = '
		UPDATE
			#__gk3_photoslide_groups
		SET
			`name` = \''.htmlspecialchars($_POST['name']).'\',
			`desc` = \''.htmlspecialchars($_POST['desc']).'\',
			`type` = \''.htmlspecialchars($_POST['type']).'\',
			`thumb_x` = '.htmlspecialchars($_POST['thumb_x']).',
			`thumb_y` = '.htmlspecialchars($_POST['thumb_y']).',
			`image_x` = '.htmlspecialchars($_POST['image_x']).',
			`image_y` = '.htmlspecialchars($_POST['image_y']).',
			`background` = \''.htmlspecialchars($_POST['background']).'\',
			`default_quality` = '.htmlspecialchars($_POST['default_quality']).',
			`default_image` = '.htmlspecialchars($_POST['default_image']).'
		WHERE
			`id` = '.$_POST['id'].'
		;';
		//
		$this->_db->setQuery($query);
		//
		return $this->_db->query();		
	}
	
	/**
	 * ModelGroup::removeGroup()
	 * 
	 * @param mixed $id
	 * @return bool
	 */
	 
	function removeGroup($id)
	{
		foreach($id as $item)
		{
			$query = '
			DELETE FROM
				#__gk3_photoslide_groups
			WHERE
				`id` = '.$item.'
			;';
			//
			$this->_db->setQuery($query);
			//
			if($this->_db->query())
			{
				require_once(JPATH_COMPONENT.DS.'models'.DS.'slide.php');
				$slide_model = new ModelSlide();
				
				$query = 'SELECT id FROM #__gk3_photoslide_slides WHERE group_id = '.$item.';';
				$this->_db->setQuery($query);
				
				foreach($this->_db->loadObjectList() as $slide){
					$slide_model->removeSlide(array($slide->id));
				} 
				
				return TRUE;
			}	
			else
			{
				return FALSE;
			}			
		}
		
		return TRUE;
	}
	
	/**
	 * ModelGroup::groupType()
	 * 
	 * @param mixed $id
	 * @return DBO
	 */
	 
	function groupType($id)
	{
		$query = "
			SELECT 
				`g`.`type` AS `type`
			FROM 
				#__gk3_photoslide_groups AS `g`
			WHERE 
				`g`.`id` = ".$id."
			LIMIT 1;";	
		// 		
		$this->_db->setQuery($query);
		$this->_db->query();
		//	
		if($this->_db->getNumRows() > 0)
		{
			$row = $this->_db->loadRow();
			return $row[0];			
		}
		else
		{
			return FALSE;
		}		
	}
}

/* End of file group.php */
/* Location: ./models/group.php */