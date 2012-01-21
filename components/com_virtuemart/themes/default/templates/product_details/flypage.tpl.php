<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__);
 ?>
 
<script type="text/javascript">
window.addEvent('load', function() {
    if($('gk_product_tabs')){
        $('ficha').addEvent('click', function(e){
            var evt = new Event(e).target;
            
            if((window.ie && evt.nodeName == 'SPAN') || (!window.ie && evt.getTag() == 'span')) {
                if($(evt).getParent().getParent().getProperty('id') == 'gk_product_tabs') {
                    $$('.gk_product_tab').addClass('gk_unvisible');
                    $$('#gk_product_tabs li').setProperty('class', '');
                    var num = 0;
                    $$('#gk_product_tabs li').each(function(el, i){
                        if(el == evt.getParent()){ num = i; }
                    });
                    $$('.gk_product_tab')[num].removeClass('gk_unvisible');
		            $$('#gk_product_tabs li')[num].setProperty('class', 'gk_product_tab_active');
                }
            } else if((window.ie && evt.nodeName == 'LI') || (!window.ie && evt.getTag() == 'li')) {
                if($(evt).getParent().getProperty('id') == 'gk_product_tabs') {
                    $$('.gk_product_tab').addClass('gk_unvisible');
                    $$('#gk_product_tabs li').setProperty('class', '');
                    var num = 0;
                    $$('#gk_product_tabs li').each(function(el, i){
                        if(el == evt.getParent()){ num = i; }
                    });
                    $$('.gk_product_tab')[num].removeClass('gk_unvisible');
		            $$('#gk_product_tabs li')[num].setProperty('class', 'gk_product_tab_active');
                }
            }
        });
    }
});
</script>

<div id="ficha" class="vmMainPage1 clearfix">
     <div class="vmMainPage2"><?php echo $buttons_header // The PDF, Email and Print buttons ?>
          <?php
