<?php
/**
 *
 * 会员 - 用户 - 验证器
 *
 * @package   NiPHP
 * @category  application\admin\validate\user
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2017/12
 */
namespace app\admin\validate\user;

use think\Validate;

class Member extends Validate
{
    protected $rule = [
        'id'           => ['require', 'number'],
        'username'     => ['require', 'length:6,20', 'unique:member', 'token'],
        'password'     => ['require', 'length:6,20'],
        'not_password' => ['require', 'confirm:password'],
        'email'        => ['email', 'unique:member'],
        'realname'     => ['max:50'],
        'nickname'     => ['max:50'],
        'portrait'     => ['max:250'],
        'gender'       => ['require', 'number'],
        'birthday'     => ['date'],
        'province'     => ['number'],
        'city'         => ['number'],
        'area'         => ['number'],
        'phone'        => ['number', 'unique:member'],
        'status'       => ['require', 'number'],
        'level'        => ['require', 'number'],
    ];

    protected $message = [
        'id.require'            => '{%illegal operation}',
        'id.number'             => '{%illegal operation}',
        'username.require'      => '{%error username require}',
        'username.length'       => '{%error username length not}',
        'username.unique'       => '{%error username unique}',
        'password.require'      => '{%error password require}',
        'password.length'       => '{%error password length not}',
        'not_password.require'  => '{%error not_password require}',
        'not_password.confirm'  => '{%error not_password confirm}',
        'email.email'           => '{%error email email}',
        'realname.max'          => '{%error realname length not}',
        'nickname.max'          => '{%error nickname length not}',
        'portrait.max'          => '{%error portrait length not}',
        'gender.require'        => '{%error gender require}',
        'gender.number'         => '{%error gender number}',
        'birthday.date'         => '{%error birthday date}',
        'province.number'       => '{%error province number}',
        'city.number'           => '{%error city number}',
        'area.number'           => '{%error area number}',
        'phone.number'          => '{%error phone number}',
        'status.require'        => '{%error status require}',
        'status.number'         => '{%error status number}',
        'level.require'         => '{%error level require}',
        'level.number'          => '{%error level number}',
    ];

    protected $scene = [
        'added' => [
            'username',
            'password',
            'not_password',
            'email',
            'realname',
            'nickname',
            'portrait',
            'gender',
            'birthday',
            'province',
            'city',
            'area',
            'phone',
            'status',
            'level',
        ],
        'editor' => [
            'id',
            'username',
            'password',
            'not_password',
            'email',
            'realname',
            'nickname',
            'portrait',
            'gender',
            'birthday',
            'province',
            'city',
            'area',
            'phone',
            'status',
            'level',
        ],
        'editorNoPwd' => [
            'id',
            'username',
            'email',
            'realname',
            'nickname',
            'portrait',
            'gender',
            'birthday',
            'province',
            'city',
            'area',
            'phone',
            'status',
            'level',
        ],
    ];
}
