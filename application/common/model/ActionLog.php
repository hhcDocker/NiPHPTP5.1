<?php
/**
 *
 * 行为日志表 - 数据层
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

class ActionLog extends Model
{
    protected $name = 'action_log';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    protected $pk = 'id';
    protected $type = [
        'action_id' => 'integer',
        'user_id'   => 'integer',
    ];
    protected $field = [
        'id',
        'action_id',
        'user_id',
        'action_ip',
        'module',
        'remark',
        'create_time'
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
}
