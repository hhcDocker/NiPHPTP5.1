<?php
/**
 *
 * 分类 - 栏目 - 控制器
 *
 * @package   NiPHPCMS
 * @category  admin\logic\category
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2017/12
 */
namespace app\admin\logic\category;

class Type
{

    /**
     * 查询
     * @access public
     * @param
     * @return array
     */
    public function query()
    {
        $map = [];
        // 搜索
        if ($key = input('param.q')) {
            $map[] = ['t.name', 'like', $key . '%'];
        }
        // 安栏目
        if ($cid = input('param.cid/f')) {
            $map[] = ['t.category_id', '=', $cid];
        }

        $result =
        model('common/type')
        ->view('type t', 'id,category_id,name,description')
        ->view('category c', ['name'=>'cat_name'], 'c.id=t.category_id')
        ->where($map)
        ->order('t.id DESC')
        ->paginate(null, null, [
            'path' => url('category/type'),
        ]);

        foreach ($result as $key => $value) {
            $result[$key]->url = [
                'editor' => url('category/type', ['operate' => 'editor', 'id' => $value['id']]),
                'remove' => url('category/type', ['operate' => 'remove', 'id' => $value['id']]),
            ];
        }
        $page = $result->render();
        $list = $result->toArray();

        return [
            'list' => $list['data'],
            'page' => $page
        ];
    }

    /**
     * 获得导航
     * @access public
     * @param
     * @return array
     */
    public function category($_pid = 0)
    {
        $map = [
            ['pid', '=', $_pid],
            ['model_id', 'not in', '8,9'],
            ['lang', '=', lang(':detect')],
        ];

        $result =
        model('common/category')->field(['id', 'name'])
        ->where($map)
        ->order('sort DESC, id DESC')
        ->select();

        foreach ($result as $key => $value) {
            $res = $this->category($value->id);
            $result[$key]->child = $res;
        }

        return $result;
    }

    /**
     * 新增
     * @access public
     * @param
     * @return mixed
     */
    public function added()
    {
        $receive_data = [
            'name'        => input('post.name'),
            'category_id' => input('post.category_id/f'),
            'description' => input('post.description'),
            '__token__'   => input('post.__token__'),
        ];

        $result = validate('admin/type.added', input('post.'), 'category');
        if (true !== $result) {
            return $result;
        }

        $result = model('common/type')
        ->added($receive_data);

        create_action_log($receive_data['name'], 'type_added');

        return !!$result;
    }

    /**
     * 删除
     * @access public
     * @param
     * @return mixed
     */
    public function remove()
    {
        $receive_data = [
            'id'  => input('post.id/f'),
        ];

        $map  = [
            ['id', '=', $receive_data['id']],
        ];

        $result =
        model('common/type')->field(true)
        ->where($map)
        ->find();

        create_action_log($result['name'], 'type_remove');

        return model('common/type')
        ->remove($receive_data);
    }

    /**
     * 查询要修改的数据
     * @access public
     * @param
     * @return array
     */
    public function find()
    {
        $map = [
            ['id', '=', input('post.id/f')]
        ];

        return model('common/type')->field(true)
        ->where($map)
        ->find();
    }

    /**
     * 编辑
     * @access public
     * @param
     * @return mixed
     */
    public function editor()
    {
        $receive_data = [
            'id'          => input('post.id'),
            'name'        => input('post.name'),
            'category_id' => input('post.category_id/f'),
            'description' => input('post.description'),
            '__token__'   => input('post.__token__'),
        ];

        $result = validate('admin/type.editor', input('post.'), 'category');

        if (true !== $result) {
            return $result;
        }

        create_action_log($receive_data['name'], 'type_editor');

        return model('common/type')
        ->editor($receive_data);
    }
}