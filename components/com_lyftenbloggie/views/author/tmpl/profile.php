<?php


//Redirecionamos para que no se pueda utilizar esta funcionalidad del blog
//que permitiria cambiar los datos del pérfil
//LOS DATOS DEL PERFIL DEL USUARIO SE CAMBIAN DESDE EL COMPONENTE JOMSOCIAL

header( 'Location: index.php?option=com_lyftenbloggie&view=lyftenbloggie&category=0&Itemid=21' ); 

/**
 * LyftenBloggie 1.1.0 - Joomla! Blog Manager
 * @package LyftenBloggie 1.1.0
 * @copyright (C) 2009-2010 Lyften Designs
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.lyften.com/ Official website
 **/
 
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<h3><?php echo JText::_('GESTION BLOG'); ?></h3>
<table class="contentpaneopen">
<tbody><tr>
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.authorForm;
	if (pressbutton == 'cancelprofile') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (form.name.value == ""){
		alert( "<?php echo JText::_( 'ADD AUTHOR NAME' ); ?>" );
	} else {
		submitform( pressbutton );
	}
}

function submitform(pressbutton){
	if (pressbutton) {
		document.authorForm.task.value=pressbutton;
	}
	if (typeof document.authorForm.onsubmit == "function") {
		document.authorForm.onsubmit();
	}
	document.authorForm.submit();
}
</script>
<form action="<?php echo JURI::base() ?>index.php" method="post" name="authorForm" id="authorForm" enctype="multipart/form-data">
<td valign="top">
	<div id="lyftenbloggie" class="lyftenbloggie">
		<div class="dashboard">
			<div class="dash_title">
				<h1><?php echo JText::_('MY PROFILE'); ?></h1>
				<div class="menu_blog clrfix">
					<ul>
						<li><a href="<?php echo JRoute::_('/'.LyftenBloggieHelperRoute::getAccountRoute()); ?>"><span><span><?php echo JText::_('ALL ENTRIES'); ?></span></span></a></li>
						<li><a href="<?php echo JRoute::_('/'.LyftenBloggieHelperRoute::getAccountRoute('pending')); ?>"><span><span><?php echo JText::_('PENDING ENTRIES'); ?></span></span></a></li>
						<li><a href="<?php echo JRoute::_('/'.LyftenBloggieHelperRoute::getAccountRoute('form')); ?>"><span><span><?php echo JText::_('NEW ENTRY'); ?></span></span></a></li>
						<?php /* <li><a href="<?php echo JRoute::_(LyftenBloggieHelperRoute::getAccountRoute('profile')); ?>" class="active"><span><span><?php echo JText::_('MY PROFILE'); ?></span></span></a></li> */?>
					</ul>
				</div>
			</div>
			<div id="dash_sub_menu">
				<div id="tab_1" class="active" onclick="tabsClass.switchTab(this);"><?php echo JText::_('BASIC INFORMATION'); ?></div>
				<?php if($this->avatarUsed == 'default') { ?>
				<div id="tab_2" class="notactive" onclick="tabsClass.switchTab(this);"><?php echo JText::_('AVATAR'); ?></div>
				<?php } ?>
				<span class="settings_actions">
					<a href="javascript:void(null);" class="rbutton" onclick="return submitbutton('saveauthor')"><?php echo JText::_('SAVE'); ?></a>&nbsp;
					<a href="javascript:void(null);" class="rbutton" onclick="submitbutton('cancelprofile')"><?php echo JText::_('CANCEL'); ?></a>
				</span>
			</div>
			<div class="entryForm">

		<div id="tab_1_data">
	     	<fieldset>
      			<legend>Basic Data</legend>
      			<ol>
					<li>
						<label class="field-title"><?php echo JText::_('AUTHOR NAME'); ?>:<em>*</em></label>
						<label>
							<input name="name" class="txtbox-long" tabindex="1" value="<?php echo $this->author->get('username'); ?>" size="50" maxlength="100" type="text">
						</label>
						<span class="clearer">&nbsp;</span>
					</li>

					<li class="even">
						<label class="field-title"><?php echo JText::_('ABOUT BLOCK');?></label>
						<label>
							<textarea name="about" cols="50" rows="9" id="about"><?php echo $this->author->get('about');?></textarea>
						</label>
						<span class="clearer">&nbsp;</span>
					</li>
					<li>
						<label class="field-title"><?php echo JText::_('FACEBOOK URL');?></label>
						<label>
							<input name="params[facebookURL]" id="paramsfacebookURL" value="<?php echo $this->author->get('attrib.facebookURL'); ?>" class="txtbox-long" type="text" />
						</label>
						<span class="clearer">&nbsp;</span>
					</li>	
					<li class="even">
						<label class="field-title"><?php echo JText::_('DIGG URL');?></label>
						<label>
							<input name="params[diggURL]" id="paramsdiggURL" value="<?php echo $this->author->get('attrib.diggURL'); ?>" class="txtbox-long" type="text" />
						</label>
						<span class="clearer">&nbsp;</span>
					</li>	
					<li>
						<label class="field-title"><?php echo JText::_('DELICIOUS URL');?></label>
						<label>
							<input name="params[deliciousURL]" id="paramsdeliciousURL" value="<?php echo $this->author->get('attrib.deliciousURL'); ?>" class="txtbox-long" type="text" />
						</label>
						<span class="clearer">&nbsp;</span>
					</li>	
					<li class="even">
						<label class="field-title"><?php echo JText::_('TECHNORATI URL');?></label>
						<label>
							<input name="params[technoratiURL]" id="paramstechnoratiURL" value="<?php echo $this->author->get('attrib.technoratiURL'); ?>" class="txtbox-long" type="text" />
						</label>
						<span class="clearer">&nbsp;</span>
					</li>	
					<li>
						<label class="field-title"><?php echo JText::_('TWITTER URL');?></label>
						<label>
							<input name="params[twitterURL]" id="paramstwitterURL" value="<?php echo $this->author->get('attrib.twitterURL'); ?>" class="txtbox-long" type="text" />
						</label>
						<span class="clearer">&nbsp;</span>
					</li>	
					<li class="even">
						<label class="field-title"><?php echo JText::_('FLICKR URL');?></label>
						<label>
							<input name="params[flickrURL]" id="paramsflickrURL" value="<?php echo $this->author->get('attrib.flickrURL'); ?>" class="txtbox-long" type="text" />
						</label>
						<span class="clearer">&nbsp;</span>
					</li>	
					<li>
						<label class="field-title"><?php echo JText::_('MYBLOGLOG URL');?></label>
						<label>
							<input name="params[mybloglogURL]" id="paramsmybloglogURL" value="<?php echo $this->author->get('attrib.mybloglogURL'); ?>" class="txtbox-long" type="text" />
						</label>
						<span class="clearer">&nbsp;</span>
					</li>	
					<li class="even">
						<label class="field-title"><?php echo JText::_('FRIENDFEED URL');?></label>
						<label>
							<input name="params[ffindURL]" id="paramsffindURL" value="<?php echo $this->author->get('attrib.ffindURL'); ?>" class="txtbox-long" type="text" />
						</label>
						<span class="clearer">&nbsp;</span>
					</li>	
	      		</ol>
      		</fieldset>
		</div>

		<?php if($this->avatarUsed == 'default') { ?>
		<div id="tab_2_data" style="display: none;">
	     	<fieldset>
      			<legend>Avatar</legend>
      			<ol>
					<li class="even">
						<label class="field-title"><?php echo JText::_('AVATAR'); ?>:</label>
						<label><input class="txtbox-long" type="file" id="avatar" name="avatar" /><br /><small><?php echo JText::_('UPLOADING A NEW AVATAR WILL DELETE THE OLD ONE'); ?></small></label>
						<span class="clearer">&nbsp;</span>
					</li>
					<?php if($this->author->get('avatar')) : ?>
					<li class="even">
						<label class="field-title"><?php echo JText::_('CURRENT AVATAR'); ?>:</label>
						<label><img src="<?php echo $this->author->get('avatar'); ?>"/></label>
						<span class="clearer">&nbsp;</span>
					</li>
				<?php endif; ?>
	      		</ol>
      		</fieldset>
		</div>
		<?php } ?>


	</div>
	<input type="hidden" name="task" value="saveauthor" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<div class="clear"></div>
</div>
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
<script type="text/javascript">
<!--
	tabsClass.addTabs("dash_sub_menu");
-->
</script>
			<br style="clear:left;"/>
		</div>
	</div>
</td>
</tr>
</tbody></table>