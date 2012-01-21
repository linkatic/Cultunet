<?php
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
<style type="text/css">
<?php if($this->editType == 'joomla') { ?>
.mce_editable{background: #9C9C9C; border: 3px solid #D5D5D5;width:92%;}
.mceLayout{padding:0;margin:0;}
.mceToolbarTop, .mceToolbarBottom {background: #D5D5D5; width:92%}
a.mceButtonNormal img, a.mceButtonSelected img {border: 1px solid #959595 !important;}
a.mceButtonSelected img {background-color: #959595;}
a.mceButtonNormal img:hover, a.mceButtonSelected img:hover {background-color: #959595;}
a.mceButtonDisabled img {border: 1px solid #959595 !important;}
td.mceStatusbar{background:#D5D5D5;}
.nicEdit-main {background-color:#ffffff;}
.nicEdit-panel {background-color: #fff !important;}
.nicEdit-button {background-color: #fff !important;}
<?php } ?>
#editor-xtd-buttons{margin:-10px 0 0 0;padding:0;}
.button2-left {margin-top:10px;padding-left:7px;*padding-right:10px;}
.button2-left a,.button2-left span {padding: 3px 6px 0 6px;}
.button2-left,.button2-left div {background:none;float: right;text-align:center;}
.button2-left .image a, .button2-left .readmore a, .button2-left .blank a{color:#4B6F6F;}
.button2-left .image, .button2-left .readmore, .button2-left .blank {padding: 2px 3px;margin-right: 4px;text-decoration: none;border-width: 1px;border-color: #CACACA;border-style: solid;font-weight: bold;background:#CACACA;-moz-border-radius: 2px;-khtml-border-radius: 2px;-webkit-border-radius: 2px;border-radius: 2px;}
</style>
<h3><?php echo JText::_('GESTION BLOG'); ?></h3>
<div class="border-container bg-container">
<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
<script language="javascript" type="text/javascript">
<!--
	function submitbutton(pressbutton)
	{
		var form = document.postForm;

		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		var text = <?php echo $this->editor->getContent( 'text' ); ?>
		if (document.postForm.title.value == ""){
			alert( "<?php echo JText::_( 'ENTRY MUST HAVE A TITLE', true ); ?>" );
		} else if (form.catid.value == ""){
				alert( "<?php echo JText::_( 'YOU MUST SELECT A CATEGORY', true ); ?>" );
		} else if (text == ""){
			alert( "<?php echo JText::_( 'ENTRY MUST HAVE SOME TEXT', true ); ?>" );
		} else {
			<?php echo $this->editor->save( 'text' ); ?>
			submitform( pressbutton );
		}

	}

	function submitform(pressbutton){
		if (pressbutton) {
			document.postForm.task.value=pressbutton;
		}
		if (typeof document.postForm.onsubmit == "function") {
			document.postForm.onsubmit();
		}
		document.postForm.submit();
	}
<?php if($this->photo != 'avatar') { ?>
	function changeImage(name)
	{
		document.getElementById('entryimg').src='<?php echo $this->author_folders['url'].'/display/'; ?>'+name;
		return;
	}
<?php } ?>
<?php if(method_exists($this->editor, 'javascript')) {
echo $this->editor->javascript();
} ?>
//-->
</script>
<form action="<?php echo JURI::base() ?>index.php" method="post" name="postForm" id="postForm" enctype="multipart/form-data">
<div id="lyftenbloggie" class="lyftenbloggie">
	<div class="dashboard">
		<div class="dash_title">
			<h1><?php echo $this->title; ?></h1>
			<div class="menu_blog clrfix">
				<ul>
					<li><a href="<?php echo JRoute::_('/'.LyftenBloggieHelperRoute::getAccountRoute()); ?>"><span><span><?php echo JText::_('ALL ENTRIES'); ?></span></span></a></li>
					<?php if(BloggieFactory::allowAccess('admin.can_approve')) { ?>
					<li><a href="<?php echo JRoute::_('/'.LyftenBloggieHelperRoute::getAccountRoute('pending')); ?>"><span><span><?php echo JText::_('PENDING ENTRIES'); ?></span></span></a></li>
					<?php } ?>
					<li><a href="#" class="active"><span><span><?php echo (!$this->row->id) ? JText::_('NEW ENTRY') : JText::_('EDIT ENTRY'); ?></span></span></a></li>
					<?php /* <li><a href="<?php echo JRoute::_(LyftenBloggieHelperRoute::getAccountRoute('profile')); ?>"><span><span><?php echo JText::_('MY PROFILE'); ?></span></span></a></li> */?>
				</ul>
			</div>
		</div>
		<div id="dash_sub_menu">
			<div id="tab_1" class="active" onclick="tabsClass.switchTab(this);"><?php echo JText::_('CONTENT'); ?></div>
			<?php if($this->photo != 'avatar') { ?>
			<div id="tab_2" class="notactive" onclick="tabsClass.switchTab(this);"><?php echo JText::_('DISPLAY IMAGE'); ?></div>
			<?php } ?>
			<div id="tab_3" class="notactive" onclick="tabsClass.switchTab(this);"><?php echo JText::_('TAGS'); ?></div>
			
			<?php /*
			<div id="tab_4" class="notactive" onclick="tabsClass.switchTab(this);"><?php echo JText::_('TRACKBACKS'); ?></div>
			<div id="tab_5" class="notactive" onclick="tabsClass.switchTab(this);"><?php echo JText::_('METADATA'); ?></div>
						*/ ?>
			<div id="tab_6" class="notactive" style="border:0 none;"onclick="tabsClass.switchTab(this);"><?php echo JText::_('SETTINGS'); ?></div>
			<span class="settings_actions">
				<a href="javascript:void(null);" class="rbutton" onclick="submitbutton('save')"><?php echo JText::_('SAVE'); ?></a>&nbsp;
				<a href="javascript:void(null);" class="rbutton" onclick="submitbutton('cancel')"><?php echo JText::_('CANCEL'); ?></a>
			</span>
		</div>
		<div class="entryForm">

		<div id="tab_1_data">
	     	<fieldset>
      			<legend>Entry Content</legend>
      			<ol>
      				<li class="even">
						<label class="field-title"><?php echo JText::_('TITLE'); ?>:<em>*</em></label>
						<label>
							<input id="title" name="title" class="txtbox-long" tabindex="1" value="<?php echo $this->row->title; ?>" type="text">
							<span id="title_error" class="form-error"></span>
						</label>
						<span class="clearer">&nbsp;</span>
					</li>
					<li class="even">
						<label class="field-title"><?php echo JText::_('CATEGORY'); ?>:</label>
						<label><?php echo $this->lists['catid']; ?> <span onmouseout="HideHelp('category_hlp');" onmouseover="ShowHelp('category_hlp', '<?php echo JText::_('CATEGORY'); ?>', '<?php echo JText::_('CATEGORIES DESC FULL'); ?>')" class="helptp">[?]</span><div style="display: none;" id="category_hlp"></div></label>
						<span class="clearer">&nbsp;</span>
					</li>
					<li>
						<label class="field-title"><?php echo JText::_('SLUG'); ?>:</label>
						<label>
							<input id="alias" name="alias" class="txtbox-long" maxlength="255" tabindex="1" value="<?php echo $this->row->alias; ?>" type="text"> <span onmouseout="HideHelp('slug_hlp');" onmouseover="ShowHelp('slug_hlp', '<?php echo JText::_('SLUG'); ?>', '<?php echo JText::_('SLUG DESC FULL'); ?>')" class="helptp">[?]</span><div style="display: none;" id="slug_hlp"></div>
						</label>
						<span class="clearer">&nbsp;</span>
					</li>
      				<li class="even" style="padding:10px 0px 10px 0;">
						<?php echo $this->editor->display( 'text',  $this->row->text, '100%;', '350', '75', '20', array('pagebreak') ) ; ?>
						<span class="clearer">&nbsp;</span>
					</li>
					<?php if($this->row->modified != $this->nullDate) { ?>
					<li>
						<?php echo JText::_('LAST EDITED BY'); ?> <?php echo $this->row->author->username; ?><br />
						On <?php echo JHTML::_('date', $this->row->modified, '%b %d, %Y at %I:%M %p'); ?>
					</li>	     				
					<?php } ?>
      			</ol>
      		</fieldset>
		</div>
		<?php if($this->photo != 'avatar') { ?>
		<div id="tab_2_data" style="display: none;">
			<fieldset>
				<legend>Entry Display Image</legend>
				<ol>
					<?php if(BloggieFactory::allowAccess('author.can_upload')) { ?>
      				<li class="even">
						<label class="field-title"><?php echo JText::_('UPLOAD'); ?>:</label>
						<label>
							<input id="upload" name="upload" type="file" class="txtbox-long">
						</label>
						<span class="clearer">&nbsp;</span>
					</li>
					<?php } ?>
					<li>
						<label class="field-title"><?php echo JText::_('ON SERVER'); ?>:</label>
						<label>
							<?php echo $this->lists['images']; ?>
						</label>
						<?php echo $this->lists['image']; ?>
						<span class="clearer">&nbsp;</span>
					</li>
				</ol>
			</fieldset>
		</div>
		<?php } ?>

		<div id="tab_3_data" style="display: none;">
			<fieldset>
				<legend>Entry Tags</legend>
				<ol>
					<li class="even">
						<div class="field-title"><?php echo JText::_('TAGS'); ?>:</div>
						<div>
							<ul class="taglist">
								<?php echo $this->lists['tags']; ?>
							</ul>
							<p><?php echo JText::_('TAGS DESC FULL'); ?></p>
						</div>
						<span class="clearer">&nbsp;</span>
					</li>
					<?php if(BloggieFactory::allowAccess('author.create_tags')) { ?>
      				<li>
						<label class="field-title"><?php echo JText::_('ADD TAG'); ?>:</label>
						<label>
							<input id="tagname" name="tagname" class="txtbox-long" type="text"> <span onmouseout="HideHelp('tagname_hlp');" onmouseover="ShowHelp('tagname_hlp', '<?php echo JText::_('ADD TAG'); ?>', '<?php echo JText::_('ADD TAG DESC'); ?>')" class="helptp">[?]</span><div style="display: none;" id="tagname_hlp"></div>
						</label>
						<span class="clearer">&nbsp;</span>
					</li>
					<?php } ?>
				</ol>
			</fieldset>
		</div>

		<div id="tab_4_data" style="display: none;">
			<fieldset>
				<legend>Entry Trackbacks</legend>
				<ol>
					<li class="even"<?php echo (empty($this->row->pinged)) ? ' style="border-bottom:0 none;"' : ''; ?>>
						<label class="field-title"><?php echo JText::_('TRACKBACKS'); ?>:</label>
						<label>
							<input name="trackbacks" style="width: 400px;" id="trackback" class="txtbox-long" tabindex="7" value="" type="text">
							<br /><small><?php echo JText::_('TRACKBACKS DESC FULL'); ?></small>
						</label>
						<span class="clearer">&nbsp;</span>
					</li>			

					<?php if(!empty($this->row->pinged)) { ?>
					<li>
						<table>
						<tr>
						<td valign="top"><label class="field-title"><?php echo JText::_('ALREADY PINGED'); ?>:</label></td>
						<td valign="top"><label>
						<?php
						foreach($this->row->pinged as $ping)
						{
							echo ($ping) ? '<p style="padding-left:20px;margin:0;">&#149;&nbsp;'.$ping.'</p>' : '';
						} ?>
						</label></td>
						</tr>
						</table>
						<span class="clearer">&nbsp;</span>
					</li>
					<?php } ?>
				</ol>
			</fieldset>
		</div>

		<div id="tab_5_data" style="display: none;">
			<fieldset>
				<legend>Entry metadata</legend>
				<ol>
					<li class="even">
						<label class="field-title"><?php echo JText::_('DESCRIPTION'); ?>:</label>
						<label>
							<textarea name="meta[description]" cols="30" rows="5" id="metadescription"><?php echo $this->row->metadesc; ?></textarea>
						</label>
						<span class="clearer">&nbsp;</span>
					</li>
					<li>
						<label class="field-title"><?php echo JText::_('KEYWORDS'); ?>:</label>
						<label>
							<textarea name="meta[keywords]" cols="30" rows="5" id="metakeywords"><?php echo $this->row->metakey; ?></textarea>
						</label>
						<span class="clearer">&nbsp;</span>
					</li>
					<li class="even">
						<label class="field-title"><?php echo JText::_('AUTHOR'); ?>:</label>
						<label>
							<input name="meta[author]" id="metaauthor" value="<?php echo $this->form->get('author'); ?>" class="txtbox-long" size="20" type="text">
						</label>
						<span class="clearer">&nbsp;</span>
					</li>
					<li>
						<label class="field-title"><?php echo JText::_('ROBOTS'); ?>:</label>
						<label>
							<input name="meta[robots]" id="metarobots" value="<?php echo $this->form->get('robots'); ?>" class="txtbox-long" size="20" type="text">
						</label>
						<span class="clearer">&nbsp;</span>
					</li>
				</ol>
			</fieldset>
		</div>

		<div id="tab_6_data" style="display: none;">
			<fieldset>
				<legend>Entry Settings</legend>
				<ol>
					<li class="even">
						<label class="field-title"><?php echo JText::_('PUBLISH STATUS'); ?>:</label>
						<label>
							<?php echo $this->lists['state']; ?>
						</label>
						<span class="clearer">&nbsp;</span>
					</li>	
					<li>
						<label class="field-title"><?php echo JText::_('PUBLISH'); ?>: </label>
						<label>
							<?php echo $this->lists['created']; ?>
						</label>
						<span class="clearer">&nbsp;</span>
					</li>	
					
					<?php /*     				
					<li class="even">
						<label class="field-title"><?php echo JText::_('ACCESS LEVEL'); ?>:</label>
						<label>
							<?php echo $this->lists['access']; ?>
						</label>
						<span class="clearer">&nbsp;</span>
					</li>
					*/?>
					<?php if($this->lists['comments']) { ?>
					<li>
						<label class="field-title"><?php echo JText::_('COMMENTS'); ?>: </label>
						<label>
							<?php echo $this->lists['comments']; ?>
						</label>
						<span class="clearer">&nbsp;</span>
					</li>
					<?php } ?>	
				</ol>
			</fieldset>
		</div>
		</div>
	</div>
</div>
<input type="hidden" name="option" value="com_lyftenbloggie" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="referer" value="<?php echo @$_SERVER['HTTP_REFERER']; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
<div class="clear"></div>
<?php JHTML::_('behavior.keepalive'); ?>
<script type="text/javascript">
<!--
	tabsClass.addTabs("dash_sub_menu");
-->
</script>
</td>
</tr>
</tbody></table>
</div>