<?php
/**
 *
 * 权限表 - 数据层
 *
 * @package   NiPHPCMS
 * @category  common\model
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: Access.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\common\model;

use think\Model;

class Access extends Model
{
    protected $name = 'access';
    protected $autoWriteTimestamp = false;
    protected $updateTime = false;
    protected $pk = 'id';
    protected $field = [
        'id',
        'role_id',
        'node_id',
        'status',
        'level',
        'module'
    ];

    /**
     * 新增
     * @access public
     * @param  array  $_receive_data
     * @return mixed
     */
    public function added($_receive_data)
    {
        unset($_receive_data['id'], $_receive_data['__token__']);

        $result =
        $this->allowField(true)
        ->create($_receive_data);

        return $result->id;
    }

    /**
     * 删除
     * @access public
     * @param  array  $_receive_data
     * @return boolean
     */
    public function remove($_receive_data)
    {
        $map  = [
            ['id', '=', $_receive_data['id']],
        ];

        $result =
        $this->where($map)
        ->delete();

        return !!$result;
    }

    /**
     * 修改
     * @access public
     * @param  array  $_receive_data
     * @return boolean
     */
    public function editor($_receive_data)
    {
        $map  = [
            ['id', '=', $_receive_data['id']],
        ];

        unset($_receive_data['id'], $_receive_data['__token__']);

        $result =
        $this->allowField(true)
        ->where($map)
        ->update($_receive_data);

        return !!$result;
    }
}
