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
<?php echo BlogSystemFun::getSideMenu('1', 'Settings'); ?>
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	
	var form = document.adminForm;
	
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (form.title.value == ""){
		alert( "<?php echo JText::_( 'PLEASE ENTER AN TITLE' ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
function checkit(id)
{
	var form = document.adminForm;
	form.discountaction[id].checked=true
	return;
}
</script>

<div class="brezza">

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-60">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'DETAILS' ); ?></legend>
	<table class="admintable">
		<tr>
			<td width="100" class="key">
				<label for="title">
					<?php echo JText::_( 'TITLE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="35" value="<?php echo $this->rows->title; ?>" />
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'DEFAULT' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['default']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'DESCRIPTION' ); ?>:
			</td>
			<td>
				<?php echo JText::_( $this->rows->description ); ?>
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="col width-40">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'PARAMETERS' ); ?></legend>
	<?php
		jimport('joomla.html.pane');
        // TODO: allowAllClose should default true in J!1.6, so remove the array when it does.
		$pane = &JPane::getInstance('sliders', array('allowAllClose' => true));
		echo $pane->startPane('plugin-pane');
		echo $pane->startPanel(JText::_('PLUGIN PARAMETERS'), 'param-page');
		if($output = $this->rows->params->render('params')) :
			echo $output;
		else :
			echo "<div style=\"text-align: center; padding: 5px; \">".JText::_('THERE ARE NO PARAMETERS FOR THIS ITEM')."</div>";
		endif;
		echo $pane->endPanel();

		if ($this->rows->params->getNumParams('advanced')) {
			echo $pane->startPanel(JText :: _('ADVANCED PARAMETERS'), "advanced-page");
			if($output = $this->rows->params->render('params', 'advanced')) :
				echo $output;
			else :
				echo "<div  style=\"text-align: center; padding: 5px; \">".JText::_('THERE ARE NO ADVANCED PARAMETERS FOR THIS ITEM')."</div>";
			endif;
			echo $pane->endPanel();
		}
		echo $pane->endPane();
	?>
	</fieldset>
</div>
<div class="clr"></div>
</div>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_lyftenbloggie" />
<input type="hidden" name="id" value="<?php echo $this->rows->id; ?>" />
<input type="hidden" name="controller" value="themes" />
<input type="hidden" name="view" value="themes" />
<input type="hidden" name="task" value="" />
</form>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
	</td>
</tr>
</table>