<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Equipe extends Model
{
    use HasApiTokens, SoftDeletes;
    protected $guarded = [];
}
