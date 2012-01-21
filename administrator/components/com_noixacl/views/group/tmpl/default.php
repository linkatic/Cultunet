<?php
defined('_JEXEC') or die('Restricted access');
$cid = JRequest::getVar( 'cid', array(0) );

$edit = $cid[0];
$text = intval($edit) ? JText::_( 'NOIXACL_VIEW_GROUP_TEXT_EDIT' ) : JText::_( 'NOIXACL_VIEW_GROUP_TEXT_NEW' );

JToolBarHelper::title( JText::_( 'NOIXACL_VIEW_GROUP_TEXT_GROUP' ) . ': <small><small>[ '. $text .' ]</small></small>' , 'user.png' );
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();

/**
 *  clean item data
 */
JFilterOutput::objectHTMLSafe( $user, ENT_QUOTES, '' );

$adaptersList = $this->Adapters->listAdapters();

$groupName = $this->group[2];
JRequest::setVar('groupName',$groupName);
?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");

		/**
                 * field validation
                 */
		if (trim(form.name.value) == "") {
			alert( "<?php echo JText::_( 'NOIXACL_VIEW_GROUP_VALIDATE_GROUP_NAME', true ); ?>" );
		} else if (form.parent_id.value == "") {
			alert( "<?php echo JText::_( 'NOIXACL_VIEW_GROUP_VALIDATE_PARENT_GROUP', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" autocomplete="off">
<table width="100%" cellspacing="0">
	<tr>
		<td colspan="100%">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'NOIXACL_VIEW_GROUP_LEGEND_GROUP_DETAILS' ); ?></legend>
				<table class="admintable" cellspacing="1">
					<tr>
						<td width="150" class="key">
							<label for="name">
								<?php echo JText::_( 'NOIXACL_VIEW_GROUP_LABEL_GROUP_NAME' ); ?>
							</label>
						</td>
						<td>
                            <input type="text" name="name" id="name" class="inputbox" size="40" value="<?php echo $this->group[2]; ?>" />
						</td>
					</tr>
					<tr>
						<td valign="top" class="key">
							<label for="gid">
								<?php echo JText::_( 'NOIXACL_VIEW_GROUP_LABEL_PARENT_GROUP' ); ?>
							</label>
						</td>
						<td>
							<?php echo $this->lists['gid']; ?>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
    <tr valign="top">
        <td width="200px">
        <?php if( count($adaptersList) > 0 ): ?>
            <fieldset id="treeview">
                <legend><?php echo JText::_( 'NOIXACL_VIEW_GROUP_LEGEND_ADAPTERS' ); ?></legend>
				<div id="media-tree_tree"></div>
                <?php echo $this->loadTemplate('adapters'); ?>
            </fieldset>
            <?php endif; ?>
        </td>
        <td>
			<?php
			$this->Adapters->renderViews($adaptersList);
			?>
		</td>
	</tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->group[0]; ?>" />
<input type="hidden" name="lft" value="<?php echo $this->group[4]; ?>" />
<input type="hidden" name="rgt" value="<?php echo $this->group[5]; ?>" />
<input type="hidden" name="cid[]" value="<?php echo $this->group[0]; ?>" />
<input type="hidden" name="option" value="com_noixacl" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
<br clear="all" />
<?php include JPATH_COMPONENT_ADMINISTRATOR.DS."libraries".DS."version.php"; ?>