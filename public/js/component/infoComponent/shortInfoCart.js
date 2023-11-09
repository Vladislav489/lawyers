class  shortCartGrid{
    constructor(selector,option){
        this.templatehtml   = {}
        this.option         = option;
        this.id             = this.option['name']
        this.vueObject      = null;
        this.urlAdi         = (this.option['url']!== undefined)?this.option['url']:null;
        this.params         = (this.option['params']!=undefined)?this.option['params']:{};
        this.contener       = selector;
        this.templates()
        if(this.templatehtml[this.option['template']]){
            this.template = this.templatehtml[this.option['template']];
        }else{
            this.template = this.option['template'];
        }

        if(this.option['autostart'] == true){
            this.startWidget();
        }else{
            if (this.option['data'] !== undefined) {
                this.createWidget();
            }
        }
    }

    templates(){
        this.templatehtml['template_1']="<div id=\""+this.id+"\" class=\"main-today-info flex-between\">"+
            "<div class=\"flex\">"+
            "<img loading=\"lazy\"  v-bind:src=\"/images/+data.url_icon\" alt=\"\" class=\"main-today-info__img\">"+
            "<div class=\"\">"+
            "<p class=\"main-today-info__name\">{{data.short_name}}/USD</p>"+
            "<p class=\"main-today-info__text\">{{data.full_name}}/Dollar</p>"+
            "</div>"+
            "</div>"+
            "<div class=\"flex-end flex-col\">"+
            "<p class=\"main-today-info__price\">{{data.price}}</p>"+
            "<p class=\"main-today-info__percentage up\">"+
            "<svg width=\"10\" height=\"12\" viewBox=\"0 0 10 12\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">"+
            "<path d=\"M5.45962 0.54038C5.20578 0.286539 4.79422 0.286539 4.54038 0.54038L0.403806 4.67695C0.149965 4.93079 0.149965 5.34235 0.403806 5.59619C0.657646 5.85003 1.0692 5.85003 1.32304 5.59619L5 1.91924L8.67695 5.59619C8.9308 5.85003 9.34235 5.85003 9.59619 5.59619C9.85003 5.34235 9.85003 4.93079 9.59619 4.67695L5.45962 0.54038ZM5.65 12L5.65 0.999999L4.35 0.999999L4.35 12L5.65 12Z\" fill=\"#00BA4A\"></path>"+
            "</svg>"+
            "<span class=\"\">0.26% (51.00)</span>"+
            "</p>"+
            "</div>"+
            "</div>";

    }

    startWidget(){
        if (this.urlAdi != null) {
            this.loadFromAajax();
        } else {
            if (this.option['data'] !== undefined) {
                this.createWidget();
            }
        }
    }

    createWidget(){
        var $this = this;
        if(this.option['name'] == "" || this.option['name'] == undefined)
            this.option['name'] = 'shortInfoCart_';
            this.vueObject = new Vue({
                el:this.contener,
                data:this.option,
                template: this.template,
                methods:{
                    format:formatDate,
                    is_null:function (item) {
                        return (item == null || item == "")?"-":item
                    },
                    laxLen:function (item,column = null) {},
                }
            });
    }

    loadFromAajax() {
        var $this = this;
        $.ajax({
            url:this.urlAdi,
            type:'post',
            data:this.params,
            dataType:"json",
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
        this.vueObject.$set(key,data);
    }
}




