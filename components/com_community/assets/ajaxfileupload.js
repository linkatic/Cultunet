joms.jQuery.extend({

    createUploadIframe: function(id, uri)
	{
			//create frame
            var frameId = 'jUploadFrame' + id;
            
            if(window.ActiveXObject) {
                var io = document.createElement('<iframe id="' + frameId + '" name="' + frameId + '" />');
                if(typeof uri== 'boolean'){
                    io.src = 'javascript:false';
                }
                else if(typeof uri== 'string'){
                    io.src = uri;
                }
            }
            else {
                var io = document.createElement('iframe');
                io.id = frameId;
                io.name = frameId;
            }
            io.style.position = 'absolute';
            io.style.top = '-1000px';
            io.style.left = '-1000px';

            document.body.appendChild(io);

            return io			
    },
    createUploadForm: function(id, fileElementId)
	{
		//create form	
		var formId = 'jUploadForm' + id;
		var fileId = 'jUploadFile' + id;
		
		var form = joms.jQuery('<form  action="" method="POST" name="' + formId + '" id="' + formId + '" enctype="multipart/form-data"></form>');	
		var oldElement = joms.jQuery('#' + fileElementId);
		var newElement = joms.jQuery(oldElement).clone();
		joms.jQuery(oldElement).attr('id', fileId);
		joms.jQuery(oldElement).before(newElement);
		joms.jQuery(oldElement).appendTo(form);
		//set attributes
		joms.jQuery(form).css('position', 'absolute');
		joms.jQuery(form).css('top', '-1200px');
		joms.jQuery(form).css('left', '-1200px');
		joms.jQuery(form).appendTo('body');

		return form;
    },

    ajaxFileUpload: function(s) {
        // TODO introduce global settings, allowing the client to modify them for all requests, not only timeout		
        s			= joms.jQuery.extend({}, joms.jQuery.ajaxSettings, s);
        var id		= new Date().getTime()        
		var form	= joms.jQuery.createUploadForm(id, s.fileElementId);
		var io 		= joms.jQuery.createUploadIframe(id, s.secureuri);
		var frameId = 'jUploadFrame' + id;
		var formId	= 'jUploadForm' + id;		
        
		// Watch for a new set of requests
        if ( s.global && ! joms.jQuery.active++ )
		{
			joms.jQuery.event.trigger( "ajaxStart" );
		}            
        var requestDone = false;
        
		// Create the request object
        var xml = {}   
        if ( s.global )
            joms.jQuery.event.trigger("ajaxSend", [xml, s]);
        
		// Wait for a response to come back
        var uploadCallback = function(isTimeout)
		{			
			var io = document.getElementById(frameId);
            try
			{
				if(io.contentWindow)
				{
					 xml.responseText = io.contentWindow.document.body?io.contentWindow.document.body.innerHTML:null;
                	 xml.responseXML = io.contentWindow.document.XMLDocument?io.contentWindow.document.XMLDocument:io.contentWindow.document;
					 
				}
				else if(io.contentDocument)
				{
					xml.responseText = io.contentDocument.document.body?io.contentDocument.document.body.innerHTML:null;
                	xml.responseXML = io.contentDocument.document.XMLDocument?io.contentDocument.document.XMLDocument:io.contentDocument.document;
				}						
            }
			catch(e)
			{
				joms.jQuery.handleError(s, xml, null, e);
			}
			
            if ( xml || isTimeout == "timeout") 
			{				
                requestDone = true;
                var status;
                try
				{
                    status = isTimeout != "timeout" ? "success" : "error";
                    
					// Make sure that the request was successful or notmodified
                    if ( status != "error" )
					{
                        // process the data (runs the xml through httpData regardless of callback)
                        var data = joms.jQuery.uploadHttpData( xml, s.dataType );    

						// If a local callback was specified, fire it and pass it the data
                        if ( s.success )
                        {
                        	s.success( data, status );
						}

                        // Fire the global callback
                        if( s.global )
                        {
                        	joms.jQuery.event.trigger( "ajaxSuccess", [xml, s] );
						}                            
                    }
					else
					{
						joms.jQuery.handleError(s, xml, status);
					}
                }
				catch(e) 
				{
                    status = "error";
                    joms.jQuery.handleError(s, xml, status, e);
                }

                // The request was completed
                if( s.global )
                    joms.jQuery.event.trigger( "ajaxComplete", [xml, s] );

                // Handle the global AJAX counter
                if ( s.global && ! --joms.jQuery.active )
                    joms.jQuery.event.trigger( "ajaxStop" );

                // Process result
                if ( s.complete )
                    s.complete(xml, status);

                joms.jQuery(io).unbind()

                setTimeout(function()
									{	try 
										{
											joms.jQuery(io).remove();
											joms.jQuery(form).remove();	
											
										} catch(e) 
										{
											joms.jQuery.handleError(s, xml, null, e);
										}									

									}, 100)

                xml = null

            }
        }
        // Timeout checker
        if ( s.timeout > 0 ) 
		{
            setTimeout(function(){
                // Check to see if the request is still happening
                if( !requestDone ) uploadCallback( "timeout" );
            }, s.timeout);
        }
        try 
		{
           // var io = $('#' + frameId);
			var form = joms.jQuery('#' + formId);
			joms.jQuery(form).attr('action', s.url);
			joms.jQuery(form).attr('method', 'POST');
			joms.jQuery(form).attr('target', frameId);
            if(form.encoding)
			{
                form.encoding = 'multipart/form-data';				
            }
            else
			{				
                form.enctype = 'multipart/form-data';
            }			
            joms.jQuery(form).submit();

        } catch(e) 
		{			
            joms.jQuery.handleError(s, xml, null, e);
        }
        if(window.attachEvent){
            document.getElementById(frameId).attachEvent('onload', uploadCallback);
        }
        else{
            document.getElementById(frameId).addEventListener('load', uploadCallback, false);
        } 		
        return {abort: function () {}};	

    },

    uploadHttpData: function( r, type ) {
        var data = !type;
        
        data	= type == "xml" || data ? r.responseXML : r.responseText;
        
		// If the type is "script", eval it in global context
        if ( type == "script" )
        {
        	joms.jQuery.globalEval( data );
		}
            
        // Get the JavaScript object, if JSON is used.
        if ( type == "json" )
		{
			//alert(data);
            eval( "data = " + data );
        }

        // evaluate scripts within html
        if ( type == "html" )
        {
        	joms.jQuery("<div>").html(data).evalScripts();
		}

        return data;
    }
});

