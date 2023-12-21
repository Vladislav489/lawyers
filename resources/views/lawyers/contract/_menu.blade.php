<?php

$items = [
    [
        'url' => 'actionContractList_contractcontroller',
        'text' => 'Список'
    ],
    [
        'url' => 'actionContractCreate_contractcontroller',
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
