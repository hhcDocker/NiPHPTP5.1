<?php
/**
 *
 * 分类表 - 商城 - 数据层
 *
 * @package   NiPHPCMS
 * @category  admin\model
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: MallType.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\admin\model;

use think\Model;

class MallType extends Model
{
    protected $name = 'mall_type';
    protected $autoWriteTimestamp = true;
    protected $updateTime = 'update_time';
    protected $pk = 'id';
    protected $field = [
        'id',
        'pid',
        'name',
        'image',
        'sort',
        'create_time',
        'update_time',
        'lang'
    ];
}
