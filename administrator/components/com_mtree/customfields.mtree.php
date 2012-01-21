<?php
/**
 * @version		$Id: customfields.mtree.php 908 2010-07-01 09:59:20Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */


defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mtree'.DS.'customfields.mtree.html.php' );

/***
* Custom Fields
*/

function manageftattachments($id, $option) {

	exit();
}

function uploadft( $option ) {
	global $mtconf, $mainframe;
	
	$database =& JFactory::getDBO();
	
	require_once( $mtconf->getjconf('absolute_path') . '/includes/domit/xml_domit_lite_include.php' );
	
	$files = JRequest::get('files');
	$filename = $files['userfile']['tmp_name'];

	if(!empty($filename)) {

		$xmlDoc = & JFactory::getXMLParser('Simple');
		if (!$xmlDoc->loadFile( $filename )) {
			return false;
		}
		
		if ($xmlDoc->document->name() != 'fieldtype') {
			return null;
		}
		
		$useelements = $xmlDoc->document->attributes('useelements');
		$usesize = $xmlDoc->document->attributes('usesize');
		$taggable = $xmlDoc->document->attributes('taggable');
		$version = $xmlDoc->document->version[0]->data();
		$website = $xmlDoc->document->website[0]->data();
		$desc = $xmlDoc->document->description[0]->data();
		$field_type = $xmlDoc->document->name[0]->data();
		$caption = $xmlDoc->document->caption[0]->data();
		$class = $xmlDoc->document->class[0]->data();

		if(empty($useelements)) { $useelements = 0;	}
		if(empty($usesize)) { $usesize = 0;	}
		if(empty($version)) { $version = '1.00'; }
		if( empty($field_type) || empty($caption) || empty($class) ) {
			return null;
		}

		$database->setQuery('SELECT ft_id FROM #__mt_fieldtypes WHERE field_type = ' . $database->quote($field_type) . ' LIMIT 1');
		$duplicate_ft_id = $database->loadResult();
		
		if( $duplicate_ft_id > 0 ) {
			$ft_id = saveft2( $field_type, $caption, $class, $useelements, $usesize, $taggable, $version, $website, $desc, $duplicate_ft_id );
			$database->setQuery( "DELETE FROM #__mt_fieldtypes_att WHERE ft_id = " . $ft_id );
			$database->query();
		} else {
			$ft_id = saveft2( $field_type, $caption, $class, $useelements, $usesize, $taggable, $version, $website, $desc );
		}
		
		if( isset($xmlDoc->document->attachments) ) {
			$attachmentsChildNodes = $xmlDoc->document->attachments[0]->children();
			$attachment = new mtFieldTypesAtt( $database );
			foreach($attachmentsChildNodes as $attachmentsChildNode) {

				$filename = $attachmentsChildNode->filename[0]->data();
				$filesize = $attachmentsChildNode->filesize[0]->data();
				$extension = $attachmentsChildNode->extension[0]->data();
				$ordering = $attachmentsChildNode->ordering[0]->data();
				$filedata = $attachmentsChildNode->filedata[0]->data();

				$database->setQuery( 'INSERT INTO #__mt_fieldtypes_att (ft_id, filename, filedata, filesize, extension, ordering) '
					. ' VALUES('
					. $database->quote($ft_id) . ', '
					. $database->quote($filename) . ', '
					. $database->quote(base64_decode($filedata)) . ', '
					. $database->quote($filesize) . ', '
					. $database->quote($extension) . ', '
					. '\'9999\')'
					);
				$database->query();
				$attachment->reorder('ft_id='.$ft_id);
			}
		}
		
		if( is_null($duplicate_ft_id) ) {
			# Create an unpublished custom field for the new field type
			$database->setQuery('INSERT INTO #__mt_customfields (field_type, caption, published, ordering, advanced_search, simple_search, iscore)'
				.	"\n VALUES(" . $database->quote($field_type) . ", " . $database->quote($caption) . ", '0', '99', '0', '0', '0')");
			$database->query();

			$row = new mtCustomFields( $database );
			$row->reorder( 'published >= 0' );
			
			$mainframe->redirect('index.php?option=com_mtree&task=managefieldtypes',JText::_( 'Field type installation success' ));
		} else {
			$mainframe->redirect('index.php?option=com_mtree&task=managefieldtypes',JText::_( 'Field type upgraded successfully' ));
		}
		
	}
	
}

