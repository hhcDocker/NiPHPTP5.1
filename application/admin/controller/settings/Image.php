<?php
/**
 *
 * 图片设置 - 设置 - 控制器
 *
 * @package   NiPHPCMS
 * @category  admin\controller\settings
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: Image.php v1.0.1 $
 * @link      www.NiPHP.com
 * @since     2017/09/13
 */
namespace app\admin\controller\settings;

class Image
{

    /**
     * 编辑图片设置
     * @access public
     * @param
     * @return array
     */
    public function editor()
    {
        if (request()->isPost()) {
            $result = $this->update();
        } else {
            $basic = logic('Image', 'logic\settings');
            $result = $basic->getImageConfig();
        }

        return $result;
    }

    /**
     * 保存图片基础设置
     * @access private
     * @param
     * @return mixed
     */
    private function update()
    {
        $form_data = [
            'auto_image'             => input('post.auto_image/f'),
            'article_module_width'   => input('post.article_module_width/f'),
            'article_module_height'  => input('post.article_module_height/f'),
            'picture_module_width'   => input('post.picture_module_width/f'),
            'picture_module_height'  => input('post.picture_module_height/f'),
            'download_module_width'  => input('post.download_module_width/f'),
            'download_module_height' => input('post.download_module_height/f'),
            'page_module_width'      => input('post.page_module_width/f'),
            'page_module_height'     => input('post.page_module_height/f'),
            'product_module_width'   => input('post.product_module_width/f'),
            'product_module_height'  => input('post.product_module_height/f'),
            'job_module_width'       => input('post.job_module_width/f'),
            'job_module_height'      => input('post.job_module_height/f'),
            'link_module_width'      => input('post.link_module_width/f'),
            'link_module_height'     => input('post.link_module_height/f'),
            'ask_module_width'       => input('post.ask_module_width/f'),
            'ask_module_height'      => input('post.ask_module_height/f'),
            'add_water'              => input('post.add_water/f'),
            'water_type'             => input('post.water_type/f'),
            'water_location'         => input('post.water_location/f'),
            'water_text'             => input('post.water_text'),
            'water_image'            => input('post.water_image'),
            '__token__'              => input('post.__token__'),
        ];

        // 验证请求数据
        $result = validate($form_data, 'Image', 'validate\settings');
        if (true !== $result) {
            return $result;
        }

        unset($form_data['__token__']);

        $basic = logic('Image', 'logic\settings');
        return $basic->update($form_data);
    }
}