<?php

namespace BBDOCms\Models;

use Illuminate\Database\Eloquent\Model;

class MyFile extends Model
{
    public $timestamps = true;
    protected $table = 'files';
    protected $softDelete = false;

    protected $hidden = [];
    protected $fillable = ['file', 'type', 'description', 'editor_id'];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($item) {
            if ($item->modules()->count() > 0) {
                $item->modules()->delete();
            }
        });
    }

    public function modules()
    {
        return $this->hasMany('BBDOCms\Models\Module', 'file_id');
    }

    public function content()
    {
        return $this->hasMany('BBDOCms\Models\ItemContent');
    }
}
