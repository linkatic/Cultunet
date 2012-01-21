if (typeof(joms)=='undefined')
{
	joms = {
		jQuery: window.jQuery,
		extend: function(obj){
			this.jQuery.extend(this, obj);
		}
	}
}

joms.extend({
	plugins: {
		extend: function (obj){
			//joms.jQuery.extend(joms.plugin, func);
			joms.jQuery.extend(joms.plugins, obj);
		},
		initialize: function()
		{
			joms.jQuery.each(joms.plugins, function(index, value) {
				try{
				    value.initialize();
				}
				catch(err)
				{
					//Handle errors here
				}
			});
		}
	},
	activities: {
		getContent: function( activityId ){
				jax.call('community' , 'activities,ajaxGetContent' , activityId );
		},
		setContent: function( activityId , content ){
			joms.jQuery("#profile-newsfeed-item-content-" + activityId ).html( content );
			joms.jQuery("#profile-newsfeed-item-content-" + activityId ).removeClass("small profile-newsfeed-item-action").addClass("newsfeed-content-hidden").slideDown();
		},
		showVideo: function( activityId ){
			joms.jQuery('#profile-newsfeed-item-content-' + activityId + ' .video-object').slideDown();
			joms.jQuery('#profile-newsfeed-item-content-' + activityId + ' .video-object embed').css('width' , joms.jQuery('#profile-newsfeed-item-content-' + activityId ).width() );
		}
	},
	apps: {
		windowTitle: '',
		toggle: function (id){
			joms.jQuery(id).children('.app-box-actions').slideToggle('fast');
			joms.jQuery(id).children('.app-box-footer').slideToggle('fast');
			joms.jQuery(id).children('.app-box-content').slideToggle('fast',
				function() {

					joms.jQuery.cookie( id , joms.jQuery(this).css('display') );
					joms.jQuery(id).toggleClass('collapse', (joms.jQuery(this).css('display')=='none'));
				}
			);
		},
		showAboutWindow: function(appName){
			var ajaxCall = "jax.call('community', 'apps,ajaxShowAbout', '"+appName+"');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		showPrivacyWindow: function(appName){
			var ajaxCall = "jax.call('community', 'apps,ajaxShowPrivacy', '"+appName+"');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		showSettingsWindow: function(id, appName){
			var ajaxCall = "jax.call('community', 'apps,ajaxShowSettings', '"+id+"', '"+appName+"');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		savePrivacy: function(){
			var value   = joms.jQuery('input[name=privacy]:checked').val();
			var appName = joms.jQuery('input[name=appname]').val();
			jax.call('community', 'apps,ajaxSavePrivacy', appName, value);
		},
		saveSettings: function(){
			jax.call('community', 'apps,ajaxSaveSettings', jax.getFormValues('appSetting'));
		},
		remove: function(appName){
			var ajaxCall = "jax.call('community', 'apps,ajaxRemove', '"+appName+"');";
			cWindowShow(ajaxCall, this.windowTitle, 450, 100);
		},
		add: function(appName){
			jax.call('community', 'apps,ajaxAdd', appName );
		},
		initToggle: function(){
			joms.jQuery('.app-box').each(function(){
				var id	= '#' + joms.jQuery(this).attr('id');

				if(joms.jQuery.cookie( id )=='none')
				{
					joms.jQuery(id).addClass('collapse');
					joms.jQuery(id).children('.app-box-actions').css('display' , 'none' );
					joms.jQuery(id).children('.app-box-footer').css('display' , 'none' );
					joms.jQuery(id).children('.app-box-content').css('display' , 'none' );
				}
			});
		}
	},
	bookmarks:{
		show: function( currentURI ){
			var ajaxCall = "jax.call('community', 'bookmarks,ajaxShowBookmarks','" + currentURI + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		email: function( currentURI ){

			var formContent	= jax.getFormValues('bookmarks-email');
			var content		= formContent[1][1];
			var email		= formContent[0][1];

			var ajaxCall = "jax.call('community', 'bookmarks,ajaxEmailPage','" + currentURI + "','" +  email + "',\"" + content + "\");";
			cWindowShow( ajaxCall , '', 450, 100);
		}
	},
	report: {
		emptyMessage: '',

		checkReport: function(){
			if( joms.jQuery( '#report-message' ).val() == '' )
			{
				joms.jQuery( '#report-message-error' ).html( this.emptyMessage ).css( 'color' , 'red' );
				return false;
			}
			return true;
		},
		showWindow: function ( reportFunc, arguments ){   
			var ajaxCall	= 'jax.call("community" , "system,ajaxReport" , "' + reportFunc + '","' + location.href + '" ,' + arguments + ');';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		submit: function ( reportFunc , pageLink , arguments ){
			if( joms.report.checkReport() )
			{
			var formVars = jax.getFormValues('report-form');

			var ajaxcall='jax.call("community", "system,ajaxSendReport","' + reportFunc + '","' + location.href + '","' + formVars[1][1] + '" , ' + arguments + ')';

// 				var message	= escape( joms.jQuery('#report-message').val() );
// 				var ajaxcall='jax.call("community", "system,ajaxSendReport","' + reportFunc + '","' + location.href + '","' + message + '" , ' + arguments + ')';
				cWindowShow(ajaxcall, '', 450, 100);
			}
		}
	},
	featured: {
		add: function(uniqueId , controller ){
			var ajaxCall = "jax.call('community', '" + controller + ",ajaxAddFeatured', '"+uniqueId+"');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		remove: function(uniqueId , controller ){
			var ajaxCall = "jax.call('community','" + controller + ",ajaxRemoveFeatured','" + uniqueId + "');";
			cWindowShow(ajaxCall,'', 450, 100);
		}
	},
	flash: {
		enabled: function(){
			// ie
			try
			{
				try
				{
					// avoid fp6 minor version lookup issues
					// see: http://blog.deconcept.com/2006/01/11/getvariable-setvariable-crash-internet-explorer-flash-6/
					var axo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash.6');
					try
					{
						axo.AllowScriptAccess = 'always';
					}
					catch(e)
					{
						return '6,0,0';
					}
				}
				catch(e)
				{
				}
				return new ActiveXObject('ShockwaveFlash.ShockwaveFlash').GetVariable('$version').replace(/\D+/g, ',').match(/^,?(.+),?$/)[1];
			// other browsers
			}
			catch(e)
			{
				try
				{
					if(navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin)
					{
						return (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]).description.replace(/\D+/g, ",").match(/^,?(.+),?$/)[1];
					}
				}
				catch(e)
				{
				}
			}
			return false;
		}
	},
	notifications: {
		showWindow: function (){
			var ajaxCall = 'jax.call("community", "notification,ajaxGetNotification", "")';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		updateNotifyCount: function (){
			var notifyCount	= joms.jQuery('#toolbar-item-notify-count').text();

			if(joms.jQuery.trim(notifyCount) != '' && notifyCount > 0)
			{
				//first we update the count. if the updated count == 0, then we hide the tab.
				notifyCount = notifyCount - 1;
				joms.jQuery('#toolbar-item-notify-count').html(notifyCount);
				if (notifyCount == 0)
				{
					joms.jQuery('#toolbar-item-notify').hide();
				}
			}
		}
	},
	filters:{
		bind: function(){
			var loading	= this.loading;
			joms.jQuery(document).ready( function()
			{
				//sorting option binding for members display
				joms.jQuery('.newest-member').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
				        loading( joms.jQuery(this).attr('class') );
						jax.call('community', 'frontpage,ajaxGetNewestMember', frontpageUsers);
					}
				});
				joms.jQuery('.active-member').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
			            loading( joms.jQuery(this).attr('class') );
						jax.call('community', 'frontpage,ajaxGetActiveMember', frontpageUsers);
					}
				});
				joms.jQuery('.popular-member').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
				        loading( joms.jQuery(this).attr('class') );
				    	jax.call('community', 'frontpage,ajaxGetPopularMember', frontpageUsers);
					}
				});
				joms.jQuery('.featured-member').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
				        loading( joms.jQuery(this).attr('class') );
				    	jax.call('community', 'frontpage,ajaxGetFeaturedMember', frontpageUsers);
					}
				});

				//sorting option binding for activity stream
				joms.jQuery('.all-activity').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
			            loading( joms.jQuery(this).attr('class') );
						jax.call('community', 'frontpage,ajaxGetActivities', 'all');
					}
				});
				joms.jQuery('.me-and-friends-activity').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
				        loading( joms.jQuery(this).attr('class') );
				    	jax.call('community', 'frontpage,ajaxGetActivities', 'me-and-friends');
					}
				});
				joms.jQuery('.active-profile-and-friends-activity').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
				        loading( joms.jQuery(this).attr('class') );
				    	jax.call('community', 'frontpage,ajaxGetActivities', 'active-profile-and-friends', joms.user.getActive());
					}
				});
				joms.jQuery('.active-profile-activity').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
				        loading( joms.jQuery(this).attr('class') );
				    	jax.call('community', 'frontpage,ajaxGetActivities', 'active-profile', joms.user.getActive());
					}
				});
				joms.jQuery('.p-active-profile-and-friends-activity').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
				        loading( joms.jQuery(this).attr('class') );
				    	jax.call('community', 'frontpage,ajaxGetActivities', 'active-profile-and-friends', joms.user.getActive(), 'profile');
					}
				});
				joms.jQuery('.p-active-profile-activity').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
				        loading( joms.jQuery(this).attr('class') );
				    	jax.call('community', 'frontpage,ajaxGetActivities', 'active-profile', joms.user.getActive(), 'profile');
					}
				});

				// sorting and binding for videos
				joms.jQuery('.newest-videos').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
				        loading( joms.jQuery(this).attr('class') );
						jax.call('community', 'frontpage,ajaxGetNewestVideos', frontpageVideos);
					}
				});
				joms.jQuery('.popular-videos').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
				        loading( joms.jQuery(this).attr('class') );
				    	jax.call('community', 'frontpage,ajaxGetPopularVideos', frontpageVideos);
					}
				});
				joms.jQuery('.featured-videos').bind('click', function() {
				    if ( !joms.jQuery(this).hasClass('active-state') ) {
				        loading( joms.jQuery(this).attr('class') );
				    	jax.call('community', 'frontpage,ajaxGetFeaturedVideos', frontpageVideos);
					}
				});

				// remove last link border
				joms.jQuery('.popular-member').css('border-right', '0').css('padding-right', '0');
				joms.jQuery('.me-and-friends-activity').css('border-right', '0').css('padding-right', '0');
				joms.jQuery('.active-profile-activity').css('border-right', '0').css('padding-right', '0');
			});
		},
		loading: function(element){
			elParent = joms.jQuery('.'+element).parent().parent().attr('id');
			if ( elParent === '' ) {
		        elParent = joms.jQuery('.'+element).parent().attr('id');
			}
		    joms.jQuery('#' + elParent + ' .loading').show();
		    joms.jQuery('#' + elParent + ' a').removeClass('active-state');
		    joms.jQuery('.'+element).addClass('active-state');
		},
		hideLoading: function(){
			joms.jQuery( '.loading' ).hide();
			// rebind the tooltip
			joms.jQuery('.jomTipsJax').addClass('jomTips');
			joms.tooltip.setup();
		}
	},
	groups: {
		invitation: {
			accept: function( groupId ){
				jax.call( 'community' , 'groups,ajaxAcceptInvitation' , groupId )
			},
			reject: function( groupId ){
				jax.call( 'community' , 'groups,ajaxRejectInvitation' , groupId  );
			}
		},
		addInvite: function( element ){
			var parentId = joms.jQuery('#' +element).parent().attr('id');

			if(parentId == "friends-list")
			{
				joms.jQuery("#friends-invited").append(joms.jQuery('#' +element)).html();
			}
			else
			{
				joms.jQuery("#friends-list").append(joms.jQuery('#' +element)).html();
			}
		},
		removeTopic: function( title , groupid , topicid ){
			var ajaxCall = 'jax.call("community","groups,ajaxShowRemoveDiscussion", "' + groupid + '","' + topicid + '");';
			cWindowShow(ajaxCall, title, 450, 100);
		},
		editBulletin: function(){

			if( joms.jQuery('#bulletin-edit-data').css('display') == 'none' )
			{
				joms.jQuery('#bulletin-edit-data').show();
			}
			else
			{
				joms.jQuery('#bulletin-edit-data').hide();
			}

		},
		removeBulletin: function( title , groupid , bulletinid ){
			var ajaxCall = 'jax.call("community", "groups,ajaxShowRemoveBulletin", "' + groupid + '","' + bulletinid + '");';
			cWindowShow(ajaxCall, title, 450, 100);
		},
		unpublish: function( groupId ){
			jax.call( 'community' , 'groups,ajaxUnpublishGroup', groupId);
		},
		leave: function( groupid ){
			var ajaxCall = 'jax.call("community", "groups,ajaxShowLeaveGroup", "' + groupid + '");';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		joinWindow: function( groupid ){
			var ajaxCall = 'jax.call("community", "groups,ajaxShowJoinGroup", "' + groupid + '", location.href );';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		edit: function(){
			// Check if input is already displayed
			joms.jQuery('#community-group-info .cdata').each(function(){
				// Test if the next div is cinput

				if(joms.jQuery(this).next().html() && joms.jQuery(this).css('display') != 'none' )
					joms.jQuery(this).css('display' , 'none');
				else
					joms.jQuery(this).css('display' , 'block');
			});

			joms.jQuery('#community-group-info .cinput').each(function(){
				if(joms.jQuery(this).css('display') == 'none')
					joms.jQuery(this).css('display' , 'block');
				else
					joms.jQuery(this).css('display' , 'none');
			});

			if(joms.jQuery('div#community-group-info-actions').css('display') != 'none')
				joms.jQuery('div#community-group-info-actions').css('display' , 'none');
			else
				joms.jQuery('div#community-group-info-actions').css('display' , 'block');
		},
		save: function( groupid ){
			var name		= joms.jQuery('#community-group-name').val();
			var description	= joms.jQuery('#community-group-description').val();
			var website		= joms.jQuery('#community-group-website').val();
			var category	= joms.jQuery('#community-group-category').val();
			var approvals	= joms.jQuery("input[@name='group-approvals']:checked").val();

			jax.call('community' , 'groups,ajaxSaveGroup' , groupid , name , description , website , category , approvals);
		},
		update: function( groupName , groupDescription , groupWebsite , groupCategory){
			// Re-update group data
			joms.jQuery('#community-group-data-name').html( groupName );
			joms.jQuery('#community-group-data-description').html( groupDescription );
			joms.jQuery('#community-group-data-website').html( groupWebsite );
			joms.jQuery('#community-group-data-category').html( groupCategory );
			this.edit();
		},
		removeMember: function( memberId , groupId ){
			jax.call('community', 'groups,ajaxRemoveMember', memberId , groupId );
		},
		deleteGroup: function( groupId ){
			var ajaxCall = "jax.call('community', 'groups,ajaxWarnGroupDeletion', '" + groupId + "');";
			cWindowShow(ajaxCall, '', 450, 100, 'warning');
		}
	},
	friends: {
		saveTag: function(){
			var formVars = jax.getFormValues('tagsForm');
			jax.call("community", "friends,ajaxFriendTagSave", formVars);
			return false;
		},
		saveGroup: function(userid) {
			if(document.getElementById('newtag').value == ''){
			    window.alert('TPL_DB_INVALIDTAG');
			}else{
				jax.call("community", "friends,ajaxAddGroup",userid,joms.jQuery('#newtag').val());
			}
		},
		cancelRequest: function( friendsId ){
			var ajaxCall = 'jax.call("community" , "friends,ajaxCancelRequest" , "' + friendsId + '");';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		connect: function( friendid ){
			var ajaxCall = 'jax.call("community", "friends,ajaxConnect", '+friendid+')';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		addNow: function(){
		 	var formVars = jax.getFormValues('addfriend');
		 	jax.call("community", "friends,ajaxSaveFriend",formVars);
		 	return false;
		}
	},
	messaging: {
		loadComposeWindow: function(userid){
			var ajaxCall = 'jax.call("community", "inbox,ajaxCompose", '+userid+')';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		send: function(){
			var formVars = jax.getFormValues('writeMessageForm');
			jax.call("community", "inbox,ajaxSend", formVars);
			return false;
		}
	},
	walls: {
		add: function ( uniqueId, addFunc ){

			jax.loadingFunction = function()
			{
				joms.jQuery('#wall-message').attr('disabled', true);
				joms.jQuery('#wall-submit').attr('disabled', true);
			}

			jax.doneLoadingFunction = function()
			{
				joms.jQuery('#wall-message').attr('disabled', false);
				joms.jQuery('#wall-submit').attr('disabled', false);
			};

			if(typeof getCacheId == 'function')
			{
				cache_id = getCacheId();
			}
			else
			{
				cache_id = "";
			}

			jax.call('community', addFunc, joms.jQuery('#wall-message').val(), uniqueId, cache_id);
		},
		insert: function( html ){
			joms.jQuery('#wall-message').val('');
			joms.jQuery('#wallContent').prepend(html);
		},
		remove: function( type , wallId , contentId ){
			if(confirm('Are you sure you want to delete this wall?'))
			{
				jax.call('community' , type + ',ajaxRemoveWall' , wallId , contentId );
				joms.jQuery('#wall_' + wallId ).fadeOut('normal', function(){joms.jQuery(this).remove()});

				// Process ajax calls
			}
		},
		update: function( id , message ){
			//Hide popups
			cWindowHide();

			//Update the existing html codes
			joms.jQuery( '#wall_' + id ).replaceWith( message );
		},
		save: function( id , editableFunc ){
			jax.call('community' , 'system,ajaxUpdateWall' , id , joms.jQuery('#wall-edit-' + id).val() , editableFunc );
		},
		edit: function( id , permissionFunc ){

			if( joms.jQuery('#wall-edit-' + id ).val() != null )
			{
				joms.jQuery('#wall-message-'+ id).show();
				joms.jQuery('#wall-edit-container-'+ id).remove();
			}
			else
			{
				// Hide current message
				joms.jQuery('#wall-message-' + id ).hide();
				joms.jQuery('#wall_' + id + ' div.content').prepend( '<span id="wall-edit-container-' + id + '"></span>').prepend('<div class="loading" style="display:block;float: left;"></div>');

				jax.call('community' , 'system,ajaxEditWall' , id , permissionFunc );
				joms.utils.textAreaWidth('#wall-edit-' + id );
				joms.utils.autogrow('#wall-edit-' + id);
			}
		}
	},
	toolbar: {
		timeout: 500,
		closetimer: 0,
		ddmenuitem: 0,
		open: function( id ){

			if ( joms.jQuery('#'+id).length > 0 ) {
				// cancel close timer
				joms.toolbar.cancelclosetime();

				// close old layer
				if(joms.toolbar.ddmenuitem)
				{
					joms.toolbar.ddmenuitem.style.visibility = 'hidden';
				}

				// get new layer and show it
				joms.toolbar.ddmenuitem = document.getElementById(id);
				joms.toolbar.ddmenuitem.style.visibility = 'visible';
			}
		},
		close: function(){
			if(joms.toolbar.ddmenuitem)
			{
				joms.toolbar.ddmenuitem.style.visibility = 'hidden';
			}
		},
		closetime: function(){
			joms.toolbar.closetimer	= window.setTimeout( joms.toolbar.close , joms.toolbar.timeout );
		},
		cancelclosetime: function(){
			if( joms.toolbar.closetimer )
			{
				window.clearTimeout( joms.toolbar.closetimer );
				joms.toolbar.closetimer = null;
			}
		}
	},
	registrations:{
		windowTitle: '',
		showTermsWindow: function(){
			var ajaxCall = 'jax.call("community", "register,ajaxShowTnc", "")';
			cWindowShow(ajaxCall, this.windowTitle , 600, 350);
		},
		authenticate: function(){
			jax.call("community", "register,ajaxGenerateAuthKey");
		},
		authenticateAssign: function(){
			jax.call("community", "register,ajaxAssignAuthKey");
		},
		assignAuthKey: function(fname, lblname, authkey){
			eval("document.forms['" + fname + "'].elements['" + lblname + "'].value = '" + authkey + "';");
		},
		showWarning: function(message) {
			cWindowShow('joms.jQuery(\'#cWindowContent\').html(\''+message+'\')' , 'Notice' , 450 , 200 , 'warning');
		}
	},
	comments:{
		add: function(id){
			var cmt = joms.jQuery('#'+ id +' textarea').val();
			if(cmt != '') {
				joms.jQuery('#'+ id +' .wall-coc-form-action.add').attr('disabled', true);
				if(typeof getCacheId == 'function')
				{
					cache_id = getCacheId();
				}
				else
				{
					cache_id = "";
				}
				jax.call("community", "plugins,walls,ajaxAddComment", id, cmt, cache_id);
			}
		},
		insert: function(id, text){
			joms.jQuery('#'+ id +' form').before(text);
			joms.comments.cancel(id);
		},
		remove: function(obj){
			var cmtDiv = joms.jQuery(obj).parents('.wallcmt');
			var index  = joms.jQuery(obj).parents('.wallcmt').parent().children().index(cmtDiv);
			try{ console.log(index); } catch(err){}
			var parentId = joms.jQuery(obj).parents('.wallcmt').parent().attr('id');
			try{ console.log(parentId); } catch(err){}
			//joms.jQuery(obj).parent('.wallcmt').remove();

			jax.call("community", "plugins,walls,ajaxRemoveComment", parentId, index);
		},
		cancel: function(id){
			joms.jQuery('#'+ id +' textarea').val('');
			joms.jQuery('#'+ id +' form').hide();
			joms.jQuery('#'+ id +' .show-cmt').show();
			joms.jQuery('#'+ id + ' .wall-coc-errors').hide();
		},
		show: function(id){
			var w = joms.jQuery('#'+ id +' form').parent().width();

			joms.jQuery('#'+ id +' .wall-coc-form-action.add').attr('disabled', false);
			joms.jQuery('#'+ id +' form').width(w).show();
			joms.jQuery('#'+ id +' .show-cmt').hide();

			var textarea = joms.jQuery('#'+ id +' textarea');
			joms.utils.textAreaWidth(textarea);
			joms.utils.autogrow(textarea);

			textarea.blur(function(){
				if (joms.jQuery(this).val()=='') joms.comments.cancel(id);
			});
		}
	},
	utils: {
		// Resize the width of the giventext to follow the innerWidth of
		// another DOM object
		// The textarea must be visible
		textAreaWidth: function(target) {
			with (joms.jQuery(target))
			{
				css('width', '100%');
				// Google Chrome doesn't return correct outerWidth() else things would be nicer.
				// css('width', width()*2 - outerWidth(true));
				css('width', width() - parseInt(css('borderLeftWidth'))
				                     - parseInt(css('borderRightWidth'))
				                     - parseInt(css('padding-left'))
				                     - parseInt(css('padding-right')));
			}
		},

		autogrow: function (id, options) {
			if (options==undefined)
				options = {};

			// In JomSocial, by default every autogrow element will have a 300 maxHeight.
			options.maxHeight = options.maxHeight || 300;

			joms.jQuery(id).autogrow(options);
		}
	},
	maps: {
		mapsObj: null,
		geocoder: null,
		initialize: function(target, address, title, info) {
			if(typeof google.maps =='undefined')
			{
				// Google map is not loaded yet, wait another 1 seconds?
				setTimeout('joms.maps.initialize(\''+target+'\', \''+address+'\')', 1000);
			}
			else
			{
				joms.maps.geocoder = new google.maps.Geocoder();
				joms.maps.geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {

						if(joms.maps.mapsObj == null)
						{
							joms.maps.mapsObj = new Array();
						}
						// @todo: unoptimized, should not load a random location first
						var latlng = new google.maps.LatLng(-34.397, 150.644);
					    var mapOptions = {
					      zoom: 14,
					      center: latlng,
					      mapTypeId: google.maps.MapTypeId.ROADMAP
					    }

					    // Map id is incremented for each map
					    var mapId = joms.maps.mapsObj.length;

					    joms.maps.mapsObj[mapId] = new google.maps.Map(document.getElementById(target), mapOptions);

						joms.maps.mapsObj[mapId].setCenter(results[0].geometry.location);
						var marker = new google.maps.Marker({
							map: joms.maps.mapsObj[mapId],
							position: results[0].geometry.location,
							title:title
						});

						if(info.length > 0){
							var infowindow = new google.maps.InfoWindow({
							    content: info
							});

							google.maps.event.addListener(marker, 'click', function() {
								var mapId  = joms.jQuery('div#'+target).data('maps');
								infowindow.open(joms.maps.mapsObj[mapId], marker);
							});
						}

						// Store map object in the div id
						joms.jQuery('div#'+target).data('maps', mapId);

					} else {
						alert("Geocode was not successful for the following reason: " + status);
					}
				});
			}
		},
		addMarker: function(target, lat, lng, title, info) {
			if(joms.maps.mapsObj == null)
			{
				// Google map is not loaded yet, wait another 1 seconds?
				setTimeout('joms.maps.addMarker(\''+target+'\', '+lat+', '+lng+', \''+title+'\', \''+info+'\')', 1000);
			}
			else
			{
				var mapId  = joms.jQuery('div#'+target).data('maps');
				var myLatlng = new google.maps.LatLng(lat,lng);
				var marker = new google.maps.Marker({
					position: myLatlng,
					map: joms.maps.mapsObj[mapId],
					title:title
				});

				if(info.length > 0){
					var infowindow = new google.maps.InfoWindow({
					    content: info
					});

					google.maps.event.addListener(marker, 'click', function() {
						var mapId  = joms.jQuery('div#'+target).data('maps');
						infowindow.open(joms.maps.mapsObj[mapId], marker);
					});
				}

			}
		}
	},
	connect: {
	    checkRealname: function( value ){
	        var tmpLoadingFunction  = jax.loadingFunction;
			jax.loadingFunction = function(){};
			jax.doneLoadingFunction = function(){ jax.loadingFunction = tmpLoadingFunction; };
			jax.call('community','connect,ajaxCheckName', value);
		},
	    checkEmail: function( value ){
	        var tmpLoadingFunction  = jax.loadingFunction;
			jax.loadingFunction = function(){};
			jax.doneLoadingFunction = function(){ jax.loadingFunction = tmpLoadingFunction; };
			jax.call('community','connect,ajaxCheckEmail', value);
		},
		checkUsername: function( value ){
	        var tmpLoadingFunction  = jax.loadingFunction;
			jax.loadingFunction = function(){};
			jax.doneLoadingFunction = function(){ jax.loadingFunction = tmpLoadingFunction; };
		    jax.call('community','connect,ajaxCheckUsername', value);
		},
		// Displays popup that requires user to update their details upon
		update: function(){
			var ajaxCall = "jax.call('community', 'connect,ajaxUpdate' );";
			cWindowShow(ajaxCall, '', 450, 200);
		},
		updateEmail: function(){
		    joms.jQuery('#facebook-email-update').submit();
		},
		importData: function(){
		    var importStatus    = joms.jQuery('#importstatus').is(':checked') ? 1 : 0;
		    var importAvatar    = joms.jQuery('#importavatar').is(':checked') ? 1 : 0 ;
		    jax.call('community','connect,ajaxImportData',  importStatus , importAvatar );
		},
		mergeNotice: function(){
			var ajaxCall = "jax.call('community','connect,ajaxMergeNotice');";
			cWindowShow(ajaxCall, '', 450, 200);
		},
		merge: function(){
			var ajaxCall = "jax.call('community','connect,ajaxMerge');";
			cWindowShow(ajaxCall, '', 450, 200);
		},
		validateUser: function(){
			// Validate existing user
			var ajaxCall = "jax.call('community','connect,ajaxValidateLogin','" + joms.jQuery('#existingusername').val() + "','" + joms.jQuery('#existingpassword').val() + "');";
			cWindowShow(ajaxCall, '', 450, 200);
		},
		newUser: function(){
			var ajaxCall = "jax.call('community','connect,ajaxShowNewUserForm');";
			cWindowShow(ajaxCall, '', 450, 200);
		},
		existingUser: function(){
			var ajaxCall = "jax.call('community','connect,ajaxShowExistingUserForm');";
			cWindowShow(ajaxCall, '', 450, 200);
		},
		selectType: function(){
			if(joms.jQuery('[name=membertype]:checked').val() == '1' )
			{
				joms.connect.newUser();
			}
			else
			{
				joms.connect.existingUser();
			}
		},
		validateNewAccount: function(){
			// Check for errors on the forms.
			jax.call('community','connect,ajaxCheckEmail', joms.jQuery('#newemail').val() );
			jax.call('community','connect,ajaxCheckUsername', joms.jQuery('#newusername').val() );
			jax.call('community','connect,ajaxCheckName', joms.jQuery('#newname').val() );

			var isValid	= true;
			if(joms.jQuery('#newname').val() == "" || joms.jQuery('#error-newname').css('display') != 'none')
			{
				isValid = false;
			}

			if(joms.jQuery('#newusername').val() == "" || joms.jQuery('#error-newusername').css('display') != 'none')
			{
				isValid = false;
			}

			if(joms.jQuery('#newemail').val() == '' || joms.jQuery('#error-newemail').css('display') != 'none' )
			{
				isValid = false;
			}

			if(isValid)
			{
				var ajaxCall = "jax.call('community', 'connect,ajaxCreateNewAccount' , '" + joms.jQuery('#newname').val() + "', '" + joms.jQuery('#newusername').val() + "','" + joms.jQuery('#newemail').val() + "');";
				cWindowShow(ajaxCall, '', 450, 200);
			}
		}
	},

	// Video component
	videos: {
		playProfileVideo: function(id, userid){
			jax.call('community', 'profile,ajaxPlayProfileVideo', id, userid);
		},
		linkConfirmProfileVideo: function(id){
			var ajaxCall = "jax.call('community', 'profile,ajaxConfirmLinkProfileVideo', '" + id + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		linkProfileVideo: function(id){
			var ajaxCall = "jax.call('community', 'profile,ajaxLinkProfileVideo', '" + id + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		removeConfirmProfileVideo: function(userid, videoid){
			var ajaxCall = "jax.call('community', 'profile,ajaxRemoveConfirmLinkProfileVideo', '" + userid + "', '" + videoid + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		removeLinkProfileVideo: function(userid, videoid){
			var ajaxCall = "jax.call('community', 'profile,ajaxRemoveLinkProfileVideo', '" + userid + "', '" + videoid + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		showEditWindow: function(id , redirectUrl ){

			if( typeof redirectUrl == 'undefined' )
				redirectUrl	= '';

			var ajaxCall = "jax.call('community', 'videos,ajaxEditVideo', '"+id+"' , '" + redirectUrl + "');";
			cWindowShow(ajaxCall, '' , 450, 400);
		},
		deleteVideo: function(videoId){
			var ajaxCall = "jax.call('community' , 'videos,ajaxRemoveVideo', '" + videoId + "','myvideos');";
			cWindowShow(ajaxCall, '', 450, 150);
		},
		playerConf: {
			// Default flowplayer configuration here
		},
		addVideo: function(creatortype, groupid) {
			if(typeof creatortype == "undefined" || creatortype == "")
			{
				var creatortype="";
				var groupid = "";
			}
			var ajaxCall = "jax.call('community', 'videos,ajaxAddVideo', '" + creatortype + "', '" + groupid + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		linkVideo: function(creatortype, groupid) {
			var ajaxCall = "jax.call('community', 'videos,ajaxLinkVideo', '" + creatortype + "', '" + groupid + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		uploadVideo: function(creatortype, groupid) {
			var ajaxCall = "jax.call('community', 'videos,ajaxUploadVideo', '" + creatortype + "', '" + groupid + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		submitLinkVideo: function() {
			var isValid = true;

			videoLinkUrl = "#linkVideo input[name='videoLinkUrl']";
			if(joms.jQuery.trim(joms.jQuery(videoLinkUrl).val())=='')
			{
				joms.jQuery(videoLinkUrl).addClass('invalid');
				isValid = false;
			}
			else
			{
				joms.jQuery(videoLinkUrl).removeClass('invalid');
			}

			if (isValid)
			{
				joms.jQuery('#cwin-wait').css("margin-left","20px");
				joms.jQuery('#cwin-wait').show();

				document.linkVideo.submit();
			}
		},
		submitUploadVideo: function() {
			var isValid = true;

			videoFile = "#uploadVideo input[name='videoFile']";

			if(joms.jQuery.trim(joms.jQuery(videoFile).val())=='')
			{
				joms.jQuery(videoFile).addClass('invalid');
				isValid = false;
			}
			else
			{
				joms.jQuery(videoFile).removeClass('invalid');
			}

			videoTitle = "#uploadVideo input[name='title']";
			if(joms.jQuery.trim(joms.jQuery(videoTitle).val())=='')
			{
				joms.jQuery(videoTitle).addClass('invalid');
				isValid = false;
			}
			else
			{
				joms.jQuery(videoTitle).removeClass('invalid');
			}

			if (isValid)
			{
				joms.jQuery('#cwin-wait').css("margin-left","20px");
				joms.jQuery('#cwin-wait').show();

				document.uploadVideo.submit();
			}
		},
		fetchThumbnail: function(videoId){
			var ajaxCall = "jax.call('community' , 'videos,ajaxFetchThumbnail', '" + videoId + "','myvideos');";
			cWindowShow(ajaxCall, '', 450, 150);
		}
	},
	users: {
		banUser: function( userId , isBlocked ){
			var ajaxCall = "jax.call('community', 'profile,ajaxBanUser', '" + userId + "' , '" + isBlocked + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		removePicture: function( userId ){
			var ajaxCall = "jax.call('community', 'profile,ajaxRemovePicture', '" + userId + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		uploadNewPicture: function( userId ){
			var ajaxCall = "jax.call('community', 'profile,ajaxUploadNewPicture', '" + userId + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
	   blockUser: function( userId ){
			var ajaxCall = 'jax.call("community", "profile,ajaxBlockUser", "' + userId + '");';
			cWindowShow(ajaxCall, '', 450, 100);
       },
	   unBlockUser: function( userId, layout ){
	        layout = layout || null;
			var ajaxCall = 'jax.call("community", "profile,ajaxUnblockUser", "' + userId + '", "' + layout + '");';
			cWindowShow(ajaxCall, '', 450, 100);
       }
	},

	user: {
		getActive: function( ){
			// return the current active user
			return js_profileId;
		}
	},

	events: {
		deleteEvent: function( eventId ){
			var ajaxCall = "jax.call('community', 'events,ajaxWarnEventDeletion', '" + eventId + "');";
			cWindowShow(ajaxCall, '', 450, 100, 'warning');
		},
		join: function( eventId ){
			var ajaxCall = 'jax.call("community", "events,ajaxRequestInvite", "' + eventId + '", location.href );';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		leave: function( eventId ){
			var ajaxCall = 'jax.call("community", "events,ajaxIgnoreEvent", "' + eventId + '");';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		sendmail: function( eventId ){
			var ajaxCall = 'jax.call("community", "events,ajaxSendEmail", "' + eventId + '");';
			cWindowShow(ajaxCall, '', 450, 300);
		},
		confirmBlockGuest: function(userId, eventId){
			var ajaxCall = 'jax.call("community", "events,ajaxConfirmBlockGuest", "' + userId + '", "' + eventId + '");';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		blockGuest: function(userId, eventId){
			var ajaxCall = 'jax.call("community", "events,ajaxBlockGuest", "' + userId + '", "' + eventId + '");';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		confirmUnblockGuest: function(userId, eventId){
			var ajaxCall = 'jax.call("community", "events,ajaxConfirmUnblockGuest", "' + userId + '", "' + eventId + '");';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		unblockGuest: function(userId, eventId){
			var ajaxCall = 'jax.call("community", "events,ajaxUnblockGuest", "' + userId + '", "' + eventId + '");';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		confirmRemoveGuest: function(userId, eventId){
			var ajaxCall = 'jax.call("community", "events,ajaxConfirmRemoveGuest", "' + userId + '", "' + eventId + '");';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		removeGuest: function(userId, eventId){
			var ajaxCall = 'jax.call("community", "events,ajaxRemoveGuest", "' + userId + '", "' + eventId + '");';
			cWindowShow(ajaxCall, '', 450, 100);
		},
		joinNow: function(eventId){
			jax.call("community" , "events,ajaxJoinInvitation" , eventId );
		},
		rejectNow: function(eventId){
			jax.call('community' , 'events,ajaxRejectInvitation' , eventId );
		}
	},
	profile: {
		setStatusLimit: function( textAreaElement ){
			joms.jQuery( textAreaElement ).keyup(function(){
				var max = parseInt( joms.jQuery(this).attr('maxlength'));
				if( joms.jQuery(this).val().length > max)
				{
					joms.jQuery(this).val( joms.jQuery(this).val().substr(0, joms.jQuery(this).attr('maxlength')));
				}
				joms.jQuery('#profile-status-notice span').html( (max - joms.jQuery(this).val().length) );
			});
		}
	},
	tooltip: {
		setup: function( ){
			// Hide all active visible qTip
			joms.jQuery('.qtip-active').hide();
			setTimeout('joms.jQuery(\'.qtip-active\').hide()', 150);
			try{ clearTimeout(joms.jQuery.fn.qtip.timers.show); } catch(e){}

			// Scan the document and setup the tooltip that has .jomTips
			joms.jQuery(".jomTips").each(function(){
		    	var tipStyle = 'tipNormal';
		    	var tipWidth = 220;
		    	var tipPos	 = {corner: {target: 'topMiddle',tooltip: 'bottomMiddle'}}
		    	var tipShow  = true;
		    	var tipHide	 = { when: { event: 'mouseout' }, effect: { length: 10 } }

		    	if(joms.jQuery(this).hasClass('tipRight'))
				{
		    		tipStyle = 'tipRight';
		    		tipWidth = 320;
		    		tipPos	 = {corner: {target: 'rightMiddle',tooltip: 'leftMiddle'}}
		    	}

		    	if(joms.jQuery(this).hasClass('tipWide'))
				{
		    		tipWidth = 420;
		    	}

		    	if(joms.jQuery(this).hasClass('tipFullWidth'))
				{
		    		tipWidth = joms.jQuery(this).innerWidth()-20;
		    	}

		    	// Split the title and the content
		    	var title = '';
		    	var content = joms.jQuery(this).attr('title');
				var contentArray = content.split('::');

				// Remove the 'title' attributes from the existing .jomTips classes
				joms.jQuery( this ).attr('title' , '' );

				if(contentArray.length == 2)
				{
					content = contentArray[1];
					title = { text: contentArray[0] } ;
				} else
					title = title = { text: '' } ; ;


		    	joms.jQuery(this).qtip({
		    		content: {
					   text: content,
					   title: title
					},
					style: {name:tipStyle , width: tipWidth },
					position: tipPos,
					hide: tipHide,
					show: { solo: true, effect: { length: 50 } }
			 	}).removeClass('jomTips');
			});

			return true;
		},

		setStyle: function() {
			joms.jQuery.fn.qtip.styles.tipNormal = { // Last part is the name of the style
				width: 320,
				border: {
					width: 7,
					radius: 5
				},
				tip: true,
				name: 'dark' // Inherit the rest of the attributes from the preset dark style
			}

			joms.jQuery.fn.qtip.styles.tipRight = { // Last part is the name of the style
				tip: 'leftMiddle',
				name: 'tipNormal' // Inherit the rest of the attributes from the preset dark style
			}

			return true;
		}
	}
});


// close layer when click-out
joms.jQuery(document).click( function() {
    joms.toolbar.close();
});

function get_cookies_array() {

    var cookies = { };

    if (document.cookie && document.cookie != '')
	{
		var split = document.cookie.split(';');
		for (var i = 0; i < split.length; i++)
		{
			var name_value = split[i].split("=");
			name_value[0] = name_value[0].replace(/^ /, '');
			cookies[decodeURIComponent(name_value[0])] = decodeURIComponent(name_value[1]);
		}
    }

    return cookies;

}


// Document ready
joms.jQuery(document).ready(function () {

    joms.jQuery
	joms.tooltip.setStyle();
	joms.tooltip.setup();
	joms.apps.initToggle();
	joms.plugins.initialize();
});

/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
joms.jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = joms.jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

(function(jQuery) {
    joms.jQuery.fn.autogrow = function(options) {

        this.filter('textarea').each(function() {

			var textarea = joms.jQuery(this);

			// Hide scrollbar first.
			textarea.css('overflow','hidden');

			options.minHeight = options.minHeight || textarea.height();
			options.maxHeight = options.maxHeight || 0;

            textarea.siblings('.textarea-shadow').remove();
            var shadow = joms.jQuery('<div class="textarea-shadow">').css({
                             'position'    : 'absolute',
                             'visibility'  : 'hidden',
                             'font-size'   : (textarea[0].currentStyle==undefined) ? textarea.css('font-size') : textarea[0].currentStyle.fontSize,
                             'font-family' : textarea.css('font-family'),
                             'line-height' : textarea.css('line-height'),
                             'width'       : textarea.width()
                         }).insertBefore(textarea);

			var timer;
            var update = function() {
                var times = function(string, number) {
                    var _res = '';
                    for(var i=0; i<number; i++) {
                        _res = _res + string;
                    }
                    return _res;
                };

                var val = textarea[0].value.replace(/</g, '&lt;')
                                     .replace(/>/g, '&gt;')
                                     .replace(/&/g, '&amp;')
                                     .replace(/\njQuery/, '<br/>&nbsp;')
                                     .replace(/\n/g, '<br/>')
                                     .replace(/ {2,}/g, function(space) { return times('&nbsp;', space.length -1) + ' ' });
                shadow.html(val);

                // Resize textarea if maxHeight is not reached
                var newHeight = Math.max(shadow.height() + 20, options.minHeight);
				if(newHeight < options.maxHeight || options.maxHeight==0)
				{
					textarea.css({'height'   : newHeight,
					              'overflow' : 'hidden'
					             });
				// Enable scrollbar if maxHeight is reached.
				} else {
                	textarea.css('overflow','auto');
				}

                clearTimeout(timer);
            }

            var checkExpand = function() {
            	timer = setTimeout(update, 100);
            }

            textarea.change(checkExpand).keydown(checkExpand).keyup(checkExpand);

            update.apply(this);
        });

        return this;

    }

})(joms.jQuery);

/*!
 * jquery.qtip. The jQuery tooltip plugin
 *
 * Copyright (c) 2009 Craig Thompson
 * http://craigsworks.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Launch  : February 2009
 * Version : 1.0.0-rc3
 * Released: Tuesday 12th May, 2009 - 00:00
 * Debug: jquery.qtip.debug.js
 */

(function(jQuery)
{
jQuery.fn.qtip=function(options,blanket)
{
var i,id,interfaces,opts,obj,command,config,api;
if(typeof options=='string')
{
if(typeof joms.jQuery(this).data('qtip')!=='object')
jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.NO_TOOLTIP_PRESENT,false);
if(options=='api')
return joms.jQuery(this).data('qtip').interfaces[jQuery(this).data('qtip').current];
else if(options=='interfaces')
return joms.jQuery(this).data('qtip').interfaces;
}
else
{
if(!options)options={};
if(typeof options.content!=='object'||(options.content.jquery&&options.content.length>0))options.content={text:options.content};
if(typeof options.content.title!=='object')options.content.title={text:options.content.title};
if(typeof options.position!=='object')options.position={corner:options.position};
if(typeof options.position.corner!=='object')options.position.corner={target:options.position.corner,tooltip:options.position.corner};
if(typeof options.show!=='object')options.show={when:options.show};
if(typeof options.show.when!=='object')options.show.when={event:options.show.when};
if(typeof options.show.effect!=='object')options.show.effect={type:options.show.effect};
if(typeof options.hide!=='object')options.hide={when:options.hide};
if(typeof options.hide.when!=='object')options.hide.when={event:options.hide.when};
if(typeof options.hide.effect!=='object')options.hide.effect={type:options.hide.effect};
if(typeof options.style!=='object')options.style={name:options.style};
options.style=sanitizeStyle(options.style);
opts=jQuery.extend(true,{},jQuery.fn.qtip.defaults,options);
opts.style=buildStyle.call({options:opts},opts.style);
opts.user=jQuery.extend(true,{},options);
};
return joms.jQuery(this).each(function()
{
if(typeof options=='string')
{
command=options.toLowerCase();
interfaces=jQuery(this).qtip('interfaces');
if(typeof interfaces=='object')
{
if(blanket===true&&command=='destroy')
while(interfaces.length>0)interfaces[interfaces.length-1].destroy();
else
{
if(blanket!==true)interfaces=[jQuery(this).qtip('api')];
for(i=0;i<interfaces.length;i++)
{
if(command=='destroy')interfaces[i].destroy();
else if(interfaces[i].status.rendered===true)
{
if(command=='show')interfaces[i].show();
else if(command=='hide')interfaces[i].hide();
else if(command=='focus')interfaces[i].focus();
else if(command=='disable')interfaces[i].disable(true);
else if(command=='enable')interfaces[i].disable(false);
};
};
};
};
}
else
{
config=jQuery.extend(true,{},opts);
config.hide.effect.length=opts.hide.effect.length;
config.show.effect.length=opts.show.effect.length;
if(config.position.container===false)config.position.container=jQuery(document.body);
if(config.position.target===false)config.position.target=jQuery(this);
if(config.show.when.target===false)config.show.when.target=jQuery(this);
if(config.hide.when.target===false)config.hide.when.target=jQuery(this);
id=jQuery.fn.qtip.interfaces.length;
for(i=0;i<id;i++)
{
if(typeof joms.jQuery.fn.qtip.interfaces[i]=='undefined'){id=i;break;};
};
obj=new qTip(jQuery(this),config,id);
jQuery.fn.qtip.interfaces[id]=obj;
if(jQuery(this).data('qtip') !== null && typeof jQuery(this).data('qtip') == 'object')
{
if(typeof joms.jQuery(this).attr('qtip')==='undefined')
jQuery(this).data('qtip').current=jQuery(this).data('qtip').interfaces.length;
jQuery(this).data('qtip').interfaces.push(obj);
}
else joms.jQuery(this).data('qtip',{current:0,interfaces:[obj]});
if(config.content.prerender===false&&config.show.when.event!==false&&config.show.ready!==true)
{
config.show.when.target.bind(config.show.when.event+'.qtip-'+id+'-create',{qtip:id},function(event)
{
api=jQuery.fn.qtip.interfaces[event.data.qtip];
api.options.show.when.target.unbind(api.options.show.when.event+'.qtip-'+event.data.qtip+'-create');
api.cache.mouse={x:event.pageX,y:event.pageY};
construct.call(api);
api.options.show.when.target.trigger(api.options.show.when.event);
});
}
else
{
obj.cache.mouse={
x:config.show.when.target.offset().left,
y:config.show.when.target.offset().top
};
construct.call(obj);
}
};
});
};
function qTip(target,options,id)
{
var self=this;
self.id=id;
self.options=options;
self.status={
animated:false,
rendered:false,
disabled:false,
focused:false
};
self.elements={
target:target.addClass(self.options.style.classes.target),
tooltip:null,
wrapper:null,
content:null,
contentWrapper:null,
title:null,
button:null,
tip:null,
bgiframe:null
};
self.cache={
mouse:{},
position:{},
toggle:0
};
self.timers={};
jQuery.extend(self,self.options.api,
{
show:function(event)
{
var returned,solo;
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'show');
if(self.elements.tooltip.css('display')!=='none')return self;
self.elements.tooltip.stop(true,false);
returned=self.beforeShow.call(self,event);
if(returned===false)return self;
function afterShow()
{
if(self.options.position.type!=='static')self.focus();
self.onShow.call(self,event);
if(jQuery.browser.msie)self.elements.tooltip.get(0).style.removeAttribute('filter');
};
self.cache.toggle=1;
if(self.options.position.type!=='static')
self.updatePosition(event,(self.options.show.effect.length>0));
if(typeof self.options.show.solo=='object')solo=jQuery(self.options.show.solo);
else if(self.options.show.solo===true)solo=jQuery('div.qtip').not(self.elements.tooltip);
if(solo)solo.each(function(){if(jQuery(this).qtip('api').status.rendered===true)jQuery(this).qtip('api').hide();});
if(typeof self.options.show.effect.type=='function')
{
self.options.show.effect.type.call(self.elements.tooltip,self.options.show.effect.length);
self.elements.tooltip.queue(function(){afterShow();jQuery(this).dequeue();});
}
else
{
switch(self.options.show.effect.type.toLowerCase())
{
case'fade':
self.elements.tooltip.fadeIn(self.options.show.effect.length,afterShow);
break;
case'slide':
self.elements.tooltip.slideDown(self.options.show.effect.length,function()
{
afterShow();
if(self.options.position.type!=='static')self.updatePosition(event,true);
});
break;
case'grow':
self.elements.tooltip.show(self.options.show.effect.length,afterShow);
break;
default:
self.elements.tooltip.show(null,afterShow);
break;
};
self.elements.tooltip.addClass(self.options.style.classes.active);
};
return joms.jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_SHOWN,'show');
},
hide:function(event)
{
var returned;
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'hide');
else if(self.elements.tooltip.css('display')==='none')return self;
clearTimeout(self.timers.show);
self.elements.tooltip.stop(true,false);
returned=self.beforeHide.call(self,event);
if(returned===false)return self;
function afterHide(){self.onHide.call(self,event);};
self.cache.toggle=0;
if(typeof self.options.hide.effect.type=='function')
{
self.options.hide.effect.type.call(self.elements.tooltip,self.options.hide.effect.length);
self.elements.tooltip.queue(function(){afterHide();jQuery(this).dequeue();});
}
else
{
switch(self.options.hide.effect.type.toLowerCase())
{
case'fade':
self.elements.tooltip.fadeOut(self.options.hide.effect.length,afterHide);
break;
case'slide':
self.elements.tooltip.slideUp(self.options.hide.effect.length,afterHide);
break;
case'grow':
self.elements.tooltip.hide(self.options.hide.effect.length,afterHide);
break;
default:
self.elements.tooltip.hide(null,afterHide);
break;
};
self.elements.tooltip.removeClass(self.options.style.classes.active);
};
return joms.jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_HIDDEN,'hide');
},
updatePosition:function(event,animate)
{
var i,target,tooltip,coords,mapName,imagePos,newPosition,ieAdjust,ie6Adjust,borderAdjust,mouseAdjust,offset,curPosition,returned
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'updatePosition');
else if(self.options.position.type=='static')
return joms.jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.CANNOT_POSITION_STATIC,'updatePosition');
target={
position:{left:0,top:0},
dimensions:{height:0,width:0},
corner:self.options.position.corner.target
};
tooltip={
position:self.getPosition(),
dimensions:self.getDimensions(),
corner:self.options.position.corner.tooltip
};
if(self.options.position.target!=='mouse')
{
if(self.options.position.target.get(0).nodeName.toLowerCase()=='area')
{
coords=self.options.position.target.attr('coords').split(',');
for(i=0;i<coords.length;i++)coords[i]=parseInt(coords[i]);
mapName=self.options.position.target.parent('map').attr('name');
imagePos=jQuery('img[usemap="#'+mapName+'"]:first').offset();
target.position={
left:Math.floor(imagePos.left+coords[0]),
top:Math.floor(imagePos.top+coords[1])
};
switch(self.options.position.target.attr('shape').toLowerCase())
{
case'rect':
target.dimensions={
width:Math.ceil(Math.abs(coords[2]-coords[0])),
height:Math.ceil(Math.abs(coords[3]-coords[1]))
};
break;
case'circle':
target.dimensions={
width:coords[2]+1,
height:coords[2]+1
};
break;
case'poly':
target.dimensions={
width:coords[0],
height:coords[1]
};
for(i=0;i<coords.length;i++)
{
if(i%2==0)
{
if(coords[i]>target.dimensions.width)
target.dimensions.width=coords[i];
if(coords[i]<coords[0])
target.position.left=Math.floor(imagePos.left+coords[i]);
}
else
{
if(coords[i]>target.dimensions.height)
target.dimensions.height=coords[i];
if(coords[i]<coords[1])
target.position.top=Math.floor(imagePos.top+coords[i]);
};
};
target.dimensions.width=target.dimensions.width-(target.position.left-imagePos.left);
target.dimensions.height=target.dimensions.height-(target.position.top-imagePos.top);
break;
default:
return joms.jQuery.fn.qtip.log.error.call(self,4,jQuery.fn.qtip.constants.INVALID_AREA_SHAPE,'updatePosition');
break;
};
target.dimensions.width-=2;target.dimensions.height-=2;
}
else if(self.options.position.target.add(document.body).length===1)
{
target.position={left:jQuery(document).scrollLeft(),top:jQuery(document).scrollTop()};
target.dimensions={height:jQuery(window).height(),width:jQuery(window).width()};
}
else
{
if(typeof self.options.position.target.attr('qtip')!=='undefined')
target.position=self.options.position.target.qtip('api').cache.position;
else
target.position=self.options.position.target.offset();
target.dimensions={
height:self.options.position.target.outerHeight(),
width:self.options.position.target.outerWidth()
};
};
newPosition=jQuery.extend({},target.position);
if(target.corner.search(/right/i)!==-1)
newPosition.left+=target.dimensions.width;
if(target.corner.search(/bottom/i)!==-1)
newPosition.top+=target.dimensions.height;
if(target.corner.search(/((top|bottom)Middle)|center/)!==-1)
newPosition.left+=(target.dimensions.width/2);
if(target.corner.search(/((left|right)Middle)|center/)!==-1)
newPosition.top+=(target.dimensions.height/2);
}
else
{
target.position=newPosition={left:self.cache.mouse.x,top:self.cache.mouse.y};
target.dimensions={height:1,width:1};
};
if(tooltip.corner.search(/right/i)!==-1)
newPosition.left-=tooltip.dimensions.width;
if(tooltip.corner.search(/bottom/i)!==-1)
newPosition.top-=tooltip.dimensions.height;
if(tooltip.corner.search(/((top|bottom)Middle)|center/)!==-1)
newPosition.left-=(tooltip.dimensions.width/2);
if(tooltip.corner.search(/((left|right)Middle)|center/)!==-1)
newPosition.top-=(tooltip.dimensions.height/2);
ieAdjust=(jQuery.browser.msie)?1:0;
ie6Adjust=(jQuery.browser.msie&&parseInt(jQuery.browser.version.charAt(0))===6)?1:0;
if(self.options.style.border.radius>0)
{
if(tooltip.corner.search(/Left/)!==-1)
newPosition.left-=self.options.style.border.radius;
else if(tooltip.corner.search(/Right/)!==-1)
newPosition.left+=self.options.style.border.radius;
if(tooltip.corner.search(/Top/)!==-1)
newPosition.top-=self.options.style.border.radius;
else if(tooltip.corner.search(/Bottom/)!==-1)
newPosition.top+=self.options.style.border.radius;
};
if(ieAdjust)
{
if(tooltip.corner.search(/top/)!==-1)
newPosition.top-=ieAdjust
else if(tooltip.corner.search(/bottom/)!==-1)
newPosition.top+=ieAdjust
if(tooltip.corner.search(/left/)!==-1)
newPosition.left-=ieAdjust
else if(tooltip.corner.search(/right/)!==-1)
newPosition.left+=ieAdjust
if(tooltip.corner.search(/leftMiddle|rightMiddle/)!==-1)
newPosition.top-=1
};
if(self.options.position.adjust.screen===true)
newPosition=screenAdjust.call(self,newPosition,target,tooltip);
if(self.options.position.target==='mouse'&&self.options.position.adjust.mouse===true)
{
if(self.options.position.adjust.screen===true&&self.elements.tip)
mouseAdjust=self.elements.tip.attr('rel');
else
mouseAdjust=self.options.position.corner.tooltip;
newPosition.left+=(mouseAdjust.search(/right/i)!==-1)?-6:6;
newPosition.top+=(mouseAdjust.search(/bottom/i)!==-1)?-6:6;
}
if(!self.elements.bgiframe&&jQuery.browser.msie&&parseInt(jQuery.browser.version.charAt(0))==6)
{
jQuery('select, object').each(function()
{
offset=jQuery(this).offset();
offset.bottom=offset.top+jQuery(this).height();
offset.right=offset.left+jQuery(this).width();
if(newPosition.top+tooltip.dimensions.height>=offset.top
&&newPosition.left+tooltip.dimensions.width>=offset.left)
bgiframe.call(self);
});
};
newPosition.left+=self.options.position.adjust.x;
newPosition.top+=self.options.position.adjust.y;
curPosition=self.getPosition();
if(newPosition.left!=curPosition.left||newPosition.top!=curPosition.top)
{
returned=self.beforePositionUpdate.call(self,event);
if(returned===false)return self;
self.cache.position=newPosition;
if(animate===true)
{
self.status.animated=true;
self.elements.tooltip.animate(newPosition,200,'swing',function(){self.status.animated=false});
}
else self.elements.tooltip.css(newPosition);
self.onPositionUpdate.call(self,event);
if(typeof event!=='undefined'&&event.type&&event.type!=='mousemove')
jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_POSITION_UPDATED,'updatePosition');
};
return self;
},
updateWidth:function(newWidth)
{
var hidden;
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'updateWidth');
else if(newWidth&&typeof newWidth!=='number')
return joms.jQuery.fn.qtip.log.error.call(self,2,'newWidth must be of type number','updateWidth');
hidden=self.elements.contentWrapper.siblings().add(self.elements.tip).add(self.elements.button);
if(!newWidth)
{
if(typeof self.options.style.width.value=='number')
newWidth=self.options.style.width.value;
else
{
self.elements.tooltip.css({width:'auto'});
hidden.hide();
if(jQuery.browser.msie)
self.elements.wrapper.add(self.elements.contentWrapper.children()).css({zoom:'normal'});
newWidth=self.getDimensions().width+1;
if(!self.options.style.width.value)
{
if(newWidth>self.options.style.width.max)newWidth=self.options.style.width.max
if(newWidth<self.options.style.width.min)newWidth=self.options.style.width.min
};
};
};
if(newWidth%2!==0)newWidth-=1;
self.elements.tooltip.width(newWidth);
hidden.show();
if(self.options.style.border.radius)
{
self.elements.tooltip.find('.qtip-betweenCorners').each(function(i)
{
jQuery(this).width(newWidth-(self.options.style.border.radius*2));
})
};
if(jQuery.browser.msie)
{
self.elements.wrapper.add(self.elements.contentWrapper.children()).css({zoom:'1'});
self.elements.wrapper.width(newWidth);
if(self.elements.bgiframe)self.elements.bgiframe.width(newWidth).height(self.getDimensions.height);
};
return joms.jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_WIDTH_UPDATED,'updateWidth');
},
updateStyle:function(name)
{
var tip,borders,context,corner,coordinates;
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'updateStyle');
else if(typeof name!=='string'||!jQuery.fn.qtip.styles[name])
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.STYLE_NOT_DEFINED,'updateStyle');
self.options.style=buildStyle.call(self,jQuery.fn.qtip.styles[name],self.options.user.style);
self.elements.content.css(jQueryStyle(self.options.style));
if(self.options.content.title.text!==false)
self.elements.title.css(jQueryStyle(self.options.style.title,true));
self.elements.contentWrapper.css({borderColor:self.options.style.border.color});
if(self.options.style.tip.corner!==false)
{
if(jQuery('<canvas>').get(0).getContext)
{
tip=self.elements.tooltip.find('.qtip-tip canvas:first');
context=tip.get(0).getContext('2d');
context.clearRect(0,0,300,300);
corner=tip.parent('div[rel]:first').attr('rel');
coordinates=calculateTip(corner,self.options.style.tip.size.width,self.options.style.tip.size.height);
drawTip.call(self,tip,coordinates,self.options.style.tip.color||self.options.style.border.color);
}
else if(jQuery.browser.msie)
{
tip=self.elements.tooltip.find('.qtip-tip [nodeName="shape"]');
tip.attr('fillcolor',self.options.style.tip.color||self.options.style.border.color);
};
};
if(self.options.style.border.radius>0)
{
self.elements.tooltip.find('.qtip-betweenCorners').css({backgroundColor:self.options.style.border.color});
if(jQuery('<canvas>').get(0).getContext)
{
borders=calculateBorders(self.options.style.border.radius)
self.elements.tooltip.find('.qtip-wrapper canvas').each(function()
{
context=jQuery(this).get(0).getContext('2d');
context.clearRect(0,0,300,300);
corner=jQuery(this).parent('div[rel]:first').attr('rel')
drawBorder.call(self,jQuery(this),borders[corner],
self.options.style.border.radius,self.options.style.border.color);
});
}
else if(jQuery.browser.msie)
{
self.elements.tooltip.find('.qtip-wrapper [nodeName="arc"]').each(function()
{
jQuery(this).attr('fillcolor',self.options.style.border.color)
});
};
};
return joms.jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_STYLE_UPDATED,'updateStyle');
},
updateContent:function(content,reposition)
{
var parsedContent,images,loadedImages;
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'updateContent');
else if(!content)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.NO_CONTENT_PROVIDED,'updateContent');
parsedContent=self.beforeContentUpdate.call(self,content);
if(typeof parsedContent=='string')content=parsedContent;
else if(parsedContent===false)return;
if(jQuery.browser.msie)self.elements.contentWrapper.children().css({zoom:'normal'});
if(content.jquery&&content.length>0)
content.clone(true).appendTo(self.elements.content).show();
else self.elements.content.html(content);
images=self.elements.content.find('img[complete=false]');
if(images.length>0)
{
loadedImages=0;
images.each(function(i)
{
jQuery('<img src="'+jQuery(this).attr('src')+'" />')
.load(function(){if(++loadedImages==images.length)afterLoad();});
});
}
else afterLoad();
function afterLoad()
{
self.updateWidth();
if(reposition!==false)
{
if(self.options.position.type!=='static')
self.updatePosition(self.elements.tooltip.is(':visible'),true);
if(self.options.style.tip.corner!==false)
positionTip.call(self);
};
};
self.onContentUpdate.call(self);
return joms.jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_CONTENT_UPDATED,'loadContent');
},
loadContent:function(url,data,method)
{
var returned;
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'loadContent');
returned=self.beforeContentLoad.call(self);
if(returned===false)return self;
if(method=='post')
jQuery.post(url,data,setupContent);
else
jQuery.get(url,data,setupContent);
function setupContent(content)
{
self.onContentLoad.call(self);
jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_CONTENT_LOADED,'loadContent');
self.updateContent(content);
};
return self;
},
updateTitle:function(content)
{
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'updateTitle');
else if(!content)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.NO_CONTENT_PROVIDED,'updateTitle');
returned=self.beforeTitleUpdate.call(self);
if(returned===false)return self;
if(self.elements.button)self.elements.button=self.elements.button.clone(true);
self.elements.title.html(content)
if(self.elements.button)self.elements.title.prepend(self.elements.button);
self.onTitleUpdate.call(self);
return joms.jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_TITLE_UPDATED,'updateTitle');
},
focus:function(event)
{
var curIndex,newIndex,elemIndex,returned;
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'focus');
else if(self.options.position.type=='static')
return joms.jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.CANNOT_FOCUS_STATIC,'focus');
curIndex=parseInt(self.elements.tooltip.css('z-index'));
newIndex=7000+jQuery('div.qtip[qtip]').length-1;
if(!self.status.focused&&curIndex!==newIndex)
{
returned=self.beforeFocus.call(self,event);
if(returned===false)return self;
jQuery('div.qtip[qtip]').not(self.elements.tooltip).each(function()
{
if(jQuery(this).qtip('api').status.rendered===true)
{
elemIndex=parseInt(jQuery(this).css('z-index'));
if(typeof elemIndex=='number'&&elemIndex>-1)
jQuery(this).css({zIndex:parseInt(jQuery(this).css('z-index'))-1});
jQuery(this).qtip('api').status.focused=false;
}
})
self.elements.tooltip.css({zIndex:newIndex});
self.status.focused=true;
self.onFocus.call(self,event);
jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_FOCUSED,'focus');
};
return self;
},
disable:function(state)
{
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'disable');
if(state)
{
if(!self.status.disabled)
{
self.status.disabled=true;
jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_DISABLED,'disable');
}
else joms.jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.TOOLTIP_ALREADY_DISABLED,'disable');
}
else
{
if(self.status.disabled)
{
self.status.disabled=false;
jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_ENABLED,'disable');
}
else joms.jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.TOOLTIP_ALREADY_ENABLED,'disable');
};
return self;
},
destroy:function()
{
var i,returned,interfaces;
returned=self.beforeDestroy.call(self);
if(returned===false)return self;
if(self.status.rendered)
{
self.options.show.when.target.unbind('mousemove.qtip',self.updatePosition);
self.options.show.when.target.unbind('mouseout.qtip',self.hide);
self.options.show.when.target.unbind(self.options.show.when.event+'.qtip');
self.options.hide.when.target.unbind(self.options.hide.when.event+'.qtip');
self.elements.tooltip.unbind(self.options.hide.when.event+'.qtip');
self.elements.tooltip.unbind('mouseover.qtip',self.focus);
self.elements.tooltip.remove();
}
else self.options.show.when.target.unbind(self.options.show.when.event+'.qtip-create');
if(typeof self.elements.target.data('qtip')=='object')
{
interfaces=self.elements.target.data('qtip').interfaces;
if(typeof interfaces=='object'&&interfaces.length>0)
{
for(i=0;i<interfaces.length-1;i++)
if(interfaces[i].id==self.id)interfaces.splice(i,1)
}
}
delete joms.jQuery.fn.qtip.interfaces[self.id];
if(typeof interfaces=='object'&&interfaces.length>0)
self.elements.target.data('qtip').current=interfaces.length-1;
else
self.elements.target.removeData('qtip');
self.onDestroy.call(self);
jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_DESTROYED,'destroy');
return self.elements.target
},
getPosition:function()
{
var show,offset;
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'getPosition');
show=(self.elements.tooltip.css('display')!=='none')?false:true;
if(show)self.elements.tooltip.css({visiblity:'hidden'}).show();
offset=self.elements.tooltip.offset();
if(show)self.elements.tooltip.css({visiblity:'visible'}).hide();
return offset;
},
getDimensions:function()
{
var show,dimensions;
if(!self.status.rendered)
return joms.jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.TOOLTIP_NOT_RENDERED,'getDimensions');
show=(!self.elements.tooltip.is(':visible'))?true:false;
if(show)self.elements.tooltip.css({visiblity:'hidden'}).show();
dimensions={
height:self.elements.tooltip.outerHeight(),
width:self.elements.tooltip.outerWidth()
};
if(show)self.elements.tooltip.css({visiblity:'visible'}).hide();
return dimensions;
}
});
};
function construct()
{
var self,adjust,content,url,data,method,tempLength;
self=this;
self.beforeRender.call(self);
self.status.rendered=true;
self.elements.tooltip='<div qtip="'+self.id+'" '+
'class="qtip '+(self.options.style.classes.tooltip||self.options.style)+'"'+
'style="display:none; -moz-border-radius:0; -webkit-border-radius:0; border-radius:0;'+
'position:'+self.options.position.type+';">'+
'  <div class="qtip-wrapper" style="position:relative; overflow:hidden; text-align:left;">'+
'    <div class="qtip-contentWrapper" style="overflow:hidden;">'+
'       <div class="qtip-content '+self.options.style.classes.content+'"></div>'+
'</div></div></div>';
self.elements.tooltip=jQuery(self.elements.tooltip);
self.elements.tooltip.appendTo(self.options.position.container)
self.elements.tooltip.data('qtip',{current:0,interfaces:[self]});
self.elements.wrapper=self.elements.tooltip.children('div:first');
self.elements.contentWrapper=self.elements.wrapper.children('div:first').css({background:self.options.style.background});
self.elements.content=self.elements.contentWrapper.children('div:first').css(jQueryStyle(self.options.style));
if(jQuery.browser.msie)self.elements.wrapper.add(self.elements.content).css({zoom:1});
if(self.options.hide.when.event=='unfocus')self.elements.tooltip.attr('unfocus',true);
if(typeof self.options.style.width.value=='number')self.updateWidth();
if(jQuery('<canvas>').get(0).getContext||jQuery.browser.msie)
{
if(self.options.style.border.radius>0)
createBorder.call(self);
else
self.elements.contentWrapper.css({border:self.options.style.border.width+'px solid '+self.options.style.border.color});
if(self.options.style.tip.corner!==false)
createTip.call(self);
}
else
{
self.elements.contentWrapper.css({border:self.options.style.border.width+'px solid '+self.options.style.border.color});
self.options.style.border.radius=0;
self.options.style.tip.corner=false;
jQuery.fn.qtip.log.error.call(self,2,jQuery.fn.qtip.constants.CANVAS_VML_NOT_SUPPORTED,'render');
};
if((typeof self.options.content.text=='string'&&self.options.content.text.length>0)
||(self.options.content.text.jquery&&self.options.content.text.length>0))
content=self.options.content.text;
else if(typeof self.elements.target.attr('title')=='string'&&self.elements.target.attr('title').length>0)
{
content=self.elements.target.attr('title').replace("\\n",'<br />');
self.elements.target.attr('title','');
}
else if(typeof self.elements.target.attr('alt')=='string'&&self.elements.target.attr('alt').length>0)
{
content=self.elements.target.attr('alt').replace("\\n",'<br />');
self.elements.target.attr('alt','');
}
else
{
content=' ';
jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.NO_VALID_CONTENT,'render');
};
if(self.options.content.title.text!==false)createTitle.call(self);
self.updateContent(content);
assignEvents.call(self);
if(self.options.show.ready===true)self.show();
if(self.options.content.url!==false)
{
url=self.options.content.url;
data=self.options.content.data;
method=self.options.content.method||'get';
self.loadContent(url,data,method);
};
self.onRender.call(self);
jQuery.fn.qtip.log.error.call(self,1,jQuery.fn.qtip.constants.EVENT_RENDERED,'render');
};
function createBorder()
{
var self,i,width,radius,color,coordinates,containers,size,betweenWidth,betweenCorners,borderTop,borderBottom,borderCoord,sideWidth,vertWidth;
self=this;
self.elements.wrapper.find('.qtip-borderBottom, .qtip-borderTop').remove();
width=self.options.style.border.width;
radius=self.options.style.border.radius;
color=self.options.style.border.color||self.options.style.tip.color;
coordinates=calculateBorders(radius);
containers={};
for(i in coordinates)
{
containers[i]='<div rel="'+i+'" style="'+((i.search(/Left/)!==-1)?'left':'right')+':0; '+
'position:absolute; height:'+radius+'px; width:'+radius+'px; overflow:hidden; line-height:0.1px; font-size:1px">';
if(jQuery('<canvas>').get(0).getContext)
containers[i]+='<canvas height="'+radius+'" width="'+radius+'" style="vertical-align: top"></canvas>';
else if(jQuery.browser.msie)
{
size=radius*2+3;
containers[i]+='<v:arc stroked="false" fillcolor="'+color+'" startangle="'+coordinates[i][0]+'" endangle="'+coordinates[i][1]+'" '+
'style="width:'+size+'px; height:'+size+'px; margin-top:'+((i.search(/bottom/)!==-1)?-2:-1)+'px; '+
'margin-left:'+((i.search(/Right/)!==-1)?coordinates[i][2]-3.5:-1)+'px; '+
'vertical-align:top; display:inline-block; behavior:url(#default#VML)"></v:arc>';
};
containers[i]+='</div>';
};
betweenWidth=self.getDimensions().width-(Math.max(width,radius)*2);
betweenCorners='<div class="qtip-betweenCorners" style="height:'+radius+'px; width:'+betweenWidth+'px; '+
'overflow:hidden; background-color:'+color+'; line-height:0.1px; font-size:1px;">';
borderTop='<div class="qtip-borderTop" dir="ltr" style="height:'+radius+'px; '+
'margin-left:'+radius+'px; line-height:0.1px; font-size:1px; padding:0;">'+
containers['topLeft']+containers['topRight']+betweenCorners;
self.elements.wrapper.prepend(borderTop);
borderBottom='<div class="qtip-borderBottom" dir="ltr" style="height:'+radius+'px; '+
'margin-left:'+radius+'px; line-height:0.1px; font-size:1px; padding:0;">'+
containers['bottomLeft']+containers['bottomRight']+betweenCorners;
self.elements.wrapper.append(borderBottom);
if(jQuery('<canvas>').get(0).getContext)
{
self.elements.wrapper.find('canvas').each(function()
{
borderCoord=coordinates[jQuery(this).parent('[rel]:first').attr('rel')];
drawBorder.call(self,jQuery(this),borderCoord,radius,color);
})
}
else if(jQuery.browser.msie)self.elements.tooltip.append('<v:image style="behavior:url(#default#VML);"></v:image>');
sideWidth=Math.max(radius,(radius+(width-radius)))
vertWidth=Math.max(width-radius,0);
self.elements.contentWrapper.css({
border:'0px solid '+color,
borderWidth:vertWidth+'px '+sideWidth+'px'
})
};
function drawBorder(canvas,coordinates,radius,color)
{
var context=canvas.get(0).getContext('2d');
context.fillStyle=color;
context.beginPath();
context.arc(coordinates[0],coordinates[1],radius,0,Math.PI*2,false);
context.fill();
};
function createTip(corner)
{
var self,color,coordinates,coordsize,path;
self=this;
if(self.elements.tip!==null)self.elements.tip.remove();
color=self.options.style.tip.color||self.options.style.border.color;
if(self.options.style.tip.corner===false)return;
else if(!corner)corner=self.options.style.tip.corner;
coordinates=calculateTip(corner,self.options.style.tip.size.width,self.options.style.tip.size.height);
self.elements.tip='<div class="'+self.options.style.classes.tip+'" dir="ltr" rel="'+corner+'" style="position:absolute; '+
'height:'+self.options.style.tip.size.height+'px; width:'+self.options.style.tip.size.width+'px; '+
'margin:0 auto; line-height:0.1px; font-size:1px;">';
if(jQuery('<canvas>').get(0).getContext)
self.elements.tip+='<canvas height="'+self.options.style.tip.size.height+'" width="'+self.options.style.tip.size.width+'"></canvas>';
else if(jQuery.browser.msie)
{
coordsize=self.options.style.tip.size.width+','+self.options.style.tip.size.height;
path='m'+coordinates[0][0]+','+coordinates[0][1];
path+=' l'+coordinates[1][0]+','+coordinates[1][1];
path+=' '+coordinates[2][0]+','+coordinates[2][1];
path+=' xe';
self.elements.tip+='<v:shape fillcolor="'+color+'" stroked="false" filled="true" path="'+path+'" coordsize="'+coordsize+'" '+
'style="width:'+self.options.style.tip.size.width+'px; height:'+self.options.style.tip.size.height+'px; '+
'line-height:0.1px; display:inline-block; behavior:url(#default#VML); '+
'vertical-align:'+((corner.search(/top/)!==-1)?'bottom':'top')+'"></v:shape>';
self.elements.tip+='<v:image style="behavior:url(#default#VML);"></v:image>';
self.elements.contentWrapper.css('position','relative');
};
self.elements.tooltip.prepend(self.elements.tip+'</div>');
self.elements.tip=self.elements.tooltip.find('.'+self.options.style.classes.tip).eq(0);
if(jQuery('<canvas>').get(0).getContext)
drawTip.call(self,self.elements.tip.find('canvas:first'),coordinates,color);
if(corner.search(/top/)!==-1&&jQuery.browser.msie&&parseInt(jQuery.browser.version.charAt(0))===6)
self.elements.tip.css({marginTop:-4});
positionTip.call(self,corner);
};
function drawTip(canvas,coordinates,color)
{
var context=canvas.get(0).getContext('2d');
context.fillStyle=color;
context.beginPath();
context.moveTo(coordinates[0][0],coordinates[0][1]);
context.lineTo(coordinates[1][0],coordinates[1][1]);
context.lineTo(coordinates[2][0],coordinates[2][1]);
context.fill();
};
function positionTip(corner)
{
var self,ieAdjust,paddingCorner,paddingSize,newMargin;
self=this;
if(self.options.style.tip.corner===false||!self.elements.tip)return;
if(!corner)corner=self.elements.tip.attr('rel');
ieAdjust=positionAdjust=(jQuery.browser.msie)?1:0;
self.elements.tip.css(corner.match(/left|right|top|bottom/)[0],0);
if(corner.search(/top|bottom/)!==-1)
{
if(jQuery.browser.msie)
{
if(parseInt(jQuery.browser.version.charAt(0))===6)
positionAdjust=(corner.search(/top/)!==-1)?-3:1;
else
positionAdjust=(corner.search(/top/)!==-1)?1:2;
};
if(corner.search(/Middle/)!==-1)
self.elements.tip.css({left:'50%',marginLeft:-(self.options.style.tip.size.width/2)});
else if(corner.search(/Left/)!==-1)
self.elements.tip.css({left:self.options.style.border.radius-ieAdjust});
else if(corner.search(/Right/)!==-1)
self.elements.tip.css({right:self.options.style.border.radius+ieAdjust});
if(corner.search(/top/)!==-1)
self.elements.tip.css({top:-positionAdjust});
else
self.elements.tip.css({bottom:positionAdjust});
}
else if(corner.search(/left|right/)!==-1)
{
if(jQuery.browser.msie)
positionAdjust=(parseInt(jQuery.browser.version.charAt(0))===6)?1:((corner.search(/left/)!==-1)?1:2);
if(corner.search(/Middle/)!==-1)
self.elements.tip.css({top:'50%',marginTop:-(self.options.style.tip.size.height/2)});
else if(corner.search(/Top/)!==-1)
self.elements.tip.css({top:self.options.style.border.radius-ieAdjust});
else if(corner.search(/Bottom/)!==-1)
self.elements.tip.css({bottom:self.options.style.border.radius+ieAdjust});
if(corner.search(/left/)!==-1)
self.elements.tip.css({left:-positionAdjust});
else
self.elements.tip.css({right:positionAdjust});
};
paddingCorner='padding-'+corner.match(/left|right|top|bottom/)[0];
paddingSize=self.options.style.tip.size[(paddingCorner.search(/left|right/)!==-1)?'width':'height'];
self.elements.tooltip.css('padding',0);
self.elements.tooltip.css(paddingCorner,paddingSize);
if(jQuery.browser.msie&&parseInt(jQuery.browser.version.charAt(0))==6)
{
newMargin=parseInt(self.elements.tip.css('margin-top'))||0;
newMargin+=parseInt(self.elements.content.css('margin-top'))||0;
self.elements.tip.css({marginTop:newMargin});
};
};
function createTitle()
{
var self=this;
if(self.elements.title!==null)self.elements.title.remove();
self.elements.title=jQuery('<div class="'+self.options.style.classes.title+'">')
.css(jQueryStyle(self.options.style.title,true))
.css({zoom:(jQuery.browser.msie)?1:0})
.prependTo(self.elements.contentWrapper);
if(self.options.content.title.text)self.updateTitle.call(self,self.options.content.title.text);
if(self.options.content.title.button!==false
&&typeof self.options.content.title.button=='string')
{
self.elements.button=jQuery('<a class="'+self.options.style.classes.button+'" style="float:right; position: relative"></a>')
.css(jQueryStyle(self.options.style.button,true))
.html(self.options.content.title.button)
.prependTo(self.elements.title)
.click(function(event){if(!self.status.disabled)self.hide(event)});
};
};
function assignEvents()
{
var self,showTarget,hideTarget,inactiveEvents;
self=this;
showTarget=self.options.show.when.target;
hideTarget=self.options.hide.when.target;
if(self.options.hide.fixed)hideTarget=hideTarget.add(self.elements.tooltip);
if(self.options.hide.when.event=='inactive')
{
inactiveEvents=['click','dblclick','mousedown','mouseup','mousemove',
'mouseout','mouseenter','mouseleave','mouseover'];
function inactiveMethod(event)
{
if(self.status.disabled===true)return;
clearTimeout(self.timers.inactive);
self.timers.inactive=setTimeout(function()
{
jQuery(inactiveEvents).each(function()
{
hideTarget.unbind(this+'.qtip-inactive');
self.elements.content.unbind(this+'.qtip-inactive');
});
self.hide(event);
}
,self.options.hide.delay);
};
}
else if(self.options.hide.fixed===true)
{
self.elements.tooltip.bind('mouseover.qtip',function()
{
if(self.status.disabled===true)return;
clearTimeout(self.timers.hide);
});
};
function showMethod(event)
{
if(self.status.disabled===true)return;
if(self.options.hide.when.event=='inactive')
{
jQuery(inactiveEvents).each(function()
{
hideTarget.bind(this+'.qtip-inactive',inactiveMethod);
self.elements.content.bind(this+'.qtip-inactive',inactiveMethod);
});
inactiveMethod();
};
clearTimeout(self.timers.show);
clearTimeout(self.timers.hide);
self.timers.show=setTimeout(function(){self.show(event);},self.options.show.delay);
};
function hideMethod(event)
{
if(self.status.disabled===true)return;
if(self.options.hide.fixed===true
&&self.options.hide.when.event.search(/mouse(out|leave)/i)!==-1
&&jQuery(event.relatedTarget).parents('div.qtip[qtip]').length>0)
{
event.stopPropagation();
event.preventDefault();
clearTimeout(self.timers.hide);
return false;
};
clearTimeout(self.timers.show);
clearTimeout(self.timers.hide);
self.elements.tooltip.stop(true,true);
self.timers.hide=setTimeout(function(){self.hide(event);},self.options.hide.delay);
};
if((self.options.show.when.target.add(self.options.hide.when.target).length===1
&&self.options.show.when.event==self.options.hide.when.event
&&self.options.hide.when.event!=='inactive')
||self.options.hide.when.event=='unfocus')
{
self.cache.toggle=0;
showTarget.bind(self.options.show.when.event+'.qtip',function(event)
{
if(self.cache.toggle==0)showMethod(event);
else hideMethod(event);
});
}
else
{
showTarget.bind(self.options.show.when.event+'.qtip',showMethod);
if(self.options.hide.when.event!=='inactive')
hideTarget.bind(self.options.hide.when.event+'.qtip',hideMethod);
};
if(self.options.position.type.search(/(fixed|absolute)/)!==-1)
self.elements.tooltip.bind('mouseover.qtip',self.focus);
if(self.options.position.target==='mouse'&&self.options.position.type!=='static')
{
showTarget.bind('mousemove.qtip',function(event)
{
self.cache.mouse={x:event.pageX,y:event.pageY};
if(self.status.disabled===false
&&self.options.position.adjust.mouse===true
&&self.options.position.type!=='static'
&&self.elements.tooltip.css('display')!=='none')
self.updatePosition(event);
});
};
};
function screenAdjust(position,target,tooltip)
{
var self,adjustedPosition,adjust,newCorner,overflow,corner;
self=this;
if(tooltip.corner=='center')return target.position
adjustedPosition=jQuery.extend({},position);
newCorner={x:false,y:false};
overflow={
left:(adjustedPosition.left<jQuery.fn.qtip.cache.screen.scroll.left),
right:(adjustedPosition.left+tooltip.dimensions.width+2>=jQuery.fn.qtip.cache.screen.width+jQuery.fn.qtip.cache.screen.scroll.left),
top:(adjustedPosition.top<jQuery.fn.qtip.cache.screen.scroll.top),
bottom:(adjustedPosition.top+tooltip.dimensions.height+2>=jQuery.fn.qtip.cache.screen.height+jQuery.fn.qtip.cache.screen.scroll.top)
};
adjust={
left:(overflow.left&&(tooltip.corner.search(/right/i)!=-1||(tooltip.corner.search(/right/i)==-1&&!overflow.right))),
right:(overflow.right&&(tooltip.corner.search(/left/i)!=-1||(tooltip.corner.search(/left/i)==-1&&!overflow.left))),
top:(overflow.top&&tooltip.corner.search(/top/i)==-1),
bottom:(overflow.bottom&&tooltip.corner.search(/bottom/i)==-1)
};
if(adjust.left)
{
if(self.options.position.target!=='mouse')
adjustedPosition.left=target.position.left+target.dimensions.width;
else
adjustedPosition.left=self.cache.mouse.x
newCorner.x='Left';
}
else if(adjust.right)
{
if(self.options.position.target!=='mouse')
adjustedPosition.left=target.position.left-tooltip.dimensions.width;
else
adjustedPosition.left=self.cache.mouse.x-tooltip.dimensions.width;
newCorner.x='Right';
};
if(adjust.top)
{
if(self.options.position.target!=='mouse')
adjustedPosition.top=target.position.top+target.dimensions.height;
else
adjustedPosition.top=self.cache.mouse.y
newCorner.y='top';
}
else if(adjust.bottom)
{
if(self.options.position.target!=='mouse')
adjustedPosition.top=target.position.top-tooltip.dimensions.height;
else
adjustedPosition.top=self.cache.mouse.y-tooltip.dimensions.height;
newCorner.y='bottom';
};
if(adjustedPosition.left<0)
{
adjustedPosition.left=position.left;
newCorner.x=false;
};
if(adjustedPosition.top<0)
{
adjustedPosition.top=position.top;
newCorner.y=false;
};
if(self.options.style.tip.corner!==false)
{
adjustedPosition.corner=new String(tooltip.corner);
if(newCorner.x!==false)adjustedPosition.corner=adjustedPosition.corner.replace(/Left|Right|Middle/,newCorner.x);
if(newCorner.y!==false)adjustedPosition.corner=adjustedPosition.corner.replace(/top|bottom/,newCorner.y);
if(adjustedPosition.corner!==self.elements.tip.attr('rel'))
createTip.call(self,adjustedPosition.corner);
};
return adjustedPosition;
};
function jQueryStyle(style,sub)
{
var styleObj,i;
styleObj=jQuery.extend(true,{},style);
for(i in styleObj)
{
if(sub===true&&i.search(/(tip|classes)/i)!==-1)
delete styleObj[i];
else if(!sub&&i.search(/(width|border|tip|title|classes|user)/i)!==-1)
delete styleObj[i];
};
return styleObj;
};
function sanitizeStyle(style)
{
if(typeof style.tip!=='object')style.tip={corner:style.tip};
if(typeof style.tip.size!=='object')style.tip.size={width:style.tip.size,height:style.tip.size};
if(typeof style.border!=='object')style.border={width:style.border};
if(typeof style.width!=='object')style.width={value:style.width};
if(typeof style.width.max=='string')style.width.max=parseInt(style.width.max.replace(/([0-9]+)/i,"jQuery1"));
if(typeof style.width.min=='string')style.width.min=parseInt(style.width.min.replace(/([0-9]+)/i,"jQuery1"));
if(typeof style.tip.size.x=='number')
{
style.tip.size.width=style.tip.size.x;
delete style.tip.size.x;
};
if(typeof style.tip.size.y=='number')
{
style.tip.size.height=style.tip.size.y;
delete style.tip.size.y;
};
return style;
};
function buildStyle()
{
var self,i,styleArray,styleExtend,finalStyle,ieAdjust;
self=this;
styleArray=[true,{}];
for(i=0;i<arguments.length;i++)
styleArray.push(arguments[i]);
styleExtend=[jQuery.extend.apply(jQuery,styleArray)];
while(typeof styleExtend[0].name=='string')
{
styleExtend.unshift(sanitizeStyle(jQuery.fn.qtip.styles[styleExtend[0].name]));
};
styleExtend.unshift(true,{classes:{tooltip:'qtip-'+(arguments[0].name||'defaults')}},jQuery.fn.qtip.styles.defaults);
finalStyle=jQuery.extend.apply(jQuery,styleExtend);
ieAdjust=(jQuery.browser.msie)?1:0;
finalStyle.tip.size.width+=ieAdjust;
finalStyle.tip.size.height+=ieAdjust;
if(finalStyle.tip.size.width%2>0)finalStyle.tip.size.width+=1;
if(finalStyle.tip.size.height%2>0)finalStyle.tip.size.height+=1;
if(finalStyle.tip.corner===true)
finalStyle.tip.corner=(self.options.position.corner.tooltip==='center')?false:self.options.position.corner.tooltip;
return finalStyle;
};
function calculateTip(corner,width,height)
{
var tips={
bottomRight:[[0,0],[width,height],[width,0]],
bottomLeft:[[0,0],[width,0],[0,height]],
topRight:[[0,height],[width,0],[width,height]],
topLeft:[[0,0],[0,height],[width,height]],
topMiddle:[[0,height],[width/2,0],[width,height]],
bottomMiddle:[[0,0],[width,0],[width/2,height]],
rightMiddle:[[0,0],[width,height/2],[0,height]],
leftMiddle:[[width,0],[width,height],[0,height/2]]
};
tips.leftTop=tips.bottomRight;
tips.rightTop=tips.bottomLeft;
tips.leftBottom=tips.topRight;
tips.rightBottom=tips.topLeft;
return tips[corner];
};
function calculateBorders(radius)
{
var borders;
if(jQuery('<canvas>').get(0).getContext)
{
borders={
topLeft:[radius,radius],topRight:[0,radius],
bottomLeft:[radius,0],bottomRight:[0,0]
};
}
else if(jQuery.browser.msie)
{
borders={
topLeft:[-90,90,0],topRight:[-90,90,-radius],
bottomLeft:[90,270,0],bottomRight:[90,270,-radius]
};
};
return borders;
};
function bgiframe()
{
var self,html,dimensions;
self=this;
dimensions=self.getDimensions();
html='<iframe class="qtip-bgiframe" frameborder="0" tabindex="-1" src="javascript:false" '+
'style="display:block; position:absolute; z-index:-1; filter:alpha(opacity=\'0\'); border: 1px solid red; '+
'height:'+dimensions.height+'px; width:'+dimensions.width+'px" />';
self.elements.bgiframe=self.elements.wrapper.prepend(html).children('.qtip-bgiframe:first');
};
jQuery(document).ready(function()
{
jQuery.fn.qtip.cache={
screen:{
scroll:{left:jQuery(window).scrollLeft(),top:jQuery(window).scrollTop()},
width:jQuery(window).width(),
height:jQuery(window).height()
}
};
var adjustTimer;
jQuery(window).bind('resize scroll',function(event)
{
clearTimeout(adjustTimer);
adjustTimer=setTimeout(function()
{
if(event.type==='scroll')
jQuery.fn.qtip.cache.screen.scroll={left:jQuery(window).scrollLeft(),top:jQuery(window).scrollTop()};
else
{
jQuery.fn.qtip.cache.screen.width=jQuery(window).width();
jQuery.fn.qtip.cache.screen.height=jQuery(window).height();
};
for(i=0;i<jQuery.fn.qtip.interfaces.length;i++)
{
var api=jQuery.fn.qtip.interfaces[i];
if(api.status.rendered===true
&&(api.options.position.type!=='static'
||api.options.position.adjust.scroll&&event.type==='scroll'
||api.options.position.adjust.resize&&event.type==='resize'))
{
api.updatePosition(event,true);
}
};
}
,100);
})
jQuery(document).bind('mousedown.qtip',function(event)
{
if(jQuery(event.target).parents('div.qtip').length===0)
{
jQuery('.qtip[unfocus]').each(function()
{
var api=jQuery(this).qtip("api");
if(jQuery(this).is(':visible')&&!api.status.disabled
&&jQuery(event.target).add(api.elements.target).length>1)
api.hide(event);
})
};
})
});
jQuery.fn.qtip.interfaces=[]
jQuery.fn.qtip.log={error:function(){return this;}};
jQuery.fn.qtip.constants={};
jQuery.fn.qtip.defaults={
content:{
prerender:false,
text:false,
url:false,
data:null,
title:{
text:false,
button:false
}
},
position:{
target:false,
corner:{
target:'bottomRight',
tooltip:'topLeft'
},
adjust:{
x:0,y:0,
mouse:true,
screen:false,
scroll:true,
resize:true
},
type:'absolute',
container:false
},
show:{
when:{
target:false,
event:'mouseover'
},
effect:{
type:'fade',
length:100
},
delay:140,
solo:false,
ready:false
},
hide:{
when:{
target:false,
event:'mouseout'
},
effect:{
type:'fade',
length:100
},
delay:0,
fixed:false
},
api:{
beforeRender:function(){},
onRender:function(){},
beforePositionUpdate:function(){},
onPositionUpdate:function(){},
beforeShow:function(){},
onShow:function(){},
beforeHide:function(){},
onHide:function(){},
beforeContentUpdate:function(){},
onContentUpdate:function(){},
beforeContentLoad:function(){},
onContentLoad:function(){},
beforeTitleUpdate:function(){},
onTitleUpdate:function(){},
beforeDestroy:function(){},
onDestroy:function(){},
beforeFocus:function(){},
onFocus:function(){}
}
};
jQuery.fn.qtip.styles={
defaults:{
background:'white',
color:'#111',
overflow:'hidden',
textAlign:'left',
width:{
min:0,
max:250
},
padding:'5px 9px',
border:{
width:1,
radius:0,
color:'#d3d3d3'
},
tip:{
corner:false,
color:false,
size:{width:13,height:13},
opacity:1
},
title:{
background:'#e1e1e1',
fontWeight:'bold',
padding:'7px 12px'
},
button:{
cursor:'pointer'
},
classes:{
target:'',
tip:'qtip-tip',
title:'qtip-title',
button:'qtip-button',
content:'qtip-content',
active:'qtip-active'
}
},
cream:{
border:{
width:3,
radius:0,
color:'#F9E98E'
},
title:{
background:'#F0DE7D',
color:'#A27D35'
},
background:'#FBF7AA',
color:'#A27D35',
classes:{tooltip:'qtip-cream'}
},
light:{
border:{
width:3,
radius:0,
color:'#E2E2E2'
},
title:{
background:'#f1f1f1',
color:'#454545'
},
background:'white',
color:'#454545',
classes:{tooltip:'qtip-light'}
},
dark:{
border:{
width:3,
radius:0,
color:'#303030'
},
title:{
background:'#404040',
color:'#f3f3f3'
},
background:'#505050',
color:'#f3f3f3',
classes:{tooltip:'qtip-dark'}
},
red:{
border:{
width:3,
radius:0,
color:'#CE6F6F'
},
title:{
background:'#f28279',
color:'#9C2F2F'
},
background:'#F79992',
color:'#9C2F2F',
classes:{tooltip:'qtip-red'}
},
green:{
border:{
width:3,
radius:0,
color:'#A9DB66'
},
title:{
background:'#b9db8c',
color:'#58792E'
},
background:'#CDE6AC',
color:'#58792E',
classes:{tooltip:'qtip-green'}
},
blue:{
border:{
width:3,
radius:0,
color:'#ADD9ED'
},
title:{
background:'#D0E9F5',
color:'#5E99BD'
},
background:'#E5F6FE',
color:'#4D9FBF',
classes:{tooltip:'qtip-blue'}
}
};
})(joms.jQuery);