var inncSimpleGrid = (function () {
    var i = 1;
    return function () {return i++;}
})();

class Pagination{
    constructor(selector,option){
        this.option         = option;
        this.targetObject   = (this.option['targetObject']!== undefined)?this.option['targetObject']:null;
        this.countButton    = (this.option['countButton']!== undefined)?this.option['countButton']:10;
        this.pagination     = this.initVariablePagination(this.option['pagination'])
        this.id             = this.option['name']
        this.params         = (this.option['params'] !== undefined)?this.option['params']:{};
        this.target         = (this.option['target'] != undefined)?this.option['target']:false;
        this.createWidget();
    }
    initVariablePagination(pagination,copyParamIfExist = null){
        var defaultPagination = {
            'pageSize':10, 'page':1, 'totalCount':0,
            'typePagination':0, 'showPagination':0,
            'countPage':1, 'showInPage':10, 'all_load':0,'physical_presence':0
        }
        var targrt = (copyParamIfExist == null)? defaultPagination:copyParamIfExist;
        for(var key in defaultPagination){
            if(copyParamIfExist != null ){
                if(targrt[key] != undefined) pagination[key] = parseInt(targrt[key]);
            }else{
                if(pagination[key] === undefined) pagination[key] = parseInt(targrt[key]);
            }
        }
        return pagination;
    }
    targetBuild(page){
        var templateUrl = "";
        var utlTarget = [];
        if(this.target['params'] != undefined) {
            for (var keys in this.target['params']) {
                var value = this.target['params'][keys]['value']
                var filter = this.target['params'][keys]['filter'] + "=" + value;
                utlTarget.push(filter);
            }
        }
        utlTarget.push("page="+page);
        utlTarget.push("pageSize="+this.pagination['pageSize']);
        templateUrl  = this.target['route']+"?"+utlTarget.join("&");
        return templateUrl;
    }
    createWidget() {
        var $this = this;
        var interval = setInterval(function () {
            if($this.targetObject.completeLoad == true){
                $this.pagination['page']  = $this.targetObject.pagination['page'];
                $this.pagination['countPage'] = $this.targetObject.pagination['countPage'];
                $this.pagination['pageSize'] = $this.targetObject.pagination['pageSize'];
                $this.pagination['totalCount'] = $this.targetObject.pagination['totalCount'];

                switch ($this.pagination['typePagination']) {
                    case 1:
                        $this.createPaginationNum()
                        break;
                    case 3:
                        $this.createPaginationArrow()
                        break;
                }
                clearInterval(interval);
            }
        },700);
    }
    createPaginationArrow(){
        var up =  $("<div><div class='paginationUp'><a href='"+this.targetBuild((this.pagination['page']+1))+"'><i class='fa fa-arrow-circle-up'>&nbsp;</i></a></div></div>")
        up.insertAfter("#" + this.targetObject + '_body');
        var down = $("<div><div class='paginationDown'><a href='"+this.targetBuild((this.pagination['page']+1))+"'><i class='fa fa-arrow-circle-down'>&nbsp;</i></a></div></div>")
        down.insertAfter("#" + this.targetObject + '_body');
    }
    createPaginationNum(){
        var pagination = null;
        var $this = this;
        if($("#"+this.targetObject.id+"_footer").length == 0){
            pagination = $("<div id='"+this.targetObject.id+"_footer' class='simple_grid_footer'></div>").append($("<div class='pagination'></div>"));
            pagination.insertAfter("#"+this.targetObject.id+"_body");
        }
        var arreyStep = [];
        var page = parseInt(this.pagination['page']);
        var countItemStep =  this.countButton;
        if(page >=  parseInt(countItemStep/2)+3){
            countItemStep = (countItemStep + page) - parseInt(countItemStep/2);
            page-= parseInt(countItemStep/2);
        }else{
            page = 1;
        }
        var arreyStep = [];
        for(var pageStep = page; pageStep <= countItemStep; pageStep++){arreyStep.push(pageStep);}

        var ul = $("<ul id='"+this.targetObject.id+"_pagination'>");
        var buttomLeft = parseInt(this.pagination['page'])-1;
        var buttomRight =parseInt(this.pagination['page'])+1;
        if(buttomLeft > 1){
            var li = $("<li class='button-left'><a href='"+this.targetBuild((this.pagination['page']-1))+"'>" +
                "<i class='fa fa-caret-left'></i></a></li>").attr("data-page", buttomLeft)
            ul.append(li)
        }
        for(var i = 0; i < arreyStep.length-1;i++){
            if(arreyStep[i] <= this.pagination['countPage']) {
                var li = $("<li><a href='"+this.targetBuild((arreyStep[i]))+"'><i>"+arreyStep[i]+"</i></a></li>").attr("data-page", arreyStep[i])
                if(arreyStep[i] == this.pagination['page']){li.addClass('active')}
                ul.append(li)
            }
        }
        ul.append($("<li class='button-right'>").append($("<div>...</div>")))
        if( parseInt(this.pagination['page']) < (this.pagination['countPage'] - 9)) {
            var li = $("<li><i>" + this.pagination['countPage'] + "</i></li>").attr("data-page", this.pagination['countPage'])
            ul.append(li)
        }
        if( buttomRight < this.pagination['countPage'] ){
            var li = $("<li class='button-right'><a href='"+this.targetBuild((this.pagination['page']+1))+"'><i class='fa fa-caret-right'></i></a></li>").attr("data-page",buttomRight)
            ul.append(li);
        }
        $("#"+this.targetObject.id+"_pagination").remove()
        $("#"+this.targetObject.id+"_footer").find(".pagination").append(ul)
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
    setUrlParams(params){
        if(this.params.length > 0 ){
            this.params.concat(params);
        }else{
            this.params = params;
        }
        this.loadFromAajax();
    }
    getUrlParams(){
        return this.params;
    }
}




