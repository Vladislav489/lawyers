CKEDITOR.dialog.add('SPImageDialog',
function(editor){
  return{
    title:'Insert Image from SharePoint',
    minWidth:400,
    minHeight:200,
    contents:[
      {
        id:'SPimgSettings',
        label:'Image Settings',
        elements:[
          {
            type:'text',
            id:'imgSrc',
            label:'Image Source: *',
            validate:CKEDITOR.dialog.validate.notEmpty('Image Source field cannot be empty')
        },
        {
          type:'button',
          id:'brsButton',
          label:'Browse',
          title:'Browse',
          style: 'float:right',
          onClick: function() {
              var thisDialog = CKEDITOR.dialog.getCurrent(); //get the current instance of the dialog

              with(new AssetPickerConfig('spAssetPickerObj'))
              {{
                 DefaultAssetImageLocation='';
                 CurrentWebBaseUrl= window._spPageContextInfo.webAbsoluteUrl; //obtain the absolute URL of the Sharepoint Site Collection
                 OverrideDialogFeatures='';
                 OverrideDialogTitle='';
                 OverrideDialogDesc='';
                 OverrideDialogImageUrl='';
                 AssetUrlClientID=''; //not needed this time
                 AssetTextClientID='';
                 UseImageAssetPicker=true; //make this false to show Documents
                 DefaultToLastUsedLocation=true;
                 DisplayLookInSection=true;
                 ReturnCallback = function(spi) {
                   thisDialog.setValueOf('SPimgSettings','imgSrc',spi); //set the value of the image source field with the path of the image in SharePoint
                 };
               }}

                APD_LaunchAssetPickerUseConfigCurrentUrl('spAssetPickerObj');

             }
          },
          {
            type: 'text',
            id: 'imgWidth',
            label: 'Width:',
            maxLength: 5,
            inputStyle: 'width:50px',
            validate: function() {
              if (this.getValue()) {
                if(isNaN(parseFloat(this.getValue()))) {
                  alert('Width must be numeric.' );
                  return false;
                }
              }
            }
          },
          {
            type: 'text',
            id: 'imgHeight',
            label: 'Height:',
            maxLength: 5,
            inputStyle: 'width:50px',
            validate: function() {
              if (this.getValue()) {
                if(isNaN(parseFloat(this.getValue()))) {
                  alert('Height must be numeric.' );
                  return false;
                }
              }
            }
          }
      ]
    }
  ],
  onShow:function(){
    //UGLY hack:
    //set Z-INDEX to be just behind the SharePoint modals Z-Index
    $('div.cke_dialog_background_cover').css('z-index', 1501);

    $('table.cke_dialog.cke_ltr.cke_single_page').css('z-index', 1502);
  },
  onOk:function(){
    var dialog = this;
    var imageObj = editor.document.createElement('img');
    imageObj.setAttribute('src', dialog.getValueOf('SPimgSettings', 'imgSrc'));
    if(dialog.getValueOf('SPimgSettings', 'imgHeight') != '') {
      imageObj.setAttribute('height', dialog.getValueOf('SPimgSettings', 'imgHeight'));
    }

    if(dialog.getValueOf('SPimgSettings', 'imgWidth') != '') {
      imageObj.setAttribute('width', dialog.getValueOf('SPimgSettings', 'imgWidth'));
    }

    editor.insertElement(imageObj); //insert the image
  }
}});
