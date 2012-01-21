<?php
/**
 * NoNumber! Elements Helper File: Assignments
 *
 * @package     NoNumber! Elements
 * @version     1.6.0
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright (C) 2010 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* Assignments
* $assignment = no / include / exclude / none
*/
class NoNumberElementsAssignmentsHelper
{
	var $_db = null;
	var $_params = null;
	var $_types = array();

	function NoNumberElementsAssignmentsHelper()
	{
		$this->_db =& JFactory::getDBO();
		
		$this->_types = array(
			'MenuItem',
			'SecsCats',
			'SecsCats2',
			'Categories_K2',
			'Categories_MR',
			'Articles',
			'Components',
			'URL',
			'Browsers',
			'Date',
			'UserGroupLevels',
			'Users',
			'Languages',
			'Templates',
			'PHP'
		);
	}

	function getRequestParams()
	{
		$params->option = JRequest::getCmd( 'option' );
		$params->view = JRequest::getCmd( 'view' );
		$params->task = JRequest::getCmd( 'task' );
		$params->id = JRequest::getInt( 'id' );
		$params->Itemid = JRequest::getInt( 'Itemid' );

		switch ( $params->option ) {
			case 'com_categories':
				$params->option = 'com_content';
				$params->view = 'category';
				break;
			case 'com_sections':
				$params->option = 'com_content';
				$params->view = 'section';
				break;
		}

		if ( !$params->id ) {
			$cid = JRequest::getVar( 'cid', array( 0 ), 'method', 'array' );
			$cid = array( (int) $cid['0'] );
			$params->id = $cid['0'];
		}
		
		return $params;
	}

	function initParams( &$params, $type = '' )
	{
		if ( !isset( $params->assignment ) ) {
			$params->assignment = 'all';
		} else {
			$this->getAssignmentState( $params->assignment );
		}
		
		if ( !isset( $params->selection ) ) {
			$params->selection = array();
		}
		if ( !isset( $params->params ) ) {
			$params->params = null;
		}
		
		switch ( $type ) {
			case 'MenuItem':
				if ( !isset( $params->params->inc_children ) ) {
					$params->params->inc_children = 0;
				}
				if ( !isset( $params->params->inc_noItemid ) ) {
					$params->params->inc_noItemid = 0;
				}
				break;
			case 'SecsCats':
				if ( !isset( $params->params->inc_children ) ) {
					$params->params->inc_children = 1;
				}
				if ( !isset( $params->params->inc_sections ) ) {
					$params->params->inc_sections = 1;
				}
				if ( !isset( $params->params->inc_categories ) ) {
					$params->params->inc_categories = 1;
				}
				if ( !isset( $params->params->inc_articles ) ) {
					$params->params->inc_articles = 1;
				}
				if ( !isset( $params->params->inc_others ) ) {
					$params->params->inc_others = 0;
				}
				break;
			case 'SecsCats2':
				if ( !isset( $params->params->inc_children ) ) {
					$params->params->inc_children = 1;
				}
				break;
			case 'Categories_K2':
			case 'Categories_MR':
				if ( !isset( $params->params->inc_children ) ) {
					$params->params->inc_children = 0;
				}
				if ( !isset( $params->params->inc_categories ) ) {
					$params->params->inc_categories = 1;
				}
				if ( !isset( $params->params->inc_items ) ) {
					$params->params->inc_items = 1;
				}
				break;
			case 'Date':
				if ( !isset( $params->params->publish_up ) ) {
					$params->params->publish_up = 0;
				}
				if ( !isset( $params->params->publish_down ) ) {
					$params->params->publish_down = 0;
				}
				break;

		}
	}

