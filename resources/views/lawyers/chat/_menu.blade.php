<?php

$items = [
    [
        'url' => 'actionChatList_chatcontroller',
        'text' => 'Список'
    ],
    [
        'url' => 'actionChatCreate_chatcontroller',
        'text' => 'Создание'
    ]
];

?>
<div class="btn-group my-3">

    @foreach ($items as $item)
        <a @class([
            'btn',
            'btn-outline-primary',
            'active' => Route::currentRouteName() === $item['url']
        ]) href="{{ route__($item['url']) }}">{{ $item['text'] }}</a>
    @endforeach

</div>
