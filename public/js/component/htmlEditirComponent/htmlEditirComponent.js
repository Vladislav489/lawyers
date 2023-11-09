class HtmlEditor {
    constructor(contaner,option) {
        this.contaner           = $(contaner);
        this.teplateData        = (option['template'] !== undefined) ?option['template'] :"";
        this.CKEDITOR__         = null;
        this.url_buld_view      = option['url_buld_view'];
        this.id                 = (option['name'] !== undefined) ?option['name']:"NoName";
        this.ConnectElement     = (option['ConnectElement'] !== undefined)? option['ConnectElement']:null;
        this.modeStatus         = false;
        this.globalparams       = (option['params'] !== undefined)? option['params']:{};
        this.UrlForComponent    = (option['UrlForComponent'] !== undefined)? option['UrlForComponent']:null;
        this.AfterInit          = (option['callAfterInit'] !== undefined)? option['callAfterInit']:null;
    }

    buildView(){
        var $this = this;
        if(this.url_buld_view !== undefined){
            var sendData = parserUrlFromString(location.search);
            sendData['template'] = $this.teplateData;
            $.ajax({url:$this.url_buld_view, type:'post',
                async:false, data:sendData, dataType:"html",
                success:function (data) {
                    CKEDITOR.instances[$this.id + '_editor'].setData(data)
                    $this.teplateData['view'] = data;
                }
            });
        }
    }


    cutStyleScriptMetaSystem(textEditor){
        var ListAnchor = {
            'meta':['meta-start','meta-end'],
            'style':['style-start','style-end'],
            'script_lib':['js-lib-start','js-lib-end'],
            'js-lib-component':['js-lib-component-start','js-lib-component-end'],
            'js-code-component':['js-code-component-start','js-code-component-end'],
            'js-code-component-load':['js-code-component-load-start','js-code-component-load-end'],
            'breadcrumbs':['breadcrumbs-start','breadcrumbs-end'],
            'js-lib-component-head':['js-lib-component-head-start','js-lib-component-head-end']
        }

        for(var key in ListAnchor){
            var searchS = `<!--${ListAnchor[key][0]}-->`;
            var searchE = `<!--${ListAnchor[key][1]}-->`;
            var start = textEditor.indexOf(searchS);
            var end  = textEditor.indexOf(searchE);
            if(start !=-1 && end !=-1) {
                var Target = textEditor.substr(parseInt(start), (parseInt(end) + parseInt(searchE.length)) - parseInt(start));
                textEditor = textEditor.replace(Target, '');
            }
        }
        textEditor = textEditor.replace('<!--body-page-start-->','').replace('<!--body-page-end-->','');
        textEditor = textEditor.replace('<!--head-start-->','').replace('<!--head-end-->','');
        textEditor = textEditor.replace('<!--body-start-->','').replace('<!--body-end-->','');
        return textEditor;
    }



    connectWidget(){
        var $this = this;
        CKEDITOR.document.getById(this.ConnectElement).on('dragstart', function (evt) {
            var target = evt.data.getTarget().getAscendant('li', true);
            var componentCode =  page__.getElementPage($this.ConnectElement).obj.getFindItem('id',$(target).attr('id'));
            CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
            if($this.modeStatus){
                evt.data.dataTransfer.setData('component', componentCode);
                //evt.data.dataTransfer.setData('text/html', componentCode);
            }else{
                evt.data.dataTransfer.setData('component', componentCode);
                evt.data.dataTransfer.setData('text/html', target.getText());
                if ( evt.data.dataTransfer.$.setDragImage && evt.data.getTarget().getAscendant('img', true)) {
                    // evt.data.dataTransfer.$.setDragImage(target.findOne('img').$, 0, 0);
                }
            }
        });
    }




    rebuildCode(textEditor){
        var findComponent = [];
        textEditor = this.cutStyleScriptMetaSystem(textEditor);
        var textEditorNew = $("<div>"+textEditor+"</div>");
        findComponent = $(textEditorNew).find("component").get();
        for(var index = 0; index < findComponent.length; index++)
            $(textEditorNew).find("component#"+$(findComponent[index]).attr("id")).remove()

        var templateList  =  $(textEditorNew).find("component_template").get();
        for(var index = 0; index < templateList.length; index++) {
            var Code = $(templateList[index]).html();
            $(templateList[index]).replaceWith(Code);
        }
        var script = $(textEditorNew).find("script[date-id_script]").get();
        for(var key in script)
            script[key].remove();

        return $(textEditorNew).html().replaceAll("</section>",'').replaceAll("<section>",'');
    }

    init() {
        var $this = this;

        this.CKEDITOR__ = CKEDITOR.replace(this.id + '_editor',{
            contentsCss: $this.style_list,
            allowedContent : false,
            height: 800,

            /*filebrowserBrowseUrl: '/apps/ckfinder/3.4.5/ckfinder.html',
            filebrowserImageBrowseUrl: '/apps/ckfinder/3.4.5/ckfinder.html?type=Images',
            filebrowserUploadUrl: '/apps/ckfinder/3.4.5/core/connector/php/connector.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl: '/apps/ckfinder/3.4.5/core/connector/php/connector.php?command=QuickUpload&type=Images',
            removeButtons: 'PasteFromWord'*/
            on: {
                instanceReady: function(editor) {
                    //CKEDITOR.instances[$this.id + '_editor'].connnectElementComponent = document.querySelector("#"+$this.ConnectElement);
                    CKEDITOR.instances[$this.id + '_editor'].setData($this.teplateData['view']);
                    CKEDITOR.instances[$this.id + '_editor'].urlForComponent = $this.UrlForComponent,
                    this.on( 'mode', function() {
                        if (this.mode == "source") {
                            $this.modeStatus = true;
                            $this.teplateData['body_view'] = $this.rebuildCode(CKEDITOR.instances[$this.id + '_editor'].getData(),"source");
                            CKEDITOR.instances[$this.id + '_editor'].setData($this.teplateData['body_view']);
                        } else {
                            if($this.modeStatus){
                                $this.teplateData['body_view'] = CKEDITOR.instances[$this.id + '_editor'].getData();
                                $this.modeStatus = false;
                                $this.buildView();
                            }
                        }
                    } );
                    this.on('change',function(event){
                        $this.teplateData['body_view'] = $this.rebuildCode(CKEDITOR.instances[$this.id + '_editor'].getData(),"change");
                    });



                    CKEDITOR.instances[$this.id + '_editor'].document.on('dragover', function(event) {
                        event.data.$.preventDefault(true);
                        event.data.$.dataTransfer.dropEffect = "copy";

                    });
                    setTimeout(function () {
                        $this.connectWidget(editor);
                    },300);


                    if($this.AfterInit != null)
                        setTimeout(function () {
                            $this.AfterInit($this);

                        },800);

                }
            }
        });

        this.CKEDITOR__.connnectElementComponent = this.ConnectElement;
    }
    setConnectElement(ConnectElement){
        this.ConnectElement = ConnectElement
        return this;
    }
    getTemplate(){
        return this.teplateData;
    }
    setDataToHtml(data){
        CKEDITOR.instances[this.id + '_editor'].setData(data);
        //   var ckframe = $($this.CKEDITOR__.document.getWindow().$.frameElement.contentDocument);
        //         });
    }

}
