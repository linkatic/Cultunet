<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 * @param	profile			A Profile object that contains profile fields for this specific user
 * @param	profile->
 * @params	isMine		boolean is this profile belongs to me?
 */
defined('_JEXEC') or die();
?>
<div class="cModule">
	<h3><?php echo JText::_('CC ABOUT ME');?></h3>
	
	<?php if( $isMine ): ?>
	<a class="edit-this" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=edit');?>" title="<?php echo JText::_('CC EDIT PROFILE'); ?>">[ <?php echo JText::_('CC EDIT PROFILE'); ?> ]</a>
	<?php endif; ?>

	<?php foreach( $profile['fields'] as $groupName => $items ): ?>

	<?php if( $groupName != 'ungrouped' ): ?>
	<h4><?php echo JText::_( $groupName ); ?></h4>
	<?php endif; ?>
	
	<dl class="profile-right-info">


		<?php foreach( $items as $item ): ?>
			<dt><?php echo JText::_( $item['name'] ); ?></dt>
	    	<dd>
	    		<?php 
	    			/*
 					* HACK PARA FILTRAR EN SELECT MULTIPLE
 					*/
 					if ($item['type'] == 'list') { 
	    			?>   
			        <?php $terms = explode(",", $item['value']);
					for ($n = 1; $n <= count($terms); $n += 1) { ?>
					     <a href="<?php echo $item['searchLink'.$n]; ?>"> 
					          <?php echo CProfileLibrary::getFieldData( $item['type'] , $item['value'.$n] ); ?>
					     </a> <br />
					<?php }?>
        		<?php } else { ?>
		    		<?php if(!empty($item['searchLink'])) :?>
						<a href="<?php echo $item['searchLink']; ?>"> 
					<?php endif; ?>
					
					<?php echo CProfileLibrary::getFieldData( $item['type'] , $item['value'] ); ?>
					
					<?php if(!empty($item['searchLink'])) :?>
						</a> 
					<?php endif; ?>
				<?php }?>
			</dd>
	    <?php endforeach; ?>
	</dl>
	<?php endforeach; ?>
</div>

<?php
/*
 * HACK PARA FILTRAR EN SELECT MULTIPLE
 */

/*
 
<h2 class="app-box-title">
<?php echo JText::_('CC ABOUT ME');?>
<?php if( $isMine ) : ?>
<span>
<a class="app-title-link" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&task=edit');?>">[ <?php echo JText::_('CC EDIT PROFILE');?> ]</a>
</span>
<?php endif; ?>
</h2>


<?php foreach( $profile['fields'] as $groupName => $items ): ?>
<ul class="profile-info">
<?php if( $groupName != 'ungrouped' ): ?>
<li class="title"><?php echo ($groupName != 'ungrouped') ? $groupName : ''; ?></li>
<?php endif; ?>

<?php foreach( $items as $item ): ?>
<li class="info-title"><?php echo $item['name']; ?></li>
     <li class="info-detail">
        <?php if ($item['type'] == 'list') { ?>
       
        <?php $terms = explode(",", $item['value']);
for ($n = 1; $n <= count($terms); $n += 1) { ?>
          
            <a href="<?php echo $item['searchLink'.$n]; ?>"> 
             <?php echo CProfileLibrary::getFieldData( $item['type'] , $item['value'.$n] ); ?>
             </a> 

<?php }?>

        <?php } else { ?>

     <?php if(!empty($item['searchLink'])) :?>
<a href="<?php echo $item['searchLink']; ?>"> 
<?php endif; ?>

<?php echo CProfileLibrary::getFieldData( $item['type'] , $item['value'] ); ?>

<?php if(!empty($item['searchLink'])) :?>
</a> 
<?php endif; ?>
        
        <?php } ?>
        
</li>
    <?php endforeach; ?>
</ul>
<?php endforeach; ?>
 */
?>
