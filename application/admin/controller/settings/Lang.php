<?php
/**
 *
 * 语言设置 - 设置 - 控制器
 *
 * @package   NiPHPCMS
 * @category  admin\controller\settings
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: Lang.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\admin\controller\settings;

use app\admin\logic\settings\Lang as LogicLang;

class Lang
{

    /**
     * 编辑语言设置
     * @access public
     * @param
     * @return array
     */
    public function editor()
    {
        if (request()->isPost()) {
            $result = $this->update();
        } else {
            $logic_lang = new LogicLang;
            $result = $logic_lang->getLangConfig();
        }

        return $result;
    }

    /**
     * 保存语言设置
     * @access private
     * @param
     * @return mixed
     */
    private function update()
    {
        $form_data = [
            'system'         => input('post.system'),
            'website'        => input('post.website'),
            'lang_switch_on' => input('post.lang_switch_on/f'),
            '__token__'      => input('post.__token__'),
        ];

        // 验证请求数据
        $result = validate($form_data, 'Lang', 'settings', 'admin');
        if (true !== $result) {
            return $result;
        }

        unset($form_data['__token__']);

        $logic_lang = new LogicLang;
        return $logic_lang->update($form_data);
    }
}
