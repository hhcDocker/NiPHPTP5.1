<?php
/**
 *
 * 控制层
 * Api
 *
 * @package   NiPHP
 * @category  app\controller
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2019
 */
declare (strict_types = 1);

namespace app\controller;

use app\library\Api as LibraryApi;

class Api extends LibraryApi
{

    /**
     * 查询接口
     * @access public
     * @param  string $name API分层名
     * @return void
     */
    public function query(string $name = 'cms')
    {
        $this->setModule($name)->run();
    }


    public function handle(string $name = 'cms')
    {
        # code...
    }

    /**
     * 上传接口
     * @access public
     * @param
     * @return void
     */
    public function upload()
    {

    }

    public function abort(int $code = 404)
    {
        throw new HttpException($code);
    }
}
