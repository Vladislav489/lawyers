class Box {
    static currentId = 0;
    _id = ++Box.currentId;
    get idClass() {return this._id;}
    constructor(selector,option){
        this.option                 = option;
        this.contener               = selector;
        this.callAfterloadComponent = this.option['callAfterloadComponent'];
        this.vueObject              = null;
        this.HeightItem             = 0;
        this.templatehtml           = {}
        this.completeLoad           = false;
        this.id                     = this.option['name'];
        this.idFind                 = "#"+this.id;
        this.urlAdi                 = (this.option['url'] !== undefined)? this.option['url']:null;
        this.params                 = (this.option['params'] !== undefined)? this.option['params']:{};
        this.data                   = (this.option['data'] !== undefined)? this.option['data']:null;
        this.paramsBox              = (this.option['paramsBox'] !== undefined)? this.option['paramsBox']:{};
        this.dataBox                = []; // данные хранятся тут в массиве если useLocal=1

        this.pricesItems            = []; // временные данные хранения цены для подсчета общих сумм
        this.pricesItems            = []; // общая сумма

        this.templates()
        this.template   = (this.templatehtml[this.option['template']])?this.templatehtml[this.option['template']]:this.option['template'];

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
        var $this  = this;
        this.templatehtml['box'] = ``
    }
    createWidget() {
        var $this = this;
        if (this.option['name'] == "" || this.option['name'] == undefined)
            this.option['name'] = 'Cart';

        this.template = eval('`'+this.template+'`');
        this.vueObject = new Vue({
            el:this.contener,
            data:this.option,
            template: this.template,
            mounted:function(){},
            methods:{
                getCountItems(countItems){$this.getCountItems(countItems)},
                initIcon(boxName,id){$this.initIcon(boxName,id)},
            }
        });

        this.callAfterloadComponent;
        this.completeLoad = true;
    }
    loadFromAajax(){
        var $this = this;
        var sendParams = this.option['params'];
        $.ajax({url:this.urlAdi, type:'post', data:sendParams, dataType:"json",
            success:function (data) {
                if (data.length != 0 && data != undefined) {
                    $this.data = $this.option['data'] = data['result'];
                    $this.createWidget();
                } else {
                    $this.data = $this.option['data'] = null;
                    $this.createWidget();
                }
            }
        });
    }

    startWidget() {
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

    //-------------МЕТОДЫ Box-са
    removeByIndex(boxName, index_, by_=1) {
        let box =  this.getDataBox(boxName)
        box.splice(index_,by_) //удаляем
        if(parseInt(this.paramsBox.useLocal)==1) {this.dataBox[boxName] = box;}
        if(!parseInt(this.paramsBox.useLocal)==1) this.toBox(boxName, box)
        this.initCountItems(boxName)
    }

    removeById(boxName, id) {
        let box =  this.getDataBox(boxName)
        box = box.filter(box_ => box_.id != id); // возвращаем массив с удаленным элементом
        if(parseInt(this.paramsBox.useLocal)==1) {this.dataBox[boxName] = box;}
        if(!parseInt(this.paramsBox.useLocal)==1) this.toBox(boxName, box)
        this.initCountItems(boxName)
    }

    add(boxName, item, isDelete = null) {

        if(!boxName || !item) return false;
        let box = this.getDataBox(boxName)

        if (box && box.find(itm => itm.id === item.id) !== undefined) {
            //удаляем item из корзины если есть такой, и стоит опция isDelete не null
            if(isDelete){this.removeById(isDelete.boxName, isDelete.id)}
            return true
        }

        if(!box) {
            item = [item]
        } else {
            item = box.unshift(item)
            item = box
        }

        if(parseInt(this.paramsBox.useLocal)==1) {this.dataBox[boxName] = item;}
        if(!parseInt(this.paramsBox.useLocal)==1) this.toBox(boxName, item)
        this.initCountItems(boxName)
    }

    toBox(boxName, item) {
        switch (boxName) {
            case 'cart':{
                localStorage.setItem(boxName, JSON.stringify(item))
                break;
            }
            case 'favourites':{
                localStorage.setItem(boxName, JSON.stringify(item))
                break;
            }
            default: break;
        }
    }

    getIdsBox(boxName) {
        let ids = []
        let box =  this.getDataBox(boxName)
        console.log(box);
        if(!box || box.length === 0 || box === null){console.log('return null');return null; }
        if(box) {console.log('return not null'); box.forEach((item) =>{ids.push(item.id)}) }
        return ids
    }

    incrementItem(boxName, item, count = 1) {
        let box =  this.getDataBox(boxName)
        box[item]['count'] += count
        if (box[item]['count'] >= 1) {
            if(parseInt(this.paramsBox.useLocal)==1) {console.log(this.dataBox); this.dataBox[boxName] = box;}
            if(!parseInt(this.paramsBox.useLocal)==1) this.toBox(boxName, box)
            var countDiv = document.getElementById('count_cart_'+item);
            countDiv.innerHTML = box[item]['count'];
            this.totalSumOrder(boxName)
        }
    }

    decrementItem(boxName, item, count = 1) {
        let box =  this.getDataBox(boxName)
        box[item]['count'] -= count
        if (box[item]['count'] >= 1) {
            if(parseInt(this.paramsBox.useLocal)==1) {console.log(this.dataBox); this.dataBox[boxName] = box;}
            if(!parseInt(this.paramsBox.useLocal)==1) this.toBox(boxName, box)
            var countDiv = document.getElementById('count_cart_'+item);
            countDiv.innerHTML = box[item]['count'];
            this.totalSumOrder(boxName)
        }
    }

    getDataBox(boxName){
       if(parseInt(this.paramsBox.useLocal)==1){
          return  this.dataBox[boxName]= (this.dataBox[boxName] === undefined) ? null : this.dataBox[boxName]
       }
       return JSON.parse(localStorage.getItem(boxName))
    }

    existItem(boxName, id){
        if(!boxName || !id) return false;
        let box =  this.getDataBox(boxName)
        if (box && box.find(itm => itm.id === id) !== undefined) {
            return true // если есть с таким id
        }
        return false
    }

    initCountItems(boxName) {
        var $this = this;
        let count = this.getDataBox(boxName) ? this.getDataBox(boxName).length : 0
        document.querySelector(`component[data-name='${$this.id}'] span`).textContent=count;
    }

    getCountItems(boxName){
        return this.getDataBox(boxName) ? this.getDataBox(boxName).length : 0
    }

    initIconsAll(boxName,className){
        let id = null
        let items = document.querySelectorAll(`[class="item-like"]`)
        items.forEach(item =>{
            id = item.id.replace(/\D/g,'') // находим в строке только id
                if (this.existItem(boxName, parseInt(id))) {
                 item.setAttribute('fill', 'block') //если есть такой в box
                } else {
                    item.setAttribute('fill', 'none')
                }  //если нет такого в box
        })
    }



    initIcon(boxName, idTag, id){
        let item = document.querySelectorAll(`[id=${idTag}]`)
        item.forEach(itm => {
            if(this.existItem(boxName, parseInt(id))){
                itm.setAttribute('fill', 'block'); return true//если есть такой в box
            }else{itm.setAttribute('fill', 'none'); return false}  //если нет такого в box

        })
    }

    initIconById(boxName, idTag, id){
        let item = document.querySelector(`[id=${idTag}]`)
            if(this.existItem(boxName, parseInt(id))){
                return true//если есть такой в box
            }else{ return false}  //если нет такого в box
    }

    setPricesItems(boxName, items){
        let box =  this.getDataBox(boxName)
        if(items)
        box.forEach((itm, index) =>{
            itm['price']= items[index].price
        })
        this.toBox(boxName, box)
    }

    totalSumOrder(boxName){

        let box =  this.getDataBox(boxName)
        let totalSum = 0
        if(box==null)return false
        box.forEach((itm, index) =>{
            totalSum = totalSum+itm.price*itm.count
         })
        totalSum =  Number(totalSum.toFixed(2))// округляем сумму
        let sumShow =  document.querySelector(`[class='total-price-real']`)
        if (sumShow) sumShow.innerHTML=totalSum

        return totalSum
    }

    getDataForOrder(boxName){
        let items = this.getDataBox(boxName)
        let itemsOrder = []
        items.forEach(itm=>{
            itemsOrder.push({entity_id:  itm.id,entity_price: itm.price,entity_quantity:  itm.count,comment :'comment '+itm.id, entity_quantity_type_id: 1})
        })
        return itemsOrder
    }

    //set get параметры
    setUrlParams(params){
        this.params = (this.params.length > 0 )?this.params.concat(params):params;
        this.loadFromAajax();
    }
    getUrlParams(){return this.params;}
}
