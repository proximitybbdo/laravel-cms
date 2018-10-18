<?php 
namespace BBDO\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class ItemBlockContent extends Model {

  protected $table = 'items_block_content';
  public $timestamps = true;
  protected $softDelete = false;

  protected $hidden = array();
  protected $fillable = array('item_id','block_id','type','content');

  public function item()
  {
    return $this->belongsTo('BBDO\Cms\Models\Item');
  }

  public function itemBlock()
  {
    return $this->belongsTo('BBDO\Cms\Models\ItemBlock','block_id');
  }
}