function downloadft( $ft_id, $option ) {
	$database	=& JFactory::getDBO();
	
	$database->setQuery('SELECT * FROM #__mt_fieldtypes AS ft LEFT JOIN #__mt_fieldtypes_info AS fti ON fti.ft_id=ft.ft_id WHERE ft.ft_id = ' . $ft_id . ' LIMIT 1');
	$row = $database->loadObject();

	$database->setQuery('SELECT * FROM #__mt_fieldtypes_att AS fta WHERE fta.ft_id = ' . $ft_id);
	$attachments = $database->loadObjectList();

	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
	header("Content-Type: application/xml");
	header("Content-Disposition: attachment; filename=mFieldType_" . $row->field_type . "-" . $row->ft_version . ".xml");
	// header('Content-transfer-encoding: binary');
	
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";

	echo "\n\n<fieldtype useelements=\"" . $row->use_elements . "\" usesize=\"" . $row->use_size . "\" taggable=\"" . $row->taggable . "\" >";
	echo "\n\t<name>" . $row->field_type . "</name>";
	echo "\n\t<caption><![CDATA[" . $row->ft_caption . "]]></caption>";
	echo "\n\t<class><![CDATA[" . $row->ft_class . "]]></class>";
	echo "\n\t<version>" . $row->ft_version . "</version>";
	echo "\n\t<website><![CDATA[" . $row->ft_website . "]]></website>";
	echo "\n\t<description><![CDATA[" . $row->ft_desc . "]]></description>";
	if(count($attachments)>0) {
		echo "\n\t<attachments>";
		foreach($attachments AS $attachment) {
			echo "\n\t\t<attachment>";
			echo "\n\t\t\t<filename>" . $attachment->filename . "</filename>";
			echo "\n\t\t\t<filesize>" . $attachment->filesize . "</filesize>";
			echo "\n\t\t\t<extension>" . $attachment->extension . "</extension>";
			echo "\n\t\t\t<ordering>" . $attachment->ordering . "</ordering>";
			echo "\n\t\t\t<filedata><![CDATA[" . base64_encode($attachment->filedata) . "]]></filedata>";
			echo "\n\t\t</attachment>";
		}
		echo "\n\t</attachments>";
	}
	echo "\n</fieldtype>";
	exit();
}

function editft( $ft_id, $option ) {
	$database	=& JFactory::getDBO();
	
	if( $ft_id > 0 ) {
		$database->setQuery('SELECT ft.*, fti.ft_version, fti.ft_website, fti.ft_desc FROM #__mt_fieldtypes AS ft LEFT JOIN #__mt_fieldtypes_info AS fti ON fti.ft_id=ft.ft_id WHERE ft.ft_id = ' . $database->quote($ft_id) );
		$row = $database->loadObject();
		$database->setQuery('SELECT * FROM #__mt_fieldtypes_att WHERE ft_id = ' . $database->quote($ft_id) . ' ORDER BY ordering ASC');
		$attachments = $database->loadObjectList();
	} else {
		$row->ft_id = 0;
		$row->field_type = '';
		$row->ft_caption = '';
		$row->ft_class = '';
		$row->use_elements = '0';
		$row->use_size = '0';
		$row->taggable = '0';
		$row->iscore = '0';
		$row->ft_version = '1';
		$row->ft_website = 'http://';
		$row->ft_desc = '';
		$attachments = array();
	}
	HTML_mtcustomfields::editft( $row, $attachments, $option );
	
}

