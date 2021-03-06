<?php
/**
 *
 * 网站 - 控制器
 *
 * @package   NiPHP
 * @category  application\cms\controller
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\cms\controller;

class Index extends Base
{
    /**
     * 首页
     * @access public
     * @param
     * @return mixed
     */
    public function index()
    {
        print_r($_SERVER);die();
        return $this->fetch('index');
    }

    /**
     * 列表页
     * @access public
     * @param
     * @return mixed
     */
    public function entry()
    {
        $table_name = logic('cms/article')->queryTableName();
        if (!$table_name) {
            abort(404);
        }
        return $this->fetch('list_' . $table_name);
    }

    /**
     * 内容
     * @access public
     * @param
     * @return mixed
     */
    public function article()
    {
        $table_name = logic('cms/article')->queryTableName();
        if (!$table_name) {
            abort(404);
        }
        return $this->fetch($table_name);
    }

    /**
     * 频道页
     * @access public
     * @param
     * @return mixed
     */
    public function channel()
    {
        return $this->fetch('channel');
    }

    /**
     * 反馈
     * @access public
     * @param
     * @return mixed
     */
    public function feedback()
    {
        $this->assign('data', logic('cms/feedback')->queryInput());
        return $this->fetch('feedback');
    }

    /**
     * 留言
     * @access public
     * @param
     * @return mixed
     */
    public function message()
    {
        return $this->fetch('message');
    }

    /**
     * 评论
     * @access public
     * @param
     * @return mixed
     */
    public function comment()
    {
        return $this->fetch('comment');
    }

    /**
     * 标签
     * @access public
     * @param
     * @return mixed
     */
    public function tags()
    {
        logic('cms/tags')->article();
        return $this->fetch('tags');
    }

    /**
     * 搜索
     * @access public
     * @param
     * @return mixed
     */
    public function search()
    {
        logic('cms/Search')->query();
        return $this->fetch('search');
    }

    /**
     * 跳转
     * @access public
     * @param
     * @return mixed
     */
    public function go()
    {
        $table_name = logic('cms/article')->queryTableName();
        if (!$table_name) {
            abort(404);
        }

        // 外部模型
        elseif ($table_name == 'external') {
            $result =
            model('common/category')
            ->where([
                ['id', '=', input('param.cid/f')]
            ])
            ->cache(!APP_DEBUG ? __METHOD__ . input('param.cid/f') : false)
            ->value('url');
        }

        // 文章等模型
        else {
            $result =
            model('common/' . $table_name)
            ->where([
                ['id', '=', input('param.id/f')],
                ['category_id', '=', input('param.cid/f')]
            ])
            ->cache(!APP_DEBUG ? __METHOD__ . input('param.id/f') . input('param.cid/f') : false)
            ->value('url');
        }

        return redirect($result);
    }

    /**
     * 异常抛出
     * @access public
     * @param
     * @return void
     */
    public function abort()
    {
        abort(input('param.code/f', 404));
        return false;
    }

    public function caiji($_url = '/wapbook/326_260820.html', $_num = 0)
    {
        if ($_num >= 50) {
            die();
        }
        set_time_limit(0);


        $snoopy = new \Snoopy;
        $agent = array(
            'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1',
            'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.23 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.23 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.1.1; Nexus 6 Build/LYZ28E) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.23 Mobile Safari/537.36',

            'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.1+ (KHTML, like Gecko) Version/10.0.0.1337 Mobile Safari/537.1+',
            'Mozilla/5.0 (MeeGo; NokiaN9) AppleWebKit/534.13 (KHTML, like Gecko) NokiaBrowser/8.5.0 Mobile Safari/534.13',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0.187 Mobile Safari/534.11+',
            'Mozilla/5.0 (iPad; CPU OS 4_3_2 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8H7 Safari/6533.18.5',
            'Mozilla/5.0 (iPad; CPU OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3',
            'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25',
            'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_2 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8H7 Safari/6533.18.5',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25',
            'Mozilla/5.0 (Linux; Android 4.1.2; Nexus 7 Build/JZ054K) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Safari/535.19',
            'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19',
        );
        $key = array_rand($agent, 1);
        $snoopy->agent = $agent[$key];
        $snoopy->fetch('https://m.biquge.com.tw' . $_url);
        $result = $snoopy->results;
        $result = iconv('GB2312', 'UTF-8//IGNORE', $result);
        preg_match('/(<meta name="keywords" content=")(.*?)(")/si', $result, $matches);
        $title = explode(',', $matches[2]);
        $title = $title[1];

        preg_match('/(<div id="novelcontent" class="novelcontent">)(.*?)(<\/div>)/si', $result, $matches);
        $content = $matches[2];

        $content = preg_replace([
            '/<(ul.*?)>(.*?)<(\/script.*?)>/si',
            '/(&nbsp;)/si',
            '/(<br\/>){2}/si',
        ], ['', '', '</p><p>'], $content);
        $content = str_replace('<br/>', '', $content);

        $book_id = 2;

        $add_data = [
            'title'   => $title,
            'content' => $content,
            'book_id' => $book_id,
            'is_pass' => 1,
            'show_time' => time(),
        ];
        foreach ($add_data as $key => $value) {
            $value = trim($value);
            $value = safe_filter($value);
            $value = htmlspecialchars($value);

            $add_data[$key] = $value;
        }

        $has =
        model('common/BookArticle')
        ->field(['id'])
        ->where([
            ['title', '=', $add_data['title']],
            ['book_id', '=', $book_id]
        ])
        ->find();
        if (!$has) {
            model('common/BookArticle')
            ->added($add_data);
        }

        preg_match('/(<p class="p1 p3"><a href=")(.*?)(")/si', $result, $matches);
        file_put_contents('request.log', $matches[2] . "\r\n", FILE_APPEND);
        $this->caiji($matches[2], ++$_num);
    }
}
