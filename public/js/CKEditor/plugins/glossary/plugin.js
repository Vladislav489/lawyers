
CKEDITOR.plugins.add( 'glossary', {

	icons: 'glossary',

	init: function( editor ) {

		editor.addCommand( 'glossary', new CKEDITOR.dialogCommand( 'glossaryDialog', {

			allowedContent: 'glossary[title,id]',
			requiredContent: 'glossary',

			contentForms: [
				'term',
				'title'
			]
		} ) );


		// Добваление кнопки
		editor.ui.addButton( 'Glossary', {

			label: 'Термин',
			command: 'glossary',
			toolbar: 'about'
		});


		// Добавление пункта в контекстное меню
		if ( editor.contextMenu ) {

			editor.addMenuGroup( 'glossaryGroup' );
			editor.addMenuItem( 'glossaryItem', {
				label: 'Термин',
				icon: this.path + 'icons/glossary.png',
				command: 'glossary',
				group: 'glossaryGroup'
			});

			editor.contextMenu.addListener( function( element ) {
					return { glossaryItem: CKEDITOR.TRISTATE_OFF };
			});
		}


		CKEDITOR.dialog.add( 'glossaryDialog', this.path + 'dialogs/dialog.js' );
	}
});
