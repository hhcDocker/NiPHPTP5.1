<?php
/**
 *
 * 管理栏目 - 栏目 - 控制器
 *
 * @package   NiPHPCMS
 * @category  admin\logic\category
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: Category.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\admin\logic\category;

class Category
{

    public function getListData()
    {
        $map = [
            ['c.pid', '=', 0],
            ['c.lang', '=', lang(':detect')],
        ];

        if ($key = request()->param('q')) {
            $map[] = ['c.name', 'like', $key . '%'];
        }

        $category = model('Category');
        $result =
        $category->view('category c', 'id,pid,name,type_id,model_id,is_show,is_channel,sort')
        ->view('model m', ['name'=>'model_name'], 'm.id=c.model_id')
        ->view('category cc', ['id'=>'child'], 'c.id=cc.pid', 'LEFT')
        ->where($map)
        ->group('c.id')
        ->order('c.type_id ASC, c.sort ASC, c.id DESC')
        ->select();

        return $result;
    }
}
