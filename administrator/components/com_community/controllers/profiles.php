<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controller' );

/**
 * Jom Social Profile Controller
 */
class CommunityControllerProfiles extends CommunityController
{
	function __construct()
	{
		parent::__construct();

		$this->registerTask( 'publish' , 'savePublish' );
		$this->registerTask( 'unpublish' , 'savePublish' );		
		$this->registerTask( 'orderup' , 'saveOrder' );
		$this->registerTask( 'orderdown' , 'saveOrder' );
	}

	/**	
	 * Removes the specific field
	 *	 	
	 * @access public
	 *
	 **/
	function removeField()
	{
		$mainframe	=& JFactory::getApplication();
		
 		$ids	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$count	= count($ids);
		
		foreach( $ids as $id )
		{
			$table	=& JTable::getInstance( 'profiles', 'CommunityTable' );
			$table->load( $id );

			if(!$table->delete( $id ))
			{
				// If there are any error when deleting, we just stop and redirect user with error.
				$message	= JText::_('CC THERE WAS A PROBLEM WHILE REMOVING THE FIELD');
				$mainframe->redirect( 'index.php?option=com_community&task=profile' , $message);
				exit;
			}
		}

		$cache = & JFactory::getCache('com_content');
		$cache->clean();
		$message	= JText::_( $count . ' Field(s) successfully removed.');
 		$mainframe->redirect( 'index.php?option=com_community&view=profiles' , $message );
	}
 
