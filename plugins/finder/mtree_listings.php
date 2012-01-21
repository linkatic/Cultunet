<?php
/**
 * @version		$Id: mtree_listings.php 907 2010-07-01 09:38:05Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2010 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('JPATH_BASE') or die;

jimport('joomla.filesystem.file');

// Continue only when Finder is installed.
if ( !JFile::exists(JPATH_ADMINISTRATOR.'/components/com_finder/helpers/indexer/adapter.php') ) {
	return false;
}

include( JPATH_ROOT.DS.'components'.DS.'com_mtree'.DS.'init.php');

// Load the base adapter.
require_once JPATH_ADMINISTRATOR.'/components/com_finder/helpers/indexer/adapter.php';

// Load the language files for the adapter.
$lang = JFactory::getLanguage();
$lang->load('plg_finder_mtree_listings');

/**
 * Finder adapter for Moses Tree Listings.
 */
class plgFinderMTree_Listings extends FinderIndexerAdapter
{
	/**
	 * @var		string		The plugin identifier.
	 */
	protected $_context = 'MTree_listings';

	/**
	 * @var		string		The sublayout to use when rendering the results.
	 */
	protected $_layout = 'listing';

	/**
	 * @var		string		The type of content that the adapter indexes.
	 */
	protected $_type_title = 'Listing';

	/**
	 * Method to update the link information for items that have been changed
	 * from outside the edit screen. This is fired when the item is published,
	 * approved or unpublished.
	 *
	 * @param	array		An array of item ids.
	 * @param	string		The property that is being changed.
	 * @param	integer		The new value of that property.
	 * @return	boolean		True on success.
	 * @throws	Exception on database error.
	 */
	public function onChangeMTreeListing($ids, $property, $value)
	{
		if ($property === 'state')
		{
			foreach ($ids as $id)
			{
				$sql = clone($this->_getStateQuery());
				$sql->where('l.link_id = '.(int)$id);

				// Get the published states.
				$this->_db->setQuery($sql);
				$item = $this->_db->loadObject();

				// Translate the state.
				$value = $this->_translateState($value, $item->cat_published);

				// Update the item.
				$this->_change($id, $property, $value);
			}
		}

		return true;
	}

	/**
	 * Method to update the item link information when the item category is
	 * changed. This is fired when the item category is published or unpublished.
	 *
	 * @param	array		An array of item ids.
	 * @param	string		The property that is being changed.
	 * @param	integer		The new value of that property.
	 * @return	boolean		True on success.
	 * @throws	Exception on database error.
	 */
	public function onChangeMTreeCategory($ids, $property, $value)
	{
		// Check if we are changing the category state.
		if ($property === 'published')
		{
			// The listing published state is tied to the category published
			// states so we need to look up category's published states before we
			// change anything.
			foreach ($ids as $id)
			{
				$sql = clone($this->_getStateQuery());
				$sql->where('c.cat_id = '.(int)$id);

				// Get the published states.
				$this->_db->setQuery($sql);
				$items = $this->_db->loadObjectList();

				// Adjust the state for each item within the category.
				foreach ($items as $item)
				{
					// Translate the state.
					$value = $this->_translateState($item->link_published, $item->cat_published);

					// Update the item.
					$this->_change($item->link_id, 'state', $value);
				}
			}
		}
		return true;
	}

	/**
	 * Method to remove the link information for listings that have been deleted.
	 *
	 * @param	array		An array of listing ids.
	 * @return	boolean		True on success.
	 * @throws	Exception on database error.
	 */
	public function onDeleteMTreeListing($ids)
	{
		// Remove the listings.
		return $this->_remove($ids);
	}

	/**
	 * Method to reindex the link information for an item that has been saved.
	 *
	 * @param	integer		The id of the item.
	 * @return	boolean		True on success.
	 * @throws	Exception on database error.
	 */
	public function onSaveMTreeListing($id)
	{
		// Run the setup method.
		$this->_setup();

		// Get the item.
		$item = $this->_getItem($id);

		// Index the item.
		$this->_index($item);

		return true;
	}
	
