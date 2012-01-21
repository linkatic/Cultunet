function cWindowDraw()
{
	// Remove any existing #cWindow.
	joms.jQuery('#cWindow').remove();

	// cWindow html structure.
	var html  = '';
	html += '<div id="cWindow">';
	html += '	<!-- top section -->';
	html += '	<div id="cwin_tl"></div>';
	html += '	<div id="cwin_tm"></div>';
	html += '	<div id="cwin_tr"></div>';
	html += '	<div style="clear: both;"></div>';
	html += '	<!-- middle section -->';
	html += '	<div id="cwin_ml"></div>';
	html += '	<div id="cWindowContentOuter">';
	html += '		<div id="cWindowContentTop">';
	html += '			<a href="javascript:void(0);" onclick="cWindowHide();" id="cwin_close_btn">Close</a>';
	html += '			<div id="cwin_logo"></div>';
	html += '           <div class="clr"></div>';
	html += '		</div>';			
	html += '		<div id="cWindowContentWrap"><div id="cWindowContent">';
	html += '		</div></div>';
	html += '	</div>';
	html += '	<div id="cwin_mr"></div>';
	html += '	<div style="clear: both;"></div>';
	html += '	<!-- bottom section -->';
	html += '	<div id="cwin_bl"></div>';
	html += '	<div id="cwin_bm"></div>';
	html += '	<div id="cwin_br"></div>';
	html += '	<div style="clear: both;"></div>';
	html += '</div>';

	var cWindow = joms.jQuery(html).addClass('new').prependTo('body');
	
	return cWindow;
}

function cWindowShow(windowCall, winTitle, winContentWidth, winContentHeight, winType)
{
	// Get cWindow
	var cWindow = joms.jQuery('#cWindow');
	if (cWindow.length<1) cWindow = cWindowDraw();

	// Fix inconsistencies in arguments
	winType          = (winType=='' || winType==null) ? 'dialog' : winType;
	winContentWidth  = parseInt(winContentWidth);
	winContentHeight = parseInt(winContentHeight);	

	// Set up dimensions
	if (cWindow.hasClass('new'))
	{
		var cWindowSize = cWindowGetSize(winContentWidth, winContentHeight);
		cWindowResizeAddTask('#cWindow', {'width'      : cWindowSize.width(),
			                              'height'     : cWindowSize.height(),
			                              'left'       : cWindowSize.left(),
			                              'margin-top' : cWindowSize.top(),
			                              'z-index'    : cGetZIndexMax() + 1
			                  	         }, {'animate': false});
		cWindowResizeAddTask('#cWindowContentOuter, #cwin_tm, #cwin_bm', {'width' : cWindowSize.contentOuterWidth() }, {'animate': false});
		cWindowResizeAddTask('#cWindowContentOuter, #cwin_ml, #cwin_mr', {'height': cWindowSize.contentOuterHeight() }, {'animate': false});
		cWindowResizeAddTask('#cWindowContentWrap', {'height': cWindowSize.contentWrapHeight() }, {'animate': false});

		cWindowResizeExecuteTask(function()
		{
			cWindow.removeClass('new');
		});
	}
	
	// Assign window type
	joms.jQuery('#cWindow').attr('class', winType);
	
	// Set up content
	joms.jQuery('#cwin_logo').html(winTitle);
	joms.jQuery('#cWindowContent').empty();
	joms.jQuery('#cWindowAction').remove();

	// Set up behaviour
	jax.loadingFunction     = function() {
		joms.jQuery('#cWindowContentWrap').addClass('loading')
		                                  .css('overflow', 'hidden');
	};
	jax.doneLoadingFunction = function() {
		joms.jQuery('#cWindowContentWrap').removeClass('loading')
		                                  .css('overflow', 'auto');
	};

	// IE6: Rebuild alpha transparent border
	if (joms.jQuery.browser.msie && joms.jQuery.browser.version.substr(0,1)<7 && typeof(jomsIE6)!="undefined" && jomsIE6==true)
	{
		joms.jQuery('#cwin_tm, #cwin_bm, #cwin_ml, #cwin_mr').each(function()
		{
			joms.jQuery(this)[0].filters(0).sizingMethod="crop";
		})
	}

	// Resize cWindow
	cWindowResize2(winContentWidth, winContentHeight, windowCall);
}

function cWindowHide()
{
	if (joms.jQuery('#cWindowAction').size()>0)
	{
		joms.jQuery('#cWindowAction').animate({bottom: -30}, function()
		{
			joms.jQuery('#cWindow').fadeOut('fast', function(){
				joms.jQuery('#cWindowAction').remove();
				joms.jQuery(this).addClass('new');
			});
		});
	} else {
		joms.jQuery('#cWindow').fadeOut('fast', function(){
			joms.jQuery(this).addClass('new');
		});
	}
}

function cWindowGetSize(winContentWidth, winContentHeight)
{
	var cWindowSize = {contentWrapHeight   : function() { return winContentHeight },
		               contentOuterWidth   : function() { return winContentWidth },
	                   contentOuterHeight  : function() { return winContentHeight + 30 },
	                   width               : function() { return this.contentOuterWidth() + 40 },
	                   height              : function() { return this.contentOuterHeight() + 40 },
	                   left                : function() { return (joms.jQuery(window).width() - this.width()) / 2 },
	                   top                 : function() { return joms.jQuery(document).scrollTop() + (document.documentElement["clientHeight"] - this.height()) / 2 }
	                  };
	return cWindowSize;
}

// Legacy code. Forwards to cWindowResize2().
function cWindowResize(newHeight, callback, action)
{
	newHeight = parseInt(newHeight);
	cWindowResize2(joms.jQuery('#cWindowContentOuter').width(), newHeight, callback);
	if (action!=undefined) cWindowActions(action);
}

