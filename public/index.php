<?php
/**
 *
 * 应用入口文件
 *
 * @package   NiPHPCMS
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2018/7
 *
 * CB|Alpha 内测版
 * RC|Beta  正式候选版
 * Demo     演示版
 * Stable   稳定版
 * Release  正式版
 */
namespace think;

// 调试开关
define('APP_DEBUG', true);
define('NP_VERSION', '2.0.1 Alpha 8.2613');



version_compare(PHP_VERSION, '5.6.0', '>=') or die('PHP version >= 5.6.0!');
if (APP_DEBUG) set_time_limit(60); else set_time_limit(300);
ini_set('memory_limit', '32M');
header('X-Powered-By: NiPHP ' . NP_VERSION);
// if (function_exists('ob_gzhandler')) ob_start('ob_gzhandler');
libxml_disable_entity_loader(true);

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// thinkphp版本支持
version_compare(Container::get('app')->version(), '5.1.24', '=') or die('ThinkPHP version = 5.1.24!');

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();

