jQuery(document).ready(function()
{
	blackout.replaceDefaultAvatar();
});

var replacePatterns = [
	{
		find: ['components/com_community/assets/default.jpg',
		       'components/com_community/assets/default_thumb.jpg'],
		rep :  'components/com_community/templates/blackout/images/default_thumb.png'
	},
	{
		find: ['components/com_community/assets/groupAvatar.png'],
		rep :  'components/com_community/templates/blackout/images/group.png'
	},
	{
		find: ['components/com_community/assets/groupThumbAvatar.png',
		       'components/com_community/assets/group_thumb.jpg'],
		rep :  'components/com_community/templates/blackout/images/group_thumb.png'      
	},
	{
		find: ['components/com_community/assets/album_thumb.jpg'],
		rep :  'components/com_community/templates/blackout/images/album_thumb.png'
	},
	{
		find: ['components/com_community/assets/eventAvatar.png'],
		rep : 'components/com_community/templates/blackout/images/event.png'
	},
	{
		find: ['components/com_community/assets/eventThumbAvatar.png'],
		rep :  'components/com_community/templates/blackout/images/event_thumb.png'
	}
];

var blackout = {
	replaceDefaultAvatar: function()
	{
		joms.jQuery('img').each(function()	
		{
			image = joms.jQuery(this);
			
			if (image.attr('src')==undefined)
				return;

			joms.jQuery(replacePatterns).each(function()
			{
				var pattern = this;

				joms.jQuery(pattern.find).each(function(i)
				{
					if (image.attr('src').match(pattern.find[i]))
						image.attr('src', image.attr('src').split(pattern.find[i])[0] + pattern.rep);
				})
			});
		});
	}
}


/* joms.filters.bind
   An alternative approach joms.filters.bind. May find its way back
   to joms class in script.js if it's good.
 */
 
 // joms.filters.option['']();
var joms_filters_option = {
	newestMember: function()
	{
		jax.call('community', 'frontpage,ajaxGetNewestMember', frontpageUsers)
	},
	activeMember: function()
	{
		jax.call('community', 'frontpage,ajaxGetActiveMember', frontpageUsers)
	},
	popularMember: function()
	{
		jax.call('community', 'frontpage,ajaxGetPopularMember', frontpageUsers)
	},
	featuredMember: function()
	{
		jax.call('community', 'frontpage,ajaxGetFeaturedMember', frontpageUsers)
	},
	allActivity: function()
	{
		jax.call('community', 'frontpage,ajaxGetActivities', 'all')
	},
	meAndFriendsActivity: function()
	{
		jax.call('community', 'frontpage,ajaxGetActivities', 'me-and-friends')
	},
	activeProfileAndFriendsActivity: function()
	{
		jax.call('community', 'frontpage,ajaxGetActivities', 'active-profile-and-friends', joms.user.getActive())
	},
	activeProfileActivity: function()
	{
		jax.call('community', 'frontpage,ajaxGetActivities', 'active-profile', joms.user.getActive());
	},
	newestVideos: function()
	{
		jax.call('community', 'frontpage,ajaxGetNewestVideos', frontpageVideos);
	},
	popularVideos: function()
	{
		jax.call('community', 'frontpage,ajaxGetPopularVideos', frontpageVideos);
	},
	featuredVideos: function()
	{
		jax.call('community', 'frontpage,ajaxGetFeaturedVideos', frontpageVideos);
	}
}

// joms.filters.activate()
function joms_filters_activate(e)
{	 
	var filterOption      = joms.jQuery(e);
	    filterOption.name = filterOption.attr('id').split('_')[1];

	if (!filterOption.hasClass('active'))
	{
		filterOption.addClass('active loading')
		            .siblings()
		            .removeClass('active');

		joms_filters_option[filterOption.name]();
	}
}

jQuery(document).ready(function()
{
	// Override joms.filters.hideLoading
	// Another reason why jax.call() needs callback param!
	joms.filters.hideLoading = function()
	{		
		jQuery( '.loading' ).hide();	
		jQuery('.filterOption').removeClass('loading').show();
		jQuery('.jomTipsJax').addClass('jomTips');
		joms.tooltip.setup();
		blackout.replaceDefaultAvatar();
	}
});