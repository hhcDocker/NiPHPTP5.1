<?php
/**
 *
 * 标签 - 业务层
 *
 * @package   NiPHP
 * @category  application\cms\logic
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2018/8
 */
namespace app\cms\logic;

class Tags
{

    /**
     * 查询标签
     * @access public
     * @param  int $data
     * @return array
     */
    public function query()
    {
        $result =
        model('common/tags')
        ->field('id,name,count')
        ->where([
            ['lang', '=', lang(':detect')],
        ])
        ->cache(!APP_DEBUG ? __METHOD__ : false)
        ->select();

        $result = $result ? $result->toArray() : [];

        foreach ($result as $key => $value) {
            $result[$key]['url'] = url('/tags/' . $value['id']) . '?tags=' . $value['name'];
        }

        return $result;
    }

    public function article()
    {

    }
}
