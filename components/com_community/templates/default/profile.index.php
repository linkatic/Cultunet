<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 **/
defined('_JEXEC') OR DIE();
?>

<script type="text/javascript"> joms.filters.bind();</script>

<!-- begin: #cProfileWrapper -->
<div id="cProfileWrapper">
	<?php echo $adminControlHTML; ?>
	<!-- begin: .cLayout -->
	<div class="cLayout clrfix">
	
		<?php $this->renderModules( 'js_profile_top' ); ?>
		<?php if($isMine) $this->renderModules( 'js_profile_mine_top' ); ?>		

		<!-- begin: .cSidebar -->
	    <div class="cSidebar clrfix">
	    	<?php $this->renderModules( 'js_side_top' ); ?>
	    	<?php $this->renderModules( 'js_profile_side_top' ); ?>
			<?php echo $sidebarTop; ?>
			
			<?php if($isMine) $this->renderModules( 'js_profile_mine_side_top' ); ?>
			<?php echo $about; ?>
			<?php echo $friends; ?>
			<?php echo $groups; ?>
			<?php if($isMine) $this->renderModules( 'js_profile_mine_side_bottom' ); ?>
			
			<?php echo $sidebarBottom; ?>
			<?php $this->renderModules( 'js_profile_side_bottom' ); ?>
			<?php $this->renderModules( 'js_side_bottom' ); ?>
	    </div>
	    <!-- end: .cSidebar -->




        <!-- begin: .cMain -->
	    <div class="cMain">
			<div class="page-actions">
			  <?php echo $blockUserHTML;?>  
			  <?php echo $reportsHTML;?>
			  <?php echo $bookmarksHTML;?>
			  <div id="editLayout-stop" class="page-action" style="display: none;">
			  	<a onclick="joms.editLayout.stop()" href="javascript: void(0)"><?php echo JText::sprintf('CC STOP EDIT PROFILE APPS LAYOUT') ?></a>
			  </div>
			</div>
					
			<?php echo @$header; ?>
			
			<?php $this->renderModules( 'js_profile_feed_top' ); ?>
			
			<div id="activity-stream-nav" class="filterlink">
			    <div style="float: right;">
					<a class="p-active-profile-and-friends-activity active-state" href="javascript:void(0);"><?php echo JText::sprintf('CC PROFILE OWNER AND FRIENDS' , $profileOwnerName );?></a>
					<a class="p-active-profile-activity" href="javascript:void(0);"><?php echo $profileOwnerName ?></a>
				</div>
				<div class="loading"></div>
			</div>
			
			<div class="activity-stream-profile">
				<div id="activity-stream-container">
			  	<?php echo $newsfeed; ?>
			  	</div>
			</div>
			
			<?php $this->renderModules( 'js_profile_feed_bottom' ); ?>
			<div id="apps-sortable" class="connectedSortable" >
			<?php echo $content; ?>
			</div> 

		</div>
	    <!-- end: .cMain -->

		<?php if($isMine) $this->renderModules( 'js_profile_mine_bottom' ); ?>
		<?php $this->renderModules( 'js_profile_bottom' ); ?>
		
	</div>
	<!-- end: .cLayout -->

</div>
<!-- begin: #cProfileWrapper -->
<?php /* Insert plugin javascript at the bottom */ echo $jscript; ?>
