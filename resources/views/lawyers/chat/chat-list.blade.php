@extends('lawyers.layouts.main')
@section('title', 'Чаты')

@section('content')
    <section class="gradient-bg u-container chat-section">
        <div class="container">
            <div class="chat-container">

            @include('component_build',[
	            "component" => 'component.gridComponent.simpleGrid',
                "params_component" => [
                    "autostart" => 'true',
                    "name" => "chat_list",
					'url' => route__("actionGetChatList_mainstay_chat_chatmainstaycontroller"),
                    "template"=>"
                <div class='chats-block'>
                    <form action='#' class='search-chat'>
                        <span class='burger'></span>

                        <label>
                            <input type='search' name='chat-search' placeholder='Поиск'>
                            <input type='image' src='/lawyers/images/icons/search-messages-icon.svg' alt='search-icon'>
                        </label>
                    </form>

                    <div class='chats'>
                        <div class='chat popup-btn' data-popup='chat-popup' v-for=\"chat in data\" v-if=\"chat.last_message\" @click.prevent=\"openChat(chat.id)\">
                            <img src='/lawyers/images/main/lawyer-avatar.png' alt='avatar-img' class='lawyer-avatar'>
                            <div class='chat_right'>
                                <h3 class='chat_title'>
                                    @{{ chat.name }}
                                    <img src='/lawyers/images/icons/chat-verify.svg' alt='verify-img' class='chat-verify'>
                                    <time>@{{ chat.last_message.last_message_time }}</time>
                                </h3>
                                <p class='chat-preview'>
                                    @{{ chat.last_message.message }}
                                    <span class='chat-new-message' v-if=\"chat.count_new_messages\">@{{ chat.count_new_messages }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                ",
                ]
            ])

                <div class='chat-window'>
                @include('component_build',[
	            "component" => 'component.infoComponent.textInfo',
                "params_component" => [
                    "autostart" => 'true',
                    "name" => "chat_header",
                    "url" => route("actionGetChatInfo_mainstay_chat_chatmainstaycontroller"),
                    "params" => ['id' => request()->get('chat_id')],
					"callAfterloadComponent" => "function() {
					}",
                    "template" => "<div class='chat-window_header'>
                        <div class='chat-header_left'>
                            <img src='/lawyers/images/main/lawyer-avatar.png' alt='avatar-img' class='lawyer-avatar'>
                            <div class='chat_info'>
                                <h4>@{{ data.name }}</h4>
                                <time>был в сети 5 мин назад</time>
                            </div>
                        </div>

                        <div class='chat-header_buttons'>
                            <button class='chat-order' type='button'>
                                Заказ <img src='/lawyers/images/icons/chat-order.svg' alt='order-icon'>
                            </button>
                            <button type='button'><img src='/lawyers/images/icons/search-messages-icon.svg' alt='search-icon'></button>
                            <button type='button'><img src='/lawyers/images/icons/call-icon.svg' alt='call-icon'></button>
                            <button type='button'><img src='/lawyers/images/icons/more-icon.svg' alt='more-icon' @click=\"chatOptions()\"></button>
                            <ul id='chat_options' hidden>
                                <li><a @click.prevent=\"deleteChat(data.id)\">Удалить чат</a></li>
                                <li>Заблокировать пользователя</li>
                                <li></li>
                            </ul>
                        </div>
                    </div>"
                    ]
                    ])


                @include('component_build',[
	            "component" => 'component.gridComponent.simpleGrid',
                "params_component" => [
                    "autostart" => 'true',
                    "name" => "chat_window",
                    "url" => route("actionGetChatMessages_mainstay_chat_chatmainstaycontroller"),
                    "params" => [
						'chat_id' => request()->get('chat_id'),
						'page' => '1',
						'pageSize' => '20'
						],
					"callAfterloadComponent" => "function() {
					    if(this.params.chat_id) {
                            readMessages(this.params.chat_id)
                        }
					}",
                    "template" => "
                    <div class='message-wrapper' :id=\"name + '_body'\">
                        <div class='messages-container' id='messages_container' v-if=\"data.chat_messages\">

                            <div v-for=\"message in data.chat_messages.filter(item => !getNewMessages(data.chat_messages).includes(item))\" class='message-bubble'
                                :class=\"message.sender_user_id != data.auth_user ? 'other-message': 'your-message'\"
                                v-bind:data-message-id=\"message.id\"
                                v-bind:data-message-status=\"message.is_read\"
                                :id=\"'message' + message.id\"
                                @click.right=\"console.log(111)\">
                                <p v-if=\"message.message_type_id == 1\" data-message>@{{ message.message }}</p>
                                <p v-if=\"message.message_type_id != 1 && message.message.includes('chat/')\">
                                    <a @click=\"viewFile(message.message)\">
                                    <img src='/lawyers/images/icons/file-icon.svg' style='width:20px'>@{{ trimFilePath(message.message) }}</a>
                                </p>
                                <time v-if=\"message.is_read && message.sender_user_id == data.auth_user\">@{{ message.time }}</time>
                            </div>

                        <div>
                            <div class='messages-data' v-if=\"getNewMessages(data.chat_messages).length > 0\">
                                <time></time>
                                <span>Новые сообщения</span>
                            </div>

                            <div v-for=\"message in getNewMessages(data.chat_messages)\" class='message-bubble'
                             :class=\"message.sender_user_id != data.auth_user ? 'other-message': 'your-message'\"
                             v-bind:data-message-id=\"message.id\"
                             v-bind:data-message-status=\"message.is_read\">
                                <p v-if=\"message.message_type_id == 1\" data-message>@{{ message.message }}</p>
                                <p v-if=\"message.message_type_id != 1 && message.message.includes('chat/')\">
                                    <a @click=\"viewFile(message.message)\">
                                    <img src='/lawyers/images/icons/file-icon.svg' style='width:20px'>@{{ trimFilePath(message.message) }}</a>
                                </p>
                                <time v-if=\"message.is_read && message.sender_user_id == data.auth_user\">@{{ message.time }}</time>
                            </div>
                        </div>


                        </div>

                        <div id='has_attached_files'></div>
                        <a data-delete hidden @click=\"deleteFiles()\">x</a>
                        <div class='send-message-input'>
                            <label>
                                <span class='attach-icon'>
                                    <input type='file' id='file' @change=\"showFiles($('#file'))\">
                                </span>
                                <input type='text' placeholder='Введите сообщение...' name='message-text' id='message'
                                @keyup.enter=\"sendMessage($('#message').val())\">

                                <input type='image' src='/lawyers/images/icons/send-icon.svg' alt='send-message-icon'
                                 @click.prevent=\"sendMessage($('#message').val())\">
                            </label>
                        </div>
                    </div>
                    ",
                    'pagination'=>
                    [
                        'page'=> 1,
                        'pageSize'=> 20,
                        'countPage'=> 1,
                        'typePagination'=> 1,
                        'showPagination'=> 0,
                        'showInPage'=> 4,
                        'count_line'=> 1,
                        'all_load'=> 0,
                        'physical_presence'=> 0
                    ],
                ]
            ])
                </div>

            </div>
        </div>
    </section>