function cWindowResize2(winContentWidth, winContentHeight, callback)
{
	var cWindowSize = cWindowGetSize(winContentWidth, winContentHeight);

	// Legacy code. If there's a difference in width, do not resize (pre-1.5 behaviour).
	var options = {'animate': (joms.jQuery('#cWindow').width()==cWindowSize.width())};

	joms.jQuery('#cWindowContent').addClass('resizing');

	cWindowResizeAddTask('#cWindow', {'width'     : cWindowSize.width(),
		                              'height'    : cWindowSize.height(),
		                              'left'      : cWindowSize.left(),
		                              'marginTop' : cWindowSize.top()
		                  	         }, options);
	cWindowResizeAddTask('#cWindowContentOuter, #cwin_tm, #cwin_bm', {'width' : cWindowSize.contentOuterWidth() }, options);
	cWindowResizeAddTask('#cWindowContentOuter, #cwin_ml, #cwin_mr', {'height': cWindowSize.contentOuterHeight() }, options);
	cWindowResizeAddTask('#cWindowContentWrap', {'height': cWindowSize.contentWrapHeight()}, options);

	// Reverse task if cWindow is shrinking
	if (joms.jQuery('#cWindow').width() > cWindowSize.width() || joms.jQuery('#cWindow').height() > cWindowSize.height())
		cWindowResizeTask.reverse();

	cWindowResizeExecuteTask(callback);
}

var cWindowResizeTask = new Array();
function cWindowResizeAddTask(target, props, options)
{
	// Set up animation properties
	var defaultProps = {};
	joms.jQuery.extend(defaultProps, props);
	
	// Set up animation options
	var defaultOptions = {'animate' : true,
		                  'queue'   : false,
	                      'duration': 400,
	                      'easing'  : 'swing',
	                      'complete': function(){
	                      	    clearTimeout(window.cWindowResizeComplete);
	                      		window.cWindowResizeComplete = setTimeout(function()
	                      		{
	                      			joms.jQuery('#cWindowContent').removeClass('resizing');
	                      		}, 400);
	                      		joms.jQuery(this).removeClass('resizing');
						   }
	                     };
	joms.jQuery.extend(defaultOptions, options);
	
	if (defaultOptions.animate)
	{
		cWindowResizeTask.push(function(){ joms.jQuery(target).addClass('resizing').animate(defaultProps, defaultOptions); });
	} else {
		cWindowResizeTask.push(function(){ joms.jQuery(target).css(defaultProps); });
	}
}

function cWindowResizeExecuteTask(callback)
{
	do
	{
		cWindowResizeTask[0]();
		cWindowResizeTask.splice(0,1);
	}
	while(cWindowResizeTask.length>0);

	if (callback!=undefined && typeof(callback)=="string") eval(callback);
	if (typeof(callback)=="function") callback();
}

var cWindowActionsPoll;
function cWindowActions(action)
{
	// Remove any existing cWindowActionsPoll
	// Reason why is removed multiple times because cWindowActions
	// could be called at any point of entry!
	clearInterval(cWindowActionsPoll);

	setTimeout(function(){
		// Remove any existing cWindowActionsPoll
		clearInterval(cWindowActionsPoll);
		
		// Create new cWindowActionsPoll
		cWindowActionsPoll = setInterval(function()
							 {
							 	var n = joms.jQuery('.resizing').length;
							 	if (n < 1)
							 	{
									_cWindowActions(action);
									clearInterval(cWindowActionsPoll);
								}
							 }, 300);
	}, 300);
}

function _cWindowActions(action)
{
	// Remove any existing cWindowAction	
	joms.jQuery('#cWindowAction').remove();
	
	// Create new cWindowAction
	cWindowAction = joms.jQuery('<div>').attr('id', 'cWindowAction')
	                               .html(action)
	                               .css('bottom', '-30px')
	                               .appendTo('#cWindowContentOuter');

	// Resize cWindow
	cWindowResizeAddTask('#cWindow', {height: '+=30px'}, {'duration': 200});
	cWindowResizeAddTask('#cWindow', {marginTop: '-=15px'}, {'duration': 200});
	
	cWindowResizeAddTask('#cWindowContentOuter, #cwin_mr, #cwin_ml', {height: '+=30px'}, {'duration': 200});
	cWindowResizeExecuteTask();
	
	setTimeout(function(){joms.jQuery("#cWindowAction").animate({bottom: '0px'})}, 200);

	// Set up behavior when actions are invoked
	jax.loadingFunction = function() {
		joms.jQuery('#cWindowAction').addClass('loading');
		joms.jQuery('#cWindowContent').find('input, textarea, button')
		                         .attr('disabled', true);
	}
	jax.doneLoadingFunction = function() {
		joms.jQuery('#cWindowAction').removeClass('loading');
		joms.jQuery('#cWindowContent').find('input, textarea, button')
		                         .attr('disabled', false);
	};
}

function cGetZIndexMax()
{
	var allElems = document.getElementsByTagName?
	document.getElementsByTagName("*"):
	document.all; // or test for that too
	var maxZIndex = 0;

	for(var i=0;i<allElems.length;i++) {
		var elem = allElems[i];
		var cStyle = null;
		if (elem.currentStyle) {cStyle = elem.currentStyle;}
		else if (document.defaultView && document.defaultView.getComputedStyle) {
			cStyle = document.defaultView.getComputedStyle(elem,"");
		}

		var sNum;
		if (cStyle) {
			sNum = Number(cStyle.zIndex);
		} else {
			sNum = Number(elem.style.zIndex);
		}
		if (!isNaN(sNum)) {
			maxZIndex = Math.max(maxZIndex,sNum);
		}
	}	
	return maxZIndex;
}