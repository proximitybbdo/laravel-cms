<?php 

namespace BBDO\Cms\Domain;

use BBDO\Cms\Models;

class File
{
    public function create($data){
      $result = Models\MyFile::create(array(
      'file'=>$data['file'],
      'type'=>$data['type'],
      'description'=>$data['description'],
      'editor_id'=>$data['editor_id'],
      ));

      $result->save();
      if($data['module'] != null) {
        $module = new Models\Module(array('file_id'=>$result->id,'module_type'=>$data['module']));
        $result->modules()->save($module);
      }
      return $result;
    }

    public function assign_module($data){
      $item = Models\MyFile::find($data['id']);
      $status = $data['status'];
      $module = $item->modules()->where('module_type',$data['module'])->first();
      if($status === "true" && $module == null){
        //array_push($modules,$data['module']);
        $module = new Models\Module(array('file_id'=>$item->id,'module_type'=>$data['module']));
        $item->modules()->save($module);
      }

      if($status === "false" && $module != null){
        $module->delete();
      }
    }

    public function garbage($data){
      $item = Models\MyFile::find($data['id']);

      $content_types = config::get('cms.files.'.$item->type.'.content_type');
      $itemService = new Item("");
      $item_content = $itemService->remove_content_search($content_types,$item->id); //item content delete
      $item->garbage = 1;   
      $item->save();

      return $item;
    }

    public function purge($data){
      $count = Models\MyFile::destroy($data['ids']);
      return $count;
    }

    public function destroy(){
      $item = Models\MyFile::find($id);
      $item->destroy();
    }

    public function get_all_admin($garbage = 0, $type, $module = null){
      $items = Models\MyFile::where('garbage',$garbage)->where('type',$type);

      if($module != null){
        $items->whereHas('modules', function($q)use($module)
        {
            $q->where('module_type', '=', $module);
        });
      }
      $items->with('modules');
      return $items->orderBy('id','DESC')->limit(30)->get();
    }

    public static function get_image_container($id,$type){
      $file = Models\MyFile::find($id);
      $result = '';
      try {
        $config = self::getTypeConfig($type);
        $path = 'uploads/image/';
        if($config != null && $config['generate_thumb']) {
          $path = $path . 'thumbs/';
        }        
        $path = $path . $file->file;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $result = '<img id="dynamic" src="data:image/'. $type . ';base64,' . base64_encode($data) . '" style="width:150px">';
      }catch(\Exception $ex){
      }      
      return $result;
    }

    /**
     * @param string $type
     * 
     * @return array
     */
    public static function getTypeConfig($type) 
    {
        $config = \Config::get('cms.image_types.' . $type);

        if ($config == null) {
            $config = \Config::get('cms.image_types.image_default');
        }

        return $config;
    }
  }