/* based on xajax Javascript library (http://www.xajaxproject.org) */
if (!window.blogajax)
{
	function blogAJAX()
	{
		this.loader = '';
		this.options = {url: '', type: 'get', nocache: true, data: ''};

		this.$ = function(id) {if(!id){return null;}var o=document.getElementById(id);if(!o&&document.all){o=document.all[id];}return o;};
		this.extend = function(o, e){for(var k in (e||{}))o[k]=e[k];return o;};
		this.encode = function(t){return encodeURIComponent(t);};
		this.setup = function(options) {this.options = this.extend(this.options, options);};

		this.xhr = function () {
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
		}
		
		this.form2query = function(sId)
		{
			var frm = this.$(sId);
			if (frm && frm.tagName.toUpperCase() == 'FORM') {
				var e = frm.elements, params = [];
				for (var i=0; i < e.length; i++) {
					var name = e[i].name, value;
					if (!name) continue;
					if (e[i].type && ('radio' == e[i].type || 'checkbox' == e[i].type) && false === e[i].checked) continue;
					if ('select-multiple' == e[i].type) {
						for (var j = 0; j < e[i].length; j++) {
							if (true === e[i].options[j].selected)
								params.push(name+'='+this.encode(e[i].options[j].value));
						}
					} else { 
						params.push(name+'='+this.encode(e[i].value));
					}
				}
				this.loader = 'modalBox_content_loading';
				this.ajax({type: 'get', data: params.join('&')});
			}
			return false;
		};

		this.startLoading = function()
		{
			if(this.loader)
			{
				ldLoader = document.getElementById(this.loader);
				ldLoader.style.display='block';
			}
		};
		
		this.finishLoading = function()
		{
			if(this.loader)
			{
				ldLoader = document.getElementById(this.loader);
				ldLoader.style.display='none';			
			}
		};

		this.ajax = function(options)
		{
			var xhr = this.xhr();
			if (!xhr) return false;
			var o = this.extend(this.options, options);
			var url = o.url, blog = this;

			if ('get' == o.type) {
				if (o.data) {
					url += (url.indexOf("?")==-1 ? '?' : '&') + 'option=com_lyftenbloggie&task=ajax&' + o.data;
					o.data = null;
				}
			}

			xhr.open(o.type.toUpperCase(), url, true);

			if ('post' == o.type)
				try {xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");}catch(e){}
			if (true === o.nocache)
				xhr.setRequestHeader('If-Modified-Since', 'Thu, 01 Jan 1970 00:00:00 GMT');

			xhr.onreadystatechange = function() {
				if (xhr.readyState != 4) return;
				blog.finishLoading();
				if (xhr.status==200) {
					blog.processResponse(xhr.responseText);
				}
				delete xhr;
				xhr = null;
			};

			try {
				blog.startLoading();
				xhr.send(o.data);
			} catch(e) { blog.finishLoading(); }

			delete blog;
			delete xhr;
			delete o;
			return true;
		};

		this.call = function(sFunction, aArgs, loader, sType, sForm)
		{
			var params = 'act=' + this.encode(sFunction);
			if (aArgs) {
				for (var key in aArgs) {
					params += '&param['+key+']='+this.encode(aArgs[key]);
				}
			} else if (sForm) {
				params += '&' + this.form2query(sForm);
			}

			if (!sType) {
				sType = 'get';
			}

			this.loader = loader;

			this.ajax({type: sType, data: params});
			return true;
		};

		this.processResponse = function(sText)
		{
			if(sText==='') return false;
			if(sText.substring(0,3)!='[ {'){var idx=sText.indexOf('[ {');sText=sText.substr(idx);}
			var result;try {result=eval(sText);}catch(e){}
			if ('undefined' == typeof result) {return false;}

			var cmd, id, property, data, obj = null;

			for (var i=0;i<result.length;i++)
			{
				cmd 		= result[i]['n'];
				id 			= result[i]['t'];
				property	= result[i]['p'];
				data 		= result[i]['d'];
				obj 		= this.$(id);

				switch(cmd) {
					case 'as': if(obj){eval("obj."+property+"=data;");} break;
					case 'al': if(data){alert(data);} break;
					case 'js': if(data){eval(data);} break;
					default: this.error('Unknown command: ' + cmd);break;
				}
			}
			delete result;
			delete cmd;
			delete id;
			delete property;
			delete data;
			delete obj;
			return true;
		};

		this.error = function(){};
	}
	var blogajax = new blogAJAX();
}