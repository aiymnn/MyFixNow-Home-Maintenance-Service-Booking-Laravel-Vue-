<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceAreaService extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceAreaServiceFactory> */
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = ['service_id', 'area_id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function area()
    {
        return $this->belongsTo(ServiceArea::class, 'area_id');
    }
}
