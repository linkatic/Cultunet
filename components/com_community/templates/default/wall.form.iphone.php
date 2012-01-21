<script type="text/javascript" language="javascript">

function wallRemove( id )
{
	if(confirm('<?php echo JText::_('CC CONFIRM REMOVE WALL'); ?>'))
	{		
		joms.jQuery('#wall_'+id).fadeOut('normal').remove();
		if(typeof getCacheId == 'function') {
			cache_id = getCacheId();
		}else{
			cache_id = "";
		}	
		jax.call('community','<?php echo $ajaxRemoveFunc; ?>', id, cache_id );
	}
}

joms.jQuery(document).ready(function()
{
	//joms.utils.textAreaWidth('#wall-message');
});
</script>
<div class="appsBoxTitle"><?php echo JText::_('CC WALL'); ?></div>
<div style="padding: 0 5px; text-align: center;">
	<textarea id="wall-message" name="message" class="inputbox"></textarea>
	
	<button id="wall-submit" class="button" style="margin: 10px auto 0;" onclick="joms.walls.add('<?php echo $uniqueId; ?>', '<?php echo $ajaxAddFunction;?>');return false;" name="save">
		<?php echo JText::_('CC WALL ADD COMMENT');?>
	</button>
	
	<?php if(!empty($viewAllLink)): ?>
	<div style="text-align:right" class="app-box-footer">
		<a href="<?php echo $viewAllLink; ?>">
			<?php echo JText::_('CC SHOW ALL'); ?>
		</a>
	</div>
	<?php endif; ?>
</div>