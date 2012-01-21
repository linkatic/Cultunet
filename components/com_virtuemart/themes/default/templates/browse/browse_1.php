<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__);
 ?>
 <div class="browseProductContainer">
        
         <div class="browseProductImageContainer">
	        <script type="text/javascript">//<![CDATA[
	        document.write('<a href="javascript:void window.open(\'<?php echo $product_full_image ?>\', \'win2\', \'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=<?php echo $full_image_width ?>,height=<?php echo $full_image_height ?>,directories=no,location=no\');">');
	        document.write( '<?php echo ps_product::image_tag( $product_thumb_image, 'class="browseProductImage" border="0" title="'.$product_name.'" alt="'.$product_name .'"' ) ?></a>' );
	        //]]>
	        </script>
	        <noscript>
	            <a href="<?php echo $product_full_image ?>" class="gk_vm_product_image" target="_blank" title="<?php echo $product_name ?>">
	            <?php echo ps_product::image_tag( $product_thumb_image, 'class="browseProductImage" border="0" title="'.$product_name.'" alt="'.$product_name .'"' ) ?>
	            </a>
	        </noscript>
        </div>
		  
        <h3 class="browseProductTitle"><a title="<?php echo $product_name ?>" href="<?php echo $product_flypage ?>">
            <?php echo $product_name ?></a>
        </h3>
        
        <div class="browsePriceContainer">
            <?php echo $product_price ?>
        </div>
        

        <div class="browseProductDescription">
            <?php echo $product_s_desc ?>&nbsp;
			<?php /*
            <a href="<?php echo $product_flypage ?>" title="<?php echo $product_details ?>"><br />
			<?php echo $product_details ?>...</a>
			*/
			?>
        </div>
        <span class="browseAddToCartContainer">
        <?php echo $form_addtocart ?>
        </span>

</div>