	/**
	 * Method to index an item. The item must be a FinderIndexerResult object.
	 *
	 * @param	object		The item to index as an FinderIndexerResult object.
	 * @throws	Exception on database error.
	 */
	protected function _index(FinderIndexerResult $item)
	{
		// Initialize the item parameters.
		$item->params	= new JParameter($item->params);
		$item->metadata	= new JParameter($item->metadata);

		// Trigger the onPrepareContent event.
		$item->summary	= FinderIndexerHelper::prepareContent($item->summary, $item->params);
		// $item->body		= FinderIndexerHelper::prepareContent($item->link_desc, $item->params);

		// Use listing name for title
		$item->title = $item->link_name;

		// Build the necessary route and path information.
		$item->url		= $this->_getURL($item->link_id);
		$item->route	= $this->_getURL($item->link_id) . $this->_getItemid('com_mtree');
		$item->path		= FinderIndexerHelper::getContentPath($item->route);

		// Add the meta-data processing instructions.
		$simple_searchable_cf_ids = $this->_getSimplSearchableCustomFieldIDs();
		if( !empty($simple_searchable_cf_ids) )
		{
			foreach( $simple_searchable_cf_ids AS $cf_id )
			{
				$item->addInstruction(FinderIndexer::META_CONTEXT, 'cfvalue'.$cf_id);
			}
		}

		// Deals with simple searchable core fields
		$sql = new JDatabaseQuery();
		$sql->select('substring(cf.field_type,5) AS customfield');
		$sql->from('#__mt_customfields AS cf');
		$sql->where('published = 1 && simple_search = 1 && iscore = 1');
		$this->_db->setQuery($sql);
		$simple_searchable_core_custom_fields = $this->_db->loadResultArray();

		if( !empty($simple_searchable_core_custom_fields) )
		{
			foreach( $simple_searchable_core_custom_fields AS $custom_field )
			{
				if( !in_array($custom_field,array('name','desc')) )
				{
					$item->addInstruction(FinderIndexer::META_CONTEXT, $custom_field);
				}
			}
		}

		// Translate the state. Listings should only be published if the category is published.
		$item->state = $this->_translateState($item->link_published, $item->cat_published);

		// Set the language.
		$item->language	= $item->params->get('language', FinderIndexerHelper::getDefaultLanguage());

		// Add the type taxonomy data.
		$item->addTaxonomy('Type', 'Listing');

		// Add additional taxonomy for custom fields containing elements
		$sql = new JDatabaseQuery();
		$sql->select('cf.cf_id, cf.field_elements');
		$sql->select('CASE WHEN CHAR_LENGTH(cf.search_caption) THEN cf.search_caption ELSE cf.caption END as caption');
		$sql->from('#__mt_customfields as cf');
		$sql->where('cf.published = 1 AND cf.simple_search = 1 AND field_elements !=\'\'');
		$this->_db->setQuery($sql);
		$taxonomies = $this->_db->loadObjectList();

		if( !empty($taxonomies) )
		{
			foreach( $taxonomies AS $taxonomy )
			{
				$elements = array();
				$elements = explode('|',$taxonomy->field_elements);
				if( !empty($elements) ) {
					foreach( $elements AS $element )
					{
						$item->addTaxonomy($taxonomy->caption, trim($element));
					}
				}
			}
		}

		// Add the category taxonomy data.
		if (!empty($item->cat_name)) {
			$item->addTaxonomy('Category', $item->cat_name, $item->cat_published);
		}

		// Get content extras.
		FinderIndexerHelper::getContentExtras($item);
		
		// Index the item.
		FinderIndexer::index($item);
	}

	/**
	 * Method to get the SQL query used to retrieve the list listings.
	 *
	 * @return	object		A JDatabaseQuery object.
	 */
	protected function _getListQuery()
	{
		global $mtconf;
		
		$simple_searchable_cf_ids = $this->_getSimplSearchableCustomFieldIDs();

		$sql = new JDatabaseQuery();
		$sql->select('l.link_id, l.link_name, l.alias, l.user_id');
		$sql->select('l.address, l.city, l.state, l.country, l.postcode, l.telephone, l.fax, l.email, l.website, l.price');
		$sql->select('l.link_desc AS summary');
		$sql->select('c.cat_id, c.cat_name, c.alias, c.cat_published, c.cat_approved');
		$sql->select('l.link_published, l.link_approved');
		$sql->select('l.publish_up AS publish_start_time, l.publish_down AS publish_end_time');

		switch( $mtconf->get('sef_link_slug_type') )
		{
			case 1:
			default:
				$sql->select('l.alias as slug');
				break;
			case 2:
				$sql->select('l.link_id as slug');
				break;
		}
		
		$sql->select('c.alias as catslug');
		$sql->select('u.name AS author');
		$sql->from('#__mt_links AS l');
		$sql->join('LEFT', '#__mt_cl AS cl ON cl.link_id = l.link_id AND cl.main = 1');
		$sql->join('LEFT', '#__mt_cats AS c ON c.cat_id = cl.cat_id');
		$sql->join('LEFT', '#__users AS u ON u.id = l.user_id');
		
		if( !empty($simple_searchable_cf_ids) )
		{
			foreach( $simple_searchable_cf_ids AS $cf_id )
			{
				$sql->select('cfv'.$cf_id.'.value AS cfvalue'.$cf_id);
				$sql->join(
					'LEFT', '#__mt_cfvalues AS cfv'.$cf_id
					.' ON cfv'.$cf_id.'.cf_id = '.$cf_id.' AND cfv'.$cf_id.'.link_id = l.link_id'
					);
			}
		}
		
		return $sql;
	}

