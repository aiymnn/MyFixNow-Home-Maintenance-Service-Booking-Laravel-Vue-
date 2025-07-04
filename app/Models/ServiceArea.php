<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceArea extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceAreaFactory> */
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = ['name', 'state_id'];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_area_services', 'area_id', 'service_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
