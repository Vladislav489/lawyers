/**
 * Basic sample plugin inserting abbreviation elements into CKEditor editing area.
 */

// Register the plugin with the editor.
// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.plugins.html
CKEDITOR.plugins.add( 'hkurlsplit',
{
	// The plugin initialization logic goes inside this method.
	// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.pluginDefinition.html#init
	init: function( editor )
	{
		// Define an editor command that inserts an abbreviation.
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.editor.html#addCommand
		editor.addCommand( 'abbrDialog11',new CKEDITOR.dialogCommand( 'abbrDialog11' ) );
		// Create a toolbar button that executes the plugin command.
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.html#addButton
		editor.ui.addButton( 'HkUrlSplit',
		{
			// Toolbar button tooltip.
			label: 'Url Split',
			// Reference to the plugin command name.
			command: 'abbrDialog11',
			// Button's icon file path.
			icon: this.path + 'images/timestamp.png'
		} );
		// Add a dialog window definition containing all UI elements and listeners.
		// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.html#.add
		CKEDITOR.dialog.add( 'abbrDialog11', function ( editor )
		{
			return {
				// Basic properties of the dialog window: title, minimum size.
				// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.dialogDefinition.html
				title : 'Url Split',
				minWidth : 290,
				minHeight : 80,
				// Dialog window contents.
				// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.definition.content.html
				contents :
				[
					{
						// Definition of the Basic Settings dialog window tab (page) with its id, label, and contents.
						// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.contentDefinition.html
						id : 'tab1',
						label : 'Basic Settings',
						elements :
						[
							{
								// Dialog window UI element: a text input field for the abbreviation text.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.textInput.html
								type : 'text',
								id : 'abbr',
								// Text that labels the field.
								// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.ui.dialog.labeledElement.html#constructor
								label : 'Insert links',
								// Validation checking whether the field is not empty.
								validate : CKEDITOR.dialog.validate.notEmpty( "URL Not be empty" )
							}

						]
					},

				],
				// This method is invoked once a user closes the dialog window, accepting the changes.
				// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.dialogDefinition.html#onOk
				onOk : function()
				{
					// A dialog window object.
					// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.dialog.html
					var dialog = this;
                    var vaid_url = dialog.getValueOf( 'tab1', 'abbr' );
                    var partsOfStr = vaid_url.split('/');
                    var website = partsOfStr[0]+'//'+partsOfStr[2];
                    var ic;
                    var beurl='';
                            for (ic = 3; ic <= partsOfStr.length-1; ic++) {
                                beurl += partsOfStr[ic] + "/";
                                }
                    var url = beurl.substring(0,beurl.length-1);
					editor.insertHtml( 'Website : '+website+ '<br> URL: /' + url );
				}
			};
		} );
	}
} );