	/**	
	 * Save the ordering of the entire records.
	 *	 	
	 * @access public
	 *
	 **/	 
	function saveOrder()
	{
		$mainframe	=& JFactory::getApplication();
	
		// Determine whether to order it up or down
		$direction	= ( JRequest::getWord( 'task' , '' ) == 'orderup' ) ? -1 : 1;

		// Get the ID in the correct location
 		$id			= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$db			=& JFactory::getDBO();
		
		if( isset( $id[0] ) )
		{
			$id		= (int) $id[0];

			// Load the JTable Object.
			$table	=& JTable::getInstance( 'profiles' , 'CommunityTable' );

			$table->load( $id );
			
			if( $table->type == 'group' )
			{
				$query	= 'SELECT * FROM ' . $db->nameQuote( '#__community_fields' ) . ' '
						. 'WHERE ' . $db->nameQuote( 'ordering' ) . ' > ' . $db->Quote( $table->ordering ) . ' '
						. 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( 'group' ) . ' '
						. 'ORDER BY ordering ASC '
						. 'LIMIT 1';

				$db->setQuery( $query );
				$nextGroup	= $db->loadObject();

				if( $nextGroup || $direction == -1 )
				{
					if( $direction == -1 )
					{
						// Get previous group in list
						$query	= 'SELECT * FROM ' . $db->nameQuote( '#__community_fields' ) . ' '
								. 'WHERE ' . $db->nameQuote( 'ordering' ) . ' < ' . $db->Quote( $table->ordering ) . ' '
								. 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( 'group' ) . ' '
								. 'ORDER BY ordering DESC LIMIT 1';

						$db->setQuery( $query );
						$previousGroup	= $db->loadObject();

						$query	= 'SELECT * FROM ' . $db->nameQuote( '#__community_fields' ) . ' '
								. 'WHERE ' . $db->nameQuote( 'ordering' ) . ' >= ' . $db->Quote( $table->ordering);

						if( $nextGroup )
						{
							$query	.= ' AND ' . $db->nameQuote( 'ordering' ) . ' < ' . $db->Quote( $nextGroup->ordering );
						}

						$query .= 'ORDER BY ordering ASC';

						$db->setQuery( $query );
						$currentFields	= $db->loadObjectList();

						// Get previous fields in the group
						$query		= 'SELECT * FROM ' . $db->nameQuote( '#__community_fields' ) . ' '
									. 'WHERE ' . $db->nameQuote( 'ordering' ) . ' >= ' . $db->Quote( $previousGroup->ordering ) . ' '
									. 'AND ' . $db->nameQuote( 'ordering') . ' < ' . $db->Quote( $table->ordering ) . ' '
									. 'ORDER BY ordering ASC';

						$db->setQuery( $query );
						$previousFields	= $db->loadObjectList();

						for( $i = 0; $i < count( $previousFields ); $i++ )
						{
							$row	=& $previousFields[ $i ];
							
							$row->ordering			= $row->ordering + count( $currentFields );

							$query	= 'UPDATE ' . $db->nameQuote( '#__community_fields' ) . ' '
									. 'SET ' . $db->nameQUote('ordering') . '=' . $db->Quote( $row->ordering ) . ' '
									. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $row->id );
							$db->setQuery( $query );
							$db->query();
						}

						for( $i = 0; $i < count( $currentFields ); $i ++ )
						{
							$row	=& $currentFields[ $i ];
							
							$row->ordering	= $row->ordering - count( $previousFields );

							$query	= 'UPDATE ' . $db->nameQuote( '#__community_fields' ) . ' '
									. 'SET ' . $db->nameQUote('ordering') . '=' . $db->Quote( $row->ordering ) . ' '
									. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $row->id );
							$db->setQuery( $query );
							$db->query();
						}
					}
					else
					{
						// Get end
						$query	= 'SELECT ordering FROM ' . $db->nameQuote( '#__community_fields' ) . ' '
								. 'WHERE ' . $db->nameQuote( 'ordering' ) . ' > ' . $db->Quote( $nextGroup->ordering ) . ' '
								. 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( 'group' ) . ' '
								. 'ORDER BY ordering ASC '
								. 'LIMIT 1';
						$db->setQuery( $query );
						$nextGroupLimit	= $db->loadResult();
						
						// Get the next group childs
						if( $nextGroupLimit )
						{
							$query		= 'SELECT * FROM ' . $db->nameQuote( '#__community_fields' ) . ' '
										. 'WHERE ordering >=' . $nextGroup->ordering . ' '
										. 'AND ordering < ' . $nextGroupLimit . ' '
										. 'ORDER BY ordering ASC';
						}
						else
						{
							$query		= 'SELECT * FROM ' . $db->nameQuote( '#__community_fields' ) . ' '
										. 'WHERE ordering >=' . $nextGroup->ordering . ' '
										. 'ORDER BY ordering ASC';
						}
						$db->setQuery( $query );
						$nextGroupChilds	= $db->loadObjectList();

						$nextGroupsCount	= count( $nextGroupChilds );
						
						// Get all childs of the current group field
						$query		= 'SELECT * FROM ' . $db->nameQuote( '#__community_fields' ) . ' '
									. 'WHERE ordering >=' . $table->ordering . ' '
									. 'AND ordering < ' . $nextGroup->ordering . ' '
									. 'ORDER BY ordering ASC';

						$db->setQuery( $query );	
						$currentGroupChilds	= $db->loadObjectList();
						$currentGroupsCount	= count( $currentGroupChilds );
						
						for( $i = 0; $i < count( $nextGroupChilds ); $i++ )
						{
							$row	=& $nextGroupChilds[ $i ];
							
							//$row->ordering		= $row->ordering - $currentGroupsCount;
							$row->ordering			= $table->ordering++;
							$query	= 'UPDATE ' . $db->nameQuote( '#__community_fields' ) . ' '
									. 'SET ' . $db->nameQUote('ordering') . '=' . $db->Quote( $row->ordering ) . ' '
									. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $row->id );

							$db->setQuery( $query );
							$db->query();
						}

						for( $i = 0; $i < count( $currentGroupChilds ); $i ++ )
						{
							$child	=& $currentGroupChilds[ $i ];
							
							$child->ordering	= $nextGroupsCount + $child->ordering;

							$query	= 'UPDATE ' . $db->nameQuote( '#__community_fields' ) . ' '
									. 'SET ' . $db->nameQUote('ordering') . '=' . $db->Quote( $child->ordering ) . ' '
									. 'WHERE ' . $db->nameQuote( 'id' ) . '=' . $db->Quote( $child->id );
							$db->setQuery( $query );
							$db->query();
						}
					}				
				}
			}
			else
			{
				$table->move( $direction );
			}
			
			$cache	=& JFactory::getCache( 'com_content');
			$cache->clean();
			
			$mainframe->redirect( 'index.php?option=com_community&view=profiles' );
		}
		
	}

	/**
	 * AJAX method to save a field
	 * 
	 * @param	int	id	Current field id
	 * @param	Array data	The fields data
	 * 
	 * @return	JAXResponse object	Azrul's AJAX Response object
	 **/
	function ajaxSaveGroup( $id , $data )
	{
		$user	=& JFactory::getUser();

		if ( $user->get('guest'))
		{
			JError::raiseError( 403, JText::_('CC ACCESS FORBIDDEN') );
			return;
		}

		$response	= new JAXResponse();

		// Load the JTable Object.
		$row	=& JTable::getInstance( 'profiles' , 'CommunityTable' );
		$row->load( $id );	
		$isValid		= true;
		$data['type']	= 'group';
		
		$row->bindAjaxPost( $data );

		// Do some validation before blindly saving the profile.
		if( empty( $row->name ) )
		{
			$error		= JText::_('CC NAME SHOULD NOT BE LEFT EMPTY');
			
			$response->addScriptCall( 'joms.jQuery("#error-notice").html("' . $error . '");');
			$isValid	= false;
		}
		
		if( $isValid )
		{
			$groupOrdering	= isset($data['group']) ? $data['group'] : '';
			$row->store( $groupOrdering );
			$parent			= '';
	
			// Get the view
			$view		=& $this->getView( 'profiles' , 'html' );
	
			if($id != 0)
			{
				$name	= '<a href="javascript:void(0);" onclick="azcommunity.editField(\'' . $row->id . '\');">' . $row->name . '</a>';
				$type	= '<span id="type' . $row->id . '" onclick="$(\'typeOption\').style.display = \'block\';$(this).style.display = \'none\';">'
						. JString::ucfirst($row->type)
						. '</span>';
		
				$publish		= $view->getPublish( $row , 'published' , 'profiles,ajaxTogglePublish' );
				$required		= $view->getPublish( $row , 'required' , 'profiles,ajaxTogglePublish');
				$visible		= $view->getPublish( $row , 'visible' , 'profiles,ajaxTogglePublish');
				$registration	= $view->getPublish( $row , 'registration' , 'profiles,ajaxTogglePublish');
	
				// Set the parent id
				$parent		= $row->id;
				
				// Update the rows in the table at the page.
				//@todo: need to update the title in a way looks like Joomla initialize the tooltip on document ready
				$response->addAssign('name' . $row->id, 'innerHTML' , $name);
				
				$response->addAssign('type' . $row->id, 'innerHTML', $type);
				$response->addAssign('published' . $row->id, 'innerHTML', $publish);
				$response->addAssign('required' . $row->id, 'innerHTML', $required);
				$response->addAssign('visible' . $row->id, 'innerHTML', $visible);
				$response->addAssign('registration' . $row->id, 'innerHTML', $registration);
				$response->addAssign('min' . $row->id, 'value', $row->min);
				$response->addAssign('max' . $row->id, 'value', $row->max);
				
			}
			else
			{
				$response->addScriptCall('window.location.href = "' . JURI::base() . 'index.php?option=com_community&view=profiles";');
			}
			$response->addScriptCall('cWindowHide();');
		}
		
		$response->sendResponse();
	}
	
	/**
	 * AJAX method to save a field
	 * 
	 * @param	int	id	Current field id
	 * @param	Array data	The fields data
	 * 
	 * @return	JAXResponse object	Azrul's AJAX Response object
	 **/
	function ajaxSaveField( $id , $data )
	{
		$user	=& JFactory::getUser();

		if ( $user->get('guest')) {
			JError::raiseError( 403, JText::_('CC ACCESS FORBIDDEN') );
			return;
		}

		$response	= new JAXResponse();

		// Load the JTable Object.
		$row	=& JTable::getInstance( 'profiles' , 'CommunityTable' );
		$row->load( $id );	
		$isValid	= true;
		$row->bindAjaxPost( $data );
		
// 		print_r($row);
		
		// override the option visiable, registration and required for label type.
		if($row->type == 'label')
		{
		    $row->visible 		= 0;
		    $row->required 		= 0;
		}
		
// 		echo '<br/>';
// 		print_r($row);
//
// 		exit;
		
		// Do some validation before blindly saving the profile.
		if( empty( $row->name ) )
		{
			$error		= JText::_('CC NAME SHOULD NOT BE LEFT EMPTY');
			
			$response->addScriptCall( 'joms.jQuery("#error-notice").html("' . $error . '");');
			$isValid	= false;
		}
		
		if( empty( $row->fieldcode ) )
		{
			$error		= JText::_('CC FIELD CODE SHOULD NOT BE LEFT EMPTY');
			
			$response->addScriptCall( 'joms.jQuery("#error-notice").html("' . $error . '");');
			$isValid	= false;
		}		
		
		if($row->required && !$row->registration)
		{
			$error		= JText::_('CC A FIELD IN REQUIRED SHOULD SET AS REGISTRATION AS WELL');
			
			$response->addScriptCall( 'joms.jQuery("#error-notice").html("' . $error . '");');
			$isValid	= false;
		}
		
		if( $isValid )
		{
			$groupOrdering	= isset($data['group']) ? $data['group'] : '';
			$row->store( $groupOrdering );
			$parent			= '';
	
			// Get the view
			$view		=& $this->getView( 'profiles' , 'html' );
	
			if($id != 0)
			{
				$name	= '<a href="javascript:void(0);" onclick="azcommunity.editField(\'' . $row->id . '\');">' . $row->name . '</a>';
				$type	= '<span id="type' . $row->id . '" onclick="$(\'typeOption\').style.display = \'block\';$(this).style.display = \'none\';">'
						. JString::ucfirst($row->type)
						. '</span>';
		
				$publish		= $view->getPublish( $row , 'published' , 'profiles,ajaxTogglePublish' );
				if($row->type == 'label')
				{
					$required		= $view->showPublish( $row , 'required');
					$visible		= $view->showPublish( $row , 'visible');
				}
				else
				{
					$required		= $view->getPublish( $row , 'required' , 'profiles,ajaxTogglePublish');
					$visible		= $view->getPublish( $row , 'visible' , 'profiles,ajaxTogglePublish');
				}
				
				$registration	= $view->getPublish( $row , 'registration' , 'profiles,ajaxTogglePublish');
	
				// Set the parent id
				$parent		= $row->id;
				
				// Update the rows in the table at the page.
				//@todo: need to update the title in a way looks like Joomla initialize the tooltip on document ready
				$response->addAssign('name' . $row->id, 'innerHTML' , $name);
				
				$response->addAssign('type' . $row->id, 'innerHTML', $type);
				$response->addAssign('published' . $row->id, 'innerHTML', $publish);
				$response->addAssign('required' . $row->id, 'innerHTML', $required);
				$response->addAssign('visible' . $row->id, 'innerHTML', $visible);
				$response->addAssign('registration' . $row->id, 'innerHTML', $registration);
				$response->addAssign('min' . $row->id, 'value', $row->min);
				$response->addAssign('max' . $row->id, 'value', $row->max);
				
			}
			else
			{
				$response->addScriptCall('window.location.href = "' . JURI::base() . 'index.php?option=com_community&view=profiles";');
			}
			$response->addScriptCall('cWindowHide();');
		}
		else
		{
			//release the form input back to enabled.
			$response->addScriptCall('joms.jQuery(\'#cWindowContent\').find(\'input, textarea, button\').attr(\'disabled\', false)');
		}
		
		$response->sendResponse();
	}

	/**
	 * AJAX method to toggle publish group and its associated fields status
	 * 
	 * @param	int	id	Current field id
	 * @param	string field	The field publish type
	 * 
	 * @return	JAXResponse object	Azrul's AJAX Response object
	 **/
	function ajaxGroupTogglePublish( $id , $field )
	{
	
		$user	=& JFactory::getUser();

		// @rule: Disallow guests.
		if ( $user->get('guest'))
		{
			JError::raiseError( 403, JText::_('CC ACCESS FORBIDDEN') );
			return;
		}
		
		$this->_registerFieldCheck($id , $field);		

		// Get the view
		$view		=& $this->getView( 'profiles' , 'html' );
		$response	= new JAXResponse();
	
		$gRow	=& JTable::getInstance( 'profiles' , 'CommunityTable' );
		$gRow->load( $id );
		
		$model			=& $this->getModel( 'profiles' );
		$groupFields	= $model->getGroupFields($gRow->ordering);
		
		//update all the fields
		if(count($groupFields) > 0)
		{
			foreach($groupFields as $item)
			{				
				// Load the JTable Object.
				$row	=& JTable::getInstance( 'profiles' , 'CommunityTable' );
				$row->load( $item->id );
		
				if( $gRow->$field ) //we follow the group here.
				{
					$row->$field	= 0;
					$row->store();
					$image			= 'publish_x.png';
				}
				else
				{
					$row->$field	= 1;
					$row->store();
					$image			= 'tick.png';
				}
					
				$html	= $view->getPublish( $row , $field , 'profiles' . ',ajaxTogglePublish' );
			   	$response->addAssign( $field . $row->id , 'innerHTML' , $html );
						
			}
		}
						
				
		//now update group
		if( $gRow->$field )
		{
			$gRow->$field	= 0;
			$gRow->store();
			$image			= 'publish_x.png';
		}
		else
		{
			$gRow->$field	= 1;
			$gRow->store();
			$image			= 'tick.png';
		}
			
		$html	= $view->getPublish( $gRow , $field , 'profiles' . ',ajaxGroupTogglePublish' );
	   	$response->addAssign( $field . $gRow->id , 'innerHTML' , $html );		
		
		return $response->sendResponse();		
		
	}


	/**
	 * AJAX method to toggle publish status
	 * 
	 * @param	int	id	Current field id
	 * @param	string field	The field publish type
	 * 
	 * @return	JAXResponse object	Azrul's AJAX Response object
	 **/
	function ajaxTogglePublish( $id , $field )
	{
		$user	=& JFactory::getUser();

		// @rule: Disallow guests.
		if ( $user->get('guest'))
		{
			JError::raiseError( 403, JText::_('CC ACCESS FORBIDDEN') );
			return;
		}	
	
	
		$this->_registerFieldCheck($id , $field);
		
		$response	= new JAXResponse();
		$view		=& $this->getView( 'profiles' , 'html' );		
		
		$row		=& JTable::getInstance( 'profiles' , 'CommunityTable' );
		$row->load( $id );

		if( $row->$field ) //we follow the group here.
		{
			$row->$field	= 0;
			$row->store();
			$image			= 'publish_x.png';
		}
		else
		{
			$row->$field	= 1;
			$row->store();
			$image			= 'tick.png';
		}
		
		$html	= $view->getPublish( $row , $field , 'profiles' . ',ajaxTogglePublish' );
	   	$response->addAssign( $field . $id , 'innerHTML' , $html );		
		
		//we need to check group status as well.				
		$model	=& $this->getModel( 'profiles' );
		$group	= $model->getFieldGroup($row->ordering);
		
		$gRow	=& JTable::getInstance( 'profiles' , 'CommunityTable' );
		$gRow->load( $group->id );				
		
		if( !$gRow->$field && $row->$field)
		{
			//this mean group currently was disabled. and the new status for child is enabled.
			//so we need to enable this group as well.
			
			$gRow->$field	= 1;
			$gRow->store();
			$image			= 'tick.png';
			
			$html	= $view->getPublish( $gRow , $field , 'profiles' . ',ajaxGroupTogglePublish' );
		   	$response->addAssign( $field . $gRow->id , 'innerHTML' , $html );			
		}
		else
		{
			//check if all the fields under this group are disabled.
			$groupFields	= $model->getGroupFields($gRow->ordering);
			$countDisabled	= 0;			
			
			//update all the fields
			if(count($groupFields) > 0)
			{
				foreach($groupFields as $item)
				{
					if(! $item->$field)
						$countDisabled++;
				}
				
				if( count($groupFields) == $countDisabled)
				{
			
					$gRow->$field	= 0;
					$gRow->store();
					$image			= 'publish_x.png';
					
					$html	= $view->getPublish( $gRow , $field , 'profiles' . ',ajaxGroupTogglePublish' );
				   	$response->addAssign( $field . $gRow->id , 'innerHTML' , $html );						
				}
			}
		}
		
		//return parent::ajaxTogglePublish( $id , $field , 'profiles' );
		return $response->sendResponse();		
	}
	
	function _registerFieldCheck($id , $field)
	{
		$failed	= false;
	
		if($field == 'registration' || $field == 'required')
		{
			//if current toggle field is registration, we need to check on 'required'
			// if user disabled this registration field.
			$html	= '';
			$row	=& JTable::getInstance( 'profiles' , 'CommunityTable' );
			$row->load( $id );
			
			if(($field == 'registration') && ($row->required && $row->registration))
			{
				// this mean the action is to toggle registration from true to false when required is true.
				// if thats the case, it defeated the rules where required field must
				// be a registration field as well.
				$html	= 'You cannot disable \'registration\' when the field is required.';
				$failed	= true;
			}
			else if(($field == 'required') && (!$row->required && !$row->registration))			
			{
				// this mean the action is to toggle required from false to true when registration is false
				// if thats the case, it defeated the rules where required field must
				// be a registration field as well.
				$html	= 'You cannot enable \'required\' when the field is not a registration field.';
				$failed	= true;			
			}			
			
			
			if($failed)
			{
				$response	= new JAXResponse();				
								
				$response->addScriptCall("cWindowShow('jax.call(\"community\",\"\")', 'Error' , 400, 70, 'error');");
				$response->addAssign('cWindowContent', 'innerHTML', $html);
				$response->addScriptCall("joms.jQuery('#cWindowContent').css('overflow','auto');");				
				return $response->sendResponse();			
			}
		}
	}

	/**
	 * AJAX method to display the form
	 * 
	 * @param	int	fieldId	The fieldId that we are editing
	 * @param	boolean	isGroup	Determines whether the current field is a group
	 * 
	 * @return	JAXResponse object	Azrul's AJAX Response object
	 **/
	function ajaxEditGroup( $fieldId , $isGroup = false )
	{
		$user	=& JFactory::getUser();
		
		if ( $user->get('guest'))
		{
			JError::raiseError( 403, JText::_('CC ACCESS FORBIDDEN') );
			return;
		}

		$response	= new JAXResponse();

		$model			=& $this->getModel( 'profiles' );
		$fieldGroups	= $model->getGroups();
		
		// Load the JTable Object.
		$row	=& JTable::getInstance( 'profiles' , 'CommunityTable' );
		$row->load( $fieldId );

		ob_start();
?>
<div style="background-color: #F9F9F9; border: 1px solid #D5D5D5; margin-bottom: 10px; padding: 5px;font-weight: bold;">
	<?php echo JText::_('CC CREATE NEW GROUP FOR YOUR CUSTOM PROFILES');?>
</div>
<div id="error-notice" style="color: red; font-weight:700;"></div>
<div style="clear: both;"></div>
<form action="#" method="post" name="editField" id="editField">
<table cellspacing="0" class="admintable" border="0" width="100%">
	<tbody>
		<tr>
			<td class="key"><?php echo JText::_('CC NAME');?></td>
			<td>:</td>
			<td>
				<input type="text" value="<?php echo $row->name;?>" name="name" style="width:250px;" />
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_('CC PUBLISHED');?></td>
			<td>:</td>
			<td>
				<span><?php echo $this->_buildRadio($row->published, 'published', array( JText::_('CC YES'), JText::_('CC NO')));?></span>
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_('CC VISIBLE');?></td>
			<td>:</td>
			<td><?php echo $this->_buildRadio($row->visible, 'visible', array( JText::_('CC YES'), JText::_('CC NO')));?></td>
		</tr>
	</tbody>
</table>
</form>
<?php
		$contents	= ob_get_contents();
		ob_end_clean();

		$buttons	= '<input type="button" class="button" onclick="javascript:azcommunity.saveFieldGroup(\'' . $row->id . '\');return false;" value="' . JText::_('CC SAVE') . '"/>';
		$buttons	.= '&nbsp;&nbsp;<input type="button" class="button" onclick="javascript:cWindowHide();" value="' . JText::_('CC CANCEL') . '"/>';
		$response->addAssign( 'cWindowContent' , 'innerHTML' , $contents );
		$response->addScriptCall( 'cWindowActions' , $buttons );
		return $response->sendResponse();
	}
	
	/**
	 * AJAX method to display the form
	 * 
	 * @param	int	fieldId	The fieldId that we are editing
	 * @param	boolean	isGroup	Determines whether the current field is a group
	 * 
	 * @return	JAXResponse object	Azrul's AJAX Response object
	 **/
	function ajaxEditField( $fieldId , $isGroup = false )
	{
		$user	=& JFactory::getUser();
		
		if ( $user->get('guest')) {
			JError::raiseError( 403, JText::_('CC ACCESS FORBIDDEN') );
			return;
		}

		$response	= new JAXResponse();

		$model			=& $this->getModel( 'profiles' );
		$fieldGroups	= $model->getGroups();
		
		// Load the JTable Object.
		$row	=& JTable::getInstance( 'profiles' , 'CommunityTable' );

		$row->load( $fieldId );

		$group			= $model->getFieldGroup( $row->ordering );
		
		ob_start();
?>
<div style="background-color: #F9F9F9; border: 1px solid #D5D5D5; margin-bottom: 10px; padding: 5px;font-weight: bold;">
	<?php echo JText::_('CC CREATE NEW CUSTOM PROFILE FOR YOUR SITE');?>
</div>
<div id="error-notice" style="color: red; font-weight:700;"></div>
<div style="clear: both;"></div>
<form action="#" method="post" name="editField" id="editField">
<table cellspacing="0" class="admintable" border="0" width="100%">
	<tbody>
		<tr>
			<td class="key"><?php echo JText::_('CC NAME');?></td>
			<td>:</td>
			<td>
				<input type="text" value="<?php echo $row->name;?>" name="name" />
			</td>
			<td class="key"><?php echo JText::_('CC PUBLISHED');?></td>
			<td>:</td>
			<td>
				<span><?php echo $this->_buildRadio($row->published, 'published', array('Yes', 'No'));?></span>
			</td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_('CC TYPE');?></td>
			<td>:</td>
			<td><?php echo $this->_buildTypes($row->type);?></td>
			<td class="key"><?php echo JText::_('CC REQUIRED');?></td>
			<td>:</td>
			<td colspan="4"><?php echo $this->_buildRadio($row->required, 'required', array('Yes', 'No'));?></td>
		</tr>
		<tr style="<?php echo ($row->type != 'group') ? 'display: table-row;' : 'display: none;'; ?>" class="fieldGroups">
			<td class="key"><?php echo JText::_('CC GROUP');?></td>
			<td>:</td>
			<td colspan="4">
				<select name="group">
			<?php
				for( $i = 0; $i < count( $fieldGroups ); $i++ )
				{
					$selected	= (isset($group->id) && $group->id == $fieldGroups[$i]->id ) ? ' selected="selected"' : '';
			?>
				<option value="<?php echo $fieldGroups[$i]->ordering;?>"<?php echo $selected;?>><?php echo $fieldGroups[$i]->name; ?></option>
			<?php
				}
			?>
				</select>
			</td>
		</tr>		
		<tr>
			<td class="key"><?php echo JText::_('CC FIELD CODE');?></td>
			<td>:</td>
			<td><input type="text" value="<?php echo $row->fieldcode; ?>" name="fieldcode" maxlength="255" /></td>
			<td class="key"><?php echo JText::_('CC VISIBLE');?></td>
			<td>:</td>
			<td><?php echo $this->_buildRadio($row->visible, 'visible', array('Yes', 'No'));?></td>
		</tr>
		
		<tr>
			<td valign="top" class="key"><?php echo JText::_('CC REGISTRATION');?></td>
			<td valign="top">:</td>
			<td colspan="4">
				<?php echo $this->_buildRadio($row->registration, 'registration', array('Yes', 'No'));?>
			</td>
		</tr>
		<?php echo $this->_buildSize( $row );?>
		<tr>
			<td valign="top" class="key"><?php echo JText::_('CC TOOLTIP');?></td>
			<td valign="top">:</td>
			<td colspan="4">
				<textarea rows="3" cols="50" name="tips"><?php echo $row->tips;?></textarea>
			</td>
		</tr>
		<?php echo $this->_buildOptions($row, $row->id, $row->type);?>
	</tbody>
</table>
</form>
<?php
		$contents	= ob_get_contents();
		ob_end_clean();

		$buttons	= '<input type="button" class="button" onclick="javascript:azcommunity.saveField(\'' . $row->id . '\');return false;" value="' . JText::_('CC SAVE') . '"/>';
		$buttons	.= '&nbsp;&nbsp;<input type="button" class="button" onclick="javascript:cWindowHide();" value="' . JText::_('CC CANCEL') . '"/>';
		$response->addAssign( 'cWindowContent' , 'innerHTML' , $contents );
		$response->addScriptCall( 'cWindowActions' , $buttons );
		return $response->sendResponse();
	}

	/**
	 * Method to build field types data
	 * 
	 * @access	private	 
	 * @param	string	Selected type
	 * 	 
	 * @return	string	HTML output
	 **/
	function _buildTypes( $selectedType )
	{
		$model	=& $this->getModel( 'profiles' );
		$html	= '';
		$types	= $model->getProfileTypes();

		$html	.= '<select name="type" onchange="azcommunity.changeType(this.value)" id="type">';

		foreach( $types as $type => $value)
		{
			$selected	= ( trim($type) == $selectedType ) ? 'selected="true"' : '';
			$html		.= '<option value="' . $type . '"' . $selected . '>' . $value . '</option>';
		}
		return $html;
	}

	/**
	 * Method to build Radio fields
	 * 
	 * @access	private
	 * @param	string
	 * 	 
	 * @return	string	HTML output
	 **/
	function _buildRadio($status, $fieldname, $values)
	{
		$html	= '<span>';
		
		if($status || $status == '1'){
			$html	.= '<input type="radio" name="' . $fieldname . '" value="1" checked="checked" />' . $values[0];
			$html	.= '<input type="radio" name="' . $fieldname . '" value="0" />' . $values[1];
		} else {
			$html	.= '<input type="radio" name="' . $fieldname . '" value="1" />' . $values[0];
			$html	.= '<input type="radio" name="' . $fieldname . '" value="0" checked="checked" />' . $values[1];	
		}
		$html	.= '</span>';
		
		return $html;
	}
	
	function _buildOptions(& $row, $id, $type)
	{
		if( $row->type == 'select' || $row->type == 'singleselect' || $row->type == 'radio' || $row->type == 'list' || $row->type == 'checkbox')
		{
			$html	= '<tr style="display: table-row;" class="fieldOptions">';
		}
		else
		{
			$html	= '<tr style="display: none;" class="fieldOptions">';
		}
	
	
		$html	.= '	<td class="key" valign="top">
							<span>Option</span><br />
						</td>
						<td valign="top">:</td>
						<td colspan="4">';
		$html	.= '<textarea rows="4" cols="50" name="options">' . $row->options . '</textarea><br />'
				 . '<span>Separate each options with a new line</span>';
	
		return $html;
	}
	
	function _buildSize(&$row)
	{
		if( $row->type == 'text' || $row->type == 'textarea' || $row->type == '')
		{
			$html	= '<tr style="display: table-row;" class="fieldSizes">';
		}	
		else
		{
			$html	= '<tr style="display: none;" class="fieldSizes">';
		}
	
		$html	.= '<td class="key">' . JText::_('CC MINIMUM CHARACTERS') . '</td>'
				 . '<td>:</td>'
				 . '<td>'
				 . '	<input type="text" name="min" size="5" value="' . $row->min . '" />'
				 . '</td>'
				 . '<td class="key">' . JText::_('CC MAXIMUM CHARACTERS') . '</td>'
				 . '<td>:</td>'
				 . '<td>'
				 . '	<input type="text" name="max" size="5" value="' . $row->max . '" />'
				 . '</td>'
				 . '</tr>';
		return $html;
	}
}