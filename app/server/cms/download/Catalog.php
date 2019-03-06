<?php
/**
 *
 * API接口层
 * 下载列表
 *
 * @package   NiPHP
 * @category  app\server\cms\download
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @link      www.NiPHP.com
 * @since     2019
 */
declare (strict_types = 1);

namespace app\server\cms\download;

use think\facade\Cache;
use think\facade\Config;
use think\facade\Lang;
use think\facade\Request;
use app\library\Base64;
use app\model\Article as ModelArticle;
use app\model\ArticleData as ModelArticleData;
use app\model\TagsArticle as ModelTagsArticle;

class Catalog
{

    /**
     * 查询列表
     * @access public
     * @param
     * @return array
     */
    public function query()
    {
        $map = [
            ['article.is_pass', '=', '1'],
            ['article.show_time', '<=', time()],
            ['article.lang', '=', Lang::detect()]
        ];

        if ($category_id = Request::param('cid/f', null)) {
            $map[] = ['article.category_id', '=', $category_id];
        } else {
            return [
                'debug' => false,
                'cache' => false,
                'msg'   => Lang::get('param error'),
                'data'  => Request::param('', [], 'trim')
            ];
        }

        if ($com = Request::param('com/f', 0)) {
            $map[] = ['article.is_com', '=', '1'];
        } elseif ($top = Request::param('top/f', 0)) {
            $map[] = ['article.is_top', '=', '1'];
        } elseif ($hot = Request::param('hot/f', 0)) {
            $map[] = ['article.is_hot', '=', '1'];
        }

        if ($type_id = Request::param('tid/f', null)) {
            $map[] = ['article.type_id', '=', $type_id];
        }

        $cache_key = md5(count($map) . $category_id . $com . $top . $hot . $type_id);
        if (!Cache::has($cache_key)) {
            $result =
            ModelArticle::view('article article', ['id', 'category_id', 'title', 'thumb', 'url', 'keywords', 'description', 'access_id', 'update_time'])
            ->view('category category', ['name' => 'cat_name'], 'category.id=article.category_id')
            ->view('model model', ['name' => 'action_name'], 'model.id=category.model_id and model.id=1')
            ->view('level level', ['name' => 'level_name'], 'level.id=article.access_id', 'LEFT')
            ->view('type type', ['id' => 'type_id', 'name' => 'type_name'], 'type.id=article.type_id', 'LEFT')
            ->where($map)
            ->order('article.is_top DESC, article.is_hot DESC , article.is_com DESC, article.sort DESC, article.id DESC')
            ->paginate();
            $list = $result->toArray();
            $list['render'] = $result->render();

            Cache::tag('catalog')->set($cache_key, $list);
        } else {
            $list = Cache::get($cache_key);
        }

        foreach ($list['data'] as $key => $value) {
            $value['flag'] = Base64::flag($value['category_id'] . $value['id'], 7);
            $value['thumb'] = imgUrl($value['thumb']);
            $value['cat_url'] = url($value['action_name'] . '/' . $value['category_id']);
            $value['url'] = url($value['action_name'] . '/' . $value['category_id'] . '/' . $value['id']);


            // 附加字段数据
            $fields =
            ModelArticleData::view('article_data data', ['data'])
            ->view('fields fields', ['name' => 'fields_name'], 'fields.id=data.fields_id')
            ->where([
                ['data.main_id', '=', $value['id']],
            ])
            ->cache('modelarticledata' . $value['id'], null, 'CATALOG')
            ->select()
            ->toArray();
            foreach ($fields as $val) {
               $value[$val['fields_name']] = $val['data'];
            }


            // 标签
            $value['tags'] =
            ModelTagsArticle::view('tags_article article', ['tags_id'])
            ->view('tags tags', ['name'], 'tags.id=article.tags_id')
            ->where([
                ['article.article_id', '=', $value['id']],
            ])
            ->cache('modeltagsarticle' . $value['id'], null, 'CATALOG')
            ->select()
            ->toArray();

            $list['data'][$key] = $value;
        }

        return [
            'debug' => false,
            'msg'   => Lang::get('success'),
            'data'  => [
                'list'         => $list['data'],
                'total'        => $list['total'],
                'per_page'     => $list['per_page'],
                'current_page' => $list['current_page'],
                'last_page'    => $list['last_page'],
                'page'         => $list['render'],
            ]
        ];
    }
}
