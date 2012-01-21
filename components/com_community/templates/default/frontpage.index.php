<?php
/**
 * @package		JomSocial
 * @subpackage 	Template 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 * 
 */
defined('_JEXEC') or die();

?>

<script type="text/javascript"> joms.filters.bind();</script>

<!-- begin: #cFrontpageWrapper -->
<div id="cFrontpageWrapper">
	<?php 
	/**
	 * if user logged in 
	 * 		load frontpage.members.php
	 * else 
	 * 		load frontpage.guest.php
	 */  
	echo $header;
	?>
	
	
	<!-- begin: .cLayout -->
	<div class="cLayout clrfix">
		
		<!-- begin: .cMainGC -->
		<div class="cMainGC clrfix">
        	<?php
			/**
			 * ----------------------------------------------------------------------------------------------------------			
			 * Latest members section here
			 * ----------------------------------------------------------------------------------------------------------			 
			 */			 			
			?>
			<?php if ( $config->get( 'showlatestmembers' ) == '1' || ( $config->get('showlatestmembers') == '2' && $my->id != 0 ) ) { ?>
			<?php echo $latestMembers; ?>
			<?php } ?>


		<?php
			/**
			 * ----------------------------------------------------------------------------------------------------------			
			 * Activity stream section here
			 * ----------------------------------------------------------------------------------------------------------			 
			 */			 			
			?>
			<?php if( $config->get('showactivitystream') == '1' || ($config->get('showactivitystream') == '2' && $my->id != 0 ) ) { ?>
			<!-- Recent Activities -->
			<div class="app-box" id="recent-activities">
		        <div class="app-box-header">
		        <div class="app-box-header">
		            <h2 class="app-box-title"><?php echo JText::_('CC RECENT ACTIVITIES'); ?></h2>
		            <div class="app-box-menus">
		                <div class="app-box-menu toggle">
		                    <a class="app-box-menu-icon"
		                       href="javascript: void(0)"
		                       onclick="joms.apps.toggle('#recent-activities');"><span class="app-box-menu-title"><?php echo JText::_('CC EXPAND');?></span></a>
		                </div>
		            </div>
				</div>
		        </div>	
		        <div class="app-box-content">
					<?php if ( $alreadyLogin == 1 ) : ?>
		            <div id="activity-stream-nav" class="filterlink">
		                <div style="float: right;">
		                    <a class="all-activity<?php echo $config->get('frontpageactivitydefault') == 'all' ? ' active-state': '';?>" href="javascript:void(0);"><?php echo JText::_('CC SHOW ALL') ?></a>
		                    <a class="me-and-friends-activity<?php echo $config->get('frontpageactivitydefault') == 'friends' ? ' active-state': '';?>" href="javascript:void(0);"><?php echo JText::_('CC ME AND FRIENDS') ?></a>
		                </div>
		                <div class="loading"></div>
		            </div>
		            <?php endif; ?>
		        
		            <div class="activity-stream-front">
		                <div id="activity-stream-container">
		                    <?php echo $userActivities; ?>
		                </div>
		            </div>
		        </div>
			</div>
		  	<!-- Recent Activities -->
		  	<?php } ?>
		</div>

    	<!-- begin: .cSidebar -->
	    <div class="cSidebar clrfix">

 			<?php $this->renderModules( 'js_side_top' ); ?>	    
	    	
	    	<?php if( $config->get('showsearch') == '1' || ($config->get('showsearch') == '2' && $my->id != 0 ) ) { ?>
	    	<?php
			/**
			 * ----------------------------------------------------------------------------------------------------------			
			 * Searchbox section here
			 * ----------------------------------------------------------------------------------------------------------			 
			 */			 			
			?>
			<!-- Search -->
			<?php if($this->params->get('frontpageShowSeach')) { ?>
		    <div class="cModule searchbox">
		        <h3><span><?php echo JText::_('CC SEARCH'); ?></span></h3>
		        <form name="search" id="cFormSearch" method="post" action="<?php echo CRoute::_('index.php?option=com_community&view=search');?>">
		        	<fieldset class="fieldset">
		        	
		        		<div class="input_wrap clrfix">
			            	<a href="javascript:void(0);" onclick="joms.jQuery('#cFormSearch').submit();" class="search_button"><span><?php echo JText::_('CC BUTTON SEARCH'); ?></span></a>
			            	<input type="text" class="inputbox" id="keyword" name="q" />
			            </div>
			            
			        	<div class="small">
			            	<?php echo JText::sprintf('CC TRY ADVANCED SEARCH', CRoute::_('index.php?option=com_community&view=search&task=advancesearch') ); ?>
			        	</div>
		        	</fieldset>
		        </form>
		    </div>
		    <?php } ?>
			<!-- Search -->
			<?php } ?>
			
			
			<?php
			/**
			 * ----------------------------------------------------------------------------------------------------------			
			 * Latest groups section here
			 * ----------------------------------------------------------------------------------------------------------			 
			 */			 			
			?>
			<?php if($this->params->get('frontpageShowLatestGroup')) { ?>
			<?php if( !COMMUNITY_FREE_VERSION ) { ?>
			<?php if($config->get('enablegroups') ) { ?>
			<?php if( !empty($latestGroups) && ( $config->get('showlatestgroups') == '1' || ($config->get('showlatestgroups') == '2' && $my->id != 0 ) ) ) { ?>
			<!-- Latest Groups -->
			<div class="cModule latest-groups">
				<?php echo $latestGroups; ?>
			</div>
			<!-- Latest Groups -->
			<?php } ?>
			<?php } ?>
			<?php } ?>
			<?php } ?>
			
			
			
			<?php
			/**
			 * ----------------------------------------------------------------------------------------------------------			
			 * Latest photos section here
			 * ----------------------------------------------------------------------------------------------------------			 
			 */			 			
			?>
			<?php if($this->params->get('frontpageShowNewPhotos')) { ?>
			<?php if( !COMMUNITY_FREE_VERSION ) { ?>
			<?php if($config->get('enablephotos')){ ?>
			<?php if( $config->get('showlatestphotos') == '1' || ($config->get('showlatestphotos') == '2' && $my->id != 0 ) ) { ?>
					<div class="cModule latest-photos">
						<div class="app-box-content">
							<h3><span><?php echo JText::_('CC NEW PHOTOS'); ?></span></h3>
							<ul class="cThumbList clrfix">
							<?php
							if( !$latestPhotos )
							{
							?>
								<li><?php echo JText::_('CC NO PHOTOS UPLOADED YET');?></li>
							<?php
							}
							else
							{
								for( $i = 0 ; $i < count( $latestPhotos ); $i++ )
								{
									$row	=& $latestPhotos[$i];
							?>
							<li>
							<a href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=photo&albumid=' . $row->albumid .  '&userid=' . $row->user->id) . '#photoid=' . $row->id;?>"><img class="avatar jomTips" width="45" height="45" title="<?php echo $this->escape($row->caption);?>::<?php echo JText::sprintf('CC PHOTO UPLOADED BY' , $row->user->getDisplayName() );?>" src="<?php echo $row->getThumbURI(); ?>" alt="<?php echo $this->escape( $row->user->getDisplayName() );?>" /></a>
							</li>
							<?php
								}
							}
							?>
							</ul>
					</div>
					
			    <div class="app-box-footer">
			        <a href="<?php echo CRoute::_('index.php?option=com_community&view=photos'); ?>"><?php echo JText::_('CC VIEW ALL PHOTOS'); ?></a>
			    </div>
			</div>
			<?php } ?>
			<?php } ?>
			<?php } ?>
			<?php } ?>
			



            <?php
			/**
			 * ----------------------------------------------------------------------------------------------------------			
			 * Whos online section here
			 * ----------------------------------------------------------------------------------------------------------			 
			 */			 			
			?>
			<?php if($this->params->get('frontpageShowWhosOnline')) { ?>
			<?php if( $config->get('showonline') == '1' || ($config->get('showonline') == '2' && $my->id != 0 ) ) { ?>
			<div class="cModule whos-online">
			    <h3><span><?php echo JText::_('CC WHOSE ONLINE'); ?></span></h3>
			    <ul class="cThumbList clrfix">
			    	<?php for ( $i = 0; $i < count( $onlineMembers ); $i++ ) { ?>
			    	<?php $row =& $onlineMembers[$i]; ?>
					<li>
						<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$row->id ); ?>"><img class="avatar jomTips" src="<?php echo $row->user->getThumbAvatar(); ?>" title="<?php echo cAvatarTooltip($row->user); ?>" width="45" height="45" alt="<?php echo $this->escape( $row->user->getDisplayName() );?>" /></a>
					</li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
			<?php } ?>
			
   			<?php $this->renderModules( 'js_side_bottom' ); ?>
   
	    </div>
	    <!-- end: .cSidebar -->




        <!-- begin: .cMain -->
	    <div class="cMain clrfix">			
            <?php
			/**
			 * ----------------------------------------------------------------------------------------------------------			
			 * Latest videos section here
			 * ----------------------------------------------------------------------------------------------------------			 
			 */			 			
			?>
    		<?php if( !COMMUNITY_FREE_VERSION ) { ?>
			<?php if($config->get('enablevideos')) { ?>
			<?php if( $config->get('showlatestvideos') == '1' || ($config->get('showlatestvideos') == '2' && $my->id != 0 ) ) { ?>
				<!-- Latest Video -->
	            <div class="app-box" id="latest-videos">
	                <div class="app-box-header">
	                <div class="app-box-header">
	                	<h2 class="app-box-title"><?php echo JText::_('CC VIDEOS'); ?></h2>
	                    <div class="app-box-menus">
	                        <div class="app-box-menu toggle">
	                            <a class="app-box-menu-icon"
	                               href="javascript: void(0)"
	                               onclick="joms.apps.toggle('#latest-videos');"><span class="app-box-menu-title"><?php echo JText::_('CC EXPAND');?></span></a>
	                        </div>
	                    </div>
	                </div>
	                </div>
	                
	                <div class="app-box-content">
		                <div id="latest-videos-nav" class="filterlink">
							<div style="float: right;">
	                            <a class="newest-videos active-state" href="javascript:void(0);"><?php echo JText::_('CC NEWEST VIDEOS') ?></a>
	                            <a class="featured-videos" href="javascript:void(0);"><?php echo JText::_('CC FEATURED VIDEOS') ?></a>
	                            <a class="popular-videos" href="javascript:void(0);"><?php echo JText::_('CC POPULAR VIDEOS') ?></a>
	                        </div>
	                        <div class="loading"></div>
	                    </div>
	                	
	                	<div id="latest-videos-container" class="clrfix">
						<?php foreach( $latestVideos as $video ) { ?>
	
				        <div class="video-items video-item jomTips" id="<?php echo "video-" . $video->id ?>" title="<?php echo $video->title . '::' . CStringHelper::trim($this->escape($video->description) , VIDEO_TIPS_LENGTH ); ?>">
					        
							<div class="video-item clrfix">
							
					            <div class="video-thumb">
				                    <a class="video-thumb-url" href="<?php echo $video->url; ?>" style="width: <?php echo $videoThumbWidth; ?>px; height:<?php echo $videoThumbHeight; ?>px;">
										<img src="<?php echo $video->thumb; ?>" style="width: <?php echo $videoThumbWidth; ?>px; height:<?php echo $videoThumbHeight; ?>px;" alt="<?php echo $this->escape($video->title); ?>" />
									</a>
				                    <span class="video-durationHMS"><?php echo $video->durationHMS; ?></span>
					            </div>
					
					            <div class="video-summary">
					                <div class="video-title">
					                	<a href="<?php echo $video->url; ?>"><?php echo $video->title; ?></a>
					                </div>
					                
					                <div class="video-details small">
					                    <div class="video-hits"><?php echo JText::sprintf('CC VIDEO HITS COUNT', $video->hits) ?></div>
					                    <div class="video-lastupdated">
											<?php echo JText::sprintf('CC VIDEO LAST UPDATED', JHTML::_('date', $video->created , JText::_('DATE_FORMAT_LC2')) ); ?>
										</div>
					                    <div class="video-creatorName">
											<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$video->creator); ?>">
												<?php echo $video->creatorName; ?>
											</a>
										</div>
					                </div>
					            </div>

				            </div>
				            
						</div>
				        
						<?php } ?>
	                    
	                </div>
									</div>
	                <div class="app-box-footer">
	                    <a href="<?php echo CRoute::_('index.php?option=com_community&view=videos'); ?>"><?php echo JText::_('CC VIEW ALL VIDEOS'); ?></a>
	                </div>
	                
	            </div>
	            <!-- Latest Video -->
	        <?php } ?>
			<?php } ?>
			<?php } ?>

	
		  	
	    </div>
	    <!-- end: .cMain -->

	</div>
	<!-- end: .cLayout -->

	
</div>
<!-- begin: #cFrontpageWrapper -->