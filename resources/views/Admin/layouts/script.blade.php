<script type="text/javascript" src="/js/jquery/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="/js/jquery/ui/alert.js"></script>
<script type="text/javascript" src="/js/jquery/ui/jquery-ui.js"></script>
<script type="text/javascript" src="/js/vue/vue.min.js"></script>
<script type="text/javascript" src="/js/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/main.js"></script>
<script data-create_page="admin">

    var page__ = null;
        var global_data = @php echo json_encode([
        "route"=>(isset($route))?$route:[],
        "params"=>$params_global]) @endphp;
        global_data['token'] = '{{csrf_token()}}';
        if(page__ == null)
            page__ = new Page(global_data);
        InitHeader();
        page__.onLoadPage();
</script>


