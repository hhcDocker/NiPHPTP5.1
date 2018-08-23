<?php
/**
 *
 * 广告 - 业务层
 *
 * @package   NiPHPCMS
 * @category  application\cms\logic
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2018/8
 */
namespace app\cms\logic;

class Ads
{

    /**
     * 查询广告
     * @access public
     * @param  int $data
     * @return array
     */
    public function query($_ads_id = 0)
    {
        $_ads_id = input('param.ads_id/f', $_ads_id);

        $date = strtotime(date('Y-m-d'));
        $map = [
            ['id', '=', $_ads_id],
            ['start_time', 'ELT', $date],
            ['end_time', 'EGT', $date],
            ['lang', '=', lang(':detect')],
        ];

        $result =
        model('common/ads')
        ->field(true)
        ->where($map)
        ->cache(!APP_DEBUG)
        ->find();

        $result['url'] = url('/ads/' . $result['id']);

        return $result;
    }
}