<?php
/**
 *
 * 缓解并发 - 行为
 *
 * @package   NiPHPCMS
 * @category  application\common\behavior
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2018/9
 */

namespace app\common\behavior;

class Concurrent
{

    /**
     * 并发压力
     * @access public
     * @param
     * @return void
     */
    public function run()
    {
        // 拦截频繁请求
        // 拦截非法请求
        if ($this->intercept()) {
            trace('[INTERCEPT]', 'alert');
            abort(502);
        }

        // 阻挡Ajax Pjax Post类型请求
        // 阻挡common模块请求
        if (request_block()) {
            return true;
        }

        // trace('[behavior] concurrent', 'warning');

        // 万分之一抛出异常
        if (!APP_DEBUG && rand(1, 10000) === 1) {
            abort(502);
        }
    }

    /**
     * 拦截频繁请求
     * 拦截非法请求
     * @access private
     * @param
     * @return void
     */
    private function intercept()
    {
        $ip = md5('CIP' . request()->ip());
        // 如果缓存不存在,生成缓存

        if (cache('?' . $ip)) {
            $result = cache($ip);

            if ($result['total'] > 50) {
                return true;
            } elseif ($result['total'] >= 50) {
                $result['total'] = $result['total'] + 1;
                $result['time']  = strtotime(date('Y-m-d 23:59:59'));
            } elseif (time() <= $result['time']) {
                $result['total'] = $result['total'] + 1;
                $result['time']  = time() + 10;
            } else {
                $result['total'] = 1;
                $result['time']  = time() + 10;
            }
        } else {
            $result = [
                'total' => 1,
                'time'  => time(),
            ];
        }

        cache($ip, $result, 0);

        return false;
    }
}