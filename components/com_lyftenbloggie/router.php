<?php
/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

function LyftenBloggieBuildRoute(&$query)
{
	static $items;

	$segments	= array();
	$itemid		= null;
	$showID 	= false;	
	
	// Get the menu items for this component.
	if (!$items) {
		$component	= &JComponentHelper::getComponent('com_lyftenbloggie');
		$menu		= &JSite::getMenu();
		$items		= $menu->getItems('componentid', $component->id);
	}
	
	// Search for an appropriate menu item.
	if (is_array($items))
	{
		// If only the option and itemid are specified in the query, return that item.
		if (!isset($query['view']) && !isset($query['id']) && !isset($query['catid']) && isset($query['Itemid'])) {
			$itemid = (int) $query['Itemid'];
		}

		// Search for a specific link based on the critera given.
		if (!$itemid)
		{
			foreach ($items as $item)
			{
				if (isset($item->id) && isset($query['Itemid']) 
				    && $item->id != $query['Itemid'])
				{
				    continue;
				}
				// Check if this menu item links to this item.
				if (isset($item->query['view']) && $item->query['view'] == 'entry'
					&& isset($item->query['id']) && $item->query['id'] == $query['id'])
				{
					$itemid	= $item->id;
				}
				elseif (isset($item->query['view']) && $item->query['view'] == 'lyftenbloggie'
					&& isset($query['category'])
					&& !isset($item->query['id']))
				{
					$itemid	= $item->id;
				}
				elseif (isset($item->query['view']) && $item->query['view'] == 'lyftenbloggie'
					&& isset($query['category']) && isset($query['id'])
					&& isset($item->query['category']) && $item->query['category'] == $query['category'])
				{
					$itemid	= $item->id;
				}
				elseif (isset($item->query['view']) && $item->query['view'] == 'entry'
						&& isset($item->query['id']) && $item->query['id'] == $query['id'])
				{
					$itemid	= $item->id;
				}
				elseif (isset($item->query['view']) && $item->query['view'] == 'author'
						&& isset($item->query['layout']) && $item->query['layout'] == $query['layout'])
				{
					$itemid	= $item->id;
				}
				
			}
		}

		// If no specific link has been found, search for a general one.
		if (!$itemid)
		{
			foreach ($items as $item)
			{
				$mquery = $item->query;
				if (isset($item->id) && isset($query['Itemid']) 
				    && $item->id != $query['Itemid'])
				{
				    continue;
				}

				if ((isset($mquery['view']) && isset($query['view'])) && $mquery['view'] == $query['view'])
				{
					if ($query['view'] == 'entry' && isset($query['category']) && isset($mquery['category']))
					{
						$itemid		= $item->id;
						$segments[]	= strtolower($query['category']);
						unset($query['category']);
						unset($query['year']);
						unset($query['month']);
						unset($query['day']);
						break;
					}
					elseif ($query['view'] == 'comments' && isset($query['id']) && isset($mquery['id']) && isset($query['year']) && isset($query['month']) && isset($query['day']))
					{
						if ($query['id'] == $mquery['id'])
						{
							$itemid		= $item->id;
							$segments[]	= $query['year'];
							$segments[]	= $query['month'];
							$segments[]	= $query['day'];
							$segments[]	= $query['id'];
							$segments[]	= strtolower(JText::_('COMMENTS'));
							unset($query['year']);
							unset($query['month']);
							unset($query['day']);
							unset($query['view']);
							break;
						}
					}
					elseif ($query['view'] == 'author')
					{
						$itemid		= $item->id;
						$segments[] = strtolower($query['view']);
						if(isset($query['layout']))
							$segments[]	= (strtolower($query['layout']) == 'form') ? 'new-entry' : strtolower($query['layout']);

						unset($query['view'], $query['layout']);
						break;
					}
					elseif ($query['view'] == 'lyftenbloggie' && isset($query['category']))
					{
						$itemid		= $item->id;
						$segments[]	= strtolower($query['category']);
						unset($query['category']);
						break;
					}
					elseif ($query['view'] == 'entry')
					{
						if (isset($query['id']) && isset($query['category']))
						{
							$itemid		= $item->id;
							$segments[]	= strtolower($query['category']);
							$segments[]	= $query['id'];
							unset($query['category']);
							break;
						}
					}
				}
			}
		}
	}

	if (isset($query['id']) && isset($query['year']) && isset($query['month']) && isset($query['day']))
	{
		$segments[]	= $query['year'];
		$segments[]	= $query['month'];
		$segments[]	= $query['day'];
		$segments[]	= $query['id'];
		unset($query['year']);
		unset($query['month']);
		unset($query['day']);
		unset($query['view']);
		unset($query['id']);
	}
				
	if (isset($query['year']) && isset($query['month']) && isset($query['day']))
	{
		$segments[]	= $query['year'];
		$segments[]	= $query['month'];
		$segments[]	= ($query['day'] != 0) ? $query['day'] : strtolower(JText::_('ENTRIES'));
		unset($query['year']);
		unset($query['month']);
		unset($query['day']);
	}

	if ((isset($query['view']) && $query['view'] == 'author'))
	{
		$segments[] = 'author';
		if(isset($query['layout']))
			$segments[]	= (strtolower($query['layout']) == 'form') ? 'new-entry' : strtolower($query['layout']);

		unset($query['view'], $query['layout']);
	}

	if (isset($query['view']) && $query['view'] == 'entry' && isset($query['id']) && isset($query['category']))
	{
		if(isset($query['category']) && !$itemid){
			if(!$query['category']){
				$segments[]	= strtolower(JText::_('ALL'));
			}else{
				$segments[]	= ($query['category']) ? strtolower($query['category']) : strtolower(JText::_('UNCATEGORIZED'));
			}
		}
		$segments[]	= $query['id'];
		unset($query['category']);
		unset($query['id']);
		unset($query['view']);
	}
		
	// Check if a tag was specified.
	if (isset($query['tag']))
	{
		$segments[] = urlencode(strtolower(JText::_('TAG')));
		$segments[] = $query['tag'];
		unset($query['tag']);
	}
		
	// Check if a category was specified.
	if (isset($query['category']))
	{
		if(!$query['category']){
			$segments[]	= strtolower(JText::_('ALL'));
		}else{
			$segments[]	= ($query['category']) ? strtolower($query['category']) : strtolower(JText::_('UNCATEGORIZED'));
		}
		unset($query['view']);
		unset($query['category']);
	}

	// Check if an author was specified.
	if (isset($query['author']))
	{
		$segments[] = strtolower(JText::_('AUTHORS'));
		$segments[] = $query['author'];
		unset($query['author']);
	}

	if ($itemid)
	{
		$query['Itemid'] = $itemid;

		// Remove the unnecessary URL segments.
		unset($query['view']);
		unset($query['id']);
	}

	return $segments;
}