function saveft( $ft_id, $option ) {
	global $mainframe;

	$database	=& JFactory::getDBO();

	$field_type		= JRequest::getWord( 'field_type', '' );
	$class 			= JRequest::getVar( 'ft_class', '', 'post', 'none', 2 );
	$website 		= JRequest::getString( 'ft_website', '' );
	$desc 			= JRequest::getString( 'ft_desc', '' );
	$caption 		= JRequest::getString( 'ft_caption', '' );
	$useatt 		= @JRequest::getVar( 'useatt', array() );
	$use_elements 	= JRequest::getInt( 'use_elements', '' );
	$use_size 		= @JRequest::getInt( 'use_size', '' );
	$taggable 		= @JRequest::getInt( 'taggable', '' );
	$ft_version		= @JRequest::getCmd( 'ft_version', 1 );
	
	$attachment = new mtFieldTypesAtt( $database );

	if( $ft_id > 0 ) {
		if(count($useatt) > 0) {
			$database->setQuery('DELETE FROM #__mt_fieldtypes_att WHERE ft_id = ' . $database->quote($ft_id) . ' AND fta_id NOT IN (' . implode(',',$useatt) . ')');
		} else {
			$database->setQuery('DELETE FROM #__mt_fieldtypes_att WHERE ft_id = ' . $database->quote($ft_id));
		}
		$database->query();
		
		$attachment->reorder('ft_id='.$ft_id);
		
		$database->setQuery('UPDATE #__mt_fieldtypes SET field_type = ' . $database->quote($field_type) . ', ft_caption = ' . $database->quote($caption) . ', ft_class = ' . $database->quote($class) . ', use_elements = ' . $database->quote($use_elements) . ', use_size = ' . $database->quote($use_size) . ', taggable = ' . $database->quote($taggable) . ' WHERE ft_id = ' . $database->quote($ft_id) . ' LIMIT 1');
		$database->query();
		$database->setQuery('UPDATE #__mt_fieldtypes_info SET ft_version = ' . $database->quote($ft_version) . ', ft_website = ' . $database->quote($website) . ', ft_desc = ' . $database->quote($desc) . ' WHERE ft_id = ' . $database->quote($ft_id) . ' LIMIT 1');
		$database->query();
	} else {
		$ft_id = saveft2( $field_type, $caption, $class, $use_elements, $use_size, $taggable, $ft_version, $website, $desc );

		# Create an unpublished custom field for the new field type
		$database->setQuery('INSERT INTO #__mt_customfields (field_type, caption, published, ordering, advanced_search, simple_search, tag_search, iscore)'
			.	' VALUES(' . $database->quote($field_type) . ', ' . $database->quote($caption) . ', \'0\', \'99\', \'0\', \'0\', \'' . $taggable . '\', \'0\')');
		$database->query();
	}
	
	// $database->setQuery('SET GLOBAL max_allowed_packet =10485760');
	// $database->query();
	
	$files = JRequest::get('files');
	if(array_key_exists('attachment',$files)) {
		for($i=0;$i<count($files['attachment']['name']);$i++) {
			if ( !empty($files['attachment']['name'][$i]) && $files['attachment']['error'][$i] == 0 &&  $files['attachment']['size'][$i] > 0 ) {
				$data = fread(fopen($files['attachment']['tmp_name'][$i], "r"), $files['attachment']['size'][$i]);
		
				$database->setQuery( 'INSERT INTO #__mt_fieldtypes_att (ft_id, filename, filedata, filesize, extension, ordering) '
					. ' VALUES('
					. $database->quote($ft_id) . ', '
					. $database->quote($files['attachment']['name'][$i]) . ', '
					. $database->quote($data) . ', '
					. $database->quote($files['attachment']['size'][$i]) . ', '
					. $database->quote($files['attachment']['type'][$i]) . ', '
					. '\'9999\')'
					);
				$database->query();
				$attachment->reorder('ft_id='.$ft_id);
				
			}
		}
	}
	
	$row = new mtCustomFields( $database );
	$row->reorder( 'published >= 0' );
	
	$task = JRequest::getCmd( 'task', '', 'post');
	if ( $task == "applyft" ) {
		$mainframe->redirect( "index.php?option=$option&task=editft&cfid=$ft_id" );
	} else {
		$mainframe->redirect( "index.php?option=$option&task=managefieldtypes" );
	}
	
}