if( $this->get_cfg( 'showPathway' )) {
	echo "<div class=\"pathway\">$navigation_pathway</div>";
}
if( $this->get_cfg( 'product_navigation', 1 )) {
	if( !empty( $previous_product )) {
		echo '<a class="previous_page" href="'.$previous_product_url.'">'.shopMakeHtmlSafe($previous_product['product_name']).'</a>';
	}
	if( !empty( $next_product )) {		
		echo '<a class="next_page" href="'.$next_product_url.'">'.shopMakeHtmlSafe($next_product['product_name']).'</a>';
	}
}
?>
	<div style="clear:both"></div>
     </div>
     <div class="vmMainPage3">
          <div class="prod_details">
               <?php  if( $this->get_cfg('showManufacturerLink') ) { $rowspan = 5; } else { $rowspan = 4; } ?>
               <div class="vm_main_info clearfix">
                    <div class="lf"> 
                    <?php echo str_replace('<br/>', '', $product_image); ?>
                    </div>
                    <div class="rcolumn">
                         <h1><?php echo $product_name ?> <?php echo $edit_link ?></h1>
                         <div class="pprice"> 
                         <?php echo $product_price_lbl ?> <?php echo $product_price ?>
                         </div>
                    </div>
					<?php echo $addtocart ?>
               </div>
               
               
               
               <?php if( !empty($images)) { ?>
               <div class="thumbnailListContainer">
                    <h5><?php echo $VM_LANG->_('PHPSHOP_MORE_IMAGES') ?></h5>
                    <?php 

					echo $this->vmListAdditionalImages( $product_id, $images );

		  		?>
               </div>
               <?php } 	?>
               <ul id="gk_product_tabs">
                    <li class="gk_product_tab_active"><span><?php echo $VM_LANG->_('PHPSHOP_FLYPAGE_LBL') ?></span></li>
                    <li><span><?php echo $VM_LANG->_('VM_PRODUCT_ENQUIRY_LBL') ?></span></li>
                    <li><span><?php echo $VM_LANG->_('PHPSHOP_REVIEWS') ?></span></li>
               </ul>
               <div class="td_bg clearfix">
                    <div id="gk_product_tab_1" class="gk_product_tab">
                         <?php if( $this->get_cfg('showManufacturerLink')) : ?>
                         <div> <?php //echo $manufacturer_link ?>
                         </div>
                         <?php endif; ?>
                         <div> <?php echo $product_description ?> <span style="font-style: italic;"><?php echo $file_list ?></span> </div>
                         <?php if( $this->get_cfg( 'showAvailability' )) : ?>
                         <div class="lf"> <?php echo $product_availability; ?> </div>
                         <?php endif; ?>
                         <div class="rf"> <?php echo $product_packaging ?> </div>
                         <div> <?php echo $product_type ?> </div>
                    </div>
                    <div id="gk_product_tab_2" class="gk_product_tab gk_unvisible">
                         <?php									
									if ( $set == 0 ) { // This is the enquiry form!
									
										$validate = vmIsJoomla( '1.5' ) ? JUtility::getHash( $mainframe->getCfg( 'db' ) ) : mosHash( $mainframe->getCfg( 'db' ) );
										?>
                         <form action="<?php echo $mm_action_url ?>index.php" method="post" name="emailForm" id="emailForm">
                              <label for="contact_name"><?php echo $VM_LANG->_('NAME_PROMPT') ?></label>
                              <input type="text" name="name" id="contact_name" size="66" class="inputbox" value="<?php echo $name ?>"/>
                              <br />
                              <br />
                              <label for="contact_mail"><?php echo $VM_LANG->_('EMAIL_PROMPT') ?></label>
                              <input type="text" id="contact_mail" name="email" size="66" label="Your email" class="inputbox" value="<?php echo $email ?>" />
                              <br />
                              <br />
                              <label for="contact_text"><?php echo $VM_LANG->_('MESSAGE_PROMPT') ?></label><br/>
                              <textarea rows="10" cols="50" name="text" id="contact_text" class="inputbox"><?php echo $subject ?></textarea>
                              <br />
                              <input type="button" name="send" value="<?php echo $VM_LANG->_('SEND_BUTTON') ?>" class="button" onclick="validateEnquiryForm()" />
                              <input type="hidden" name="product_id" value="<?php echo  $product_id;  ?>" />
                              <input type="hidden" name="product_sku" value="<?php echo  $product_sku;  ?>" />
                              <input type="hidden" name="set" value="1" />
                              <input type="hidden" name="func" value="productAsk" />
                              <input type="hidden" name="page" value="shop.ask" />
                              <input type="hidden" name="option" value="com_virtuemart" />
                              <input type="hidden" name="flypage" value="<?php echo $flypage ?>" />
                              <input type="hidden" name="Itemid" value="<?php echo $Itemid ?>" />
                              <input type="hidden" name="<?php echo $validate ?>" value="1" />
                         </form>
                         <script type="text/javascript"><!--
										function validateEnquiryForm() {
											if ( ( document.emailForm.text.value == "" ) || ( document.emailForm.email.value.search("@") == -1 ) || ( document.emailForm.email.value.search("[.*]" ) == -1 ) ) {
												alert( "<?php echo $VM_LANG->_('CONTACT_FORM_NC',false); ?>" );
											} else if ( ( document.emailForm.email.value.search(";") != -1 ) || ( document.emailForm.email.value.search(",") != -1 ) || ( document.emailForm.email.value.search(" ") != -1 ) ) {
												alert( "No puedes introducir más de una dirección de mail" );
											} else {
												document.emailForm.action = "<?php echo sefRelToAbs("index.php"); ?>"
												document.emailForm.submit();
											}
										}
										--></script>
                         <?php
									}
									else { // if set==1 then we have sent the email to the vendor and say thank you here.
									  ?>
                         <img src="<?php echo VM_THEMEURL ?>images/button_ok.png" height="48" width="48" align="center" alt="Success" border="0" /> <?php echo $VM_LANG->_('THANK_MESSAGE') ?> <br />
                         <br />
                         <a class="button" href="<?php echo $product_link ?>"><?php echo $VM_LANG->_('VM_RETURN_TO_PRODUCT') ?></a>
                         <?php 
									}
									?>
                    </div>
                    <div id="gk_product_tab_3" class="gk_product_tab gk_unvisible">
                         <div> <?php echo $product_reviews ?> </div>
                         <div> <?php echo $product_reviewform ?> </div>
                    </div>
               </div>
               <?php echo $related_products ?>
               <?php if( $this->get_cfg('showVendorLink')) { ?>
               <div style="text-align: center;"> <?php echo $vendor_link ?><br />
               </div>
               <?php  } ?>
          </div>
          <?php 
if( !empty( $recent_products )) { ?>
          <?php echo $recent_products; ?>
          <?php 
}
/* if( !empty( $navigation_childlist )) { ?>
          <div class="vmMorecategories">
               <h3><?php echo $VM_LANG->_('PHPSHOP_MORE_CATEGORIES') ?></h3>
               <?php echo $navigation_childlist ?><br style="clear:both"/>
          </div>
          <?php 
} */ ?>
     </div>
</div>
