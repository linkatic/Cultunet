joms.extend({
	gallery:{
		inputFocused: false,
		bindFocus: function(){
			joms.jQuery('textarea,:text').each( function(){

				joms.jQuery( this ).focus( function(){
					joms.gallery.inputFocused	= true;
				}).blur( function(){
					joms.gallery.inputFocused	= false;
				});
			});
		},
		bindKeys: function(){

			joms.jQuery(document.documentElement).keyup(function (event) {
				
				// handle cursor keys
				// We should not move the pictures if the focus is at elsewhere.
				if( !joms.gallery.inputFocused )
				{
					if (event.keyCode == 37)
					{
						joms.gallery.displayPhoto(joms.gallery.prevPhoto());
					}
					else if (event.keyCode == 39)
					{
						joms.gallery.displayPhoto(joms.gallery.nextPhoto());
					}
				}
			});
		},
		showDeletePhoto: function( element ){
			if( joms.jQuery( element ).children('.photo-action') )
			{
				if( joms.jQuery( element ).children('.photo-action').css('display') == 'none' )
				{
					joms.jQuery( element ).children( '.photo-action' ).show();
				}
				else
				{
					joms.jQuery( element ).children( '.photo-action' ).hide();
				}
			}
		},
		confirmRemovePhoto: function( id ){
			
			var action = '';
			
			if( id == null )
			{
				var photos = jsPlaylist.photos;
				var photo = joms.gallery.currentPhoto();
				
				id			= photo.id;

				// Remove in JSON
				photos.splice(joms.gallery.getPlaylistIndex(photo.id), 1);
				
				var lastEntry = (jsPlaylist.photos.length<1) ? 1 : 0;

				if (!lastEntry)
				{
					action	= 'joms.gallery.displayPhoto(joms.gallery.nextPhoto());cWindowHide();';
				}
			}
			
			var ajaxCall = "jax.call('community', 'photos,ajaxConfirmRemovePhoto', '" + id + "' , '" + action + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		removePhoto: function( id , action ){
			var ajaxCall = "jax.call('community', 'photos,ajaxRemovePhoto', '" + id + "', '" + action + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		},
		getPlaylistIndex: function(photoId){
			if (photoId==undefined)
				return 0;
		
			var playlistIndex;
			joms.jQuery.each(jsPlaylist.photos, function(i)
			{
				if (this.id==photoId)
					playlistIndex = i;
			});
			
			return playlistIndex;
		},
		nextPhoto: function(photo){
			var playlistIndex = 0;
			
			if (photo!=undefined)
			{
				playlistIndex = joms.gallery.getPlaylistIndex(photo.id);		
			} else {
				playlistIndex = jsPlaylist.currentPlaylistIndex + 1;
			
				if (playlistIndex >= jsPlaylist.photos.length)
					playlistIndex = 0;
			}
		
			return jsPlaylist.photos[playlistIndex];
		},
		prevPhoto: function( photo ){
			var playlistIndex = 0;
			
			if (photo!=undefined)
			{
				playlistIndex = joms.gallery.getPlaylistIndex(photo.id);
			} else {
				playlistIndex = jsPlaylist.currentPlaylistIndex - 1;
			
				if (playlistIndex < 0)
					playlistIndex = jsPlaylist.photos.length - 1;		
			}
		
			return jsPlaylist.photos[playlistIndex];
		},
		currentPhoto: function( photo ){
			var playlistIndex = jsPlaylist.currentPlaylistIndex;
			
			if (photo!=undefined)
			{
				playlistIndex = joms.gallery.getPlaylistIndex(photo.id);
				joms.gallery.urlPhotoId(photo.id);
			}
			
			if (playlistIndex==undefined)
			{
				playlistIndex = joms.gallery.getPlaylistIndex(joms.gallery.urlPhotoId());
			}
		
			jsPlaylist.currentPlaylistIndex = playlistIndex;
			
			return jsPlaylist.photos[playlistIndex];
		},
		urlPhotoId: function(photoId){
			if (photoId==undefined)
			{
				var url = document.location.href;
				if (url.match('#') && url.split('#')[1].match('photoid='))
				{
					url = url.split('#')[1];
					if (url.match('&'))
						url = url.split('&')[0];
					return url.split('=')[1];
				}
			} else {
				document.location = document.location.href.split('#')[0] + '#photoid=' + photoId;
			}
		},
		init: function(){
			/* Fallback to older joms.jQuery versions (should conflict arises) */
			if (typeof(joms.jQuery.isArray)=="undefined")
			{
				joms.jQuery.extend({
					isArray: function( obj ) {
						return obj.constructor==Array;
					}
				});
			}
		
			joms.gallery.displayViewport();
			joms.gallery.displayPhoto(joms.gallery.currentPhoto());
		},
		displayViewport: function(){
			// Set up photoViewport events
			var photoViewport = joms.jQuery('#cGallery .photoViewport');
			photoViewport.unbind();
			photoViewport.hover(
				function(){ joms.jQuery('#cGallery .photoAction').fadeIn('fast'); },
				function(){	joms.jQuery('#cGallery .photoAction').fadeOut('fast'); }
			);
				
			// Setting photoDisplay into 16:12 aspect ratio
			var photoDisplay = joms.jQuery('#cGallery .photoDisplay');
			photoDisplay.css('height', Math.floor(photoDisplay.width() / 16 * 12));	
		
			// Position loading icons
			var photoLoad = joms.jQuery('#cGallery .photoLoad');
			photoLoad.css({'top' : Math.floor((photoDisplay.height() / 2) - (photoLoad.height() / 2)),
				           'left': Math.floor((photoDisplay.width() / 2) - (photoLoad.width() / 2))
				          });	
			
			// photoActions
			var photoActions = joms.jQuery('#cGallery .photoActions');
			photoActions.css({'width' : photoDisplay.width(),
				              'height': 0,
				              'top'   : 0,
				              'left'  : 0
				             });
		
			var photoAction_next = joms.jQuery('#cGallery .photoAction._next');
			photoAction_next.css({'top'  : Math.floor((photoDisplay.height() / 2) - (photoAction_next.height() / 2)),
		                          'right': 0
		                         });
		
			var photoAction_prev = joms.jQuery('#cGallery .photoAction._prev');
			photoAction_prev.css({'top' : Math.floor((photoDisplay.height() / 2) - (photoAction_prev.height() / 2)),
		                          'left': 0
		                         });
			
			// photoTags
			var photoTags = joms.jQuery('#cGallery .photoTags');
			photoTags.css({'width' : photoDisplay.width(),
				           'height': 0,
				           'top'   : 0,
				           'left'  : 0
				          });
		},
		displayPhoto: function(photo){
			var photoLoad = joms.jQuery('#cGallery .photoLoad');
			photoLoad.show();
		
			// Before display photo
			joms.gallery.currentPhoto(photo);
		
			// Update thumbnail
			joms.jQuery('#cGallery .photoAction._next img').attr('src', joms.gallery.nextPhoto().thumbnail);
			joms.jQuery('#cGallery .photoAction._prev img').attr('src', joms.gallery.prevPhoto().thumbnail);
			
			joms.gallery.displayPhotoCaption(photo.caption);
			
			var newPhotoImage = joms.gallery.createPhotoImage(photo, function()
			{
				var photoDisplay  = joms.jQuery('#cGallery .photoDisplay');
				var photoImage    = joms.jQuery('#cGallery .photoImage');
				
				photoImage.fadeOut('fast', function()
				{
					photoDisplay.empty();
					
					newPhotoImage.appendTo(photoDisplay);
		
					// The new photo need to be made visible first before we can get the correct size, (IE!)
					// So, we take it out of the screen first (unless u have super wide screen!)
					newPhotoImage.css({'top': '3000px','left': '4000px', 'visibility':'visible', 'position':'absolute'});
					
					// If newPhoto height/width is larger than the viewport, we need to do a html resize
					var photoHeight = newPhotoImage[0].height;
					var photoWidth  = newPhotoImage[0].width; 
					if( newPhotoImage[0].height > photoDisplay.height() ){
						photoWidth  = photoWidth * (photoDisplay.height() / photoHeight);
						photoHeight = photoDisplay.height(); 
					}
					if( photoWidth > photoDisplay.width() ){
						photoHeight  = photoHeight * (photoDisplay.width() / photoWidth);
						photoWidth 	 = photoDisplay.width(); 
					}
					
					// Now that we have the correct size, reposition the image
					newPhotoImage.css({'width'      : photoWidth,
					                   'height'     : photoHeight,
					                   'top'        : Math.floor((photoDisplay.height() - photoHeight) / 2),
					                   'left'       : Math.floor((photoDisplay.width()  - photoWidth)  / 2),
					                   'visibility' : 'visible',
					                   'display'    : 'none'        
					                  })
					             .fadeIn('fast', function()
					              {
					              	joms.gallery.displayCreator(photo.id);
									joms.gallery.displayPhotoWalls(photo.id);
									joms.gallery.displayPhotoTags(photo.tags);
								  });
		
					var photoLoad = joms.jQuery('#cGallery .photoLoad');
					photoLoad.hide();		
				});
		
			});
		
			// Prefetch images
			joms.gallery.prefetchPhoto([joms.gallery.prevPhoto(), joms.gallery.nextPhoto()]);
		},
		createPhotoImage: function(photo, callback){
			if (typeof(callback)!="function")
				callback = function(){};
		
			var photoImage = joms.jQuery(new Image()).load(callback)
			                                    .attr({
													'id'    : 'photo-' + photo.id,
													'class' : 'photoImage',
													'alt'   :   photo.caption,
													'title' : '',
													'src'   : joms.gallery.getPhotoUrl(photo)
												});
		
			return photoImage;
		},
		prefetchPhoto: function(photos){
			if (!joms.jQuery.isArray(photos))
				photos = [photos];
		
			joms.jQuery.each(photos, function(i, photo)
			{
				if (!photo.loaded)
				{
					joms.gallery.createPhotoImage(photo, function(){
						photo.loaded=true;
					});
				}
			});
		},
		getPhotoUrl: function(photo){
			var photoDisplay = joms.jQuery('#cGallery .photoDisplay');
			var photoUrl = '';
			
			// If it's from remote storage
			if(photo.url.indexOf('option=com_community')==-1)
			{
				photoUrl = photo.url;
			} else {
				photoUrl = photo.url + '&' + joms.jQuery.param({'maxW': photoDisplay.width(), 'maxH': photoDisplay.height()});
			}
			return photoUrl;
		},
		displayPhotoCaption: function(photoCaption){
			var photoCaptionText = joms.jQuery('#cGallery .photoCaptionText');
			photoCaptionText.text((photoCaption!='') ? photoCaption : jsPlaylist.language.CC_NO_PHOTO_CAPTION_YET);	
		},
		editPhotoCaption: function(){
			var photoCaption = joms.jQuery('#cGallery .photoCaption');
			photoCaption.addClass('editMode');
		
			var photoCaptionText  = joms.jQuery('#cGallery .photoCaptionText');
			var photoCaptionInput = joms.jQuery('#cGallery .photoCaptionInput');
			photoCaptionInput.val(joms.jQuery.trim(photoCaptionText.text()));
		},
		cancelPhotoCaption: function(){
			var photoCaption = joms.jQuery('#cGallery .photoCaption');
			photoCaption.removeClass('editMode');
			
			var photoCaptionInput = joms.jQuery('#cGallery .photoCaptionInput');
			photoCaptionInput.val('');
		},
		savePhotoCaption: function(){
			var photoCaptionText  = joms.jQuery('#cGallery .photoCaptionText');
			var photoCaptionInput = joms.jQuery('#cGallery .photoCaptionInput');
			
			var oldPhotoCaption = joms.jQuery.trim(photoCaptionText.text());
			var newPhotoCaption = joms.jQuery.trim(photoCaptionInput.val());
			
			if (newPhotoCaption=='' || newPhotoCaption==oldPhotoCaption)
			{
				joms.gallery.cancelPhotoCaption();
			} else {
				jax.call('community', 'photos,ajaxSaveCaption', joms.gallery.currentPhoto().id, newPhotoCaption);
			}
		},
		updatePhotoCaption: function(photoId, photoCaption){
			// Update photo caption
			var photoCaptionText  = joms.jQuery('#cGallery .photoCaptionText');
			photoCaptionText.text(photoCaption);
			
			// Update playlist caption
			jsPlaylist.photos[joms.gallery.getPlaylistIndex(photoId)].caption = photoCaption;
		
			joms.gallery.cancelPhotoCaption();
		},
		displayPhotoWalls: function(photoId){
			jax.call('community', 'photos,ajaxSwitchPhotoTrigger', photoId);
		},
		setPhotoAsDefault: function(){
			if(confirm(jsPlaylist.language.CC_SET_PHOTO_AS_DEFAULT_DIALOG))
			{
				jax.call('community', 'photos,ajaxSetDefaultPhoto', jsPlaylist.album, joms.gallery.currentPhoto().id);
			}
		},
		downloadPhoto: function(){
			window.open(jsPlaylist.photos[jsPlaylist.currentPlaylistIndex].originalUrl);
		},
		updatePhotoReport: function(html){
			joms.jQuery('.page-action#report-this').remove();
			joms.jQuery('.page-actions').prepend(html);
		},
		newPhotoTag: function(properties){
			var tag = {'id': null,
			           'userId': null,
			           'photoId': null,
			           'displayName': null,
			           'profileUrl': null,
			           'top': null,
			           'left':null,
			           'width': null,
			           'height': null,
		   		 	   'displayTop': null,
				 	   'displayLeft': null,
				 	   'displayWidth': null,
				 	   'displayHeight': null,
				 	   'canRemove:': null
			   	      };
		
			joms.jQuery.extend(tag, properties);
			
			return tag;
		},
		createPhotoTag: function(tag){
			var photo     = joms.jQuery('#cGallery .photoImage');
			var photoTags = joms.jQuery('#cGallery .photoTags');
		
			if (typeof(tag)=='string')
				tag = eval('(' + tag + ')');
		
			// If it's a single tag, put it into an array anyway.
			var singleTag = false;
			if (!joms.jQuery.isArray(tag))
			{
				tag = [tag];
				singleTag = true;
			}
		
			// Create photo tag
			var newPhotoTags = new Array();
			joms.jQuery.each(tag, function(i, tag)
			{
				var photoTag = joms.gallery.drawPhotoTag(tag, photo);
				photoTag.data('tag', tag)
						.attr('id', 'photoTag-' + tag.id)
						.hover(
							function(){ joms.gallery.showPhotoTag(tag.id, 'Label'); },
							function(){ joms.gallery.hidePhotoTag(tag.id); }
						)
				        .appendTo(photoTags);
		
				var photoTagLabel = joms.jQuery('<div class="photoTagLabel">');
		
				photoTagLabel.html(tag.displayName);
				photoTagLabel.wrapInner('<span></span>')
				             .appendTo(photoTag);
				
				newPhotoTags.push(photoTag);
			});
			
			// Return value
			if (singleTag)
				return newPhotoTags[0];
			else
				return newPhotoTags;
		},
		drawPhotoTag: function( tag, photo){
			// Test if display dimensions has to be redrawn by
			// setting a simple text case. As long as one value
			// is missing or incorrect, redraw tag.
			var redrawTag = (tag.displayWidth != tag.width * photo.width());
			
			if (redrawTag)
			{
				// Calculate displayWidth
				tag.displayWidth = tag.width * photo.width();
				
				// Calculate displayHeight
				tag.displayHeight = tag.height * photo.height();
				
				// Calculate displayTop
				tag.displayTop = (tag.top * photo.height()) - (tag.displayHeight / 2);
		
				if (tag.displayTop < 0)
					tag.displayTop = 0;
				
				maxTop = photo.height() - tag.displayHeight;
				if (tag.displayTop > maxTop)
					tag.displayTop = maxTop;
				
				// Calculate displayLeft
				tag.displayLeft = (tag.left * photo.width()) - (tag.displayWidth / 2);
				
				if (tag.displayLeft < 0)
					tag.displayLeft = 0;
			
				maxLeft = photo.width() - tag.displayWidth;
				if (tag.displayLeft > maxLeft)
					tag.displayLeft = maxLeft;
			}
		
			// Create photoTag
			var photoTag = joms.jQuery('<div class="photoTag">');
			photoTag.css({'width' : tag.displayWidth,
			              'height': tag.displayHeight,
			              'top'   : tag.displayTop,
			              'left'  : tag.displayLeft
			             })
		
			// Create photoTagBorder
			// - For dark/light photo where tag's border color
			//   might blend and dissappear within the photo.
			var photoTagBorder = joms.jQuery('<div class="photoTagBorder">');
			photoTagBorder.css({'width' : tag.displayWidth - 4,
			                    'height': tag.displayHeight - 4,
			                    /* Override border styling with !important in CSS */   
			                    'border': '2px solid #222'
				               })
				          .appendTo(photoTag);
		
			// Update display dimensions into playlist tag except for unsubmitted tags
			if (tag.id!=null)
				joms.gallery.updatePlaylistTag(tag);
		
			return photoTag;
		},
		updatePlaylistTag: function( tag ){
			var playlistTag;
			
			// If tag exists, use it.
			var tags = jsPlaylist.photos[joms.gallery.getPlaylistIndex(tag.photoId)].tags;
			joms.jQuery.each(tags, function()
			{
				if (this.id==tag.id)
					playlistTag=this;
			})
		
			// If tag does not exist, create it.
			if (playlistTag==undefined)
				playlistTag = tags[tags.push(joms.gallery.newPhotoTag())-1];
			
			// Merge tag's properties
			joms.jQuery.extend(playlistTag, tag);
		},
		displayPhotoTags: function(tags){
			// Before display photo Tag
			joms.gallery.clearPhotoTag();
			joms.gallery.clearPhotoTextTag();
			
			var photoImage = joms.jQuery("#cGallery .photoImage");
		
			// photoTags container to follow photo position & dimension.
			var photoTags = joms.jQuery("#cGallery .photoTags");
			photoTags.css({'width' : photoImage.width(),
				           'height': photoImage.height(),
				           'top'   : photoImage.position().top,
				           'left'  : photoImage.position().left
				          });
		
			joms.gallery.createPhotoTag(tags);
			joms.gallery.createPhotoTextTag(tags);
		},
		addPhotoTag: function(userId){
			var tag = joms.jQuery("#cGallery .photoTag.new").data('tag');
			jax.call('community' , 'photos,ajaxAddPhotoTag', tag.photoId, userId, tag.top, tag.left, tag.width, tag.height);
			
			joms.gallery.cancelNewPhotoTag();
		},
		removePhotoTag: function(tag){
			jax.call('community', 'photos,ajaxRemovePhotoTag', tag.photoId, tag.userId);
			
			joms.gallery.clearPhotoTag(tag);
			joms.gallery.clearPhotoTextTag(tag);
				
			var tags = jsPlaylist.photos[joms.gallery.getPlaylistIndex(tag.photoId)].tags;
			
			joms.jQuery.each(tags, function(i)
			{
				if (this.id==tag.id)
					tags.splice(i, 1);
			});	
		},
		clearPhotoTag: function(tag){
			if (tag==undefined)
			{
				joms.jQuery('#cGallery .photoTag').remove();
			} else {
				joms.jQuery('#photoTag-' + tag.id).remove();
			}	
		},
		showPhotoTag: function(id, classSuffix){
			joms.jQuery('#photoTag-' + id).addClass('show' + classSuffix);
		},
		hidePhotoTag: function(id){
			joms.jQuery('#photoTag-' + id).removeClass('show showLabel showForce');
		},
		createPhotoTextTag: function(tags){
			var photoTextTags = joms.jQuery('#cGallery .photoTextTags');
		
			if (typeof(tags)=='string')
				tags = eval('(' + tags + ')');
			
			// If it's a single tag, put it into an array anyway.
			var singleTag = false;
			if (!joms.jQuery.isArray(tags))
			{
				tags = [tags];
				singleTag = true;
			}
		
			// Create photo tag
			var newPhotoTextTags = new Array();
			joms.jQuery.each(tags, function(i, tag)
			{		
				if (tag.id==undefined)
					return;
		
				// photoTextTag
				var photoTextTag = joms.jQuery('<span class="photoTextTag"></span>');
				
				photoTextTag.data('tag', tag)
							.attr('id', 'photoTextTag-' + tag.id)
							.hover(
								function(){ joms.gallery.showPhotoTag(tag.id, 'Force'); },
								function(){ joms.gallery.hidePhotoTag(tag.id); }
							 )
							.appendTo(photoTextTags);			
		
				// photoTextTagLink
				var photoTextTagLink = joms.jQuery('<a>');
				photoTextTagLink.attr('href', tag.profileUrl)
				                .html(tag.displayName)
								.prependTo(photoTextTag);
				
				// photoTextTagActions
				if (tag.canRemove) {
					/* Temporarily belong inside this if condition */
					var photoTextTagActions = joms.jQuery('<span class="photoTextTagActions"></span>');
					photoTextTagActions.appendTo(photoTextTag);
		
					var photoTextTagAction_remove = joms.jQuery('<a class="photoTextTagAction" href="javascript: void(0);"></a>');
					photoTextTagAction_remove.addClass('_remove')
											 .html(jsPlaylist.language.CC_REMOVE)
											 .click( function(){ joms.gallery.removePhotoTag(tag); } )
											 .appendTo(photoTextTagActions);
		
					photoTextTagActions.before(' ').prepend('(').append(')');
				}
		
				newPhotoTextTags.push(photoTextTag);
			});
			
			joms.gallery.commifyTextTags();
		
			return newPhotoTextTags;
		},
		commifyTextTags: function(){
			// Remove all comma	
			joms.jQuery('#cGallery .photoTextTags .comma').remove();
			
			// Rebuild comma
			photoTextTag = joms.jQuery('#cGallery .photoTextTag');
			photoTextTag.each(function(i)
			{
				if (i==0) return;
				
				var comma = joms.jQuery('<span class="comma"></span>');
				comma.html(', ')
				     .prependTo(this);
			});
		},
		clearPhotoTextTag: function(tag){
			if (tag==undefined)
			{
				joms.jQuery('#cGallery .photoTextTag').remove();
			} else {
				joms.jQuery('#photoTextTag-' + tag.id).remove();
				joms.gallery.commifyTextTags();
			}
		},
		startTagMode: function(){
			joms.jQuery('#cGallery .photoTagInstructions').slideDown('fast');
			joms.jQuery('#startTagMode').hide();
		
			var photoViewport = joms.jQuery('#cGallery .photoViewport');
			photoViewport.addClass('tagMode');
		
			var photo = joms.jQuery('#cGallery .photoImage');
			var photoImage = photo;
			var photoTags = joms.jQuery('#cGallery .photoTags');
			
			photoTags.click(function(e){
		
				// Remove any unsubmitted photo tags
				joms.jQuery('.photoTag.new').remove();
		
				// Create new photo tag
				var photoTag = joms.gallery.createPhotoTag( joms.gallery.newPhotoTag({'photoId': joms.gallery.currentPhoto().id,
														   'top'    : (e.pageY - joms.jQuery(this).offset().top) / photo.height(),
					                                       'left'   : (e.pageX - joms.jQuery(this).offset().left) / photo.width(),
				                                           'width'  : jsPlaylist.config.defaultTagWidth / photo.width(),
				                                           'height' : jsPlaylist.config.defaultTagHeight / photo.height()
					                                      }));
				photoTag.addClass('new');
		
				// Attach photoTagActions to photoTag
				var photoTagActions = joms.jQuery('#cGallery .photoTagActions');
				photoTagActions.css({'top' : photoTag.position().top + photoTag.outerHeight(true),
					                 'left': photoTag.position().left
					                })
							   .show();
		
				photoTagActions.children('*').click(function(event){
					event.stopPropagation();
				});
		
			});
		},
		stopTagMode: function(){
			joms.gallery.cancelNewPhotoTag();
			
			var photoViewport = joms.jQuery('#cGallery .photoViewport');
			photoViewport.removeClass('tagMode');
					
			var photoTags = joms.jQuery("#cGallery .photoTags");
			photoTags.unbind('click');
		
			joms.jQuery('#cGallery .photoTagInstructions').hide();
			joms.jQuery('#startTagMode').show();
			
			cWindowHide();
		},
		selectNewPhotoTagFriend: function(){
			var photoTagFriend = joms.jQuery('#cGallery .photoTagFriend');
			
			// If user has no friend
			if (photoTagFriend.length<1)
			{
				cWindowShow(function()
				{
					joms.jQuery('#cWindowContent').html(jsPlaylist.language.CC_PHOTO_TAG_NO_FRIEND);
				}, jsPlaylist.language.CC_SELECT_FRIEND, 450, 80);
				return;
			}
			
			// If user has tagged all friends
			if (joms.gallery.currentPhoto().tags.length == photoTagFriend.length) {
				cWindowShow(function()
				{
					joms.jQuery('#cWindowContent').html(jsPlaylist.language.CC_PHOTO_TAG_ALL_TAGGED);	
				}, jsPlaylist.language.CC_SELECT_FRIEND, 450, 80);
				return;
			}
			
			// Else, proceed as usual.
			cWindowShow(function(){
				joms.gallery.showPhotoTagFriends();
			}, jsPlaylist.language.CC_SELECT_FRIEND, 300, 300);
			cWindowActions('<button class="button" onclick="joms.gallery.confirmPhotoTagFriend();">' + jsPlaylist.language.CC_CONFIRM + '</button>');	
		},
		confirmPhotoTagFriend: function(){
			//hide notice message if previously displayed.
			joms.jQuery('#cWindow .js-system-message').hide();
			
			var photoTagFriendChecked = joms.jQuery('#cWindow .photoTagFriend input:checked');
			if(photoTagFriendChecked.length > 0)
			{
				joms.gallery.addPhotoTag(photoTagFriendChecked.val());
			} else {
				joms.jQuery('#cWindow .js-system-message').show();
				joms.jQuery('#cWindow .js-system-message').fadeOut(5000);
			}
		},
		showPhotoTagFriends: function(){
			// Append friends master list to cWindow
			joms.jQuery('#cWindowContent').empty();
			joms.jQuery('#cGallery .photoTagSelectFriend').clone().appendTo('#cWindowContent');
					
			// Filter out all tagged users
			var filterOut = new Array();
			joms.jQuery.each(joms.gallery.currentPhoto().tags, function()
			{
				filterOut.push(this.userId);
			});
		
			joms.gallery.filterPhotoTagFriend(filterOut);
		
			// Focus input box (after 300ms delay for the cWindow to fade in first)
			setTimeout("joms.jQuery('#cWindowContent .photoTagFriendFilter').focus()", 300)
		},
		filterPhotoTagFriend: function(filterOut){
			var photoTagFriend           = joms.jQuery('#cWindow .photoTagFriend');
			var photoTagFriendFilter     = joms.jQuery('#cWindow .photoTagFriendFilter');
			var photoTagFriendFilterText = joms.jQuery.trim(photoTagFriendFilter.val());
			
			if (filterOut!=undefined)
			{
				joms.jQuery.each(filterOut, function(i, userId)
				{			
					photoTagFriend.filter(function()
					{
						return joms.jQuery(this).attr('id')=='photoTagFriend-' + userId;
					}).addClass('tagged');
				})
				
				return;
			}
		
			if (photoTagFriendFilterText=='')
			{
				photoTagFriend.not('.tagged')
				              .removeClass('hide');
				return;
			}
		
			photoTagFriend.not('.tagged')
			              .addClass('hide')
			              .filter(function()
			               {
			               	   return (this.textContent || this.innerText || '').toUpperCase().indexOf(photoTagFriendFilterText.toUpperCase()) >= 0
			               })
			              .removeClass('hide');
		},
		cancelNewPhotoTag: function(){
			var newPhotoTag = joms.jQuery('.photoTag.new');
			newPhotoTag.remove();
			
			var photoTagActions = joms.jQuery('#cGallery .photoTagActions');
			photoTagActions.hide();
		},
		displayCreator: function(photoid){
			jax.call('community', 'photos,ajaxDisplayCreator', photoid);
		},
		setProfilePicture: function( ){
			var ajaxCall = "jax.call('community', 'photos,ajaxLinkToProfile', '" + joms.gallery.currentPhoto().id + "');";
			cWindowShow(ajaxCall, '', 450, 100);
		}
	}
});

/**
 * The following functions are deprecated and should be avoided
 * 
 * Deprecated since 1.8.x
 **/   
function getPlaylistIndex(photoId)
{
	joms.gallery.getPlaylistIndex( photoId );
} 

function nextPhoto(photo)
{
	joms.gallery.nextPhoto( photo );
}

function prevPhoto(photo)
{
	joms.gallery.prevPhoto( photo );
}

function currentPhoto(photo)
{
	joms.gallery.currentPhoto( photo );
}

function urlPhotoId(photoId)
{
	joms.gallery.urlPhotoId( photoId );
}

function initGallery()
{
	joms.gallery.init();
}

function displayViewport()
{
	joms.gallery.displayViewPort();
}
 
function displayPhoto(photo)
{
	joms.gallery.displayPhoto( photo );
}

function createPhotoImage(photo, callback)
{
	joms.gallery.createPhotoImage( photo , callback );
}

function prefetchPhoto(photos)
{
	joms.gallery.prefetchPhoto( photos );
}

function getPhotoUrl(photo)
{
	joms.gallery.getPhotoUrl( photo );
}

function displayPhotoCaption(photoCaption)
{
	joms.gallery.displayPhotoCaption( photoCaption );
}

function editPhotoCaption()
{
	joms.gallery.editPhotoCaption();
}

function cancelPhotoCaption()
{
	joms.gallery.cancelPhotoCaption();
}

function savePhotoCaption()
{
	joms.gallery.savePhotoCaption();
}

function updatePhotoCaption(photoId, photoCaption)
{
	joms.gallery.updatePhotoCaption( photoId , photoCaption );
}

function displayPhotoWalls(photoId)
{
	joms.gallery.displayPhotoWalls( photoId );
}

function setPhotoAsDefault()
{
	joms.gallery.setPhotoAsDefault();
}

function removePhoto()
{
	joms.gallery.confirmRemovePhoto();
}

function downloadPhoto()
{
	joms.gallery.downloadPhoto();
}

function updatePhotoReport(html)
{
	joms.gallery.updatePhotoReport( html );
}

function newPhotoTag(properties)
{
	joms.gallery.newPhotoTag( properties );
}

function createPhotoTag(tag)
{
	joms.gallery.createPhotoTag( tag );
}

function drawPhotoTag(tag, photo)
{	
	joms.gallery.drawPhotoTag( tag , photo );
}

function updatePlaylistTag(tag)
{	
	joms.gallery.updatePlaylistTag( tag );
}

function displayPhotoTags(tags)
{
	joms.gallery.displayPhotoTags( tags );
}

function addPhotoTag(userId)
{
	joms.gallery.addPhotoTag( userId );
}

function removePhotoTag(tag)
{
	joms.gallery.removePhotoTag( tag );
}

function clearPhotoTag(tag)
{	
	joms.gallery.clearPhotoTag( tag );
}

function showPhotoTag(id, classSuffix)
{
	joms.gallery.showPhotoTag( id , classSuffix );
}

function hidePhotoTag(id)
{
	joms.gallery.hidePhotoTag( id );
}

function createPhotoTextTag(tags)
{
	joms.gallery.createPhotoTextTag( tags );
}

function commifyTextTags()
{
	joms.gallery.commifyTextTags();
}

function clearPhotoTextTag(tag)
{
	joms.gallery.clearPhotoTextTag(tag);
}

function startTagMode()
{
	joms.gallery.startTagMode();
}

function stopTagMode()
{
	joms.gallery.stopTagMode();
}

function selectNewPhotoTagFriend()
{
	joms.gallery.selectNewPhotoTagFriend();
}

function confirmPhotoTagFriend()
{
	joms.gallery.confirmPhotoTagFriend();
}

function showPhotoTagFriends()
{
	joms.gallery.showPhotoTagFriends();
}

function filterPhotoTagFriend(filterOut)
{
	joms.gallery.filterPhotoTagFriend( filterOut );
}

function cancelNewPhotoTag()
{
	joms.gallery.cancelNewPhotoTag();
}

function displayCreator(photoid)
{
	joms.gallery.displayCreator(photoid);
}

function setProfilePicture( )
{
	joms.gallery.setProfilePicture();
}