<?php
/**
 *
 * 微信关键词回复 - 验证
 *
 * @package   NiPHPCMS
 * @category  admin\validate\wechat
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2017/12
 */
namespace app\admin\validate\wechat;

use think\Validate;

class Keyword extends Validate
{
    protected $rule = [
        'id'      => ['require', 'number'],
        'keyword' => ['require', 'length:2,30', 'token'],
        'title'   => ['require', 'max:80'],
        'content' => ['require', 'max:500'],
        'type'    => ['require', 'number'],
        'image'   => ['max:250'],
        'url'     => ['max:500'],
        'status'  => ['require', 'number'],
    ];

    protected $message = [
        'id.require'      => '{%illegal operation}',
        'id.number'       => '{%illegal operation}',
        'keyword.require' => '{%error keyword require}',
        'keyword.length'  => '{%error keyword length not}',
        'title.require'   => '{%error title require}',
        'title.max'       => '{%error title max}',
        'content.require' => '{%error content require}',
        'content.max'     => '{%error content max}',
        'type.require'    => '{%error type require}',
        'type.number'     => '{%error type number}',
        'image.max'       => '{%error image max}',
        'url.max'         => '{%error url max}',
    ];

    protected $scene = [
        'added' => [
            'keyword',
            'title',
            'content',
            'type',
            'image',
            'url',
            'status',
        ],
        'editor' => [
            'id',
            'keyword',
            'title',
            'content',
            'type',
            'image',
            'url',
            'status',
        ],
    ];
}
