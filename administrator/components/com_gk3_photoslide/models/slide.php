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
 * Slide model.
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class ModelSlide
{
	var $_db;
	
	/**
	 * ModelSlide::__construct()
	 * 
	 * @return null
	 */
	 
	function ModelSlide()
	{
		$this->_db =& JFactory::getDBO();
	}
	
	/**
	 * ModelSlide::getSlides()
	 * 
	 * @param mixed $id
	 * @return DBO
	 */ 
	 
	function getSlides($id)
	{
		// SQL query
		$query = '
		SELECT 
			`s`.`id` AS `id`, 
			`s`.`group_id` AS `gid`, 
			`s`.`name` AS `name`,
			`s`.`filename` AS `filename`,
			`s`.`article` AS `article`,
			`s`.`title` AS `title`,
			`s`.`link_type` AS `link_type`,
			`s`.`link` AS `link`,
			`s`.`content` AS `content`,
			`s`.`published` AS `published`,
			`s`.`access` AS `access`,
			`s`.`order` AS `order`,
			`s`.`image_x` AS `image_x`,
			`s`.`image_y` AS `image_y`,
			`s`.`stretch` AS `stretch` 
		FROM 
			#__gk3_photoslide_slides AS `s` 
		WHERE
			`s`.`group_id` = '.$id.'	
		ORDER BY 
			`s`.`order`
		LIMIT 100;';
		// run SQL query	
		$this->_db->setQuery($query);
		// return results of SQL query
		return $this->_db->loadObjectList();
	}
	
	/**
	 * ModelSlide::addSlide()
	 * 
	 * @return DBO
	 */
	 
	function addSlide($hash)
	{
		$query = '
		SELECT 
			MAX( `order` ) + 1 AS max 
		FROM 
			#__gk3_photoslide_slides
		LIMIT 1;';
		$this->_db->setQuery($query);
		$row = $this->_db->loadRow();
		$max_order = (isset($row[0])) ? $row[0] : 1;
		
		$query = '
		INSERT INTO
			#__gk3_photoslide_slides
		VALUES (
			DEFAULT,
			'.$_POST['gid'].',
			\''.htmlspecialchars($_POST['name']).'\',
			\''.$hash.'\',
			'.$_POST['article'].',
			\''.htmlspecialchars($_POST['title']).'\',
			'.$_POST['link_type'].',
			\''.htmlspecialchars($_POST['link']).'\',
			\''.htmlspecialchars($_POST['content']).'\',
			1,
			'.$_POST['access'].',
			'.$max_order.',
			'.$_POST['image_x'].',
			'.$_POST['image_y'].',
			'.$_POST['stretch'].'
		);';
		//
		$this->_db->setQuery($query);
		//
		return $this->_db->query();
	}
	
	/**
	 * ModelSlide::editSlide()
	 * 
	 * @param mixed $id
	 * @return DBO
	 */
	 
	function editSlide($id)
	{
		$query = '
		UPDATE
			#__gk3_photoslide_slides
		SET 
			name = \''.htmlspecialchars($_POST['name']).'\',
			article = '.$_POST['article'].',
			title = \''.htmlspecialchars($_POST['title']).'\',
			link_type = '.htmlspecialchars($_POST['link_type']).',
			link = \''.htmlspecialchars($_POST['link']).'\',
			content = \''.htmlspecialchars($_POST['content']).'\',
			access = '.$_POST['access'].',
			image_x = '.$_POST['image_x'].',
			image_y = '.$_POST['image_y'].',
			stretch = '.$_POST['stretch'].'
		WHERE 
			id = '.$id.'	
		LIMIT 1;';
		//
		$this->_db->setQuery($query);
		//
		return $this->_db->query();		
	}
	
	/**
	 * ModelSlide::removeSlide()
	 * 
	 * @param mixed $id
	 * @return DBO
	 */
	 
	function removeSlide($id)
	{
		foreach($id as $item)
		{
			$query = '
			SELECT filename FROM
				#__gk3_photoslide_slides
			WHERE
				id = '.$item.'
			;';
			//
			$this->_db->setQuery($query);
			$this->_db->query();
			$slide = $this->_db->loadRow();
			
			// fileToRemove - $slide[0];
			jimport('joomla.filesystem.file');
			// removing original image
			if(JFile::exists(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'original'.DS.$slide[0])){
				JFile::delete(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'original'.DS.$slide[0]);
			}
			// removing medium thumbnail
			if(JFile::exists(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_big'.DS.$slide[0])){
				JFile::delete(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_big'.DS.$slide[0]);
			}
			// removing small thumbnail
			if(JFile::exists(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_small'.DS.$slide[0])){
				JFile::delete(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_small'.DS.$slide[0]);
			}
			// removing slide data
			$query = '
			DELETE FROM
				#__gk3_photoslide_slides
			WHERE
				id = '.$item.'
			;';
			//
			$this->_db->setQuery($query);
			//
			if($this->_db->query() === FALSE) return FALSE;		
		}		
		
		return TRUE;
	}
	
	/**
	 * ModelSlide::publishSlide()
	 * 
	 * @param mixed $id
	 * @return DBO
	 */
	 
	function publishSlide($id)
	{
		foreach($id as $item)
		{	
			$query = '
			UPDATE
				#__gk3_photoslide_slides
			SET 
				published = 1
			WHERE
				id = '.$item.'
			;';
			//
			$this->_db->setQuery($query);
			//
			if($this->_db->query() === FALSE) return FALSE;
		}			
		
		return TRUE;
	}
	
	/**
	 * ModelSlide::unpublishSlide()
	 * 
	 * @param mixed $id
	 * @return DBO
	 */
	 
	function unpublishSlide($id)
	{
		foreach($id as $item)
		{
			$query = '
			UPDATE
				#__gk3_photoslide_slides
			SET 
				published = 0
			WHERE
				id = '.$item.'
			;';
			//
			$this->_db->setQuery($query);
			//
			if($this->_db->query() === FALSE) return FALSE;	
		}
		
		return TRUE;	
	}
	
	/**
	 * ModelSlide::orderSlide()
	 * 
	 * @param mixed $order
	 * @param mixed $gid
	 * @return DBO
	 */
	 
	function orderSlide($order, $gid)
	{
		$query = '
		SELECT 
			* 
		FROM 
			#__gk3_photoslide_slides 
		WHERE 
			group_id = '.$gid.' 
		ORDER BY 
			`order` ASC
		LIMIT 100;';
		$this->_db->setQuery($query);
		// creating array of query results
		$rows = array();
		// storage query results in $rows variable
		foreach($this->_db->loadObjectList() as $row) array_push($rows, $row->id);
		// for each array element mak
		for($j = 0; $j < count($rows); $j++){
			// actualization of tab
			$query = '
			UPDATE 
				#__gk3_photoslide_slides 
			SET 
				`order` = '.$order[$j].' 
			WHERE 
				id = '.$rows[$j].';';
			// make query
			$this->_db->setQuery($query);
			$this->_db->query();
		}	
	}
	
	/**
	 * ModelSlide::accessSlide()
	 * 
	 * @param mixed $level
	 * @param mixed $id
	 * @return DBO
	 */
	 
	function accessSlide($level, $id)
	{
		$query = '
		UPDATE
			#__gk3_photoslide_slides
		SET 
			access = '.$level.'
		WHERE
			id = '.$id.'
		;';
		//
		$this->_db->setQuery($query);
		//
		return $this->_db->query();		
	}
	
	/**
	 * ModelSlide::getSlide()
	 * 
	 * @param mixed $id
	 * @return bool or DBO
	 */ 
	 
	function getSlide($id)
	{
		// SQL query
		$query = '
		SELECT 
			`t`.`id` AS `id`, 
			`t`.`group_id` AS `gid`, 
			`t`.`name` AS `name`,
			`t`.`article` AS `article`,
			`t`.`title` AS `title`,
			`t`.`link_type` AS `link_type`,
			`t`.`link` AS `link`,
			`t`.`content` AS `content`,
			`t`.`published` AS `published`,
			`t`.`access` AS `access`,
			`t`.`order` AS `order`,
			`t`.`image_x` AS `image_x`,
			`t`.`image_y` AS `image_y`,
			`t`.`stretch` AS `stretch`,
			`t`.`filename` AS `filename`
		FROM 
			#__gk3_photoslide_slides AS `t` 
		WHERE
			`t`.`id` = '.$id.'	
		LIMIT 1;';
		// run SQL query	
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
}

/* End of file slide.php */
/* Location: ./models/slide.php */