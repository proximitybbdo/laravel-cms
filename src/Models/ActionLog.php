<?php

namespace BBDO\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    protected $table = 'actionlog';

    protected $fillable = [
        'user_id',
        'item_id',
        'module',
        'action',
        'data',
        'lang',
        'ip'
    ];
}