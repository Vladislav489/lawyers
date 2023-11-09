class  groupGrid{
    static currentId = 0;
    _id = ++groupGrid.currentId;

    get idClass(){
        return this._id;
    }
    constructor(selector,option){
        this.option              = option;
        this.contener            = selector;
        this.templatehtml        = {};
        this.vueObject           = null;
        this.postionItem         = this.HeightItem = this.pageOld = 0;
        this.completeLoad        = false;
        this.arrayPagination     = this.arrayPhysicalPresence = this.listObject = [];

        this.pagination          = this.initVariablePagination(this.option['pagination']);

        this.id                  = this.option['name']
        this.idFooter            = this.id + '_footer';
        this.idBody              = this.id + '_body';
        this.idPagin             = this.id + '_pagination';
        this.data                = (this.option['data'] != undefined)?this.option['data']:null;
        this.globalData          = (this.option['globalData'] !== undefined)?this.option['globalData']:false;
        this.globalParams        = (this.option['globalParams'] !== undefined)?this.option['globalParams']:false;
        this.urlAdi              = (this.option['url'] !== undefined)?this.option['url']:null;
        this.params              = (this.option['params'] != undefined)?this.option['params']:{};
        this.target              = (this.option['target'] != undefined)?this.option['target']:false;
        this.lineLen             = (this.option['lineLen'] != undefined)?this.option['lineLen']:3;
        this.callScriptComponent = this.option['callbackComponent'];


        this.template()
        this.template_ =(this.templatehtml[this.option['template']])?this.templatehtml[this.option['template']]:this.option['template'];

        if (empty(this.data)){
            if(this.globalData !==false) {
                if (page__ !== undefined) {
                    this.option['data'] = this.data = page__.getGolobalData(this.globalData);
                    this.createWidget()
                }
            }else{
                (this.option['autostart'] == true)?this.startWidget():this.createWidget()
            }
        }else {
            this.InitdataComponentAfterGetData({'result':this.data,'pagination':this.pagination});
            //this.createWidget()
        }
    }
    startWidget(){
        if(!empty(this.urlAdi))
            this.loadFromAajax();
        else
            if (!empty(this.option['data']))
                this.createWidget();
    }

    initVariablePagination(pagination_,copyParamIfExist = null){
        var defaultPagination = {
            'pageSize':10, 'page':1, 'totalCount':0,
            'typePagination':0, 'showPagination':0,
            'countPage':1, 'showInPage':1, 'count_line':1, 'all_load':0,'physical_presence':0
        }
        var targrt = (copyParamIfExist == null)? defaultPagination:copyParamIfExist;
        for(var key in defaultPagination){
            if (!empty(copyParamIfExist))
                if (!empty(targrt[key])) pagination_[key] = parseInt(targrt[key]);
            else
                if (!empty(key) && empty(pagination_[key])) pagination_[key] = targrt[key];
        }
        return pagination_;
    }
    template(){
        var templateComponent =  $(this.option['templateComponent']);
        var ComponentBody =  $(templateComponent).find(">:first-child");
        var idComponent =  $(ComponentBody).attr('id');
        $(ComponentBody).removeAttr('id');
        if(this.option['indefication'] != undefined)
            $(ComponentBody).attr(':id',`'${idComponent}'+items_.`+this.option['indefication']);
        else
            $(ComponentBody).attr(':id',`'${idComponent}'+items_.id`);
        var Buff = $("<div></div>")
        Buff.append(templateComponent);
        this.templatehtml['group1'] = `<div id='${this.id}'><div v-bind:id="name+'_body'" class='flex-between flex-wrap'>
            <div v-for="(items_,index) in data" v-show="arrayPhysicalPresence[index]" class='card-item mt-3'>
            <div class='flex'>`
        if(!empty(this.option['name_group'])) {
            this.templatehtml['group1'] += `<div v-show="items_.${this.option['name_group']} != ''" class='line'></div>`
        }
        if(this.target) {
            var templateUrl = this.targetBuild();
            if(!empty(this.option['name_group']))
                this.templatehtml['group1'] += `<h2 v-show="items_.${this.option['name_group']} != ''" class='card-item__title'>
                <a v-bind:href="${templateUrl}">{{items_.${this.option['name_group']}}}</a></h2>`
        }else{
            if(!empty(this.option['name_group'])) {
                this.templatehtml['group1'] += `<h2 v-show="items_.${this.option['name_group']} != ''" class='card-item__title'>{{items_.${this.option['name_group']}}}</h2>`
            }
        }
        this.templatehtml['group1'] += `</div>${Buff.html()}</div></div></div></div>`;
    }
    setComponentScriptCallBack(callback){
        if(callback instanceof Function) {this.callScriptComponent = callback;}
    }
    targetBuild(){
        if(empty(this.target))
            return false;

        var templateUrl = "";
        var utlTarget = [];
        var utlTargetChpu = [];
        var utlTargetFormat = {};
        if(this.target.hasOwnProperty('params')) {
            for (var keys in this.target['params']) {
                var filter = this.target['params'][keys]['filter'];
                utlTargetFormat[filter] = "'+items_." + this.target['params'][keys]['value'];
                utlTargetChpu.push(utlTargetFormat[filter]);
                utlTarget.push(filter + "=" + utlTargetFormat[filter] );
            }
        }
        if(this.target.hasOwnProperty('format')) {
            var url_ = new URL(location.origin+this.target['route']);
                switch (this.target['format']) {
                    case 'chpu':
                        if(utlTargetChpu.length > 0) {
                            templateUrl = "'" + url_.pathname+ "/" + utlTargetChpu.join("+'/")
                            if(url_.search.length > 0)
                                templateUrl += url_.search;
                        } else
                            templateUrl = this.target['route'];
                    break;
                    case 'url':
                        templateUrl = this.target['route'];
                    break;
                    case 'params_with_url':
                        if(url_.search.length > 0)
                            templateUrl = "'"+ url_.pathname + url_.search.length+"&"+utlTarget.join("+'&");
                        else
                            if(utlTarget.length > 0)
                                templateUrl = "'" + url_.pathname + "?" + utlTarget.join("+'&");
                    break;
                    default:
                        var format = this.target['format'].match(/(\{(.+?)})/g);
                        var FormatParams = []
                        for (var i = 0; i < format.length; i++){
                            var filterName =  format[i].replace('{','').replace('}','').replace('?','')
                                    FormatParams.push(utlTargetFormat[filterName]);
                        }
                        templateUrl = "'" + url_.pathname+ "/" + utlTargetChpu.join("+'/")
                    break;
                }
                return templateUrl;
            }
        return "'"+this.target['route']+"?"+utlTarget.join("+'&");
    }

    createPaginationArrow(){
        if(this.completeLoad == false) {
            var $this = this;
            var up = $("<div><div class='paginationUp'><i class='fa fa-arrow-circle-up'>&nbsp;</i></div></div>")
            up.click(function(){
                document.getElementById($this.idBody).scrollTop -= $this.HeightItem * $this.pagination['count_line'];
            }).insertBefore("#" + this.id + '_body');

            var down = $("<div><div class='paginationDown'><i class='fa fa-arrow-circle-down'>&nbsp;</i></div></div>")
            down.click(function () {
                $this.postionItem += $this.pagination['showInPage'];
                if ($this.postionItem >= $this.option['data'].length && this.pagination['physical_presence'] == 0) {
                    $this.pagination['page'] += 1;
                    $this.addloadFromAajax();
                    document.getElementById($this.idBody).scrollTop += $this.HeightItem * $this.pagination['count_line'];
                } else {
                    document.getElementById($this.idBody).scrollTop += $this.HeightItem * $this.pagination['count_line'];
                }

            }).insertAfter("#" + $this.idBody);
        }
    }
    createPaginationMore(){
        var $this = this;
        if(this.completeLoad == false) {
            $("<div><div class='paginationMore'>More</div></div>").click(function () {
                $this.pagination['page'] += 1;
                if($this.pagination['all_load'] == 0)
                    $this.addloadFromAajax();
                 else
                    ($this.pagination['physical_presence'] == 1 )?$this.showItem():$this.setOption('data',$this.arrayPagination[$this.pagination['page']-1]);
            }).insertAfter("#" + this.idBody);
        }
    }
    createPaginationNum(){
        var pagination = null;
        var $this = this;
        var arreyStep = [];
        if($("#"+this.idFooter).length == 0){
            pagination = $("<div id='"+this.idFooter+"' class='group_grid_footer'></div>").append($("<div class='pagination'></div>"));
            pagination.insertAfter("#"+this.idBody);
        }

        var page = parseInt(this.pagination['page']);
        var countItemStep = 10;
        if(page >= 8){
            countItemStep = (countItemStep + page) - 5 ;
            page-= 5
        }else
            page = 1;

        for(var pageStep = page; pageStep <= countItemStep; pageStep++) arreyStep.push(pageStep);
        var ul = $("<ul id='"+this.idPagin+"'>");
        var buttomLeft = parseInt(this.pagination['page'])-1;
        var buttomRight =parseInt(this.pagination['page'])+1;
        if(buttomLeft > 1){
            var li = $("<li class='button-left'><i class='fa fa-caret-left'></i></li>")
                .attr("data-page", buttomLeft)
                .click(function () {$this.pageClick($(this).data('page'))})
            ul.append(li)
        }
        for(var i = 0; i < arreyStep.length-1; i++){
            if(arreyStep[i] <= this.pagination['countPage']) {
                var li = $("<li><i>"+arreyStep[i]+"</i></li>")
                    .attr("data-page", arreyStep[i])
                    .click(function () {$this.pageClick($(this).data('page'))})
                if(arreyStep[i] == this.pagination['page'])
                    li.addClass('active')
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
        ul.append(li)
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
        $("#"+this.idPagin).remove()
        $("#"+this.idFooter).find(".pagination").append(ul)
    }
    staticPagination(){
        this.arrayPagination = array_chank(this.option['data'],this.pagination['showInPage']);
        this.pagination['countPage'] = this.arrayPagination.length
        return this.arrayPagination[this.pagination['page']-1];
    }

    hiddenitems(array){
        var buff = [];
        var flag = (this.option['pagination']['typePagination'] == 3)?true:false;
        for(var key in array) buff.push(flag);
        return buff;
    }
    showStart(array){
        var lengShow = (array.length > this.pagination['showInPage']) ?this.pagination['showInPage']:array.length;
        for(var i = 0;  i < lengShow; i++) array[i] = true;
        return array;
    }
    showItem(){
        var from = (this.pagination['page']-1)*this.pagination['showInPage']
        var to = from + parseInt(this.pagination['showInPage']);
        switch (this.pagination['typePagination']) {
            case 1:
                this.arrayPhysicalPresence = this.hiddenitems(this.arrayPhysicalPresence);
                for(var index = from; index < to;index++)
                    this.arrayPhysicalPresence[index] = true;
                this.setOption('arrayPhysicalPresence', this.arrayPhysicalPresence);
                this.createPaginationNum();
                break;
            case 2:
                for(var index = from; index < to; index++){
                    this.arrayPhysicalPresence[index] = true;
                    this.vueObject.$set(this.vueObject.arrayPhysicalPresence,index,true);
                }
                break;
            case 3:
                this.arrayPhysicalPresence[this.postionItem-1] = true;
                this.vueObject.$set(this.vueObject.arrayPhysicalPresence,this.postionItem,true);
                break;
        }
    }

    createWidget(){
        var $this = this;
        if(this.option['name'] == "" || this.option['name'] == undefined)
            this.option['name'] = 'groupGrid';
        if (this.pagination['physical_presence'] == 0 && this.pagination['all_load'] == 1 )
            this.data = this.option['data'] = this.staticPagination();

        this.vueObject = new Vue({
            el:this.contener,
            data:this.option,
            template:this.template_,
            methods:{},
            mounted:function(){
                if( $this.option['pagination']['typePagination'] == 3){
                    $this.pagination['page'] += 1;
                    $this.addloadFromAajax();
                }
            },
        });
    }
    buildGroupComponent(){
        var $this = this;
        if(this.listObject.length != 0 && this.option['pagination']['typePagination'] == 1 ) {
            var id = $(this.option['templateComponent']).attr("id")
            for (var index in this.data){
                var objTemplate = $(this.option['templateComponent']).attr("id",id+this.data[index]['id'])
                $("#"+this.listObject[index].id).replaceWith(objTemplate);
            }
        }
        for (var key in this.data) {
            var groupName = (!empty(this.option['name_group']))? this.data[key][this.option['name_group']]:"";

            var name_componet = (!empty(this.option['indefication']))? this.data[key][this.option['indefication']]:this.data[key]['id'];

            var add_pararm = {};
            for(var indexParasm in this.option['add_params']){
                if(!empty(this.option['add_params'][indexParasm]['field']))
                    add_pararm[this.option['add_params'][indexParasm]['target']] = this.data[key][this.option['add_params'][indexParasm]['field']];
                if(!empty(this.option['add_params'][indexParasm]['value']))
                    add_pararm[this.option['add_params'][indexParasm]['target']] = this.option['add_params'][indexParasm]['value'];
            }

            var dataInclude_component = this.data[key]['data'];
            this.listObject[key] = this.callScriptComponent(name_componet,groupName,add_pararm,dataInclude_component,this)
            setTimeout(function () {}, 200);
        }
    }

    InitdataComponentAfterGetData(data){
        var $this = this;
        if (!empty(data)) {
            $this.option['data'] = data['result'];
            $this.data = data['result'];
            if (!empty(data['pagination'])) {
                $this.pagination = $this.initVariablePagination($this.pagination, data['pagination']);
                this.arrayPhysicalPresence = $this.option['arrayPhysicalPresence'] = $this.showStart($this.hiddenitems(data['result']));
            }
            $this.createWidget()
            $this.buildGroupComponent();
            if($this.option['pagination']['showPagination'] == 1)
            switch ($this.option['pagination']['typePagination']) {
                case 1:
                    $this.staticPagination()
                    $this.createPaginationNum()
                break;
                case 2:
                    $this.createPaginationMore()
                break;
                case 3:
                    $this.createPaginationArrow()
                    var timer =  setInterval(function () {
                        if($this.listObject[0].completeLoad){
                            if($this.HeightItem == 0) {
                                var top =  $($("#" + $this.idBody)[0].childNodes[0]).css('margin-top');
                                $this.HeightItem = parseFloat($("#" + $this.idBody)[0].childNodes[0].clientHeight)+parseInt(top);
                                $("#" + $this.idBody).css({'overflow':'hidden',"height":$this.HeightItem * $this.pagination['count_line']})
                                $this.completeLoad = true;
                                clearInterval(timer);
                            }
                        }
                    },200);
                break;
            }
        }
    }

    checkParasms(){
        var $this = this;
        var sendParams = {}
        if(this.globalParams){
            Object.assign(sendParams,page__.params);
            Object.assign(sendParams,this.option['params']);
        }else{
            var sendParams =  this.option['params'];
        }
        if(sendParams == undefined) sendParams = {};
        if(sendParams['page'] == undefined || this.globalParams == false)
            sendParams['page'] = this.pagination['page'];
        if(sendParams['pageSize'] == undefined || this.globalParams == false)
            sendParams['pageSize'] = this.pagination['pageSize'];

        return sendParams;
    }
    loadFromAajax() {
        var $this = this;
        $.ajax({url:this.urlAdi, type:'post', data:this.checkParasms(), dataType:"json",
            success:function (data) {$this.InitdataComponentAfterGetData(data);}
        });
    }
    addloadFromAajax(){
        var $this = this;
        $.ajax({url:this.urlAdi, type:'post', data:this.checkParasms(), dataType:"json",
            success:function (data) {
                if (!empty(data)) {
                    $this.data = $this.option['data'] = $this.option['data'].concat(data['result']);
                    var count =  $this.option['data'] .length
                    for(var index = 0; index < count; index++) $this.arrayPhysicalPresence[index] = true;
                    $this.setOption('data',$this.option['data'])
                    $this.setOption('arrayPhysicalPresence',$this.arrayPhysicalPresence)
                    $this.buildGroupComponent();
                }
            }
        });
    }
    pageClick(page){
        this.pageOld = this.pagination['page'];
        this.pagination['page'] = page
        if(this.pagination['all_load'] == 0)
            (this.option['pagination']['typePagination'] == 1)? this.loadFromAajax():this.addloadFromAajax()
        else
            (this.pagination['physical_presence'] == 0)?
                this.setOption('list', this.arrayPagination[this.pagination['page'] - 1]):this.showItem();
    }
    setOption(key,data){
        this.option[key] = data;
        this.vueObject[key] = data;
        this.vueObject.$forceUpdate()
    }
    setUrlParams(params){
        this.params = (this.params.length > 0 )?this.params.concat(params):params;
        this.loadFromAajax();
    }
    getUrlParams(){return this.params;}
}




