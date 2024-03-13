class selectComponent extends parentComponent {
    constructor(selector,option) {
        super(selector,option);
        this.selectItem             = null;
        this.clearName              = this.option['clear_name'];
        this.target                 = (this.option['target'] != undefined)? this.option['target']:false;
        this.change                 = (this.option['change'] === 'function')? this.option['change']:null,
        this.focus                  = (this.option['focus'] === 'function')? this.option['change']:null,
        this.select                 = (this.option['select'] === 'function')? this.option['change']:null,
        this.templates();
    }
    templates() {
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
    findOption(id) {return (this.data[id] !== undefined) ? this.data[id]:false;}
    deleteAllOption() {this.setOption('data',[])}
    defaultSelect() {
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
                    $("#" + this.id).change({'obj_class': this}, this.option['change']);
                }
                if(this.option['focus'] != null)
                    $("#" + this.id).focus({'obj_class': this}, this.option['focus']);
                if(this.option['select'] != null)
                    $("#" + this.id).select({'obj_class': this}, this.option['select'])
                break;
        }
    }
    createWidget() {
        var $this = this;
        super.createWidget()
        this.defaultSelect();
    }
    addloadFromAajax() {
        var $this = this;
        $.ajax({
            url:this.urlAdi,
            type:'post',
            data:super.checkParasms(),
            dataType:"json",
            success:function (data) {
                if (data.length != 0 && data != undefined) {
                    $this.data = $this.option['data']  = $this.option['data'].concat(data['result']);
                    $this.setOption('data',$this.option['data'])
                }
            }
        });
    }
}




