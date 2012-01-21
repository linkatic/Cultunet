<?php
/**
 * @package		JomSocial
 * @subpackage 	Template
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @param	author		string
 * @param	categories	An array of category objects.
 * @params	groups		An array of group objects.
 * @params	pagination	A JPagination object.
 * @params	isJoined	boolean	determines if the current browser is a member of the group
 * @params	isMine		boolean is this wall entry belong to me ?
 * @params	config		A CConfig object which holds the configurations for Jom Social
 */
defined('_JEXEC') or die();
?>

<script type="text/javascript">
joms.jQuery(document).ready( function() {
	if ( joms.jQuery('.sectiontablefooter').length > 0 ) {
		generatePagination();
	}
});

function generatePagination()
{
	url = window.location.search.substring(1);
	q 	= url.split("&");
	
	var totalFriends 	= <?php echo $my->getFriendCount(); ?>;
	var prevClass 		= false;
	var nextClass 		= false;

	var url = 'index.php?option=com_community&view=friends&userid=<?php echo $my->id; ?>';
	var limitNext;
	var currentPage = 1, totalPage = 1;

	// get total page
	totalPage = Math.ceil( totalFriends / 20 );
	
	newP = '';
	
	if ( totalFriends > 20 ) {
	
		for ( i = 0; i < q.length; i++ ) {

			ft = q[i].split("=");
		
		
			if ( ft[0] == 'limitstart' ) {
				var limit = parseInt( ft[1] );
				var tempTotal = limit + 20;
				
				if ( tempTotal <= totalFriends ) {
					limitNext = '&limitstart=' + tempTotal;
					nextClass = true;
				}
				else {
					nextClass = false;
				}
				
				if ( limit == 20 ) {
					limitPrev = '';
					prevClass = true;
				}
				else if ( limit > 20 ) {
					limitPrev = '&limitstart=' + ( limit - 20 );
					prevClass = true;
				}
				currentPage = Math.ceil( ft[1] / 20 ) + 1;
			}
			else {
				limitNext = '&limitstart=20';
				nextClass = true;
				currentPage = 1;
			}		
		}
		newP += '<div id="pagination" class="black-button">';
		
		if ( nextClass ) {
			newP += '	<a href="' + url + limitNext + '" class="btn-blue btn-next"><span><?php echo JText::_("CC NEXT"); ?></span></a>';
		}
		else {
			newP += '	<span class="btn-blue btn-next-disabled"><span><?php echo JText::_("CC NEXT"); ?></span></span>';
		}
	
		// prev button
		if ( prevClass ) {
			newP += '	<a href="' + url + '" class="btn-blue btn-prev"><span><?php echo JText::_("CC PREV"); ?></span></a>';
		}
		else {
			newP += '	<span class="btn-blue btn-prev-disabled"><span><?php echo JText::_("CC PREV"); ?></span></span>';
		}
		
		var pageString	= '<?php echo JText::sprintf("CC SHOW PAGE OF TOTAL PAGE", "' + currentPage + '", "' + totalPage + '"); ?>';
		
		newP += '<div class="pagenum">' + pageString + '</div>';
		newP += '</div>';
		newP += '<div class="clr"></div>';				
	}

	joms.jQuery('.sectiontablefooter').html(newP);
}
</script>

<?php if( !empty( $friends ) ) : ?>
	<?php foreach( $friends as $user ) : ?>

<div class="mini-profile">
	<div class="mini-profile-avatar">
		<a href="<?php echo $user->profileLink; ?>">
			<img class="avatar" width="45" src="<?php echo $user->getThumbAvatar(); ?>" alt="<?php echo $user->getDisplayName(); ?>" />
		</a>
	</div>
	<div class="mini-profile-details">
		<h3 class="name">
			<a href="<?php echo $user->profileLink; ?>"><strong><?php echo $user->getDisplayName(); ?></strong></a>
		</h3>
	
		<div class="status">
			<?php echo $user->getStatus() ;?>
		</div>

		<div class="icons">
		    <span class="icon-group">
		    	<a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid=' . $user->id );?>"><?php echo JText::sprintf( (CStringHelper::isPlural($user->friendsCount)) ? 'CC FRIENDS COUNT MANY' : 'CC FRIENDS COUNT' , $user->friendsCount);?></a>
		    </span>
		    
		    <?php if($user->isOnline()): ?>
			<span class="icon-online">
		    	<?php echo JText::_('CC ONLINE'); ?>
		    </span>
		    <?php else: ?>
		    <span class="icon-offline">
		    	<?php echo JText::_('CC OFFLINE'); ?>
		    </span>
		    <?php endif; ?>
	        
		</div>
	</div>
	<div class="clr"></div>
	<a style="width: 100%; z-index: 1000; cursor: pointer; position: absolute; top: 0; left: 0; height: 100%; display: block; text-decoration: none;" href="<?php echo $user->profileLink; ?>">&nbsp;</a>
	<a class="remove" onclick="if(!confirm('<?php echo JText::_('CC CONFIRM DELETE FRIEND'); ?>'))return false;" href="<?php echo CRoute::_('index.php?option=com_community&view=friends&task=remove&fid='.$user->id); ?>">
		<?php echo JText::_('CC REMOVE'); ?>
	</a>
	
</div>

	<?php endforeach; ?>
<?php endif; ?>