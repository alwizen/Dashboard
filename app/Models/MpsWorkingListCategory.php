<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpsWorkingListCategory extends Model
{
    //protected $table = 'mps_working_list_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
}
