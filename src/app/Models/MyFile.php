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
          if($item->modules()->count() > 0){
            $item->modules()->delete();
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