<?php 

// Check listing details' access
if( 
	$this->config->getTemParam('limitDetailsViewToRegistered',0) == 0
	||
	(
		$this->config->getTemParam('limitDetailsViewToRegistered',0) == 1
		&&
		$this->my->id > 0
	)
) {
	
	?>
	<h3><?php echo JText::_( '+ Ficha detallada del recurso' ); ?></h3>
	<div id="detalle-recurso" class="border-container bg-container">	
		<div id="enviar_a_amigo">
			<a onclick="window.open(this.href,'win2','width=400,height=350,menubar=yes,resizable=yes'); return false;" title="E-mail" href="/gestionycultura.com/index.php?option=com_mailto&tmpl=component&link=<?php echo base64_encode($_SERVER['REQUEST_URI']); ?>"><img alt="E-mail" src="/images/M_images/emailButton.png"> Enviar a un amigo</a>		
		</div>	
		<?php
		
		include $this->loadTemplate( 'sub_listingDetails.tpl.php' );
	
		if ($this->mtconf['use_map']) include $this->loadTemplate( 'sub_map.tpl.php' );
	
		if ($this->mt_show_review) include $this->loadTemplate( 'sub_reviews.tpl.php' ); 
	
	} else {
		?>
		 
		<?php
		echo JText::_( 'Please login to view more information about this listing.' );
	}
?>
	</div><!-- Fin border-container -->