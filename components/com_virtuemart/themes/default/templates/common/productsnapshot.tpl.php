<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>
<!-- The product image DIV. -->

<a title="<?php echo $product_name ?>" href="<?php echo $product_link ?>">
<?php

		// Print the product image or the "no image available" image

		echo ps_product::image_tag( $product_thumb_image, "alt=\"".$product_name."\"");

	?>
</a>
<!-- The product name DIV. -->
<?php if( $show_product_name ) : ?>
<br />
<a title="<?php echo $product_name ?>" href="<?php echo $product_link ?>"><?php echo $product_name; ?></a>
<?php endif;?>
<!-- The product price DIV. -->
<?php

if( !empty($price) ) {
    
	echo ('<br />');
	echo $price;

}

?>
<!-- The add to cart DIV. -->
<?php

if( !empty($addtocart_link) ) {

	?>
<div>
     <form action="<?php echo  $mm_action_url ?>index.php" method="post" name="addtocart" id="addtocart">
          <input type="hidden" name="option" value="com_virtuemart" />
          <input type="hidden" name="page" value="shop.cart" />
          <input type="hidden" name="Itemid" value="<?php echo ps_session::getShopItemid(); ?>" />
          <input type="hidden" name="func" value="cartAdd" />
          <input type="hidden" name="prod_id" value="<?php echo $product_id; ?>" />
          <input type="hidden" name="product_id" value="<?php echo $product_id ?>" />
          <input type="hidden" name="quantity" value="1" />
          <input type="hidden" name="set_price[]" value="" />
          <input type="hidden" name="adjust_price[]" value="" />
          <input type="hidden" name="master_product[]" value="" />
          <input type="submit" class="addtocart_button" value="<?php echo $VM_LANG->_('PHPSHOP_CART_ADD_TO') ?>" title="<?php echo $VM_LANG->_('PHPSHOP_CART_ADD_TO') ?>" />
     </form>
</div>
<?php

}

?>
