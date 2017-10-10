<?php
/**
 *
 * 请求日志表 - 数据层
 *
 * @package   NiPHPCMS
 * @category  admin\model
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: RequestLog.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\admin\model;

use think\Model;

class RequestLog extends Model
{
    protected $name = 'request_log';
    protected $autoWriteTimestamp = true;
    protected $updateTime = 'update_time';
    protected $pk = 'id';
    protected $field = [
        'id',
        'ip',
        'module',
        'count',
        'update_time',
        'create_time'
    ];
}