joms.uploader = {
	startIndex: 0,
	postUrl: '',
	originalPostUrl : '',
	uploadText: '',
	addNewUpload: function(){
		this.startIndex	+= 1;
		
		var html	= joms.jQuery('#photoupload').clone();
		html		= joms.jQuery(html).attr('id', 'photoupload-' + this.startIndex  ).css('display','block');

		// Apend data into the container
		joms.jQuery('#photoupload-container').append( html );
	
	 	// Set the input id correctly
	 	joms.jQuery('#photoupload-' + this.startIndex + ' :file').attr('id', 'Filedata-' + this.startIndex );
	 	joms.jQuery('#photoupload-' + this.startIndex + ' :file').attr('name', 'Filedata-' + this.startIndex );
	 	joms.jQuery( '#photoupload-' + this.startIndex + ' :input:hidden' ).attr('value' , this.startIndex );

		// Bind remove function
	 	joms.jQuery( '#photoupload-' + this.startIndex + ' .remove' ).bind( 'click' , function(){
	 		joms.jQuery( this ).parent().remove();
	 	} );
		  	
	},
	startUpload: function() {
		var currentIndex	= joms.jQuery('#photoupload').next().find('.elementIndex').val();

		// If this is called, we need to disable the upload button so that no duplicates will happen.
		joms.jQuery( '#upload-photos-button' ).hide();	
		joms.jQuery( '.add-new-upload' ).hide();
		joms.jQuery('#photoupload-container input').filter(function(){return joms.jQuery(this).parent().css('display') == 'block';}).attr('disabled',true);
		joms.uploader.upload( currentIndex );
	},
	upload: function ( elementIndex ){
		joms.jQuery('#Filedata-' + elementIndex).attr('disabled', false );
		
		if( joms.jQuery('#Filedata-' + elementIndex).val() == '' )
		{
			joms.jQuery( '#photoupload-' + elementIndex ).remove();
			joms.uploader.upload();

			// Test if there is a form around if it doesn't add a new form.
			if( joms.jQuery('#photoupload').next().length == 0 )
			{
				joms.uploader.addNewUpload();
			}
			else
			{
				joms.jQuery('#photoupload-container input').filter(function(){return joms.jQuery(this).parent().css('display') == 'block';}).attr('disabled',false);
			}
			joms.jQuery( '#upload-photos-button' ).show();

			joms.jQuery( '#new-upload-button' ).show();
			return;
		}

		// Revert to original path
		joms.uploader.postUrl = joms.uploader.originalPostUrl;
		
		// Check whether photo uploaded is set to be the default.
		var defaultPhoto	= (joms.jQuery('#photoupload-' + elementIndex + ' :input:checked').val() == "1" ) ? '1' : '0';
		this.postUrl = this.postUrl.replace('DEFAULT_PHOTOS', defaultPhoto);

		// Get the next upload id so it can pass back to this function again
		var nextUpload		= joms.jQuery( '#photoupload-' + elementIndex ).next().find('.elementIndex').val();
		nextUpload			= (nextUpload != '' ) ? nextUpload : 'undefined';
		this.postUrl = this.postUrl.replace('NXUP', nextUpload);

		// Hide existing form and whow a loading image so the user knows it's uploading.
		joms.jQuery('#photoupload-' + elementIndex ).children().each(function(){ 
			joms.jQuery(this).css('display','none');
		} );
		
		joms.jQuery('#photoupload-' + elementIndex ).append('<div id="photoupload-loading-' + elementIndex + '"><span class="loading" style="display:block;float: none;margin: 0px;"></span><span>' + joms.uploader.uploadText + '</span></div>');
		
		joms.jQuery.ajaxFileUpload({
				url: this.postUrl,
				secureuri:false,
				fileElementId:'Filedata-' + elementIndex,
				dataType: 'json',
				success: function (data, status){									   
					// Hide the loading class because it was added before the upload started.
					joms.jQuery( '#photoupload-loading-' + elementIndex ).remove();

					if(typeof(data.error) != 'undefined' && data.error == 'true' )
					{
						// Show nice red background stating error
						joms.jQuery( '#photoupload-' + elementIndex ).css('background', '#ffeded');
	
						// There was an error during the post, show the error message the user.
						joms.jQuery( '#photoupload-' + elementIndex).append( '<span class="error">' + data.msg + '</span>' );
					}
					else
					{
						// Upon success post to the site, we need to add some status.
						joms.jQuery( '#photoupload-' + elementIndex ).css('background', '#edfff3');
						joms.jQuery( '#photoupload-' + elementIndex ).append( '<span class="success">' + data.msg + '</span>');

                                                //Show uploaded photos
                                                joms.jQuery('#community-photo-items').show();

                                                joms.jQuery(new Image()).attr('src', data.thumbUrl)
                                                        .appendTo('#community-photo-items div.container')
                                                        .wrap('<div class="photo-item" />');
					}

					// Fadeout existing upload form
					joms.jQuery( '#photoupload-' + elementIndex).fadeOut( 4500 , function() {
						joms.jQuery( '#photoupload-' + elementIndex ).remove();
		
						// Test if there is a form around if it doesn't add a new form.
						if( joms.jQuery('#photoupload').next().length == 0 )
						{
							joms.uploader.addNewUpload();
						}
					});

					// Show the remove button
					joms.jQuery( '#photoupload-' + elementIndex + ' .remove').css('display','block');
					
					if( data.nextupload != 'undefined' )
					{
						joms.uploader.upload( data.nextupload );
						return;
					}
					else
					{
						joms.jQuery( '#upload-photos-button' ).show();	
						joms.jQuery( '#new-upload-button' ).show();
					}

				},
				error: function (data, status, e){
	// 				var names = '';
	// 				
	// 				for(var name in data)
	// 					names += name + "\n";
	// 				
	// 				alert(names);
	// 				alert(e.description);
				}
			}
		)
		return false;
	}
}

