<?php

namespace BBDOCms\Models;

use Illuminate\Database\Eloquent\Model;

class ItemBlockContent extends Model
{
    public $timestamps = true;
    protected $table = 'items_block_content';
    protected $softDelete = false;

    protected $hidden = [];
    protected $fillable = ['item_id', 'block_id', 'type', 'content'];

    public function item()
    {
        return $this->belongsTo('BBDOCms\Models\Item');
    }

    public function itemBlock()
    {
        return $this->belongsTo('BBDOCms\Models\ItemBlock', 'block_id');
    }
}
