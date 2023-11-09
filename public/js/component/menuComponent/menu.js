class Menu{
    static currentId = 0;
    _id = ++Menu.currentId;

    get idClass(){
        return this._id;
    }
    constructor(selector,option){
        this.option                 = option;
        this.contener               = selector;
        this.callAfterloadComponent = this.option['callAfterloadComponent'];
        this.vueObject              = null;
        this.HeightItem             = 0;
        this.templatehtml           = {}
        this.completeLoad           = false;
        this.id                     = this.option['name']
        this.idFind                 = "#"+this.id;
        this.memory                 = {};
        this.urlAdi                 = (this.option['url'] !== undefined)? this.option['url']:null;
        this.params                 = (this.option['params'] !== undefined)? this.option['params']:{};
        this.data                   = (this.option['data'] !== undefined)? this.option['data']:null;
        this.globalData             = (this.option['globalData'] !== undefined)? this.option['globalData']:false;
        this.typeMenu               = (this.option['style'] !== undefined)? this.option['style']:false;
        this.templates()
        this.template   = (this.templatehtml[this.option['template']])?this.templatehtml[this.option['template']]:this.option['template'];
        this.templateItem = (this.templatehtml[this.option['templateItem']])?this.templatehtml[this.option['templateItem']]:this.option['templateItem'];

        this.memory = localStorage.getItem('admin_menu');
        if(this.memory !==null){
            this.memory = JSON.parse(this.memory);
        } else {
            this.memory = {};
        }

        if (empty(this.data)){
            if(this.globalData !==false) {
                if (page__ !== undefined) {
                    this.option['data'] = this.data = page__.getGolobalData(this.globalData); this.data = page__.getGolobalData(this.globalData);
                }
            }else{
                (this.option['autostart'] == true)?this.startWidget():this.createWidget()
            }
        } else {
            this.createWidget();
        }
    }
    templates(){
        var classArroe = (this.typeMenu == 1)?'fa-chevron-circle-down':'fa-chevron-right'
        var $this  = this;

        this.templatehtml['menu'] =
            `<div id="${this.id}" v-bind:class="'nav_menu_component_'+style">
                <ul class="topmenu_menu_component">
                    <menu-tree v-for="hItem in data" v-bind:item="hItem"></menu-tree>
                </ul>
            </div>`
        this.templatehtml['menuItem'] =
            `<li v-bind:id="item.item.id" >
                <a v-bind:href="item.item.url ||'#'">
                    <i v-bind:class="'fa arrow_l'+item.item.icon">&nbsp;</i>{{item.item.lable}}
                    <i class="fa arrow_r" style="margin-left:2px" :class="{'${classArroe}':item.children}">&nbsp;</i></a>
                    <ul v-if="item.children !== null" class="submenu_menu_component">
                        <menu-tree v-for="y in item.children" v-bind:item="y"></menu-tree>
                    </ul>
            </li>`

        this.templatehtml['menuItemFile'] =
            `<li v-bind:id="item.item.id" >
                <a v-bind:href="item.item.url ||'#'">
                <i v-bind:class="'fa arrow_l '+item.item.icon">&nbsp;</i>
                    {{item.item.lable}}
                <i class="fa arrow_r" style="margin-left:2px" :class="{\'${classArroe}':item.children}">&nbsp;</i></a>
                <ul v-if="item.children !== null" class="submenu_menu_component">
                    <menu-tree v-for="y in item.children" v-bind:item="y"></menu-tree>
                </ul>
            </li>`
    }
    openItemFromMemory() {
        var menu = $(this.idFind)
        for (var key in this.memory) {
            var li = menu.find(`li#${key}`);
            if (this.memory[key]) {
                li.find(".submenu_menu_component").addClass('open');
                li.find("i").addClass('caret-down');
            } else {
                li.find(".submenu_menu_component").removeClass('open');
                li.find("i").removeClass('caret-down');
            }
        }
    }
    createWidget() {
        var classArroe = (this.typeMenu == 1)?'fa-chevron-circle-down':'fa-chevron-right'
        var $this = this;
        if (this.option['name'] == "" || this.option['name'] == undefined)
            this.option['name'] = 'Menu';
        this.templateItem = eval('`'+this.templateItem+'`');
        Vue.component('menu-tree', {
            props:['item'],
            template:this.templateItem,
            methods:{}
        })
        this.template = eval('`'+this.template+'`');
        this.vueObject = new Vue({
            el:this.contener,
            data:this.option,
            template: this.template,
            mounted:function(){},
            methods:{}
        });
        if( this.typeMenu   =='1'){
            $(this.idFind).find(".topmenu_menu_component").find('a').click(function(){
                $this.memory[this.parentElement.getAttribute('id')] = this.parentElement.querySelector(".submenu_menu_component").classList.toggle("open");
                localStorage.setItem('admin_menu',JSON.stringify($this.memory));
                this.parentElement.querySelector(".arrow_r").classList.toggle('caret-down')
            })
        }
        this.callAfterloadComponent;
        this.completeLoad = true;
        this.openItemFromMemory();
    }
    loadFromAajax(){
        var $this = this;
        var sendParams = this.option['params'];
        $.ajax({url:this.urlAdi, type:'post', data:sendParams, dataType:"json",
            success:function (data) {
                if (data.length != 0 && data != undefined) {
                    $this.data = $this.option['data'] = data['result'];
                    $this.createWidget();
                }
            }
        });
    }
    startWidget(){
        if(this.urlAdi != null)
            this.loadFromAajax();
        else
        if (this.option['data'] !== undefined)
            this.createWidget();
    }
    setOption(key,data){
        this.option[key] = data;
        try {
            this.vueObject[key] = data;
            this.vueObject.$forceUpdate();
        }catch (c) {
            console.log(data,key)
        }
    }
    getFindItem(key,value,data = null){
        var findItem = null;

        if(data == null) data = this.data;
        for(var index in data){
            if(data[index]['item'][key] !== undefined){
               if(data[index]['item'][key] == value) {
                   findItem = data[index];
                   return findItem
               }
            }
            if(data[index]['children'] !== null) {
                findItem = this.getFindItem(key, value, data[index]['children']);
                if(findItem != null)
                    return findItem;
            }
        }

        return findItem;

    }



    setUrlParams(params){
        this.params = (this.params.length > 0 )?this.params.concat(params):params;
        this.loadFromAajax();
    }
    getUrlParams(){return this.params;}
}