function saveft2( $field_type, $caption, $class, $useelements, $usesize, $taggable, $version, $website, $desc, $ft_id=0 ) {
	$database	=& JFactory::getDBO();

	if($ft_id == 0) {
		$isNew = true;
	} else {
		$isNew = false;
	}
	if($isNew) {
		$sql = 'INSERT INTO #__mt_fieldtypes (field_type,ft_caption,ft_class,use_elements,use_size,taggable) ';
		$sql .=	'VALUES('
			.	'\'' . $database->getEscaped($field_type) . '\','
			.	'\'' . $database->getEscaped($caption) . '\','
			.	'\'' . $database->getEscaped($class) . '\','
			.	'\'' . $database->getEscaped($useelements) . '\','
			.	'\'' . $database->getEscaped($usesize) . '\','
			.	'\'' . $database->getEscaped($taggable) . '\''
			.	')';
	} else {
		$sql = 'UPDATE #__mt_fieldtypes SET ';
		$sql .= 'field_type = \'' . $database->getEscaped($field_type) . '\'';
		$sql .= ', ft_caption = \'' . $database->getEscaped($caption) . '\'';
		$sql .= ', ft_class = \'' . $database->getEscaped($class) . '\'';
		$sql .= ', use_elements = \'' . $database->getEscaped($useelements) . '\'';
		$sql .= ', use_size = \'' . $database->getEscaped($usesize) . '\'';
		$sql .= ', taggable = \'' . $database->getEscaped($taggable) . '\'';
		$sql .= ' WHERE ft_id = ' . $ft_id . ' LIMIT 1';
	}
	$database->setQuery( $sql );
	$database->query();
	if($isNew) {
		$ft_id = $database->insertid();
	}
	
	$website = ($website == 'http://') ? '' : $database->getEscaped($website);
	if($isNew) {
		$sql = 'INSERT INTO #__mt_fieldtypes_info (ft_id,ft_version,ft_website,ft_desc) ';
		$sql .= 'VALUES('
			.	'\'' . $ft_id . '\','
			.	'\'' . $database->getEscaped($version) . '\','
			.	'\'' . $database->getEscaped($website) . '\','
			.	'\'' . $database->getEscaped($desc) . '\''
			.	')';
	} else {
		$sql = 'UPDATE #__mt_fieldtypes_info SET '
			.	'ft_version = \'' . $database->getEscaped($version) . '\','
			.	'ft_website = \'' . $database->getEscaped($website) . '\','
			.	'ft_desc = \'' . $database->getEscaped($desc) . '\''
			.	' WHERE ft_id = ' . $ft_id . ' LIMIT 1';		
	}
	$database->setQuery( $sql );
	$database->query();
	
	return $ft_id;
}

function removeft( $ft_id, $option ) {
	global $mainframe;
	
	$database	=& JFactory::getDBO();
	
	# Get field_type value
	$database->setQuery('SELECT field_type, iscore FROM #__mt_fieldtypes WHERE ft_id = ' . $ft_id . ' LIMIT 1');
	$fieldtype = $database->loadObject();
	$field_type = $fieldtype->field_type;
	
	if($fieldtype->iscore) {
		$mainframe->redirect("index.php?option=$option&task=managefieldtypes",JText::_( 'Cannot delete core fieldtype' ));
	} else {
		if(!empty($field_type)) {

			# Get cf_id(s) that uses this field type
			$database->setQuery('SELECT cf_id FROM #__mt_customfields WHERE field_type = ' . $database->quote($field_type) );
			$cf_ids = $database->loadResultArray();

			if(count($cf_ids)>0) {
				# Delete attachments
				$database->setQuery('DELETE FROM #__mt_cfvalues_att WHERE cf_id IN (' . implode(',',$cf_ids) . ')');
				$database->query();		

				# Delete values the uses this field type
				$database->setQuery('DELETE FROM #__mt_cfvalues WHERE cf_id IN (' . implode(',',$cf_ids) . ')');
				$database->query();		

				# Delete instances of this field type
				$database->setQuery('DELETE FROM #__mt_customfields WHERE cf_id IN (' . implode(',',$cf_ids) . ') LIMIT ' . count($cf_ids));
				$database->query();		
			}

			# Delete attachments
			$database->setQuery('DELETE FROM #__mt_fieldtypes_att WHERE ft_id = ' . $ft_id);
			$database->query();		

			# Delete field type's information
			$database->setQuery('DELETE FROM #__mt_fieldtypes_info WHERE ft_id = ' . $ft_id . ' LIMIT 1');
			$database->query();		

			# Delete field type itself
			$database->setQuery('DELETE FROM #__mt_fieldtypes WHERE ft_id = ' . $ft_id . ' LIMIT 1');
			$database->query();		

		} 
		$mainframe->redirect("index.php?option=$option&task=managefieldtypes");
	}
}

function cancelft( $option ) {
	global $mainframe;
	$mainframe->redirect( 'index.php?option='. $option .'&task=managefieldtypes' );
}

function managefieldtypes( $option ) {
	$database	=& JFactory::getDBO();
	
	$database->setQuery( "SELECT ft.*, fti.ft_version, fti.ft_website, fti.ft_desc FROM #__mt_fieldtypes AS ft LEFT JOIN #__mt_fieldtypes_info AS fti ON fti.ft_id = ft.ft_id ORDER BY iscore ASC, ft_caption ASC" );
	$rows = $database->loadObjectList();
	
	HTML_mtcustomfields::managefieldtypes( $option, $rows );
}

