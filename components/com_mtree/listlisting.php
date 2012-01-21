<?php
/**
 * @version		$Id: listlisting.php 891 2010-06-02 11:16:30Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

class mtListListing {

	var $task=null;
	var $list=null;
	var $subcats=null;
	var $now=null;
	var $limitstart=0;
	var $link_ids=null;
	var $link_ids_results=null;

	function mtListListing( $task ) {
		global $mtconf;
		$this->task = $task;
		$jdate		= JFactory::getDate();
		$this->now	= $jdate->toMySQL();
		$this->list = array(
			'listpopular' => array(
				'title'		=> JText::_( 'Popular listing2' ),
				'header'	=> JText::_( 'Popular listing' ),
				'name'		=> 'popular',
				'template'	=> 'page_popular.tpl.php'
				),
			'listmostrated' => array(
				'title'		=> JText::_( 'Most rated listing2' ),
				'header'	=> JText::_( 'Most rated listing' ),
				'name'		=> 'mostrated',
				'template'	=> 'page_mostRated.tpl.php'
				),
			'listtoprated' => array(
				'title'		=> JText::_( 'Top rated listing2' ),
				'header'	=> JText::_( 'Top rated listing' ),
				'name'		=> 'toprated',
				'template'	=> 'page_topRated.tpl.php'
				),
			'listmostreview' => array(
				'title'		=> JText::_( 'Most reviewed listing2' ),
				'header'	=> JText::_( 'Most reviewed listing' ),
				'name'		=> 'mostreview',
				'template'	=> 'page_mostReviewed.tpl.php'
				),
			'listnew' => array(
				'title'		=> JText::_( 'New listing2' ),
				'header'	=> JText::_( 'New listing' ),
				'name'		=> 'new',
				'template'	=> 'page_new.tpl.php'
				),
			'listupdated' => array(
				'title'		=> JText::_( 'Recently updated listing2' ),
				'header'	=> JText::_( 'Recently updated listing' ),
				'name'		=> 'updated',
				'template'	=> 'page_updated.tpl.php'
				),
			'listfavourite' => array(
				'title'		=> JText::_( 'Most favoured listings2' ),
				'header'	=> JText::_( 'Most favoured listings' ),
				'name'		=> 'favourite',
				'template'	=> 'page_mostFavoured.tpl.php'
				),
			'listfeatured' => array(
				'title'		=> JText::_( 'Featured listing2' ),
				'header'	=> JText::_( 'Featured listing' ),
				'name'		=> 'featured',
				'template'	=> 'page_featured.tpl.php'
				)		
			);
	}

	function setSubcats( $cat_ids ) {
		$this->subcats = $cat_ids;
	}

	function setLimitStart( $limitstart ) {
		if( !is_numeric($limitstart) || $limitstart < 0 ) {
			$this->limitstart = 0;
		} else {
			$this->limitstart = $limitstart;
		}
	}

	/*
	 * This return a precise limitstart value to SQL query to always return the last page's limitstart to prevent
	 * unexpected results.
	 */
	function getLimitStart() {
		global $mtconf;
		
		$limitstart = 0;
		if( ($this->limitstart % $mtconf->get('fe_num_of_'.$this->getName())) != 0 ) {
			$limitstart = floor($this->limitstart/$mtconf->get('fe_num_of_'.$this->getName())) * $mtconf->get('fe_num_of_'.$this->getName());
		} else {
			$limitstart = $this->limitstart;
		}

		if(
			$this->getName() == 'featured' 
			&&
			($limitstart + $mtconf->get('fe_num_of_'.$this->getName())) > $this->getTotalFeatured()
		) {
			$limitstart = floor( $this->getTotalFeatured() / $mtconf->get('fe_num_of_'.$this->getName()) ) * $mtconf->get('fe_num_of_'.$this->getName());
		}
		
		if( 
			$mtconf->get('fe_total_'.$this->getName()) > 0 && $limitstart >= $mtconf->get('fe_total_'.$this->getName())
		) {
			$limitstart = $mtconf->get('fe_total_'.$this->getName()) - $mtconf->get('fe_num_of_'.$this->getName());
		}
		$this->setLimitStart( $limitstart );
		return $limitstart;
	}

	function getImplodedSubcats() {
		if( count($this->subcats) == 1 && $this->subcats[0] == 0 ) {
			return 0;
		} else {
			return implode( ", ", $this->subcats );
		}	
	}

	function getTitle() {
		return $this->list[$this->task]['title'];
	}

	function getHeader() {
		return $this->list[$this->task]['header'];
	}

	function getTemplate() {
		return $this->list[$this->task]['template'];
	}

	function getName() {
		return $this->list[$this->task]['name'];
	}

	function getListNewLinkCount() {
		global $mtconf;

		if ( ($this->limitstart + $mtconf->get('fe_num_of_new')) > $mtconf->get('fe_total_new') ) {
			return $mtconf->get('fe_total_new') - $this->limitstart;
		} else {
			return $mtconf->get('fe_num_of_new');
		}
	}

	function prepareQuery() {
		global $mtconf;
		
		$database	=& JFactory::getDBO();

		$nullDate	= $database->getNullDate();

		switch( $this->task ) {
			case 'listfavourite':
				$database->setQuery(
					"SELECT f.link_id, COUNT(f.fav_id) AS favourites
					  FROM (#__mt_favourites AS f, #__mt_cl AS cl, #__mt_cats AS cat)
					  LEFT JOIN #__mt_links AS l ON l.link_id = f.link_id 
					  WHERE link_published='1' && link_approved='1' 
					  AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= ".$database->Quote($this->now)." ) 
					  AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= ".$database->Quote($this->now)." ) 
					  AND l.link_id = cl.link_id
 					  AND cl.main = 1
					  AND cl.cat_id = cat.cat_id
					  " . ( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '') . "
					  GROUP BY f.link_id 
					  ORDER BY favourites DESC 
					  LIMIT $this->limitstart, " . $mtconf->get('fe_num_of_favourite')
					);
				$this->link_ids_results = $database->loadObjectList('link_id');
				break;
			case 'listmostreview':
				$database->setQuery(
					"SELECT r.link_id, COUNT(r.link_id) AS reviews
					  FROM (#__mt_reviews AS r, #__mt_cl AS cl, #__mt_cats AS cat)
					  LEFT JOIN #__mt_links AS l ON l.link_id = r.link_id 
					  WHERE link_published='1' && link_approved='1' 
					  AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= ".$database->Quote($this->now)." ) 
					  AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= ".$database->Quote($this->now)." ) 
					  AND l.link_id = cl.link_id
 					  AND cl.main = 1
					  AND cl.cat_id = cat.cat_id
					  " . ( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '') . "
					  AND  r.rev_approved = '1'
					  GROUP BY r.link_id 
					  ORDER BY reviews DESC 
					  LIMIT $this->limitstart, " . $mtconf->get('fe_num_of_mostreview')
					);
				$this->link_ids_results = $database->loadObjectList('link_id');
				break;
		}
		if( !empty($this->link_ids_results) ) {
			foreach( $this->link_ids_results AS $result ) {
				$this->link_ids[] = $result->link_id;
			}
		}
	}
	
	function getSQL() {
		global $mtconf;

		$database	=& JFactory::getDBO();
		$nullDate	= $database->getNullDate();

		$sql = '';
		switch( $this->task ) {
			case 'listpopular':
				$sql = "SELECT l.*, u.username, cat.cat_id, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl, #__mt_cats AS cat) "
						. "\n LEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1 "
						. "\n LEFT JOIN #__users AS u ON u.id = l.user_id "
						. "WHERE link_published='1' && link_approved='1' "
						. "\n AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$this->now'  ) "
						. "\n AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$this->now' ) "
						. "\n AND l.link_id = cl.link_id "
						. "\n AND cl.main = 1 "
						. "\n AND cl.cat_id = cat.cat_id "
						. ( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '')
						. "ORDER BY link_hits DESC "
						. "LIMIT " . $this->getLimitStart() . ", " . $mtconf->get('fe_num_of_popular');
				break;
			case 'listmostrated':
				$sql = "SELECT l.*, u.username, cat.cat_id, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl, #__mt_cats AS cat) "
						. "\n LEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1 "
						. "\n LEFT JOIN #__users AS u ON u.id = l.user_id "
						. "WHERE link_published='1' && link_approved='1' "
						. "\n AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$this->now'  ) "
						. "\n AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$this->now' ) "
						. "\n AND l.link_id = cl.link_id "
						. "\n AND cl.main = 1 "
						. "\n AND cl.cat_id = cat.cat_id "
						. ( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '')
						. "ORDER BY link_votes DESC, link_rating DESC " 
						. "LIMIT " . $this->getLimitStart() . ", " . $mtconf->get('fe_num_of_mostrated');
				break;
			case 'listtoprated':
				$sql = "SELECT l.*, u.username, cat.cat_id, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl, #__mt_cats AS cat) "
						. "\n LEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1 "
						. "\n LEFT JOIN #__users AS u ON u.id = l.user_id "
						. "WHERE link_published='1' && link_approved='1' "
						. "\n AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$this->now'  ) "
						. "\n AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$this->now' ) "
						. "\n AND l.link_id = cl.link_id "
						. "\n AND cl.main = 1 "
						. "\n AND cl.cat_id = cat.cat_id "
						. ( ( $mtconf->get('min_votes_for_toprated') >= 1 ) ? "\n AND l.link_votes >= " . $mtconf->get('min_votes_for_toprated') . " " : '' )
						. ( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '')
						. "ORDER BY link_rating DESC, link_votes DESC  " 
						. "LIMIT " . $this->getLimitStart() . ", " . $mtconf->get('fe_num_of_toprated');
				break;
			case 'listmostreview':
				if( !empty($this->link_ids) )
				{
					$sql = "SELECT l.*, u.username, cl.cat_id, COUNT(r.rev_id) AS reviews, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl) "
						.	"\nLEFT JOIN #__mt_reviews AS r ON r.link_id = l.link_id"
						.	"\nLEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1"
						.	"\nLEFT JOIN #__users AS u ON u.id = l.user_id "
						.	"\nWHERE l.link_id IN (" . implode(",", $this->link_ids) . ")"
						.	"\nAND cl.main = '1'"
						.	"\nAND cl.link_id = l.link_id"				
						.	"\nGROUP BY l.link_id"
						.	"\nLIMIT " . $mtconf->get('fe_num_of_mostreview');
				}
				break;
			case 'listnew':
				$sql = "SELECT l.*, u.username, cat.cat_id, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl, #__mt_cats AS cat) "
						. "\n LEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1 "
						. "\n LEFT JOIN #__users AS u ON u.id = l.user_id "
						. "WHERE link_published='1' && link_approved='1' "
						. "\n AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$this->now'  ) "
						. "\n AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$this->now' ) "
						. "\n AND l.link_id = cl.link_id "
						. "\n AND cl.main = 1 "
						. "\n AND cl.cat_id = cat.cat_id "
						. ( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '')
						. "ORDER BY link_created DESC ";
				$sql .= "LIMIT " . $this->getLimitStart() . ", " . $this->getListNewLinkCount();
				break;
			case 'listupdated':
				$sql = "SELECT l.*, u.username, cat.cat_id, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl, #__mt_cats AS cat) "
						. "\n LEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1 "
						. "\n LEFT JOIN #__users AS u ON u.id = l.user_id "
						. "WHERE link_published='1' && link_approved='1' "
						. "\n AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$this->now'  ) "
						. "\n AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$this->now' ) "
						. "\n AND l.link_id = cl.link_id "
						. "\n AND cl.main = 1 "
						. "\n AND cl.cat_id = cat.cat_id "
						. ( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '')
						. "ORDER BY link_modified DESC ";
				$sql .= "LIMIT " . $this->getLimitStart() . ", " . $mtconf->get('fe_num_of_updated');
				break;
			case 'listfavourite':
				if( !empty($this->link_ids) )
				{
					$sql = "SELECT l.*, u.username, cl.cat_id, COUNT(r.rev_id) AS reviews, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl) "
						.	"\nLEFT JOIN #__mt_reviews AS r ON r.link_id = l.link_id"
						.	"\nLEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1"
						.	"\nLEFT JOIN #__users AS u ON u.id = l.user_id "
						.	"\nWHERE l.link_id IN (" . implode(",", $this->link_ids) . ")"
						.	"\nAND cl.main = '1'"
						.	"\nAND cl.link_id = l.link_id"				
						.	"\nGROUP BY l.link_id"
						.	"\nLIMIT " . $mtconf->get('fe_num_of_favourite');
				}
				break;
			case 'listfeatured':
				$sql = "SELECT l.*, u.username, cat.cat_id, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl, #__mt_cats AS cat) "
						. "\n LEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1 "
						. "\n LEFT JOIN #__users AS u ON u.id = l.user_id "
						. "WHERE link_published='1' && link_approved='1' && link_featured='1' "
						. "\n AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$this->now'  ) "
						. "\n AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$this->now' ) "
						. "\n AND l.link_id = cl.link_id "
						. "\n AND cl.main = 1 "
						. "\n AND cl.cat_id = cat.cat_id "
						. ( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '')
						. "ORDER BY link_name ASC " 
						. "LIMIT " . $this->getLimitStart() . ", " . $mtconf->get('fe_num_of_featured');
				break;
		}
		return $sql;
	}
	
	function getListings() {
		$database	=& JFactory::getDBO();
		
		$this->prepareQuery();

		$sql = $this->getSQL();
		if( !empty($sql) )
		{
			$database->setQuery( $sql );
			$links = $database->loadObjectList();
			$this->sortLinks($links);
		} else {
			$links = array();
		}
		
		
		return $links;
	}

	function getPageNav() {
		global $mtconf;

		$database	=& JFactory::getDBO();
		$nullDate	= $database->getNullDate();

		$config_total_listing = $mtconf->get('fe_total_' . substr($this->task,4));

		switch( $this->task ) {

			default:
				# Get the total available listings
				$sql = "SELECT COUNT(*) FROM (#__mt_links AS l, #__mt_cl AS cl) "
					. "WHERE link_published='1' && link_approved='1' "
					. "\n AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$this->now'  ) "
					. "\n AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$this->now' ) "
					. "\n AND l.link_id = cl.link_id "
					. "\n AND cl.main = 1 ";
				switch( $this->task )
				{
					case 'listmostrated':
						$sql .= "\n AND l.link_votes > 0 ";
						break;
					case 'listtoprated':
						$sql .= "\n AND l.link_rating > 0 ";
						$sql .= ( ( $mtconf->get('min_votes_for_toprated') >= 1 ) ? "\n AND l.link_votes >= " . $mtconf->get('min_votes_for_toprated') . " " : '' );
						break;
				}
				$sql .= ( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '');
				$database->setQuery( $sql );
				$total = $database->loadResult();
				
				$config_listing_perpage = $mtconf->get('fe_num_of_' . substr($this->task,4));

				if( $total > $config_total_listing ) {
					$total = $config_total_listing;
				}
				
				if ( ($this->limitstart + $config_listing_perpage) > $config_total_listing ) {
					$link_count = $config_total_listing - $this->limitstart;
				} else {
					$link_count = $config_listing_perpage;
				}
				break;
			
			case 'listmostreview':
				$total = $this->getTotalReviewed();
				if( $total > $config_total_listing ) {
					$total = $config_total_listing;
				}
				$link_count = $mtconf->get('fe_num_of_mostreview');
				break;
			
			case 'listfavourite':
				$total = $this->getTotalFavourited();
				if( $total > $config_total_listing ) {
					$total = $config_total_listing;
				}
				$link_count = $mtconf->get('fe_num_of_favourite');
				break;
				
			case 'listfeatured':
				$total = $this->getTotalFeatured();
				$link_count = $mtconf->get('fe_num_of_featured');
				break;
		}


		# Page Navigation
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $this->getLimitStart(), $link_count);
		return $pageNav;
	}
	
	function getTotalFavourited()
	{
		$database = JFactory::getDBO();
		$nullDate	= $database->getNullDate();
		
		$database->setQuery(
			
			"SELECT COUNT(DISTINCT l.link_id) FROM (#__mt_links AS l, #__mt_cl AS cl, #__mt_cats AS cat, #__mt_favourites AS f) "
					. "WHERE link_published='1' && link_approved='1' "
					. "\n AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$this->now'  ) "
					. "\n AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$this->now' ) "
					. "\n AND l.link_id = cl.link_id "
					. "\n AND l.link_id = f.link_id "
					. "\n AND cl.main = 1 "
					. "\n AND cl.cat_id = cat.cat_id "
					. ( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '')
			);
			return $database->loadResult();
	}
	
	function getTotalReviewed()
	{
		$database = JFactory::getDBO();
		$nullDate	= $database->getNullDate();

		$database->setQuery(
			"SELECT COUNT(DISTINCT l.link_id) FROM (#__mt_links AS l, #__mt_cl AS cl, #__mt_cats AS cat) "
					. "LEFT JOIN #__mt_reviews AS r ON r.link_id = l.link_id "
					. "WHERE l.link_published='1' && l.link_approved='1' && r.rev_approved='1' "
					. "\n AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$this->now'  ) "
					. "\n AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$this->now' ) "
					. "\n AND l.link_id = cl.link_id "
					. "\n AND cl.main = 1 "
					. "\n AND cl.cat_id = cat.cat_id "
					. ( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '')
			);
			return $database->loadResult();
	}
	
	function getTotalFeatured()
	{
		$database = JFactory::getDBO();
		$nullDate	= $database->getNullDate();

		$database->setQuery( "SELECT COUNT(*) FROM (#__mt_links AS l, #__mt_cl AS cl) "
			. "WHERE link_published='1' && link_approved='1' && link_featured='1' "
			. "\n AND ( publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$this->now'  ) "
			. "\n AND ( publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$this->now' ) " 
			. "\n AND l.link_id = cl.link_id "
			. "\n AND cl.main = 1 "
			.	( ($this->getImplodedSubcats()) ? "\n AND cl.cat_id IN (" . $this->getImplodedSubcats() . ") " : '')
			);
		return $database->loadResult();
	}
	
	function sortLinks( &$links ) {
		switch( $this->task ) {
			case 'listfavourite':
				for( $i=0; $i<count($links); $i++ ) {
					$links[$i]->favourites = $this->link_ids_results[$links[$i]->link_id]->favourites;
				}
				usort($links,array($this,'sortfavourites'));
				break;
			case 'listmostreview':
				for( $i=0; $i<count($links); $i++ ) {
					$links[$i]->reviews = $this->link_ids_results[$links[$i]->link_id]->reviews;
				}
				usort($links,array($this,'sortreviews'));
				break;			
			
		}
		
	}
	
	function sortfavourites($val1,$val2) {
		if( $val1->favourites < $val2->favourites ) {
			return 1;
		}
		if( $val1->favourites > $val2->favourites ) {
			return -1;
		}
		if( $val1->favourites == $val2->favourites ) {
			return 0;
		}
	}

	function sortreviews($val1,$val2) {
		if( $val1->reviews < $val2->reviews ) {
			return 1;
		}
		if( $val1->reviews > $val2->reviews ) {
			return -1;
		}
		if( $val1->reviews == $val2->reviews ) {
			return 0;
		}
	}
}
?>