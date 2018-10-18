<?php

namespace BBDO\Cms\Domain;

use Exception;
use BBDO\Cms\Exceptions\SlugNotFoundHttpException;
use BBDO\Cms\Models\SlugHistory as SlugHistoryModel;

class SlugHistory
{
    /**
     * @param $slug_content_item
     */
    public static function add($slug_content_item)
    {
        $slug_history_exists = SlugHistoryModel::where('item_id', $slug_content_item->item_id)
            ->where('lang',$slug_content_item->lang)
            ->where('slug',$slug_content_item->content)
        ;

        if ($slug_history_exists->count() == 0)
        {
            SlugHistoryModel::create([
                'item_id'   =>  $slug_content_item->item_id,
                'lang'      =>  $slug_content_item->lang,
                'slug'      =>  $slug_content_item->content
            ]);
        }
    }

    /**
     * @param string $search_slug
     * @param string $lang
     *
     * @return Item
     */
    public function get_item_by_slug($search_slug, $lang)
    {
        $result = SlugHistoryModel::where('lang', $lang)
            ->where('slug', $search_slug)
            ->first()
        ;

        if (is_null($result)) {
            throw new SlugNotFoundHttpException('Slug does not exist');
        }

        return $result->item;
    }
}