	/**
	 * Method to get the URL for the item. The URL is how we look up the link
	 * in the Finder index.
	 *
	 * @param	mixed		The id of the item.
	 * @return	string		The URL of the item.
	 */
	protected function _getURL($id)
	{
		return 'index.php?option=com_mtree&task=viewlink&link_id='.$id;
	}

	/**
	 * Method to get the Itemid of a published component's menu item.
	 *
	 * @param	string		component string in the form of: com_xxx
	 * @return	string		'&Itemid=X' or empty if no results.
	 */
	protected function _getItemId($option)
	{
		$component =& JComponentHelper::getComponent('com_mtree');

		$menus	= &JApplication::getMenu('site', array());
		$items	= $menus->getItems('componentid', $component->id);

		foreach($items as $item)
		{
			if( $item->query['option'] == 'com_mtree' )
			{
				return '&Itemid='.$item->id;
			}
		}

		return '';
	}
	
	/**
	 * Method to translate the native listing states into states that the
	 * indexer can use.
	 *
	 * @param	integer		The listing state.
	 * @param	integer		The category state.
	 * @return	integer		The translated indexer state.
	 */
	private function _translateState($link_published, $cat_published)
	{
		if ($link_published == 0 || $cat_published == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	/**
	 * Method to get a SQL query to load the published states for a listing.
	 *
	 * @return	object		A JDatabaseQuery object.
	 */
	private function _getStateQuery()
	{
		$sql = new JDatabaseQuery();
		$sql->select('l.link_id');
		$sql->select('l.link_published, c.cat_published');
		$sql->from('#__mt_links AS l');
		$sql->join('LEFT', '#__mt_cl AS cl ON cl.link_id = l.link_id AND cl.main = 1');
		$sql->join('LEFT', '#__mt_cats AS c ON c.cat_id = cl.cat_id');
		
		return $sql;
	}
	
	/**
	 * Method to get a list of simple searchable custom field IDs
	 *
	 * @return	array		An array of custom field ids
	 */
	private function _getSimplSearchableCustomFieldIDs()
	{
		$sql = new JDatabaseQuery();
		$sql->select('cf.cf_id');
		$sql->from('#__mt_customfields as cf');
		$sql->where('cf.published = 1 AND cf.simple_search = 1');
		$this->_db->setQuery($sql);
		
		return $this->_db->loadResultArray();
	}
	
	/**
	 * Method to get a content item to index.
	 *
	 * @param	integer		The id of the content item.
	 * @return	object		A FinderIndexerResult object.
	 * @throws	Exception on database error.
	 */
	protected function _getItem($id)
	{
		// Get the list query and add the extra WHERE clause.
		$sql = $this->_getListQuery();
		$sql->where('l.link_id = '.(int)$id);

		// Get the item to index.
		$this->_db->setQuery($sql);
		$row = $this->_db->loadAssoc();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			// Throw database error exception.
			throw new Exception($this->_db->getErrorMsg(), 500);
		}

		// Convert the item to a result object.
		$item = JArrayHelper::toObject($row, 'FinderIndexerResult');

		// Set the item type.
		$item->type_id	= $this->_type_id;

		// Set the item layout.
		$item->layout	= $this->_layout;

		return $item;
	}
	
	/**
	 * Method to setup the indexer to be run.
	 *
	 * @return	boolean		True on success.
	 */
	protected function _setup()
	{
		return true;
	}
	
}