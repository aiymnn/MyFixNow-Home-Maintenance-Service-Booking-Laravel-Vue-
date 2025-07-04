<?php

namespace App\Models;

use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceImage extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceImageFactory> */
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = ['service_id', 'image_path', 'caption'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
