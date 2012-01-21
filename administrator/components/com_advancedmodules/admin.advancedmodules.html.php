<?php
/**
 * @package     Advanced Module Manager
 * @version     1.9.7
 *
 * @author      Peter van Westen <peter@nonumber.nl>
 * @link        http://www.nonumber.nl
 * @copyright   Copyright (C) 2010 NoNumber! All Rights Reserved
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * BASE ON JOOMLA CORE FILE:
 * /administrator/components/com_modules/admin.modules.html.php
 */

/**
* @version		$Id: admin.modules.html.php 11672 2009-03-08 20:39:41Z willebil $
* @package		Joomla
* @subpackage	Modules
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_PLUGINS.DS.'system'.DS.'nonumberelements'.DS.'helpers'.DS.'parameters.php';

/**
* @package		Joomla
* @subpackage	Modules
*/
class HTML_modules
{
	/**
	* Writes a list of the defined modules
	* @param array An array of category objects
	*/
	function view( &$rows, &$client, &$page, &$lists )
	{
		$user =& JFactory::getUser();

		//Ordering allowed ?
		$ordering = ( $lists['order'] == 'm.ordering' || $lists['order'] == 'm.position' );

		JHTML::_( 'behavior.tooltip' );
		$plugin = JPluginHelper::getPlugin( 'system', 'advancedmodules' );
		$parameters =& NNePparameters::getParameters();
		$pluginparams = '';
		if ( isset( $plugin->params ) ) {
			$pluginparams = $plugin->params;
		}
		$plugin_params = $parameters->getParams( $pluginparams, JPATH_SITE.DS.'plugins'.DS.'system'.DS.'advancedmodules.xml' );
		if ( $plugin_params->open_in_modals ) {
			JHTML::_( 'behavior.modal' );
		}
		?>
		<form action="<?php echo JRoute::_( 'index.php?option=com_advancedmodules'); ?>" method="post" target="" name="adminForm">
			<div style="float:left;padding:0px 2px;">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $lists['search']; ?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById( 'search' ).value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</div>
			<div style="float:right;padding:0px 2px;">
				<?php
					echo $lists['state'];
					echo $lists['type'];
					echo $lists['position'];
					echo $lists['template'];
					if ( $client->id == 0 ) {
						echo $lists['access'];
						echo '<span class="hasTip" title="'.JText::_( 'Select Menu Item Assignment' ).'::'.JText::_( 'Select Menu Item Assignment (description)' ).'">'
							.$lists['menuitems']
							.'</span>';
					}
				?>
			</div>
			<div style="clear:both;"></div>
			<table class="adminlist" cellspacing="1">
				<thead>
					<tr>
						<th width="20">
							<?php echo JText::_( 'NUM' ); ?>
						</th>
						<th width="20">
							<input type="checkbox" name="toggle" value="" onclick="checkAll( <?php echo count( $rows ); ?> );" />
						</th>
						<th class="title" style="text-align:left;">
							<?php echo JHTML::_('grid.sort', 'Module Name', 'm.title', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<?php if ( $plugin_params->show_tooltip != 1 ) { ?>
						<th style="text-align:left;">
							<?php echo JText::_( 'Description' ); ?>
						</th>
						<?php } ?>
						<th nowrap="nowrap" width="1%">
							<?php echo JHTML::_('grid.sort', 'Published', 'm.published', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th width="80" nowrap="nowrap">
							<?php echo JHTML::_('grid.sort', 'Order', 'm.ordering', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th width="1%">
							<?php if ( $ordering ) echo JHTML::_('grid.order',  $rows ); ?>
						</th>
						<?php
						if ( $client->id == 0 ) {
							?>
							<th nowrap="nowrap" width="1%">
								<?php echo JHTML::_('grid.sort', 'Access', 'groupname', @$lists['order_Dir'], @$lists['order'] ); ?>
							</th>
							<?php
						}
						?>
						<th nowrap="nowrap" width="1%">
							<?php echo JHTML::_('grid.sort',   'Position', 'm.position', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th nowrap="nowrap" width="10%" class="title" style="text-align:left;">
							<?php echo JHTML::_('grid.sort',   'Type', 'm.module', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th nowrap="nowrap" width="1%" style="text-align:left;">
							<?php echo JHTML::_('grid.sort',   'ID', 'm.id', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="12">
							<?php echo $page->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php
				$parameters =& NNePparameters::getParameters();

				$k = 0;
				for ( $i=0, $n=count( $rows ); $i < $n; $i++ ) {
					$row 	=& $rows[$i];

					$url = JRoute::_( 'index.php?option=com_advancedmodules&client='. $client->id .'&task=edit&cid[]='. $row->id );

					$tooltip = JText::_( 'Edit Module' ).'::'.htmlspecialchars( $row->title );
					$advanced_params = $parameters->getParams( $row->adv_params );
					if ( $plugin_params->show_tooltip != 2 && isset( $advanced_params->tooltip ) && $advanced_params->tooltip ) {
						$tooltip .= '<br /><em>'.htmlspecialchars( $advanced_params->tooltip ).'</em>';
					}

					$link = '<span class="editlinktip hasTip" title="'.$tooltip.'">'
							.'<a href="'.$url.'">'
								.$row->title
							.'</a>'
						.'</span> ';
					if ( $plugin_params->open_in_modals ) {
						$modal = '<span class="hasTip" title="'.$tooltip.'<br /><br /><strong>'.JText::_( 'Open in modal window' ).'</strong>">'
								.'<a href="'.$url.'&tmpl=component"'
								.' class="modal" rel="{handler: \'iframe\', size: {x:window.getSize().scrollSize.x-100, y: window.getSize().size.y-100}}">'
									.'<img src="components/com_advancedmodules/images/edit.png" alt="'.JText::_( 'Open in modal window' ).'" />'
								.'</a>'
							.'</span> ';
						$link = $modal.$link;
					}


					$access 	= JHTML::_('grid.access',   $row, $i );
					$checked 	= JHTML::_('grid.checkedout',   $row, $i );
					$published 	= JHTML::_('grid.published', $row, $i );
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="right">
							<?php echo $page->getRowOffset( $i ); ?>
						</td>
						<td width="20">
							<?php echo $checked; ?>
						</td>
						<td>
						<?php
						if (  JTable::isCheckedOut( $user->get ('id' ), $row->checked_out ) ) {
							echo $row->title;
						} else {
							echo $link;
						}
						?>
						</td>
						<?php
						if ( $plugin_params->show_tooltip != 1 ) { ?>
						<td>
							<?php if ( isset( $advanced_params->tooltip ) ) { echo $advanced_params->tooltip; } ?>
						</td>
						<?php } ?>
						<td align="center">
							<?php echo $published; ?>
						</td>
						<td class="order" colspan="2">
							<span><?php echo $page->orderUpIcon( $i, ( $row->position == @$rows[$i-1]->position ), 'orderup', 'Move Up', $ordering ); ?></span>
							<span><?php echo $page->orderDownIcon( $i, $n, ( $row->position == @$rows[$i+1]->position ),'orderdown', 'Move Down', $ordering ); ?></span>
							<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
							<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
						</td>
						<?php
						if ( $client->id == 0 ) {
							?>
							<td align="center">
								<?php echo $access; ?>
							</td>
							<?php
						}
						?>
						<td align="center">
							<?php echo $row->position; ?>
						</td>
						<td>
							<?php echo $row->module ? $row->module : JText::_( 'User' ); ?>
						</td>
						<td>
							<?php echo $row->id; ?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				</tbody>
			</table>

			<input type="hidden" name="option" value="com_advancedmodules" />
			<input type="hidden" name="client" value="<?php echo $client->id; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}

	function move( &$rows, &$client, &$lists )
	{
	?>
		<script language="javascript" type="text/javascript">
			function submitbutton( pressbutton ) {
				var form = document.adminForm;
				if ( pressbutton == 'cancel' ) {
					submitform( pressbutton );
					return;
				}

				// do field validation
				if ( !getSelectedValue( 'adminForm', 'position' ) ) {
					alert( "<?php echo JText::_( 'Please select a position from the list', true ); ?>" );
				} else {
					submitform( pressbutton );
				}
			}
		</script>

		<form action="index.php" method="post" name="adminForm">
			<table class="adminform">
				<tr>
					<td width="3%"></td>
					<td  valign="top" width="30%">
						<strong><?php echo JText::_( 'Move to Position' ); ?>:</strong>
						<br />
							<?php echo $lists['position']; ?>
						<br /><br />
					</td>
					<td  valign="top">
						<strong><?php echo JText::_( 'Modules being moved' ); ?>:</strong>
						<br />
						<ol>
						<?php foreach ( $rows as $module ) : ?>
							<li><?php echo $module->title; ?></li>
						<?php endforeach; ?>
						</ol>
					</td>
				</tr>
			</table>

			<input type="hidden" name="option" value="com_advancedmodules" />
			<input type="hidden" name="boxchecked" value="1" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="client" value="<?php echo $client->id; ?>" />
			<?php foreach ( $rows as $module ) : ?>
			<input type="hidden" name="cid[]" value="<?php echo $module->id; ?>" />
			<?php endforeach; ?>
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	<?php
	}

	/**
	* Writes the edit form for new and existing module
	*
	* A new record is defined when <var>$row</var> is passed with the <var>id</var>
	* property set to 0.
	* @param JTableCategory The category object
	* @param array <p>The modules of the left side.  The array elements are in the form
	* <var>$leftorder[<i>order</i>] = <i>label</i></var>
	* where <i>order</i> is the module order from the db table and <i>label</i> is a
	* text label associciated with the order.</p>
	* @param array See notes for leftorder
	* @param array An array of select lists
	* @param object Parameters
	*/
	function edit( &$model, &$row, &$orders2, &$lists, &$params, $client )
	{
		JRequest::setVar( 'hidemainmenu', 1 );

		// clean item data
		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'content' );

		$document =& JFactory::getDocument();
		$editor =& JFactory::getEditor();

		$document->addScript( JURI::root(true).'/plugins/system/nonumberelements/js/script.js' );
		$document->addScript( JURI::root(true).'/plugins/system/nonumberelements/elements/toggler.js' );

		$script = "
			function submitbutton( pressbutton ) {
				if ( ( pressbutton == 'save' || pressbutton == 'apply' ) && ( document.adminForm.title.value == '' ) ) {
					alert( '".JText::_( 'Module must have a title', true )."' );
				} else {
					if ( pressbutton == 'save' ) {
						document.adminForm.target = '_parent';
					}
		";
		if ( $row->module == '' || $row->module == 'mod_custom' ) {
			$script .= $editor->save( 'content' );
		}
		$script .= "
					submitform( pressbutton );
				}
			}
			var originalOrder 	= '".$row->ordering."';
			var originalPos 	= '".$row->position."';
			var orders 			= new Array();	// array in the format [key,value,text]
		";
		$i = 0;
		foreach ( $orders2 as $k => $items ) {
			foreach ( $items as $v ) {
				$script .= "\n".'	orders['.$i++.'] = new Array( "'.$k.'","'.$v->value.'","'.$v->text.'" );';
			}
		}
		$script .= "
			window.addEvent( 'domready', function() {
				if ( !nnTogglerSet ) {
					nnTogglerSet = new nnToggler();
				}
			});
		";
		$document->addScriptDeclaration( $script );

		$tmpl = JRequest::getCmd( 'tmpl' );

		if ( $tmpl == 'component' ) {
			HTML_modules::placeModalHeader( 'edit' );
		}

		jimport( 'joomla.html.pane' );
        // TODO: allowAllClose should default true in J!1.6, so remove the array when it does.
		$pane =& JPane::getInstance( 'sliders', array( 'allowAllClose' => true ) );

		JHTML::_( 'behavior.tooltip' );
		?>
		<form action="<?php echo JRoute::_( 'index.php'); ?>" method="post" name="adminForm">
		<div class="col width-50">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Details' ); ?></legend>

				<table class="admintable" cellspacing="1">
					<tr>
						<td valign="top" class="key">
							<?php echo JText::_( 'Module Type' ); ?>:
						</td>
						<td>
							<strong>
								<?php echo JText::_( $row->module ); ?>
							</strong>
						</td>
					</tr>
					<tr>
						<td class="key">
							<label for="title">
								<?php echo JText::_( 'Title' ); ?>:
							</label>
						</td>
						<td>
							<input class="text_area" type="text" name="title" id="title" size="50" value="<?php echo $row->title; ?>" />
						</td>
					</tr>
					<tr>
						<td width="100" class="key">
							<?php echo JText::_( 'Show title' ); ?>:
						</td>
						<td>
							<?php echo $lists['showtitle']; ?>
						</td>
					</tr>
					<tr>
						<td valign="top" class="key">
							<?php echo JText::_( 'Published' ); ?>:
						</td>
						<td>
							<?php echo $lists['published']; ?>
						</td>
					</tr>
				<?php
					if ( $row->client_id != 1 ) :
						$plugin = JPluginHelper::getPlugin( 'system', 'advancedmodules' );
						$parameters =& NNePparameters::getParameters();
						$plugin_params = $parameters->getParams( $plugin->params, JPATH_SITE.DS.'plugins'.DS.'system'.DS.'advancedmodules.xml' );
						if ( $plugin_params->show_hideempty ) :
				?>
					<tr>
						<td valign="top" class="key">
							<label for="hideempty" class="hasTip" title="<?php echo JText::_( 'Hide if empty' ); ?>::<?php echo JText::_( 'Hides the module if its output is empty' ); ?>">
								<?php echo JText::_( 'Hide if empty', 0 ); ?>:
							</label>
						</td>
						<td>
							<?php echo $lists['hideempty']; ?>
						</td>
					</tr>
				<?php
						endif;
					endif;
				?>
					<tr>
						<td valign="top" class="key">
							<label for="position" class="hasTip" title="<?php echo JText::_( 'MODULE_POSITION_TIP_TITLE', true); ?>::<?php echo JText::_('MODULE_POSITION_TIP_TEXT', true ); ?>">
								<?php echo JText::_( 'Position' ); ?>:
							</label>
						</td>
						<td>
							<input type="text" id="position" name="position" value="<?php echo $row->position; ?>"  onchange="form.position_select.value=this.value" />
							<select id="position_select" onchange="document.getElementById('position').value=this.options[this.selectedIndex].value;this.value=''" style="background-color:#F0F0F0;">
							<?php
								echo '<option value="">-- '.JText::_( 'Select Position' ).' --</option>';
								$positions = $model->getPositions();
								foreach ( $positions as $position ) {
									echo '<option value="'.$position.'">'.$position.'</option>';
								}
							?>
							</select>
						</td>
					</tr>

					<tr>
						<td valign="top"  class="key">
							<label for="ordering">
								<?php echo JText::_( 'Order' ); ?>:
							</label>
						</td>
						<td>
							<script language="javascript" type="text/javascript">
							<!--
							writeDynaList( 'class="inputbox" name="ordering" id="ordering" size="1"', orders, originalPos, originalPos, originalOrder );
							//-->
							</script>
						</td>
					</tr>
					<tr>
						<td valign="top" class="key">
							<label for="access">
								<?php echo JText::_( 'Access Level' ); ?>:
							</label>
						</td>
						<td>
							<?php echo $lists['access']; ?>
						</td>
					</tr>
					<tr>
						<td valign="top" class="key">
							<?php echo JText::_( 'ID' ); ?>:
						</td>
						<td>
							<?php echo $row->id; ?>
						</td>
					</tr>
					<tr>
						<td valign="top" class="key">
							<?php echo JText::_( 'Description' ); ?>:
						</td>
						<td>
							<?php
								echo JText::_( html_entity_decode( $row->description ) );
							?>
						</td>
					</tr>
				<?php
					if ( $row->client_id != 1 ) :
				?>
					<tr>
						<td valign="top" class="key">
							<label class="hasTip" title="<?php echo JText::_( 'Custom Description' ); ?>::<?php echo JText::_( 'Custom Description (description)' ); ?>"><?php echo JText::_( 'Custom Description' ); ?>:</label>
						</td>
						<td>
							<?php echo $lists['tooltip']; ?>
						</td>
					</tr>
				<?php
					endif;
				?>
				</table>
			</fieldset>
		</div>

		<div class="col width-50">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Parameters' ); ?></legend>

				<?php
					echo $pane->startPane( "menu-pane" );

						echo $pane->startPanel( JText :: _( 'Module Parameters' ), "param-page" );
							$p = $params;
							$params = $p->render( 'params' );
							if ( $params ) {
								echo $params;
							} else {
								echo "<div style=\"text-align: center; padding: 5px; \">".JText::_( 'There are no parameters for this item' )."</div>";
							}
						echo $pane->endPanel();

					if ( $p->getNumParams( 'advanced' ) ) {
						echo $pane->startPanel( JText :: _( 'Advanced Parameters' ), "advanced-page" );
							$params = $p->render( 'params', 'advanced' );
							if( $params ) {
								echo $params;
							} else {
								echo "<div  style=\"text-align: center; padding: 5px; \">".JText::_( 'There are no advanced parameters for this item' )."</div>";
							}
						echo $pane->endPanel();
					}

					if ( $p->getNumParams( 'legacy' ) ) {
						echo $pane->startPanel( JText :: _( 'Legacy Parameters' ), "legacy-page" );
							$params = $p->render( 'params', 'legacy' );
							if ( $params ) {
								echo $params;
							} else {
								echo "<div  style=\"text-align: center; padding: 5px; \">".JText::_( 'There are no legacy parameters for this item' )."</div>";
							}
						echo $pane->endPanel();
					}

					if ( $p->getNumParams( 'other' ) ) {
						echo $pane->startPanel( JText :: _( 'Other Parameters' ), "other-page" );
							$params = $p->render( 'params', 'other' );
							if ( $params ) {
								echo $params;
							} else {
								echo "<div  style=\"text-align: center; padding: 5px; \">".JText::_( 'There are no other parameters for this item' )."</div>";
							}
						echo $pane->endPanel();
					}
					
					if ( $row->client_id != 1 ) {
						echo $pane->startPanel( JText :: _('Module Assignment' ), "assignment-page" );
							echo HTML_modules::renderAssignments( $row, $lists );
						echo $pane->endPanel();
					}
					echo $pane->endPane();
				?>
			</fieldset>
		</div>
		<div class="clr"></div>

		<?php
		if ( !$row->module || $row->module == 'custom' || $row->module == 'mod_custom' ) {
			?>
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Custom Output' ); ?></legend>

				<?php
				// parameters : areaname, content, width, height, cols, rows
				echo $editor->display( 'content', $row->content, '100%', '400', '60', '20', array( 'pagebreak', 'readmore' ) ) ;
				?>

			</fieldset>
			<?php
		}
		?>

		<input type="hidden" name="option" value="com_advancedmodules" />
		<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="original" value="<?php echo $row->ordering; ?>" />
		<input type="hidden" name="module" value="<?php echo $row->module; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="client" value="<?php echo $client->id ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	<?php
	}

	function preview()
	{
		$editor =& JFactory::getEditor();

		?>
		<script type="text/javascript">
			var form = window.top.document.adminForm
			var title = form.title.value;

			var alltext = window.top.<?php echo $editor->getContent( 'text' ) ?>;
		</script>

		<table align="center" width="90%" cellspacing="2" cellpadding="2" border="0">
			<tr>
				<td class="contentheading" colspan="2"><script type="text/javascript">document.write( title );</script></td>
			</tr>
			<tr>
				<td valign="top" height="90%" colspan="2"><script type="text/javascript">document.write( alltext + "" );</script></td>
			</tr>
		</table>
		<?php
	}

/**
	/**
	* Displays a selection list for module types
	*/
	function add( &$modules, $client )
	{
 		JHTML::_( 'behavior.tooltip' );

		?>
		<form action="<?php echo JRoute::_( 'index.php'); ?>" method="post" name="adminForm">

		<table class="adminlist" cellpadding="1" summary="Add Module">
		<thead>
		<tr>
			<th colspan="4">
			<?php echo JText::_( 'Modules' ); ?>
			</th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th colspan="4">&nbsp;
			</th>
		</tr>
		</tfoot>
		<tbody>
		<?php
		$altRow = 0;
		$count = count( $modules );
		// Variable-column ready, just pass $cols in.
		$cols = 2;
		$pct = floor( 100 / $cols );
		$rows = ceil( $count / $cols );
		$posn = 0;
		do {
			?>
			<tr class="<?php echo 'row' . $altRow; ?>" valign="top">
			<?php
			$altRow = 1 - $altRow;
			for ( $col = 0; $col < $cols; ++$col ) :
				if ( ( $mod = $posn + $col * $rows ) >= $count ) :
					?>
					<td width="<?php echo $pct; ?>%">&nbsp;</td>
					<?php
					continue;
				endif;
				$item =& $modules[$mod];
				$link = 'index.php?option=com_advancedmodules&amp;task=edit&amp;module='
					. $item->module . '&amp;created=1&amp;client=' . $client->id;

				echo '
					<td width="'.$pct.'%">
						<span class="editlinktip hasTip" title="'
							.htmlspecialchars($item->name.' :: '
							.JText::_( stripslashes( $item->descrip) ), ENT_QUOTES, 'UTF-8' )
							.'" name="module" value="'.$item->module.'" onclick="isChecked( this.checked );">
						<input type="radio" name="module" value="'
							.$item->module.'" id="cb<?php echo $mod; ?>"/><a href="'
							.$link.'">'
							.htmlspecialchars( $item->name, ENT_QUOTES, 'UTF-8' )
							.'</a></span>
					</td>';
			endfor;
			++$posn;
			?>
			</tr>
		<?php
		} while ( $posn < $rows );
		?>
		</tbody>
		</table>

		<input type="hidden" name="option" value="com_advancedmodules" />
		<input type="hidden" name="client" value="<?php echo $client->id; ?>" />
		<input type="hidden" name="created" value="1" />
		<input type="hidden" name="task" value="edit" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php
	}
	/**
	* Displays a selection list for module types
	*/
	function placeModalHeader( $action )
	{
		$document =& JFactory::getDocument();

		$document->addStyleSheet( JURI::base( true ).'/templates/system/css/system.css' );
		$document->addStyleSheet( JURI::base( true ).'/templates/khepri/css/template.css' );
		$document->addStyleSheet( JURI::base( true ).'/templates/khepri/css/rounded.css' );

		// Render the toolbar
		echo '
			<div class="padding"><div id="toolbar-box">
	   			<div class="t"><div class="t"><div class="t"></div></div></div>
				<div class="m">
					<div class="toolbar" id="toolbar">
						<table class="toolbar"><tr>
							<td class="button" id="toolbar-save">
								<a href="#" onclick="javascript: submitbutton( \'save\' )" class="toolbar">
									<span class="icon-32-save" title="'.JText::_( 'Save' ).'"></span>
									'.JText::_( 'Save' ).'
								</a>
							</td>
							<td class="button" id="toolbar-apply">
								<a href="#" onclick="javascript: submitbutton( \'apply\' )" class="toolbar">
									<span class="icon-32-apply" title="'.JText::_( 'Apply' ).'"></span>
									'.JText::_( 'Apply' ).'
								</a>
							</td>
							<td class="button" id="toolbar-cancel">
								<a href="#" onclick="javascript: parent.location.href=parent.location; parent.SqueezeBox.window.setStyle( \'display\', \'none\' );return false;" class="toolbar">
									<span class="icon-32-cancel" title="'.JText::_( 'Close' ).'"></span>
									'.JText::_( 'Close' ).'
								</a>
							</td>
						</tr></table>
					</div>
					<div class="header icon-48-module">
						'.JText::_( 'Module' ).': <small><small>[ '.JText::_( $action ).' ]</small></small>
					</div>
					<div class="clr"></div>
				</div>
				<div class="b"><div class="b"><div class="b"></div></div>
			</div></div>';
	}
	function renderAssignments( &$row, &$lists )
	{
		jimport( 'joomla.filesystem.file' );
		
		// Loads English language file as fallback (for undefined stuff in other language file)
		$file = JPATH_ADMINISTRATOR.DS.'language'.DS.'en-GB'.DS.'en-GB.com_advancedmodules.ini';
		$lang =& JFactory::getLanguage();
		$lang->_load( $file, 'com_advancedmodules', 0 );

		$plugin = JPluginHelper::getPlugin( 'system', 'advancedmodules' );
		
		$parameters =& NNePparameters::getParameters();
		$plugin_params = $parameters->getParams( $plugin->params, JPATH_SITE.DS.'plugins'.DS.'system'.DS.'advancedmodules.xml' );

		if ( $plugin_params->show_mirror_module ) {
			echo $lists['assignments']->render( 'advancedparams', 'mirror_module' );
			echo '<div id="'.rand( 1000000, 9999999 ).'___mirror_module.0" class="nntoggler" style="visibility: hidden;">';
		} else {
			echo '<div>';
		}
		
		if (	$plugin_params->show_match_method
			&& (	$plugin_params->show_assignto_secscats
				||	$plugin_params->show_assignto_k2cats
				||	$plugin_params->show_assignto_mrcats
				||	$plugin_params->show_assignto_articles
				||	$plugin_params->show_assignto_components
				||	$plugin_params->show_assignto_urls
				||	$plugin_params->show_assignto_browser
				||	$plugin_params->show_assignto_date
				||	$plugin_params->show_assignto_usergrouplevels
				||	$plugin_params->show_assignto_users
				||	$plugin_params->show_assignto_languages
				||	$plugin_params->show_assignto_templates
				||	$plugin_params->show_assignto_php
			)
		) {
			echo $lists['assignments']->render( 'advancedparams', 'match_method' );
		}

		echo $lists['assignments']->render( 'advancedparams', 'assignto_menuitems' );

		if ( $plugin_params->show_assignto_secscats ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_secscats' );
		}
		if ( $plugin_params->show_assignto_k2cats && JFile::exists( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'admin.k2.php' ) ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_k2cats' );
		}
		if ( $plugin_params->show_assignto_mrcats && JFile::exists( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_resource'.DS.'resource.php' ) ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_mrcats' );
		}
		if ( $plugin_params->show_assignto_articles ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_articles' );
		}
		if ( $plugin_params->show_assignto_components ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_components' );
		}
		if ( $plugin_params->show_assignto_urls ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_urls' );
		}
		if ( $plugin_params->show_assignto_browsers ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_browsers' );
		}
		if ( $plugin_params->show_assignto_date ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_date' );
		}
		if ( $plugin_params->show_assignto_usergrouplevels ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_usergrouplevels' );
		}
		if ( $plugin_params->show_assignto_users ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_users' );
		}
		if ( $plugin_params->show_assignto_languages ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_languages' );
		}
		if ( $plugin_params->show_assignto_templates ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_templates' );
		}
		if ( $plugin_params->show_assignto_php ) {
			echo $lists['assignments']->render( 'advancedparams', 'assignto_php' );
		}
		echo '</div>';
	}
}