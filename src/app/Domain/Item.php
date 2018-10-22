<?php

namespace BBDO\Cms\Domain;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use BBDO\Cms\Helpers\Log;
use BBDO\Cms\Models;
use Cache;

class Item
{
  private $module = "";

  public function __construct($module_type = null)
  {
    $this->module = $module_type;
  }

  /**
   * @param $moduleType
   */
  public function setModule($moduleType)
  {
      $this->module = $moduleType;
  }

  public function create($data)
  {
    $item = Models\Item::create(array(
      'description'=>$data['description'],
      'status'=>$data['status'],
      'editor_id'=>$data['editor_id'],
      'sort'=>0,
      'start_date'=>$data['start_date'] != null ? Carbon::parse($data['start_date']) : null,
      'end_date'=>$data['end_date'] != null ? Carbon::parse($data['end_date']) : null,
      'type'=>$data['type'],
      'version'=>$data['version'],
      'module_type'=>$this->module,
      ));

    logAction($this->module, 'CREATE', $item->id);

    if($data['content'] != null) {
      $item->content()->saveMany($data['content']);
    }
    
    if(array_key_exists('block_content',$data) && $data['block_content'] != null) {
      foreach($data['block_content'] as $block){
        $block['item_id'] = $item->id;
        $block['lang'] = $block['lang'];
        $item_block = Models\ItemBlock::create(
          $block
        );
        if(array_key_exists('content',$block)) {
          $content_obj_arr = collect($block['content'])->map(function ($content, $key) use($item) {
              $content['item_id'] = $item->id;
              return new Models\ItemBlockContent($content);
          });
          $item_block->content()->saveMany($content_obj_arr);
        }
        if(array_key_exists('links',$block)) {
          $links = collect($block['links'])->transform(function($link,$key) use($item){
           $link['item_id'] = $item->id;
           $link['created_at'] = Carbon::now();
           return $link;
          })->all();
          $item_block->links()->sync($links);
        }
      }
    }

    if($data['links'] != null) {
      $item->links()->sync($data['links']);
    }

    Cache::flush();

    return $item;
  }

  public function update($lang, $data)
  {
    $item = Models\Item::find($data['id']);
    $item->description = $data['description'];
    //$item->status = $data['status'];
    $item->editor_id = $data['editor_id']; 
    $item->start_date = $data['start_date'] != null ? Carbon::parse($data['start_date']) : null;    
    $item->end_date = $data['end_date'] != null ? Carbon::parse($data['end_date']) : null;    
    $item->type = $data['type'];    
    $item->version = $data['version'];    

    $item->save();

    logAction($this->module, 'UPDATE', $data['id'], $lang);

    Models\ItemContent::destroy($item->contentLang($lang)->where('version',1)->pluck('id')->toArray());
    $item->content()->saveMany($data['content']);

    if(array_key_exists('block_content',$data) && $data['block_content'] != null) {
      //remove deleted blocks
      $remove_block_ids = $item->blocksLang($lang)->get()->reduce(function ($remove_block_ids,$block)use($data){
        if(!array_key_exists($block->type, $data['block_content'])){
          $remove_block_ids[] = $block->id;
        }
        return $remove_block_ids;
      });
      if(count($remove_block_ids) > 0) {
        Models\ItemBlock::destroy($remove_block_ids);
      }
      foreach($data['block_content'] as $block){
        $block['item_id'] = $item->id;
        $item_block = $item->block($lang,$block['type']);
        if($item_block == null) {
          $item_block = Models\ItemBlock::create($block);
        } else {
          $item_block->update($block);
        }
        if(array_key_exists('content',$block)) {
          Models\ItemBlockContent::destroy($item_block->content()->pluck('id')->toArray());
          $content_obj_arr = collect($block['content'])->map(function ($content, $key) use($item) {
              $content['item_id'] = $item->id;
              return new Models\ItemBlockContent($content);
          });
          $item_block->content()->saveMany($content_obj_arr);
        }
        if(array_key_exists('links',$block)) {
          $links = collect($block['links'])->transform(function($link,$key) use($item){
           $link['item_id'] = $item->id;
           $link['updated_at'] = Carbon::now();
           return $link;
          })->all();
          $item_block->links()->sync($links);
        }
      }
    }

    $item->links()->sync($data['links']);

    Cache::flush();

    return $item;
  }

