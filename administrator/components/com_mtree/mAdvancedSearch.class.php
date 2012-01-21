<?php
/**
 * @version		$Id: mAdvancedSearch.class.php 754 2009-08-06 13:18:50Z CY $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */


defined('_JEXEC') or die('Restricted access');

class mAdvancedSearch {
	
	var $_db = null;
	var $conditions = null;
	var $totalResults = null;
	var $arrayLinkId = null;
	var $cfvCounter = 0;
	
	var $where = null;
	var $join = null;
	var $having = null;
	var $limitToCategory = null;
	var $operator = null;
	
	function mAdvancedSearch( $database ) {
		$this->_db = $database;
	}

	function addCondition( $field, $searchFieldValues ) {
		$where = call_user_func_array(array($field, 'getWhereCondition'),$searchFieldValues);
		$this->where[] = str_replace('cfv#.', 'cfv' . $this->cfvCounter . '.', $where);
		if(!$field->isCore()) {
			$this->join[] = 'LEFT JOIN #__mt_cfvalues AS cfv' . $this->cfvCounter . ' ON l.link_id = cfv' . $this->cfvCounter . '.link_id AND cfv' . $this->cfvCounter . '.cf_id = ' . $field->getId();
			$this->cfvCounter++;
		}
	}
	
	function addRawCondition( $condition ) {
		$this->where[] = $condition;
	}
	
	function addHavingCondition( $condition ) {
		$this->having[] = $condition;
	}
	
	function limitToCategory( $cat_ids ) {
		$this->limitToCategory = $cat_ids;
	}
	
	function useOrOperator() {
		$this->operator = 'OR';
	}
	
	function useAndOperator() {
		$this->operator = 'AND';
	}
	
	function getOperator() {
		if( $this->operator == 'OR' || $this->operator == 'AND' ) {
			return $this->operator;
		} else {
			return 'AND';
		}
	}
	
	function search($published=null,$approved=null) {
		global $mtconf;

		if(count($this->where) > 0 || count($this->having) > 0 || count($this->limitToCategory) > 0) {

			$sql = 'SELECT DISTINCT l.link_id, COUNT(r.rev_id) AS reviews FROM #__mt_links AS l';
			$sql .= "\n LEFT JOIN #__mt_cfvalues AS cfv ON l.link_id = cfv.link_id";
			if( count($this->join) > 0 ) {
				$sql .= "\n ";
				$sql .= implode( "\n ", $this->join );
			}
			$sql .=	"\n LEFT JOIN #__mt_reviews AS r ON r.link_id = l.link_id";
			$sql .= "\n LEFT JOIN #__mt_cl AS cl ON cl.link_id = l.link_id";
			
			if( count($this->where) > 0 || count($this->limitToCategory) > 0) {
				$sql .= "\n WHERE ";
			}
			if( count($this->where) > 0 ) {
				$sql .= '(' . implode( ' ' . $this->getOperator() . ' ', $this->where ) . ')';
			}
			if( $published ) {
				global $mtconf;
				$jdate 		= JFactory::getDate();
				$now 		= $jdate->toMySQL();
				$database 	=& JFactory::getDBO();
				$nullDate	= $database->getNullDate();

				if( count($this->where) > 0 ) {
					$sql .= "\nAND ";
				}
				$sql .= "(publish_up = ".$database->Quote($nullDate)." OR publish_up <= '$now')  AND "
				 	. "(publish_down = ".$database->Quote($nullDate)." OR publish_down >= '$now') AND "
					. "link_published = '1'";
			}
			if( $approved ) {
				$sql .= "\nAND link_approved = '1'";
			}
			if( count($this->limitToCategory) > 0 ) {
				$sql .= "\nAND cl.cat_id IN (" . implode( ',', $this->limitToCategory ) . ")";
			}
			$sql .=	"\nGROUP BY l.link_id";

			if( count($this->having) > 0 ) {
				$sql .= "\nHAVING ";
				$sql .= implode( ' OR ', $this->having );
			}

			$this->_db->setQuery( $sql );
			// echo '<p />' . $sql;
			$this->arrayLinkId = $this->_db->loadResultArray();
			$this->totalResults = count($this->arrayLinkId);
			
			if( $this->_db->getErrorMsg() == '' ) {
				return true;
			} else {
				return false;
			}
			
		} else {
			return true;
		}
	}
	
	function loadResultList( $limitstart=0, $limit=15) {
		global $mtconf;
		
		if( count($this->arrayLinkId) > 0 ) {
			$sql = "SELECT l.*, u.username, cl.cat_id, COUNT(r.rev_id) AS reviews, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl) "
				.	"\nLEFT JOIN #__mt_reviews AS r ON r.link_id = l.link_id"
				.	"\nLEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1"
				.	"\nLEFT JOIN #__users AS u ON u.id = l.user_id "
				.	"\nWHERE l.link_id IN (" . implode(",", $this->arrayLinkId) . ")"
				.	"\nAND cl.main = '1'"
				.	"\nAND cl.link_id = l.link_id"				
				.	"\nGROUP BY l.link_id";
			
			if( $mtconf->get('min_votes_to_show_rating') > 0 && $mtconf->get('first_listing_order1') == 'link_rating' ) {
				$sql .= "\n ORDER BY link_votes >= " . $mtconf->get('min_votes_to_show_rating') . ' DESC, ' . $mtconf->get('first_listing_order1') . ' ' . $mtconf->get('first_listing_order2') . ', ' . $mtconf->get('second_listing_order1') . ' ' . $mtconf->get('second_listing_order2') . ' ';
			} else {
				$sql .= "\n ORDER BY " . $mtconf->get('first_listing_order1') . ' ' . $mtconf->get('first_listing_order2') . ', ' . $mtconf->get('second_listing_order1') . ' ' . $mtconf->get('second_listing_order2') . ' ';
			}

			if( $limitstart >= 0 && $limit > 0 )
			{
				$sql .= "\nLIMIT $limitstart, $limit";
			}
			$this->_db->setQuery( $sql );
			return $this->_db->loadObjectList();
		} else {
			return null;
		}
	}
	
	function getTotal() {
		if( !is_null($this->arrayLinkId) ) {
			return count($this->arrayLinkId);
		} else {
			return 0;
		}
	}
	
}
?>