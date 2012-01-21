<?php
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" name="adminForm" autocomplete="off">
<table width="100%" cellspacing="0">
	<tr>
		<td colspan="100%">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'NOIXACL_VIEW_ACCESSLEVEL_LEGEND_GROUP_DETAILS' ); ?></legend>
				<table class="admintable" cellspacing="1">
					<tr>
						<td width="150" class="key">
							<label for="name">
								<?php echo JText::_( 'NOIXACL_VIEW_ACCESSLEVEL_LABEL_NAME' ); ?>
							</label>
						</td>
						<td>
                            <input type="text" name="name" id="name" class="inputbox" size="50" value="<?php echo $this->accesslevel->name; ?>" />
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>
<?php if( count($this->groups) > 0 ): ?>
	<fieldset class="adminform">
    <legend><?php echo JText::_( 'NOIXACL_VIEW_USER_LEGEND_MULTIGROUP' ); ?></legend>
        <table class="adminlist" cellpadding="1">
        <thead>
			<tr>
				<th width="3%" class="title">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->groups); ?>);" />
				</th>
				<th class="title">
					<?php echo JTEXT::_('NOIXACL_VIEW_USER_TEXT_GROUP_NAME'); ?>
				</th>
			</tr>
		</thead>
        <tbody>
        <?php
			$i = 0;
			foreach($this->groups as $groupValue => $groupName)
			{
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
                	<?php if( $groupValue > 28 ): ?>
                		<input type="checkbox" disabled="disabled" />
                    <?php else: ?>
						<input type="checkbox" name="multigroups[]" <?php if( in_array($groupValue,$this->levelGroups) ){ echo "checked=\"checked\""; } ?> id="cb<?php echo $i; ?>" value="<?php echo $groupValue; ?>" />
                    <?php endif; ?>
				</td>
				<td>
					<?php echo $groupName; ?>
				</td>
			</tr>
			<?php
				$i++;
			}
			?>
		</tbody>
	</table>
    </fieldset>
    <?php endif; ?>
<input type="hidden" name="id" value="<?php echo $this->accesslevel->id; ?>" />
<input type="hidden" name="option" value="com_noixacl" />
<input type="hidden" name="controller" value="accesslevels" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
<br clear="all" />
<?php include JPATH_COMPONENT_ADMINISTRATOR.DS."libraries".DS."version.php"; ?>