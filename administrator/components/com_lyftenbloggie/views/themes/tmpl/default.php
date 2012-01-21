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
global $mainframe;
?>
<?php echo BlogSystemFun::getSideMenu('1', 'Settings'); ?>
<script language="javascript" type="text/javascript">
<!--
	function submitbutton1(pressbutton) {
		var form = document.adminForm;

		// do field validation
		if (form.install_package.value == ""){
			alert( "Please select a theme from your computer to install" );
		} else {
			form.task.value = 'doinstall';
			form.submit();
		}
	}
//-->
</script>
<h2 class="settingstitle"><?php echo JText::_('THEMES'); ?></h2>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
	<table class="adminform">
		<tr>

			<td width="140">
				<label for="install_package"><?php echo JText::_('UPLOAD THEME PACKAGE FILE'); ?>:</label>
			</td>
			<td>
				<input class="input_box" id="install_package" name="install_package" size="57" type="file">
				<input class="button" value="Upload File &amp; Install" onclick="submitbutton1()" type="button">
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
			<th width="5"></th>
			<th class="title"><?php echo Jtext::_('TITLE'); ?></th>
			<th width="15%"><?php echo Jtext::_('VERSION'); ?></th>
			<th width="5%"><?php echo Jtext::_('DEFAULT'); ?></th>
			<th width="15%"><?php echo Jtext::_('DATE'); ?></th>
			<th width="15%"><?php echo Jtext::_('AUTHOR'); ?></th>
			<th width="1%" nowrap="nowrap"><?php echo JText::_( 'ID' ); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="8">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<?php
		$k = 0;
		$i = 0;
		$n = count($this->rows);
		
		foreach ($this->rows as $row) {
			$link 		= 'index.php?option=com_lyftenbloggie&amp;controller=themes&amp;task=edit&amp;cid[]='. $row->id;
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td width="7"><input id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" type="radio"></td>
			<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->title; ?></a></td>
			<td align="center"><?php echo $row->version; ?></td>
			<td align="center">
			<?php if ($row->is_default == 1) { ?>
				<img src="templates/khepri/images/menu/icon-16-default.png"/>
			<?php } else { ?>
				&nbsp;
			<?php } ?>
			</td>
			<td align="center"><?php echo $row->create_date; ?></td>
			<td align="center"><?php echo $row->author; ?></td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php 
		$k = 1 - $k;
        $i++;
		}
		if($i == 0) {
		?>
		<tr>
			<td align="center" colspan="8"><?php echo JText::_( 'THERE ARE NO THEMES SETUP' ); ?></td>
		</tr>
		<?php 		
		}
		?>
	</tbody>

	</table>
	
	<input type="hidden" name="boxchecked" value="0" />	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="controller" value="themes" />
	<input type="hidden" name="view" value="themes" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
	</td>
</tr>
</table>