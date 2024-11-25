<?php

return [
    'paginate' => [
        'display_count' => 5,
    ],
    'filter' => [
        'all' => [
            'method' => null,
            'label' => 'すべて',
        ],
        'overDeadline' => [
            'method' => 'overDeadline',
            'label' => '期限切れ',
        ],
        'noDeadline' => [
            'method' => 'noDeadline',
            'label' => '期限なし',
        ],
        'noUser' => [
            'method' => 'noUser',
            'label' => '担当者未設定',
        ],
    ],
];
