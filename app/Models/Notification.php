<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Notification extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = ['user_id', 'title', 'body', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
