<?php
	
	/*--------------------------------------------------------------
	# Gestion y Cultura Template - Julio 2010 (para Joomla 1.5)
	# Copyright (C) 2010 Linkatic. Todos los derechos reservados.
	# Licencia: Copyrighted Commercial Software
	# Autores: Lorena Hernandez y Vicente Gimeno
	# Website: http://www.linkatic
	# Soporte: info@linkatic.com 
	---------------------------------------------------------------*/
	
	// no direct access
	defined('_JEXEC') or die('Restricted access');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
	<meta name="verify-iw" content="IW818381382196997" />
	<jdoc:include type="head" />
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/components/com_mtree/templates/m2/template.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/template.css" type="text/css" />
	<!--[if IE 7]><link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/styles_ie7.css" type="text/css" /><![endif]-->
	<!--[if IE 8]><link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/styles_ie8.css" type="text/css" /><![endif]-->
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/css/common.css" type="text/css" />
</head>
	<body id="gc">
		<div id="page">
			<!-- <div id="top-header">
				<div class="banner_t"><jdoc:include type="modules" name="banner_top" style="xhtml" /></div>
			</div> -->
			<div id="container" class="clearfix">
				<div id="header">
					<div style="position:absolute; top:-2px; left:201px; z-index:10;">
						<img src="/images/versionbeta.png" alt="Versión Beta" title="Versión Beta" />
					</div>
					<div id="logo">
						<jdoc:include type="modules" name="logo" />
						<a href="<?php echo $this->baseurl ?>">
							<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/images/logoCultunet.png" alt="<?php echo JText::_('Cultunet'); ?>" />
						</a>
						<div id="promotores">
							<a href="<?php echo $this->baseurl ?>/index.php?option=com_content&view=article&id=2&Itemid=8">
								<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/images/logo_areadetrabajo.png" alt="Área de Trabajo" class="l1" />
							</a>
							<a href="http://www.oei.es" target="_blank">
								<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/images/logo_oei.png" alt="Organización de Estados Iberoamericanos" class="l2" />
							</a>
						</div>
					</div>
					<div id="banner_t">
						<jdoc:include type="modules" name="banner_top" style="xhtml" />					
						<div id="login"><jdoc:include type="modules" name="login" style="xhtml" /></div>
					</div>

				</div><!-- Fin header -->
				<div id="layer_nav">
					<div id="menu">
						<jdoc:include type="modules" name="menu" />
					</div>
					<div id="menu_extra">
						<div id="language"><jdoc:include type="modules" name="language" style="xhtml" /></div>
						<div id="search"><jdoc:include type="modules" name="search" style="xhtml" /></div>
						<div id="icons_redes"><jdoc:include type="modules" name="icons_redes" style="xhtml" /></div>
					</div>
				</div><!-- Fin layer_nav -->
				<div id="breadcrumb">
					<jdoc:include type="modules" name="breadcrumb" style="xhtml" />
				</div>
				<div id="content">
				<?php if($this->countModules('left_untercio')) { //dos columnas ?>
					<div class="untercio">
						<jdoc:include type="modules" name="left_untercio" style="xhtml" />
						<jdoc:include type="modules" name="left_untercio_content" style="cultunet" />
					</div>
					<div class="dostercios ml20">
						<jdoc:include type="message" style="xhtml" />
						<jdoc:include type="modules" name="right_top" style="xhtml" />
						<jdoc:include type="component" />
						<jdoc:include type="modules" name="right_dostercios" style="xhtml" />
					</div>
				<?php } else { ?>
					<div class="entero">
						<jdoc:include type="message" style="xhtml" />
						<jdoc:include type="modules" name="right_top" style="xhtml" />
						<jdoc:include type="component" />
						<jdoc:include type="modules" name="right_dostercios" style="xhtml" />
					</div>
				<?php } ?>
					<div class="clear">&nbsp;</div>
				</div><!-- Fin content -->
			</div><!-- Fin container -->
			<div id="footer">
				<div class="unmedio"><div class="banner_bl"><jdoc:include type="modules" name="banner_bottom_l" style="xhtml" /></div></div>
				<div class="unmedio"><div class="banner_br"><jdoc:include type="modules" name="banner_bottom_r" style="xhtml" /></div></div>
				<div id="menu_footer"><jdoc:include type="modules" name="menu_footer" style="xhtml" /></div>
				<div id="patrocinadores"><jdoc:include type="modules" name="patrocinadores" style="xhtml" /></div>
				<div style="margin:0 0 20px 0; padding:10px 0; text-align: center; font-size: 11px; font-weight:bold; clear:both">Web desarrollada por <a href="http://www.linkatic.com" target="_blank">Linkatic</a></div>
			</div>
		</div>
		<jdoc:include type="modules" name="debug" />
		<script type="text/javascript">
		
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-19622265-1']);
		  _gaq.push(['_setDomainName', 'none']);
		  _gaq.push(['_setAllowLinker', true]);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		
		</script>
	</body>
</html>