  public function updateLinks($data){
    $item = Models\Item::find($data['id']);

    $item->links()->sync($data['links']);

    Cache::flush();

    return $item;
  }

  public function updateFeatured($id){
    Models\Item::where('is_featured',1)->update(['is_featured'=>0]);

    $item = Models\Item::find($id);
    $item->is_featured = 1;

    $item->save();

    Cache::flush();

    return $item;
  }

  /**
   * @param string $slug
   * @param string $lang
   * @param int $id
   * @param string $module_type
   *
   * @return int
   */
  public function countSlug($slug, $lang, $id = null, $module_type = null)
  {
    $count_slug = Models\Item::wherehas('content', function($query) use ($slug, $lang, $id, $module_type)
    {
      if ($id != null) {
        $query->where('item_id', '!=', $id);
      }

      if ($module_type != null) {
        $query->where('module_type', '=', $module_type);
      }

      // $query->where('version', '=', 0);
      $query->where('content', '=', $slug);
      $query->where('type', '=', 'slug');
      $query->where('lang', '=', $lang);
    })->count();

    return $count_slug;
  }

  public function destroy($id)
  {
    $item = Models\Item::find($id);
    $item->delete();

    Cache::flush();

    Log::action($this->module, 'DELETE', $id);
  }

  public function publishDraft($id, $lang)
  {
    $item = Models\Item::find($id);    
    Models\ItemContent::destroy($item->contentLang($lang)->where("version",0)->pluck('id')->toArray());
    $content = $item->contentLang($lang)->where("version",1)->update(array("version"=>0));
    
    //destory all online blocks
    Models\ItemBlock::destroy($item->blocksLang($lang,0)->pluck('id')->toArray());
    $blocks = $item->blocksLang($lang)->update(array("version"=>0));

    $slug_content = $item->contentLang($lang)->where("version",0)->where('type','slug')->first();
    if($slug_content != null){
      SlugHistory::add($slug_content);
    }

    $item = Models\Item::find($id);
    Cache::flush();

    logAction($this->module, 'PUBLISH', $id, $lang);

    return $item;
  }

  public function revert($id, $lang)
  {
    $item = Models\Item::find($id);
    Models\ItemContent::destroy($item->contentLang($lang)->where("version",1)->pluck('id')->toArray());
    $content = $item->contentLang($lang)->where("version",0)->update(array("version"=>1));
    $item = \Item::find($id);

    logAction($this->module, 'REVERT', $id, $lang);

    return $item;
  }

  public function copyLangContent($id, $source_lang, $destination_lang)
  {
    $item = Models\Item::find($id);
    $content = $item->contentLang($source_lang)->where("version",1)->get();
    if(count($content) == 0){
      $content = $item->contentLang($source_lang)->where("version",0)->get();
    }
    $content_arr = array();
    foreach($content as $value){
      $content_arr[] = new Models\ItemContent(array('version'=>1,'lang'=>$destination_lang,'type'=>$value->type,'content'=>$value->content));
    }
    $item->content()->saveMany($content_arr);

    $blocks = $item->blocks(1)->where('lang',$source_lang)->with('content')->get();
    if(count($blocks) == 0){
      $blocks = $item->blocks(0)->where('lang',$source_lang)->with('content')->get();
    }
    if(count($blocks) != 0){
      foreach($blocks as $block) {
        
        $block_arr = $block->toArray();
        $block_arr['id']=null;
        $block_arr['lang'] = $destination_lang;
        
        $new_block = new Models\ItemBlock($block_arr);
        $new_block->save();

        $content_arr = [];
        $content = $block->content()->get();
        foreach($content as $value){  
          $blockcontent_arr = $value->toArray();
          $blockcontent_arr['id']=null;
          $content_arr[] = new Models\ItemBlockContent($blockcontent_arr);
        }

        $new_block->content()->saveMany($content_arr);

        $links = $block->links()->get();
        $link_arr = [];
        foreach($links as $link){  
          $link_arr[] = [
            'item_id'=>$block->item_id,
            'link_id'=>$link->id,
            'link_type'=>$item->module_type,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
          ];
        }
        $new_block->links()->sync($link_arr);
        
      }
    }

    Cache::flush();

    logAction($this->module, 'COPYLANG', $id, $destination_lang);

    return $item;
  }

