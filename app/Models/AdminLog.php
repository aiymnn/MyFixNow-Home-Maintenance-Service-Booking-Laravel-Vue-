<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AdminLog extends Model
{
    use SoftDeletes, HasUuids;

    protected $fillable = ['admin_id', 'action', 'related_table', 'related_id'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
