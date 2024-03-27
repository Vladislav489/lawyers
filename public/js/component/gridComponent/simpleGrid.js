class simpleGrid extends parentComponent {
    constructor(selector,option) {
        super(selector,option)
        this.HeightItem             = 0;
        this.pageOld                = 0;
        this.pagination             = this.initVariablePagination(option['pagination'])
        this.arrayPagination        = [];
        this.arrayPhysicalPresence  = [];
        this.postionItem            = (this.pagination['pageSize'] > 0) ? this.pagination['pageSize'] : this.pagination['showInPage'];
        this.templates()
    }
    initVariablePagination(pagination,copyParamIfExist = null) {
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
                if(key !== undefined &&  pagination[key] === undefined) pagination[key] = targrt[key];
        }
        return pagination;
    }
    checkParasms() {
        var $this = this;
        var sendParams = super.checkParasms();
        if (this.globalParams == false){
            sendParams['page'] = this.pagination['page'];
            sendParams['pageSize'] = this.pagination['pageSize'];
        }
        return sendParams;
    }
    templates() { this.templatehtml['cart_item'] = "";}
    staticPagination() {
        this.arrayPagination = array_chank(this.option['data'],this.pagination['showInPage']);
        this.pagination['countPage'] = this.arrayPagination.length
        return this.arrayPagination[this.pagination['page']-1];
    }
    hiddenitems(array) {// прячит элементы
        var buff = {};
        var flag = (this.pagination['typePagination'] == 3)? true:false;
        for(var key in array) buff[key] = flag;
        return buff;
    }
    showStart(array) {
        for(var i = 0; i < this.pagination['showInPage']; i++) array[i] = true;
        return array;
    }
    // работает только тогда когда есть физическая загрузка
    showItem() {
        //берем стартовую страницу и сколько показать (старт)
        var from = (this.pagination['page']-1)*this.pagination['showInPage']
        //сколько показать и добавляем те что уже показанны
        var to = from+parseInt(this.pagination['showInPage']);
        switch (this.pagination['typePagination']) {
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
                for(var index = from; index < to; index++){
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

    loadFromAajax() {
        var $this = this;
        super.callAjaxSuccess = function(data) {
            $this.pagination = $this.initVariablePagination($this.pagination,data['pagination']);
            if(data['column'] !== undefined)
                $this.setOption('column',data['column']);
        }
        super.loadFromAajax()
    }

    createWidget() {
        var $this = this;
        if (this.pagination['physical_presence'] == 0 && this.pagination['all_load'] == 1)
            this.data = this.staticPagination();
        super.createWidget();
        if(this.option['pagination']['showPagination'] == 1){
            switch (this.pagination['typePagination']) {
                case 1:
                    if (this.pagination['physical_presence'] == 0 && this.pagination['all_load'] == 1) {
                        this.staticPagination()
                    }
                    this.createPaginationNum()
                    break;
                case 2:
                    this.createPaginationMore()
                    break;
                case 3:
                    this.createPaginationArrow()
                    break;
            }
        }
    }


    createPaginationArrow(){
        var $this = this;
        var pagination = null;
        // if(this.pagination['all_load'] == 0) {
        //     this.pagination['page'] += 1;
        //     this.addloadFromAajax();
        // }
        if($("#"+this.id+"_footer").length == 0 && this.data !='null' && Array.isArray(this.data)  &&  this.pagination['totalCount'] > this.data.length){
            pagination = $("<div id='"+this.id+"_footer' class='simple_grid_footer'></div>").append($("<div id='pagination' class='pagination fs-section_pages'></div>"));
            pagination.insertAfter("#"+this.id+"_body");
        }
        // this.HeightItem = $("#"+this.id+"_body")[0].childNodes[0].clientHeight;
        // $("#"+$this.id+"_body").scrollTop(100)
        //
        // var Size = (this.pagination['all_load'] == 0)?
        //     this.HeightItem*this.pagination['pageSize']:this.HeightItem*this.pagination['showInPage']
        // $("#"+this.id+"_body").css("height",Size)

        var prev = $("<a href='#' class='fs-page_nav disabled mobile-hidden'>Предыдущая страница</a>")
        var next = $("<a href='#' class='fs-page_nav active mobile-hidden'>Следующая страница</a>")

        $('#pagination').append(prev)
        $('#pagination').append(next)

        prev.click(function(){
            if ($this.pagination['page'] > 1) {
                prev.prop('disabled', false)
                $this.pagination['page'] -= 1
                if($this.pagination['all_load'] == 0) {
                    $this.loadFromAajax();
                    $('#pagination').remove(prev)
                    $("#"+this.id+"_footer").find("#pagination").append(prev)
                }
            }
        });
        next.click(function() {
            $this.pagination['page'] += 1
            if($this.pagination['all_load'] == 0) {
                $this.loadFromAajax();
                $('#pagination').remove(next)
                $("#"+this.id+"_footer").find("#pagination").append(next)
            }
        });

    }
    createPaginationMore(){
        var $this = this;
        $("<button class=\"more-services\">Еще</button>").click(function(){
            $this.pagination['page'] += 1;
            if($this.pagination['all_load'] == 0) {
                $this.addloadFromAajax();
            } else {
                ($this.pagination['physical_presence'] == 1 )? $this.showItem():$this.setOption('data',$this.arrayPagination[$this.pagination['page']-1]);
            }
        }).insertAfter("#"+this.id+'_footer');
    }
    createPaginationNum() {
        var pagination = null;
        var $this = this;
        var arreyStep = [];
        var page = parseInt(this.pagination['page']);
        var countItemStep = 10;

        if($("#"+this.id+"_footer").length == 0 && this.data !='null' && Array.isArray(this.data)  &&  this.pagination['totalCount'] > this.data.length){
            pagination = $("<div id='"+this.id+"_footer' class='simple_grid_footer'></div>").append($("<div class='pagination'></div>"));
            pagination.insertAfter("#"+this.id+"_body");
        }
        if(page >= 8) {
            countItemStep = (countItemStep + page) - 5 ;
            page-= 5
        } else {
            page = 1;
        }
        for(var pageStep = page; pageStep <= countItemStep; pageStep++)
            arreyStep.push(pageStep);

        var ul = $("<ul id='"+this.id+"_pagination'>");
        var buttomLeft = parseInt(this.pagination['page']) - 1;
        var buttomRight = parseInt(this.pagination['page']) + 1;
        if(buttomLeft >= 1) {
            var li = $("<li class='button-left'><i class='fa fa-caret-left'></i></li>").attr("data-page", buttomLeft).click(function () {
                $this.pageClick($(this).data('page'))
            })
            ul.append(li)
        }
        for(var i = 0; i < arreyStep.length - 1;i++) {
            if(arreyStep[i] <= this.pagination['countPage']) {
                var li = $("<li><i>"+arreyStep[i]+"</i></li>").attr("data-page", arreyStep[i]).click(function () {
                    $this.pageClick($(this).data('page'))
                })
                if(arreyStep[i] == this.pagination['page']){li.addClass('active')}
                ul.append(li)
            }
        }
        // var pageNumbEnter = $("<div data-input_on='1' id='"+$this.id+"_numb'>...</div>").mouseenter(function(target){
        //     var obj = $(this);
        //     if(obj.data('input_on') == '1'){
        //         obj.data('input_on','0')
        //         var input  = $("<input type='number' style='width:40px' value='"+$this.pagination['page']+"' min='1' id='"+$this.id+"_input' name='page_number'/>").keypress(function(event) {
        //             if(event.keyCode === 13){
        //                 $this.pagination['page'] = $(this).val();
        //                 $this.loadFromAajax();
        //                 obj.data('input_on','1')
        //             }
        //         }).blur(function (event) {
        //             setTimeout(function () {
        //                 obj.html("...");
        //                 obj.data('input_on','1')
        //             },1000)
        //         });
        //         var button = $("<button>&#10132;</button>").click(function () {
        //             $this.pagination['page'] = $("#"+$this.id+"_input").val();
        //             $this.loadFromAajax();
        //             obj.data('input_on','1')
        //         })
        //         obj.html("")
        //         obj.append(input).append(button);
        //     }
        // })

        // ul.append($("<li class='button-right'>").append(pageNumbEnter))
        if( parseInt(this.pagination['page']) < (this.pagination['countPage'] - 9)) {
            var li = $("<li><i>" + this.pagination['countPage'] + "</i></li>").attr("data-page", this.pagination['countPage']).click(function () {
                $this.pageClick($(this).data('page'))
            })
            ul.append(li)
        }
        if( buttomRight <= this.pagination['countPage'] ){
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
            (this.pagination['typePagination'] == 1)? this.loadFromAajax():this.addloadFromAajax()
        } else {
            (this.pagination['physical_presence'] == 0 )?
                this.setOption('data', this.arrayPagination[this.pagination['page'] - 1]):this.showItem();
        }
    }
}
