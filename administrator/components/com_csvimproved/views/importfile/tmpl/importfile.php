<?php
/**
 * Import file
 *
 * @package CSVImproved
 * @subpackage Import
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: importfile.php 850 2009-03-19 02:24:42Z Suami $
 */
 
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' ); 
$csvilog = JRequest::getVar('csvilog');

/* Display any messages there are */
if (!empty($csvilog->logmessage)) echo $csvilog->logmessage;
else {
   ?>
   <form action="index.php" method="post" name="adminForm">
	<table class="adminlist">
		<thead>
		<tr>
			<th colspan="4" class="message"><?php echo JText::_('Results for').' '.JRequest::getVar('user_filename'); ?></th>
		</tr>
		</thead>
		<tr>
			<th class="title" width="5%">
			<?php echo JText::_('Line'); ?>
			</th>
			<th class="title">
			<?php echo JText::_('Result'); ?>
			</th>
			<th class="title">
			<?php echo JText::_('Message'); ?>
			</th>
		</tr>
		<?php
		$k = 0;
		$logresult = $csvilog->GetStats();
		
		for ($i=1, $n=count($logresult); $i <= $n; $i++) {
			if (isset($logresult[$i])) {
				$row = $logresult[$i];
			?>
			<tr class="<?php echo 'row'. $k; ?>">
				<td align="center">
					<?php echo $i; ?>
				</td>
				<td>
					<?php echo $row['result']; ?>
				</td>
				<td width="80%">
					<?php
						$msgbox = '';
						if (count($row['status']) > 0) {
							foreach ($row['status'] as $result => $details) {
								echo '<span class="msgtitle">'.JText::_($result).' :: ';
								$rid = rand();
								echo '<a onclick="switchMenu(\''.$rid.'\');" title="'.JText::_('SHOW_HIDE').'"> '.JText::_('SHOW_HIDE').'</a></span>';
								$msgbox .= '<div id="'.$rid.'" class="debug">'.$details['message'].'</div>';
							}
							echo $msgbox;
						}
					?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
			}
		}
		/* Get the logcount for statistics */
		$logcount = JRequest::getVar('logcount');
		?>
		</table>
		<br />
		<table class="adminlist">
		<thead>
		<tr><th colspan="<?php echo count($logcount); ?>"><?php echo JText::_('Totals'); ?></th></tr>
		</thead>
		<tr>
		<?php
		if (is_array($logcount) && count($logcount) > 0) {
			foreach ($logcount as $result => $count) {
			echo '<th>'.JText::_($result).': '.$count.'</th>';
			}
		} 
		?>
		</tr>
		</table>
	<input type="hidden" name="option" value="com_csvimproved" />
	<input type="hidden" name="task" value="import" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="import" />
</form>
<br clear="all" />
<?php
   /* Show debug log */
   if ($csvilog->debug_message != '') { ?>
	   <table class="adminlist">
	   <thead><tr><th><?php echo JText::_('Debug message'); ?></th></tr></thead>
	   <tr><td><?php echo $csvilog->debug_message; ?></td></tr>
	   </table>
   <?php }
}
?>
