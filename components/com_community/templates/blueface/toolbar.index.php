<?php
$view 			= JRequest::getVar('view', 'frontpage', 'REQUEST');
$groupKey		= $customToolbar->getToolBarGroupKey();
$toolbarClass	= array();
//$toolbarClass = array('frontpage'=> '', 'profile' => '', 'friends'=>'', 'apps'=>'', 'inbox'=>'', 'notify' => '', 'extra' => '' );
//$toolbarClass = array_fill_keys($groupKey, '');
if(! empty($groupKey))
{
	$emptyArr		= array_fill(0, count($groupKey), '');
	$toolbarClass	= array_combine($groupKey, $emptyArr);
} 

$uri			= JRequest::getUri();
$activeToolbar	= $customToolbar->getActiveToolBarGroup($uri);

/**
 * If cannot locate the uri string, then we use view to determine.
 */ 
if(empty($activeToolbar))
{	
	$activeToolbar	= $customToolbar->getGroupActiveView($view);
}

$toolbarClass[$activeToolbar] = 'toolbar-active';
if(! empty($toolbarClass[TOOLBAR_PROFILE]))
	$toolbarClass[TOOLBAR_PROFILE] = (!$isMine && $activeToolbar == TOOLBAR_PROFILE) ? '':$toolbarClass[TOOLBAR_PROFILE]; 
