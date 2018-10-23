<?php 
namespace BBDO\Cms\Models;

use Illuminate\Database\Eloquent\Model;

  class SlugHistory extends Model {

    protected $table = 'slug_history';
    public $timestamps = true;
    protected $softDelete = false;

    protected $hidden = [];
    protected $fillable = ['item_id','lang','slug'];

    public function item()
    {
      return $this->belongsTo('BBDO\Cms\Models\Item');
    }

  }