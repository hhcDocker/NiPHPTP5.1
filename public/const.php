<?php
if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    die('require PHP >= 5.6.0 !');
}

// CB|Alpha 内测版 RC|Beta 正式候选版 Demo 演示版 Stable 稳定版 Release 正式版
define('NP_VERSION', '1.0.1 CB18');

define('APP_DEBUG', true);

set_time_limit(300);
ini_set('memory_limit', '16M');