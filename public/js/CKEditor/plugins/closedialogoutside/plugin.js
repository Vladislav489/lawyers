/*
*  The "closedialogoutside" plugin is indented to enhance the
*  "dialog" plugin by adding the functionality of closing (cancel)
*  the dialog if click anywhere outside of the dialog box.
* @author	 	Didrik Gauffin Wohlfarth
* @copyright	Licensed under the MIT license. See LICENSE.txt
*/

CKEDITOR.plugins.add('closedialogoutside');

document.querySelector('body').addEventListener('mousedown', function(evt) {
    if ( evt.target.classList.contains('cke_dialog_background_cover') && CKEDITOR.dialog.getCurrent() ) {
        // Cick outside CKEditor dialog, hide dialog
    	// If there's a Cancel button, click it, else just fire the cancel event and hide the dialog.
        button = CKEDITOR.dialog.getCurrent().getButton( 'cancel' );
		if ( button ) {
			CKEDITOR.tools.setTimeout( button.click, 0, button );
		}
		else {
			if ( CKEDITOR.dialog.getCurrent().fire( 'cancel', { hide : true } ).hide !== false )
			CKEDITOR.dialog.getCurrent().hide();
		}
    }
}, true); // Use Capturing
