<?php
/**
 *
 * 异步请求实现 - 业务层
 *
 * @package   NiPHP
 * @category  application\common\logic
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2018/7
 */
namespace app\common\logic;

use think\Response;
use think\Request;
use think\exception\HttpResponseException;

use think\facade\Config;
use think\facade\Lang;

class Async
{
    protected $request;

    protected $module;                                                          // 模块名

    protected $appid;
    protected $appsecret;
    protected $token       = null;                                              // 令牌
    protected $sign;                                                            // 签名
    protected $sign_type   = 'md5';                                             // 签名类型
    protected $timestamp   = 0;                                                 // 请求时间
    protected $format      = 'json';                                            // 返回数据类型[json|pjson|xml]
    protected $version     = null;                                              // 版本号

    protected $method;                                                          // 执行方法名
    protected $layer       = 'logic';                                           // 业务名
    protected $class       = 'index';                                           // 类名
    protected $action      = 'index';                                           // 方法名

    protected $logicObject = null;                                              // 业务类对象

    protected $apiDebug    = false;                                             // 调试模式
    protected $apiCache    = false;                                             // 缓存
    protected $debugMsg    = [];                                                // 错误信息

    /**
     * 构造方法
     * @access public
     * @param  Request $request Request 对象
     * @return
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        // 初始化Async
        $this->init();

        // 初始化
        $this->initialize();
    }

    /**
     * 初始化
     * @access protected
     * @param
     * @return
     */
    protected function initialize()
    {
        # code...
    }

    /**
     * 发送数据
     * @access protected
     * @param
     * @return
     */
    protected function send()
    {
        return call_user_func_array([$this->logicObject, $this->action], []);
    }

    /**
     * 运行
     * @access protected
     * @param
     * @return object
     */
    protected function run()
    {
        $this->analysisMethod();                                                // 解析method参数
        $this->checkMethod();                                                   // method参数检查
        $this->loadLang();                                                      // 加载语言
        $this->token();
        $this->sid();
        return $this;
    }

    /**
     * 验证权限
     * @access protected
     * @param
     * @return object
     */
    protected function auth()
    {
        abort(404);
    }

    /**
     * 验证签名
     * @access protected
     * @param
     * @return object
     */
    protected function sign()
    {
        if (!$this->sign) {
            $this->error('[ASYNC] SIGN ERROR');
        }

        $params = input('param.', '', 'trim');
        ksort($params);

        $str = '';
        foreach ($params as $key => $value) {
            if (is_string($value) && !in_array($key, ['sign'])) {
                $str .= $key . '=' . $value . '&';
            }
        }
        $str = trim($str, '&');
        $str = call_user_func($this->sign_type, $str);

        if (!hash_equals($str, $this->sign)) {
            $this->debugMsg['sign'] = $str;
            trace('[SIGN] request sign error', 'error');
            $this->error('[ASYNC] SIGN ERROR');
        }

        return $this;
    }

    /**
     * 验证SID
     * @access protected
     * @param
     * @return object
     */
    protected function sid()
    {
        if ($this->sid && !preg_match('/^[A-Za-z0-9]+$/u', $this->sid)) {
            trace('[ASYNC] request sid error', 'error');
            $this->error('[ASYNC] REQUEST SID ERROR');
        }

        return $this;
    }

    /**
     * 验证TOKEN
     * @access protected
     * @param
     * @return object
     */
    protected function token()
    {
        $referer = sha1(
            // $this->request->server('HTTP_ORIGIN') .
            // $this->request->header('Referer') .
            $this->request->server('HTTP_USER_AGENT') .
            $this->request->ip() .
            env('root_path') .
            date('Ymd')
        );

        if (!$this->token or !hash_equals($referer, $this->token)) {
            $this->debugMsg[] = 'token=' . $referer;
            $this->debugMsg[] = $this->request->header('Referer');
            trace('[ASYNC] request token error', 'error');
            trace('[ASYNC] self::token ' . $referer, 'error');
            trace('[ASYNC] request::token ' . $this->token, 'error');
            $this->error('[ASYNC] REQUEST TOKEN ERROR');
        }

        return $this;
    }

    /**
     * 加载模块语言包
     * @access protected
     * @param
     * @return mixed
     */
    protected function loadLang()
    {
        $lang_path  = env('app_path') . $this->module;
        $lang_path .= DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR;
        $lang_path .= safe_filter_strict(Lang::detect()) . '.php';
        Lang::load($lang_path);
    }

    /**
     * 设置模块
     * @access protected
     * @param
     * @return mixed
     */
    protected function setModule($_name)
    {
        $this->module = $_name;
    }

    /**
     * method 参数检查
     * @access private
     * @param
     * @return void
     */
    private function checkMethod()
    {
        // 检查业务分层文件是否存在
        $file_path = $this->module . DIRECTORY_SEPARATOR . 'logic' . DIRECTORY_SEPARATOR;
        // 版本目录
        $file_path .= $this->version ? $this->version . DIRECTORY_SEPARATOR : '';
        // 分层目录
        $file_path .= $this->layer !== 'logic' ? $this->layer . DIRECTORY_SEPARATOR : '';

        $file_path .= ucfirst($this->class) . '.php';

        // 文件是否存在
        if (!is_file(env('app_path') . $file_path)) {
            $this->debugMsg[] = $file_path;
            $this->debugMsg[] = '$' . $this->class . '->' . $this->action . '() logic doesn\'t exist';
            trace('[METHOD] request params error', 'error');
            trace('[METHOD] ' . $file_path, 'error');
            $this->error('[ASYNC METHOD] METHOD NOT FOUND');
        }

        // 检查方法是否存在
        $logic_params  = $this->module . '/';
        $logic_params .= $this->version ? $this->version . '\\' . $this->layer : $this->layer;
        $logic_params .= '/' . $this->class;
        $this->logicObject = logic($logic_params);
        if (!method_exists($this->logicObject, $this->action)) {
            $this->debugMsg[] = $file_path;
            $this->debugMsg[] = '$' . $this->class . '->' . $this->action . '() logic doesn\'t exist';
            trace('[METHOD] request params error', 'error');
            trace('[METHOD] ' . $file_path, 'error');
            $this->error('[ASYNC METHOD] PARAMETER NOT FOUND');
        }
    }

