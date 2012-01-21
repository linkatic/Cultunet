<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

if($empty_cart) { ?>
    
    <div style="margin: 0 auto;">
    <?php if(!$vmMinicart) { ?>
    <?php }
    echo $VM_LANG->_('PHPSHOP_EMPTY_CART') ?>
    </div>
<?php } 
else {
    // Loop through each row and build the table
    foreach( $minicart as $cart ) { 		
		foreach( $cart as $attr => $val ) {
			// Using this we make all the variables available in the template
			// translated example: $this->set( 'product_name', $product_name );
			$this->set( $attr, $val );
		}
        if(!$vmMinicart) { // Build Minicart
            ?>
            <div class="gk_vm_minicart_product clearfix">
				<div class="gk_vm_product">
            		<a href="<?php echo $cart['url'] ?>"><?php echo $cart['product_name'] ?></a> (<strong><?php echo $cart['quantity'] ?></strong>x)
            	</div>
            	<div class="gk_vm_attributes">
            		<?php echo $cart['attributes']; ?>
            	</div>
               	<div class="gk_vm_price">
            		<?php echo $cart['price'] ?>
            	</div>
            </div>
            <?php
        }
    }
}
 ?>
<div class="lf" ><?php echo $total_products ?></div>
<div class="rf"><?php echo $total_price ?></div>
<?php if (!$empty_cart && !$vmMinicart) { ?>
    <div class="gk_vm_show_cart">
    <?php echo $show_cart ?>
    </div>

<?php } 
echo $saved_cart;
?>