	function passAll( &$params, $match_method = 'and', $article = 0 )
	{
		if ( empty( $params ) ) {
			return 1;
		}
		
		$mainframe =& JFactory::getApplication();
		$this->_params = $this->getRequestParams();

		// if no id is found, check if menuitem exists to get view and id
		if ( $mainframe->isSite() && ( !$this->_params->option || !$this->_params->id ) ) {
			$menu =& JSite::getMenu();
			if ( empty( $this->_params->Itemid ) ) {
				$menuItem =& $menu->getActive();
			} else {
				$menuItem =& $menu->getItem( $this->_params->Itemid );
			}
			if ( !$this->_params->option ) {
				$this->_params->option = ( empty( $menuItem->query['option'] ) ) ? null : $menuItem->query['option'];
			}
			$this->_params->view = ( empty( $menuItem->query['view'] ) ) ? null : $menuItem->query['view'];
			$this->_params->task = ( empty( $menuItem->query['task'] ) ) ? null : $menuItem->query['task'];
			if ( !$this->_params->id ) {
				$this->_params->id = ( empty( $menuItem->query['id'] ) ) ? null : $menuItem->query['id'];
			}
			unset( $menuItem );
		}

		$pass = ( $match_method == 'and' ) ? 1 : 0;
		foreach ( $this->_types as $type ) {
			if ( isset( $params[$type] ) ) {
				$this->initParams( $params[$type], $type );
				$func = 'pass'.$type;
				if ( ( $pass && $match_method == 'and' ) || ( !$pass && $match_method == 'or' ) ) {
					if ( $params[$type]->assignment == 'all' ) {
						$pass = 1;
					} else if ( $params[$type]->assignment == 'none' ) {
						$pass = 0;
					} else {
						$pass = $this->$func( $params[$type]->params, $params[$type]->selection, $params[$type]->assignment, $article );
					}
				}
			}
		}
		return ( $pass ) ? 1 : 0;
	}

