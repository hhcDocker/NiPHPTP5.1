<?php
/**
 *
 * 管理员表 - 数据层
 *
 * @package   NiPHP
 * @category  application\common\model
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2017/12
 */
namespace app\common\model;

use think\Model;

class Admin extends Model
{
    protected $name = 'admin';
    protected $autoWriteTimestamp = true;
    protected $updateTime = 'last_login_time';
    protected $pk = 'id';
    protected $field = [
        'id',
        'username',
        'password',
        'email',
        'salt',
        'last_login_ip',
        'last_login_ip_attr',
        'last_login_time',
        'create_time',
        'update_time'
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
        $result =
        $this->where([
            ['id', '=', $_receive_data['id']],
        ])
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
        unset($_receive_data['__token__']);

        $_receive_data['update_time'] = time();

        $result =
        $this->allowField(true)
        ->save($_receive_data, ['id' => $_receive_data['id']]);

        return !!$result;
    }
}
