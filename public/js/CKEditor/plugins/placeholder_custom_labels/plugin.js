/**
 * This is a pure modification for the placeholder_select plugin.
 *
 */

CKEDITOR.plugins.add('placeholder_custom_labels',
{
	requires : ['richcombo'],
	init : function( editor )
	{
		//  array of placeholders to choose from that'll be inserted into the editor
		var placeholders = [];

		// init the default config - empty placeholders
		var defaultConfig = {
			format: '[[%placeholder%]]',
			placeholders : []
		};

		// merge defaults with the passed in items
		var config = CKEDITOR.tools.extend(defaultConfig, editor.config.placeholder_custom_labels || {}, true);

		// run through an create the set of items to use
		for (var i = 0; i < config.placeholders.length; i++) {
			// get our potentially custom placeholder format
			var placeholder = config.format.replace('%placeholder%', config.placeholders[i]);
			var value = (config.valuePreffix || '') + placeholder + (config.valueSuffix || '');
			var menu =  (config.menuItemPreffix || '') + value + (config.menuItemSuffix || '');
			placeholders.push([value, menu, value]);
		}

		// add the menu to the editor
		editor.ui.addRichCombo('placeholder_custom_labels',
		{
			label: 		config.title,
			title: 		config.title,
			voiceLabel: config.title,
			className: 	'cke_format',
			multiSelect:false,
			panel:
			{
				css: [].concat(editor.config.contentsCss).concat(CKEDITOR.skin.getPath('editor')),
				voiceLabel: editor.lang.panelVoiceLabel
			},

			init: function()
			{
				for (var i in placeholders)
				{
					this.add(placeholders[i][0], placeholders[i][1], placeholders[i][2]);
				}
			},

			onClick: function( value )
			{
				editor.focus();
				editor.fire( 'saveSnapshot' );
				editor.insertHtml(value);
				editor.fire( 'saveSnapshot' );
			}
		});
	}
});
