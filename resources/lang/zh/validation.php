<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | following language lines contain default error messages used by
    | validator class. Some of these rules have multiple versions such
    | as size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute必须接受',
    'active_url'           => ':attribute不是一个有效链接',
    'after'                => ':attribute必须在:date之后',
    'alpha'                => ':attribute只允许填写字母',
    'alpha_dash'           => ':attribute只允许填写允许字母、数字、破折号以及底线',
    'alpha_num'            => ':attribute只允许填写字母和数字',
    'array'                => ':attribute必须为一个数组',
    'before'               => ':attribute必须在:date之前',
    'between'              => [
        'numeric' => ':attribute在:min和:max之间',
        'file'    => ':attribute只允许在:min和:max字节之间',
        'string'  => ':attribute只允许有:min到:max个字符',
        'array'   => ':attribute只允许有:min到:max个项目',
    ],
    'boolean'              => ':attribute只能为布尔值',
    'confirmed'            => ':attribute不匹配',
    'date'                 => ':attribute不是一个有效日期',
    'date_format'          => ':attribute不匹配格式(:format)',
    'different'            => ':attribute和:other必须不同',
    'digits'               => ':attribute必须有:digits位',
    'digits_between'       => ':attribute必须在:min和:max位之间',
    'email'                => ':attribute必须为邮箱地址',
    'filled'               => ':attribute是必须填写的',
    'exists'               => '选项:attribute是无效的',
    'image'                => ':attribute必须为图片',
    'in'                   => '选项:attribute是无效的',
    'integer'              => ':attribute必须为整数',
    'ip'                   => ':attribute必须是一个ip地址',
    'max'                  => [
        'numeric' => ':attribute不得大于:max',
        'file'    => ':attribute不得大于:max字节',
        'string'  => ':attribute不得大于:max位',
        'array'   => ':attribute不得大于:max个项目',
    ],
    'mimes'                => ':attribute必须为:values格式的文件',
    'min'                  => [
        'numeric' => ':attribute不得小于:min',
        'file'    => ':attribute不得小于:min个字节',
        'string'  => ':attribute不得小于:min位',
        'array'   => ':attribute不得小于:min个项目',
    ],
    'not_in'               => '选项:attribute是无效的',
    'numeric'              => ':attribute必须为数字',
    'regex'                => ':attribute格式无效',
    'required'             => ':attribute字段是必须的',
    'required_if'          => ':attribute字段需要:other为:value',
    'required_with'        => '当:values存在时:attribute是必须的',
    'required_with_all'    => '当:values存在时:attribute是必须的',
    'required_without'     => '当:values不存在时:attribute是必须的',
    'required_without_all' => '当:values不存在时:attribute是必须的',
    'same'                 => ':attribute必须和:other匹配',
    'size'                 => [
        'numeric' => ':attribute必须为:size.',
        'file'    => ':attribute必须为:size个字节',
        'string'  => ':attribute必须为:size位',
        'array'   => ':attribute必须仅有:size个项目',
    ],
    'string'               => ':attribute必须为字符',
    'timezone'             => ':attribute必须为有效的时间戳',
    'unique'               => ':attribute已经存在了',
    'url'                  => ':attribute格式无效',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
