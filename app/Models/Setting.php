<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Setting extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = ['key_name', 'value'];
}
