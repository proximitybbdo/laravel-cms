<?php 
namespace BBDO\Cms\Models;

use Illuminate\Database\Eloquent\Model;

  class MyFile extends Model {

    protected $table = 'files';
    public $timestamps = true;
    protected $softDelete = false;

    protected $hidden = [];
    protected $fillable = ['file','type','description','editor_id'];

    public static function boot()
    {
        parent::boot();

        static::deleted(function($item)
        {
          if(count($item->modules())>0){
            ItemContent::destroy($item->modules()->pluck('id'));
          }
        });

    }

    public function modules()
    {
      return $this->hasMany('BBDO\Cms\Models\Module','file_id');
    }

    public function content()
    {
      return $this->hasMany('BBDO\Cms\Models\ItemContent');
    }

  }