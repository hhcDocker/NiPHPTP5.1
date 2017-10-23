<?php
/**
 *
 * 栏目表 - 数据层
 *
 * @package   NiPHPCMS
 * @category  admin\model
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: Category.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\admin\model;

use think\Model;

class Category extends Model
{
    protected $name = 'category';
    protected $autoWriteTimestamp = true;
    protected $updateTime = 'update_time';
    protected $pk = 'id';
    protected $field = [
        'id',
        'pid',
        'name',
        'aliases',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'image',
        'type_id',
        'model_id',
        'is_show',
        'is_channel',
        'sort',
        'access_id',
        'url',
        'create_time',
        'update_time',
        'lang'
    ];

    /**
     * 获取器
     * 操作url
     * @access public
     * @param
     * @return string
     */
    public function getOperationUrlAttr($_value, $_data)
    {
        $url = [
            'editor' => url('', array('operate' => 'editor', 'id' => $_data['id'])),
            'remove' => url('', array('operate' => 'remove', 'id' => $_data['id'])),
        ];

        return $url;
    }

    /**
     * 获取器
     * 栏目类型
     * @access public
     * @param  int    $value
     * @return string
     */
    public function getTypeNameAttr($_value, $_data)
    {
        $type = [
            1 => lang('type top'),
            2 => lang('type main'),
            3 => lang('type foot'),
            4 => lang('type other')
        ];

        return $type[$_data['type_id']];
    }

    /**
     * 获取器
     * 栏目模型名
     * @access public
     * @param  string $_value
     * @return string
     */
    public function getModelNameAttr($_value)
    {
        return lang('model ' . $_value);
    }

    /**
     * 获取器
     * 栏目是否显示
     * @access public
     * @param  int    $_value
     * @return string
     */
    public function getShowAttr($_value, $_data)
    {
        return $_data['is_show'] ? lang('show') : lang('hide');
    }

    /**
     * 获取器
     * 栏目是否为频道栏目
     * @access public
     * @param  int    $_value
     * @return string
     */
    public function getChannelAttr($_value, $_data)
    {
        return $_data['is_channel'] ? lang('yes') : lang('no');
    }
}
