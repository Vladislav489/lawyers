<script src="/js/jquery/jquery-3.6.0.min.js"></script>
<script src="/js/vue/vue.min.js"></script>
<script src="/js/main.js"></script>
<script src="/js/component/parentComponent.js"></script>
<script src="/js/dropzone/dropzone1/dropzone.min.js"></script>
<script src="/lawyers/scripts/jquery.fancybox.min.js"></script>
<script src="/lawyers/scripts/popup.js" type="module"></script>
<script src="/lawyers/scripts/select2.full.min.js"></script>
<script src="/lawyers/scripts/slick.min.js"></script>
<script src="/lawyers/scripts/script.js"></script>
<script data-create_page="front">
    var page__ = null;

    @if (!isset($admin))
        window.addEventListener('pageshow', PageShowHandler, false);
        window.addEventListener('pagehide',  PageShowHandler, false);
        window.addEventListener('beforeunload', UnloadHandler, false);

        function PageShowHandler() {
            window.addEventListener('beforeunload', UnloadHandler, false);
        }

        function UnloadHandler() {
            window.removeEventListener('beforeunload', UnloadHandler, false);
        }

        window.addEventListener('error', function (evt) {
            var errorText = [
                evt.message,
                'URL: ' + evt.filename,
                'Line: ' + evt.lineno + ', Column: ' + evt.colno,
                'Stack: ' + (evt.error && evt.error.stack || '(no stack trace)')
            ].join('\n');

            var DOM_ID = 'rendering-debug-pre';

            if (!document.getElementById(DOM_ID)) {
                var log = document.createElement('pre');
                log.id = DOM_ID;
                log.style.whiteSpace = 'pre-wrap';
                log.textContent = errorText;

                if (!document.body) {
                    document.body = document.createElement('body');
                }

                document.body.insertBefore(log, document.body.firstChild);
            } else {
                document.getElementById(DOM_ID).textContent += '\n\n' + errorText;
            }
        });
    @endif

    var global_data = @php echo json_encode([
        'route' => (isset($route)) ? $route : [],
        'params' => (isset($params_global)) ? $params_global : []
    ]) @endphp;

    global_data['token'] = '{{csrf_token()}}';
    page__ = new Page(global_data);
    InitHeader();
    page__.onLoadPage();
</script>
