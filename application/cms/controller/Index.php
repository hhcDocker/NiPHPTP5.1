<?php
/**
 *
 * 网站 - 控制器
 *
 * @package   NiPHPCMS
 * @category  application\cms\controller
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\cms\controller;

class Index extends Base
{
    /**
     * 首页
     * @access public
     * @param
     * @return mixed
     */
    public function index()
    {
        $result =
        model('common/node')
        ->select();

        $result = $result->toArray();

        $data = [];
        foreach ($result as $key => $value) {
            $data[] = [
                'role_id' => 1,
                'status'  => 1,
                'node_id' => $value['id'],
                'level'   => $value['level'],
                'module'  => $value['name'],
            ];

        }

        model('common/access')
        ->insertAll($data);




        halt(1);


        return $this->fetch('index.html');
    }

    /**
     * 列表页
     * @access public
     * @param
     * @return mixed
     */
    public function entry($operate = '')
    {
        $tpl = $operate ? $operate : '';
        return $this->fetch($operate . '.html');
    }

    /**
     * 频道页
     * @access public
     * @param
     * @return mixed
     */
    public function channel()
    {
        halt('channel');
        return $this->fetch('channel.html');
    }

    /**
     * 反馈
     * @access public
     * @param
     * @return mixed
     */
    public function feedback()
    {
        halt('feedback');
        return $this->fetch('feedback.html');
    }

    /**
     * 留言
     * @access public
     * @param
     * @return mixed
     */
    public function message()
    {
        halt('message');
        return $this->fetch('message.html');
    }

    /**
     * 标签
     * @access public
     * @param
     * @return mixed
     */
    public function tags()
    {
        return $this->fetch('tags.html');
    }

    /**/
    public function go()
    {
        # code...
    }

    /**
     * IP信息
     * @access public
     * @param
     * @return mixed
     */
    public function getipinfo()
    {
        json(logic('common/IpInfo')->getInfo());
    }
}
