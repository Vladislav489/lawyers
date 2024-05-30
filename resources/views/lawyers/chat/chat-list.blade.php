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

            @if(session()->get('type_id') == 1)
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
                            <button class='chat-order' type='button' @click.prevent=\"makeAnOrder(data.chat_users)\">
                                Заказ <img src='/lawyers/images/icons/chat-order.svg' alt='order-icon'>
                            </button>
                            <button type='button'><img src='/lawyers/images/icons/search-messages-icon.svg' alt='search-icon'></button>
                            <button type='button'><img src='/lawyers/images/icons/more-icon.svg' alt='more-icon' @click=\"toggleOptions()\"></button>
                            <ul id='chat_options' hidden>
                                <li><a @click.prevent=\"deleteChat(data.id)\">Удалить чат</a></li>
                            </ul>
                        </div>
                    </div>"
                    ]
                    ])
            @else
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

                        <button type='button'><img src='/lawyers/images/icons/search-messages-icon.svg' alt='search-icon'></button>
                        <button type='button'><img src='/lawyers/images/icons/more-icon.svg' alt='more-icon' @click=\"toggleOptions()\"></button>
                        <ul id='chat_options' hidden>
                            <li><a @click.prevent=\"deleteChat(data.id)\">Удалить чат</a></li>
                        </ul>
                    </div>
                </div>"
                ]
                ])
            @endif


                @include('component_build',[
	            "component" => 'component.gridComponent.simpleGrid',
                "params_component" => [
                    "autostart" => 'true',
                    "name" => "chat_window",
                    "url" => route("actionGetChatMessages_mainstay_chat_chatmainstaycontroller"),
                    "params" => [
						'chat_id' => request()->get('chat_id'),
						'page' => 1,
						'pageSize' => 20
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
                                @click.prevent=\"togglePopup(message.id)\">
                                <p v-if=\"message.message_type_id == 1\" data-message>@{{ message.message }}</p>
                                <p v-if=\"message.message_type_id != 1 && message.message.includes('chat/')\">
                                    <a @click=\"viewFile(message.message)\">
                                    <img src='/lawyers/images/icons/file-icon.svg' style='width:20px'>@{{ trimFilePath(message.message) }}</a>
                                </p>
                                <time v-if=\"message.is_read && message.sender_user_id == data.auth_user\">@{{ message.time }}</time>
                                <a v-if=\"message.sender_user_id == data.auth_user\"
                                 @click=\"deleteMessage(message)\" :id=\"'delete_btn' + message.id\" hidden>delete</a>
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

    <script>

        $(document).ready(function() {
        })

        function getHeight() {
            return document.querySelector('.messages-container').scrollHeight
        }

        function loadMoreMessages() {
            let currentChatHeight = getHeight()
            document.querySelector('.messages-container').addEventListener('scroll', function () {
                if (this.scrollTop === 0) {
                    let chatWindow = page__.getElementsGroup('chat_window')[0]['obj']

                    chatWindow.pagination['page'] += 1
                    let newChatHeight
                    addChatLoadFromAjax(chatWindow,
                        function (data) {
                            let $this = chatWindow
                            if (data['result']['chat_messages'].length != 0 && data != undefined) {
                                for (let index in data['result']['chat_messages']) {
                                    $this.option['data']['chat_messages'].unshift(data['result']['chat_messages'][index]);
                                }
                                $this.setOption('data',$this.option['data'])
                                setTimeout(function() {
                                    newChatHeight = getHeight()
                                    document.querySelector('.messages-container').scrollTo({
                                        top: newChatHeight - currentChatHeight
                                    }, 1000)
                                })
                                currentChatHeight = newChatHeight
                            }

                    })
                }
            })
        }

        function addChatLoadFromAjax(componentObj, callback) {
            var $this = componentObj;
            $.ajax({
                url: $this.urlAdi,
                type:'post',
                data: $this.checkParasms(),
                dataType:"json",
                success: callback
            });
        }

        function readMessage(messageId, messageStatus, chatId) {
            if (messageStatus === 0) {
                console.log('read', messageId, chatId, messageStatus)
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
            leaveOtherChatChannel()
            listenChatChannel(chatId)
            let chatWindow = getChatWindow()
            chatWindow.pagination['page'] = 1
            chatWindow.setUrlParams({
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
        }

        function getNewMessages(messages) {
            return messages.filter(message => message.is_read == 0 && message.sender_user_id != {{ auth()->id() }})
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
                    chatWindow.data.chat_messages.push(response)
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
            let recipientsObj = chat.chat_users.filter((user) => user.user_id !== {{ auth()->id() }} && user.is_deleted !== 1);
            if (!message) {
                return;
            }
            if (recipientsObj.length === 0) {
                alert('Кому ты пишешь? Ты один тут нахуй!')
                $('#message').val('')
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

        function togglePopup(messageId) {
            $('#delete_btn' + messageId).prop('hidden', !$('#delete_btn' + messageId).prop('hidden'))
        }

        function toggleOptions() {
            $('#chat_options').attr('hidden', !$('#chat_options').attr('hidden'))
        }

        function deleteMessage(message) {
            const messageId = message.id
            page__.sendData(
                '{{ route__('actionDeleteMessage_mainstay_chat_chatmainstaycontroller') }}',
                {
                    id: messageId,
                    user_id: message.sender_user_id
                },
                function(data) {
                    getChatWindow().data.chat_messages = getChatWindow().data.chat_messages.filter(message => message.id !== messageId)
                }
            )
        }

        function deleteChat(chatId) {
            page__.sendData(
                '{{ route__('actionChatDelete_mainstay_chat_chatmainstaycontroller') }}',
                {
                    chat_id: chatId
                },
                function (response) {
                    if (response) {
                        location.reload()
                    }
                }
            )
        }

        function leaveOtherChatChannel() {
            let channelsToLeave
            if (Object.keys(window.Echo.connector.channels).length > 0) {
                channelsToLeave = Object.keys(window.Echo.connector.channels)
                    .filter(channel => channel !== 'notification_channel' && !channel.includes('private-notification_user.'))
                channelsToLeave.forEach((channelName) => {
                    window.Echo.leave(channelName)
                })
            }
        }

        function listenChatChannel(chatId) {
            window.Echo.private('send_message.' + chatId)
                .listen('.send_message', (data) => {
                    let chatWindow = getChatWindow()
                    if (chatWindow.params.chat_id == data.chat_id) {
                        chatWindow.data.chat_messages.push(data)
                        setTimeout(function () {
                            readMessages(data.chat_id)
                        }, 200)
                    }
                })
                .listen('.read_message', (data) => {
                    let chatWindow = getChatWindow()
                    if (chatWindow.params.chat_id) {
                        chatWindow.data.chat_messages.find(item => item.id == data.id).is_read = data.is_read
                    }
                })
        }

        function getChatWindow() {
            let chatWindow
            if (page__.getElementsGroup('chat_window')[0] !== undefined) {
                chatWindow = page__.getElementsGroup('chat_window')[0]['obj']
            }
            return chatWindow
        }

        function makeAnOrder(chatUsers) {
            let employee = chatUsers.filter(user => user.user_id !== {{ auth()->id() }})
            location.href = `{{ route__('actionCreateVacancy_controllers_client_clientcontroller') }}?employee_id=${employee[0].user_id}&employee_name=${employee[0].name}`
        }

    </script>

@endsection
