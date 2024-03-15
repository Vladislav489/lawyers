class multiSelectComponent extends parentComponent {
    static currentId = 0;
    _id = ++multiSelectComponent.currentId;
    get idClass(){return this._id;}
    constructor(selector,option) {
        super(selector,option);
        this.selectItems        = [];
        this.selectItemStart    = (this.option['selectItemStart'] != 'undefined')? this.option['selectItemStart']:null;
        this.clearName          = this.option['clear_name'];
        this.target             = (this.option['target'] != undefined)? this.option['target']:false;
        this.change             = (this.option['change'] === 'function')? this.option['change']:null,
        this.focus              = (this.option['focus'] === 'function')? this.option['change']:null,
        this.select             = (this.option['select'] === 'function')? this.option['change']:null,
        super.checkLoadData();
        this.templates()
    }
    templates(){
        var default_title =   (( this.option['default_title'] == undefined)? "Выбрать все":this.option['default_title'])
        this.templatehtml = {};
        this.templatehtml =
            `<ul  v-bind:id='name'>
                <li v-for="(items_ , index) in data" clickselect  v-bind:data-index='index' :data-id='items_.id' >
                    <input type='hidden' v-bind:name="'${this.clearName}['+items_.id+']'">
                    {{items_.name}}
                </li>
            </ul>`
    }

    findItemsInStack(index){
        for(var indexKey in this.selectItems){
            if (this.selectItems[indexKey]['index']  == index) {
                return indexKey;
            }
        }
        return false
    }

    clearSelect() {
        $("#"+this.id).find('.selected').removeClass('selected')
        this.selectItems = [];
    }

    clearSelectChecked() {
        $("#"+this.id).find('.selected').removeClass('selected').prop("checked", false)
        this.selectItems = [];
    }


    selectItem(items = []) {
       for(var key in this.selectItemStart) {
           for( var keyData in this.data) {
               if (this.selectItemStart[key] == this.data[keyData]['id']) {
                   this.selectItems.push({'data': this.data[keyData], 'id': this.data[keyData]['id'], 'index': this.data[keyData]['id']})
               }
           }
       }
    }

    getSelect() {
        let getListData = [];
        for(let key in this.selectItems) {
            getListData.push(this.selectItems[key]['data'])
        }
        return getListData
    }
    clickSelect() {
        var $this = this;
        $("#"+this.id).find('[clickselect]').click(function() {
            var index = this.getAttribute('data-index');
            var searchData = null;
            for(var key in $this.data) {
                if(index == $this.data[key]['id']) {
                    searchData = $this.data[key];
                    break
                }
            }
            console.log('clickselect---+++')
            var find = $this.findItemsInStack(index)
            if(find === false) {
                $(this).addClass('selected')
                $this.selectItems.push({'data': searchData, 'id': searchData.id, 'index': searchData.id})
            } else {
                $this.selectItems.splice(find,1);
                $(this).removeClass('selected')
            }
        });
    }

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
                    $(this.idFind).change({'obj_class': this}, this.option['change']);
                }
                if(this.option['focus'] != null)
                    $(this.idFind).focus({'obj_class': this}, this.option['focus']);
                if(this.option['select'] != null)
                    $(this.idFind).select({'obj_class': this}, this.option['select'])
                break;
        }
    }
    createWidget() {
        var $this = this;
        super.createWidget()
        this.defaultSelect();
        this.clickSelect();
    }
}
