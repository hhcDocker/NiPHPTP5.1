<?php
/**
 *
 * 缓存配置
 *
 * @package   NiPHPCMS
 * @category  config
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2017/12
 */

return [
    // 驱动方式
    'type'         => 'File',
    // 缓存前缀
    'prefix'       => substr(md5(request()->subDomain() . env('root_path')), 0, 9),
    // 缓存有效期 0表示永久缓存
    'expire'       => APP_DEBUG ? 300 : 28800,
    'cache_subdir' => !APP_DEBUG,
];
