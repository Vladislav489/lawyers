<?php
use App\Models\System\RouteBilder;
use App\Models\System\Component\Component;



$routrList = json_encode(RouteBilder::getRotesAllRouts()['frontcontroller']);
return [
    'pagination'=>[
        'pageSize' => ['type' => 'int',
            'tag_option' => [
                'help'      => 'Тут указуется количество отображаемых элементов',
                'lable'     => 'Количество на стр.',
                'tag'       => 'input',
                'type'      => 'checkbox',
            ],
            'default' => 10
        ],
        'page' => ['type' => 'int',
            'tag_option' => [
                'help'      => '',
                'lable'     => 'стартовая стр.',
                'tag'       => 'input',
                'type '     => 'checkbox',
            ],
            'default' => 1
        ],
        'totalCount' => ['type'=>'int',
            'tag_option' => [
                'help'      => '',
                'lable'     => 'Общее количество',
                'tag'       => 'input',
                'type'      => 'checkbox'
            ],
            'default' => 0],
        'typePagination' => ['type' => 'int',
            'tag_option' => [
                'help'      => '',
                'lable'     => 'Тип пагинации',
                'tag'       => 'select',
                'field'     => ['id','name'],
                'data'      => json_encode([
                    ['id' => 1,'name' => 'Нумерованная'],
                    ['id' => 2,'name' =>"Больше"],
                    ['id' => 3,'name' => "Слайдер"],
                ])
            ],
            'default' =>1
        ],
        'showPagination' => ['type' => 'bool',
            'tag_option'=>[
                'help'      => '',
                'lable'     => 'Отображать пагинацию',
                'tag'       => 'input',
                'type'      => 'checkbox'
            ],
            'default' => false
        ],
        'all_load' => ['type'=>'bool',
            'tag_option' => [
                'help'      => '',
                'lable'     => 'Выгружать с запоменанием ',
                'tag'       => 'input',
                'type'      => 'checkbox'
            ],
            'default' => false
        ],
        'showInPage' => ['type' => 'int',
            'tag_option' => [
                'help'      => '',
                'lable'     => 'Сколько реально отображать на стр.',
                'tag'       => 'input',
                'type'      => 'checkbox'
            ],
            'default' => 10
        ],
        'physical_presence' => ['type'=>'bool',
            'tag_option' => [
                'help'      => '',
                'lable'     => 'Полная физическая выгрузка',
                'tag'       => 'input',
                'type'      => 'checkbox'
            ],
            'default' => false
        ]
    ],
    'autostart' => ['type' => 'bool',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Авто старт нуден для Ajax',
            'tag'       => 'input',
            'type'      => 'checkbox'
        ],
        'default' => false
    ],
    'url' => ['type' => 'text',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Путь к дкйствию на фронте',
            'tag'       => 'select',
            'field'     => ['url','clearAction'],
            'data'      => $routrList,
            'required'  => true,
        ] ,
        'default' => 0
    ],
    'name' => ['type' => 'text',
        'tag_option'=>[
            'help'      => '',
            'lable'     => 'Имя компонента',
            'tag'       => 'input',
            'type'      => 'text',
            'pattern'   =>'[A-z0-9_]',
            'required'  => true,
        ],
        'default' => 'noName'
    ],
    'data' => ['type' => 'json',
        'tag_option'=>[
            'help'      => '',
            'lable'     => 'Данные для стартовой загрузки без Ajax',
            'tag'       => 'textarea',
            'pattern'   =>'^{.*}$'
        ],
        'default' =>1
    ],
    'globalData' => ['type' => 'text',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Зарузка данных из loadGlobalData (пердаем имя данных) ',
            'tag'       => 'input',
            'type'      => 'text',
            'pattern'   =>'[A-z0-9_]'
        ],
        'default' => false
    ],
    'template' => ['type' => 'html',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Шаблон компонента(HTML (формат VUE)) или имя готового',
            'tag'       => 'textarea'
        ],
        'default' => ''
    ],
    'globalParams' => ['type' => 'bool',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Включает прослушку параметров из Урл ',
            'tag'       => 'input',
            'type'      => 'checkbox'
        ],
        'default' => false
    ],
    'params' => ['type'=>'json',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Стартовые фильтры данных ',
            'tag'       => 'input',
            'type'      => 'text',
            'pattern'   =>'^{.*}$'
        ],
        'default' => null
    ],
    'target' => ['type' => 'json',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Установка урла если есть переходы с комронента',
            'tag'       => 'textarea',
            'pattern'   =>'^{.*}$'
        ],
        "sub"=>[
            'route'   => ['type' => 'text','tag_option' => ['help' => '', 'lable' =>'','tag'=>'select'] ,"default" => false],
            'format'  => ['type' => 'text','tag_option' => ['help' => '', 'lable' =>'', 'tag' => 'select'] ,"default" => false],
            'params'  => ['type' => 'json','tag_option' => ['help' => '', 'lable' =>'','tag' => 'input','type'=>'text'],
                'default' => null],
        ]
    ],
    'include' => ['type' => 'bool',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Подключть как js,css файл',
            'tag'       => 'input',
            'type'      => 'checkbox'
        ],
        'default' => false
    ],
    'ssr' => ['type' => 'bool',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Загрузка данных при создании стр. без Ajax',
            'tag'       =>'input',
            'type'      =>'checkbox'],
        'default' => true
    ],
    'includeFromHeadToDown' => ['type'=>'bool',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Подключать скриты по ходу зарузки стр. ,или  после загркзки стр.',
            'tag'       => 'input',
            'type'      => 'checkbox'
        ],
        'default' => false],
    'column' => ['type' => 'json',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Название верхних колонок',
            'tag'       => 'input',
            'type'      => 'text',
            'pattern'   =>'^{.*}$'
        ],
        'default' => null
    ],
    'union' => ['type' => 'json',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Выполнять все запросы как объединеные',
            'tag'       => 'input',
            'type'      =>'checkbox'
        ],
        'default' => null],
    'union_group' => ['type' => 'json',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Кретерий объединение данных',
            'tag'       => 'input',
            'type'      =>'checkbox'
        ],
        'default' => null],
    'add_params' => ['type' => 'json',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Добавочные параметры для Под комонента',
            'tag'       => 'input',
            'type'      => 'checkbox'
        ],
        'default' => null],
    'name_group' => ['type' => 'text',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Поле которое будут в заголовке группы',
            'tag'       => 'input',
            'type'      => 'text',
            'pattern'   => '[A-z0-9_]'

        ],
        'default' =>''],
    'include_component' =>['type' => 'component',
        'tag_option' =>[
            'help'      => '',
            'lable'     => 'Под компонет',
            'tag'       => 'select',
            'field'     => ['id','lable'],
            'data'      => '',
        ],
    ],
    'default_title' => ['type' => 'text',
        'tag_option'=>[
            'help'      => '',
            'lable'     => 'Стартовое значение для списка',
            'tag'       => 'input',
            'type'      => 'checkbox'
        ],
        'default' => false
    ],
    'change' => ['type' => 'callbak',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Сallbak изменения',
            'tag'       => 'input',
            'type'      => 'text'],
        'default' => null
    ],
    'focus' => ['type' => 'callbak',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Сallbak фокус',
            'tag'       => 'input',
            'type'      => 'text'
        ],
        'default' => null
    ],
    'select' => ['type' => 'callbak',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Сallbak выбора',
            'tag'       => 'input',
            'type'      => 'text'
        ],
        'default' =>null
    ],
    'clear_name'  => ['type'=>'text',
        'tag_option'=>[
            'help'      => '',
            'lable'     => 'Чистое имя',
            'tag'       => 'input',
            'type'      => 'text'
        ],
        'default' =>"loadData"
    ],
    'callback' => ['type'=>'callbak',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Сallbak функция',
            'tag'       => 'input',
            'type'      => 'text'
        ],
        'default' => null
    ],
    'templateItem'=> ['type' => 'html',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Под темплейт для меню',
            'tag'       => 'textarea'
        ],
        'default' =>''
    ],
    'typeMenu' => ['type' => 'text',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Тип меню',
            'tag'       =>'select',
            'field'     => ['id','name'],
            'data'      => json_encode([
                ['id'=>1,'name'=>"Дроп клик вертикальное меню"],
                ['id'=>2,'name'=>"Горизонтпльное всплывающее меню"],
                ['id'=>3,'name'=>"Вертикальное всплывающее меню"],
                ['id'=>4,'name'=>"Кубическое горизонтальное меню "],
            ])
        ] ,
        'default' => 0
    ],
    'targetObject'=> ['type' => 'json',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Объект из стрпаницы',
            'tag'       => 'input',
            'type'      => 'text',
            'pattern'   =>'[A-z0-9_]'
        ],
        'default' => null
    ],
    'format' => ['type' => 'text',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Формат вывода',
            'tag'       => 'input',
            'type'      => 'text'
        ],
        'default' => ''],

    'indefication'=> ['type' => 'text',
        'tag_option' => [
            'help'      => '',
            'lable'     => 'Индификатор для подключаемого компонента',
            'tag'       => 'input',
            'type'      => 'text',
            'pattern'   =>'[A-z0-9_]'
        ],
        'default' => ''],
];