function customfields( $option ) {
	global $mainframe, $mtconf;
	
	$database =& JFactory::getDBO();
	
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mtconf->getjconf('list_limit') );
	$limitstart = $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 );

 	$database->setQuery( 'SELECT COUNT(*) FROM #__mt_customfields');
	$total = $database->loadResult();
	
	jimport('joomla.html.pagination');
	$pageNav = new JPagination($total, $limitstart, $limit);
	
	$database->setQuery( 'SELECT cf.*, ft.ft_caption FROM #__mt_customfields AS cf '
		.	'LEFT JOIN #__mt_fieldtypes AS ft ON ft.field_type = cf.field_type '
		.	'ORDER BY ordering ASC'
		. "\nLIMIT $pageNav->limitstart,$pageNav->limit");
	$custom_fields = $database->loadObjectList();
	HTML_mtcustomfields::customfields( $custom_fields, $pageNav, $option );
}

function editcf( $cf_id, $option ) {
	global $mtconf;
	
	$database	=& JFactory::getDBO();
	
	$row = new mtCustomFields( $database );
	$row->load( $cf_id );
	$params = null;

	if ($row->cf_id == 0) {
		$row->caption = '';
		$row->field_type = 'text';
		$row->cat_id = 0;
		$row->ordering = 0;
		$row->hidden = 0;
		$row->published = 1;
		$row->size = 30;
		$row->hide_caption = 0;
		$row->advanced_search = 0;
		$row->simple_search = 0;
		$row->search_caption = '';
		$row->details_view=1;
		$row->summary_view=0;
		$row->tag_search=0;
		
	} else {
		$database->setQuery("SELECT COUNT(fta.fta_id) FROM #__mt_fieldtypes_att AS fta "
			.	"LEFT JOIN #__mt_fieldtypes AS ft ON ft.ft_id=fta.ft_id "
			.	"WHERE ft.field_type = " . $database->quote($row->field_type) . " AND fta.filename = " . $database->quote($mtconf->get('params_xml_filename')) . " LIMIT 1"
		);

		if($database->loadResult() == 1) {
			# Parameters
			require_once( $mtconf->getjconf('absolute_path') . '/administrator/components/com_mtree/include/parameter.php' );
			
			$database->setQuery("SELECT fta.filedata FROM #__mt_fieldtypes_att AS fta "
				.	"LEFT JOIN #__mt_fieldtypes AS ft ON ft.ft_id=fta.ft_id "
				.	"WHERE ft.field_type = '" . $database->getEscaped($row->field_type) . "' AND fta.filename = 'params.xml' LIMIT 1");
			$xmlText = $database->loadResult();
			$params = new MParameter( $row->params, $xmlText );
		}
	}

	$lists = array();

	# build the html select list for ordering
	$order = JHTML::_('list.genericordering', 'SELECT ordering AS value, caption AS text'
		. "\nFROM #__mt_customfields ORDER BY ordering ASC"	);
	$lists['ordering'] = JHTML::_('select.genericlist', $order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval( $row->ordering ) );
	
	# Generate the Field Types
	$cf_types = array (
		'text' => JText::_( 'Field type text' ),
		// 'multitext' => JText::_( 'Field type multitext' ),
		'selectlist' => JText::_( 'Field type selectlist' ),
		'selectmultiple' => JText::_( 'Field type selectmultiple' ),
		'checkbox' => JText::_( 'Field type checkbox' ),
		'radiobutton' => JText::_( 'Field type radiobutton' )
		);
	# Get custom field types
	$database->setQuery("SELECT * FROM #__mt_fieldtypes WHERE iscore = '0' ORDER BY ft_caption ASC");
	$custom_cf_types = $database->loadObjectList('field_type');

	$lists["field_types"] = '<select name="field_type" onchange="updateInputs(this.value)">';
	$lists["field_types"] .= '<optgroup label="' . JText::_( 'Basic fieldtypes' ) . '">';
	foreach( $cf_types AS $key => $value ) {
		$lists["field_types"] .= '<option value="' . $key . '"' . (($row->field_type == $key)?' selected':'') . '>' . $value . '</option>';
	}
	$lists["field_types"] .= '</optgroup>';
	$lists["field_types"] .= '<optgroup label="' . JText::_( 'Custom fieldtypes' ) . '">';
	foreach( $custom_cf_types AS $key => $value ) {
		$lists["field_types"] .= '<option value="' . $key . '"' . (($row->field_type == $key)?' selected':'') . '>' . $value->ft_caption . '</option>';
	}
	$lists["field_types"] .= '</optgroup>';
	$lists["field_types"] .= '</select>';
	
	if($row->field_type == 'coreuser') {
		$lists['advanced_search'] = JHTML::_('select.booleanlist', 'advanced_search', 'class="inputbox" disabled', 0);
	} else {
		$lists['advanced_search'] = JHTML::_('select.booleanlist', 'advanced_search', 'class="inputbox"', (($row->advanced_search == 1) ? 1 : 0));
	}
	
	$lists['tag_search'] = JHTML::_('select.booleanlist', 'tag_search', 'class="inputbox"', (($row->tag_search == 1) ? 1 : 0));
	$lists['simple_search'] = JHTML::_('select.booleanlist', 'simple_search', 'class="inputbox"', (($row->simple_search == 1) ? 1 : 0));

	$lists['details_view'] = JHTML::_('select.booleanlist', 'details_view', 'class="inputbox"'.(($cf_id=='1')?' disabled':''), (($row->details_view == 1) ? 1 : 0));
	$lists['summary_view'] = JHTML::_('select.booleanlist', 'summary_view', 'class="inputbox"'.(($cf_id=='1')?' disabled':''), (($row->summary_view == 1) ? 1 : 0));
	
	if( in_array($row->cf_id,array(1)) ) {
		$lists['required_field'] = JHTML::_('select.booleanlist', 'required_field', 'class="inputbox" disabled', '1');
	} elseif ( in_array($row->cf_id,array(3,14,15,16,17,18,19,20,21,22,26,27)) ) {
		$lists['required_field'] = JHTML::_('select.booleanlist', 'required_field', 'class="inputbox" disabled', '0');
	} else {
		$lists['required_field'] = JHTML::_('select.booleanlist', 'required_field', 'class="inputbox"', (($row->required_field == 1) ? 1 : 0));
	}
	$lists['hidden'] = JHTML::_('select.booleanlist', 'hidden', 'class="inputbox"', (($row->hidden == 1) ? 1 : 0));
	$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', (($row->published == 1) ? 1 : 0));
	
	# make order list
	$orders = JHTML::_('list.genericordering', 'SELECT ordering AS value, caption AS text'
		. "\nFROM #__mt_customfields ORDER BY ordering"	);
	$lists["order"] = JHTML::_('select.genericlist', $orders, 'ordering', 'class="text_area" size="1"', 'value', 'text', intval( $row->ordering ) );

	HTML_mtcustomfields::editcf( $row, $custom_cf_types, $lists, $params, $option );
}

