class selectComponent{
    static currentId = 0;
    _id = ++selectComponent.currentId;
    get idClass(){return this._id;}
    constructor(selector,option){
        this.option                 = option;
        this.contener               = selector;
        this.vueObject              = null;
        this.completeLoad           = false;
        this.selectItem             = null;
        this.clearName              = this.option['clear_name'];
        this.id                     = this.option['name'];
        this.urlAdi                 = (this.option['url']!== undefined)? this.option['url']:null;
        this.params                 = (this.option['params'] !== undefined)? this.option['params']:{};
        this.data                   = (this.option['data'] !== undefined)? this.option['data']:null;
        this.globalData             = (this.option['globalData'] !== undefined)? this.option['globalData']:false;
        this.target                 = (this.option['target'] != undefined)? this.option['target']:false;
        this.change                 = (this.option['change'] === 'function')? this.option['change']:null,
        this.focus                  = (this.option['focus'] === 'function')? this.option['change']:null,
        this.select                 = (this.option['select'] === 'function')? this.option['change']:null,
        this.idFind                 = "#" + this.id;
        this.callAfterloadComponent = this.option['callAfterloadComponent'];
        this.templates()
        this.template               = (this.templatehtml[this.option['template']])?this.templatehtml[this.option['template']]:this.option['template'];

        if (empty(this.data)){
            if(this.globalData !== false) {
                if (page__ !== undefined)
                    this.option['data'] = this.data = page__.getGolobalData(this.globalData); this.data = page__.getGolobalData(this.globalData);
            }else{
                (this.option['autostart'] == true)?this.startWidget():this.createWidget();
            }
        } else
            this.createWidget();

    }
    checkParasms(){
        var $this = this;
        if(this.globalParams){
            var sendParams ={}
            Object.assign(sendParams,page__.params);
            Object.assign(sendParams,this.option['params']);
        } else {
            var sendParams =  this.option['params'];
        }
        if(sendParams == undefined) sendParams = {};
        return sendParams;
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

    targetBuild(){
        if(this.target){
            var utlTarget = [];
            for (var keys in this.target['params']) {
                var value = "'+items_."+this.target['params'][keys]['value']
                var filter = this.target['params'][keys]['filter']+"="+value;
                utlTarget.push(filter);
            }
            var templateUrl  = "'"+this.target['route']+"?"+utlTarget.join("+'&");
            return templateUrl;
        }
        return false;
    }
    templates(){
        var default_title =   (( this.option['default_title'] == undefined)? "Выбрать все":this.option['default_title'])
        this.templatehtml = {};
        this.templatehtml['simpleSelect'] = `<select :id='name' class='simpleSelect' name='${this.clearName}'>
            <option value='' selected='true'>${default_title}</option>
            <option v-for=\"(items_ , index) in data \" :data-text='items_' :value='index'>{{items_}}</option>
            </select>`

        this.templatehtml['selectJquery'] = `<select :id='name' class='selectJquery' name='${this.clearName}'>
            <option v-for=\"(items_ , index) in data \"  :data-text='items_' :value='index'>{{items_}}</option>
            </select>`

        this.templatehtml['selectJqueryGroup'] = `<select :id='name' class='selectJqueryGroup' name='${this.clearName}'>
            <optgroup v-for=\"(group_ , index) in data \" :label='group_.name'>
            <option v-for=\"(items_ , index) in group.list \" :data-text='items_' :value='index'>{{items_}}</option>
            <optgroup>
            </select>`;
    }
    startWidget(){
        if (this.urlAdi != null) {
            this.loadFromAajax();
        } else {
            if (this.option['data'] !== undefined) this.createWidget();
        }
    }

    findOption(id){return (this.data[id] !== undefined) ?this.data[id]:false;}
    deleteAllOption(){
        this.setOption('data',[])
    }
    createWidget() {
        var $this = this;
        if (this.option['name'] == "" || this.option['name'] == undefined)
            this.option['name'] = 'Select';
        if(this.vueObject == null ) {
            this.vueObject = new Vue({
                el: this.contener,
                data: this.option,
                template: this.template,
                mounted: function () {},
                methods: {}
            });
        } else {
            this.option['data'] = this.data
            this.setOption('data',this.option['data'])
        }

        switch (this.option['template']) {
            case 'selectJquery':
            case 'selectJqueryGroup':
                var parasmsSelect = {}
                if(this.option['change'] != null)
                    parasmsSelect['change']= this.option['change'];
                if(this.option['focus'] != null)
                    parasmsSelect['focus']= this.option['focus'];
                if(this.option['select'] != null)
                    parasmsSelect['select']= this.option['select'];
                $(this.idFind).selectmenu(parasmsSelect);
                break;
            default:
                if(this.option['change'] != null) {
                    console.log("select")
                    $(this.idFind).change({'obj_class': this}, this.option['change']);
                }
                if(this.option['focus'] != null)
                    $(this.idFind).focus({'obj_class': this}, this.option['focus']);
                if(this.option['select'] != null)
                    $(this.idFind).select({'obj_class': this}, this.option['select'])
                break;
        }
        this.callAfterloadComponent;
        this.completeLoad = true;
    }
    addloadFromAajax(){
        var $this = this;
        $.ajax({
            url:this.urlAdi,
            type:'post',
            data:this.checkParasms(),
            dataType:"json",
            success:function (data) {
                if (data.length != 0 && data != undefined) {
                    $this.data = $this.option['data']  = $this.option['data'].concat(data['result']);
                    if (data['pagination'] != undefined) {
                        $this.pagination = $this.initVariablePagination($this.pagination,data['pagination']);
                        $this.textlen = data['maxlen']
                    }
                    $this.setOption('data',$this.option['data'])
                }
            }
        });
    }
    loadFromAajax(){
        var $this = this;
        $.ajax({
            url:this.urlAdi,
            type:'post',
            data:this.checkParasms(),
            dataType:"json",
            success:function (data) {
                if (data.length != 0 && data != undefined) {
                    $this.data = $this.option['data'] = data['result'];
                    $this.createWidget();
                }
            }
        });
    }
    setOption(key,data){
        this.option[key] = data;
        try {
            this.vueObject[key] = data;
            this.vueObject.$forceUpdate();
        }catch (c) {
            console.log(c)
        }
    }
}




