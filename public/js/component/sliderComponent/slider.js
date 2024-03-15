class Slider {
    static currentId = 0;
    _id = ++Slider.currentId;
    get idClass() { return this._id;}
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
        this.paramsSlider           = (this.option['paramsSlider'] !== undefined)? this.option['paramsSlider']:{};
        this.currentPosition        = 0;
        this.currentPositionLoop    = 0;
        this.currentPositionLoopPrev = this.option['data'].length-1;

        this.dataLoop        = [];

        this.templates()
        this.template   = (this.templatehtml[this.option['template']])?this.templatehtml[this.option['template']]:this.option['template'];
        this.templateItem = (this.templatehtml[this.option['templateItem']])?this.templatehtml[this.option['templateItem']]:this.option['templateItem'];

        if (empty(this.data)){
            if (this.globalData !==false) {
                if (page__ !== undefined)
                    this.option['data'] = this.data = page__.getGolobalData(this.globalData); this.data = page__.getGolobalData(this.globalData);
            } else {
                (this.option['autostart'] == true)?this.startWidget():this.createWidget()
            }
        } else {
            this.createWidget();
        }
    }
    templates(){
        var $this = this;
        this.templatehtml['slider'] =
            `<div class="items_container"  >
            <div class="items-navigation">
            <a id="item-nav_prev_one" class="item-nav item-prev" v-on:click="moveLeft()"></a>
            <a id="item-nav_next_one" class="item-nav item-next" v-on:click="moveRight()"></a>
            </div>
            <slider-items v-for="(hItem,hIndex) in data" v-bind:item='hItem'  v-bind:index='hIndex'></slider-items>
            </div>`

        this.templatehtml['sliderItem'] =
            `<a v-bind:id="'slider_'+index"  v-bind:href="'product/'+item.alias_url_unic ||'#' " class='item middle-hidden'>
                       <div  class='flex item-image'>
                           <img class='item_first-image' src="images/main/section1/jacket.png" alt="jacket-img">
                           <img class='item_second-image' src="images/main/section1/sweater.png" alt="sweater-img">
                           <svg xmlns='http://www.w3.org/2000/svg' width='23' height='20' viewBox='0 0 23 20'
                                fill='none' class='item-like' id='heart-icon'>
                               <path
                                   d='M22.0648 6.37392C22.0648 7.65298 21.5796 8.94308 20.5983 9.92443L18.9664 11.5563L11.9425 18.5802C11.9094 18.6132 11.8984 18.6243 11.8653 18.6463C11.8323 18.6243 11.8212 18.6132 11.7881 18.5802L3.13241 9.92443C2.15106 8.94308 1.66589 7.66401 1.66589 6.37392C1.66589 5.08383 2.15106 3.79374 3.13241 2.81239C5.09511 0.860714 8.27072 0.860714 10.2334 2.81239L11.8543 4.4443L13.4862 2.81239C15.4489 0.860714 18.6135 0.860714 20.5762 2.81239C21.5796 3.79374 22.0648 5.0728 22.0648 6.37392Z'
                                   stroke='#8E8E8E'
                                   stroke-width='1.55667'
                                   stroke-linecap='round'
                                   stroke-linejoin='round'/>
                           </svg>

                           <span class='item-addToCart flex'>
                                           <img v-bind:src="'/storage/img/gifti/icons/bag-item.svg'" alt='bag-icon'>
                            </span>
                       </div>

                       <div class='item-price'>
                           <span class='item-price_new'>₽ {{item.price}}</span>
                           <span class='item-price_old'>₽ {{item.price}}</span>
                       </div>
                       <h3 class='item-title'>{{item.name}}</h3>
                        <p class='item-desc'>Название: {{item.name}}</p>
                       <div class='item-size_container'>
                           <span class='item-size'>42</span>
                           <span class='item-size'>44</span>
                           <span class='item-size'>46</span>
                           <span class='item-size item-size_disabled'>48</span>
                           <span class='item-size item-size_disabled'>50</span>
                       </div>
                   </a>`
    }

    createWidget() {
        var $this = this;
        if (this.option['name'] == "" || this.option['name'] == undefined)
            this.option['name'] = 'Slider';
        this.templateItem = eval('`'+this.templateItem+'`');
        Vue.component('slider-items', {
            props:['item','index'],
            template:this.templateItem,
            methods:{}
        })
        this.template = eval('`'+this.template+'`');
        this.vueObject = new Vue({
            el:this.contener,
            data:this.option,
            template: this.template,
            mounted:function(){},
            methods:{
                moveLeft(){$this.moveLeft();},
                moveRight(){$this.moveRight();},
            }
        });
        if(parseInt(this.paramsSlider.loop) !==1) $this.initItems();
        if(parseInt(this.paramsSlider.loop)===1) $this.initItemsLoop();
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

    initItems(){
        var $this = this;
        for(let i = this.currentPosition; i !== this.data.length; i++){
            document.getElementById('slider_'+i).id = $this.id+'_'+i;
            const none_itesm = document.getElementById($this.id+'_'+i);
            if(i >= parseInt(this.paramsSlider.columns)) {none_itesm.style.display = 'none';}
        }
    }

    initItemsLoop() {
        var $this = this;
        for(let i = this.currentPosition; i !== this.data.length; i++){
            document.getElementById('slider_'+i).id = $this.id+'_'+i;
            const none_itesm = document.getElementById($this.id+'_'+i);
            this.dataLoop.push(none_itesm)
            if(i >= parseInt(this.paramsSlider.columns)) {none_itesm.remove();}
        }
    }
    moveRight(){
        if(this.data.length <= parseInt(this.paramsSlider.columns)){return false;}
        var $this = this;
        if(parseInt(this.paramsSlider.loop)!==1 && this.currentPosition + parseInt(this.paramsSlider.columns)  < this.data.length) {
            let curr =  parseInt(this.currentPosition)
            for(let i =  parseInt(this.currentPosition);
                i !== curr+parseInt(this.paramsSlider.move); i++){
                const block_item = document.getElementById($this.id+'_'+(i+ parseInt(this.paramsSlider.columns)))
                const none_item = document.getElementById($this.id+'_'+(i))
                if(block_item && none_item){
                    this.currentPosition++
                    block_item.style.display = 'block';
                    none_item.style.display = 'none';
                }
            }
         } else if(parseInt(this.paramsSlider.loop)==1) {
            var $this = this;
            let curr =  parseInt(this.currentPosition)
            for(let i =  parseInt(this.currentPosition);
                i !== curr+parseInt(this.paramsSlider.move); i++){
                this.currentPosition++
                const el = document.querySelector(`component[data-name='${$this.id}'] .items_container`);
                if(this.dataLoop.includes(this.dataLoop[parseInt(i+parseInt(this.paramsSlider.columns))])){
                    el.append(this.dataLoop[parseInt(i+parseInt(this.paramsSlider.columns))]);
                    this.currentPositionLoop=0
                }
                if(! this.dataLoop.includes(this.dataLoop[parseInt(i+parseInt(this.paramsSlider.columns))])){
                   // console.log(parseInt(i+parseInt(this.paramsSlider.columns))-this.data.length)
                    el.append(this.dataLoop[parseInt(i+parseInt(this.paramsSlider.columns))-this.data.length])
                   // el.append(this.dataLoop[i-parseInt(this.paramsSlider.columns)-1])
                    this.currentPositionLoop++
                }
                document.querySelector(`component[data-name='${$this.id}'] .items_container a[class='item middle-hidden']`).remove();
                if(this.data.length <= this.currentPosition) this.currentPosition = 0
            }

         }
    }

    moveLeft(){
        if(this.data.length <= parseInt(this.paramsSlider.columns)) {return false;}
        var $this = this;
            if(parseInt(this.paramsSlider.loop)!==1 && this.currentPosition >= 0) {
            let curr =  parseInt(this.currentPosition)
            for(let i =  parseInt(this.currentPosition);
                i >= curr-parseInt(this.paramsSlider.move); i--){
                const none_item = document.getElementById($this.id+'_'+(i+ parseInt(this.paramsSlider.columns)))
                const block_item = document.getElementById($this.id+'_'+(i))
                if(block_item && none_item){
                    this.currentPosition--
                    block_item.style.display = 'block';
                    none_item.style.display = 'none';
                    if (parseInt($this.currentPosition) < 0){ this.currentPosition = 0}
                }
            }
        }else if(parseInt(this.paramsSlider.loop)==1) {
                var $this = this;
                let curr =  parseInt(this.currentPosition)
                for(let i =  parseInt(this.currentPosition);
                    i !== curr-parseInt(this.paramsSlider.move); i--){
                    this.currentPosition--
                    const el = document.querySelector(`component[data-name='${$this.id}'] .items_container`);
                    if(this.dataLoop.includes(this.dataLoop[i-1])){
                        el.prepend(this.dataLoop[i-1]);
                        this.currentPositionLoopPrev=this.data.length-1
                    }
                    if(! this.dataLoop.includes(this.dataLoop[i-1])){
                      //  console.log(this.currentPositionLoopPrev)
                        el.prepend(this.dataLoop[this.currentPositionLoopPrev])
                        this.currentPositionLoopPrev--
                    }
                    document.querySelector(`component[data-name='${$this.id}'] .items_container a[class='item middle-hidden']:last-of-type`).remove();
                    if(-1 === this.currentPosition){
                        this.currentPosition=this.data.length-1
                    }
                }
            }
    }
    setUrlParams(params) {
        this.params = (this.params.length > 0 )?this.params.concat(params):params;
        this.loadFromAajax();
    }
    getUrlParams(){return this.params;}
}
