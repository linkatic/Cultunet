<div style="text-align:right;margin-bottom:10px;">
	<a href="#" onclick="javascript:window.print(); return false" title="<?php echo JText::_( 'Print' ) ?>"><?php echo JText::_( 'Print' ) ?></a>&nbsp;|&nbsp;
	<a href="#" onclick="window.close(); return false" title="<?php echo JText::_( 'Close this window' ) ?>"><?php echo JText::_( 'Close this window' ) ?></a>
</div>
<div id="listing">
<h2><?php 
$link_name = $this->fields->getFieldById(1);
$this->plugin( 'ahreflisting', $this->link, $link_name->getOutput(1), '', array("edit"=>false,"delete"=>true) ) ?></h2>
<div class="fields">
<?php
$this->fields->resetPointer();
while( $this->fields->hasNext() ) {
	$f = $this->fields->getField();
	$value = $f->getValue();
	if( ( (!$f->hasInputField() && !$f->isCore() && empty($value)) || !empty($value) ) && !in_array($f->getName(),array('link_name','link_desc','city','state','country','postcode')) ) {

		$this->fields->resetPointer();
		while( $this->fields->hasNext() ) {
			$field = $this->fields->getField();
			$value = $field->getValue();
			if( ( (!$field->hasInputField() && !$field->isCore() && empty($value)) || !empty($value) ) && !in_array($field->getName(),array('link_name','link_desc','city','state','country','postcode')) ) {
				echo '<div class="row">';
				if($field->id == 4) {
					echo '<div class="caption">' . $field->getCaption() . '</div>';
					echo '<div class="data">';
					echo $field->getOutput(); 
					if($field5 = $this->fields->getFieldById(5)) {
						echo ', ' . $field5->getValue();
					}
					if($field8 = $this->fields->getFieldById(8)) {
						echo ', ' . $field8->getValue();
					}
					if($field6 = $this->fields->getFieldById(6)) {
						echo ', ' . $field6->getValue();
					}
					if($field7 = $this->fields->getFieldById(7)) {
						echo ', ' . $field7->getValue();
					}
					echo '</div>';
				} else { 
					echo '<div class="caption">';
					if($field->hasCaption()) {
						echo $field->getCaption() . '';
					}
					echo '</div>';
					echo '<div class="data">';
					echo $field->getDisplayPrefixText(); 
					echo $field->getOutput(1);
					echo $field->getDisplaySuffixText(); 
					echo '</div>';
				}
				echo '</div>';
			}
			$this->fields->next();
		}
		break;
	}
	$this->fields->next();
}
?></div><?php 

if ($this->link->link_image) { 
	echo '<div class="thumbnail' . (($this->config->getTemParam('imageDirectionListingSummary','right')=='right') ? '':'-left') . '">';
	echo '<a href="index.php?option=com_mtree&task=viewimage&img_id=' . $this->link->img_id . '&Itemid=' . $this->Itemid . '">';
	$this->plugin( 'mt_image', $this->link->link_image, '3', $this->link->link_name );
	echo '</a>';
	if( $this->total_images > 1 ) {
		echo '<div><a href="index.php?option=com_mtree&task=viewgallery&link_id=' . $this->link->link_id . '&Itemid=' . $this->Itemid . '">' . JText::_( 'View gallery' ) . '</a></div>';
	}
	echo '</div>';
}
if(!is_null($this->fields->getFieldById(2))) { 
	$link_desc = $this->fields->getFieldById(2);
	echo $link_desc->getOutput();
}
echo '</span>';
?>

</div>
<div style="text-align:right;margin-bottom:10px;">
	<a href="#" onclick="javascript:window.print(); return false" title="<?php echo JText::_( 'Print' ) ?>"><?php echo JText::_( 'Print' ) ?></a>&nbsp;|&nbsp;
	<a href="#" onclick="window.close(); return false" title="<?php echo JText::_( 'Close this window' ) ?>"><?php echo JText::_( 'Close this window' ) ?></a>
</div>
