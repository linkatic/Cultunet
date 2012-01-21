<?php
/**
 * @version		$Id: admin.mtimporter.html.php 843 2010-02-04 11:43:03Z CY $
 * @package		MT Importer
 * @copyright	(C) 2004-2010 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

class HTML_mtimporter {
	
	function check_jcontent( &$sections ) {
		JToolBarHelper::title(  JText::_( 'Import data from Joomla\'s content and web links' ), 'addedit.png' );
	?>
	<form action="index.php" method="post" name="adminForm">
	<table class="adminform">
		<tr valign="top">
			<td align="left">
				<p /><b>Select the sections you wish to import to Mosets Tree</b> and then <b>press the Import button</b	>.<p />These sections and its categories and content will be imported to the root directory. Please note that most mambot calls (eg: {mosimage}, {mospagebreak} etc.) will not work in a Mosets Tree listing.<p />Since you're importing data from another component, you need to perform "<b>Recount Cats/Listings</b>" after the import process is completed. This function will recount the number of categories and listings you have in Mosets Tree.
			</td>
		</tr>
	</table>
	<p align="left" />
	<table class="adminlist">
		<thead>
			<th width="20">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $sections );?>);" />
			</th>
			<th class="title" width="76%">
			Section Name
			</th>
			<th width="12%" nowrap="nowrap">
			# Categories
			</th>
			<th width="12%" nowrap="nowrap">
			# Content Items
			</th>
		</thead><?php
		$k = 0;
		for ( $i=0, $n=count( $sections ); $i < $n; $i++ ) {
			$row = &$sections[$i];
			JFilterOutput::objectHTMLSafe($row);
			$link = 'index.php?option=com_sections&scope=content&task=editA&hidemainmenu=1&id='. $row->id;
			$checked = JHTML::_('grid.checkedout',  $row, $i);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td width="20" align="right"><?php echo $i+1; ?></td>
				<td width="20"><?php echo $checked; ?></td>
				<td width="35%"><?php echo $row->title; ?></td>
				<td align="center"><?php echo $row->categories; ?></td>
				<td align="center"><?php echo $row->contentitems; ?></td>
				<?php
				$k = 1 - $k;
				?>
			</tr>
			<?php
		}
		?>
		<tr><th colspan="5" align="left"></th></tr>
		</table>
	<p align="left" />
	<input type="submit" value="Import" />
	
	<input type="hidden" name="task" value="import_jcontent" />
	<input type="hidden" name="option" value="com_mtimporter" />
	</form>
	
	<?php
	}
	
	function check_mosdir( &$pt_count, &$mt_count, &$custom_fields, &$cust ) {
		JToolBarHelper::title(  JText::_( 'Import data from mosDirectory 2.2x' ), 'addedit.png' );
	?>
<form action="index.php" method="post" name="adminForm">
<table class="adminform">
	<tr valign="top">
		<td width="33%" align="left">
		<h1>Step 1</h1>

		<table class="adminform">
			<tr><td><h3>Pre-check and warning</h3>
				<font color="Blue"><b>Introduction</b></font>: MT Importer will import all categories and listings from mosDirectory 2.2.x to Mosets Tree version 2.1.x.<br /><br />			
			
				<font color="Green"><b>Requirement</b></font>: You must have the <b>correct version</b> of mosDirectory and Mosets Tree installed before you can use this Importer.<br /><br />

				<font color="Red"><b>WARNING</b></font>: This importer will delete all your current listing in Mosets Tree before importing any data from mosDirectory. Please backup your database if you do not wish to delete these information.<br /><br />
			</td></tr>

			
			
			<tr><td><u><b>mosDirectory</b></u></td></tr>
			<tr><td>Number of Categories: <b><?php echo (($pt_count['cats'] >= 0) ? $pt_count['cats'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>
			<tr><td>Number of Listings: <b><?php echo (($pt_count['listings'] >= 0) ? $pt_count['listings'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>
			<tr><td>Custom Fields: <b><?php echo (($custom_fields > 0) ? count($custom_fields) : "<font color=\"Red\">No table found</font>" ) ?></b></td></tr>

			<tr><td>&nbsp;</td></tr>

			<tr><td><u><b>Mosets Tree</b></u></td></tr>
			<tr><td>Number of Categories: <b><?php echo $mt_count['cats'] ?></b></td></tr>
			<tr><td>Number of Listings: <b><?php echo $mt_count['listings'] ?></b></td></tr>

		</table>
		</td>	

		<td width="33%" align="left">
		<h1>Step 2</h1>

		<table class="adminform">

		<tr>
			<td colspan="2">
				<h3>Custom Fields</h3>
				Options below allow you to map custom fields from mosDirectory to Tree's custom fields. Please make sure the fields are mapped to a compatible fieldtype, otherwise the values will not be displayed properly.<br /><br />
			</td>
		</tr>
		
		<tr><td width="40%"><b><u>mosDirectory field</u></b></td><td width="90%"><b><u>Mosets Tree's custom field</u></b></td></tr>

		<?php if ( count($custom_fields) > 0 ) { 
			$i = 0;
			foreach( $custom_fields AS $cf ) {
				$i++;
				$cust_list = JHTML::_('select.genericlist', $cust, $cf->name, 'class="inputbox" size="1"', 'value', 'text', 0 );
		?>
		<tr><td><?php echo $cf->name ?>:</td><td><?php echo $cust_list ?></td></tr>
		<?php 
				} 
			}
		?>
		</table>

		</td>

		<td width="33%" align="left">
		<h1>Step 3</h1>
		<table class="adminform">
			<tr><td><h3>Import</h3>
				Click the "Import" button below to start the import process. You will be notified and redirected to Mosets Tree main page when the import is complete.<p />Since you're importing data from another component, you need to perform "<b>Recount Cats/Listings</b>" after the import process is complete. This function will recount the number of categories and listings you have in Mosets Tree.<br /><br />
			</td></tr>

			<tr><td><input type="submit" value="Import" <?php 
			if( $pt_count['cats'] <= 0 && $pt_count['listings'] <= 0 ) {
				echo 'disabled ';
			}
			?>/></td></tr>
		</table>
		</td>

	</tr>
</table>

<input type="hidden" name="task" value="import_mosdir" />
<input type="hidden" name="option" value="com_mtimporter" />

</form>
	<?php
	}

	function check_gossamer( &$pt_count, &$mt_count, $table_prefix='linksql_' ) {
		JToolBarHelper::title(  JText::_( 'Import data from Gossamer Links' ), 'addedit.png' );
	?>
<form action="index.php" method="post" name="adminForm">
<table class="adminform">
	<tr valign="top">
		<td width="50%" align="left">
		<h1>Step 1</h1>

		<table class="adminform">
			<tr><td><h3>Pre-check and warning</h3>
				<font color="Blue"><b>Introduction</b></font>: MT Importer will import all categories and listings from Gossamer Links to Mosets Tree version 1.5x.<br /><br />			

				<font color="Green"><b>Requirement</b></font>: You must have Mosets Tree version 1.5x installed and Gossamer Links table exists in the database before you can use this Importer.<br /><br />

				<font color="Red"><b>WARNING</b></font>: This importer will delete all your current listing in Mosets Tree before importing any data from Gossamer Links. Please backup your database if you do not wish to delete these information.<br /><br />
			</td></tr>

			
			
			<tr><td><u><b>Gossamer Links</b></u></td></tr>
			<tr><td>Number of Categories: <b><?php echo (($pt_count['cats'] >= 0) ? $pt_count['cats'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>
			<tr><td>Number of Related Categories: <b><?php echo (($pt_count['relcats'] >= 0) ? $pt_count['relcats'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>
			<tr><td>Number of Listings: <b><?php echo (($pt_count['listings'] >= 0) ? $pt_count['listings'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>
			<?php
			if($pt_count['cats'] == -1 && $pt_count['relcats'] == -1 && $pt_count['listings'] == -1) {
			?>
			<tr><td>If you are using a non-default table prefix, please enter the prefix and check again:</td></tr>
			<tr><td>
				<input type="text" name="table_prefix" size="10" value="<?php echo $table_prefix ?>" />
				<input type="button" value="Check Again" onclick="document.adminForm.task.value='check_gossamerlinks';document.adminForm.submit();" />
			</td></tr>
			<?php	
			} else {
			?>
			<input type="hidden" name="table_prefix" value="<?php echo $table_prefix ?>" />
			<?php
			}
			?>
			<tr><td>&nbsp;</td></tr>

			<tr><td><u><b>Mosets Tree</b></u></td></tr>
			<tr><td>Number of Categories: <b><?php echo $mt_count['cats'] ?></b></td></tr>
			<tr><td>Number of Listings: <b><?php echo $mt_count['listings'] ?></b></td></tr>

		</table>
		</td>	

		<td width="50%" align="left">
		<h1>Step 2</h1>
		<table class="adminform">
			<tr><td><h3>Import</h3>
				Click the "Import" button below to start the import process. You will be notified and redirected to Mosets Tree main page when the import is complete.<p />Since you're importing data from another source, you need to perform "<b>Recount Cats/Listings</b>" after the import process is complete. This function will recount the number of categories and listings you have in Mosets Tree.<br /><br />
			</td></tr>

			<tr><td><input type="submit" value="Import" <?php
				if($pt_count['cats'] == -1 && $pt_count['relcats'] == -1 && $pt_count['listings'] == -1) {
				echo 'disabled '	;
				}
				?>/></td></tr>
		</table>
		</td>

	</tr>
</table>

<input type="hidden" name="task" value="import_gossamerlinks" />
<input type="hidden" name="option" value="com_mtimporter" />

</form>
	<?php
	}

	function check_esyndicate( &$pt_count, &$mt_count, $table_prefix='v2206_' ) {
			JToolBarHelper::title(  JText::_( 'Import data from eSyndicate' ), 'addedit.png' );
		?>
	<form action="index.php" method="post" name="adminForm">
	<table class="adminform">
		<tr valign="top">
			<td width="50%" align="left">
			<h1>Step 1</h1>

			<table class="adminform">
				<tr><td><h3>Pre-check and warning</h3>
					<font color="Blue"><b>Introduction</b></font>: MT Importer will import all categories and listings from eSyndicate to Mosets Tree version 2.1x. The importer currently does not support importing of storage and file based field from eSyndicate.<br /><br />			

					<font color="Green"><b>Requirement</b></font>: You must have Mosets Tree version 2.1x installed and eSyndicate table exists in the database before you can use this Importer.<br /><br />

					<font color="Red"><b>WARNING</b></font>: This importer will delete all your current listings and categories in Mosets Tree before importing any data from eSyndicate. Please backup your database if you do not wish to delete these information.<br /><br />
				</td></tr>

				<tr><td><u><b>eSyndicate</b></u></td></tr>
				<tr><td>Number of Categories: <b><?php echo (($pt_count['cats'] >= 0) ? $pt_count['cats'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>
				<tr><td>Number of Listings: <b><?php echo (($pt_count['listings'] >= 0) ? $pt_count['listings'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>
				<?php
				if($pt_count['cats'] == -1 && $pt_count['listings'] == -1) {
				?>
				<tr><td>If you are using a non-default table prefix, please enter the prefix and check again:</td></tr>
				<tr><td>
					<input type="text" name="table_prefix" size="10" value="<?php echo $table_prefix ?>" />
					<input type="button" value="Check Again" onclick="document.adminForm.task.value='check_esyndicate';document.adminForm.submit();" />
				</td></tr>
				<?php	
				} else {
				?>
				<input type="hidden" name="table_prefix" value="<?php echo $table_prefix ?>" />
				<?php
				}
				?>
				<tr><td>&nbsp;</td></tr>

				<tr><td><u><b>Mosets Tree</b></u></td></tr>
				<tr><td>Number of Categories: <b><?php echo $mt_count['cats'] ?></b></td></tr>
				<tr><td>Number of Listings: <b><?php echo $mt_count['listings'] ?></b></td></tr>

			</table>
			</td>	

			<td width="50%" align="left">
			<h1>Step 2</h1>
			<table class="adminform">
				<tr><td><h3>Import</h3>
					Click the "Import" button below to start the import process. You will be notified and redirected to Mosets Tree main page when the import is complete.<p />Since you're importing data from another source, you need to perform "<b>Recount Cats/Listings</b>" after the import process is complete. This function will recount the number of categories and listings you have in Mosets Tree.<br /><br />
				</td></tr>

				<tr><td><input type="submit" value="Import" <?php
					if($pt_count['cats'] == -1 && $pt_count['listings'] == -1) {
					echo 'disabled '	;
					}
					?>/></td></tr>
			</table>
			</td>

		</tr>
	</table>

	<input type="hidden" name="task" value="import_esyndicate" />
	<input type="hidden" name="option" value="com_mtimporter" />

	</form>
		<?php
	}

	function check_bookmarks( &$pt_count, &$mt_count, &$custom_fields, &$cust ) {
			JToolBarHelper::title(  JText::_( 'Import data from Bookmarks 2.7' ), 'addedit.png' );
		?>
	<form action="index.php" method="post" name="adminForm">
	<table class="adminform">
		<tr valign="top">
			<td width="33%" align="left">
			<h1>Step 1</h1>

			<table class="adminform">
				<tr><td><h3>Pre-check and warning</h3>
					<font color="Blue"><b>Introduction</b></font>: MT Importer will import all categories and listings from Bookmarks 2.7 to Mosets Tree version 2.1.x.<br /><br />			

					<font color="Green"><b>Requirement</b></font>: You must have the <b>correct version</b> of Bookmarks and Mosets Tree installed before you can use this Importer.<br /><br />

					<font color="Red"><b>WARNING</b></font>: This importer will delete all your current listing in Mosets Tree before importing any data from mosDirectory. Please backup your database if you do not wish to delete these information.<br /><br />
				</td></tr>



				<tr><td><u><b>Bookmarks</b></u></td></tr>
				<tr><td>Number of Categories: <b><?php echo (($pt_count['cats'] >= 0) ? $pt_count['cats'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>
				<tr><td>Number of Listings: <b><?php echo (($pt_count['listings'] >= 0) ? $pt_count['listings'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>
				<tr><td>Custom Fields: <b><?php echo (($custom_fields > 0) ? count($custom_fields) : "<font color=\"Red\">No table found</font>" ) ?></b></td></tr>

				<tr><td>&nbsp;</td></tr>

				<tr><td><u><b>Mosets Tree</b></u></td></tr>
				<tr><td>Number of Categories: <b><?php echo $mt_count['cats'] ?></b></td></tr>
				<tr><td>Number of Listings: <b><?php echo $mt_count['listings'] ?></b></td></tr>

			</table>
			</td>	

			<td width="33%" align="left">
			<h1>Step 2</h1>

			<table class="adminform">

			<tr>
				<td colspan="2">
					<h3>Custom Fields</h3>
					Options below allow you to map custom fields from Bookmarks to Tree's custom fields. Make sure the fields are mapped to a compatible fieldtype, otherwise the values will not be displayed correctly.<br /><br />
				</td>
			</tr>

			<tr><td width="40%"><b><u>Bookmarks field</u></b></td><td width="90%"><b><u>Mosets Tree's custom field</u></b></td></tr>

			<?php if ( count($custom_fields) > 0 ) { 
				$i = 0;
				foreach( $custom_fields AS $cf ) {
					$i++;
					$cust_list = JHTML::_('select.genericlist', $cust, $cf->name, 'class="inputbox" size="1"', 'value', 'text', 0 );
			?>
			<tr><td><?php echo $cf->title . ' ('.$cf->name.')' ?>:</td><td><?php echo $cust_list ?></td></tr>
			<?php 
					} 
				}
			?>
			</table>

			</td>

			<td width="33%" align="left">
			<h1>Step 3</h1>
			<table class="adminform">
				<tr><td><h3>Import</h3>
					Click the "Import" button below to start the import process. You will be notified and redirected to Mosets Tree main page when the import is complete.<p />Since you're importing data from another component, you need to perform "<b>Recount Cats/Listings</b>" after the import process is complete. This function will recount the number of categories and listings you have in Mosets Tree.<br /><br />
				</td></tr>

				<tr><td><input type="submit" value="Import" <?php 
				if( $pt_count['cats'] <= 0 && $pt_count['listings'] <= 0 ) {
					echo 'disabled ';
				}
				?>/></td></tr>
			</table>
			</td>

		</tr>
	</table>

	<input type="hidden" name="task" value="import_bookmarks" />
	<input type="hidden" name="option" value="com_mtimporter" />

	</form>
		<?php
		}
		
		function check_remository( &$pt_count, &$mt_count ) {
				JToolBarHelper::title(  JText::_( 'Import data from Remository 3.5' ), 'addedit.png' );
			?>
		<form action="index.php" method="post" name="adminForm">
		<table class="adminform">
			<tr valign="top">
				<td width="33%" align="left">
				<h1>Step 1</h1>

				<table class="adminform">
					<tr><td><h3>Pre-check and warning</h3>
						<font color="Blue"><b>Introduction</b></font>: MT Importer will import all containers and files from Remository 3.5 to Mosets Tree version 2.1.x.<br /><br />			

						<font color="Green"><b>Requirement</b></font>: You must have the <b>correct version</b> of Remository and Mosets Tree installed before you can use this Importer.<br /><br />

						<font color="Red"><b>WARNING</b></font>: This importer will delete all your current listing in Mosets Tree before importing any data from Remository. Please backup your database if you do not wish to delete these information.<br /><br />
					</td></tr>



					<tr><td><u><b>Remository</b></u></td></tr>
					<tr><td>Number of Categories: <b><?php echo (($pt_count['cats'] >= 0) ? $pt_count['cats'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>
					<tr><td>Number of Listings: <b><?php echo (($pt_count['listings'] >= 0) ? $pt_count['listings'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>

					<tr><td>&nbsp;</td></tr>

					<tr><td><u><b>Mosets Tree</b></u></td></tr>
					<tr><td>Number of Categories: <b><?php echo $mt_count['cats'] ?></b></td></tr>
					<tr><td>Number of Listings: <b><?php echo $mt_count['listings'] ?></b></td></tr>

				</table>
				</td>	

				<td width="33%" align="left">
				<h1>Step 2</h1>
				<table class="adminform">
					<tr><td><h3>Import</h3>
						Click the "Import" button below to start the import process. You will be notified and redirected to Mosets Tree main page when the import is complete.<p />Since you're importing data from another component, you need to perform "<b>Recount Cats/Listings</b>" after the import process is complete. This function will recount the number of categories and listings you have in Mosets Tree.<br /><br />
					</td></tr>

					<tr><td><input type="submit" value="Import" <?php 
					if( $pt_count['cats'] <= 0 && $pt_count['listings'] <= 0 ) {
						echo 'disabled ';
					}
					?>/></td></tr>
				</table>
				</td>

			</tr>
		</table>

		<input type="hidden" name="task" value="import_remository" />
		<input type="hidden" name="option" value="com_mtimporter" />

		</form>
			<?php
			}

		function check_sobi2( &$pt_count, &$mt_count ) {
					JToolBarHelper::title(  JText::_( 'Import data from SOBI2' ), 'addedit.png' );
				?>
			<form action="index.php" method="post" name="adminForm">
			<table class="adminform">
				<tr valign="top">
					<td width="33%" align="left">
					<h1>Step 1</h1>

					<table class="adminform">
						<tr><td><h3>Pre-check and warning</h3>
							<font color="Blue"><b>Introduction</b></font>: MT Importer will import all entries and categories from SOBI2 to Mosets Tree version 2.1.x.<br /><br />			

							<font color="Green"><b>Requirement</b></font>: You must have the <b>correct version</b> of SOBI2 2.9.x and Mosets Tree installed before you can use this Importer.<br /><br />

							<font color="Red"><b>WARNING</b></font>: This importer will delete all your current listings in Mosets Tree before importing any data from SOBI2. Please backup your database if you do not wish to delete these information.<br /><br />
						</td></tr>



						<tr><td><u><b>SOBI2</b></u></td></tr>
						<tr><td>Number of Categories: <b><?php echo (($pt_count['cats'] >= 0) ? $pt_count['cats'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>
						<tr><td>Number of Entries: <b><?php echo (($pt_count['listings'] >= 0) ? $pt_count['listings'] : "<font color=\"Red\">No table found</font>") ?></b></td></tr>

						<tr><td>&nbsp;</td></tr>

						<tr><td><u><b>Mosets Tree</b></u></td></tr>
						<tr><td>Number of Categories: <b><?php echo $mt_count['cats'] ?></b></td></tr>
						<tr><td>Number of Listings: <b><?php echo $mt_count['listings'] ?></b></td></tr>

					</table>
					</td>	

					<td width="33%" align="left">
					<h1>Step 2</h1>
					<table class="adminform">
						<tr><td><h3>Import</h3>
							Click the "Import" button below to start the import process. You will be notified and redirected to Mosets Tree main page when the import is complete.<p />Since you're importing data from another component, you need to perform "<b>Recount Cats/Listings</b>" after the import process is complete. This function will recount the number of categories and listings you have in Mosets Tree.<br /><br />
						</td></tr>

						<tr><td><input type="submit" value="Import" <?php 
						if( $pt_count['cats'] <= 0 && $pt_count['listings'] <= 0 ) {
							echo 'disabled ';
						}
						?>/></td></tr>
					</table>
					</td>

				</tr>
			</table>

			<input type="hidden" name="task" value="import_sobi2" />
			<input type="hidden" name="option" value="com_mtimporter" />

			</form>
				<?php
				}

	function check_csv() {
		global $mainframe;
		JToolBarHelper::title(  JText::_( 'Import data from Comma Seperated Values (CSV) File' ), 'addedit.png' );
	?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

<table cellspacing=10>
	<tr valign="top">

		<td width="70%" align="left">
		<h1>Step 1: Introduction</h1>

		<table class="adminform">
			<tr><td>
				This Importer will import all listings from a <i>.csv</i> files. <a href="components/com_mtimporter/sample.csv">Download a sample</a> and start by adding your listings to the file. Please bear in mind the following when adding listings:
				<ul>
					<li>The first line of <i>sample.csv</i> contains the list of column names that map to Moset Tree's database. Only the first column - <b>link_name</b> is compulsory. Other columns are optional and can be safely removed. If you're removing a column, make sure you remove the corresponding values for the listings.</li>
					<li>Second line and onwards is where you insert your data. One line for each listing. In <i>sample.csv</i>, the second line is filled with one sample listing.</li>
					<li>You may use Microsoft Excel or any other word processor to edit the file. Make sure you do not save the formatting when prompted.</li>
					<li>Enter Category ID to the <b>cat_id</b> field. This information can be found when you're browsing the category. If no cat_id is specified, Importer will import the listing to Root category (0). To import a listing to more than one category, specify the category IDs separated by command. ie: "2,6,17"</li>
					<li>Enter User ID to the <b>user_id</b> field. This information can be found from your database table called <i><?php echo $mainframe->getCfg('dbprefix'); ?>users</i>. If no user_id column is specified, the listing will be owned by <b>admin</b> by default.</li>
					<li>If you want a particular listing to be featured, set <b>link_featured</b> field to <i>1</i>, otherwise set it to <i>0</i>.</li>
					<li>There is no need to enter <b>link_published</b> or <b>link_approved</b>'s value. All imported listings will be published and approved automatically. </li>
					<li>To import custom values, the ID of the custom field will be the column name. In the sample, the last 2 columns are mapped to custom fields with ID 25 and 26. You can locate these IDs at <a href="<?php echo JURI::base(); ?>/administrator/index.php?option=com_mtree&amp;task=customfields">Custom Fields</a> page.</li>
					<li>If you have multiple values in a column (Checkbox or Multiple select box), separate each values with the bar character. For example - value1|value2|value3</li>
					<li>The field separator is comma (,) and the fields should be enclosed by double quote if the values contains comma.</li>
				</ul>
				
				<p />
				What this Importer <i>doesn't</i> do:
				<ul>
					<li>It does not support importing files or binary based data.</li>
					<li>It does not create categories. You have to create the categories first before starting the import.</li>
				</ul>
				
				<p />

				<font color="Red"><b>WARNING</b></font>: <b>PLEASE BACKUP YOUR DATABASE BEFORE PROCEEDING TO THE NEXT STEP.</b> Although we have done everything possible to minimize the risk of database corruption, accident do happens once a while. Backing up your database is the best protection to this.<br /><br />
			</td></tr>
		</table>
		</td>	

		<td width="30%" align="left">
		<h1>Step 2: Import</h1>
		<table class="adminform">
			<tr><td>
				Select your <i>.csv</i> file and click "Import" button below to start the import process. You will be notified and redirected to Mosets Tree main page when the import is completed.<p />Since you're importing data from another source, you need to perform "<b>Recount Cats/Listings</b>" after the import process is complete. This function will recount the number of categories and listings you have in Mosets Tree.<br />
			</td></tr>

			<tr><td>
				CSV File: <input class="text_area" type="file" name="file_csv" />
				<p />
				<input type="submit" value="Import" />
			</td></tr>
		</table>
		</td>
	</tr>
</table>

<input type="hidden" name="task" value="import_csv" />
<input type="hidden" name="option" value="com_mtimporter" />

</form>
	<?php
	}

}
?>