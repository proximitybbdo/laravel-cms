<?php

namespace BBDO\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'files_modules';

    protected $hidden = [];
    protected $fillable = ['file_id', 'module_type'];
}