	/**
	 * passSimple
	 * @param <string> $value
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passSimple( $values = '', $selection = array(), $assignment = 'all' )
	{
		if ( !is_array( $values ) ) {
			$values = explode( ',', $values );
		}
		if ( !is_array( $selection ) ) {
			if ( !( strpos( $selection, '|' ) === false ) ) {
				$selection = explode( '|', $selection );
			} else {
				$selection = explode( ',', $selection );
			}
		}

		$pass = 0;
		foreach ( $values as $value ) {
			if ( in_array( $value, $selection ) ) {
				$pass = 1;
				break;
			}
		}

		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}
	}

	/**
	 * passMenuItems
	 * @param <object> $params
	 * inc_children
	 * inc_noItemid
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passMenuItem( &$params, $selection = array(), $assignment = 'all' )
	{
		if ( !is_array( $selection ) ) {
			if ( !( strpos( $selection, '|' ) === false ) ) {
				$selection = explode( '|', $selection );
			} else {
				$selection = explode( ',', $selection );
			}
		}
		
		$pass = 0;
		if ( $this->_params->Itemid ) {
			$pass = in_array( $this->_params->Itemid, $selection );
			if ( !$pass && $params->inc_children ) {
				$parentids = $this->getMenuItemParentIds( $this->_params->Itemid );
				foreach ( $parentids as $parent ) {
					if ( in_array( $parent, $selection ) ) {
						$pass = 1;
						break;
					}
				}
				unset( $parentids );
			}
		} else if ( $params->inc_noItemid ) {
			$pass = 1;
		}

		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}

	}

	/**
	 * passSecsCats
	 * @param <object> $params
	 * inc_children
	 * inc_sections
	 * inc_categories
	 * inc_articles
	 * inc_others
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passSecsCats( &$params, $selection = array(), $assignment = 'all', $article = 0 )
	{
		// components that use the com_content secs/cats
		$components = array( 'com_content', 'com_contentsubmit' );
		if ( !in_array( $this->_params->option, $components ) ) {
			return ( $assignment == 'exclude' );
		}

		if ( !is_array( $selection ) ) {
			if ( !( strpos( $selection, '|' ) === false ) ) {
				$selection = explode( '|', $selection );
			} else {
				$selection = explode( ',', $selection );
			}
		}

		if ( empty( $selection ) ) {
			return ( $assignment == 'exclude' );
		}

		$pass = 0;

		$inc = (
				$this->_params->option == 'com_contentsubmit'
			||	( $params->inc_sections && $this->_params->option == 'com_content' && $this->_params->view == 'section' )
			||	( $params->inc_categories && $this->_params->option == 'com_content' && $this->_params->view == 'category' )
			||	( $params->inc_articles && $this->_params->option == 'com_content' && ( $this->_params->view == '' || $this->_params->view == 'article' ) )
			||	( $params->inc_others && !( $this->_params->option == 'com_content' && ( $this->_params->view == 'section' || $this->_params->view == 'category' || $this->_params->view == 'article' ) ) )
		);

		if ( $inc ) {

			$secs = array();
			$cats = array();
			foreach ( $selection as $seccat ) {
				$seccat = explode( '.', $seccat );
				if ( count( $seccat ) > 1 ) {
					// category
					$cats[] = $seccat['1'];
				} else {
					// section
					$secs[] = $seccat['0'];
					if ( $params->inc_children ) {
						$query = 'SELECT id'
							.' FROM #__categories'
							.' WHERE section = '.(int) $seccat['0'];
						$this->_db->setQuery( $query );
						$categories = $this->_db->loadResultArray();
						if ( !is_array( $categories ) ) {
							$categories = array();
						}
						$cats = array_merge( $cats, $categories );
					}
				}
			}

			if( $this->_params->option == 'com_contentsubmit' ) {
				// Content Submit
				$contentsubmit_params = new ContentsubmitModelArticle();
				if ( in_array( $contentsubmit_params->_id, $cats ) ) {
					$pass = 1;
				}
			} else {
				if ( $params->inc_others && !( $this->_params->option == 'com_content' && ( $this->_params->view == 'section' || $this->_params->view == 'category' || $this->_params->view == 'article' ) ) ) {
					if ( $article ) {
						if ( !isset( $article->id ) ) {
							if ( isset( $article->slug ) ) {
								$article->id = (int) $article->slug;
							}
						}
						if ( !isset( $article->catid ) ) {
							if ( isset( $article->catslug ) ) {
								$article->catid = (int) $article->catslug;
							}
						}
						$this->_params->id = $article->id;
						$this->_params->view = 'article';
					}
				}

				switch( $this->_params->view ) {
					case 'section':
						$pass = in_array( $this->_params->id, $secs );
						break;
					case 'category':
						$pass = in_array( $this->_params->id, $cats );
						break;
					default:
						if ( !$article ) {
							$article =& JTable::getInstance( 'content' );
							$article->load( $this->_params->id );
						}
						if ( $article->catid ) {
							$pass = in_array( $article->catid, $cats );
						} else {
							$catid					= JRequest::getInt( 'catid' );
							$filter_sectionid		= JRequest::getInt( 'filter_sectionid' );
							if ( $catid && $catid !== -1 ) {
								$pass = in_array( $catid, $cats );
							} else if ( $filter_sectionid !== '' &&  $filter_sectionid !== -1 ) {
								$pass = in_array( $filter_sectionid, $secs );
							}
						}
						break;
				}
			}
		}

		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}
	}

	/**
	 * passCategories_K2
	 * @param <object> $params
	 * inc_children
	 * inc_categories
	 * inc_items
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passCategories_K2( &$params, $selection = array(), $assignment = 'all', $article = 0 )
	{
		if ( $this->_params->option != 'com_k2' ) {
			return ( $assignment == 'exclude' );
		}

		if ( !is_array( $selection ) ) {
			if ( !( strpos( $selection, '|' ) === false ) ) {
				$selection = explode( '|', $selection );
			} else {
				$selection = explode( ',', $selection );
			}
		}

		$pass = (
				( $params->inc_categories && $this->_params->view == 'itemlist' && $this->_params->task == 'category' )
			||	( $params->inc_items && $this->_params->view == 'item' )
		);

		if ( !$pass ) {
			return ( $assignment == 'exclude' );
		}

		if ( $article && isset( $article->catid ) ) {
			$cats = $article->catid;
		} else {
			switch ( $this->_params->view ) {
				case 'itemlist':
					$cats = $this->_params->id;
					break;
				case 'item':
				default:
					$query = 'SELECT catid'
						.' FROM #__k2_items'
						.' WHERE id = '.(int) $this->_params->id
						.' LIMIT 1'
						;
					$this->_db->setQuery( $query );
					$cats = $this->_db->loadResult();
					break;
			}
		}

		if ( !is_array( $cats ) ) {
			$cats = explode( ',', $cats );
		}

		if ( $params->inc_children && !empty( $cats ) ) {
			foreach ( $cats as $cat ) {
				$cats = array_merge( $cats, $this->getK2CatParentIds( $cat ) );
			}
		}

		return $this->passSimple( $cats, $selection, $assignment );
	}


	/**
	 * passCategories_MR
	 * @param <object> $params
	 * inc_children
	 * inc_categories
	 * inc_items
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passCategories_MR( &$params, $selection = array(), $assignment = 'all', $article = 0 )
	{
		if ( $this->_params->option != 'com_resource' ) {
			return ( $assignment == 'exclude' );
		}

		if ( !is_array( $selection ) ) {
			if ( !( strpos( $selection, '|' ) === false ) ) {
				$selection = explode( '|', $selection );
			} else {
				$selection = explode( ',', $selection );
			}
		}


		$id = JRequest::getCmd( 'category_id' );
		$pass = (
				( $params->inc_categories && $this->_params->view == 'list' )
			||	( $params->inc_items && $this->_params->view == 'article' )
		);

		if ( !$pass ) {
			return ( $assignment == 'exclude' );
		}

		if ( $article && isset( $article->catid ) ) {
			$cats = $article->catid;
		} else {
			$id = JRequest::getCmd( 'category_id' );
			switch ( $this->_params->view ) {
				case 'list':
					$cats = JRequest::getCmd( 'category_id' );
					if ( !$cats ) {
						$cats = JRequest::getCmd( 'section_id' );
					}
					break;
				case 'item':
				default:
					$id = JRequest::getCmd( 'article' );
					if ( $id ) {
						$query = 'SELECT catid'
							.' FROM #__js_res_record_category'
							.' WHERE record_id = '.(int) $id
							;
						$this->_db->setQuery( $query );
						$cats = $this->_db->loadResultArray();
					} else {
						$cats = array( 0 );
					}
					break;
			}
		}

		if ( !is_array( $cats ) ) {
			$cats = explode( ',', $cats );
		}

		if ( $params->inc_children && !empty( $cats ) ) {
			foreach ( $cats as $cat ) {
				$cats = array_merge( $cats, $this->getMRCatParentIds( $cat ) );
			}
		}

		return $this->passSimple( $cats, $selection, $assignment );
	}

	/**
	 * passArticles
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passArticles( &$params, $selection = array(), $assignment = 'all' )
	{
		if ( $this->_params->option != 'com_content' && $this->_params->view != 'article' && !$this->_params->id ) {
			return ( $assignment == 'exclude' );
		}

		if ( !is_array( $selection ) ) {
			if ( !( strpos( $selection, '|' ) === false ) ) {
				$selection = explode( '|', $selection );
			} else {
				$selection = explode( ',', $selection );
			}
		}
		
		if ( !count( $selection ) ) {
			return ( $assignment == 'exclude' );
		}

		if ( in_array( $this->_params->id, $selection ) ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}
	}

	/**
	 * passComponents
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passComponents( &$params, $selection = array(), $assignment = 'all' )
	{
		return $this->passSimple( $this->_params->option, $selection, $assignment );
	}

	/**
	 * passURL
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passURL( &$params, $selection = array(), $assignment = 'all' )
	{
		$url = JFactory::getURI();
		$url = $url->_uri;

		if ( !is_array( $selection ) ) {
			$selection = explode( "\n", $selection );
		}

		$pass = 0;
		foreach ( $selection as $url_part ) {
			if ( $url_part !== '' ) {
				$url_part = str_replace( '&amp;', '(&amp;|&)', $url_part );
				if (	@preg_match( '#'.$url_part.'#si', $url )
					||	@preg_match( '#'.$url_part.'#si', html_entity_decode( $url ) )
				) {
					$pass = 1;
					break;
				}
			}
		}

		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}
	}

	/**
	 * passBrowsers
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passBrowsers( &$params, $selection = array(), $assignment = 'all' )
	{
		$pass = 0;

		if ( !is_array( $selection ) ) {
			if ( !( strpos( $selection, '|' ) === false ) ) {
				$selection = explode( '|', $selection );
			} else {
				$selection = explode( ',', $selection );
			}
		}

		foreach ( $selection as $sel ) {
			if ( !( strpos( $_SERVER['HTTP_USER_AGENT'], $sel ) === false ) ) {
				$pass = 1;
				break;
			}
		}
		
		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}

	}
	
	/**
	 * passDate
	 * @param <object> $params
	 * publish_up
	 * publish_down
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passDate( &$params, $selection = array(), $assignment = 'all' )
	{
		if ( $params->publish_up || $params->publish_down ) {
			$now =& JFactory::getDate();
			$now = $now->toFormat();
			if ( $params->publish_up ) {
				$publish_up =& JFactory::getDate( $params->publish_up );
				$publish_up = $publish_up->toFormat();
				if ( $publish_up > $now ) {
					// outside date range
					return ( $assignment == 'exclude' );
				}
			}
			if ( $params->publish_down ) {
				$publish_down =& JFactory::getDate( $params->publish_down );
				$publish_down = $publish_down->toFormat();
				if ( $publish_down < $now ) {
					// outside date range
					return ( $assignment == 'exclude' );
				}
			}
		}
		// no date range set
		return ( $assignment == 'include' );
	}

	/**
	 * passUserGroupLevels
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passUserGroupLevels( &$params, $selection = array(), $assignment = 'all' )
	{
		$user =& JFactory::getUser();

		if ( !is_array( $selection ) ) {
			if ( !( strpos( $selection, '|' ) === false ) ) {
				$selection = explode( '|', $selection );
			} else {
				$selection = explode( ',', $selection );
			}
		}
		if ( in_array( 29, $selection ) ) {
			$selection[] = 18;
			$selection[] = 19;
			$selection[] = 20;
			$selection[] = 21;
		}
		if ( in_array( 30, $selection ) ) {
			$selection[] = 23;
			$selection[] = 24;
			$selection[] = 25;
		}

		return $this->passSimple( $user->get( 'gid' ), $selection, $assignment );
	}

	/**
	 * passUsers
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passUsers( &$params, $selection = array(), $assignment = 'all' )
	{
		$user =& JFactory::getUser();

		return $this->passSimple( $user->get( 'id' ), $selection, $assignment );
	}

	/**
	 * passLanguages
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passLanguages( &$params, $selection = array(), $assignment = 'all' )
	{
		$lang = JFactory::getLanguage();
		$lang = array( $lang->_lang, strtolower( $lang->_lang ) );

		return $this->passSimple( $lang, $selection, $assignment );
	}

	/**
	 * passTemplates
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passTemplates( &$params, $selection = array(), $assignment = 'all' )
	{
		$mainframe =& JFactory::getApplication();
		$template =& $mainframe->getTemplate();

		return $this->passSimple( $template, $selection, $assignment );
	}

	/**
	 * passPHP
	 * @param <object> $params
	 * @param <array> $selection
	 * @param <string> $assignment
	 * @return <bool>
	 */
	function passPHP( &$params, $selection = array(), $assignment = 'all' )
	{
		if ( !is_array( $selection ) ) {
			$selection = array( $selection );
		}

		$pass = 0;
		foreach ( $selection as $php ) {
			// replace \n with newline and other fix stuff
			$php = str_replace( '\|', '|', $php );
			$php = preg_replace( '#(?<!\\\)\\\n#', "\n", $php );
			$php = str_replace( '[:REGEX_ENTER:]', '\n', $php );

			if ( trim( $php ) == '' ) {
				$pass = 1;
				break;
			}
			
			$val = '$temp_PHP_Val = create_function( \'\', $php.\';\' );';
			$val .= ' $pass = ( $temp_PHP_Val() ) ? 1 : 0; unset( $temp_PHP_Val );';
			@eval( $val );
			
			if ( $pass ) {
				break;
			}
		}

		if ( $pass ) {
			return ( $assignment == 'include' );
		} else {
			return ( $assignment == 'exclude' );
		}
	}

