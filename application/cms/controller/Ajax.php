<?php
/**
 *
 * API - 控制器
 *
 * @package   NiPHPCMS
 * @category  application\cms\controller
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2017/12
 */
namespace app\cms\controller;

use app\common\logic\Async;

class Ajax extends Async
{

    /**
     * 查询请求
     * @access public
     * @param
     * @return json
     */
    public function query()
    {
        $result = $this->handle();
        if (!is_null($result)) {
            $this->success('QUERY SUCCESS', $result);
        } else {
            $this->error('404', 'ABORT:404', '404');
        }
    }

    /**
     * 获得IP地址地区信息
     * @access public
     * @param
     * @return json
     */
    public function getipinfo()
    {
        $result = logic('common/IpInfo')->getInfo(input('param.ip'));
        $this->success('QUERY SUCCESS', $result);
    }

    protected function auth()
    {
        return true;
    }
}