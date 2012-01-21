<table class="adminlist" cellspacing="1" id="<?php echo $this->adapterName; ?><?php echo $this->viewName; ?>TableSection<?php echo $sections->id; ?>" style="<?php echo $styleTable; ?>">
	<thead>
		<tr>
			<th width="1%">
				<?php echo JText::_( 'ID' ); ?>
			</th>
			<th width="20%" class="title">
				<?php echo JText::_( 'Category' ); ?>
			</th>
            <th width="9%" nowrap="nowrap">
                <?php echo JText::_( 'Access Level' ); ?>
            </th>
			<th width="70%" nowrap="nowrap">
				<?php echo JText::_( 'Permisions' ); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php if( empty($this->categoriesList) ): ?>
	<tr class="<?php echo "row0"; ?>">
		<td align="center" colspan="100%"><?php echo JText::_('There are no Categories'); ?></td>
	</tr>
	<?php else: ?>
        <?php $k = 0; ?>
		<?php foreach($this->categoriesList as $categorie): ?>
        <?php $access = JHTML::_('grid.access', $categorie, $k ); ?>
        <?php $k = $k % 2; ?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center"><?php echo $categorie->id; ?></td>
            
			<td>
                    <a href="#" onclick="showAdapterTasks('<?php echo $this->adapterName; ?><?php echo $this->viewName; ?>Category<?php echo $categorie->id; ?>Tasks')">
                    	<?php echo $categorie->treename; ?>
                    </a>
            </td>
            <td align="center"><?php echo $access; ?></td>
            <td align="center">
                <?php
                    $groupName = JRequest::getVar( 'groupName' );
                    $extraParams = array(
                        '$catid' => $categorie->id
                    );
                    $groupTasks = $this->adapterControl->loadGroupTasks($groupName,$this->adapterName,$this->viewName,$extraParams);
                ?>
    			<div id="<?php echo $this->adapterName; ?><?php echo $this->viewName; ?>Category<?php echo $categorie->id; ?>">
                    <?php
                            if( !empty($groupTasks) ){
                                echo trim(implode(',',$groupTasks));
                            }
                            else{
                                echo JText::_( 'none' );
                            }
                    ?>
                </div>
                <?php
                /**
                 * Loading category params
                 */
                $this->adapterControl->renderTasks($this->adapterName,$this->viewName,$this->tasks,"Category{$categorie->id}",$groupTasks,"[{$categorie->id}]");
                ?>
			</td>
		</tr>
        <?php $k++; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
</table>