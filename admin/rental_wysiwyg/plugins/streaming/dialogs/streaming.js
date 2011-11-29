/*
<stream provider="1" file="avatar.flv" type="rtmp" contentProvider="91" width="800" height="600"></stream>
*/

CKEDITOR.dialog.add( 'streaming', function ( editor )
{
	var lang = editor.lang.video;
	var streamProvider = false;
	var streamContentProvider = false;
	function commitValue( videoNode, extraStyles )
	{
		var value=this.getValue();

		if ( !value ) {
			if ( this.id=='id' ) {						
				value = generateId();				
			} else {				
				return false;
			}
		}		

		videoNode.setAttribute(this.id, value);
		
		switch( this.id )
		{
			case 'width':
				extraStyles.width = value + 'px';
				break;
			case 'height':
				extraStyles.height = value + 'px';
				break;
		}
		
	}

	function loadValue( videoNode )
	{
		if ( videoNode ) {
			this.setValue( videoNode.getAttribute( this.id ) );
		} else {
			if ( this.id == 'id')
				this.setValue( generateId() );
		}
	}
	/*
	function onChange( videoNode )
	{

	}
	*/

	function generateId()
	{
		var now = new Date();
		return 'video' + now.getFullYear() + now.getMonth() + now.getDate() + now.getHours() + now.getMinutes() + now.getSeconds();
	}

	// To automatically get the dimensions of the poster image
	var onImgLoadEvent = function()
	{
		// Image is ready.
		var preview = this.previewImage;
		preview.removeListener( 'load', onImgLoadEvent );
		preview.removeListener( 'error', onImgLoadErrorEvent );
		preview.removeListener( 'abort', onImgLoadErrorEvent );

		this.setValueOf( 'info', 'width', preview.$.width );
		this.setValueOf( 'info', 'height', preview.$.height );
	};

	var onImgLoadErrorEvent = function()
	{
		// Error. Image is not loaded.
		var preview = this.previewImage;
		preview.removeListener( 'load', onImgLoadEvent );
		preview.removeListener( 'error', onImgLoadErrorEvent );
		preview.removeListener( 'abort', onImgLoadErrorEvent );
	};

	return {
		title : lang.dialogTitle,
		minWidth : 400,
		minHeight : 200,

		onShow : function()
		{			
			// Clear previously saved elements.
			this.fakeImage = this.videoNode = null;
			// To get dimensions of poster image
			this.previewImage = editor.document.createElement( 'img' );

			var fakeImage = this.getSelectedElement();			
			
			if ( fakeImage && fakeImage.data( 'cke-real-element-type' ) && fakeImage.data( 'cke-real-element-type' ) == 'streaming' )
			{
				this.fakeImage = fakeImage;

				var videoNode = editor.restoreRealElement( fakeImage );
				videoNode.setAttribute('width', parseInt(fakeImage.getStyle('width')));
				videoNode.setAttribute('height', parseInt(fakeImage.getStyle('height')));

				this.videoNode = videoNode;

				this.setupContent( videoNode, [] );
				
				//$('#cke_106_select').trigger('change');
			}
			else
				this.setupContent( null, [] );
			
			
		},

		onOk : function()
		{
			// If there's no selected element create one. Otherwise, reuse it
			var videoNode = null;
			if ( !this.fakeImage )
			{
				videoNode = CKEDITOR.dom.element.createFromHtml( '<cke:stream></cke:stream>', editor.document );
				videoNode.setAttributes(
					{
						/*
						'class':	'ckeSteaming'
						'style':	"display:block;width:500px;height:350px;margin-left:auto;margin-right:auto"*/
					} );
			}
			else
			{
				videoNode = this.videoNode;
			}

			
			var extraStyles = {};
			this.commitContent( videoNode, extraStyles );
			
		   extraStyles.display = 'block';
		   videoNode.setStyles( extraStyles );
		   
			// Refresh the fake image.
			var newFakeImage = editor.createFakeElement( videoNode, 'cke_streaming', 'streaming', false );
			newFakeImage.setStyles( extraStyles );
			
			if ( this.fakeImage )
			{
				newFakeImage.replace( this.fakeImage );
				editor.getSelection().selectElement( newFakeImage );
			}
			else
				editor.insertElement( newFakeImage );
		},
		onHide : function()
		{
			if ( this.previewImage )
			{
				this.previewImage.removeListener( 'load', onImgLoadEvent );
				this.previewImage.removeListener( 'error', onImgLoadErrorEvent );
				this.previewImage.removeListener( 'abort', onImgLoadErrorEvent );
				this.previewImage.remove();
				this.previewImage = null;		// Dialog is closed.
			}
		},

		contents :
		[
			{
				id : 'info',
				elements :
				[
					{
						type : 'select',
						id : 'provider',
						items :
						[
							[ 'Please Select', '' ]
						],
						label : 'Stream Provider',
						commit : commitValue,
						setup : function (videoNode){
							//loadValue(videoNode);
							if ( videoNode ) {
								this.setValue( videoNode.getAttribute( this.id ) );
								var oldStreamProviderValue = videoNode.getAttribute('provider');
								var oldStreamTypeValue = videoNode.getAttribute('type');
								//alert(oldStreamProviderValue);
								//alert(oldStreamTypeValue);
							} else {
								if ( this.id == 'id')
									this.setValue( generateId() );
							}
							var ctr = 0;
							$('.cke_dialog_contents').find('select').each(function (){
								switch(ctr){
									case 0:
										streamProviders = $(this);

									break;
									case 1:
										streamContentProvider = $(this);
									break;
								}
								ctr++;
							});
							$.ajax({
									cache: false,
									dataType: 'html',
									url: js_app_link('app=products&appPage=new_product&action=getStreamProviders'),
									success: function (data){
										streamProviders.html($(data).html());
										streamProviders.val(oldStreamProviderValue);
										
										$.ajax({
											cache: false,
											dataType: 'html',
											url: js_app_link('app=products&appPage=new_product&action=getProviderStreamTypes&pID=' + streamProviders.val()),
											success: function (data){
												streamContentProvider.html($(data).html());
												streamContentProvider.val(oldStreamTypeValue);
											}
										});

									}
							});

						}
					},
					
					{
						type : 'select',
						items : [[ 'Please choose stream provider first', '' ]],
						id : 'type',
						label : 'Stream Type',
						commit : commitValue,
						setup : loadValue
					},
					
					{
						type : 'text',
						'default' : '',
						id : 'file',
						label : 'Stream File',
						commit : commitValue,
						setup : loadValue
					},					
																
					{
						type : 'text',
						'default': 320,
						id : 'width',
						label : 'Width',						
						commit : commitValue,
						setup : loadValue
					},
					
					{
						type : 'text',
						'default' : '240',
						id : 'height',
						label : 'Height',
						commit : commitValue,
						setup : loadValue
					},					
											
					{
						type : 'text',
						'default' : '',
						id : 'id',
						label : 'ID',
						commit : commitValue,
						setup : loadValue
					}
					
				]
			}

		]
	};
} );