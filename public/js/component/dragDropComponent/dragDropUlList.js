class dragDropUlList{
    static currentId = 0;
    _id = ++dragDropUlList.currentId;

    get idClass(){
        return this._id;
    }
    constructor(selector,container,option){
        this.option             = option;

        this.contener           = selector;
        this.targetContainer    = container;
        this.vueObject          = null;
        this.textlen            = this.templatehtml = {}
        this.HeightItem         = this.pageOld = 0;
        this.arrayPagination    = this.arrayPhysicalPresence = [];
        this.completeLoad       = false;
        this.templateReturnData = "";
        this.pagination         = this.initVariablePagination(this.option['pagination'])

        this.id         = this.option['name'];
        this.idFind     = "#"+this.id;
        this.urlAdi     = (this.option['url']!== undefined)?this.option['url']:null;
        this.params     = (this.option['params'] !== undefined)?this.option['params']:{};
        this.data       = (this.option['data'] !== undefined)?this.option['data']:null;
        this.target     = (this.option['target'] !== undefined)?this.option['target']:{};

        this.callAfterloadComponent = (this.option['callAfterloadComponent']!== undefined)? this.option['callAfterloadComponent']:null;
        this.callDropFunction       = (this.option['callDropFunction'] !== undefined)? this.option['callDropFunction']:null;
        this.postionItem            = (this.pagination['pageSize'] > 0)?this.pagination['pageSize']:this.pagination['showInPage'];

        this.templates()
        this.template   = (this.templatehtml[this.option['template']])?this.templatehtml[this.option['template']]:this.option['template'];

        if (this.data == null){
            if (this.option['autostart'] == true) {
                this.startWidget();
            } else {
                this.createWidget()
            }
        }else {
            this.createWidget()
        }
    }

    targetBuild(){
        if(this.target) {
            var utlTarget = [];
            for (var keys in this.target['params']) {
                var value = "'+items_." + this.target['params'][keys]['value']
                var filter = this.target['params'][keys]['filter'] + "="+value;
                utlTarget.push(filter);
            }
            var templateUrl = "'" + this.target['route'] + "?" + utlTarget.join("+'&");
            return templateUrl;
        }
        return false;
    }
    initVariablePagination(pagination,copyParamIfExist = null){
        var defaultPagination = {
            'pageSize':10, 'page':1, 'totalCount':0,
            'typePagination':0, 'showPagination':0,
            'countPage':1, 'showInPage':10, 'all_load':0,'physical_presence':0
        }
        var targrt = (copyParamIfExist == null)? defaultPagination:copyParamIfExist;
        for(var key in defaultPagination) {
            if(copyParamIfExist != null )
                if(targrt[key] != undefined) pagination[key] = targrt[key];
            else
                if(pagination[key] === undefined) pagination[key] = targrt[key];
        }
        return pagination;
    }
    templates(){
        this.templatehtml['cart_item'] = `<div id='${this.id}'>
                <ul v-bind:id="name">
                    <li class='dragendrop' v-for="(items_ , index) in data " v-bind:data-template=''>
                            <div class='codeDragendrop' style='display:none'>{{items_.code}}</div>
                            <span>{{items_.name}}</span>
                    </li>
                </ul>
                </div>`;
    }

    setDragDrop(){
        var $this = this;
        if(this.targetContainer !== null &&  this.targetContainer.length > 1) {
            var SelectoSnap = [];
            for (var i = 0; i < this.targetContainer.length; i++){
                SelectoSnap.push("#"+$(this.targetContainer[i]).attr('id'));
                $(this.targetContainer[i]).droppable({
                    drop: function( event, ui ) {
                        $this.callDropFunction(event, ui );}

                });
            }

            $(this.idFind).find(".dragendrop").draggable({
                revert: function (evt) {
                    console.log(evt)
                    //do some extra stuff...
                    return 'invalid';
                },
                appendTo: SelectoSnap.join(","),
                stop: function( event, ui ) {
                    console.log(event, ui);
                    console.log(event);
                    console.log($(ui.helper[0]).attr('id'));
                }
            });

        } else {
            if(this.targetContainer !== null) {
                console.log(this.targetContainer);
                if($(this.targetContainer).length == 0){
                    this.targetContainer = eval(this.targetContainer);
                    if(this.targetContainer.length == 0) {
                        this.targetContainer = this.targetContainer.prevObject;
                    }

                    $(this.targetContainer).droppable({
                        drop: function (event, ui) {
                            console.log(event, ui)
                            $this.callDropFunction($this.targetContainer, event, ui);
                        }
                    });
                }else {

                    $(this.targetContainer).droppable({
                        drop: function (event, ui) {
                            console.log(event, ui)
                            $this.callDropFunction($this.targetContainer, event, ui);
                        }
                    });
                }

                $(this.idFind).find(".dragendrop").draggable({
                    revert: function (evt) {
                        return 'invalid';
                    }
                })

            }else{
                $(this.idFind).find(".dragendrop").draggable({
                    revert: function (evt) {
                        return 'invalid';
                    },
                    appendTo: this.targetContainer,
                })
            }
        }
    }
    startWidget(){
        if(this.urlAdi != null) {
            if (this.data == null)
                this.loadFromAajax();
            else
                this.createWidget();
        }
    }

    staticPagination(){
        this.arrayPagination = array_chank(this.option['data'],this.pagination['showInPage']);
        this.pagination['countPage'] = this.arrayPagination.length
        return this.arrayPagination[this.pagination['page']-1];
    }

    hiddenitems(array){
        var newArray = {};
        if(this.option['pagination']['typePagination'] == 3) {
            for(var key in array)
                newArray[key] = true
        } else {
            for(var key in array)
                newArray[key] = false
        }
        return newArray;
    }
    showStart(array){
        for(var i = 0;  i < this.pagination['showInPage']; i++)
            array[i] = true;
        return array;
    }
    showItem(){
        var from = (this.pagination['page']-1) * this.pagination['showInPage']
        var to = from + parseInt(this.pagination['showInPage']);
        switch (this.option['pagination']['typePagination']) {
            case 1:
                this.arrayPhysicalPresence = this.hiddenitems(this.arrayPhysicalPresence);
                for(var key in this.arrayPhysicalPresence){
                    this.vueObject.$set(this.vueObject.arrayPhysicalPresence,key,false);
                }
                for(var index = from; index < to;index++){
                    this.arrayPhysicalPresence[index] = true;
                    this.vueObject.$set(this.vueObject.arrayPhysicalPresence,index,true);
                }
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

    createWidget() {
        var $this = this;
        if (this.option['name'] == "" || this.option['name'] == undefined)
            this.option['name'] = 'simpleGrid';
        if (this.pagination['physical_presence'] == 0 && this.pagination['all_load'] == 1)
            this.option['data'] = this.staticPagination();


            this.vueObject = new Vue({
                el: this.contener,
                data: this.option,
                template: this.template,
                mounted: function () {
                },
                methods: {
                    format: formatDate,
                    getByKey: function (data, key) {
                        return data[key];
                    },
                    is_null: function (item) {
                        return (item == null || item == "") ? "-" : item
                    },
                    laxLen: function (item, column = null) {
                        if ($this.textlen != undefined) {
                            if (column == null) {
                                var addSpace = $this.textlen[column] - item.length;
                                for (var i = 0; i < addSpace; i++) {
                                    item += "&nbsp;";
                                }
                                return item;
                            } else {
                                if (item.length > 30) {
                                    var str = item.substr(0, 27)
                                    return str.substr(0, str.lastIndexOf(" ")) + "...";
                                }
                                return item;
                            }
                        } else {
                            if (item.length > 30) {
                                var str = item.substr(0, 27)
                                return str.substr(0, str.lastIndexOf(" ")) + "...";
                            }
                            return item;
                        }
                    },
                }
            });

        if($this.option['pagination']['showPagination'] == 1){
            switch ($this.option['pagination']['typePagination']) {
                case 1:
                    if (this.pagination['physical_presence'] == 0 && this.pagination['all_load'] == 1)
                        $this.staticPagination()
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
        this. setDragDrop();
        this.callAfterloadComponent;
        this.completeLoad = true;
    }
    addloadFromAajax(){
        var $this = this;
        var sendParams = this.option['params'];
        if(sendParams == undefined)
            sendParams ={}
        sendParams['pageSize'] = this.pagination['pageSize'];
        sendParams['page'] = this.pagination['page'];

        $.ajax({url:this.urlAdi, type:'post', data:sendParams, dataType:"json",
            success:function (data) {
                if (data.length != 0 && data != undefined) {
                    $this.data = $this.option['data']  = $this.option['data'].concat(data['result']);
                    if (data['pagination'] != undefined) {
                        $this.pagination = $this.initVariablePagination($this.pagination,data['pagination']);
                        $this.textlen = data['maxlen']
                    }
                    if(data['column'] != undefined) {
                        $this.option['column'] = data['column'];
                        this.setOption('column',$this.option['column'])
                    }
                    $this.setOption('data',$this.option['data'])
                }
            }
        });
    }
    loadFromAajax(){
        var $this = this;
        var sendParams =  this.option['params'];
        if(sendParams == undefined)
            sendParams ={}
        sendParams['pageSize'] =  this.pagination['pageSize'];
        sendParams['page'] = this.pagination['page'];

        $.ajax({url:this.urlAdi, type:'post', data:sendParams, dataType:"json",
            success:function (data) {
                if (data.length != 0 && data != undefined) {
                  $this.data =  $this.option['data'] = data['result'];
                    if(data['column'] != undefined){
                        $this.option['column'] = data['column'];
                    }
                    if (data['pagination'] != undefined) {
                        $this.pagination = $this.initVariablePagination($this.pagination,data['pagination']);
                        $this.textlen = data['maxlen']
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
            this.pagination['page'] += 1;
            this.addloadFromAajax();
        }

        this.HeightItem = $(this.idFind)[0].childNodes[0].clientHeight;
        $(this.idFind).scrollTop(100)

        var Size = (this.HeightItem*this.pagination['showInPage']) ? this.HeightItem*this.pagination['pageSize']:
            this.HeightItem*this.pagination['showInPage']

        $(this.idFind).css("height",Size)

        var up = $("<div><div class='paginationUp'><i class='fa fa-arrow-circle-up'>&nbsp;</i></div></div>")
        up.click(function(){
            document.getElementById($this.id).scrollTop -= $this.HeightItem;
        }).insertBefore(this.idFind);
        var down = $("<div><div class='paginationDown'><i class='fa fa-arrow-circle-down'>&nbsp;</i></div></div>")
        down.click(function(){
            if ($this.pagination['all_load'] == 0) {
                $this.postionItem += 1;
                $this.pagination['page'] += 1;
                if($this.postionItem >= $this.option['data'].length -2)
                    $this.addloadFromAajax();
            } else {
                $this.postionItem+=1;
                ($this.pagination['physical_presence'] == 1 )?$this.showItem():$this.setOption('data',$this.arrayPagination[$this.pagination['page']-1]);
            }
            $($this.idFind).scrollTop($($this.idFind).scrollTop()+$this.HeightItem)

        }).insertAfter(this.idFind);
    }
    createPaginationMore(){
        var $this = this;
        $("<div><div class='paginationMore'>More</div></div>").click(function(){
            $this.pagination['page']+=1;
            if($this.pagination['all_load'] == 0) {
                $this.addloadFromAajax();
            } else {
                ($this.pagination['physical_presence'] == 1 )?$this.showItem():$this.setOption('data',$this.arrayPagination[$this.pagination['page']-1]);
            }
        }).insertAfter(this.idFind);
    }
    createPaginationNum(){
        var pagination = null;
        var $this = this;
        if($(this.idFind+"_footer").length == 0){
            pagination = $("<div id='"+this.id+"_footer' class='simple_grid_footer'></div>").append($("<div class='pagination'></div>"));
            pagination.insertAfter(this.idFind);
        }
        var arreyStep = [];
        var page = parseInt(this.pagination['page']);
        var countItemStep = 10;
        if(page >= 8){
            countItemStep = (countItemStep + page) - 5 ;
            page-= 5
        }else{
            page = 1;
        }
        var arreyStep = [];
        for(var pageStep = page; pageStep <= countItemStep; pageStep++){arreyStep.push(pageStep);}

        var ul = $("<ul id='"+this.id+"_pagination'>");
        var buttomLeft = parseInt(this.pagination['page'])-1;
        var buttomRight =parseInt(this.pagination['page'])+1;
        if(buttomLeft > 1){
            var li = $("<li class='button-left'><i class='fa fa-caret-left'></i></li>").attr("data-page", buttomLeft).click(function () {
                $this.pageClick($(this).data('page'))
            })
            ul.append(li)
        }
        for(var i = 0; i < arreyStep.length-1; i++) {
            if(arreyStep[i] <= this.pagination['countPage']) {
                var li = $("<li><i>"+arreyStep[i]+"</i></li>").attr("data-page", arreyStep[i]).click(function () {
                    $this.pageClick($(this).data('page'))
                })
                if(arreyStep[i] == this.pagination['page']){li.addClass('active')}
                ul.append(li)
            }
        }
        ul.append($("<li class='button-right'>").append($("<div>...</div>")))
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
        $(this.idFind+"_pagination").remove()
        $(this.idFind+"_footer").find(".pagination").append(ul)
    }

    pageClick(page){
        this.pageOld = this.pagination['page'];
        this.pagination['page'] = page
        if(this.pagination['all_load'] == 0) {
            (this.option['pagination']['typePagination'] == 1)?this.loadFromAajax():this.addloadFromAajax()
        } else {
            (this.pagination['physical_presence']==0)?
                this.setOption('data', this.arrayPagination[this.pagination['page'] - 1]):this.showItem();
        }

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
    setUrlParams(params){
        this.params = params;
        this.option['params'] = params;
        this.loadFromAajax();
    }
    getUrlParams(){
        return this.params;
    }

}




