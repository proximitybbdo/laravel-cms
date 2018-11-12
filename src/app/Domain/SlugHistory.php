<?php

namespace BBDO\Cms\Domain;

use BBDO\Cms\Models\SlugHistory as SlugHistoryModel;

class SlugHistory
{
    /**
     * @param $slug_content_item
     */
    public static function add($slug_content_item)
    {
        $slug_history_exists = SlugHistoryModel::where('item_id', $slug_content_item->item_id)
            ->where('lang', $slug_content_item->lang)
            ->where('slug', $slug_content_item->content);

        if ($slug_history_exists->count() == 0) {
            SlugHistoryModel::create([
                'item_id' => $slug_content_item->item_id,
                'lang' => $slug_content_item->lang,
                'slug' => $slug_content_item->content
            ]);
        }
    }

    /**
     * @param string $search_slug
     * @param string $lang
     *
     * @return Item
     */
    public function getItemBySlug($search_slug, $lang)
    {
        $result = SlugHistoryModel::where('lang', $lang)
            ->where('slug', $search_slug)
            ->firstOrFail();

        return $result->item;
    }
}
