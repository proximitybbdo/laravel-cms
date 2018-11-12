<?php

namespace BBDO\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class SlugHistory extends Model
{
    public $timestamps = true;
    protected $table = 'slug_history';
    protected $softDelete = false;

    protected $hidden = [];
    protected $fillable = ['item_id', 'lang', 'slug'];

    public function item()
    {
        return $this->belongsTo('BBDO\Cms\Models\Item');
    }
}
