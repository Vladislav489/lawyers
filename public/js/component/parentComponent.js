
class parentComponent {
    static currentId = 0;
    _id = ++parentComponent.currentId;
    get idClass() {return this._id;}
    constructor(selector,option,callVackStart = null) {
        this.templatehtml           = {}
        this.option                 = option;
        this.contener               = selector;
        this.vueObject              = null;
        this.callBeforloadComponent = this.option['callBeforloadComponent'];
        this.callAfterloadComponent = this.option['callAfterloadComponent'];
        this.id                     = this.option['name']
        this.completeLoad           = false;
        this.urlAdi                 = (this.option['url'] !== undefined)? this.option['url']:null;
        this.params                 = (this.option['params'] != undefined)? this.option['params']:{};
        this.target                 = (this.option['target'] != undefined) ? this.option['target']:false;
        this.globalParams           = (option['globalParams'] !== undefined) ? option['globalParams']:false;
        this.data                   = (this.option['data'] != undefined)? this.option['data']:null;
        this.globalData             = (this.option['globalData'] !== undefined)?this.option['globalData']:false;
        this.callAjaxSuccess        = this.option['callAjaxSuccess'];
    }
    startWidget() {
        var $this = this;
        if ((this.option['autostart'] == 'true' || this.option['autostart'] == true)  && this.urlAdi != 'undefined' && this.urlAdi != undefined && this.urlAdi != null && empty(this.option['data'])) {
            console.log("loadFromAajax "+this.id)
            return this.loadFromAajax();
        }

        if (empty(this.option['data']) && this.globalData !== false && page__ !== undefined) {
            var dataGlobal = page__.getGolobalData(this.globalData);
                if(dataGlobal['data'] ==  undefined)
                    dataGlobal['data'] = dataGlobal;
            for(var key in dataGlobal) {
                   this.option[key] = dataGlobal[key];
            }

            return this.createWidget();
        }

        if (this.option['data'] != null || this.option['data'] !== undefined) {
            console.log("createWidgetData "+this.id)
            return this.createWidget();
        }

        this.option['data'] = this.data = [];
        return this.createWidget();
    }
    addUrlParams(params){
        var sendParams = {};
        Object.assign(sendParams,params)
        Object.assign(sendParams,this.option['params']);
        for (var key in params) {
            if(sendParams[key] !== undefined)
                sendParams[key] = params[key];
        }
        this.params = sendParams;
        this.option['params'] = sendParams;
        return this;
    }
    targetBuild() {
        if(this.target == undefined) return false;
        var templateUrl = "";
        var utlTarget = [];
        var utlTargetChpu = [];
        var utlTargetFormat = {};

        if(this.target.hasOwnProperty('params')) {
            for (var keys in this.target['params']) {
                var filter = this.target['params'][keys]['filter'];
                utlTargetFormat[filter] = "'+items_." + this.target['params'][keys]['value'];
                utlTargetChpu.push(utlTargetFormat[filter]);
                utlTarget.push(filter + "=" + utlTargetFormat[filter]);
            }
        }

        if(this.target.hasOwnProperty('format')) {
            var url_site = new URL(location.href);
            if(this.target['route'].indexOf("http://") == -1 && this.target['route'].indexOf("https://") == -1)
                this.target['route'] = url_site.origin + this.target['route'];

            var url_ = new URL(this.target['route']);
            switch (this.target['format']) {
                case 'chpu':
                    if(utlTargetChpu.length > 0) {
                        templateUrl = "'" + url_.pathname+ "/" + utlTargetChpu.join("+'/")
                        if(url_.search.length > 0) templateUrl += url_.search;
                    } else {
                        templateUrl = this.target['route'];
                    }
                    break;
                case 'url':
                    templateUrl = this.target['route'];
                    break;
                case 'params_with_url':
                    if (url_.search.length > 0) {
                        templateUrl = "'" + url_.pathname + url_.search.length + "&" + utlTarget.join("+'&");
                    } else {
                        if(utlTarget.length > 0) templateUrl = "'" +url_.pathname+ "?" + utlTarget.join("+'&");
                    }
                    break;
                default:
                    var format = this.target['format'].match(/(\{(.+?)})/g);
                    var FormatParams = []
                    for (var i = 0; i < format.length;i++){
                        FormatParams.push(utlTargetFormat[
                            format[i].replace('{','').replace('}','').replace('?','')
                            ]);
                    }
                    templateUrl = "'" + url_.pathname+ "/" + utlTargetChpu.join("+'/")
                    break;
            }
        } else {
            var templateUrl = "'" + this.target['route'] + "?" + utlTarget.join("+'&");
        }
        return templateUrl;
    }
    createWidget() {
        var $this = this;
        this.template = (this.templatehtml[this.option['template']]) ?
            this.templatehtml[this.option['template']]:this.option['template']
        this.template = eval('`'+this.template+'`');
        if(this.callBeforloadComponent !=null && typeof(this.callBeforloadComponent) == "function" ){
            this.option = this.callBeforloadComponent(this)
        }
        if(this.option['data'] == null) {
            this.option['data'] = [];
        }
        console.log('createWidget '+this.id,this.option);
        this.vueObject = new Vue({
            el:this.contener,
            data:this.option,
            template: this.template,
            methods:{
                getByKey:function(data,key){
                    return data[key];}
            }
        });

        console.log('createWidgetParent',this.vueObject,this.option);
        if(this.callAfterloadComponent != null && typeof(this.callAfterloadComponent) == "function" ){
            console.log('callAfterloadComponent '+ this.id );
            this.callAfterloadComponent(this);
        }

        this.completeLoad = true;
        return true;
    }
    loadFromAajax(paramsSend ={}) {
        var $this = this;
        var sendParams = {};
        console.log('checkParasms In ajax'+this.id,this.checkParasms(),paramsSend);
        Object.assign(sendParams,this.checkParasms());
        Object.assign(sendParams,paramsSend);
        $.ajax({url:this.urlAdi, type:'post', data:sendParams, dataType:"json",
            success:function (data) {
                if(data.length != 0 && data != undefined ) {
                    $this.data = $this.option['data'] = data['result'];
                    $this.option['count_new_items'] = data['count_new'] ?? 0
                    if($this.callAjaxSuccess != null && typeof($this.callAjaxSuccess) === typeof(Function)) {
                        var rez = $this.callAjaxSuccess(data,$this)
                        if(rez != undefined){
                            $this.option = rez;
                        }
                    }
                    if(this.vueObject != null) {
                        for (var key in $this.option) {
                            if ($this.vueObject[key] != undefined) {
                                $this.vueObject[key] = $this.option[key];
                            }
                        }
                        $this.updateVue()
                    } else {
                        $this.createWidget();
                    }
                }
            }
        });
        return true;
    }
    addloadFromAajax() {
        var $this = this;
        $.ajax({
            url:this.urlAdi,
            type:'post',
            data: this.checkParasms(),
            dataType:"json",
            success:function (data) {
                if (data.length != 0 && data != undefined) {
                    $this.data = $this.option['data']  = $this.option['data'].concat(data['result']);
                    $this.setOption('data',$this.option['data'])
                }
            }
        });
    }
    setOption(key,data) {
       this.option[key] = data;
       if(key == 'data')
           this.data = data;
        try {
            this.vueObject[key] = data;
            this.vueObject.$forceUpdate();
        } catch (c) {


            console.log(c)
        }
    }
    setUrlParams(params) {
        if(this.params.length > 0 ) {
            this.params.concat(params);
            this.option['params'] = this.params;
        } else {
            this.option['params'] = this.params = params;
        }
        this.loadFromAajax();
    }
    setData(data){
        this.setOption('data',data);
    }
    setAddData(data){
       var box = this.vueObject.data
       box.concat(data)
       this.setOption('data',box);
       return box;
    }