    /**
     * 解析method参数
     * 参数[业务分层名.类名.方法名]
     * 参数[logic.类名.方法名]
     * 参数[logic.类名.index]
     * @access private
     * @param
     * @return mixed
     */
    private function analysisMethod()
    {
        if (!$this->method) {
            trace('[METHOD] Undefined parameters', 'error');
            $this->error('[ASYNC METHOD] Undefined parameters');
        }

        // 参数[模块名.业务分层名.类名.方法名]
        // 参数[业务分层名.类名.方法名]
        // 参数[logic.类名.方法名] 业务分层名默认logic
        // 参数[logic.类名.index] 业务分层名默认logic 方法名默认index
        $count = count(explode('.', $this->method));
        if ($count == 4) {
            list($this->module, $this->layer, $this->class, $this->action) = explode('.', $this->method, 4);
        } elseif ($count == 3) {
            list($this->layer, $this->class, $this->action) = explode('.', $this->method, 3);
        } elseif ($count == 2) {
            list($this->class, $this->action) = explode('.', $this->method, 2);
        } elseif ($count == 1) {
            list($this->class) = explode('.', $this->method, 1);
        }
    }

    /**
     * 初始化Async
     * @access private
     * @param
     * @return void
     */
    private function init()
    {
        $this->module    = strtolower($this->request->module());                // 模块名称

        $this->appid     = input('param.appid/f');                              //
        $this->appsecret = input('param.appsecret');                            //

        $this->token     = $this->request->header('x-request-token');           // 令牌
        $this->token     = logic('common/SafeFilter')->filter($this->token, true);

        $this->sid       = $this->request->header('x-request-id');              // SESSIONID
        $this->sid       = logic('common/SafeFilter')->filter($this->sid, true);
        $this->sid       = decrypt($this->sid);

        $this->timestamp = input('param.timestamp/f', time());                  // 请求时间戳
        $this->timestamp = $this->request->header('x-request-timestamp');       // 请求时间戳
        $this->timestamp = (float) $this->timestamp;

        $this->sign      = input('param.sign');                                 // 请求数据签名
        $this->sign_type = strtolower(input('param.sign_type', 'md5'));         // 签名类型
        $this->format    = strtolower(input('param.format', 'json'));           // 返回数据类型
        $this->method    = strtolower(input('param.method'));                   // 请求API方法名

        $this->version   = input('param.version', null);                        // 版本号
        if ($this->version) {
            list($major, $minor) = explode('.', $this->version, 3);
            $this->version = 'v' . $major . '.' . $minor;
        }

        $this->apiCache  = input('param.cache/b', !APP_DEBUG);                  // 缓存数据
        $this->apiDebug  = APP_DEBUG;                                           // 显示调试信息
    }

    /**
     * 操作成功返回的数据
     * @access protected
     * @param  string  $msg  提示信息
     * @param  mixed   $data 要返回的数据
     * @param  integer $code 错误码，默认为SUCCESS
     * @return void
     * @throws HttpResponseException
     */
    protected function success($_msg, $_data = [], $_code = 'SUCCESS')
    {
        $this->result($_msg, $_data, $_code);
    }
    /**
     * 操作失败返回的数据
     * @access protected
     * @param  string  $msg  提示信息
     * @param  mixed   $data 要返回的数据
     * @param  integer $code 错误码，默认为ERROR
     * @return void
     * @throws HttpResponseException
     */
    protected function error($_msg, $_data = [], $_code = 'ERROR')
    {
        $this->result($_msg, $_data, $_code);
    }

    /**
     * 返回封装后的 API 数据到客户端
     * @access private
     * @param  mixed   $msg  提示信息
     * @param  mixed   $data 要返回的数据
     * @param  integer $code 错误码，默认为SUCCESS
     * @return void
     * @throws HttpResponseException
     */
    private function result($_msg, $_data = [], $_code = 'SUCCESS')
    {
        $result = [
            'code'    => $_code,
            'message' => $_msg,
            'data'    => $_data,
            'time'    => date('Y-m-d H:i:s', $this->request->server('REQUEST_TIME')),
            'ip'      => logic('common/IpInfo')->getInfo(),
            'runtime' => number_format(microtime(true) - app()->getBeginTime(), 6) . '秒',
            'memory'  => number_format((memory_get_usage() - app()->getBeginMem()) / 1024 / 1024, 2) . 'MB',
        ];

        if ($this->apiDebug) {
            $result['debug'] = [
                'sql query' => \think\Db::$queryTimes . '条查询 ' . \think\Db::$executeTimes . '条写入',
                'db cache'  => app('cache')->getReadTimes() . '次读取 ' . app('cache')->getWriteTimes() . '次写入',
                'include'   => count(get_included_files()) . '个文件',
                'async'     => $this->debugMsg,
                'params'    => input('param.', [], 'trim'),
                'cookie'    => $_COOKIE,
                'method'    => $this->method,
                'header'    => $this->request->header()
            ];
        }

        $response = Response::create($result, $this->format, 200)->allowCache($this->apiCache);

        throw new HttpResponseException($response);
    }
}
