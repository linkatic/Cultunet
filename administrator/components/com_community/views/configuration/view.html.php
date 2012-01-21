<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
/**
 * Configuration view for Jom Social
 */
class CommunityViewConfiguration extends JView
{
	/**
	 * The default method that will display the output of this view which is called by
	 * Joomla
	 * 
	 * @param	string template	Template file name
	 **/	 	
	function display( $tpl = null )
	{
		//Load pane behavior
		jimport('joomla.html.pane');
		$pane   	=& JPane::getInstance('sliders');
		$document	=& JFactory::getDocument();
		
		// Load tooltips
		JHTML::_('behavior.tooltip', '.hasTip');
		JHTML::_('behavior.switcher');
		
		$params	= $this->get( 'Params' );

		// Add submenu
		$contents = '';
		ob_start();
		require_once( JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_community' . DS . 'views' . DS . 'configuration' . DS . 'tmpl' . DS . 'navigation.php' );

		$contents = ob_get_contents();
		ob_end_clean();

		$document	=& JFactory::getDocument();
		
		$document->setBuffer($contents, 'modules', 'submenu');

		$lists = array();

		for ($i=1; $i<=31; $i++) {
			$qscale[]	= JHTML::_('select.option', $i, $i);
		}
		
		$lists['qscale'] = JHTML::_('select.genericlist',  $qscale, 'qscale', 'class="inputbox" size="1"', 'value', 'text', $params->get('qscale', '11'));

		$videosSize = array
		(
			JHTML::_('select.option', '320x240', '320x240 (QVGA 4:3)'),
			JHTML::_('select.option', '400x240', '400x240 (WQVGA 5:3)'),
			JHTML::_('select.option', '400x300', '400x300 (Quarter SVGA 4:3)'),
			JHTML::_('select.option', '480x272', '480x272 (Sony PSP 30:17)'),
			JHTML::_('select.option', '480x320', '480x320 (iPhone 3:2)'),
			JHTML::_('select.option', '512x384', '512x384 (4:3)'),
			JHTML::_('select.option', '600x480', '600x480 (5:4)'),
			JHTML::_('select.option', '640x360', '640x360 (16:9)'),
			JHTML::_('select.option', '640x480', '640x480 (VCA 4:3)'),
			JHTML::_('select.option', '800x600', '800x600 (SVGA 4:3)'),
		);

		$lists['videosSize'] = JHTML::_('select.genericlist',  $videosSize, 'videosSize', 'class="inputbox" size="1"', 'value', 'text', $params->get('videosSize'));
		
		$dstOffset	= array();
		$counter = -12;
		for($i=0; $i <= 24; $i++ ){
			$dstOffset[] = 	JHTML::_('select.option', $counter, $counter);
			$counter++;
		}
		
		$lists['dstOffset'] = JHTML::_('select.genericlist',  $dstOffset, 'daylightsavingoffset', 'class="inputbox" size="1"', 'value', 'text', $params->get('daylightsavingoffset'));
		

		$networkModel	= $this->getModel( 'network' , false );

		$JSNInfo 		=& $networkModel->getJSNInfo();
		$JSON_output	=& $networkModel->getJSON();

		$lists['enable'] = JHTML::_('select.booleanlist',  'network_enable', 'class="inputbox"', $JSNInfo['network_enable'] );
		
		$uploadLimit = ini_get('upload_max_filesize');
		$uploadLimit = JString::str_ireplace('M', ' MB', $uploadLimit);

		$this->assignRef( 'JSNInfo', $JSNInfo );
		$this->assignRef( 'JSON_output', $JSON_output );		
		$this->assignRef( 'lists', $lists );
		$this->assign( 'uploadLimit' , $uploadLimit );		
		$this->assign( 'config' , $params );

		parent::display( $tpl );

	}

	function getTemplatesList( $name , $default = '' )
	{
		$path	= dirname(JPATH_BASE) . DS . 'components' . DS . 'com_community' . DS . 'templates';
	
		if( $handle = @opendir($path) )
		{
			while( false !== ( $file = readdir( $handle ) ) )
			{
				// Do not get '.' or '..' or '.svn' since we only want folders.
				if( $file != '.' && $file != '..' && $file != '.svn' && JFolder::exists( $path . DS . $file) )
					$templates[]	= $file;
			}
		}
		
		$html	= '<select name="' . $name . '">';

		foreach( $templates as $template )
		{
			if( $template )
			if( !empty( $default ) )
			{
				$selected	= ( $default == $template ) ? ' selected="true"' : '';
			}
			$html	.= '<option value="' . $template . '"' . $selected . '>' . $template . '</option>';
		}
		$html	.= '</select>';

		return $html;
	}

	function getKarmaHTML( $name , $value, $readonly=false, $updateTarget='')
	{
		$isReadOnly	= ($readonly) ? ' readonly="readonly"' : '';
		$requiredTargetUpdate = (! empty($updateTarget)) ? 'onblur="azcommunity.updateField(\''.$name.'\', \''.$updateTarget.'\')"' : '';
	
		$html	= '<table width="100%">';
		$html	.= '<tr>';
		$html	.= '	<td width="10%">';
		$html	.= '<input type="text" size="3" value="' . $value . '" name="' . $name . '" id="'.$name.'" '.$isReadOnly.' '.$requiredTargetUpdate.' /> ';
		$html	.= JText::_('Use Image');
		$html	.= '	</td>';
		$html	.= '	<td>';
		$html	.= '	<img src="' . $this->_getKarmaImage( $name ) . '" />';
		$html	.= '	</td>';
		$html	.= '</tr>';
		$html	.= '</table>';
		return $html;
	}

	function getNotifyTypeHTML( $selected )
	{
		$types	= array();
		
		$types[]	= array( 'key' => '1' , 'value' => JText::_('CC EMAIL') );
		$types[]	= array( 'key' => '2' , 'value' => JText::_('CC PRIVATE MESSAGE') );
		
		$html		= '<select name="notifyby">';
		
		foreach( $types as $type => $option )
		{
			$selectedData	= '';
			if( $option['key'] == $selected )
			{
				$selectedData	= ' selected="true"';
			}
			$html	.= '<option value="' . $option['key'] . '"' . $selectedData . '>' . $option['value'] . '</option>';
		}
		$html	.= '</select>';
		
		return $html;
	}
	
	function getPrivacyHTML( $name , $selected , $showSelf = false )
	{
		$public		= ( $selected == 0 ) ? 'checked="true" ' : '';
		$members	= ( $selected == 20 ) ? 'checked="true" ' : '';
		$friends	= ( $selected == 30 ) ? 'checked="true" ' : '';
		$self		= ( $selected == 40 ) ? 'checked="true" ' : '';
		
		$html	= '<input type="radio" value="0" name="' . $name . '" ' . $public . '/> ' . JText::_('CC PUBLIC');
		$html	.= '<input type="radio" value="20" name="' . $name . '" ' . $members . '/> ' . JText::_('CC MEMBERS');
		$html	.= '<input type="radio" value="30" name="' . $name . '" ' . $friends . '/> ' . JText::_('CC FRIENDS');
		
		if( $showSelf )
		{
			$html	.= '<input type="radio" value="40" name="' . $name . '" ' . $self . '/> ' . JText::_('CC SELF');
		}
		return $html;
	}
	
	/**
	 * Method to return the image path for specific elements
	 * @access	private
	 *
	 * @return	string	$image	The path to the image.
	 */
	function _getKarmaImage( $name )
	{
		$image	= '';
		
		switch( $name )
		{
			case 'point0':
				$image	= JURI::root() . 'components/com_community/templates/default/images/karma-0.5-5.gif';
				break;
			case 'point1':
				$image	= JURI::root() . 'components/com_community/templates/default/images/karma-1-5.gif';
				break;
			case 'point2':
				$image	= JURI::root() . 'components/com_community/templates/default/images/karma-2-5.gif';
				break;
			case 'point3':
				$image	= JURI::root() . 'components/com_community/templates/default/images/karma-3-5.gif';
				break;
			case 'point4':
				$image	= JURI::root() . 'components/com_community/templates/default/images/karma-4-5.gif';
				break;
			case 'point5':
				$image	= JURI::root() . 'components/com_community/templates/default/images/karma-5-5.gif';
				break;
			default:
				$image	= JURI::root() . 'components/com_community/templates/default/images/karma-0-5.gif';
				break;			
		}
		return $image;
	}
	
	function setToolBar()
	{
		// Get the toolbar object instance
		$bar =& JToolBar::getInstance('toolbar');

		// Set the titlebar text
		JToolBarHelper::title( JText::_( 'CC CONFIGURATION' ), 'configuration');
		
		// Add the necessary buttons
		JToolBarHelper::back( JText::_('CC HOME') , 'index.php?option=com_community');
		JToolBarHelper::divider();
		JToolBarHelper::save( '' );
		JToolBarHelper::cancel();
	}
	
	function getEditors()
	{
		$db		=& JFactory::getDBO();
		
		// compile list of the editors
		$query = 'SELECT element AS value, name AS text'
				. ' FROM #__plugins'
				. ' WHERE folder = "editors"'
				. ' AND published = 1'
				. ' ORDER BY ordering, name';
		$db->setQuery( $query );
		$editors = $db->loadObjectList();
		
		return $editors;
	}
	
	function getFieldCodes( $elementName , $selected = '' )
	{
		$db		=& JFactory::getDBO();
		$query	= 'SELECT DISTINCT fieldcode FROM #__community_fields';
		$db->setQuery( $query );
		$fieldcodes	= $db->loadObjectList();
		
		$html		= '<select name="'. $elementName . '">';
		
		foreach( $fieldcodes as $fieldcode )
		{
			if( !empty($fieldcode->fieldcode ) )
			{
				$selectedData	= '';
	
				if( $fieldcode->fieldcode == $selected )
				{
					$selectedData	= ' selected="true"';
				}
				$html	.= '<option value="' . $fieldcode->fieldcode . '"' . $selectedData . '>' . $fieldcode->fieldcode . '</option>';
			}
		}
		$html	.= '</select>';
		
		return $html;
	}
	
	function getFolderPermissionsPhoto( $name , $selected )
	{		
		$all		= ( $selected == '0777' ) ? 'checked="true" ' : '';
		$default	= ( $selected == '0755' ) ? 'checked="true" ' : '';

		$html	 = '<input type="radio" value="0777" name="' . $name . '" ' . $all . '/> ' . JText::_('CC CHMOD777');
		$html	.= '<input type="radio" value="0755" name="' . $name . '" ' . $default . '/> ' . JText::_('CC SYSTEM DEFAULT');

		return $html;
	}
	
	function getFolderPermissionsVideo( $name , $selected )
	{		
		$all		= ( $selected == '0777' ) ? 'checked="true" ' : '';
		$default	= ( $selected == '0755' ) ? 'checked="true" ' : '';

		$html	 = '<input type="radio" value="0777" name="' . $name . '" ' . $all . '/> ' . JText::_('CC CHMOD777');
		$html	.= '<input type="radio" value="0755" name="' . $name . '" ' . $default . '/> ' . JText::_('CC SYSTEM DEFAULT');

		return $html;
	}
}