{{--    @include('js.util')--}}
{{--    @include('js.validation')--}}
{{--    @include('js.async-api')--}}
{{--    @include('js.delete-handler')--}}
    <script>

        $(document).ready(function() {
            readMessages()
        })

        function loadMoreMessages() {
            let currentChatHeight = document.querySelector('.messages-container').scrollHeight
            document.querySelector('.messages-container').addEventListener('scroll', function () {
                if (this.scrollTop === 0) {
                    let chatWindow = page__.getElementsGroup('chat_window')[0]['obj']
                    // let topMessageId = chatWindow.data.chat_messages[0].id
                    // console.log(chatWindow.vueObject)

                    chatWindow.pagination['page'] += 1
                    chatWindow.addChatLoadFromAajax()
                    let newChatHeight = this.scrollHeight
                    console.log(currentChatHeight, newChatHeight)
                    let top
                    if (currentChatHeight === newChatHeight) {
                        top = currentChatHeight
                    } else {
                        top = newChatHeight - currentChatHeight
                    }
                    console.log(top)
                    this.scrollTo({
                        top: top
                    })
                    currentChatHeight = newChatHeight
                }
            })
        }

        function readMessage(messageId, messageStatus, chatId) {
            if (messageStatus == 0) {
                page__.sendData(
                    '{{ route__('actionReadMessage_mainstay_chat_chatmainstaycontroller') }}',
                    {
                        id: messageId,
                        is_read: 1,
                        chat_id: chatId,
                        user_id: {{ auth()->id() }}
                    },
                    function (response) {
                        let chatWindow = page__.getElementsGroup('chat_window')[0]['obj']
                        chatWindow.data.chat_messages.find(item => item.id == response.id).is_read = 1
                        page__.getElementsGroup('chat_list')[0]['obj'].setUrlParams({})
                    },
                    function (error) {
                        console.log(error);
                    }
                )
            }
        }

        function getNewMessages(messages) {
            return messages.filter(message => message.is_read == 0 && message.sender_user_id != {{ auth()->id() }})
        }

        function readMessages(chatId) {
            const options = {
                root: null,
                rootMargin: '0px',
                threshold: 0.5
            }
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const messageId = parseInt(entry.target.getAttribute('data-message-id'));
                        const messageStatus = parseInt(entry.target.getAttribute('data-message-status'));
                        readMessage(messageId, messageStatus, chatId)
                        observer.unobserve(entry.target);
                    }
                });
            }, options)
            document.querySelectorAll('.other-message').forEach((elem) => {
                observer.observe(elem)
            })

        }

        function openChat(chatId) {
            page__.getElementsGroup('chat_window')[0]['obj'].setUrlParams({
                chat_id: chatId,
                page: 1,
                pageSize: 20
            })
            page__.getElementsGroup('chat_header')[0]['obj'].setUrlParams({
                id: chatId
            })
            setTimeout(function () {
                scrollChatDown()
                loadMoreMessages()
            }, 250);
            // setTimeout(function () {
            //     readMessages(chatId)
            // }, 250)
        }

        function scrollChatDown() {
            let container = document.getElementById("messages_container")
            container.scrollTo({
                top: container.scrollHeight
            })
        }

        function sendFormData(data) {
            $.ajax({
                method: 'POST',
                data: data,
                contentType: false,
                processData: false,
                url: '{!! route__('actionSendMessage_mainstay_chat_chatmainstaycontroller') !!}',
                success: function (response) {
                    let chatWindow = page__.getElementsGroup('chat_window')[0]['obj']
                    // if (chatWindow.data.chat_messages[chatWindow.data.chat_messages.length - 1].message === response.message) {
                    //     chatWindow.data.chat_messages[chatWindow.data.chat_messages.length - 1].message = response.message
                    // } else {
                        chatWindow.data.chat_messages.push(response)
                    // }

                    page__.getElementsGroup('chat_list')[0]['obj'].setUrlParams({})
                    setTimeout(function () {
                        scrollChatDown()
                    }, 10);
                }
            })
        }

        function sendMessage(message) {
            if ($('#file')[0].files.length > 0) {
                let files = $('#file')[0].files[0]
                deleteFiles()
                sendMessage(files)
            }
            let chat = page__.getElementsGroup('chat_header')[0].obj.data
            console.log(chat)
            let recipientsObj = chat.chat_users.filter((user) => user.user_id !== {{ auth()->id() }});
            if (!message) {
                return;
            }
            let recipientsArr = recipientsObj.map((user) => user.user_id);
            let data = new FormData()
            data.append('recipients', JSON.stringify(recipientsArr))
            data.append('sender_user_id', {!! auth()->id() !!})
            data.append('target_user_id', recipientsObj[0].user_id)
            data.append('chat_id', chat.id)
            if (typeof message === 'string') {
                data.append('message_type_id', '1')
                data.append('message', message)
            } else {
                data.append('message_type_id', '2')
                data.append('files', message)
            }
            $('#message').val('')
            // let json = {}
            // data.forEach((value, key) => json[key] = value);
            // let currentMessage = JSON.stringify(json);
            // page__.getElementsGroup('chat_window')[0]['obj'].data.chat_messages.push(JSON.parse(currentMessage))
            // scrollChatDown()
            sendFormData(data)
        }

        function trimFilePath(path) {
            const parts = path.split('/')
            return parts[parts.length - 1];
        }

        function viewFile(path) {
            const route = `{{ route('download') }}?path=${path}`
            window.open(route)
        }

        function showFiles(element) {
            let counter = $('input#file')[0].files.length;
            if (counter > 0 && counter != null && counter != undefined) {
                $('#has_attached_files').text(counter + ' прикрепленный файл')
                $('[data-delete]').attr('hidden', false)
            }
        }

        function deleteFiles(){
            $('#file').val('')
            $('#has_attached_files').text('')
            $('[data-delete]').attr('hidden', true)
        }

        function chatOptions() {
            $('#chat_options').attr('hidden', !$('#chat_options').attr('hidden'))
        }

        function deleteChat(chatId) {
            page__.sendData(
                '{{ route__('actionChatDelete_mainstay_chat_chatmainstaycontroller') }}',
                {
                    id: chatId
                },
                function (response) {
                    if (response) {
                        location.reload()
                    }
                }
            )
        }

    </script>

    <script type="module">
        import Echo from '{{asset('js/echo.js')}}'

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            wsHost: '{{ env('PUSHER_HOST') }}',
            wsPort: '{{ env('PUSHER_PORT') }}',
            forceTLS: true,
            disableStats: true,
        });

        function getChatWindow() {
            return page__.getElementsGroup('chat_window')[0]['obj']
        }

        window.Echo.channel('send_message')
            .listen('.send_message', (data) => {
                console.log('send_message')
                let chatWindow = getChatWindow()
                if (chatWindow.params.chat_id == data.chat_id) {
                    chatWindow.data.chat_messages.push(data)
                    setTimeout(function () {
                        readMessages(data.chat_id)
                    }, 200)
                }
                page__.getElementsGroup('chat_list')[0]['obj'].setUrlParams({})
            }).listen('.read_message', (data) => {
                let chatWindow = getChatWindow()
                if (chatWindow.params.chat_id) {
                    chatWindow.data.chat_messages.find(item => item.id == data.id).is_read = data.is_read
                }
            })
    </script>
@endsection
