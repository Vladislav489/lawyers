class BreadcrumbsSimple{
    static currentId = 0;
    _id = ++BreadcrumbsSimple.currentId;
    get idClass(){return this._id;}
    constructor(selector,option){
        this.option         = option;
        this.contener       = selector;
        this.completeLoad   = false;
        this.vueObject      = null;

        this.id                     = this.option['name']
        this.data                   = (this.option['data']!== undefined)? this.option['data']:null;
        this.globalData             = (this.option['globalData'] !== undefined)?this.option['globalData']:false;
        this.list_routs             = (this.option['list_routs'] !== undefined)?this.option['list_routs']:null;
        this.urlAdi                 = (this.option['url'] !== undefined)?this.option['url']:null;
        this.params                 = (this.option['params'] !== undefined)?this.option['params']:{};
        this.globalParams           = (option['globalParams'] !== undefined)?option['globalParams']:true;
        this.template               = this.option['template'];
        this.callAfterloadComponent = this.option['callAfterloadComponent'];

        if (empty(this.data )){
            if(this.globalData !==false) {
                if (page__ !== undefined) {
                    this.option['data'] = this.data = page__.getGolobalData(this.globalData);
                }
            }else{
                (this.option['autostart'] == true)?this.startWidget():this.createWidget()
            }
        }else {
            this.createWidget()
        }
    }

    checkParasms(){
        var sendParams = {}
        if (this.globalParams) {
            Object.assign(sendParams,page__.params);
            Object.assign(sendParams,this.option['params']);
        } else {
            sendParams = (this.option['params'] != undefined)? this.option['params']:{};
        }
        if(this.list_routs != null)
            sendParams['list_routs'] = this.list_routs;
        sendParams['route'] = page__.routeParams;
        return sendParams;
    }
    loadFromAajax(){
        var $this = this;
        $.ajax({url: this.urlAdi, type: 'post', data: this.checkParasms(), dataType: "json",
            success: function (data) {
                if (data['result'] != undefined && data['result'] != false) {
                    $this.data = $this.option['data'] = data['result'];
                    $this.createWidget();
                }
            }
        });
    }
    createWidget() {
        if (this.option['name'] == "" || this.option['name'] == undefined)
            this.option['name'] = 'Breadcrumbs';

        this.vueObject = new Vue({
            el:this.contener,
            data:this.option,
            template: this.template,
            mounted:function(){},
            methods:{
                format:formatDate,
                getByKey:function(data,key){ return data[key]; },
                is_null:function (item) { return (item == null || item == "")? "-":item },
            }
        });
        this.callAfterloadComponent;
        this.completeLoad = true;
    }
    setOption(key,data){
        this.option[key] = data;
        try {
            this.vueObject[key] = data;
            this.vueObject.$forceUpdate();
        } catch (c) {
            $(this.contener).html("<span>Missing Data!!!</span>")
            console.log(this.template);
            console.log(this.option['pagination']['typePagination'])
            console.log(key)
            console.log(data)
            console.log(this.vueObject);
            console.log(c)
        }
    }
    setUrlParams(params){
        this.params = params;
        this.option['params'] = params;
    }
    getUrlParams(){
        return this.params;
    }
}




