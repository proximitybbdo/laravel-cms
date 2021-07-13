<?php

namespace BBDOCms\Models;

use Illuminate\Database\Eloquent\Model;

class ItemContent extends Model
{
    public $timestamps = true;
    protected $table = 'items_content';
    protected $softDelete = false;

    protected $hidden = [];
    protected $fillable = ['item_id', 'version', 'lang', 'type', 'content'];

    public function item()
    {
        return $this->belongsTo('BBDOCms\Models\Item');
    }
}
