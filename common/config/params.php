<?php
return [
    // 以下是框架内置配置参数
    'workermanSocketIOPort' => 30000,
    'workermanHttpWebPort'  => 20000,
    'webDomain'             => 'localhost',
    'uploadPath'            => '/uploadfile',
    'accessModule'          => ['admin'],

    // 交易产品列表，同时也是表名
    'productList'           => [
        'cl',
        'm0',
        'pp0',
        'sr0',
        'y0',
        'scbu',
        'zcsr',
        'dcpp',
    ],

    // 交易产品列表
    'productCode'           => [
        'cl'    => 'hf_CL',
        'scbu'=>'nf_BU0',
        'pp0' =>'nf_RB0',
        'y0'=>'hf_CHA50CFD',
        'm0'=>'hf_HSI',
        'sr0'=>'hkHSI',
        'zcsr'=>'hf_GC',
        'dcpp'=>'hf_SI'
    ],

    'bankList' => [
        'ICBC'    => '中国工商银行',
        'ABC'     => '中国农业银行',
        'BOC'     => '中国银行',
        'CCB'     => '中国建设银行',
        'PSBC'    => '中国邮政储蓄银行',
        'COMM'    => '交通银行',
        'CMB'     => '招商银行',
        'SPDB'    => '上海浦东发展银行',
        'CIB'     => '兴业银行',
        'HXBANK'  => '华夏银行',
        'GDB'     => '广东发展银行',
        'CMBC'    => '中国民生银行',
        'CITIC'   => '中信银行',
        'CEB'     => '中国光大银行',
        'EGBANK'  => '恒丰银行',
        'CZBANK'  => '浙商银行',
        'BOHAIB'  => '渤海银行',
        'SPABANK' => '平安银行',
    ],

];
