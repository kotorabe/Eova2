<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Devis extends Model
{
    use SoftDeletes;

    protected $table = 'devis';
    protected $guarded = [];

    //
}
