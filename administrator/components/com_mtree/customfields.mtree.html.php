<?php
/**
 * @version		$Id: customfields.mtree.html.php 876 2010-05-21 11:52:19Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

class HTML_mtcustomfields {

	function editft( $row, $attachments, $option ) {
		global $mtconf;
		
		if( $row->ft_id == 0 ) {
	?>
	<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm2">
	<table class="adminform">
	<tr><th><?php echo JText::_( 'Upload package file' ) ?></th></tr>
	<tr>
		<td align="left">
		<?php echo JText::_( 'Package file' ) ?>:
		<input class="text_area" name="userfile" type="file" size="70"/>
		<input class="button" type="submit" value="<?php echo JText::_( 'Upload file and install' ) ?>" />
		</td>
	</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option ?>"/>
	<input type="hidden" name="task" value="uploadft"/>
	</form>
	<p />
	<?php } ?>
	<script language="javascript" type="text/javascript">
	var attCount=1;
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton=='cancelft') {
			submitform( pressbutton );
			return;
		}
		if (form.field_type.value==""||form.ft_caption.value==""||form.ft_class.value==""){
			alert( "<?php echo JText::_( 'Please complete the field type name caption and code before saving' ) ?>" );
		} else {
			submitform( pressbutton );
		}
	}
	function gebid(id) {return document.getElementById(id);}

	function addAtt() {
		var newLi = document.createElement("LI");
		newLi.id="att"+attCount;
		var newFile=document.createElement("INPUT");
		newFile.style.marginRight="5px";
		newFile.className="text_area";
		newFile.name="attachment[]";
		newFile.type="file";
		newFile.size="30";
		newLi.appendChild(newFile);
		var newLink=document.createElement("A");
		newLink.href="javascript:remAtt("+attCount+")";

		removeText=document.createTextNode("remove");
		newLink.appendChild(removeText);


		newLi.appendChild(newLink);
		gebid('upload_att').appendChild(newLi);
		attCount++;
	}
	function remAtt(id) {gebid('upload_att').removeChild(gebid('att'+id));}
	</script>
	<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm">
	
	<?php if( $row->ft_id == 0 ) { ?>
	<table class="adminheading"><tr><th><?php echo JText::_( 'Or create a new field type' ) ?></th></tr></table>
	<?php } else { ?>
	<table class="adminheading"><tr><th><?php echo JText::_( 'Edit field type' ) ?></th></tr></table>
	<?php } ?>
	<table width="100%" class="adminform">
		<tr><th colspan="2"><?php echo JText::_( 'Field type details' ) ?></th></tr>
		<tr>
			<td width="15%"><?php echo JText::_( 'Name of the field type' ) ?>:</td>
			<td width="85%"><?php 
			if( $row->ft_id == 0 ) { 
			?><input type="text" name="field_type" size="30" value="<?php echo $row->field_type ?>" class="text_area" /><?php
			} else {
				echo $row->field_type;
				?><input type="hidden" name="field_type" value="<?php echo $row->field_type ?>" /><?php
			}
			?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_( 'Caption' ) ?>:</td>
			<td><input type="text" name="ft_caption" size="30" value="<?php echo $row->ft_caption ?>" class="text_area" /></td>
		</tr>
		<tr>
			<td valign="top"><?php echo JText::_( 'Php class code' ) ?>:</td>
			<td><textarea cols="80" rows="20" name="ft_class" class="text_area"><?php echo htmlentities($row->ft_class) ?></textarea></td>
		</tr>
		<tr>
			<td><?php echo JText::_( 'Uses elements' ); ?></td>
			<td><?php echo JHTML::_('select.booleanlist', 'use_elements', 'class="inputbox"'.(($row->iscore=='1')?' disabled':''), (($row->use_elements == 1) ? 1 : 0)); ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_( 'Uses size' ) ?></td>
			<td><?php echo JHTML::_('select.booleanlist', 'use_size', 'class="inputbox"'.(($row->iscore=='1')?' disabled':''), (($row->use_size == 1) ? 1 : 0)); ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_( 'Taggable' ) ?></td>
			<td><?php echo JHTML::_('select.booleanlist', 'taggable', 'class="inputbox"'.(($row->iscore=='1')?' disabled':''), (($row->taggable == 1) ? 1 : 0)); ?></td>
		</tr>
		<tr>
			<td><?php echo JText::_( 'Version' ) ?>:</td>
			<td><input type="text" name="ft_version" size="10" value="<?php echo $row->ft_version ?>" class="text_area"<?php echo ($row->iscore)?' disabled':'' ?> /></td>
		</tr>
		<tr>
			<td><?php echo JText::_( 'Website' ) ?>:</td>
			<td><input type="text" name="ft_website" size="50" value="<?php echo $row->ft_website ?>" class="text_area"<?php echo ($row->iscore)?' disabled':'' ?> /></td>
		</tr>
		<tr>
			<td valign="top"><?php echo JText::_( 'Description' ) ?>:</td>
			<td><textarea cols="80" rows="4" name="ft_desc" class="text_area"<?php echo ($row->iscore)?' disabled':'' ?>><?php echo $row->ft_desc ?></textarea></td>
		</tr>
		<tr>
			<td valign="top"><?php echo JText::_( 'Attachments' ) ?>:</td>
			<td valign="top"><ol style="margin:0 15px;padding:0" id="upload_att"><?php
			foreach( $attachments AS $attachment ) {
				echo '<li style="margin-bottom:2px;">';
				echo ' <input type="checkbox" name="useatt[]" value="' . $attachment->fta_id . '" checked />&nbsp;';
				echo '<a href="' . JRoute::_( JURI::root().'index.php?option=com_mtree&task=att_download&ft=' . $row->field_type . '&o=' . $attachment->ordering ) . '" target="_blank">' . $attachment->filename . '</a>';
				echo '&nbsp;';
				if( $attachment->filesize < 1024 ) {
					echo $attachment->filesize . ' bytes';
				}  elseif( $attachment->filesize < 1048576 ) {
					echo round($attachment->filesize / 1024) . ' KB';
				} else {
					echo round($attachment->filesize / 1048576) . ' MB';
				}
				echo '</li>';
			}
			?>
			</ol>
			<br />
			<a href="javascript:addAtt();" id="add_att">Add an attachment</a>
			</td>
		</tr>
	</table>

	<input type="hidden" name="option" value="<?php echo $option ?>"/>
	<input type="hidden" name="task" value="saveft"/>
	<input type="hidden" name="id" value="<?php echo $row->ft_id ?>"/>
	</form>
	<?php
	}
	
	function managefieldtypes( $option, $rows ) {
	?>
	<div style="position:relative;top:5px;clear:both;text-align:left;margin-bottom:20px;"><img style="position:relative;top:4px;" src="../components/com_mtree/img/arrow_left.png" width="16" height="16" /> <a href="index.php?option=com_mtree&amp;task=customfields"><b><?php echo JText::_( 'Back to custom fields' ) ?></b></a></div>
	<form action="index.php" method="post" name="adminForm" id="adminForm">
		
	<table class="adminlist">
	<thead>
	<tr>
		<th width="25%" class="title"><?php echo JText::_( 'Field type' ) ?></th>
		<th width="35%" class="title"><?php echo JText::_( 'Description' ) ?></th>
		<th width="5%" align="center"><?php echo JText::_( 'Version' ) ?></th>
		<th width="20%" align="left"><?php echo JText::_( 'Website' ) ?></th>
		<th width="10%" align="left"><?php echo JText::_( 'Download xml' ) ?></th>
	</tr>
	</thead>
	<?php
	if(count($rows) > 0) {
		$rc = 0;
		for ($i = 0, $n = count( $rows ); $i < $n; $i++) {
			$row =& $rows[$i];
			?>
		<tr class="<?php echo "row$rc"; ?>">
			<td valign="top">
			<input type="radio" id="cb<?php echo $i;?>" name="cfid[]" value="<?php echo $row->ft_id; ?>" onclick="isChecked(this.checked);"><a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','editft')">
			<?php echo $row->ft_caption; ?></a></td>
			<td><?php 
			if($row->iscore) {
				echo '<b>' . JText::_( 'Core fieldtype' ) . '</b>';	
			} else {
				echo $row->ft_desc;	
			}
			?></td>
			<td><?php echo $row->ft_version; ?></td>
			<td><a href="<?php echo $row->ft_website; ?>" target="_blank"><?php echo $row->ft_website; ?></a></td>
			<td align="center"><a href="index.php?option=<?php echo $option; ?>&amp;task=downloadft&amp;cfid=<?php echo $row->ft_id; ?>&amp;format=raw"><?php echo JText::_( 'Download' ); ?></a></td>
		</tr>
			<?php 
			$rc = $rc == 0 ? 1 : 0;
		} 
	} else {
		echo '<tr><td colspan="5">No custom field type installed.</td></tr>';
	}
	?>
	<tfoot>
	<tr><th colspan="5"></th></tr>
	<tfoot>
	</table>
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value="managefieldtypes" />
	<input type="hidden" name="boxchecked" value="0" />
	</form>
	<?php
	}
	
	function customfields( $custom_fields, $pageNav, $option ) {
		global $mtconf;
	?>
	<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="adminheading">
		<tr><td>
			<a href="index.php?option=com_mtree&amp;task=managefieldtypes"><?php echo JText::_( 'Manage field types' ) ?></a>
		</td></tr>
	</table>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
		<thead>
		<th width="20"><?php echo JText::_( 'Id' ) ?></th>
		<th width="20"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $custom_fields ); ?>);" /></th>
		<th width="40%" align="left" nowrap><?php echo JText::_( 'Caption' ) ?></th>
		<th width="20%" align="left"><?php echo JText::_( 'Field type' ) ?></th>
		<th width="50" align="center" nowrap><?php echo JText::_( 'Advanced searchable' ) ?></th>
		<th width="50" align="center" nowrap><?php echo JText::_( 'Simple searchable' ) ?></th>
		<th width="50" align="center" nowrap><?php echo JText::_( 'Required' ) ?></th>

		<th width="50" align="center" nowrap><?php echo JText::_( 'Summary view' ) ?></th>
		<th width="50" align="center" nowrap><?php echo JText::_( 'Details view' ) ?></th>

		<th width="10%" align="center" nowrap><?php echo JText::_( 'Published' ) ?></th>
		<th width="4%" align="center" nowrap colspan="2"><?php echo JText::_( 'Ordering' ) ?></th>
		</thead>
	
		<?php
		$k = 0;
		for ($i=0, $n=count( $custom_fields ); $i < $n; $i++) {
			$row = &$custom_fields[$i];
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center"><?php echo $row->cf_id;?></td>
			<td>
				<input type="checkbox" id="cb<?php echo $i;?>" name="cfid[]" value="<?php echo $row->cf_id; ?>" onClick="isChecked(this.checked);" />
			</td>
			<td align="left">
				<a href="index.php?option=com_mtree&amp;task=editcf&amp;cfid=<?php echo $row->cf_id; ?>"><?php 
					if ( strlen($row->caption) > 55 ) {
						echo strip_tags(substr($row->caption, 0, 55))."...";
					} else {
						echo strip_tags($row->caption);
					}
				?></a>
			</td>
			<td><?php 
				if($row->iscore) {
					echo '<b>' . strtoupper(JText::_( 'Core' )) . '</b>';
				} else { 
					if( is_null($row->ft_caption) ) {
						echo JText::_( 'FIELD TYPE ' . strtoupper($row->field_type) );
					} else {
						echo $row->ft_caption;
					}
				} ?></td>
			<?php if ($row->hidden) { 
				?>
				<td align="center" colspan="5"><strong><?php echo JText::_( 'Hidden field' ) ?></strong></td>
				<?php
			} else { ?>
			<td align="center"><?php if ($row->advanced_search) { 
				echo '<img border="0" src="images/tick.png">';
			} else {
				echo '<img border="0" width="6" height="6" src="images/publish_x.png">';
			} 
			?></td>
			<td align="center"><?php if ($row->simple_search) { 
				echo '<img border="0" src="images/tick.png">';
			} else {
				echo '<img border="0" width="6" height="6" src="images/publish_x.png">';
			} 
			?></td>
			<td align="center"><?php if ($row->required_field) { 
				echo '<img border="0" src="images/tick.png">';
			} else {
				echo '<img border="0" width="6" height="6" src="images/publish_x.png">';
			} 
			?></td>
			
			<td align="center"><?php if ($row->summary_view) { 
				echo '<img border="0" src="images/tick.png">';
			} else {
				echo '<img border="0" width="6" height="6" src="images/publish_x.png">';
			} 
			?></td>
			<td align="center"><?php if ($row->details_view) { 
				echo '<img border="0" src="images/tick.png">';
			} else {
				echo '<img border="0" width="6" height="6" src="images/publish_x.png">';
			} 
			?></td>
			<?php
			
			}
			
				$task = $row->published ? 'cf_unpublish' : 'cf_publish';
				$img = $row->published ? 'publish_g.png' : 'publish_x.png';
			?>
			<td align="center">
				<?php if ($row->field_type <> 'corename') { ?>
				<a href="javascript:void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a>
				<?php } else {echo "<img src=\"images/publish_g.png\">";} ?>
			</td>
			<td class="order">
				<span><?php echo $pageNav->orderUpIcon( $i, true, 'cf_orderup' ); ?></span>
			</td>
			<td class="order">
				<span><?php echo $pageNav->orderDownIcon( $i, $n, true, 'cf_orderdown'  ); ?></span>
			</td>
			<!-- <td class="order" align="center">
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
			</td> -->
		</tr>
		<?php
			$k = 1 - $k;
		}
		?>
		<tfoot>
			<tr>
				<td colspan="12">
					<?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value="customfields" />
	<input type="hidden" name="boxchecked" value="0">
	</form>
	<?php
	}

	function editcf( $row, $custom_cf_types, $lists, $params, $option ) {
	?>
	<script language="javascript">
	<!--
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if(pressbutton=='cancelcf') {
			submitform(pressbutton);
			return;
		}
		if (form.caption.value == "") {
			alert( "<?php echo JText::_( 'Please fill in the fields caption' ) ?>" );
		} else if (form.iscore.value == "0" && ( form.field_type.value == "checkbox" || form.field_type.value == "selectlist" || form.field_type.value == "selectmultiple" || form.field_type.value == "radiobutton" ) && form.field_elements.value == "" ) {
			alert( "Please fill in the Field Elements." );
		} else {
			submitform( pressbutton );
		}
	}
	function updateInputs(ftype) {
		var f = document.adminForm;
		if (ftype=='selectlist'||ftype=='selectmultiple'||ftype=='checkbox'||ftype=='radiobutton'||ftype=='corecountry'||ftype=='corestate'||ftype=='corecity'<?php
		foreach( $custom_cf_types AS $custom_cf_type ) {
			if($custom_cf_type->use_elements) {	echo '||ftype==\'' . $custom_cf_type->field_type . '\''; }
		}
		?>) {
			f.field_elements.disabled=false;
			f.field_elements.style.backgroundColor='#FFFFFF'; 
		} else {
			f.field_elements.style.backgroundColor='#f5f5f5'; 
			f.field_elements.disabled=true;
		}

		if (ftype=='selectlist'||ftype=='selectmultiple'||ftype=='checkbox'||ftype=='radiobutton'||ftype=='corecountry'||ftype=='corestate'||ftype=='corecity'<?php
		foreach( $custom_cf_types AS $custom_cf_type ) {
			if($custom_cf_type->taggable) {	echo '||ftype==\'' . $custom_cf_type->field_type . '\''; }
		}
		?>) {
			f.tag_search[0].disabled=false;
			f.tag_search[1].disabled=false;
		} else {
			f.tag_search[0].disabled=true;
			f.tag_search[1].disabled=true;
		}
		
		if(ftype=='checkbox'||ftype =='radiobutton'<?php
		foreach( $custom_cf_types AS $custom_cf_type ) {
			if(!$custom_cf_type->use_size) {	echo '||ftype==\'' . $custom_cf_type->field_type . '\''; }
		}
		?>) {
			f.size.disabled=true;
		} else {
			f.size.disabled=false;
		}
	}
	-->
	</script>
	<form action="index.php" method="post" name="adminForm">
	<table>
		<tr>
			<td valign="top">
				
	<fieldset>
	<legend><?php echo JText::_( 'Custom field' ) ?></legend>
	<table width="100%" class="admintable">
		<tr>
			<td width="20%" class="key"><?php echo JText::_( 'Field type' ) ?>:</td>
			<td width="80%"><?php
			if( $row->iscore ) { 
				echo '<b>' . JText::_( 'Core field' ) . '</b>';
				echo '<input type="hidden" name="field_type" value="' . $row->field_type. '" />';
			} else { 
				echo $lists['field_types']; 
			}
			echo '<input type="hidden" name="iscore" value="' . $row->iscore . '" />';
			if( !$row->iscore && $row->cf_id == 0 ) {
				echo '<span style="background-color:white;margin-left:10px;">' . JText::_( 'Some fieldtype has params desc' ) . '</span>';
			}
			?></td>
		</tr>
		<tr>
			<td class="key" rowspan="2"><?php echo JText::_( 'Caption' ) ?>:</td>
			<td><input type="text" size="40" name="caption" class="text_area" value="<?php echo htmlspecialchars($row->caption) ?>" /></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="hide_caption" id="hide_caption" class="text_area" value="1"<?php echo ($row->hide_caption) ? ' checked' : '' ?> /> <label for="hide_caption"><?php echo JText::_( 'Hide caption' ) ?></label></td>
		</tr>
		<tr>
			<td valign="top" class="key"><?php echo JText::_( 'Field elements' ) ?>:</td>
			<td><textarea name="field_elements" rows="8" cols="50" class="text_area"><?php echo $row->field_elements ?></textarea>
				<br /><?php echo JText::_( 'Field elements note' ) ?></td>
		</tr>
		<tr>
			<td valign="top" rowspan="2" class="key"><?php echo JText::_( 'Prefix and suffix text to display during field modification' ) ?>:</td>
			<td><?php echo JText::_( 'Prefix' ) ?>: <input type="text" size="40" name="prefix_text_mod" class="text_area" value="<?php echo htmlspecialchars($row->prefix_text_mod) ?>" /></td>
		</tr>
		<tr><td><?php echo JText::_( 'Suffix' ) ?>: <input type="text" size="40" name="suffix_text_mod" class="text_area" value="<?php echo htmlspecialchars($row->suffix_text_mod) ?>" /></td></tr>
		<tr>
			<td valign="top" rowspan="2" class="key"><?php echo JText::_( 'Prefix and suffix text to display during display' ) ?>:</td>
			<td><?php echo JText::_( 'Prefix' ) ?>: <input type="text" size="40" name="prefix_text_display" class="text_area" value="<?php echo htmlspecialchars($row->prefix_text_display) ?>" /></td>
		</tr>
		<tr><td><?php echo JText::_( 'Suffix' ) ?>: <input type="text" size="40" name="suffix_text_display" class="text_area" value="<?php echo htmlspecialchars($row->suffix_text_display) ?>" /></td></tr>
		<tr>
			<td class="key"><?php echo JText::_( 'Size' ) ?>:</td>
			<td><input type="text" size="40" name="size" class="text_area" value="<?php echo $row->size ?>" /></td>
		</tr>
		<?php if ($row->field_type <> 'corename') { ?>
		<tr>
			<td class="key"><?php echo JText::_( 'Published' ) ?>:</td>
			<td><?php echo $lists['published'] ?></td>
		</tr>
		<?php } else { ?><input type="hidden" name="published" value="1"><?php
		} 
		?>
		<tr>
			<td class="key"><?php echo JText::_( 'Shown in details view' ) ?>:</td>
			<td><?php echo $lists['details_view'] ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'Shown in summary view' ) ?>:</td>
			<td><?php echo $lists['summary_view'] ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'Taggable' ) ?>:</td>
			<td><?php echo $lists['tag_search'] ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'Simple searchable' ) ?>:</td>
			<td><?php echo $lists['simple_search'] ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'Advanced searchable' ) ?>:</td>
			<td><?php echo $lists['advanced_search'] ?></td>
		</tr>
		<tr>
			<td class="key"><?php echo JText::_( 'Required field' ) ?>:</td>
			<td><?php echo $lists['required_field'] ?></td>
		</tr>
		<?php if ($row->field_type <> 'corename') { ?>
		<tr>
			<td class="key"><?php echo JText::_( 'Hidden field' ) ?>:</td>
			<td><?php echo $lists['hidden'] ?> <span style="background-color:white;margin-left:10px;"><?php echo JText::_( 'Hidden field desc' ); ?></span></td>
		</tr>
		<?php } else { ?><input type="hidden" name="hidden" value="0"><?php
		} 

		if($row->cf_id) { ?>
		<tr>
			<td class="key"><?php echo JText::_( 'Ordering' ) ?>:</td>
			<td><?php echo $lists['order'] ?></td>
		</tr>
		<?php } ?>
	</table>
	</fieldset>
	</td>
	
	<?php if(!is_null($params)) { JHTML::_('behavior.tooltip'); ?>
	<td valign="top" width="350" style="padding-left:5px">
		
	<fieldset>
	<legend><?php echo JText::_( 'Parameters' ) ?></legend>
	<?php echo $params->render();?>
	</fieldset>
	
	</td>
	<?php } ?>
	</tr>
	</table>
	
	<input type="hidden" name="option" value="<?php echo $option; ?>">
	<input type="hidden" name="cf_id" value="<?php echo $row->cf_id; ?>">
	<input type="hidden" name="task" value="save_customfields" />
	</form>
	<script language="javascript"><!--
	updateInputs(document.adminForm.field_type.value);
	--></script>
	<?php
	}
}
?>