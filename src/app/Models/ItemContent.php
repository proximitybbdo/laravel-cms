<?php 
namespace BBDO\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class ItemContent extends Model {

  protected $table = 'items_content';
  public $timestamps = true;
  protected $softDelete = false;

  protected $hidden = [];
  protected $fillable = ['item_id','version','lang','type','content'];

  public function item()
  {
    return $this->belongsTo('BBDO\Cms\Models\Item');
  }
}