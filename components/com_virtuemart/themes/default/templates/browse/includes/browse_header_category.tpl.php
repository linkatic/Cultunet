<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 

mm_showMyFileName(__FILE__);?>

<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 

mm_showMyFileName(__FILE__);?>

<div id="nagl">
     <h3><?php echo $browsepage_lbl; ?>
          <?php 
 if( $this->get_cfg( 'showFeedIcon', 1 ) && (VM_FEED_ENABLED == 1) ) { ?>
          <a href="index.php?option=<?php echo VM_COMPONENT_NAME ?>&amp;page=shop.feed&amp;category_id=<?php echo $category_id ?>" title="<?php echo $VM_LANG->_('VM_FEED_SUBSCRIBE_TOCATEGORY_TITLE') ?>">
          <img src="<?php echo VM_THEMEURL ?>/images/feed-icon-14x14.png" alt="feed" border="0"/>
          </a>
          <?php 

 } ?>
     </h3>
</div>

