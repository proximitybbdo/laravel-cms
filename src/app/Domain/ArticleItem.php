<?php

namespace BBDOCms\Domain;

class ArticleItem extends PublicItem
{
    public $module;
    private $lead = null;

    public function __construct($lead)
    {
        $this->module = 'ARTICLES';
        $this->lead = $lead;
        parent::__construct();
    }

    public function get_homecontent($exclude_ids)
    {
        $firstcall = count($exclude_ids) == 0;
        $featured = null;
        if ($firstcall) {
            $featured = $this->getOneFeatured($this->module);
            if ($featured) {
                $exclude_ids[] = $featured->id;
            }
        }

        $result = $this->getData($exclude_ids)->shuffle();

        if ($firstcall && $featured) {
            $result->prepend($featured);
        }

        return $result;
    }

    private function getData($exclude_ids, $filter = null)
    {
        $top_score = $this->lead->top_score();
        $levels = $this->getAll('LEVEL', null, null, null, null, null, false, false, null)->pluck('id');
        $categories = $this->getAll('CATEGORY', null, null, null, null, null, false, false, null)->pluck('id');
        $result = collect([]);
        $items = null;
        $ratio_percat = config('app.topscore_overview_ratio_percat');
        foreach (config('app.overview_scores') as $max => $parts) {
            if ($top_score->score < $max) {
                for ($i = 0; $i < count($levels); $i++) {
                    for ($j = 0; $j < count($categories); $j++) {
                        $ratio = $ratio_percat[1];
                        if ($top_score->category_id == $categories[$j]) {
                            $ratio = $ratio_percat[0];
                        }
                        $amount = intval($parts[$i] * $ratio);

                        $items = $this->getAll($this->module, 'LEVEL', [$levels[$i], $categories[$j]], 'start_date', null, $amount, false, true, $exclude_ids);
                        $result = $result->merge($items);
                    }
                }
                break;
            }
        }
        return $result;
    }

    public function get_morecontent($exclude_ids)
    {
        return $this->getMoreData($exclude_ids)->shuffle();
    }

    private function getMoreData($exclude_ids, $filter = null)
    {
        $items = null;
        $amount = array_sum(head(config('app.overview_scores')));
        $items = $this->getAll($this->module, 'LEVEL', [], 'id', null, $amount, false, false, $exclude_ids);

        return $items;
    }

    public function get_categorycontent($id, $exclude_ids)
    {
        $result = $this->getAll($this->module, 'CATEGORY', [$id], 'start_date', null, null, false, true, $exclude_ids);

        return $result->shuffle();
    }

    public function get_tagcontent($id, $exclude_ids)
    {
        $result = $this->getAll($this->module, 'TAG', [$id], 'start_date', null, null, false, true, $exclude_ids);

        return $result->shuffle();
    }

    public function get_relateditems($cat_id, $level_sort, $article_id)
    {
        $arr_items = [];
        $levels = $this->getAll('LEVEL', null, null, 'sort', null, null, true, false, null);

        foreach ($levels as $level) {
            $arr_items[$level->sort] = $this->getAll($this->module, 'LEVEL', [$level->id, $cat_id], 'start_date', null, 10, false, true, [$article_id]);
        }

        $result = $this->intertwin($arr_items, $level_sort);

        return $result->shuffle();
    }

    private function intertwin($arr_items, $level_item)
    {
        $result = collect();
        $rest = 0;
        foreach ($arr_items as $level => $items) {
            $take = 0;

            if (abs($level_item - $level) == 1) {
                $take = $rest + 1;
            } elseif ($level == $level_item) {
                $take = $rest + 2;
            }

            if ($items->count() < $take) {
                $take = $items->count();
            }
            $amount = $take;
            if ($take > 0) {
                if ($items->count() < $take) {
                    $amount = $items->count();
                }
                if ($amount > 0) {
                    if ($amount == 1) {
                        $result = $result->push($items->random(1));
                    } else {
                        $result = $result->merge($items->random($amount));
                    }
                }
            }
            $rest = $take - $amount;
            if ($result->count() == 3) {
                break;
            }
        }

        return $result;
    }

    public function get_moreitems($cat_id, $level_sort, $article_id)
    {
        $arr_items = [];
        $levels = $this->getAll('LEVEL', null, null, 'sort', null, null, true, false, null);

        foreach ($levels as $level) {
            $arr_items[$level->sort] = $this->getAll($this->module, 'LEVEL', [$level->id], 'start_date', null, 10, false, true, [$article_id]);
        }

        $result = $this->intertwin($arr_items, $level_sort);

        return $result->shuffle();
    }

    public function get_categoryitems($cat_id, $amount)
    {
        $result = $this->getAll($this->module, 'CATEGORY', [$cat_id], 'start_date', null, 10, false, true, []);

        return $result->random($amount);
    }
}
