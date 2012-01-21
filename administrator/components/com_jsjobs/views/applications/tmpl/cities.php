<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/views/applications/tmpl/category.php
 ^ 
 * Description: Default template for categories view
 ^ 
 * History:		NONE
 ^ 
 */
 
// this is the basic listing scene when you click on the component 
// in the component menu
defined('_JEXEC') or die('Restricted access');
JRequest :: setVar('layout', 'cities');
$_SESSION['cur_layout']='cities';

?>
<table width="100%">
	<tr>
		<td align="left" width="175" valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
			<form action="index.php" method="post" name="adminForm">
			<table class="adminlist" border="0">
				<thead>
					<tr><td colspan="3">
					<?php 
						if (isset($_SESSION['js_countrycode'])) $countrycode = $_SESSION['js_countrycode']; 
						if (isset($_SESSION['js_statecode'])) $statecode = $_SESSION['js_statecode']; 
					?>
						<a href="index.php?option=com_jsjobs&task=view&layout=countries"><?php echo JText::_('JS_COUNTRIES'); ?></a> >
						<a href="index.php?option=com_jsjobs&task=view&layout=states&ct=<?php echo $countrycode; ?>" ><?php echo JText::_('JS_STATES'); ?></a> >
						<a href="index.php?option=com_jsjobs&task=view&layout=counties&sd=<?php echo $statecode; ?>" ><?php echo JText::_('JS_COUNTIES'); ?> </a>
					</td></tr>
					<tr>
						<th width="20">
							<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
						</th>
						<th  width="60%" class="title"><?php echo JText::_('NAME'); ?></th>
						<th><?php echo JText::_('JS_PUBLISHED'); ?></th>
					</tr>
				</thead>
			<?php
			jimport('joomla.filter.output');
			$k = 0;
				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
				$row =& $this->items[$i];
				$checked = JHTML::_('grid.id', $i, $row->id);
				$link = JFilterOutput::ampReplace('index.php?option='.$option.'&task=edit&cid[]='.$row->id);
				?>
				<tr valign="top" class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>">
						<?php echo $row->name; ?></a>
					</td>
					<td align="center">
						<?php
							if ($row->enabled == 'Y')$img 	= 'tick.png'; else $img 	= 'publish_x.png';
						?>
						<img src="images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;">
			<?php
				include_once('components/com_jsjobs/views/jscr.php');
			?>
			</td>
			</tr></table>
		</td>
	</tr>
	
</table>							