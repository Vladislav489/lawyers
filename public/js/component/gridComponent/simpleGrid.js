class simpleGrid{
    static currentId = 0;
    _id = ++simpleGrid.currentId;

    get idClass(){
        return this._id;
    }
    constructor(selector,option) {
        this.option                 = option;
        this.contener               = selector;
        this.vueObject              = null;
        this.HeightItem             = this.pageOld = 0;
        this.textlen                = this.templatehtml = {}
        this.arrayPagination        = this.arrayPhysicalPresence = [];
        this.completeLoad           = false;
        this.id                     = this.option['name']
        this.maxLenText             = (this.option['maxLenText'] !== undefined) ? this.option['maxLenText'] : undefined;
        this.urlAdi                 = (this.option['url'] !== undefined) ? this.option['url'] : null;
        this.params                 = (this.option['params'] !== undefined) ? this.option['params'] : {};
        this.target                 = (this.option['target'] != undefined) ? this.option['target'] : false;
        this.data                   = (this.option['data'] != undefined) ? this.option['data'] : null;
        this.globalData             = (option['globalData'] !== undefined)?option['globalData']:false;
        this.pagination             = this.initVariablePagination(this.option['pagination'])
        this.globalParams           = (option['globalParams'] !== undefined) ? option['globalParams'] : false;
        this.postionItem            = (this.pagination['pageSize'] > 0) ? this.pagination['pageSize'] : this.pagination['showInPage'];
        this.callAfterloadComponent = this.option['callAfterloadComponent'];
        this.templates()
        this.template               = (this.templatehtml[this.option['template']]) ? this.templatehtml[this.option['template']] : this.option['template'];
        console.log(empty(this.data));
        if (empty(this.data)){
            if(this.globalData !== false) {
                if (page__ !== undefined) {
                    this.option['data'] = this.data = page__.getGolobalData(this.globalData);
                    this.createWidget();
                }
            }else{
                console.log('from ajax');
                (this.option['autostart'] == true)?this.startWidget():this.createWidget()
            }
        } else {
            console.log('from data');
            this.createWidget();
        }
    }
    initVariablePagination(pagination,copyParamIfExist = null){
        var defaultPagination = {
            'pageSize':10, 'page':1, 'totalCount':0,
            'typePagination':0, 'showPagination':0,
            'countPage':1, 'showInPage':10, 'all_load':0,'physical_presence':0
        }
        var targrt = (copyParamIfExist == null)? defaultPagination:copyParamIfExist;
        for(var key in defaultPagination){
            if(copyParamIfExist != null )
                if(targrt[key] != undefined) pagination[key] = targrt[key];
            else
                if(key !== undefined &&  pagination[key] === undefined) pagination[key] = targrt[key];
        }
        return pagination;
    }

    targetBuild(){
        if(this.target == undefined)
            return false;

        var templateUrl = "";
        var utlTarget = [];
        var utlTargetChpu = [];
        var utlTargetFormat = {};
        if(this.target.hasOwnProperty('params')){
            for (var keys in this.target['params']){
                var filter = this.target['params'][keys]['filter'];
                utlTargetFormat[filter] = "'+items_." + this.target['params'][keys]['value'];
                utlTargetChpu.push(utlTargetFormat[filter]);
                utlTarget.push(filter + "=" + utlTargetFormat[filter]);
            }
        }

        if(this.target.hasOwnProperty('format')){
            var url_site = new URL(location.href);
            if(this.target['route'].indexOf("http://") == -1 && this.target['route'].indexOf("https://") == -1)
                this.target['route'] = url_site.origin + this.target['route'];
            var url_ = new URL(this.target['route']);
            switch (this.target['format']) {
                case 'chpu':
                    if(utlTargetChpu.length > 0) {
                        templateUrl = "'" + url_.pathname+ "/" + utlTargetChpu.join("+'/")
                        if(url_.search.length > 0) templateUrl += url_.search;
                    } else
                        templateUrl = this.target['route'];
                break;
                case 'url':
                    templateUrl = this.target['route'];
                break;
                case 'params_with_url':
                    if(url_.search.length > 0){
                        templateUrl = "'" + url_.pathname + url_.search.length + "&" + utlTarget.join("+'&");
                    }else{
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
        }else{
            var templateUrl = "'" + this.target['route'] + "?" + utlTarget.join("+'&");
        }
        return templateUrl;
    }
    templates(){
        this.templatehtml['cart_item'] =
            "<div id='"+this.id+"' class='card-item-info'>" +
            "<div v-if=\"typeof(column) !== 'undefined'\" v-bind:id=\"name+'_head'\" class=\"card-item-info-title flex-between\">" +
                "<span v-for=\"items_col in column\" class='company-name'>{{items_col.name}}</span>" +
            "</div>"+
            "<div v-bind:id=\"name+'_body'\" class='simple_grid_body' style='overflow: hidden'>"
            if(this.target){
                this.templatehtml['cart_item'] += "<div v-for=\"(items_ , index) in data \">"
                this.templatehtml['cart_item'] += (this.pagination['physical_presence'] == 1)?"<div v-show=\"arrayPhysicalPresence[index]\">":"<div>"
                this.templatehtml['cart_item'] += "<a v-bind:href=\""+this.targetBuild()+"\">" +
                "<div class='card-item-info-list flex-between'>"
            }else{
                 this.templatehtml['cart_item'] += "<div v-for=\"(items_, index) in data\" class='card-item-info-list flex-between'>"
                 this.templatehtml['cart_item'] += (this.pagination['physical_presence'] == 1)?"<div v-show=\"arrayPhysicalPresence[index]\">": "<div>"
            }
            this.templatehtml['cart_item'] += "<div class='flex'>" +
            "<div v-if=\"typeof(items_.url_target) !=='undefined'\" class=\"conversion\">" +
                "<img loading='lazy' v-if=\"typeof(items_.url_target) !=='undefined'\" v-bind:src=\"/images/+items_.url_target\" alt='conversion.png' width='40px' height='40px' class='conversion__from'>"+
                "<img loading='lazy' v-if=\"typeof(items_.url_target) !=='undefined'\" v-bind:src=\"/images/+items_.url_icon\" alt='conversion.png' width='40px' height='40px' class='conversion__to'>"+
                "<img loading='lazy' v-if=\"typeof(items_.url_target) !=='undefined'\" src=\"/images/arrow.png\" alt='arrow.png' class='arrow'>"+
            "</div>"+
            "<img loading='lazy' v-if=\"typeof(items_.url_target) =='undefined'\" v-bind:src=\"/images/+items_.url_icon\" width='40px' height='40px' v-bind:alt=\"items_.full_name\" class='card-item-info-list__img'>" +
                "<div>"+
                    "<p v-if=\"typeof(items_.url_target) =='undefined'\" class=\"card-item-info-list__name\">{{items_.short_name}}</p>" +
                    "<p v-if=\"typeof(items_.url_target) !='undefined'\" class=\"card-item-info-list__name\">{{items_.short_name_show}}</p>" +
                    "<p v-bind:title=\"items_.full_name\" class='card-item-info-list__text'>{{laxLen(items_.full_name,'full_name')}}</p>" +
                "</div>"+
            "</div>"+
            "<div class='flex-end flex-col'>" +
                "<p class='card-item-info-list__price' v-bind:class=\"[items_.up_down ? 'up' : 'down']\" >" +
                    "<svg v-if=\"items_.up_down == 1\" width='10' height='12' viewBox='0 0 10 12' fill='none' xmlns='http://www.w3.org/2000/svg'>" +
                        "<path d='M5.45962 0.54038C5.20578 0.286539 4.79422 0.286539 4.54038 0.54038L0.403806 4.67695C0.149965 4.93079 0.149965 5.34235 0.403806 5.59619C0.657646 5.85003 1.0692 5.85003 1.32304 5.59619L5 1.91924L8.67695 5.59619C8.9308 5.85003 9.34235 5.85003 9.59619 5.59619C9.85003 5.34235 9.85003 4.93079 9.59619 4.67695L5.45962 0.54038ZM5.65 12L5.65 0.999999L4.35 0.999999L4.35 12L5.65 12Z' fill='#00BA4A'/>" +
                    "</svg>" +
                     "<svg v-if=\"items_.up_down == 0\" width='10' height='12' viewBox='0 0 10 12' fill='none' xmlns='http://www.w3.org/2000/svg'>"+
                        "<path d='M5.45962 0.54038C5.20578 0.286539 4.79422 0.286539 4.54038 0.54038L0.403806 4.67695C0.149965 4.93079 0.149965 5.34235 0.403806 5.59619C0.657646 5.85003 1.0692 5.85003 1.32304 5.59619L5 1.91924L8.67695 5.59619C8.9308 5.85003 9.34235 5.85003 9.59619 5.59619C9.85003 5.34235 9.85003 4.93079 9.59619 4.67695L5.45962 0.54038ZM5.65 12L5.65 0.999999L4.35 0.999999L4.35 12L5.65 12Z' fill='#00BA4A'></path>"+
                     "</svg>&nbsp;"+
                     "<span>{{is_null(items_.price)}}</span>" +
                "</p>" +
                "<p class='card-item-info-list__date'>{{format(items_.updated_at)}}</p>" +
            "</div>"
            this.templatehtml['cart_item'] += "</div>"
            this.templatehtml['cart_item'] += (this.target)?"</div></a></div></div>":"</div></div>";
            this.templatehtml['cart_item'] += "</div>"
    }
    startWidget(){
        if(this.urlAdi != null)
            this.loadFromAajax();
        else
        if (this.option['data'] !== undefined)
            this.createWidget();
    }
    staticPagination(){
        this.arrayPagination = array_chank(this.option['data'],this.pagination['showInPage']);
        this.pagination['countPage'] = this.arrayPagination.length
        return this.arrayPagination[this.pagination['page']-1];
    }
    hiddenitems(array){// прячит элементы
        var buff = {};
        var flag = (this.option['pagination']['typePagination'] == 3)? true:false;
        for(var key in array)
            buff[key] = flag;
        return buff;
    }
    showStart(array){
        for(var i = 0;i < this.pagination['showInPage'];i++)
            array[i] = true;
        return array;
    }
    // работает только тогда когда есть физическая загрузка
    showItem(){
        //берем стартовую страницу и сколько показать (старт)
        var from = (this.pagination['page']-1)*this.pagination['showInPage']
        //сколько показать и добавляем те что уже показанны
        var to = from+parseInt(this.pagination['showInPage']);
        switch (this.option['pagination']['typePagination']) {
            case 1:
                // пагинация по страницам
                this.arrayPhysicalPresence = this.hiddenitems(this.arrayPhysicalPresence);
                for(var key in this.arrayPhysicalPresence)
                    this.vueObject.$set(this.vueObject.arrayPhysicalPresence,key,false);

                for(var index = from; index < to;index++){
                    this.arrayPhysicalPresence[index] = true;
                    this.vueObject.$set(this.vueObject.arrayPhysicalPresence,index,true);
                }
                this.createPaginationNum();
                break;
            case 2: // рагинация more
                //устанавливаем значения на показ в массиве arrayPhysicalPresence
                for(var index = from; index < to;index++){
                    this.arrayPhysicalPresence[index] = true;
                    this.vueObject.$set(this.vueObject.arrayPhysicalPresence,index,true);
                }
                break;
            case 3://стрелочная пагинация
                this.arrayPhysicalPresence[this.postionItem-1] = true;
                this.vueObject.$set(this.vueObject.arrayPhysicalPresence,this.postionItem,true);
                break;
        }
    }
    createWidget() {
        var $this = this;
        if (this.option['name'] == "" || this.option['name'] == undefined)
            this.option['name'] = 'simpleGrid';
        if (this.pagination['physical_presence'] == 0 && this.pagination['all_load'] == 1)
            this.option['data'] = this.staticPagination();

        this.vueObject = new Vue({
            el:this.contener,
            data:this.option,
            template: this.template,
            mounted:function(){},
            methods:{
               format:formatDate,
               getByKey:function(data,key){return data[key];},
               is_null:function (item) {return (item == null || item == "")?"-":item},
               laxLen:function (item,column = null) {
                   if(this.maxLenText !== undefined && column != null) {
                       if (this.maxLenText[column] != undefined) {
                           var strlen = this.maxLenText[column]
                           if(item.length > strlen-3) {
                               item = item.substr(0, parseInt(strlen) - 3);
                               var lastSpacePos = item.lastIndexOf(" ")
                               if (lastSpacePos != -1 && lastSpacePos == 0) {
                                   return item.substr(0, lastSpacePos) + " ...";
                               } else {
                                   return item + " ...";
                               }
                           }else{
                               return item
                           }
                       } else {
                           return item;
                       }
                   }else{
                       return item;
                   }
               },
            }
        });
        if($this.option['pagination']['showPagination'] == 1){
            switch ($this.option['pagination']['typePagination']) {
                case 1:
                    if (this.pagination['physical_presence'] == 0 && this.pagination['all_load'] == 1) {
                        $this.staticPagination()
                    }
                    $this.createPaginationNum()
                    break;
                case 2:
                    $this.createPaginationMore()
                    break;
                case 3:
                    $this.createPaginationArrow()
                    break;
            }
        }
        this.callAfterloadComponent;
        this.completeLoad = true;
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

        if(sendParams == undefined) sendParams ={};

        if(sendParams['page'] == undefined || this.globalParams == false)
            sendParams['page'] = this.pagination['page'];
        if(sendParams['pageSize'] == undefined || this.globalParams == false)
            sendParams['pageSize'] = this.pagination['pageSize'];

        return sendParams;
    }
    addloadFromAajax(){
        var $this = this;
        $.ajax({
            url:this.urlAdi,
            type:'post',
            data:this.checkParasms(),
            dataType:"json",
            statusCode: {
                500: function() {
                    $($this.contener).html("<div style='text-align: center'><img src='https://www.google.com/url?sa=i&url=https%3A%2F%2Fusagif.com%2Fru%2Fgifki-zagruzki%2F&psig=AOvVaw1_t0y8jGNypO_-i0iHpP9M&ust=1681480284532000&source=images&cd=vfe&ved=0CBEQjRxqFwoTCIj8q9z_pv4CFQAAAAAdAAAAABAE'></div>")
                }
            },
            success:function (data) {
                if (data.length != 0 && data != undefined) {
                    $this.option['data']  = $this.option['data'].concat(data['result']);
                    if (data['pagination'] != undefined) {
                        $this.pagination = $this.initVariablePagination($this.pagination,data['pagination']);
                        $this.textlen = data['maxlen']
                    }
                    if(data['column'] != undefined){
                        $this.option['column'] = data['column'];
                        this.setOption('column',$this.option['column'])
                    }
                    $this.setOption('data',$this.option['data'])
               }else{
                    $(this.contener).html("<div style='text-align: center'><img src='https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif'></div>")
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
            statusCode: {
                   500: function() {
                       $($this.contener).html("<div style='text-align: center'><img src='https://upload.wikimedia.org/wikipedia/commons/c/c7/Loading_2.gif'></div>")
                   }
            },
            success:function (data) {
                if (data.length != 0 && data != undefined){
                    $this.option['data'] = data['result'];
                    if(data['column'] != undefined)
                        $this.option['column'] = data['column'];
                    if (data['pagination'] != undefined){
                        $this.pagination = $this.initVariablePagination($this.pagination,data['pagination']);
                        $this.arrayPhysicalPresence = $this.option['arrayPhysicalPresence'] = $this.showStart($this.hiddenitems(data['result']));
                    }
                    $this.createWidget();
                }
            }
        });
    }
    createPaginationArrow(){
        var $this = this;
        if(this.pagination['all_load'] == 0) {
            this.pagination['page']+=1;
            this.addloadFromAajax();
        }
        this.HeightItem = $("#"+this.id+"_body")[0].childNodes[0].clientHeight;
        $("#"+$this.id+"_body").scrollTop(100)

        var Size = (this.pagination['all_load'] == 0)?
            this.HeightItem*this.pagination['pageSize']:this.HeightItem*this.pagination['showInPage']
        $("#"+this.id+"_body").css("height",Size)

        var up = $("<div><div class='paginationUp'><i class='fa fa-arrow-circle-up'>&nbsp;</i></div></div>")
        var down = $("<div><div class='paginationDown'><i class='fa fa-arrow-circle-down'>&nbsp;</i></div></div>")

        up.click(function(){
            document.getElementById($this.id+"_body").scrollTop -= $this.HeightItem;
        }).insertBefore("#"+this.id+'_body');
        down.click(function(){
            if ($this.pagination['all_load'] == 0) {
                $this.postionItem += 1;
                $this.pagination['page'] += 1;
                if($this.postionItem >= $this.option['data'].length - 2)
                    $this.addloadFromAajax();
            } else {
                $this.postionItem += 1;
                if($this.pagination['physical_presence'] == 1)
                    $this.showItem();
                else
                    $this.setOption('data',$this.arrayPagination[$this.pagination['page']-1]);
            }
            $("#"+$this.id+"_body").scrollTop($("#"+$this.id+"_body").scrollTop()+$this.HeightItem)
        }).insertAfter("#" + this.id + '_body');
    }
    createPaginationMore(){
        var $this = this;
        $("<div><div class='paginationMore'>More</div></div>").click(function(){
            $this.pagination['page'] += 1;
            if($this.pagination['all_load'] == 0) {
                $this.addloadFromAajax();
            } else {
                ($this.pagination['physical_presence'] == 1 )? $this.showItem():$this.setOption('data',$this.arrayPagination[$this.pagination['page']-1]);
            }
        }).insertAfter("#"+this.id+'_body');
    }
    createPaginationNum(){
        var pagination = null;
        var $this = this;
        var arreyStep = [];
        var page = parseInt(this.pagination['page']);
        var countItemStep = 10;
        if($("#"+this.id+"_footer").length == 0){
            pagination = $("<div id='"+this.id+"_footer' class='simple_grid_footer'></div>").append($("<div class='pagination'></div>"));
            pagination.insertAfter("#"+this.id+"_body");
        }
        if(page >= 8){
            countItemStep = (countItemStep + page) - 5 ;
            page-= 5
        }else{
            page = 1;
        }
        for(var pageStep = page; pageStep <= countItemStep; pageStep++)
            arreyStep.push(pageStep);
        var ul = $("<ul id='"+this.id+"_pagination'>");
        var buttomLeft = parseInt(this.pagination['page']) - 1;
        var buttomRight = parseInt(this.pagination['page']) + 1;
        if(buttomLeft > 1){
            var li = $("<li class='button-left'><i class='fa fa-caret-left'></i></li>").attr("data-page", buttomLeft).click(function () {
                $this.pageClick($(this).data('page'))
            })
            ul.append(li)
        }
        for(var i = 0; i < arreyStep.length - 1;i++){
            if(arreyStep[i] <= this.pagination['countPage']) {
                var li = $("<li><i>"+arreyStep[i]+"</i></li>").attr("data-page", arreyStep[i]).click(function () {
                    $this.pageClick($(this).data('page'))
                })
                if(arreyStep[i] == this.pagination['page']){li.addClass('active')}
                ul.append(li)
            }
        }
        var pageNumbEnter = $("<div data-input_on='1' id='"+$this.id+"_numb'>...</div>").mouseenter(function(target){
             var obj = $(this);
             if(obj.data('input_on') == '1'){
                 obj.data('input_on','0')
                 var input  = $("<input type='number' style='width:40px' value='"+$this.pagination['page']+"' min='1' id='"+$this.id+"_input' name='page_number'/>").keypress(function(event) {
                    if(event.keyCode === 13){
                        $this.pagination['page'] = $(this).val();
                        $this.loadFromAajax();
                        obj.data('input_on','1')
                    }
                }).blur(function (event) {
                     setTimeout(function () {
                         obj.html("...");
                         obj.data('input_on','1')
                     },1000)
                 });
                var button = $("<button>&#10132;</button>").click(function () {
                    $this.pagination['page'] = $("#"+$this.id+"_input").val();
                    $this.loadFromAajax();
                    obj.data('input_on','1')
                })
                obj.html("")
                obj.append(input).append(button);
             }
        })

        ul.append($("<li class='button-right'>").append(pageNumbEnter))
        if( parseInt(this.pagination['page']) < (this.pagination['countPage'] - 9)) {
            var li = $("<li><i>" + this.pagination['countPage'] + "</i></li>").attr("data-page", this.pagination['countPage']).click(function () {
                $this.pageClick($(this).data('page'))
            })
            ul.append(li)
        }
        if( buttomRight < this.pagination['countPage'] ){
           var li = $("<li class='button-right'><i class='fa fa-caret-right'></i></li>").attr("data-page",buttomRight).click(function() {
               $this.pageClick($(this).data('page'))
           })
           ul.append(li);
        }
        $("#"+this.id+"_pagination").remove()
        $("#"+this.id+"_footer").find(".pagination").append(ul)
    }
    pageClick(page){
         this.pageOld = this.pagination['page'];
         this.pagination['page'] = page
        if(this.pagination['all_load'] == 0) {
            (this.option['pagination']['typePagination'] == 1)? this.loadFromAajax():this.addloadFromAajax()
        } else {
            (this.pagination['physical_presence'] == 0 )?
                this.setOption('data', this.arrayPagination[this.pagination['page'] - 1]):this.showItem();
        }
    }
    setOption(key,data){
        this.option[key] = data;
        try {
            this.vueObject[key] = data;
            this.vueObject.$forceUpdate();
        } catch (c) {
            console.log(c)
        }
    }
    setUrlParams(params){
        this.params = params;
        this.option['params'] = params;
        this.loadFromAajax();
    }
    getUrlParams(){
        return this.params;
    }
}
