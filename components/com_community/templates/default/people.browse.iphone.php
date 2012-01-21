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
 * @params	sortings	A html data that contains the sorting toolbar 
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

	var totalUser 	= <?php echo $totalUser; ?>;
	var prevClass 	= false;
	var nextClass 	= false;

	var url = 'index.php?option=com_community&view=search&task=browse';
	var limitNext, limitPrev;
	var currentPage = 1, totalPage = 1;

	// get total page
	totalPage = Math.ceil( totalUser / 20 );
	
	newP = '';
	
	if ( totalUser > 20 ) {
		for ( i = 0; i < q.length; i++ ) {
	
			ft = q[i].split("=");
		
		
			if ( ft[0] == 'limitstart' ) {
				
				var limit = parseInt( ft[1] );
				var tempTotal = limit + 20;
				
				if ( tempTotal <= totalUser ) {
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
			newP += '	<a href="' + url + limitPrev + '" class="btn-blue btn-prev"><span><?php echo JText::_("CC PREV"); ?></span></a>';
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

<div id="back-toolbar" class="black-button">
	<a class="btn-blue btn-prev" href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $my->id); ?>">
		<span><?php echo JText::_('CC BACK TO PROFILE'); ?></span>
	</a>
	<div class="clr"></div>
</div>

<?php if( !empty( $data ) ) { ?>
	<?php foreach( $data as $row ) : ?>
		<?php $displayname = $row->user->getDisplayName(); ?>
		<?php if(!empty($row->user->id) && !empty($displayname)) : ?>
<div class="mini-profile" onclick="window.location='<?php echo $row->profileLink; ?>'">
	<div class="mini-profile-avatar">
		<a href="<?php echo $row->profileLink; ?>"><img class="avatar" width="45" src="<?php echo $row->user->getThumbAvatar(); ?>" alt="<?php echo $row->user->getDisplayName(); ?>" /></a>
	</div>
	<div class="mini-profile-details">
		<h3 class="name">
			<a href="<?php echo $row->profileLink; ?>"><strong><?php echo $row->user->getDisplayName(); ?></strong></a>
		</h3>
		
		<?php 
		$status = $row->user->getStatus();
		if ( !empty( $status ) ) : ?>
		<div style="margin: 0 0 5px;"><?php echo $row->user->getStatus() ;?></div>
		<?php endif; ?>
		
		<div>
		    <span class="icon-group">
		    	<?php echo JText::sprintf( (CStringHelper::isPlural($row->friendsCount)) ? 'CC FRIENDS COUNT MANY' : 'CC FRIENDS COUNT', $row->friendsCount);?>
		    </span>
			
			<?php if($row->user->isOnline()): ?>
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
	<a style="width: 100%; z-index: 1000; cursor: pointer; position: absolute; top: 0; left: 0; height: 100%; display: block; text-decoration: none;" href="<?php echo $row->profileLink; ?>">&nbsp;</a>
</div>
		<?php endif; ?>
	<?php endforeach; ?>	
	
	
	<?php echo (isset($pagination)) ? $pagination->getPagesLinks() : ''; ?>	
<?php } else { ?>

	<?php if (isset($filter) && count($filter) > 0) { ?>
		<div style="border:1px solid #00CCFF; padding:20px; background-color:#CCFFFF">
		<?php echo JText::_('CC NO RESULT FROM CUSTOM SEARCH');?>
		</div>
	<?php } ?>

<?php } ?>