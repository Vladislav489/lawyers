<script>
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
                if (chatWindow.pagination['page'] > chatWindow.pagination.countPage) {
                    return
                }
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
                    let chatList = getChatList()
                    if (chatList !== undefined && chatList !== null) {
                        chatList['obj'].setUrlParams({})
                    }
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
            checkOnline(chatId)
            readMessages(chatId)
            setInterval(function () {checkOnline(chatId)}, 12000)
        }, 300);
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
                let chatList = getChatList()
                if (chatList !== undefined && chatList !== null) {
                    chatList['obj'].setUrlParams({})
                }
                setTimeout(function () {
                    scrollChatDown()
                }, 10);
            },
            error: function (error) {
                if (error.status == 422) {
                    let messages = error.responseJSON.errors
                    alert (messages.files[0])
                }

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
            alert('Не удалось отправить сообщение, так как собеседник удалил чат')
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
            $('[data-delete]').parent().addClass('active');
        }
    }

    function deleteFiles(){
        $('#file').val('')
        $('#has_attached_files').text('')
        $('[data-delete]').parent().removeClass('active');
    }

    function togglePopup(messageId) {
        $('#delete_btn' + messageId).prop('hidden', !$('#delete_btn' + messageId).prop('hidden'))
    }

    function toggleOptions() {
        $('#chat_options').attr('hidden', !$('#chat_options').attr('hidden'))
    }

    function deleteMessage(message) {
        const messageId = message.id
        const chatId = getChatWindow().params.chat_id
        page__.sendData(
            '{{ route__('actionDeleteMessage_mainstay_chat_chatmainstaycontroller') }}',
            {
                id: messageId,
                user_id: message.sender_user_id,
                chat_id: chatId,
                recipients: message.recipients
            },
            function(data) {
                getChatWindow().data.chat_messages = getChatWindow().data.chat_messages.filter(message => message.id !== messageId)
                let chatList = getChatList()
                if (chatList !== undefined && chatList !== null) {
                    chatList['obj'].setUrlParams({})
                }
            }
        )
    }

    function getChatList() {
        return page__.getElementsGroup('chat_list')[0]
    }

    function checkOnline(chatId) {
        if (chatId === null || chatId === undefined) {
            return
        }
        let chatHeader = page__.getElementsGroup('chat_header')[0]['obj']
        page__.sendData(
            '{{ route__('actionCheckIfOnline_mainstay_user_usermainstaycontroller') }}',
            {
                user_id: chatHeader.data.chat_users.find(user => user.user_id !== {{ auth()->id() }}).user_id,
            },
            function(data) {
                if (data) {
                    chatHeader.data.is_online = 1
                    chatHeader.updateVue()
                } else {
                    chatHeader.data.is_online = 0
                    chatHeader.updateVue()
                }
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
