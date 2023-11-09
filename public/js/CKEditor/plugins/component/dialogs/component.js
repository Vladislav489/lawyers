
function buidNode(option,name){
    var optionOrig = option;
    option = option['tag_option'];

    var elementForm = null;
    var lable       = null;
    var help        = null;
    var block       = null;
    var style = {
        "block":[
            'display: flex',
            'align-items: stretch',
            'align-items: center',

        ],
        'lable':['display:inline-block!important','padding-left:5px','padding-top:5px','padding-bottom:5px'],
        'elementForm':['display:inline-block!important','padding-left:5px','padding-right:5px'],

        'help':['content: "?"', 'font-size:12pt', 'background-color:#2280f0',
            'border-radius:5px', 'padding-left:4px', 'padding-right:4px',
            'cursor: pointer', 'color: white', 'margin-left: 3px', 'font-weight: bold',
            'border: 1px solid black'],

        'checkbox':['border:1px solid gainsboro', 'border-radius:5px','height:18px','width:18px', 'margin-left:5px'],
        'text_select':['width:215px','border:1px solid gainsboro', 'border-radius:5px','height:35px', 'margin-left:5px'],
        'textarea':['width:700px','height:100px','border:1px solid gainsboro', 'border-radius:5px'],

    };
    if(option['tag'] !==undefined ){
        lable = document.createElement("p");
        lable.setAttribute('style', style['lable'].join(';')+";padding-left:25px");
        lable.innerHTML = option['lable'];
        elementForm = document.createElement(option['tag']);
        switch (option['tag']) {
            case 'input':
                elementForm.setAttribute('type',option['type']);
                if(option['type'] != 'checkbox') {
                    elementForm.setAttribute('style', style['text_select'].join(';'));
                    elementForm.setAttribute('name',name);
                } else {
                    elementForm.setAttribute('style', style['checkbox'].join(';'));
                    elementForm.setAttribute('name',name);
                    lable.setAttribute('style', style['lable'].join(';'));
                }
                break;
            case 'select':

                elementForm.setAttribute('style',style['text_select'].join(';'));
                elementForm.setAttribute('name',name);
                var optionStart =  document.createElement("option");
                optionStart.value = "";
                optionStart.innerText = "Выберите текст";
                elementForm.appendChild(optionStart);
                if(option['data'] != undefined){
                    if( typeof option['data']  == 'string' ){
                        option['data'] = JSON.parse(option['data']);
                    }

                    for(var key in option['data']) {
                        var option_ =  document.createElement("option");
                        switch (optionOrig['type']) {
                            case 'component':
                                option_.setAttribute("data-params",JSON.stringify(option['data'][key]['item']['params']));
                                    option_.value = option['data'][key]['item'][option['field'][0]];
                                    option_.innerText = option['data'][key]['item'][option['field'][1]];
                                break;
                                default:
                                    option_.value = option['data'][key][option['field'][0]];
                                    option_.innerText = option['data'][key][option['field'][1]];
                                    break;
                        }
                        elementForm.appendChild(option_);
                    }

                }
                break;
            case 'textarea':
                elementForm.setAttribute('style',style['textarea'].join(';'));
                elementForm.setAttribute('name',name);
                break;
        }

        block = document.createElement("div");
        block.setAttribute("style",style['block'].join(";"));



        help  = document.createElement("div");
        help.setAttribute("style",style['lable'].join(";"));
        var span = document.createElement("span");
        span.setAttribute("style",style['help'].join(";"));
        span.setAttribute('title',option['help']);
        span.innerHTML ="?";
        help.appendChild(span);
        var block1 = document.createElement("div");
        block1.setAttribute("style",style['lable'].join(";"));
        block1.appendChild(elementForm);


        if(option['type'] == 'checkbox')
            block.appendChild(lable);
        block.appendChild(block1);
        block.appendChild(help);
        var main  = document.createElement("div");
        if(option['type'] != 'checkbox')
            main.appendChild(lable);
        main.appendChild(block);
        return  main.outerHTML;
    }
    return  null;
}
function createFormTag(dataBild,name){
   var option = dataBild;
   if(option['tag_option'] !== undefined) {
      return  buidNode(option,name);
   } else {
       if(option.length != 0) {
           for (var nameVariable in option) {
                createFormTag(option[nameVariable], nameVariable);
           }
       }else{
           return '';
       }
   }
   return '';
}
function buildProperty(option) {

    if(option['item'] != undefined  && option['item']['params'] != undefined ) {
        var params = option.item.params;
        var element = [];
        if(params.length != 0 ) {
            for (var nameParametr in params) {
                element.push(createFormTag(params[nameParametr],nameParametr));
            }
        }else{
            return `<div>Настройки отсутствуют</div>`;
        }
    }
    return `<div>${element.join('\n')}</div>`;
}