function savecf( $option ) {
	global $mainframe;
	
	$database	=& JFactory::getDBO();
	$row 		= new mtCustomFields( $database );
	
	$hide_caption = JRequest::getInt( 'hide_caption', 0 );

	$params = JRequest::getVar( 'params', '', 'post', 'array');
	$post	= JRequest::get( 'post' );
	$post['prefix_text_mod'] = JRequest::getVar('prefix_text_mod', '', 'POST', 'string', JREQUEST_ALLOWHTML);
	$post['suffix_text_mod'] = JRequest::getVar('suffix_text_mod', '', 'POST', 'string', JREQUEST_ALLOWHTML);
	$post['prefix_text_display'] = JRequest::getVar('prefix_text_display', '', 'POST', 'string', JREQUEST_ALLOWHTML);
	$post['suffix_text_display'] = JRequest::getVar('suffix_text_display', '', 'POST', 'string', JREQUEST_ALLOWHTML);

	if( !array_key_exists('hide_caption', $post) || $post['hide_caption'] != '1' )  {
		$post['hide_caption'] = 0;
	}

 	// Save parameters
	$params = JRequest::getVar( 'params', array(), 'post', 'array');

	if ( is_array( $params ) ) {
		$attribs = array();
		foreach ( $params as $k=>$v) {
			$attribs[] = "$k=$v";
		}
		$row->params = implode( "\n", $attribs );
		unset($post['params']);
	}
	
	if (!$row->bind( $post )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if( $row->cf_id == 0 )
	{
		// Set the tag_search colum based on taggable value of the field type
		$database->setQuery( 'SELECT taggable FROM #__mt_fieldtypes WHERE field_type = ' . $database->Quote($row->field_type) . ' LIMIT 1' );
		$row->tag_search = $database->loadResult();
	}
	
	# Successively remove '|' at the start and end to eliminate blank options
	while (substr($row->field_elements, -1) == '|') {
		$row->field_elements = substr($row->field_elements, 0, -1);
	}
	while (substr($row->field_elements, 0, 1) == '|') {
		$row->field_elements = substr($row->field_elements, 1);
	}

	# Clean up Field Elements's data. Remove spaces around '|' so that it is used correctly in SET COLUMN in MySQL
	$tmp_fe_array = explode('|',$row->field_elements);
	foreach($tmp_fe_array AS $tmp_fe) {
		# Detect usage of comma.
		if (strrpos($tmp_fe,',') == FALSE) 
		{
			$tmp_fe_array2[] = trim($tmp_fe);
		} else {
			echo "<script> alert('".JText::_( 'Warning commas are not allowed in field elements' )."'); window.history.go(-1); </script>\n";
			exit();
		}
	}
	$row->field_elements = implode('|',$tmp_fe_array2);

	# Put new item to last
	if($row->cf_id <= 0) $row->ordering = 999;

	# Check if field_type is taggable. If yes, we set 1 to tag_search for this custom field
	/*
	$database->setQuery( 'SELECT taggable FROM #__mt_fieldtypes WHERE field_type = ' . $database->Quote($row->field_type) . ' LIMIT 1' );
	$taggable = $database->loadResult();
	
	if( $taggable == 1 ) {
		$row->tag_search = 1;
	} else {
		$row->tag_search = 0;
	}
	*/
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->reorder( 'published >= 0' );

	$task = JRequest::getCmd( 'task', '', 'post');

	if ( $task == "applycf" ) {
		$mainframe->redirect( "index.php?option=$option&task=editcf&cfid=" . $row->cf_id );
	} else {
		$mainframe->redirect( "index.php?option=$option&task=customfields" );
	}

}

function ordercf( $cf_id, $inc, $option ) {
	global $mainframe;
	
	$database	=& JFactory::getDBO();
	
	$row = new mtCustomFields( $database );
	$row->load( $cf_id );
	$row->move( $inc, '' );
	$mainframe->redirect( 'index.php?option='. $option .'&task=customfields' );
}

function cf_publish( $cf_id, $publish=1 ,$option ) {
	global $mainframe;

	$database	=& JFactory::getDBO();

	if (!is_array( $cf_id ) || count( $cf_id ) < 1) {
		echo "<script> alert('".JText::_( 'Please select an item to publish or unpublish' )."'); window.history.go(-1);</script>\n";
		exit();
	}

	$ids = implode( ',', $cf_id );

	$database->setQuery( 'UPDATE #__mt_customfields SET published = ' . intval($publish) . " WHERE cf_id IN ($ids) AND cf_id NOT IN (1)" );
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$mainframe->redirect( "index.php?option=$option&task=customfields" );

}

function removecf( $id, $option ) {
	global $mainframe;

	$database	=& JFactory::getDBO();

	for ($i = 0; $i < count($id); $i++) {
		$query = "SELECT iscore FROM #__mt_customfields WHERE cf_id='" . intval($id[$i]) . "' LIMIT 1";
		$database->setQuery($query);
		
		if(($iscore = $database->loadResult()) == null) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		
		if ($iscore == 1) {
			$mainframe->redirect( "index.php?option=$option&task=customfields", JText::_( 'Cannot delete core field' ) );
		} else {
			# Delete the main fields data
			$database->setQuery("DELETE FROM #__mt_customfields WHERE `cf_id`='".intval($id[$i])."'");
			$database->query();

			# Delete the data associated with this field
			$database->setQuery("DELETE FROM #__mt_cfvalues WHERE `cf_id`='".intval($id[$i])."'");
			$database->query();
			
			# Delete the data associated with this field
			$database->setQuery("DELETE FROM #__mt_cfvalues_att WHERE `cf_id`='".intval($id[$i])."'");
			$database->query();
			
		}
	}
	$mainframe->redirect("index.php?option=$option&task=customfields");
}

function cancelcf( $option ) {
	global $mainframe;
	$mainframe->redirect( 'index.php?option='. $option .'&task=customfields' );
}

?>