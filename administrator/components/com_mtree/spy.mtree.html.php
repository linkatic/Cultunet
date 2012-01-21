<?php
/**
 * @version		$Id: spy.mtree.html.php 876 2010-05-21 11:52:19Z cy $
 * @package		Mosets Tree
 * @copyright	(C) 2005-2009 Mosets Consulting. All rights reserved.
 * @license		GNU General Public License
 * @author		Lee Cher Yeong <mtree@mosets.com>
 * @url			http://www.mosets.com/tree/
 */


defined('_JEXEC') or die('Restricted access');

class HTML_mtspy {

	function viewClones( $option, $clones, $cloners_listings ) {
	?>
	<table class="adminlist">
	<thead>
	<tr align="left">
		<th width="15%"><?php echo JText::_( 'Ip address' ) ?></th>
		<th width="85%" nowrap="nowrap"><?php echo JText::_( 'Clones' ) ?></th>
	</tr>
	</thead>
	<?php
	if( count($clones) > 0 ) {
		$ip = '';
		foreach( $clones AS $clone ) {
			if( empty($ip) OR $ip <> $clone->log_ip ) {
				$ip = $clone->log_ip;
				$clone_count[$clone->log_ip] = 1;
			} else {
				$clone_count[$clone->log_ip]++;
			}
			$clone_user[$clone->log_ip][] = array( 
				'username' => $clone->username, 
				'user_id' => $clone->user_id, 
				'name' => $clone->name, 
				'email' => $clone->email, 
				'num_of_links' => $clone->num_of_links,
				'blocked' => $clone->user_blocked
				); 
		}
		foreach( $clone_count AS $ip => $count ) {
			echo '<tr align="left">';
			//echo '<td>' . $ip. ' ('.$count.')</td>';
			echo '<td>' . $ip . '</td>';
			echo '<td>';
			foreach( $clone_user[$ip] AS $cuser ) {
				if( $cuser['num_of_links'] > 0 ) {
					echo '<b>' . mtfHTML::user( $cuser['user_id'], $cuser['username'], '', $cuser['blocked'] ) . '</b>';
					echo '(';
					echo $cuser['num_of_links'].' listings';
					echo ')';
				} else {
					echo mtfHTML::user( $cuser['user_id'], $cuser['username'], '', $cuser['blocked'] );
				}
				echo '&nbsp; ';
			}
			echo '</td>';
			echo '</tr>';
		}
	} else {
		echo '<tr><td colspan="2">' . JText::_( 'No clone detected' ) . '</td></tr>';
	}
	?>
	</table>
	<?php if( count($clones) > 0 ) { ?>
	<br />
	<table class="adminlist">
	<thead>
	<tr align="left">
		<th width="5">#</th>
		<th width="45%"><?php echo JText::_( 'Listings owned by suspect cloners' ) ?></th>
		<th width="22%" nowrap="nowrap"><?php echo JText::_( 'Owner' ) ?></th>
		<th width="20%" nowrap="nowrap"><?php echo JText::_( 'Ratings and votes' ) ?></th>
		<th width="5%" nowrap="nowrap"><?php echo JText::_( 'Hits' ) ?></th>
		<th width="8%" nowrap="nowrap" align="center"><?php echo JText::_( 'Created' ) ?></th>
	</tr>
	</thead>
	<?php
		$c=0;
		foreach( $cloners_listings AS $link ) {
			echo '<tr align="left">';
			echo '<td width="5">' . ++$c . '</td>';			
			echo '<td><a href="index.php?option='.$option.'&task=spy&task2=viewlisting&id='.$link->link_id.'">' . $link->link_name . '</a></td>';
			echo '<td>' . mtfHTML::user( $link->user_id, $link->username, $link->name ) . '</td>';
			echo '<td>' . mtfHTML::rating( $link->link_rating ) . '&nbsp; ' . $link->link_votes . ' votes</td>';
			echo '<td>' . ( ($link->link_hits) ? $link->link_hits : '-' ) . '</td>';
			echo '<td align="right">' . date( 'j M y', strtotime($link->link_created) ) . '</td>';
			echo '</tr>';
		}

	?>
	</table>
	<?php
		}
	}

