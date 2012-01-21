//DHTML modal dialog box
if (!window.bloggieModalObj)
{
	BloggieModal = function()
	{
		this.options = {
			url: '',
			title: ' ',
			overlay: false,
			htmlOfModalMessage: '',
			width: 400,
			height: 200,
			shadowDivVisible: true,
			shadowOffset: 5,
			MSIE: false,
			cssClassOfMessageBox : 'modalBox_content',
			past_url : ''
		};

		var divs_transparentDiv;
		var divs_content;
		var iframe;
		var existingBodyOverFlowStyle;
		var dynContentObj;
		if(navigator.userAgent.indexOf('MSIE')>=0) this.options.MSIE = true;
	}

	BloggieModal.prototype = {

		setSource : function(urlOfSource)
		{
			this.options.url = urlOfSource;
		},
		setTitle : function(title)
		{
			this.options.title = title;
		},
		setHtmlContent : function(newHtmlContent)
		{
			this.options.htmlOfModalMessage = newHtmlContent;

		},
		setSize : function(width,height)
		{
			if(width)this.options.width = width;
			if(height)this.options.height = height;
		},
		setCssClassMessageBox : function(newCssClass)
		{
			this.options.cssClassOfMessageBox = newCssClass;
			if(this.divs_content){
				if(this.options.cssClassOfMessageBox)
					this.divs_content.className=this.options.cssClassOfMessageBox;
				else
					this.divs_content.className='modalBox_content';
			}
		},
		setShadowOffset : function(newShadowOffset)
		{
			this.options.shadowOffset = newShadowOffset

		},
		display : function()
		{
			if(!this.divs_content){
				this.__createDivs();
			}
			
			//Set Overlay
			if(this.options.overlay) {
				this.divs_transparentDiv.style.display='block';
			}

			// Redisplaying divs
			this.divs_content.style.display='block';
			this.divs_shadow.style.display='block';
			if(this.options.MSIE)this.iframe.style.display='block';
			this.__resizeDivs();
			this.__insertContent();	// Calling method which inserts content into the message div.
		},
		setShadowDivVisible : function(visible)
		{
			this.options.shadowDivVisible = visible;
		},
		close : function()
		{
			if(this.options.overlay){
				this.divs_transparentDiv.style.display='none';
			}

			this.divs_content.style.display='none';
			this.divs_shadow.style.display='none';
			if(this.options.MSIE)this.iframe.style.display='none';
		},
		addEvent : function(whichObject,eventType,functionName,suffix)
		{ 
		  if(!suffix)suffix = '';
		  if(whichObject.attachEvent){ 
			whichObject['e'+eventType+functionName+suffix] = functionName; 
			whichObject[eventType+functionName+suffix] = function(){whichObject['e'+eventType+functionName+suffix]( window.event );} 
			whichObject.attachEvent( 'on'+eventType, whichObject[eventType+functionName+suffix] ); 
		  } else 
			whichObject.addEventListener(eventType,functionName,false); 	    
		},
		__createDivs : function()
		{
			// Creating transparent div
			if(this.options.overlay) {
				this.divs_transparentDiv = document.createElement('DIV');
				this.divs_transparentDiv.className='modalDialog_transparentDivs';
				this.divs_transparentDiv.style.left = '0px';
				this.divs_transparentDiv.style.top = '0px';
				document.body.appendChild(this.divs_transparentDiv);
			}

			// Creating content div
			this.divs_content = document.createElement('DIV');
			this.divs_content.className = 'modalDialog_contentDiv';
			this.divs_content.id = 'modalBox_container';
			this.divs_content.style.zIndex = 100000;

			this.divs_content.innerHTML = '<div id="modalBox_content_loading" class="loading"></div><div class="dialog_title"><a href="#" onclick="closeMessage();return false" class="close">Cancel</a><h2 id="blogModalTitle">'+this.options.title+'</h2></div><div id="modalBox_content"></div>';

			if(this.options.MSIE){
				this.iframe = document.createElement('<IFRAME src="about:blank" frameborder=0>');
				this.iframe.style.zIndex = 90000;
				this.iframe.style.position = 'absolute';
				document.body.appendChild(this.iframe);
			}

			document.body.appendChild(this.divs_content);

			// Creating shadow div
			this.divs_shadow = document.createElement('DIV');
			this.divs_shadow.className = 'modalBox_shadow';
			this.divs_shadow.style.zIndex = 95000;
			document.body.appendChild(this.divs_shadow);
		},
		__getBrowserSize : function()
		{
			var bodyWidth = document.documentElement.clientWidth;
			var bodyHeight = document.documentElement.clientHeight;

			var bodyWidth, bodyHeight; 
			if (self.innerHeight){ // all except Explorer 
			 
			   bodyWidth = self.innerWidth; 
			   bodyHeight = self.innerHeight; 
			}  else if (document.documentElement && document.documentElement.clientHeight) {
			   // Explorer 6 Strict Mode 		 
			   bodyWidth = document.documentElement.clientWidth; 
			   bodyHeight = document.documentElement.clientHeight; 
			} else if (document.body) {// other Explorers 		 
			   bodyWidth = document.body.clientWidth; 
			   bodyHeight = document.body.clientHeight; 
			} 
			return [bodyWidth,bodyHeight];
		},
		__resizeDivs : function()
		{
			if(this.options.cssClassOfMessageBox)
				this.divs_content.className=this.options.cssClassOfMessageBox;
			else
				this.divs_content.className='modalBox_content';

			if(!this.divs_content)return;

			// Preserve scroll position
			var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
			var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);

			window.scrollTo(sl,st);
			setTimeout('window.scrollTo(' + sl + ',' + st + ');',10);

			var brSize = this.__getBrowserSize();
			var bodyWidth = brSize[0];
			var bodyHeight = brSize[1];

			// Setting width and height of content div
			this.divs_content.style.width = this.options.width + 'px';
			this.divs_content.style.height= (this.options.height + 40) + 'px';  
			var tmpWidth = this.divs_content.offsetWidth;
			var tmpHeight = this.divs_content.offsetHeight;

			// Setting width and height of left transparent div
			this.divs_content.style.left = Math.ceil((bodyWidth - tmpWidth) / 2) + 'px';;
			this.divs_content.style.top = Math.ceil((bodyHeight - tmpHeight) / 2) + 'px';

			if(this.options.MSIE){
				this.iframe.style.left = this.divs_content.style.left;
				this.iframe.style.top = this.divs_content.style.top;
				this.iframe.style.width = this.divs_content.style.width;
				this.iframe.style.height = this.divs_content.style.height;
			}
	 
			this.divs_shadow.style.left = (this.divs_content.style.left.replace('px','')/1 + this.options.shadowOffset) + 'px';
			this.divs_shadow.style.top = (this.divs_content.style.top.replace('px','')/1 + this.options.shadowOffset) + 'px';
			this.divs_shadow.style.height = tmpHeight + 'px';
			this.divs_shadow.style.width = tmpWidth + 'px';

			if(!this.options.shadowDivVisible)this.divs_shadow.style.display='none';	// Hiding shadow if it has been disabled
		},
		xhr : function()
		{
			if ('undefined' != typeof XMLHttpRequest) {
				xhr = function () {
					return new XMLHttpRequest();
				}
			} else if ('undefined' != typeof ActiveXObject) {
				xhr = function () {
					try {
						return new ActiveXObject('Msxml2.XMLHTTP.4.0');
					} catch (e) {
						xhr = function () {
							try {
								return new ActiveXObject('Msxml2.XMLHTTP');
							} catch (e2) {
								xhr = function () {
									return new ActiveXObject('Microsoft.XMLHTTP');
								}
								return xhr();
							}
						}
						return xhr();
					}
				}
			} else if (window.createRequest) {
				xhr = function () {
					return window.createRequest();
				}
			} else {
				xhr = function () {
					throw {
						code: 10002
					};
				}
			}
			return xhr();
		},
		ajax : function()
		{
			var xhr = this.xhr();
			if (!xhr) return false;

			//Already Displayed
			if(this.options.past_url == this.options.url) return true

			var divs_loading = document.getElementById('modalBox_content_loading');
			var divs_content = document.getElementById('modalBox_content');
			var divs_title = document.getElementById('blogModalTitle');
			divs_loading.style.display='block';
			divs_content.innerHTML = '';

			xhr.open('GET', this.options.url, true);

			xhr.onreadystatechange = function() {
				if (xhr.readyState != 4) return;
				if (xhr.status==200) {
					divs_title.innerHTML = bloggieModalObj.options.title;
					divs_content.innerHTML = xhr.responseText;
					divs_loading.style.display='none';
					bloggieModalObj.__resizeDivs();
				}
				delete xhr;
				xhr = null;
			};
			try {
				xhr.send(this.options.url);
			} catch(e) {}

			delete xhr;
			this.options.past_url = this.options.url;
			return true;
		},
		__insertContent : function()
		{
			if(this.options.url){	// url specified - load content dynamically
				this.ajax();
			}else{	// no url set, put static content inside the message box
				content = document.getElementById('modalBox_content');
				content.innerHTML = this.options.htmlOfModalMessage;
				this.options.past_url = '';
			}
		}
	}
	var bloggieModalObj = new BloggieModal();

	function closeMessage(){bloggieModalObj.close();}

	function displayMessage(e)
	{
		if(!e.href) return false;

		bloggieModalObj.setSource(e.href);
		if(e.title){
			bloggieModalObj.setTitle(e.title);
		}
		bloggieModalObj.setCssClassMessageBox(false);
		bloggieModalObj.setSize(400,200);
		bloggieModalObj.setShadowDivVisible(true);
		bloggieModalObj.display();
	}

	function displayStaticMessage(messageContent,cssClass)
	{
		bloggieModalObj.setHtmlContent(messageContent);
		bloggieModalObj.setSize(300,150);
		bloggieModalObj.setCssClassMessageBox(cssClass);
		bloggieModalObj.setSource(false);
		bloggieModalObj.setShadowDivVisible(true);
		bloggieModalObj.display();
	}
}