<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>

<?php

defined( 'vmToolTipCalled') or define('vmToolTipCalled', 1);
echo $vendor_store_desc."";

//echo "<h4 class=\"gk_shop_categories\">".$VM_LANG->_('PHPSHOP_CATEGORIES')."</h4>";

//echo $categories; 
?> 






<?php

// Show Featured Products

if( $this->get_cfg( 'showFeatured', 1 )) {

    /* featuredproducts(random, no_of_products,category_based) no_of_products 0 = all else numeric amount

    edit featuredproduct.tpl.php to edit layout */

    echo $ps_product->featuredProducts(true,10,false);

}

// Show Latest Products

if( $this->get_cfg( 'showlatest', 1 )) {

    /* latestproducts(random, no_of_products,month_based,category_based) no_of_products 0 = all else numeric amount

    edit latestproduct.tpl.php to edit layout */

    ps_product::latestProducts(true,10,false,false);

}
?>
<?php echo $recent_products; ?>