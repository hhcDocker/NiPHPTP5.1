<?php
/**
 *
 * 登录 - 账户 - 验证器
 *
 * @package   NiPHPCMS
 * @category  admin\validate\account
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: AccountLogin.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\admin\validate\account;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'username' => ['require', 'length:6,20', 'token'],
        'password' => ['require', 'max:30'],
        // 'captcha'  => ['require', 'length:5', 'captcha'],
    ];

    protected $message = [
        'username.require' => '{%error username require}',
        'username.length'  => '{%error username length not}',
        'password.require' => '{%error password require}',
        'password.length'  => '{%error password length not}',
        'captcha.require'  => '{%error captcha require}',
        'captcha.length'   => '{%error captcha length}',
        'captcha.captcha'  => '{%error captcha}',
    ];
}