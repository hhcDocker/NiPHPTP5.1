<?php
/**
 *
 * 全局 - 控制器
 *
 * @package   NiPHPCMS
 * @category  application\cms\controller
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\cms\controller;

use think\Controller;

class Base extends Controller
{
    // 请求参数
    protected $requestParam = [];

    protected $domain = '';

    protected function initialize()
    {
        concurrent_error();

        // 请求参数
        $this->requestParam = [
            // 请求模块
            'module'     => strtolower($this->request->module()),
            // 请求控制器
            'controller' => strtolower($this->request->controller()),
            // 请求方法
            'action'     => strtolower($this->request->action()),
            // 语言
            'lang'       => lang(':detect'),
        ];

        // 域名
        $this->domain = $this->request->domain() . $this->request->root() . '/';

        $this->setTemplate();
        lang(':load');
    }

    /**
     * 设置模板
     * @access private
     * @param
     * @return void
     */
    private function setTemplate()
    {
        // 重新定义模板目录
        $view_path  = env('root_path') . basename($this->request->root());
        $view_path .= DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR;
        $view_path .= 'cms' . DIRECTORY_SEPARATOR;
        $view_path .= config('default_theme') . DIRECTORY_SEPARATOR;

        // 模板地址 带域名
        $default_theme  = $this->domain . 'theme/cms/';
        $default_theme .= config('default_theme') . '/';

        if (is_wechat_request()) {
            if (is_dir($view_path . 'wechat' . DIRECTORY_SEPARATOR)) {
                $view_path     .= 'wechat' . DIRECTORY_SEPARATOR;
                $default_theme .= 'wechat/';
            } elseif (is_dir($view_path . 'mobile' . DIRECTORY_SEPARATOR)) {
                $view_path     .= 'mobile' . DIRECTORY_SEPARATOR;
                $default_theme .= 'mobile/';
            }
        } elseif (request()->isMobile()) {
            if (is_dir($view_path . 'mobile' . DIRECTORY_SEPARATOR)) {
                $view_path     .= 'mobile' . DIRECTORY_SEPARATOR;
                $default_theme .= 'mobile/';
            }
        }

        $replace = [
            '__DOMAIN__'   => $this->domain,
            '__PHP_SELF__' => basename($this->request->baseFile()),
            '__STATIC__'   => $this->domain . 'static/',
            '__THEME__'    => config('default_theme'),
            '__CSS__'      => $default_theme . 'css/',
            '__JS__'       => $default_theme . 'js/',
            '__IMG__'      => $default_theme . 'images/',
        ];

        $template = config('template.');
        $template['view_path'] = $view_path;
        $template['tpl_replace_string'] = $replace;
        $template['view_depr'] = '_';
        config('template.view_path', $view_path);
        config('template.tpl_replace_string', $replace);
        config('template.view_depr', '_');

        $this->engine($template);
        $this->filter('view_filter');
    }

    /**
     * 加载模板输出
     * @access protected
     * @param  string $template 模板文件名
     * @param  array  $vars     模板输出变量
     * @param  array  $config   模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $config = [])
    {
        if ($template) {
            $template = config('template.view_path') . $template;
        }
        return parent::fetch($template, $vars, $config);
    }
}
