<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceCategoryFactory> */
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = ['name', 'icon', 'description'];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }
}