function LyftenBloggieParseRoute($segments)
{
	$vars	= array();

	// Get the active menu item.
	$menu	= &JSite::getMenu();
	$item	= &$menu->getActive();

	// Count route segments
	$count = count($segments);

	// Check if we have a valid menu item.
	if (is_object($item))
	{
		//Handle View and Identifier
		switch($item->query['view'])
		{
			case 'lyftenbloggie' :
			{
				if ($count == 3 && $segments[1] != $segments[2])
				{
					$vars['view']	= 'lyftenbloggie';
					$vars['year']	= $segments[0];
					$vars['month']	= $segments[1];
					$vars['day']	= ($segments[2] != 0) ? $segments[2] : $segments[2];
				}
				elseif ($count == 3)
				{
					$vars['view']		= 'entry';
					$vars['category']	= $segments[0];
					$vars['id']			= $segments[1];
				}
				elseif ($count == 4)
				{
					$vars['view']	= 'entry';
					$vars['year']	= $segments[0];
					$vars['month']	= $segments[1];
					$vars['day']	= $segments[2];
					$vars['id']		= $segments[3];
				}
				elseif ($count == 5 && $segments[4] == strtolower(JText::_('COMMENTS')))
				{
					$vars['view']	= 'comments';
					$vars['year']	= $segments[0];
					$vars['month']	= $segments[1];
					$vars['day']	= $segments[2];
					$vars['id']		= $segments[3];
				}
				elseif ($count == 2 && $segments[1] == strtolower(JText::_('NEW')))
				{
					$vars['view']		= 'entry';
					$vars['layout']		= 'form';
				}
				elseif ($count == 2 && $segments[0] == strtolower(JText::_('TAG')))
				{
					$vars['view']		= 'lyftenbloggie';
					$vars['tag']		= $segments[1];
				}
				elseif (isset($item->query['category']) && $count == 1)
				{
				
					if (strpos($segments[0], ':')) {
						$vars['view']		= 'entry';
						$vars['id']			= $segments[0];
					}elseIf(is_numeric($segments[0])){
						$vars['view']		= 'entry';
						$vars['id']			= $segments[0];
					}else{
						$vars['view']		= 'lyftenbloggie';
						if($segments[0] ==  strtolower(JText::_('ALL'))){
							$vars['category']	= 0;
						}else{
							$vars['category']	= $segments[0];
						}
					}
				}
				elseif ($count == 2 && $segments[0] == strtolower(JText::_('AUTHORS')))
				{
					$vars['view']		= 'lyftenbloggie';
					$vars['author']		= $segments[1];
				}
				elseif (isset($item->query['category']) && $count == 2)
				{
					$vars['view']		= 'entry';
					$vars['id']			= $segments[1];
					$vars['category']	= $segments[0];
				}

			} break;

			case 'entry'   :
			{
				if ($item->query['view'] == 'entry' && isset($segments[0]))
				{
					if (isset($segments[0]) && strpos($segments[0], ':')) {
						list($catid, $alias) = explode(':', $segments[0], 2);
					}
					$vars['view']	= 'entry';
					$vars['catid']	= $catid;
				}

			} break;

			case 'author'   :
			{
				$vars['view']	= 'author';
				if ($count == 2)
					$vars['layout']		= ($segments[1] == 'new-entry') ? 'form' : $segments[1];
			} break;
		}
	}
	else
	{
		// Check if there are any route segments to handle.
		if ($count)
		{
			if ($count == 1 && $segments[0] == 'author')
			{
				$vars['view']	= 'author';
			}
			elseif ($count == 2 && $segments[0] == 'author' && $segments[1] == 'mydetails')
			{
				$vars['view']	= 'author';
				$vars['layout']	= ($segments[1] == 'new-entry') ? 'form' : $segments[1];
			}
			elseif ($count == 3)
			{
				$vars['view']	= 'lyftenbloggie';
				$vars['year']	= $segments[0];
				$vars['month']	= $segments[1];
				$vars['day']	= ($segments[2] != 0) ? $segments[2] : $segments[2];
			}
			elseif ($count == 4)
			{
				$vars['view']	= 'entry';
				$vars['year']	= $segments[0];
				$vars['month']	= $segments[1];
				$vars['day']	= ($segments[2] != 0) ? $segments[2] : $segments[2];
				$vars['id']		= $segments[3];
			}
			elseif (count($segments) == 2 && $segments[0] == strtolower(JText::_('AUTHORS')))
			{
				$vars['view']		= 'lyftenbloggie';
				$vars['author']		= $segments[1];
			}
			elseif ($count == 2)
			{
				$vars['view']		= 'entry';
				$vars['id']			= $segments[1];
				$vars['category']	= $segments[0];
			}
			elseif ($count == 1)
			{
				$vars['view']		= 'lyftenbloggie';
				$vars['category']	= $segments[0];
			}
		}
	}
	return $vars;
}
?>