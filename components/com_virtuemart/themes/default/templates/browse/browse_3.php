<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__);
 ?>
 <div class="browseProductContainer">
 	<div style="height:250px">
	  <div class="browseProductImageContainer">
	  	<a href="<?php echo $product_flypage ?>" class="gk_vm_product_image" title="<?php echo $product_name ?>">
	        <?php echo ps_product::image_tag( $product_thumb_image, 'class="browseProductImage" border="0" title="'.$product_name.'" alt="'.$product_name .'"' ) ?>
	       </a>
	  </div>
		
	  <h3 class="browseProductTitle"><a title="<?php echo $product_name ?>" href="<?php echo $product_flypage ?>">
	      <?php echo $product_name ?></a>
	  </h3>
	        
	  <div class="browsePriceContainer">
	      <?php echo $product_price ?>
	  </div>
	     
	<?php /*
	   <div class="browseProductDescription">
	     	<?php echo $product_s_desc ?>&nbsp;
				
	            <a href="<?php echo $product_flypage ?>" title="<?php echo $product_details ?>"><br />
				<?php echo $product_details ?>...</a>
				
				
	   </div>
	  */ ?>
   </div>
   <br style="clear:both;" />
   <span class="browseAddToCartContainer">
        <?php echo $form_addtocart ?>
   </span>
</div>
