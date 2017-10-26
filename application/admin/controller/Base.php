<?php
/**
 *
 * 全局 - 控制器
 *
 * @package   NiPHPCMS
 * @category  admin\controller
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: Base.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\admin\controller;

use think\Controller;
use think\facade\Env;
use think\facade\Lang;

class Base extends Controller
{
    // 请求参数
    protected $requestParam = [];

    protected function initialize()
    {
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

        // 权限
        $this->auth();
        // 语言
        $this->lang();
        // 模板
        $this->theme();
    }

    /**
     * 列表
     * @access protected
     * @param  string    $_name
     * @param  string    $_layer
     * @param  array     $_vars
     * @return string
     */
    protected function listing($_name, $_layer = '', $_vars = [])
    {
        $result = action($_name, $_vars, $_layer);
        $this->assign('json_data', json_encode($result));
        return $this->fetch();
    }

    /**
     * 新增
     * @access protected
     * @param  string    $_name
     * @param  string    $_layer
     * @param  array     $_vars
     * @return mixed
     */
    protected function added($_name, $_layer = '', $_vars = [])
    {
        $result = action($_name, $_vars, $_layer);
        if (!is_array($result)) {
            $this->showMessage($result, lang('added success'));
        } else {
            if ($result) {
                $this->assign('json_data', json_encode($result));
            }
            return $this->fetch($this->requestParam['action'] . '_' . input('param.operate'));
        }
    }

    /**
     * 删除
     * @access protected
     * @param  string    $_name
     * @param  string    $_layer
     * @param  array     $_vars
     * @return void
     */
    protected function remove($_name, $_layer = '', $_vars = [])
    {
        $result = action($_name, $_vars, $_layer);
        $this->showMessage($result, lang('remove success'));
    }

    /**
     * 编辑
     * @access protected
     * @param  string    $_name
     * @param  string    $_layer
     * @param  array     $_vars
     * @return mixed
     */
    protected function editor($_name, $_layer = '', $_vars = [])
    {
        $result = action($_name, $_vars, $_layer);
        if (!is_array($result)) {
            $this->showMessage($result, lang('editor success'));
        } else {
            if ($result) {
                $this->assign('json_data', json_encode($result));
            }
            return $this->fetch($this->requestParam['action'] . '_' . input('param.operate'));
        }
    }

    /**
     * 排序
     * @access protected
     * @param  string    $_name
     * @param  string    $_layer
     * @param  array     $_vars
     * @return void
     */
    protected function sort($_name, $_layer = '', $_vars = [])
    {
        $result = action($_name, $_vars, $_layer);
        $this->showMessage($result, lang('sort success'));
    }

    /**
     * 上传文件
     * @access public
     * @param
     * @return mixed
     */
    public function upload()
    {
        if ($this->request->isPost()) {
            $form_data = [
                'upload'   => input('file.upload'),
                'type'     => input('post.type'),
                'model'    => input('post.model'),
                'input_id' => input('post.input_id'),
            ];
            $result = validate($form_data, 'Upload', 'validate\common');
            if (true !== $result) {
                $this->error($result);
            }

            $upload = logic('Upload', 'logic\common');
            $result = $upload->fileOne($form_data);

            if (is_string($result)) {
                $this->error($result);
            } else {
                $return = upload_to_javasecipt($result);
            }
        } else {
            $return = $this->fetch('./upload');
        }

        return $return;
    }

    /**
     * 显示提示信息
     * @access protected
     * @param  bool|srting $_resutn_data 返回数据
     * @param  string      $_msg         提示信息
     * @param  string      $_url         跳转链接
     * @return void
     */
    protected function showMessage($_return_data, $_msg = '', $_url = null)
    {
        if (true === $_return_data) {
            $this->success($_msg, $_url);
        } elseif (false === $_return_data) {
            $this->error($_msg, $_url);
        } else {
            $this->error($_return_data);
        }
    }

    /**
     * 权限
     * @access private
     * @param
     * @return void
     */
    private function auth()
    {
        if (session('?' . config('user_auth_key'))) {
            $rbac = logic('Rbac', 'logic\account');
            // 审核用户权限
            if (!$rbac->checkAuth(session(config('user_auth_key')))) {
                $this->error('no permission', 'settings/info');
            }
            // 登录页重定向
            if ($this->requestParam['action'] == 'login') {
                $this->redirect(url('settings/info'));
            }
        } elseif ($this->requestParam['controller'] != 'account') {
            $this->redirect(url('account/login'));
        }
    }

    /**
     * 加载语言包
     * @access protected
     * @param
     * @return void
     */
    private function lang()
    {
        // 允许的语言
        Lang::setAllowLangList(config('lang_list'));

        $lang_path  = Env::get('app_path') . $this->request->module();
        $lang_path .= DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR;
        $lang_path .= Lang::detect() . DIRECTORY_SEPARATOR;

        // 加载全局语言包
        Lang::load($lang_path . Lang::detect() . '.php');

        // 加载对应语言包
        $lang_name  = $this->requestParam['controller'] . DIRECTORY_SEPARATOR;
        $lang_name .= $this->requestParam['action'];
        Lang::load($lang_path . $lang_name . '.php');
    }

    /**
     * 模版设置
     * @access protected
     * @param
     * @return void
     */
    private function theme()
    {
        $view_path  = Env::get('root_path') . basename($this->request->root());
        $view_path .= DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR;
        $view_path .= $this->request->module() . DIRECTORY_SEPARATOR;
        $view_path .= config('default_theme') . DIRECTORY_SEPARATOR;

        $template = config('template.');
        $template['view_path'] = $view_path;
        config('template.view_path', $view_path);
        $this->view->engine($template);

        // 默认跳转页面对应的模板文件
        $dispatch = [
            'dispatch_success_tmpl' => $view_path . 'dispatch_jump.html',
            'dispatch_error_tmpl'   => $view_path . 'dispatch_jump.html',
        ];
        config($dispatch, 'app');

        // 获得域名地址
        // $domain  = $this->request->domain();
        // $domain .= substr($this->request->baseFile(), 0, -9);
        $domain = $this->request->domain() . $this->request->root() . '/';
        $default_theme  = $domain . 'theme/' . $this->request->module();
        $default_theme .= '/'. config('default_theme') . '/';

        $replace = [
            '__DOMAIN__'   => $domain,
            '__PHP_SELF__' => basename($this->request->baseFile()),
            '__STATIC__'   => $domain . 'static/',
            '__THEME__'    => config('default_theme'),
            '__CSS__'      => $default_theme . 'static/css/',
            '__JS__'       => $default_theme . 'static/js/',
            '__IMG__'      => $default_theme . 'static/images/',
        ];

        // 注入常用模板变量
        $common = logic('Common', 'logic\common');
        $auth_data = $common->createSysData();

        $replace['__TITLE__'] = $auth_data['title'];
        if (!empty($auth_data['auth_menu'])) {
            $replace['__SUB_TITLE__']  = $auth_data['sub_title'];
            $replace['__BREADCRUMB__'] = $auth_data['breadcrumb'];

            $this->assign('__ADMIN_DATA__', $auth_data['admin_data']);

            $this->assign('auth_menu', json_encode($auth_data['auth_menu']));
        }

        config('app.view_replace_str', $replace);
        $this->view->replace($replace);

        $this->assign('request_param', json_encode($this->requestParam));

        $this->assign('button_search', false);
        $this->assign('button_added', false);
    }
}
