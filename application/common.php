<?php
/**
 *
 * 应用公共函数文件
 *
 * @package   NiPHPCMS
 * @category  application
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: common.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */

use think\facade\Lang;
use think\facade\Debug;

/**
 * 运行时间与占用内存
 * @param  boolean $start
 * @return mixed
 */
use_time_memory(true);
function use_time_memory($start = false)
{
    if ($start) {
        Debug::remark('memory_start');
    } else {
        return
        lang('run time') .
        Debug::getRangeTime('memory_start', 'end', 2) . ' S ' .
        lang('run memory') .
        Debug::getRangeMem('memory_start', 'end');
    }
}

/**
 * 实例化验证器
 * @param  array  $_data     验证数据
 * @param  string $_name     验证器名
 * @param  string $_layer    业务层名
 * @param  srring $_module   模块名
 * @return mixed
 */
function validate($_data, $_name, $_layer = 'validate', $_module = '')
{
    if ($_layer) {
        $_layer = 'validate\\' . $_layer;
    } else {
        $_layer = 'validate';
    }

    if (is_array($_name)) {
        $v = app()->validate();
        $v->rule($_name);
    } else {
        if (strpos($_name, '.')) {
            // 支持场景
            list($_name, $scene) = explode('.', $_name);
        }

        $v = app()->validate($_name, $_layer, false, $_module);
        if (!empty($scene)) {
            $v->scene($scene);
        }
    }
    if (!$v->check($_data)) {
        $return = $v->getError();
    } else {
        $return = true;
    }
    return $return;
}

/**
 * 获取语言变量值
 * @param  string $_name 语言变量名
 * @param  array  $_vars 动态变量值
 * @param  string $_lang 语言
 * @return mixed
 */
function lang($_name, $_vars = [], $_lang = '')
{
    if ($_name == ':detect') {
        $return = Lang::detect();
    } else {
        $return = Lang::get($_name, $_vars, $_lang);
    }
    return $return;
}

/**
 * 清理运行垃圾文件
 * @param
 * @return void
 */
if (!rand(0, 10)) {
    remove_rundata();
}
function remove_rundata()
{
    $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    $dir .= 'runtime' . DIRECTORY_SEPARATOR;

    $cache = $dir . 'cache' . DIRECTORY_SEPARATOR . '*';
    $log   = $dir . 'log' . DIRECTORY_SEPARATOR . '*';
    $temp  = $dir . 'temp' . DIRECTORY_SEPARATOR . '*';

    $all_files = (array) glob($temp);
    $files = [
        'cache' => (array) glob($cache),
        'log'   => (array) glob($log),
    ];

    $child = [];
    foreach ($files['log'] as $path) {
        $arr = (array) glob($path . DIRECTORY_SEPARATOR . '*');
        if ($arr) {
            $child = array_merge($child, $arr);
        } else {
            $child[] = $path;
        }

    }
    $all_files = array_merge($all_files, $child);

    $child = [];
    foreach ($files['cache'] as $path) {
        if (is_dir($path)) {
            $arr = (array) glob($path . DIRECTORY_SEPARATOR . '*');
            $child = array_merge($child, $arr);
        } else {
            $child[] = $path;
        }

    }
    $all_files = array_merge($all_files, $child);

    shuffle($all_files);
    $all_files = array_slice($all_files, 0, 20);

    foreach ($all_files as $path) {
        if (filectime($path) <= strtotime('-30 days')) {
            if (is_dir($path)) {
                @rmdir($path);
            } else {
                @unlink($path);
            }
        }
    }
}

/**
 * 过滤XSS
 * @param  string $_data
 * @return string
 */
