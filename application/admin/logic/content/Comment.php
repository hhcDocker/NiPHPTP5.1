<?php
/**
 *
 * 评论 - 内容 - 控制器
 *
 * @package   NiPHPCMS
 * @category  application\admin\logic\content
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2018/6
 */
namespace app\admin\logic\content;

class Comment
{
    /**
     * 查询
     * @access public
     * @param
     * @return array
     */
    public function query()
    {
        $map = [
            ['lang', '=', lang(':detect')],
        ];

        $result =
        model('common/comment')
        ->where($map)
        ->order('id DESC')
        ->paginate(null, null, [
            'path' => url('content/comment'),
        ]);

        $page = $result->render();
        $list = $result->toArray();

        return [
            'list' => $list['data'],
            'page' => $page
        ];
    }

    /**
     * 新增
     * @access public
     * @param
     * @return mixed
     */
    public function added()
    {}

    /**
     * 删除
     * @access public
     * @param
     * @return mixed
     */
    public function remove()
    {}

    /**
     * 查询要修改的数据
     * @access public
     * @param
     * @return array
     */
    public function find()
    {}

    /**
     * 编辑
     * @access public
     * @param
     * @return mixed
     */
    public function editor()
    {}
}
