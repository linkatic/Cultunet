<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );



mm_showMyFileName(__FILE__);



 ?>

<div class="browseProductContainer"> <a href="<?php echo $product_flypage ?>" class="gk_vm_product_image" title="<?php echo $product_name ?>"> <?php echo ps_product::image_tag( $product_thumb_image, 'class="browseProductImage" border="0" title="'.$product_name.'" alt="'.$product_name .'"' ) ?> </a>
     <h2> <a href="<?php echo $product_flypage ?>"><?php echo $product_name ?></a> </h2>
     <p> <?php echo $product_price ?></p>
     
     <p class="clear"><?php echo $product_s_desc ?></p> 
     <p class="browseProductrating"> <?php echo $product_rating ?> </p>
     <p> <a class="button_details" href="<?php echo $product_flypage ?>"><?php echo $product_details ?>...</a> </p><br style="clear:both;" />
</div>
