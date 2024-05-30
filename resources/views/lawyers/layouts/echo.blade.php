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

    function getChatListComponent() {
        return page__.getElementsGroup('chat_list')[0]
    }

    function getChatWindow() {
        return page__.getElementsGroup('chat_window')[0]
    }

    window.Echo.private('notification_user.' + {{ auth()->id() }})
        .listen('.new_message_notification', (data) => {
            let chatList = getChatListComponent()
            if (chatList !== undefined) {
                chatList['obj'].setUrlParams({})
            }
            let notificationCounter = page__.getElementsGroup('notification_counter')[0]
            if (notificationCounter !== undefined) {
                notificationCounter['obj'].setUrlParams({user_id: {{ auth()->id() }} })
            }
        })
        .listen('.message_delete', (data) => {
            let chatList = getChatListComponent()
            let chatWindow = getChatWindow()
            if (chatList !== undefined) {
                chatList['obj'].setUrlParams({})
            }
            if (chatWindow !== undefined && chatWindow['obj'].params.chat_id == data.chat_id) {
                chatWindow['obj'].data.chat_messages = chatWindow['obj'].data.chat_messages.filter(message => message.id != data.deleted_message_id)
            }
        })
</script>

@endauth


