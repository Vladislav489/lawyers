/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
   palagin = [

      /*main*/
     //'component',
      'indent', 'forms','widget',
      'format', 'clipboard', 'wysiwygarea',
      'htmlwriter', 'sourcearea', 'justify',
      'editorplaceholder','elementspath','widgetselection',
      'basicstyles','stylesheetparser', 'stylesheetparser-fixed',
      'widgetcontextmenu','basewidget','codemirror',

   //    'component',

      /*tag*/
      'div','iframe','image', 'listblock',
      'link','font', 'label', 'list',
      'tab', 'table', 'pre','button','yaqr',
      'blockquote','spotify','tliyoutube2', 'emojione',

      /*hideen work*/
      'enterkey','entities',

      'dialogui','dialog','dialogadvtab','colordialog','texzilla',
      'iframedialog','closedialogoutside',

      'indentblock', 'indentlist',

      /*group work*/
      'panelbutton', 'panel','toolbar','tabletools',
      'richcombo','format_buttons',

      'xml', 'ajax', 'templates', 'menu',

       /*function*/
      'zoom','contextmenu', 'notification','magicline',
      'menubutton','maximize','find','floatingspace',
      'crossreference','horizontalrule','autogrow',
      'spacingsliders', 'sketchfab', 'embedsemantic',
      'savemarkdown','openlink',  'inserthtmlfile',
      'tableresize', 'tableresizerowandcolumn','prism',
      'wenzgmap','ckeditor_wiris', 'extraformattributes',
      'FMathEditor',   'docfont',

       'newpage','exportpdf','fixed',


      'liststyle','language','wordcount',
      /*image*/
       'niftyimages', 'fakeobjects', 'filebrowser',
       'tabbedimagebrowser','base64image', 'imagepaste',
       'imageresize',

      'pastetext', 'pastetools', 'pastefromword', 'pastefromgdocs', 'pastefromlibreoffice',
      'selectallcontextmenu',
/*'uploadwidget',*/
       'symbol',
      'soundPlayer', 'html5audio',
      'videodetector', 'layoutmanager',
      'uicolor', 'leaflet', 'html5video',

      'ckeditor-gwf-plugin',

       'balloontoolbar','colorinput',
       'blockimagepaste', 'btbutton',
       'bt_table', 'btgrid', 'brclear', 'chart',

       'confighelper',

       'googleDocPastePlugin', 'googledocs', 'googlesearch', 'googlethisterm',

       'insertpre', 'numericinput', 'tangy-input',
       'component'
   ],
    config.allowedContent = {};
    config.tabSpaces = 0;
    config.fillEmptyBlocks = function (element) {
        return true; // DON'T DO ANYTHING!!!!!
    };
    config.enterMode = CKEDITOR.ENTER_BR;
    config.entities = false;
    config.basicEntities = false;
    config.plugins =  palagin.join(',')
	config.language = 'ru';
    config.height = 700 +'px';
    config.language_list = [ 'en:English', 'es:Spanish','ru:Русский' ];
	// config.uiColor = '#AADC6E';
};