function escape_xss($_data)
{
    if (is_array($_data)) {
        foreach ($_data as $key => $value) {
            $_data[$key] = escape_xss($value);
        }
    } else {
        $pattern = [
            '/<\?php(.*?)\?>/si',
            '/<\?(.*?)\?>/si',
            '/<%(.*?)%>/si',
            '/<\?php|<\?|\?>|<%|%>/si',

            '/on([a-zA-Z]*?)(=)["|\'](.*?)["|\']/si',
            '/(javascript:)(.*?)(\))/si',
            '/<\!--.*?-->/si',
            '/<(\!.*?)>/si',

            '/<(javascript.*?)>(.*?)<(\/javascript.*?)>/si',
            '/<(\/?javascript.*?)>/si',

            '/<(vbscript.*?)>(.*?)<(\/vbscript.*?)>/si',
            '/<(\/?vbscript.*?)>/si',

            '/<(expression.*?)>(.*?)<(\/expression.*?)>/si',
            '/<(\/?expression.*?)>/si',

            '/<(applet.*?)>(.*?)<(\/applet.*?)>/si',
            '/<(\/?applet.*?)>/si',

            '/<(xml.*?)>(.*?)<(\/xml.*?)>/si',
            '/<(\/?xml.*?)>/si',

            '/<(blink.*?)>(.*?)<(\/blink.*?)>/si',
            '/<(\/?blink.*?)>/si',

            '/<(link.*?)>(.*?)<(\/link.*?)>/si',
            '/<(\/?link.*?)>/si',

            '/<(script.*?)>(.*?)<(\/script.*?)>/si',
            '/<(\/?script.*?)>/si',

            '/<(embed.*?)>(.*?)<(\/embed.*?)>/si',
            '/<(\/?embed.*?)>/si',

            '/<(object.*?)>(.*?)<(\/object.*?)>/si',
            '/<(\/?object.*?)>/si',

            '/<(iframe.*?)>(.*?)<(\/iframe.*?)>/si',
            '/<(\/?iframe.*?)>/si',

            '/<(frame.*?)>(.*?)<(\/frame.*?)>/si',
            '/<(\/?frame.*?)>/si',

            '/<(frameset.*?)>(.*?)<(\/frameset.*?)>/si',
            '/<(\/?frameset.*?)>/si',

            '/<(ilayer.*?)>(.*?)<(\/ilayer.*?)>/si',
            '/<(\/?ilayer.*?)>/si',

            '/<(layer.*?)>(.*?)<(\/layer.*?)>/si',
            '/<(\/?layer.*?)>/si',

            '/<(bgsound.*?)>(.*?)<(\/bgsound.*?)>/si',
            '/<(\/?bgsound.*?)>/si',

            '/<(title.*?)>(.*?)<(\/title.*?)>/si',
            '/<(\/?title.*?)>/si',

            '/<(base.*?)>(.*?)<(\/base.*?)>/si',
            '/<(\/?base.*?)>/si',

            '/<(meta.*?)>(.*?)<(\/meta.*?)>/si',
            '/<(\/?meta.*?)>/si',

            '/<(style.*?)>(.*?)<(\/style.*?)>/si',
            '/<(\/?style.*?)>/si',

            '/<(html.*?)>(.*?)<(\/html.*?)>/si',
            '/<(\/?html.*?)>/si',

            '/<(head.*?)>(.*?)<(\/head.*?)>/si',
            '/<(\/?head.*?)>/si',

            '/<(body.*?)>(.*?)<(\/body.*?)>/si',
            '/<(\/?body.*?)>/si',
        ];

        $_data = preg_replace($pattern, '', $_data);

        $pattern = [
            '/[  ]+/si' => ' ',    // 多余空格
            '/[\s]+</si' => '<',   // 多余回车
            '/>[\s]+/si' => '>',

            // SQL
            '/(select)/si' => '<span>s</span>elect',
            '/(drop)/si'   => '<span>d</span>rop',
            '/(delete)/si' => '<span>d</span>elete',
            '/(create)/si' => '<span>c</span>reate',
            '/(update)/si' => '<span>u</span>pdate',
            '/(insert)/si' => '<span>i</span>nsert',

            // 特殊字符
            '/(〃|”|“)/si'  => '&quot;',
            '/(￥)/si'      => '&yen;',
            '/(—|－|～)/si' => '-',
            '/(\*)/si'      => '&lowast;',
            '/(`)/si'       => '&acute;',
            '/(™)/si'       => '&trade;',
            '/(®)/si'       => '&reg;',
            '/(©)/si'       => '&copy;',
            '/(’|‘)/si'     => '&acute;',
            '/(×)/si'       => '&times;',
            '/(÷)/si'       => '&divide;',
            '/à|á|å|â|ä/si' => 'a',
            '/è|é|ê|ẽ|ë/si' => 'e',
            '/ì|í|î/si'     => 'i',
            '/ò|ó|ô|ø/si'   => 'o',
            '/ù|ú|ů|û/si'   => 'u',
            '/ç|č/si'       => 'c',
            '/ñ|ň/si'       => 'n',
            '/ľ/si'         => 'l',
            '/ý/si'         => 'y',
            '/ť/si'         => 't',
            '/ž/si'         => 'z',
            '/š/si'         => 's',
            '/æ/si'         => 'ae',
            '/ö/si'         => 'oe',
            '/ü/si'         => 'ue',
            '/Ä/si'         => 'Ae',
            '/Ü/si'         => 'Ue',
            '/Ö/si'         => 'Oe',
            '/ß/si'         => 'ss',

        ];
        $_data = preg_replace(array_keys($pattern), array_values($pattern), $_data);

        // 全角转半角
        $strtr = [
            '０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4', '５' => '5',
            '６' => '6', '７' => '7', '８' => '8', '９' => '9',

            'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E', 'Ｆ' => 'F',
            'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J', 'Ｋ' => 'K', 'Ｌ' => 'L',
            'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O', 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R',
            'Ｓ' => 'S', 'Ｔ' => 'T', 'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X',
            'Ｙ' => 'Y', 'Ｚ' => 'Z',

            'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd', 'ｅ' => 'e', 'ｆ' => 'f',
            'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i', 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l',
            'ｍ' => 'm', 'ｎ' => 'n', 'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r',
            'ｓ' => 's', 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
            'ｙ' => 'y', 'ｚ' => 'z',

            '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[', '】' => ']',
            '〖' => '[', '〗' => ']', '｛' => '{', '｝' => '}', '％' => '%', '＋' => '+',
            '：' => ':', '？' => '?', '！' => '!',
            '…' => '...', '‖' => '|', '｜' => '|', '　' => '',
            ];

        $_data = strtr($_data, $strtr);

        // 个性字符过虑
        $rule = '/[^\x{4e00}-\x{9fa5}a-zA-Z0-9\s\_\-\(\)\[\]\{\}\|\?\/\!\@\#\$\%\^\&\+\=\:\;\"\'\<\>\,\.\，\。\《\》\\\\]+/u';
        $_data = preg_replace($rule, '', $_data);
    }

    return $_data;
}
