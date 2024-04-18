
class Page {
    constructor (option = {}){
        this.params          = (option['params'] !== undefined)?option['params']:{};
        this.object          = {};
        this.objectIndex     = [];
        this.selectOnPage    = null;
        this.chankPage       = null;
        this.routeParams     = (option['route'] !== undefined)?option['route']:{};
        this.token           = option['token'];
        this.url             = '';
        this.urlInfo         = '';
        this.templateUrl     = '';
        this.globlaData      = (option['globlaData'] !== undefined)?option['globlaData']:{};
        this.afterOnLoadPage = null;
        this.margeParamaFlag = false;
        this.init();
    }
    init() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': ($('meta[name="csrf-token"]').attr('content') == undefined)?this.token:$('meta[name="csrf-token"]').attr('content')
            },
            beforeSend:function(jqXHR, settings){
                settings.url = changeUrlToProtocol(settings.url)
            }
        });
        if(this.routeParams.hasOwnProperty('url')){
             this.templateUrl = this.routeParams['url']
             if(this.templateUrl.match(/(\{(.+?)})/g)!== null)
                 this.templateUrl = this.routeParams['template_url']
             var result = this.templateUrl.match(/(\{(.+?)})/g);
             if(result !== null)
                this.margeParamaFlag = true;
         }
         this.url = window.location.href;
         this.urlInfo = new URL(this.url);
    }

    prarserUrl($url) {
        return this.urlInfo = new URL($url);
    }
    getUrlInfo() {return this.urlInfo;}
    getParams() {return this.params;}
    changeUrlToProtocol(url) {
        var urlRequest = location.href;
        return (urlRequest.indexOf('https://') == -1 )? url.replace("https://", "http://"):url.replace("http://", "https://");
    }
    combainUrlWithTemplateUrl(url,template_url,params = {}) {
        console.log(url.match(/(\{(.+?)})/g))
        console.log(template_url.match(/(\{(.+?)})/g))
        console.log(this.globlaData['itemPage']);
    }


    chengeChankPage(target) {
        $('[pagechank]').css('display','none')
        $('[pagechank=\"'+target+'\"]').css('display','block')
        this.chankPage = target;
    }
    setChankPage(pageChangId) {
        this.chankPage = pageChangId;
        return this;
    }

    setCallbackOnLoad(callback) {this.afterOnLoadPage = callback;}

    onLoadPage() {
        if(this.afterOnLoadPage != null && this.afterOnLoadPage instanceof Function ){this.afterOnLoadPage(this);}
    }

    waitLoadComponent(callBack) {
        var counter = 0;
        var interval =  setInterval(function(){
              if(callBack() || counter > 40)
                clearInterval(interval);
        },200);
    }

    addToGlobalData(key,data) {this.globlaData[key] = data;}
    loadGlobalData(urlAction,params,name,callback = null,data_ = null) {
        var $this = this;
        var prm = {};
        Object.assign(prm,this.params)
        Object.assign(prm,params);
        var urlRequest = location.href;
        if(data_ == null) {
            $.ajax({
                url: urlAction, type: 'post',
                data: prm, dataType: "json",
                success: function (data) {
                        $this.globlaData[name] = (callback != null && callback && callback instanceof Function)? callback(data, $this): data;

                }
            });
        } else {
            $this.globlaData[name] = (callback != null && callback && callback instanceof Function)? callback(data_, $this):data_;
        }
    }
    getGolobalData(name) {
        if(this.globlaData[name] !== undefined)
            return this.globlaData[name];
        if(eval('this.globlaData'+name) !== undefined)
            return eval('this.globlaData'+name)

        return {};
    }

    sendData(urlAction,params,callback = null,errorCallback,method = "post") {
        var $this = this;
        var prm = {};
        Object.assign(prm,this.params);
        if(this.margeParamaFlag)
            Object.assign(prm,params);
        else
            prm = params;

        $.ajax({
            url:urlAction, type:method,
            data:prm, dataType:"json",
            success:function (data) {
                if(callback != null && callback && callback instanceof Function){data = callback(data,$this);}
            },
            error: function (error) {
                if(errorCallback!= null && errorCallback && errorCallback instanceof Function) {
                    var data = errorCallback(error,$this);
                }
            }
        });
    }

    getElementPage(find) {
        for(var key in this.object)
            if(key.indexOf(find) != -1)
                return this.object[key]
    }
    addNewElement(objectNew,key) {
        var index = this.objectIndex.push(objectNew);
        if(this.object.hasOwnProperty(key))
            key += index;
        this.object[key] = {'obj':objectNew,'index':index};
    }
    getElementByIndex(index) {return this.objectIndex[index];}
    getComponentByName(find) {
        var returnElement = [];
        for(var key in this.object)
            if(key.indexOf(find) != -1){returnElement.push(this.object[key]);}
        return(returnElement.length <= 1)? returnElement[0]['obj']:returnElement;
    }
    getElement(find) {
       var obj = this.getElementsGroup(find);
        for(var index in obj)
            if(obj[index].obj.option['clear_name'] == find)
                return obj[index];
    }
    getElementsGroup(find) {
        var returnElement = [];
        for(var key in this.object)
            if(key.indexOf(find) != -1){returnElement.push(this.object[key]);}
        return returnElement;
    }
    deleteElementIndex(index) {
        delete this.objectIndex[index];
        for(var key in this.object){
            if(this.object[key]['index'] == index){delete this.object[key];}
        }
    }
    deleteElementKey(key) {
        delete this.objectIndex[object[key]['index']];
        delete this.object[key];
    }
}

