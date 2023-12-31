﻿/**
* Copyright (c) 2016-2019, University of Illinois - Jon Gunderson and Nicholas Hoyt.
* All rights reserved. For licensing, see LICENSE.md
*/

CKEDITOR.plugins.add( 'a11yfirsthelp', {
  requires: 'richcombo',

  // jscs:disable maximumLineLength
  lang: 'en,en-au,en-ca,en-gb', // %REMOVE_LINE_CORE%
  // jscs:enable maximumLineLength

  // Test if CKEditor is installed in Drupal by looking at path
  // information specific to the Drupal A11yFirst Module
  isDrupal: function() {
    return this.path.toLowerCase().indexOf('/a11yfirst/js/plugins/') > 0;
  },

  onLoad: function () {
    CKEDITOR.document.appendStyleSheet( this.path + 'styles/a11yfirsthelp.css' );

    // Check to see if drupal stylesheet needs to be loaded
    if (this.isDrupal()) {
      // save path location of use with image references in the help dialog
      CKEDITOR.drupalA11yFirstPath = this.path.split('plugins')[0];
      console.log('loading: ' + this.path + 'styles/a11yfirsthelp-drupal.css');
      CKEDITOR.document.appendStyleSheet( this.path + 'styles/a11yfirsthelp-drupal.css' );
    }
  },

  init: function( editor ) {
    if ( editor.blockless )
      return;

    var allowedContent = [],
        config = editor.config,
        lang = editor.lang.a11yfirsthelp,

        a11yFirstHelpDialogCmd = 'a11yFirstHelpDialog',
        keyboardShortcutsValue = 'keyboardShortcuts',
        keyboardShortcutsCmd   = 'a11yHelp', // defined in a11yhelp plugin by CKSource
        helpTopics = config.a11yFirstHelpTopics,
        helpTopicKeys = Object.keys( helpTopics ),
        menuStyle = new CKEDITOR.style( { element: 'p' } );

    // Load the separator script
    CKEDITOR.scriptLoader.load( this.path + 'js/separator.js' );

    // Load the showdown script
    CKEDITOR.scriptLoader.load( this.path + 'js/showdown.min.js' );

    // Initialize A11yFirst Help dialog and command
    CKEDITOR.dialog.add( a11yFirstHelpDialogCmd, this.path + 'dialogs/a11yfirst-help.js' );
    editor.addCommand( a11yFirstHelpDialogCmd, new CKEDITOR.dialogCommand( a11yFirstHelpDialogCmd ) );

    // Register a11yfirsthelp command
    editor.addCommand( 'allyfirsthelp', {
      allowedContent: allowedContent,
      contextSensitive: false
    } );

    // Create namespaced object for help option
    if (!editor.a11yfirst) {
      editor.a11yfirst = {};
    }

    // Add richcombo button and menu items
    editor.ui.addRichCombo( 'A11yFirstHelp', {
      label: lang.label,
      title: lang.panelTitle,
      toolbar: 'mode,100',
      command: 'allyfirsthelp',
      allowedContent: allowedContent,

      panel: {
        css: [ CKEDITOR.skin.getPath( 'editor' ) ].concat( config.contentsCss ),
        multiSelect: false,
        attributes: { 'aria-label': lang.panelTitle }
      },

      init: function() {
        for ( var i = 0; i < helpTopicKeys.length; i++ ) {
          var key = helpTopicKeys[ i ];
          var label = lang[ key ].menu;
          var title = lang[ key ].title;

          // Add separator between list of help options and other items
          if ( key === 'gettingStarted' )
            this.addSeparator();

          // Add the entry to the panel list
          this.add( key, menuStyle.buildPreview( label ), title );
        }

        /*
        this.add( keyboardShortcutsValue, menuStyle.buildPreview( lang.keyboardShortcutsLabel ),
          lang.keyboardShortcutsLabel );
        */
      },

      onClick: function( value ) {
        if ( value === keyboardShortcutsValue ) {
          editor.execCommand( keyboardShortcutsCmd );
          return;
        }
        // editor.fire( 'saveSnapshot' );
        if (helpTopicKeys.indexOf( value ) !== -1) {
          editor.a11yfirst.helpOption = helpTopics[ value ].option;
          editor.execCommand( a11yFirstHelpDialogCmd );
        }
      }

    } ); // end addRichCombo

  } // end init

} ); // end plugins.add

CKEDITOR.config.a11yFirstHelpTopics = {
  'headingHelp': {
    option:  'HeadingHelp'
  },
  'listHelp': {
    option:  'ListHelp'
  },
  'imageHelp': {
    option:  'ImageHelp'
  },
  'inlineStyleHelp': {
    option:  'InlineStyleHelp'
  },
  'linkHelp': {
    option:  'LinkHelp'
  },
  'gettingStarted': {
    option:  'GettingStarted'
  },
  'aboutA11yFirst': {
    option:  'AboutA11yFirst'
  }
};