?>
<div id="cToolbarNav">
	<ul id="community-toolbar">
		<?php
			if( $config->get('displayhome') )
			{
				if(isset($customToolbar) && !empty($customToolbar)){
					if($customToolbar->hasToolBarGroup(TOOLBAR_HOME)){
						$homeItem	= $customToolbar->getToolbarItems(TOOLBAR_HOME);			
		?>
	    <li id="toolbar-item-frontpage" class="<?php echo $toolbarClass[TOOLBAR_HOME]; ?> toolbar-item">
			<a href="<?php echo $homeItem->link; ?>" onmouseover="joms.toolbar.open('m0')" onmouseout="joms.toolbar.closetime()">
				<?php echo $homeItem->caption; ?>
			</a>
        	<?php
				if(!empty($homeItem) && (!empty($homeItem->child['append']) || !empty($homeItem->child['prepend'])))
				{
					echo '<div id="m0" onmouseover="joms.toolbar.cancelclosetime()" onmouseout="joms.toolbar.closetime()">';
					echo $customToolbar->getMenuItems(TOOLBAR_HOME, 'all');
					echo '</div>';		
				}
        	?>			
		</li>
		<?php
					}
				}		
			}
		?>
		
		<?php
			if(isset($customToolbar) && !empty($customToolbar)){
				if($customToolbar->hasToolBarGroup(TOOLBAR_PROFILE)){
					$profileItem	= $customToolbar->getToolbarItems(TOOLBAR_PROFILE);
		?>		
	    <li id="toolbar-item-profile" class="<?php echo $toolbarClass[TOOLBAR_PROFILE]; ?> toolbar-item">
			<a href="<?php echo $profileItem->link; ?>" onmouseover="joms.toolbar.open('m1')" onmouseout="joms.toolbar.closetime()" class="has-submenu">
				<?php echo $profileItem->caption; ?>
			</a>
	        <div id="m1" onmouseover="joms.toolbar.cancelclosetime()" onmouseout="joms.toolbar.closetime()" style="visibility: hidden;" class="<?php echo $toolbarClass[TOOLBAR_PROFILE]; ?>">
		        	<?php echo $customToolbar->getMenuItems(TOOLBAR_PROFILE, 'prepend'); ?>
		        	<?php echo $customToolbar->getMenuItems(TOOLBAR_PROFILE, 'append'); ?>				
	        </div>
	    </li>
	    <?php
    			}
    		}	
	    ?>
		
		<?php
			if(isset($customToolbar) && !empty($customToolbar)){
				if($customToolbar->hasToolBarGroup(TOOLBAR_FRIEND)){
					$frenItem	= $customToolbar->getToolbarItems(TOOLBAR_FRIEND);
		?>			    
	    <li id="toolbar-item-friends" class="<?php echo $toolbarClass[TOOLBAR_FRIEND];?> toolbar-item">			
			<a href="<?php echo $frenItem->link; ?>" onmouseover="joms.toolbar.open('m2')" onmouseout="joms.toolbar.closetime()" class="has-submenu">
				<?php echo $frenItem->caption; ?>
			</a>			
	        <div id="m2" onmouseover="joms.toolbar.cancelclosetime()" onmouseout="joms.toolbar.closetime()" style="visibility: hidden;" class="<?php echo $toolbarClass[TOOLBAR_FRIEND];?>">
	        	<?php echo $customToolbar->getMenuItems(TOOLBAR_FRIEND, 'prepend');?>
	        	<?php echo $customToolbar->getMenuItems(TOOLBAR_FRIEND, 'append');?>				
	        </div>
	    </li>
	    <?php
    			}
    		}	
	    ?>	    

		<?php
			if(isset($customToolbar) && !empty($customToolbar)){
				if($customToolbar->hasToolBarGroup(TOOLBAR_APP)){
					$appItem	= $customToolbar->getToolbarItems(TOOLBAR_APP);
		?>	    
  		<li id="toolbar-item-apps" class="<?php echo $toolbarClass[TOOLBAR_APP];?> toolbar-item">			
			<a href="<?php echo $appItem->link; ?>" onmouseover="joms.toolbar.open('m3')" onmouseout="joms.toolbar.closetime()" class="has-submenu">
				<?php echo $appItem->caption; ?>
			</a>			
	        <div id="m3" onmouseover="joms.toolbar.cancelclosetime()" onmouseout="joms.toolbar.closetime()" style="visibility: hidden; overflow: hidden;" class="<?php echo $toolbarClass[TOOLBAR_APP];?>">
	        	<?php echo $customToolbar->getMenuItems(TOOLBAR_APP, 'prepend'); ?>
	        	<?php echo $customToolbar->getMenuItems(TOOLBAR_APP, 'append'); ?>	        				
	        </div>
		</li>
	    <?php
    			}
    		}	
	    ?>		
		<?php
		if( $config->get('enablepm') )
		{
			if(isset($customToolbar) && !empty($customToolbar)){
				if($customToolbar->hasToolBarGroup(TOOLBAR_INBOX)){
					$inboxItem	= $customToolbar->getToolbarItems(TOOLBAR_INBOX);		
		?>
  		<li id="toolbar-item-inbox" class="<?php echo $toolbarClass[TOOLBAR_INBOX];?> toolbar-item">			
			<a href="<?php echo $inboxItem->link; ?>" onmouseover="joms.toolbar.open('m4')" onmouseout="joms.toolbar.closetime()" class="has-submenu">
				<?php echo $inboxItem->caption; ?>
			</a>
	        <div id="m4" onmouseover="joms.toolbar.cancelclosetime()" onmouseout="joms.toolbar.closetime()" style="visibility: hidden;" class="<?php echo $toolbarClass[TOOLBAR_INBOX];?>">
	        	<?php echo $customToolbar->getMenuItems(TOOLBAR_INBOX, 'prepend'); ?>
				<?php echo $customToolbar->getMenuItems(TOOLBAR_INBOX, 'append'); ?>				
	        </div>
		</li>
		<?php
    			}
    		}		
		}
		?>
		<?php
			if(isset($customToolbar) && !empty($customToolbar)){
				$myExtraToolbar	=& $customToolbar->getExtraToolbars();
				if(! empty($myExtraToolbar)) 				
				{
					$startCnt	= 5; //this counter used for javascript and div id.
					foreach($myExtraToolbar as $key	=> $item)
					{
						echo '<li id="toolbar-item-'.$startCnt.'" class="'.$toolbarClass[$key].' toolbar-item">';
						echo '	<a href="'.$item->link.'" onmouseover="joms.toolbar.open(\'m'.$startCnt.'\')" onmouseout="joms.toolbar.closetime()" class="has-submenu">'.$item->caption.'</a>';
						echo '	<div id="m'.$startCnt.'" onmouseover="joms.toolbar.cancelclosetime()" onmouseout="joms.toolbar.closetime()" style="visibility: hidden;">';
						echo $customToolbar->getMenuItems($key, 'all');							
						echo '	</div>';
						echo '</li>';
						$startCnt++;
					}//end foreach
				}//end if
			}
		?>		
		
		<?php if ( (!empty($notiAlert)) && ($notiAlert > 0) ) { ?>
		<li id="toolbar-item-notify" class="toolbar-item">
			<a href="javascript:joms.notifications.showWindow();">
				<span id="toolbar-item-notify-count"><?php echo $notiAlert; ?></span>
			</a>
		</li>
		<?php }//end if?>
	</ul>
	
	<div class="toolbar-myname">
		<?php if( $config->get('fbconnectkey') && $config->get('fbconnectsecret') && $isFacebookUser ) { ?>
			<div id="fb-root"></div>
			<script type="text/javascript">
			var count	= 1;
			window.fbAsyncInit = function() {
			    FB.init({appId: '<?php echo $config->get('fbconnectkey');?>', status: false, cookie: true, xfbml: true});
			         FB.Event.subscribe('auth.logout', function(response) {
			         	joms.jQuery('#communitylogout').submit();
			         });
			};
			(function() {
			    var e = document.createElement('script');
			    e.type = 'text/javascript';
			    e.src = document.location.protocol +
			        '//connect.facebook.net/en_US/all.js';
			    e.async = true;
			    document.getElementById('fb-root').appendChild(e);
			}());
			</script>
			<fb:login-button autologoutlink="true" size="small" background="light"><?php echo JText::_('CC LOGOUT');?></fb:login-button>
			<form action="index.php" method="post" name="communitylogout" id="communitylogout">
				<input type="hidden" name="option" value="com_user" />
				<input type="hidden" name="task" value="logout" />
				<input type="hidden" name="return" value="<?php echo $logoutLink; ?>" />
			</form>			
		
		<?php } else { ?>
		
			<form action="index.php" method="post" name="communitylogout" id="communitylogout">
				<a href="javascript:void(0);" onclick="document.communitylogout.submit();"><?php echo JText::_('CC LOGOUT');?></a>
				<input type="hidden" name="option" value="com_user" />
				<input type="hidden" name="task" value="logout" />
				<input type="hidden" name="return" value="<?php echo $logoutLink; ?>" />
			</form>
			
		<?php } ?>
	</div>
	<div class="clr"></div>
</div>
<?php if ( $miniheader ) : ?>
	<?php echo @$miniheader; ?>
<?php endif; ?>
