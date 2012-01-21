<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 

$iCol = 1;
//Number of featured products to show per row
$featured_per_row = 2;
//Set the cell width
$cellwidth = intval( (100 / $featured_per_row) - 2 );

if( empty( $featured_products )) {
	return; // Do nothing, if there are no Featured!
}
echo "<div class=\"feature clearfix\">";
echo "<h3>".$VM_LANG->_('VM_FEATURED_PRODUCT')."</h3>";
?>
<div class="border-container bg-container">
<?php
foreach( $featured_products as $featured ) {
	?>
<div class="fprod_con clearfix">
     <?php
			if ( $featured["product_thumb"] ) { ?>
     <a class="fimage" title="<?php echo $featured["product_name"] ?>" href="<?php $sess->purl(URL."index.php?option=com_virtuemart&amp;page=shop.product_details&amp;flypage=".$featured["flypage"]."&amp;product_id=".$featured["product_id"]) ?>">
     <?php echo ps_product::image_tag( $featured["product_thumb"], "class=\"browseProductImage\" border=\"0\" alt=\"".$featured["product_name"]."\"");
				?></a>
     <?php
			}?>
     <a title="<?php echo $featured["product_name"] ?>" href="<?php $sess->purl(URL."index.php?option=com_virtuemart&amp;page=shop.product_details&amp;flypage=".$featured["flypage"]."&amp;product_id=".$featured["product_id"]) ?>">
     <h4><?php echo $featured["product_name"] ?></h4>
     </a>
     <?php echo $featured['product_price'] ?>
     <span class="s_desc"><?php echo $featured['product_s_desc'] ?></span>
     <?php echo $featured['form_addtocart'] ?>
</div>
<?php
	// Do we need to close the current row now?
	if ($iCol == $featured_per_row) { // If the number of products per row has been reached
		
		$iCol = 1;
	}
	else {
		$iCol++;
	}
}

?>
<div class="clearfix"></div>
</div><!-- Fin border-container -->
</div>


