 
<div id="listing">
<h2><?php 
$link_name = $this->fields->getFieldById(1);
$this->plugin( 'ahreflisting', $this->link, $link_name->getOutput(1), '', array("delete"=>true) ) ?></h2>

<div class="review"><div class="review-head"><div class="review-title"><?php 
if($this->review->rating > 0) { 
	?><div class="review-rating"><?php $this->plugin( 'review_rating', $this->review->rating ); ?></div><?php 
}
$this->plugin('ahref', array("path"=>"index.php?option=".$this->option."&task=viewlink&link_id=".$this->link_id."&Itemid=".$this->Itemid,"fragment"=>"rev-".$this->review->rev_id), $this->review->rev_title,'id="rev-'.$this->review->rev_id.'"'); 

?></div><div class="review-info"><?php 
echo JText::_( 'Reviewed by' ) ?> <b><?php echo ( ($this->review->user_id) ? $this->review->username : $this->review->guest_name); ?></b>, <?php echo date("F j, Y",strtotime($this->review->rev_date)) ?>
</div>
<?php 
echo '<div id="rhc'.$this->review->rev_id.'" class="found-helpful"'.( ($this->review->vote_total==0)?' style="display:none"':'' ).'>';
echo '<span id="rh'.$this->review->rev_id.'">';
if( $this->review->vote_total > 0 ) { 
	printf( JText::_( 'People find this review helpful' ), $this->review->vote_helpful, $this->review->vote_total );
}
echo '</span>';
echo '</div>';
echo '</div>';
?>
<div class="review-text">
<?php 
echo trim($this->review->rev_text);

if( !empty($this->review->ownersreply_text) && $this->review->ownersreply_approved ) {
	echo '<div class="owners-reply">';
	echo '<span>'.JText::_( 'Owners reply' ).'</span>';
	echo '<p>' . nl2br(trim($this->review->ownersreply_text)) . '</p>';
	echo '</div>';
}
?>
</div>
</div>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			form.task.value='viewlink';
			form.submit();
			return;
		}
	<?php if( $this->user_id <= 0 ) { ?>
		// do field validation
		if (form.your_name.value == ""){
			alert( "<?php echo JText::_( 'Please fill in the form' ) ?>" );
		} else {
	<?php } ?>
			form.task.value=pressbutton;
			try {
				form.onsubmit();
				}
			catch(e){}
			form.submit();
	<?php if( $this->user_id <= 0 ) { ?>
		}
	<?php } ?>
	}
</script>
<br clear="all" />
<div class="title"><?php echo JText::_( 'Report review' ); ?></div>
<form action="<?php echo JRoute::_("index.php") ?>" method="post" name="adminForm" id="adminForm">
<table border="0" cellpadding="3" cellspacing="0">
	<?php if( $this->user_id <= 0 ) { ?>
	<tr>
		<td width="20%"><?php echo JText::_( 'Your name' ) ?>:</td>
		<td width="80%"><input type="text" name="your_name" class="inputbox" size="40" /></td>
	</tr>
	<?php } ?>
	<tr><td colspan="2"><b><?php echo JText::_( 'Message' ) ?>:</b></td></tr>
	<tr><td colspan="2"><textarea name="message" rows="8" cols="69" class="inputbox"></textarea></td></tr>
	<tr>
		<td colspan="2">
			<input type="hidden" name="option" value="<?php echo $this->option ?>" />
			<input type="hidden" name="task" value="send_reportreview" />
			<input type="hidden" name="rev_id" value="<?php echo $this->review->rev_id ?>" />
			<input type="hidden" name="link_id" value="<?php echo $this->review->link_id ?>" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid ?>" />
			<input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
			<input type="button" value="<?php echo JText::_( 'Send' ) ?>" onclick="javascript:submitbutton('send_reportreview')" class="button" /> <input type="button" value="<?php echo JText::_( 'Cancel' ) ?>" onclick="javascript:submitbutton('cancel')" class="button" />
		</td>
	</tr>
</table>
</form>

</div>