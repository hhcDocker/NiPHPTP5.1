<?php
/**
 *
 * 数据安全过滤 - 服务层
 *
 * @package   NiPHP
 * @category  app\server
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2019
 */
namespace app\server;

use SessionHandlerInterface;
use think\facade\Config;
use app\model\Session as SessionModel;

class Session implements SessionHandlerInterface
{
    private $prefix;
    private $expire;

    /**
     * 构造方法
     * @access public
     * @param  Request $_request Request对象
     * @return void
     */
    public function __construct($_config = '')
    {
        $this->config = $_config ? $_config : Config::get('session.');

        $this->prefix = Config::get('session.prefix');
        $this->expire = Config::get('session.expire');
    }

    /**
     * 打开Session
     * @access public
     * @param  string    $_savePath
     * @param  mixed     $_sessName
     */
    public function open($_savePath, $_sessName): bool
    {
        return true;
    }

    /**
     * 关闭Session
     * @access public
     */
    public function close(): bool
    {
        $this->gc(ini_get('session.gc_maxlifetime'));
        return true;
    }

    /**
     * 读取Session
     * @access public
     * @param  string $_sessID
     */
    public function read($_sessID)
    {
        $map = [
            ['session_id', '=', $this->prefix . $_sessID]
        ];

        if ($this->expire != 0) {
            $map[] = ['update_time', '>=', time() - $this->expire];
        }
        $result =
        SessionModel::where($map)
        ->value('data');

        return $result ? $result : '';
    }

    /**
     * 写入Session
     * @access public
     * @param  string    $_sessID
     * @param  string    $_sessData
     * @return bool
     */
    public function write($_sessID, $_sessData): bool
    {
        $result =
        SessionModel::where([
            ['session_id', '=', $this->prefix . $_sessID]
        ])
        ->find()
        ->toArray();

        $data = [
            'session_id'  => $this->prefix . $_sessID,
            'data'        => $_sessData ? $_sessData : '',
            'update_time' => time()
        ];

        if (!empty($result)) {
            SessionModel::where([
                ['session_id', '=', $this->prefix . $_sessID],
            ])
            ->update($data);
            return !!SessionModel::getNumRows();
        } else {
            SessionModel::insert($data);
            return !!SessionModel::getNumRows();
        }
    }

    /**
     * 删除Session
     * @access public
     * @param  string $_sessID
     * @return bool
     */
    public function destroy($_sessID): bool
    {
        SessionModel::where([
            ['session_id', '=', $this->prefix . $_sessID]
        ])
        ->delete();
        return !!SessionModel::getNumRows();

    }

    /**
     * Session 垃圾回收
     * @access public
     * @param  string $sessMaxLifeTime
     * @return true
     */
    public function gc($_sessMaxLifeTime): bool
    {
        if ($this->expire != 0) {
            $map = [
                ['update_time', '<=', time() - $this->expire]
            ];
        } else {
            $_sessMaxLifeTime = $_sessMaxLifeTime ? $_sessMaxLifeTime : 86400;
            $map = [
                ['update_time', '<=', time() - $_sessMaxLifeTime]
            ];
        }

        SessionModel::where($map)
        ->delete();
        return !!SessionModel::getNumRows();
    }
}