    updateVue(){
        this.vueObject.$forceUpdate();
    }
    setOptions(data) {
        for (var key in data){
            if(this.vueObject.hasOwnProperty(key))
                this.vueObject[key] = data[key];
        }
        this.vueObject.$forceUpdate();
    }
    chengeDataByIndex(data,index) {
        var box = this.vueObject.$data['data'];
        if(box[index] !== undefined) {
            box[index] = data;
            this.setData(box);
            return box;
        }
        return true;
    }
    chengeDataByKey(data,saerchKey,saerchValue){
        var box = this.vueObject.$data['data'];
        for (var i = 0; i < data.length;i++) {
            if(box[i][saerchKey] == saerchValue){
                box[i] = data;
                this.setData(box);
                return box;
            }
        }
        return false;
    }
    setAddUnicData(data,key = null,flagUpdate = false){
        var box = this.vueObject.$data['data']
        for (var i = 0; i < data.length;i++) {
            var flagAdd = true;
            if(box.length > 0)
                for (var j = 0; j < box.length; j++) {
                    if (key == null) {
                        if (JSON.stringify(box[j]) === JSON.stringify(data[i])) flagAdd = false;
                    } else {
                        if (box[j][key] == data[i][key]) flagAdd = false;
                    }
                }
            if(flagAdd) {box.push(data[i])}
        }
        this.setOption('data',box);
        return box;
    }
    getUrlParams() { return this.params; }
    checkParasms() {
        var $this = this;
        if(this.globalParams) {
            var sendParams = {}
            Object.assign(sendParams,page__.params);
            Object.assign(sendParams,this.params);
        } else {
            var sendParams = this.params;
        }
        if(sendParams == undefined) sendParams = {};
        return sendParams;
    }
}