  public function publishItem($id, $status)
  {
    $item = Models\Item::find($id);
    $item->status = $status;
    $item->save();

    Cache::flush();

    logAction($this->module, 'PUBLISHITEM', $id);

    return $item;
  }

  /**
   * @param int $id
   * @param string $type
   *
   * @return Models\Item
   *
   * @throws Exception
   */
  public function featureItem($id, $type)
  {
    // get item from db
    $item = Models\Item::find($id);

    if (!$item) {
        throw new Exception('Item not found', 404);
    }

    if ($type == 'featured') {
        // check config to get max amount of featured items
        $maxFeaturedItems = \Config::get('cms.' . $item->module_type . '.max_featured_items');

        // get amount of featured items
        $numberOfFeaturedItems = Models\Item::where('type', 'featured')
            ->count()
        ;

        if ($maxFeaturedItems == $numberOfFeaturedItems) {
            throw new Exception('Max number of featured ' . $item->module_type . ' reached!', 400);
        }
    }

    // update item
    $item->type = $type;
    $item->save();

    // flush cache
    Cache::flush();

    return $item;
  }

  public function getSingleItem($cat) {
    $result = Models\Item::select('id')->where('module_type',$this->module);

    if($cat != null){
      $result->whereHas('links', function($q)use($cat)
      {
          $q->where('link_id', '=', $cat);

      });
    }
    return $result->first();
  }

  public function getAllAdmin($cat,$sort = 'sort',$desc = 'ASC') {
    $result = Models\Item::where('module_type',$this->module)
    ->orderBy($sort,$desc);

    if($cat != null){
      $result->whereHas('links', function($q)use($cat)
      {
          $q->where('link_id', '=', $cat);

      });
    }
    return $result->get();
  }

