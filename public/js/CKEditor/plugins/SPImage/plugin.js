CKEDITOR.plugins.add('SPImage',
  {
    icons:'SPImage',
    init: function(editor) {
      editor.addCommand('SPImage',new CKEDITOR.dialogCommand('SPImageDialog'));
      editor.ui.addButton('SPImage', {label:editor.lang.common.image,command:'SPImage',toolbar:'insert'});
      CKEDITOR.dialog.add('SPImageDialog',this.path+'dialogs/SPImage.js')
    }
  }
);
