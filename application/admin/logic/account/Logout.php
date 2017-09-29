<?php
/**
 *
 * 注销 - 帐户 - 业务层
 *
 * @package   NiPHPCMS
 * @category  admin\logic\account
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: Logout.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\admin\logic\account;

class Logout
{

    /**
     * 清除登录用户认证信息
     * @access public
     * @param
     * @return void
     */
    public function removeAuth()
    {
        session(null);
        cookie(null);
    }
}
