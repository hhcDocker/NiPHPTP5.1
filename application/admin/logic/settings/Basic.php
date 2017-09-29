<?php
/**
 *
 * 基础设置 - 设置 - 业务层
 *
 * @package   NiPHPCMS
 * @category  admin\logic\settings
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: Basic.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\admin\logic\settings;

class Basic
{

    /**
     * 查询基础设置数据
     * @access public
     * @param
     * @return array
     */
    public function getBasicConfig()
    {
        $map = [
            ['name', 'in', 'website_name,website_keywords,website_description,bottom_message,copyright,script'],
            ['lang', '=', lang(':detect')],
        ];

        // 实例化设置表模型
        $config = model('Config');

        $result =
        $config->field(true)
        ->where($map)
        ->select();

        $data = [];
        foreach ($result as $value) {
            $value = $value->toArray();
            $data[$value['name']] = $value['value'];
        }

        return $data;
    }

    /**
     * 保存修改基础设置
     * @access public
     * @param  array  $form_data
     * @return mixed
     */
    public function saveBasicConfig($form_data)
    {
        // 实例化设置表模型
        $config = model('Config');

        $map = $data = [];
        foreach ($form_data as $key => $value) {
            $map  = ['name'  => $key];
            $data = ['value' => $value];

            $config->allowField(true)
            ->isUpdate(true)
            ->save($data, $map);
        }

        return true;
    }
}
