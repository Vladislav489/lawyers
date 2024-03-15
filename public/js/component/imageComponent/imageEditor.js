/*  const locale_ru_RU = {
                            // override default English locale to your custom
                            Crop: 'Обзрезать',
                            'Delete-all': 'Удалить всё',
                            // etc...
                        }*/
class imageEditor {
    static currentId = 0;
    _id = ++imageEditor.currentId;

    get idClass(){return this._id;}
    constructor(option) {
        this.defaultOption = {
            includeUI: {
                locale: 'locale_ru_RU',
                theme: whiteTheme, // or whiteTheme
                initMenu: 'filter',
                menuBarPosition: 'left',
            },
            cssMaxWidth: 700,
            cssMaxHeight: 500,
            usageStatistics: false,
        }

        this.fileEditor  = null;

        this.ImageEditor = null;
        this.option = option;
        this.dialog    = (this.option['dialog'] != undefined) ? this.option['dialog'] : null;
        this.name_component = (this.option['name_component'] != undefined) ? this.option['name_component'] : {};
        this.targetReturnData = (this.option['targetReturnData'] != undefined) ? this.option['targetReturnData'] : {};
        this.id = this.option['name']
        this.optionImageEditor = (this.option['option'] != undefined) ? this.option['option'] : this.defaultOption;
        this.init();
    }

    init(){
        var $this = this;
        this.ImageEditor = new tui.ImageEditor('#'+this.id, this.optionImageEditor);
        var img = this.ImageEditor;
        window.onresize = function () {
            $this.ImageEditor.ui.resizeEditor();
        };

        this.ImageEditor.loadImageFromURL = (function() {
            var cached_function = $this.ImageEditor.loadImageFromURL;
            function waitUntilImageEditorIsUnlocked(img) {
                return new Promise((resolve,reject)=>{
                    const interval = setInterval(()=>{
                        if (!$this.ImageEditor._isLocked) {
                            clearInterval(interval);
                            resolve();
                        }
                    }, 100);
                })
            }
            return function() {
                return waitUntilImageEditorIsUnlocked($this.ImageEditor).then(()=>cached_function.apply(this, arguments));
            };
        })();
    }
    setFileToEdit(file){
        var $this = this;
        console.log(file);
        this.fileEditor = file
        this.ImageEditor.loadImageFromURL(
            file.dataURL,file.name).then(result => {
            $this.ImageEditor.ui.resizeEditor({
                imageSize: {oldWidth: result.oldWidth,
                    oldHeight: result.oldHeight,
                    newWidth: result.newWidth,
                    newHeight: result.newHeight
                },
            });
            $this.ImageEditor.ui.activeMenuEvent();
        })

        if(this.dialog !== undefined){
            $("#"+this.dialog).modal('toggle')
        }
    }

    clear(){this.ImageEditor.clearObjects()}

    getOldFile(){return this.fileEditor}

    getFileFromEdit(){
       if(this.targetReturnData != undefined) {
           page__.object[this.targetReturnData]['obj'].files[parseInt(this.fileEditor['index'])].dataURL = this.ImageEditor.toDataURL();
           var File = page__.object[this.targetReturnData]['obj'].files;
           console.log(File);
           page__.object[this.targetReturnData]['obj'].removeAllFiles();
           for (key in File ){

               page__.object[this.targetReturnData]['obj'].files.push(File[key]);
               page__.object[this.targetReturnData]['obj'].displayExistingFile(File[key],File[key].dataURL);
           }


           $("#"+this.dialog).modal('hide');
       }
       return { 'dataURL':this.ImageEditor.toDataURL(),"name":this.fileEditor.name};
    }
}




