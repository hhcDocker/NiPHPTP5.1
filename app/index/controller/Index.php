<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        // $s = new \app\common\model\Session;
        // $s->
        session("t", time());
        return '';

        return '<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script><script>$.ajax({
            url: "http://www.tp5.com/index/index/hello.html",
            type: "get",
            headers: {
                "accept": "application/vnd.tp5.v1.0.1+json",
                "authentication": "f0c4b4105d740747d44ac6dcd78624f906202706",
            },
            data: {
                method: "account.user.login"
            },
        });</script>';
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.2<br/><span style="font-size:30px">12载初心不改 - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    public function hello()
    {
        $a = new \app\common\server\Api;
        $a->app_name = 'admin';
        $a->run()->send();
    }
}
