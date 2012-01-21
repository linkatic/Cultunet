<?php

/**
 * 
 * @version		3.0.0
 * @package		Joomla
 * @subpackage	Photoslide GK3
 * @copyright	Copyright (C) 2008 - 2009 GavickPro. All rights reserved.
 * @license		GNU/GPL
 * 
 * ==========================================================================
 * 
 * Check system html.
 * 
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// getting client variable
$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

?>

<?php if($modal_news == 1) : ?>	
<link rel="stylesheet" type="text/css" href="<?php echo $uri->root(); ?>administrator/components/com_gk3_photoslide/interface/css/check.system.css" media="all" />
<?php endif; ?>

<?php if($modal_news == 0) : ?>	
<div id="wrapper">
<?php endif; ?>

	<?php if($modal_news == 0) : ?>	
	<?php ViewNavigation::generate(array(JText::_("CHECK_SYSTEM") => 'option=com_gk3_photoslide&c=check_system')); ?>
	<?php endif; ?>
	
	<div id="info">
	
		<p><?php echo JText::_('COMPONENT_VERSION'); ?> <?php echo $systemcheck->version; ?></p>
		
		<h3><?php echo JText::_('CHECK_FOR_UPDATE'); ?> <button id="checker" onclick="checkk(event);"><?php echo JText::_('CHECK_FOR_UPDATE'); ?></button></h3>
		<script type="text/javascript">
		function checkk(e){
			var event = new Event(e);
			var version='3.0.0';
			var el=event.target;
			el.innerHTML='<?php echo JText::_('SC_LOADING');?>';
			el.disabled=true;
			new Asset.javascript('http://update.gavick.com/index.php/json/data/components/com_gk3_photoslide',{
				onload: function(){	
					var info=new Element('div');
					var content='';
					if($defined($G_available['components'])){
						if($defined($G_available['components']['com_gk3_photoslide'])){
							if($G_available.components['com_gk3_photoslide'].version != version){
								content='<a href="http://update.gavick.com/index.php/update/html/components/com_gk3_photoslide/'+$G_available.components['com_gk3_photoslide'].version+'"><?php echo JText::_('NEW_VERSION');?> ('+$G_available.components['com_gk3_photoslide'].version+')</a>';
							}else{
								content='<?php echo JText::_('NO_NEW_VERSION');?>';
							}
						}
						else{
							content='<?php echo JText::_('NO_NEW_VERSION');?>';
						}	
					}
					else{
						content='<?php echo JText::_('NO_NEW_VERSION');?>';
					}
					info.innerHTML=content;
					info.injectAfter(el);
					el.remove();
				}
			});
			
			if(window.ie){
				var timerr = (function(){
					if($defined($G_available)){
						$clear(timerr);
						var info=new Element('div');
						var content='';
						if($defined($G_available['components'])){
							if($defined($G_available['components']['com_gk3_photoslide'])){
								if($G_available.components['com_gk3_photoslide'].version != version){
									content='<a href="http://update.gavick.com/index.php/update/html/components/com_gk3_photoslide/'+$G_available.components['com_gk3_photoslide'].version+'"><?php echo JText::_('NEW_VERSION');?> ('+$G_available.components['com_gk3_photoslide'].version+')</a>';
								}else{
									content='<?php echo JText::_('NO_NEW_VERSION');?>';
								}
							}
							else{
								content='<?php echo JText::_('NO_NEW_VERSION');?>';
							}	
						}
						else{
							content='<?php echo JText::_('NO_NEW_VERSION');?>';
						}
						info.innerHTML=content;
						info.injectAfter(el);
						el.remove();
					}
				}).periodical(250);
			}
		}
		</script>
		
		<h3><?php echo JText::_('GD_STATUS_INFO'); ?></h3>

		<p><?php echo JText::_('GDSTATUS'); ?> <?php $systemcheck->GDStatus(); ?></p>
		<p><?php echo JText::_('GDVERSION'); ?> <?php $systemcheck->GDVersion(); ?></p>
		<p><?php echo JText::_('GDPNGSUPPORT'); ?> <?php $systemcheck->PNGSupport(); ?></p>
		
		<h3><?php echo JText::_('CATALOGS_PERMISSIONS'); ?></h3>
		
		<table class="adminlist">
			<thead>
				<tr>
					<th width="4%" class="title" align="center">#</th>
					<th width="48%" class="title" align="center"><?php echo JText::_('CATALOG'); ?></th>
					<th width="48%" class="title" align="center"><?php echo JText::_('PERMISSIONS'); ?></th>
				</tr>
			</thead>
			<tfoot>
					<tr>
						<td colspan="3"><?php echo JText::_('CHECK_SYSTEM_FOOT_INFO_CATALOGS'); ?></td>
					</tr>
			</tfoot>
			<tbody>
				<tr>
					<td align="center">1</td>
					<td align="center">/</td>
					<td align="center"><?php $systemcheck->folderStatus(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'); ?></td>
				</tr>
				<tr>
					<td align="center">2</td>
					<td align="center">/original/</td>
					<td align="center"><?php $systemcheck->folderStatus(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'original'); ?></td>
				</tr>
				<tr>
					<td align="center">3</td>
					<td align="center">/thumbs_small/</td>
					<td align="center"><?php $systemcheck->folderStatus(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_small'); ?></td>
				</tr>
				<tr>
					<td align="center">4</td>
					<td align="center">/thumbs_big/</td>
					<td align="center"><?php $systemcheck->folderStatus(JPATH_SITE.DS.'components'.DS.'com_gk3_photoslide'.DS.'thumbs_big'); ?></td>
				</tr>
			</tbody>
		</table>
		
		<h3><?php echo JText::_('TABLE_STATUS'); ?></h3>	
		
		<table class="adminlist">
			<thead>
				<tr>
					<th width="4%" class="title" align="center">#</th>
					<th width="48%" class="title" align="center"><?php echo JText::_('TABLE'); ?></th>
					<th width="48%" class="title" align="center"><?php echo JText::_('TABLE_EXIST'); ?></th>
				</tr>
			</thead>
			<tfoot>
					<tr>
						<td colspan="3"><?php echo JText::_('CHECK_SYSTEM_FOOT_INFO'); ?></td>
					</tr>
			</tfoot>
			<tbody>
				<tr>
					<td align="center">1</td>
					<td align="center"><?php echo $systemcheck->prefix; ?>gk3_photoslide_groups</td>
					<td align="center"><?php $systemcheck->DBTableStatus('gk3_photoslide_groups');?></td>
				</tr>
				<tr>
					<td align="center">2</td>
					<td align="center"><?php echo $systemcheck->prefix; ?>gk3_photoslide_slides</td>
					<td align="center"><?php $systemcheck->DBTableStatus('gk3_photoslide_slides');?></td>
				</tr>
				<tr>
					<td align="center">3</td>
					<td align="center"><?php echo $systemcheck->prefix; ?>gk3_photoslide_options</td>
					<td align="center"><?php $systemcheck->DBTableStatus('gk3_photoslide_options');?></td>
				</tr>
			</tbody>
		</table>


	</div>
	
<?php if($modal_news == 0) : ?>		
</div>

<form action="index.php" method="get" name="adminForm">
	<input type="hidden" name="option" value="com_gk3_photoslide" />
	<input type="hidden" name="client" value="<?php echo $client->id;?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="c" value="check_system" />
	<input type="hidden" name="boxchecked" value="0" />
</form>	

<?php endif; ?>