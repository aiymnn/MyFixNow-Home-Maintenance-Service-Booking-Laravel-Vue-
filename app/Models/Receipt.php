<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Receipt extends Model
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The data type of the primary key.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'payment_id',
        'pdf_path',
        'amount',
        'details',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'details' => 'array',
        'amount' => 'decimal:2',
    ];

    /**
     * Boot method to generate UUID automatically.
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the payment associated with the receipt.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get full URL for PDF path if stored in storage/app/public or S3.
     */
    protected function pdfUrl(): Attribute
    {
        return Attribute::get(fn() => asset('storage/' . $this->pdf_path));
    }
}