  public function getAllAdminList($module_type,$link_item_id, $link_type = '%') {
    $result = \DB::select('select i1.id, i1.description, i2.item_id
                        from items i1
                        left join items_link i2 on i2.link_id = i1.id and i2.item_id = ? and i2.link_type like ?
                        where i1.module_type = ? 
                        order by i1.description
                        ', array($link_item_id,$link_type,$module_type));

    return $result;
  }

  public function getAllAdminListBlockLinks($module_type,$link_block_id, $lang, $link_type = '%',$version = 1) {
    $result = \DB::select('select i1.id, i1.description, ibl.item_id
                        from items i1
                        left join items_block_links ibl on ibl.link_id = i1.id and ibl.block_id = ? and ibl.link_type like ? 
                        left join items_block i2 on i2.id = ibl.item_id and i2.version = ? and i2.lang = ?
                        where i1.module_type = ? 
                        order by i1.sort
                        ', array($link_block_id,$link_type,$version,$lang,$module_type));
    return $result;
  }

  public function getAllAdminListInception($module_type,$link_item_id, $link_type = '%') {
            
    $result = \DB::select('select i1.id, i1.description, i2.item_id
                        from items i1
                        inner join items_link i3 on i3.item_id = i1.id and i3.link_id = ?
                        left join items_link i2 on i2.link_id = i1.id and i2.item_id = ? and i2.link_type like ?
                        where i1.module_type = ? 
                        order by i1.sort
                        ', array($link_item_id,$link_item_id,$link_type,$module_type));

    return $result;
  }

  public function getAdmin($id,$lang) {
    $result = Models\Item::where('id',$id)
    ->with(array('content' => function($query) use (&$lang)
    {
    $query->where('lang', '=', $lang);
    }))
    ->with(array('blocks' => function($query) use (&$lang) {
      $query->where('lang','=',$lang)->with('content');
    }))->first();
    return $result;
  }

  public function getItemsLanguages() {
     $item_langs = \DB::table('items_content')
                    ->select('item_id','lang','version')
                    ->distinct('item_id','lang','version')
                    ->orderBy('item_id')
                    ->orderBy('version','Asc')->get();

     $result = array();
     foreach($item_langs as $item_lang) {
      $value = array();
      if(array_key_exists($item_lang->item_id, $result)) {
        $value = $result[$item_lang->item_id];
      }
      if(array_key_exists($item_lang->lang, $value)) {
        $langvalue = $value[$item_lang->lang];
        if($langvalue == 'online'){
          $langvalue = "edit";
        }        
      }
      else {
          $langvalue = $item_lang->version == 0 ? "online":"draft";
      }
      $value[$item_lang->lang] = $langvalue;
      $result[$item_lang->item_id] = $value;
     }

     return $result;
  }

  public function getItemLanguages($id) {
    $item_langs = \DB::table('items_content')
                    ->select('item_id','lang','version')
                    ->distinct('item_id','lang','version')
                    ->where('item_id',$id)
                    ->orderBy('version','Asc')->get();             
     $value = array();
     foreach($item_langs as $item_lang) {
      
      if(array_key_exists($item_lang->lang, $value)) {
        $langvalue = $value[$item_lang->lang];
        if($langvalue == 'online'){
          $langvalue = "edit";
        }        
      }
      else {
          $langvalue = $item_lang->version == 0 ? "online":"draft";
      }
      $value[$item_lang->lang] = $langvalue;
      
     }

     return $value;
  }

  public function sortItems($id, $to_index)
  {
    $items = $this->getAllAdmin(null);

    $i = 1;
    $keys = $items->modelKeys();
    $from_index = array_search($id, $items->modelKeys());

    $out = array_splice($keys, $from_index, 1);
    array_splice($keys, $to_index, 0, $out);

    $i = 1;
    foreach ($keys as $key) {
      $post = $items->find($key);
      $post->sort = $i;
      $post->save();
      $i++;
    }

    Cache::flush();

    logAction($this->module, 'SORT', $id);
  }

  public function sortItemsBlocks($item_id,$block_id,$to_index){
    $blocks = $this->blocks();
    //TODO

    $i = 1;
    $keys = $items->modelKeys();
    $from_index = array_search($id, $items->modelKeys());

    $out = array_splice($keys, $from_index, 1);
    array_splice($keys, $to_index, 0, $out);

    $i = 1;
    foreach ($keys as $key) {
      $post = $items->find($key);
      $post->sort = $i;
      $post->save();
      $i++;
    }

    Cache::flush();
  }

  public function removeContentSearch($content_types,$id){

    return Models\ItemContent::whereIn('type',$content_types)->where('content',$id)->delete();
    Cache::flush();

  }

  public function getContentsearchIds($content_types){

    return Models\ItemContent::select('content')->whereIn('type',$content_types)->distinct('content')->pluck('content');

  }

  public function rewriteIndexes() {
    \DB::update('SET @rownum := 0;update items set sort = (select @rownum:=@rownum+1) where module_type = ? order by items.sort',$this->module_type);
  }

  public function needsApproval(){
    return Models\ItemContent::select('item_id','version')->distinct('item_id','version')->where('version',0)->get()->count();
  } 

  public function getContentModules($lang = null) {
    $allcontent = array();
    $content_modules = \Config::get('cms.content_modules');
    foreach($content_modules as $module) {
      \DB::setFetchMode(\PDO::FETCH_ASSOC);     
      $result = \DB::table('items_content')
                    ->select('items_content.id','item_id','lang','type','content','items.module_type')
                    ->distinct('item_id','lang','version')
                    ->join('items','items.id','=','items_content.item_id')
                    //->where('items.status',1)
                    ->where('items.module_type',$module)
                    ->where('version',0)
                    ->where('lang',$lang)
                    ->orderBy('item_id')
                    ->orderBy('type')
                    ->get();
     \DB::setFetchMode(\PDO::FETCH_CLASS);   

      $allcontent[] = $result;
    }

    return $allcontent;
  }

    /**
     * @param string $module_type
     * @param array $ids
     *
     * @return Collection
     */
    public function getIds($module_type, array $ids)
    {
        $result = Models\Item::select('id', 'description', 'status', 'editor_id', 'module_type', 'sort', 'start_date', 'end_date', 'type')
            ->where('module_type', strtoupper($module_type))
            ->whereIn('id', $ids)
        ;

        return $result->get();
    }
 }