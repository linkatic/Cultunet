<script type="text/javascript">
function showPositionModules()
{
    var showMenuTypeID = "<?php echo $this->adapterName; ?><?php echo $this->viewName; ?>TableModules"+$("<?php echo $this->adapterName; ?><?php echo $this->viewName; ?>TableModules").value;
    var totalOptions = $("<?php echo $this->adapterName; ?><?php echo $this->viewName; ?>TableModules").options.length;
    for( var numOption = 0 ; numOption < totalOptions ; numOption++ ){
        var myMenuTypeElem = "<?php echo $this->adapterName; ?><?php echo $this->viewName; ?>TableModules"+ $("<?php echo $this->adapterName; ?><?php echo $this->viewName; ?>TableModules").options[numOption].value;
        $(myMenuTypeElem).style.display = 'none';
    }
    $(showMenuTypeID).style.display = 'block';
}
</script>
<table>
	<tr>
		<td width="100%">
			<?php echo JText::_( 'Position' ); ?>:
			<?php echo $this->lists["positions"]; ?>
		</td>
	</tr>
</table>
<?php $styleTable = "display:block;"; ?>
<?php foreach($this->positionsList as $position): ?>
<table class="adminlist" cellspacing="1" id="<?php echo $this->adapterName; ?><?php echo $this->viewName; ?>TableModules<?php echo $position->id; ?>" style="<?php echo $styleTable; ?>">
	<thead>
        <th width="1%">
            <?php echo JText::_( 'ID' ); ?>
        </th>
        <th width="20%" class="title">
            <?php echo JText::_( 'Module' ); ?>
        </th>
        <th width="9%" nowrap="nowrap">
            <?php echo JText::_( 'Access Level' ); ?>
        </th>
        <th width="70%" nowrap="nowrap">
            <?php echo JText::_( 'Permisions' ); ?>
        </th>
    </thead>
	<tbody>
	<?php if( empty($position->modulesList) ): ?>
	<tr class="row0">
		<td align="center" colspan="100%"><?php echo JText::_('There are no Modules'); ?></td>
	</tr>
	<?php else: ?>
        <?php $k = 0; ?>
		<?php foreach($position->modulesList as $module): ?>
            <?php $access = JHTML::_('grid.access', $module, $k ); ?>
            <?php $k = $k % 2; ?>
    		<tr class="<?php echo "row$k"; ?>">
                <td align="center"><?php echo $module->id; ?></td>
                <td>
                    <?php if($module->access): ?>
                        <a href="javascript:void(0);" onclick="showAdapterTasks('<?php echo $this->adapterName; ?><?php echo $this->viewName; ?>Position<?php echo $module->id; ?>Tasks')">
                    <?php endif; ?>
                        <?php echo $module->title; ?>
                    <?php if($module->access): ?>
                        </a>
                    <?php endif; ?>
                </td>
                <td align="center"><?php echo $access; ?></td>
                <td align="center">
                <?php
                $groupName = JRequest::getVar( 'groupName' );
                $extraParams = array(
                    '$moduletitle' => $module->title,
                	'$module' => $module->module
                );
                
                $groupTasks = $this->adapterControl->loadGroupTasks( $groupName, $this->adapterName,$this->viewName,$extraParams);
                ?>
                <div id="<?php echo $this->adapterName; ?><?php echo $this->viewName; ?>Position<?php echo $module->id; ?>">
                    <?php
                        if($module->access){
                            if( !empty($groupTasks) ){
                                echo trim(implode(',',$groupTasks));
                            }
                            else{
                                echo JText::_( 'none' );
                            }
                        }
                        else{
                           echo JText::_( 'You can not set permissions in' ); ?> <?php echo $module->groupname; ?> <?php echo JText::_( 'access level' );
                        }
                    ?>
                </div>
                <?php
                /**
                 * Loading Category Params
                 */
                $this->adapterControl->renderTasks($this->adapterName,$this->viewName,$this->tasks,"Position{$module->id}",$groupTasks,"[{$module->module}][{$module->title}]");
                ?>
                </td>
            </tr>
        <?php $k++; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
<?php $styleTable = "display:none;"; ?>
<?php endforeach; ?>