	/**
	 * getAssignmentState
	 * @param <string> $assignment
	 */
	function getAssignmentState( &$assignment )
	{
		switch ( $assignment ) {
			case 1:
			case 'include':
				$assignment = 'include';
				break;
			case 2:
			case 'exclude':
				$assignment = 'exclude';
				break;
			case 3:
			case -1:
			case 'none':
				$assignment = 'none';
				break;
			default:
				$assignment = 'all';
				break;
		}
	}

	function getMenuItemParentIds( $id = 0 )
	{
		return $this->getParentIds( $id, 'menu' );
	}

	function getK2CatParentIds( $id = 0 )
	{
		return $this->getParentIds( $id, 'k2_categories' );
	}

	function getMRCatParentIds( $id = 0 )
	{
		return $this->getParentIds( $id, 'js_res_category' );
	}

	function getParentIds( $id = 0, $table = 'menu' )
	{
		$parent_ids = array();

		if ( !$id ) {
			return $parent_ids;
		}

		while ( $id ) {
			$query = 'SELECT parent'
				.' FROM #__'.$table
				.' WHERE id = '. (int) $id
				.' LIMIT 1';
			$this->_db->setQuery( $query );
			$id = $this->_db->loadResult();
			if ( $id ) {
				$parent_ids[] = $id;
			}
		}
		return $parent_ids;
	}
}