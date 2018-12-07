<?php
/**
 *
 * API - 控制器
 *
 * @package   NiPHPCMS
 * @category  application\admin\controller
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2018/9
 */
namespace app\admin\controller;

use app\common\logic\Async;

class Ajax extends Async
{

    /**
     * 初始化
     * @access protected
     * @param
     * @return
     */
    protected function _initialize()
    {
        if (request()->ext() != '' && request()->ext() !== 'do')
            abort(404);
    }

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
     * 执行请求
     * @access public
     * @param
     * @return json
     */
    public function settle()
    {
        $result = $this->handle();
        if ($result === true) {
            $this->success(lang('exec success'), $result);
        } else {
            $this->error($result);
        }
    }

    /**
     * 上传
     * @access public
     * @param
     * @return json
     */
    public function upload()
    {
        $result = $this->handle();
        if ($result === false) {
            return $this->error($this->errorMsg);
        } elseif (is_string($result)) {
            return $this->error($result);
        } else {
            if (input('param.type') === 'ckeditor') {
                return json([
                    'uploaded' => true,
                    'url' => $result['domain'] . $result['save_dir'] . $result['file_name'],
                ]);
            } else {
                return $this->success(lang('upload success'), $result);
            }

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

    /**
     * 验证Auth
     * @access protected
     * @param
     * @return mixed
     */
    protected function auth()
    {
        // 权限验证
        if ($this->action != 'login') {
            // 是否登录
            if (!session('?' . config('user_auth_key'))) {
                abort(404);
            }

            // 登录权限信息
            if (logic('common/Rbac')->checkAuth(
                session(config('user_auth_key')),
                'admin',
                $this->layer,
                $this->class,
                $this->action
            )) {
                trace('[NO AUTHORITY] ' . $this->layer . $this->class . $this->action, 'warning');
                abort(404);
            }
        }

        return true;
    }

    /**
     * 验证异步加密签名
     * @access protected
     * @param
     * @return mixed
     */
    protected function sign()
    {
        return true;
    }
}