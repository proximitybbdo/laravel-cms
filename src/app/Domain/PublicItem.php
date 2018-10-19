<?php

namespace BBDO\Cms\Domain;

use BBDO\Cms\Models;
use Auth;
use Carbon\Carbon;
use Cache;

class PublicItem {
  protected $lang = '';
  protected $preview = false;

  function __construct() {
    $this->lang = \LaravelLocalization::getCurrentLocale();

    if(\Input::get('preview') != null){
      if (Auth::check()) {
        $this->preview = true;
      }
    }
  }

  public function get_all($module_type,$link_type,$links,$sort,$pagesize = null, $amount = null, $desc = false, $mustApplyAllLinks = false, $exclude_ids = null) {
    $cache_key = 'item_get_all_' . trim($module_type) . '_' .
      $this->lang . '_' .
      ($link_type != null ? $link_type : '') . '_' .
      ($links != null ? implode('-',$links) : '') . '_' .
      ($sort != null ? $sort : '') . '_' .
      ($pagesize != null ? $pagesize : '') . '_' .
      ($amount != null ? $amount : '') . '_' .
      ($desc != null ? $desc : '') . '_' .
      ($mustApplyAllLinks != null ? $mustApplyAllLinks : '') . '_' .
      ($exclude_ids != null && count($exclude_ids)==1? $exclude_ids[0] : '');

    //dd($exclude_ids);

    $cache_disabled = false;
    if(($exclude_ids != null && count($exclude_ids)>1)|| $this->preview){
      $cache_disabled = true;
    }

    if(Cache::has($cache_key) && !$cache_disabled) {
      $result = Cache::get($cache_key);
    }
    else {

      if($sort == null) {
        $sort = 'id';
      }

      $order = $desc ? 'desc' : 'asc';
      $result = Models\Item::select('id','description','status','editor_id','module_type','sort','start_date','end_date','type')
      ->where('module_type',strtoupper($module_type))
      ->where('status',1)
      ->whereHas('content', function($q) {
        $q->where('version', '<=', $this->preview?1:0);
        $q->where('lang', '=', $this->lang);
      });

      if($sort != 'random') {
        $result->orderBy($sort,$order);
      }
      else {
        $result->orderByRaw("RAND()");
      }


      if($link_type != null && $links != null){
        if(!$mustApplyAllLinks) {
          $result->whereHas('links', function($q)use($links) {        
            $q->whereIn('link_id', $links);
          });
        }
        else {
          foreach($links as $link) {
            $result->whereHas('links', function($q)use($link) {        
              $q->where('link_id', $link);
            });
          }
        }        
      }
      if($exclude_ids != null && is_array($exclude_ids)) {
        $result->whereNotIn('id',$exclude_ids);
      }

      if($amount == null) {
        if($pagesize == null) {
          $result = $result->get();           
        }
        else {
          $result = $result->paginate($pagesize);
        }
      }
      else {
        $result = $result->limit($amount)->get();
      }

      if(!$cache_disabled){
        Cache::put($cache_key, $result,Carbon::now()->addDays(30));
      }
    }    
    
    return $result;      
  }

  public function get_ids($module_type,$ids) {
    $result = Models\Item::select('id','description','status','editor_id','module_type','sort','start_date','end_date','type')->where('module_type',strtoupper($module_type))
    ->whereHas('content', function($q) {
      $q->where('version', '=', 0);
      $q->where('lang', '=', $this->lang);
    })->where('status',1)
    ->whereIn('id',$ids);

    return $result->get();
  }

  public function get_one_slug($slug,$module_type) {
    $cache_key = 'item_' . $slug .'_mod_'. $module_type . '_lang' . $this->lang;
    if(Cache::has($cache_key) && !$this->preview) {
      $result = Cache::get($cache_key);
    }
    else {
      $result = Models\Item::select('id','description','status','editor_id','module_type','sort','created_at','start_date','end_date','type')
      ->where('module_type',strtoupper($module_type))
      ->whereHas('content', function($q) use ($slug) {
        $q->where('version', '<=', $this->preview?1:0);
        //$q->where('lang', '=', $this->lang);
        $q->where('type','slug');
        $q->where('content',$slug);
      });
      if(!$this->preview){
        $result->where('status',1);
      }

      $result = $result->first();
      if(!$this->preview){
        Cache::put($cache_key,$result,Carbon::now()->addDays(30));
      }
    }

    return $result;
  }

  public function get_one($id,$module_type) {
    $cache_key = 'item_' . $id . '_lang' . $this->lang;

    if(Cache::has($cache_key) && !$this->preview) {
      $result = Cache::get($cache_key);
    }
    else {
      $result = Models\Item::select('id','description','status','editor_id','module_type','sort','start_date','end_date','type')->where('id',$id)
      ->where('module_type',strtoupper($module_type))
      ->whereHas('content', function($q) {
        $q->where('version', '<=', $this->preview?1:0);
        $q->where('lang', '=', $this->lang);
      })->where('status',1)->first();
      if(!$this->preview){
      Cache::put($cache_key,$result,Carbon::now()->addDays(30));
      }
    }

    return $result;
  }

