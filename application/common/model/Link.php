<?php
/**
 *
 * 友链表 - 数据层
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
use think\model\concern\SoftDelete;

class Link extends Model
{
    use SoftDelete;
    protected $name = 'link';
    protected $autoWriteTimestamp = true;
    protected $updateTime = 'update_time';
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
    protected $pk = 'id';
    protected $type = [
        'category_id' => 'integer',
        'type_id'     => 'integer',
        'mebmer_id'   => 'integer',
        'is_pass'     => 'integer',
        'sort'        => 'integer',
        'hits'        => 'integer',
        'user_id'     => 'integer',
    ];
    protected $field = [
        'id',
        'title',
        'logo',
        'description',
        'category_id',
        'type_id',
        'mebmer_id',
        'is_pass',
        'sort',
        'hits',
        'user_id',
        'url',
        'create_time',
        'update_time',
        'delete_time',
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
        ->save($_receive_data, ['id' => $_receive_data['id']]);

        return !!$result;
    }

    /**
     * 排序
     * @access public
     * @param
     * @return boolean
     */
    public function sort()
    {
        $form_data = [
            'id' => input('post.sort/a'),
        ];

        foreach ($form_data['id'] as $key => $value) {
            $data[] = [
                'id'   => (float) $key,
                'sort' => (float) $value,
            ];
        }

        $result =
        $this->saveAll($data);

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
}
