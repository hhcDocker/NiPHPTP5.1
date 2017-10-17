<?php
/**
 *
 * 管理栏目 - 栏目 - 验证器
 *
 * @package   NiPHPCMS
 * @category  admin\validate\category
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: Category.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\admin\validate\category;

use think\Validate;

class Category extends Validate
{
    protected $rule = [
        'id'        => ['require', 'number'],
        'name'      => ['require', 'length:2,255', 'unique:category', 'token'],
        'aliases'   => ['length:4,255', 'alpha', 'unique:category'],
        'type_id'   => ['require', 'number'],
        'image'     => ['max:255'],
        'model_id'  => ['require', 'number'],
        'access_id' => ['require', 'number'],
        'url'       => ['url', 'max:255'],
    ];

    protected $message = [
        'id.require'        => '{%illegal operation}',
        'id.number'         => '{%illegal operation}',
        'name.require'      => '{%error catname require}',
        'name.length'       => '{%error catname length not}',
        'name.unique'       => '{%error catname unique}',
        'aliases.length'    => '{%error aliases length not}',
        'aliases.unique'    => '{%error aliases unique}',
        'aliases.alpha'     => '{%error aliases alpha}',
        'type_id.require'   => '{%error type}',
        'type_id.number'    => '{%error type}',
        'image.max'         => '{%error image}',
        'model_id.require'  => '{%error model}',
        'model_id.number'   => '{%error model}',
        'access_id.require' => '{%error access}',
        'access_id.number'  => '{%error access}',
        'url.url'           => '{%error url}',
    ];

    protected $scene = [
        'added' => [
            'name',
            'aliases',
            'type_id',
            'image',
            'model_id',
            'access_id',
            'url',
        ],
        'editor' => [
            'id',
            'name',
            'aliases',
            'type_id',
            'image',
            'model_id',
            'access_id',
            'url',
        ],
    ];
}