  public function get_one_featured($module_type) {
    $cache_key = 'item_featured_' . $module_type . '_lang_' . $this->lang;

    if(Cache::has($cache_key) && !$this->preview) {
      $result = Cache::get($cache_key);
    }
    else {
      $result = Models\Item::select('id','description','status','editor_id','module_type','sort','start_date','end_date','type')
      ->where('is_featured',1)
      ->where('module_type',strtoupper($module_type))
      ->whereHas('content', function($q) {
        $q->where('version', '<=', $this->preview ? 1:0);
        $q->where('lang', '=', $this->lang);
      })->where('status',1)->first();
      //dd($result);
      if(!$this->preview){
        Cache::put($cache_key,$result,Carbon::now()->addDays(30));
      }

    }

    return $result;
  }

  public function get_active_item($module_type) {
    $result = Models\Item::select('id','description','status','editor_id','module_type','sort','start_date','end_date','type')
                ->where('module_type',strtoupper($module_type))
                ->where('start_date','<=',Carbon::now())
                ->where('end_date','>',Carbon::now())
                ->whereHas('content', function($q) {
                    $q->where('version', '<=', $this->preview ? 1:0);
                    $q->where('lang', '=', $this->lang);
                  });

    if(!$this->preview){
      $result->where('status',1);
    }
    return $result->first();
  }

  // public function get_content_item($module_type) {
  //   $result = Models\Item::select('id','description','status','module_type','sort','date')->where('module_type',strtoupper($module_type))
  //   ->whereHas('content', function($q) {
  //     $q->where('version', '<=', $this->preview?1:0);
  //     $q->where('lang', '=', $this->lang);
  //   })
  //   ->with(array('content' => function($q) {
  //     $q->select('item_id','version','lang','type','content');
  //     $q->where('lang', '=', $this->lang);
  //     $q->where('version', '<=', $this->preview?1:0);
  //     //TODO cache
  //     //$q->rememberForever();
  //   }))->where('status',0);

  //   if(!$this->preview){
  //     //TODO cache
  //     //$result->rememberForever();
  //   }

  //   return $result->first();
  // }


/*  public function get_page_content($module_type,$property,$override_lang = null) {
    $version = 0;
    if($this->preview){
      $version = 1;
    }
    if($override_lang != null){
      $this->lang = $override_lang;
    }
    $result = Models\ItemContent::select('type','content')
    ->where('version','<=',$version)
    ->where('lang','=',$this->lang)
    ->whereHas('item', function($q) use($module_type) {
      $q->where('status', '=', 0);
      $q->where('module_type',$module_type);
    })
    ->orderBy('version','ASC');

    if($version == 0){
      //TODO cache
      //$result->rememberForever();
    }
    $contents = $result->get()->pluck('content','type');

    if(array_key_exists($property, $contents)){
      return $contents[$property];
    }
    else {
      return "";
    }
  }*/

  // public function get_searchcontent($module_type,$search, $arrContent) {
  //   $items = null;
  //   if($module_type != null) {

  //     $query = "select distinct c1.item_id";
  //     if(is_array($arrContent)){
  //       for($i = 0;$i<count($arrContent);$i++){
  //         $query .= ", c".($i+2).".content as ".$arrContent[$i];
  //       }
  //     }    
  //     $query .= " FROM items_content c1 join items i on c1.item_id = i.id";
  //     if(is_array($arrContent)){
  //       for($i = 0;$i<count($arrContent);$i++){
  //         $query .= " left join items_content c".($i+2)." on c".($i+2).".item_id = c1.item_id and c".($i+2).".version = c1.version and c".($i+2).".lang = c1.lang and c".($i+2).".type = '".$arrContent[$i]."'";
  //       }
  //     }                          
  //     $query .= " where MATCH(c1.content) AGAINST (? IN BOOLEAN MODE)
  //                 and c1.version = 0
  //                 and c1.lang = ?
  //                 and i.status = 1
  //                 and i.module_type = ?";
  //     $items = DB::connection('mysql')->select($query,array(trim($search).'*',$this->lang,$module_type));

  //   }

  //   return $items;
  // }

  // public function get_searchcontent_v2($module_type,$search, $arrContent) {
  //   $items = null;
  //   if($module_type != null) {

  //     $items = Models\Item::whereHas('content', function($q) use ($search) {
  //       $q->where('version', '=', 0);
  //       $q->where('lang', '=', $this->lang);
  //       $q->whereRaw("MATCH(content) AGAINST(?)", array($search));
  //     })
  //     ->where("status",1)
  //     ->where("module_type",$module_type)
  //     ->get();
  //   }

  //   return $items;
  // }



}