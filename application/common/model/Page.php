<?php
/**
 *
 * 单页表 - 数据层
 *
 * @package   NiPHPCMS
 * @category  common\model
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2017/12
 */
namespace app\common\model;

use think\Model;

class Page extends Model
{
    protected $name = 'page';
    protected $autoWriteTimestamp = true;
    protected $updateTime = 'update_time';
    protected $pk = 'id';
    protected $type = [
        'category_id'   => 'integer',
        'type_id'       => 'integer',
        'is_pass'       => 'integer',
        'is_com'        => 'integer',
        'is_top'        => 'integer',
        'is_hot'        => 'integer',
        'sort'          => 'integer',
        'hits'          => 'integer',
        'user_id'       => 'integer',
        'is_link'       => 'integer',
        'comment_count' => 'integer',
    ];
    protected $field = [
        'id',
        'title',
        'keywords',
        'description',
        'content',
        'thumb',
        'category_id',
        'type_id',
        'is_pass',
        'is_com',
        'is_top',
        'is_hot',
        'sort',
        'hits',
        'comment_count',
        'username',
        'origin',
        'user_id',
        'url',
        'is_link',
        'show_time',
        'create_time',
        'update_time',
        'delete_time',
        'access_id',
        'lang'
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

        $result =
        $this->allowField(true)
        ->where([
            ['id', '=', $_receive_data['id']],
        ])
        ->update($_receive_data);

        return !!$result;
    }

    /**
     * 获取器
     * 审核名称
     * @access protected
     * @param  string $_value
     * @return string
     */
    protected function getPassNameAttr($_value)
    {
        return lang('pass ' . $_value);
    }

    /**
     * 获取器
     * 栏目模型名
     * @access protected
     * @param  string $_value
     * @return string
     */
    protected function getModelNameAttr($_value)
    {
        return lang('model ' . $_value);
    }

    /**
     * 获取器
     * 推荐状态名
     * @access protected
     * @param  string $_value
     * @return string
     */
    protected function getComNameAttr($_value)
    {
        return lang('article com ' . $_value);
    }

    /**
     * 获取器
     * 最热状态名
     * @access protected
     * @param  string $_value
     * @return string
     */
    protected function getHotNameAttr($_value)
    {
        return lang('article hot ' . $_value);
    }

    /**
     * 获取器
     * 置顶状态名
     * @access protected
     * @param  string $_value
     * @return string
     */
    protected function getTopNameAttr($_value)
    {
        return lang('article top ' . $_value);
    }

    /**
     * 获取器
     * 跳转状态名
     * @access protected
     * @param  string $_value
     * @return string
     */
    protected function getLinkNameAttr($_value)
    {
        return lang('article link ' . $_value);
    }
}