	function viewListing( $option, $Itemid, $listing_activities, $reviews, $listing, $clones ) {
		global $mtconf;
		JHTML::_('behavior.tooltip');
	?>
	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
	<form action="index.php" method="post" name="adminForm">
	<table class="adminheading" width="100%">
		<tr><td>
			<fieldset>
			<legend><?php echo JText::_( 'Listing' ) ?>: <?php echo $listing->link_name ?></legend>
			<table style="font-weight:normal;" width="100%">
			<tr><td width="50%" valign="top">
			<table cellpadding="3">
				<tr><td colspan="2"><b><a href="<?php echo $mtconf->getjconf('live_site') . '/index.php?option=com_mtree&task=viewlink&link_id=' . $listing->link_id . '&Itemid='.$Itemid; ?>" target="_blank"><?php echo JText::_( 'View listing' ) ?></a> | <a href="index.php?option=com_mtree&task=editlink&link_id=<?php echo $listing->link_id ?>"><?php echo JText::_( 'Edit' ) ?></a></b></td></tr>
				<tr><td align="right"><?php echo JText::_( 'Owner' ) ?>: </td><td><b><?php echo mtfHTML::user( $listing->user_id, $listing->username, $listing->name ); ?></b></td></tr>
				<tr><td align="right"><?php echo JText::_( 'Rating' ) ?>: </td><td><?php echo mtfHTML::rating( $listing->link_rating ); echo '&nbsp; ' . $listing->link_rating . ' (<b>'.$listing->link_votes.' votes</b>)'; ?></td>
				</tr>
				<tr><td align="right"><?php echo JText::_( 'Reviews' ) ?>: </td><td><b><?php echo count($reviews); ?></b></td></tr>
			</table>

			</td>
			<td width="50%" valign="top">

			<table cellpadding="3">
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr><td align="right"><?php echo JText::_( 'Listing id' ) ?>: </td><td><b><?php echo $listing->link_id; ?></b></td></tr>
				<tr><td align="right"><?php echo JText::_( 'Created' ) ?>: </td><td><b><?php echo $listing->link_created; ?></b></td></tr>
				<tr><td align="right"><?php echo JText::_( 'Modified' ) ?>: </td><td><b><?php echo $listing->link_modified; ?></b></td></tr>
			</table>

			</td></tr>
			</table>
			</fieldset>
		</td></tr>
	</table>
	
	<table class="adminlist">
	<thead>
	<tr align="left">
		<th width="5">#</th>
		<th width="45%"><?php echo JText::_( 'Activities' ) ?></th>
		<th width="33%"><?php echo JText::_( 'User' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Ip address' ) ?></th>
		<th width="12%" nowrap="nowrap" align="center"><?php echo JText::_( 'Date' ) ?></th>
	</tr>
	</thead>
	<?php
	$clone_votes=0;
	$removed_votes=0;
	if( count($listing_activities) == 0 ) {
		echo '<tr><td colspan="5"><i>No activities.</i></td></tr>';
	} else {
		$c=0;
		foreach( $listing_activities AS $ua ) {
			echo '<tr align="left">';
			echo '<td width="5">' . ++$c . '</td>';
			echo '<td>' . mtfHTML::userActivity( $ua->log_type, $ua->value, $ua->link_id, $ua->link_name );
			if( $ua->log_type == 'votereview' ) echo ' - <i>'.$ua->rev_title . '</i>';
			if( $ua->user_id == $listing->user_id ) {
				switch( $ua->log_type ) {
					case 'vote':
						echo ' <span class="owner_vote">' . JText::_( 'Owner vote' ) . '</span>';
						break;
					case 'votereview':
						echo ' <span class="owner_vote">' . JText::_( 'Owner vote' ) . '</span>';
						break;
				}
			} elseif( in_array( $ua->user_id, $clones ) ) {
				echo ' <span class="' . (($ua->user_blocked)?'clone_vote_removed':'clone_vote') . '">' . JText::_( 'Clone vote' ) . '</span>';
				$clone_votes++;
				
				if ($ua->user_blocked) {
					$removed_votes++;
				}

			}

			echo '</td>';
			echo '<td>' . mtfHTML::user( $ua->user_id, $ua->username, $ua->name, $ua->user_blocked ) . '</td>';
			echo '<td>' . mtfHTML::ipAddress( $ua->log_ip ) . '</td>';
			echo '<td align="right">' . date( 'j M y, H:i', strtotime($ua->log_date) ) . '</td>';
			echo '</tr>';
		}
	}
	if( ($clone_votes - $removed_votes) > 0 ) {
	?>
	<tr><td colspan="5" align="left">
	<?php if ($removed_votes > 0 ) { 
		echo '<b>' . $removed_votes . ' votes</b> have been removed.<br />';
	}
	echo sprintf( JText::_( 'Analysis for actual rating' ), round(((($clone_votes-$removed_votes)/$listing->link_votes)*100),2), ($clone_votes-$removed_votes), $listing->link_votes, round((($listing->link_votes*$listing->link_rating)-(5*($clone_votes-$removed_votes)))/($listing->link_votes-($clone_votes-$removed_votes)),2) );
	?></b>.</td></tr>
	<?php } ?>
	</table>
	<?php
	if( count($reviews) > 0 ) {
	?>
	<br />
	<table class="adminlist">
	<thead>
	<tr align="left">
		<th width="5">#</th>
		<th width="35%"><?php echo JText::_( 'Reviews' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Helpfuls' ) ?></th>
		<th width="35%" nowrap="nowrap"><?php echo JText::_( 'User' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Ip address' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Date' ) ?></th>
	</tr>
	</thead>
	<?php

	$c=0;
	foreach( $reviews AS $review ) {
		echo '<tr align="left">';
		echo '<td width="5">' . ++$c . '</td>';
		echo '<td>';
		echo mtfHTML::review($review->rev_id,$review->rev_title, $review->rev_text, $review->rev_approved);
		if( $review->user_id == $listing->user_id ) {
			echo ' <span class="owner_rev">'.JText::_( 'Owner review' ).'</span>';
		}
		if( in_array( $review->user_id, $clones ) ) {
			echo ' <span class="clone_rev">'.JText::_( 'Clone review' ).'</span>';
		}
		'</td>';
		echo '<td>' . mtfHTML::helpfuls( $review->vote_helpful, $review->vote_total ) . '</td>';
		echo '<td>' . mtfHTML::user( $review->user_id, $review->username, $review->name, $review->user_blocked ) . '</td>';
		echo '<td>' . mtfHTML::ipAddress( $review->log_ip ) . '</a></td>';
		echo '<td>' . date( 'j M, H:i', strtotime($review->rev_date) ) . '</td>';
		echo '</tr>';
	}
	?></table>
	<?php } ?>

	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value="spy" />
	<input type="hidden" name="task2" value="users" />
	</form>
	<?php
	}

	function viewLinks( $option, $lists, $search, $pageNav, $links ) {
	?>
	<form action="index.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<td width="75%">
			<fieldset>
			<legend><?php echo JText::_( 'Listing' ) ?>: <?php echo JText::_( 'Search' ) ?></legend>
			<img src="images/generic.png" align="left" hspace="15" />

			<table style="font-weight:normal;" width="100%">
			<tr><td width="35%">

			<table cellpadding="1"><tr>
					<td align="right"><?php echo JText::_( 'Listing name' ) ?>: </td>
					<td><b><input class="text_area" type="text" name="link_name" size="24" value="<?php if (isset($search['link_name'])) echo $search['link_name'] ?>" /></b></td>
			</tr></table>
			
			</td><td width="65%">

			<table cellpadding="1"><tr>
					<td align="right"><?php echo JText::_( 'Listing id' ) ?>: </td>
					<td><b><input class="text_area" type="text" name="link_id" size="24" value="<?php if (isset($search['link_id'])) echo $search['link_id'] ?>" /></b></td>
			</tr></table>

			</td></tr>

			<tr><td colspan="2"><input type="submit" value="<?php echo JText::_( 'Search' ) ?>" /> <input type="reset" value="<?php echo JText::_( 'Reset' ) ?>" /></td></tr>
			</table>

			</fieldset>
			</td>
			<td width="25%" align="right" valign="bottom" style="padding-bottom:10px;"><?php echo $lists['orderby']; ?><?php echo $pageNav->getLimitBox(); ?></td>
		</tr>

	</table>

	<table class="adminlist">
	<thead>
	<tr align="left">
		<th width="5">#</th>
		<th width="35%"><?php echo JText::_( 'Listing' ) ?></th>
		<th width="22%" nowrap="nowrap"><?php echo JText::_( 'Owner' ) ?></th>
		<th width="20%" nowrap="nowrap"><?php echo JText::_( 'Ratings and votes' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Reviews' ) ?></th>
		<th width="5%" nowrap="nowrap"><?php echo JText::_( 'Hits' ) ?></th>
		<th width="8%" nowrap="nowrap" align="center"><?php echo JText::_( 'Created' ) ?></th>
	</tr>
	</thead>
	<?php
		$c=0;
		foreach( $links AS $link ) {
			echo '<tr align="left">';
			echo '<td width="5">' . $pageNav->getRowOffset( $c++ ) . '</td>';			
			echo '<td>' . mtfHTML::listing( $link->link_id, $link->link_name ) . '</td>';
			echo '<td>' . mtfHTML::user( $link->user_id, $link->username, $link->name ) . '</td>';
			echo '<td>' . mtfHTML::rating( $link->link_rating ) . '&nbsp; ' . $link->link_votes . ' votes</td>';
			echo '<td>' . ( ($link->reviews) ? $link->reviews : '-' ) . '</td>';
			echo '<td>' . ( ($link->link_hits) ? $link->link_hits : '-' ) . '</td>';
			echo '<td align="right">' . date( 'j M y', strtotime($link->link_created) ) . '</td>';
			echo '</tr>';
		}
		?>
		<tfoot>
		<tr>
			<td colspan="7">
				<?php echo $pageNav->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
	</table>
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value="spy" />
	<input type="hidden" name="task2" value="listings" />
	</form>
	<?php
	}

	function viewUser( $option, $Itemid, $user_activities, $reviews, $links, $user, $clones=array(), $removed_clones=array(), $lists=array() ) {
		global $mtconf;
		$task2	= strval(JRequest::getCmd( 'task2', ''));
		if(array_key_exists('clone_owner',$lists)) {
			echo '<script language="javascript" type="text/javascript" src="' . $mtconf->getjconf('live_site') . $mtconf->get('relative_path_to_js_library') . '"></script>';
		}
		JHTML::_('behavior.tooltip');
	?>
	<script language="javascript" type="text/javascript">
	<?php if(array_key_exists('clone_owner',$lists)) { ?>
	jQuery.noConflict();
	function detectOther(ref) {
		if(ref.options[ref.selectedIndex].value == '-1') {
			jQuery('#clone_owner_username').css('display','inline');
			jQuery('#clone_owner_username')[0].focus();
		} else {
			jQuery('#clone_owner_username').css('display','none');
		}
	}
	function removeClone(){
		var owner = '';
		if(jQuery('#clone_owner').val() == '-1') {
			owner = jQuery('#clone_owner_username').val();
		} else {
			owner = jQuery('#clone_owner').val();
		}
		if(owner!='') {
			location.href = "index.php?option=com_mtree&task=spy&task2=removecloneandalllogs&id=<?php echo $user->id ?>&owner=" + owner;
		}
	}
	<?php } ?>
	function perform_action(ref) {
		switch(ref.options[ref.selectedIndex].value) {
			case '1':
				window.open("<?php echo $mtconf->getjconf('live_site') . '/index.php?option=com_mtree&task=viewowner&user_id=' . $user->id . '&Itemid='.$Itemid; ?>");
				break;
			case '2':
				location.href = "index.php?option=com_users&view=user&task=edit&cid[]=<?php echo $user->id ?>";
				break;
			case '3':
				if( confirm('<?php echo JText::_( 'Confirm remove user and all its data' ) ?>') ) {
					location.href = "index.php?option=com_mtree&task=spy&task2=removeuserandalllogs&id=<?php echo $user->id ?>";
				}
				break;
			case '4':
				jQuery('#clone').slideDown('fast');
				break;
		}
	}

	function submitbutton(pressbutton) {
		var form = document.adminForm;
		form.task.value = 'spy';
		form.task2.value = 'removelogs';
		form.submit();
	}
	</script>
	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
	<form action="index.php" method="post" name="adminForm">
	<table class="adminheading" width="100%">
		<tr><td>
			<fieldset>
			<legend><?php echo ($task2 == 'viewclone') ? JText::_( 'Clone' ) : JText::_( 'User' ) ?>: <?php echo $user->name ?></legend>
			<img src="images/user.png" align="left" hspace="15" />
			<table style="font-weight:normal;" width="100%">
			<tr><td width="60%" valign="top">

			<table cellpadding="3">
				<?php if($task2 == 'viewuser') { ?>
				<tr>
					<td align="right" valign="top"><?php echo JText::_( 'Action' ) ?>: </td>
					<td valign="top">
						<select id="action" class="text_area" onchange="perform_action(this)">
							<option value=''></option>
							<option value='1'><?php echo JText::_( 'View users page in front end' ) ?></option>
							<option value='2'><?php echo JText::_( 'Edit user in user manager' ) ?></option>
							<?php if( count($links) <= 0 ) { ?>							
							<option value='3'><?php echo JText::_( 'Remove user including his her activities' ) ?></option>
							<option value='4'><?php echo JText::_( 'Mark this user as clone' ) ?></option>
							<?php } ?>
						</select>
						<?php
						if(count($lists)>0) {
							echo '<div id="clone" style="display:none">';
							echo '<table cellpadding="2">';
							echo '<tr><td>';
							echo 'Set the clone owner to:';
							echo '&nbsp;';
							echo $lists['clone_owner'];
							echo '&nbsp;<input type="text" id="clone_owner_username" class="text_area" style="display:none" size="20" />';
							echo '</td></tr>';
							echo '<tr><td align="left">';
							echo '<input type="button" onclick="removeClone()" value="' . sprintf(JText::_( 'Remove s including his her activities' ),$user->username) . '" class="inputbox" />';
							echo '&nbsp; or &nbsp;<a href="#" onclick="jQuery(\'#clone\').slideUp(\'fast\');jQuery(\'#action\').val(\'\');return false;">' . JText::_( 'Cancel' ) . '</a>';
							echo '</table>';
							echo '</div>';
						}
						?>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td align="right"><?php echo JText::_( 'Email' ) ?>: </td>
					<td><a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_( 'Username' ) ?>: </td>
					<td><b><?php echo $user->username; ?></b></td>
				</tr>
				<?php
				if(count($removed_clones)>0) {
					echo '<tr>';
					echo '<td align="right">' . JText::_( 'Removed clones' ) . '(' . count($removed_clones) . '):</td>';
					echo '<td>';
					$removed_clones_output = array();
					foreach($removed_clones AS $removed_clone) {
						$removed_clones_output[] = mtfHTML::cloneuser( $removed_clone->user_id, $removed_clone->username );
					}
					echo implode(', ',$removed_clones_output);
					echo '</td>';
					echo '</tr>';
				}
				?>
			</table>

			</td>
			<td width="40%" valign="top">

			<table cellpadding="3">
				<tr>
					<td align="right"><?php echo JText::_( 'User id' ) ?>: </td>
					<td><b><?php echo $user->id; ?></b></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_( 'Register' ) ?>: </td>
					<td><b><?php echo $user->registerDate; ?></b></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_( 'Last visit' ) ?>: </td>
					<td><b><?php echo $user->lastvisitDate; ?></b></td>
				</tr>
			</table>

			</td></tr>
			</table>
			</fieldset>
		</td></tr>
	</table>
	
	<?php
	if( count($clones) > 0 ) {
	?>
	<table class="adminlist">
	<thead>
	<tr align="left">
		<th width="15%"><?php echo JText::_( 'Ip address' ) ?></th>
		<th width="85%" nowrap="nowrap"><?php echo JText::_( 'Clones' ) ?></th>
	</tr>
	</thead>
	<?php
	$ip = '';
	foreach( $clones AS $clone ) {
		if( empty($ip) OR $ip <> $clone->log_ip ) {
			$ip = $clone->log_ip;
			$clone_count[$clone->log_ip] = 1;
		} else {
			$clone_count[$clone->log_ip]++;
		}
		$clone_user[$clone->log_ip][] = array( 'username' => $clone->username, 'user_id' => $clone->user_id, 'name' => $clone->name, 'blocked' => $clone->user_blocked ); 
	}
	foreach( $clone_count AS $ip => $count ) {
		echo '<tr align="left">';
		echo '<td>' . $ip . '</td>';
		echo '<td>';
		foreach( $clone_user[$ip] AS $cuser ) {
			echo mtfHTML::user( $cuser['user_id'], $cuser['username'], '', $cuser['blocked'] );
			echo '&nbsp; ';
		}
		echo '</td>';
		echo '</tr>';
	}
	?>
	</table><br />
	<?php
	}
	?>
			
	<?php
	if( count($user_activities) == 0 ) {
		echo '<div align=\'left\'><i>' . JText::_( 'This user has not cast any votes yet' ) . '</i></div>';
	} else {
	?>

	<table class="adminlist">
	<thead>
	<tr align="left">
		<th width="5">#</th>
		<th width="5"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($user_activities); ?>);" /></th>
		<th width="22%"><?php echo JText::_( 'Activities' ) ?></th>
		<th width="55%" nowrap="nowrap"><?php echo JText::_( 'Listing review' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Ip address' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Date' ) ?></th>
	</tr>
	</thead>
	<?php

	$c=0;
	$i=0;
		foreach( $user_activities AS $ua ) {
			echo '<tr align="left">';
			echo '<td width="5">' . ++$c . '</td>';
			echo '<td><input type="checkbox" id="cb' . $i . '" name="cid[]" value="' . $ua->log_id . '" onclick="isChecked(this.checked);" /></td>';
			echo '<td>' . mtfHTML::userActivity( $ua->log_type, $ua->value, $ua->link_id, $ua->link_name ) . '</td>';
			echo '<td>' . mtfHTML::listing( $ua->link_id, $ua->link_name, $ua->rev_title, $ua->rev_approved ) . '</td>';
			echo '<td>' . mtfHTML::ipAddress( $ua->log_ip ) . '</td>';
			echo '<td align="right">' . date( 'j M y, H:i', strtotime($ua->log_date) ) . '</td>';
			echo '</tr>';
			$i++;
		}

	?>
	</table>

	<?php
	}

	if( count($reviews) == 0 ) {
		//echo "<div align='left'><i>This user has not written any reviews yet.</i></div>";
	} else {
	?>
	<br />
	<table class="adminlist" width="100%">
	<thead>
	<tr align="left">
		<th width="5">#</th>
		<th width="22%"><?php echo JText::_( 'Written reviews' ) ?></th>
		<th width="15%" nowrap="nowrap"><?php echo JText::_( 'Helpfuls' ) ?></th>
		<th width="40%" nowrap="nowrap"><?php echo JText::_( 'Listing' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Ip address' ) ?></th>
		<th width="13%" nowrap="nowrap"><?php echo JText::_( 'Date' ) ?></th>
	</tr>
	</thead>
	<?php

	$c=0;
	foreach( $reviews AS $review ) {
		echo '<tr align="left">';
		echo '<td width="5">' . ++$c . '</td>';
		echo '<td>' . mtfHTML::review($review->rev_id,$review->rev_title,$review->rev_text) . '</td>';
		echo '<td>' . mtfHTML::helpfuls( $review->vote_helpful, $review->vote_total ) . '</td>';
		echo '<td>' . '<a href="index.php?option='.$option.'&task=spy&task2=viewlisting&id='.$review->link_id.'">' . $review->link_name . '</a></td>';
		if( !empty($review->log_ip) ) {
			echo '<td>' . mtfHTML::ipAddress( $review->log_ip ) . '</a></td>';
		} else { echo '<td>-</td>'; }
		echo '<td>' . date( 'j M y, H:i', strtotime($review->rev_date) ) . '</td>';
		echo '</tr>';
	}

	?>
	</table>
	<?php } 

	if( count($links) > 0 ) {
	?>
	<br />
	<table class="adminlist" width="100%">
	<thead>
	<tr align="left">
		<th width="5">#</th>
		<th width="40%"><?php echo JText::_( 'Owned listings' ) ?></th>
		<th width="20%" nowrap="nowrap"><?php echo JText::_( 'Ratings and votes' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Reviews' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Hits' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Created' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Modified' ) ?></th>
	</tr>
	</thead>
	<?php

	$c=0;
	foreach( $links AS $link ) {
		echo '<tr align="left">';
		echo '<td width="5">' . ++$c . '</td>';
		echo '<td>' . mtfHTML::listing( $link->link_id, $link->link_name ) . '</td>';
		echo '<td>' . mtfHTML::rating( $link->link_rating ) . '&nbsp; ' . $link->link_votes . ' votes</td>';
		echo '<td>' . $link->reviews . '</td>';
		echo '<td>' . $link->link_hits . '</td>';
		echo '<td>' . date( 'j M', strtotime($link->link_created) ) . '</td>';
		echo '<td>' . date( 'j M', strtotime($link->link_modified) ) . '</td>';
		echo '</tr>';
	}

	?>
	</table>
	<?php } ?>

	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value="spy" />
	<input type="hidden" name="task2" value="viewuser" />
	<input type="hidden" name="boxchecked" value="0" />
	</form>
	<?php
	}

	function viewUsers( $option, $lists, $search, $pageNav, $Itemid, $users ) {
	?>
	<form action="index.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<td width="80%">
			<fieldset>
			<legend><?php echo JText::_( 'Users' ) ?>: <?php echo JText::_( 'Search' ) ?></legend>

			<table style="font-weight:normal;" width="100%">
			<tr><td width="33%">

			<table cellpadding="1">
				<tr>
					<td align="right"><?php echo JText::_( 'Username' ) ?>: </td>
					<td><b><input class="text_area" type="text" name="username" size="24" value="<?php if (isset($search['username'])) echo $search['username'] ?>" /></b></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_( 'Name' ) ?>: </td>
					<td><b><input class="text_area" type="text" name="name" size="24" value="<?php if (isset($search['name'])) echo $search['name'] ?>" /></b></td>
				</tr>
			</table>

			</td>
			<td width="33%">

			<table cellpadding="1">
				<tr>
					<td align="right"><?php echo JText::_( 'Email' ) ?>: </td>
					<td><b><input class="text_area" type="text" name="email" size="24" value="<?php if (isset($search['email'])) echo $search['email'] ?>" /></b></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_( 'User id' ) ?>: </td>
					<td><b><input class="text_area" type="text" name="id" size="24" value="<?php if (isset($search['id'])) echo $search['id'] ?>" /></b></td>
				</tr>
			</table>

			</td>
			<td width="33%" valign="top">

			<table cellpadding="1">
				<tr>
					<td align="right"><?php echo JText::_( 'Password hash' ) ?>: </td>
					<td><b><input class="text_area" type="text" name="password" size="24" value="<?php if (isset($search['password'])) echo $search['password'] ?>" /></b></td>
				</tr>
				<tr>
					<td align="right"><?php echo JText::_( 'Ip address' ) ?>: </td>
					<td><b><input class="text_area" type="text" name="ip" size="24" value="<?php if (isset($search['ip'])) echo $search['ip'] ?>" /></b></td>
				</tr>
			</table>

			</td></tr>
			<tr><td colspan="3"><tr><td colspan="2"><input type="submit" value="Search" /> <input type="reset" value="Reset" /></td></tr></td></tr>
			</table>

			</fieldset>
			</td>
			<td width="20%" align="right" valign="bottom" style="padding-bottom:10px;"><?php echo $lists['orderby']; ?><?php echo $pageNav->getLimitBox(); ?></td>
		</tr>
	</table>

	<table class="adminlist">
	<thead>
	<tr align="left">
		<th width="5">#</th>
		<th width="10%"><?php echo JText::_( 'User' ) ?></th>
		<th width="12%" nowrap="nowrap" align="right"><?php echo JText::_( 'Last activity' ) ?></th>
		<th width="43%" nowrap="nowrap"><?php echo JText::_( 'Listing review' ) ?></th>
		<th width="10%" nowrap="nowrap"><?php echo JText::_( 'Ip address' ) ?></th>
		<th width="13%" nowrap="nowrap"><div class="vrhl"><abbr title="<?php echo JText::_( 'Votes' ) ?>">V</abbr></div> <div class="vrhl"><abbr title="<?php echo JText::_( 'Reviews' ) ?>">R</abbr></div> <div class="vrhl"><abbr title="<?php echo JText::_( 'Helpfuls' ) ?>">H</abbr></div> <div class="vrhl"><abbr title="<?php echo JText::_( 'Listings' ) ?>">L</abbr></div> </th>
		<th width="12%" nowrap="nowrap" align="center"><?php echo JText::_( 'Date' ) ?></th>
	</tr>
	</thead>
	<?php
		$c=0;
		foreach( $users AS $user ) {
			echo '<tr align="left">';
			echo '<td width="5">' . $pageNav->getRowOffset( $c++ ) . '</td>';			
			echo '<td>' . mtfHTML::user( $user->id, $user->username, $user->name ) . '</td>';
			echo '<td align="right">' . mtfHTML::userActivity( $user->log_type, $user->value, $user->link_id, $user->link_name ) . '</td>';
			echo '<td>' . mtfHTML::listing( $user->link_id, $user->link_name, $user->rev_title, $user->rev_approved ) . '</td>';
			echo '<td>' . mtfHTML::ipAddress( $user->log_ip ) . '</td>';			
			echo '<td align="center">';
			echo '<div class="vrhl">' . ( ($user->votes) ? $user->votes : '-' ) . '</div> ';
			echo '<div class="vrhl">' . ( ($user->reviews) ? $user->reviews : '-' ) . '</div> ';
			echo '<div class="vrhl">' . ( ($user->votereviews) ? $user->votereviews : '-' ) . '</div> ';
			echo '<div class="vrhl">' . ( ($user->listings) ? $user->listings : '-' ) . '</div> ';
			echo '</td>';
			echo '<td align="right">' . ((!empty($user->log_date)) ? date( 'j M y, H:i', strtotime($user->log_date) ) : '') . '</td>';
			echo '</tr>';
		}
		?>
		<tfoot>
		<tr>
			<td colspan="10">
				<?php echo $pageNav->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
	</table>

	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value="spy" />
	<input type="hidden" name="task2" value="users" />
	</form>
	<?php
	}
	
	function printStartMenu( $option, $task ) {
	?>
	<style type="text/css">
	div.vrhl {
	width:17px;
	float:left;
	text-align:center;
	margin-right:3px;
	}
	span.owner_rev, span.owner_vote {background-color:#FFD2D2;border-bottom:2px solid #FF7D7D;padding:2px 4px 0 4px;font-weight:bold}
	span.clone_rev, span.clone_vote {background-color:#FFD2D2;border-bottom:0px solid #FF7D7D;padding:0 4px}
	span.clone_vote_removed {padding:0 4px;text-decoration:line-through}
	span.blocked_user {text-decoration:line-through;color:black}
	span.user {text-decoration:underline;color:#C64934}
	span.pending{background-color:#FFFB8A;border-bottom:2px solid #CEC704;padding:2px 4px 0 4px;font-weight:bold;margin-left:3px;}
	</style>
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
	<tr><td align="left" valign="top" width="160" height="0">
		<table cellpadding="2" cellspacing="0" border="0" width="160" height="100%" align="left" style="border: 1px solid #cccccc;background-color:#F1F3F5;clear:both;float:left;">
			<tr><td colspan="2" style="background: #DDE1E6; border-bottom: 1px solid #cccccc;font-weight:bold;"><?php echo JText::_( 'Spy directory' ) ?></td></tr>
			<tr>
				<td width="20"><img src="../components/com_mtree/img/group.png" width="16" height="16" /> </td>
				<td width="100%"><a href="index.php?option=com_mtree&task=spy&task2=users"><b><?php echo JText::_( 'View users' ) ?></b></a></td>
			</tr>
			<tr>
				<td><img src="../components/com_mtree/img/page_white.png" width="16" height="16" /> </td>
				<td><a href="index.php?option=com_mtree&task=spy&task2=listings"><b><?php echo JText::_( 'View listings' ) ?></b></a></td>
			</tr>
			<tr>
				<td><img src="../components/com_mtree/img/group_error.png" width="16" height="16" /> </td>
				<td><a href="index.php?option=com_mtree&task=spy&task2=clones"><b><?php echo JText::_( 'View clones' ) ?></b></a></td>
			</tr>

			<tr><td colspan="2" style="background: #DDE1E6; border-bottom: 1px solid #cccccc;font-weight:bold;"><?php echo JText::_( 'Search listings' ) ?></td></tr>
			<tr><td colspan="2">
				<form action="index.php" method="post" name="adminForm_searchlisting">
					<input type="text" name="link_name" class="text_area" style="width:100%" /> <input type="submit" value="<?php echo JText::_( 'Go' ) ?>" />
					<input type="hidden" name="option" value="<?php echo $option;?>" />
					<input type="hidden" name="task" value="spy" />
					<input type="hidden" name="task2" value="listings" />
				</form>
			</td></tr>

			<tr><td colspan="2" style="background: #DDE1E6; border-bottom: 1px solid #cccccc;font-weight:bold;"><?php echo JText::_( 'Search users' ) ?></td></tr>
			<tr><td colspan="2">
				<form action="index.php" method="post" name="adminForm_searchlisting">
					<input type="text" name="username_name" class="text_area" style="width:100%" /> <input type="submit" value="<?php echo JText::_( 'Go' ) ?>" />
					<input type="hidden" name="option" value="<?php echo $option;?>" />
					<input type="hidden" name="task" value="spy" />
					<input type="hidden" name="task2" value="users" />
				</form>
			</td></tr>
		</table>
		<div style="position:relative;top:5px;clear:both;text-align:center;"><img style="position:relative;top:4px;" src="../components/com_mtree/img/arrow_left.png" width="16" height="16" /> <a href="index.php?option=com_mtree"><b><?php echo JText::_( 'Back to directory' ) ?></b></a></div>

	</td><td valign="top" width="100%">
	<?php
	}

	function printEndMenu( $task ) {
	?>
	</td></tr>
	</table>
	<?php
	}

}

class mtfHTML {

	function ipAddress( $ip ) {
		if( !empty($ip) ) {
			return '<a href="index.php?option=com_mtree&task=spy&task2=users&ip=' . $ip . '">' . $ip . '</a>';
		} else {
			return '-';
		}
	}

	function cloneuser( $user_id, $username='' ) {
		return mtfHTML::user($user_id,$username,'',0,'viewclone');
	}
	
	function user( $user_id, $username='', $name='', $block=0, $task2='viewuser' ) {
		$html = '';

		if( $user_id > 0 ) { 

			if( empty($name) && !empty($username) ) {
				$html = '<a href="index.php?option=com_mtree&task=spy&task2=' . $task2 . '&id='.$user_id.'">' . $username . '</a>';
			} elseif( !empty($name) && empty($username) ) {
				$html = '<a href="index.php?option=com_mtree&task=spy&task2=' . $task2 . '&id='.$user_id.'">' . $name . '</a>';
			} else {
				$html = '<a href="index.php?option=com_mtree&task=spy&task2=' . $task2 . '&id='.$user_id.'">' . $username . '</a>';
			}

			if( $block ) {
				$html = '<span class="blocked_user">' . $html . '</span>';
			} elseif( empty($name) && !empty($username) ) {
				$html = '<span class="user">' . $html . '</span>';
			}

		} elseif ( $user_id == 0 && ( !empty($username) && !empty($name) ) ) {
			$html = $name . ' ('.$username.')'; 
		} else { 
			$html = '<i>'.JText::_( 'Unregistered' ).'</i>';
		}
		return $html;
	}

	function listing( $link_id, $link_name, $review_title='', $rev_approved=1 ) { 
		$html = '<a href="index.php?option=com_mtree&task=spy&task2=viewlisting&id=' . $link_id .'">';
		if( !empty($review_title) ) {
			$html .= mtfHTML::forceMaxChars( $link_name, 50 );
			$html .= '</a>';
			$html .= ' (' . mtfHTML::forceMaxChars( $review_title, 50 ) . ')';
			if(!$rev_approved) {
				$html .= '<span class="pending">'.JText::_( 'Pending' ).'</span>';
			}
		} else {
			$html .= mtfHTML::forceMaxChars( $link_name, 100 );
			$html .= '</a>';
		}
		return $html; 
	}

	function forceMaxChars( $text, $maxchar ) {
		if( strlen($text) > $maxchar ) {
			return substr( $text, 0, ($maxchar -3) ) . '...';
		} else {
			return $text;
		}
	}
	
	function helpfuls( $vote_helpful, $vote_total ) {
		if ( $vote_total == 0 ) {
			return '-';
		} else {
			return sprintf(JText::_( 'Out of' ), $vote_helpful,  $vote_total);
		}
	}

	function userActivity( $log_type, $value, $link_id, $link_name ) {
		global $mtconf;

		$ret = '';
		switch( $log_type ) {
			case 'vote':
				if( $value > 0 ) {
					$ret .= mtfHTML::rating( $value );
				} else {
					$ret .= mtfHTML::rating( 0 );
				}
				break;

			case 'votereview':
				if( $value == 1 ) {
					$ret = '<img src="'.$mtconf->getjconf('live_site').'/components/com_mtree/img/comment_add.png" width="16" height="16" hspace="0" />';

				} elseif  ( $value == -1 ) {
					$ret = '<img src="'.$mtconf->getjconf('live_site').'/components/com_mtree/img/comment_delete.png" width="16" height="16" hspace="0" />';

				}
				break;

			case 'review':
				return '<img src="'.$mtconf->getjconf('live_site').'/components/com_mtree/img/comment.png" width="16" height="16" hspace="0" />';
				break;

			case 'replyreview':
				return '<img src="'.$mtconf->getjconf('live_site').'/components/com_mtree/img/user_comment.png" width="16" height="16" hspace="0" />';
				break;

			case 'addfav':
				return '<img src="'.$mtconf->getjconf('live_site').'/components/com_mtree/img/heart_add.png" width="16" height="16" hspace="0" />';
				break;

			case 'removefav':
				return '<img src="'.$mtconf->getjconf('live_site').'/components/com_mtree/img/heart_delete.png" width="16" height="16" hspace="0" />';
				break;

			default:
				return '<a href="index.php?option=com_mtree&task=spy&task2=viewlisting&id=' . $link_id .'">' . $log_type . '</a>';
				break;

		}
		return $ret;
	}

	function rating( $rating ) {
		global $mtconf;

		$star = floor($rating);
		$html = '';

		// Print starts
		for( $i=0; $i<$star; $i++) {
			$html .= '<img src="'.$mtconf->getjconf('live_site').'/components/com_mtree/img/star_10.png" hspace="1" />';
		
		}

		if( ($rating-$star) >= 0.5 && $star > 0 ) {
			$html .= '<img src="'.$mtconf->getjconf('live_site').'/components/com_mtree/img/star_05.png" hspace="1" />';
			$star += 1;
		}

		// Print blank star
		for( $i=$star; $i<5; $i++) {
			$html .= '<img src="'.$mtconf->getjconf('live_site').'/components/com_mtree/img/star_00.png" hspace="1" />';
		}

		# Return the listing link
		return $html;

	}

	function review($rev_id, $rev_title, $rev_text='', $rev_approved=1) {
		$html = '<a href="index.php?option=com_mtree&task=editreview&rid=' . $rev_id .'"';
		if(!empty($rev_text)) {
			$html .= ' class="hasTip" title="'.$rev_title.'::'.$rev_text.'"';
		}
		$html .= '>';
		$html .= $rev_title;
		$html .= '</a>';
		if(!$rev_approved) {
			$html .= '<span class="pending">'.JText::_( 'Pending' ).'</span>';
		}
		return $html; 
	}


}

?>