function takeDataFromDialog(listItemElemetn){
    var return_data = {};
    for(var key = 0; key < listItemElemetn.length;key++){
        switch ( listItemElemetn[key].tagName){
            case 'INPUT':
                switch (listItemElemetn[key].getAttribute('type')){
                    case 'checkbox':
                        return_data[listItemElemetn[key].getAttribute('name')] = listItemElemetn[key].checked ;
                        break;
                    case 'radio':
                        break;
                    case 'text':
                        return_data[listItemElemetn[key].getAttribute('name')] =listItemElemetn[key].value;
                        break;
                }
                break;
            case 'SELECT':
                return_data[listItemElemetn[key].getAttribute('name')] = listItemElemetn[key].value;
                break;
            case "TEXTAREA":
                return_data[listItemElemetn[key].getAttribute('name')] = listItemElemetn[key].value;
                break;

        }

    }
    return return_data;
}


(function(undefined) {
  CKEDITOR.dialog.add('componentDialog', function (editor) {
    var componentId = editor.widgets.focused.idComponent;
    return {
      title: editor.dataOption[componentId].item.lable,
      minWidth: 700,
      minHeight: 400,
      contents: [
        {
          id: componentId,
          label: editor.dataOption[componentId].item.lable,
          elements: [
            {
              type: 'html',
              html:''//buildProperty(editor.dataOption[componentId])
            }
          ]
        },
      ],

        onShow: function(env) {
            var selection = editor.getSelection();
            var element = selection.getStartElement();
            var optionComponent =  editor.dataOption[editor.widgets.focused.idComponent];
            this.definition.dialog.parts.title.$.innerText = optionComponent['item']['lable'];
            this.definition.dialog.parts.contents.$.innerHTML =  buildProperty(optionComponent);
            if(document.querySelector("[name='include_component']").length > 0) {
                document.querySelector("[name='include_component']").onchange = function () {
                    var value = this.value;
                    var params = JSON.parse(this.options[this.selectedIndex].getAttribute('data-params'))
                    var element = [];
                    if(params.length != 0 ) {
                        for (var nameParametr in params) {
                            element.push(createFormTag(params[nameParametr],nameParametr));
                        }
                    }else{
                        return `<div>Настройки отсутствуют</div>`;
                    }
                    var win = window.open("","Edit component","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=200,top="+(screen.height-400)+",left="+(screen.width-840))
                    win.document.body.innerHTML =   `<div>${element.join('\n')}</div>
                        <div><button id="saveSubParasmComponent">Ok</button><button id="closeWindow">Cansel</button>
                    </div>`
                        theDoc = win.document,
                        theScript = document.createElement('script');
                    function injectThis() {
                        alert("dasdsa")
                        document.querySelector("#saveSubParasmComponent").addEventListener('click',function(){
                                var dataForm = document.querySelectorAll("input,select,textarea");
                                var data = {};
                                for(var index = 0;index < dataForm.length;index++){
                                    switch ( dataForm[index].tagName){
                                        case 'INPUT':
                                            switch (dataForm[index].getAttribute('type')){
                                                case 'checkbox':
                                                    data[dataForm[index].getAttribute('name')] = dataForm[index].checked;
                                                    break;
                                                case 'radio':
                                                    break;
                                                case 'text':
                                                    data[dataForm[index].getAttribute('name')] = dataForm[index].value;
                                                    break;
                                            }
                                            break;
                                        case 'SELECT':
                                            data[dataForm[index].getAttribute('name')] = dataForm[index].value;
                                            break;
                                        case "TEXTAREA":
                                            data[dataForm[index].getAttribute('name')] = dataForm[index].value;
                                            break;

                                    }
                                }
                                console.log(data)
                            })
                       // alert(document.body.innerHTML);
                    }
                    theScript.innerHTML = 'window.onload = ' + injectThis.toString() + ';';
                    theDoc.body.appendChild(theScript);



                    win.addEventListener("beforeunload",function (){
                        alert("закрыт")
                    })
                }
            }
        },

        onOk: function(env) {
            var dialog = this;
            var abbr = this.element;
            console.log(dialog,abbr);
            var data = takeDataFromDialog(dialog.parts.contents.$.querySelectorAll("input,select,textarea"));
            console.log(data);
           /* $.ajax({
                url:editor.urlForComponent, type:'post',
                data:{}, dataType:"json",
                success:function (data) {

                }
            });*/


        }

    };
  });
})();