function padTo2Digits(num) {return num.toString().padStart(2, '0');}

function InitHeader() {
    const lang = document.querySelector('.header-lang'),
        menuBurger = document.querySelector('.menu-burger'),
            mobileMenu = document.querySelector('.mobile-menu'),
            mobileMenuLists = document.querySelectorAll('.mobile-menu ul li'),
            header = document.querySelector('.header'),
            body = document.querySelector('body');
        if(lang != null){
            lang.addEventListener('click', () => {
                lang.classList.toggle('showLang')
            });
        }
        if(menuBurger != null) {
            menuBurger.addEventListener('click', () => {
                menuBurger.classList.toggle('change');
                mobileMenu.classList.toggle('showMobileMenu');
                body.classList.toggle('showScroll')
            });
        }
        if(mobileMenuLists != null) {
            mobileMenuLists.forEach(btn => {
                btn.addEventListener('click', () => {
                    menuBurger.classList.toggle('change');
                    mobileMenu.classList.toggle('showMobileMenu');
                    body.classList.toggle('showScroll')
                })
            });
        }
}

function formatDate (date,flagTime = false) {
    var date  = new Date(date);
    var dateStr =  [   padTo2Digits(date.getDate()),
                       padTo2Digits(date.getMonth() + 1),
                       date.getFullYear(),
                    ].join('/');
    if(flagTime !=false){
        dateStr+=' ' +
            [   padTo2Digits(date.getHours()),
                padTo2Digits(date.getMinutes()),
                padTo2Digits(date.getSeconds()),
            ].join(':')
    }
    return dateStr
}

function array_chank(inputArray,perChunk){
    const result = inputArray.reduce((resultArray, item, index) => {
        const chunkIndex = Math.floor(index/perChunk)
        if(!resultArray[chunkIndex]) {resultArray[chunkIndex] = []}
        resultArray[chunkIndex].push(item)
        return resultArray
    }, [])
    return result
}

function  parserUrlFromString($urlParasmsString) {
    var pairs = $urlParasmsString.substring(1).split("&"),
        obj = {}, pair, i;
    for ( i in pairs ) {
        if ( pairs[i] === "" ) continue;
        pair = pairs[i].split("=");
        obj[ decodeURIComponent( pair[0] ) ] = decodeURIComponent( pair[1] );
    }
    return obj;
}

function empty(data){
    if(typeof data  == "object" && data !== null){
        return (data.length < 0) ?true:false;
    }
    return (data == undefined || data === undefined || data == null || data == "")
}

function clearForm(selector){
    dataEdit = $(selector).find("input,select,textarea");
    for(var index = 0; index < dataEdit.length;index++) {
        switch ($(dataEdit[index]).prop("tagName")) {
            case "SELECT":
                $(dataEdit[index]).find("option").removeAttr("selected")
                $(dataEdit[index]).find("option").first().attr("selected", true)
                break;
            default:
                var element =  $(dataEdit[index])
                if(element.attr('type') == "checkbox"){
                    $(dataEdit[index]).val('');
                    $(dataEdit[index]).removeAttr('checked')
                    $('.js_select').val(null).trigger('change');
                } else {
                    $(dataEdit[index]).val('');
                }
                break;
       }
    }
}

function setDateToFormElement(element, data){
    switch ($(element).prop('tagName')) {
        case 'INPUT':
            switch ($(element).attr('type')) {
                case "hidden":
                case "text":
                    $(element).val(data);
                    break;
                case 'radio':
                case 'checkbox':
                    $(element).attr("checked",(data==1)?true:false);
                    break;
            }
            break;
        case 'SELECT':
            $(element).find("option").removeAttr('selected');
            $(element).find("option[value='"+data+"']").attr("selected",true)
            $(element).val(data);
            break;
        case 'TEXTAREA':
            $(element).val(data);
            break;
    }
}

async function createFile(url, type){
    if (typeof window === 'undefined') return // make sure we are in the browser
    const response = await fetch(url)
    const data = await response.blob()
    const metadata = {
        type: type || 'video/quicktime'
    }
    return new File([data], url, metadata)
}

async function getFileFromLink(url) {
    const fileRes = await fetch(url);
    const blob = await fileRes.blob();

    let fileName = getFilenameFromContentDisposition(fileRes);
    if (!fileName) {
        fileName = url.split("/").pop();
    }

    const file = new File([blob], fileName, {
        type: blob.type,
    });

    return file;
}

function  changeUrlToProtocol(url){
    var url_site = new URL(location.href);
    if(url.indexOf("http://") == -1 && url.indexOf("https://") == -1)
        url = url_site.origin + url;
    var url__ = new URL(url);
    return  url__.pathname;
}
