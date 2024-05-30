<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script type="module" src="{{asset('js/echo.js')}}"></script>
@auth()
<script type="module">
    import Echo from '{{asset('js/echo.js')}}'

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env('PUSHER_APP_KEY') }}',
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        wsHost: '{{ env('PUSHER_HOST') }}',
        wsPort: '{{ env('PUSHER_PORT') }}',
        forceTLS: false,
        disableStats: true,
    });

    window.Echo.private('notification_user.' + {{ auth()->id() }})
        .listen('.new_message_notification', (data) => {
            let chatList = page__.getElementsGroup('chat_list')[0]
            if (chatList !== undefined) {
                chatList['obj'].setUrlParams({})
            }
        })
</script>

@endauth


