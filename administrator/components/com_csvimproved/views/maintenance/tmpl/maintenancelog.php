<?php
/**
 * Maintenance log
 *
 * @package CSVImproved
 * @subpackage Maintenance
 * @author Roland Dalmulder
 * @link http://www.csvimproved.com
 * @copyright Copyright (C) 2006 - 2009 RolandD Cyber Produksi
 * @version $Id: maintenancelog.php 890 2009-04-23 17:02:24Z Suami $
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
		
		$logcount = array();
		for ($i=1, $n=count( $logresult ); $i <= $n; $i++) {
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
								echo '<a onclick="jQuery(\'#'.$rid.'\').toggle();" title="'.JText::_('SHOW_HIDE').'"> '.JText::_('SHOW_HIDE').'</a></span>';
								$msgbox .= '<div id="'.$rid.'" class="debug">'.$details['message'].'</div>';
								if (!array_key_exists($row['result'], $logcount)) $logcount[$row['result']] = 1;
								else $logcount[$row['result']]++;
							}
							echo $msgbox;
						}
					?>
				</td>
			</tr>
			<?php
			}
			$k = 1 - $k;
		}
		?>
		</table>
		<br />
		<table class="adminlist">
		<thead>
		<tr><th colspan="<?php echo count($logcount); ?>"><?php echo JText::_('Totals'); ?></th></tr>
		</thead>
		<tr>
		<?php foreach ($logcount as $result => $count) {
			echo '<th>'.JText::_($result).': '.$count.'</th>';
		} ?>
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
