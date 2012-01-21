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

<h2 class="settingstitle"><?php echo JText::_('INSTALL ADDONS FROM LYFTEN'); ?></h2>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'NUM' ); ?></th>
			<th width="1%"></th>
			<th class="title"><?php echo Jtext::_('TITLE'); ?></th>
			<th width="30%"><?php echo Jtext::_('DESCRIPTION'); ?></th>
			<th width="85px"><?php echo Jtext::_('RATING'); ?></th>
			<th width="10%"><?php echo Jtext::_('TYPE'); ?></th>
			<th width="15%"><?php echo Jtext::_('VERSION'); ?></th>
			<th width="10%"><?php echo Jtext::_('HOMEPAGE'); ?></th>
			<th width="2%"><?php echo Jtext::_('ID'); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="9">
				<del class="container"><div class="pagination">
					<?php echo $this->pageNav->getPagesLinks(); ?>
					<div class="limit"><?php echo $this->pageNav->getPagesCounter(); ?></div>
					<input type="hidden" name="limitstart" value="<?php echo $this->pageNav->limitstart; ?>" />
				</div></del>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<?php
		if(isset($this->rows['brezza']['plugins']))
		{
			$k = 0;
			$i = 0;
			foreach($this->rows['brezza']['plugins'] as $key=>$plugin)
			{
				$url = ($plugin['url']) ? '<a href="'.$plugin['url'].'" target="_blank">'.JText::_('VISIT').'</a>' : '';
				$type = JText::_('EXT_TYPE_'.strtoupper($plugin['type']));
				$type = ($type == 'EXT_TYPE_'.strtoupper($plugin['type'])) ? $plugin['type'] : $type;

				$rating = JText::_('Not Yet Rated');
				if(isset($plugin['rate']))
				{
					list($votes, $minus) = explode('|', $plugin['rate']);

					//sql calculation doesn't work with negative values and thus only minus votes will not be taken into account
					if ($votes == 0 && $minus != 0) {
						$rating = JText::sprintf('Rated Negative', $minus);
					} elseif ($votes == 0 && $minus == 0) {
						$rating = JText::_('Not Yet Rated');
					}

					//we do the rounding here and not in the query to get better ordering results
					$rating = round($votes);
					$rating  = '<div class="rating_bar"><div style="width:'.$rating.'%"></div></div>';
				}

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
				<td width="7" align="center"><input id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo base64_encode($plugin['install']); ?>" onclick="isChecked(this.checked);" type="radio"></td>
				<td align="left"><a href="#" onclick="javascript:document.adminForm.cb<?php echo $i; ?>.checked = true;submitbutton('install');"><?php echo urldecode($plugin['title']); ?></a></td>
				<td align="left"><?php echo urldecode($plugin['about']); ?></td>
				<td align="center"><?php echo $rating; ?></td>
				<td align="center"><?php echo $type; ?></td>
				<td align="center"><?php echo $plugin['version']; ?></td>
				<td align="center"><?php echo $url; ?></td>
				<td align="center"><?php echo $plugin['id'] ?></td>
			</tr>
			<?php
				$k = 1 - $k;
				$i++;
			}
		}else{
		?>
		<tr>
			<td align="center" colspan="9"><?php echo ($this->notice) ? $this->notice : JText::_( 'THERE ARE NO ADDONS AVAILABLE' ); ?></td>
		</tr>
		<?php 		
		}
		?>
	</tbody>

	</table>
	
	<input type="hidden" name="boxchecked" value="0" />	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_lyftenbloggie" />
	<input type="hidden" name="controller" value="addons" />
	<input type="hidden" name="view" value="addons" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
	</td>
</tr>
</table>