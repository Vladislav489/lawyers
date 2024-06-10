@extends('lawyers.layouts.main')
@section('title', 'Чаты')

 @php
 $shouldFlex = true
 @endphp

@section('content')
    <section class="u-container chat-section">
        <div class="container">
            <div class="chat-container">
                <div class='chats-block'>
                @include('component_build',[
                    "component" => 'component.gridComponent.simpleGrid',
                    "params_component" => [
                        "autostart" => 'true',
                        "name" => "chat_list",
                        'url' => route__("actionGetChatList_mainstay_chat_chatmainstaycontroller"),
                        "template"=>"
                        <div>
                        <form action='#' class='search-chat'>
                            <span class='burger'></span>

                            <label>
                                <input type='search' name='chat-search' placeholder='Поиск'>
                                <input type='image' src='/lawyers/images/icons/search-messages-icon.svg' alt='search-icon'>
                            </label>
                        </form>

                        <div class='chats'>
                            <div class='chat popup-btn' data-popup='chat-popup' v-for=\"chat in data\" v-if=\"chat.last_message\" @click.prevent=\"openChat(chat.id)\">
                                <img src='/lawyers/images/main/lawyer-avatar.png' alt='avatar-img' class='chat-avatar'>
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
                </div>
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
                            <img src='/lawyers/images/main/lawyer-avatar.png' alt='avatar-img' class='chat-avatar'>
                            <div class='chat_info'>
                                <h4>@{{ data.name }}</h4>
                                <time>@{{ data.is_online !== undefined && data.is_online === 1 ? 'Онлайн' : data.last_online}}</time>
                            </div>
                        </div>

                        <div class='chat-header_buttons'>
                            <button class='chat-order' type='button' @click.prevent=\"makeAnOrder(data.chat_users)\">
                                Заказ <img src='/lawyers/images/icons/chat-order.svg' alt='order-icon'>
                            </button>
                            <button type='button'><img src='/lawyers/images/icons/search-messages-icon.svg' alt='search-icon'></button>
                            <button type='button'><img src='/lawyers/images/icons/more-icon.svg' alt='more-icon'></button>
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
                        <img src='/lawyers/images/main/lawyer-avatar.png' alt='avatar-img' class='chat-avatar'>
                        <div class='chat_info'>
                            <h4>@{{ data.name }}</h4>
                            <time>@{{ data.is_online !== undefined && data.is_online === 1 ? 'Онлайн' : data.last_online}}</time>
                        </div>
                    </div>

                    <div class='chat-header_buttons'>

                        <button type='button'><img src='/lawyers/images/icons/search-messages-icon.svg' alt='search-icon'></button>
                        <button type='button'><img src='/lawyers/images/icons/more-icon.svg' alt='more-icon'></button>
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
                        $(function(){
                            $('[data-name=message]').click(function() {
                                $(this).find('[data-name=delete-message]').fadeToggle(0);
                            });
                        });
					}",
                    "template" => "
                    <div class='message-wrapper' :id=\"name + '_body'\">
                        <div class='messages-container' id='messages_container' v-if=\"data.chat_messages\">

                            <div data-name='message' v-for=\"message in data.chat_messages.filter(item => !getNewMessages(data.chat_messages).includes(item))\" class='message-bubble'
                                :class=\"message.sender_user_id != data.auth_user ? 'other-message': 'your-message'\"
                                v-bind:data-message-id=\"message.id\"
                                v-bind:data-message-status=\"message.is_read\"
                                :id=\"'message' + message.id\">
                                <p v-if=\"message.message_type_id == 1\" data-message>@{{ message.message }}</p>
                                <p v-if=\"message.message_type_id != 1 && message.message.includes('chat/')\">
                                    <a @click=\"viewFile(message.message)\" class='chat-file'>@{{ trimFilePath(message.message) }}</a>
                                </p>
                                <p v-if=\"message.sender_user_id != data.auth_user\">@{{ message.time }}</p>
                                <time v-if=\"message.is_read && message.sender_user_id == data.auth_user\">@{{ message.time }}</time>
                                <span class='delete-message' data-name='delete-message' v-if=\"message.sender_user_id == data.auth_user\" @click=\"deleteMessage(message)\" :id=\"'delete_btn' + message.id\"></span>
                            </div>


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
                                <p v-if=\"message.sender_user_id != data.auth_user\">@{{ message.time }}</p>
                                <time v-if=\"message.is_read && message.sender_user_id == data.auth_user\"></time>
                            </div>



                        </div>
                        <div class='attached_files'>
                        <p id='has_attached_files'></p>
                        <a data-delete @click=\"deleteFiles()\" class='attached_files_del'></a>
                        </div>
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

    @include('js/chat-scripts')

@endsection
