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
		<script language="javascript" type="text/javascript">
		<!--
		function resethit(task, id, div)
		{
			var form = document.adminForm;
				
			var res = new reseter();
		    res.reset( task, id, div, 'entries' );
		}
		
		function submitbutton(pressbutton)
		{
			var form = document.adminForm;

			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			var text = <?php echo $this->editor->getContent( 'text' ); ?>
			if (form.title.value == ""){
				alert( "<?php echo JText::_( 'ENTRY MUST HAVE A TITLE', true ); ?>" );
			} else if (form.catid.value == ""){
 				alert( "<?php echo JText::_( 'YOU MUST SELECT A CATEGORY', true ); ?>" );
			} else if (text == ""){
				alert( "<?php echo JText::_( 'ENTRY MUST HAVE SOME TEXT', true ); ?>" );
			} else {
				<?php
				echo $this->editor->save( 'text' );
				?>
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td valign="top">
				<?php echo $this->loadTemplate('details'); ?>			
			</td>
			<td valign="top" width="320" style="padding: 7px 0 0 5px">

				<?php echo $this->loadTemplate('stats');

				$title = JText::_( 'ENTRY OPTIONS' );
				echo $this->pane->startPane("content-pane");
				echo $this->pane->startPanel( $title, "detail-page" );
				echo $this->form->render('details');

				$title = JText::_( 'ENTRY SETTINGS' );
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( $title, "settings-page" );
				echo $this->form->render('params', 'settings');

				$title = JText::_( 'TAGS' );
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( $title, "tags-page" );
				?>
				<table class="paramlist admintable" cellspacing="1" width="100%">
					<tr>
						<td class="paramlist_key" width="40%"><label for="title"><?php echo JText::_( 'AVAILABLE TAGS' ); ?></label></td>
						<td class="paramlist_value">
						    <ul class="checklist">
						        <?php echo $this->lists['tags']; ?>
						    </ul>
						</td>
					</tr>
					<tr>
						<td class="paramlist_key" width="40%">
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'ADD TAGS' ); ?>::<?php echo JText::_( 'DETAILTAGS' ); ?>">
							<?php echo JText::_( 'ADD TAGS' ); ?>
							</span>						
						</td>
						<td class="paramlist_value"><input id="tagname" name="tagname" class="inputbox" size="30" type="text"></td>
					</tr>
				</table>
				<?php
				$title = JText::_( 'METADATA INFORMATION' );
				echo $this->pane->endPanel();
				echo $this->pane->startPanel( $title, "metadata-page" );
				echo $this->form->render('meta', 'metadata');

				echo $this->pane->endPanel();
				echo $this->pane->endPane();
			?>
			</td>
		</tr>
		</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_lyftenbloggie" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="created_by" value="<?php echo $this->row->created_by; ?>" />
<input type="hidden" name="controller" value="entries" />
<input type="hidden" name="view" value="entry" />
<input type="hidden" name="cid[]" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="mask" value="0" />
<input type="hidden" name="version" value="<?php echo $this->row->version; ?>" />
<input type="hidden" name="hits" value="<?php echo $this->row->hits; ?>" />
</form>

</div>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>