
class  textInfo{
    static currentId = 0;
    _id = ++textInfo.currentId;

    get idClass(){
        return this._id;
    }
    constructor(selector,option){
        this.templatehtml       = {}
        this.option             = option;
        this.id                 = this.option['name']
        this.vueObject          = null;
        this.urlAdi             = (this.option['url'] !== undefined)? this.option['url']:null;
        this.params             = (this.option['params'] != undefined)? this.option['params']:{};
        this.data               = (this.option['data'] != undefined)? this.option['data']:null;
        this.globalData         = (this.option['globalData'] !== undefined)?this.option['globalData']:false;
        this.contener           = selector;
        this.templates()
        this.template           = (this.templatehtml[this.option['template']]) ?
                                            this.templatehtml[this.option['template']]:this.option['template'];

        if (empty(this.data)){
            if(this.globalData !== false) {
                if (page__ !== undefined) {
                    this.option['data'] = this.data = page__.getGolobalData(this.globalData);
                }
            }
            if (this.option['autostart'] == true) {
                this.startWidget();
            } else {
                if(this.data == 'tamplate_clear' || this.data != null)
                    this.createWidget()
            }
        }else {
            this.createWidget()
        }
    }
    templates(){
        this.templatehtml['template_1'] = "";
    }
    startWidget(){
        if (this.urlAdi != null) {
            this.loadFromAajax();
        } else {
            if (this.option['data'] !== null || this.data == 'tamplate_clear')
                this.createWidget();
        }
    }
    createWidget(){
        var $this = this;
        if(this.option['name'] == "" || this.option['name'] == undefined)
            this.option['name'] = 'textInfo';
        this.vueObject = new Vue({
            el:this.contener,
            data:this.option,
            template: this.template,
            methods:{
                format:formatDate,
                is_null:function (item) {
                    return (item == null || item == "")? "-":item
                },
                laxLen:function (item,column = null) {},
            }
        });
    }
    loadFromAajax() {
        var $this = this;

        $.ajax({url:this.urlAdi, type:'post', data:this.params, dataType:"json",
            success:function (data) {
                if(data.length != 0 &&  data != undefined ) {
                    $this.option['data'] = data['result'];
                    $this.createWidget();
                }
            }
        });
    }
    setOption(key,data){
        this.option[key] = data;
    }
    setUrlParams(params){
        if(this.params.length > 0 ){
            this.params.concat(params);
        }else{
            this.params = params;
        }
        this.loadFromAajax();
    }
    getUrlParams(){
        return this.params;
    }
}




